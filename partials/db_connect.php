<?php
$db_host = 'localhost'; //主機名稱
$db_name = 'small_proj';    //資料庫名稱
$db_user = 'joshlin11';
$db_pass = '0927';

// data source name
$dsn = "mysql:host={$db_host};dbname={$db_name};charset=utf8";
$pdo_options =[
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8", //連線後，資料全都以utf8做編碼
];

$pdo = new PDO($dsn, $db_user, $db_pass, $pdo_options);