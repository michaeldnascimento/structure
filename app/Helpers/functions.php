<?php

if (!function_exists('env')) {

    /**
     * Obtém o valor de uma variável de ambiente. Suporta boolean, empty and null.
     *
     */
    function env(string $key, $default = null)
    {

        $value = getenv($key);

        if ($value === false) {
            return value($default);
        }
        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'empty':
            case '(empty)':
                return '';
            case 'null':
            case '(null)':
                return null;
        }
        if (strlen($value) > 1 && starts_with($value, '"') && ends_with($value, '"')) {
            return substr($value, 1, -1);
        }
        return $value;

    }

}

if (!function_exists('value')) {

    /**
     * Retorna o valor padrão do valor dado.
     *
     * @param  mixed  $value
     * @return mixed
     */
    function value($value) {
        return $value instanceof Closure ? $value() : $value;
    }

}

if (!function_exists('starts_with')) {

    /**
     * Determine se uma determinada string começa com uma determinada substring.
     *
     * @param string $haystack
     * @param  string|array  $needles
     * @return bool
     */
    function starts_with(string $haystack, $needles): bool
    {
        foreach ((array) $needles as $needle) {
            if ($needle != '' && substr($haystack, 0, strlen($needle)) === (string) $needle) {
                return true;
            }
        }
        return false;
    }

}

if (!function_exists('ends_with')) {

    /**
     * Determine se uma determinada string termina com uma determinada substring.
     *
     * @param string $haystack
     * @param  string|array  $needles
     * @return bool
     */
    function ends_with(string $haystack, $needles): bool
    {
        foreach ((array) $needles as $needle) {
            if (substr($haystack, -strlen($needle)) === (string) $needle) {
                return true;
            }
        }
        return false;
    }

}

    function isAlert($tipo)
    {
        if (isset($_SESSION[$tipo])) {
            return TRUE;
        }

        return FALSE;
    }

    function alert($mensagem, $tipo = null)
    {
        if (!isset($tipo)) {
            $tipo = 'informacao';
        }
        $_SESSION[$tipo] = $mensagem;
    }

    function session($tipo) {
        $msg = $_SESSION[$tipo];
        unset($_SESSION[$tipo]);
        return $msg;
    }

    /**
     * Exibe os dados
     */
    function dd($data, $dump = false)
    {
        echo '<pre>';
        ($dump) ? var_dump($data) : print_r($data);
        echo '</pre>';
    }

    /**
     * Exibe os dados
     */
    function debug($data, $dump = false)
    {
        echo '<pre>';
        ($dump) ? var_dump($data) : print_r($data);
        echo '</pre>';
    }


