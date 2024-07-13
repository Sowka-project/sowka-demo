<?php

$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "sowka";

if(!$conn = mysqli_connect($dbhost,$dbuser,$dbpass,$dbname)){
    die("failed to connect!");
}


