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

jimport( 'joomla.application.component.controller' );

class ccqueryController extends JController {
    function ccqueryController() {
        parent::__construct();
    }

   	function display($tmpl = null)
    {
    	$view = JRequest::getVar('view');
    	if( $view == 'editrow' )
    		JRequest::setVar('hidemainmenu', 1);
    	else
    		JRequest::setVar('hidemainmenu', 0);

        parent::display($tmpl);
    }
   	function newRow()
    {
    	$data = JRequest::get('post');
 		$link = JRoute::_('index.php?option=com_ccquery&view=editrow&tablename='.$data['tablename'].'&pagemode=NEW', false);
	    $this->setRedirect($link);
    }

    function cancelRow()
    {
      	$data = JRequest::get('post');
    	
        $link = JRoute::_('index.php?option=com_ccquery&view=browser&tablename='.$data['tablename'], false);
	    $this->setRedirect($link);
    }

    function saveRow()
    {
        $data = JRequest::get('post');
        $model = $this->getModel('editrow');

        if ($model->store($data)) {
            $message = JText::_('CCQ_SAVED_SUCCESSFULLY');
        } else {
            $message = $model->getError();
        }
		
	    $link = JRoute::_('index.php?option=com_ccquery&view=browser&tablename='.$data['tablename'], false);
	    $this->setRedirect($link, $message);
    }

   	function editRow()
    {
	    $link = JRoute::_('index.php?option=com_ccquery&view=editrow', false);
	    $this->setRedirect($link);
    }

    function deleteRow()
    {
        $data = JRequest::get('post');
        $model = $this->getModel('editrow');

        if ($model->delete($data)) {
            $message = JText::_('CCQ_DELETED_SUCCESFULLY');
        } else {
            $message = $model->getError();
        }

	    $link = JRoute::_('index.php?option=com_ccquery&view=browser&tablename='.$data['tablename'], false);
	    $this->setRedirect($link, $message);
    }
}
?>