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
    public $_blockName;
    public $_template;
    public function setBlockName($block)
    {
        $this->_blockName=$block;
    }
    public function setTemplate($template)
    {
        $this->_template=$template;
    }
    public function execute()
    {
        $this->loadLayout($this->_template.".phtml", 1);
    }
    public function childBlock()
    {
        
    }
}
