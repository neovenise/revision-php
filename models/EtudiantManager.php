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
        $req = 'select idEtudiant, nom, prenom , datenaissance, email, telmobile, Etudiant.idSection ';
        $req .= 'from etudiant ';
        $req .= 'join Section ON Etudiant.idSection = Section.idSection ';
        $req .= 'WHERE Etudiant.idSection = :Section';
        $result = self::$cnx->prepare($req);
        //Récupération du libelle pour éviter une erreur PHP1801.
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

    public static function SupprimerUnEtudiant(Etudiant $unEtudiant)
    {
        if (self::$cnx == null) {
            self::$cnx = DbManager::connect();
        }
        $req = 'delete from etudiant where idEtudiant = :id';
        $result = self::$cnx->prepare($req);
        $idEtudiant = $unEtudiant->GetID();
        $result->bindParam(':id', $idEtudiant, PDO::PARAM_INT);
        $nbLignes = $result->execute();
        if ($nbLignes != 1) {
            throw new Exception("Erreur : Le nombre de ligne affectés lors de la suppression n'est pas celui attendu : " + $nbLignes);
        }

    }

}