if (!formbuilder.mod_form_authorized) {
	document.querySelectorAll<HTMLElement>('[data-name="upload_videos_external"]').forEach((field) => {
		field.style.display = 'none';
	});
}

export default undefined;
