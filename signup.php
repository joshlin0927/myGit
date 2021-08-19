<?php
include __DIR__ . '/partials/init.php';
$title = '會員註冊';
?>
<?php include __DIR__ . '/partials/html_head.php'; ?>
<?php include __DIR__ . '/partials/navbar.php'; ?>
<style>
    form .form-group small {
        color: grey;
    }
</style>
<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">會員註冊</h5>

                    <form name="form1" onsubmit="checkForm(); return false;">
                        <div class="form-group">
                            <label for="account">電子信箱即是您的帳號 E-mail is your account*</label>
                            <input type="text" class="form-control" id="account" name="account">
                            <small class="form-text ">youremail@example.com</small>
                        </div>
                        <div class="form-group">
                            <label for="password">密碼 Password*</label>
                            <input type="text" class="form-control" id="password" name="password">
                            <small class="form-text ">請填寫至少八位數密碼</small>
                        </div>
                        <div class="form-group">
                            <label for="full_name">姓名 Full Name*</label>
                            <input type="text" class="form-control" id="full_name" name="full_name">
                            <small class="form-text ">請輸入全名</small>
                        </div>
                        <div class="form-group">
                            <label for="nick_name">暱稱 Nick Name</label>
                            <input type="text" class="form-control" id="nick_name" name="nick_name">
                            <small class="form-text "></small>
                        </div>
                        <div class="form-group">
                            <label for="language">擅長的語言 Languages that you good at*</label>
                            <input type="text" class="form-control" id="language" name="language">
                            <small class="form-text ">
                                請直接輸入您所擅長的語言，例如：日文、英文
                            </small>
                        </div>
                        <div class="form-group">
                            <label for="birthday">生日 Birthday</label>
                            <input type="date" class="form-control" id="birthday" name="birthday">
                            <small class="form-text "></small>
                        </div>
                        <div class="form-group">
                            <label for="gender">性別 Gender</label>
                            <select class="form-control" id="gender" name="gender">
                                <option value="none" selected="selected" id="none" disabled>
                                    ----------請選擇 Please select----------
                                </option>
                                <option value="male" id="male">男性 Male</option>
                                <option value="female" id="female">女性 Female</option>
                            </select>
                            <small class="form-text "></small>
                        </div>
                        <div class="form-group" id="nationality-group">
                            <label for="nationality">國籍 Nationality</label>
                            <input type="text" class="form-control" id="nationality" name="nationality">
                            <small class="form-text "></small>
                        </div>

                        <button type="submit" class="btn btn-primary">註冊 Sign Up</button>
                    </form>


                </div>
            </div>
        </div>
    </div>


</div>
<?php include __DIR__ . '/partials/scripts.php'; ?>
<script>
    const email_re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

    const full_name = document.querySelector('#full_name');
    const account = document.querySelector('#account');
    const pwd = document.querySelector('#password');
    const lan = document.querySelector('#language');
    const gender = document.querySelector('#gender');
    const nat = document.querySelector('#nationality');


    function checkForm() {
        full_name.nextElementSibling.innerHTML = '';
        full_name.style.border = '1px #CCCCCC solid';
        account.nextElementSibling.innerHTML = '';
        account.style.border = '1px #CCCCCC solid';
        pwd.nextElementSibling.innerHTML = '';
        pwd.style.border = '1px #CCCCCC solid';
        lan.nextElementSibling.innerHTML = '';
        lan.style.border = '1px #CCCCCC solid';
        gender.nextElementSibling.innerHTML = '';
        gender.style.border = '1px #CCCCCC solid';

        let isPass = true;
        if (full_name.value.length < 2) {
            isPass = false;
            full_name.nextElementSibling.innerHTML = '請填寫正確的姓名';
            full_name.nextElementSibling.style.color = 'red';
            full_name.style.border = '1px red solid';
        }

        if (!email_re.test(account.value)) {
            isPass = false;
            account.nextElementSibling.innerHTML = '請填寫正確的 Email 格式';
            account.nextElementSibling.style.color = 'red';
            account.style.border = '1px red solid';
        }

        if (pwd.value.length < 8) {
            isPass = false;
            pwd.nextElementSibling.innerHTML = '請填寫至少八位數密碼';
            pwd.nextElementSibling.style.color = 'red';
            pwd.style.border = '1px red solid';
        }

        if (lan.value.length < 1) {
            isPass = false;
            lan.nextElementSibling.innerHTML = '請填寫您所擅長的語言，例如：日文、英文';
            lan.nextElementSibling.style.color = 'red';
            lan.style.border = '1px red solid';
        }

        if (gender.value == "none") {
            isPass = false;
            gender.nextElementSibling.innerHTML = '請選擇您的性別';
            gender.nextElementSibling.style.color = 'red';
            gender.style.border = '1px red solid';
            return;
        }

        if (isPass) {
            const fd = new FormData(document.form1);
            fetch('signup_api.php', {
                    method: 'POST',
                    body: fd
                })
                .then(r => r.json())
                .then(obj => {
                    console.log(obj);
                    if (obj.success) {
                        alert('註冊成功，三秒後自動跳轉');
                        setInterval(location.href = "index_.php", 3000)
                    } else {
                        alert(obj.error);
                    }
                })

        }
    }
</script>
<?php include __DIR__ . '/partials/html_foot.php'; ?>