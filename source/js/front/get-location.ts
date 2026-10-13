const componentForm: Record<string, { name: string; addressType: 'short_name' | 'long_name' }> = {
	street_number: { name: 'street', addressType: 'short_name' },
	route: { name: 'street', addressType: 'short_name' },
	locality: { name: 'city', addressType: 'long_name' },
	postal_code: { name: 'postal-code', addressType: 'long_name' },
};

class GetLocation {
	constructor() {
		if (navigator.geolocation && document.getElementById('form-get-location')) this.handleEvents();
	}

	private handleEvents(): void {
		document.addEventListener('click', (event) => {
			const button = event.target instanceof Element ? event.target.closest<HTMLElement>('#form-get-location') : null;
			if (!button) return;
			event.preventDefault();

			const target = button.closest<HTMLElement>('[class*="mod-form"]');
			const icon = button.querySelector<HTMLElement>('.pricon');
			icon?.classList.replace('pricon', 'spinner');
			icon?.classList.replace('pricon-location-pin', 'spinner-dark');
			navigator.geolocation.getCurrentPosition(
				(position) => {
					const googleMapsPos = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
					new google.maps.Geocoder().geocode({ latLng: googleMapsPos }, (results, status) => {
						if (status !== google.maps.GeocoderStatus.OK || !results?.[0] || !target) return;
						const fullAddress: string[] = [];
						results[0].address_components.forEach((component) => {
							const addressType = component.types[0];
							const mapping = componentForm[addressType];
							const value = mapping ? component[mapping.addressType] : '';
							const input = mapping ? target.querySelector<HTMLInputElement>(`[id$="address-${mapping.name}"]`) : null;
							if (input) input.value = value;
							if (addressType === 'route') fullAddress[0] = value;
							if (addressType === 'street_number') fullAddress[1] = value;
							const street = target.querySelector<HTMLInputElement>('[id$="address-street"]');
							if (street) street.value = fullAddress.join(' ');
						});
					});
					icon?.classList.remove('spinner', 'spinner-dark');
					icon?.classList.add('pricon', 'pricon-location-pin');
				},
				() => {
					button.innerHTML = `<span><i class="pricon pricon-notice-warning"></i> ${formbuilder.something_went_wrong}</span>`;
				},
			);
		});
	}
}

export default new GetLocation();
