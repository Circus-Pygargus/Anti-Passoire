/** @Todo : add style : see in other style files and refacto */

// js
import './pagination';


document.addEventListener("DOMContentLoaded", () => {

    // Il y a des * juste après les labels des boutons radio
    const radioButtonsLabels = document.querySelectorAll('.radio-group div label');
    radioButtonsLabels.forEach(radioBtnLabel => {
        radioBtnLabel.classList.remove('required');
    });

    // actions du bouton d'affichage ou non des options de recherches avancées (max par page, trier par, ordre d'affichage)
    const searcher = document.querySelector('.search');
    if (searcher) {
        const displayerBtn = document.querySelector('.displayer-btn');
        const hideableDisplayer = searcher.querySelector('.hideable');
        displayerBtn.addEventListener('click', () => {
            searcher.classList.toggle('opened');
            hideableDisplayer.classList.toggle('hidden');
        });
    }
});