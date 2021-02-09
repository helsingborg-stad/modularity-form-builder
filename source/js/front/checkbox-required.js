export default (() => {

    /*
    ** Will validate the checkbox for checkbox groups
    */
    const getCheckBoxGroups = () => {
        document.querySelectorAll('.checkbox-group-required').forEach(group => {
            const checkboxes = group.querySelectorAll('.c-option [type="checkbox"]:checked');
            const validator = group.querySelector('.js-checkbox-valid');

            // If there is checked boxes then tick off the validating checkbox
            if (checkboxes.length > 0) {
                validator.setAttribute('checked', true);
            } else {
                validator.removeAttribute('checked');
            }
        })
    }

    /*
    ** Adds event listener for when a checkbox is change to revalidate form
    */
    const addListeners = () => {
        document.querySelectorAll('.checkbox-group').forEach(group => {
            const checkboxes = group.querySelectorAll('.c-option [type="checkbox"]');

            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', () => {
                    getCheckBoxGroups();
                })
            });
            
            group.querySelectorAll('.js-checkbox-valid').forEach(validator => {
                validator.addEventListener('invalid', (e) => {
                    e.preventDefault();

                    const labelElm = group.querySelector('label');

                    // Highlight label of checkbox group
                    labelElm.classList.add('u-color__text--danger');
                    labelElm.scrollIntoView({'block': 'center'});
                })
            })
        })
    }

    /*
    ** Initial check for checkboxes and add listeners
    */
    const init = () => {
        getCheckBoxGroups();
        addListeners();
    }

    return init();
})();