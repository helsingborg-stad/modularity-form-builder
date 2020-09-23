export default (function addFile() {
    const forms = document.querySelectorAll('.mod-form');
    
    forms.forEach((form) => {
        
        const visibileInput = form.querySelector('.c-fileinput__input');
        const submit = form.querySelector('[type="submit"]');

        visibileInput.addEventListener('change', (event) => {

            const hiddenInput = visibileInput.cloneNode(true);
            hiddenInput.setAttribute('style', 'display:none');
            visibileInput.parentNode.insertBefore(hiddenInput, visibileInput.nextSibling);
            //visibileInput.value = '';
            //visibileInput.files = [];
            
            //console.log(visibileInput.files);
            console.log(hiddenInput.files);
        });

        console.log(submit);

        submit.addEventListener('submit', (event) => {
            console.log(event);
        });
    });
    
})();