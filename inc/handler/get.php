<?php

if (!empty($_GET)) {
    if (isset($_GET['forum'])) {
        include 'forum/index.php';
    }
    if (isset($_GET['login'])) {
        include 'login.php';
    }
    if (isset($_GET['logout'])) {
        include 'logout.php';
    }
    if (isset($_GET['register'])) {
        include 'register.php';
    }
} else {
    include 'home.php';
}

?>