<?php
require_once('.\models\EtudiantManager.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous" />
    <title>Back-Office</title>
</head>

<body>
    <?php

    ?>
    <div class="container py-5">
        <h1>Liste des étudiants</h1>
        
        <p class="my-0">Filtre :</p>
        <form method="get" action="" class="mb-4 d-flex gap-2">
        
        <select class="form-select w-50" name="section">
            <option value="all">Tous</option>
            <?php
            $sectionHtml = "";
            foreach($listeSections as $uneSection){
                $sectionHtml .= "<option value=".$uneSection->GetID();
                if ($idSectionFiltre == $uneSection->GetID()){
                    $sectionHtml.= ' selected';
                } 
                $sectionHtml.= ">".$uneSection->GetLibelle()."</option>";
            }
            echo $sectionHtml;
            ?>
           
        </select>
        <input type="submit" class="btn btn-primary" value="Filtrer"></input>
        </form>

        <table class="table table-bordered table-striped">
                <thead class="table-primary">
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Date de naissance</th>
                    <th>Email</th>
                </thead>
            <tbody>
            <?php
            $etudiantHtml = '';
            
            foreach($etudiants as $unEtudiant){
                $etudiantHtml.= '<tr>';
                $etudiantHtml.= '<td>' .$unEtudiant->GetNom().'</td>';
                $etudiantHtml.= '<td>' .$unEtudiant->GetPrenom().'</td>';
                $etudiantHtml.= '<td>' .$unEtudiant->GetDateNaissance()->format('d/m/Y').'</td>';
                $etudiantHtml.= '<td>' .$unEtudiant->GetMail().'</td>';
                $etudiantHtml.= '</tr>';
            }
            echo $etudiantHtml;
            ?>
            </tbody>
        </table>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm"
    crossorigin="anonymous"></script>

</html>