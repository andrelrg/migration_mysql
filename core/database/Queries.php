<?php 

    class Queries{
        public static $SELECT_CURRENT_TOKEN = "SELECT 
                token 
            FROM 
                migration_control 
            ORDER BY 
                id DESC 
            LIMIT 1";
    }