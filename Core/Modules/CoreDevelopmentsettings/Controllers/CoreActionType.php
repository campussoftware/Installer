<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CoreActionType
 *
 * @author ramesh
 */
class Core_Modules_CoreDevelopmentsettings_Controllers_CoreActionType extends Core_Controllers_NodeController
{
    //put your code here
    private $_name=NULL;
    private $_short_code=NULL;
    
    public function setNodeActionName($name)
    {
        $this->_name=$name;
    }
    public function getNodeActionName()
    {
        return $this->_name;
    }
    public function setNodeActionShortCode($code) 
    {
        $this->_short_code=$code;
    }
    public function getNodeActionShortCode()
    {
        return $this->_short_code;
    }
    public function dataSave()
    {        
        try
        {
            $db1=new Core_DataBase_ProcessQuery();
            $db1->setTable("core_action_type");
            $db1->addField("id");
            $db1->addWhere("short_code='".$this->getNodeActionShortCode()."'");
            $registernodeid=$db1->getValue();
            $db=new Core_DataBase_ProcessQuery();
            $db->setTable("core_action_type");
            $db->addFieldArray(array("name"=>$this->getNodeActionName()));
            $db->addFieldArray(array("short_code"=>$this->getNodeActionShortCode()));            
            $db->addFieldArray(array("updatedat"=>Core::getDateTime()));
            if($registernodeid)
            {
                $db->addWhere("short_code='".$this->getNodeActionShortCode()."'");
                $db->buildUpdate();
            }
            else
            {
                $db->addFieldArray(array("createdat"=>Core::getDateTime()));
                $db->buildInsert();
            }           
            $db->executeQuery();        
        }
        catch(Exception $ex)
        {
            Core::Log(__METHOD__.$ex->getMessage(),"installdata.log");
        }
    }  
    
}
