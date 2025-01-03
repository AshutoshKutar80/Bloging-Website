<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login Page</title>

    <style>
    * {
        margin: 0;
        padding: 0;
    }

    body {
        height: 100%;
        background-color: #dde1e7;
        /* text-align: center;
  justify-content: center; */
        justify-items: center;
    }

    .container {
        width: 450px;
        margin-top: 100px;
    }

    .heading {
        /* background-color: blue; */
        text-align: center;
        width: 300px;
        margin: auto;
        margin-bottom: 30px;
    }

    .form {
        background-color: #fefffe;
        border-radius: 10px;
        padding: 20px 0px;
    }



    .row11 {
        display: flex;
        flex-direction: column;
        padding: 30px 20px;
    }

    .row11 label {
        margin-bottom: 5px;
        font-size: 20px;
    }

    .row11 input {
        height: 40px;
        background-color: #f5f5fa;
        border-radius: 4px;
        border: 1px solid #55a5ff;
        font-size: 20px;
    }

    .row21 {
        display: flex;
        padding: 10px 20px;
        flex-direction: column;
    }

    .row21 input {
        height: 40px;
        background-color: #f5f5fa;
        border-radius: 4px;
        border: 1px solid #55a5ff;
        margin-top: 5px;
        font-size: 20px;
    }

    .row21 label {
        font-size: 20px;
    }

    .row4 {
        display: flex;
        flex-direction: column;
        padding: 10px 20px;
        /* margin-top: -9px; */
    }

    .row4 button {
        height: 40px;
        margin: 20px 0px;
        background-color: #297ad6;
        border: 1px solid #55a5ff;
        border-radius: 8px;
        color: #fefffe;
        cursor: pointer;
        font-size: 20px;
    }

    .row4 label {
        margin: auto;
        margin-top: -10px;
        margin-bottom: 10px;
        color: #297ad6;
    }

    .row4 a {
        text-decoration: none;
        color: #297ad6;
    }

    .message {
        margin: 20px 0;
        padding: 10px;
        color: white;
        border-radius: 5px;
        text-align: center;
    }

    .success {
        background-color: #4CAF50;
    }

    .error {
        background-color: #f44336;
    }
    </style>
</head>

<body>
    <div class="container">
        <div class="heading">
            <h1>Join Social Network</h1>
        </div>
        <div class="form">
            <form method="post" action="fetch.php">
                <div class="row2">
                    <div class="row11">
                        <label for="email">Email Address :</label>
                        <input type="email" name="email" placeholder=" Email here" id="email" />
                    </div>
                </div>

                <div class="row3">
                    <div class="row21">
                        <label for="password">Password :</label>
                        <input type="password" name="password" id="password" placeholder=" user password" />
                    </div>
                </div>

                <div class="row4">
                    <button type="submit" name="login">Sign Up</button>
                    <label>Don't have account?
                        <a href="signup.php">Create Account</a></label>
                </div>
            </form>

        </div>
    </div>
</body>

</html>