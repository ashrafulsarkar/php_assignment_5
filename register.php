<?php
session_start();

if (isset($_SESSION['user_role'])) {
    header("Location: index.php");
}

function is_valid_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function user_exists($file_path, $username, $email) {
    if (($handle = fopen($file_path, "r")) !== false) {
        while (($data = fgetcsv($handle, 1000, ",")) !== false) {
            list($existing_username, $existing_email, $existing_password) = $data;
            if ($existing_username === $username || $existing_email === $email) {
                fclose($handle);
                return true;
            }
        }
        fclose($handle);
    }
    return false;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $role = "user";

    
    if (empty($username) || empty($email) || empty($password) || !is_valid_email($email)) {
        echo "Please fill in all fields with valid data.";
    } else {
        $user_data = "$username,$email,$password,$role" . PHP_EOL;
        $file_path = 'users.csv';

        if (!file_exists($file_path)) {
            $header = "Username,Email,Password,Role" . PHP_EOL;
            file_put_contents($file_path, $header);
            $admin_data = "admin,admin@gmail.com,1234,admin" . PHP_EOL;
            file_put_contents($file_path, $admin_data, FILE_APPEND);
        }

        if (user_exists($file_path, $username, $email)) {
            echo "Username or email already exists. Please choose a different one.";
        } else {
            file_put_contents($file_path, $user_data, FILE_APPEND);
            echo "Registration successful. You can now log in.";
        }
    }
}
?>

<!-- HTML Registration Form -->
<form method="POST" action="">
    <label for="username">Username:</label>
    <input type="text" name="username" required><br>
    <label for="email">Email:</label>
    <input type="email" name="email" required><br>
    <label for="password">Password:</label>
    <input type="password" name="password" required><br>
    <input type="submit" value="Register">
    <a href="login.php">Login</a>
</form>
