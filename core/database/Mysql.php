<?php

    class Mysql{
        private $connectionInfo;
        private $pdo;
        private $conString = "mysql:host=%s;port=%s;dbname=%s";
        public $result;
        
        function __construct(){
            $str  = file_get_contents(dirname(__FILE__).'/../../db_config.json');
            
            $this->connectionInfo = json_decode($str, true);
            $this->connect();
        }

        private function connect(){
            $conString = sprintf(
                $this->conString,
                $this->connectionInfo['host'],
                $this->connectionInfo['port'],
                $this->connectionInfo['database']
            );
            $this->pdo = new PDO(
                $conString, 
                $this->connectionInfo['user'],
                $this->connectionInfo['password']
            );
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        public function select($query, ...$args): Mysql{
            $query = vsprintf($query, $args);
            try {
                $stmt = $this->pdo->prepare($query);
                $stmt->execute();
            }catch(Exception $e) {
                echo 'Exception -> ';
                var_dump($e->getMessage());
            }
            $this->result = $stmt;
            return $this;
        }

        public function getFetchAll(): array{
            return $this->result->fetchAll(PDO::FETCH_ASSOC);
        }

        public function execute($query): bool{
            try{
                $this->pdo->exec($query);
            }catch(PDOException $e){
                echo "ERRO: ". $e->getMessage();
                return FALSE;
            }

            return TRUE;

        }

        public function insert($query, $arrArgs): bool{
            try {
                $stmt = $this->pdo->prepare($query);
                $stmt->execute($arrArgs);
            } catch(PDOException $e) {
                echo "ERRO: ". $e->getMessage();
                return FALSE;
            }
            return TRUE;
        }

        public function close(){
            $this->pdo = NULL;
        }


    }
