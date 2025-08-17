<?php
class AuthController extends Controller {
  private function throttle_check($email) {
    $pdo = Database::pdo();
    $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    $stmt = $pdo->prepare('SELECT * FROM login_attempts WHERE email=? AND ip=? LIMIT 1');
    $stmt->execute([$email, $ip]);
    $row = $stmt->fetch();
    $limit = 5; // attempts
    $cooldown = 300; // 5 minutes
    if ($row) {
      $last = strtotime($row['last_attempt']);
      if ($row['attempts'] >= $limit && (time() - $last) < $cooldown) {
        return false;
      }
    }
    return true;
  }
  private function throttle_hit($email, $success) {
    $pdo = Database::pdo();
    $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    $stmt = $pdo->prepare('SELECT * FROM login_attempts WHERE email=? AND ip=? LIMIT 1');
    $stmt->execute([$email, $ip]);
    $row = $stmt->fetch();
    if ($success) {
      if ($row) {
        $pdo->prepare('DELETE FROM login_attempts WHERE id=?')->execute([$row['id']]);
      }
      return;
    }
    if ($row) {
      $pdo->prepare('UPDATE login_attempts SET attempts=attempts+1, last_attempt=NOW() WHERE id=?')->execute([$row['id']]);
    } else {
      $pdo->prepare('INSERT INTO login_attempts(email, ip, attempts, last_attempt) VALUES(?,?,1,NOW())')->execute([$email, $ip]);
    }
  }
// test function login1
  public function login1(){
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      //verify_csrf();
      $email = trim($_POST['email'] ?? '');
      $pass  = $_POST['password'] ?? '';

      if (!$this->throttle_check($email)) {
        $this->view('auth/login', ['error' => 'Too many attempts. Try again later.']);
        return;
      }

      $userModel = new User();
      $user = $userModel->findByEmail($email);


      
      $pass = 'Admin@123'; // what you type in the login form
$hash = '$2y$10$W8bI6z1pT8hj6x5bU2nO0uA2L/EdjFv0pZ2tZx7f6aYH3yQ/6kWbC';

var_dump(password_verify($pass, $hash));
      $ok = $user && $user['status']==='active' && password_verify($pass, $user['password_hash']);
      $this->throttle_hit($email, $ok);

      if ($ok) {
        regenerate_session();
        $_SESSION['user'] = ['id'=>$user['id'], 'name'=>$user['name'], 'email'=>$user['email']];
        redirect(); exit;
      }
      $this->view('auth/login', ['error' => 'Invalid credentials']);
      return;
    }
    $this->view('auth/login');
  }

  
public function logout(){ session_destroy(); redirect('auth/login'); }




public function login() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        //verify_csrf();
        $email = trim($_POST['email'] ?? '');
        $pass  = $_POST['password'] ?? '';

        // Throttle check
        if (!$this->throttle_check($email)) {
            $this->view('auth/login', ['error' => 'Too many attempts. Try again later.'], 'auth');
            return;
        }

        $userModel = new User();
        $user = $userModel->findByEmail($email);

        // Debug: check if user exists
        if (!$user) {
            $this->throttle_hit($email, false);
            $this->view('auth/login', ['error' => "User not found for email: $email"], 'auth');
            return;
        }

        // Debug: check password
        $isPasswordCorrect = password_verify($pass, $user['password_hash']);
        if (!$isPasswordCorrect) {
            $this->throttle_hit($email, false);
            $this->view('auth/login', ['error' => 'Incorrect password.'], 'auth');
            return;
        }

        // Check user status
        if ($user['status'] !== 'active') {
            $this->throttle_hit($email, false);
            $this->view('auth/login', ['error' => 'User account is not active.'], 'auth');
            return;
        }

        // Success: log in
        $this->throttle_hit($email, true);
        regenerate_session();
        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email']
        ];
        redirect(); // goes to base_url
        exit;
    }

    // GET request: show login form
    $this->view('auth/login', [], 'auth');
}



}
