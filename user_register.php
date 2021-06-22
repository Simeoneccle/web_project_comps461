<?php
include "connection.php";
include "functions.php";

$errors_array = array();
$success_array = array();

if (isset($_POST["submit"])) {

    try {
        if (isset($_POST["fullname"]) && isset($_POST["username"]) && isset($_POST["email"]) && isset($_POST["password"])) {

            $fullName = trim($_POST["fullname"]);
            $username = trim($_POST["username"]);
            $email = trim($_POST["email"]);
            $passWord = trim($_POST["password"]);
            $error_exists = false;

            if (empty($fullName)) {
                $errors_array[] = "Fullname is required";

                $error_exists = true;
            } elseif (strlen($fullName) < 3 || strlen($fullName) > 200) {
                $errors_array[] = 'Fullname must be between 3 to 200 character';
                $error_exists = true;
            }
            if (empty($email)) {
                $errors_array[] = 'Email is required';
                $error_exists = true;
            } elseif (strlen($email) > 200) {
                $errors_array[] = 'Email is too long';
                $error_exists = true;
            }
            if (empty($passWord)) {
                $errors_array[] = 'Password is required';
                $error_exists = true;
            }

            if (strlen($passWord) < 8 || strlen($passWord) > 50) {
                $errors_array[] = 'Password must be between 8 to 50 characters';
                $error_exists = true;
            }

            if (!$error_exists) {

                if (!check_email_exist($con, $email)) {
                    //check for username

                    if (!check_username_exist($con, $username)) {

                        $_hashedPassword = password_hash($passWord, PASSWORD_DEFAULT);
                        $_query = 'INSERT INTO userregister (FullName,username,Email,UserPassword) values (?,?,?,?)';
                        $statement = $con->prepare($_query);
                        $statement->bind_param('ssss', $fullName, $username, $email, $_hashedPassword);

                        if ($statement->execute()) {

                            $success_array[] = 'Account Created succe$success_arrayfully';
                        }

                    } else {
                        $errors_array[] = "Username already exists";
                    }
                } else {
                    $errors_array[] = "Email is already taken, please try again";
                }

            }
        }
    } catch (Exception $ex) {
        $errors_array[] = $ex->getMessage();
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="./css/bootstrap.css" type="text/css">
    <link rel="stylesheet" href="./css/main.css" type="text/css">
</head>

<body>
    <div class="container">
        <div class="row mt-5">
            <div class="col-md-7 offset-md-2 p-5">

                <?php
if (isset($errors_array) && !empty($errors_array)) {
    foreach ($errors_array as $alert) {
        echo '  <div class="alert alert-danger" role="alert">
                ' . $alert . '
                </div>';

    }
}

if (isset($success_array) && !empty($success_array)) {
    foreach ($success_array as $msg) {
        echo '  <div class="alert alert-succe$success_array" role="alert">
                ' . $msg . '
                </div>';

    }
}

?>
                <form action="" method="POST" autocomplete="off">
                    <h4 class="text-uppercase">Create New Account</h4>
                    <div class="form-group mb-2">
                        <label for="">Fullname</label>
                        <input type="text" class="form-control" name="fullname" id="fullname"
                            placeholder="Enter your fullname">
                    </div>

                    <div class="form-group mb-2">
                        <label for="">Username</label>
                        <input type="text" class="form-control" name="username" id="username"
                            placeholder="Enter your username">
                    </div>

                    <div class="form-group mb-2">
                        <label for="">Email</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="Enter your email">
                    </div>


                    <div class="form-group mb-2">
                        <label for="">Password</label>
                        <input type="password" class="form-control" name="password" id="password"
                            placeholder="Choose a password">
                    </div>

                    <input type="submit" value="Sign Up" class="form-control btn btn-succe$success_array my-3"
                        name="submit">

                </form>
                <p>Have an account? <a href="login.php">Sign In</a></p>
            </div>
        </div>
    </div>

    <?php set_footer()?>