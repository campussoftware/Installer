<?php
class Core_Sms_SendSms 
{
    protected $_mobileNumbers=array();
    protected $_message=NULL;
    protected $_smsSettings=array();
    function __construct() 
    {
        $smsSettings=CoreClass::getModel("core_sms_settings");
        $smsSettings->addCustomFilter("active_status='1'");
        $smsSettings->getCollection();
                
        if(Core::countArray($smsSettings->_collections)>0)
        {
            foreach ($smsSettings->_collections as $collection)
            {
                $this->_smsSettings=$collection;
            }
        };  
    }
    public function setMobileNumber($mobileNumbers)
    {
        if(!Core::isArray($mobileNumbers))
        {
            $mobileNumbers=array($mobileNumbers);
        }
        $this->_mobileNumbers=array_merge($this->_mobileNumbers,$mobileNumbers);
        
    }
    public function setMessage($message)
    {
        $this->_message=($message);
    }
    public function sendSms()
    {
        try
        {
            $nodeSave=new Core_Model_NodeSave();
            $nodeSave->setNode("core_sms_log");
            $nodeSave->setData("mobile_no", Core::convertArrayToString($this->_mobileNumbers, ','));
            $nodeSave->setData("sms_status", 'Sent');
            $nodeSave->setData("senderid", $this->_smsSettings['senderid']);
            $nodeSave->setData("date", Core::getDate());
            $nodeSave->setData("text",$this->_message);
            $nodeSave->save();
			echo $smsUrl=$this->_smsSettings['gateway']."&username=".$this->_smsSettings['username']."&pass=".$this->_smsSettings['password']."&route=".$this->_smsSettings['route']."&senderid=".$this->_smsSettings['senderid']."&numbers=".Core::convertArrayToString($this->_mobileNumbers,",")."&message=".$this->_message;
			
			$response=file_get_contents($smsUrl);
           /* $curl=new Core_CurlCall();
            $curl->setUrl($this->_smsSettings['gateway']);
            $curl->setReturnTransfer(true);
            $curl->setPostData("username", $this->_smsSettings['username']);
            $curl->setPostData("pass", $this->_smsSettings['password']);
            $curl->setPostData("route", $this->_smsSettings['route']);
            $curl->setPostData("senderid", $this->_smsSettings['senderid']);
            $curl->setPostData("numbers", Core::convertArrayToString($this->_mobileNumbers, ','));
            $curl->setPostData("message", $this->_message);
            $curl->callCurl();*/
            $nodeSave->setData("id", $nodeSave->getId());
            $nodeSave->setData("smstrackerid",trim(strip_tags($response)));
            $nodeSave->save();
        }
        catch(Exception $ex)
        {
            Core::Log($ex->getMessage());
        }
        return true;
    }
    
}
