<?php
session_start();
if (!isset($_SESSION['admin'])) { die('Доступ запрещён'); }

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'save_products' && !empty($_POST['products'])) {
        $data = array_values($_POST['products']);
        file_put_contents('../data/products.json', json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        echo json_encode(['status'=>'ok', 'message'=>'Товары сохранены']);
    } elseif ($action === 'save_reviews' && !empty($_POST['reviews'])) {
        $data = array_values($_POST['reviews']);
        file_put_contents('../data/reviews.json', json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        echo json_encode(['status'=>'ok', 'message'=>'Отзывы сохранены']);
    } else {
        echo json_encode(['status'=>'error', 'message'=>'Нет данных']);
    }
    exit;
}
echo json_encode(['status'=>'error', 'message'=>'Неверный метод']);