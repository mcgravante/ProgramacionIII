<?php

use  NNV \ RestCountries ;


class Rest{

    protected $rest;

    public function __construct(){
        $this->rest= new RestCountries;
    }
}