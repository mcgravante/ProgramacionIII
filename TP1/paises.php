<?php

include 'api.php';
include 'interface.php';
include 'paisesBusqueda.php';



Class Paises extends Rest implements IInterface{
    
    public $paises;

    public function __construct($parametro, $metodoBusqueda)
    {
        parent::__construct();
        if ($metodoBusqueda!="") {
            $this->paises=new PaisesBusqueda($parametro, $metodoBusqueda, $this->rest);
        }
        else{
            $this->paises = $this->rest->all();
        }

    }

    public function Mostrar()
    {
        
        echo json_encode($this->paises);
        
    }
}