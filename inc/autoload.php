<?php

spl_autoload_register(function ($className) {
    include 'class/' . $className . '.class.php';
});

?>
