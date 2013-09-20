<?php
/**
* Survey Force component for Joomla
* @version $Id: def.php 2009-11-16 17:30:15
* @package Survey Force
* @subpackage def.php
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' ); 

// current server time
$now = date( 'Y-m-d H:i', time() );
if (!defined('_CURRENT_SERVER_TIME')) define( '_CURRENT_SERVER_TIME', $now );
if (!defined('_CURRENT_SERVER_TIME_FORMAT')) define( '_CURRENT_SERVER_TIME_FORMAT', '%Y-%m-%d %H:%M:%S' ); 
if (!defined('_PDF_GENERATED')) define('_PDF_GENERATED','Generated:');
if (!defined('_PDF_POWERED')) define('_PDF_POWERED','Powered by Joomla!'); 
?>