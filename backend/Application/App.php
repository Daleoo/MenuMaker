<?php
namespace App;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\LogFactory as Logger;

class App extends Application {
    private static $_db;

    public function __construct() {
        parent::__construct();
        App::init();
        $this['debug'] = true;
        $this->register(new \Silex\Provider\TwigServiceProvider(), array(
            'twig.path' => __DIR__.DS.'..'.DS.'templates'.DS,
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

        $this->get('item/list/{parent}', function($parent) {
            $collection = App::model('item')->getCollection()->filter('parent',$parent)->toJson();
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
            $collection = App::model('item')->getCollection()->filter('menu',$id)->toJson();
            return $collection;
        });

        $this->get('menu/generate/takeout/{id}',function($id) {
            $controller = App::controller('menu');
            return $controller->generateTakeOutMenu($id);

        });

        $this->get('menu/generate/eatin/{id}',function($id) {
            $controller = App::controller('menu');
            return $controller->generateEatInMenu($id);

        });
        $this->put('item/update',function(Request $request) {
            $model = App::model('item');
            $data = (array) json_decode($request->getContent(),true);
            $model->setData($data);
            $model->save();
            return json_encode($model->getData());
        });

        $this->delete('item/delete',function(Request $request) {
            $model = App::model('item');
            $data = json_decode($request->getContent(),true);
            $model = App::model('item')->load($data['item']);

            if($model->getId()) {
                $model->delete();
            }

            return true;
        });

        $this->put('item/create',function(Request $request) {
            $model = App::model('item');
            $data = (array) json_decode($request->getContent(),true);
            $model->setData($data)
                ->save();

            return true;
        });

        $this->get('menu/push/{id}',function($id) {
            $menu = App::model('menu')->load($id);
            if($menu->getId()) {
                $list = App::model('item')->getCollection()->filter('menu',$id)->toJson();
                $req = ['menu' => $menu->get('title'), 'items' => $list];
                $req = json_encode($req);
                return $req;
            }

            return 0;
        });
    }

    /**
     * Load model using psr-4 path
     */
    public static function model($path) {
        $namespace = "\\App\\Model\\".ucfirst($path);
        return new $namespace();
    }

    /**
     * Load controller using psr-4 path
     */
    public static function controller($path) {
        $namespace = "\\App\\Controller\\".ucfirst($path);
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
