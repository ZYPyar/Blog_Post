<?php
require_once 'db_connect.php';

// 記事とタグを取得する
// LEFT JOIN を使うことで、記事に紐づくタグ名を取得し、GROUP_CONCATでカンマ区切りの文字列にする
$stmt = $pdo->query("
    SELECT 
        p.id, 
        p.title, 
        p.content, 
        p.created_at, 
        GROUP_CONCAT(t.name) AS tags
    FROM 
        posts p
    LEFT JOIN 
        post_tags pt ON p.id = pt.post_id
    LEFT JOIN 
        tags t ON pt.tag_id = t.id
    GROUP BY 
        p.id
    ORDER BY 
        p.created_at DESC
");
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Daily Tech Blog</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Daily Tech Blog</h1>
        <a href="post.php" class="btn">Create New Post</a>

        <?php foreach ($posts as $post): ?>
            <div class="post">
                <h2><?php echo htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8'); ?></h2>
                <div class="post-meta">
                    Created at: <?php echo $post['created_at']; ?>
                </div>
                <p>
                    <?php echo nl2br(htmlspecialchars(mb_substr($post['content'], 0, 150), ENT_QUOTES, 'UTF-8')); ?>...
                </p>
                <?php if (!empty($post['tags'])): ?>
                <div class="post-tags">
                    <?php 
                        $tags = explode(',', $post['tags']);
                        foreach($tags as $tag) {
                            echo '<span>' . htmlspecialchars($tag, ENT_QUOTES, 'UTF-8') . '</span>';
                        }
                    ?>
                </div>
                <?php endif; ?>
                <a href="edit.php?id=<?php echo $post['id']; ?>">Edit</a> | 
                <a href="delete.php?id=<?php echo $post['id']; ?>" onclick="return confirm('Are you sure you want to delete this post?');">Delete</a>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>