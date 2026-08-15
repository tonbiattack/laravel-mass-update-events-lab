# Laravel Eloquent一括更新イベントデバッグラボ

Eloquentの `where()->update()` による一括更新では、対象モデルを取得しないためモデルの `updated` イベントが発火しない挙動を再現する。

| 項目 | 内容 |
|---|---|
| Laravel | 13.25.0 |
| PHP | 8.3.6 |
| DB | SQLite（テスト時はインメモリ） |
| 対象テスト | `php artisan test tests/Feature/MassUpdateEventsTest.php` |

初期状態では、一括更新後に検索インデックス相当の `updated` リスナーが実行されるべきテストが失敗する。修正後は、対象をモデルインスタンスとして取得して更新し、同じテストを回帰テストとして成功させる。
