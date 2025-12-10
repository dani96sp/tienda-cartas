<?php
// auth.php
session_start();
require_once 'db.php';

class Auth {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }

    public function register($username, $password) {
        // Simple validation
        if (empty($username) || empty($password)) {
            return ['success' => false, 'message' => 'Please fill in all fields.'];
        }

        // Check if user exists
        $stmt = $this->db->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->rowCount() > 0) {
            return ['success' => false, 'message' => 'Username already taken.'];
        }

        // Hash password
        $hash = password_hash($password, PASSWORD_DEFAULT);

        // Insert user
        try {
            $stmt = $this->db->prepare("INSERT INTO users (username, password_hash) VALUES (?, ?)");
            $stmt->execute([$username, $hash]);
            return ['success' => true, 'message' => 'Registration successful! Please login.'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Registration failed: ' . $e->getMessage()];
        }
    }

    public function login($username, $password) {
        $stmt = $this->db->prepare("SELECT id, username, password_hash FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            return ['success' => true, 'message' => 'Login successful!'];
        } else {
            return ['success' => false, 'message' => 'Invalid credentials.'];
        }
    }

    public function logout() {
        session_destroy();
        return ['success' => true, 'message' => 'Logged out.'];
    }

    public static function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    public static function getCurrentUser() {
        if (self::isLoggedIn()) {
            return [
                'id' => $_SESSION['user_id'],
                'username' => $_SESSION['username']
            ];
        }
        return null;
    }
}

// Handle requests if accessed directly via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    $auth = new Auth();
    $response = ['success' => false, 'message' => 'Invalid action'];

    if ($_POST['action'] === 'register') {
        $response = $auth->register($_POST['username'] ?? '', $_POST['password'] ?? '');
    } elseif ($_POST['action'] === 'login') {
        $response = $auth->login($_POST['username'] ?? '', $_POST['password'] ?? '');
    } elseif ($_POST['action'] === 'logout') {
        $response = $auth->logout();
    }

    echo json_encode($response);
    exit;
}
