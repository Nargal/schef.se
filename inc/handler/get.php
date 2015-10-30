<?php

if (!empty($_GET)) {
    if (isset($_GET['forum'])) {
        include 'forum.php';
    }

    if (isset($_GET['login'])) {
        include 'login.php';
    }
}

?>