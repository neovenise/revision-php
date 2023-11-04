<?php
define('ROOT', __DIR__);
define('DEFAULT_CONTROLLER', 'Etudiant');
define('DEFAULT_ACTION', 'ListeEtudiants');

$params = array();
if (isset($_POST) && !empty($_POST)) {
    extract($_POST);
    foreach ($_POST as $key => $value) {
        $params[$key] = $value;
    }
}
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
//Uniquement pour le développement, à supprimer avant d'envoyer en production
if ($controller == 'Reset'){
    require_once ROOT.'/models/DbManager.php';
    DbManager::reset();
    header('Location: /revision-php/');
}
else{
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
        print_r('Y a rien');
    }
} else {
    print_r('Y a rien');
}

/*au cas où vous vous demanderez pourquoi je ne ferme pas la balise php 
https://www.php.net/manual/en/language.basic-syntax.phptags.php :
If a file contains only PHP code, it is preferable to omit the PHP closing tag at the end 
of the file. This prevents accidental whitespace or new lines being added after the PHP 
closing tag, which may cause unwanted effects because PHP will start output buffering 
when there is no intention from the programmer to send any output at that point in the 
script.
*/
}