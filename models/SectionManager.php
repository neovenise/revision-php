<?php
require_once('Section.php');
require_once('DbManager.php');

class SectionManager
{
    private static ?\PDO $cnx = null;
    private static $lesSections = array();

    /**
     * Obtient la liste de toutes les sections.
     *
     * Cette méthode se connecte à la base de données, exécute une requête pour récupérer
     * les sections, crée des objets Section et les stocke dans un tableau.
     *
     * @return array Un tableau d'objets Section.
     */
    public static function GetLesSections(): array
    {
        self::$cnx = DbManager::connect();
        $req = 'select idSection,libelleSection ';
        $req .= 'from Section';
        $result = self::$cnx->prepare($req);
        $result->execute();
        $result->setFetchMode(PDO::FETCH_ASSOC);
        while ($uneSection = $result->fetch()) {
            self::$lesSections[] = new Section(
                $uneSection['idSection'],
                $uneSection['libelleSection']
            );
        }
        return self::$lesSections;
    }


    /**
     * Obtient une section par son identifiant.
     *
     * Cette méthode recherche d'abord la section dans le tableau des sections existantes.
     * Si elle n'est pas trouvée, elle effectue une requête pour la récupérer depuis la base de données.
     *
     * @param int $unId L'identifiant de la section à rechercher.
     * @return Section Une instance de la classe Section correspondant à l'identifiant donné.
     * @throws Exception Si la section n'est pas trouvée dans la base de données.
     */
    public static function getSectionParId(int $unId): Section
    {
    //Pour éviter des créations de sections inutiles, mais je doute que ça soit nécessaire
    //Edit : Finalement ça l'est ?
        $laSection = false;
        $i = 0;
        while(!$laSection && $i < count(self::$lesSections)){
            if(self::$lesSections[$i]->GetId() == $unId){
                $laSection = self::$lesSections[$i];
            }
            $i++;
        }
        if (!$laSection){
        self::$cnx = DbManager::connect();
        $req = 'select idSection,libelleSection ';
        $req .= 'from Section ';
        $req .= 'where idSection = :idS';
        $result = self::$cnx->prepare($req);
        $result->bindParam("idS",$unId,PDO::PARAM_INT);
        $result->execute();
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $uneSection = $result->fetch();
        if ($uneSection == false) {
            throw new Exception("La requête n'a pas retourné de ligne (La section n'existe pas ?)");
        }
        $laSection = new Section($uneSection['idSection'],$uneSection['libelleSection']);
        self::$lesSections[] = $laSection;
    }
        return $laSection;
    }
}