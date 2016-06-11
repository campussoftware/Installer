<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CoreSetupschema
 *
 * @author venkatesh
 */
class Core_Modules_CoreDevelopmentsettings_Setup_V1_CoreSetupschema
{
    //put your code here
    function execute()
    {
        $setup=new Core_DataBase_Setup();   
        $setup->setTable("core_setupschema");
        if($setup->tableExists($setup->getTable()))
        {
            $setup->addColumnName(["name"=>"attributeversion",
                "displayValue"=>"Attribute Version",                
                "type"=>"varchar",
                "size"=>"255",
                "after"=>"dataversion"
                ]);
            $setup->alterTable();
        }
        
    }
}
