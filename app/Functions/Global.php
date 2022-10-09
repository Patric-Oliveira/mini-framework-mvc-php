<?php

/**
 * Recebe uma informação e debuga o seu valor
 *
 * @param  mixed $param Valor a ser impresso
 * @return void
 */
function dd($param = null)
{
    echo'<pre>';
    print_r($param);
    echo'</pre>';

    die();
}

/**
 * Redireciona o usuário para a página informada 
 *
 * @param  string $url URL a ser redirecionada
 * @param  int $httpCode Código HTTP de resposta, por padrão é código 200
 * @return void
 */
function redirect(string $url, int $httpCode = 200)
{
    http_response_code($httpCode);
    header('Location: ' . $url);
}

/**
 * Recebe uma informação e imprime o seu valor em JSON
 *
 * @param  mixed $param
 * @param  int $httpCode Código HTTP de resposta, por padrão é código 200
 * @return void
 */
function responseJson($param, int $httpCode) 
{
    header('Content-Tyoe: application/json', true, $httpCode);
    echo json_decode($param);
}
