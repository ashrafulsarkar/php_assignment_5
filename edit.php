<?php
session_start();

if (!isset($_SESSION['user_role'])) {
    header("Location: login.php");
} 
$user_role = $_SESSION['user_role'];
if ( ('admin' == $user_role) || ('manager' == $user_role) ) { 
    $username = '';
    if (isset($_GET['username'])) {
        $username = $_GET['username'];
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $username = $_POST["username"];
        $new_role = $_POST["new_role"];

        $file_path = 'users.csv';
        $lines = file($file_path);

        foreach ($lines as $key => $line) {
            list($existing_username, $email, $password, $role) = explode(',', trim($line));
            if ($existing_username === $username) {
                $lines[$key] = "$existing_username,$email,$password,$new_role\n";
                break;
            }
        }

        file_put_contents($file_path, $lines);
        header("Location: index.php");
    }
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Edit User Role</title>
    </head>
    <body>
        <h1>Edit User Role</h1>
        <form method="POST" action="">
            <label for="username">Username:</label>
            <span><?php echo $username; ?></span>
            <input type="hidden" name="username" value="<?php echo $username; ?>" required><br>

            <label for="new_role">New Role:</label>
            <select name="new_role">
                <?php if ('admin' == $user_role) { ?>
                <option value="admin">Admin</option>
                <?php } ?>
                <option value="manager">Manager</option>
                <option value="user">User</option>
            </select><br>
            <input type="submit" value="Update Role">
            <p>Only admin can create an another admin.</p>
        </form>
    </body>
    </html>
<?php
}else{
    header("Location: login.php");
}
?>

