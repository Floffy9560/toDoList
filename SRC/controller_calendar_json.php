<?php

require_once 'models/Project.php';

header('Content-Type: application/json');

$projectModel = new Project();
$projects = $projectModel->getAllProject($_SESSION['idUser']);

echo json_encode($projects);
