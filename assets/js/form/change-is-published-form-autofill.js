document.addEventListener("DOMContentLoaded", () => {
    const formToAutoFill = document.querySelector('form[name="change_anti_passoire_is_published"]');

    if (formToAutoFill && document.querySelector('.is-admin')) {
        const autoFillFormBtns = document.querySelectorAll('.sof-form-activator');
        const confirmationModal = document.querySelector('#confirmation-modal');
        const confirmationModalMessageDisplayer = confirmationModal.querySelector('.confirmation-modal_message');
        const confirmationModalConfirmBtn = confirmationModal.querySelector('#modal-confirm');
        const confirmationModalCancelBtn = confirmationModal.querySelector('#modal-cancel');

        const activatorBtnClicked = (event) => {
            const activatorBtn = event.target;
            formToAutoFill.querySelector('#change_anti_passoire_is_published_slug').value = activatorBtn.dataset.slug;
            formToAutoFill.querySelector('#change_anti_passoire_is_published_isPublished').value = activatorBtn.dataset.isPublishedWanted;
            openConfirmationModal(activatorBtn.dataset.slug, activatorBtn.dataset.isPublishedWanted);
        };

        const openConfirmationModal = (slug, isPublishedWanted) => {
            confirmationModalMessageDisplayer.innerHTML = `T'es vraiment sûr de vouloir modifier la possibilité de trouver cette aide ??`;
            confirmationModal.classList.remove('d-none');
        };

        const sendForm = () => {
            formToAutoFill.submit();
        };

        const hideConfirmationModal = () => {
            confirmationModal.classList.add('d-none');
        };
        autoFillFormBtns.forEach(activatorBtn => {
            activatorBtn.addEventListener('click', activatorBtnClicked);
        })

        confirmationModalConfirmBtn.addEventListener('click', sendForm);

        confirmationModalCancelBtn.addEventListener('click', hideConfirmationModal);
    }
});
