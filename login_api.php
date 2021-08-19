<?php
include __DIR__ . '/partials/init.php';

$output = [
    'success' => false,
    'error' => '',
    'code' => 0,
];

$sql = "SELECT * FROM teachers WHERE account=?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$_POST['account']]);
$m = $stmt->fetch();


if (!isset($_POST['account']) or !isset($_POST['password'])) {
    $output['error'] = '沒有帳號資料或密碼';
    $output['code'] = 400;
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}



if (empty($m)) {
    $output['error'] = '帳號錯誤';
    $output['code'] = 401;
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit; 
}

if (!password_verify($_POST['password'], $m['password'])) {
    $output['error'] = '密碼錯誤';
    $output['code'] = 405;
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}

$output['success'] = true;
$output['code'] = 200;

$_SESSION['user'] = $m;

echo json_encode($output, JSON_UNESCAPED_UNICODE);
