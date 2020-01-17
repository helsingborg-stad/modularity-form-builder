export default (() => {
  const getValidity = (element, scope) => {
    const checkedCheckboxes = scope.querySelectorAll('.required[type="checkbox"]:checked');
    const valid = checkedCheckboxes.length > 0;
    const errorsEl = scope.getElementsByClassName('errors')[0];

    hyperform.setRenderer('attachWarning', (warning, element) => {
      if (errorsEl.children.length <= 0) {
        errorsEl.appendChild(warning);
      }
    });
    
    hyperform(window, {
      classes: {
        warning: 'text-danger',
      }
    });

    element.setCustomValidity(valid ? '' : formbuilder.checkbox_required);

    return valid;
  };

  const setHyperformValidation = scope => {
    const requiredCheckboxes = scope.querySelectorAll('[type="checkbox"].required');

    if (typeof requiredCheckboxes === 'object' && requiredCheckboxes.length > 0) {
      for (let i = 0; i < requiredCheckboxes.length; i++) {
        const checkbox = requiredCheckboxes[i];

        hyperform.addValidator(checkbox, element => getValidity(element, scope));
      }
    }
  };

  const setCheckboxValidationRules = modularityForm => {
    const checkboxGroups = modularityForm.getElementsByClassName('checkbox-group');
    for (let i = 0; i < checkboxGroups.length; i++) {
      const checkboxGroup = checkboxGroups.item(i);
      setHyperformValidation(checkboxGroup);
    }
  };

  /**
   * Loop through forms and set custom validation rules to required checkboxes
   */
  const init = () => {
    const forms = document.getElementsByClassName('modularity-validation');

    for (let i = 0; i < forms.length; i++) {
      const form = forms.item(i);
      setCheckboxValidationRules(form);
      /* Whenever a checkbox is clicked, revalidate all other checkboxes */
      let inputElements = form.getElementsByTagName('input');
      inputElements = Array.from(inputElements);
      inputElements = inputElements.filter(elem => elem.type === 'checkbox');

      for (let j = 0; j < inputElements.length; j++) {
        inputElements[j].addEventListener('click', () => {
          for (let k = 0; k < inputElements.length; k++) {
            inputElements[k].reportValidity();
          }
        });
      }
    }
  };

  return init();
})();
