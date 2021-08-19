<?php
include __DIR__ . '/partials/init.php';
$title = '上傳影片';
?>
<?php include __DIR__ . '/partials/html_head.php'; ?>
<?php include __DIR__ . '/partials/navbar.php'; ?>
<style>
    form .form-group small {
        color: grey;
        display: block;
    }
</style>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">上傳影片</h5>

                    <form name="form1" onsubmit="checkForm(); return false;">


                        <div class="form-group">
                            <label for="video_name">影片名稱 *</label>
                            <input type="text" class="form-control" id="video_name" name="video_name">
                            <small class="form-text">請輸入至少一個字作為名稱</small>
                        </div>

                        <div class="form-group">
                            <label for="video_cover">要上傳的影片封面 *</label>
                            <input type="file" class="form-control" id="video_cover" name="video_cover" accept="image/*">
                            <small class="form-text">僅接受 .jpg .png 類型的檔案</small>
                            <div id="vidPreview"></div>
                        </div>

                        <div class="form-group">
                            <label for="video_link">要上傳的影片 *</label>
                            <input type="file" class="form-control" id="video_link" name="video_link" accept="video/*">
                            <small class="form-text">僅接受小於 4GB 的 .avi .mov .mp4 .wmv 類型的檔案</small>
                            <div id="vidPreview"></div>
                        </div>

                        <div class="form-group d-none">
                            <label for="teacher_sid">nick_name</label>
                            <input type="text" class="form-control" id="teacher_sid" name="teacher_sid" value="<?= $_SESSION['user']['sid'] ?>">
                            <small class="form-text "></small>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/partials/scripts.php'; ?>

<script>
    const vid_name = document.querySelector('#video_name');
    const vid_link = document.querySelector('#video_link');

    vid_link.addEventListener('change', () => {
        var allowVid_types = /(\.avi|\.mov|\.mp4|\.flv|\.wmv)$/i;

        if (vid_link.files.length > 0) {
            if (vid_link.files[0].size > 4 * 1024 * 1024 * 1024) {
                vid_link.nextElementSibling.innerHTML = '檔案超過 4GB';
                vid_link.nextElementSibling.style.color = 'red';
                vid_link.style.border = '1px red solid';
                return;
            }
        }


        if (!allowVid_types.exec(vid_link.value)) {
            vid_link.nextElementSibling.innerHTML = '僅接受 .avi .mov .mp4 .wmv 類型的檔案';
            vid_link.nextElementSibling.style.color = 'red';
            vid_link.style.border = '1px red solid';
            vid_link.value = '';
            return;
        }
        // else {
        //     //Video preview
        //     if (vid_link.files && vid_link.files[0]) {
        //         var reader = new FileReader();
        //         reader.onload = function(e) {
        //             document.querySelector('#vidPreview').innerHTML = '<video width="320" height="240" poster="img/2cfe692daee4b315c404e597e30e8ef7a9c9a652.jpg" controls> <source src="' + e.target.result + '" type="video/*"></video>';
        //         };
        //         reader.readAsDataURL(vid_link.files[0]);
        //     }
        // }
        vid_link.nextElementSibling.innerHTML = '等待上傳';
        vid_link.style.border = '1px #CCCCCC solid';
    });

    function checkForm() {
        vid_name.nextElementSibling.innerHTML = '';
        vid_name.style.border = '1px #CCCCCC solid';

        let isPass = true;
        if (vid_name.value.length < 1) {
            isPass = false;
            nextElementSibling.innerHTML = '請輸入至少一個字作為名稱';
            vid_name.nextElementSibling.style.color = 'red';
            vid_name.style.border = '1px red solid';
        }


        if (isPass) {
            const fd = new FormData(document.form1);
            fetch('video_upload_api_test.php', {
                    method: 'POST',
                    body: fd
                })
                .then(r => r.json())
                .then(obj => {
                    console.log(obj);
                    if (obj.success) {
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