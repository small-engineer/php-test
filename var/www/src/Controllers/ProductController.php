<?php
namespace App\Controllers;

use PDO;
use PDOException;

class ProductController {
    public static function displayNewArrivals() {
        try {
            $pdo = new PDO('sqlite:/var/www/db/database.sqlite');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $pdo->query('SELECT * FROM products ORDER BY id DESC LIMIT 5');

            echo '<ul>';
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<li data-id="' . htmlspecialchars($row['id']) . '">';
                echo '<img src="' . htmlspecialchars($row['image_path']) . '" alt="' . htmlspecialchars($row['name']) . '">';
                echo '<h3>' . htmlspecialchars($row['name']) . '</h3>';
                echo '<p>' . htmlspecialchars($row['description']) . '</p>';
                echo '<p class="price">' . htmlspecialchars($row['price']) . '円</p>';
                echo '<p>在庫: ' . htmlspecialchars($row['stock']) . '</p>';
                echo '<button class="cart-button">カートへ</button>';
                echo '</li>';
            }
            echo '</ul>';
        } catch (PDOException $e) {
            echo "データベースエラー: " . $e->getMessage();
        }
    }

    public static function purchaseItems($items) {
        try {
            $pdo = new PDO('sqlite:/var/www/db/database.sqlite');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();

            foreach ($items as $item) {
                $stmt = $pdo->prepare('UPDATE products SET stock = stock - 1 WHERE id = ? AND stock > 0');
                $stmt->execute([$item['id']]);

                if ($stmt->rowCount() === 0) {
                    throw new PDOException('在庫不足のため、購入できませんでした。');
                }
            }

            $pdo->commit();
            return ['success' => true];
        } catch (PDOException $e) {
            $pdo->rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
?>
