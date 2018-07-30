<?php

    include "database/Queries.php";
    include "database/Mysql.php";
    
    class Migrator{

        private $mysql;

        function __construct(){
            $this->mysql = new Mysql();
        }

        public function migrate($token, $params): bool{
            $file = glob('../migrations/'. $token .'*.php')[0];
            
            if ($file){
                $starttime = microtime();
                include $file;

                $curr = $this->getCurrentToken();
                $to = intval($token);

                //Foward
                if ($curr > $to){
                    $this->runFoward($curr, $to);
                //Backwards
                }else{
                    $this->runBackwards($curr, $to);
                }

                $endtime = microtime(true);
                $timediff = $endtime - $starttime;
    
                return $this->setNewToken($token, $curr, $endtime);
                
            }

            return FALSE;
        }

        private function runFoward($from, $to){
            if($from == $to){
                return TRUE;
            }
            if ($this->mysql->execute(eval('$foward'))){
                return $this->runFoward(++$from, $to);
            }
            return FALSE;
            
        }
        private function runBackwards($from, $to){
            if($from == $to){
                return TRUE;
            }
            if ($this->mysql->execute(eval('$backwards'))){
                return $this->runFoward(--$from, $to);
            }
            return FALSE;
            
        }

        private function getCurrentToken(): int{
            $query = Queries::$SELECT_CURRENT_TOKEN;
            $curr = $this->mysql->select($query)->getFetchAll();
            if (empty($curr)){
                return NULL;
            }
        }

        private function setNewToken($token, $prev_token, $elapsed_time): bool{
            $query = Queries::$INSERT_CONTROL;
            $params = array(
                ':token'          => $token,
                ':prev_token'     => $prev_token,
                ':elapsed_time'   => $elapsed_time
            );
            return $this->mysql->insert($query);
        }

    }