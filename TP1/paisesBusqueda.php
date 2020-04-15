<?php

Class PaisesBusqueda{
    

    public function __construct($parametro, $metodoBusqueda, $apiRest)
    {
        switch ($metodoBusqueda) {
            case 'nombre':
                $this->paises=$apiRest->byName($parametro, true);
                break;
            case 'region':
                $this->paises=$apiRest->byRegion($parametro);
                break;
            case 'capital':
                $this->paises=$apiRest->byCapitalCity($parametro);
            break;
            case 'idioma':
                $this->paises=$apiRest->byLanguage($parametro);
            break;
            default:
                break;
        }

    }
}