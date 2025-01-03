<?php 
    include("../conn/conn.php");
    
    if($_SERVER['REQUEST_METHOD']=='POST'){
        $name = $_POST['name'];
        $dob = $_POST['dob'];
        $email = $_POST['email'];
        $password = $_POST['password'];


        $file_name = $_FILES['profile-pic']['name'];
        $tmp_name = $_FILES['profile-pic']['tmp_name'];
        $size = $_FILES['profile-pic']['size'];
        // $folder = 'C:/xampp/htdocs/social_network/image/'.$file_name;
         $upload_dir = '../image/';
        $folder = $upload_dir . basename($file_name);
        // $folder = 'uploads/'.$file_name;  // Save in the uploads directory



        $check = "select * from users where email = '$email'";
        $varify = mysqli_query($conn, $check);
        if (mysqli_num_rows($varify)) {
            echo "
                    <script>
                        alert('Email already exists');
                        window.location.href = 'signup.php';
                    </script>
                    ";
                exit; // Stops further execution after outputting the script
        }
        else{
            if($size > 2*1024*1024)
            {
                echo "image should be less 2MB";
            }

            else{
                if(move_uploaded_file($tmp_name, $folder)){
                    $hashedpassword = password_hash($password, PASSWORD_DEFAULT);
                    $sql = "insert into users(full_name, dob, email, password, profile_pic) values('$name', '$dob', '$email', '$hashedpassword', '$folder')";

                    if(mysqli_query($conn, $sql))
                    {
                        echo "
                            <script>
                                alert(`User Successfully SignUp.`);
                                window.location.href = 'Login.php';
                            </script>
                        ";
                    }
                    else{
                        echo "error :". mysqli_error($conn);
                    }
                }
                else{
                    echo "not inserted file";
                }
                
            }
        }
    }

    $conn->close();


?>