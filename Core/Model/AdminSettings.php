<?php
class Core_Model_AdminSettings 
    {
        public $_actionRequestFrom=NULL;
        public $_requestedData;
        public $_currentAction=NULL;
        public $_currentNode=NULL;
        public $_currentSelector=NULL;
        public $_parentNode=NULL;
        public $_parentAction=NULL;
        public $_parentValue=NULL;
        public $_exactSearchAttributes=array();
        public $_nodesFullStructure;
        public $_currentNodeStructure;  
        public $_navigationPath=array();
        public $_tableName=null;
        public $_primaryKey=null;
        public $_descriptor=NULL;
        public $_filesData=array();
        public $_nodeProperties=array();
        public $_methodType="REQUEST";
        public $_isAPI=NULL;
        public $_childNode=NULL;
        public $_nodeDetails=array();
        public $_actionSource='frentend';
        public function __construct($requesteddata,$filesData) 
        {
            if($_POST)
            {
                $this->_methodType="POST";
            }     
            if(count($requesteddata)>0)
            {
                foreach ($requesteddata as $key => $value) 
                {
                    if(!Core::isArray($value))
                    {
                        $requesteddata[$key]=Core_CodeProcess::stripslashes_deep($value);
                    }
                }
            }            
            $this->_requestedData=$requesteddata;
            $this->_filesData=$filesData;     
            $wp=new Core_WebsiteSettings();
            if(isset($this->_requestedData['reditectpath']))
            {
                $list=explode("/",$this->_requestedData['reditectpath']);
                if(Core::convertStringToLower($list['0'])=='api')
                {
			$this->_isAPI=1; 
			$this->_currentNode=$list['1'];
			$this->_currentAction=$list['2'];
			$this->_currentSelector=$list['3'];
			$this->_parentNode=$list['4'];
			$this->_parentAction=$list['5'];
			$this->_parentValue=$list['6']; 
			$np = new Core_Model_NodeProperties();
			$np->setNode($this->_currentNode);
			$this->_nodeDetails=$np->getNodeDetails();
                }                
                if(Core::convertStringToLower($list['0'])==$wp->adminRouteCode)
                {
                    $this->_actionSource="admin";
                    if($this->_methodType!="POST" && Core::getValueFromArray($list,'4')=='MTO')
                    {
                        $this->_currentNode=$list['1'];
                        $this->_currentAction=$list['2'];
                        $this->_currentSelector=$list['3'];
                        if($list[5])
                        {
                            $this->_childNode=$list[5];
                        }
                    }
                    else 
                    {
                        $this->_currentNode=Core::getValueFromArray($list,'1');
                        $this->_currentAction=Core::getValueFromArray($list,'2');
                        $this->_currentSelector=Core::getValueFromArray($list,'3');
                        if(Core::getValueFromArray($list,'4')!='MTO')
                        {
                            $this->_parentNode=Core::getValueFromArray($list,'4');
                            $this->_parentAction=Core::getValueFromArray($list,'5');
                            $this->_parentValue=Core::getValueFromArray($list,'6');
                        }
                    }
                    
                    if(Core::keyInArray($this->_currentNode."_parentnode", $this->_requestedData))
                    {
                        $this->_parentNode=$this->_requestedData[$this->_currentNode."_parentnode"];
                    }
                    if(Core::keyInArray($this->_currentNode."_parentidvalue", $this->_requestedData))
                    {
                        $this->_parentValue=$this->_requestedData[$this->_currentNode."_parentidvalue"];
                    }
                    if(Core::keyInArray($this->_currentNode."_parentaction", $this->_requestedData))
                    {
                        $this->_parentAction=$this->_requestedData[$this->_currentNode."_parentaction"];
                    }
                    if(Core::keyInArray("parentformNode", $this->_requestedData))
                    {
                        $this->_parentNode=$this->_requestedData['parentformNode'];
                    }
                    if(Core::keyInArray("parentformvalue", $this->_requestedData))
                    {
                        $this->_parentValue=$this->_requestedData['parentformvalue'];
                    }
                    if(Core::keyInArray("parentformAction", $this->_requestedData))
                    {
                        $this->_parentAction=$this->_requestedData['parentformAction'];
                    }
		$np = new Core_Model_NodeProperties();
		$np->setNode($this->_currentNode);
		$this->_nodeDetails=$np->getNodeDetails();
                }
                else
                {
                    $nodeModel=CoreClass::getModel("core_url_rewrite");
		    $nodeModel->setNodeName("core_url_rewrite");
                    $nodeModel->addCustomFilter("request_path='".addslashes($this->_requestedData['reditectpath'])."'");
                    $nodeModel->getCollection();	
					
                    if(Core::countArray($nodeModel->_collections))
                    {
                        foreach ($nodeModel->_collections as $collectionData)
                        {
                            $targetpath=$collectionData['target_path'];
                            $list=explode("/",$targetpath);
                            $this->_currentNode=$list['0'];
                            $this->_currentAction=$list['1'];
                            $this->_currentSelector=$list['2'];
                            $this->_parentNode=$list['3'];
                            $this->_parentAction=$list['4'];
                            $this->_parentValue=$list['5'];
                        }
			$np = new Core_Model_NodeProperties();
			$np->setNode($this->_currentNode);
			$this->_nodeDetails=$np->getNodeDetails();
                    }
                    else
                    {
                        $this->_currentNode=$list['0'];
                        $this->_currentAction=$list['1'];
                        $this->_currentSelector=$list['2'];
                        $this->_parentNode=$list['3'];
                        $this->_parentAction=$list['4'];
                        $this->_parentValue=$list['5'];   
			$np = new Core_Model_NodeProperties();
			$np->setRouterName($this->_currentNode);				
			$this->_nodeDetails=$np->getNodeDetailsBasedonRouter();						
			if(Core::countArray($this->_nodeDetails)==0)
			{
				$np = new Core_Model_NodeProperties();
				$np->setNode($this->_currentNode);
				$this->_nodeDetails=$np->getNodeDetails();
			} 
			else
			{
				$this->_currentNode=$this->_nodeDetails['nodename'];
			}
                    }
					  
                }				
			}
        }                
    }
?>
