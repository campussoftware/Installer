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
class Core_Modules_CoreDevelopmentsettings_Setup_V1_SchemaInstall
{
    //put your code here
    function __construct() 
    {
        $this->setUp();
    }
    protected function setUp()
    {
        $node="CoreSetupschema";
        $nodeClass="Core_Modules_CoreDevelopmentsettings_Setup_V1_".$node;
        $rnode=new $nodeClass();
        $rnode->execute();
        
    }
}
