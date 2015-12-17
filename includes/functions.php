<?php

function redirect_to($new_location){
    header("Location: " . $new_location);
    exit;
}

function confirm_query($result_set){
    if (!$result_set){
        die("Database query failed.");
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
    confirm_query($subject_set);
    return $subject_set;
}

function find_subject_by_id($subject_id){
    global $connection;

    $safe_subject_id = mysqli_real_escape_string($connection, $subject_id);

    $query = "SELECT * ";
    $query .= "FROM subjects ";
    $query .= "WHERE id = {$safe_subject_id} ";
    $query .= "LIMIT 1";
    $subject_set = mysqli_query($connection, $query);
    confirm_query($subject_set);
    if ($subject = mysqli_fetch_assoc($subject_set)){
        return $subject;
    }else {
        return null;
    }

}

function find_page_by_id($page_id){
    global $connection;

    $safe_page_id = mysqli_real_escape_string($connection, $page_id);

    $query = "SELECT * ";
    $query .= "FROM pages ";
    $query .= "WHERE id = {$safe_page_id} ";
    $query .= "LIMIT 1";
    $page_set = mysqli_query($connection, $query);
    confirm_query($page_set);
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
    confirm_query($page_set);
    return $page_set;
}

function find_selected_page(){
    global $current_subject;
    global $current_page;

    if (isset($_GET["subject"])){
        $current_subject = find_subject_by_id($_GET["subject"]);
        $current_page = null;
    } elseif(isset($_GET["page"])){
        $current_subject = null;
        $current_page = find_page_by_id($_GET["page"]);
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



