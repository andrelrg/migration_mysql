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
                    $success = (new Migrator(TRUE))->migrate('0');
                    break;
                case 'create':
                    if (isset($this->args[1])){
                        $newMigration = new MigrationCreator($this->args[1]);
                        $success = $newMigration->create();
                    }else{
                        $this->notValid();
                    }
                    break;
                case 'migrate':
                    if (isset($this->args[1])){
                        if (isset($this->args[2]) && $this->args[2] == "fake"){
                            $success = (new Migrator(FALSE, TRUE))->migrate(
                                $this->args[1]
                            );
                        }else{
                            $success = (new Migrator(FALSE))->migrate(
                                $this->args[1]
                            );
                        }
                    }else{
                        $this->notValid();
                    }
                    break;
                default:
                    $this->notValid();
                    break;
            }

            if ($success){
                echo "Success!\n";
            }else{
                echo "There was a problem with your operation =/ Verify your migration file.\n";
            }
        }

        private function showHelp(){
            echo "Avaliable commands: \n\n";
            echo "begin: Create the migration control table\n\n";
            echo "create <migration_name>: Create a php file to be filled with your DB alterations.\n\n";
            echo "migrate <migration_token>: Execute the migrations fowards or backwards, depending on the given token.\n";
            echo "    Optional: fake: Fakes the execution for this token and save the pointer to him.\n";
            echo "    Usage example: php migration.php migrate 02 fake\n\n";
        }

        private function notValid(){
            echo "WARGING: Invalid Arguments\n";
            echo "Use 'php migration.php help' for usage informations.\n";
        }
    }