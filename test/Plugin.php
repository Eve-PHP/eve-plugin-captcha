<?php //-->
/**
 * This file is part of the Eden PHP Library.
 * (c) 2014-2016 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

class Eve_Csrf_Plugin extends PHPUnit_Framework_TestCase
{
    public function testImport()
    {
        $callback = Eve\Captcha\Plugin::i()->import('123', '321');
		
		$this->assertTrue(is_callable($callback));
		
		$request = eden('registry');
		
		eve()->addCaptcha(
			$request,
			eden('registry'),
			array(
				'method' => 'GET',
				'check_captcha' => true
			)
		);	
		
		$this->assertFalse($request->get('valid_captcha'));
		$request->set('valid_captcha', true);
		$request->set('get', 'g-recaptcha-response', '1234567890');
		
		eve()->addCaptcha(
			$request,
			eden('registry'),
			array(
				'method' => 'GET',
				'check_captcha' => true,
				'make_captcha' => true
			)
		);	
		
		$this->assertTrue($request->get('valid_captcha') !== false);
		
		$_GET['g-recaptcha-response'] = $_SESSION['captcha'] = $request->get('csrf');
	
		$error = false;
		try {
			$callback($request, eden('registry'));
		} catch(Exception $e) {
			$error = true;
		}
		
		$this->assertFalse($error);
	}
}