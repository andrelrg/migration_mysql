<?php

    $fowards = "CREATE TABLE IF NOT EXISTS migration_control (
        id INT(6) AUTO_INCREMENT PRIMARY KEY,
        token INT(6) NOT NULL,
        prev_token INT(6) NULL,
        name_files varchar(2000) NOT NULL,
        faked boolean DEFAULT FALSE,
        elapsed_time FLOAT(10,5) NOT NULL,
        execution_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    $backwards = "DROP TABLE migration_control";
    