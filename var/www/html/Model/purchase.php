<?php
session_start();
require __DIR__ . '../../../src/Controllers/ProductController.php';

use App\Controllers\ProductController;

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cartItems = json_decode(file_get_contents('php://input'), true);
    $result = ProductController::purchaseItems($cartItems);

    echo json_encode($result);
}
?>
