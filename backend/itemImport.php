<?php
require_once __DIR__.'/vendor/autoload.php';
define('DS',DIRECTORY_SEPARATOR);

require_once __DIR__.DS.'CsvParser.php';
use App\App;

App::init();
$parser = new CsvParser();
$parser->read('MenuItems.csv');

App::db()->query("DROP TABLE item");
foreach($parser as $item) {
    $model = App::model('item');

    $menu = App::model('menu')->getCollection()->filter('title',$item['menu'])->getFirstItem();
    $parent = App::model('item')->getCollection()
                ->filter('title',$item['parent'])
                ->filter('menu', $menu->getId())
                ->getFirstItem();

    $data = [
        'title' => $item['title'],
        'description' => $item['description'],
        'eatinprice' => $item['eatinprice'],
        'takeoutprice' => $item['takeoutprice'],
        'menu' => $menu ? $menu->getId() : 0,
        'parent' => $parent ? $parent->getId() : 0,
        'takeout' => $item['takeout'],
        'eatin' => $item['eatin']
    ];

    $model->setData($data);
    $model->save();

}
