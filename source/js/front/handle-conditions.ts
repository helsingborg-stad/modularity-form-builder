class HandleConditions {
	constructor() {
		this.handleRequired();
		this.handleEvents();
	}

	private handleRequired(): void {
		document.querySelectorAll<HTMLElement>('[class*="mod-form"] [conditional-target]').forEach((target) => {
			const isHidden = window.getComputedStyle(target).display === 'none';
			target.querySelectorAll<HTMLInputElement | HTMLSelectElement | HTMLTextAreaElement>('[required], [hidden-required]').forEach((field) => {
				if (isHidden && field.required) {
					field.required = false;
					field.setAttribute('hidden-required', 'true');
				} else if (!isHidden && field.hasAttribute('hidden-required')) {
					field.required = true;
					field.removeAttribute('hidden-required');
				}
			});
		});
	}

	private handleEvents(): void {
		document.addEventListener('change', (event) => {
			const input = event.target instanceof HTMLInputElement ? event.target : null;
			const conditional = input?.getAttribute('conditional');
			const form = input?.closest<HTMLElement>('[class*="mod-form"]');

			if (conditional && form) {
				const parsedCondition = JSON.parse(conditional.replaceAll("'", '"')) as { label: string };
				form.querySelectorAll<HTMLElement>(`div[conditional-target^='{"label":"${CSS.escape(parsedCondition.label)}",']`).forEach((target) => {
					target.style.display = 'none';
				});
				form.querySelectorAll<HTMLElement>(`div[conditional-target='${CSS.escape(conditional)}']`).forEach((target) => {
					target.style.display = '';
				});
			}

			this.handleRequired();
		});
	}
}

export default new HandleConditions();
