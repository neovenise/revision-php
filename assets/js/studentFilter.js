// TODO: Finir le filtrage d'étudiant par section
/*
document.getElementById("section-filter").addEventListener("change",async (event) => {
        let selectForm = event.currentTarget;
        let currentSelected = selectForm.options[selectForm.selectedIndex].value;
        if (currentSelected = "all"){
        
        }
})
*/


function addRow(id,nom,prenom,datenaissance,email){
    //Cette partie n'est d'aucune utilité autre que d'éviter de simplement faire "innerHTML", qui serait 100x plus simple.
    //J'aime me compliquer la vie, merci pour votre compréhension...
    let table = document.getElementById("table-student");
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
