/**
 * Get Url referrer save to storage then set fields in form.
 */

let refUrlStorageHistory = null;
let refUrlStorage = null;

/**
 * Modularity form builder referer
 * Adds referer address and page address to hidden field in form.
 * (Register the page previously visit and the form page)
 */
export default class ModularityFormBuilderReferer {

    /**
     * Create Local Storage.
     */
    constructor() {

        if (typeof (Storage) !== 'undefined') {
            this.setStorage();
        }
    };

    /**
     * Fetches url parameter
     * @param uriParam
     * @returns {boolean|string}
     */
    getUrlParameter(uriParam) {
        let pageUri = decodeURIComponent(window.location.search.substring(1)),
            uriVars = pageUri.split('&'),
            paramName;

        for (let i = 0; i < uriVars.length; i++) {
            paramName = uriVars[i].split('=');

            if (paramName[0] === uriParam) {
                return paramName[1] === undefined ? true : paramName[1];
            }
        }
    };

    /**
     * Check local storage
     * @param storageType
     * @returns {string}
     */
    checkStorage(storageType) {
        return localStorage.getItem(storageType);
    };

    /**
     *  Creates a Local storage
     */
    setStorage() {

        if (this.checkStorage('refUrlStorage') !== window.location.href) {

            if (this.getUrlParameter('modularityForm')) {

                refUrlStorageHistory = localStorage.setItem(
                    'refUrlStorageHistory',
                    decodeURIComponent(
                        this.getUrlParameter('modularityForm')
                    )
                );

                refUrlStorage = localStorage.setItem(
                    'refUrlStorageHistory',
                    decodeURIComponent(
                        this.getUrlParameter('modularityReferrer'))
                );

            } else {

                refUrlStorageHistory = localStorage.setItem(
                    'refUrlStorageHistory',
                    this.checkStorage('refUrlStorage')
                );

                refUrlStorage = localStorage.setItem(
                    'refUrlStorage',
                    window.location.href
                );
            }
        }

        this.addStorageRefererToDoom();
    };

    /**
     *  Adding referer URL to doom
     */
    addStorageRefererToDoom() {
        if (document.getElementById('modularity-form-history').length !== 0) {
            document.getElementById('modularity-form-history').value = this.checkStorage(
                'refUrlStorageHistory'
            );

            document.getElementById('modularity-form-url').value = this.checkStorage(
                'refUrlStorage'
            );
        }
    };

}

new ModularityFormBuilderReferer();