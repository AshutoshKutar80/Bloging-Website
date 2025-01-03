<?php
session_start();

// Destroy the session
// session_unset();
session_destroy();

// Redirect to login page
echo "<script>alert('Logout Successfully.'); window.location.href = 'Login.php';</script>";
exit();
?>