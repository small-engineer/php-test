<?php
require __DIR__ . '/../src/Components/Header.php';
require __DIR__ . '/../src/Components/Footer.php';
require __DIR__ . '/../src/Components/Popup.php';
require __DIR__ . '/../src/Controllers/ProductController.php';

use App\Components\Header;
use App\Components\Footer;
use App\Components\Popup;
use App\Controllers\ProductController;

Header::render();
Popup::render();
?>
<div class="content">
    <h2>商品一覧</h2>
    <?php ProductController::displayNewArrivals(); ?>
</div>
<?php Footer::render(); ?>
