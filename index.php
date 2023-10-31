<?php
session_start();

if ( !isset( $_SESSION[ 'user_role' ] ) ) {
    header( "Location: login.php" );
}
$user_role = $_SESSION[ 'user_role' ];
?>
<h2><?php echo ucfirst( $user_role ); ?> Dashboard</h2>
<h4><a href="logout.php">Logout</a></h4>

<?php if ( "admin" == $user_role || "manager" == $user_role ) {

    function get_user_list( $file_path ) {
        $user_list = [];

        if (  ( $handle = fopen( $file_path, "r" ) ) !== false ) {
            for ( $i = 0; $i < 2; $i++ ) {
                fgetcsv( $handle, 1000, "," );
            }
            while (  ( $data = fgetcsv( $handle, 1000, "," ) ) !== false ) {
                list( $username, $email, $password, $role ) = $data;
                $user_list[  ] = [
                    'Username' => $username,
                    'Email'    => $email,
                    'Role'     => $role,
                 ];
            }
            fclose( $handle );
        }
        return $user_list;
    }
    $file_path = 'users.csv';

    $users = get_user_list( $file_path );
?>


<h1>User List</h1>
    <table>
        <tr>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Action</th>
        </tr>
        <?php foreach ( $users as $user ): ?>
        <tr>
            <td><?php echo $user[ 'Username' ]; ?></td>
            <td><?php echo $user[ 'Email' ]; ?></td>
            <td><?php echo $user[ 'Role' ]; ?></td>

            <td>
                <a href="edit.php?username=<?php echo $user[ 'Username' ]; ?>">Edit</a>
                <?php if ( "admin" == $user_role) {;?>| <a href="delete.php?username=<?php echo $user[ 'Username' ]; ?>" onclick="return confirm('Are you sure you want to delete it?')">Delete</a><?php };?>
            </td>
        </tr>
        <?php endforeach;?>
    </table>
    <p>Only admin can delete another user.</p>
    <style>
        table td, table th {
            border: 1px solid black;
            padding: 10px;
        }
    </style>
<?php
} else {
    ?>
    <h4>User Cann't See user list</h4>
    <?php
}
?>