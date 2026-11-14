<?php

declare(strict_types=1);

namespace GPIbarra\ChatwootSdk\Resources\Application;

use GPIbarra\ChatwootSdk\Requests\Application\HelpCenter\CreateArticleRequest;
use GPIbarra\ChatwootSdk\Requests\Application\HelpCenter\CreateCategoryRequest;
use GPIbarra\ChatwootSdk\Requests\Application\HelpCenter\CreatePortalRequest;
use GPIbarra\ChatwootSdk\Requests\Application\HelpCenter\DeleteArticleRequest;
use GPIbarra\ChatwootSdk\Requests\Application\HelpCenter\DeleteCategoryRequest;
use GPIbarra\ChatwootSdk\Requests\Application\HelpCenter\DeletePortalRequest;
use GPIbarra\ChatwootSdk\Requests\Application\HelpCenter\GetArticleRequest;
use GPIbarra\ChatwootSdk\Requests\Application\HelpCenter\GetCategoryRequest;
use GPIbarra\ChatwootSdk\Requests\Application\HelpCenter\GetPortalRequest;
use GPIbarra\ChatwootSdk\Requests\Application\HelpCenter\ListArticlesRequest;
use GPIbarra\ChatwootSdk\Requests\Application\HelpCenter\ListCategoriesRequest;
use GPIbarra\ChatwootSdk\Requests\Application\HelpCenter\ListPortalsRequest;
use GPIbarra\ChatwootSdk\Requests\Application\HelpCenter\UpdateArticleRequest;
use GPIbarra\ChatwootSdk\Requests\Application\HelpCenter\UpdateCategoryRequest;
use GPIbarra\ChatwootSdk\Requests\Application\HelpCenter\UpdatePortalRequest;
use GPIbarra\ChatwootSdk\Resources\AccountScopedResource;
use GPIbarra\ChatwootSdk\Support\Payload;

/**
 * Help center portals, categories and articles. Portals are addressed by slug. Bodies are sent under
 * the `portal`, `category` and `article` root keys the controllers require.
 */
final class HelpCenterResource extends AccountScopedResource
{
    /**
     * @return array<array-key, mixed>
     */
    public function listPortals(): array
    {
        return $this->sendForArray(new ListPortalsRequest($this->accountId));
    }

    /**
     * @return array<array-key, mixed>
     */
    public function getPortal(string $portalSlug, ?string $locale = null): array
    {
        return $this->sendForArray(new GetPortalRequest($this->accountId, $portalSlug, Payload::filter(['locale' => $locale])));
    }

    /**
     * @param  array<string, mixed>  $extra  `color`, `custom_domain`, `header_text`, `homepage_link`, `page_title`, `config`, ...
     * @return array<array-key, mixed>
     */
    public function createPortal(string $name, string $slug, array $extra = []): array
    {
        return $this->sendForArray(new CreatePortalRequest($this->accountId, [
            'portal' => Payload::merge(['name' => $name, 'slug' => $slug], $extra),
        ]));
    }

    /**
     * @param  array<string, mixed>  $attributes
     * @return array<array-key, mixed>
     */
    public function updatePortal(string $portalSlug, array $attributes): array
    {
        return $this->sendForArray(new UpdatePortalRequest($this->accountId, $portalSlug, ['portal' => $attributes]));
    }

    public function deletePortal(string $portalSlug): void
    {
        $this->sendForNothing(new DeletePortalRequest($this->accountId, $portalSlug));
    }

    /**
     * @return array<array-key, mixed>
     */
    public function listCategories(string $portalSlug, ?string $locale = null): array
    {
        return $this->sendForArray(new ListCategoriesRequest($this->accountId, $portalSlug, Payload::filter(['locale' => $locale])));
    }

    /**
     * @return array<array-key, mixed>
     */
    public function getCategory(string $portalSlug, int $categoryId): array
    {
        return $this->sendForArray(new GetCategoryRequest($this->accountId, $portalSlug, $categoryId));
    }

    /**
     * @param  array<string, mixed>  $extra  `slug`, `description`, `position`, `icon`, `parent_category_id`, `associated_category_id`.
     * @return array<array-key, mixed>
     */
    public function createCategory(string $portalSlug, string $name, string $locale, array $extra = []): array
    {
        return $this->sendForArray(new CreateCategoryRequest($this->accountId, $portalSlug, [
            'category' => Payload::merge(['name' => $name, 'locale' => $locale], $extra),
        ]));
    }

    /**
     * @param  array<string, mixed>  $attributes
     * @return array<array-key, mixed>
     */
    public function updateCategory(string $portalSlug, int $categoryId, array $attributes): array
    {
        return $this->sendForArray(new UpdateCategoryRequest($this->accountId, $portalSlug, $categoryId, ['category' => $attributes]));
    }

    public function deleteCategory(string $portalSlug, int $categoryId): void
    {
        $this->sendForNothing(new DeleteCategoryRequest($this->accountId, $portalSlug, $categoryId));
    }

    /**
     * @param  array<string, mixed>  $query  `locale`, `query`, `page`, `category_slug`, `status`, `author_id`.
     * @return array<array-key, mixed>
     */
    public function listArticles(string $portalSlug, array $query = []): array
    {
        return $this->sendForArray(new ListArticlesRequest($this->accountId, $portalSlug, $query));
    }

    /**
     * @return array<array-key, mixed>
     */
    public function getArticle(string $portalSlug, int $articleId): array
    {
        return $this->sendForArray(new GetArticleRequest($this->accountId, $portalSlug, $articleId));
    }

    /**
     * @param  array<string, mixed>  $extra  `slug`, `description`, `category_id`, `author_id`, `status`, `locale`, `position`, `meta`, ...
     * @return array<array-key, mixed>
     */
    public function createArticle(string $portalSlug, string $title, string $content, array $extra = []): array
    {
        return $this->sendForArray(new CreateArticleRequest($this->accountId, $portalSlug, [
            'article' => Payload::merge(['title' => $title, 'content' => $content], $extra),
        ]));
    }

    /**
     * @param  array<string, mixed>  $attributes
     * @return array<array-key, mixed>
     */
    public function updateArticle(string $portalSlug, int $articleId, array $attributes): array
    {
        return $this->sendForArray(new UpdateArticleRequest($this->accountId, $portalSlug, $articleId, ['article' => $attributes]));
    }

    public function deleteArticle(string $portalSlug, int $articleId): void
    {
        $this->sendForNothing(new DeleteArticleRequest($this->accountId, $portalSlug, $articleId));
    }
}
