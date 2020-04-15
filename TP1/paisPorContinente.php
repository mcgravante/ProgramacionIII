<?php

require_once __DIR__ .'/vendor/autoload.php';
include 'paises.php';

$paises=new Paises("Americas", "region");

$paises->Mostrar();