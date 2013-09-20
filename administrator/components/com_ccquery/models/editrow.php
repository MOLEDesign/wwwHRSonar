<?php
/**
 * @version		$Id$
 * @Project		ccQuery - Joomla! Database Explorer Extension/Component
 * @author 		Warrier, Thomas Varghese 
 * @package		ccQuery
 * @copyright	Copyright (C) 2008-2009 codeclassic.org. All rights reserved.
 * @license 	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class ccqueryModelEditRow extends JModel {
    function __construct() {
		parent::__construct();
    }

    function getTableData($tablename)
    {
    	$db 	= &JFactory::getDBO();
    	$query	= 'SELECT * FROM '.$tablename;

		$db->setQuery($query);
		$items = $db->loadRowList();

		return $items;
    }

    function store($data)
    {
		$setpart = ' SET ';
		$whrpart = ' WHERE ';
		for($index = 1; $index <= $data['colcount']; $index++){
			$colname = $data['colname_'.$index];
			$setpart .= $colname.' = \''.$data[$colname].'\'';

			if(strlen(trim($data['colvalue_'.$index])) > 0){
				$whrpart .= $colname.' = \''.$data['colvalue_'.$index].'\'';

				if($index < $data['colcount']){
					$whrpart .= ' AND ';
				}
			}

			if($index < $data['colcount']){
				$setpart .= ', ';
			}
		}

		if($data["pagemode"] == 'NEW'){
			$query = 'INSERT INTO '.$data['tablename'].$setpart;
		}
		else{
			$query = 'UPDATE '.$data['tablename'].$setpart.$whrpart;
		}

		$db 	= &JFactory::getDBO();
		$db->setQuery($query);
		if($db->query()){
			return true;
		}
		else{
			$this->setError($db->getError());
			return false;
		}
    }

   	function delete($data)
    {

		$whrpart = ' WHERE ';
		for($index = 1; $index <= $data['colcount']; $index++){

			$colname = $data['colname_'.$index];
			$fldname = $data[$colname.'_'.$data['rowid']];

			if(strlen(trim($fldname)) > 0){
				$whrpart .= $colname.' = \''.$fldname.'\'';

				if($index < $data['colcount']){
					$whrpart .= ' AND ';
				}
			}
		}

		$query = 'DELETE FROM '.$data['tablename'].$whrpart;

		$db 	= &JFactory::getDBO();
		$db->setQuery($query);
		if($db->query()){
			return true;
		}
		else{
			$this->setError($db->getError());
			return false;
		}
    }
}
?>