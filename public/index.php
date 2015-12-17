<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php $layout_context = "public"; ?>
<?php include("../includes/layouts/header.php"); ?>

<?php find_selected_page()?>

<div id="main" xmlns="http://www.w3.org/1999/html">
	<div id="navigation">
		<?php echo public_navigation($current_subject, $current_page); ?>
	</div>
	<div id="page">
		<?php if($current_subject) { ?>
			<h2><?php echo htmlentities($current_subject["menu_name"]); ?></h2>

		<?php } elseif($current_page) { ?>
			<h2><?php echo htmlentities($current_page["menu_name"]); ?></h2>
			<br />Content:<br><div class="view-content"><?php echo htmlentities($current_page["content"]); ?></div><br />

		<?php } else{ ?>
			<h1>Please select a subject or a page</h1>
		<?php }  ?>

	</div>
</div>

<?php include("../includes/layouts/footer.php"); ?>

