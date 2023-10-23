var btnSupprimer = document.querySelectorAll(".delete");

btnSupprimer.forEach((bouton) =>
    bouton.addEventListener("click", confirmerdelete)
);

function confirmerdelete(caller) {
    //Je suis prêt à accepter les points en moins pour cette obsession absolument pas optimal
    let ligne = caller.currentTarget.parentNode.parentNode;
    let nom = ligne.childNodes[0].textContent;
    let prenom = ligne.childNodes[1].textContent;
    if (
        confirm(
            "Êtes-vous sûr de vouloir supprimer " + nom + " " + prenom + " ?"
        )
    ) {
        window.location.href =
            "supprimer/" + ligne.getAttribute("data-student-id");
    }
}
