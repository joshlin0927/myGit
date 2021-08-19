<?php
include __DIR__ . '/partials/init.php';

header('Content-Type: application/json');


// 要存放圖檔的資料夾
$imgFolder = __DIR__ . '/img/';

// 允許的檔案類型
$imgTypes = [
    'image/jpeg' => '.jpg',
    'image/png' => '.png',
];

$output = [
    'success' => false,
    'error' => '資料欄位不足',
    'code' => 0,
    'rowCount' => 0,
    'postData' => $_POST,
];

// 預設是沒有上傳資料，沒有上傳成功
$isSaved = false;

// 如果有上傳檔案
if (!empty($_FILES) and !empty($_FILES['video_cover'])) {

    //TODO:為什麼抓不到圖片的附檔名?

    $ext = isset($imgTypes[$_FILES['video_cover']['type']]) ? $imgTypes[$_FILES['video_cover']['type']] : null;
    // 取得副檔名

    // 如果是允許的檔案類型
    if (!empty($ext)) {
        $filename = sha1($_FILES['video_cover']['name'] . rand()) . $ext;

        if (move_uploaded_file(
            $_FILES['video_cover']['tmp_name'],
            $imgFolder . $filename
        )) {

            $sql = "UPDATE `video_list` SET 
                              `video_cover`=?,  `video_name`=?
                                WHERE `sid`=?";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $filename,
                $_POST['video_name'],
                $_POST['sid'],
            ]);

            $output['rowCount'] = $stmt->rowCount(); // 修改的筆數
            if ($stmt->rowCount() == 1) {
                $isSaved = true;
                $output['filename'] = $filename;
                $output['success'] = true;
                $output['error'] = '';
            } else {
                $output['error'] = '資料沒有修改';
            }
        }
    }
    echo json_encode($output);
}
