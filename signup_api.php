<?php
include __DIR__. '/partials/init.php';

header('Content-Type: application/json');

$output = [
    'success' => false,
    'error' => '',
    'code' => 0,
    'rowCount' => 0,
    'postData' => $_POST,
];



if(mb_strlen($_POST['full_name']) < 2) {
    $output['error'] = '姓名長度太短';
    $output['code'] = 410;
    echo json_encode($output);
    exit;
}

if(! filter_var($_POST['account'], FILTER_VALIDATE_EMAIL)) {
    $output['error'] = 'email 格式錯誤';
    $output['code'] = 420;
    echo json_encode($output);
    exit;
}


$cryptPwd = password_hash($_POST['password'], PASSWORD_DEFAULT);

$sql = "INSERT INTO `teachers`(
                `account`, `password`, `full_name`, `nick_name`, 
                `language`, `birthday`, `gender`, `nationality`, 

                `verified_at`
                
                )VALUES (
                    ?, ?, ?, ?,
                    ?, ?, ?, ?,

                    NOW()
               )";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    $_POST['account'],
    $cryptPwd,
    $_POST['full_name'],
    $_POST['nick_name'],
    $_POST['language'],
    $_POST['birthday'],
    $_POST['gender'],
    $_POST['nationality']

]);

$output['rowCount'] = $stmt->rowCount();
if($stmt->rowCount()==1){
    $output['success'] = true;
} 

echo json_encode($output, JSON_UNESCAPED_UNICODE);
