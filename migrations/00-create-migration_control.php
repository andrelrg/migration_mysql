<?php

    $foward = "CREATE TABLE IF NOT EXISTS migration_control (
        id INT(6) AUTO_INCREMENT PRIMARY KEY,
        token VARCHAR(4) NOT NULL,
        prev_token VARCHAR(4) NULL,
        elapsed_time INT(3) NOT NULL,
        execution_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    $backwards = "DROP TABLE migration_control";