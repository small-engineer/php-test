<?php
try {
    $pdo = new PDO('sqlite:/var/www/db/database.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 商品テーブルの作成
    $pdo->exec("CREATE TABLE IF NOT EXISTS products (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        description TEXT NOT NULL,
        price REAL NOT NULL,
        image_path TEXT NOT NULL,
        stock INTEGER NOT NULL
    )");

    // ユーザーテーブルの作成
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        email TEXT NOT NULL,
        password TEXT NOT NULL
    )");

    // サンプルデータの挿入
    $pdo->exec("INSERT INTO products (name, description, price, image_path, stock) VALUES
        ('さくらんぼ', 'ジュノハート 青森県産 15粒入 3〜4Lサイズ', 6980, '/images/cherry.png', 8),
        ('スイカ', '大栄西瓜 鳥取県産 1玉 2〜3L 約7〜8kg', 3980, '/images/watermelon.png', 43),
        ('パイナップル', '超大玉 サンドルチェ 沖縄県産 1.5kg×2玉', 2980, 'images/pineapple.png', 20),
        ('梨', '二十世紀梨 鳥取県産 5kg', 2700, 'images/pear.png', 40)");

    echo "データベースの初期化が完了しました。\n";
} catch (PDOException $e) {
    echo "データベースエラー: " . $e->getMessage() . "\n";
}
