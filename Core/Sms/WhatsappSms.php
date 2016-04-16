<?php
include_once './lib/whatsapp/whatsprot.class.php';
class Core_Sms_WhatsappSms 
{
    function __construct() {
        $usename='919700590406';
	$identity='ramesh';
	$nickname="ramesh";
	$password='N9zDoAJjmAMotbV26VFgAznI6MI=';
	$debug=false;
	$w=new WhatsProt($usename,$identity,$nickname,$password,$debug);
	$w->connect();
	$w->loginWithPassword($password);
	
	$w->sendMessage('919703298025','Hi Ramesh');
	
    ;
}
}
