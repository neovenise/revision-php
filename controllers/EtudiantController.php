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
                $params['datenaissance'] = DateTime::createFromFormat("d/m/Y", $params['datenaissance']);
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

        public static function getInfoEtudiant($params){
            try{
                $infoEtudiant = EtudiantManager::getInfoEtudiant($params['idEtudiant']);
                print(json_encode($infoEtudiant));
            }catch (Exception $ex) {
                echo "Erreur lors de l'obtention de l'étudiant : {$ex->getMessage()}";
            }
        }

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
        // Envoyer une réponse HTTP 500 Internal Server Error
        echo $ex->getMessage();
        http_response_code(500);
        exit();
        }
    }
}