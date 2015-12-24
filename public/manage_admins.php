<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php $layout_context = "admin"; ?>
<?php include("../includes/layouts/header.php"); ?>

<div id="main" xmlns="http://www.w3.org/1999/html">
    <div id="navigation">
        &nbsp;
    </div>
    <div id="page">
        <?php echo session_message(); ?>
        <h2>Manage Admins</h2> <br>
        <?php
        if(!table_exists_in_db("admins")){echo create_new_admin_table("admins");}
        if (!table_is_empty("admins")){
        $admins_set=find_all_admins();
        ?>
        <table id="admin" >
            <tr><th>Username</th> <th>Actions</th></tr>
            <?php while ($admin = mysqli_fetch_assoc($admins_set)){ ?>
            <tr><td><?php echo htmlentities($admin["username"]);?></td>
                <td><a href="edit_admin.php?admin=
                        <?php echo urlencode($admin["id"]);?>
                        ">Edit</a> ,
                    <a href="delete_admin.php?admin=
                        <?php echo urlencode($admin["id"]);?>
                        " onclick="return confirm(\'Are you sure?\')";>Delete</a>
                </td>
            </tr>
            <?php } ?>
        </table>
        <?php
        }
        ?>
        <a href="new_admin.php">+ Add New Admin</a>

        <hr />


    </div>
</div>

<?php include("../includes/layouts/footer.php"); ?>

