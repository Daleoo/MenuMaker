<?php

/**
  * Abstract class for Controller objects
  *
  */
namespace App\Controller;

use App\App;

abstract class Controller
{
    protected $request;
    protected $params;

    public function route($action,$request) {
        $this->request = $request;
        $this->loadParams();

        if($action) {
            echo $action;
            if(method_exists($this,$action."Action")) {
                $action .= "Action";
                echo $action;
                return $this->$action();
            } else {
                //TODO: Redirect to 404
            }
        } else {
            return $this->indexAction();
        }
    }

    protected function getRequest() {
        return $this->request;
    }

    protected function render($page, array $variables = []) {
        return $this->renderer->render($page, $variables);
    }

    protected function loadParams() {
        $vars = $this->getRequest()->param('vars');

        //Remove trailing slash
        $vars = rtrim($vars,"/");

        $varArray = [];
        if($vars) {

            $exploded = explode("/",$vars);
            for($i = 0; $i < count($exploded); $i++) {
                $varArray[$exploded[$i]] = $exploded[$i+1];
                $i++;
            }
        }

        $this->params = $varArray;
    }
    protected function param($key) {
        if(isset($this->params[$key])) {
            return $this->params[$key];
        }
    }

}
