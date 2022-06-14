/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

// start the Stimulus application
import './bootstrap';


const paginationElems = document.querySelectorAll('#pagination li.clickable');
const searcherForm = document.querySelector('[name="search_anti_passoire"]');

const requestAnotherPage = (event) => {
    const searcherPageNumberInput = searcherForm.querySelector('#search_anti_passoire_pageNumber');
    searcherPageNumberInput.value = event.target.dataset.pageNumber;
    searcherForm.submit()
};

if (searcherForm) {
    searcherForm.querySelector('button[type="submit"]')
        .addEventListener('click', (event) => {
            event.preventDefault();
            searcherForm.querySelector('#search_anti_passoire_pageNumber').value = 1;
            searcherForm.submit();
        })
    ;
}

if (paginationElems.length) {
    paginationElems.forEach(elem => elem.addEventListener('click', requestAnotherPage));
}
