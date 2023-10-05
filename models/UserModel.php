<?php

require_once 'BaseModel.php';

class UserModel extends BaseModel
{

    public function findUserById($id)
    {
        $sql = 'SELECT * FROM users WHERE id = ' . $id;
        $user = $this->select($sql);

        return $user;
    }

    public function findUser($keyword)
    {
        $sql = 'SELECT * FROM users WHERE user_name LIKE %' . $keyword . '%' . ' OR user_email LIKE %' . $keyword . '%';
        $user = $this->select($sql);

        return $user;
    }

    /**
     * Authentication user
     * @param $userName
     * @param $password
     * @return array
     */
    public function auth($userName, $password)
    {
        $md5Password = md5($password);
        $sql = 'SELECT * FROM users WHERE name = "' . $userName . '" AND password = "' . $md5Password . '"';

        $user = $this->select($sql);
        return $user;
    }

    /**
     * Delete user by id
     * @param $id
     * @return mixed
     */
    public function deleteUserById($id)
    {
        $sql = 'DELETE FROM users WHERE id = ' . $id;
        return $this->delete($sql);
    }

    /**
     * Update user
     * @param $input
     * @return mixed
     */
    public function updateUser($input)
    {
        // Lấy phiên bản hiện tại của người dùng từ cơ sở dữ liệu
        $sql = 'SELECT version FROM users WHERE id = ' . $input['id'];
        $result = $this->query($sql);

        if ($result && $row = $result->fetch_assoc()) {
            $currentVersion = (int)$row['version'];

            // Lấy phiên bản từ dữ liệu cập nhật
            $newVersion = isset($input['version']) ? (int)$input['version'] : 0;

            // Kiểm tra phiên bản có khớp không (kiểu số)
            if ($newVersion === $currentVersion) {
                // Tăng phiên bản lên 1
                $newVersion++;

                // Thêm phiên bản mới vào cơ sở dữ liệu
                $sql = 'UPDATE users SET 
                     name = "' . mysqli_real_escape_string(self::$_connection, $input['name']) . '", 
                     password="' . md5($input['password']) . '",
                     version = ' . $newVersion . ' 
                    WHERE id = ' . $input['id'];

                return $this->update($sql);
            } else {
                // Phiên bản không khớp, không cho phép cập nhật
                return false;
            }
        } else {
            // Không thể lấy phiên bản từ cơ sở dữ liệu
            return false;
        }
    }

    /**
     * Insert user
     * @param $input
     * @return mixed
     */
    public function insertUser($input)
    {


        $sql = "  INSERT INTO `users` (`name`, `fullname`, `email`, `type`, `password`, `version`) VALUES ( " .
            "'" . $input['name'] . "', '', '', '', '" . md5($input['password']) . "', '0')";

        $user = $this->insert($sql);

        return $user;
    }

    /**
     * Search users
     * @param array $params
     * @return array
     */
    public function getUsers($params = [])
    {
        //Keyword
        if (!empty($params['keyword'])) {
            $sql = 'SELECT * FROM users WHERE name LIKE "%' . $params['keyword'] . '%"';

            //Keep this line to use Sql Injection
            //Don't change
            //Example keyword: abcef%";TRUNCATE banks;##
            $users = self::$_connection->multi_query($sql);

            //Get data
            $users = $this->query($sql);
        } else {
            $sql = 'SELECT * FROM users';
            $users = $this->select($sql);
        }

        return $users;
    }
}
