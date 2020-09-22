export default (function addFile() {
    const visibileInput = document.querySelector('.c-fileinput__input');

    visibileInput.addEventListener('change', (event) => {
        const files = visibileInput.files;
        const hiddenInput = visibileInput.cloneNode(true);
        hiddenInput.setAttribute('style', 'display:none');
        visibileInput.parentNode.insertBefore(hiddenInput, visibileInput.nextSibling);
        visibileInput.value = '';
        //visibileInput.files = [];

        //console.log(visibileInput.files);
        console.log(hiddenInput.files);
    });
    console.log(visibileInput);
})();