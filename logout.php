<?php
    $auth = new EAuth($mysqli);
    $auth->logout($_COOKIE[COOKIE_EAUTH]);
    
    unset($_COOKIE[COOKIE_EAUTH]);
    setcookie(COOKIE_EAUTH, null, -1, '/');
    
    header('Location: ' . $_SERVER['HTTP_REFERER']);
?>