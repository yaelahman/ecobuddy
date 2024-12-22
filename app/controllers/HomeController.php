<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/User.php';

class HomeController extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function index()
    {
        $user = $this->userModel->getAll();
        $this->render('user/index', ['user' => $user]);
    }

    public function create()
    {
        echo "WOI";
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->userModel->create([
                'title' => $_POST['title'],
                'description' => $_POST['description']
            ]);
            $this->redirect('/');
        } else {
            $this->render('user/create');
        }
    }

    public function edit($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->userModel->update($id, [
                'title' => $_POST['title'],
                'description' => $_POST['description']
            ]);
            $this->redirect('/');
        } else {
            $task = $this->userModel->getById($id);
            if (!$task) {
                $this->error404();
            }
            $this->render('user/edit', ['task' => $task]);
        }
    }

    public function delete($id)
    {
        if (!$this->userModel->delete($id)) {
            $this->error404();
        }
        $this->redirect('/');
    }
}
