<?php
require "db.php";

if(isset($_GET['id'])) {
  $id = $_GET['id'];
  echo $id;
  $res = $conn->query("DELETE FROM projects WHERE id = $id");
  if($res) {
    header("Location: index.php");
    echo "Project deleted successfully";
  } else {
    echo "Error deleting project";
  }
}