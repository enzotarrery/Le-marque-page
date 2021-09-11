/* comportements.js */

document.addEventListener("DOMContentLoaded", main);

function main() {

    // On récupère le bouton de modification
    let updateButtons = document.querySelectorAll("button.update");

    // On empeche le reload
    updateButtons.forEach(button => {button.addEventListener("click", function(event) {event.preventDefault()})});

    // On affecte un gestionnaire d'évènement pour un clique sur le bouton
    updateButtons.forEach(button =>  {button.addEventListener("click", getData)});

    // Le gestionnaire d'évènement
    function getData() {
        // On récupère les données nécessaires
        let parent = this.closest("tr");
        let data = parent.querySelectorAll("td");

        // On passe le formulaire en mode modification
        let submit = document.getElementById("send");
        submit.setAttribute("name", "update");

        // On récupère les éléments du formulaire
        let inputs = document.querySelectorAll("tfoot input, select");

        // On place les infos
        for (i=0; i<inputs.length-1; i++) {
            if (!inputs[i].options) {
                inputs[i].value = data[i].textContent;
            }
        }
    }
}