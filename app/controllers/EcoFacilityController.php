<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/EcoFacility.php';
require_once __DIR__ . '/../models/EcoCategory.php';

class EcoFacilityController extends Controller
{
    private $userModel;
    private $ecoFacilityModel;
    private $ecoCategoryModel;

    public function __construct()
    {
        $this->userModel = new User();
        $this->ecoFacilityModel = new EcoFacility();
        $this->ecoCategoryModel = new EcoCategory();
    }

    public function index()
    {
        $this->render('eco_facility/index', [
            'script' => $this->component('eco_facility')
        ]);
    }

    public function datatable()
    {
        // Capture DataTables parameters
        $start = isset($_GET['start']) ? (int)$_GET['start'] : 0;
        $length = isset($_GET['length']) ? (int)$_GET['length'] : 10;
        $search = isset($_GET['search']['value']) ? $_GET['search']['value'] : '';
        $orderColumnIndex = isset($_GET['order'][0]['column']) ? (int)$_GET['order'][0]['column'] : 0;
        $orderDirection = isset($_GET['order'][0]['dir']) ? $_GET['order'][0]['dir'] : 'DESC';

        // Define orderable columns (adjust based on your database schema)
        $columns = ['id', 'title', 'description', 'category', 'town', 'county', 'postcode'];
        $orderColumn = isset($columns[$orderColumnIndex]) ? $columns[$orderColumnIndex] : 'id';

        // Get data
        $ecoFacility = $this->ecoFacilityModel->getAll($start, $length, $search, $orderColumn, $orderDirection);
        $totalRecords = $this->ecoFacilityModel->getTotalRecords();
        $filteredRecords = !empty($search) ? $this->ecoFacilityModel->getFilteredRecords($search) : $totalRecords;

        // Prepare response
        $response = [
            "draw" => isset($_GET['draw']) ? (int)$_GET['draw'] : 1,
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $filteredRecords,
            "data" => []
        ];

        foreach ($ecoFacility as $facility) {
            $response['data'][] = [
                $facility['title'],
                $facility['description'],
                $facility['category'],
                $facility['town'],
                $facility['county'],
                $facility['postcode'],
                isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'Manager' ?
                    '
                    <div class="btn-group">
                        <a href="' . BASE_URL . "/eco-facility/edit/" . $facility['id'] . '" class="btn btn-warning btn-sm">Edit</a>
                        <button class="btn btn-danger btn-sm delete-button" data-id="' . $facility['id'] . '">Delete</button>
                    </div>
                ' : '-'
            ];
        }

        // Output JSON response
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'title' => $_POST['title'] ?? 0,
                'category' => 1,
                'description' => $_POST['description'] ?? 0,
                'houseNumber' => $_POST['houseNumber'] ?? 0,
                'streetName' => $_POST['streetName'] ?? 0,
                'town' => $_POST['town'] ?? 0,
                'county' => $_POST['county'] ?? 0,
                'postcode' => $_POST['postcode'] ?? 0,
                'lng' => $_POST['lng'] ?? 0,
                'lat' => $_POST['lat'] ?? 0,
                'contributor' => $_SESSION['user_id']
            ];

            // Validate data here (add your validation logic)

            // Insert the new eco facility record
            if ($this->ecoFacilityModel->create($data)) {
                $this->redirect('/eco-facility');
            } else {
                // Handle error (e.g., show an error message)
                echo "Error creating eco facility.";
            }
        }

        $this->render('eco_facility/create', [
            'category' => $this->ecoCategoryModel->all()
        ]);
    }

    public function edit($id)
    {
        // Fetch the existing eco facility data
        $facility = $this->ecoFacilityModel->getById($id);

        if (!$facility) {
            // Handle error if facility not found
            echo "Eco facility not found.";
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'title' => $_POST['title'] ?? $facility['title'],
                'category' => $_POST['category'] ?? $facility['category'],
                'description' => $_POST['description'] ?? $facility['description'],
                'town' => $_POST['town'] ?? $facility['town'],
                'county' => $_POST['county'] ?? $facility['county'],
                'postcode' => $_POST['postcode'] ?? $facility['postcode'],
                'contributor' => $_SESSION['user_id']
            ];

            // Validate data here (add your validation logic)

            // Update the eco facility record
            if ($this->ecoFacilityModel->update($id, $data)) {
                $this->redirect('/eco-facility');
            } else {
                // Handle error (e.g., show an error message)
                echo "Error updating eco facility.";
            }
        }

        $this->render('eco_facility/edit', [
            'ecoFacility' => $facility,
            'category' => $this->ecoCategoryModel->all()
        ]);
    }

    public function delete($id)
    {
        // Fetch the existing eco facility data
        $facility = $this->ecoFacilityModel->getById($id);

        if (!$facility) {
            // Handle error if facility not found
            echo json_encode(["error" => "Eco facility not found."]);
            return;
        }

        // Attempt to delete the eco facility record
        if ($this->ecoFacilityModel->delete($id)) {
            echo json_encode(["success" => "Eco facility deleted successfully."]);
            $this->redirect('/eco-facility');
        } else {
            // Handle error (e.g., show an error message)
            echo json_encode(["error" => "Error deleting eco facility."]);
        }
    }
}
