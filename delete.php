<?php
session_start(); // Start the session if not already started

if (!isset($_SESSION['user_role'])) {
    header("Location: login.php");
} 
$user_role = $_SESSION['user_role'];
if ('admin' == $user_role) {

    function delete_user_by_username($file_path, $username) {
        $lines = file($file_path);
        $new_lines = [];
    
        foreach ($lines as $line) {
            list($existing_username, $email, $password, $role) = explode(',', trim($line));
            if ($existing_username !== $username) {
                $new_lines[] = "$existing_username,$email,$password,$role";
            }
        }

        print_r($new_lines);
        file_put_contents($file_path, implode(PHP_EOL, $new_lines));
    }
    
    $file_path = 'users.csv';

    if (isset($_GET['username'])) {
        $username_to_delete = $_GET['username'];
        delete_user_by_username($file_path, $username_to_delete);
        $_data = "" . PHP_EOL;
        file_put_contents($file_path, $_data, FILE_APPEND);
    }
    header("Location: index.php");
}else{
    header("Location: index.php");
}
?>
