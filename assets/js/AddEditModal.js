const modal = document.getElementById("modalEtudiant");
const bsModal = new bootstrap.Modal(modal);
const validButton = document.getElementById("form-action");
const formInputs = document.getElementsByClassName("form-control");
const formElement = document.getElementById("modal-form");
const sectionFilter = document.getElementById("section-filter");
let isEditing = false;
let studentId = 0;

modal.addEventListener('show.bs.modal',async (event) =>{
    let caller = event.relatedTarget;
    formElement.classList.remove("was-validated");
    if (caller.parentNode.parentNode.getAttribute("data-student-id")){
    validButton.value = "Modifier";
    let row = caller.parentNode.parentNode;
    studentId = row.getAttribute("data-student-id");
    isEditing = true;
    let response = await fetch("http://localhost/revision-php/infoEtudiant/"+studentId+"/");
    let etudiant = await response.json();
    document.getElementById("modal-nom").value = etudiant['nom'];
    document.getElementById("modal-prenom").value = etudiant['prenom'];
    document.getElementById("modal-datenaissance").value = etudiant['datenaissance'];
    document.getElementById("modal-mail").value = etudiant['email'];
    document.getElementById("modal-tel").value = etudiant['telmobile'];
    let sectionOptions = document.getElementById("modal-section").options;
    for (var i = 0; i < sectionOptions.length ; i++){
        if (sectionOptions[i].value == etudiant['idSection']){
            sectionOptions.selectedIndex = i;
        }
    }
    }
    else{
        validButton.value = "Ajouter";
        studentId = 0;
        for (var i = 0; i < formInputs.length ; i++){
            formInputs[i].value = '';
        }
    }
})
formElement.addEventListener('submit', async (event) => {
        event.preventDefault();
        event.stopPropagation();
        if (!formElement.checkValidity()) {
            formElement.classList.add('was-validated');
        }
        else if(studentId > 0){
            //après analyse ça sert à rien de tout mettre dans des variables
            let nom = document.getElementById("modal-nom").value;
            let prenom = document.getElementById("modal-prenom").value;
            let datanaissance = document.getElementById("modal-datenaissance").value;
            let email = document.getElementById("modal-mail").value;
            let telmobile = document.getElementById("modal-tel").value;
            let idSection = document.getElementById("modal-section").value;
            const studentEdit =  {
            "id": studentId,
            "nom" : nom,
            "prenom" : prenom,
            "datenaissance" : datanaissance,
            "mail" : email,
            "tel" : telmobile,
            "idSection" : idSection};
                

            let response = await fetch("http://localhost/revision-php/modifier/",{
                method: "POST",
                headers:{
                    "Content-Type": "application/json"
                },
                redirect:"follow", //?
                body:JSON.stringify(studentEdit)
            });

            if(response.ok){
                let table = document.getElementById("table-student");
                for(let child of table.children){
                    if(child.getAttribute("data-student-id") == studentId){
                        child.children[0].textContent = nom;
                        child.children[1].textContent = prenom;
                        child.children[2].textContent = datanaissance;
                        child.children[3].textContent = email;
                    }
                }
                
                bsModal.hide();
            }

        }
        else{
            let nom = document.getElementById("modal-nom").value;
            let prenom = document.getElementById("modal-prenom").value;
            let datenaissance = document.getElementById("modal-datenaissance").value;
            let email = document.getElementById("modal-mail").value;
            let telmobile = document.getElementById("modal-tel").value;
            let idSection = document.getElementById("modal-section").value;
            const studentEdit =  {
                "nom" : nom,
                "prenom" : prenom,
                "datenaissance" : datenaissance,
                "mail" : email,
                "tel" : telmobile,
                "idSection" : idSection};
                let response = await fetch("http://localhost/revision-php/ajouter/",{
                    method: "POST",
                    headers:{
                        "Content-Type": "application/json"
                    },
                    redirect:"follow", //?
                    body:JSON.stringify(studentEdit)
                });
            
            if(response.ok){
                console.log(sectionFilter.options[sectionFilter.selectedIndex].value);
                if(sectionFilter.options[sectionFilter.selectedIndex].value == 'all' || sectionFilter.options[sectionFilter.selectedIndex].value == idSection){
                    let studentId = response.json()['id'];
                    let table = document.getElementById("table-student");
                    //Cette partie n'est d'aucune utilité autre que d'éviter de simplement faire "innerHTML", qui serait 100x plus simple.
                    //J'aime me compliquer la vie, merci pour votre compréhension...
                    let row = document.createElement("tr");
                    row.setAttribute("data-student-id",studentId);
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
                    editButton.textContent = "Modifier";
                    let editIcon = document.createElement("i");
                    editIcon.classList.add("bi","bi-pencil-square");
                    editButton.appendChild(editIcon);
                    buttonContainer.appendChild(editButton);
                    let deleteButton = document.createElement("button");
                    deleteButton.classList.add("btn","btn-danger");
                    deleteButton.textContent = "Supprimer"
                    let deleteIcon = document.createElement("i");
                    deleteIcon.classList.add("bi","bi-trash-fill");
                    deleteButton.appendChild(deleteIcon);
                    buttonContainer.appendChild(deleteButton);
                    row.appendChild(buttonContainer);
                    table.appendChild(row);
                    bsModal.hide();
                }
            }
        }

    })
