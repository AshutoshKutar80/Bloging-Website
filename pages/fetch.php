<?php
session_start(); // Start the session

include("../conn/conn.php");

class UserAuth {
    private $conn;

    // Constructor to initialize database connection
    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    // Method to login the user
    public function login($email, $password) {
        // Encapsulation: Securely interact with database
        $user = $this->getUserByEmail($email);

        if ($user) {
            if (password_verify($password, $user['password'])) {
                // Success: Set session and redirect
                $_SESSION['user_id'] = $user['id'];
                $this->redirectWithMessage('Login Successfully', 'update.php');
            } else {
                // Incorrect password
                $this->redirectWithMessage('Please enter the correct password', 'Login.php');
            }
        } else {
            // User does not exist
            $this->redirectWithMessage('User does not exist, please create an account', 'signup.php');
        }
    }

    // Private method to fetch user by email
    private function getUserByEmail($email) {
        // Abstraction: Database query is hidden
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Private method to redirect with a message
    private function redirectWithMessage($message, $url) {
        // Single Responsibility: Handle redirection with messages
        echo "
            <script>
                alert('$message');
                window.location.href = '$url';
            </script>
        ";
        exit();
    }
}

// Instantiate the UserAuth class
$auth = new UserAuth($conn);

// Process login if POST request is made
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    // Call the login method
    $auth->login($_POST['email'], $_POST['password']);
}
?>