<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation_functions.php"); ?>

<?php find_selected_page(); ?>

<?php if (!$current_subject) {redirect_to("manage_content.php"); } ?>

<?php
if(isset($_POST['submit'])) {

    // validations
    $required_fields = array("menu_name", "position", "visible");
    validate_presences($required_fields);

    $fields_with_max_length = array("menu_name" => 30);
    validate_max_legth($fields_with_max_length);

    if (empty($errors)) {
        //Perform Update
        $id = $current_subject["id"];
        $menu_name = mysql_prep($_POST['menu_name']);
        $position = (int)$_POST['position'];
        $visible = (int)$_POST['visible'];

        $query = "UPDATE subjects SET ";
        $query .= "menu_name = '{$menu_name}' , ";
        $query .= "position = {$position} , ";
        $query .= "visible = {$visible} ";
        $query .= "WHERE id = {$id} ";
        $query .= "LIMIT 1";
        $result = mysqli_query($connection, $query);

        if ($result && mysqli_affected_rows($connection) >= 0) {
            $_SESSION["message"] = "Subject updated";
            redirect_to("manage_content.php");
        } else {
            $message = "Subject update failed";
        }


    } else {
        //this is probably a get request
    }
}
?>


<?php include("../includes/layouts/header.php"); ?>

<div id="main" xmlns="http://www.w3.org/1999/html">
    <div id="navigation">
        <?php echo navigation($current_subject, $current_page); ?>
    </div>
    <div id="page">
        <?php
        if(!empty($message)){echo "<div class=\"message\">" . htmlentities($message) . "</div>"; }
        echo form_errors($errors); ?>

        <h2>Edit  Subject: <?php echo htmlentities($current_subject["menu_name"]); ?></h2>
        <form action="edit_subject.php?subject=<?php echo urlencode($current_subject["id"]); ?>" method="post">
            <p>Menu Name:
                <input type="text" name="menu_name" value="<?php echo htmlentities($current_subject["menu_name"]); ?>" />
            </p>
            <p>Position:
                <select name="position">
                    <?php
                    $subject_count=mysqli_num_rows(find_all_subjects());
                    for($count=1; $count <=$subject_count; $count ++){
                        echo "<option value=\"$count\" ";
                        if ($count == $current_subject["position"]) {
                        echo "selected ";
                        }
                        echo ">$count</option>";
                    }
                    ?>
                </select>
            </p>
            <p>Visible:
                <input type="radio" name="visible" value="0"
                    <?php if (0 == $current_subject["visible"]){echo " checked";} ?>
                /> No
                &nbsp;
                <input type="radio" name="visible" value="1"
                    <?php if (1 == $current_subject["visible"]){echo " checked";} ?>
                 /> Yes
            </p>
            <input type="submit" name="submit" value="Edit Subject"/>
        </form>
        <br />
        <a href="manage_content.php">Cancel</a>
        &nbsp;
        &nbsp;
        <a href="delete_subject.php?subject=
        <?php echo urlencode($current_subject["id"]); ?>
        " onclick="return confirm('Are you sure?');">Delete Subject</a>

    </div>
</div>

<?php include("../includes/layouts/footer.php"); ?>

