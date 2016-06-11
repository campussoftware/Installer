<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SchemaInstall
 *
 * @author venkatesh
 */
class Core_Modules_CoreDevelopmentsettings_Data_V102_DataInstall
{
    //put your code here
    function __construct() 
    {
        $this->setDataUp();
    }
    protected function setDataUp()
    {
        $node="CoreCacheManagement";
        $nodeClass="Core_Modules_CoreDevelopmentsettings_Data_V102_".$node;
        $rnode=new $nodeClass();
        $rnode->execute();
        
    }
}
