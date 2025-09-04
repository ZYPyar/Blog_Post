<?php
// データベース接続情報
define('DB_HOST', 'localhost');
define('DB_NAME', 'blog_app'); // 作成したデータベース名
define('DB_USER', 'root');      // ご自身のMySQLユーザー名
define('DB_PASS', 'root');          // ご自身のMySQLパスワード

try {
    // PDO (PHP Data Objects) を使ってデータベースに接続
    $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASS);
    // エラー発生時に例外をスローするように設定
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // 接続エラーの場合はメッセージを表示して終了
    die("データベースの接続に失敗しました: " . $e->getMessage());
}
