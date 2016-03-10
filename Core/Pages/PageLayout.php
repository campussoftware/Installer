<?php
class Core_Pages_PageLayout extends Core_Model_Language
{
    protected $_content=null;
    protected $_jsContent=null;
    protected $_cssContent=null;
    protected $_themeName=NULL;
    protected $_isFooter=NULL;
    protected $_isHeader=NULL;
    protected $_PagePropties=array();
    public $_currentNodeName=NULL;
    public $_currentNodeModule;
    public $_nodeName;


    public function setCurrentNodeName($nodeName)
    {
        $this->_currentNodeName=$nodeName;
    }
    protected function setTheme($themeName)
    {
        $this->_themeName=$themeName;
    }
    protected function setFooter()
    {
        $this->_isFooter=1;
        $this->_isHeader=NULL;
    }
    protected function setHeader()
    {
        $this->_isHeader=1;
        $this->_isFooter=NULL;
    }
    protected function addJs($filename)
    {
        $deaultroot=true;
        $rootObj=new Core_WebsiteSettings();
        if(strpos($filename,"https://") !== false) 
        {
            $deaultroot=false;
        }
        if(strpos($filename,"http://") !== false) 
        {
            $deaultroot=false;
        }
        if($deaultroot)
        {
            $jsFileLocation=$rootObj->websiteUrl.$filename;
        }
        else
        {
            $jsFileLocation=$filename;
        }
        $this->_jsContent='
        <script type="text/javascript" src="'.$jsFileLocation.'"></script>';
        
        $this->_content.=$this->_jsContent; 
    }
    protected function addCss($filename)
    {
        $deaultroot=true;
        $rootObj=new Core_WebsiteSettings();
        if(strpos($filename,"https://") !== false) 
        {
            $deaultroot=false;
        }
        if(strpos($filename,"http://") !== false) 
        {
            $deaultroot=false;
        }
        if($deaultroot)
        {
            $cssFileLocation=$rootObj->websiteUrl.$filename;
        }
        else
        {
            $cssFileLocation=$filename;
        }
        $this->_cssContent='
        <link href="'.$cssFileLocation.'" rel="stylesheet" />';
        
        $this->_content.=$this->_cssContent;
    }
    protected function addContent($content)
    {
        if($content)
        {
            if($this->_content)
            {
                $this->_content.=" 
                                   ";
            }
            $this->_content.=$content;
        }
    }
    
    protected function renderLayout()
    {
        
        echo $this->_content;
        $this->_content=null;
        return ;
    }
    protected function addDefaultJs()
    {                
        $ws=new Core_WebsiteSettings();
        $pageSection="";
        if($this->_isHeader==1)
        {
            $pageSection="header";
        }
        if($this->_isFooter==1)
        {
            $pageSection="footer";
        }
        if($pageSection!="")
        {
            $jsFolder=$ws->documentRoot."js/".$pageSection."/"."Core";
            $fl=new Core_FileList();
            $fl->setDir($jsFolder);
            $fl->setFilterExtension("js");
            $fileslist=$fl->scanFileList();    
            if(count($fileslist)>0)
            {

                foreach ($fileslist as $filename)
                {
                    $filename=str_replace($ws->documentRoot,$ws->websiteAdminUrl,$filename);
                    $this->addJs($filename);
                }
            }
            $jsFolder=$ws->documentRoot."js/".$pageSection."/".$ws->themeName;
            $fl=new Core_FileList();
            $fl->setDir($jsFolder);
            $fl->setFilterExtension("js");
            /* @var $fileslist type */
            $fileslist=$fl->scanFileList();    
            if(count($fileslist)>0)
            {

                foreach ($fileslist as $filename)
                {
                    $filename=str_replace($ws->documentRoot,$ws->websiteAdminUrl,$filename);
                    $this->addJs($filename);
                }
            }
            if($this->_isFooter)
            {
                $jsFolder=$ws->documentRoot."js/"."project";
                $fl=new Core_FileList();
                $fl->setDir($jsFolder);
                $fl->setFilterExtension("js");
                $fileslist=$fl->scanFileList();    
                if(count($fileslist)>0)
                {

                    foreach ($fileslist as $filename)
                    {
                        $filename=str_replace($ws->documentRoot,$ws->websiteAdminUrl,$filename);
                        $this->addJs($filename);
                    }
                }
            }
        }
        
        
    }
    protected function addDefaultCss()
    {                
        $ws=new Core_WebsiteSettings();
        $pageSection="";
        if($this->_isHeader==1)
        {
            $pageSection="header";
        }
        if($this->_isFooter==1)
        {
            $pageSection="footer";
        }
        if($pageSection!="")
        {
            $defaultCssFolder=$ws->documentRoot."css/".$pageSection."/"."Core";
            $fl=new Core_FileList();
            $fl->setDir($defaultCssFolder);
            $fl->setFilterExtension("css");
            $fileslist=$fl->scanFileList();
            if(count($fileslist)>0)
            {
                foreach ($fileslist as $filename)
                {
                    $filename=str_replace($ws->documentRoot,$ws->websiteAdminUrl,$filename);
                    $this->addCss($filename);
                }
            }
            $defaultCssFolder=$ws->documentRoot."css/".$pageSection."/".$ws->themeName;
            $fl=new Core_FileList();
            $fl->setDir($defaultCssFolder);
            $fl->setFilterExtension("css");
            $fileslist=$fl->scanFileList();
            if(count($fileslist)>0)
            {
                foreach ($fileslist as $filename)
                {
                    $filename=str_replace($ws->documentRoot,$ws->websiteAdminUrl,$filename);
                    $this->addCss($filename);
                }
            }
        }
        
    }
    public function loadAttributeTemplate($attributeType,$FieldName=NULL,$actionName=NULL)
    {         
        $attributeType=ucwords($attributeType);
        if($actionName)
        {            
            $filename="Attributes/".Core::convertStringToFileName($actionName)."/".$attributeType."Template.phtml";  
            if(!$this->loadLayout($filename,1))
            {
                $filename="Attributes/".$attributeType."Template.phtml";
                $this->loadLayout($filename,1);
            }
        }
        else
        {
            $filename="Attributes/".$attributeType."Template.phtml";
            $this->loadLayout($filename,1);
        }  
        return true;
    }
    public function loadActionButtonTemapate($actionData,$primaryKeyValue,$parentActionPath)
    {         
        $this->_primaryKeyValue=$primaryKeyValue;
        $this->_actionData=$actionData;
        $this->_parentActionPath=$parentActionPath;
        $actionCode=ucwords($actionData['code']);                    
        $filename="ActionButtons/".Core::convertStringToFileName($actionCode)."Template.phtml";  
        if(!$this->loadLayout($filename,1))
        {
            $filename="ActionButtons/Template.phtml";
            $this->loadLayout($filename,1);
        }
        
        return true;
    }
    public function loadLayout($filename,$duplicateLoad=0,$returnFile=0)
    {        
        global $actionRequestFrom;
        $flag=0;
        $ws=new Core_WebsiteSettings();
        $templateRootPath=$ws->documentRoot."pages/";
        if($actionRequestFrom=="admin")
        {
           $templateRootPath.="adminhtml/";
        }
        else
        {
            $templateRootPath.="frontend/";
        }
        $currentnode=$this->_nodeName;
        $includepath="";
        if($currentnode)
        {
            $module=$this->_currentNodeModule;
            if($module)
            {    
                $module=Core::convertStringToFileName($module);
                $filenamePath=$templateRootPath.$ws->themeName."/template/".$module."/".Core::convertStringToFileName($currentnode)."/".$filename;
                if(Core::fileExists($filenamePath) && $flag==0)
                {
                    $includepath=$filenamePath;
                    $flag=1;
                }
            }
            if($flag==0)
            {
                $filenamePath=$templateRootPath.$ws->themeName."/template/".Core::convertStringToFileName($currentnode)."/".$filename;
                if(Core::fileExists($filenamePath))
                {
                    $includepath=$filenamePath;
                    $flag=1;
                }                
            }
        }
        if($flag==0)
        {
            $filenamePath=$templateRootPath.$ws->themeName."/template/".$filename;        
            if(Core::fileExists($filenamePath))
            {            
                //echo '<div class="templatehits">'.$filenamePath."</div>";
                $includepath=$filenamePath;
                $flag=1;
            }
        }
        if(Core::fileExists($includepath))
        {
            if($returnFile==1)
            {
                return $includepath;
            }
            if($duplicateLoad=="0")
            {
                include_once $includepath;
            }
            else
            {
                include $includepath;
            }
            return true;
        }
        else
        {
            
            return false;
        }
    }
    
}
?>
