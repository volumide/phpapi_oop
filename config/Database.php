<?php
    class Database{
        private static string $host = 'localhost';
        private static string $db_name = 'youthapi';
        private static string $username = 'root';
        private static string $password = '';
        private static $conn;

        public function connect(){
            self::$conn = new mysqli(self::$host, self::$username, self::$password, self::$db_name);

            if (self::$conn->connect_errno) {
                die('Connection error:  {$this->conn->connect_errno}');
            }
            return self::$conn;
        }

    }

?>