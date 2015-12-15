<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>

<?php
    $current_subject = find_subject_by_id($_GET["subject"]);
    if (!$current_subject) {
        redirect_to("manage_content.php"); }

    $page_set = select_pages_for_subject($current_subject["id"]);
    if (mysqli_num_rows($page_set) >0){
        $_SESSION["message"] = "Can't delete subjects with pages.";
        redirect_to("manage_content.php?subject={$current_subject['id']}");
    }

    $id = $current_subject["id"];

    $query = "DELETE FROM subjects ";
    $query .= "WHERE id = {$id} ";
    $query .= "LIMIT 1";
    $result = mysqli_query($connection, $query);

    if ($result && mysqli_affected_rows($connection) == 1) {
        $_SESSION["message"] = "Subject deleted.";
        redirect_to("manage_content.php");
    } else {
        $_SESSION["message"] = "Subject deletion failed.";
        redirect_to("manage_content.php?subject=$id");
    }
?>
