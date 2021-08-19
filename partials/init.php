<?php

require __DIR__. '/db_connect.php';

// 如果沒有啟用 session，就啟用它
if(! isset($_SESSION)){
    session_start();
}