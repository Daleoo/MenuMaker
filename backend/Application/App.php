<?php
namespace App;
use Silex\Application;

class App extends Application {
    private static $_db;

    public function __construct() {
        parent::__construct();
        App::init();
        $this['debug'] = true;

        $this->register(new \Silex\Provider\TwigServiceProvider(), array(
            'twig.path' => __DIR__.DS.'..'.DS.'templates',
            'twig.class_path' => __DIR__.DS.'..'.DS.'vendor'.DS.'twig'.DS.'lib',
        ));
    }

    /**
     * Initial values
     */
    public static function init() {
        $conf = self::getConfig('database');

        self::$_db = new \App\Database(
            $conf->host,
            $conf->database,
            $conf->user,
            $conf->password
        );
    }
    /**
     * Define routes
     */
    public function route() {

        //List menu items
        $this->get('item/list', function() {
            $collection = App::model('item')->getCollection()->toJson();
            return $collection;
        });

        //Get specific menu item
        $this->get('item/view/{id}', function($id) {
            $model = App::model('item');
            $model->load($id);

            return json_encode($model->getData());
        });

        $this->get('menu/list',function() {
            $collection = App::model('menu')->getCollection()->toJson();
            return $collection;
        });

        $this->get('menu/view/{id}', function($id) {

        });

        $this->get('menu/generate/{id}',function($id) {
            $controller = App::controller('menu');
            return $controller->generateMenu($id);

        });
    }

    /**
     * Load model using psr-4 path
     */
    public static function model($path) {
        $namespace = "\\App\\Model\\".$path;
        return new $namespace();
    }

    /**
     * Load controller using psr-4 path
     */
    public static function controller($path) {
        $namespace = "\\App\\Controller\\".$path;
        return new $namespace();
    }

    /**
     * Get the current database
     */
    public static function db() {
        return self::$_db;
    }

    /**
     * Load a value from the config
     */
    public static function getConfig($value) {
        $config = file_get_contents(__DIR__.'/etc/config.json');
        $decodedConfig = json_decode($config);

        if($value) {
            return $decodedConfig->$value;
        } else {
            return $decodedConfig;
        }
    }

    /**
     * Get the Twig templating engine
     */
    public static function getTemplater() {
        $app = new App();
        return $app['twig'];
    }
}

?>
