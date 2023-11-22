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
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <nav>
            <h1>お問い合わせフォーム</h1>
            <a href="../"><i class="fa-solid fa-house"></i> Home</a>
        </nav>

        <form method="post">
            <?php if(isset($err["name"])): ?>
                <div class="err"><?= $err["name"] ?></div>
            <?php endif ?>
            <label>
                <span>名前＿:</span>
                <input type="text" name="name" value="<?= h($post["name"] ?? '') ?>">
            </label>

            <?php if(isset($err["email"])): ?>
                <div class="err"><?= $err["email"] ?></div>
            <?php endif ?>
            <label>
                <span>メール:</span>
                <input type="text" name="email" value="<?= h($post["email"] ?? '') ?>">
            </label>
    
            <?php if(isset($err["message"])): ?>
                <div class="err"><?= $err["message"] ?></div>
            <?php endif ?>
            <span>内容＿:</span>
            <textarea name="message" rows="10"><?= h($post["message"] ?? '') ?></textarea>
    
            <button>確認</button>
        </form>
    </div>
</body>
</html>