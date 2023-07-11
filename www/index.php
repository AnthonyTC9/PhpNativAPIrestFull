<?php

require_once "controller/rutas.controller.php";
require_once "controller/cursos.controller.php";
require_once "controller/clientes.controller.php";
require_once "models/clientes.model.php";
require_once "models/cursos.model.php";

$routes = new ControllerRoutes();
$routes->index();

?>

