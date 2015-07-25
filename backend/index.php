<?php
require_once __DIR__.'/vendor/autoload.php';

define('DS',DIRECTORY_SEPARATOR);
use App\App;

$app = new App();
$app->route();
$app->run();
?>
