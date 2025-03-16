<?php
include '../config/connect.inc.php';
session_destroy();
header("Location:Show_music.php?logout=success"); 
exit(); 
?>