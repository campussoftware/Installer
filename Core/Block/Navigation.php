<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Navigation
 *
 * @author venkatesh
 */
class Core_Block_Navigation extends Core_Block_Block 
{

    public $_navigationPath = Array();
    

    public function getNavigationPath() {
        global $currentNode;
        $wp = new Core_WebsiteSettings();
        $lb = new Core_Model_Language();

        if ($this->_controllerObj->_nodeName) 
        {
            $i = 0;
            if ($this->_controllerObj->_parentNode) {
                $this->_navigationPath[$i]['label'] = $lb->getLabel($this->_controllerObj->_parentNode);
                $this->_navigationPath[$i]['url'] = $wp->websiteAdminUrl . $this->_controllerObj->_parentNode;
                $i++;

                $np = new Core_Model_NodeProperties();
                $np->setNode($this->_controllerObj->_parentNode);
                $nodeStructure = $np->currentNodeStructure();
                $nr = new Core_Model_NodeRelations();
                $nr->setNode($this->_controllerObj->_parentNode);
                $relations = $nr->getCurrentNodeRelation();
                if (Core::keyInArray($nodeStructure['descriptor'], $relations)) {
                    $np = new Core_Model_NodeProperties();
                    $np->setNode($relations[$nodeStructure['descriptor']]);
                    $nodeStructure = $np->currentNodeStructure();
                    $db = new Core_DataBase_ProcessQuery();
                    $db->setTable($nodeStructure['tablename']);
                    $db->addField($nodeStructure['descriptor']);
                    $db->addWhere($nodeStructure['primkey'] . "='" . $this->_controllerObj->_parentValue . "'");
                    $db->buildSelect();

                    $descriptor = $db->getValue();
                } else {
                    $db = new Core_DataBase_ProcessQuery();
                    $db->setTable($nodeStructure['tablename']);
                    $db->addField($nodeStructure['descriptor']);
                    $db->addWhere($nodeStructure['primkey'] . "='" . $this->_currentDetails->_parentValue . "'");
                    $db->buildSelect();
                    $descriptor = $db->getValue();
                }

                $this->_navigationPath[$i]['label'] = $lb->getLabel($this->_controllerObj->_parentNode) . "( " . $descriptor . " )";
                $this->_navigationPath[$i]['url'] = $wp->websiteAdminUrl . $this->_controllerObj->_parentNode . "/" . $this->_currentDetails->_parentAction . "/" . $this->_currentDetails->_parentValue;
                $i++;
                $this->_navigationPath[$i]['label'] = $lb->getLabel($this->_controllerObj->_nodeName);
                $this->_navigationPath[$i]['url'] = $wp->websiteAdminUrl . $this->_controllerObj->_parentNode . "/" . $this->_currentDetails->_parentAction . "/" . $this->_currentDetails->_parentValue;
                $i++;
            } else {
                $this->_navigationPath[$i]['label'] = $lb->getLabel($this->_controllerObj->_nodeName);
                $this->_navigationPath[$i]['url'] = $wp->websiteAdminUrl . $this->_controllerObj->_nodeName;
                $i++;
            }
            if ($this->_controllerObj->_currentSelector) {
                $np = new Core_Model_NodeProperties();
                $np->setNode($this->_controllerObj->_nodeName);
                $nodeStructure = $np->currentNodeStructure();
                $nr = new Core_Model_NodeRelations();
                $nr->setNode($this->_controllerObj->_nodeName);
                $relations = $nr->getCurrentNodeRelation();
                if (Core::keyInArray($nodeStructure['descriptor'], $relations)) {
                    $np = new Core_Model_NodeProperties();
                    $np->setNode($relations[$nodeStructure['descriptor']]);
                    $nodeStructure = $np->currentNodeStructure();
                    $db = new Core_DataBase_ProcessQuery();
                    $db->setTable($nodeStructure['tablename']);
                    $db->addField($nodeStructure['descriptor']);
                    $db->addWhere($nodeStructure['primkey'] . "='" . $this->_controllerObj->_currentSelector . "'");
                    $db->buildSelect();

                    $descriptor = $db->getValue();
                } else {
                    $db = new Core_DataBase_ProcessQuery();
                    $db->setTable($nodeStructure['tablename']);
                    $db->addField($nodeStructure['descriptor']);
                    $db->addWhere($nodeStructure['primkey'] . "='" . $this->_controllerObj->_currentSelector . "'");
                    $db->buildSelect();
                    $descriptor = $db->getValue();
                }
                $this->_navigationPath[$i]['label'] = $lb->getLabel($this->_controllerObj->_nodeName) . " ( " . $descriptor . " )";
                $this->_navigationPath[$i]['url'] = "#";
            }
        }
        return $this->_navigationPath;
    }
}