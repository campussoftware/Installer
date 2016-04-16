<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Block
 *
 * @author venkatesh
 */
class Core_Block_Block extends Core_Pages_PageLayout
{
    //put your code here
    public $_layout=NULL;
    public $_blockName;
    public $_parentBlock=NULL;
    public $_template;
    public $_controllerObj;
    function __construct($controller) 
    {
        $this->_controllerObj=$controller;
    }
    public function setLayout($layout)
    {
        $this->_layout=$layout;
    }
    public function setBlockName($block)
    {
        $this->_blockName=$block;
    }
    public function setParentBlock($parent)
    {
        $this->_parentBlock=$parent;
    }
    public function setTemplate($template)
    {
        $this->_template=$template;
    }
    public function execute()
    {
        $this->loadLayout($this->_template.".phtml", 1);
    }
    public function loadChildBlock()
    {
        $layoutContent=Core::getFileContent($this->_layout);
        $blockProperties=Core::processXmlData($layoutContent,'//block[@parent="'.$this->_blockName.'"]');
        
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
                        $block=new $class($this->_controllerObj);   
                        $block->setLayout($this->_layout);
                        $block->setBlockName($blockConfigData['name']);
                        $block->setParentBlock($this->_blockName);
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
