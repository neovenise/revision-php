<?php
require_once('Controller.php');
require_once('./models/EtudiantManager.php');
require_once('./models/SectionManager.php');
class EtudiantController extends Controller
{
    public static function ListeEtudiants($params)
    {
        $listeSections = SectionManager::GetLesSections();
        $lesEtudiants = array();
        $idSectionFiltre = null;
        $view = ROOT . '/view/EtudiantBackOffice.php';
        /*Une partie de ce bout de code a été copié d'ici :
         * https://stackoverflow.com/questions/13502352/how-to-correctly-parse-an-integer-in-php
         * En revanche je l'ai bien compris.
         * ctype_digit vérifie si la variable passé ne contient que des chiffres (entiers),
         * et donc qu'il ne peut être qu'un potentiel ID de section. 
         * intval() est une sorte de parse, qui rend la valeur de la variable passé en paramètre en INT
         *
         * TL;DR : j'ai copié du code mais je l'ai compris et si vous voyez ce texte c'est qu'il marche
         *
         * Edit après lourde modification du code : il n'a plus grand chose à voir avec
         * ce qui a été copié de base, mais je le laisse pour les explications.
         */
        if (isset($params['section']) && ctype_digit($params['section'])) {
            $idSectionFiltre = intval($params['section'], 10);
            $sectionFiltre = SectionManager::getSectionParId($idSectionFiltre);
            $lesEtudiants = EtudiantManager::GetLesEtudiantsParSection($sectionFiltre);
        } else {
            $lesEtudiants = EtudiantManager::GetLesEtudiants();
        }
        $params = array();
        $params['idSectionFiltre'] = $idSectionFiltre;
        $params['etudiants'] = $lesEtudiants;
        $params['listeSections'] = $listeSections;
        self::render($view, $params);
    }

    // TODO : Test method "delete()"
    public static function delete($params)
    {
        try {
            if (ctype_digit($params['id']))
                EtudiantManager::SupprimerUnEtudiant(intval($params['id'], 10));
            else
                throw new UnexpectedValueException('La valeur passé n\'est pas un id.');
        } catch (Exception $ex) {
            echo "Erreur : " + $ex->getMessage();
        } finally {
            EtudiantController::ListeEtudiants($params);
        }
    }
}