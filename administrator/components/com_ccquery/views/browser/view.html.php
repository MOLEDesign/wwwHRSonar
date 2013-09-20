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

class ccqueryViewBrowser extends JView {
    function display($tpl = null) {
		global $mainframe;
		$config = new JConfig();

    	$model 	= $this->getModel();
    	$treeitems 	= $model->getTreeData();

		$data 	= JRequest::get('post');
		$tbl 	= JRequest::getVar('tablename',$data['tablename']);
		if(!isset($tbl)){
			$tbl = $treeitems[0];
		}

		$query 		= "SELECT * FROM ".$tbl;
		$activetab 	= 2;
		$uri 		= 'index.php?option=com_ccquery&view=browser';

       	JToolBarHelper::addNew('newRow');

      	$filter_orderDirection 	= $mainframe->getUserStateFromRequest('com_ccquery.filter_order_Dir', 'filter_order_Dir', 'ASC','word');
        $filter_order 			= $mainframe->getUserStateFromRequest('com_ccquery.filter_order', 'filter_order', 'ordering', 'word');
        
		$limit 		= $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		$limitstart = JRequest::getVar('limitstart',0);
        $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

        if($query <> ''){
			$grid = new DataGrid($tbl, $query, $activetab);
	        $grid->setLimits($limitstart, $limit);
      		$grid->setOrdering($filter_order, $filter_orderDirection);
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
       	$this->assignRef('filter_order', $filter_order);
        $this->assignRef('filter_orderDir', $filter_orderDirection);
         
        parent::display($tpl);
    }
}
?>