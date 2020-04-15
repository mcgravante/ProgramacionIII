<?php

class miPais{

    public static $pais= "Yo soy Argentina";   

    public static function MetodoEstatico()
    {
        return self:: $pais;
    }
}
