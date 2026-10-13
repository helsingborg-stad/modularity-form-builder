type Selections = Record<string, string[]>;

class ConditionalNotification {
	private selections: Selections = {};

	constructor() {
		this.init();
		this.dataGathering();
		this.updateMainSelect();
		this.updateSubSelect();
		document.addEventListener('click', (event) => {
			if (event.target instanceof Element && event.target.closest('.post-type-mod-form input[type="checkbox"], .post-type-mod-form .acf-tab-button')) this.refresh();
		});
		document.addEventListener('change', (event) => {
			if (event.target instanceof Element && event.target.matches('[data-name="form_conditional_field"] .acf-input select')) this.updateSubSelect();
		});
	}

	private rows(): HTMLElement[] {
		return Array.from(document.querySelectorAll<HTMLElement>('[data-name="notify"] .acf-row:not(.acf-clone)'));
	}

	private select(row: HTMLElement, field: string): HTMLSelectElement | null {
		return row.querySelector<HTMLSelectElement>(`[data-name="form_${field}"] .acf-input select`);
	}

	private init(): void {
		if (typeof notificationConditions === 'undefined' || notificationConditions === null) return;
		const conditions = JSON.parse(notificationConditions) as Array<Record<string, string> | null>;
		['conditional_field', 'conditional_field_equals'].forEach((field) => {
			this.rows().forEach((row, index) => {
				const value = conditions[index]?.[field];
				const select = this.select(row, field);
				if (value && select) select.replaceChildren(new Option(value, value, true, true));
			});
		});
	}

	private refresh(): void {
		this.dataGathering();
		this.updateMainSelect();
		this.updateSubSelect();
	}

	private updateMainSelect(): void {
		this.rows().forEach((row) => {
			const select = this.select(row, 'conditional_field');
			if (!select) return;
			const selected = select.value;
			select.replaceChildren(...Object.keys(this.selections).map((value) => new Option(value, value, selected === value, selected === value)));
		});
	}

	private updateSubSelect(): void {
		this.rows().forEach((row) => {
			const main = this.select(row, 'conditional_field');
			const sub = this.select(row, 'conditional_field_equals');
			if (!main || !sub) return;
			const selected = sub.value;
			sub.replaceChildren(...(this.selections[main.value] ?? []).map((value) => new Option(value, value, selected === value, selected === value)));
		});
	}

	private dataGathering(): void {
		this.selections = {};
		document.querySelectorAll<HTMLElement>('[data-name="form_fields"] .layout').forEach((layout) => {
			const type = layout.dataset.layout;
			const label = layout.querySelector<HTMLInputElement>(".acf-fields [data-name='label'] .acf-input .acf-input-wrap input")?.value;
			const required = layout.querySelector<HTMLInputElement>("[data-name='required'] .acf-input label input")?.checked;
			const conditional = layout.querySelector<HTMLInputElement>("[data-name='conditional_logic'] .acf-input label input")?.checked;
			if (!label || !required || conditional || !['select', 'radio'].includes(type ?? '')) return;
			this.selections[label] = Array.from(layout.querySelectorAll<HTMLInputElement>("[data-name='values'] tbody [data-name='value'] .acf-input .acf-input-wrap input[type='text']")).filter((input) => !input.name.includes('acfclonefield') && input.value).map((input) => input.value);
		});
	}
}

export default new ConditionalNotification();
