    <?php
    require_once('Controller.php');
    require_once('./models/EtudiantManager.php');
    require_once('./models/SectionManager.php');
    class EtudiantController extends Controller
    {
        /**
        * Affiche la liste des étudiants avec la possibilité de filtrer par section.
        *
        * @param array $params Les paramètres de la requête, 'section' en option pour filtrer par section.
        * @return void
        */
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

        /**
        * Supprime un étudiant en fonction de son identifiant.
        *
        * @param array $params Les paramètres de la requête, avec 'id' comme clé obligatoire.
        * @return void
        * @throws UnexpectedValueException Si la valeur de 'id' n'est pas un entier.
        */
        public static function delete($params)
        {
            try {
                if (ctype_digit($params['id'])){
                    EtudiantManager::SupprimerUnEtudiant(intval($params['id'], 10));
                    http_response_code(200);
                    exit();
                }
                else{
                    throw new UnexpectedValueException('La valeur passé n\'est pas un id.');
                }
            } catch (Exception $ex)
            {
                echo "Erreur : " + $ex->getMessage();
                http_response_code(500);
                exit();
            }
        }

        /**
        * Ajoute un nouvel étudiant en fonction des paramètres fournis.
        *
        * @param array $params Les paramètres de la requête, avec les clés obligatoires 'nom', 'prenom', 'datenaissance', 'mail', 'tel', 'idSection'.
        * @return void
        * @throws Exception Si une des clés obligatoires est manquante ou en cas d'autres erreurs lors de l'ajout de l'étudiant.
        */
        public static function add($params)
        {
            try {
                // Vérifie si toutes les clés de tableau obligatoires existent
                $requiredKeys = ['nom', 'prenom', 'datenaissance', 'mail', 'tel', 'idSection'];
                foreach ($requiredKeys as $key) {
                    if (!array_key_exists($key, $params)) {
                        throw new Exception("La clé de tableau '$key' est obligatoire.");
                    }
                }
                $params['nom'] = htmlspecialchars($params['nom']);
                $params['prenom'] = htmlspecialchars($params['prenom']);
                $params['datenaissance'] = DateTime::createFromFormat("Y-m-d", $params['datenaissance']);
                $params['mail'] = filter_var($params['mail'], FILTER_SANITIZE_EMAIL);
                $params['tel'] = htmlspecialchars($params['tel']);
                $params['idSection'] = intval(filter_var($params['idSection'], FILTER_SANITIZE_NUMBER_INT));
                $idStudent = EtudiantManager::AjouterUnEtudiant($params['nom'],$params['prenom'],$params['datenaissance'],$params['mail'],$params['tel'],$params['idSection']);
                print(json_encode(array("id"=>$idStudent)));
                http_response_code(200);
                exit();
                
            } catch (Exception $ex) {
                echo $ex->getMessage();
                http_response_code(500);
                exit();
            }
        }

        /**
        * Obtient les informations d'un étudiant et les renvoie au format JSON.
        *
        * @param array $params Les paramètres de la requête, avec 'idEtudiant' comme clé obligatoire.
        * @throws Exception En cas d'erreur lors de l'obtention des informations de l'étudiant.
        */
        public static function getInfoEtudiant($params){
            try{
                $infoEtudiant = EtudiantManager::getInfoEtudiant($params['idEtudiant']);
                print(json_encode($infoEtudiant));
            }catch (Exception $ex) {
                echo "Erreur lors de l'obtention de l'étudiant : {$ex->getMessage()}";
            }
        }

        /**
        * Modifie les informations d'un étudiant en fonction des paramètres fournis.
        *
        * @param array $params Les paramètres de la requête, avec les clés obligatoires 'id', 'nom', 'prenom', 'datenaissance', 'mail', 'tel', 'idSection'.
        * @throws Exception En cas d'erreur lors de la modification de l'étudiant.
        */
        public static function editEtudiant($params){
        try {
        $requiredKeys = ['id', 'nom', 'prenom', 'datenaissance', 'mail', 'tel', 'idSection'];
        foreach ($requiredKeys as $key) {
            if (!array_key_exists($key, $params)) {
                throw new Exception("La clé de tableau '$key' est obligatoire.");
            }
        }
        $params['id'] = intval(filter_var($params['id'], FILTER_SANITIZE_NUMBER_INT));
        $params['nom'] = htmlspecialchars($params['nom']);
        $params['prenom'] = htmlspecialchars($params['prenom']);
        $params['datenaissance'] = DateTime::createFromFormat("d/m/Y", $params['datenaissance']);
        $params['mail'] = filter_var($params['mail'], FILTER_SANITIZE_EMAIL);
        $params['tel'] = htmlspecialchars($params['tel']);
        $params['idSection'] = intval(filter_var($params['idSection'], FILTER_SANITIZE_NUMBER_INT));
        EtudiantManager::editEtudiant($params['id'],$params['nom'],$params['prenom'],$params['datenaissance'],$params['mail'],$params['tel'],$params['idSection']);
        http_response_code(200);
        exit();
        }
        catch (Exception $ex) {
        // Envoie une réponse HTTP 500 Internal Server Error
        echo $ex->getMessage();
        http_response_code(500);
        exit();
        }
    }

     /**
     * Obtient la liste des étudiants d'une section et renvoie les données au format JSON.
     *
     * @param array $params Les paramètres de la requête, avec 'section' comme clé facultative. Si non fourni ou égal à 'all', toutes les sections seront incluses.
     * @throws Exception En cas d'erreur lors de l'obtention des étudiants par section.
     */
    public static function getEtudiantParSectionJSON($params){
        $lesEtudiants = array();
        try{
            /*if (!array_key_exists('section',$params)){
                throw new Exception("Aucune id de section n'a été précisée.");
            }*/
            if($params['section'] != 'all'){
                $params['section'] = intval(filter_var($params['section'], FILTER_SANITIZE_NUMBER_INT));
                $lesEtudiants = EtudiantManager::GetLesEtudiantsParSection(SectionManager::getSectionParId($params['section']));
            }
            else{
                $lesEtudiants = EtudiantManager::GetLesEtudiants();
            }
            $etudiantsFiltre = array();
            foreach($lesEtudiants as $etudiant){
                $etudiantsFiltre[] = array(
                    'id' => $etudiant->GetID(),
                    'nom' => $etudiant->GetNom(),
                    'prenom' => $etudiant->GetPrenom(),
                    'datenaissance' => $etudiant->GetDateNaissance()->format('d/m/Y'),
                    'email' => $etudiant->GetMail()
                );
            }
            print(json_encode($etudiantsFiltre));
            http_response_code(200);
            exit();
        }
        catch(Exception $ex){
            echo $ex->getMessage();
            http_response_code(500);
            exit();
        }
    }
}