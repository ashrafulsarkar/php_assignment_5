<?php
// Start a session to manage user sessions
session_start();

if (isset($_SESSION['user_role'])) {
    header("Location: index.php");
} 

function authenticate_user($email, $password) {

    $file_path = 'users.csv';

    if (!file_exists($file_path)) {
        return null;
    }

    if (($handle = fopen($file_path, "r")) !== false) {
        while (($data = fgetcsv($handle, 1000, ",")) !== false) {
            list($existing_username, $existing_email, $existing_password, $existing_role) = $data;
            if ($existing_password === $password && $existing_email === $email) {
                fclose($handle);
                return $existing_role;
            }
        }
        fclose($handle);
    }

    // Authentication failed
    return null;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    $email = $_POST["email"];
    $password = $_POST["password"];

    if (empty($email) || empty($password)) {
        echo "Please enter your email and password.";
    } else {
        $user_role = authenticate_user($email, $password);

        if ($user_role) {
            $_SESSION['user_role'] = $user_role;
            header("Location: index.php");
            exit;
        } else {
            echo "Authentication failed. Please check your email and password.";
        }
    }
}
?>

<!-- HTML Login Form -->
<form method="POST" action="">
    <label for="email">Email:</label>
    <input type="email" name="email" id="email" required><br>
    <label for="password">Password:</label>
    <input type="password" name="password" id="password" required><br>
    <input type="submit" value="Login">
    <a href="register.php">Register Account</a>
</form>
