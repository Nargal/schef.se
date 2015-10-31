<?php

include_once 'inc/class/eauth.class.php';
include_once 'inc/connect.php';

?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
        <link rel="stylesheet" type="text/css" href="inc/style/schef.se_v0.1.css">
        <script type="text/JavaScript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <script type="text/JavaScript" src="inc/js/cookie.js"></script>
        <script type="text/JavaScript" src="inc/js/nav.js"></script>
    </head>
    <body>
        <header>
            <a href="/"><img src="inc/img/logo.png" alt="" /></a>
        </header>
        <nav>
            <ul>
                <li><a href="./">Hem</a></li>
                <li>Meny 1
                    <ul>
                        <li>Submeny 1</li>
                    </ul>
                </li>
                <li>Meny 2
                    <ul>
                        <li>Submeny 1
                            <ul>
                                <li>Sub-submeny 1</li>
                                <li>Sub-submeny 2</li>
                                <li>Sub-submeny 3</li>
                            </ul>
                        </li>
                        <li>Submeny 2
                            <ul>
                                <li>Sub-submeny 1</li>
                                <li>Sub-submeny 2</li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li>Meny 3</li>
                <li><a href="index.php?forum">Forum</a></li>
                <?php
                    if (isset($_COOKIE['schefse_eauth'])) {
                        echo '<li><a href="index.php?logout">Logga ut</a></li>';
                    } else {
                        echo '<li><a href="index.php?login">Logga in</a></li>';
                    }
                ?>
            </ul>
        </nav>
        <main>
            <?php include 'inc/handler/get.php'; ?>
            <?php
                if (!isset($_COOKIE['cookie_disc'])) {
            ?>
                <div id="cookieDisclaimer">Denna webbplats använder cookies för att lagra information. Genom att fortsätta använda denna webbplats godkänner du detta.<span class="close" onclick="cookieDisc()">&times;</span></div>
            <?php
                }
            ?>
        </main>
        <footer>
            <div class="footerContent">
                <h3>Rubrik</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
            </div>
            <div class="footerContent">
                <h3>Rubrik</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
            </div>
            <div class="footerContent">
                <h3>Rubrik</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
            </div>
        </footer>
    </body>
</html>