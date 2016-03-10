<?php
class Core_Pages_Render 
{
    //put your code here
    public $_nodeName;
    public $_actionName;
    public $_layout;
    
    public function setNode($node)
    {
        $this->_nodeName=$node;
    }
    public function getNode()
    {
        return $this->_nodeName;
    }
    public function setAction($action)
    {
        $this->_actionName=$action;
    }
    public function getAction()
    {
        return $this->_actionName;
    }
    public function loadLayout()
    {
        $layout="";
        $wp=new Core_WebsiteSettings();
        $flag=0;
        
        $layout=$wp->documentRoot."/pages/frontend/".$wp->themeName."/layout/".$this->_nodeName."_".$this->_actionName.".xml";
        if(Core::fileExists($layout))
        {
            $flag=1;
        }
        if($flag==0)
        {
            $layout=$wp->documentRoot."/pages/frontend/".$wp->themeName."/layout/".$this->_nodeName.".xml";
            if(Core::fileExists($layout))
            {
                $flag=1;
            }
        }
        if($flag==0)
        {
            $layout=$wp->documentRoot."/pages/frontend/".$wp->themeName."/layout/default.xml";
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
                foreach ($blockSettings as $blockData)
                {
                    foreach ($blockData as $blockProperties)
                    {
                        if(Core::keyInArray('@attributes', $blockProperties))
                        {
                            $blockConfigData=$blockProperties['@attributes'];
                            $class=$blockConfigData['class'];
                            $block=new $class();
                            $block->setBlockName($blockConfigData['name']);
                            $block->setTemplate($blockConfigData['template']);
                            $block->execute();
                        }                    
                    }
                }
            }
        }
    }
}
