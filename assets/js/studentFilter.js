let table = document.getElementById("table-student");

/***
 * Ecouteur de l'évènement "change", qui écoute si un changement d'option sélectionné à lieu dans la balise "<select>" du javascript.
 */
document.getElementById("section-filter").addEventListener("change",async (event) => {
        while(table.firstChild){
            table.removeChild(table.lastChild);
        }
        let loadingAnimation = document.getElementById("loading");
        loadingAnimation.classList.remove("d-none");
        let selectForm = event.currentTarget;
        let currentSelected = selectForm.options[selectForm.selectedIndex].value;
        //Le fetch a été utilisé différement pour des questions d'expérience.
        let filteredStudents = await fetch("/revision-php/getEtudiantParSection/" + currentSelected + "/").then(response => response.json());
        filteredStudents.forEach(student => addRow(student.id,student.nom,student.prenom,student.datenaissance,student.email));
        loadingAnimation.classList.add("d-none");
        
})

/**
 * Ajoute une nouvelle ligne (étudiant) au tableau HTML avec les informations fournies.
 *
 * @param {string} id - L'identifiant de l'étudiant.
 * @param {string} nom - Le nom de l'étudiant.
 * @param {string} prenom - Le prénom de l'étudiant.
 * @param {string} datenaissance - La date de naissance de l'étudiant au format texte.
 * @param {string} email - L'adresse e-mail de l'étudiant.
 */
function addRow(id,nom,prenom,datenaissance,email){
    //Cette partie n'est d'aucune utilité autre que d'éviter de simplement faire "innerHTML", qui serait 100x plus simple.
    //J'aime me compliquer la vie, merci pour votre compréhension...
    let row = document.createElement("tr");
    row.setAttribute("data-student-id",id);
    for(let i = 0;i < 4;i++){
        row.appendChild(document.createElement("td"));
    }
    row.children[0].textContent = nom;
    row.children[1].textContent = prenom;
    row.children[2].textContent = datenaissance;
    row.children[3].textContent = email;
    let buttonContainer = document.createElement("td");
    buttonContainer.classList.add("d-flex","gap-2","justify-content-center");
    let editButton = document.createElement("button");
    editButton.classList.add("btn","btn-warning","edit");
    editButton.setAttribute("data-bs-toggle","modal");
    editButton.setAttribute("data-bs-target","#modalEtudiant");
    let editIcon = document.createElement("i");
    editIcon.classList.add("bi","bi-pencil-square");
    editButton.appendChild(editIcon);
    editButton.appendChild(document.createTextNode(" Modifier"));
    buttonContainer.appendChild(editButton);
    let deleteButton = document.createElement("button");
    deleteButton.classList.add("btn","btn-danger");
    let deleteIcon = document.createElement("i");
    deleteIcon.classList.add("bi","bi-trash-fill");
    deleteButton.appendChild(deleteIcon);
    deleteButton.appendChild(document.createTextNode(" Supprimer"));
    deleteButton.addEventListener("click", confirmerdelete);
    buttonContainer.appendChild(deleteButton);
    row.appendChild(buttonContainer);
    table.appendChild(row);
}
