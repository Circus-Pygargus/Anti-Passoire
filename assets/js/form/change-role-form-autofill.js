import '../../styles/form/custom-select.scss';

document.addEventListener("DOMContentLoaded", () => {
    const formToAutoFill = document.querySelector('form[name="change_user_role"]');

    if (formToAutoFill) {
        const autoFillFormBtns = document.querySelectorAll('.sof-form-activator');
        const confirmationModal = document.querySelector('#confirmation-modal');
        const confirmationModalMessageDisplayer = confirmationModal.querySelector('.confirmation-modal_message');
        const confirmationModalConfirmBtn = confirmationModal.querySelector('#modal-confirm');
        const confirmationModalCancelBtn = confirmationModal.querySelector('#modal-cancel');

        const activatorBtnClicked = (event) => {
            const activatorBtn = event.target;
            formToAutoFill.querySelector('#change_user_role_name').value = activatorBtn.dataset.name;
            formToAutoFill.querySelector('#change_user_role_wantedBiggestRole').value = activatorBtn.dataset.roleWanted;
            openConfirmationModal(activatorBtn.dataset.name, activatorBtn.dataset.roleWanted);
        };

        const openConfirmationModal = (name, wantedRole) => {
            confirmationModalMessageDisplayer.innerHTML = `T'es vraiment sûr de vouloir réattribuer le rôle ${wantedRole} à ${name} ??`;
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
