<?php
include __DIR__ . '/partials/init.php';

header('Content-Type: application/json');


// 要存放圖檔的資料夾
$vidFolder = __DIR__ . '/video/';
$imgFolder = __DIR__ . '/img/';

// 允許的檔案類型
$videoTypes = [
    'video/avi' => '.avi',
    'video/mov' => '.mov',
    'video/mp4' => '.mp4',
    'video/wmv' => '.wmv',

];

$imageTypes = [
    'image/.jpeg' => '.jpg',
    'image/.png' => '.png',
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

if (!empty($_FILES) and !empty($_FILES['video_cover'])) {

    $imgExt = isset($imageTypes[$_FILES['video_cover']['type']]) ? $imageTypes[$_FILES['video_cover']['type']] : null;

    // 取得副檔名

    // 如果是允許的檔案類型
        if(! empty($ext)){
            $filename = sha1( $_FILES['video_link']['name']. rand()). $ext;

        if (move_uploaded_file(
            $_FILES['video_link']['tmp_name'],
            $folder . $filename
        )) {
            $sql = "INSERT INTO `video_list`(
                      `video_link`, `teacher_sid`, `created_at`
                    ) VALUES (
                             ?, ?, NOW()
                    )";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $filename,
                $_POST['teacher_sid'],
            ]);

            $output['rowCount'] = $stmt->rowCount();
            if ($stmt->rowCount() == 1) {
                $isSaved = true;
                $output['error'] = '';
                $output['success'] = true;
            }
            
        }
    }

}

if (!move_uploaded_file(
    $_FILES['video_cover']['tmp_name'],
    $imgFolder . $imgName
)) {
    echo "image file move failed";
}

if (!empty($_FILES) and !empty($_FILES['video_link'])) {

    $vidExt = isset($videoTypes[$_FILES['video_link']['type']]) ? $videoTypes[$_FILES['video_link']['type']] : null;

    // 取得副檔名

    // 如果是允許的檔案類型
    if (!empty($vidExt)) {
        $vidName = sha1($_FILES['video_link']['name'] . rand()) . $vidExt;
    }
}

if (!move_uploaded_file(
    $_FILES['video_link']['tmp_name'],
    $vidFolder . $vidName
)) {
    echo "video file move failed";
}

$sql = "INSERT INTO `video_list`(
            `video_cover`, 
            `video_name`, `video_link`, `teacher_sid`, `created_at`
            ) VALUES (
                ?, 
                ?, ?, ?, NOW()
            )";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    $imgName,
    $_POST['video_name'],
    $vidName,
    $_POST['teacher_sid'],
]);

$output['rowCount'] = $stmt->rowCount();
if ($stmt->rowCount() == 1) {
    $isSaved = true;
    $output['imgName'] = $imgName;
    $output['vidName'] = $vidName;
    $output['error'] = '';
    $output['success'] = true;
}


echo json_encode($output);
?>
