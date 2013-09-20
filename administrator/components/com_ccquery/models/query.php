<?php
/**
 * @version		$Id$
 * @Project		ccQuery - Joomla! Database Explorer Extension/Component
 * @author 		Warrier, Thomas Varghese 
 * @package		ccQuery
 * @copyright	Copyright (C) 2008-2009 codeclassic.org. All rights reserved.
 * @license 	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// Import Joomla! libraries
jimport('joomla.application.component.model');

class ccqueryModelQuery extends JModel {
    function __construct() {
		parent::__construct();
    }

    function getTreeData()
    {
    	$db 	= &JFactory::getDBO();
    	$query	= 'SHOW TABLES';

		$db->setQuery($query);
		$items = $db->loadResultArray();

		return $items;
    }

    function getTableData($tablename)
    {
    	$db 	= &JFactory::getDBO();
    	$query	= 'SELECT * FROM '.$tablename;

		$db->setQuery($query);
		$items = $db->loadRowList();

		return $items;
    }

}
?>