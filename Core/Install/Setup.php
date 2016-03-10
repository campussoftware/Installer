<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Setup
 *
 * @author ramesh
 */
class Core_Install_Setup
{
    //put your code here
    function __construct() 
    {
        $this->schemaSetup();        
    }
    public function schemaSetup()
    {
		try
		{
                    $setup=new Core_DataBase_Setup();   
                    $setup->setTable("core_setupschema");
                    if(!$setup->tableExists($setup->getTable()))
                    {
                        $setup->setDisplayValue("Setup Schema Details");
                        $setup->addColumnName(array(
                            "name"=>"id",
                            "displayValue"=>"Id",
                            "prmiary"=>false,
                            "key"=>"unique",
                            "default"=>NULL,
                            "type"=>"bigint",
                            "size"=>"11",
                            "prmiary"=>1,
                            "auto_increment"=>1            
                        ));
                        $setup->addColumnName(array(
                            "name"=>"modulename",
                            "displayValue"=>"Module Name",            
                            "default"=>false,                
                            "type"=>"varchar",
                            "size"=>"255"          
                        ));
                        $setup->addColumnName(array(
                            "name"=>"schemaversion",
                            "displayValue"=>"Schema Version",                
                            "type"=>"varchar",
                            "size"=>"255"                
                        ));   
                        $setup->addColumnName(array(
                            "name"=>"dataversion",
                            "displayValue"=>"Data Version",                
                            "type"=>"varchar",
                            "size"=>"255"                
                        ));   
                        $setup->addColumnName(array(
                            "name"=>"createdby",
                            "displayValue"=>"Created User Id",            
                            "default"=>NULL,
                            "type"=>"int",
                            "size"=>"11"
                        ));
                        $setup->addColumnName(array(
                            "name"=>"createdat",
                            "displayValue"=>"Created Datetime",            
                            "default"=>NULL,
                            "type"=>"datetime"
                        ));
                        $setup->addColumnName(array(
                            "name"=>"updatedby",
                            "displayValue"=>"Updated User Id",            
                            "default"=>NULL,
                            "type"=>"int",
                            "size"=>"11"
                        ));
                        $setup->addColumnName(array(
                            "name"=>"updatedat",
                            "displayValue"=>"Updated Datetime",            
                            "default"=>NULL,
                            "type"=>"datetime"
                        ));
                        $setup->create();
                    }                      
                        
			$cp=new Core_CodeProcess();
			$files=$cp->dirToArray("Config");
                        
			if(Core::countArray($files)>0)
			{
                            foreach ($files as $folder=>$moduleData)
                            {
                                if(count($moduleData)>0)
                                {
                                    foreach ($moduleData as $module=>$moduleFiles)
                                    {
                                        if(count($moduleFiles)>0)
                                        {
                                            foreach ($moduleFiles as $file)
                                            {
                                                if(!is_array($file))
                                                {
                                                    $configFile="Config/".$folder."/".$module."/".$file;
                                                    $configFileContent=Core::getFileContent($configFile);
                                                    $configFileContentSettings=Core::convertXmlToArray($configFileContent);

                                                    if($configFileContentSettings)
                                                    {
                                                        $dp=new Core_DataBase_ProcessQuery();
                                                        $dp->setTable("core_setupschema");
                                                        $dp->addField("core_setupschema.id");
                                                        $dp->addWhere("core_setupschema.modulename='".$configFileContentSettings['name']."'");
                                                        $dp->buildSelect();
                                                        $recordid=$dp->getValue();
                                                        if($recordid=="")
                                                        {
                                                            $dp=new Core_DataBase_ProcessQuery();
                                                            $dp->setTable("core_setupschema");
                                                            $dp->addFieldArray(array("modulename"=>$configFileContentSettings['name']));                                                        
                                                            $dp->buildInsert();
                                                            $recordid=$dp->executeQuery();
                                                        }
                                                        

                                                        if(trim($configFileContentSettings['setuppath'])!="")
                                                        {
                                                                $setuppath=$configFileContentSettings['setuppath']."/SchemaInstall";
                                                                $className=str_replace("/", "_", $setuppath);
                                                                $setup=new $className();
                                                        }
                                                        
                                                        if($configFileContentSettings['datapath'])
                                                        {
                                                                $setuppath=$configFileContentSettings['datapath']."/DataInstall";
                                                                $className=str_replace("/", "_", $setuppath);
                                                                $setup=new $className();
                                                        }

                                                    }

                                                }   
                                            }
                                        }
                                    }   
                                }
                            }        
			}                        
			Core::createFile("mode.flag",1,"prod");
		}
		catch(Exception $ex)
		{
			Core::Log(__METHOD__."::".$ex->getMessage(),"installexception.log");
		}
		
    }
}
