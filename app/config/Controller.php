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
                    } elseif ($k == "last_played") {
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
    
    protected function verifyDate($date, $strict = true)
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

trait encryptDecrypt {
    /**
    * Input param
    * 1. action:encrypt/decrypt
    * 2. string: string need to encrypt or decrypt
    * 
    * return
    * converted string
    */
    protected function convert_string ($action, $string) {
        $output = '';
        $encrypt_method = "AES-256-CBC";
        $secret_key = 'eaiYYkYTysia2lnHiw0N0vx7t7a3kEJVLfbTKoQIx5o=';
        $secret_iv = 'eaiYYkYTysia2lnHiw0N0';
        // hash
        $key = hash('sha256', $secret_key);
        $initialization_vector = substr(hash('sha256', $secret_iv), 0, 16);
        if($string != '')
        {
            if($action == 'encrypt')
            {
                $output = openssl_encrypt($string, $encrypt_method, $key, 0, $initialization_vector);
                $output = base64_encode($output);
            } 
            if($action == 'decrypt') 
            {
                $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $initialization_vector);
            }
        }
        return $output;
    }
  }

?>