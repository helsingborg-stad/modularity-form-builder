type OptionGroup = { groupLabel: string; selectvalues: string[] };

class ConditionalAdmin {
	constructor() {
		this.populateSelectFields();
		this.handleEvents();
		this.restoreSelectedFields();
	}

	private restoreSelectedFields(): void {
		if (typeof acf === 'undefined') return;
		document.querySelectorAll<HTMLSelectElement>('[data-name="conditonal_field"] select').forEach((select) => {
			const value = select.closest<HTMLElement>('[data-name="conditonal_field"]')?.querySelector<HTMLInputElement>('input[type="hidden"]')?.value;
			if (value) select.value = value;
		});
	}

	private async populateSelectFields(): Promise<void> {
		const options = this.getOptions();
		for (const select of document.querySelectorAll<HTMLSelectElement>('[data-name="conditonal_field"] select')) {
			let selected = select.value;
			if (!selected && typeof modularity_current_post_id !== 'undefined') {
				const data = new URLSearchParams({ action: 'get_selected_field', moduleId: modularity_current_post_id, fieldName: select.name });
				const response = await fetch(ajaxurl, { method: 'POST', body: data });
				const savedValue = await response.text();
				if (savedValue !== 'error') selected = savedValue;
			}

			select.replaceChildren();
			document.querySelectorAll('.condition-missing').forEach((message) => {
				message.remove();
			});
			if (!options.length) {
				const message = document.createElement('p');
				message.className = 'condition-missing';
				message.textContent = formbuilder.selections_missing;
				select.before(message);
				continue;
			}

			options.forEach((optionGroup) => {
				const group = document.createElement('optgroup');
				group.label = optionGroup.groupLabel;
				optionGroup.selectvalues.forEach((optionValue) => {
					const option = new Option(optionValue, JSON.stringify({ label: this.conditionalString(optionGroup.groupLabel), value: this.conditionalString(optionValue) }));
					group.append(option);
				});
				select.append(group);
			});
			select.value = selected;
		}
	}

	private getOptions(): OptionGroup[] {
		return Array.from(document.querySelectorAll<HTMLElement>('[data-layout="radio"]:not(.acf-clone)')).map((item) => ({
			groupLabel: item.querySelector<HTMLInputElement>('[data-name="label"] input')?.value ?? '',
			selectvalues: Array.from(item.querySelectorAll<HTMLInputElement>('[data-key="field_58eb670d39fef"] table tbody tr.acf-row:not(.acf-clone) [data-name="value"] input')).map((input) => input.value),
		}));
	}

	private conditionalString(value: string): string {
		return value.toLowerCase().replace(/\s+/g, '_').replace(/[^a-z0-9_]+/gi, '').replace(/_+$/, '');
	}

	private handleEvents(): void {
		document.addEventListener('click', (event) => {
			if (event.target instanceof Element && event.target.closest('[data-name="conditional_logic"] [type="checkbox"], [data-layout="radio"] .acf-row-handle > a')) {
				void this.populateSelectFields();
			}
		});
	}
}

export default new ConditionalAdmin();
