<!DOCTYPE html>
<html>
    <head>
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
                <li><a href="index.php?login">Logga in</a></li>
            </ul>
        </nav>
        <main>
            <section>
                <?php include 'inc/handler/get.php'; ?>
            </section>
            <?php
                if (!isset($_COOKIE['cookie_disc'])) {
            ?>
                <div id="cookieDisclaimer">This website is using cookies to store information. By browsing this site you are agreeing to our usage of cookies. <span class="close" onclick="cookieDisc()">&times;</span></div>
            <?php
                }
            ?>
        </main>
        <footer>
            <div class="footerContent">a</div>
            <div class="footerContent">a</div>
            <div class="footerContent">a</div>
        </footer>
    </body>
</html>