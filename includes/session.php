<?php

session_start();

function session_message(){
    if (isset($_SESSION["message"])) {
        $message = "<div class=\"message\" >";
        $message .= htmlentities($_SESSION["message"]);
        $message .= "</div>";
        $_SESSION["message"] = null;
        return $message;
    } else{
        return null;
    }
}

function errors_message(){
    if (isset($_SESSION["errors"])) {
        $errors = $_SESSION["errors"];
        $_SESSION["errors"] = null;
        return $errors;
    } else{
        return null;
    }
}

