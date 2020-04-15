<?php

require_once __DIR__ .'/vendor/autoload.php';
include 'paises.php';

$todosLosPaises= new Paises("", "");

$todosLosPaises->Mostrar();