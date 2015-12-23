<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation_functions.php"); ?>

<?php find_selected_admin(); ?>

<?php if (!$current_admin) {redirect_to("manage_admins.php"); } ?>

<?php
if(isset($_POST['submit'])) {

    // validations
    $required_fields = array("username", "password");
    validate_presences($required_fields);

    $fields_with_max_length = array("username" => 30, "password" => 30);
    validate_max_legth($fields_with_max_length);

    if (empty($errors)) {
        //Perform Update
        $id = $current_admin["id"];
        $username = mysql_prep($_POST['username']);
        //$hashed_password = password_encrypt($_POST['password']); For php version  < 5.5
        $hashed_password = password_hash($_POST['password'], PASSWORD_BCRYPT, ['cost'=>10] );



        $query = "UPDATE admins SET ";
        $query .= "username = '{$username}' , ";
        $query .= "hashed_password = '{$hashed_password}' ";
        $query .= "WHERE id = {$id} ";
        $query .= "LIMIT 1";
        $result = mysqli_query($connection, $query);

        if ($result && mysqli_affected_rows($connection) >= 0) {
            $_SESSION["message"] = "Admin updated";
            redirect_to("manage_admins.php");
        } else {
            $message = "Admin update failed";
        }


    } else {
        //this is probably a get request
    }
}
?>

<?php $layout_context = "admin"; ?>
<?php include("../includes/layouts/header.php"); ?>

<div id="main" xmlns="http://www.w3.org/1999/html">
    <div id="navigation">
        &nbsp;
    </div>
    <div id="page">
        <?php
        if(!empty($message)){echo "<div class=\"message\">" . htmlentities($message) . "</div>"; }
        echo form_errors($errors); ?>

        <h2>Edit  Admin: <?php echo htmlentities($current_admin["username"]); ?></h2>
        <form action="edit_admin.php?admin=<?php echo urlencode($current_admin["id"]); ?>" method="post">
            <p>Username:
                <input type="text" name="username" value="<?php echo htmlentities($current_admin["username"]); ?>" />
            </p>
            <p>Password:
                <input type="password" name="password" />
            </p>

            <input type="submit" name="submit" value="Edit Admin"/>
        </form>
        <br />
        <a href="manage_admins.php">Cancel</a>


    </div>
</div>

<?php include("../includes/layouts/footer.php"); ?>

