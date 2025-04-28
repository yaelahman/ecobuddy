# EcoBuddy Framework Documentation

## Overview

EcoBuddy is a lightweight MVC PHP framework designed for rapid development of web applications. It features convention-based routing, middleware support, an ORM-like model system, and a simple view rendering engine.

## Table of Contents

1. [Installation](#installation)
2. [Directory Structure](#directory-structure)
3. [Configuration](#configuration)
4. [Routing](#routing)
5. [Controllers](#controllers)
6. [Models](#models)
7. [Views](#views)
8. [Middleware](#middleware)
9. [Database](#database)
10. [Examples](#examples)

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/yaelahman/ecobuddy.git
   ```

2. Navigate to the project directory:
   ```bash
   cd ecobuddy
   ```

3. Configure your web server to point to the project's root directory or use PHP's built-in server:
   ```bash
   php -S localhost:8000
   ```

## Directory Structure

```
ecobuddy-framework/
├── app/
│   ├── config/
│   │   ├── base.php
│   │   └── database.php
│   ├── controllers/
│   │   ├── Controller.php
│   │   ├── HomeController.php
│   │   ├── AuthController.php
│   │   └── EcoFacilityController.php
│   ├── models/
│   │   ├── Model.php
│   │   ├── User.php
│   │   ├── EcoCategory.php
│   │   ├── EcoFacilityStatus.php
│   │   ├── EcoUserTypes.php
│   │   └── EcoFacility.php
│   ├── routes/
│   │   └── middleware.php
│   └── views/
│       ├── layouts/
│       │   ├── header.phtml
│       │   └── footer.phtml
│       ├── home/
│       │   └── index.phtml
│       ├── user/
│       │   ├── index.phtml
│       │   ├── create.phtml
│       │   └── edit.phtml
│       ├── errors/
│       │   ├── 403.phtml
│       │   └── 404.phtml
│       ├── auth/
│       │   ├── component/
│       │   │   ├──script.phtml
│       │   │   └──style.phtml
│       │   ├── register.phtml
│       │   └── login.phtml
│       └── eco_facility/
│           ├── component/
│           │   ├──script.phtml
│           │   └──style.phtml
│           ├── index.phtml
│           ├── create.phtml
│           └── edit.phtml
├── assets/
│   ├── css/
│   ├── img/
│   ├── js/
│   ├── scss/
│   ├── style/
│   ├── template/
│   └── vendor/
├── ecobuddy.sqlite
└── index.php
```

## Configuration

### Base Configuration (app/config/base.php)

This file contains basic application settings:

```php
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
define("BASE_URL", "http://localhost:8000");
session_start();
```

### Database Configuration (app/config/database.php)

```php
<?php
class Database
{
    private static $instance = null;
    private $pdo;
    
    private function __construct()
    {
        try {
            $this->pdo = new PDO("sqlite:" . __DIR__ . "/../../ecobuddy.sqlite");
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Database Connection Error: " . $e->getMessage());
        }
    }
    
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance->pdo;
    }
}
```

## Routing

EcoBuddy uses a hybrid routing system that combines convention-based routing with explicit route definitions.

### Convention-Based Routing

URLs are automatically mapped to controllers and methods:

1. Format: `/controller-name/method-name/parameter1/parameter2/...`
2. Example: `/eco-facility/edit/5` maps to `EcoFacilityController->edit(5)`

#### URL to Controller Mapping Rules:

- First segment maps to a controller (kebab-case to PascalCase + "Controller")
  - `/eco-facility` → `EcoFacilityController`
- Second segment maps to a method (kebab-case to snake_case)
  - `/eco-facility/get-create` → `get_create()`
- If no second segment, defaults to `index()`
- Additional segments become method parameters

#### HTTP Method Support:

Method names can include HTTP verb prefixes:
- `get_create()` for GET requests to `/eco-facility/create`
- `post_create()` for POST requests to `/eco-facility/create`

### Explicit Routes (Legacy Support)

```php
<?php
// In app/routes/web.php
route('GET', '/admin/dashboard', function() {
    // Handler code
}, 'auth');
```

## Controllers

Controllers handle user requests and return responses. They extend the base `Controller` class.

### Base Controller

```php
<?php
class Controller
{
    protected function render($viewPath, $data = []) { /* ... */ }
    protected function component($viewPath, $script = true) { /* ... */ }
    protected function redirect($uri) { /* ... */ }
    protected function error404() { /* ... */ }
    protected function getViewPath($method = null) { /* ... */ }
}
```

### Creating a Controller

```php
<?php
require_once __DIR__ . '/Controller.php';

class ProductController extends Controller
{
    // Default method for /product
    public function index()
    {
        $this->render('product/index');
    }

    // GET handler for /product/create
    public function get_create()
    {
        $this->render('product/create');
    }

    // POST handler for /product/create
    public function post_create()
    {
        // Process form submission
        $this->redirect('/product');
    }

    // GET handler for /product/edit/1
    public function get_edit($id)
    {
        $this->render('product/edit', ['id' => $id]);
    }
}
```

### Controller Naming Convention

1. Class names: PascalCase with "Controller" suffix
2. Method names: 
   - snake_case or camelCase
   - Can have HTTP verb prefix (get_, post_, put_, delete_)
   - Map to kebab-case in URLs
3. Root controller: `HomeController` with `index()` method

## Models

Models provide an abstraction layer for database operations.

### Base Model

```php
<?php
class Model
{
    protected $table;
    protected $primaryKey = 'id';
    protected $db;

    public function __construct() { /* ... */ }
    public function all() { /* ... */ }
    public function find($id) { /* ... */ }
    public function where($column, $operator, $value) { /* ... */ }
    public function create($data) { /* ... */ }
    public function update($id, $data) { /* ... */ }
    public function delete($id) { /* ... */ }
}
```

### Creating a Model

```php
<?php
require_once __DIR__ . '/Model.php';

class Product extends Model
{
    protected $table = 'products';
    
    // Add custom methods specific to this model
    public function getActiveProducts()
    {
        return $this->where('status', '=', 'active');
    }
}
```

### Using Models

```php
<?php
$productModel = new Product();

// Get all products
$allProducts = $productModel->all();

// Find product by ID
$product = $productModel->find(5);

// Create new product
$productModel->create([
    'name' => 'Eco-friendly Water Bottle',
    'price' => 15.99,
    'description' => 'Reusable water bottle made from recycled materials'
]);

// Update product
$productModel->update(5, [
    'price' => 12.99
]);

// Delete product
$productModel->delete(5);
```

## Views

Views are PHP files with HTML markup that render the user interface.

### View Files

Views are stored in the `app/views` directory with `.phtml` extension.

Example view file (`app/views/eco_facility/create.phtml`):

```php
<h1>Create New Eco Facility</h1>
<form method="post" action="<?= BASE_URL ?>/eco-facility/create">
    <div class="form-group">
        <label for="title">Title</label>
        <input type="text" name="title" id="title" required>
    </div>
    <!-- More form fields -->
    <button type="submit" class="btn btn-primary">Create</button>
</form>
```

### Rendering Views

Use the `render` method in controllers:

```php
public function get_create()
{
    $this->render('eco_facility/create', [
        'categories' => $this->ecoCategoryModel->all()
    ]);
}
```

### Layouts

Views use layouts for common elements like headers and footers:

- `app/views/layouts/header.phtml`
- `app/views/layouts/footer.phtml`

### Components

Views can include component files for scripts and styles:

```php
public function index()
{
    $this->render('eco_facility/index', [
        'script' => $this->component('eco_facility'),
        'style' => $this->component('eco_facility', false)
    ]);
}
```

Component files are located at:
- `app/views/eco_facility/component/script.phtml`
- `app/views/eco_facility/component/style.phtml`

## Middleware

Middleware provides a mechanism for filtering HTTP requests.

### Defining Middleware

```php
<?php
// In app/routes/middleware.php
function middleware($name, $callback)
{
    global $middlewares;
    $middlewares[$name] = $callback;
}

// Define middleware
middleware('auth', function () {
    return isset($_SESSION['user_id']);
});

middleware('manager', function () {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'Manager';
});
```

### Using Middleware in Controllers

Use PHPDoc comments to apply middleware to controller methods:

```php
/**
 * Shows the form to create a new eco facility.
 * @middleware manager
 */
public function get_create()
{
    // Only accessible to managers
}
```

### Using Middleware in Routes

For explicit routes:

```php
route('GET', '/admin/dashboard', $callback, 'auth');
```

## Database

EcoBuddy uses PDO for database access with a singleton Database class.

### Query Examples

Using models:

```php
// Get facility by ID
$facility = $this->ecoFacilityModel->find($id);

// Create new facility
$this->ecoFacilityModel->create([
    'title' => $_POST['title'],
    'description' => $_POST['description']
]);

// Update facility
$this->ecoFacilityModel->update($id, [
    'title' => $_POST['title']
]);

// Delete facility
$this->ecoFacilityModel->delete($id);

// Custom query with conditions
$activeUsers = $this->userModel->where('status', '=', 'active');
```

## Examples

### Complete Controller Example

```php
<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/Product.php';

class ProductController extends Controller
{
    private $productModel;

    public function __construct()
    {
        $this->productModel = new Product();
    }

    public function index()
    {
        $products = $this->productModel->all();
        $this->render('product/index', ['products' => $products]);
    }

    /**
     * @middleware auth
     */
    public function get_create()
    {
        $this->render('product/create');
    }

    /**
     * @middleware auth
     */
    public function post_create()
    {
        $data = [
            'name' => $_POST['name'] ?? '',
            'price' => $_POST['price'] ?? 0,
            'description' => $_POST['description'] ?? ''
        ];

        if ($this->productModel->create($data)) {
            $this->redirect('/product');
        } else {
            echo "Error creating product.";
        }
    }

    /**
     * @middleware auth
     */
    public function get_edit($id)
    {
        $product = $this->productModel->find($id);
        if (!$product) {
            $this->error404();
        }
        $this->render('product/edit', ['product' => $product]);
    }

    /**
     * @middleware auth
     */
    public function post_edit($id)
    {
        $data = [
            'name' => $_POST['name'] ?? '',
            'price' => $_POST['price'] ?? 0,
            'description' => $_POST['description'] ?? ''
        ];

        if ($this->productModel->update($id, $data)) {
            $this->redirect('/product');
        } else {
            echo "Error updating product.";
        }
    }

    /**
     * @middleware auth
     */
    public function delete_product($id)
    {
        if ($this->productModel->delete($id)) {
            echo json_encode(["success" => "Product deleted successfully."]);
        } else {
            echo json_encode(["error" => "Error deleting product."]);
        }
    }
}
```

### Complete View Example

```php
<!-- app/views/product/edit.phtml -->
<div class="container">
    <h1>Edit Product</h1>
    
    <form method="post" action="<?= BASE_URL ?>/product/edit/<?= $product['id'] ?>">
        <div class="form-group">
            <label for="name">Product Name</label>
            <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>
        </div>
        
        <div class="form-group">
            <label for="price">Price</label>
            <input type="number" step="0.01" class="form-control" id="price" name="price" value="<?= htmlspecialchars($product['price']) ?>" required>
        </div>
        
        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3"><?= htmlspecialchars($product['description']) ?></textarea>
        </div>
        
        <button type="submit" class="btn btn-primary">Update Product</button>
        <a href="<?= BASE_URL ?>/product" class="btn btn-secondary">Cancel</a>
    </form>
</div>
```

## Best Practices

1. **Controller Organization**:
   - Keep related functionality in a single controller
   - Use HTTP verb prefixes for clarity
   - Follow RESTful conventions

2. **Model Design**:
   - One model per database table
   - Extend the base Model class
   - Add custom methods for complex queries

3. **View Structure**:
   - Use layouts for consistent page structure
   - Keep business logic out of views
   - Use components for reusable UI elements

4. **Security**:
   - Always use middleware for access control
   - Sanitize user input
   - Use prepared statements for all database queries (already implemented in Model class)

5. **Naming Conventions**:
   - Controllers: PascalCase with "Controller" suffix
   - Models: PascalCase, singular noun
   - Views: snake_case, organized by controller
   - Methods: snake_case with optional HTTP verb prefix
   - URLs: kebab-case

---
