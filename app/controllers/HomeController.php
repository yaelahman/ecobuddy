<?php

/**
 * HomeController is a controller that handles the home page of the application.
 * It extends the base Controller class and is responsible for fetching and rendering
 * user data on the home page.
 */
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/User.php';

class HomeController extends Controller
{
    /**
     * @var User The user model instance.
     */
    private $userModel;

    /**
     * HomeController constructor.
     * Initializes the user model instance.
     */
    public function __construct()
    {
        $this->userModel = new User();
    }

    /**
     * The index action is the entry point of the HomeController.
     * It fetches all users from the database and renders the home page view,
     * passing the users data to the view.
     */
    public function index()
    {
        $user = $this->userModel->all();
        $this->render('home/index', ['user' => $user]);
    }

    /**
     * Get data method - accessible via /home/get-data
     * @middleware auth
     */
    public function get_data()
    {
        $data = $this->userModel->all();
        // Return data as JSON
        header('Content-Type: application/json');
        echo json_encode(['data' => $data]);
    }

    /**
     * Example of a POST method
     * This will be accessible via POST to /home/save-profile
     */
    public function post_save_profile()
    {
        // Process POST data
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

        // Process data...

        // Redirect back to profile page
        $this->redirect('/home/profile');
    }

    /**
     * Example method with parameters - accessible via /home/profile/{id}
     * @middleware auth
     */
    public function profile($id)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            $this->error404();
        }

        $this->render('home/profile', ['user' => $user]);
    }
}
