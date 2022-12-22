<?php
    class App {
        protected $controller = "games";
        protected $method = "index";
        protected $params = [];

        public function __construct() {
            $url = array_slice($this->parseUrl(), 2);
            if (!empty($url)) {
                if (!empty($url[0]) && file_exists('controllers/'.$url[0].'.php')) {
                    $this->controller = $url[0];
                    unset($url[0]);
                    require_once('controllers/'.$this->controller.'.php');
                }
                //SET METHOD OF CONTROLLER
                if (!empty($url[1]) && method_exists($this->controller, $url[1])) {
                    $this->method = $url[1];
                    unset($url[1]);
                }
                $this->params = $url ? array_values($url) : [];
                
                $controller = new $this->controller;
                call_user_func_array([$controller, $this->method], $this->params);
            }
        }

        public function parseUrl() {
            if (!empty($_GET['uri'])){
                return $url = explode( '/' , rtrim($_GET['uri'], '/'));
            }
        }

        


    }



?>