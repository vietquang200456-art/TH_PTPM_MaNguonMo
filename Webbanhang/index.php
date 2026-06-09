<?php
session_start();

// Include error handler
require_once 'app/helpers/ErrorHandler.php';

// Include database
require_once 'app/models/ProductModel.php';
require_once 'app/controllers/ProductApiController.php';
require_once 'app/controllers/CategoryApiController.php';

$url = $_GET['url'] ?? '';
$url = rtrim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$urlParts = explode('/', $url);

// Determine controller name (case-insensitive)
$controllerName = !empty($urlParts[0]) ? strtolower($urlParts[0]) : 'product';
$controllerName = ucfirst($controllerName) . 'Controller';

// Determine action (default is 'index')
$action = !empty($urlParts[1]) ? $urlParts[1] : 'index';

// Get additional parameters
$params = array_slice($urlParts, 2);

// Check if controller file exists
$controllerPath = 'app/controllers/' . $controllerName . '.php';
if (!file_exists($controllerPath)) {
    ErrorHandler::log("Controller not found: $controllerName");
    ErrorHandler::show404();
}

// Load controller
require_once $controllerPath;

// Check if controller class exists
if (!class_exists($controllerName)) {
    ErrorHandler::log("Class not found: $controllerName");
    ErrorHandler::show404();
}

// Instantiate controller
try {
    $controller = new $controllerName();
} catch (Exception $e) {
    ErrorHandler::log("Failed to instantiate controller: " . $e->getMessage());
    ErrorHandler::show500($e->getMessage());
}

// Check if action method exists
if (!method_exists($controller, $action)) {
    ErrorHandler::log("Action not found: $action in $controllerName");
    ErrorHandler::show404();
}

// Call action with parameters
try {
    call_user_func_array([$controller, $action], $params);
} catch (Exception $e) {
    ErrorHandler::log("Error executing action: " . $e->getMessage());
    ErrorHandler::show500($e->getMessage());
}
?>