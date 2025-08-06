<?php 
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    echo "DSDSD";
    header("location: ../views/dashboard.php");
}
?>