<?php
include_once 'datos.php';
include_once 'pizzas.php';

class Ventas
{
    public $email;
    public $tipo;
    public $sabor;
    public $monto;
    public $fecha;


    public function __construct($email,$tipo,$sabor,$monto,$fecha)
    {
        $this->email = $email;
        $this->tipo = $tipo;
        $this->sabor = $sabor;
        $this->monto = $monto;
        $this->fecha = $fecha;
    }

    public static function CrearVenta($tipo,$sabor,$email)
    {
        
        $return = false;
        $pizzas = Datos::getJson('pizzas.json');
        $nuevoArray=array();
        $clientes = Datos::getJson('users.json');
        $monto=0;
        foreach ($pizzas as $pizza)
        {
            if ($pizza->tipo == $tipo && $pizza->sabor >= $sabor && $pizza->stock>0)
            {
                $pizza->stock = $pizza->stock - 1;
                $monto= $pizza->precio;  
                $return=$monto; 
            }
            $nuevoArray=$pizzas; 
        }
        Datos::reemplazarJSON('pizzas.json',$nuevoArray);
    
        date_default_timezone_set('UTC');
        $fecha = date('l jS \of F Y h:i:s A');
        $venta = new Ventas($email,$tipo,$sabor,$monto,$fecha);
        if (Datos::GuardarJSON('ventas.json',$venta))
        {
            $return = $monto;
        }   
        return $return;
    }

    public static function MostrarVentas()
    {
        $return = false;
        $response = Datos::getJson("ventas.json");
        $cantidad=count($response);
        $montoTotal=0;
        foreach ($response as $venta)
        {
            $montoTotal = $montoTotal + $venta->monto;
            $return = "Monto total: {$montoTotal} cantidad: {$cantidad}.";
        }
        
        return $return;
    }

    public static function MostrarVentasUser($nombre)
    {
        $return = false;
        $response = Datos::getJson("ventas.json");
        $array = array();
        foreach ($response as $users)
        {
            if ($users->email == $nombre)
            {
                array_push($array,$users);
                $return = $array;
            }
        }
        return $return;
    }
}