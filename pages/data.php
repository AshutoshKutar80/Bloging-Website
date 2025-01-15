<?php
# Line 1: Class to handle user registration
class UserRegistration {
    private $conn; // Line 2: Property to hold the database connection

    // Line 3: Constructor to initialize the database connection
    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    // Line 4: Method to handle user signup (combines email check, upload, and insert)
    public function register($name, $dob, $email, $password, $profilePic) {
        // Line 5: Check if email already exists
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $this->redirectWithMessage("Email already exists", "signup.php");
                return;
            }
            $stmt->close();
        }

        // Line 6: Handle profile picture upload
        $uploadDir = '../image/';
        $filePath = $uploadDir . basename($profilePic['name']);
        $fileSizeLimit = 2 * 1024 * 1024; // 2MB limit

        if ($profilePic['size'] > $fileSizeLimit) {
            echo "Image size should be less than 2MB.";
            return;
        }

        if (!move_uploaded_file($profilePic['tmp_name'], $filePath)) {
            echo "Error uploading profile picture.";
            return;
        }

        // Line 7: Insert user data into the database
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (full_name, dob, email, password, profile_pic) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("sssss", $name, $dob, $email, $hashedPassword, $filePath);
            if ($stmt->execute()) {
                $this->redirectWithMessage("User Successfully Signed Up", "Login.php");
            } else {
                echo "Error: " . $this->conn->error;
            }
            $stmt->close();
        } else {
            echo "Error preparing statement: " . $this->conn->error;
        }
    }

    // Line 8: Method to redirect with a message
    private function redirectWithMessage($message, $url) {
        echo "
            <script>
                alert('$message');
                window.location.href = '$url';
            </script>
        ";
    }
}

# Line 9: Include the database connection
include("../conn/conn.php");

# Line 10: Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name']; // Line 11: Get name from POST request
    $dob = $_POST['dob']; // Line 12: Get date of birth from POST request
    $email = $_POST['email']; // Line 13: Get email from POST request
    $password = $_POST['password']; // Line 14: Get password from POST request
    $profilePic = $_FILES['profile-pic']; // Line 15: Get profile picture from POST request

    # Line 16: Initialize the UserRegistration class and call the register method
    $userRegistration = new UserRegistration($conn);
    $userRegistration->register($name, $dob, $email, $password, $profilePic);
}

# Line 17: Close the database connection
$conn->close();
?>