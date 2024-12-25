<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/EcoFacility.php';

/**
 * AuthController handles user authentication and registration.
 */
class AuthController extends Controller
{
    private $userModel;
    private $ecoFacilityModel;

    /**
     * Initializes the controller with User and EcoFacility models.
     */
    public function __construct()
    {
        $this->userModel = new User();
        $this->ecoFacilityModel = new EcoFacility();
    }

    /**
     * Renders the login page.
     */
    public function login()
    {
        $this->render('auth/login', [
            'style' => $this->component('auth', false),
            'script' => $this->component('auth')
        ]);
    }

    /**
     * Handles the login form submission.
     */
    public function loginAction()
    {
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
                    if (password_verify($password, $user['password'])) { // Replace with `password_verify` for hashed passwords
                        // Set session or login logic here
                        session_start();
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['user_name'] = $user['username'];
                        $_SESSION['user_role'] = $user['role'];

                        // Debugging: Check session data
                        // Uncomment the following line during development
                        // echo '<pre>'; print_r($_SESSION); echo '</pre>';

                        // Redirect to dashboard or any other page
                        $this->redirect('/');
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

    /**
     * Handles user registration.
     */
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirmPassword'] ?? '';

            // Validate username
            if (empty($username)) {
                return $this->render('auth/register', [
                    'error' => 'Username is required.',
                    'style' => $this->component('auth', false),
                    'script' => $this->component('auth'),
                ]);
            }

            // Validate password
            if (empty($password) || strlen($password) < 12) {
                return $this->render('auth/register', [
                    'error' => 'Password must be at least 12 characters long.',
                    'style' => $this->component('auth', false),
                    'script' => $this->component('auth'),
                ]);
            }

            // Validate password strength
            if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/m', $password)) {
                return $this->render('auth/register', [
                    'error' => 'Password must contain uppercase letters, lowercase letters, numbers, and symbols.',
                    'style' => $this->component('auth', false),
                    'script' => $this->component('auth'),
                ]);
            }

            // Validate password match
            if ($password !== $confirmPassword) {
                return $this->render('auth/register', [
                    'error' => 'Passwords do not match.',
                    'style' => $this->component('auth', false),
                    'script' => $this->component('auth'),
                ]);
            }

            // Check if user already exists
            $existingUser = $this->userModel->getUserByUsername($username);
            if ($existingUser) {
                return $this->render('auth/register', [
                    'error' => 'User already exists.',
                    'style' => $this->component('auth', false),
                    'script' => $this->component('auth'),
                ]);
            }

            // Hash password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Create user
            $data = [
                'username' => $username,
                'password' => $hashedPassword,
                'userType' => 2, // Default role for new users
            ];
            if ($this->userModel->create($data)) {
                return $this->render('auth/login', [
                    'success' => 'User registered successfully. Please login.',
                    'style' => $this->component('auth', false),
                    'script' => $this->component('auth'),
                ]);
            } else {
                return $this->render('auth/register', [
                    'error' => 'Failed to register user.',
                    'style' => $this->component('auth', false),
                    'script' => $this->component('auth'),
                ]);
            }
        } else {
            return $this->render('auth/register', [
                'style' => $this->component('auth', false),
                'script' => $this->component('auth'),
            ]);
        }
    }

    /**
     * Handles user logout.
     */
    public function logout()
    {
        session_start();
        session_destroy(); // Destroy all session data
        $this->redirect('/');
        exit;
    }
}
