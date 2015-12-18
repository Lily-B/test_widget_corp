<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php $layout_context = "public"; ?>
<?php include("../includes/layouts/header.php"); ?>

<?php find_selected_page(true)?>
<div id="main" xmlns="http://www.w3.org/1999/html">
	<div id="navigation">
		<?php echo public_navigation($current_subject, $current_page); ?>
	</div>
	<div id="page">
		<?php if($current_subject || $current_page) { ?>
			<h2><?php echo htmlentities($current_page["menu_name"]); ?></h2>
			<div><?php echo nl2br(htmlentities($current_page["content"])); ?></div><br />
		<?php } else{ ?>
			<p>Welcome!</p>
		<?php }  ?>

	</div>
</div>

<?php include("../includes/layouts/footer.php"); ?>

