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
class Core_Modules_CoreDevelopmentsettings_Data_CoreActionType 
{
    //put your code here
    public function execute()
    {
        try
        {            
            $registerController=CoreClass::getController("core_registernode", "core_developmentsettings");
            $registerController->setNodeFileName("core_action_type");
            $registerController->setNodeNameData("core_action_type");
            $registerController->setDisplayValue("Action Type");
            $registerController->setIsModule("0");
            $registerController->setRootModuleId("core_developmentsettings");
            $registerController->setModuleId("core_developmentsettings");
            $registerController->setModuleDisplayId("core_developmentsettings");
            $registerController->setSortValue("3");
            $registerController->setIcon("");
            $registerController->setMenu("");
            $registerController->setIsNotification("0");
            $registerController->dataSave();
            
            $registerController=CoreClass::getController("core_node_settings", "core_developmentsettings");
            $registerController->setRegisternodeId("core_action_type");           
            $registerController->setTablename("core_action_type");
            $registerController->setAutokey("id");
            $registerController->setPrimarykey("short_code");
            $registerController->setDescriptorkey("name");
            $registerController->setMandotatoryAdd("name|short_code");
            $registerController->setMandotatoryEdit("name|short_code");
            $registerController->setUniquefields("name|short_code");
            $registerController->setHideAdd("");
            $registerController->setHideEdit("");
            $registerController->setHideView("");
            $registerController->setHideAdmin("");
            $registerController->setReadonlyAdd("");
            $registerController->setReadonlyEdit("");
            $registerController->setBoolattributes("");
            $registerController->setFile("");
            $registerController->setFck("");
            $registerController->setCheckbox("");
            $registerController->setSelectbox("");
            $registerController->setMultivalues("");
            $registerController->setEditlist("");
            $registerController->setNumberattribute("");
            $registerController->setSearch("name|short_code");
            $registerController->setDependee("");
            $registerController->setDefaultvalues("");
            $registerController->setExactsearch("");
            $registerController->setTotal("");
            $registerController->setColorattributes("");
            $registerController->setCoreNodeActionsId("add|admin|delete|edit");  
            $registerController->setActionrestriction("");  
            $registerController->setDefaultAction("admin"); 
            $registerController->setDefaultCollection('1');
            $registerController->setIsArchive("");  
            $registerController->dataSave();
            
            $actionTypeController=CoreClass::getController("core_action_type", "core_developmentsettings");
            $actionTypeController->setNodeActionName("Individual");
            $actionTypeController->setNodeActionShortCode("IN");
            $actionTypeController->dataSave();
            
            $actionTypeController->setNodeActionName("Button");
            $actionTypeController->setNodeActionShortCode("BT");
            $actionTypeController->dataSave();
            
            $actionTypeController->setNodeActionName("Single");
            $actionTypeController->setNodeActionShortCode("SN");
            $actionTypeController->dataSave();
            
            $actionTypeController->setNodeActionName("MRA");
            $actionTypeController->setNodeActionShortCode("MRA");
            $actionTypeController->dataSave();
        }
        catch (Exception $ex)
        {
            Core::Log($ex->getMessage(),"installdataexception.log");
        }
    }
}
