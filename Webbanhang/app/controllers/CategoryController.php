<?php
// Require SessionHelper and other necessary files
require_once('app/config/database.php');
require_once('app/models/CategoryModel.php');
class CategoryController
{
private $categoryModel;
private $db;

public function __construct()
{
$this->db = (new Database())->getConnection();
$this->categoryModel = new CategoryModel($this->db);
}
public function list()
{
$categories = $this->categoryModel->getCategories();
include 'app/views/category/list.php';
}
public function add()
{
    $categories = (new CategoryModel($this->db))->getCategories();
    include_once 'app/views/category/add.php';
}
}
?>