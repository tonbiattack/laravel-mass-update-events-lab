<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Article;
use App\Services\ArticleTitleRenamer;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Schema\Blueprint;
use Tests\TestCase;

final class MassUpdateEventsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $schema = app('db')->connection()->getSchemaBuilder();
        $schema->dropIfExists('articles');
        $schema->create('articles', static function (Blueprint $table): void {
            $table->id();
            $table->string('title');
        });
    }

    public function test_instance_update_dispatches_updated_event(): void
    {
        $article = Article::query()->create(['title' => 'before']);
        $updatedTitles = [];

        Article::updated(static function (Article $updated) use (&$updatedTitles): void {
            $updatedTitles[] = $updated->title;
        });

        $article->update(['title' => 'after']);

        self::assertSame(['after'], $updatedTitles);
    }

    public function test_mass_update_dispatches_updated_event_for_search_indexing(): void
    {
        $article = Article::query()->create(['title' => 'before']);
        $updatedTitles = [];

        Article::updated(static function (Article $updated) use (&$updatedTitles): void {
            $updatedTitles[] = $updated->title;
        });

        app(ArticleTitleRenamer::class)->rename($article->id, 'after');

        self::assertSame(
            ['after'],
            $updatedTitles,
            '検索インデックス更新を担うupdatedイベントが一括更新でも発火すること'
        );
    }
}
