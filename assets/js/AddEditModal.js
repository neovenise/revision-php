const modal = document.getElementById("modalEtudiant");
const bsModal = new bootstrap.Modal(modal);
const validButton = document.getElementById("form-action");
const formInputs = document.getElementsByClassName("form-control");
const formElement = document.getElementById("modal-form");
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
        if (!formElement.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
            formElement.classList.add('was-validated');
        }
        else if(studentId > 0){
            event.preventDefault();
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
    })
