<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/EcoFacility.php';

class AuthController extends Controller
{
    private $userModel;
    private $ecoFacilityModel;

    public function __construct()
    {
        $this->userModel = new User();
        $this->ecoFacilityModel = new EcoFacility();
    }

    public function login()
    {
        $this->render('auth/login', [
            'style' => $this->component('auth', false),
            'script' => $this->component('auth')
        ]);
    }

    public function loginAction() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Get the input values from the form
                $username = trim($_POST['username'] ?? '');
                $password = trim($_POST['password'] ?? '');
        
                // Validate input
                if (empty($username) || empty($password)) {
                    return $this->render('auth/login', [
                        'error' => 'Username and password are required.',
                        'style' => $this->component('auth', false),
                        'script' => $this->component('auth'),
                    ]);
                }
        
                // Verify credentials using the User model
                $user = $this->userModel->getUserByUsername($username);
        
                if ($user) {
                    // Debugging: Check retrieved user
                    // Uncomment the following line during development
                    // echo '<pre>'; print_r($user); echo '</pre>';
        
                    // Password validation
                    if ($password === $user['password']) { // Replace with `password_verify` for hashed passwords
                        // Set session or login logic here
                        session_start();
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['user_name'] = $user['username'];
                        $_SESSION['user_role'] = $user['role'];
        
                        // Debugging: Check session data
                        // Uncomment the following line during development
                        // echo '<pre>'; print_r($_SESSION); echo '</pre>';
        
                        // Redirect to dashboard or any other page
                        header('Location: /');
                        exit;
                    } else {
                        // Debugging: Password mismatch
                        error_log("Invalid password for username: $username");
                        return $this->render('auth/login', [
                            'error' => 'Invalid username or password.',
                            'style' => $this->component('auth', false),
                            'script' => $this->component('auth'),
                        ]);
                    }
                } else {
                    // Debugging: User not found
                    error_log("User not found: $username");
                    return $this->render('auth/login', [
                        'error' => 'Invalid username or password.',
                        'style' => $this->component('auth', false),
                        'script' => $this->component('auth'),
                    ]);
                }
            } catch (Exception $e) {
                // Catch unexpected errors and log them
                error_log("Error during login: " . $e->getMessage());
                return $this->render('auth/login', [
                    'error' => 'An unexpected error occurred. Please try again later. - ' . $e->getMessage(),
                    'style' => $this->component('auth', false),
                    'script' => $this->component('auth'),
                ]);
            }
        }        
    }

    public function logout()
    {
        session_start();
        session_destroy(); // Destroy all session data
        header('Location: /login'); // Redirect to login page
        exit;
    }
}
