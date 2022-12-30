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

        protected function checkFormErrors($formData = []) {
            $res = true;
            if (!empty($formData)) {
                foreach ($formData as $k => $val) {
                    if ($k == "title" || $k == "platform" || $k == "rating" || $k == "review" || $k == "last_played") {
                        if (empty(trim($val))) {
                            $res = false;
                            break;
                        } else if ($k == "last_played") {
                            //TODO:: validate date
                            if (!$this->verifyDate(trim($val), true)) {
                                $res = false;
                                break;
                            }
                        }
                    }
                    
                }
            }
            return $res;
        }

        static public function verifyDate($date, $strict = true)
        {
            $dateTime = DateTime::createFromFormat('m/d/Y, G:i A', $date);
            // echo "test".$date;
            // print_r(DateTime::getLastErrors());
            // exit;
            if ($strict) {
                $errors = DateTime::getLastErrors();
                if (!empty($errors['warning_count'])) {
                    return false;
                }
            }
            return $dateTime !== false;
        }
    }

?>