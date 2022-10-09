<?php

namespace App\Core;

class Core
{
    private $explodeURI;
    private $area;
    
    /**
     * Método construtor que inicializa as funções so core
     *
     * @return void
     */
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

        // Obtém a área atual
        $this->area = $this->getArea();

        $this->execute();
    }
    
    /**
     * Valida se existe parâmetros e retorna a área correta a ser carregada
     *
     * @return string Retorna a área do site, por padrão retorna a Home
     */
    private function getArea() : string
    {
        if (!isset($this->explodeURI[0]) || !is_array($this->explodeURI) || count($this->explodeURI) == 0) {

            return 'Home';
        }

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
        $controller = $this->getController();
        $method = $this->getMethod($controller);

        //$this->getParams()

    }
    
    /**
     * Retorna o controller a ser ultilizado com base na URI
     *
     * @return string Retorna a HomeController caso a controller informada não exista 
     */
    private function getController() : string
    {
        $contBase = 'App\\Src\\' . $this->area . '\\Controller\\';

        if (!isset($this->explodeURI[0])) {
            return $contBase . 'HomeController';
        }

        $tmpController = $contBase . ucfirst($this->explodeURI[0] . 'Controller');

        if (!class_exists($tmpController)) {
            return $contBase . 'HomeController';
        }
        return $tmpController;
        
    }

    private function getMethod($controller) : string
    {
        if (!isset($this->explodeURI[1])) {
            return 'index';
        }

        if (!method_exists($controller, $this->explodeURI[1])) {
            return 'index';
        }
        return $this->explodeURI[1];
    }

    private function getParams() : array
    {
        if (!isset($this->explodeURI[2])) {
            return [];
        }
        $tmpParams = [];

        for ($i= 2; $i < count($this->explodeURI); $i++) { 
            $tmpParams[] = $this->explodeURI[$i];
        }
        return $tmpParams;
    }
}
