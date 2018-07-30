<?php

    include "database/Queries.php";
    include "database/Mysql.php";
    
    class Migrator{

        private $fake;
        private $mysql;
        private $begin;
        private $name_files;

        function __construct($begin, $fake=FALSE){
            $this->begin = $begin;
            $this->fake = $fake;
            $this->mysql = new Mysql();
        }

        public function migrate($token): bool{
            

            $starttime = microtime();

            if ($this->begin){
                $curr = -1;
            }else{
                $curr = $this->getCurrentToken();
            }

            $to = intval($token);
            $success = FALSE;

            if($from === $to){
                echo "Your migration is already at this pointer";
                return TRUE;
            }

            //Foward
            if ($curr < $to){
                $success = $this->runFowards($curr, $to);
            //Backwards
            }else{
                $success = $this->runBackwards($curr, $to);
            }

            if (!$success){
                return FALSE;
            }
            $endtime = microtime(true);
            $timediff = round($this->microtime_diff($starttime, $endtime),5);

            echo "Elapsed Time: " . $timediff . "\n";

            return $this->setNewToken($token, $curr, $this->name_files, $this->fake, $timediff);

        }

        private function runFowards($from, $to){
            if($from == $to){
                return TRUE;
            }
            $from++;
            echo "Executing migration Token: $from\n";
            $file = glob('migrations/'. $from .'*.php')[0];
            if($file){
                $this->name_files .= $file . ",";
                include $file;

                $success = $this->fake;
                if (!$this->fake){
                    $success = $this->mysql->execute($fowards);
                }
                if ($success){
                    return $this->runFowards($from, $to);
                }
            }
            return FALSE;
            
        }
        private function runBackwards($from, $to){
            if($from == $to){
                return TRUE;
            }
            echo "Executing migration Token: $from\n";
            $file = glob('migrations/'. $from .'*.php')[0];
            if($file){
                $this->name_files .= $file . ",";
                include $file;

                $success = $this->fake;
                if (!$this->fake){
                    $success = $this->mysql->execute($backwards);
                }
                if ($success){
                    return $this->runBackwards(--$from, $to);
                }
            }
            return FALSE;
        }

        private function getCurrentToken(): int{
            $query = Queries::$SELECT_CURRENT_TOKEN;
            $curr = $this->mysql->select($query)->getFetchAll();
            if (empty($curr)){
                die("You should run the begin command before use migrations, see help\n");
            }
            return $curr[0]["token"];
        }

        private function setNewToken($token, $prev_token, $name_files, $faked, $elapsed_time): bool{
            $query = Queries::$INSERT_CONTROL;
            $params = array(
                ':token'          => $token,
                ':prev_token'     => $prev_token,
                ':name_files'     => $name_files,
                ':faked'          => $faked,
                ':elapsed_time'   => $elapsed_time
            );
            return $this->mysql->insert($query, $params);
        }

        private function microtime_diff($start, $end){
            list($start_usec, $start_sec) = explode(" ", $start);
            list($end_usec, $end_sec) = explode(" ", $end);
            $diff_sec = intval($end_sec) - intval($start_sec);
            $diff_usec = floatval($end_usec) - floatval($start_usec);
            return floatval($diff_sec) + $diff_usec;
        }

    }