const modal = document.getElementById("modalEtudiant");
const validButton = document.getElementById("form-action");
const formInputs = document.getElementsByClassName("form-control");
const formElement = document.getElementById("modal-form"); 
console.log(formInputs[0]);

modal.addEventListener('show.bs.modal',async (event) =>{
    let caller = event.relatedTarget;
    formElement.classList.remove("was-validated");
    if (caller.parentNode.parentNode.getAttribute("data-student-id")){
    validButton.value = "Modifier";
    let row = caller.parentNode.parentNode;
    let studentId = row.getAttribute("data-student-id");
    document.getElementById('modal-form').setAttribute('action',"modifier/"+studentId+"/");
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
        document.getElementById('modal-form').setAttribute('action',"ajouter/");
        for (var i = 0; i < formInputs.length ; i++){
            formInputs[i].value = '';
        }
    }
})
formElement.addEventListener('submit', event => {
        console.log("test");
        if (!formElement.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
            formElement.classList.add('was-validated');
        }
    })
