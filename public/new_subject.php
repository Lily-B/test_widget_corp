<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php $layout_context = "admin"; ?>
<?php include("../includes/layouts/header.php"); ?>

<?php find_selected_page(); ?>

<div id="main" xmlns="http://www.w3.org/1999/html">
	<div id="navigation">
        <?php echo navigation($current_subject, $current_page); ?>
	</div>
	<div id="page">
        <?php echo session_message();
        echo form_errors(errors_message()); ?>

        <h2>Create  Subject</h2>
        <form action="create_subject.php" method="post">
            <p>Menu Name:
            <input type="text" name="menu_name" value="" placeholder="Enter Menu Name"/>
            </p>
            <p>Position:
            <select name="position">
                <?php
                $page_count=mysqli_num_rows(find_all_subjects(false));
                for($count=1; $count <=($page_count+1); $count ++){
                    echo "<option value=\"$count\">$count</option>";
                }
                ?>


            </select>
            </p>
            <p>Visible:
                <input type="radio" name="visible" value="0" /> No
                &nbsp;
                <input type="radio" name="visible" value="1" /> Yes
            </p>
            <input type="submit" name="submit" value="Create Subject"/>
        </form>
        <br />
        <a href="manage_content.php">Cancel</a>

	</div>
</div>

<?php include("../includes/layouts/footer.php"); ?>

