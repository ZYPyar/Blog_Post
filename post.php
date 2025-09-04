<?php
require_once 'db_connect.php';

// フォームが送信された場合の処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $tag_ids = isset($_POST['tags']) ? $_POST['tags'] : [];

    // バリデーション
    if (empty($title) || empty($content)) {
        $error = "Title and content are required.";
    } else {
        try {
            // トランザクション開始
            $pdo->beginTransaction();

            // 1. postsテーブルに記事を挿入
            $stmt = $pdo->prepare("INSERT INTO posts (title, content) VALUES (?, ?)");
            $stmt->execute([$title, $content]);
            
            // 2. 最後に挿入された記事のIDを取得
            $post_id = $pdo->lastInsertId();

            // 3. post_tagsテーブルにタグ情報を挿入
            if (!empty($tag_ids)) {
                $stmt = $pdo->prepare("INSERT INTO post_tags (post_id, tag_id) VALUES (?, ?)");
                foreach ($tag_ids as $tag_id) {
                    $stmt->execute([$post_id, $tag_id]);
                }
            }
            
            // コミット
            $pdo->commit();

            // 一覧ページにリダイレクト
            header('Location: index.php');
            exit;

        } catch (Exception $e) {
            // エラーが発生した場合はロールバック
            $pdo->rollBack();
            $error = "Failed to post: " . $e->getMessage();
        }
    }
}

// 登録されているタグを取得
$tags_stmt = $pdo->query("SELECT * FROM tags ORDER BY name");
$all_tags = $tags_stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Create New Post</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Create New Post</h1>

        <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php endif; ?>

        <form action="post.php" method="post">
            <div>
                <label for="title">Title</label>
                <input type="text" id="title" name="title" required>
            </div>
            <div>
                <label for="content">Content</label>
                <textarea id="content" name="content" rows="10" required></textarea>
            </div>
            <div>
                <label>Tags</label>
                <div class="tag-group">
                    <?php foreach ($all_tags as $tag): ?>
                        <label>
                            <input type="checkbox" name="tags[]" value="<?php echo $tag['id']; ?>">
                            <?php echo htmlspecialchars($tag['name'], ENT_QUOTES, 'UTF-8'); ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
            <button type="submit" class="btn">Post</button>
        </form>
        <p><a href="index.php">Back to list</a></p>
    </div>
</body>
</html>