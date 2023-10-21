<?php

require_once 'BaseModel.php';

class UserModel extends BaseModel {

    public function findUserById($id) {
        $sql = 'SELECT * FROM users WHERE id = '.$id;
        $user = $this->select($sql);

        return $user;
    }

    public function findUser($keyword) {
        $sql = 'SELECT * FROM users WHERE user_name LIKE %'.$keyword.'%'. ' OR user_email LIKE %'.$keyword.'%';
        $user = $this->select($sql);

        return $user;
    }

    /**
     * Authentication user
     * @param $userName
     * @param $password
     * @return array
     */
 
     public function auth($userName, $password) {
        // Kiểm tra xem đã tồn tại $_connection hay không
        if (isset(self::$_connection)) {
            // Xử lý và làm sạch dữ liệu đầu vào để ngăn chặn SQL injection
            $userName = mysqli_real_escape_string(self::$_connection, $userName);
            $password = mysqli_real_escape_string(self::$_connection, $password);
    
            // Mã hóa mật khẩu bằng MD5 (lưu ý: không được coi là an toàn, nên sử dụng phương pháp mã hóa mật khẩu mạnh mẽ hơn)
            $md5Password = md5($password);
    
            // Tạo câu truy vấn SQL để kiểm tra sự khớp của tên người dùng và mật khẩu
            $sql = 'SELECT * FROM users WHERE name = "' . $userName . '" AND password = "'.$md5Password.'"';
            
            // Thực thi truy vấn SQL và lấy kết quả
            $user = $this->select($sql);
            
            // Trả về kết quả
            return $user;
        } else {
            // In thông báo lỗi nếu kết nối chưa được thiết lập
            printf("Connection not established");
            exit();
        }
    }



    /**
     * Delete user by id
     * @param $id
     * @return mixed
     */
    public function deleteUserById($id) {
        $sql = 'DELETE FROM users WHERE id = '.$id;
        return $this->delete($sql);

    }

    /**
     * Update user
     * @param $input
     * @return mixed
     */
    public function updateUser($input) {
        $sql = 'UPDATE users SET 
                 name = "' . mysqli_real_escape_string(self::$_connection, $input['name']) .'", 
                 password="'. md5($input['password']) .'"
                WHERE id = ' . $input['id'];

        $user = $this->update($sql);

        return $user;
    }

    /**
     * Insert user
     * @param $input
     * @return mixed
     */
    public function insertUser($input) {
        $sql = "INSERT INTO `app_web1`.`users` (`name`, `password`) VALUES (" .
                "'" . $input['name'] . "', '".md5($input['password'])."')";

        $user = $this->insert($sql);

        return $user;
    }

    /**
     * Search users
     * @param array $params
     * @return array
     */

            //Example keyword: abcef%";TRUNCATE banks;##
      
    public function getUsers($params = []) {
        // Kiểm tra xem tham số `keyword` có trống không. Nếu không trống, thì thực hiện các bước sau:
        if (!empty($params['keyword'])) {
            $sql = 'SELECT * FROM users WHERE name LIKE ?';
            $stmt = self::$_connection->prepare($sql);
            $stmt->bind_param('s', $params['keyword']);

            $stmt->execute();
    
            $users = $stmt->get_result();
        } else {
            $sql = 'SELECT * FROM users';
            $users = $this->select($sql);
        }
        return $users;
    }

}