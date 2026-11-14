<?php

declare(strict_types=1);

use GPIbarra\ChatwootSdk\ApplicationConnector;
use GPIbarra\ChatwootSdk\Exceptions\AuthenticationException;
use GPIbarra\ChatwootSdk\Exceptions\ChatwootException;
use GPIbarra\ChatwootSdk\Exceptions\NotFoundException;
use GPIbarra\ChatwootSdk\Exceptions\ValidationException;
use GPIbarra\ChatwootSdk\PlatformConnector;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('sends the access token in the api_access_token header and accepts JSON', function (): void {
    $mock = mockClient(cwFixture('user'));

    applicationApi($mock)->profile()->get();

    $headers = lastRequest($mock)->headers()->all();
    expect($headers['api_access_token'])->toBe('test-token')
        ->and($headers['Accept'])->toBe('application/json');
});

it('sends the platform app token in the api_access_token header', function (): void {
    $mock = mockClient([]);

    platformApi($mock)->accounts()->list();

    expect(lastRequest($mock)->headers()->all()['api_access_token'])->toBe('platform-token');
});

it('strips a trailing slash from the base URL', function (): void {
    expect((new ApplicationConnector('http://chatwoot:3000/', 't'))->resolveBaseUrl())->toBe('http://chatwoot:3000')
        ->and((new PlatformConnector('http://chatwoot:3000', 't'))->resolveBaseUrl())->toBe('http://chatwoot:3000');
});

it('maps Chatwoot error bodies to typed exceptions', function (int $status, array|string $body, string $class, array $errors): void {
    $mock = new MockClient([MockResponse::make($body, $status)]);

    $exception = thrownBy(fn () => applicationApi($mock)->inboxes(1)->get(99));

    expect($exception)->toBeInstanceOf(ChatwootException::class);
    assert($exception instanceof ChatwootException);
    expect($exception::class)->toBe($class)
        ->and($exception->getStatus())->toBe($status)
        ->and($exception->getErrors())->toBe($errors)
        ->and($exception->getMessage())->toContain("HTTP {$status}");
})->with([
    // render_not_found_error in app/controllers/concerns/request_exception_handler.rb
    'error string (404)' => [404, ['error' => 'Resource could not be found'], NotFoundException::class, ['Resource could not be found']],
    // Devise token auth failure
    'errors list (401)' => [401, ['errors' => ['You need to sign in or sign up before continuing.']], AuthenticationException::class, ['You need to sign in or sign up before continuing.']],
    // validate_token_api_access in Api::V1::AccountsController
    'api disabled (403)' => [403, ['error' => 'API access is not enabled for this account'], AuthenticationException::class, ['API access is not enabled for this account']],
    // render_record_invalid
    'record invalid (422)' => [422, ['message' => 'Name can\'t be blank', 'attributes' => ['name']], ValidationException::class, ['Name can\'t be blank']],
    // render json: { error: @custom_filter.errors.messages }
    'errors hash (422)' => [422, ['error' => ['name' => ['can\'t be blank']]], ValidationException::class, ['name can\'t be blank']],
    'non-JSON body (500)' => [500, 'Internal Server Error', ChatwootException::class, []],
]);

it('exposes validation attributes and the decoded body', function (): void {
    $mock = new MockClient([MockResponse::make(['message' => 'Url has already been taken', 'attributes' => ['url']], 422)]);

    $exception = thrownBy(fn () => applicationApi($mock)->webhooks(1)->create('https://x.test', ['message_created']));

    expect($exception)->toBeInstanceOf(ValidationException::class);
    assert($exception instanceof ValidationException);
    expect($exception->getAttributes())->toBe(['url'])
        ->and($exception->getBody())->toBe(['message' => 'Url has already been taken', 'attributes' => ['url']])
        ->and($exception->getResponse()->status())->toBe(422);
});
