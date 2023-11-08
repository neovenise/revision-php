<?php
require_once('DbManager.php');
require_once('Etudiant.php');

/**
 * Classe EtudiantManager
 * Gère les opérations liées aux étudiants dans la base de données.
 */
class EtudiantManager
{
    private static ?\PDO $cnx = null;
    
    /**
     * Récupère la liste des étudiants depuis la base de données.
     *
     * @return array Un tableau d'objets Etudiant représentant les étudiants.
     */
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

     /**
     * Récupère la liste des étudiants d'une section spécifique depuis la base de données.
     *
     * @param Section $uneSection L'objet Section pour filtrer les étudiants.
     * @return array Un tableau d'objets Etudiant représentant les étudiants de la section.
     */
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

    /**
     * Supprime un étudiant de la base de données en fonction de son ID.
     *
     * @param int $idEtudiant L'ID de l'étudiant à supprimer.
     * @throws Exception Si une erreur se produit lors de la suppression.
     */
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

    /**
     * Ajoute un nouvel étudiant dans la base de données.
     *
     * @param string $nom Le nom de l'étudiant.
     * @param string $prenom Le prénom de l'étudiant.
     * @param DateTime $date La date de naissance de l'étudiant.
     * @param string $mail L'adresse e-mail de l'étudiant.
     * @param string $tel Le numéro de téléphone mobile de l'étudiant.
     * @param int $idSection L'ID de la section à laquelle l'étudiant est associé.
     * @throws Exception Si une erreur se produit lors de l'ajout.
     */
    public static function AjouterUnEtudiant(string $nom, string $prenom, DateTime $date, string $mail, string $tel, int $idSection):int
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
        $req2 = 'select LAST_INSERT_ID() as id';
        $result = self::$cnx->prepare($req2);
        $result->execute();
        $idEtudiant = $result->fetch()['id'];
        return $idEtudiant;
    }

    /**
     * Récupère les informations d'un étudiant en fonction de son ID.
     *
     * @param int $idEtudiant L'ID de l'étudiant dont les informations sont demandées.
     * @return array Un tableau d'informations sur l'étudiant.
     * @throws UnexpectedValueException Si l'étudiant avec l'ID donné n'existe pas.
     */
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

    /**
     * Modifie les informations d'un étudiant en fonction de son ID.
     *
     * @param int $idEtudiant L'ID de l'étudiant à modifier.
     * @param string $nom Le nouveau nom de l'étudiant.
     * @param string $prenom Le nouveau prénom de l'étudiant.
     * @param DateTime $date La nouvelle date de naissance de l'étudiant.
     * @param string $mail La nouvelle adresse e-mail de l'étudiant.
     * @param string $tel Le nouveau numéro de téléphone mobile de l'étudiant.
     * @param int $idSection Le nouvel ID de la section à laquelle l'étudiant est associé.
     * @throws Exception Si une erreur se produit lors de la modification.
     */
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
