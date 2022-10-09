<?php

namespace App\Core;

class Core
{
    private $explodeURI;
    private $area;

    public function __construct()
    {
        // Quebra a string nas barras e converte para um array 
        // Remove indices em branco e reestrutura a ordem
        $this->explodeURI = array_values(array_filter(explode('/', $_SERVER['REQUEST_URI'])));

        // Verifica quantos parâmetros da url precisa ser removido 
        for ($i = 0; $i < REMOVE_INDEX; $i++) {
            unset($this->explodeURI[$i]);
        }

        // Reordena o array novamente
        $this->explodeURI = array_values($this->explodeURI);

        $this->area = $this->getArea();

        echo $this->area;
        dd($this->explodeURI);
    }

    private function getArea() : string
    {
        if (!isset($this->explodeURI[0]) || !is_array($this->explodeURI) || count($this->explodeURI) == 0) {

            return 'Home';
        }

        $area = $this->explodeURI[0];

        foreach (AREAS as $area => $value) {

            if (strtolower($area) == $this->explodeURI[0]) {
                unset($this->explodeURI[0]);
                $this->explodeURI = array_values($this->explodeURI);
                return $value;
            }
        }
        return 'Home';
    }

    private function execute()
    {

    }
}
