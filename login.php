<?php

include "connection.php";

$errors = array();
$success = array();

if (isset($_POST["submit"])) {
    if (isset($_POST['email']) && isset($_POST['password'])) {

        $user_email = trim($_POST['email']);
        $user_pass = trim($_POST['password']);

        $error_exists = false;

        if (empty($user_email)) {
            $errors[] = "Email is required";
            $error_exists = true;
        } elseif (!filter_var($user_email, FILTER_DEFAULT)) {
            echo '<h4 class="text-danger text-center">Invalid Email, please try again</h4>';
            $error_exists = true;
        }
        if (empty($user_pass)) {
            $errors[] = "Password is required";
            $error_exists = true;
        } elseif (strlen($user_pass) < 8 || strlen($user_pass) > 50) {
            $errors[] = "Password must be between 8 to 50 characters";
            $error_exists = true;
        }

        if (!$error_exists) {

            $sql = "SELECT UserID,FullName,username,Email,UserPassword FROM userregister WHERE username = ? or Email = ?";
            if ($stmt = $con->prepare($sql)) {

                $stmt->bind_param("ss", $user_email, $user_email);

                if ($stmt->execute()) {

                    $stmt->store_result();

                    if ($stmt->num_rows == 1) {

                        $stmt->bind_result($id, $fullname, $username, $email, $password);
                        if ($stmt->fetch()) {

                            if (password_verify($user_pass, $password)) {

                                session_start();

                                $_SESSION["loggedIn"] = true;
                                $_SESSION["user_id"] = $id;
                                $_SESSION["fullname"] = $fullname;
                                $_SESSION["username"] = $username;
                                $_SESSION["email"] = $email;

                                header("location: index.php");
                            } else {
                                $errors[] = "Email or password is incorrect";
                            }
                        }
                    } else {
                        $errors[] = "Email or password is incorrect";

                    }
                }
            }
        }
    } else {
        header("location: login.php");
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
    <div class="container-fluid">
        <div class="row">
            <div class="login-left-panel col-md-6 d-flex flex-column justify-content-center align-items-center">
                <h3 class="display-5 mb-3">Welcome To Simeon's Drug Tracking System</h3>
                <p><strong>Keep track of your medicine and daily dosages</strong></p>
                <p><strong>Get daily reminder of dosages that you should take</strong></p>
            </div>
            <div class=" login-right-panel col-md-6 d-flex flex-column justify-content-center p-5 ">

                <?php
if (isset($errors) && !empty($errors)) {
    foreach ($errors as $alert) {
        echo '  <div class="alert alert-danger" role="alert">
                ' . $alert . '
                </div>';

    }
}

if (isset($success) && !empty($success)) {
    foreach ($success as $msg) {
        echo '  <div class="alert alert-success" role="alert">
                ' . $msg . '
                </div>';

    }
}

?>
                <form action="login.php" method="post" autocomplete="off">
                    <h4 class="text-uppercase mb-5 text-center">Login to continue where you left off</h4>
                    <div class="form-group">
                        <label for="">Enter email</label>
                        <input type="text" class="form-control" name="email" id="email" placeholder="Email">
                    </div>

                    <div class="form-group">
                        <label for="">Enter Password</label>
                        <input type="password" class="form-control" name="password" id="password"
                            placeholder="Enter Password">
                    </div>

                    <input type="submit" value="Login" class="form-control btn btn-info text-white my-3" name="submit">
                </form>
                <p>Don't have an account? <a href="user_register.php">Sign Up</a></p>
            </div>

        </div>
    </div>

    <?php set_footer()?>