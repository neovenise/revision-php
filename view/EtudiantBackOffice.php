<!DOCTYPE html>
<?php
require_once('.\models\EtudiantManager.php');
$sectionHtml = "";
foreach ($listeSections as $uneSection) {
    $sectionHtml .= "<option value=" . $uneSection->GetID();
    if ($idSectionFiltre == $uneSection->GetID()) {
        $sectionHtml .= ' selected';
    }
    $sectionHtml .= ">" . $uneSection->GetLibelle() . "</option>";
}
?>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <title>Back-Office</title>
</head>

<body>

    <div class="modal fade" id="modalEtudiant" tabindex="-1" aria-labelledby="modalEtudiant" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Fiche étudiant</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="modal-form" novalidate>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <label for="nom" class="col-sm-4 col-form-label">Nom</label>
                            <div class="col-sm-8">
                                <input type="text" name="nom" id="modal-nom" class="form-control" placeholder="Nom étudiant" required/>
                                <div class="invalid-feedback">
                                Veuillez saisir un nom.
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="prenom" class="col-sm-4 col-form-label">Prénom</label>
                            <div class="col-sm-8">
                                <input type="text" name="prenom" id="modal-prenom" class="form-control" placeholder="Prénom étudiant" required/>
                                <div class="invalid-feedback">
                                Veuillez saisir un prénom.
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="datenaissance" class="col-sm-4 col-form-label">Date de naissance</label>
                            <div class="col-sm-8">
                                <input type="text" name="datenaissance" id="modal-datenaissance" class="form-control" placeholder="JJ/MM/AAAA" pattern="\d{2}/\d{2}/\d{4}" required/> <!-- Il y a d'autres manières de vérifier que par des expressions régulières, mais restons dans la simplicité. -->
                                <div class="invalid-feedback">
                                Veuillez saisir une date de naissance valide. (JJ/MM/AAAA)
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="mail" class="col-sm-4 col-form-label">Email</label>
                            <div class="col-sm-6">
                                <input type="mail" name="mail" id="modal-mail" class="form-control" placeholder="Email" required/>
                                <div class="invalid-feedback">
                                Veuillez saisir un email valide.
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="tel" class="col-sm-4 col-form-label">Tél. mobile</label>
                            <div class="col-sm-6">
                                <input type="text" name="tel" id="modal-tel" class="form-control" placeholder="+330699517103" pattern="^\+33\d{10}$" required/>
                                <div class="invalid-feedback">
                                Veuillez saisir un numéro de téléphone, il doit commencer par "+33".
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="section" class="col-sm-4 col-form-label">Section</label>
                            <div class="col-sm-6">
                                <select class="form-select" name="idSection" id="modal-section">
                                    <?php echo $sectionHtml; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="submit" id="form-action" class="btn btn-primary" value="Envoyer" />
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="container py-5">
        <h1>Liste des étudiants</h1>

        <p class="my-0">Filtre :</p>
        <form method="get" class="mb-4 d-flex gap-2">
            <select class="form-select w-50" name="section">
                <option value="all">Tous</option>
                <?php
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
                <th>Action</th>
            </thead>
            <tbody id="table-student">
                <?php
                $etudiantHtml = '';

                foreach ($etudiants as $unEtudiant) {
                    $etudiantHtml .= '<tr data-student-id="' . $unEtudiant->GetID() . '">';
                    $etudiantHtml .= '<td>' . $unEtudiant->GetNom() . '</td>';
                    $etudiantHtml .= '<td>' . $unEtudiant->GetPrenom() . '</td>';
                    $etudiantHtml .= '<td>' . $unEtudiant->GetDateNaissance()->format('d/m/Y') . '</td>';
                    $etudiantHtml .= '<td>' . $unEtudiant->GetMail() . '</td>';
                    $etudiantHtml .= '<td class="d-flex gap-2 justify-content-center"><button class="btn btn-warning edit" data-bs-toggle="modal" data-bs-target="#modalEtudiant"><i class="bi bi-pencil-square"></i> Modifier</button><button class="btn btn-danger delete"><i class="bi bi-trash-fill"></i> Supprimer</button></td>';
                    $etudiantHtml .= '</tr>';
                }
                echo $etudiantHtml;
                ?>
            </tbody>
        </table>
        <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#modalEtudiant">
            <i class="bi bi-plus"></i> Ajouter
        </button>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<script src="<?php echo '/revision-php/assets/js/confirmDelete.js' ?>"></script>
<script src="<?php echo '/revision-php/assets/js/AddEditModal.js' ?>"></script>

</html>