<?php
session_start();

function h($str) {
    return htmlspecialchars($str,ENT_QUOTES,'UTF-8');
}

if($_SERVER["REQUEST_METHOD"]==="POST") {
    $err = [];
    $post = filter_input_array(INPUT_POST,$_POST);

    // バリデーション
    if($post["name"] === "") $err["name"] = '※名前を入力して下さい';
    if($post["email"] === "") {
        $err["email"] = '※メールアドレスを入力して下さい';
    } else if(!filter_var($post["email"], FILTER_VALIDATE_EMAIL)) {
        $err["email"] = '※メールアドレスを正しく記入して下さい';
    }
    if($post["message"] === "") $err["message"] = '※メッセージを入力して下さい';

    // エラーが0だったら
    if(count($err) === 0) {
        $_SESSION["cnf"] = $post;
        $host = $_SERVER['HTTP_HOST'];
        $url = rtrim(dirname($_SERVER['PHP_SELF']), '/');
        header("Location: //$host$url/cnf.php");
        exit();
    } 
}

// cnf.phpがら戻って来た時(GET)
if($_SERVER["REQUEST_METHOD"]==="GET") {
    if(isset($_SESSION["cnf"])) $post = $_SESSION["cnf"];
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://use.fontawesome.com/releases/v6.1.1/css/all.css" rel="stylesheet">
    <title>お問い合わせ</title>
    <link rel="icon" href="../inyou.ico">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <nav>
            <h1><i class="fa-regular fa-envelope"></i> お問い合わせ</h1>
            <a href="../upload" style="color:#4285f4;">
                <i class="fa-brands fa-octopus-deploy"></i>uploader
            </a>
        </nav>

        <form method="post">
            <!-- 名前 -->
            <?php if(isset($err["name"])): ?>
                <div class="err"><?= $err["name"] ?></div>
            <?php endif ?>
            <label>
                <span><i class="fa-solid fa-user"></i></span>
                <input type="text" name="name" value="<?= h($post["name"] ?? '') ?>" placeholder="Your name">
            </label>
            
            <!-- メールアドレス -->
            <?php if(isset($err["email"])): ?>
                <div class="err"><?= $err["email"] ?></div>
            <?php endif ?>
            <label>
                <span><i class="fa-solid fa-envelope"></i></span>
                <input type="text" name="email" value="<?= h($post["email"] ?? '') ?>"  placeholder="Your e-mail">
            </label>

            <!-- 本文 -->
            <?php if(isset($err["message"])): ?>
                <div class="err"><?= $err["message"] ?></div>
            <?php endif ?>
            <span><i class="fa-solid fa-message"></i></span>
            <textarea name="message" rows="10"><?= h($post["message"] ?? '') ?></textarea>
    
            <button>確認 <i class="fa-solid fa-paper-plane"></i></button>
        </form>
    </div>
</body>
</html>