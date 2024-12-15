<?php

if(isset($_POST['name'])) {
  require "db.php";

  $name = $_POST['name'];
  $status = $_POST['status'];
  $description = $_POST['description'];
  $link = $_POST['link'];

  $res = $conn->query("INSERT INTO projects (name, status, description, link) VALUES ('$name', '$status', '$description', '$link')");
  if($res) {
    header("Location: index.php");
    echo "Project added successfully";
  } else {
    echo "Error adding project";
  }
  
}