<?php

use \Firebase\JWT\JWT;
require_once __DIR__ .'/vendor/autoload.php';
include_once './datos.php';
 class User
 {
     const TYPES = ['encargado', 'cliente'];
     const ARCHIVO = "users.json";
     const KEY = "pro3-parcial";
     public $email;
     public $clave;
     public $tipo;

     public function __construct($email,$clave,$tipo)
     {
        $this->email=$email;
        $this->clave=$clave;
        $this->tipo=$tipo;
     }

     public static function Singin($email,$clave,$tipo)
     {
        $return=false;
        $newUser = new User($email,$clave,$tipo);
        $alreadySignedIn = false;

        if (in_array($tipo, self::TYPES))
        {
            $arrayJson= Datos::getJson(self::ARCHIVO);
            if (!is_null($arrayJson))
            {
                foreach ($arrayJson as $item) 
                {
                    if ($item->email == $email) 
                    {
                        $alreadySignedIn=TRUE;
                    }
                }        
            }
            if(!$alreadySignedIn)
            {
                if (Datos::GuardarJSON(self::ARCHIVO,$newUser))
                {
                    $return=true;
                }
            }else{
                echo "Usuario ya registrado con ese mail";
            }
        }else{
            echo "Tipo invalido, usar encargado o cliente";
        }
        return $return;
     }

     public static function Login($email,$clave)
     {
        $return=false;
        $response = Datos::getJson("users.json");

        if ($response!=false)
        {
            foreach ($response as $user)
            {
            if (User::validar($email, $clave, $user->email, $user->clave))
                {
                    $payload = array(
                        "email" => $email,
                        //"id" => $user->id,
                        "clave" => $clave,
                       // "dni" => $user->dni,
                        "tipo" => $user->tipo,
                        //"obraSocial" => $user->obraSocial
                    );
                    $return=true;
                break;
                }
            }
        }
        if ($return)
        {
            $return = JWT::encode($payload, self::KEY);
        }else{
            echo "Usuario o contraseña invalidos.";
        }
        return $return;
     }

    public static function validar($email,$clave, $emailNew, $passNew)
    {
        $return = false;
         if ($passNew == $clave && $email==$emailNew)
         {
             
            $return = true;
         }
        return $return;
    }

    public static function IsAdmin($token)
     {
        $response=false;
        try
        {
            $users = JWT::decode($token, self::KEY, array("HS256"));
        }catch(Exception $ex)
        {
            return $response;
        }
        
        
        $lista = Datos::getJson('users.json');
        
        if($users)
        {
            if($users->tipo=="encargado")
            {
               $response=true;
            }
        }
        return $response;
     }

     public static function notAdmin($token)
     {
        $response=false;
        try
        {
            $users = JWT::decode($token, self::KEY, array("HS256"));
        }catch(Exception $ex)
        {
            echo "Token invalido.";
            return $response;
        }
        
        
        $lista = Datos::getJson('users.json');
        
        if($users)
        {
            if($users->tipo=="cliente")
            {

               $response=$users->email;
            }else{
                echo "Ud no es cliente";
            }
        }
        return $response;
     }


 }