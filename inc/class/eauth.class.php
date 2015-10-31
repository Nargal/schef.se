<?php

class EAuth
{
    private $mysqli;
    
    public function __construct(mysqli $mysqli)
    {
        $this->mysqli = $mysqli;
    }
    
    public function login($username, $password, $remember = 0)
    {
        $return['error'] = true;
        
        if (strpos($username, '@')) {
            $validateEmail = $this->validateEmail($username);
        
            if ($validateEmail['error']) {
                $return['message'] = $validateEmail['message'];
                return $return;
            }
        }
        
        $uid = $this->getUserId(strtolower($username));
        
        if (!$uid) {
            $return['message'] = 'Det angivna användarnamnet exsisterar ej.';
            return $return;
        }
        
        $user = $this->getBaseUser($uid);
        
        if (!$user['isactive']) {
            $return['notactive'] = true;
            $return['uid'] = $user['uid'];
            return $return;
        }
        
        if (!password_verify($password, $user['password'])) {
            $return['message'] = 'Fel lösenord.';
            return $return;
        }
        
        $sessiondata = $this->addSession($user['uid'], $remember);
        
        $return['error'] = false;
        $return['message'] = 'Logged in';
        
        $return['uid'] = $user['uid'];
        $return['username'] = $user['username'];
        $return['email'] = $user['email'];
        
        $return['hash'] = $sessiondata['hash'];
        $return['expire'] = $sessiondata['expiretime'];
        
        return $return;
    }
    
    public function register($username, $email, $password, $confirmPassword)
    {
        $return['error'] = true;
        
        $validateUsername = $this->validateUsername($username);
        
        if ($validateUsername['error']) {
            $return['message'] = $validateUsername['message'];
            return $return;
        }
        
        $validateEmail = $this->validateEmail($email);

        if ($validateEmail['error']) {
            $return['message'] = $validateEmail['message'];
            return $return;
        }
        
        $validatePassword = $this->validatePassword($password);
        
        if ($validatePassword['error']) {
            $return['message'] = $validatePassword['message'];
            return $return;
        }
        
        if ($password !== $confirmPassword) {
            $return['message'] = 'Lösenorden matchar inte.';
            return $return;
        }
        
        if ($this->isUsernameTaken($username)) {
            $return['message'] = 'Det angivna användarnamnet är upptaget, försök med ett annat.';
            return $return;
        }
        
        if ($this->isEmailTaken($email)) {
            $return['message'] = 'Den angivna e-postadressen är upptagen, försök med en annan.';
            return $return;
        }
        
        $this->insertUser($username, $password, $email);
        
        $return['error'] = false;
        $return['message'] = 'Registrerad';
        
        return $return;
    }
    
    public function createPassword($uid, $password, $confirmPassword, $active = 1)
    {
        $return['error'] = false;
        
        if ($password != $confirmPassword) {
            $return['message'] = 'Lösenorden matchar inte.';
            $return['error'] = true;
            return $return;
        }
        
        $password = $this->getHash($password);
        
        $stmt = $this->mysqli->prepare("UPDATE users SET password = ?, isactive = ? WHERE id = ?");
        $stmt->bind_param('sis', $password, $active, $uid);
        $stmt->execute();
    }
    
    private function insertUser($username, $password, $email, $isactive = 1)
    {
        $return['error'] = true;
        
        $password = $this->getHash($password);
        $email = htmlentities(strtolower($email));
        
        $stmt = $this->mysqli->prepare("INSERT INTO users(username, password, email, isactive) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('sssi', $username, $password, $email, $isactive);
        $stmt->execute();
        
        $return['error'] = false;
        return $return;
    }
    
    public function deleteUser($uid)
    {
        $stmt = $this->mysqli->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param('s', $uid);
        $stmt->execute();
    }
    
    public function getUserId($username)
    {
        $stmt = $this->mysqli->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param('ss', $username, $username);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($id);
        
        if ($stmt->num_rows == 0) {
            return false;
        }
        
        $stmt->fetch();
        
        return $id;
    }
    
    private function getBaseUser($uid)
    {
        $stmt = $this->mysqli->prepare("SELECT username, password, email, isactive FROM users WHERE id = ?");
        $stmt->bind_param('s', $uid);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($username, $password, $email, $isactive);
        
        if ($stmt->num_rows == 0) {
            return false;
        }
        
        $stmt->fetch();
        
        $return['uid'] = $uid;
        $return['username'] = $username;
        $return['password'] = $password;
        $return['email'] = $email;
        $return['isactive'] = $isactive;
        
        return $return;
    }
    
    public function getUsername($uid)
    {
        $stmt = $this->mysqli->prepare("SELECT username FROM users WHERE id = ?");
        $stmt->bind_param('i', $uid);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($username);
        
        if ($stmt->num_rows == 0) {
            $return['username'] = 'Gäst';
            return $return;
        }
        
        $stmt->fetch();
        
        $return['username'] = $username;
        return $return;
    }

    private function validateUsername($username)
    {
        $return['error'] = true;
        
        if (strlen($username) < 3) {
            $return['message'] = 'Username too short';
            return $return;
        }
        
        if (strlen($username) > 30) {
            $return['message'] = 'Username too long';
            return $return;
        }
        
        $return['error'] = false;
        
        return $return;
    }
    
    private function validateEmail($email)
    {
        $return['error'] = true;
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $return['message'] = 'E-mail invalid.';
            return $return;
        }
        
        $return['error'] = false;
        
        return $return;
    }
    
    private function validatePassword($password)
    {
        $return['error'] = true;
        
        if (strlen($password) < 6) {
            $return['message'] = 'Password too short';
            return $return;
        }
        
        if (strlen($password) > 60) {
            $return['message'] = 'Password too long';
            return $return;
        }
        
        $return['error'] = false;
        
        return $return;
    }
    
    private function isUsernameTaken($username)
    {
        $stmt = $this->mysqli->prepare("SELECT username FROM users WHERE username = ?");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($username);
        
        if ($stmt->num_rows == 0) {
            return false;
        }
        
        $stmt->fetch();
        
        return true;
    }
    
    private function isEmailTaken($email)
    {
        $stmt = $this->mysqli->prepare("SELECT email FROM users WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($email);
        
        if ($stmt->num_rows == 0) {
            return false;
        }
        
        $stmt->fetch();
        
        return true;
    }
    
    public function isLogged()
    {
        return (isset($_COOKIE[COOKIE_EAUTH]) && $this->checkSession($_COOKIE[COOKIE_EAUTH]));
    }
    
    public function isAdmin()
    {
        
    }
    
    public function logout($hash)
    {
        return $this->deleteSession($hash);
    }
    
    private function addSession($uid, $remember)
    {
        $ip = $this->getIp();
        $user = $this->getBaseUser($uid);
        
        if (!$user) {
            return false;
        }
        
        $data['hash'] = sha1('asd768JHKAS&/%&MNasdnmp009as1(&/#' . microtime());
        $agent = $_SERVER['HTTP_USER_AGENT'];
        
        $this->deleteExistingSessions($uid, $ip);
        
        $timezone = +2;
        
        $data['logindate'] = date('Y-m-d H:i:s');
        
        //$data['expiredate'] = date('Y-m-d H:i:s', strtotime('+30 minutes'));
        
        $data['expiredate'] = date('Y-m-d H:i:s');
        $data['expiretime'] = 0;
        
        if ($remember) {
            $data['expiredate'] = date('Y-m-d H:i:s', strtotime('+1 month'));
            $data['expiretime'] = strtotime($data['expiredate']);
        }
        
        $data['cookie_crc'] = sha1($data['hash'] . 'asd768JHKAS&/%&MNasdnmp009as1(&/#');
        
        $stmt = $this->mysqli->prepare("INSERT INTO sessions(uid, hash, logindate, expiredate, ip, agent, cookie_crc) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('issssss', $uid, $data['hash'], $data['logindate'], $data['expiredate'], $ip, $agent, $data['cookie_crc']);
        $stmt->execute();
        
        return $data;
    }
    
    private function deleteExistingSessions($uid, $ip)
    {
        $stmt = $this->mysqli->prepare("DELETE FROM sessions WHERE uid = ? AND ip = ?");
        $stmt->bind_param('is', $uid, $ip);
        $stmt->execute();
    }
    
    private function deleteSession($hash)
	{
        $stmt = $this->mysqli->prepare("DELETE FROM sessions WHERE hash = ?");
        $stmt->bind_param('s', $hash);
        $stmt->execute();
    }
    
    public function checkSession($hash)
    {
        $stmt = $this->mysqli->prepare("SELECT id, uid, logindate, expiredate, ip, agent, cookie_crc FROM sessions WHERE hash = ?");
        $stmt->bind_param('s', $hash);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($id, $uid, $logindate, $expireDate, $db_ip, $agent, $cookie_crc);
        $stmt->fetch();
        
        $ip = $this->getIp();
        
        if ($stmt->num_rows == 0) {
            return false;
        }
        
        if ($ip != $db_ip) {
            return false;
        }
        
        /*
        $currentDate = strtotime(date('Y-m-d H:i:s'));
        $expireDate = strtotime($expireDate);
        
        if ($currentDate > $expireDate) {
            $this->deleteExistingSessions($uid, $ip);
            return false;
        }
        */
        
        if ($cookie_crc == sha1($hash . 'asd768JHKAS&/%&MNasdnmp009as1(&/#')) {
            return true;
        }
        
        return false;
    }
    
    public function getHash($password)
	{
		return password_hash($password, PASSWORD_BCRYPT, ['cost' => BCRYPT_COST]);
	}
    
    private function getIp()
    {
        $ip = getenv('HTTP_CLIENT_IP')?:
              getenv('HTTP_X_FORWARDED_FOR')?:
              getenv('HTTP_X_FORWARDED')?:
              getenv('HTTP_FORWARDED_FOR')?:
              getenv('HTTP_FORWARDED')?:
              getenv('REMOTE_ADDR');
        
        return $ip;
    }
    
    public function getRandomKey($length = 20)
	{
		$chars = "A1B2C3D4E5F6G7H8I9J0K1L2M3N4O5P6Q7R8S9T0U1V2W3X4Y5Z6a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6q7r8s9t0u1v2w3x4y5z6";
		$key = "";
        
		for ($i = 0; $i < $length; $i++) {
			$key .= $chars{mt_rand(0, strlen($chars) - 1)};
		}
        
		return $key;
	}
    
    public function getSessionHash()
    {
        return $_COOKIE[COOKIE_EAUTH];
    }
}

?>