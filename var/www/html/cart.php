<?php
ob_start();
session_start();
require __DIR__ . '/../src/Components/Header.php';
require __DIR__ . '/../src/Components/Footer.php';
require __DIR__ . '/../src/Components/Popup.php';
require __DIR__ . '/../src/Controllers/ProductController.php';

use App\Components\Header;
use App\Components\Footer;
use App\Components\Popup;

Header::render();
Popup::render();

$isLoggedIn = isset($_SESSION['user']);
?>

<div class="content">
    <h2>カート</h2>
    <table class="cart-table">
        <thead>
            <tr>
                <th>商品名</th>
                <th>価格</th>
                <th>操作</th> <!-- 追加 -->
            </tr>
        </thead>
        <tbody id="cart-items"></tbody>
    </table>
    <?php if ($isLoggedIn): ?>
        <h2>ログイン情報</h2>
        <p>名前: <?php echo htmlspecialchars($_SESSION['user']['name']); ?></p>
        <p>メールアドレス: <?php echo htmlspecialchars($_SESSION['user']['email']); ?></p>
        <button id="purchase-button">購入</button>
    <?php else: ?>
        <p>購入するには<a href="login.php">ログイン</a>してください。</p>
    <?php endif; ?>
</div>

<?php Footer::render(); ?>
<?php ob_end_flush(); ?>
