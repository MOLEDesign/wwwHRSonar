<?php
/**
* Survey Force component for Joomla
* @version $Id: uninstall.surveyforce.php 2009-11-16 17:30:15
* @package Survey Force
* @subpackage uninstall.surveyforce.php
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

// Don't allow direct linking
defined( '_VALID_MOS' ) or defined( '_JEXEC' ) or die( 'Restricted access' );

function com_uninstall()
{
	return Jtext::_('SURVEYFORCE_COMPONENT_UNINSTALLED');
}		
?>