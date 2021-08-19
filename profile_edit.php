<?php
include __DIR__ . './partials/init.php';
$title = '修改個人資料';

if (!isset($_SESSION['user'])) {
    header('Location: index_.php');
    exit;
}

$sql = "SELECT * FROM `teachers` WHERE sid=" . intval($_SESSION['user']['sid']);

$r = $pdo->query($sql)->fetch();

if (empty($r)) {
    header('Location: index_.php');
    exit;
}
?>

<?php include __DIR__ . "./partials/html_head.php"; ?>
<?php include __DIR__ . "./partials/navbar.php"; ?>
<style>
    form .form-group small {
        color: red;
    }
</style>
<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">修改個人資料</h5>

                    <form name="form1" onsubmit="checkForm(); return false;">
                        <div class="form-group">
                            <label for="avatar">大頭貼</label>
                            


                            <?php if (empty($r['avatar'])) : ?>

                                <!-- 預設的大頭貼 -->
                            <?php else : ?>
                                <!-- 顯示原本的大頭貼 -->

                                <img src="img/<?= $r['avatar'] ?>" alt="" width="300px">

                            <?php endif; ?>


                        </div>
                        <div class="form-group">
                            <label for="account">email (帳號不能修改)</label>
                            <input type="text" class="form-control" disabled value="<?= htmlentities($r['account']) ?>">
                            <small class="form-text "></small>
                        </div>
                        <div class="form-group">
                            <label for="nick_name">暱稱</label>
                            <input type="text" class="form-control" id="nick_name" name="nick_name" value="<?= htmlentities($r['nick_name']) ?>">
                            <small class="form-text "></small>
                        </div>

                        <button type="submit" class="btn btn-primary">修改</button>
                    </form>


                </div>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . './partials/scripts.php'; ?>
<script>
    function checkForm() {

        const fd = new FormData(document.form1);
        fetch('profile_edit_api.php', {
                method: 'POST',
                body: fd
            })
            .then(r => r.json())
            .then(obj => {
                console.log(obj);
                if (obj.success) {
                    setInterval(location.reload(), 300);
                } else {
                    alert(obj.error);
                }
            })
            .catch(error => {
                console.log('error:', error);
            });


    }
</script>
<?php include __DIR__ . './partials/html_foot.php'; ?>