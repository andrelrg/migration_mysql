<?php

    include "database/Queries.php";
    include "database/Mysql.php";
    
    class Migrator{

        private $mysql;

        function __construct(){
            $this->mysql = new Mysql();
        }

        public function migrate($token): bool{
            $starttime = microtime();
            $file = glob('migrations/'. $token .'*.php')[0];
            
            if ($file){
                include $file;

                $curr = $this->getCurrentToken();
                $to = intval($token);

                //Foward
                if ($curr > $to){
                    $this->runFoward($curr, $to);

                }else{
                    while($curr<=$to){
                        if (!$this->mysql->execute(eval('$backward')))
                            return FALSE;

                    }
                }
            }

            $endtime = microtime(true);
            $timediff = $endtime - $starttime;

            echo $timediff;
            return TRUE;
        }

        private function runFoward($from, $to){
            if($from == $to){
                return TRUE;
            }
            $this->mysql->execute(eval('$foward'));
            
        }

        private function getCurrentToken(): int{
            $query = Queries::$SELECT_CURRENT_TOKEN;
            $curr = $this->mysql->select($query)->getFetchAll();
            if (empty($curr)){
                return NULL;
            }

        }

    }