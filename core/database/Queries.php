<?php 

    class Queries{
        public static $SELECT_CURRENT_TOKEN = "SELECT 
                token 
            FROM 
                migration_control 
            ORDER BY 
                id DESC 
            LIMIT 1";
        
        public static $INSERT_CONTROL = "INSERT 
            INTO 
                migration_control
                (token, prev_token, elapsed_time)
            VALUES
                (:token, :prev_token, :elapsed_time)";
    }