<?php
require_once __DIR__.'/vendor/autoload.php';

define('DS',DIRECTORY_SEPARATOR);
use App\App;

/*error_reporting(-1);
ini_set('display_errors', 'On');
*/
$app = new App();
$app->route();

$app->run();
?>
