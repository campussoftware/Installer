<?php
class Core_Pages_Render extends Core_Model_Node
{
    public $_layout;
    
    function __construct() 
    {
        parent::__construct();
    }
    public function getAdminLayout()
    {        
        $layout="";
        $wp=new Core_WebsiteSettings();
        $flag=0;
        $module=Core::convertStringToFileName($this->_currentNodeModule);
        $node=Core::convertStringToFileName($this->_nodeName);
        $layout=$wp->documentRoot."pages/adminhtml/".$wp->themeName."/layout/".$module."/".$node."_".$this->_currentAction.".xml";
        if(Core::fileExists($layout))
        {
            $flag=1;
        }
        else 
        {
            if($this->_nodeName && $flag==0)
            {
                $layout=$wp->documentRoot."pages/adminhtml/".$wp->themeName."/layout/"."CoreActions"."/".$this->_currentAction.".xml";
                if(Core::fileExists($layout))
                {
                    $flag=1;
                }
            }
        }
        if($flag==0)
        {
            $layout=$wp->documentRoot."pages/adminhtml/".$wp->themeName."/layout/".$module."/".$node.".xml";
            if(Core::fileExists($layout))
            {
                $flag=1;
            }
        }
        if($flag==0)
        {
            if($this->_nodeName)
            {
                $layout=$wp->documentRoot."pages/adminhtml/".$wp->themeName."/layout/404.xml";
            }
            else
            {
                $layout=$wp->documentRoot."pages/adminhtml/".$wp->themeName."/layout/home.xml";
            }            
        }
        $this->_layout=$layout; 
        
       
    }
    public function getLayout()
    {
        
        $layout="";
        $wp=new Core_WebsiteSettings();
        $flag=0;
        
        $layout=$wp->documentRoot."pages/frontend/".$wp->themeName."/layout/".$this->_nodeName."_".$this->_currentAction.".xml";
        if(Core::fileExists($layout))
        {
            $flag=1;
        }
        if($flag==0)
        {
            $layout=$wp->documentRoot."pages/frontend/".$wp->themeName."/layout/".$this->_nodeName.".xml";
            if(Core::fileExists($layout))
            {
                $flag=1;
            }
        }
        if($flag==0)
        {
            $layout=$wp->documentRoot."pages/frontend/".$wp->themeName."/layout/default.xml";
        }
        $this->_layout=$layout;
    } 
    public function renderLayout()
    {
        $fileContent=Core::getFileContent($this->_layout);        
        if($fileContent)
        {
            $blockSettings = Core::convertXmlToArray($fileContent);                        
            if(Core::countArray($blockSettings)>0)
            {
                foreach ($blockSettings as $tagType=>$blockData)
                {
                    if(Core::countArray($blockData)>0)
                    {
                        foreach ($blockData as $blockProperties)
                        {
                            if(Core::countArray($blockProperties)>0)
                            {
                                if(Core::countArray($blockProperties)==1)
                                {
                                    $blockProperties=array($blockProperties);
                                }
                                foreach ($blockProperties as $eachblockProperties)
                                {
                                    if(Core::keyInArray('@attributes', $eachblockProperties))
                                    {
                                        $blockConfigData=$eachblockProperties['@attributes'];
                                        $class=$blockConfigData['class'];
                                        if($class)
                                        {
                                            try
                                            {
                                                $block=new $class($this);   
                                                $block->setLayout($this->_layout);
                                                $block->setBlockName($blockConfigData['name']);
                                                $block->setTemplate($blockConfigData['template']);
                                                $block->execute();
                                            }
                                            catch (Exception $ex)
                                            {
                                               echo $ex->getMessage();
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}