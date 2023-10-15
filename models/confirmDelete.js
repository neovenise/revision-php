var btnSupprimer = document.querySelectorAll(".delete");

btnSupprimer.forEach(bouton => bouton.addEventListener("click",confirmerdelete));

function confirmerdelete(caller){
    //Je suis prêt à accepter les points en moins pour cette obsession absolument pas optimal
    console.log(caller.currentTarget);
    let ligne = caller.currentTarget.parentNode.parentNode;
    console.log(ligne);
    let nom = ligne.childNodes[0].textContent;
    console.log(nom);
    let prenom = ligne.childNodes[1].textContent;
    if(confirm("Êtes-vous sûr de vouloir supprimer " + nom + " " + prenom + " ?" )){
        window.location.href = "revision-php/supprimer/" + ligne.getAttribute("data-student-id");
     }


}