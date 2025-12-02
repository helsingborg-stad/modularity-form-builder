export default (($) => {
	var componentForm = {
		street_number: { name: "street", addressType: "short_name" },
		route: { name: "street", addressType: "short_name" },
		locality: { name: "city", addressType: "long_name" },
		postal_code: { name: "postal-code", addressType: "long_name" },
	};

	function GetLocation() {
		var locationButton = document.getElementById("form-get-location");
		if (!navigator.geolocation || locationButton === null) {
			return;
		}

		this.handleEvents();
	}

	GetLocation.prototype.handleEvents = function () {
		$("#form-get-location").click(
			((e) => {
				e.preventDefault();

				$target = $(e.target).parents('[class*="mod-form"]');
				$(e.target)
					.find(".pricon")
					.removeClass()
					.addClass("spinner spinner-dark");

				navigator.geolocation.getCurrentPosition(
					(position) => {
						var lat = position.coords.latitude,
							lng = position.coords.longitude,
							googleMapsPos = new google.maps.LatLng(lat, lng),
							googleMapsGeocoder = new google.maps.Geocoder();

						googleMapsGeocoder.geocode(
							{ latLng: googleMapsPos },
							(results, status) => {
								var fullAddress = [];

								if (status == google.maps.GeocoderStatus.OK && results[0]) {
									// Get each component of the address from the place details and fill the form
									for (
										var i = 0;
										i < results[0].address_components.length;
										i++
									) {
										var addressType = results[0].address_components[i].types[0];

										if (componentForm[addressType]) {
											var value =
												results[0].address_components[i][
													componentForm[addressType].addressType
												];
											$target
												.find(
													'[id$="address-' +
														componentForm[addressType].name +
														'"]',
												)
												.val(value);
										}

										// Combine street name and street number
										if (addressType == "route") {
											fullAddress[0] = value;
										} else if (addressType == "street_number") {
											fullAddress[1] = value;
										}
										$target
											.find('[id$="address-street"]')
											.val(fullAddress.join(" "));
									}
								}
							},
						);
						// Reset button icon
						$(e.target)
							.find(".spinner")
							.removeClass()
							.addClass("pricon pricon-location-pin");
					},
					() => {
						// Show message if Geolocate went wrong
						$(e.target).html(
							'<span><i class="pricon pricon-notice-warning"></i> ' +
								formbuilder.something_went_wrong +
								"</span>",
						);
					},
				);
			}).bind(this),
		);
	};

	return new GetLocation();
})(jQuery);
