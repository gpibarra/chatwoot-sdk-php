# Vendored Chatwoot swagger

`application_swagger.json` and `platform_swagger.json` are Chatwoot's swagger for the Application and Platform APIs, copied unmodified from the official repository at tag **`v4.19.1`**:

| File | Upstream (`v4.19.1`) | SHA-256 |
|---|---|---|
| [`application_swagger.json`](application_swagger.json) | [`swagger/tag_groups/application_swagger.json`](https://github.com/chatwoot/chatwoot/blob/v4.19.1/swagger/tag_groups/application_swagger.json) | `a06d30eb160984addc3cd85308f0b47023f2d401c7f266eedf640925756d8d3b` |
| [`platform_swagger.json`](platform_swagger.json) | [`swagger/tag_groups/platform_swagger.json`](https://github.com/chatwoot/chatwoot/blob/v4.19.1/swagger/tag_groups/platform_swagger.json) | `b8212305190dc96ee45f343ab7faa730e444eefc1cbf3711c55d82987b1d4c19` |

The SDK targets Chatwoot **v4.18.0**: request bodies and response shapes were checked against that version's Rails controllers and jbuilder views. The swagger comes from v4.19.1 because it documents endpoints that v4.18.0 already serves but its own swagger omits, such as `POST /platform/api/v1/users/{id}/token` and `POST /conversations/{id}/update_last_seen`. Where the swagger and the Rails app disagree, the SDK follows Rails; the README lists those cases under "Swagger vs. Rails".

They are reference material only and are excluded from the Composer dist archive.
