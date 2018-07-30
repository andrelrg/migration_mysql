<?php

    class Validate{
        public static function isValid($args): bool{
            $optional = array(1);
            $rules = array(
                function ($arg){
                    return in_array($arg, array('begin', 'migrate', 'help'));
                },
                function ($arg){
                    return true;
                },
                function ($arg){
                    return true;
                }
            );

            foreach($args as $key=>$val){
                if ($key == 0) continue;

                if (!isset($rules[$key]) && !in_array($key, $optional)) return false;

                if (!$rules[$key]($val)) return false;
            }
            return true;
        }

    }