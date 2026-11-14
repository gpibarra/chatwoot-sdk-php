<?php

declare(strict_types=1);

use GPIbarra\ChatwootSdk\ApplicationConnector;

/*
 * Endpoints that return decoded arrays (no DTO). Each row asserts the HTTP method, path, query string
 * and JSON body sent, and that the decoded response is handed back untouched.
 */

it('sends the expected request', function (callable $call, string $method, string $path, array $query, ?array $body): void {
    $response = ['ok' => true];
    $mock = mockClient($response);

    $result = $call(applicationApi($mock));

    assertSent($mock, $method, $path, $query, $body);

    if ($result !== null) {
        expect($result)->toBe($response);
    }
})->with([
    // Audit logs
    'audit logs: list' => [fn (ApplicationConnector $api) => $api->auditLogs(1)->list(2, ['types' => 'Inbox']), 'GET', '/api/v1/accounts/1/audit_logs', ['types' => 'Inbox', 'page' => 2], null],

    // Automation rules
    'automation rules: list' => [fn (ApplicationConnector $api) => $api->automationRules(1)->list(1), 'GET', '/api/v1/accounts/1/automation_rules', ['page' => 1], null],
    'automation rules: get' => [fn (ApplicationConnector $api) => $api->automationRules(1)->get(8), 'GET', '/api/v1/accounts/1/automation_rules/8', [], null],
    'automation rules: create' => [
        fn (ApplicationConnector $api) => $api->automationRules(1)->create('Tag VIP', 'conversation_created', [['attribute_key' => 'status', 'filter_operator' => 'equal_to', 'values' => ['open'], 'query_operator' => null]], [['action_name' => 'add_label', 'action_params' => ['vip']]], ['active' => true]),
        'POST', '/api/v1/accounts/1/automation_rules', [],
        ['active' => true, 'name' => 'Tag VIP', 'event_name' => 'conversation_created', 'conditions' => [['attribute_key' => 'status', 'filter_operator' => 'equal_to', 'values' => ['open'], 'query_operator' => null]], 'actions' => [['action_name' => 'add_label', 'action_params' => ['vip']]]],
    ],
    'automation rules: update' => [fn (ApplicationConnector $api) => $api->automationRules(1)->update(8, ['active' => false]), 'PATCH', '/api/v1/accounts/1/automation_rules/8', [], ['active' => false]],
    'automation rules: delete' => [fn (ApplicationConnector $api) => $api->automationRules(1)->delete(8), 'DELETE', '/api/v1/accounts/1/automation_rules/8', [], null],

    // Campaigns
    'campaigns: list' => [fn (ApplicationConnector $api) => $api->campaigns(1)->list(), 'GET', '/api/v1/accounts/1/campaigns', [], null],
    'campaigns: get' => [fn (ApplicationConnector $api) => $api->campaigns(1)->get(2), 'GET', '/api/v1/accounts/1/campaigns/2', [], null],
    'campaigns: create' => [fn (ApplicationConnector $api) => $api->campaigns(1)->create(['title' => 'Promo', 'inbox_id' => 7, 'message' => 'Hi']), 'POST', '/api/v1/accounts/1/campaigns', [], ['campaign' => ['title' => 'Promo', 'inbox_id' => 7, 'message' => 'Hi']]],
    'campaigns: update' => [fn (ApplicationConnector $api) => $api->campaigns(1)->update(2, ['enabled' => false]), 'PATCH', '/api/v1/accounts/1/campaigns/2', [], ['campaign' => ['enabled' => false]]],
    'campaigns: delete' => [fn (ApplicationConnector $api) => $api->campaigns(1)->delete(2), 'DELETE', '/api/v1/accounts/1/campaigns/2', [], null],

    // Canned responses
    'canned responses: list' => [fn (ApplicationConnector $api) => $api->cannedResponses(1)->list('greet'), 'GET', '/api/v1/accounts/1/canned_responses', ['search' => 'greet'], null],
    'canned responses: create' => [fn (ApplicationConnector $api) => $api->cannedResponses(1)->create('hi', 'Hello!'), 'POST', '/api/v1/accounts/1/canned_responses', [], ['canned_response' => ['short_code' => 'hi', 'content' => 'Hello!']]],
    'canned responses: update' => [fn (ApplicationConnector $api) => $api->cannedResponses(1)->update(3, ['content' => 'Hey']), 'PATCH', '/api/v1/accounts/1/canned_responses/3', [], ['canned_response' => ['content' => 'Hey']]],
    'canned responses: delete' => [fn (ApplicationConnector $api) => $api->cannedResponses(1)->delete(3), 'DELETE', '/api/v1/accounts/1/canned_responses/3', [], null],

    // CSAT
    'csat: list' => [fn (ApplicationConnector $api) => $api->csat(1)->list(['page' => 2, 'rating' => 5]), 'GET', '/api/v1/accounts/1/csat_survey_responses', ['page' => 2, 'rating' => 5], null],
    'csat: metrics' => [fn (ApplicationConnector $api) => $api->csat(1)->metrics(['since' => 1, 'until' => 2]), 'GET', '/api/v1/accounts/1/csat_survey_responses/metrics', ['since' => 1, 'until' => 2], null],

    // Custom attribute definitions
    'custom attributes: list' => [fn (ApplicationConnector $api) => $api->customAttributes(1)->list('contact_attribute'), 'GET', '/api/v1/accounts/1/custom_attribute_definitions', ['attribute_model' => 'contact_attribute'], null],
    'custom attributes: get' => [fn (ApplicationConnector $api) => $api->customAttributes(1)->get(6), 'GET', '/api/v1/accounts/1/custom_attribute_definitions/6', [], null],
    'custom attributes: create' => [
        fn (ApplicationConnector $api) => $api->customAttributes(1)->create('Plan', 'plan', 'list', 'contact_attribute', ['attribute_values' => ['gold', 'silver']]),
        'POST', '/api/v1/accounts/1/custom_attribute_definitions', [],
        ['custom_attribute_definition' => ['attribute_values' => ['gold', 'silver'], 'attribute_display_name' => 'Plan', 'attribute_key' => 'plan', 'attribute_display_type' => 'list', 'attribute_model' => 'contact_attribute']],
    ],
    'custom attributes: update' => [fn (ApplicationConnector $api) => $api->customAttributes(1)->update(6, ['attribute_description' => 'x']), 'PATCH', '/api/v1/accounts/1/custom_attribute_definitions/6', [], ['custom_attribute_definition' => ['attribute_description' => 'x']]],
    'custom attributes: delete' => [fn (ApplicationConnector $api) => $api->customAttributes(1)->delete(6), 'DELETE', '/api/v1/accounts/1/custom_attribute_definitions/6', [], null],

    // Custom filters
    'custom filters: list' => [fn (ApplicationConnector $api) => $api->customFilters(1)->list('contact'), 'GET', '/api/v1/accounts/1/custom_filters', ['filter_type' => 'contact'], null],
    'custom filters: get' => [fn (ApplicationConnector $api) => $api->customFilters(1)->get(4), 'GET', '/api/v1/accounts/1/custom_filters/4', [], null],
    'custom filters: create' => [fn (ApplicationConnector $api) => $api->customFilters(1)->create('Open VIP', 'conversation', ['payload' => []]), 'POST', '/api/v1/accounts/1/custom_filters', [], ['custom_filter' => ['name' => 'Open VIP', 'filter_type' => 'conversation', 'query' => ['payload' => []]]]],
    'custom filters: update' => [fn (ApplicationConnector $api) => $api->customFilters(1)->update(4, ['name' => 'x']), 'PATCH', '/api/v1/accounts/1/custom_filters/4', [], ['custom_filter' => ['name' => 'x']]],
    'custom filters: delete' => [fn (ApplicationConnector $api) => $api->customFilters(1)->delete(4), 'DELETE', '/api/v1/accounts/1/custom_filters/4', [], null],

    // Help center
    'help center: list portals' => [fn (ApplicationConnector $api) => $api->helpCenter(1)->listPortals(), 'GET', '/api/v1/accounts/1/portals', [], null],
    'help center: get portal' => [fn (ApplicationConnector $api) => $api->helpCenter(1)->getPortal('docs', 'en'), 'GET', '/api/v1/accounts/1/portals/docs', ['locale' => 'en'], null],
    'help center: create portal' => [fn (ApplicationConnector $api) => $api->helpCenter(1)->createPortal('Docs', 'docs', ['color' => '#000']), 'POST', '/api/v1/accounts/1/portals', [], ['portal' => ['color' => '#000', 'name' => 'Docs', 'slug' => 'docs']]],
    'help center: update portal' => [fn (ApplicationConnector $api) => $api->helpCenter(1)->updatePortal('docs', ['archived' => true]), 'PATCH', '/api/v1/accounts/1/portals/docs', [], ['portal' => ['archived' => true]]],
    'help center: delete portal' => [fn (ApplicationConnector $api) => $api->helpCenter(1)->deletePortal('docs'), 'DELETE', '/api/v1/accounts/1/portals/docs', [], null],
    'help center: encodes the slug' => [fn (ApplicationConnector $api) => $api->helpCenter(1)->getPortal('a b/c'), 'GET', '/api/v1/accounts/1/portals/a%20b%2Fc', [], null],
    'help center: list categories' => [fn (ApplicationConnector $api) => $api->helpCenter(1)->listCategories('docs', 'es'), 'GET', '/api/v1/accounts/1/portals/docs/categories', ['locale' => 'es'], null],
    'help center: get category' => [fn (ApplicationConnector $api) => $api->helpCenter(1)->getCategory('docs', 2), 'GET', '/api/v1/accounts/1/portals/docs/categories/2', [], null],
    'help center: create category' => [fn (ApplicationConnector $api) => $api->helpCenter(1)->createCategory('docs', 'Billing', 'en', ['slug' => 'billing']), 'POST', '/api/v1/accounts/1/portals/docs/categories', [], ['category' => ['slug' => 'billing', 'name' => 'Billing', 'locale' => 'en']]],
    'help center: update category' => [fn (ApplicationConnector $api) => $api->helpCenter(1)->updateCategory('docs', 2, ['position' => 1]), 'PATCH', '/api/v1/accounts/1/portals/docs/categories/2', [], ['category' => ['position' => 1]]],
    'help center: delete category' => [fn (ApplicationConnector $api) => $api->helpCenter(1)->deleteCategory('docs', 2), 'DELETE', '/api/v1/accounts/1/portals/docs/categories/2', [], null],
    'help center: list articles' => [fn (ApplicationConnector $api) => $api->helpCenter(1)->listArticles('docs', ['locale' => 'en', 'page' => 1]), 'GET', '/api/v1/accounts/1/portals/docs/articles', ['locale' => 'en', 'page' => 1], null],
    'help center: get article' => [fn (ApplicationConnector $api) => $api->helpCenter(1)->getArticle('docs', 5), 'GET', '/api/v1/accounts/1/portals/docs/articles/5', [], null],
    'help center: create article' => [fn (ApplicationConnector $api) => $api->helpCenter(1)->createArticle('docs', 'Refunds', 'How to...', ['category_id' => 2, 'author_id' => 5]), 'POST', '/api/v1/accounts/1/portals/docs/articles', [], ['article' => ['category_id' => 2, 'author_id' => 5, 'title' => 'Refunds', 'content' => 'How to...']]],
    'help center: update article' => [fn (ApplicationConnector $api) => $api->helpCenter(1)->updateArticle('docs', 5, ['status' => 'published']), 'PATCH', '/api/v1/accounts/1/portals/docs/articles/5', [], ['article' => ['status' => 'published']]],
    'help center: delete article' => [fn (ApplicationConnector $api) => $api->helpCenter(1)->deleteArticle('docs', 5), 'DELETE', '/api/v1/accounts/1/portals/docs/articles/5', [], null],

    // Integrations
    'integrations: apps' => [fn (ApplicationConnector $api) => $api->integrations(1)->apps(), 'GET', '/api/v1/accounts/1/integrations/apps', [], null],
    'integrations: create hook' => [fn (ApplicationConnector $api) => $api->integrations(1)->createHook('dialogflow', ['project_id' => 'p'], 7), 'POST', '/api/v1/accounts/1/integrations/hooks', [], ['hook' => ['app_id' => 'dialogflow', 'inbox_id' => 7, 'settings' => ['project_id' => 'p']]]],
    'integrations: update hook' => [fn (ApplicationConnector $api) => $api->integrations(1)->updateHook(9, ['status' => 'disabled']), 'PATCH', '/api/v1/accounts/1/integrations/hooks/9', [], ['hook' => ['status' => 'disabled']]],
    'integrations: delete hook' => [fn (ApplicationConnector $api) => $api->integrations(1)->deleteHook(9), 'DELETE', '/api/v1/accounts/1/integrations/hooks/9', [], null],

    // Reports
    'reports: reporting events' => [fn (ApplicationConnector $api) => $api->reports(1)->reportingEvents(['page' => 1, 'inbox_id' => 7]), 'GET', '/api/v1/accounts/1/reporting_events', ['page' => 1, 'inbox_id' => 7], null],
    'reports: metric' => [fn (ApplicationConnector $api) => $api->reports(1)->metric('conversations_count', 'inbox', 100, 200, ['id' => 7]), 'GET', '/api/v2/accounts/1/reports', ['id' => 7, 'metric' => 'conversations_count', 'type' => 'inbox', 'since' => 100, 'until' => 200], null],
    'reports: summary' => [fn (ApplicationConnector $api) => $api->reports(1)->summary('account', 100, 200), 'GET', '/api/v2/accounts/1/reports/summary', ['type' => 'account', 'since' => 100, 'until' => 200], null],
    'reports: account conversation metrics' => [fn (ApplicationConnector $api) => $api->reports(1)->conversationMetrics(), 'GET', '/api/v2/accounts/1/reports/conversations', ['type' => 'account'], null],
    'reports: agent conversation metrics' => [fn (ApplicationConnector $api) => $api->reports(1)->conversationMetrics('agent', 5), 'GET', '/api/v2/accounts/1/reports/conversations', ['type' => 'agent', 'user_id' => 5], null],
    'reports: channel summary' => [fn (ApplicationConnector $api) => $api->reports(1)->channelSummary(100, 200), 'GET', '/api/v2/accounts/1/summary_reports/channel', ['since' => 100, 'until' => 200], null],
    'reports: inbox summary' => [fn (ApplicationConnector $api) => $api->reports(1)->inboxSummary(100, 200, ['business_hours' => true]), 'GET', '/api/v2/accounts/1/summary_reports/inbox', ['business_hours' => true, 'since' => 100, 'until' => 200], null],
    'reports: agent summary' => [fn (ApplicationConnector $api) => $api->reports(1)->agentSummary(100, 200), 'GET', '/api/v2/accounts/1/summary_reports/agent', ['since' => 100, 'until' => 200], null],
    'reports: team summary' => [fn (ApplicationConnector $api) => $api->reports(1)->teamSummary(100, 200), 'GET', '/api/v2/accounts/1/summary_reports/team', ['since' => 100, 'until' => 200], null],
    'reports: first response time distribution' => [fn (ApplicationConnector $api) => $api->reports(1)->firstResponseTimeDistribution(100, 200), 'GET', '/api/v2/accounts/1/reports/first_response_time_distribution', ['since' => 100, 'until' => 200], null],
    'reports: inbox label matrix' => [fn (ApplicationConnector $api) => $api->reports(1)->inboxLabelMatrix(100, 200, ['inbox_ids' => [7]]), 'GET', '/api/v2/accounts/1/reports/inbox_label_matrix', ['inbox_ids' => [7], 'since' => 100, 'until' => 200], null],
    'reports: outgoing messages count' => [fn (ApplicationConnector $api) => $api->reports(1)->outgoingMessagesCount('agent', 100, 200), 'GET', '/api/v2/accounts/1/reports/outgoing_messages_count', ['group_by' => 'agent', 'since' => 100, 'until' => 200], null],
]);

it('downloads the CSAT report as CSV text', function (): void {
    $mock = mockClient("Agent,Rating\nAna,5\n");

    expect(applicationApi($mock)->csat(1)->download(['since' => 1]))->toBe("Agent,Rating\nAna,5\n");
    assertSent($mock, 'GET', '/api/v1/accounts/1/csat_survey_responses/download', ['since' => 1]);
});
