<?php
    class Controller {

        protected function model($model = "") {
            if (!empty($model)){
                require_once('models/games.php');
                return new $model();
            }
        }

        protected function view($view = "", $data = []) {
            if (!empty($view)){
                require_once('views/'.$view.'.php');
            }
        }
    }

?>