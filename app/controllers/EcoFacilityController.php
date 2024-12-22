<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/EcoFacility.php';

class EcoFacilityController extends Controller
{
    private $userModel;
    private $ecoFacilityModel;

    public function __construct()
    {
        $this->userModel = new User();
        $this->ecoFacilityModel = new EcoFacility();
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
        $orderDirection = isset($_GET['order'][0]['dir']) ? $_GET['order'][0]['dir'] : 'ASC';

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
                $facility['postcode']
            ];
        }

        // Output JSON response
        header('Content-Type: application/json');
        echo json_encode($response);
    }
}
