<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="index_.php">WANDERS</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
            <ul class="navbar-nav ">
                <?php if (isset($_SESSION['user'])) : ?>
                    <li class="nav-item active">
                        <a class="nav-link mr-2" href="video_upload.php"><i class="fas fa-video mr-1"></i>影片上傳</a>
                    </li>
                    <li class="nav-item">

                    </li>
                    <li class="nav-item dropdown mr-2">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <div class="rounded-circle">
                                <?php if (!empty($_SESSION['user']['avatar'])) : ?>
                                    <img src="img/<?= $_SESSION['user']['avatar'] ?>" alt=""  width="50px">
                                <?php endif; ?>
                            </div>
                            <div><?= $_SESSION['user']['nick_name'] ?></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="profile_edit.php">修改個人資料</a>
                            <a class="dropdown-item" href="video_list.php">課程與影片列表</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">登出</a>
                    </li>
                <?php else : ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">登入</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="signup.php">註冊</a>
                    </li>
                <?php endif; ?>
            </ul>

        </div>
    </div>
</nav>