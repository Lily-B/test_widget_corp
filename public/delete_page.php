<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>

<?php
    $current_page = find_page_by_id($_GET["page"]);
    if (!$current_page) {
        redirect_to("manage_content.php"); }

    /*$page_set = select_pages_for_subject($current_page["subject_id"]);
    if (mysqli_num_rows($page_set) >0){
        $_SESSION["message"] = "Can't delete subjects with pages.";
        redirect_to("manage_content.php?subject={$current_subject['id']}");
    }*/

    $id = (int)$current_page["id"];

    $query = "DELETE FROM pages ";
    $query .= "WHERE id = {$id} ";
    $query .= "LIMIT 1";
    $result = mysqli_query($connection, $query);

    if ($result && mysqli_affected_rows($connection) >= 0) {
        $_SESSION["message"] = "Page deleted.";
        redirect_to("manage_content.php");
    } else {
        $_SESSION["message"] = "Page deletion failed.";
        redirect_to("manage_content.php?page=$id");
    }
?>
