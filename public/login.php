<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation_functions.php"); ?>
<?php $layout_context = "admin"; ?>
<?php include("../includes/layouts/header.php"); ?>


<?php
$user_name="";

if(isset($_POST['submit'])){
    //Process the form

    // validations
    $required_fields = array("username", "password");
    validate_presences($required_fields);


    if(empty($errors)){
        $user_name = $_POST['username'];
        $password = $_POST['password'];


        $found_admin = attempt_login($user_name, $password);
        if ($found_admin) {
            //Success
            //Mark user as logged in

            $_SESSION['admin_id']=$found_admin['id'];
            $_SESSION['username']=$found_admin['username'];
            redirect_to("admin.php");
        }else{
            //Failure
            $_SESSION["message"] = "Username/password not found.";

        }
    }else{
        $_SESSION["errors"] = $errors;
    }

//Test if there was query error



} ?>


<div id="main" xmlns="http://www.w3.org/1999/html">
    <div id="navigation">
        &nbsp;
    </div>
    <div id="page">

        <?php echo session_message();
        echo form_errors(errors_message()); ?>

        <h2>Login</h2>

        <form action="login.php" method="post">
            <p>Username:
                <input type="text" name="username" value="<?php echo $user_name;?>"  />
            </p>
            <p>Password:
                <input type="password" name="password" />
            </p>

            <input type="submit" name="submit" value="Submit"/>
        </form>
        <br />


    </div>
</div>

<?php include("../includes/layouts/footer.php"); ?>

