export default (function addFile() {
    const forms = document.querySelectorAll('.mod-form');
    
    
    forms.forEach((form) => {
        const visibileInput = form.querySelector('.c-fileinput__input');

        visibileInput.addEventListener('change', (event) => {

            const hiddenInput = visibileInput.cloneNode(true);
            hiddenInput.setAttribute('style', 'display:none');
            visibileInput.parentNode.insertBefore(hiddenInput, visibileInput.nextSibling);
            visibileInput.value = '';
        });
    });
    
})();