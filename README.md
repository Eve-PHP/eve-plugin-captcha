![logo](http://eden.openovate.com/assets/images/cloud-social.png) Eve Captcha Plugin
====
Google Captcha for the Eve Framework
====
[![Build Status](https://api.travis-ci.org/eve-php/eve-plugin-captcha.png)](https://travis-ci.org/eve-php/eve-plugin-captcha) [![Latest Stable Version](https://poser.pugx.org/eve-php/eve-plugin-captcha/v/stable)](https://packagist.org/packages/eve-php/eve-plugin-captcha) [![Total Downloads](https://poser.pugx.org/eve-php/eve-plugin-captcha/downloads)](https://packagist.org/packages/eve-php/eve-plugin-captcha) [![Latest Unstable Version](https://poser.pugx.org/eve-php/eve-plugin-captcha/v/unstable)](https://packagist.org/packages/eve-php/eve-plugin-captcha) [![License](https://poser.pugx.org/eve-php/eve-plugin-captcha/license)](https://packagist.org/packages/eve-php/eve-plugin-captcha)
====

- [Install](#install)
- [Usage](#usage)

====

<a name="install"></a>
## Install

`composer install eve-php/eve-plugin-captcha`

====

<a name="usage"></a>
## Usage

1. Add this in public/index.php towards the top of the bootstrap chain.

```
//CAPTCHA
->add(Eve\Plugin\Captcha\Setup::i()->import('KEY', 'SECRET', '1234567890'))
```

 - `'KEY'` is the ID given by Google.
 - `'1234567890'` is the SECRET given by Google.
 - `'1234567890'` is the escape key you use when writing tests for pages using this plugin.
  
2. For each route, determine whether a CSRF ID should be generated and/or checked as in
 
```
'/product/create' => array(
	'method' => 'ALL',
	'make_captcha' => true,
	'check_captcha' => true,
	'class' => '\\Eve\\App\\Front\\Action\\Product\\Create'
),
``` 

3. In each form template add before the form tag

```
<script src='https://www.google.com/recaptcha/api.js'></script>
<div class="form-group captcha">
	<label class="control-label">{{_ 'Are you a robot ?'}}</label>
	<div>
		<input type="hidden" name="captcha" value="{{item.captcha}}" />
		<div class="g-recaptcha" data-sitekey="{{item.captcha}}"></div>
	</div>
</div>
```

4. Done ;)