
<?php
    session_start();

    class Authentication{
        private $conn;

        public  function __construct($servername, $username, $password, $dbname) {
            $this->conn = new mysqli($servername, $username, $password, $dbname);
            if ($this->conn->connect_error) {
                die("Connection failed: " . $this->conn->connect_error);
            }
        }
        public function login($username, $password) {
            $selectSql = "SELECT `username`, `password`,`authority` FROM users WHERE username = ? AND `password` = ?";  
            $selectStmt = $this->conn->prepare($selectSql);
            $selectStmt->bind_param('ss', $username, $password );
            $selectStmt->execute();
            $result = $selectStmt->get_result();
            if ($result->num_rows === 0) {
                return false;}
            else{
                $user = $result->fetch_assoc();
                $_SESSION['userDet'] = $user;
                $_SESSION['loggedin'] = true;
                return true;
                }
         }
         public function isLoggedIn() {
            return isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
        }

        public function sendUser(){
            return $_SESSION['userDet']['username'];
        }
    
        public function checkAuthority($requiredLevel) {
            return $this->isLoggedIn() && $_SESSION['userDet']['authority'] >= $requiredLevel;
        }
    
        public function logout() {
            session_unset();
            session_destroy();
            header("Location: Login.php");
            exit();
        }
    }
?>