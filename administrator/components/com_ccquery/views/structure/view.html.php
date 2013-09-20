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
jimport( 'joomla.application.component.view');
jimport('joomla.html.pagination');
include_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'DataGrid.php');

class ccqueryViewStructure extends JView {
    function display($tpl = null) {
		global $mainframe;
		$config = new JConfig();

    	$model 	= $this->getModel();
    	$treeitems 	= $model->getTreeData();

		$data 	= JRequest::get('post');
		$tbl = $data['tablename'];
		if(!isset($tbl)){
			$tbl = $treeitems[0];
		}

		$query = "SHOW FIELDS FROM ".$tbl;
		$activetab = 3;
		
		$uri 	= 'index.php?option=com_ccquery&view=structure';

        if($query <> ''){
			$grid = new DataGrid($tbl, $query, $activetab);
	        $cctable = $grid->bindGrid($this);
        }
        else{
        	$cctable 	= array();
        }

		$this->assignRef('tables', $treeitems);
		$this->assignRef('dbname', $config->db);
		$this->assignRef('action', $uri);
		$this->assignRef('tabsel', $tbl);
		$this->assignRef('cctable', $cctable);
        $this->assignRef('query', $query);
        $this->assignRef('activetab', $activetab);

        parent::display($tpl);
    }
}
?>