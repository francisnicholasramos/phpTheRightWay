<?php

/* $arr = array('a' => 1, 'b' => 2, 'c' => 3); */
/**/
/* echo json_encode($arr); */
require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

use App\Models\User;

$userModel = new User();

// object approach (with hydrate)
$user = $userModel->findById('8c472ef6-2cde-4c7c-87df-165716c7729d');
var_dump($user); // shows User object

// array approach (raw PDO)
$pdo = $userModel->getConnection();
$stmt = $pdo->prepare("select * from users where id = :id");
$stmt->execute([':id' => '8c472ef6-2cde-4c7c-87df-165716c7729d']);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
var_dump($row); // shows plain array


echo (new $controller());
 
$pizza  = "piece1 piece2 piece3 piece4 piece5 piece6";

$pieces = array_map('trim', explode(",", $pizza));

print_r($$pieces);
