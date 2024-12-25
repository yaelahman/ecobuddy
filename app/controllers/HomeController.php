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
}
