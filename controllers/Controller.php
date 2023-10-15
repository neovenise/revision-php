<?php
class Controller
{
    public static function render($view, $params)
    {
        extract($params);
        require_once($view);
    }
}