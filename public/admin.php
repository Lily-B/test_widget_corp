<?php
//1. Create a database connection

$dbhost = "localhost";
$dbuser = "test_cms";
$dbpass = "test5740960";
$dbname = "test_widget_corp";
$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

//Test if connection occurred.
if (mysqli_connect_errno()){
    die ("Database connection failed: " .
        mysqli_connect_error() .
        " (" . mysqli_connect_errno() . ") "
    );
}
?>
<?php require_once("../includes/functions.php"); ?>
<?php
// 2. Perform database query
$query = "SELECT * ";
$query .= "FROM subjects ";
$query .= "WHERE visible = 1 ";
$query .= "ORDER BY position ASC";

$result = mysqli_query($connection, $query);
//Test if there was query error
if (!$result){
die("Database query failed.");
}
?>
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
