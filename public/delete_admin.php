<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>

<?php
    find_selected_admin();

    if (!$current_admin) {
        redirect_to("manage_admins.php"); }

    $id = $current_admin["id"];

    $query = "DELETE FROM admins ";
    $query .= "WHERE id = {$id} ";
    $query .= "LIMIT 1";
    $result = mysqli_query($connection, $query);

    if ($result && mysqli_affected_rows($connection) == 1) {
        $_SESSION["message"] = "Admin deleted.";
        redirect_to("manage_admins.php");
    } else {
        $_SESSION["message"] = "Admin deletion failed.";
        redirect_to("manage_admins.php");
    }
?>
