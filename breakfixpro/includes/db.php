<?php
$host = 'localhost';
$dbname = 'breakfixpro_db';
$username = 'root';
$password = ''; // Default password for XAMPP

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Database connection established.";
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?> 
 <!-- In the above code, we have created a database connection using the PDO class. We have passed the database credentials to the PDO constructor. 
 Step 3: Create a PHP file to handle the AJAX request 
 Now, create a PHP file named  fetch_data.php  in the root directory of your project and add the following code to it. -->