<?php
namespace App\Controllers;

use PDO, PDOException, Exception;

class UserController {
    private static function getPdo() {
        try {
            $pdo = new PDO('sqlite:/var/www/db/database.sqlite');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            error_log("データベースエラー: " . $e->getMessage());
            return null;
        }
    }

    private static function handleException($message, $e) {
        error_log($message . ": " . $e->getMessage());
        throw new Exception("内部エラーが発生しました。");
    }

    private static function validateEmail($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("無効なメールアドレスです。");
        }
    }

    private static function validatePassword($password) {
        if (strlen($password) < 8) {
            throw new Exception("パスワードは8文字以上である必要があります。");
        }
    }

    private static function validateName($name) {
        if (empty($name) || strlen($name) > 255) {
            throw new Exception("名前は1文字以上255文字以下である必要があります。");
        }
    }

    public static function login($email, $password) {
        try {
            self::validateEmail($email);
            self::validatePassword($password);
        } catch (Exception $e) {
            throw new Exception("入力エラー: " . $e->getMessage());
        }

        $pdo = self::getPdo();
        if ($pdo === null) {
            throw new Exception("データベース接続エラー。");
        }

        try {
            $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
                session_regenerate_id(true);
                $_SESSION['user'] = [
                    'name' => $user['name'],
                    'email' => $user['email']
                ];
                return true;
            } else {
                throw new Exception("メールアドレスまたはパスワードが間違っています。");
            }
        } catch (PDOException $e) {
            self::handleException("ログイン処理中にエラーが発生しました", $e);
        }
    }

    public static function signup($name, $email, $password) {
        try {
            self::validateName($name);
            self::validateEmail($email);
            self::validatePassword($password);
        } catch (Exception $e) {
            throw new Exception("入力エラー: " . $e->getMessage());
        }

        $pdo = self::getPdo();
        if ($pdo === null) {
            throw new Exception("データベース接続エラー。");
        }

        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare('INSERT INTO users (name, email, password) VALUES (:name, :email, :password)');
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            self::handleException("新規登録処理中にエラーが発生しました", $e);
        }
    }
}
?>
