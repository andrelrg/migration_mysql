<?php

    class MigrationCreator{
        
        private $file;
        private $name;

        function __construct($name){
            $this->name = $name;
            $this->file = <<<EOF
<?php
    //Creation Date %s
    
    //Insert Here your migration code, lika an alter table, or a drop collumn, example: 'ALTER TABLE example ADD example_column INT;'.
    \$fowards = "";

    //Insert Here the opposite of your migration code, if your migration were the example above, you backwards code will be: 'ALTER TABLE example DROP example_column;'
    \$backwards = "";
EOF;

            date_default_timezone_set('America/Sao_Paulo');
        }

        public function create(){
            $token = $this->getNextFileToken();
            $date = new DateTime();

            $newMigration = 'migrations/' . $token . '-' . $this->name . '.php';

            try{
                $handle = fopen($newMigration, 'w') or die('Cannot create File:  '.$newMigration);
                fwrite($handle, sprintf($this->file, $date->format('Y-m-d H:i:s')));
            } catch(Exception $e){
                echo $e->getMessage();
                return FALSE;
            }
            echo "Your Migration is Ready to be filled with your commands.\nOpen using: vi $newMigration\n";
            return TRUE;
        }

        private function getNextFileToken(){
            $file = end(glob('migrations/*.php'));
            preg_match('/\d{1,4}/', $file, $matches);

            $newToken = intval($matches[0]);
            return ++$newToken;
        }
    }