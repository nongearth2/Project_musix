<?php
$host="localhost";
$user="root";
$pass="";

$link=mysqli_connect($host,$user,$pass) or die("Cannot Connect Database");
mysqli_query($link,"USE project_music;");
session_start();
?>