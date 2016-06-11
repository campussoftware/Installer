<?php  
    header('Access-Control-Allow-Origin: *');  
    error_reporting(0);
    set_time_limit(0);
    include_once 'Boostrap.php';     
    Core::checkMode();
    Core::checkCache();	
    $wp=new Core_WebsiteSettings();
    $extension=substr($_REQUEST['reditectpath'], -3);     
    if(Core::inArray($extension, array(".js","css","png","jpg","gif")))
    {
       exit; 
    }
    global $sessionData;
    session_start();
    global $globalnode_settings_details;
    global $nodefiledetails;
    global $currentNode;
    global $currentNodePropertices;
    global $actionRequestFrom;
    try
    {
        $currentNodePropertices=new Core_Model_AdminSettings($_REQUEST,$_FILES);
        $parentNode=$currentNodePropertices->_parentNode;
        $parentValue=$currentNodePropertices->_parentValue;
        $parentAction=$currentNodePropertices->_parentAction;
        $currentNode=$currentNodePropertices->_currentNode;  
        $currentAction=$currentNodePropertices->_currentAction;
        $currentModule=Core::getValueFromArray($currentNodePropertices->_nodeDetails,'module');
        $currentModuleDisplay=Core::getValueFromArray($currentNodePropertices->_nodeDetails,'moduledisplay');
        $currentRootModule=Core::getValueFromArray($currentNodePropertices->_nodeDetails,'rootmodule');
        $currentSelector=$currentNodePropertices->_currentSelector;
        $methodType=$currentNodePropertices->_methodType;
        $apiMethod=$currentNodePropertices->_isAPI;
        $actionRequestFrom=$currentNodePropertices->_actionSource;
        $childNode=$currentNodePropertices->_childNode;    
        $requestedData=$currentNodePropertices->_requestedData;
        $filesData=$currentNodePropertices->_filesData;
        
        $session=new Core_Session();
        $sessionData=$session->getSessionData();    
          
        if($methodType!='POST' && $apiMethod==1)
        {
            echo json_encode(array("status"=>"error","message"=>"Please Provide Valid Url"));
            exit;
        }        
        
        $currentProfileCode=Core::getValueFromArray($sessionData,'profile_id');
        $header=true;
        $navigation=true;
        $footer=true;
        
        if($currentAction!="")
        {
            $action=$currentAction;
        }
        else
        {
            if($actionRequestFrom=='frentend')
            {
                $action="index";
            }
            else
            {
                $action="admin";
            }
        }
        if($actionRequestFrom=='frontend')
        {
            $frontController=CoreClass::getFrontController($currentNode,$currentModule,$action);
            $frontController->setNodeName($currentNode);
            $frontController->setActionName($action);
	    $frontController->setCurrentSelector($currentSelector);
            $frontController->setRequestedData($requestedData);
            $frontController->setFilesData($filesData);
            $functionName=$action."Action";
            if(method_exists($frontController,$functionName))
            {
                $frontController->$functionName();
            }
            else
            {
                $frontController->indexAction();
            }
        }
        else
        {
            if($action=="print" || $action=="export")
            {
                $header=false;
                $navigation=false;
                $footer=false;
            }
            if($methodType=="POST" || ($currentProfileCode=="" && $actionRequestFrom=='admin'))
            {  
               $header=false;
               $navigation=false;
               $footer=false;
            }    

            if($currentNode!="")
            {  
                $node=CoreClass::getController($currentNode,$currentModule,$action);   
                $node->setNodeName($currentNode);
                $node->setAPI($apiMethod);
                $node->setChildNode($childNode);
                $node->setActionName($action);
                $node->setParentNode($parentNode);
                $node->setParentValue($parentValue);
                $node->setParentAction($parentAction);
                $node->setCurrentSelector($currentSelector);
                $node->setMethodType($methodType);
                $node->setRequestedData($requestedData);
                $node->setFilesData($filesData);
                $node->checkSession();                
                $functionName=$action."Action";   

                if(method_exists($node,$functionName))
                {
                    $node->$functionName();
                }
                else
                {
                    if($methodType=='REQUEST')
                    {
                        $node->noAction();
                    }
                    else 
                    {
                    }
                }

            }
            else
            {                 
                $session=new Core_Session();
                $session=$session->getSessionMaganager(); 
                $homeController=new Core_Controllers_AdminController();
                $homeController->setNodeName($currentNode);
                $homeController->setActionName($action);
                $homeController->setRequestedData($requestedData);
                $homeController->setFilesData($filesData);
                $functionName=$action."Action";
                if(method_exists($homeController,$functionName))
                {
                    $homeController->$functionName();
                }
                else
                {
                    $homeController->indexAction();
                }     

            }
        }
    }
 catch (Exception $ex)
 {
     echo $e->getMessage();
 }
?>