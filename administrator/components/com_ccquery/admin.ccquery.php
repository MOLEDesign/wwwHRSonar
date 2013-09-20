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
require_once(JPATH_COMPONENT.DS.'controller.php');

	if (!JRequest::getVar('view') ) {
	    JRequest::setVar('view', 'general');
	}

	$doc = &JFactory::getDocument();
    $doc->addStyleSheet(JURI::base().'components/com_ccquery/assets/ccquery.css');
	
	JToolBarHelper::title('ccQuery '. JText::_('CCQ_COMTITLE'), 'ccquery');
	$view = JRequest::getVar('view');

	$item1 = 'index.php?option=com_ccquery&view=general';
	$item2 = 'index.php?option=com_ccquery&view=query';
	$item3 = 'index.php?option=com_ccquery&view=browser';
	$item4 = 'index.php?option=com_ccquery&view=structure';
	$item5 = 'index.php?option=com_ccquery&view=about';
	
	JSubMenuHelper::addEntry(JText::_('CCQ_MENU_GENERAL'), $item1, ($view == 'general') );
	JSubMenuHelper::addEntry(JText::_('CCQ_MENU_QUERY'), $item2, ($view == 'query'));
	JSubMenuHelper::addEntry(JText::_('CCQ_MENU_BROWSER'), $item3, ($view == 'browser'));
	JSubMenuHelper::addEntry(JText::_('CCQ_MENU_STRUCTURE'), $item4, ($view == 'structure'));
	JSubMenuHelper::addEntry(JText::_('CCQ_MENU_ABOUT'), $item5, ($view == 'about'));
	

	// Initialize the controller
	$controller = new ccqueryController( );
	$controller->execute( JRequest::getCmd('task'));
	$controller->redirect();
?>