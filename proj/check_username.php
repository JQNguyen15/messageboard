<?php require_once('includes/include.php'); ?>

<?php

// This script is used by the Ajax call to determine if the username has already been taken
// Returns true if the username is available, False if the username is not available 
$uname = $_POST['userName'];
echo checkAvailUsername($uname);
?>