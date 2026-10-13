/**
 * Modularity form builder referer
 * Adds referer address and page address to hidden field in form.
 * (Register the page previously visit and the form page)
 */

let refUrlStorageHistory = null;
let refUrlStorage = null;

export default class ModularityFormBuilderReferer {
	/**
	 * Create Local Storage.
	 */
	constructor() {
		if (typeof Storage !== 'undefined') {
			this.setStorage();
		}
	}

	/**
	 * Fetches url parameter
	 * @param uriParam
	 * @returns {boolean|string}
	 */
	getUrlParameter(uriParam: string): boolean | string | undefined {
		let pageUri = decodeURIComponent(window.location.search.substring(1)),
			uriVars = pageUri.split('&'),
			paramName: string[];

		for (let i = 0; i < uriVars.length; i++) {
			paramName = uriVars[i].split('=');

			if (paramName[0] === uriParam) {
				return paramName[1] === undefined ? true : paramName[1];
			}
		}
	}

	/**
	 * Check local storage
	 * @param storageType
	 * @returns {string}
	 */
	checkStorage(storageType: string): string | null {
		return localStorage.getItem(storageType);
	}

	/**
	 *  Creates a Local storage
	 */
	setStorage() {
		if (this.checkStorage('refUrlStorage') !== window.location.href) {
			const formParameter = this.getUrlParameter('modularityForm');
			const referrerParameter = this.getUrlParameter('modularityReferrer');
			if (formParameter && typeof formParameter === 'string') {
				refUrlStorageHistory = localStorage.setItem(
					'refUrlStorageHistory',
					decodeURIComponent(formParameter),
				);

				refUrlStorage = localStorage.setItem(
					'refUrlStorageHistory',
					decodeURIComponent(typeof referrerParameter === 'string' ? referrerParameter : ''),
				);
			} else {
				refUrlStorageHistory = localStorage.setItem('refUrlStorageHistory', this.checkStorage('refUrlStorage') ?? '');

				refUrlStorage = localStorage.setItem('refUrlStorage', window.location.href);
			}
		}

		this.addStorageRefererToDoom();
	}

	/**
	 *  Adding referer URL to doom
	 */
	addStorageRefererToDoom() {
		const historyField = document.querySelector<HTMLInputElement>("input[name='modularity-form-history']");
		const urlField = document.querySelector<HTMLInputElement>("input[name='modularity-form-url']");
		if (historyField) historyField.value = this.checkStorage('refUrlStorageHistory') ?? '';
		if (urlField) urlField.value = this.checkStorage('refUrlStorage') ?? '';
	}
}

new ModularityFormBuilderReferer();
