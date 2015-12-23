<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation_functions.php"); ?>
<?php $layout_context = "admin"; ?>
<?php include("../includes/layouts/header.php"); ?>


<?php
if(isset($_POST['submit'])){
    //Process the form

    $user_name = mysql_prep($_POST['username']);
    $hashed_password = password_hash($_POST['password'], PASSWORD_BCRYPT, ['cost'=>10] );

    // validations
    $required_fields = array("username", "password");
    validate_presences($required_fields);

    $fields_with_max_length = array("username" =>30);
    validate_max_legth($fields_with_max_length);

    if(!empty($errors)){
        $_SESSION["errors"] = $errors;
        redirect_to("new_admin.php");
    } else{
        $query = "INSERT INTO admins (";
        $query .= " username, hashed_password";
        $query .= ") VALUES (";
        $query .= " '{$user_name}', '{$hashed_password}'";
        $query .= ")";
        $result = mysqli_query($connection, $query);
    }

//Test if there was query error
    if ($result) {
        $_SESSION["message"] = "Admin created";
        redirect_to("manage_admins.php");
    }else{
        $_SESSION["message"] = "Admin creation failed";
        redirect_to("new_admin.php");
    }


} ?>


<div id="main" xmlns="http://www.w3.org/1999/html">
    <div id="navigation">
        &nbsp;
    </div>
    <div id="page">
        <?php echo session_message();
        echo form_errors(errors_message()); ?>

        <h2>Create  Admin</h2>

        <form action="new_admin.php" method="post">
            <p>Username:
                <input type="text" name="username" placeholder="Enter Username" />
            </p>
            <p>Password:
                <input type="password" name="password" />
            </p>

            <input type="submit" name="submit" value="Create Admin"/>
        </form>
        <br />
        <a href="manage_admins.php">Cancel</a>

    </div>
</div>

<?php include("../includes/layouts/footer.php"); ?>

