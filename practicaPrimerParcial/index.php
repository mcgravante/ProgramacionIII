<?php

include_once './response.php';
include_once './datos.php';
include_once './users.php';
include_once './pizzas.php';
include_once './ventas.php';

session_start();

//print_r($_SESSION);

$requestMethod = $_SERVER['REQUEST_METHOD'];
$pathInfo = $_SERVER['PATH_INFO'];

$respuesta = new Response;
$respuesta->data='';

switch($requestMethod)
{
    case 'GET':
        switch($pathInfo)
        {
            case '/pizzas':
                $header = getallheaders();
                $token = $header['token'];
                $respuesta->data=Pizza::MostrarPizzas(User::isAdmin($token));
            break;
            case '/ventas':
                $header = getallheaders();
                $token = $header['token'];
                if (User::isAdmin($token))
                {
                    $respuesta->data= Ventas::MostrarVentas();
                }
                else
                {
                   $nombre = User::notAdmin($token);
                   $respuesta->data = Ventas::MostrarVentasUser($nombre);
                }
                echo json_encode($respuesta);
            break;
            default:
            $respuesta->data= "Error en pathinfo";
            $respuesta->status= 'fail';
        break;
        }
    break;
    case 'POST':
        switch($pathInfo)
        {
            case '/usuario':
                if (isset($_POST['email']) && isset($_POST['clave']) && isset($_POST['tipo'])
                && !empty($_POST['email']) && !empty($_POST['clave']) && !empty($_POST['tipo']))
                {
                    //$user = new User();
                    if (User::Singin($_POST['email'],$_POST['clave'],$_POST['tipo']))
                    {
                        $respuesta->data = 'Sign valido';
                    }else
                    {
                        $respuesta->data = 'Revisar datos';
                        $respuesta->status = 'fail';
                    }
                    
                }else
                {
                    $respuesta->data = 'Faltan datos';
                    $respuesta->status = 'fail';
                }
                echo json_encode($respuesta);

            break;
            case '/login':
                if (isset($_POST['email']) && isset($_POST['clave']) && 
                !empty($_POST['email']) && !empty($_POST['clave']))
                {
                    $respuesta->data = User::Login($_POST['email'],$_POST['clave']);
                }
                else
                {
                    $respuesta->data = 'Faltan datos';
                    $respuesta->status = 'fail';
                }
                echo json_encode($respuesta);

            break;
                case '/pizzas':
                    $header = getallheaders();
                    $token = $header['token'];
                    if (User::isAdmin($token))
                    {
                        if (isset($_POST['tipo'])  && isset($_POST['precio']) && isset($_POST['stock'])&& isset($_POST['sabor']) && isset($_FILES['foto']) &&
                        !empty($_POST['tipo'])  && !empty($_POST['precio']) && !empty($_POST['stock'])&& !empty($_POST['sabor']) && !empty($_FILES['foto']))
                        {
                            $respuesta->data = Pizza::Save($_POST['tipo'], $_POST['precio'],$_POST['stock'],$_POST['sabor'], $_FILES['foto']['tmp_name']);
                            move_uploaded_file($_FILES['foto']['tmp_name'], 'imagenes/'.$_FILES['foto']['name']);
                            Datos::MarcaAgua('imagenes/'.$_FILES['foto']['name'],'imagenes/conMarca.jpg');
                        }else
                        {
                            $respuesta->data='Faltan datos';
                            $respuesta->status='fail';
                        }
                    }else
                    {
                        $respuesta->data='Token invalido, no tiene permisos';
                        $respuesta->status='fail';
                    }
                    echo json_encode($respuesta);

                break;
                case '/ventas':
                    $header = getallheaders();
                    $token = $header['token'];
                    $cliente = User::notAdmin($token);
                    if ($cliente != false && isset($_POST['tipo'])  && isset($_POST['sabor']))
                    {
                        $respuesta->data=Ventas::CrearVenta($_POST['tipo'],$_POST['sabor'],$cliente);
                    }else
                    {
                        $respuesta->data="No pudo realizarse la venta";
                        $respuesta->status="fail";
                    }
                    echo json_encode($respuesta);
                break;
            default:
            $respuesta->data= "Error en pathinfo";
            $respuesta->status= 'fail';
            break;

        }
    break;
    default:
    $respuesta->data= "Metodo no permitido";
    $respuesta->status= 'fail';
break;
}