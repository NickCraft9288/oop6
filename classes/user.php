<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user";

class User{
    // Eigenschappen 
    public $username;
   
    private $password;
    private $db;
    
    function SetPassword($password){
        $this->password = $password;
    }

    function GetPassword(){
        return $this->password;
    }

    public function ShowUser() {
        echo "<br>Username: $this->username<br>";
        echo "<br>Password: $this->password<br>";

    }
    public function RegisterUser()
    {
        $status = false;
        $errors = [];
    
        // Validatie van de gebruiker
        $validationErrors = $this->ValidateUser();
        if (!empty($validationErrors)) {
            return $validationErrors;
        }
    
        // Check of de gebruiker al bestaat in de database
        if ($this->UserExists($this->username)) {
            array_push($errors, "Username bestaat al.");
        } else {
            // Gebruiker toevoegen aan de database
            $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);
    
            try {
                // Voorbereid de SQL-query
                $stmt = $this->db->prepare("INSERT INTO users (username, password, email) VALUES (:username, :password, :email)");
                $stmt->bindParam(':username', $this->username);
                $stmt->bindParam(':password', $hashedPassword);
                // Voer de query uit
                if ($stmt->execute()) {
                    $status = true;
                } else {
                    array_push($errors, "Fout bij het toevoegen van de gebruiker.");
                }
            } catch (PDOException $e) {
                // Handle fouten indien nodig
                echo "Error: " . $e->getMessage();
            }
        }
    
        return $errors;
    }
    
    private function UserExists($username)
    {
        // Controleer of de gebruiker al bestaat in de tabel 'users'
        try {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();
    
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
            return $user ? true : false;
        } catch (PDOException $e) {
            // Handle fouten indien nodig
            echo "Error: " . $e->getMessage();
        }
    }
    
    function ValidateUser(){
        $errors=[];

        if (empty($this->username)){
            array_push($errors, "Invalid username");
        } else if (empty($this->password)){
            array_push($errors, "Invalid password");
        }

        // Test username > 3 tekens en < 50 tekens

        return $errors;
    }

    public function LoginUser(){
        // Connect database
        // Hier moet je de logica toevoegen om de gebruiker te zoeken in de database.
        echo "Username:" . $this->username;

        // Indien gevonden dan sessie vullen
        return true;
    }

    // Check if the user is already logged in
    public function IsLoggedin() {
        // Check if user session has been set
        return isset($_SESSION['username']);
    }

    public function GetUser($username)
    {
        try {
            // Voorbereid de SQL-query
            $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username");
            $stmt->bindParam(':username', $username);
            
            // Voer de query uit
            $stmt->execute();
    
            // Haal de gebruiker op
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
            // Controleer of de gebruiker is gevonden
            if ($user) {
                // Vullen eigenschappen met waarden uit de SELECT
                $this->username = $user['username'];
           
                // Je kunt andere eigenschappen ook toevoegen op basis van je databasestructuur
            } else {
                return NULL; // Gebruiker niet gevonden
            }
    
        } catch (PDOException $e) {
            // Handle fouten indien nodig
            echo "Error: " . $e->getMessage();
        }
    }
    

    public function Logout(){
        session_start();
        // remove all session variables
        session_unset();

        // destroy the session
        session_destroy();

        header('location: index.php');
    }
}

?>
