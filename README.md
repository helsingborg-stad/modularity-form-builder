# Modularity Form Builder

Simple form builder that uses ACF for forms.

## Constants

#### Google Maps Geocoding API key
Enable auto locate capability to address fields by adding Google Maps Geocoding API key constant.

```
define('G_GEOCODE_KEY', 'YOUR_API_KEY');

```

#### Google reCaptcha API key
Enable reCAPTCHA and protect your form from spam and abuse.
Get you API key at https://www.google.com/recaptcha/

```
define('G_RECAPTCHA_KEY', 'YOUR_API_KEY');
define('G_RECAPTCHA_SECRET', 'YOUR_API_SECRET');
```