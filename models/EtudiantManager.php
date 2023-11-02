<?php
require_once('DbManager.php');
require_once('Etudiant.php');

class EtudiantManager
{
    private static ?\PDO $cnx = null;
    public static function GetLesEtudiants(): array
    {
        self::$cnx = DbManager::connect();
        $lesEtudiants = array();
        $req = 'select idEtudiant, nom, prenom , datenaissance, email, telmobile, idSection ';
        $req .= 'from etudiant';
        $result = self::$cnx->prepare($req);
        $result->execute();
        $result->setFetchMode(PDO::FETCH_ASSOC);
        while ($unEtudiant = $result->fetch()) {
            $lesEtudiants[] = new Etudiant(
                $unEtudiant['idEtudiant'],
                $unEtudiant['nom'],
                $unEtudiant['prenom'],
                $unEtudiant['email'],
                $unEtudiant['telmobile'],
                new DateTime($unEtudiant['datenaissance']),
                SectionManager::getSectionParId($unEtudiant['idSection'])
            );
        }
        return $lesEtudiants;
    }

    public static function GetLesEtudiantsParSection(Section $uneSection): array
    {
        self::$cnx = DbManager::connect();
        $lesEtudiants = array();
        $req = 'select idEtudiant, nom, prenom , datenaissance, email, telmobile, idSection ';
        $req .= 'from etudiant ';
        $req .= 'WHERE idSection = :Section';
        $result = self::$cnx->prepare($req);
        $idSection = $uneSection->GetId();
        $result->bindParam('Section', $idSection, PDO::PARAM_INT);
        $result->execute();
        while ($unEtudiant = $result->fetch()) {
            $lesEtudiants[] = new Etudiant(
                $unEtudiant['idEtudiant'],
                $unEtudiant['nom'],
                $unEtudiant['prenom'],
                $unEtudiant['email'],
                $unEtudiant['telmobile'],
                new DateTime(
                    $unEtudiant['datenaissance'],
                ),
                $uneSection
            );
        }
        return $lesEtudiants;
    }

    public static function SupprimerUnEtudiant(int $idEtudiant)
    {
        if (self::$cnx == null) {
            self::$cnx = DbManager::connect();
        }
        $req = 'delete from etudiant where idEtudiant = :id';
        $result = self::$cnx->prepare($req);
        $result->bindParam(':id', $idEtudiant, PDO::PARAM_INT);
        $nbLignes = $result->execute();
        if ($nbLignes != 1) {
            throw new Exception("Erreur : Le nombre de ligne affectés lors de la suppression n'est pas celui attendu : {$nbLignes}");
        }
    }

    public static function AjouterUnEtudiant(string $nom, string $prenom, DateTime $date, string $mail, string $tel, int $idSection)
    {
        if (self::$cnx == null) {
            self::$cnx = DbManager::connect();
        }
        $req = 'insert into etudiant(nom,prenom,datenaissance,email,telmobile,idsection)' .
            'values (:nom,:prenom,:date,:mail,:tel,:idsection)';
        $result = self::$cnx->prepare($req);
        $dateformat = $date->format('Y-m-d');
        $result->bindParam(':nom', $nom, PDO::PARAM_STR);
        $result->bindParam(':prenom', $prenom, PDO::PARAM_STR);
        $result->bindParam(':date', $dateformat, PDO::PARAM_STR);
        $result->bindParam(':mail', $mail, PDO::PARAM_STR);
        $result->bindParam(':tel', $tel, PDO::PARAM_STR);
        $result->bindParam(':idsection', $idSection, PDO::PARAM_INT);
        if(!$result->execute()){
            throw new Exception('La requête n\'a pas été effectué.');
        }
    }

    public static function getInfoEtudiant(int $idEtudiant) : array {
        if (self::$cnx == null) {
            self::$cnx = DbManager::connect();
        }
        $req = 'select idEtudiant, nom, prenom , DATE_FORMAT(datenaissance, "%d/%m/%Y") AS datenaissance, email, telmobile, idSection ';
        $req .= 'from etudiant ';
        $req .= 'WHERE idEtudiant = :id';
        $result = self::$cnx->prepare($req);
        $result->bindParam(':id', $idEtudiant, PDO::PARAM_INT);
        $result->execute();
        $etudiantInfo = $result->fetch(PDO::FETCH_ASSOC);
        if(!$etudiantInfo){
            throw new UnexpectedValueException("L'étudiant n° {$idEtudiant} n'existe pas.");
        }
        return $etudiantInfo;
    }

    public static function editEtudiant(int $idEtudiant, string $nom, string $prenom, DateTime $date, string $mail, string $tel, int $idSection){
        if (self::$cnx == null) {
            self::$cnx = DbManager::connect();
        }
        $req = 'update etudiant set nom = :nom, prenom = :prenom, datenaissance = :date, email = :mail, telmobile = :tel, idSection = :idsection ';
        $req .= 'where idEtudiant = :id';
        $result = self::$cnx->prepare($req);
        $dateformat = $date->format('Y-m-d');
        $result->bindParam(':nom', $nom, PDO::PARAM_STR);
        $result->bindParam(':prenom', $prenom, PDO::PARAM_STR);
        $result->bindParam(':date', $dateformat, PDO::PARAM_STR);
        $result->bindParam(':mail', $mail, PDO::PARAM_STR);
        $result->bindParam(':tel', $tel, PDO::PARAM_STR);
        $result->bindParam(':idsection', $idSection, PDO::PARAM_INT);
        $result->bindParam(':id', $idEtudiant, PDO::PARAM_INT);
        if(!$result->execute()){
            throw new Exception('La requête n\'a pas été effectué.');
        }
    }
}
