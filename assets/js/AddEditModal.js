const modal = document.getElementById("modalEtudiant");
const addButton = document.getElementsByClassName("edit");

modal.addEventListener('show.bs.modal',async (event) =>{
    let caller = event.relatedTarget;
    console.log(caller.parentNode.parentNode.getAttribute("data-student-id"));
    if (caller.parentNode.parentNode.getAttribute("data-student-id")){
    let row = caller.parentNode.parentNode;
    let studentId = row.getAttribute("data-student-id");
    let response = await fetch("http://localhost/revision-php/infoEtudiant/"+studentId+"/");
    let etudiant = await response.json();
    console.log(etudiant);
    document.getElementById("modal-nom").value = etudiant['nom'];
    document.getElementById("modal-prenom").value = etudiant['prenom'];
    document.getElementById("modal-datenaissance").value = etudiant['datenaissance'];
    document.getElementById("modal-mail").value = etudiant['email'];
    document.getElementById("modal-tel").value = etudiant['telmobile'];
    let sectionOptions = document.getElementById("modal-section").options
    for (var i = 0; i < sectionOptions.length ; i++){
        if (sectionOptions[i].value == etudiant['idSection']){
            sectionOptions.selectedIndex = i;
        }
    }
    }

})