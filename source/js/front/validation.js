export default (() => {
  const setHyperformValidation = elem => {
    const requiredCheckboxes = elem.querySelectorAll('[type="checkbox"].required');

    if (typeof requiredCheckboxes === 'object' && requiredCheckboxes.length > 0) {
      requiredCheckboxes.forEach(checkbox => {
        hyperform.addValidator(checkbox, function(element) {
          const checkedCheckboxes = elem.querySelectorAll('.required[type="checkbox"]:checked');
          const valid = checkedCheckboxes.length > 0;

          element.setCustomValidity(valid ? '' : formbuilder.checkbox_required);

          return valid;
        });
      });
    }
  };

  const setCheckboxValidationRules = modularityForm => {
    const checkboxGroups = modularityForm.getElementsByClassName('checkbox-group');

    for (let i = 0; i < checkboxGroups.length; i++) {
      const checkboxGroup = checkboxGroups.item(i);
      setHyperformValidation(checkboxGroup);
    }
  };

  const init = () => {
    const forms = document.getElementsByClassName('modularity-mod-form');

    for (let i = 0; i < forms.length; i++) {
      const form = forms.item(i);
      setCheckboxValidationRules(form);
    }
  };

  return init();
})();
