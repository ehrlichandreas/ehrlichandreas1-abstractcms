<?php

ini_set('display_startup_errors', true);

ini_set('display_errors', true);

ini_set('error_reporting', -1);

error_reporting(-1);

date_default_timezone_set('UTC');

require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';


$dbConfig = array
(
	'adapter'	=> 'Pdo_Mysql',
	'params'	=> array
	(
		'host'		=> 'localhost',
        //'port'      => 8889,
		'username'	=> '',
		'password'	=> '',
		'dbname'	=> 'dbname',
		'charset'	=> 'utf8',
	),
);

$dbConnection = EhrlichAndreas_Db_Db::factory($dbConfig);

$sql = 'SELECT * FROM `table`';

$response = $dbConnection->fetchAll($sql);

echo '<pre>';
print_r($response);