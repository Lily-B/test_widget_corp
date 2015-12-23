<?php

function redirect_to($new_location){
    header("Location: " . $new_location);
    exit;
}

function confirm_query($result_set, $public=true){
    if (!$result_set){
        if ($public){
            redirect_to("index.php");
        }else{
            die("Database query failed.");
        }
    }
}

function mysql_prep($string){
    global $connection;
    $prep_string = mysqli_real_escape_string($connection, $string);
    return $prep_string;
}

function find_all_subjects ($public=true) {
    global $connection;

    $query = "SELECT * ";
    $query .= "FROM subjects ";
    if ($public){
        $query .= "WHERE visible = 1 ";
    }
    $query .= "ORDER BY position ASC";
    $subject_set = mysqli_query($connection, $query);
    confirm_query($subject_set, $public);
    return $subject_set;
}

function find_subject_by_id($subject_id, $public = false){
    global $connection;

    $safe_subject_id = mysqli_real_escape_string($connection, $subject_id);

    $query = "SELECT * ";
    $query .= "FROM subjects ";
    $query .= "WHERE id = {$safe_subject_id} ";
    if ($public){
        $query .= "AND visible = 1 ";
    }
    $query .= "LIMIT 1";
    $subject_set = mysqli_query($connection, $query);
    confirm_query($subject_set, $public);
    if ($subject = mysqli_fetch_assoc($subject_set)){
        return $subject;
    }else {
        return null;
    }

}

function find_page_by_id($page_id, $public = true){
    global $connection;

    $safe_page_id = mysqli_real_escape_string($connection, $page_id);

    $query = "SELECT * ";
    $query .= "FROM pages ";
    $query .= "WHERE id = {$safe_page_id} ";
    if ($public){
        $query .= "AND visible = 1 ";
    }
    $query .= "LIMIT 1";
    $page_set = mysqli_query($connection, $query);
    confirm_query($page_set, $public);
    if ($page = mysqli_fetch_assoc($page_set)){
        return $page;
    }else {
        return null;
    }
}

function select_pages_for_subject ($subject_id, $public = true) {
    global $connection;

    $safe_subject_id = mysqli_real_escape_string($connection, $subject_id);

    $query = "SELECT * ";
    $query .= "FROM pages ";
    $query .= "WHERE subject_id ={$safe_subject_id} ";
    if ($public){
        $query .= "AND visible = 1 ";
    }
    $query .= "ORDER BY position ASC";
    $page_set = mysqli_query($connection, $query);
    confirm_query($page_set, $public);
    return $page_set;
}

function find_default_page_for_subject ($subject_id){
  $page_set = select_pages_for_subject ($subject_id, true);
    if ($first_page = mysqli_fetch_assoc($page_set)){
        return $first_page;
    }else {
        return null;
    }
}

function find_selected_page($public=false){
    global $current_subject;
    global $current_page;

    if (isset($_GET["subject"])){
        $current_subject = find_subject_by_id($_GET["subject"], $public);
        if ($current_subject && $public){
            $current_page = find_default_page_for_subject($current_subject["id"]);
        }else{
            $current_page = null;
        }
        $current_page = find_default_page_for_subject($current_subject["id"]);
    } elseif(isset($_GET["page"])){
        $current_subject = null;
        $current_page = find_page_by_id($_GET["page"], $public);
    } else {
        $current_subject = null;
        $current_page = null;
    }
}

function navigation($sel_subject, $sel_page){
  $output = '<ul class="subjects">';
    $subject_set = find_all_subjects (false);
    while ($subject = mysqli_fetch_assoc($subject_set)){
      $output .= '<li ';
        if ($sel_subject && $subject["id"] == $sel_subject["id"]){
            $output .= 'class="selected"';
        }
        $output .= ' >' ;

        $output .= '<a href="manage_content.php?subject=';
        $output .= urlencode($subject[ "id"]);
        $output .= '" >';
        $output .= htmlentities($subject["menu_name"]);
        $output .= '</a>';

        $page_set = select_pages_for_subject ($subject["id"], false);
        $output .= '<ul class="pages">';
            while ($page = mysqli_fetch_assoc($page_set)){

              $output .= '<li ';
                if ($sel_page && $page["id"] == $sel_page["id"]){
                    $output .= 'class="selected"';
                }
                $output .= ' >';

                $output .= '<a href="manage_content.php?page=';
                $output .= urlencode($page["id"]);
                $output .= '" >';
                $output .= htmlentities($page["menu_name"]);
                $output .= '</a>';
              $output .= '</li>';

            }
             mysqli_free_result($page_set);
        $output .= '</ul>';
      $output .= '</li>';

    }

    mysqli_free_result($subject_set);
  $output .= '</ul>';
    return $output;
}

function public_navigation($sel_subject, $sel_page){
    $output = '<ul class="subjects">';
    $subject_set = find_all_subjects (true);
    while ($subject = mysqli_fetch_assoc($subject_set)){
        $output .= '<li ';
        if ($sel_subject && $subject["id"] == $sel_subject["id"]){
            $output .= 'class="selected"';
        }
        $output .= ' >' ;

        $output .= '<a href="index.php?subject=';
        $output .= urlencode($subject[ "id"]);
        $output .= '" >';
        $output .= htmlentities($subject["menu_name"]);
        $output .= '</a>';

        if ($subject["id"] == $sel_subject["id"] || $subject["id"] == $sel_page["subject_id"]) {
            $page_set = select_pages_for_subject ($subject["id"], true);
            $output .= '<ul class="pages">';
            while ($page = mysqli_fetch_assoc($page_set)){

                $output .= '<li ';
                if ($sel_page && $page["id"] == $sel_page["id"]){
                    $output .= 'class="selected"';
                }
                $output .= ' >';

                $output .= '<a href="index.php?page=';
                $output .= urlencode($page["id"]);
                $output .= '" >';

                $output .= htmlentities($page["menu_name"]);
                $output .= '</a>';
                $output .= '</li>';

            }
            mysqli_free_result($page_set);
            $output .= '</ul>';
        }
        $output .= '</li>'; // end of the subject li

    }

    mysqli_free_result($subject_set);
    $output .= '</ul>';
    return $output;
}

function page_list_for_subject($subject){

$page_set = select_pages_for_subject ($subject["id"], false);
$output = '<ul class="page-list">';
    while ($page = mysqli_fetch_assoc($page_set)){

        $output .= '<li>';
        $output .= '<a href="manage_content.php?page=';
        $output .= urlencode($page["id"]);
        $output .= '" >';
        $output .= htmlentities($page["menu_name"]);
        $output .= '</a>';
        $output .= '</li>';

    }
    mysqli_free_result($page_set);
$output .= '</ul>';
    return $output;

}

function form_errors($errors = array()){
    $output = "";
    if (!empty($errors)){
        $output .= "<div class=\"error\">";
        $output .= "Please fix the following errors:";
        $output .= "<ul>";
        foreach ($errors as $key => $error){
            $output .= "<li>" . htmlentities($error) . "</li>";
        }
        $output .= "</ul>";
        $output .= "</div>";
    }
    return($output);
}

function find_all_admins() {
    global $connection;

    $query = "SELECT * ";
    $query .= "FROM admins ";
    $query .= "ORDER BY username ASC";
    $admins_set = mysqli_query($connection, $query);
    confirm_query($admins_set, false);
    return $admins_set;
}

function find_selected_admin(){
    global $current_admin;

    if (isset($_GET["admin"])){
        $current_admin = find_admin_by_id($_GET["admin"]);

    }  else {
        $current_admin = null;
    }
}

function find_admin_by_id($admin_id){
    global $connection;

    $safe_admin_id = mysqli_real_escape_string($connection, $admin_id);

    $query = "SELECT * ";
    $query .= "FROM admins ";
    $query .= "WHERE id = {$safe_admin_id} ";
    $query .= "LIMIT 1";
    $admin_set = mysqli_query($connection, $query);
    confirm_query($admin_set, false);
    if ($admin = mysqli_fetch_assoc($admin_set)){
        return $admin;
    }else {
        return null;
    }

}

function all_admins_table(){
    global $connection;
    $table_name = "admins";

    $output ="";
    if(!table_exists_in_db($table_name)){
        $output .= create_new_admin_table($table_name);
    }
    if (!table_is_empty($table_name)){
        $admins_set=find_all_admins();
        $output .= '<table id="admin" > <tr><th>Username</th> <th>Actions</th></tr>';
        while ($admin = mysqli_fetch_assoc($admins_set)){
            $output .= '<tr><td>' . htmlentities($admin["username"]) . '</td> <td>';
            $output .= '<a href="edit_admin.php?admin=';
            $output .= urlencode($admin["id"]);
            $output .=' ">Edit</a> ,';
            $output .= '<a href="delete_admin.php?admin=';
            $output .=urlencode($admin["id"]);
            $output .='" onclick="return confirm(\'Are you sure?\')";>Delete</a>';
            $output .= '</td></tr>';
        }
        $output .= '</table>';
    }
    return $output;
}

function create_new_admin_table($table_name){
    global $connection;
    $query = "CREATE TABLE " . $table_name . " (";
    $query .= "id INT(11) NOT NULL AUTO_INCREMENT, ";
    $query .= "username VARCHAR(50) NOT NULL, ";
    $query .= "hashed_password VARCHAR(60) NOT NULL, ";
    $query .= "PRIMARY KEY (id) )";
    $result = mysqli_query($connection, $query);
    if ($result) {
        return "Table " . $table_name . " created";
    }else{
        return "Table creation failed";
    }
}

function table_is_empty($table_name){
    global $connection;
    $query = "SELECT * FROM " . $table_name;
    $result = mysqli_query($connection, $query);
    $number_of_rows = mysqli_num_rows($result);
    if ($number_of_rows>0) {
        return false;
    }else{
        return true;
    }

}

function table_exists_in_db ($table_name){
    global $connection;

    $query = "SHOW TABLES";
    $table_list = mysqli_query($connection, $query);
    while ($table_in_db = mysqli_fetch_row($table_list)) {
        if ($table_name==$table_in_db[0]) {
            return true;
        }
    }
    return false;
}

function best_cost_for_hashig_passwords_on_server(){

        $timeTarget = 0.05;
        $cost = 5;
        do {
            $cost++;
            $start = microtime(true);
            password_hash("test", PASSWORD_BCRYPT, ["cost" => $cost]);
            $end = microtime(true);
        } while (($end - $start) < $timeTarget);

        return "Appropriate Cost Found: " . $cost . "\n";


}

/*function password_encrypt($password){

        $hash_format = "$2y$10$"; //tells PHP to use Blowfish with a "cost" of 10
        $salt_length = 22;        //Blowfish salt should be 22 characters or more
        $salt = generate_salt($salt_length);
        $format_and_salt = $hash_format . $salt;
        $hash = crypt($password, $format_and_salt);
        return $hash;
}

function generate_salt($length){
    //Not 100%unique, not 100% random, but good enough for a salt;
    //MD5 returns 32 characters
    $unique_random_string = md5(uniqid(mt_rand(), true));

    //Valid characters for the salt are [a-zA-Z0-9./]
    $base64_string = base64_encode($unique_random_string);

    //But not "+" wich is valid in base64 encoding
    $modified_base64_string = str_replace('+','.',$base64_string);

    //Truncate strimg to the correct length
    $salt = substr($modified_base64_string, 0, $length);

    return $salt;
}*/

/*function password_check($password, $existing_hash){
    $hash = crypt ($password, $existing_hash);
    if ($hash === $existing_hash){
        return true;
    }else{
        return false;
    }
}*/




