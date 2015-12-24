<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php $layout_context = "admin"; ?>
<?php include("../includes/layouts/header.php"); ?>



<?php find_selected_page()?>

<div id="main" xmlns="http://www.w3.org/1999/html">
	<div id="navigation">
        <br /><a href="admin.php">&laquo; Main Menu</a><br />
        <?php echo navigation($current_subject, $current_page); ?>

        <br />
        <a href="new_subject.php">+ Add New Subject</a>

	</div>
	<div id="page">
        <?php echo session_message(); ?>
        <?php if($current_subject) { ?>
            <h2>Manage Subject</h2>
            <br />Menu name:<?php echo htmlentities($current_subject["menu_name"]); ?>
            <br />Position:<?php echo $current_subject["position"]; ?>
            <br />Visible:<?php echo $current_subject["visible"] ==1? "yes": "no"; ?><br /><br />
            <a href="edit_subject.php?subject=<?php echo urlencode($current_subject["id"]); ?>">Edit Subject</a> <br /><hr>
            <h3>Pages:</h3>
            <?php echo page_list_for_subject($current_subject); ?>
            <a href="new_page.php?subject=<?php echo urlencode($current_subject["id"]); ?>">+ Add New Page for this Subject</a>

        <?php } elseif($current_page) { ?>
            <h2>Manage Page</h2>
            <br />Menu name:<?php echo htmlentities($current_page["menu_name"]); ?>
            <br />Position:<?php echo $current_page["position"]; ?>
            <br />Visible:<?php echo $current_page["visible"] ==1? "yes": "no"; ?>
            <br />Content:<br><div class="view-content"><?php echo htmlentities($current_page["content"]); ?></div><br />
            <a href="edit_page.php?page=<?php echo urlencode($current_page["id"]); ?>">Edit Page</a>
        <?php } else{ ?>
           <h1>Manage Content </h1>
        <?php }  ?>

	</div>
</div>

<?php include("../includes/layouts/footer.php"); ?>

