<?php
class Controller
{
    /**
     * Rend la vue en incluant les paramètres.
     *
     * @param string $view Le chemin vers le fichier de vue à rendre.
     * @param array $params Les paramètres à passer à la vue.
     * @return void
     */
    public static function render($view, $params)
    {
        extract($params);
        require_once($view);
    }
}