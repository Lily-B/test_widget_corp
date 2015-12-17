<?php require_once("../includes/db/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php $layout_context = "admin"; ?>
<?php include("../includes/layouts/header.php"); ?>

<div id="main">
	<div id="navigation">
		&nbsp;
	</div>
	<div id="page">
		<h2>Admin Menu</h2>
		<p>Welcome to the admin area.</p>
		<ul>
			<li><a href="manage_content.php">Manage Website content</a></li>
			<li><a href="admin.php">Manage Admins</a></li>
			<li><a href="logout.php">Logout</a></li>
		</ul>
	</div>
</div>

<?php include("../includes/layouts/footer.php"); ?>
