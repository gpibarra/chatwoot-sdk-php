<?php

declare(strict_types=1);

use GPIbarra\ChatwootSdk\ApplicationConnector;
use GPIbarra\ChatwootSdk\PlatformConnector;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\PendingRequest;
use Saloon\Repositories\Body\JsonBodyRepository;

const TEST_BASE_URL = 'http://chatwoot.test';

/**
 * A fixture from tests/Fixtures (each file documents where its shape comes from).
 *
 * @return array<string, mixed>
 */
function cwFixture(string $name): array
{
    /** @var array<string, mixed> $data */
    $data = require __DIR__.'/Fixtures/'.$name.'.php';

    return $data;
}

/**
 * @param  array<array-key, mixed>|string  $body
 */
function mockClient(array|string $body = [], int $status = 200): MockClient
{
    return new MockClient([MockResponse::make($body, $status, ['Content-Type' => 'application/json'])]);
}

function applicationApi(MockClient $mock): ApplicationConnector
{
    $api = new ApplicationConnector(TEST_BASE_URL.'/', 'test-token');
    $api->withMockClient($mock);

    return $api;
}

function platformApi(MockClient $mock): PlatformConnector
{
    $api = new PlatformConnector(TEST_BASE_URL, 'platform-token');
    $api->withMockClient($mock);

    return $api;
}

function lastRequest(MockClient $mock): PendingRequest
{
    $pending = $mock->getLastPendingRequest();

    if (! $pending instanceof PendingRequest) {
        throw new RuntimeException('No request was sent through the mock client.');
    }

    return $pending;
}

/**
 * The JSON body exactly as it goes on the wire, decoded; null when the request has no body.
 *
 * @return array<array-key, mixed>|null
 */
function sentJson(PendingRequest $pending): ?array
{
    $body = $pending->body();

    if (! $body instanceof JsonBodyRepository) {
        return null;
    }

    $decoded = json_decode((string) $body, true, flags: JSON_THROW_ON_ERROR);

    return is_array($decoded) ? $decoded : null;
}

/**
 * Asserts method, path, query string and JSON body of the last request.
 *
 * @param  array<string, mixed>  $query
 * @param  array<array-key, mixed>|null  $body  null asserts that no body was sent.
 */
function assertSent(MockClient $mock, string $method, string $path, array $query = [], ?array $body = null): void
{
    $pending = lastRequest($mock);

    expect($pending->getMethod()->value)->toBe($method)
        ->and($pending->getUrl())->toBe(TEST_BASE_URL.$path)
        ->and($pending->query()->all())->toBe($query)
        ->and(sentJson($pending))->toBe($body);
}

/**
 * Runs the callable and returns what it threw (or null).
 */
function thrownBy(callable $callable): ?Throwable
{
    try {
        $callable();
    } catch (Throwable $throwable) {
        return $throwable;
    }

    return null;
}
