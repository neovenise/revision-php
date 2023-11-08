let btnSupprimer = document.querySelectorAll(".delete");

btnSupprimer.forEach((bouton) => bouton.addEventListener("click", confirmerdelete));
    
    async function confirmerdelete(event){
        let ligne = event.currentTarget.parentNode.parentNode;
        let nom = ligne.childNodes[0].textContent;
        let prenom = ligne.childNodes[1].textContent;
        if (confirm("Êtes-vous sûr de vouloir supprimer " + nom + " " + prenom + " ?"))
        {   
            let response = await fetch("/revision-php/supprimer/" + ligne.getAttribute("data-student-id"));
            if(response.ok){
                ligne.remove();
            } 
        }
    };
