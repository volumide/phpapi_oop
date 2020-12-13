<?php
    class Controller{
        private string $db_table;
        private object $conn;
        private array $fillables;
        private array $values = [];
        private $response;

        public function __construct(string $db, object $conn, array $fillables = []){
            $this->db_table = $db;
            $this->conn = $conn;
            $this->fillables =  $fillables;
        }
        
        // send data to database
        public function create():  string {
            error_reporting(0);
            $input = json_decode(file_get_contents("php://input"));

            foreach ($this->fillables as $fillable) {
                array_push($this->values, mysqli_real_escape_string($this->conn, $input->$fillable));
            }

            $values = '"' . join('", "', $this->values). '"';
            $fillable = join(',',  $this->fillables);

            $query = "INSERT INTO $this->db_table ($fillable)  VALUES ($values)";

            if ($this->conn->query($query)) {
                $this->response = array(
                    "status" => "success",
                    "message" => "message sent successfully",
                    "data" => $input
                );
                return json_encode($this->response, 0 , 200);
            }

            return json_encode(["message" => $this->conn->error], JSON_PRETTY_PRINT);
        }

        // get all data from database
        public function read(): string {
            $query = "SELECT * FROM $this->db_table ORDER BY id";
            $result = $this->conn->query($query);
            if(mysqli_num_rows($result) < 1 ){
                return json_encode(array(
                    "status" => "success",
                    "message" => "No content",
                ));
            }

            while($row = mysqli_fetch_assoc($result)){
                array_push($this->values, $row);
            }

            $this->response = array(
                "status" => "success",
                "data" => $this->values,
            );
            return json_encode($this->response, 0 , 200);
        }

        // get data by id
        public function readSingle(): string {
            if (!isset($_GET['id'])) {
               return $this->getMessage();
            }

            $id = $_GET['id'];
            $query = "SELECT * FROM $this->db_table WHERE id = $id LIMIT 1";
            $result = $this->conn->query($query);
            if (mysqli_num_rows($result) < 1) {
                return json_encode(array(
                    "status" => "fail",
                    "message" => "not found",
                ));
            }
            while($row = mysqli_fetch_assoc($result)){
                $this->values = $row;
            }

            $this->response = array(
                "status" => "success",
                "data" => $this->values,
            );
            return json_encode($this->response, 0 , 200);
        }

        public function update(): string {
            error_reporting(0);

            $seperator = '';
            $query = "UPDATE $this->db_table SET ";
            $input = json_decode(file_get_contents("php://input"));
            
            foreach ($this->fillables as $fillable) {
                $query .= $seperator. $fillable . "='" . mysqli_real_escape_string($this->conn, $input->$fillable). "'";
                $seperator = ',';
            }

            if (isset($_GET['update'])) {
                $id = $_GET['update'];
                $query .= "WHERE id = $id";
                $result = $this->conn->query($query)  ? json_encode([
                    "status"=> "success",
                    "message" => "updated successfully"
                ]) : json_encode(["mesaage" => $this->conn->error]);

                return $result ;
            }
           
        }

        public function delete(){
            if (isset($_GET['delete'])) {
                $id = $_GET['delete'];
                $query =  "DELETE FROM $this->db_table WHERE id = $id";
                $result = $this->conn->query($query) ? json_encode([
                    "status" => "success",
                    "message" => "successfully deleted"
                ])  : json_encode([
                    "status" => "fail",
                    "message" => $this->conn->error
                ]);

                return $result;
            }

        }
    }