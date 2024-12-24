<?php
session_start();

// unset($_SESSION['userId']);
// $_SESSION['userId'] = 1;

// Check if the user is logged in
if (!isset($_SESSION['userId'])) {
    echo "not_logged_in";
} else {
    echo "logged_in";
}