<?php
session_start();
require_once 'models/UserModel.php';
$userModel = new UserModel();

$user = NULL;
$_id = NULL;

if (!empty($_GET['id'])) {
    $_id = $_GET['id'];
    $user = $userModel->findUserById($_id);
}


if (!empty($_POST['submit'])) {

    if (!empty($_id)) {
        $userModel->updateUser($_POST);
    } else {
        $userModel->insertUser($_POST);
    }
    header('location: list_users.php');
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>Form người dùng</title>
    <?php include 'views/meta.php' ?>
</head>

<body>
    <?php include 'views/header.php' ?>
    <div class="container">
        <?php if ($user || !isset($_id)) { ?>
            <div class="alert alert-warning" role="alert">
                Form người dùng
            </div>
            <form method="POST">

                <div class="form-group">
                    <label for="name">Tên</label>
                    <input class="form-control" name="name" placeholder="Tên" value='<?php if (!empty($user[0]['name'])) echo $user[0]['name'] ?>'>
                </div>
                <div class="form-group">
                    <label for="password">Mật khẩu</label>
                    <input type="password" name="password" class="form-control" placeholder="Mật khẩu">
                </div>

                <button type="submit" name="submit" value="submit" class="btn btn-primary">Gửi</button>
            </form>

        <?php } else { ?>
            <div class="alert alert-success" role="alert">
                Không tìm thấy người dùng!
            </div>
        <?php } ?>
    </div>
</body>

</html>