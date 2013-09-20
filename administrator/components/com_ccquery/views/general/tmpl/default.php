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
	JHTML::_('behavior.tooltip');
	$db = &JFactory::getDBO();
	$config = new JConfig();
	
?>

<form action="<?php echo $this->action; ?>" method="post" name="adminForm" id="adminForm">
<div class="cccontainer">
    <fieldset>
		<h1><?php echo JText::_('CCQ_GENERAL_TITLE'); ?></h1>
		<p class="titledesc"><?php echo JText::_('CCQ_GENERAL_TITLE_DESCRIPTION'); ?></p>
    </fieldset>

	<table width=100% border=0 cellspacing=0 cellpadding=0>
	<tr>
	<td>
		<div style="width:100%;">
			<fieldset class="adminform">
				<legend><?php echo JText::_('CCQ_GENERAL_CON_DETAILS'); ?></legend>
				<table class="admintable">

					<tr>
						<td class="key">
							<label for="boardname"><?php echo JText::_( 'CCQ_HOST_NAME' ); ?>:</label>
						</td>
						<td>
							<label for="boardname"><?php echo $config->host; ?></label>
						</td>
					</tr>
					<tr>
						<td class="key">
							<label for="boardname"><?php echo JText::_( 'CCQ_SERVER_TYPE' ); ?>:</label>
						</td>
						<td>
							<label for="boardname"><?php echo $config->dbtype; ?></label>
						</td>
					</tr>

					<tr>
						<td class="key">
							<label for="boardname"><?php echo JText::_( 'CCQ_SQL_VERSION' ); ?>:</label>
						</td>
						<td>
							<label for="boardname"><?php echo $db->getVersion(); ?></label>
						</td>
					</tr>

					<tr>
						<td class="key">
							<label for="boardname"><?php echo JText::_( 'CCQ_COLLATION' ); ?>:</label>
						</td>
						<td>
							<label for="boardname"><?php echo $db->getCollation(); ?></label>
						</td>
					</tr>
				</table>
			</fieldset>
		</div>
	</td>
	<td>
		<div style="width:100%;">
			<fieldset class="adminform">
				<legend><?php echo JText::_('CCQ_CLIENT_DETAILS'); ?></legend>
				<table class="admintable">
					<tr>
						<td class="key">
							<label for="boardname"><?php echo JText::_( 'CCQ_NAME' ); ?>:</label>
						</td>
						<td>
							<label for="boardname"><?php echo $config->db; ?></label>
						</td>
					</tr>

					<tr>
						<td class="key">
							<label for="boardname"><?php echo JText::_( 'CCQ_PREFIX' ); ?>:</label>
						</td>
						<td>
							<label for="boardname"><?php echo $config->dbprefix; ?></label>
						</td>
					</tr>

					<tr>
						<td class="key">
							<label for="boardname"><?php echo JText::_( 'CCQ_USER_NAME' ); ?>:</label>
						</td>
						<td >
							<label for="boardname"><?php echo $config->user; ?></label>
						</td>
					</tr>
					<tr>
						<td class="key">
							<label for="boardname"><?php echo JText::_( 'CCQ_CLIENT_IP' ); ?>:</label>
						</td>
						<td>
							<label for="boardname"><?php echo $_SERVER['REMOTE_ADDR']; ?></label>
						</td>
					</tr>
				</table>
			</fieldset>
		</div>
	</tr>
	</table>

</div>
<input type="hidden" name="option" value="com_ccquery" />
<input type="hidden" name="task" value="" />

</form>
<div class="ccpowered-by">
    Powered by <a href="http://codeclassic.org"><b>ccQuery</b></a>
</div>