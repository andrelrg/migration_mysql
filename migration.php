<?php

    include "core/MigrationManager.php";

    $mmkt = new MigrationManager($argv);
    $mmkt->execute();
