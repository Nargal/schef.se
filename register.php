<?php

$auth = new EAuth($mysqli);

if ($auth->isLogged($mysqli)) {
    header('Location: http://localhost');
}

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    
    $register = $auth->register($username, $email, $password, $confirmPassword);
    
    $message = $register['message'];
    
}

?>

<section>
    <div id="register">
        <h2>Bli medlem</h2>
        <div><?= $message ?></div>
        <form method="POST">
            <input type="text" name="username" placeholder="Användarnamn">
            <input type="text" name="email" placeholder="E-post">
            <input type="password" name="password" placeholder="Lösenord">
            <input type="password" name="confirmPassword" placeholder="Bekräfta lösenord">
            <input type="submit" value="Registrera dig">
        </form>
    </div>
</section>