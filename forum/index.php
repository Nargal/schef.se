<link rel="stylesheet" type="text/css" href="forum/inc/style/schef.se_forum_v0.1.css">

<section>
    <?php
    $auth = new EAuth($mysqli);
    
    if (!$auth->isLogged($mysqli)) {
        echo 'Du måste vara inloggad för att komma åt forumet.';
    } else {
    
    ?>
    
    <div class="forumContainer"></div>
    <div class="forumContainer"></div>
    <div class="forumContainer"></div>
    
    <?php } ?>
</section>