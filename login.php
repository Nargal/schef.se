<?php

$auth = new EAuth($mysqli);

if ($auth->isLogged($mysqli)) {
    header('Location: http://localhost');
}

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $remember = $_POST['remember'];
    
    
    $login = $auth->login($username, $password, $remember);
    
    if (!$login['error']) {
        setcookie(COOKIE_EAUTH, $login['hash'], $login['expire'], '/');
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    } else {
        $error = $login['message'];
    }
    
}

?>

<section>
    <div id="login">
        <h2>Logga in</h2>
        <div><?= $error ?></div>
        <form method="POST">
            <input type="text" name="username" placeholder="Användarnamn eller e-post">
            <input type="password" name="password" placeholder="Lösenord">
            <input type="submit" value="Logga in">
            <label><input type="checkbox" name="remember" value="1">Kom ihåg</label>
        </form>
    </div>
    <div id="register">
        <h2>Bli medlem</h2>
        <p>Genom att <a href="index.php?register">bli medlem</a> på schef.se så får du tillgång till fler tjänster.</p>
    </div>
</section>