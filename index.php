<?php
session_start();
require_once 'classes/user.php';

$user = new User();

// Indien Logout geklikt
if (isset($_GET['logout']) && $_GET['logout'] == 'true') {
    $user->Logout();
}

// Check login session: staat de user in de session?
if (!$user->IsLoggedin()) {
    // Alert not login
    echo "U bent niet ingelogd. Login om verder te gaan.<br><br>";
    // Toon login button
    echo '<a href="login_form.php">Login</a>';
} else {
    // select userdata from database
    $user->GetUser($_SESSION['username']);

    // Print userdata
    echo "<h2>Het spel kan beginnen</h2>";
    echo "Je bent ingelogd met:<br/>";
    $user->ShowUser();
    echo "<br><br>";
    echo '<a href="?logout=true">Logout</a>';
}
?>
