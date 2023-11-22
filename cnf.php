<?php
session_start();

function h($str) {
    return htmlspecialchars($str,ENT_QUOTES,'UTF-8');
}

if(!isset($_SESSION["cnf"])) {
    $host = $_SERVER['HTTP_HOST'];
    $url = rtrim(dirname($_SERVER['PHP_SELF']), '/');
    header("Location: //$host$url");
    exit();
} else {
    $post = $_SESSION["cnf"];
}

if($_SERVER["REQUEST_METHOD"]==="POST") {
    $to = '*****@hotmail.com';
    $headers = "From: {$post['email']}";
    $subject = 'メールの送信がありました';
    $body = <<<EOT
名前: {$post['name']}
メールアドレス: {$post['email']}
内容:
{$post['message']}
EOT;

    mb_send_mail($to, $subject, $body, $headers);

    unset($_SESSION["cnf"]);
    session_destroy();
    header('Location: result.html');
    exit();
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>確認フォーム</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="name">
            <span class="item">名前＿:</span>
            <?= mb_substr($post["name"], 0, 15) ?>
        </div>

        <div class="email">
            <span class="item">メール:</span>
            <?= $post["email"] ?>
        </div>

        <div class="message">
            <span class="item">内容＿:</span><br>
            <?= nl2br(mb_substr($post["message"], 0, 2000)) ?>
        </div>

        <div class="btns">
            <a href="./">戻る</a>
            <form method="post"><button>送信</button></form>
        </div>
    </div>
</body>
</html>