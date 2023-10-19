<?php
require_once 'models/UserModel.php';
$userModel = new UserModel();

$user = NULL; //Add new user
$id = NULL;
//neu khong trong thi xoa bang the post
if (!empty($_POST['submit'])) {
    $id = $_GET['id'];
    $userModel->deleteUserById($id); //Delete existing user
}
header('location: list_users.php');