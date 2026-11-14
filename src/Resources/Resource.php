<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Resources;

use GPIbarra\ChatwootSdk\Support\ResponseData;
use Saloon\Http\BaseResource;
use Saloon\Http\Request;

/**
 * Shared helpers for every resource.
 */
abstract class Resource extends BaseResource
{
    /**
     * Sends the request and returns the decoded JSON body (an empty array for an empty body).
     *
     * @return array<array-key, mixed>
     */
    protected function sendForArray(Request $request): array
    {
        return ResponseData::decode($this->connector->send($request));
    }

    /**
     * Sends the request, discarding the body. Failures still throw.
     */
    protected function sendForNothing(Request $request): void
    {
        $this->connector->send($request);
    }
}
