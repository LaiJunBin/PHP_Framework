<?php
    class Response{
        public function __construct($res = null) {
            echo $res;
            return $this;
        }

        public function json($json_data){
            header('Content-Type: application/json');
            echo json_encode($json_data);
            return $this;
        }

        public function code($code=200){
            http_response_code($code);
            $this->log($code);
            return $this;
        }

        public function view($file,$params=[]){

            $file = str_replace('.','/',$file);
            $filenames = glob("./app/views/{$file}.lai.php");

            if(count($filenames) == 0){
                $filenames = glob("./app/views/{$file}.php");
                require($filenames[0]);
                return $this;
            } else {
                header('Content-Type: text/html;charset=UTF-8');
                // header('Content-Type: text/plain');
                $html_text = Lai::decryptFile($filenames[0], $params);

                echo $html_text;
                return $this;
            }

            throw new Exception('View Template Not Found.');
        }

        public function redirect($url){
            $url = explode('/',$url);
            clearEmpty($url);
            $url = implode('/',$url);

            header("location:{$url}");
            return $this;
        }

        public function log($status_code=200){
            $addr = $_SERVER['REMOTE_ADDR'];
            $port = $_SERVER['REMOTE_PORT'];
            $request_uri = $_SERVER['REQUEST_URI'];
            $log = $addr.':'.$port.' ['.$status_code.']: '.$request_uri;
            error_log($log);
        }
    }