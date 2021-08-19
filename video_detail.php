<?php
include __DIR__ . '/partials/init.php';
$title = '影片詳細資料';

$sid = isset($_GET['sid']) ? intval($_GET['sid']) : 0;

$sql = "SELECT * FROM `video_list` WHERE sid=$sid";

//    echo $sql; exit;

$r = $pdo->query($sql)->fetch();

if (empty($r)) {
    header('Location: video_list.php');
    exit;
}

?>
<?php include __DIR__ . '/partials/html_head.php'; ?>
<?php include __DIR__ . '/partials/navbar.php'; ?>
<style>
    form .form-group small {
        color: grey;
        display: block;
    }
    /* #video_coverPreview{
        width: 150px;
    } */
</style>
<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">修改資料</h5>

                    <form name="form1" onsubmit="checkForm(); return false;">

                        <input type="hidden" name="sid" value="<?= $r['sid'] ?>">
                        <div class="form-group">
                            <label for="video_name">影片名稱 *</label>
                            <input type="text" class="form-control" id="video_name" name="video_name" value="<?= htmlentities($r['video_name']) ?>">
                            <small class="form-text">請輸入至少一個字作為名稱</small>
                        </div>
                        <div class="form-group">
                            <label for="video_cover">要上傳的影片封面 *</label>
                            <div>
                                <input type="file" class="form-control" id="video_cover" name="video_cover" accept="image/*">
                            </div>
                            <small class="form-text" id="cover_warning">僅接受 .jpg .png 類型的檔案</small>
                            <div id="video_coverPreview" class="d-flex justify-content-center"></div>
                        </div>


                        <div class="form-group">
                            <label for="video_link">video_link *</label>
                            <input type="text" class="form-control" id="video_link" name="video_link" value="<?= htmlentities($r['video_link']) ?> " disabled>
                            <small class="form-text "></small>
                        </div>

                        <button type="submit" class="btn btn-primary">修改</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>


</div>
<?php include __DIR__ . '/partials/scripts.php'; ?>
<script>
    const vid_name = document.querySelector('#video_name');
    const vid_cover = document.querySelector('#video_cover');
    const cover_warning = document.querySelector('#cover_warning');

    vid_cover.addEventListener('change', () => {
        var allowImg_types = /(\.jpg|\.png)$/i;

        cover_warning.innerHTML = '等待上傳';
        cover_warning.style.color = 'grey';
        vid_cover.style.border = '1px #CCCCCC solid';
        if (vid_cover.files.length > 0) {
            if (vid_cover.files[0].size > 5 * 1024 * 1024) {
                cover_warning.innerHTML = '檔案超過 5MB';
                cover_warning.style.color = 'red';
                vid_cover.style.border = '1px red solid';
                return;
            }
        }

        if (!allowImg_types.exec(vid_cover.value)) {
            cover_warning.innerHTML = '僅接受 .jpg .png 類型的檔案';
            cover_warning.style.color = 'red';
            vid_cover.style.border = '1px red solid';
            vid_cover.value = '';
            return;
        } else {
            //Image preview
            if (vid_cover.files && vid_cover.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.querySelector('#video_coverPreview').innerHTML = '<img width="300px" src="' + e.target.result + 
                        '"/>';
                };
                reader.readAsDataURL(vid_cover.files[0]);
            }
        }
        
    });

    function checkForm() {
        // 欄位的外觀要回復原來的狀態
        vid_name.nextElementSibling.innerHTML = '';
        vid_name.style.border = '1px #CCCCCC solid';


        let isPass = true;
        if (vid_name.value.length < 1) {
            isPass = false;
            vid_name.nextElementSibling.innerHTML = '請輸入至少一個字作為名稱';
            vid_name.nextElementSibling.style.color = 'red';
            vid_name.style.border = '1px red solid';
        }


        if (isPass) {
            const fd = new FormData(document.form1);
            fetch('video_detail_api.php', {
                    method: 'POST',
                    body: fd
                })
                .then(r => r.json())
                .then(obj => {
                    console.log(obj);
                    if (obj.success) {
                        alert('修改成功');
                        location.href = 'video_list.php';
                    } else {
                        alert(obj.error);
                    }
                })
            // .catch(error => {
            //     console.log('error:', error);
            // });
        }
    }
</script>
<?php include __DIR__ . '/partials/html_foot.php'; ?>