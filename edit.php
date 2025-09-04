<?php
require_once 'db_connect.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: index.php');
    exit;
}

// フォーム送信時の処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $tag_ids = isset($_POST['tags']) ? $_POST['tags'] : [];

    try {
        $pdo->beginTransaction();

        // 1. postsテーブルを更新
        $stmt = $pdo->prepare("UPDATE posts SET title = ?, content = ? WHERE id = ?");
        $stmt->execute([$title, $content, $id]);

        // 2. 既存のタグ関連を一旦削除
        $stmt = $pdo->prepare("DELETE FROM post_tags WHERE post_id = ?");
        $stmt->execute([$id]);

        // 3. 新しいタグ関連を挿入
        if (!empty($tag_ids)) {
            $stmt = $pdo->prepare("INSERT INTO post_tags (post_id, tag_id) VALUES (?, ?)");
            foreach ($tag_ids as $tag_id) {
                $stmt->execute([$id, $tag_id]);
            }
        }
        
        $pdo->commit();
        header('Location: index.php');
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        $error = "Failed to update: " . $e->getMessage();
    }
}

// 編集対象の記事データを取得
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->execute([$id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    header('Location: index.php');
    exit;
}

// 記事に紐づくタグIDを取得
$stmt = $pdo->prepare("SELECT tag_id FROM post_tags WHERE post_id = ?");
$stmt->execute([$id]);
$post_tag_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);

// 全てのタグを取得
$tags_stmt = $pdo->query("SELECT * FROM tags ORDER BY name");
$all_tags = $tags_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Edit Post</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Edit Post</h1>
        
        <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php endif; ?>

        <form action="edit.php?id=<?php echo $post['id']; ?>" method="post">
            <div>
                <label for="title">Title</label>
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>
            <div>
                <label for="content">Content</label>
                <textarea id="content" name="content" rows="10" required><?php echo htmlspecialchars($post['content'], ENT_QUOTES, 'UTF-8'); ?></textarea>
            </div>
            <div>
                <label>Tags</label>
                <div class="tag-group">
                    <?php foreach ($all_tags as $tag): ?>
                        <label>
                            <input type="checkbox" name="tags[]" value="<?php echo $tag['id']; ?>" <?php echo in_array($tag['id'], $post_tag_ids) ? 'checked' : ''; ?>>
                            <?php echo htmlspecialchars($tag['name'], ENT_QUOTES, 'UTF-8'); ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
            <button type="submit" class="btn">Update</button>
        </form>
        <p><a href="index.php">Back to list</a></p>
    </div>
</body>
</html>