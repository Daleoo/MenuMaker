<?php
require_once __DIR__.'/vendor/autoload.php';
define('DS',DIRECTORY_SEPARATOR);

require_once __DIR__.DS.'CsvParser.php';
use App\App;

App::init();
App::db()->query("DROP TABLE menu");

$parser = new CsvParser();
$parser->read('menus.csv');

foreach($parser as $line) {
    $model = App::model('menu');
    $model->setData($line);
    $model->save();
}
?>
