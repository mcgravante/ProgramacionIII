<?php

require_once __DIR__ .'/vendor/autoload.php';
include 'paises.php';

$paises=new Paises("United States of America", "nombre");

$paises->Mostrar();
