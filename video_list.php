<?php
include __DIR__ . '/partials/init.php';
$title = '影片列表';

if (!isset($_SESSION['user'])) {
    header('Location: index_.php');
    exit;
}


// 固定每一頁最多幾筆
$perPage = 5;

// query string parameters
$qs = [];

// 關鍵字查詢
$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';

// 用戶決定查看第幾頁，預設值為 1
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

$userSid = intval($_SESSION['user']['sid']);

$where = " WHERE teacher_sid =". $userSid ;
if (!empty($keyword)) {
    $where .= sprintf(" AND `video_name` LIKE %s ", $pdo->quote('%' . $keyword . '%'));
    $qs['keyword'] = $keyword;
}

// 總共有幾筆
$totalRows = $pdo->query("SELECT count(1) FROM video_list $where")
->fetch(PDO::FETCH_NUM)[0];

// 總共有幾頁, 才能生出分頁按鈕
$totalPages = ceil($totalRows / $perPage);

$rows = [];
// 要有資料才能讀取該頁的資料
if ($totalRows != 0) {
    // 讓 $page 的值在安全的範圍
    if ($page < 1) {
        header('Location: ?page=1');
        exit;
    }
    if ($page > $totalPages) {
        header('Location: ?page=' . $totalPages);
        exit;
    }
    
    $sql = sprintf(
        "SELECT * FROM video_list %s ORDER BY sid DESC LIMIT %s, %s",
        $where,
        ($page - 1) * $perPage,
        $perPage
    );

    $rows = $pdo->query($sql)->fetchAll();
}
?>
<?php include __DIR__ . '/partials/html_head.php'; ?>
<?php include __DIR__ . '/partials/navbar.php'; ?>
<style>
    table tbody i.fas.fa-trash-alt {
        color: darkred;
    }
</style>
<div class="container">
    <div class="row">
        <div class="col-12 d-flex justify-content-center my-4">
            <form action="video_list.php" class="form-inline my-2 my-lg-0 d-flex justify-content-end">
                <input class="form-control mr-sm-2" type="search" name="keyword" placeholder="Search" value="<?= htmlentities($keyword) ?>" aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th scope="col">
                            <i class="fas fa-trash-alt p-2"></i>
                        </th>
                        <th scope="col" class="d-none">sid</th>
                        <th scope="col">video_cover</th>
                        <th scope="col">video_name</th>
                        <th scope="col" class="overflow-auto">video_link</th>
                        <th scope="col">duration</th>
                        <th scope="col">create_at</th>
                        <th scope="col"><i class="fas fa-edit p-2"></i></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $r) : ?>
                        <tr data-sid="<?= $r['sid'] ?>">
                            <td><i class="fas fa-trash-alt p-2 ajaxDelete"></i></td>
                            <td class="d-none"><?= $r['sid'] ?></td>
                            <td><?= $r['video_cover'] ?></td>
                            <td id="v-name"><?= htmlentities($r['video_name']) ?></td>
                            <td class="overflow-auto"><?= $r['video_link'] ?></td>
                            <td><?= $r['duration'] ?></td>
                            <td><?= $r['created_at'] ?></td>
                            <td>
                                <a href="video_detail.php?sid=<?= $r['sid'] ?>">
                                    <i class="fas fa-edit p-2"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <nav aria-label="Page navigation example">
                <ul class="pagination d-flex justify-content-end">
                    <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="?<?php
                                                    $qs['page'] = $page - 1;
                                                    echo http_build_query($qs);
                                                    ?>">
                            <i class="fas fa-arrow-circle-left"></i>
                        </a>
                    </li>

                    <?php for ($i = $page - 5; $i <= $page + 5; $i++) :
                        if ($i >= 1 and $i <= $totalPages) :
                            $qs['page'] = $i;
                    ?>
                            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                <a class="page-link" href="?<?= http_build_query($qs) ?>"><?= $i ?></a>
                            </li>
                    <?php endif;
                    endfor; ?>

                    <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                        <a class="page-link" href="?<?php
                                                    $qs['page'] = $page + 1;
                                                    echo http_build_query($qs);
                                                    ?>">
                            <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>

        </div>
    </div>
</div>



<?php include __DIR__ . '/partials/scripts.php'; ?>
<script>
    const myTable = document.querySelector('table');
    const myTbody = document.querySelector("tbody");

    myTable.addEventListener('click', function(event) {

        // 判斷有沒有點到垃圾筒
        if (event.target.classList.contains('ajaxDelete')) {
            const tr = event.target.closest('tr');
            const v_name = document.querySelector('#v-name').textContent;
            const sid = tr.getAttribute('data-sid');

            if (confirm(`是否要刪除編號為 ${v_name} 的資料？`)) {
                fetch('video_delete_api.php?sid=' + sid)
                    .then(r => r.json())
                    .then(obj => {
                        if (obj.success) {
                            tr.remove();
                            setInterval(location.reload(), 300);
                        } else {
                            alert(obj.error);
                        }
                    });
            }

        }
    });
</script>
<?php include __DIR__ . '/partials/html_foot.php'; ?>