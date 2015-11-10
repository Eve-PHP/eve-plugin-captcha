<?php //-->
/**
 * This file is part of the Eden PHP Library.
 * (c) 2014-2016 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Eve\Plugin\Captcha;

use Eden\Registry\Index as Registry;

/**
 * CSRF Middleware Plugin Class
 *
 * @package  Eve
 * @category Plugin
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
class Setup extends Base
{
    /**
     * Main route method
     *
     * @return function
     */
    public function import($token, $secret, $escape = '1234567890')
    {
        Argument::i()
            ->test(1, 'string')
            ->test(2, 'string')
            ->test(3, 'string');

        //remember this scope
        $self = $this;
        
        eve()->addMethod('addCaptcha', function (
            Registry $request,
            Registry $response,
            array $meta
        ) use (
            $token,
            $self
) {
            //we already checked the captcha it's good
            //we just need to check if it's set
            if (isset($meta['check_captcha'])
                && $meta['check_captcha']
                && !$request->isKey('get', 'g-recaptcha-response')
                && !$request->isKey('post', 'g-recaptcha-response')
            ) {
                //let the action handle the rest
                $request->set('valid_captcha', false);
            }
                
            //set captcha
            if (isset($route['make_captcha']) && $meta['make_captcha']) {
                $request->set('captcha', $token);
            }
        });
        
        //You can add validators here
        return function (Registry $request, Registry $response) use ($secret, $escape, $self) {
            $request->set('valid_captcha', true);
            //CAPTCHA - whether or not we are expecting it lets do a check
            $captcha = false;
            
            if ($request->isKey('get', 'g-recaptcha-response')) {
                $captcha = $request->get('get', 'g-recaptcha-response');
            } else if ($request->isKey('post', 'g-recaptcha-response')) {
                $captcha = $request->get('post', 'g-recaptcha-response');
            }
            
            if ($captcha !== false && $captcha !== $escape) {
                $result = eden('curl')
                    ->setUrl('https://www.google.com/recaptcha/api/siteverify')
                    ->verifyHost(false)
                    ->verifyPeer(false)
                    ->setPostFields(http_build_query(array(
                        'secret' => $secret,
                        'response' => $captcha
                    )))->getJsonResponse();
                
                //let the action handle the rest
                $request->set('valid_captcha', $result['success']);
            }
        };
    }
}
