<?php
/*
 * Usage
 */

use gyaani\guy\Classes\CurlWrap;

include __DIR__. "/../vendor/autoload.php";

$settings = ['handlessl' => false, 'savecookie' => false, 'loadcookie' => false,'diagnostic' => true,'autoreferer' => true ];

$curlWrap = new CurlWrap($settings);
$resultPage = $curlWrap
    ->url("https://commerce.rediff.com/commerce/v7/checkout.jsp")
    ->referer("shopping.rediff.com")
    ->useragent("My bot 1.1V")
    ->post(['var' => 'value'],$isJson = false)
    ->headers([])
    ->exec();

$resultPage= $curlWrap->page;
$error = $curlWrap->error;
$info = $curlWrap->info;
$requestSent = $curlWrap->requestSent;
var_dump('END');
