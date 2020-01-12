<?php

use App\Database\PostgresBooksRepository;
use App\Http\BookController;
use App\Http\BookIndexRequest;

require "./settings.php";

$repository = new PostgresBooksRepository();
$controller = new BookController($repository);
$request = new BookIndexRequest();

$viewObject = $controller->index($request);
echo $viewObject->render();