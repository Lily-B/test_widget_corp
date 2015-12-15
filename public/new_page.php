<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation_functions.php"); ?>
<?php include("../includes/layouts/header.php"); ?>

<?php find_selected_page(); ?>
<?php
if(isset($_POST['submit'])){
    //Process the form

    $subject_id = (int) $_GET['subject'];
    $menu_name = mysql_prep($_POST['menu_name']);
    $position = (int) $_POST['position'];
    $visible = (int) $_POST['visible'];
    $content = mysql_prep($_POST['content']);

    // validations
    $required_fields = array("menu_name", "position", "visible", "content");
    validate_presences($required_fields);

    $fields_with_max_length = array("menu_name" =>30);
    validate_max_legth($fields_with_max_length);

    if(!empty($errors)){
        $_SESSION["errors"] = $errors;
        redirect_to("new_page.php?subject=" . $subject_id);
    } else{
        $query = "INSERT INTO pages (";
        $query .= " subject_id, menu_name, position, visible, content";
        $query .= ") VALUES (";
        $query .= " {$subject_id}, '{$menu_name}', {$position}, {$visible}, '{$content}'";
        $query .= ")";
        $result = mysqli_query($connection, $query);
    }

//Test if there was query error
    if ($result) {
        $_SESSION["message"] = "Page created";
        redirect_to("manage_content.php?subject=" . $subject_id);
    }else{
        $_SESSION["message"] = "Page creation failed";
        redirect_to("new_page.php");
    }


} ?>


<div id="main" xmlns="http://www.w3.org/1999/html">
	<div id="navigation">
        <?php echo navigation($current_subject, $current_page); ?>
	</div>
	<div id="page">
        <?php echo session_message();
        echo form_errors(errors_message()); ?>

        <h2>Create  Page</h2>

        <form action="new_page.php?subject=<?php echo urlencode($current_subject["id"]); ?>" method="post">
            <p>Menu Name:
                <input type="text" name="menu_name" placeholder="Enter Menu Name" />
            </p>
            <p>Position:
                <select name="position">
                    <?php
                    $page_count=mysqli_num_rows(select_pages_for_subject ($current_subject["id"]));
                    for($count=1; $count <=($page_count+1); $count ++){
                        echo "<option value=\"$count\" >$count</option>";
                    }
                    ?>
                </select>
            </p>
            <p>Visible:
                <input type="radio" name="visible" value="0"/> No
                &nbsp;
                <input type="radio" name="visible" value="1" /> Yes
            </p>
            <p>Content:<br>
                <textarea  class="content" name="content" rows="7" cols="50" ></textarea>
            </p>
            <input type="submit" name="submit" value="Create Page"/>
        </form>
        <br />
        <a href="manage_content.php?subject=<?php echo urlencode($current_subject["id"]); ?>">Cancel</a>

	</div>
</div>

<?php include("../includes/layouts/footer.php"); ?>

