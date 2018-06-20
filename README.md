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
Get your API key at https://www.google.com/recaptcha/

```
define('G_RECAPTCHA_KEY', 'YOUR_API_KEY');
define('G_RECAPTCHA_SECRET', 'YOUR_API_SECRET');
```

#### Data encryption
Form builder supports Encryption to keep the posted and stored data safe and unreadable without having the correct keys.
Add following defined constants to your config file and replace ADD-YOUR-KEY-HERE-1 and 
ADD-YOUR-KEY-HERE-2 with your own keys, 16 character minimum. Enable the encryption in Form submission Options.
<br>Modularity Form Builder uses OpenSSL library for symmetric and asymmetric encryption and decryption.
```
define("ENCRYPT_METHOD", "AES-256-CBC", true);
define("ENCRYPT_SECRET_KEY", "ADD-YOUR-KEY-HERE-1", true);
define("ENCRYPT_SECRET_VI", "ADD-YOUR-KEY-HERE-2", true);
```