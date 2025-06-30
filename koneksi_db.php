<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "ngertiyuk";

$mysql = new mysqli($servername, $username, $password, $database);

if ($mysql->connect_error) {
    die("Connection failed: " . $mysql->connect_error);
}