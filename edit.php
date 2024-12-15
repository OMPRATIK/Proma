<?php
require "db.php";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the project ID and other form data
    $projectId = intval($_POST['id']);
    $projectName = $_POST['name'];
    $projectStatus = intval($_POST['status']);
    $projectLink = $_POST['link'];
    $projectDescription = $_POST['description'];
    echo $projectId;

    $res = $conn->query("UPDATE projects SET name = '$projectName', status = '$projectStatus', link = '$projectLink', description = '$projectDescription' WHERE id = $projectId");
    if ($res) {
        header("Location: index.php");
        echo "Project updated successfully.";
    } else {
        echo "Error updating project: ";
    }
} else {
    echo "Invalid request method.";
}


