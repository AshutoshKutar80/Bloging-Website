<!DOCTYPE html>
<html lang="en">

<head>
    <title>SignUp Page</title>
    <link rel="stylesheet" href="../css/style.css" />
    <!-- <link rel="shortcut icon" href="../assests/logo.png" type="image/x-icon">k -->
</head>

<body>
    <div class="container">
        <div class="heading">
            <h1>Join Social Network</h1>
        </div>

        <form action="data.php" method="post" id="validation" enctype="multipart/form-data">
            <div class="row1">
                <img src="../assests/picc.png" alt="Pic Here" height="60px" width="65px" id="profile-pic" />
                <label for="path">Upload Profile Pic</label>
                <input type="file" name="profile-pic" id="path" accept="image/jpg, image/png, image/jpeg" />
            </div>

            <div class="row2">
                <div class="row11">
                    <label for="name">Full Name :</label>
                    <input type="text" placeholder=" John Doe" name="name" id="name" />
                </div>
                <div class="row11">
                    <label for="date">Date of Birth :</label>
                    <input type="date" name="dob" id="date" />
                </div>
                <div class="row11">
                    <label for="email">Email Address :</label>
                    <input type="email" placeholder=" Email here" name="email" id="email" />
                </div>
            </div>

            <div class="row3">
                <div class="row21">
                    <label for="password">Password :</label>
                    <input type="password" name="password" id="password" placeholder=" *********" />
                </div>
                <div class="row21" id="re">
                    <label for="re-password">Re - Password :</label>
                    <input type="password" name="re-password" id="re-password" placeholder=" *********" />
                </div>
            </div>

            <div class="row4">
                <small>Use A-Z, a-z, 0-9, !@#$%^&* in password</small>
                <button type="submit">Sign Up</button>
            </div>
        </form>
    </div>

    <script>
    const profile_pic = document.getElementById("profile-pic");
    const path = document.getElementById("path");
    path.onchange = function() {
        if (path.files[0]) {
            profile_pic.src = URL.createObjectURL(path.files[0]);
        }
    };

    //validate the password
    document
        .getElementById("validation")
        .addEventListener("submit", function(e) {
            const password = document.getElementById("password").value;
            const re_password = document.getElementById("re-password").value;

            const passwordRegex =
                /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
            if (!passwordRegex.test(password)) {
                e.preventDefault();
                alert(
                    "Password must be at least 8 characters long, contain at least one letter, one number, and one special symbol."
                );
            } else if (password !== re_password) {
                e.preventDefault();
                alert("password not match");
            }
        });
    </script>
</body>

</html>