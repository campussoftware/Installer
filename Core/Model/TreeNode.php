<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TreeNode
 *
 * @author ramesh
 */
class Core_Model_TreeNode 
{
    protected $_nodeName;
    protected $_filterCon;
    protected $_tableName;
    protected $_records;
    protected $_parent;
    
    public function setNodeName($node)
    {
        $this->_nodeName=$node;
    }
    public function getNodeName()
    {
        return $this->_nodeName;
    }
    public function addFilter($condition)
    {
        if($condition!="")
        {
            if($this->_filterCon)
            {
                $this->_filterCon." and ";
            }
            $this->_filterCon.$condition;
        }
    }
    public function setParent($parent)
    {
        $this->_parent=$parent;
    }
    public function getChildrecords()
    {
        $np= new Core_Model_NodeProperties($this->_nodeName);
        $currentNodeStructure=$np->currentNodeStructure();
        $this->_tableName=$currentNodeStructure['tablename'];
        $primkey=$currentNodeStructure['primkey'];
        $descriptor=$currentNodeStructure['descriptor'];
        $db=new Core_DataBase_ProcessQuery();            
        $db->setTable($this->_tableName);
        $db->addFieldArray(array($descriptor=>$descriptor));
        $db->addFieldArray(array($primkey=>$primkey));
        $db->addFieldArray(array("parent_level"=>"parent_level"));
        $db->addWhere($this->_filterCon);      
        if($this->_parent)
        {
            $db->addWhere("parent='".$this->_parent."'");
        }
        else
        {
            $db->addWhere("parent_level='1'");
        }
        
        $result1=$db->getRows();        
        if(count($result1)>0)
        {
            foreach($result1 as $rs1)
            {
                    $loop=$rs1['parent_level'];
                    $tabspace="";
                    for($k=1;$k<$loop;$k++)
                    {
                            $tabspace.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                    }			
                    $tabspace.="-->";
                    $this->_records[$rs1[$primkey]]=$tabspace.$rs1[$descriptor];
                    $this->_parent=$rs1[$primkey];				
                    $this->getChildrecords();
            }
            return $this->_records;
        }
        else
        {
                return $this->_records;
        }
    }
}
