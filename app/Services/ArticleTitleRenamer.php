<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Article;

final class ArticleTitleRenamer
{
    public function rename(int $articleId, string $title): void
    {
        Article::query()
            ->findOrFail($articleId)
            ->update(['title' => $title]);
    }
}
