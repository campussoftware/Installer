<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Session
 *
 * @author ramesh
 */
class Core_Session {

    private $siteObject;
    private $_sessionExists;
    public $_isProcessActive = 0;
    public $_api = 0;
    public $_identifier;

    function __construct() {
        $this->siteObject = new Core_WebsiteSettings();
        $cp = new Core_CodeProcess();
        $identifier = $cp->convertEncryptDecrypt('encrypt', $this->siteObject->websiteAdminUrl);
        $this->_identifier = $identifier;
    }

    public function setApi($apiValue) {
        $this->_api = $apiValue;
    }

    public function setProcessActive($active=1) {
        $this->_isProcessActive = $active;
    }

    private function checkSession() {
        $ipAddress = $_SERVER['REMOTE_ADDR'];
        if (Core::keyInArray('HTTP_X_FORWARDED_FOR', $_SERVER)) {
            $ipAddress = array_pop(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']));
        }
        $_SESSION[$this->_identifier]['ipaddress'] = $ipAddress;
        if (Core::keyInArray("profile_id", $_SESSION[$this->_identifier])) {
            $_SESSION[$this->_identifier]['_lastactivity'] = strtotime(date('Y-m-d H:i:s'));
            $this->_sessionExists = true;
        } else {
            $this->_sessionExists = false;
        }
    }

    public function getSessionMaganager() 
    {
        global $actionRequestFrom;
        $cp = new Core_CodeProcess();
        $identifier = $cp->convertEncryptDecrypt('encrypt', $this->siteObject->websiteAdminUrl);
        $this->checkSession();
        if ($this->_sessionExists) {
            if (is_array($_SESSION[$this->_identifier])) {
                return $_SESSION[$this->_identifier];
            } else {

                if ($actionRequestFrom == 'admin') {
                    $wp = new Core_WebsiteSettings();
                    Core::redirectUrl($wp->websiteAdminUrl . "core_users/logout");
                }
            }
        } else {
            if ($this->_api == 1) {
                
            } else {
                if ($actionRequestFrom == 'admin') {
                    $wp = new Core_WebsiteSettings();
                    Core::redirectUrl($wp->websiteAdminUrl . "core_users/logout");
                }
            }
        }
    }

    public function getSessionData() {
        $this->checkSession();
        if ($this->_sessionExists) {
            return $_SESSION[$this->_identifier];
        } else {
            return FALSE;
        }
    }

    public function destroySession() {
        $cp = new Core_CodeProcess();
        $identifier = $cp->convertEncryptDecrypt('encrypt', $this->siteObject->websiteAdminUrl);
        unset($_SESSION[$identifier]);
    }

}
