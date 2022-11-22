export default (() => {

    /**
     * When a checkbox is checked, add the "checked" attribute to the element (validationElement).
     * If there are no checked checkboxes within a group, remove the attribute.
     */
    const addListeners = () => {
        document.querySelectorAll('.checkbox-group-required').forEach(checkboxGroup => {
            const checkboxes = checkboxGroup.querySelectorAll('.c-option__checkbox--hidden-box');
            let validationElement = checkboxGroup.querySelector('.js-checkbox-valid');

            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', () => {
                    let validator = checkboxGroup.querySelectorAll('.c-option [type="checkbox"]:checked');
                    if(validator.length > 0) {
                        validationElement.setAttribute('checked', true);
                        checkboxGroup.querySelector('label').classList.remove('u-color__text--danger');
                    } else {
                        validationElement.removeAttribute('checked');
                    }
                })
            })
        })
    }

/**
 * Initiates the listener
 */
    const init = () => {
        addListeners();
    }

    return init();
})();