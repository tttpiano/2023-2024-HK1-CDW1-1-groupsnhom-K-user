<?php
session_start();

require_once 'models/UserModel.php';
$userModel = new UserModel();

$id = NULL;

// Lấy id của người dùng hiện tại từ phiên (session)
$currentUserId = !empty($_SESSION['id']) ? $_SESSION['id'] : null;

if (!empty($_GET['id'])) {
    $id = $_GET['id'];

    // Kiểm tra quyền truy cập
    if ($currentUserId !== $id) {
        // Người dùng không có quyền truy cập vào người dùng này
        header('Location: error.php');
        exit();
    }

    $user = $userModel->findUserById($id);
}

// Cập nhật hoặc thêm người dùng
if (!empty($_POST['submit'])) {
    if (!empty($id)) {
        $userModel->updateUser($_POST);
    } else {
        $userModel->insertUser($_POST);
    }
    header('location: list_users.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>User form</title>
    <?php include 'views/meta.php' ?>
</head>
<body>
<?php include 'views/header.php'?>
<div class="container">
    <?php if ($user || empty($id)) { ?>
        <div class="alert alert-warning" role="alert">
            User profile
        </div>
        <!-- Hiển thị thông tin người dùng -->
        <form method="POST">
            <input type="hidden" name="id" value="<?php echo $id ?>">
            <div class="form-group">
                <label for="name">Name</label>
                <span><?php if (!empty($user[0]['name'])) echo $user[0]['name'] ?></span>
            </div>
            <div class="form-group">
                <label for="password">Fullname</label>
                <span><?php if (!empty($user[0]['name'])) echo $user[0]['fullname'] ?></span>
            </div>
            <div class="form-group">
                <label for="password">Email</label>
                <span><?php if (!empty($user[0]['name'])) echo $user[0]['email'] ?></span>
            </div>
        </form>
    <?php } else { ?>
        <div class="alert alert-success" role="alert">
            User not found!
        </div>
    <?php } ?>
</div>
</body>
</html>
