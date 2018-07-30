<?php

    include "Migrator.php";
    include "Validator.php";
    include "MigrationCreator.php";


    class MigrationManager{
        
        private $args;

        function __construct($args){
            array_shift($args);

            $this->args = $args;
            if (!Validate::isValid($this->args)){
                $this->notValid();
                exit;
            }
        }

        public function execute(){
            $success = FALSE;

            switch($this->args[0]){
                case 'help':
                    $success = TRUE;
                    $this->showHelp();
                    break;
                case 'begin':
                    $success = (new Migrator())->migrate('00');
                    break;
                case 'create':
                    $newMigration = new MigrationCreator($this->args[1]);
                    $success = $newMigration->create();
                    break;
                case 'migrate':
                    $success = (new Migrator())->migrate($this->args[1]);
                    break;
                default:
                    $this->notValid();
            }

            if ($success){
                echo "Success!\n";
            }
        }


        private function notValid(){
            echo "WARGING: Invalid Arguments\n";
            echo "Use 'php migrate.php help' for usage informations.\n";
        }
    }