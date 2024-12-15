<?php

if(isset($_POST['ideaName'])) {
  require "db.php";

  $name = $_POST['ideaName'];
  $status = 3;
  $description = $_POST['ideaDescription'];
  $link = "";
  $res = $conn->query("INSERT INTO projects (name, status, description, link) VALUES ('$name', '$status', '$description', '$link')");
  if($res) {
    header("Location: index.php");
    echo "Project added successfully";
  } else {
    echo "Error adding project";
  }
  
}