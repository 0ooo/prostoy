<?php

require('../../config.php');
require('./classes/ssp.class.php');

global $CFG;

$check = [];
$check[] = $_GET['columns'][0]['data'] == 'id';
$check[] = $_GET['columns'][0]['name'] == 'id';
$check[] = $_GET['columns'][1]['data'] == 'fio';
$check[] = $_GET['columns'][1]['name'] == 'fio';
$check[] = $_GET['columns'][2]['data'] == 'email';
$check[] = $_GET['columns'][2]['name'] == 'email';
$check[] = $_GET['columns'][3]['data'] == 'post';
$check[] = $_GET['columns'][3]['name'] == 'post';
$check[] = $_GET['columns'][4]['data'] == 'message';
$check[] = $_GET['columns'][4]['name'] == 'message';
$check[] = $_GET['columns'][5]['data'] == 'created_at';
$check[] = $_GET['columns'][5]['name'] == 'created_at';
$check[] = isset($_GET['draw']);

//Проверка что запрос пришел с datatable
foreach ($check as $item) {
    if (!$item) {
        redirect('/');
        die;
    }
}

$table = $CFG->prefix . 'local_feedback';

$primaryKey = 'id';

//столбцы для выборки из бд и отображения в таблице
$columns = [
    ['db' => 'id', 'dt' => 'id'],
    ['db' => 'fio', 'dt' => 'fio'],
    ['db' => 'email', 'dt' => 'email'],
    ['db' => 'post', 'dt' => 'post'],
    ['db' => 'message', 'dt' => 'message'],
    ['db' => 'created_at', 'dt' => 'created_at'],
];

$dbInfo = [
    'user' => $CFG->dbuser,
    'pass' => $CFG->dbpass,
    'db' => $CFG->dbname,
    'host' => $CFG->dbhost
];

$response = SSP::simple($_GET, $dbInfo, $table, $primaryKey, $columns);

echo json_encode($response);