<?php
include __DIR__ . '/partials/init.php';

header('Content-Type: application/json');

// 小專
$folder = __DIR__ . '/img/';

$imgTypes = [
    'image/jpeg' => '.jpg',
    'image/png' => '.png',
];

$output = [
    'success' => false,
    'error' => '資料欄位不足',
    'code' => 0,
    'postData' => $_POST,
];


if (empty($_POST['nick_name'])) {
    echo json_encode($output);
    exit;
}

$isSaved = false;

if (!empty($_FILES) and !empty($_FILES['avatar'])) {

    $ext = isset($imgTypes[$_FILES['avatar']['type']]) ? $imgTypes[$_FILES['avatar']['type']] : null;

    if (!empty($ext)) {
        $filename = sha1($_FILES['avatar']['name'] . rand()) . $ext;

        if (move_uploaded_file(
            $_FILES['avatar']['tmp_name'],
            $folder . $filename
        )) {
            echo $filename;
            exit;
            $sql = "UPDATE `teachers` SET `avatar`=? WHERE sid=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $filename,
                $_SESSION['user']['sid'],
            ]);
            if ($stmt->rowCount()) {
                $isSaved = true;
                $_SESSION['user']['avatar'] = $filename;
                $output['filename'] = $filename;
                $output['error'] = '';
                $output['success'] = true;

                echo json_encode($output, JSON_UNESCAPED_UNICODE);
                exit;
            }
        }
    }
}

if(! $isSaved){
    $sql = "UPDATE `teachers` SET `nick_name`=? WHERE sid=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $_POST['nick_name'],
        $_SESSION['user']['sid'],
    ]);
            if($stmt->rowCount()){
                $_SESSION['user']['nick_name'] = $_POST['nick_name'];
                $output['error'] = '';
                $output['success'] = true;
            }           
}

echo json_encode($output);
