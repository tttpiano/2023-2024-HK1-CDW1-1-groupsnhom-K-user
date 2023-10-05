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
    // Kiểm tra xem có giá trị $_id hay không
    if (!empty($_id)) {
        // Lấy phiên bản người dùng từ dữ liệu POST
        $userVersion = $_POST['version'];
        // Kiểm tra xem phiên bản người dùng có khớp với phiên bản đã lấy được không
        if ($userVersion == $user[0]['version']) {
            // Cập nhật thông tin người dùng
            $_POST['version'] = $userVersion;
            $userModel->updateUser($_POST);
            header('location: list_users.php');
        } else {
            // Hiển thị cảnh báo nếu phiên bản không khớp
            echo "<script>alert('Hệ thống đã cập nhật phiên bản mới\\nXin vui lòng kiểm tra!');</script>";
        }
    } else {
        // Thêm mới người dùng nếu không có $_id
        $userModel->insertUser($_POST);
        header('location: list_users.php');
    }
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
            // Thêm 2 input để lấy ra giá trị value để so sánh phiên bảng
            <input type="hidden" name="id" value="<?php echo $_id ?>">
            <input type="hidden" name="version" value="<?= isset($user[0]['version']) ? $user[0]['version'] : 0 ?>">
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