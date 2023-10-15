<?php
define('ROOT', __DIR__);
define('DEFAULT_CONTROLLER', 'Etudiant');
define('DEFAULT_ACTION', 'ListeEtudiants');

var_dump($_SERVER['SCRIPT_FILENAME']);

// TODO : Fix main router & Fix section filters route
$params = array();
if (isset($_GET) && !empty($_GET)) {
    extract($_GET);
    foreach ($_GET as $key => $value) {
        if (($key != 'controller') && ($key != 'action')) {
            $params[$key] = $value;
        }
    }
} else {
    $controller = DEFAULT_CONTROLLER;
    $action = DEFAULT_ACTION;
}




$controller .= "Controller";

$filename = ROOT . '/controllers/' . $controller . '.php';
if (file_exists($filename)) {
    require_once ROOT . '/controllers/' . $controller . '.php';
    if (method_exists($controller, $action)) {
        try {
            $controller::$action($params);
        } catch (Exception $ex) {
            echo 'Erreur : ' . $ex->getMessage();
        }
    } else {
        print_r('mmm erreur 404 un truc dans le genre (l\'action n\'existe pas)');
    }
} else {
    print_r('mmm erreur 404 un truc dans le genre (le controller n\'existe pas)');
}

/*au cas où vous vous demanderez pourquoi je ne ferme pas la balise php 
https://www.php.net/manual/en/language.basic-syntax.phptags.php :
If a file contains only PHP code, it is preferable to omit the PHP closing tag at the end 
of the file. This prevents accidental whitespace or new lines being added after the PHP 
closing tag, which may cause unwanted effects because PHP will start output buffering 
when there is no intention from the programmer to send any output at that point in the 
script.

et vu que je ne suis pas doué avec les sauts de ligne...
*/