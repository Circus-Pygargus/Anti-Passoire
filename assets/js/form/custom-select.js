import '../../styles/form/custom-select.scss';

document.addEventListener("DOMContentLoaded", () => {
    // pas vraiment en rapport mais il y a des * juste après les labels des boutons radio
    const radioButtonsLabels = document.querySelectorAll('input[type="radio"] + label');
    radioButtonsLabels.forEach(radioBtnLabel => {
        radioBtnLabel.classList.remove('required');
    });

    const selects = document.querySelectorAll('select.custom-select-wanted');
    const customSelects = [];

    const getRelativeSelect = (item) => {
        const selectId = getRelativeCustomSelect(item).dataset.relativeSelectId;
        let selectToReturn;
        selects.forEach(select => {
            if (select.getAttribute('id') === selectId) {
                selectToReturn = select;
            }
        });
        return selectToReturn;
    };

    const getRelativeCustomSelect = (item) => {
        return item.closest('.custom-select');
    }

    const getRelativeDisplayer = (option) => {
        return getRelativeCustomSelect(option).querySelector('.custom-select-selected-list');
    }

    const openCustomSelect = (customSelect) => {
        closeAllCustomSelects();
        customSelect.classList.toggle('opened');
    };

    const closeAllCustomSelects = () => {
        customSelects.forEach(customSelect => customSelect.classList.remove('opened'));
    };

    const searchForCustomSelectLabelClicked = (event) => {
        let isClickForCustomSelect = false;
        customSelects.forEach(customSelect => {
            if (event.target.getAttribute('for') === customSelect.dataset.relativeSelectId) {
                event.preventDefault();
                isClickForCustomSelect = true;
                if (!customSelect.classList.contains('opened')) {
                    openCustomSelect(customSelect);
                }
            }
        });

        const clickAlreadyHandled = event.target.classList.contains('custom-select-selected-list');

        if (!isClickForCustomSelect && !clickAlreadyHandled) {
            closeAllCustomSelects()
        }
    }

    const customSelectDisplayerClicked = (event) => {
        const relativeCustomSelect = getRelativeCustomSelect(event.target);
        if (relativeCustomSelect.classList.contains('opened')) {
            relativeCustomSelect.classList.remove('opened');
        } else {
            openCustomSelect(relativeCustomSelect);
        }
    };

    const customSelectDisplayerItemClicked = (event) => {
        event.stopPropagation();
        customSelectDisplayerClicked(event);
    };

    const customOptionClicked = (event) => {
        const select = getRelativeSelect(event.target);
        const customSelectDisplayer = getRelativeDisplayer(event.target);
        if (getRelativeDisplayer(event.target).dataset.multiple) {
            Array.from(select).forEach(option => {
                if (option.value === event.target.dataset.value) option.selected = !option.selected;
            });
            let createNewItem = true;
            customSelectDisplayer.querySelectorAll('.custom-select-selected-item').forEach(item => {
                if (item.dataset.value == event.target.dataset.value) {
                    createNewItem = false;
                    item.remove();
                }
            });
            if (createNewItem) customSelectDisplayer.appendChild(createCustomSelectDisplayerItem(event.target.dataset.value, event.target.innerHTML));
        } else {
            select.value = event.target.dataset.value;
            // select.onChange();
            customSelectDisplayer.innerHTML = '';
            customSelectDisplayer.appendChild(createCustomSelectDisplayerItem(event.target.dataset.value, event.target.innerHTML));
        }
        event.target.classList.toggle('selected');
    };

    const createCustomSelectDisplayerItem = (value, displayedText) => {
        const customSelectDisplayerItem = document.createElement('LI');
        customSelectDisplayerItem.classList.add('custom-select-selected-item');
        customSelectDisplayerItem.dataset.value = value;
        customSelectDisplayerItem.innerHTML = displayedText;
        customSelectDisplayerItem.addEventListener('click', customSelectDisplayerItemClicked);
        return customSelectDisplayerItem;
    };

    const createCustomSelect = () => {
        selects.forEach(select => {
            const customSelect = document.createElement('DIV');
            customSelect.classList.add('custom-select');
            customSelect.dataset.relativeSelectId = select.id;

            const customSelectDisplayer = document.createElement('UL');
            customSelectDisplayer.classList.add('custom-select-selected-list');
            if (select.getAttribute('multiple')) {
                customSelect.classList.add('multiple');
                customSelectDisplayer.dataset.multiple = '1';
            }
            Array.from(select.selectedOptions).forEach(selectedOption => {
                const customSelectDisplayerItem = createCustomSelectDisplayerItem(selectedOption.value, selectedOption.innerHTML);
                customSelectDisplayer.appendChild(customSelectDisplayerItem);
            });
            customSelectDisplayer.addEventListener('click', customSelectDisplayerClicked);
            customSelect.appendChild(customSelectDisplayer);

            const customOptionssBox = document.createElement('UL');
            customOptionssBox.classList.add('custom-select-option-list');
            Array.from(select).forEach(option => {
                const customOption = document.createElement('LI');
                customOption.classList.add('custom-select-option');
                customOption.dataset.value = option.value;
                customOption.innerHTML = option.innerHTML;
                customOption.addEventListener('click', customOptionClicked);
                customOptionssBox.appendChild(customOption);
            });
            customSelect.appendChild(customOptionssBox);

            customSelects.push(customSelect);

            select.classList.add('d-none');
            select.parentElement.insertBefore(customSelect, select);
        });
    }

    document.addEventListener('click', searchForCustomSelectLabelClicked);

    createCustomSelect();
});
