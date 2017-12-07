FormBuilder = FormBuilder || {};
FormBuilder.Front = FormBuilder.Front || {};

FormBuilder.Front.collapse = (function ($) {

    function Collapse() {
        $(function() {
            this.init();
            this.handleEvents();
        }.bind(this));
    }

    Collapse.prototype.init = function() {
        $('.mod-form-collapse').each(function(index) {
            $(this).nextUntil(':not(.mod-form-field)').hide();
        });
    };

    Collapse.prototype.handleEvents = function () {
        $('button', '.mod-form-collapse').click(function(e) {
            e.preventDefault();

            $('.mod-form-collapse__icon > i', e.target).toggleClass('pricon-plus-o pricon-minus-o');

            $(e.target).parents('.mod-form-collapse')
                .nextUntil(':not(.mod-form-field)')
                .each(function() {
                    $(this).fadeToggle('fast');
                });

        }.bind(this));
    };

    return new Collapse();
})(jQuery);

var FormBuilder = FormBuilder || {};

FormBuilder = FormBuilder || {};
FormBuilder.Front = FormBuilder.Front || {};

FormBuilder.Front.getLocation = (function ($) {

	var componentForm = {
	       	street_number: 	{name : 'street', 		addressType : 'short_name'},
	        route: 			{name : 'street', 		addressType : 'short_name'},
	        locality: 		{name : 'city', 		addressType : 'long_name'},
	        postal_code: 	{name : 'postal-code',	addressType : 'long_name'}
    	};

    function GetLocation() {
        $(function() {
        	var locationButton = document.getElementById('form-get-location');
        	if (!navigator.geolocation || locationButton === null) {
				return;
			}

            this.handleEvents();
        }.bind(this));
    }

    GetLocation.prototype.handleEvents = function() {
        $('#form-get-location').click(function(e) {
        	e.preventDefault();
            $target = $(e.target).parents('[class*="mod-form"]');
            $(e.target).find('.pricon').removeClass().addClass('spinner spinner-dark');

	        navigator.geolocation.getCurrentPosition(
	            function(position) {
			        var lat = position.coords.latitude,
			        	lng = position.coords.longitude,
			        	googleMapsPos = new google.maps.LatLng(lat, lng),
			        	googleMapsGeocoder = new google.maps.Geocoder();

						googleMapsGeocoder.geocode({'latLng': googleMapsPos},
						    function(results, status) {
						    	var fullAddress = [];

								if (status == google.maps.GeocoderStatus.OK && results[0]) {
							        // Get each component of the address from the place details and fill the form
							        for (var i = 0; i < results[0].address_components.length; i++) {
							         	var addressType = results[0].address_components[i].types[0];

							          	if (componentForm[addressType]) {
							            	var value = results[0].address_components[i][componentForm[addressType].addressType];
							            	$target.find('[id$="address-' + componentForm[addressType].name + '"]').val(value);
							          	}

								       	// Combine street name and street number
						            	if (addressType == 'route') {
						            		fullAddress[0] = value;
						            	} else if(addressType == 'street_number') {
						            		fullAddress[1] = value;
						            	}
						            	$target.find('[id$="address-street"]').val(fullAddress.join(' '));
					            	}
								}
						});
				// Reset button icon
				$(e.target).find('.spinner').removeClass().addClass('pricon pricon-location-pin');
	            },
	            function() {
	            	// Show message if Geolocate went wrong
					$(e.target).html('<span><i class="pricon pricon-notice-warning"></i> ' + formbuilder.something_went_wrong + '</span>');
	            }
	        );
        }.bind(this));
    };

	return new GetLocation();

})(jQuery);

FormBuilder = FormBuilder || {};
FormBuilder.Front = FormBuilder.Front || {};

FormBuilder.Front.handleConditions = (function ($) {

    function HandleConditions() {
        $(function() {
            this.handleRequired();
            this.handleEvents();
        }.bind(this));
    }

    HandleConditions.prototype.handleRequired = function () {
        $target = $('[class*="mod-form"]');
        $('[conditional-target]:hidden', $target).find('[required]').prop('required', false).attr('hidden-required', true);
        $('[conditional-target]:visible', $target).find('[hidden-required]').prop('required', true);
    };

    HandleConditions.prototype.handleEvents = function () {
        $('input[conditional]').change(function(e) {
            $target = $(e.target).parents('[class*="mod-form"]');
            var conditional = $(e.target).attr('conditional');
            if (typeof conditional !== 'undefined' && conditional.length > 0) {
                var conditionObj = JSON.parse(conditional);
                $target.find("div[conditional-target^='{\"label\":\"" + conditionObj.label + "\",']").hide();
                $target.find("div[conditional-target='" + conditional + "']").show();
            }

            this.handleRequired();
        }.bind(this));
    };

	return new HandleConditions();

})(jQuery);

FormBuilder = FormBuilder || {};
FormBuilder.Front = FormBuilder.Front || {};

FormBuilder.Front.submit = (function ($) {

    function Submit() {
        $(function() {
            this.handleEvents();
        }.bind(this));
    }

    // Show spinner icon on submit
    Submit.prototype.handleEvents = function () {
        $('[class*="mod-form"]').submit(function(e) {
            $(e.target).find('button[type="submit"]').html('<i class="spinner"></i> ' + formbuilder.sending);
        }.bind(this));
    };

	return new Submit();

})(jQuery);
