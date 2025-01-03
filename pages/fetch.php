<?php
session_start(); // Start the session

include("../conn/conn.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {
            // Set session
            $_SESSION['user_id'] = $user['id'];
            echo "
                <script>
                    alert('Login Successfully');
                    window.location.href = 'update.php';
                </script>
            ";
            exit(); // Ensure no further code is executed after redirect
        } else {
            echo "
                <script>
                    alert('Please enter the correct password');
                    window.location.href = 'Login.php';
                </script>
            ";
        }
    } else {
        echo "
            <script>
                alert('User does not exist, please create an account');
                window.location.href = 'signup.php';
            </script>
        ";
    }
}
?>