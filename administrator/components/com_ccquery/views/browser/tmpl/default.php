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
?>
	<script type="text/javascript">

	function editClick_Handler(rowID, colCount, eventSource){
		var form 		= document.adminForm;
		form.rowid.value= rowID;
		form.action 	= 'index.php?option=com_ccquery&view=editrow';
		submitform();

	}

	function deleteClick_Handler(rowID, colCount, eventSource){
		if(confirm('<?php echo JText::_('CCQ_DELETE_CONFIRM'); ?>')){
			var form 		= document.adminForm;
			form.rowid.value= rowID;
			form.task.value = 'deleteRow';
			submitform();
		}
	}

	function treeNodeClick_Handler(tabName)
	{
		var form 				= document.adminForm;
		form.tablename.value 	= tabName;
		form.pagemode.value 	= "2";
		form.filter_order.value = '';
		form.filter_order_Dir.value = '';
		submitform();
	}

	function toggleTreeNode(current)
	{
		source = document.getElementById('childNodes');

		if(source.style.display != 'none'){
			source.style.display = 'none';
		}
		else{
			source.style.display = '';
		}
	}

	</script>

<form action="<?php echo $this->action; ?>" method="post" name="adminForm" id="adminForm">

<div class="cccontainer">
	<div class="cctree-panel">
		<ul id="browser" class="rootnode">
			<li id="dbname"  ><span onclick="toggleTreeNode(this);"><b><?php echo $this->dbname.' ('.count($this->tables).')'; ?></b></span>
				<ul id='childNodes' name='childNodes' class="filetree treeview">
					<?php foreach($this->tables as $table) { ?>
						<li><span id="<?php echo $table; ?>" name="<?php echo $table; ?>" class="folder" onclick="treeNodeClick_Handler('<?php echo $table; ?>');  " ><?php echo $table; ?></span></li>
					<?php } ?>
				</ul>
			</li>
		</ul>
	</div>

	<div class="cccontent-panel">
		<h2 class="ccheading"><?php echo $this->tabsel; ?></h2>
		<div class="ccbrowse-result-panel"><div><?php echo $this->cctable; ?></div></div>
	</div>

</div>
<input type="hidden" name="option" value="com_ccquery" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="tablename" value="<?php echo $this->tabsel; ?>" />
<input type="hidden" name="pagemode" value="2" />
<input type="hidden" name="rowid" value="0" />

<input type="hidden" name="filter_order" value="<?php echo $this->filter_order; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->filter_orderDir; ?>" />

<script>
	<?php if($this->tabsel <> ''){ ?>
	document.getElementById("<?php echo $this->tabsel; ?>").className = 'folderselected';
	<?php  } ?>
</script>

</form>

<div class="ccpowered-by">
    Powered by <a href="http://codeclassic.org"><b>ccQuery</b></a>
</div>
