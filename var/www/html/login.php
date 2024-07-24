<?php
ob_start();
require __DIR__ . '/../src/Components/Header.php';
require __DIR__ . '/../src/Components/Footer.php';
require __DIR__ . '/../src/Components/Popup.php';
require __DIR__ . '/../src/Controllers/UserController.php';

use App\Components\Header;
use App\Components\Footer;
use App\Components\Popup;
use App\Controllers\UserController;

session_start();

Header::render();
Popup::render();

$isLoggedIn = isset($_SESSION['user']);
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['login'])) {
            $email = $_POST['login-email'];
            $password = $_POST['login-password'];
            if (UserController::login($email, $password)) {
                header('Location: login.php');
                exit();
            }
        } elseif (isset($_POST['signup'])) {
            $name = $_POST['signup-name'];
            $email = $_POST['signup-email'];
            $password = $_POST['signup-password']; // 修正
            if (UserController::signup($name, $email, $password)) {
                $_SESSION['account_created'] = true;
                header('Location: login.php');
                exit();
            }
        } elseif (isset($_POST['logout'])) {
            session_unset();
            session_destroy();
            header('Location: login.php');
            exit();
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// ページの再読み込み時に新規登録フォームを再表示するために `account_created` をリセット
$showSignupForm = !isset($_SESSION['account_created']);

if (isset($_SESSION['account_created'])) {
    unset($_SESSION['account_created']);
}
?>

<div class="content">
    <?php if ($isLoggedIn): ?>
        <h2>ログイン情報</h2>
        <p>名前: <?php echo htmlspecialchars($_SESSION['user']['name']); ?></p>
        <p>メールアドレス: <?php echo htmlspecialchars($_SESSION['user']['email']); ?></p>
        <form action="login.php" method="post">
            <input type="submit" name="logout" value="ログアウト">
        </form>
    <?php else: ?>
        <h2>ログイン</h2>
        <?php if ($error): ?>
            <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form action="login.php" method="post">
            <label for="login-email">メールアドレス</label>
            <input type="email" id="login-email" name="login-email" required>
            <label for="login-password">パスワード</label>
            <input type="password" id="login-password" name="login-password" required>
            <input type="submit" name="login" value="ログイン">
        </form>

        <?php if ($showSignupForm): ?>
            <h2>新規アカウント作成</h2>
            <form action="login.php" method="post">
                <label for="signup-name">名前</label>
                <input type="text" id="signup-name" name="signup-name" required>
                <label for="signup-email">メールアドレス</label>
                <input type="email" id="signup-email" name="signup-email" required>
                <label for="signup-password">パスワード</label>
                <input type="password" id="signup-password" name="signup-password" required>
                <input type="submit" name="signup" value="登録">
            </form>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php Footer::render(); ?>
<?php ob_end_flush(); ?>
