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
?>

<form action="<?php echo $this->action; ?>" method="post" name="adminForm" id="adminForm">
	<div style="width:100%;float:left;">
		<?php echo $this->cctable; ?>
	</div>

<input type="hidden" name="option" value="com_ccquery" />
<input type="hidden" name="id" value="Test" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="pagemode" value="<?php echo $this->pagemode; ?>" />
<input type="hidden" name="tablename" value="<?php echo $this->tabsel; ?>" />

</form>


<div class="ccpowered-by">
    Powered by <a href="http://codeclassic.org"><b>ccQuery</b></a>
</div>
