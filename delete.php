<?php
require_once 'db_connect.php';

$id = $_GET['id'] ?? null;

if ($id) {
    // 外部キー制約 (ON DELETE CASCADE) を設定しているので、postsテーブルから削除するだけで
    // 関連するpost_tagsのレコードも自動的に削除される
    $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
    $stmt->execute([$id]);
}

// 処理後、一覧ページに戻る
header('Location: index.php');
exit;