<?php

declare(strict_types=1);

use GPIbarra\ChatwootSdk\Data\Agent;
use GPIbarra\ChatwootSdk\Data\Label;
use GPIbarra\ChatwootSdk\Data\Team;

it('lists labels from the payload envelope', function (): void {
    $mock = mockClient(['payload' => [cwFixture('label')]]);

    $labels = applicationApi($mock)->labels(1)->list();

    assertSent($mock, 'GET', '/api/v1/accounts/1/labels');
    expect($labels[0])->toBeInstanceOf(Label::class)
        ->and($labels[0]->id)->toBe(9)
        ->and($labels[0]->title)->toBe('vip')
        ->and($labels[0]->description)->toBe('Priority customers')
        ->and($labels[0]->color)->toBe('#1f93ff')
        ->and($labels[0]->showOnSidebar)->toBeTrue();
});

it('gets a label', function (): void {
    $mock = mockClient(cwFixture('label'));

    expect(applicationApi($mock)->labels(1)->get(9)->title)->toBe('vip');
    assertSent($mock, 'GET', '/api/v1/accounts/1/labels/9');
});

it('creates a label under the label root key', function (): void {
    $mock = mockClient(cwFixture('label'));

    applicationApi($mock)->labels(1)->create('vip', 'Priority customers', '#1f93ff', true);

    assertSent($mock, 'POST', '/api/v1/accounts/1/labels', body: ['label' => [
        'title' => 'vip',
        'description' => 'Priority customers',
        'color' => '#1f93ff',
        'show_on_sidebar' => true,
    ]]);
});

it('updates a label', function (): void {
    $mock = mockClient(cwFixture('label'));

    applicationApi($mock)->labels(1)->update(9, ['color' => '#000000']);

    assertSent($mock, 'PATCH', '/api/v1/accounts/1/labels/9', body: ['label' => ['color' => '#000000']]);
});

it('deletes a label', function (): void {
    $mock = mockClient('');

    applicationApi($mock)->labels(1)->delete(9);

    assertSent($mock, 'DELETE', '/api/v1/accounts/1/labels/9');
});

it('lists teams', function (): void {
    $mock = mockClient([cwFixture('team')]);

    $teams = applicationApi($mock)->teams(1)->list();

    assertSent($mock, 'GET', '/api/v1/accounts/1/teams');
    expect($teams[0])->toBeInstanceOf(Team::class)
        ->and($teams[0]->id)->toBe(3)
        ->and($teams[0]->name)->toBe('support')
        ->and($teams[0]->description)->toBe('First line')
        ->and($teams[0]->allowAutoAssign)->toBeTrue()
        ->and($teams[0]->accountId)->toBe(1)
        ->and($teams[0]->isMember)->toBeFalse();
});

it('gets a team', function (): void {
    $mock = mockClient(cwFixture('team'));

    expect(applicationApi($mock)->teams(1)->get(3)->id)->toBe(3);
    assertSent($mock, 'GET', '/api/v1/accounts/1/teams/3');
});

it('creates a team under the team root key', function (): void {
    $mock = mockClient(cwFixture('team'));

    applicationApi($mock)->teams(1)->create('support', 'First line', true);

    assertSent($mock, 'POST', '/api/v1/accounts/1/teams', body: ['team' => [
        'name' => 'support',
        'description' => 'First line',
        'allow_auto_assign' => true,
    ]]);
});

it('updates a team', function (): void {
    $mock = mockClient(cwFixture('team'));

    applicationApi($mock)->teams(1)->update(3, ['name' => 'tier-1']);

    assertSent($mock, 'PATCH', '/api/v1/accounts/1/teams/3', body: ['team' => ['name' => 'tier-1']]);
});

it('deletes a team', function (): void {
    $mock = mockClient('');

    applicationApi($mock)->teams(1)->delete(3);

    assertSent($mock, 'DELETE', '/api/v1/accounts/1/teams/3');
});

it('lists team members', function (): void {
    $mock = mockClient([cwFixture('agent')]);

    $members = applicationApi($mock)->teams(1)->members(3);

    assertSent($mock, 'GET', '/api/v1/accounts/1/teams/3/team_members');
    expect($members[0])->toBeInstanceOf(Agent::class);
});

it('adds team members', function (): void {
    $mock = mockClient([cwFixture('agent')]);

    applicationApi($mock)->teams(1)->addMembers(3, [5]);

    assertSent($mock, 'POST', '/api/v1/accounts/1/teams/3/team_members', body: ['user_ids' => [5]]);
});

it('replaces team members', function (): void {
    $mock = mockClient([cwFixture('agent')]);

    applicationApi($mock)->teams(1)->updateMembers(3, [5]);

    assertSent($mock, 'PATCH', '/api/v1/accounts/1/teams/3/team_members', body: ['user_ids' => [5]]);
});

it('removes team members', function (): void {
    $mock = mockClient('');

    applicationApi($mock)->teams(1)->removeMembers(3, [5]);

    assertSent($mock, 'DELETE', '/api/v1/accounts/1/teams/3/team_members', body: ['user_ids' => [5]]);
});
