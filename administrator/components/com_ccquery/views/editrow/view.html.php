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

class ccqueryViewEditRow extends JView {
    function display($tpl = null) {
		global $mainframe;
		$config = new JConfig();

    	$model 	= $this->getModel();

		$data 	= JRequest::get('post');
		$tbl 	= JRequest::getVar('tablename',$data['tablename']);
		$pagemode = JRequest::getVar('pagemode',$data['pagemode']);
		
		$query	= 'SHOW FIELDS FROM '.$tbl;
		$uri 	= 'index.php?option=com_ccquery&view=editrow';

		$grid = new DataGrid($tbl, $query, $activetab);
        $cctable = $grid->bindEditableGrid($pagemode, $data);
       
        JToolBarHelper::title(JText::_($tbl).': <small><small>['.(($pagemode == "NEW")?JText::_('CCQ_EDITROW_NEW'):JText::_('CCQ_EDITROW_EDIT')).']</small></small>' );
        JToolBarHelper::save('saveRow');
		JToolBarHelper::cancel('cancelRow');
        
	    $this->assignRef('tables', $treeitems);
		$this->assignRef('dbname', $config->db);
		$this->assignRef('action', $uri);
		$this->assignRef('tabsel', $tbl);
		$this->assignRef('pagemode', $pagemode);
		$this->assignRef('cctable', $cctable);
		
        parent::display($tpl);
    }
}
?>