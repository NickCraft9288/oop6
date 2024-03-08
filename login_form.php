<?php
require_once('classes/user.php');

// Create an instance of the User class
$user = new User();

// Check if the user is already logged in
if ($user->IsLoggedin()) {
    // User is logged in, redirect to the desired page
    header("location: index.php");
    exit();
}

// Initialize errors array
$errors = [];

// Check if the login button is clicked
if (isset($_POST['login-btn'])) {
    $user->username = $_POST['username'];
    $user->SetPassword($_POST['password']);

    // Validate user inputs
    $errors = $user->ValidateUser();

    // If no validation errors, attempt login
    if (empty($errors)) {
        // Attempt login
        if ($user->LoginUser()) {
            // Login successful, redirect
            header("location: index.php");
            exit();
        } else {
            // Login failed
            array_push($errors, "Login failed");
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
</head>
<body>

    <h3>PHP - PDO Login and Registration</h3>
    <hr/>
    
    <form action="" method="POST">    
        <h4>Login here...</h4>
        <hr>
        
        <label>Username</label>
        <input type="text" name="username" />
        <br>
        <label>Password</label>
        <input type="password" name="password" />
        <br>
        <button type="submit" name="login-btn">Login</button>
        <br>
        <a href="register_form.php">Registration</a>

        <?php
        // Display errors, if any
        if (!empty($errors)) {
            echo "<div style='color: red;'>";
            foreach ($errors as $error) {
                echo $error . "<br>";
            }
            echo "</div>";
        }
        ?>
    </form>
        
</body>
</html>
