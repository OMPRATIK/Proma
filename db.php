<?php

$sName = "localhost";
$uNmae = "root";
$pass = "";
$db_name = "project_manager";

try {
  $conn = new PDO("mysql:host=$sName;dbname=$db_name", $uNmae, $pass);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
} catch (PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              