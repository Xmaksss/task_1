<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ .  '/vendor/autoload.php';

$app = \App\App::init();

$app->addLib(new \App\Libs\Config(__DIR__ . "/.env"));

$db = new \App\Libs\DB(require_once __DIR__ . '/configs/db.php');
$app->addLib($db);
\App\Models\Model::setConnection($db->getConnection());