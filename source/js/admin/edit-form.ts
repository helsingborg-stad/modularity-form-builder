type AjaxResponse = { success: boolean; data: string };

class EditForm {
	constructor() {
		this.handleEvents();
	}

	private async request(data: FormData): Promise<AjaxResponse> {
		const response = await fetch(ajaxurl, { method: 'POST', body: data });
		return response.json() as Promise<AjaxResponse>;
	}

	private async deleteFile(postId: string, formId: string, filePath: string, fieldName: string, target: HTMLElement): Promise<void> {
		target.remove();
		const data = new FormData();
		data.append('action', 'delete_file');
		data.append('postId', postId);
		data.append('formId', formId);
		data.append('filePath', filePath);
		data.append('fieldName', fieldName);
		try {
			await this.request(data);
		} catch (error) {
			console.error('Error deleting file:', error);
		}
	}

	private async uploadFile(files: FileList, postId: string, formId: string, fieldName: string, target: HTMLElement): Promise<void> {
		const data = new FormData();
		data.append('action', 'upload_files');
		data.append('postId', postId);
		data.append('formId', formId);
		data.append('fieldName', fieldName);
		Array.from(files).forEach((file) => {
			data.append(`${fieldName}[]`, file);
		});

		const inputs = target.querySelectorAll<HTMLInputElement>('input[type="file"]');
		const status = target.querySelector<HTMLElement>('.upload-status');
		inputs.forEach((input) => { input.hidden = true; });
		if (status) status.innerHTML = '<div class="spinner spinner-dark is-active" style="float:none;"></div>';
		try {
			const response = await this.request(data);
			if (response.success) document.querySelector<HTMLElement>('#publishing-action :is(button[type="submit"], input[type="submit"]), #edit-post :is(button[type="submit"], input[type="submit"])')?.click();
			if (status) status.innerHTML = `<p>${response.data}</p>`;
		} catch (error) {
			console.error('Error uploading file:', error);
			if (status) status.hidden = true;
		} finally {
			inputs.forEach((input) => { input.hidden = false; });
		}
	}

	private async savePost(form: HTMLFormElement): Promise<void> {
		const data = new FormData(form);
		data.append('action', 'save_post');
		const button = form.querySelector<HTMLButtonElement>('button[type="submit"]');
		if (button) {
			button.disabled = true;
			button.insertAdjacentHTML('beforeend', '<span class="spinner"></span>');
		}
		try {
			const response = await this.request(data);
			if (response.success) button?.insertAdjacentHTML('beforeend', '<i class="pricon pricon-check"></i>');
			else {
				const footer = form.querySelector<HTMLElement>('.modal-footer');
				if (footer) footer.innerHTML = `<span class="notice warning"><i class="pricon pricon-notice-warning"></i> ${response.data}</span>`;
			}
		} finally {
			location.hash = '';
			location.reload();
		}
	}

	private handleEvents(): void {
		document.addEventListener('submit', (event) => {
			const form = event.target instanceof HTMLFormElement && event.target.matches('#edit-post') ? event.target : null;
			if (!form) return;
			event.preventDefault();
			void this.savePost(form);
		});
		document.addEventListener('click', (event) => {
			const button = event.target instanceof Element ? event.target.closest<HTMLElement>('.delete-form-file') : null;
			if (!button || !window.confirm(formbuilder.delete_confirm)) return;
			event.preventDefault();
			void this.deleteFile(button.getAttribute('postid') ?? '', button.getAttribute('formid') ?? '', button.getAttribute('filepath') ?? '', button.getAttribute('fieldname') ?? '', button.closest<HTMLElement>('span') ?? button);
		});
		document.addEventListener('change', (event) => {
			const input = event.target instanceof HTMLInputElement && event.target.matches('input[type="file"]') ? event.target : null;
			if (!input?.files) return;
			void this.uploadFile(input.files, input.getAttribute('postid') ?? '', input.getAttribute('formid') ?? '', input.getAttribute('fieldname') ?? '', input.closest<HTMLElement>('.mod-form-field') ?? input);
		});
	}
}

export default new EditForm();
