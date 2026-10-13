declare const formbuilder: {
	something_went_wrong: string;
};
declare namespace google.maps {
	class LatLng {
		constructor(latitude: number, longitude: number);
	}

	type AddressComponent = {
		long_name: string;
		short_name: string;
		types: string[];
	};

	type GeocoderResult = { address_components: AddressComponent[] };

	class Geocoder {
		geocode(request: { latLng: LatLng }, callback: (results: GeocoderResult[] | null, status: string) => void): void;
	}

	const GeocoderStatus: { OK: string };
}
