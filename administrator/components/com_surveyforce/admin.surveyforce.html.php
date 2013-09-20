<?php
/**
* Survey Force component for Joomla
* @version $Id: admin.surveyforce.html.php 2009-11-16 17:30:15
* @package Survey Force
* @subpackage admin.surveyforce.html.php
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );


function txt2overlib($string){
	$string = str_replace(array("\r\n", "\r", "\n"), "<br />", $string);
	$string = str_replace('\"','"',$string);
	$string = str_replace('"','\"',$string);
	$string = str_replace("'","&#039;",$string);
	$string = str_replace("'","&#39;",$string);
	$string = str_replace('&quot;','\"',$string);
	return $string;
}

class survey_force_adm_html 
{
	function showInstallMessage( $message, $title, $url ) {
		global $PHP_SELF;
		?>
		<table class="adminheading">
		<tr>
			<th class="install">
			<?php echo $title; ?>
			</th>
		</tr>
		</table>

		<table class="adminform">
		<tr>
			<td align="left">
			<strong><?php echo $message; ?></strong>
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center">
			[&nbsp;<a href="<?php echo $url;?>" style="font-size: 16px; font-weight: bold"><?php echo JText::_('COM_SF_CONTINUE'); ?></a>&nbsp;]
			</td>
		</tr>
		</table>
		<?php
	} 
		
	
	function SF_genInvitations( $option, $survey ) {
		mosCommonHTML::loadOverlib();
		EF_menu_header(); 
		?>
		<form action="index.php" method="post" name="adminForm">
		<?php if (!class_exists('JToolBarHelper')) { ?>
		<table class="adminheading">
		<tr>
			<th class="menus">
			<?php echo _SURVEY_FORCE_COMP_NAME?>: <small><?php echo JText::_('COM_SF_GENERATE_INVITATIONS'); ?></small>
			</th>
		</tr>
		</table>
		<?php } else { 
			JToolBarHelper::title( JText::_('COM_SF_GENERATE_INVITATIONS'), 'cpanel.png' );
		}
		?> 
		<script language="javascript" type="text/javascript">
		<!--
			Joomla.submitbutton = function(pressbutton) {
				var form = document.adminForm;
				if (form.number.value == '') {
					alert("<?php echo JText::_('COM_SF_PLEASE_ENTER_NUMBER_OF_INVIATIONS'); ?>");form.number.focus();
				} else {
					submitform( pressbutton );
				}
			}
		//-->
		</script>
		<table width="100%" class="adminform">
			<tr>
				<th colspan="3"><?php echo JText::_('COM_SF_PARAMETERS'); ?></th>
			</tr>
			<tr>
				<td  width="15%"><?php echo JText::_('COM_SF_NEUMBER_OF_INVITATIONS'); ?></td>
				<td  width="auto"><input type="text" name="number" value=""  /></td>
			</tr>
			<tr>
				<td  width="15%"><?php echo JText::_('COM_SF_SURVEY'); ?>:</td>
				<td  width="auto"><?php echo $survey; ?></td>
			</tr>
		</table>
		<input type="hidden" name="option" value="com_surveyforce" />
		<input type="hidden" name="task" value="" />
		</form>

		<?php
		EF_menu_footer();	
	}
	function SF_uploadImage( $option ) {
		
		$css = mosGetParam($_REQUEST,'t','');
		if (_JOOMLA15) {
		?>
			<link href="templates/bluestork/css/template.css" rel="stylesheet" type="text/css" />
			<link href="templates/bluestork/css/rounded.css" rel="stylesheet" type="text/css" />
		<?php } else {?>
			<link rel="stylesheet" href="../../templates/<?php echo $css; ?>/css/template_css.css" type="text/css" />
		<?php }?>
		<form method="post" action="index.php" enctype="multipart/form-data" name="filename">
		<table class="adminform">
		<tr>
			<th class="title"> 
				<?php echo JText::_('COM_SF_FILE_UPLOAD'); ?>
			</th>
		</tr>
		<tr>
			<td align="center">
				<input class="inputbox" name="userfile" type="file" />
			</td>
		</tr>
		<tr>
			<td>
				<input class="button" type="submit" value="<?php echo JText::_('COM_SF_UPLOAD'); ?>" name="fileupload" />
				<?php echo JText::_('COM_SF_MAX_SIZE'); ?><?php echo ini_get( 'post_max_size' );?>
			</td>
		</tr>
		</table>
		
		<input type="hidden" name="directory" value="<?php echo $directory;?>" />
		<input type="hidden" name="t" value="<?php echo $css?>">
		<input type="hidden" name="task" value="uploadimage">
		<input type="hidden" name="option" value="com_surveyforce">
		<input type="hidden" name="no_html" value="1">
		</form>
		<?php
	}

			#######################################
			###	--- ---   JAVASCRIPTS 	--- --- ###
	function SF_JS_getObj() {
		?>
		<script language="javascript" type="text/javascript">
		function getObj(name)
		{
		  if (document.getElementById)  {  return document.getElementById(name);  }
		  else if (document.all)  {  return document.all[name];  }
		  else if (document.layers)  {  return document.layers[name];  }
		}
		</script>
		<?php
	}	

			#######################################
			###	--- ---   CATEGORIES 	--- --- ###
	
	function SF_showCatsList( &$rows, &$pageNav, $option ) {
		global $my;

		mosCommonHTML::loadOverlib();
		EF_menu_header();
		?>
		<form action="index.php" method="post" name="adminForm">
		<?php if (!class_exists('JToolBarHelper')) { ?>
		<table class="adminheading" >
		<tr>
			<th class="categories">
			<?php echo _SURVEY_FORCE_COMP_NAME?>: <small><?php echo JText::_('COM_SF_CATEGORIES_LIST'); ?></small>
			</th>
		</tr>
		</table>
		<?php } else { 
			JToolBarHelper::title( JText::_('COM_SF_CATEGORIES_LIST'), 'categories.png' );
		}?>
		<table class="adminlist">
		<tr>
			<th width="20">#</th>
			<th width="20" class="title"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($rows); ?>);" /></th>
			<th class="title"><?php echo JText::_('COM_SF_NAME'); ?></th>
		</tr>
		<?php
		$k = 0;
		for ($i=0, $n=count($rows); $i < $n; $i++) {
			$row = $rows[$i];
			$link 	= 'index.php?option=com_surveyforce&task=surveys&catid='. $row->id;
			$checked = mosHTML::idBox( $i, $row->id);?>
			<tr class="<?php echo "row$k"; ?>">
				<td><?php echo $pageNav->rowNumber( $i ); ?></td>
				<td><?php echo $checked; ?></td>
				<td align="left">
					<span>
						<?php echo mosToolTip( mysql_escape_string(nl2br($row->sf_catdescr)), JText::_('COM_SF_CATEGORY_DESCRIPTION'), 280, 'tooltip.png', $row->sf_catname, $link );?>
					</span>
				</td>
			</tr>
			<?php
			$k = 1 - $k;
		}?>
		</table>
		<?php echo $pageNav->getListFooter(); ?>
		<input type="hidden" name="option" value="com_surveyforce" />
		<input type="hidden" name="task" value="categories" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="hidemainmenu" value="0">
		</form>
		<?php
		EF_menu_footer();
	}
	
	function SF_editCategory( &$row, &$lists, $option ) {
		global $mosConfig_live_site;

		mosCommonHTML::loadOverlib();
		EF_menu_header();
		?>
		<script language="javascript" type="text/javascript">
		<!--
		Joomla.submitbutton = function(pressbutton) {
			var form = document.adminForm;

			if (pressbutton == 'cancel_cat') {
				submitform( pressbutton );
				return;
			}
			// do field validation
			if (TRIM_str(form.sf_catname.value) == ""){
				alert( "<?php echo JText::_('COM_SF_CATEGORY_MUST_HAVE_NAME'); ?>" );
			} else {
				submitform( pressbutton );
			}
		}
		//-->
		</script>
		
		<form action="index.php" method="post" name="adminForm">
		<?php if (!class_exists('JToolBarHelper')) { ?>
		<table class="adminheading">
		<tr>
			<th class="edit">
			<?php echo _SURVEY_FORCE_COMP_NAME?>:
			<small>
			<?php echo $row->id ? JText::_('COM_SF_EDIT_CATEGORY') : JText::_('COM_SF_NEW_CATEGORY'); ?>
			</small>
			</th>
		</tr>
		</table>
		<?php } else { 
			JToolBarHelper::title( ($row->id ? JText::_('COM_SF_EDIT_CATEGORY') : JText::_('COM_SF_NEW_CATEGORY')), ($row->id ? 'category-edit.png' : 'category-add.png') );
		}?>
		<table width="100%" class="adminform">
			<tr>
				<th colspan="2"><?php echo JText::_('COM_SF_CATEGORY_DETAILS'); ?></th>
			<tr>
			<tr>
				<td align="right" width="20%"><?php echo JText::_('COM_SF_NAME'); ?>:</td>
				<td><input class="text_area" type="text" name="sf_catname" size="50" maxlength="100" value="<?php echo $row->sf_catname; ?>" /></td>
			</tr>
			<tr>
				<td align="right" width="20%" valign="top"><?php echo JText::_('COM_SF_DESCRIPTION'); ?>:</td>
				<td><textarea class="text_area" name="sf_catdescr" rows="5" style="width:90%;"><?php echo $row->sf_catdescr; ?></textarea></td>
			</tr>
		</table>
		<br />
		<input type="hidden" name="option" value="com_surveyforce" />
		<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
		<input type="hidden" name="user_id" value="<?php echo $row->user_id; ?>" />
		<input type="hidden" name="task" value="" />
		</form>
		<?php
		EF_menu_footer();
	}
			#######################################
			###	--- ---    SURVEYS  	--- --- ###
	
	function SF_showSurvsList( &$rows, &$lists, &$pageNav, $option, $is_i = false ) {
		global $my, $task;
		
		$sf_config = new mos_Survey_Force_Config( );
		mosCommonHTML::loadOverlib();
		EF_menu_header();
		?>
		<script language="javascript" type="text/javascript">
		<!--
			Joomla.submitbutton = function(pressbutton) {
				var form = document.adminForm;
				if (pressbutton == 'preview_survey') {
					form.target = "_blank";	
					submitform( pressbutton );		
					form.target = "";
					form.task.value = 'surveys';
				} else if (pressbutton == 'del_surv') { 					
					if(confirm('<?php echo JText::_('COM_SF_DELETE_SURVEY'); ?>')) {
						submitform( pressbutton );
						return;
					}
				} else {
					submitform( pressbutton );
				}
			}
		//-->
		</script>

		<form action="index.php" method="post" name="adminForm">
		<?php if (!class_exists('JToolBarHelper')) { ?>

		<table class="adminheading">
		<tr>
			<th>
			<?php echo _SURVEY_FORCE_COMP_NAME?>: <?php echo ((!$is_i)?"<small>". JText::_('COM_SF_SURVEYS_LIST')."</small>":"<small>". JText::_('COM_SF_CSV_REPORT_SELECT_SURVEY').";</small>")?>
			</th>
			<td width="right" nowrap>
			<?php if ($is_i) {?>
				<input type="checkbox" name="inc_imp" value="1"><?php echo JText::_('COM_SF_INCLUDE_IMP_SCALE'); ?>
				<br />
			<?php } ?>
			<?php echo $lists['category'];?>
			</td> 			
		</tr>
		<?php if ($is_i) {?>
		<tr><td align="left" colspan="2">
			<?php echo JText::_('COM_SF_THIS_REPORT_CONTAINS'); ?>
			</td></tr>
		<?php } ?>
		</table>
		<?php } else { 
			JToolBarHelper::title( ((!$is_i)?JText::_('COM_SF_SURVEYS_LIST'):JText::_('COM_SF_CSV_REPORT_SELECT_SURVEY')), ((!$is_i)?'static.png':'print.png') );
		?>
		<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td align="left" width="100%">&nbsp;</td>
			<td nowrap="nowrap">
				<?php if ($is_i) {?>
				<input type="checkbox" name="inc_imp" value="1"><?php echo JText::_('COM_SF_INCLUDE_IMP_SCALE'); ?>
				<br />
				<?php } ?>
				<?php echo $lists['category'];?>
			</td> 			
		</tr>
		</table>

		<?php }?>

		<table class="adminlist">
		<tr>
			<th width="20">id</th>
			<th width="20" class="title"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($rows); ?>);" /></th>
			<th class="title"><?php echo JText::_('COM_SF_NAME'); ?></th>
			<th class="title"><?php echo JText::_('COM_SF_ACTIVE'); ?></th>
			<th class="title"><?php echo JText::_('COM_SF_CATEGORY'); ?></th>
			<th class="title"><?php echo JText::_('COM_SF_AUTHOR'); ?></th>
			<th class="title"><?php echo JText::_('COM_SF_PUBLIC'); ?></th>
			<th class="title"><?php echo JText::_('COM_SF_AUTO_PAGE_BREAK'); ?></th>
			<th class="title"><?php echo JText::_('COM_SF_FOR_INVITED'); ?></th>
			<th class="title"><?php echo JText::_('COM_SF_FOR_REG'); ?></th>
			<?php if ($sf_config->get('sf_enable_jomsocial_integration')) { ?>
			<th class="title"><?php echo JText::_('COM_SF_FOR_FRIENDS'); ?></td>
			<?php }?>
			<th class="title"><?php echo JText::_('COM_SF_FOR_USERS_IN_LISTS'); ?></th>
			<th class="title"><?php echo JText::_('COM_SF_EXPIRED_ON'); ?></th>
		</tr>
		<?php
		$k = 0;
		for ($i=0, $n=count($rows); $i < $n; $i++) {
			$row = $rows[$i];
			$link = '#';
			if ($task == 'surveys') {
				$link 	= 'index.php?option='.$option.'&task=questions&surv_id='. $row->id;
			} elseif ($task == 'rep_surv') {
				$link 	= 'index.php?option='.$option.'&task=view_rep_survA&id='. $row->id;
			}
			$img_published	= $row->published ? 'tick.png' : 'publish_x.png';
			$task_published	= $row->published ? 'unpublish_surv' : 'publish_surv';
			$alt_published 	= $row->published ? 'Published' : 'Unpublished';
			$img_public		= $row->sf_public ? 'tick.png' : 'publish_x.png';
			$img_invite		= $row->sf_invite ? 'tick.png' : 'publish_x.png';
			$img_reg		= $row->sf_reg ? 'tick.png' : 'publish_x.png';
			$img_friend		= $row->sf_friend ? 'tick.png' : 'publish_x.png';
			$img_spec		= $row->sf_special ? 'tick.png' : 'publish_x.png';
			$img_auto_pb 	= $row-> sf_auto_pb  ? 'tick.png' : 'publish_x.png';
			$checked = mosHTML::idBox( $i, $row->id);
			?>
			<tr class="<?php echo "row$k"; ?>">
				<td align="center"><?php 
					echo $row->id;
				?></td>
				<td><?php echo $checked; ?></td>
				<td align="left">				
					<span>
	
						<?php echo mosToolTip( strip_tags(((str_replace('"',"'", nl2br($row->sf_descr))))), JText::_('COM_SF_SURVEY_DESCRIPTION'), 280, 'tooltip.png', $row->sf_name, $link );	?>					
					</span>
				</td>
				<td align="left">
					<a href="javascript: void(0);" onClick="return listItemTask('cb<?php echo $i;?>','<?php echo $task_published;?>')">
						<img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/<?php echo $img_published;?>"  border="0" alt="<?php echo $alt_published; ?>" />
					</a>
				</td>
				<td align="left">
					<?php echo $row->sf_catname; ?>
				</td>
				<td align="left">
					<?php echo $row->username; ?>
				</td>
				<td align="left">
						<img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/<?php echo $img_public;?>"  border="0" alt="<?php echo $alt_published; ?>" />
				</td>
				<td align="left">
						<img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/<?php echo $img_auto_pb;?>"  border="0" alt="<?php echo $alt_published; ?>" />
				</td>
				<td align="left">
						<img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/<?php echo $img_invite;?>"  border="0" alt="<?php echo $alt_published; ?>" />
				</td>
				<td align="left">
						<img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/<?php echo $img_reg;?>"  border="0" alt="<?php echo $alt_published; ?>" />
				</td>
				<?php if ($sf_config->get('sf_enable_jomsocial_integration')) { ?>
				<td align="left">
						<img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/<?php echo $img_friend;?>" width="16" height="16" border="0" alt="<?php echo $alt_published; ?>" />
				</td>
				<?php } ?>
				<td align="left">
						<img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/<?php echo $img_spec;?>"  border="0" alt="<?php echo $alt_published; ?>" />
				</td>				
				<td align="left">
						<?php 
						if ($row->sf_date != '0000-00-00 00:00:00' && date('Y-m-d 00:00:00', strtotime('now')) > $row->sf_date)
							echo '<b style="color:#FF0000">'.mosFormatDate($row->sf_date, "Y-m-d").'</b>';
						elseif ($row->sf_date == '0000-00-00 00:00:00')
							echo '--';
						else
							echo mosFormatDate($row->sf_date, "Y-m-d");
						?>
				</td>
			</tr>
			<?php
			$k = 1 - $k;
		}
		?>
		</table>
		<?php echo $pageNav->getListFooter(); ?>

		<input type="hidden" name="option" value="com_surveyforce" />
		<input type="hidden" name="task" value="<?php echo $task?>" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="hidemainmenu" value="0">
		</form>
		<?php
		EF_menu_footer();
	}
	
	function SF_editSurvey( &$row, &$lists, $option ) {
		global $mosConfig_live_site, $database;
		
		$query = "SELECT id FROM #__components WHERE link LIKE '%sf_score%' ";
		$database->setQuery( $query );
		$is_surveyforce_score = false;
		if ($database->LoadResult()) 
			$is_surveyforce_score = true;
		
		if (_JOOMLA15) {
			jimport( 'joomla.html.editor' );
	
			$conf =& JFactory::getConfig();
			$editor = $conf->getValue('config.editor');
			$editorz =& JEditor::getInstance($editor);
			$editorz =& JFactory::getEditor();
		}	
		
		$sf_config = new mos_Survey_Force_Config( );
		
		mosCommonHTML::loadOverlib();
		mosCommonHTML::loadCalendar();
		EF_menu_header();
		survey_force_adm_html::SF_JS_getObj();
		?>
		<script type="text/javascript" src="/includes/js/joomla.javascript.js "></script>

		<script language="javascript" type="text/javascript">
		<!--
		Joomla.submitbutton = function(pressbutton) {
			var form = document.adminForm;

			if (pressbutton == 'cancel_surv') {
				submitform( pressbutton );
				return;
			}
			// do field validation
			if (form.sf_name.value == ""){
				alert( "<?php echo JText::_('COM_SF_SURVEY_MUST_HAVE_NAME'); ?>" );
			} else {
				submitform( pressbutton );
			}
		}
		//-->
		</script>
		<form action="index.php" method="post" name="adminForm">
		<?php if (!class_exists('JToolBarHelper')) { ?>

		<table class="adminheading">
		<tr>
			<th class="edit">
			<?php echo _SURVEY_FORCE_COMP_NAME?>:
			<small>
			<?php echo $row->id ? JText::_('COM_SF_EDIT_SURVEY') : JText::_('COM_SF_NEW_SURVEY');?>
			</small>
			</th>
		</tr>
		</table>
		<?php } else { 
			JToolBarHelper::title( ($row->id ? JText::_('COM_SF_EDIT_SURVEY') : JText::_('COM_SF_NEW_SURVEY')), 'static.png' );
		}?> 
		
		<table width="100%" class="adminform">
			<tr>
				<th colspan="3"><?php echo JText::_('COM_SF_SURVEY_DETAILS'); ?></th>
			<tr>
			<tr>
				<td align="right" width="20%"><?php echo JText::_('COM_SF_NAME'); ?>:</td>
				<td colspan="2"><input class="text_area" type="text" name="sf_name" size="50" maxlength="100" value="<?php echo $row->sf_name; ?>" /></td>
			</tr>
			<tr>
				<td align="right" width="20%" valign="top"><?php echo JText::_('COM_SF_DESCRIPTION'); ?></td>
				<td colspan="2"><?php 
				if (_JOOMLA15) {
					echo $editorz->display('sf_descr', $row->sf_descr, '100%;', '250', '40', '20', array('pagebreak', 'readmore'));
				}
				else {
					editorArea( 'editor2', $row->sf_descr, 'sf_descr', '100%;', '250', '40', '20' ) ; 
				}

				?>
				</td>
			</tr>
			<tr>
				<td align="right" width="20%" valign="top"><?php echo JText::_('COM_SF_SHORT_DESCRIPTION'); ?><br /><small><?php echo JText::_('COM_SF_IT_IS_SHOWN_ONLY'); ?></small></td>
				<td colspan="2"><?php 
				if (_JOOMLA15) {
					echo $editorz->display('surv_short_descr', $row->surv_short_descr, '100%;', '250', '40', '20', array('pagebreak', 'readmore'));
				}
				else {
					editorArea( 'editor2', $row->surv_short_descr, 'surv_short_descr', '100%;', '250', '40', '20' ) ; 
				}

				?>
				</td>
			</tr>
			<tr>
				<td align="right" width="20%"><?php echo JText::_('COM_SF_ENABLE_WELCOME'); ?></td>
				<td  colspan="2"><?php echo $lists['sf_enable_descr'] ?></td>
			</tr>
			<tr>
				<td align="right" width="20%"><?php echo JText::_('COM_SF_IMAGE'); ?></td>
				<td  colspan="2">
				<?php $directory = 'surveyforce';
				global $mainframe;
				$cur_template = $mainframe->getTemplate();
				?>
				<table cellpadding="0" cellspacing="0" border="0"><tr><td>
				<?php echo $lists['images']?></td><td>
				<a href="#" onclick="popupWindow('index.php?option=com_surveyforce&amp;no_html=1&amp;task=uploadimage&amp;directory=<?php echo $directory; ?>&amp;t=<?php echo $cur_template; ?>','win1',250,100,'no');"><img src="<?php echo $mosConfig_live_site;?>/administrator/components/com_surveyforce/images/filesave.png" border="0"  alt="<?php echo JText::_('COM_SF_SAVE_ORDER'); ?>" /></a>
				</td></tr></table>
				</td>
			</tr>
			<tr><td></td>
				<td  colspan="2"><img src="<?php echo ($row->sf_image)?('../images/surveyforce/'.$row->sf_image): JURI::root().'components/com_surveyforce/images/blank.png'?>" name="imagelib">
				</td>
			</tr>
			<tr>
				<td align="right" width="20%"><?php echo JText::_('COM_SF_SHOW_PROGRESS'); ?></td>
				<td  colspan="2"><?php echo $lists['sf_progressbar'] ?>&nbsp;&nbsp;<?php echo JText::_('COM_SF_TYPE'); ?><?php echo $lists['sf_progressbar_type'];?></td>
			</tr>
			<tr>
				<td align="right" width="20%"><?php echo JText::_('COM_SF_TEMPLATE'); ?></td>
				<td  colspan="2"><?php echo $lists['sf_templates']; ?></td>
			</tr>
			<!--<tr>
				<td align="right" width="20%"><?php echo JText::_('COM_SF_USE_SURVEYFORCE_CSS'); ?></td>
				<td  colspan="2"></td>
			</tr>-->
			<tr>
				<td align="right" width="20%"><?php echo JText::_('COM_SF_CATEGORY'); ?>:</td>
				<td  colspan="2"><?php echo $lists['sf_categories']; ?></td>
			</tr>

			<tr>
				<td align="right" width="20%"><?php echo JText::_('COM_SF_EXPIRED_ON'); ?></td>
				<td  colspan="2" style="vertical-align:middle;"> <?php 
									if ($row->sf_date != '0000-00-00 00:00:00')
										$sf_date = mosFormatDate($row->sf_date, "Y-m-d"); 
									else 
										$sf_date = '';

						echo JHTML::_('calendar',(($sf_date != '-')?$sf_date:''), 'sf_date','start_date','%Y-%m-%d' , array('size'=>10,'maxlength'=>"10"));
					?>
				</td>
			</tr>			
			<tr>
				<td align="right" width="20%"><?php echo JText::_('COM_SF_ACTIVE'); ?>:</td>
				<td  colspan="2"><?php echo $lists['published'] ?></td>
			</tr>
			<tr>
				<td align="right" width="20%"><?php echo JText::_('COM_SF_RANDOMIZE_QUESTIONS'); ?></td>
				<td  colspan="2"><?php echo $lists['sf_random']; ?><br/><small><?php echo JText::_('COM_SF_NEITHER_QUESTION_RULES'); ?></small></td>
			</tr>
			<tr>
				<td align="right" width="20%"><?php echo JText::_('COM_SF_AUTO_INSERT_PAGE_BREAK'); ?></td>
				<td  colspan="2"><?php echo $lists['sf_auto_pb'] ?></td>
			</tr>
			<tr>
				<td align="right" width="20%"><?php echo JText::_('COM_SF_DO_NOT_STORE_PERSONAL'); ?></td>
				<td  colspan="2"><?php echo $lists['sf_anonymous'] ?>&nbsp;&nbsp;<?php 
					$tip = JText::_('COM_SF_USER_PERSONAL_DATA');
					echo mosToolTip( $tip );?></td>
			</tr>
			<tr>
				<td align="right" width="20%"><?php echo JText::_('COM_SF_PUBLIC'); ?>:</td>
				<td  colspan="2">
					<input type="hidden" name="sf_public" value="<?php echo $row->sf_public; ?>">
					<input type="checkbox" name="sf_public_chk" onClick="javascript: this.form['sf_public'].value = (this.checked)?1:0;" <?php echo ($row->sf_public == 1)?"checked":""; ?>>
					&nbsp;&nbsp;&nbsp;&nbsp;<?php echo JText::_('COM_SF_VOITING'); ?>:&nbsp;
					<?php echo $lists['sf_pub_voting']; ?>
					&nbsp;&nbsp;&nbsp;&nbsp;<?php echo JText::_('COM_SF_CONTROL_TYPE'); ?>:&nbsp;
					<?php echo $lists['sf_pub_control']; ?>
					&nbsp;&nbsp;<?php 
					$tip = JText::_('COM_SF_VOTING_OPTION_WILL_BE_ENABLED');
					echo mosToolTip( $tip );?>
				</td>
			</tr>
			<tr>
				<td align="right" width="20%"><?php echo JText::_('COM_SF_FOR_INVITED'); ?>:</td>
				<td  colspan="2">
					<input type="hidden" name="sf_invite" value="<?php echo $row->sf_invite; ?>">
					<input type="checkbox" name="sf_invite_chk" onClick="javascript: this.form['sf_invite'].value = (this.checked)?1:0;" <?php echo ($row->sf_invite == 1)?"checked":""; ?>>
					&nbsp;&nbsp;&nbsp;&nbsp;<?php echo JText::_('COM_SF_VOITING'); ?>:&nbsp;
				<?php echo $lists['sf_inv_voting']; ?>
				</td>
			</tr>
			<tr>
				<td align="right" width="20%"><?php echo JText::_('COM_SF_FOR_REGISTERED'); ?></td>
				<td  colspan="2">
					<input type="hidden" name="sf_reg" value="<?php echo $row->sf_reg; ?>">
					<input type="checkbox" name="sf_reg_chk" onClick="javascript: this.form['sf_reg'].value = (this.checked)?1:0;" <?php echo ($row->sf_reg == 1)?"checked":""; ?>>
				&nbsp;&nbsp;&nbsp;&nbsp;<?php echo JText::_('COM_SF_VOITING'); ?>:&nbsp;
				<?php echo $lists['sf_reg_voting']; ?>
				
				</td>
			</tr>
			<?php if ($sf_config->get('sf_enable_jomsocial_integration')) { ?>
			<tr>
				<td align="right" width="20%"><?php echo JText::_('COM_SF_FOR_FRIENDS'); ?>:</td>
				<td  colspan="2">
					<input type="hidden" name="sf_friend" value="<?php echo $row->sf_friend; ?>">
					<input type="checkbox" name="sf_friend_chk" onClick="javascript: this.form['sf_friend'].value = (this.checked)?1:0;" <?php echo ($row->sf_friend == 1)?"checked":""; ?>>
				&nbsp;&nbsp;&nbsp;&nbsp;<?php echo JText::_('COM_SF_VOITING'); ?>:&nbsp;
				<?php echo $lists['sf_friend_voting']; ?>
				
				</td>
			</tr>
			<?php } ?>
			<?php if ($lists['userlists'] != null) {?>
			<tr>
				<td align="right" width="20%" valign="top"><?php echo JText::_('COM_SF_FOR_USERS_IN_LISTS'); ?>:</td>
				<td valign="top" style="vertical-align:top; width:20px" width="20px">
					<input type="hidden" name="sf_special" value="<?php echo $row->sf_special; ?>">
					<input type="checkbox" name="sf_special_chk" onClick="javascript: this.form['sf_special'].value = (this.checked)?1:0;" <?php echo ($row->sf_special)?"checked":""; ?>>
				</td>
				<td style="text-align:left">
					<?php echo $lists['userlists'] ?>
				</td>
			</tr>
			<?php } ?>
			
			<tr>
				<td align="right" width="20%"><?php echo JText::_('COM_SF_IF_SURVEY_PASSED'); ?></td>
				<td  colspan="2">
					<label for="sf_after_start0"><input type="radio" <?php echo ($row->sf_after_start == 0 ? 'checked="checked"': '')?> name="sf_after_start" id="sf_after_start0" value="0"/><?php echo JText::_('COM_SF_SHOW_WARNING'); ?></label><br />
					<label for="sf_after_start1"><input type="radio" <?php echo ($row->sf_after_start == 1 ? 'checked="checked"': '')?> name="sf_after_start" id="sf_after_start1" value="1"/><?php echo JText::_('COM_SF_SHOW_MESSAGE_AND_SURVEY'); ?></label>
				</td>
			</tr>
			<tr>
				<td><?php echo JText::_('COM_SF_FINAL_PAGE'); ?></td><td valign="top" colspan="2"><input type="radio" <?php echo ($row->sf_fpage_type == 1 ? 'checked="checked"': '')?> name="sf_fpage_type" value="1"/><?php echo JText::_('COM_SF_SHOW_RESULTS'); ?></td>	
			</tr>
			<!-- '<strong><?php echo JText::_('COM_SF_END_OF_SURVEY'); ?></strong>' -->
			
			<?php if ($is_surveyforce_score) {?>
			<tr valign="top">
				<td ></td><td valign="top" colspan="2"><input type="radio" <?php echo ($row->sf_fpage_type == 2 ? 'checked="checked"': '')?> name="sf_fpage_type" value="1"/><?php echo JText::_('COM_SF_SHOW_SCORE_RESULTS'); ?></td>
				
			</tr>
			<?php }?>
			<tr valign="top">
				<td ></td><td valign="top" colspan="2"><input type="radio"  <?php echo ($row->sf_fpage_type == 0 ? 'checked="checked"': '')?> name="sf_fpage_type" value="0"/><?php echo JText::_('COM_SF_SHOW_TEXT'); ?></td>			
				
			</tr>
			<tr valign="top">
				<td><?php echo JText::_('COM_SF_FINAL_PAGE_TEXT'); ?></td><td  colspan="2"><?php 
				if (_JOOMLA15) {
					echo $editorz->display('sf_fpage_text', ($row->sf_fpage_text == null ? '' : $row->sf_fpage_text), '100%;', '250', '40', '20', array('pagebreak', 'readmore'));
				}
				else {
					editorArea( 'editor3', ($row->sf_fpage_text == null ? '' : $row->sf_fpage_text), 'sf_fpage_text', '100%;', '250', '40', '20' ) ; 
				}
 
				?></td>
			</tr>
			
		</table>

		<br />
		<input type="hidden" name="sf_author" value="<?php echo $row->sf_author; ?>" />
		<input type="hidden" name="option" value="com_surveyforce" />
		<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
		<input type="hidden" name="task" value="" />
		</form>
		<?php
		EF_menu_footer();
	}
	
	function SF_moveSurvey_Select( $option, $cid, $CategoryList, $items ) {
		global $task; 
		EF_menu_header();
		?>
		<form action="index.php" method="post" name="adminForm">
		<?php if (!class_exists('JToolBarHelper')) { ?>
		<table class="adminheading">
		<tr>
			<th>
			<?php echo _SURVEY_FORCE_COMP_NAME?>:
			<small>
			<?php if ($task == 'move_surv_sel') { ?>
				<?php echo JText::_('COM_SF_MOVE_SURVEY'); ?>
			<?php } elseif ($task == 'copy_surv_sel') { ?>
				<?php echo JText::_('COM_SF_COPY_SURVEY'); ?>
			<?php } ?>
			</small>
			</th>
		</tr>
		</table>
		<?php } else { 
			if ($task == 'move_surv_sel') {
				JToolBarHelper::title( JText::_('COM_SF_MOVE_SURVEY'), 'static.png' );
			} elseif ($task == 'copy_surv_sel') {
				JToolBarHelper::title( JText::_('COM_SF_COPY_SURVEY'), 'static.png' );
			}
		}?> 

		<table class="adminform">
		<tr>
			<td width="3%"></td>
			<td align="left" valign="top" width="30%">
			<?php echo JText::_('COM_SF_COPY_MOVE_TO_CATEGORY'); ?>
			<br />
			<?php echo $CategoryList ?>
			<br /><br />
			</td>
			<td align="left" valign="top" width="20%">
			<?php echo JText::_('COM_SF_SURVEY_COPIED'); ?>
			<br />
			<?php
			echo "<ol>";
			foreach ( $items as $item ) {
				echo "<li>". $item->sf_name ." (".$item->sf_catname.")</li>";
			}
			echo "</ol>";
			?>
			</td>
			<td valign="top">
			<?php echo JText::_('COM_SF_THIS_WILL_COPY_MOVE'); ?>
			</td>
		</tr>
		</table>
		<br /><br />

		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="task" value="" />
		<?php
		foreach ( $cid as $id ) {
			echo "\n <input type=\"hidden\" name=\"cid[]\" value=\"$id\" />";
		}
		?>
		</form>
		<?php
		EF_menu_footer();
	} 	
			#######################################
			###	--- ---   QUESTIONS  	--- --- ###

	function SF_editSection( &$row, &$lists, $option ) {
		global $mosConfig_live_site;

		mosCommonHTML::loadOverlib();
		mosCommonHTML::loadCalendar();
		EF_menu_header();
		?>
		<script type="text/javascript" src="/includes/js/joomla.javascript.js "></script>

		<script language="javascript" type="text/javascript">
		<!--
		Joomla.submitbutton = function(pressbutton) {
			var form = document.adminForm;

			if (pressbutton == 'cancel_section') {
				submitform( pressbutton );
				return;
			}
			// do field validation
			if (form.sf_name.value == ""){
				alert( "<?php echo JText::_('COM_SF_SECTION_MUST_HAVE_NAME'); ?>" );
			} else {
				submitform( pressbutton );
			}
		}
		//-->
		</script>
		<form action="index.php" method="post" name="adminForm">
		<?php if (!class_exists('JToolBarHelper')) { ?>
		<table class="adminheading">
		<tr>
			<th class="edit">
			<?php echo _SURVEY_FORCE_COMP_NAME?>:
			<small>
			<?php echo $row->id ? JText::_('COM_SF_EDIT_SECTION') : JText::_('COM_SF_NEW_SECTION');?>
			</small>
			</th>
		</tr>
		</table>
		<?php } else { 
			JToolBarHelper::title( ($row->id ? JText::_('COM_SF_EDIT_SECTION') : JText::_('COM_SF_NEW_SECTION')), 'static.png' );
		}?> 
		
		<table width="100%" class="adminform">
			<tr>
				<th colspan="3"><?php echo JText::_('COM_SF_SECTION_DETAILS'); ?></th>
			<tr>
			<tr>
				<td align="right" width="20%"><?php echo JText::_('COM_SF_NAME'); ?>:</td>
				<td><input class="text_area" type="text" name="sf_name" size="50" maxlength="100" value="<?php echo $row->sf_name; ?>" /></td>
				<td >
				</td>
			</tr>
			<tr>
				<td align="right" width="20%"><?php echo JText::_('COM_SF_ADD_SECTION_NAME'); ?></td>
				<td><?php echo $lists['addname']; ?></td>
			</tr>
			<tr>
				<td align="right" width="20%"><?php echo JText::_('COM_SF_SURVEY'); ?>:</td>
				<td><?php echo $lists['sf_surveys']; ?></td>
			</tr>
			<tr>
				<td align="right" width="20%"><?php echo JText::_('COM_SF_QUESTIONS'); ?></td>
				<td><?php echo $lists['sf_questions']; ?></td>
			</tr>
			<tr>
				<td align="right" width="20%"><?php echo JText::_('COM_SF_ORDERING'); ?></td>
				<td><?php echo $lists['ordering']; ?></td>
			</tr>
			
		</table>
		<br />
		<input type="hidden" name="option" value="com_surveyforce" />
		<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
		<input type="hidden" name="task" value="" />
		</form>
		<?php
		EF_menu_footer();
	}
	
	function SF_showQuestsList( &$rows, &$lists, &$pageNav, $option ) {
		global $my, $option, $mosConfig_live_site;
		mosCommonHTML::loadOverlib();
		EF_menu_header();
		?>
		<script language="javascript" type="text/javascript">
		<!--
		Joomla.submitbutton = function(pressbutton) {
			var form = document.adminForm;

			if (pressbutton == 'add_new') {
				switch (form.qtypes_id.value) {
					case '1': pressbutton = 'add_likert'; 		break;
					case '2': pressbutton = 'add_pickone'; 		break;
					case '3': pressbutton = 'add_pickmany'; 	break;
					case '4': pressbutton = 'add_short'; 		break;
					case '5': pressbutton = 'add_drp_dwn'; 		break;
					case '6': pressbutton = 'add_drg_drp'; 		break;
					case '7': pressbutton = 'add_boilerplate';  break;
					case '8': pressbutton = 'add_pagebreak'; 	break;
					case '9': pressbutton = 'add_ranking';	 	break;
				}
			}
			submitform( pressbutton );
		}
		function saveorder( n ) {
			checkAll_button( n );
		}

		//needed by saveorder function
		function checkAll_button( n ) {
			for ( var j = 0; j <= n; j++ ) {
				box = eval( "document.adminForm.cb" + j );
				if ( box ) {
					if ( box.checked == false ) {
						box.checked = true;
					}
				}
				
				box = eval( "document.adminForm.cbs" + j );
				if ( box ) {
					if ( box.checked == false ) {
						box.checked = true;
					}
				}
			}

			document.adminForm.task.value='saveorder';

			document.adminForm.submit(); 			
		} 
		//-->
		</script>
		<form action="index.php?option=com_surveyforce" method="post" name="adminForm">
		<input type="hidden" name="option" value="com_surveyforce" />
		<?php if (!class_exists('JToolBarHelper')) { ?>
		<table class="adminheading">
		<tr>
			<th>
				<?php echo _SURVEY_FORCE_COMP_NAME?>: <small><?php echo JText::_('COM_SF_LIST_OF_QUESTIONS'); ?></small>
			</th>
			<td align="right" width="500px" >
				<table width="300px">
					<tr><td width="300px" align="right"><b><?php echo JText::_('COM_SF_NEW_QUESTION'); ?></b></td><td><?php echo $lists['qtypes'];?></td></tr>
					<tr><td width="300px" align="right"><b><?php echo JText::_('COM_SF_SURVEY'); ?>:</b></td><td><?php echo $lists['survey'];?></td></tr>
				</table>			
			</td> 			
		</tr>
		</table>
		<?php } else { 
			JToolBarHelper::title( JText::_('COM_SF_LIST_OF_QUESTIONS'), 'static.png' ); ?>
			<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td align="left" width="100%">&nbsp;</td>
				<td nowrap="nowrap">
					<table width="300px">
					<!--<tr><td width="300px" align="right"><b>New question:</b></td><td><?php //echo $lists['qtypes'];?></td></tr>-->
					<tr><td width="300px" align="right"><b><?php echo JText::_('COM_SF_SURVEY'); ?>:</b></td><td><?php echo $lists['survey'];?></td></tr>
					</table>
				</td> 			
			</tr>
			</table>

		<?php }?> 
		<div  align="left"><?php echo $lists['sf_auto_pb_on']?></div>
		<table class="adminlist" >
		<tr>
			<th width="20">#</th>			
			<th width="20" class="title"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($rows); ?>);" /></th>
			<th class="title" width="2%" ><?php echo JText::_('COM_SF_TEXT'); ?></th>
			<th class="title" width="33%" >&nbsp;</th>
			<th class="title" width="7%" ><?php echo JText::_('COM_SF_PUBLISHED'); ?></th>
			<th class="title" width="7%" ><?php echo JText::_('COM_SF_COMPULSORY'); ?></th>
			<th class="title" colspan="2" width="5%"><?php echo JText::_('COM_SF_REORDER'); ?></th>
			<th width="2%"><?php echo JText::_('COM_SF_ORDER'); ?></th>
			<th width="1%">
				<a href="javascript: <?php echo ($lists['survid'] > 0? 'saveorder('.count( $rows ).')' : ' void(0); ' )?>"><img src="<?php echo $mosConfig_live_site;?>/administrator/components/com_surveyforce/images/filesave.png" border="0"  alt="<?php echo JText::_('COM_SF_SAVE_ORDER'); ?>" /></a>
			</th> 
			<th class="title"><?php echo JText::_('COM_SF_TYPE'); ?></th>
			<th class="title"><?php echo JText::_('COM_SF_SURVEY'); ?></th>
		</tr>
		<?php
		$k = 0;
		$s = 1;
		$ii = 0;
		$jj = 0;
		$first = true;
		$last = true;
		for ($i=0, $n=count($rows); $i < $n; $i++) {
			$row = $rows[$i];
			$last = (isset($rows->last)?$rows->last:true);
			$img_published	= isset($row->published) && $row->published ? 'tick.png' : 'publish_x.png';
			$task_published	= isset($row->published) && $row->published ? 'unpublish_quest' : 'publish_quest';
			$alt_published 	= isset($row->published) && $row->published ? 'Published' : 'Unpublished';
			$img_compulsory	= isset($row->sf_compulsory) && $row->sf_compulsory ? 'tick.png' : 'publish_x.png';
			$task_compulsory	= isset($row->sf_compulsory) && $row->sf_compulsory ? 'uncompulsory_quest' : 'compulsory_quest';			
			
			if ( !isset($row->sf_section_id)) {
				$checked = '<input type="checkbox" id="cbs'.$ii.'" name="sec[]" value="'.$row['id'].'" onclick="isChecked(this.checked);" />';				
			?>
				<tr class="<?php echo "row$k"; ?>">
					<td>&nbsp;</td>
					<td><?php echo $checked; ?></td>
					
					<td align="left" colspan="2"><a title="<?php echo JText::_('COM_SF_EDIT_SECTION'); ?>" href='index.php?option=<?php echo $option?>&task=editA_sec&id=<?php echo $row['id']?>'><?php echo $row['sf_name']?></a></td>
					<td align="center"></td>
					<td align="center"><?php if (!isset($row['first']) && $row['quest_id'] != '') echo '<a href="#reorder" onClick="return listItemTask(\'cbs'.$ii.'\',\'orderupS\')" title= "'.JText::_('COM_SF_MOVE_UP_SECTION').'"><img src="'.JURI::root().'administrator/components/com_surveyforce/images/uparrow-1.png"  border="0" alt="'.JText::_('COM_SF_MOVE_UP_SECTION').'"></a>'; ?></td>
					<td align="center"><?php if (!isset($row['end']) && $row['quest_id'] != '') echo '<a href="#reorder" onClick="return listItemTask(\'cbs'.$ii.'\',\'orderdownS\')" title="'.JText::_('COM_SF_MOVE_DOWN_SECTION').'"><img src="'.JURI::root().'administrator/components/com_surveyforce/images/downarrow-1.png"  border="0" alt="'.JText::_('COM_SF_MOVE_DOWN_SECTION').'"></a>'; ?></td>
					<td align="center" colspan="2">
					<input type="text" name="orderS[]" size="5" value="<?php echo $row['ordering'] ?>" class="text_area" style="text-align: center;border : 1px solid #a88;" <?php echo ($row['quest_id'] == ''?'disabled="disabled"':'')?>  />
					</td>
					<td align="left"><?php echo JText::_('COM_SF_SECTION'); ?></td>
					<td align="left"><?php echo $row['survey_name']; ?></td>
				</tr>
			<?php
				$ii++;
				$k = 1 - $k;
			}
			else {
				$link 	= 'index.php?option=com_surveyforce&task=editA_quest&id='. $row->id;
				$checked = mosHTML::idBox( $jj, $row->id);
				?>
				<tr class="<?php echo "row$k"; ?>">
					<td align="center"><?php echo $pageNav->rowNumber( $jj ); ?></td>
					<td align="center"><?php					
						if (isset($rows[$i-1]->sf_section_id) && $lists['survid'] > 0 && $i > 0 && $row->sf_section_id == $rows[$i-1]->sf_section_id && $row->sf_section_id > 0) {
							echo $s;
							$s++;
						}
						elseif ($lists['survid'] > 0 && $row->sf_section_id > 0) {
							$s = 1;
							echo $s;
							$s++;
						}
						elseif ($row->sf_section_id == 0 || $lists['survid'] < 1){
							echo $checked;
						}				
					?>
					</td>
					<?php echo ($row->sf_section_id > 0 && $lists['survid'] > 0 ? '<td width="20px"  style="width:20px"  >'.$checked.'</td>':''); ?>
					<td style="text-align:left" align="left" <?php echo ($row->sf_section_id > 0 && $lists['survid'] > 0? '':'colspan="2"')?>><?php					
					
					$txt_for_tip = JText::_('COM_SF_IMPORTANCE_SCALE_NOT_DEFINED');
					if ($row->sf_impscale) {
						$txt_for_tip = "<b>".mysql_escape_string(nl2br($row->iscale_name))."</b><br>";
						$tot = $row->total_iscale_answers;
						$txt_for_tip .= "<table width=\'100%\' cellpadding=0 cellspacing=0 border=0>";
						foreach ($row->answer_imp as $arow) {
							$txt_for_tip .= "<tr><td width=\'85%\'>".$arow->ftext.":</td><td><b>".$arow->ans_count . "</b></td></tr>";
						}
						$txt_for_tip .= "</table>";
					}
					?>
					<?php echo mosToolTip(mysql_escape_string(nl2br(txt2overlib($txt_for_tip))), JText::_('COM_SF_QUESTION_RANK'), 280, 'tooltip.png', (strlen(trim(strip_tags($row->sf_qtext))) > 100 ? mb_substr(trim(strip_tags($row->sf_qtext)), 0, 100).'...': trim(strip_tags($row->sf_qtext))), $link );?>

					</td>
					<td align="left">
						<a href="javascript: void(0);" onClick="return listItemTask('cb<?php echo $jj;?>','<?php echo $task_published;?>')">
						<img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/<?php echo $img_published;?>"  border="0" alt="<?php echo $alt_published; ?>" />
						</a>
					</td>
					<td align="left">
						<a href="javascript: void(0);" onClick="return listItemTask('cb<?php echo $jj;?>','<?php echo $task_compulsory;?>')">
						<img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/<?php echo $img_compulsory;?>"  border="0" />
						</a>
					</td>
					<td align="center">
					<?php if ((($jj+$pageNav->limitstart > 0)) && $first && $lists['survid'] > 0) 
							echo '<a href="#reorder" onClick="return listItemTask(\'cb'.$jj.'\',\'orderup\')" title="'.JText::_('COM_SF_MOVE_UP').'"><img src="'.JURI::root().'administrator/components/com_surveyforce/images/uparrow.png"  border="0" alt="'.JText::_('COM_SF_MOVE_UP').'"></a>'; 
					?>
					</td>
					<td align="center">
					<?php if (($jj+$pageNav->limitstart < $pageNav->total-1) && $lists['survid'] > 0) 
							echo '<a href="#reorder" onClick="return listItemTask(\'cb'.$jj.'\',\'orderdown\')" title="'.JText::_('COM_SF_MOVE_DOWN').'"><img src="'.JURI::root().'administrator/components/com_surveyforce/images/downarrow.png"  border="0" alt="'.JText::_('COM_SF_MOVE_DOWN').'"></a>'; 
					?>
					</td>
					<td align="center" colspan="2">
					<input type="text" name="order[]" size="5" value="<?php echo ($row->sf_section_id > 0? $s-1: $row->ordering);?>" class="text_area" style="text-align: center" <?php echo ($lists['survid'] > 0? '' : ' disabled="disabled" ' )?> />
					</td>
					<td align="left">
						<?php echo $row->qtype_full; ?>
					</td>
					<td align="left">
						<?php echo $row->survey_name; ?>
					</td>
				</tr>
				<?php
				
				$last = true;
				$k = 1 - $k;
				$jj++;
			}
		}?>
		</table>
		<?php echo $pageNav->getListFooter(); ?>

		<input type="hidden" name="option" value="com_surveyforce" />
		<input type="hidden" name="task" value="questions" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="hidemainmenu" value="0">
		</form>
		
		<?php
		EF_menu_footer();
	}
	
	function SF_editQ_Short( &$row, &$lists, $option ) {
		global $mosConfig_live_site, $task, $_MAMBOTS;
		
		if (_JOOMLA15) {
			jimport( 'joomla.html.editor' );
	
			$conf =& JFactory::getConfig();
			$editor = $conf->getValue('config.editor');
			$editorz =& JEditor::getInstance($editor);
			$editorz =& JFactory::getEditor();
		}

		mosCommonHTML::loadOverlib();
		EF_menu_header();
		?>
		<script language="javascript" type="text/javascript">
		<!--
		Joomla.submitbutton = function(pressbutton) {
			var form = document.adminForm;

			if (pressbutton == 'cancel_quest') {
				submitform( pressbutton );
				return;
			}
			// do field validation
			
			fillTextArea();
			if (false && form.sf_qtext.value == ""){
				alert( "<?php echo JText::_('COM_SF_QUESTION_MUST_HAVE_TEXT'); ?>" );
			} else {
				submitform( pressbutton );
			}
		}
		
		function fillTextArea () {
			var form = document.adminForm;
			<?php 		
			//print WYSIWYG editor function name to save content to textarea
			$script = '';
			if (_JOOMLA15) {
				$script = $editorz->save('sf_qtext');
			}
			else {
				$results = $_MAMBOTS->trigger( 'onGetEditorContents', array( 'editor2', 'sf_qtext' ) );
				if (trim($results[0])) {
					$script = $results[0];
				}
			}
			if (trim($script))
				echo $script;
			?>
			
			return true;
		}

		//-->
		</script>
		
		<form action="index.php" method="post" name="adminForm">
		<?php if (!class_exists('JToolBarHelper')) { ?>
		<table class="adminheading">
		<tr>
			<th>
			<?php echo _SURVEY_FORCE_COMP_NAME?>:
			<small>
			<?php echo $row->id ? JText::_('COM_SF_EDIT_QUESTION') : JText::_('COM_SF_NEW_QUESTION'); echo JText::_('COM_SF_SHORT_ANSWER');?>
			</small>
			</th>
		</tr>
		</table>
		<?php } else { 
			JToolBarHelper::title( ($row->id ? JText::_('COM_SF_EDIT_QUESTION') : JText::_('COM_SF_NEW_QUESTION')).JText::_('COM_SF_SHORT_ANSWER'), 'static.png' );
		}?> 
		
		<table width="100%" class="adminform">
			<tr>
				<th colspan="2"><?php echo JText::_('COM_SF_QUESTION_DETAILS'); ?></th>
			<tr>
			<tr>
				<td align="right" width="20%" valign="top"><?php echo JText::_('COM_SF_QUESTION_TEXT'); ?>:</td>
				<td><?php 
				if (_JOOMLA15) {
					echo $editorz->display('sf_qtext', $row->sf_qtext, '100%;', '250', '40', '20', array('pagebreak', 'readmore'));
				}
				else {
					editorArea( 'editor2', $row->sf_qtext, 'sf_qtext', '100%;', '250', '40', '20' ) ; 
				}

				?>
				</td>
			</tr>
			<tr>
			<td ></td>
			<td ><b><small><?php echo JText::_('COM_SF_EVERY_X_IN_QUESTION'); ?></small></b></td>
			</tr>
			<tr>
				<td><?php echo JText::_('COM_SF_SURVEY'); ?>:</td><td><?php echo $lists['survey'];?></td>
			</tr>
			<tr>
				<td><?php echo JText::_('COM_SF_IMPORTANCE_SCALE'); ?>:</td><td><?php echo $lists['impscale'];?><input type="button" class="text_area" name="Define new" onClick="javascript: fillTextArea();submitform('add_iscale_from_quest')" value="<?php echo JText::_('COM_SF_DEFINE_NEW'); ?>"></td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('COM_SF_PUBLISHED'); ?>:
				</td>
				<td>
					<?php echo $lists['published']; ?>
				</td>
			</tr>
			<tr>
				<td><?php echo JText::_('COM_SF_ORDERING'); ?></td><td><?php echo $lists['ordering'];?></td>
			</tr> 
			<?php if ( $lists['sf_section_id'] != null ) {?>
			<tr>
				<td><?php echo JText::_('COM_SF_SECTION'); ?>:</td><td><?php echo $lists['sf_section_id'];?></td>
			</tr> 
			<?php }?>
			<tr>
				<td>
					<?php echo JText::_('COM_SF_COMPULSORY_QUESTION'); ?>:
				</td>
				<td>
					<?php echo $lists['compulsory']; ?>
				</td>
			</tr> 
			<?php if (!($row->id > 0)) {?>
			<tr>
				<td>
					<?php echo JText::_('COM_SF_INSERT_PAGE_BREAK_AFTER_QUESTION'); ?>
				</td>
				<td>
					<?php echo $lists['insert_pb']; ?>
				</td>
			</tr>
			<?php } ?>
			<tr>
				<td>
					<?php echo JText::_('COM_SF_HIDDEN_BY_DEFAULT'); ?>
				</td>
				<td>
					<?php echo $lists['sf_default_hided']; ?>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<script type="text/javascript" src="<?php echo $mosConfig_live_site;?>/administrator/components/com_surveyforce/js/jquery.pack.js"></script>
					<script type="text/javascript" language="javascript" >
						jQuery.noConflict();
						var sf_is_loading = false;
					</script>
					<table class="adminlist" id="show_quest">
					<tr>
						<th class="title" colspan="4"><?php echo JText::_('COM_SF_DONT_SHOW_QUESTION'); ?></th>
					</tr>
					<?php if (is_array($lists['quest_show']) && count($lists['quest_show'])) 
							foreach($lists['quest_show'] as $rule) {
								if ( ($rule->sf_qtype == 2) || ($rule->sf_qtype == 3) ) {
							?>
							
							<tr>
								<td width="375px;"><?php echo JText::_('COM_SF_FOR_QUESTION'); ?> "<?php echo $rule->sf_qtext;?>" <input type="hidden" name="sf_hid_rule2_id[]" value="<?php echo $rule->bid;?>" /><input type="hidden" name="sf_hid_rule2_alt_id[]" value="<?php echo $rule->did;?>" /><input type="hidden" name="sf_hid_rule2_quest_id[]" value="<?php echo $rule->qid;?>" /></td>
								<td colspan="2"> <?php echo JText::_('COM_SF_ANSWER_IS'); ?> "<?php echo $rule->qoption;?>"</td>
								<td><a href="javascript: void(0);" onclick="javascript:Delete_row(this); return false;" title="<?php echo JText::_('COM_SF_DELETE'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/publish_x.png"  border="0" alt="<?php echo JText::_('COM_SF_DELETE');?>"></a></td>
							</tr>
							<?php } elseif (($rule->sf_qtype == 1) || ($rule->sf_qtype == 5) || ($rule->sf_qtype == 6)) {?>
							<tr>
								<td  width="375px;"><?php echo JText::_('COM_SF_FOR_QUESTION'); ?> "<?php echo $rule->sf_qtext;?>"<input type="hidden" name="sf_hid_rule2_id[]" value="<?php echo $rule->bid;?>" /><input type="hidden" name="sf_hid_rule2_alt_id[]" value="<?php echo ($rule->sf_qtype == 1?$rule->sdid:$rule->fdid);?>" /><input type="hidden" name="sf_hid_rule2_quest_id[]" value="<?php echo $rule->qid;?>" /></td>
								<td> <?php echo JText::_('COM_SF_AND_FOR_OPTION'); ?> "<?php echo $rule->qoption;?>"</td>
								<td> <?php echo JText::_('COM_SF_ANSWER_IS'); ?> "<?php echo ($rule->sf_qtype == 1?$rule->astext:$rule->aftext);?>"</td>
								<td><a href="javascript: void(0);" onclick="javascript:Delete_row(this); return false;" title="<?php echo JText::_('COM_SF_DELETE'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/publish_x.png"  border="0" alt="<?php echo JText::_('COM_SF_DELETE');?>"></a></td>
							</tr>
							<?php } elseif ($rule->sf_qtype == 9) {?>
							<tr >
								<td  width="375px;"><?php echo JText::_('COM_SF_FOR_QUESTION'); ?> "<?php echo $rule->sf_qtext;?>"<input type="hidden" name="sf_hid_rule2_id[]" value="<?php echo $rule->bid;?>" /><input type="hidden" name="sf_hid_rule2_alt_id[]" value="<?php echo $rule->did;?>" /><input type="hidden" name="sf_hid_rule2_quest_id[]" value="<?php echo $rule->qid;?>" /></td>
								<td> <?php echo JText::_('COM_SF_AND_FOR_OPTION'); ?> "<?php echo $rule->qoption;?>"</td>
								<td> <?php echo JText::_('COM_SF_RANK_IS'); ?> "<?php echo $rule->aftext;?>"</td>
								<td><a href="javascript: void(0);" onclick="javascript:Delete_row(this); return false;" title="<?php echo JText::_('COM_SF_DELETE'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/publish_x.png"  border="0" alt="<?php echo JText::_('COM_SF_DELETE');?>"></a></td>
							</tr>	
							<?php }
							}?>
					</table>
					<table width="100%"  id="show_quest2">
					<tr>
						<td style="width:70px;"><?php echo JText::_('COM_SF_FOR_QUESTION'); ?> </td><td style="width:15px;"><?php echo $lists['quests3'];?></td>
						<td width="auto" colspan="2" ><div id="quest_show_div"></div>						
						</td>
					</tr>							
					<tr>
						<td colspan="4" style="text-align:left;"><input id="add_button" type="button" name="add" value="<?php echo JText::_('COM_SF_ADD'); ?>" onclick="javascript: if(!sf_is_loading) addRow();"  />
						</td>
					</tr>
					</table>
					<script type="text/javascript" language="javascript">
						function Delete_row(element) {
							var del_index = element.parentNode.parentNode.sectionRowIndex;
							var tbl_id = element.parentNode.parentNode.parentNode.parentNode.id;
							element.parentNode.parentNode.parentNode.deleteRow(del_index);							
						}
	
						function addRow(){
							var qtype = jQuery('#sf_qtype2').get(0).value;
							
							var sf_field_data_m = jQuery('#sf_field_data_m').get(0).options[jQuery('#sf_field_data_m').get(0).selectedIndex].value;
							var q_id = jQuery('#sf_quest_list3').get(0).options[jQuery('#sf_quest_list3').get(0).selectedIndex].value;
							if (qtype != 2 && qtype != 3) {
								if (qtype == 1)
									var sf_field_data_a = jQuery('#f_scale_data').get(0).options[jQuery('#f_scale_data').get(0).selectedIndex].value;
								else
									var sf_field_data_a = jQuery('#sf_field_data_a').get(0).options[jQuery('#sf_field_data_a').get(0).selectedIndex].value;
							} else {
								var sf_field_data_a = 0;
							}
							
							var tbl_elem = jQuery('#show_quest').get(0);
							var row = tbl_elem.insertRow(tbl_elem.rows.length);
									
							var cell1 = document.createElement("td");
							var cell2 = document.createElement("td");
							var cell3 = document.createElement("td");
							var cell4 = document.createElement("td");
							var input_hidden = document.createElement("input");
							var input_hidden2 = document.createElement("input");
							var input_hidden3 = document.createElement("input");
							input_hidden.type = "hidden";
							input_hidden.name = 'sf_hid_rule2_id[]';
							input_hidden.value = sf_field_data_m;
							
							input_hidden2.type = "hidden";
							input_hidden2.name = 'sf_hid_rule2_alt_id[]';
							input_hidden2.value = sf_field_data_a;
							
							input_hidden3.type = "hidden";
							input_hidden3.name = 'sf_hid_rule2_quest_id[]';
							input_hidden3.value = q_id;
							cell1.width = '375px';
							cell1.innerHTML = '<?php echo JText::_('COM_SF_FOR_QUESTION'); ?> "'+jQuery('#sf_quest_list3').get(0).options[jQuery('#sf_quest_list3').get(0).selectedIndex].innerHTML+'"';
							cell1.appendChild(input_hidden);
							cell1.appendChild(input_hidden2);
							cell1.appendChild(input_hidden3);
							if (qtype != 2 && qtype != 3) {
								cell2.innerHTML = ' <?php echo JText::_('COM_SF_AND_FOR_OPTION'); ?> "'+jQuery('#sf_field_data_m').get(0).options[jQuery('#sf_field_data_m').get(0).selectedIndex].innerHTML+'"';				
								if (qtype != 9) {
									if (qtype == 1)
										cell3.innerHTML = ' <?php echo JText::_('COM_SF_ANSWER_IS'); ?> "'+jQuery('#f_scale_data').get(0).options[jQuery('#f_scale_data').get(0).selectedIndex].innerHTML+'"';
									else
										cell3.innerHTML = ' <?php echo JText::_('COM_SF_ANSWER_IS'); ?> "'+jQuery('#sf_field_data_a').get(0).options[jQuery('#sf_field_data_a').get(0).selectedIndex].innerHTML+'"';
								}else {
									cell3.innerHTML = ' <?php echo JText::_('COM_SF_RANK_IS'); ?> "'+jQuery('#sf_field_data_a').get(0).options[jQuery('#sf_field_data_a').get(0).selectedIndex].innerHTML+'"';
								}
							} else {
								cell2.innerHTML = ' <?php echo JText::_('COM_SF_ANSWER_IS'); ?> "'+jQuery('#sf_field_data_m').get(0).options[jQuery('#sf_field_data_m').get(0).selectedIndex].innerHTML+'"';	
							}
							
							cell4.innerHTML = '<a href="javascript: void(0);" onclick="javascript:Delete_row(this); return false;" title="<?php echo JText::_('COM_SF_DELETE'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/publish_x.png"  border="0" alt="<?php echo JText::_('COM_SF_DELETE');?>"></a>';							
							row.appendChild(cell1);
							row.appendChild(cell2);							
							row.appendChild(cell3);
							row.appendChild(cell4);						
						}
						function processReq(http_request) {
							if (http_request.readyState == 4) {
								if ((http_request.status == 200)) {									
									var response = http_request.responseXML.documentElement;
									var text = '<?php echo JText::_('COM_SF_REQUEST_ERROR'); ?>';
									try {
										text = response.getElementsByTagName('data')[0].firstChild.data;
									} catch(e) {}
									jQuery('div#quest_show_div').html(text);							
								}
							}
						}
						function showOptions(val) {
							
							jQuery('input#add_button').get(0).style.display = 'none';
							
							jQuery('div#quest_show_div').html("<?php echo JText::_('COM_SF_PLEASE_WAIT_LOADING'); ?>");
							
							var http_request = false;
							if (window.XMLHttpRequest) { // Mozilla, Safari,...
								http_request = new XMLHttpRequest();
								if (http_request.overrideMimeType) {
									http_request.overrideMimeType('text/xml');
								}
							} else if (window.ActiveXObject) { // IE
								try { http_request = new ActiveXObject("Msxml2.XMLHTTP");
								} catch (e) {
									try { http_request = new ActiveXObject("Microsoft.XMLHTTP");
									} catch (e) {}
								}
							}
							if (!http_request) {
								return false;
							}

							http_request.onreadystatechange = function() { processReq(http_request); };
<?php 
$live_site = $GLOBALS['mosConfig_live_site'];
if (substr($_SERVER['HTTP_HOST'],0,4) == 'www.') {
	if (strpos($GLOBALS['mosConfig_live_site'], 'www.') !== false)
		$live_site = $GLOBALS['mosConfig_live_site'];
	else {
		$live_site = str_replace(substr($_SERVER['HTTP_HOST'],4), $_SERVER['HTTP_HOST'], $GLOBALS['mosConfig_live_site']);
	}
} else { 
	if (strpos($GLOBALS['mosConfig_live_site'], 'www.') !== false) 
		$live_site = str_replace('www.'.$_SERVER['HTTP_HOST'], $_SERVER['HTTP_HOST'], $GLOBALS['mosConfig_live_site']);
	else
		$live_site = $GLOBALS['mosConfig_live_site'];
}

$live_site_parts = parse_url($live_site); 

$live_url = $live_site_parts['scheme'].'://'.$live_site_parts['host'].(isset($live_site_parts['port'])?':'.$live_site_parts['port']:'').(isset($live_site_parts['path'])?$live_site_parts['path']:'/');

if ( substr($live_url, strlen($live_url)-1, 1) !== '/')
	$live_url .= '/';
?>

							http_request.open('GET', '<?php echo $live_url;?>administrator/index.php?no_html=1&option=com_surveyforce&task=get_options&rand=<?php echo time();?>&quest_id='+val, true);
							http_request.send(null);

							sf_is_loading = false;
						}					
						if (jQuery('#sf_quest_list3').get(0).options.length > 0)
							showOptions(jQuery('#sf_quest_list3').get(0).options[jQuery('#sf_quest_list3').get(0).selectedIndex].value);
						else {
							jQuery('table#show_quest').get(0).style.display = 'none';
							jQuery('table#show_quest2').get(0).style.display = 'none';
						}
					</script>
				</td>
			</tr>
		</table>
		<br />
		<input type="hidden" name="sf_qtype" value="4" />
		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="id" value="<?php echo $row->id;?>" />
		<input type="hidden" name="task" value="" />
	
		<input type="hidden" name="quest_id" value="<?php echo $row->id;?>" />
		<input type="hidden" name="red_task" value="<?php echo $task;?>" />
		</form>
		<?php
		EF_menu_footer();
	}
	
	function SF_editQ_Boilerplate( &$row, &$lists, $option, $q_om_type ) {
		global $mosConfig_live_site, $task, $_MAMBOTS;
		
		if (_JOOMLA15) {
			jimport( 'joomla.html.editor' );
	
			$conf =& JFactory::getConfig();
			$editor = $conf->getValue('config.editor');
			$editorz =& JEditor::getInstance($editor);
			$editorz =& JFactory::getEditor();
		}

		mosCommonHTML::loadOverlib();
		EF_menu_header();
		?>
		<script language="javascript" type="text/javascript">
		<!--
		Joomla.submitbutton = function(pressbutton) {
			var form = document.adminForm;

			if (pressbutton == 'cancel_quest') {
				submitform( pressbutton );
				return;
			}
			// do field validation
			
			<?php 		
			//print WYSIWYG editor function name to save content to textarea
			
			$script = '';
			if (_JOOMLA15) {
				$script = $editorz->save('sf_qtext');
			}
			else {	
				$results = $_MAMBOTS->trigger( 'onGetEditorContents', array( 'editor2', 'sf_qtext' ) );
				if (trim($results[0])) {
					$script = $results[0];
				}
			}
			
			if (trim($script))
				echo $script;
			?>

			if (form.sf_qtext.value == ""){
				alert( "<?php echo JText::_('COM_SF_BOILERPLATE_QUESTION_MUST_HAVE_TEXT'); ?>" );
			} else {
				submitform( pressbutton );
			}
		}
		//-->
		</script>
		
		
		
		
		<form action="index.php" method="post" name="adminForm">
		<?php if (!class_exists('JToolBarHelper')) { ?>

		<table class="adminheading">
		<tr>
			<th>
			<?php echo _SURVEY_FORCE_COMP_NAME?>:
			<small>
			<?php echo $row->id ? JText::_('COM_SF_EDIT_QUESTION') : JText::_('COM_SF_NEW_QUESTION'); echo JText::_('COM_SF_BOILERPLATE'); ?>
			</small>
			</th>
		</tr>
		</table>
		<?php } else { 
			JToolBarHelper::title( ($row->id ? JText::_('COM_SF_EDIT_QUESTION') : JText::_('COM_SF_NEW_QUESTION')).JText::_('COM_SF_BOILERPLATE'), 'static.png' );
		}?> 
		<table width="100%" class="adminform">
			<tr>
				<th colspan="2"><?php echo JText::_('COM_SF_QUESTION_DETAILS'); ?></th>
			<tr>
			<tr>
				<td align="right" width="20%" valign="top"><?php echo JText::_('COM_SF_QUESTION_TEXT'); ?>:</td>
				<td><?php 
				if (_JOOMLA15) {
					echo $editorz->display('sf_qtext', $row->sf_qtext, '100%;', '250', '40', '20', array('pagebreak', 'readmore'));
				}
				else {
					editorArea( 'editor2', $row->sf_qtext, 'sf_qtext', '100%;', '250', '40', '20' ) ; 
				}

				?>
				</td>
			</tr>
			<tr>
				<td><?php echo JText::_('COM_SF_SECTION'); ?>:</td><td><?php echo $lists['survey'];?></td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('COM_SF_PUBLISHED'); ?>:
				</td>
				<td>
					<?php echo $lists['published']; ?>
				</td>
			</tr>
			<tr>
				<td><?php echo JText::_('COM_SF_ORDERING'); ?></td><td><?php echo $lists['ordering'];?></td>
			</tr> 
			<?php if ( $lists['sf_section_id'] != null ) {?>
			<tr>
				<td><?php echo JText::_('COM_SF_SECTION'); ?>:</td><td><?php echo $lists['sf_section_id'];?></td>
			</tr> 
			<?php }?>
			<?php if (!($row->id > 0)) {?>
			<tr>
				<td>
					<?php echo JText::_('COM_SF_INSERT_PAGE_BREAK_AFTER_QUESTION'); ?>
				</td>
				<td>
					<?php echo $lists['insert_pb']; ?>
				</td>
			</tr>
			<?php } ?>
			<tr>
				<td>
					<?php echo JText::_('COM_SF_HIDDEN_BY_DEFAULT'); ?>
				</td>
				<td>
					<?php echo $lists['sf_default_hided']; ?>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<script type="text/javascript" src="<?php echo $mosConfig_live_site;?>/administrator/components/com_surveyforce/js/jquery.pack.js"></script>
					<script type="text/javascript" language="javascript" >
						jQuery.noConflict();
						var sf_is_loading = false;
					</script>
					<table class="adminlist" id="show_quest">
					<tr>
						<th class="title" colspan="4"><?php echo JText::_('COM_SF_DONT_SHOW_QUESTION'); ?></th>
					</tr>
					<?php if (is_array($lists['quest_show']) && count($lists['quest_show'])) 
							foreach($lists['quest_show'] as $rule) {
								if ( ($rule->sf_qtype == 2) || ($rule->sf_qtype == 3) ) {
							?>
							
							<tr>
								<td width="375px;"> <?php echo JText::_('COM_SF_FOR_QUESTION'); ?> "<?php echo $rule->sf_qtext;?>" <input type="hidden" name="sf_hid_rule2_id[]" value="<?php echo $rule->bid;?>" /><input type="hidden" name="sf_hid_rule2_alt_id[]" value="<?php echo $rule->did;?>" /><input type="hidden" name="sf_hid_rule2_quest_id[]" value="<?php echo $rule->qid;?>" /></td>
								<td colspan="2"> <?php echo JText::_('COM_SF_ANSWER_IS'); ?> "<?php echo $rule->qoption;?>"</td>
								<td><a href="javascript: void(0);" onclick="javascript:Delete_row(this); return false;" title="<?php echo JText::_('COM_SF_DELETE'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/publish_x.png"  border="0" alt="<?php echo JText::_('COM_SF_DELETE'); ?>"></a></td>
							</tr>
							<?php } elseif (($rule->sf_qtype == 1) || ($rule->sf_qtype == 5) || ($rule->sf_qtype == 6)) {?>
							<tr>
								<td  width="375px;"><?php echo JText::_('COM_SF_FOR_QUESTION'); ?> "<?php echo $rule->sf_qtext;?>"<input type="hidden" name="sf_hid_rule2_id[]" value="<?php echo $rule->bid;?>" /><input type="hidden" name="sf_hid_rule2_alt_id[]" value="<?php echo ($rule->sf_qtype == 1?$rule->sdid:$rule->fdid);?>" /><input type="hidden" name="sf_hid_rule2_quest_id[]" value="<?php echo $rule->qid;?>" /></td>
								<td> <?php echo JText::_('COM_SF_AND_FOR_OPTION'); ?> "<?php echo $rule->qoption;?>"</td>
								<td> <?php echo JText::_('COM_SF_ANSWER_IS'); ?> "<?php echo ($rule->sf_qtype == 1?$rule->astext:$rule->aftext);?>"</td>
								<td><a href="javascript: void(0);" onclick="javascript:Delete_row(this); return false;" title="<?php echo JText::_('COM_SF_DELETE'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/publish_x.png"  border="0" alt="<?php echo JText::_('COM_SF_DELETE'); ?>"></a></td>
							</tr>
							<?php } elseif ($rule->sf_qtype == 9) {?>
							<tr >
								<td  width="375px;"> <?php echo JText::_('COM_SF_FOR_QUESTION'); ?> "<?php echo $rule->sf_qtext;?>"<input type="hidden" name="sf_hid_rule2_id[]" value="<?php echo $rule->bid;?>" /><input type="hidden" name="sf_hid_rule2_alt_id[]" value="<?php echo $rule->did;?>" /><input type="hidden" name="sf_hid_rule2_quest_id[]" value="<?php echo $rule->qid;?>" /></td>
								<td> <?php echo JText::_('COM_SF_AND_FOR_OPTION'); ?> "<?php echo $rule->qoption;?>"</td>
								<td> <?php echo JText::_('COM_SF_RANK_IS'); ?> "<?php echo $rule->aftext;?>"</td>
								<td><a href="javascript: void(0);" onclick="javascript:Delete_row(this); return false;" title="<?php echo JText::_('COM_SF_DELETE'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/publish_x.png"  border="0" alt="<?php echo JText::_('COM_SF_DELETE'); ?>"></a></td>
							</tr>	
							<?php }
							}?>
					</table>
					<table width="100%"  id="show_quest2">
					<tr>
						<td style="width:70px;"><?php echo JText::_('COM_SF_FOR_QUESTION'); ?> </td><td style="width:15px;"><?php echo $lists['quests3'];?></td>
						<td width="auto" colspan="2" ><div id="quest_show_div"></div>						
						</td>
					</tr>							
					<tr>
						<td colspan="4" style="text-align:left;"><input id="add_button" type="button" name="add" value="<?php echo JText::_('COM_SF_ADD'); ?>" onclick="javascript: if(!sf_is_loading) addRow();"  />
						</td>
					</tr>
					</table>
					<script type="text/javascript" language="javascript">
						function Delete_row(element) {
							var del_index = element.parentNode.parentNode.sectionRowIndex;
							var tbl_id = element.parentNode.parentNode.parentNode.parentNode.id;
							element.parentNode.parentNode.parentNode.deleteRow(del_index);							
						}
	
						function addRow(){
							var qtype = jQuery('#sf_qtype2').get(0).value;
			
							var sf_field_data_m = jQuery('#sf_field_data_m').get(0).options[jQuery('#sf_field_data_m').get(0).selectedIndex].value;
							var q_id = jQuery('#sf_quest_list3').get(0).options[jQuery('#sf_quest_list3').get(0).selectedIndex].value;
							if (qtype != 2 && qtype != 3) {
								if (qtype == 1)
									var sf_field_data_a = jQuery('#f_scale_data').get(0).options[jQuery('#f_scale_data').get(0).selectedIndex].value;
								else
									var sf_field_data_a = jQuery('#sf_field_data_a').get(0).options[jQuery('#sf_field_data_a').get(0).selectedIndex].value;
							 } else {
								var sf_field_data_a = 0;
							}
							
							var tbl_elem = jQuery('#show_quest').get(0);
							var row = tbl_elem.insertRow(tbl_elem.rows.length);
									
							var cell1 = document.createElement("td");
							var cell2 = document.createElement("td");
							var cell3 = document.createElement("td");
							var cell4 = document.createElement("td");
							var input_hidden = document.createElement("input");
							var input_hidden2 = document.createElement("input");
							var input_hidden3 = document.createElement("input");
							input_hidden.type = "hidden";
							input_hidden.name = 'sf_hid_rule2_id[]';
							input_hidden.value = sf_field_data_m;
							
							input_hidden2.type = "hidden";
							input_hidden2.name = 'sf_hid_rule2_alt_id[]';
							input_hidden2.value = sf_field_data_a;
							
							input_hidden3.type = "hidden";
							input_hidden3.name = 'sf_hid_rule2_quest_id[]';
							input_hidden3.value = q_id;
							cell1.width = '375px';
							cell1.innerHTML = '<?php echo JText::_('COM_SF_FOR_QUESTION'); ?> "'+jQuery('#sf_quest_list3').get(0).options[jQuery('#sf_quest_list3').get(0).selectedIndex].innerHTML+'"';
							cell1.appendChild(input_hidden);
							cell1.appendChild(input_hidden2);
							cell1.appendChild(input_hidden3);
							if (qtype != 2 && qtype != 3) {
								cell2.innerHTML = ' <?php echo JText::_('COM_SF_AND_FOR_OPTION'); ?> "'+jQuery('#sf_field_data_m').get(0).options[jQuery('#sf_field_data_m').get(0).selectedIndex].innerHTML+'"';				
								if (qtype != 9){
									if (qtype == 1)
										cell3.innerHTML = ' <?php echo JText::_('COM_SF_ANSWER_IS'); ?> "'+jQuery('#f_scale_data').get(0).options[jQuery('#f_scale_data').get(0).selectedIndex].innerHTML+'"';
									else
										cell3.innerHTML = ' <?php echo JText::_('COM_SF_ANSWER_IS'); ?> "'+jQuery('#sf_field_data_a').get(0).options[jQuery('#sf_field_data_a').get(0).selectedIndex].innerHTML+'"';
								}else {
									cell3.innerHTML = ' <?php echo JText::_('COM_SF_RANK_IS'); ?> "'+jQuery('#sf_field_data_a').get(0).options[jQuery('#sf_field_data_a').get(0).selectedIndex].innerHTML+'"';
								}
							} else {
								cell2.innerHTML = ' <?php echo JText::_('COM_SF_ANSWER_IS'); ?> "'+jQuery('#sf_field_data_m').get(0).options[jQuery('#sf_field_data_m').get(0).selectedIndex].innerHTML+'"';	
							}
							
							cell4.innerHTML = '<a href="javascript: void(0);" onclick="javascript:Delete_row(this); return false;" title="<?php echo JText::_('COM_SF_DELETE'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/publish_x.png"  border="0" alt="<?php echo JText::_('COM_SF_DELETE'); ?>"></a>';							
							row.appendChild(cell1);
							row.appendChild(cell2);							
							row.appendChild(cell3);
							row.appendChild(cell4);						
						}
						function processReq(http_request) {
							if (http_request.readyState == 4) {
								if ((http_request.status == 200)) {									
									var response = http_request.responseXML.documentElement;
									var text = '<?php echo JText::_('COM_SF_REQUEST_ERROR'); ?>';
									try {
										text = response.getElementsByTagName('data')[0].firstChild.data;
									} catch(e) {}
									jQuery('div#quest_show_div').html(text);							
								}
							}
						}
						function showOptions(val) {
							
							jQuery('input#add_button').get(0).style.display = 'none';
							
							jQuery('div#quest_show_div').html("<?php echo JText::_('COM_SF_PLEASE_WAIT_LOADING'); ?>");
							
							var http_request = false;
							if (window.XMLHttpRequest) { // Mozilla, Safari,...
								http_request = new XMLHttpRequest();
								if (http_request.overrideMimeType) {
									http_request.overrideMimeType('text/xml');
								}
							} else if (window.ActiveXObject) { // IE
								try { http_request = new ActiveXObject("Msxml2.XMLHTTP");
								} catch (e) {
									try { http_request = new ActiveXObject("Microsoft.XMLHTTP");
									} catch (e) {}
								}
							}
							if (!http_request) {
								return false;
							}

							http_request.onreadystatechange = function() { processReq(http_request); };

<?php 
$live_site = $GLOBALS['mosConfig_live_site'];
if (substr($_SERVER['HTTP_HOST'],0,4) == 'www.') {
	if (strpos($GLOBALS['mosConfig_live_site'], 'www.') !== false)
		$live_site = $GLOBALS['mosConfig_live_site'];
	else {
		$live_site = str_replace(substr($_SERVER['HTTP_HOST'],4), $_SERVER['HTTP_HOST'], $GLOBALS['mosConfig_live_site']);
	}
} else { 
	if (strpos($GLOBALS['mosConfig_live_site'], 'www.') !== false) 
		$live_site = str_replace('www.'.$_SERVER['HTTP_HOST'], $_SERVER['HTTP_HOST'], $GLOBALS['mosConfig_live_site']);
	else
		$live_site = $GLOBALS['mosConfig_live_site'];
}

$live_site_parts = parse_url($live_site); 

$live_url = $live_site_parts['scheme'].'://'.$live_site_parts['host'].(isset($live_site_parts['port'])?':'.$live_site_parts['port']:'').(isset($live_site_parts['path'])?$live_site_parts['path']:'/');

if ( substr($live_url, strlen($live_url)-1, 1) !== '/')
	$live_url .= '/';
?>

							http_request.open('GET', '<?php echo $live_url;?>administrator/index.php?no_html=1&option=com_surveyforce&task=get_options&rand=<?php echo time();?>&quest_id='+val, true);
							http_request.send(null);

							sf_is_loading = false;
						}					
						if (jQuery('#sf_quest_list3').get(0).options.length > 0)
							showOptions(jQuery('#sf_quest_list3').get(0).options[jQuery('#sf_quest_list3').get(0).selectedIndex].value);
						else {
							jQuery('table#show_quest').get(0).style.display = 'none';
							jQuery('table#show_quest2').get(0).style.display = 'none';
						}
					</script>
				</td>
			</tr>
		</table>
		<br />
		<input type="hidden" name="sf_impscale" value="0" />
		<input type="hidden" name="sf_compulsory" value="0" />
		<input type="hidden" name="sf_qtype" value="7" />
		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="id" value="<?php echo $row->id;?>" />
		<input type="hidden" name="task" value="" />
		</form>
		<?php
		EF_menu_footer();
	}
	
	
	function SF_editQ_Likert_PickOneMany( &$row, &$lists, $option, $q_om_type ) {
		global $mosConfig_live_site, $task, $_MAMBOTS;

		mosCommonHTML::loadOverlib();
		if (_JOOMLA15) {
			jimport( 'joomla.html.editor' );
	
			$conf =& JFactory::getConfig();
			$editor = $conf->getValue('config.editor');
			$editorz =& JEditor::getInstance($editor);
			$editorz =& JFactory::getEditor();
		}

		survey_force_adm_html::SF_JS_getObj();
		EF_menu_header();
		?>
		<script language="javascript" type="text/javascript">
		<!--
		var quest_type = <?php echo $q_om_type; ?>;
		var field_name = '';
		var field_id = '';
		function Redeclare_element_inputs2(object) {			
			if (object.hasChildNodes()) {
				var children = object.childNodes;
				for (var i = 0; i < children.length; i++) {
					if (children[i].nodeName.toLowerCase() == 'input') {						
						var inp_name = children[i].name;
						
						var inp_value = children[i].value;
						object.removeChild(object.childNodes[i]);
							
						var input_hidden = document.createElement("input");
						input_hidden.type = "hidden";
						input_hidden.name = inp_name;
						input_hidden.value = inp_value;
						object.appendChild(input_hidden);						
					}
				}
			}
		}

		function analyze_cat(){
			var element = getObj('inp_tmp');
			if (element){
				var parent = element.parentNode;
				
				var inpu_value = element.value;
				parent.removeChild(element);
				var  cat_id_sss = '0';
				if (parent.hasChildNodes()) {
					var children = parent.childNodes;
					for (var i = 0; i < children.length; i++) {
						if (children[i].nodeName.toLowerCase() == 'input') {
							if (children[i].name == field_id) {
								cat_id_sss = children[i].value;
							}
						}
					}
				}
				var input_cat2 = document.createElement("input");
				input_cat2.type = "hidden";
				input_cat2.name = field_name;
				input_cat2.value = inpu_value;
				var input_id2 = document.createElement("input");
				input_id2.type = "hidden";
				input_id2.name = field_id;				
				input_id2.value = cat_id_sss;
				
				var span = document.createTextNode(inpu_value);				
				parent.innerHTML = '';				
				parent.appendChild(input_cat2);
				parent.appendChild(input_id2);
				
				parent.appendChild(span);				
				
			}
			
		}
		var edit_id = '';
		function edit_name(e, field, field2){
			analyze_cat()
				field_name = field;
				field_id = field2;						
				if (!e) { e = window.event;}
					var cat2=e.target?e.target:e.srcElement;			
				Redeclare_element_inputs2(cat2);
				var cat_name_value = '';
				var found = false;
				if (cat2.hasChildNodes()) {
					var children = cat2.childNodes;
					var children_count = children.length;
					for (var i = 0; i < children_count; i++) {
						if (children[i].nodeName.toLowerCase() == 'input') {						
							if (children[i].name == field_name) {
								cat_name_value = children[i].value;
								found = true;
							} 
						}
					}
					if (!found) return;
					for (var i = 0; i < children.length; i++) {
						if (children[i].nodeName.toLowerCase() != 'input') {						
							cat2.removeChild(cat2.childNodes[i]);
						}
					}
				}
				var input_cat3 = document.createElement("input");
				input_cat3.type = "text";
				input_cat3.id = "inp_tmp";
				input_cat3.name = "inp_tmp";
				
				input_cat3.value = cat_name_value;
				input_cat3.setAttribute("style","z-index:5000");
				if (window.addEventListener) { input_cat3.addEventListener('dblclick', analyze_cat, false);}else { input_cat3.attachEvent('ondblclick', analyze_cat );}
				cat2.appendChild(input_cat3);		
						
		}

		function ReAnalize_tbl_Rows( start_index, tbl_id ) {
			start_index = 1;
			var tbl_elem = getObj(tbl_id);
			
			if (tbl_elem.rows[start_index]) {
				var count = start_index; var row_k = 1 - start_index%2;//0;
				for (var i=start_index; i<tbl_elem.rows.length; i++) {
					tbl_elem.rows[i].cells[0].innerHTML = count;				
					if (i > 1) { 
						tbl_elem.rows[i].cells[3].innerHTML = '<a href="javascript: void(0);" onClick="javascript:Up_tbl_row(this); return false;" title="<?php echo JText::_('COM_SF_MOVE_UP'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/uparrow.png"  border="0" alt="<?php echo JText::_('COM_SF_MOVE_UP'); ?>"></a>';
					} else { tbl_elem.rows[i].cells[3].innerHTML = ''; }
					if (i < (tbl_elem.rows.length - 1)) {
						tbl_elem.rows[i].cells[4].innerHTML = '<a href="javascript: void(0);" onClick="javascript:Down_tbl_row(this); return false;" title="<?php echo JText::_('COM_SF_MOVE_DOWN'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/downarrow.png"  border="0" alt="<?php echo JText::_('COM_SF_MOVE_DOWN'); ?>"></a>';;
					} else { tbl_elem.rows[i].cells[4].innerHTML = ''; }
					tbl_elem.rows[i].className = 'row'+row_k;
					count++;
					row_k = 1 - row_k;
				}
			}
			<?php if ($q_om_type == 1 && $row->id || $q_om_type != 1) {?>
			Add_fields_to_select();
			<?php }?>
		}
		
		function Redeclare_element_inputs(object) {
			if (object.hasChildNodes()) {
				var children = object.childNodes;
				for (var i = 0; i < children.length; i++) {
					if (children[i].nodeName.toLowerCase() == 'input') {
						var inp_name = children[i].name;
						var inp_value = children[i].value;
						object.removeChild(object.childNodes[i]);
						var input_hidden = document.createElement("input");
						input_hidden.type = "hidden";
						input_hidden.name = inp_name;
						input_hidden.value = inp_value;
						object.appendChild(input_hidden);
					}
				};
			};
		}


		function Delete_tbl_row(element) {
			var del_index = element.parentNode.parentNode.sectionRowIndex;
			var tbl_id = element.parentNode.parentNode.parentNode.parentNode.id;
			element.parentNode.parentNode.parentNode.deleteRow(del_index);
			ReAnalize_tbl_Rows(del_index - 1, tbl_id);
		}

		function Up_tbl_row(element) {
			if (element.parentNode.parentNode.sectionRowIndex > 1) {
				var sec_indx = element.parentNode.parentNode.sectionRowIndex;
				var table = element.parentNode.parentNode.parentNode;
				var tbl_id = table.parentNode.id;
				var cell2_tmp = element.parentNode.parentNode.cells[1].innerHTML;
				var td_value = element.parentNode.parentNode.cells[1].childNodes[0].value;
				var td_id = element.parentNode.parentNode.cells[1].childNodes[1].value;
				
				var name1 = element.parentNode.parentNode.cells[1].childNodes[0].name;
				var name2 = element.parentNode.parentNode.cells[1].childNodes[1].name;
				element.parentNode.parentNode.parentNode.deleteRow(element.parentNode.parentNode.sectionRowIndex);
				// nel'zya prosto skopirovat' staryi innerHTML, t.k. ne sozdadutsya DOM elementy (for IE, Opera compatible).
				var row = table.insertRow(sec_indx - 1);
				var cell1 = document.createElement("td");
				var cell2 = document.createElement("td");
				var cell3 = document.createElement("td");
				var cell4 = document.createElement("td");
				
				var input_hidden = document.createElement("input");
				var input_hidden2 = document.createElement("input");
				var span = document.createElement("span");
				
				cell1.align = 'center';
				cell1.innerHTML = 0;
				cell2.align = 'left';
				
				input_hidden.type = "hidden";				
				input_hidden.value = td_value;
				input_hidden.name = name1;
				input_hidden.setAttribute('name', name1);
				
				input_hidden2.type = "hidden";				
				input_hidden2.value = td_id;
				input_hidden2.name = name2;
				input_hidden2.setAttribute('name', name2);
				
				span.innerHTML = td_value;				
				cell2.appendChild(input_hidden);
				cell2.appendChild(input_hidden2);
				cell2.appendChild(span);
				
				cell3.innerHTML = '<a href="javascript: void(0);" onClick="javascript:Delete_tbl_row(this); return false;" title="<?php echo JText::_('COM_SF_DELETE'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/publish_x.png"  border="0" alt="<?php echo JText::_('COM_SF_DELETE'); ?>"></a>';
				cell4.innerHTML = '<a href="javascript: void(0);" onClick="javascript:Up_tbl_row(this); return false;" title="<?php echo JText::_('COM_SF_MOVE_UP'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/uparrow.png"  border="0" alt="<?php echo JText::_('COM_SF_MOVE_UP'); ?>"></a>';
				row.appendChild(cell1);
				row.appendChild(cell2);
				row.appendChild(cell3);
				row.appendChild(cell4);
				row.appendChild(document.createElement("td"));
				row.appendChild(document.createElement("td"));
								
				ReAnalize_tbl_Rows(sec_indx - 2, tbl_id);
			}
		}

		function Down_tbl_row(element) {
			if (element.parentNode.parentNode.sectionRowIndex < element.parentNode.parentNode.parentNode.rows.length - 1) {
				var sec_indx = element.parentNode.parentNode.sectionRowIndex;
				var table = element.parentNode.parentNode.parentNode;
				var tbl_id = table.parentNode.id;
				var cell2_tmp = element.parentNode.parentNode.cells[1].innerHTML;
				var td_value = element.parentNode.parentNode.cells[1].childNodes[0].value;
				var td_id = element.parentNode.parentNode.cells[1].childNodes[1].value;
				
				var name1 = element.parentNode.parentNode.cells[1].childNodes[0].name;
				var name2 = element.parentNode.parentNode.cells[1].childNodes[1].name;
				
				element.parentNode.parentNode.parentNode.deleteRow(element.parentNode.parentNode.sectionRowIndex);
				var row = table.insertRow(sec_indx + 1);
				var cell1 = document.createElement("td");
				var cell2 = document.createElement("td");
				var cell3 = document.createElement("td");
				var cell4 = document.createElement("td");
				
				var input_hidden = document.createElement("input");
				var input_hidden2 = document.createElement("input");
				var span = document.createElement("span");
				
				input_hidden.type = "hidden";
				input_hidden.name = name1;
				input_hidden.value = td_value;
				
				input_hidden2.type = "hidden";
				input_hidden2.name = name2;
				input_hidden2.value = td_id;
				
				cell1.align = 'center';
				cell1.innerHTML = 0;
				cell2.align = 'left';
				
				span.innerHTML = td_value;
				cell2.appendChild(input_hidden);
				cell2.appendChild(input_hidden2);
				cell2.appendChild(span);
				
				cell3.innerHTML = '<a href="javascript: void(0);" onClick="javascript:Delete_tbl_row(this); return false;" title="<?php echo JText::_('COM_SF_DELETE'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/publish_x.png"  border="0" alt="<?php echo JText::_('COM_SF_DELETE'); ?>"></a>';
				cell4.innerHTML = '<a href="javascript: void(0);" onClick="javascript:Up_tbl_row(this); return false;" title="<?php echo JText::_('COM_SF_MOVE_UP'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/uparrow.png"  border="0" alt="<?php echo JText::_('COM_SF_MOVE_UP'); ?>"></a>';
				row.appendChild(cell1);
				row.appendChild(cell2);
				row.appendChild(cell3);
				row.appendChild(cell4);
				row.appendChild(document.createElement("td"));
				row.appendChild(document.createElement("td"));
				ReAnalize_tbl_Rows(sec_indx, tbl_id);
			}
		}

		function Add_new_tbl_field(elem_field, tbl_id, field_name, field_name2) {
			var new_element_txt = getObj(elem_field).value;
			if (TRIM_str(new_element_txt) == '') {
				alert("<?php echo JText::_('COM_SF_PLEASE_ENTER_TEXT_TO_FIELD'); ?>");return;
			}
			getObj(elem_field).value = '';
			var tbl_elem = getObj(tbl_id);
			var row = tbl_elem.insertRow(tbl_elem.rows.length);					
			var cell1 = document.createElement("td");
			var cell2 = document.createElement("td");
			var cell3 = document.createElement("td");
			var cell4 = document.createElement("td");
			var cell5 = document.createElement("td");
			var cell6 = document.createElement("td");
			var input_hidden = document.createElement("input");
			var input_hidden2 = document.createElement("input");
			var span = document.createElement("span");
			input_hidden.type = "hidden";
			input_hidden.name = field_name;
			input_hidden.value = new_element_txt;
			
			input_hidden2.type = "hidden";
			input_hidden2.name = field_name2;
			input_hidden2.value = 0;
			cell1.align = 'center';
			cell1.innerHTML = 0;
			cell2.setAttribute("ondblclick", "javascript:edit_name(event, '"+field_name+"','"+field_name2+"');"); 
			span.innerHTML = new_element_txt;
			cell2.appendChild(input_hidden);
			cell2.appendChild(input_hidden2);
			cell2.appendChild(span);
			cell3.innerHTML = '<a href="javascript: void(0);" onClick="javascript:Delete_tbl_row(this); return false;" title="<?php echo JText::_('COM_SF_DELETE'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/publish_x.png"  border="0" alt="<?php echo JText::_('COM_SF_DELETE'); ?>"></a>';
			cell4.innerHTML = '<a href="javascript: void(0);" onClick="javascript:Up_tbl_row(this); return false;" title="<?php echo JText::_('COM_SF_MOVE_UP'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/uparrow.png"  border="0" alt="<?php echo JText::_('COM_SF_MOVE_UP'); ?>"></a>';
			cell5.innerHTML = '';
			row.appendChild(cell1);
			row.appendChild(cell2);
			row.appendChild(cell3);
			row.appendChild(cell4);
			row.appendChild(cell5);
			row.appendChild(cell6);
			ReAnalize_tbl_Rows(tbl_elem.rows.length - 2, tbl_id);
		}
		
		function Delete_tbl_row2(element) {
			var del_index = element.parentNode.parentNode.sectionRowIndex;
			var tbl_id = element.parentNode.parentNode.parentNode.parentNode.id;
			element.parentNode.parentNode.parentNode.deleteRow(del_index);
			ReAnalize_tbl_Rows2(del_index - 1, tbl_id);
		}

		function ReAnalize_tbl_Rows2( start_index, tbl_id ) {
			start_index = 1;
			var tbl_elem = getObj(tbl_id);
			if (tbl_elem.rows[start_index]) {
				var count = start_index; var row_k = 1 - start_index%2;
				for (var i=start_index; i<tbl_elem.rows.length; i++) {
					tbl_elem.rows[i].cells[0].innerHTML = count;
					Redeclare_element_inputs(tbl_elem.rows[i].cells[1]);
					Redeclare_element_inputs(tbl_elem.rows[i].cells[2]);
					tbl_elem.rows[i].className = 'row'+row_k;
					count++;
					row_k = 1 - row_k;
				}
			}
		}
		
<?php if ($q_om_type != 1) {?>

		function Add_new_tbl_field2(elem_field, tbl_id, field_name, elem_field2, field_name2) {
			var new_element_txt = getObj(elem_field).value;
			var new_element_txt2 = getObj(elem_field2).value;
			if (getObj(elem_field2).selectedIndex < 0 )
				return;
			var new_element_txt2_text = getObj(elem_field2).options[getObj(elem_field2).selectedIndex].innerHTML;
			if (TRIM_str(new_element_txt) == '') {
				alert("<?php echo JText::_('COM_SF_PLEASE_ENTER_TEXT_TO_FIELD'); ?>");return;
			}
			var tbl_elem = getObj(tbl_id);
			var row = tbl_elem.insertRow(tbl_elem.rows.length);
			var cell1 = document.createElement("td");
			var cell2 = document.createElement("td");
			var cell2b = document.createElement("td");
			var cell3 = document.createElement("td");
			var cell3b = document.createElement("td");
			var cell4 = document.createElement("td");
			var cell5 = document.createElement("td");
			var cell6 = document.createElement("td");
			var input_hidden = document.createElement("input");
			input_hidden.type = "hidden";
			input_hidden.name = field_name;
			input_hidden.value = new_element_txt;
			var input_hidden2 = document.createElement("input");
			input_hidden2.type = "hidden";
			input_hidden2.name = field_name2;
			input_hidden2.value = new_element_txt2;
			cell1.align = 'center';
			cell1.innerHTML = 0;
			cell2.innerHTML = new_element_txt;
			cell2.appendChild(input_hidden);
			cell2b.innerHTML = new_element_txt2_text;
			cell2b.appendChild(input_hidden2);
			cell3.innerHTML = '<a href="javascript: void(0);" onClick="javascript:Delete_tbl_row2(this); return false;" title="<?php echo JText::_('COM_SF_DELETE'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/publish_x.png"  border="0" alt="<?php echo JText::_('COM_SF_DELETE'); ?>"></a>';
			cell3b.innerHTML = '<input type="text" style="text-align:center" class="text_area" name="priority[]" size="3" value="'+getObj('new_priority').value+'" />';
			getObj('new_priority').value = '0';
			row.appendChild(cell3);
			cell4.innerHTML = '';
			cell5.innerHTML = '';
			row.appendChild(cell1);
			row.appendChild(cell2);
			row.appendChild(cell2b);
			row.appendChild(cell3b);
			row.appendChild(cell3);			
			row.appendChild(cell4);
			row.appendChild(cell5);
			row.appendChild(cell6);
			ReAnalize_tbl_Rows2(tbl_elem.rows.length - 2, tbl_id);
		}

		function Add_fields_to_select() {
			var tbl_elem = getObj('qfld_tbl');
			var start_index = 1;
			document.adminForm.sf_field_list.options.length = 0;
			var option_ind = 0;
			if (tbl_elem.rows[start_index]) {
				var count = start_index;
				for (var i=start_index; i<tbl_elem.rows.length; i++) {
					if (tbl_elem.rows[i].cells[1].hasChildNodes()) {
						var children = tbl_elem.rows[i].cells[1].childNodes;
						for (var ii = 0; ii < children.length; ii++) {
							if (children[ii].nodeName.toLowerCase() == 'input' && children[ii].name == 'sf_hid_fields[]') {
								document.adminForm.sf_field_list.options[option_ind] = new Option(children[ii].value, children[ii].value );
								option_ind++;
							}
						};
					};
					
				}
			}
			if (getObj('other_option_cb').checked){
				document.adminForm.sf_field_list.options[option_ind] = new Option(getObj('other_option').value, getObj('other_option').value );
				option_ind++;
			}
		}
		
<?php } elseif ($q_om_type == 1 && $row->id) { ?>	
		
		function Add_new_tbl_field2(elem_field, elem_field3, tbl_id, field_name, elem_field2, field_name2, field_name3) {
			var new_element_txt = getObj(elem_field).value;
			var new_element_txt2 = getObj(elem_field2).value;
			var new_element_txt3 = getObj(elem_field3).value;
			if (getObj(elem_field2).selectedIndex < 0 )
				return;
			if (getObj(elem_field3).selectedIndex < 0 )
				return;
			var new_element_txt2_text = getObj(elem_field2).options[getObj(elem_field2).selectedIndex].innerHTML;
			var new_element_txt3_text = getObj(elem_field3).options[getObj(elem_field3).selectedIndex].innerHTML;
			if (TRIM_str(new_element_txt) == '') {
				alert("<?php echo JText::_('COM_SF_PLEASE_ENTER_TEXT_TO_FIELD'); ?>");return;
			}
			var tbl_elem = getObj(tbl_id);
			var row = tbl_elem.insertRow(tbl_elem.rows.length);
			var cell1 = document.createElement("td");
			var cell2 = document.createElement("td");
			var cell2b = document.createElement("td");
			var cell2bb = document.createElement("td");
			var cell3 = document.createElement("td");
			var cell3b = document.createElement("td");
			var cell4 = document.createElement("td");
			var cell5 = document.createElement("td");
			var input_hidden = document.createElement("input");
			input_hidden.type = "hidden";
			input_hidden.name = field_name;
			input_hidden.value = new_element_txt;
			var input_hidden2 = document.createElement("input");
			input_hidden2.type = "hidden";
			input_hidden2.name = field_name2;
			input_hidden2.value = new_element_txt2;
			var input_hidden3 = document.createElement("input");
			input_hidden3.type = "hidden";
			input_hidden3.name = field_name3;
			input_hidden3.value = new_element_txt3;
			cell1.align = 'center';
			cell1.innerHTML = 0;
			cell2.innerHTML = new_element_txt;
			cell2.appendChild(input_hidden);
			
			cell2b.innerHTML = new_element_txt2_text;
			cell2b.appendChild(input_hidden2);
			
			cell2bb.innerHTML = new_element_txt3_text;
			cell2bb.appendChild(input_hidden3);
			cell3.innerHTML = '<a href="javascript: void(0);" onClick="javascript:Delete_tbl_row2(this); return false;" title="<?php echo JText::_('COM_SF_DELETE'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/publish_x.png"  border="0" alt="<?php echo JText::_('COM_SF_DELETE'); ?>"></a>';
			cell3b.innerHTML = '<input type="text" style="text-align:center" class="text_area" name="priority[]" size="3" value="'+getObj('new_priority').value+'" />';
			getObj('new_priority').value = '0';
			row.appendChild(cell3);
			cell4.innerHTML = '';
			cell5.innerHTML = '';
			row.appendChild(cell1);
			row.appendChild(cell2);
			row.appendChild(cell2bb);
			row.appendChild(cell2b);
			row.appendChild(cell3b);
			row.appendChild(cell3);			
			row.appendChild(cell4);
			row.appendChild(cell5);
			ReAnalize_tbl_Rows2(tbl_elem.rows.length - 2, tbl_id);
		}
		
		function Add_fields_to_select() {
			var tbl_elem = getObj('qfld_tbl');
			var start_index = 1;
			document.adminForm.sf_field_list.options.length = 0;
			var option_ind = 0;
			if (tbl_elem.rows[start_index]) {
				var count = start_index;
				for (var i=start_index; i<tbl_elem.rows.length; i++) {
					if (tbl_elem.rows[i].cells[1].hasChildNodes()) {
						var children = tbl_elem.rows[i].cells[1].childNodes;
						for (var ii = 0; ii < children.length; ii++) {
							if (children[ii].nodeName.toLowerCase() == 'input' && children[ii].name == 'sf_hid_fields[]') {
								document.adminForm.sf_field_list.options[option_ind] = new Option(children[ii].value, children[ii].value );
								option_ind++;
							}
						};
					};
					
				}
			}
			
			tbl_elem = getObj('qfld_tbl_scale');
			start_index = 1;
			document.adminForm.sf_list_scale_fields.options.length = 0;
			option_ind = 0;
			if (tbl_elem.rows[start_index]) {
				var count = start_index;
				for (var i=start_index; i<tbl_elem.rows.length; i++) {
					if (tbl_elem.rows[i].cells[1].hasChildNodes()) {
						var children = tbl_elem.rows[i].cells[1].childNodes;
						for (var ii = 0; ii < children.length; ii++) {
							if (children[ii].nodeName.toLowerCase() == 'input' && children[ii].name == 'sf_hid_scale[]') {
								document.adminForm.sf_list_scale_fields.options[option_ind] = new Option(children[ii].value, children[ii].value );
								option_ind++;
							}
						};
					};
					
				}
			}
		}
<?php } ?>

		Joomla.submitbutton = function(pressbutton) {
			var form = document.adminForm;
			if (pressbutton == 'cancel_quest') {
				submitform( pressbutton );
				return;
			}
			
			fillTextArea();
			// do field validation
			if (false && form.sf_qtext.value == ""){
				alert( "<?php echo JText::_('COM_SF_QUESTION_MUST_HAVE_TEXT'); ?>" );
			} 
			else {
				submitform( pressbutton );
			}
		}
		function fillTextArea () {
			var form = document.adminForm;
			<?php 		
			//print WYSIWYG editor function name to save content to textarea
			$script = '';
			if (_JOOMLA15) {
				$script = $editorz->save('sf_qtext');
			}
			else {
				$results = $_MAMBOTS->trigger( 'onGetEditorContents', array( 'editor2', 'sf_qtext' ) );
				if (trim($results[0])) {
					$script = $results[0];
				}
			}
			if (trim($script))
				echo $script;
			?>
			
			return true;
		}

		//-->
		</script>
		
		<form action="index.php" method="post" name="adminForm"  id="adminForm">
		<?php if (!class_exists('JToolBarHelper')) { ?>

		<table class="adminheading">
		<tr>
			<th>
			<?php echo _SURVEY_FORCE_COMP_NAME?>:
			<small>
			<?php echo $row->id ? JText::_('COM_SF_EDIT_QUESTION') : JText::_('COM_SF_NEW_QUESTION'); echo ' ('.(($q_om_type == 1)?JText::_('COM_SF_LIKERT_SCALE'):(JText::_('COM_SF_PICK').(($q_om_type == 2)?JText::_('COM_SF_ONE'):JText::_('COM_SF_MANY'))) ) .')';?>
			</small>
			</th>
		</tr>
		</table>
		<?php } else { 
			JToolBarHelper::title( ($row->id ? JText::_('COM_SF_EDIT_QUESTION') : JText::_('COM_SF_NEW_QUESTION')).' ('.(($q_om_type == 1)?JText::_('COM_SF_LIKERT_SCALE'):(JText::_('COM_SF_PICK').(($q_om_type == 2)?JText::_('COM_SF_ONE'):JText::_('COM_SF_MANY'))) ) .')', 'static.png' );
		}?>
		<table width="100%" class="adminform">
			<tr>
				<th colspan="2"><?php echo JText::_('COM_SF_QUESTION_DETAILS'); ?></th>
			</tr>
			<tr>
				<td align="right" width="20%" valign="top"><?php echo JText::_('COM_SF_QUESTION_TEXT'); ?>:</td>
				<td><?php 
				if (_JOOMLA15) {
					echo $editorz->display('sf_qtext', $row->sf_qtext, '100%;', '250', '40', '20', array('pagebreak', 'readmore'));
				}
				else {
					editorArea( 'editor2', $row->sf_qtext, 'sf_qtext', '100%;', '250', '40', '20' ) ; 
				}
 
				?>
				</td>
			</tr>
			<tr>
				<td><?php echo JText::_('COM_SF_SURVEY'); ?>:</td><td><?php echo $lists['survey']; ?></td>
			</tr>
			<tr>
				<td><?php echo JText::_('COM_SF_IMPORTANCE_SCALE'); ?>:</td><td><?php echo $lists['impscale'];?><input type="button" class="text_area" name="Define new" onClick="javascript: fillTextArea();document.adminForm.task.value='add_iscale_from_quest';document.adminForm.submit();" value="<?php echo JText::_('COM_SF_DEFINE_NEW'); ?>"></td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('COM_SF_PUBLISHED'); ?>:
				</td>
				<td>
					<?php echo $lists['published']; ?>
				</td>
			</tr>
			<tr>
				<td><?php echo JText::_('COM_SF_ORDERING'); ?></td><td><?php echo $lists['ordering']; ?></td>
			</tr> 
			<?php if ( $lists['sf_section_id'] != null ) {?>
			<tr>
				<td><?php echo JText::_('COM_SF_SECTION'); ?>:</td><td><?php echo $lists['sf_section_id'];?></td>
			</tr> 
			<?php }?>
			<tr>
				<td>
					<?php echo JText::_('COM_SF_COMPULSORY_QUESTION'); ?>:
				</td>
				<td>
					<?php echo $lists['compulsory']; ?>				
				</td>
			</tr> 
			<?php if ($q_om_type == 1) {?>
			<tr>
				<td>
					<?php echo JText::_('COM_SF_FACTOR_NAME'); ?>:
				</td>
				<td>
					<input id="sf_fieldtype" class="text_area" style="width:120px " type="text" name="sf_fieldtype" value="<?php echo $row->sf_fieldtype?>">
				</td>
			</tr>
			<?php } 
			if ($q_om_type == 2) { ?>
			<tr>
				<td>
					<?php echo JText::_('COM_SF_USE_DROP_DOWN_STYLE'); ?>:
				</td>
				<td>
					<?php echo $lists['use_drop_down']; ?>
				</td>
			</tr>
			<?php
			}
			?>
			
			<?php if (!($row->id > 0)) {?>
			<tr>
				<td>
					<?php echo JText::_('COM_SF_INSERT_PAGE_BREAK_AFTER_QUESTION'); ?>
				</td>
				<td>
					<?php echo $lists['insert_pb']; ?>
				</td>
			</tr>
			<?php } ?>
			<tr>
				<td>
					<?php echo JText::_('COM_SF_HIDDEN_BY_DEFAULT'); ?>
				</td>
				<td>
					<?php echo $lists['sf_default_hided']; ?>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<script type="text/javascript" src="<?php echo $mosConfig_live_site;?>/administrator/components/com_surveyforce/js/jquery.pack.js"></script>
					<script type="text/javascript" language="javascript" >
						jQuery.noConflict();
						var sf_is_loading = false;
					</script>
					<table class="adminlist" id="show_quest">
					<tr>
						<th class="title" colspan="4"><?php echo JText::_('COM_SF_DONT_SHOW_QUESTION'); ?></th>
					</tr>
					<?php if (is_array($lists['quest_show']) && count($lists['quest_show'])) 
							foreach($lists['quest_show'] as $rule) {
								if ( ($rule->sf_qtype == 2) || ($rule->sf_qtype == 3) ) {
							?>
							
							<tr>
								<td width="375px;"> <?php echo JText::_('COM_SF_FOR_QUESTION'); ?> "<?php echo $rule->sf_qtext;?>" <input type="hidden" name="sf_hid_rule2_id[]" value="<?php echo $rule->bid;?>" /><input type="hidden" name="sf_hid_rule2_alt_id[]" value="<?php echo $rule->did;?>" /><input type="hidden" name="sf_hid_rule2_quest_id[]" value="<?php echo $rule->qid;?>" /></td>
								<td colspan="2"> <?php echo JText::_('COM_SF_ANSWER_IS'); ?> "<?php echo $rule->qoption;?>"</td>
								<td><a href="javascript: void(0);" onclick="javascript:Delete_row(this); return false;" title="<?php echo JText::_('COM_SF_DELETE'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/publish_x.png"  border="0" alt="<?php echo JText::_('COM_SF_DELETE'); ?>"></a></td>
							</tr>
							<?php } elseif (($rule->sf_qtype == 1) || ($rule->sf_qtype == 5) || ($rule->sf_qtype == 6)) {?>
							<tr>
								<td  width="375px;"> <?php echo JText::_('COM_SF_FOR_QUESTION'); ?> "<?php echo $rule->sf_qtext;?>"<input type="hidden" name="sf_hid_rule2_id[]" value="<?php echo $rule->bid;?>" /><input type="hidden" name="sf_hid_rule2_alt_id[]" value="<?php echo ($rule->sf_qtype == 1?$rule->sdid:$rule->fdid);?>" /><input type="hidden" name="sf_hid_rule2_quest_id[]" value="<?php echo $rule->qid;?>" /></td>
								<td> <?php echo JText::_('COM_SF_AND_FOR_OPTION'); ?> "<?php echo $rule->qoption;?>"</td>
								<td> <?php echo JText::_('COM_SF_ANSWER_IS'); ?> "<?php echo ($rule->sf_qtype == 1?$rule->astext:$rule->aftext);?>"</td>
								<td><a href="javascript: void(0);" onclick="javascript:Delete_row(this); return false;" title="<?php echo JText::_('COM_SF_DELETE'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/publish_x.png"  border="0" alt="<?php echo JText::_('COM_SF_DELETE'); ?>"></a></td>
							</tr>
							<?php } elseif ($rule->sf_qtype == 9) {?>
							<tr >
								<td  width="375px;"> <?php echo JText::_('COM_SF_FOR_QUESTION'); ?> "<?php echo $rule->sf_qtext;?>"<input type="hidden" name="sf_hid_rule2_id[]" value="<?php echo $rule->bid;?>" /><input type="hidden" name="sf_hid_rule2_alt_id[]" value="<?php echo $rule->did;?>" /><input type="hidden" name="sf_hid_rule2_quest_id[]" value="<?php echo $rule->qid;?>" /></td>
								<td> <?php echo JText::_('COM_SF_AND_FOR_OPTION'); ?> "<?php echo $rule->qoption;?>"</td>
								<td> <?php echo JText::_('COM_SF_RANK_IS'); ?> "<?php echo $rule->aftext;?>"</td>
								<td><a href="javascript: void(0);" onclick="javascript:Delete_row(this); return false;" title="<?php echo JText::_('COM_SF_DELETE'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/publish_x.png"  border="0" alt="<?php echo JText::_('COM_SF_DELETE'); ?>"></a></td>
							</tr>	
							<?php }
							}?>
					</table>
					<table width="100%"  id="show_quest2">
					<tr>
						<td style="width:70px;"><?php echo JText::_('COM_SF_FOR_QUESTION'); ?> </td><td style="width:15px;"><?php echo $lists['quests3'];?></td>
						<td width="auto" colspan="2" ><div id="quest_show_div"></div>						
						</td>
					</tr>							
					<tr>
						<td colspan="4" style="text-align:left;"><input id="add_button" type="button" name="add" value="<?php echo JText::_('COM_SF_ADD'); ?>" onclick="javascript: if(!sf_is_loading) addRow();"  />
						</td>
					</tr>
					</table>
					<script type="text/javascript" language="javascript">
						function Delete_row(element) {
							var del_index = element.parentNode.parentNode.sectionRowIndex;
							var tbl_id = element.parentNode.parentNode.parentNode.parentNode.id;
							element.parentNode.parentNode.parentNode.deleteRow(del_index);							
						}
	
						function addRow(){
							var qtype = jQuery('#sf_qtype2').get(0).value;
							var sf_field_data_m = jQuery('#sf_field_data_m').get(0).options[jQuery('#sf_field_data_m').get(0).selectedIndex].value;
							var q_id = jQuery('#sf_quest_list3').get(0).options[jQuery('#sf_quest_list3').get(0).selectedIndex].value;
							if (qtype != 2 && qtype != 3) {
								if (qtype == 1)
									var sf_field_data_a = jQuery('#f_scale_data').get(0).options[jQuery('#f_scale_data').get(0).selectedIndex].value;
								else
									var sf_field_data_a = jQuery('#sf_field_data_a').get(0).options[jQuery('#sf_field_data_a').get(0).selectedIndex].value;
							} else {
								var sf_field_data_a = 0;
							}
							
							var tbl_elem = jQuery('#show_quest').get(0);
							var row = tbl_elem.insertRow(tbl_elem.rows.length);
									
							var cell1 = document.createElement("td");
							var cell2 = document.createElement("td");
							var cell3 = document.createElement("td");
							var cell4 = document.createElement("td");
							var input_hidden = document.createElement("input");
							var input_hidden2 = document.createElement("input");
							var input_hidden3 = document.createElement("input");
							input_hidden.type = "hidden";
							input_hidden.name = 'sf_hid_rule2_id[]';
							input_hidden.value = sf_field_data_m;
							
							input_hidden2.type = "hidden";
							input_hidden2.name = 'sf_hid_rule2_alt_id[]';
							input_hidden2.value = sf_field_data_a;
							
							input_hidden3.type = "hidden";
							input_hidden3.name = 'sf_hid_rule2_quest_id[]';
							input_hidden3.value = q_id;
							cell1.width = '375px';
							cell1.innerHTML = '<?php echo JText::_('COM_SF_FOR_QUESTION'); ?> "'+jQuery('#sf_quest_list3').get(0).options[jQuery('#sf_quest_list3').get(0).selectedIndex].innerHTML+'"';
							cell1.appendChild(input_hidden);
							cell1.appendChild(input_hidden2);
							cell1.appendChild(input_hidden3);
							if (qtype != 2 && qtype != 3) {
								cell2.innerHTML = ' <?php echo JText::_('COM_SF_AND_FOR_OPTION'); ?> "'+jQuery('#sf_field_data_m').get(0).options[jQuery('#sf_field_data_m').get(0).selectedIndex].innerHTML+'"';				
								if (qtype != 9) {
									if (qtype == 1)
										cell3.innerHTML = ' <?php echo JText::_('COM_SF_ANSWER_IS'); ?> "'+jQuery('#f_scale_data').get(0).options[jQuery('#f_scale_data').get(0).selectedIndex].innerHTML+'"';
									else
										cell3.innerHTML = ' <?php echo JText::_('COM_SF_ANSWER_IS'); ?> "'+jQuery('#sf_field_data_a').get(0).options[jQuery('#sf_field_data_a').get(0).selectedIndex].innerHTML+'"';
								}else {
									cell3.innerHTML = ' <?php echo JText::_('COM_SF_RANK_IS'); ?> "'+jQuery('#sf_field_data_a').get(0).options[jQuery('#sf_field_data_a').get(0).selectedIndex].innerHTML+'"';
								}
							} else {
								cell2.innerHTML = ' <?php echo JText::_('COM_SF_ANSWER_IS'); ?> "'+jQuery('#sf_field_data_m').get(0).options[jQuery('#sf_field_data_m').get(0).selectedIndex].innerHTML+'"';	
							}
							
							cell4.innerHTML = '<a href="javascript: void(0);" onclick="javascript:Delete_row(this); return false;" title="<?php echo JText::_('COM_SF_DELETE'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/publish_x.png"  border="0" alt="<?php echo JText::_('COM_SF_DELETE'); ?>"></a>';							
							row.appendChild(cell1);
							row.appendChild(cell2);							
							row.appendChild(cell3);
							row.appendChild(cell4);						
						}
						function processReq(http_request) {
							if (http_request.readyState == 4) {
								if ((http_request.status == 200)) {									
									var response = http_request.responseXML.documentElement;
									var text = '<?php echo JText::_('COM_SF_REQUEST_ERROR'); ?>';
									try {
										text = response.getElementsByTagName('data')[0].firstChild.data;
									} catch(e) {}
									jQuery('div#quest_show_div').html(text);							
								}
							}
						}
						function showOptions(val) {
							
							jQuery('input#add_button').get(0).style.display = 'none';
							
							jQuery('div#quest_show_div').html("<?php echo JText::_('COM_SF_PLEASE_WAIT_LOADING'); ?>");
							
							var http_request = false;
							if (window.XMLHttpRequest) { // Mozilla, Safari,...
								http_request = new XMLHttpRequest();
								if (http_request.overrideMimeType) {
									http_request.overrideMimeType('text/xml');
								}
							} else if (window.ActiveXObject) { // IE
								try { http_request = new ActiveXObject("Msxml2.XMLHTTP");
								} catch (e) {
									try { http_request = new ActiveXObject("Microsoft.XMLHTTP");
									} catch (e) {}
								}
							}
							if (!http_request) {
								return false;
							}

							http_request.onreadystatechange = function() { processReq(http_request); };

<?php 
$live_site = $GLOBALS['mosConfig_live_site'];
if (substr($_SERVER['HTTP_HOST'],0,4) == 'www.') {
	if (strpos($GLOBALS['mosConfig_live_site'], 'www.') !== false)
		$live_site = $GLOBALS['mosConfig_live_site'];
	else {
		$live_site = str_replace(substr($_SERVER['HTTP_HOST'],4), $_SERVER['HTTP_HOST'], $GLOBALS['mosConfig_live_site']);
	}
} else { 
	if (strpos($GLOBALS['mosConfig_live_site'], 'www.') !== false) 
		$live_site = str_replace('www.'.$_SERVER['HTTP_HOST'], $_SERVER['HTTP_HOST'], $GLOBALS['mosConfig_live_site']);
	else
		$live_site = $GLOBALS['mosConfig_live_site'];
}

$live_site_parts = parse_url($live_site); 

$live_url = $live_site_parts['scheme'].'://'.$live_site_parts['host'].(isset($live_site_parts['port'])?':'.$live_site_parts['port']:'').(isset($live_site_parts['path'])?$live_site_parts['path']:'/');

if ( substr($live_url, strlen($live_url)-1, 1) !== '/')
	$live_url .= '/';
?>

							http_request.open('GET', '<?php echo $live_url;?>administrator/index.php?no_html=1&option=com_surveyforce&task=get_options&rand=<?php echo time();?>&quest_id='+val, true);
							http_request.send(null);

							sf_is_loading = false;
						}				
						if (jQuery('#sf_quest_list3').get(0).options.length > 0)
							showOptions(jQuery('#sf_quest_list3').get(0).options[jQuery('#sf_quest_list3').get(0).selectedIndex].value);
						else {
							jQuery('table#show_quest').get(0).style.display = 'none';
							jQuery('table#show_quest2').get(0).style.display = 'none';
						}
						
						
					</script>
				</td>
			</tr>
		</table>
		<br />
		<?php if ($q_om_type == 1) {?>
		<table width="100%" class="adminform">
		<tr><td width="20%">
				<input type="radio" name="is_likert_predefined" value="1" <?php echo ($row->is_likert_predefined == 1)?'checked':''?>> <?php echo JText::_('COM_SF_USE_PREDEFINED_LIKERT_SCALE'); ?>:
			</td>
			<td><?php echo $lists['likert_scale']; ?>
			</td>
		</tr><tr><td>
			<input type="radio" name="is_likert_predefined" value="0" <?php echo ($row->is_likert_predefined == 0)?'checked':''?>> <?php echo JText::_('COM_SF_DEFINE_SCALE'); ?>:
			</td><td>
		</td></tr></table>
		<br>
		<small><?php echo JText::_('COM_SF_DOUBLECLICK_TO_AN_OPTION_TO_EDIT'); ?></small>
		<table class="adminlist" id="qfld_tbl_scale">
		<tr>
			<th width="20px" align="center">#</th>
			<th class="title" width="200px"><?php echo JText::_('COM_SF_SCALE_OPTIONS'); ?></th>
			<th width="20px" align="center" class="title"></th>
			<th width="20px" align="center" class="title"></th>
			<th width="20px" align="center" class="title"></th>
			<th width="auto"></th>
		</tr>
			<?php
			$k = 0; $ii = 1; $ind_last = count($lists['sf_fields_scale']);
			foreach ($lists['sf_fields_scale'] as $frow) { 
			?>	<input type="hidden" name="old_sf_hid_scale_id[]" value="<?php echo $frow->id?>">
				<tr class="<?php echo "row$k"; ?>">
					<td align="center"><?php echo $ii?></td>
					<td align="left"  ondblclick="edit_name(event, 'sf_hid_scale[]', 'sf_hid_scale_id[]');"><input type="hidden" name="sf_hid_scale[]" value="<?php echo $frow->stext?>"><input type="hidden" name="sf_hid_scale_id[]" value="<?php echo $frow->id?>">
						<?php echo $frow->stext?>
						
					</td>
					<td><a href="javascript: void(0);" onClick="javascript:Delete_tbl_row(this); return false;" title="<?php echo JText::_('COM_SF_DELETE'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/publish_x.png"  border="0" alt="<?php echo JText::_('COM_SF_DELETE'); ?>"></a></td>
					<td><?php if ($ii > 1) { ?><a href="javascript: void(0);" onClick="javascript:Up_tbl_row(this); return false;" title="<?php echo JText::_('COM_SF_MOVE_UP'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/uparrow.png"  border="0" alt="<?php echo JText::_('COM_SF_MOVE_UP'); ?>"></a><?php } ?></td>
					<td><?php if ($ii < $ind_last) { ?><a href="javascript: void(0);" onClick="javascript:Down_tbl_row(this); return false;" title="<?php echo JText::_('COM_SF_MOVE_DOWN'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/downarrow.png"  border="0" alt="<?php echo JText::_('COM_SF_MOVE_DOWN'); ?>"></a><?php } ?></td>
					<td></td>
				</tr>
			<?php
			$k = 1 - $k; $ii ++;
			 } ?>
		 </table><br>
		<div style="text-align:left; padding-left:30px ">
			<input id="new_scale" class="text_area" style="width:205px " type="text" name="new_scale">
			<input class="text_area" type="button" name="add_new_scale" style="width:70px " value="<?php echo JText::_('COM_SF_ADD'); ?>" onClick="javascript:Add_new_tbl_field('new_scale', 'qfld_tbl_scale', 'sf_hid_scale[]', 'sf_hid_scale_id[]');">
		</div>
		<br />
		<?php } ?>
		<small><?php echo JText::_('COM_SF_DOUBLE_CLICK_TO_EDIT_OPTION'); ?></small>
		<table class="adminlist" id="qfld_tbl">
		<tr>
			<th width="20px" align="center">#</th>
			<th class="title" width="200px"><?php echo JText::_('COM_SF_QUESTION_OPTIONS'); ?></th>
			<th width="20px" align="center" class="title"></th>
			<th width="20px" align="center" class="title"></th>
			<th width="20px" align="center" class="title"></th>
			<th width="auto"></th>
		</tr>
		<?php
		$k = 0; $ii = 1; $ind_last = count($lists['sf_fields']);
		$other_option = null;
		foreach ($lists['sf_fields'] as $frow) { 
			if (isset($frow->is_main) && $frow->is_main == 0) {
				$other_option = $frow;
				continue;
			}
		?>
			<input type="hidden" name="old_sf_hid_field_ids[]" value="<?php echo $frow->id?>"/>
			<tr class="<?php echo "row$k"; ?>">
				<td align="center"><?php echo $ii?></td>
				<td align="left" onDblClick="edit_name(event, 'sf_hid_fields[]', 'sf_hid_field_ids[]');"><input type="hidden" name="sf_hid_fields[]" value="<?php echo $frow->ftext?>"/><input type="hidden" name="sf_hid_field_ids[]" value="<?php echo $frow->id?>"/>
					<?php echo $frow->ftext?>
					
				</td>
				<td><a href="javascript: void(0);" onClick="javascript:Delete_tbl_row(this); return false;" title="<?php echo JText::_('COM_SF_DELETE'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/publish_x.png"  border="0" alt="<?php echo JText::_('COM_SF_DELETE'); ?>"></a></td>
				<td><?php if ($ii > 1) { ?><a href="javascript: void(0);" onClick="javascript:Up_tbl_row(this); return false;" title="<?php echo JText::_('COM_SF_MOVE_UP'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/uparrow.png"  border="0" alt="<?php echo JText::_('COM_SF_MOVE_UP'); ?>"></a><?php } ?></td>
				<td><?php if ($ii < $ind_last) { ?><a href="javascript: void(0);" onClick="javascript:Down_tbl_row(this); return false;" title="<?php echo JText::_('COM_SF_MOVE_DOWN'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/downarrow.png"  border="0" alt="<?php echo JText::_('COM_SF_MOVE_DOWN'); ?>"></a><?php } ?></td>
				<td></td>
			</tr>
		<?php
		$k = 1 - $k; $ii ++;
		 } ?>
		</table>
		<?php 
		if ($q_om_type == 3) { ?>
		<table width="100%" class="adminlist" >
			<tr>
				<td align="right" width="15%" valign="top"><?php echo JText::_('COM_SF_MAX_NUMBER_CHECKED_OPTIONS'); ?><br /><small><?php echo JText::_('COM_SF_SET_ZERO_IF_THERE_NO_MAXIMUM'); ?></small></td>
				<td><input type="text" class="text_area" size="4" name="sf_num_options" value="<?php echo $row->sf_num_options;?>"/>
				</td>
			</tr>
		</table>
		<?php }	
		
		if ($q_om_type == 2 || $q_om_type == 3) {?>
		<table width="100%" class="adminlist" >
		<tr class="<?php echo "row$k"; ?>">
			<td width="20px" align="center"><input type="checkbox" onchange="javascript:Add_fields_to_select();" name="other_option_cb" id="other_option_cb" value="2"  <?php echo (($other_option != null && !isset($lists['other_option'])) || (isset($lists['other_option']) && $lists['other_option'] == 1) ?'checked="checked"':'')?> /></td>
			<td align="left" colspan="5"><?php echo JText::_('COM_SF_OTHERS_OPTION'); ?> <input onkeyup="javascipt: Add_fields_to_select();"  class="text_area" style="width:120px " type="text" name="other_option" id="other_option" value="<?php echo ($other_option == null?'Other':$other_option->ftext)?>">		
			<input type="hidden" name="other_op_id" value="<?php echo ($other_option == null?'0':$other_option->id)?>"/>
			</td>
		</tr>
		</table>
		
		
		<?php 	
		}?>
		<br>
		<div style="text-align:left; padding-left:30px ">
			<input id="new_field" class="text_area" style="width:205px " type="text" name="new_field">
			<input class="text_area" type="button" name="add_new_field" style="width:70px " value="<?php echo JText::_('COM_SF_ADD'); ?>" onClick="javascript:Add_new_tbl_field('new_field', 'qfld_tbl', 'sf_hid_fields[]', 'sf_hid_field_ids[]');">
			<br/><br/>
			<input class="button" type="button" name="set_default" value="<?php echo JText::_('COM_SF_SET_DEFAULT'); ?>" onClick="javascript: <?php echo ($row->id > 0?"submitbutton('set_default');":"alert('".JText::_('COM_SF_YOU_CAN_SET_DEFAULT_ANSWERS')."');")?>">
			
		</div>
		<br />
				
		<table class="adminlist">
		<tr>
			<th width="20px" align="center">#</th>
			<th class="title" width="200px"><?php echo JText::_('COM_SF_QUESTION_RULES'); ?></th>
			<th width="20px" align="center" class="title"></th>
			<th width="20px" align="center" class="title"></th>
			<th width="20px" align="center" class="title"></th>
			<th width="auto"></th>
		</tr></table>
		<?php if ($q_om_type == 1 && $row->id || $q_om_type != 1) {?>
		<table class="adminlist" id="qfld_tbl_rule">
		<tr>
			<th width="2%" align="center">#</th>
			<?php if ($q_om_type == 1) { ?>
			<th class="title" width="22%"><?php echo JText::_('COM_SF_QUESTION_OPTIONS'); ?></th>
			<?php }?>
			<th class="title" width="14%"><?php echo JText::_('COM_SF_ANSWER'); ?></th>
			<th class="title" width="22%"><?php echo JText::_('COM_SF_QUESTION'); ?></th>
			<th class="title" width="22%"><?php echo JText::_('COM_SF_PRIORITY'); ?></th>
			<th width="2%" align="left" class="title"></th>
			<th width="2%" align="left" class="title"></th>
			<th width="auto"></th>
			<?php if ($q_om_type != 1) { ?>
			<th width="auto"></th>
			<?php }?>
		</tr>

			<?php
			$k = 0; $ii = 1; $ind_last = count($lists['sf_fields_rule']);
			foreach ($lists['sf_fields_rule'] as $rrow) { ?>
				<tr class="<?php echo "row$k"; ?>">
					<td align="center"><?php echo $ii?></td>
					<td align="left">
						<?php echo $rrow->ftext;?>
						<input type="hidden" name="sf_hid_rule[]" value="<?php echo $rrow->ftext?>">
					</td>
					<?php if ($q_om_type == 1) { ?>					
					<td align="left">
						<?php echo $rrow->alt_ftext;?>
						<input type="hidden" name="sf_hid_rule_alt[]" value="<?php echo $rrow->alt_ftext?>">
					</td>
					<?php }?>
					<td align="left">
						<?php echo $rrow->next_quest_id . ' - ' . (strlen(strip_tags($rrow->sf_qtext)) > 55? mb_substr(strip_tags($rrow->sf_qtext), 0, 55).'...': strip_tags($rrow->sf_qtext))?>
						<input type="hidden" name="sf_hid_rule_quest[]" value="<?php echo $rrow->next_quest_id?>">
					</td>
					<td>
						<input type="text" style="text-align:center" class="text_area" name="priority[]" size="3" value="<?php echo $rrow->priority?>" />
					</td>
					<td><a href="javascript: void(0);" onClick="javascript:Delete_tbl_row2(this); return false;" title="<?php echo JText::_('COM_SF_DELETE'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/publish_x.png"  border="0" alt="<?php echo JText::_('COM_SF_DELETE'); ?>"></a></td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<?php if ($q_om_type != 1) { ?>	
					<td>&nbsp;</td>
					<?php }?>
				</tr>
			<?php
			$k = 1 - $k; $ii ++;
			 } ?>
		 </table><br>	
		<div style="text-align:left; padding-left:30px ">
		<input type="checkbox" name="super_rule" value="1" <?php echo $lists['checked']; ?> /><?php echo JText::_('COM_SF_GO_TO_QUESTION'); ?> <?php echo $lists['quests2']; ?> <?php echo JText::_('COM_SF_NEXT_REGARDLESS_WHAT_ANSWER'); ?><br />
		<small><?php echo JText::_('COM_SF_TO_OVERRIDE_THIS_RULE'); ?></small>
		</div><br />
		<div style="text-align:left; padding-left:30px "> 	
		<?php if ($q_om_type == 1) { ?>		
		<?php echo JText::_('COM_SF_IF_FOR'); ?><?php echo $lists['sf_list_fields']; ?> <?php echo JText::_('COM_SF_ANSWER_IS'); ?> <?php echo $lists['sf_list_scale_fields']; ?>, 
		<?php } else {?>
		<?php echo JText::_('COM_SF_IF'); ?><?php echo JText::_('COM_SF_ANSWER_IS'); ?> <?php echo $lists['sf_list_fields']; ?>, 
		<?php }?>
		<?php echo JText::_('COM_SF_GO_TO_QUESTION'); ?><?php echo $lists['quests']; ?>, <?php echo JText::_('COM_SF_PRIORITY'); ?> <input type="text" style="text-align:center" class="text_area" name="new_priority" id="new_priority" size="3" value="0" />
		<?php if ($q_om_type == 1) { ?>
		<input class="text_area" type="button" name="add_new_rule"  value="<?php echo JText::_('COM_SF_ADD'); ?>" onClick="javascript:Add_new_tbl_field2('sf_field_list', 'sf_list_scale_fields', 'qfld_tbl_rule', 'sf_hid_rule[]', 'sf_quest_list', 'sf_hid_rule_quest[]', 'sf_hid_rule_alt[]');">
		<?php } else {?>
		<input class="text_area" type="button" name="add_new_rule"  value="<?php echo JText::_('COM_SF_ADD'); ?>" onClick="javascript:Add_new_tbl_field2('sf_field_list', 'qfld_tbl_rule', 'sf_hid_rule[]', 'sf_quest_list', 'sf_hid_rule_quest[]');">
		<?php }?>
		</div>
		<br />
		<?php } 
		else
			echo "<div align='left'>".JText::_('COM_SF_YOU_CAN_DEFINE_RULES_FOR_LIKERT_SCALE_AFTER_SAVING')."</div><br/>";
		?>
		<br/>
		<input type="hidden" name="sf_qtype" value="<?php echo $q_om_type; ?>" />
		<input type="hidden" name="option" value="com_surveyforce" />
		<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
		<input type="hidden" name="task" value="" />
		
		<input type="hidden" name="quest_id" value="<?php echo $row->id;?>" />
		<input type="hidden" name="red_task" value="<?php echo $task;?>" />
		</form>
		<?php
		EF_menu_footer();
	}

	function SF_editQ_Rankings( &$row, &$lists, $option, $q_rank_type ) {
		global $mosConfig_live_site, $task, $_MAMBOTS;

		mosCommonHTML::loadOverlib();
		if (_JOOMLA15) {
			jimport( 'joomla.html.editor' );
	
			$conf =& JFactory::getConfig();
			$editor = $conf->getValue('config.editor');
			$editorz =& JEditor::getInstance($editor);
			$editorz =& JFactory::getEditor();
		}
		
		survey_force_adm_html::SF_JS_getObj();
		EF_menu_header();
		?>
		<script language="javascript" type="text/javascript">
		<!--
		var field_name = '';
		var field_id = '';
		function Redeclare_element_inputs2(object) {			
			if (object.hasChildNodes()) {
				var children = object.childNodes;
				for (var i = 0; i < children.length; i++) {
					if (children[i].nodeName.toLowerCase() == 'input') {						
							var inp_name = children[i].name;
						
							var inp_value = children[i].value;
							object.removeChild(object.childNodes[i]);							
							var input_hidden = document.createElement("input");
							input_hidden.type = "hidden";
							input_hidden.name = inp_name;
							input_hidden.value = inp_value;
							object.appendChild(input_hidden);						
					}
				}
			}
		}

		function analyze_cat(){
			var element = getObj('inp_tmp');
			if (element){
				var parent = element.parentNode;
				
				var inpu_value = element.value;
				parent.removeChild(element);
				var  cat_id_sss = '0';
				if (parent.hasChildNodes()) {
					var children = parent.childNodes;
					for (var i = 0; i < children.length; i++) {
						if (children[i].nodeName.toLowerCase() == 'input') {
							if (children[i].name == field_id) {
								cat_id_sss = children[i].value;
							}
						}
					}
				}
				var input_cat2 = document.createElement("input");
				input_cat2.type = "hidden";
				input_cat2.name = field_name;
				input_cat2.value = inpu_value;
				var input_id2 = document.createElement("input");
				input_id2.type = "hidden";
				input_id2.name = field_id;
				input_id2.value = cat_id_sss;

				var span = document.createTextNode(inpu_value);
				parent.innerHTML = '';

				parent.appendChild(input_cat2);
				parent.appendChild(input_id2);
				parent.appendChild(span);				
			}
		}

		function edit_name(e, field, field2){
			analyze_cat();
			field_name = field;
			field_id = field2;			
					
			if (!e) { e = window.event;}
				var cat2=e.target?e.target:e.srcElement;			
			Redeclare_element_inputs2(cat2);
			var cat_name_value = '';
			var found = false;
			if (cat2.hasChildNodes()) {
				var children = cat2.childNodes;
				var children_count = children.length;
				for (var i = 0; i < children_count; i++) {
					if (children[i].nodeName.toLowerCase() == 'input') {						
						if (children[i].name == field_name) {
							cat_name_value = children[i].value;
							found = true;
						}
					}
				}
				if (!found) return; 
				for (var i = 0; i < children.length; i++) {
					if (children[i].nodeName.toLowerCase() != 'input') {						
						cat2.removeChild(cat2.childNodes[i]);
					}
				}
			}
			var input_cat3 = document.createElement("input");
			input_cat3.type = "text";
			input_cat3.id = "inp_tmp";
			input_cat3.name = "inp_tmp";
			
			input_cat3.value = cat_name_value;
			input_cat3.setAttribute("style","z-index:5000");
			if (window.addEventListener) { input_cat3.addEventListener('dblclick', analyze_cat, false);}else { input_cat3.attachEvent('ondblclick', analyze_cat );}
			cat2.appendChild(input_cat3);			
		}
		
		function ReAnalize_tbl_Rows( start_index, tbl_id ) {
			start_index = 1;
			var tbl_elem = getObj(tbl_id);
			if (tbl_elem.rows[start_index]) {
				var count = start_index; var row_k = 1 - start_index%2;
				for (var i=start_index; i<tbl_elem.rows.length; i++) {
					tbl_elem.rows[i].cells[0].innerHTML = count;					
					if (i > 1) { 
						tbl_elem.rows[i].cells[4].innerHTML = '<a href="javascript: void(0);" onClick="javascript:Up_tbl_row(this); return false;" title="<?php echo JText::_('COM_SF_MOVE_UP'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/uparrow.png"  border="0" alt="<?php echo JText::_('COM_SF_MOVE_UP'); ?>"></a>';
					} else { tbl_elem.rows[i].cells[4].innerHTML = ''; }
					if (i < (tbl_elem.rows.length - 1)) {
						tbl_elem.rows[i].cells[5].innerHTML = '<a href="javascript: void(0);" onClick="javascript:Down_tbl_row(this); return false;" title="<?php echo JText::_('COM_SF_MOVE_DOWN'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/downarrow.png"  border="0" alt="<?php echo JText::_('COM_SF_MOVE_DOWN'); ?>"></a>';;
					} else { tbl_elem.rows[i].cells[5].innerHTML = ''; }
					tbl_elem.rows[i].className = 'row'+row_k;
					count++;
					row_k = 1 - row_k;
				}
			}
			Add_fields_to_select();
		}
		
		function Redeclare_element_inputs(object) {
			if (object.hasChildNodes()) {
				var children = object.childNodes;
				for (var i = 0; i < children.length; i++) {
					if (children[i].nodeName.toLowerCase() == 'input') {
						var inp_name = children[i].name;
						var inp_value = children[i].value;
						object.removeChild(object.childNodes[i]);
						var input_hidden = document.createElement("input");
						input_hidden.type = "hidden";
						input_hidden.name = inp_name;
						input_hidden.value = inp_value;
						object.appendChild(input_hidden);
					}
				};
			};
		}


		function Delete_tbl_row(element) {
			var del_index = element.parentNode.parentNode.sectionRowIndex;
			var tbl_id = element.parentNode.parentNode.parentNode.parentNode.id;
			element.parentNode.parentNode.parentNode.deleteRow(del_index);
			ReAnalize_tbl_Rows(del_index - 1, tbl_id);
		}

		function Up_tbl_row(element) {
			if (element.parentNode.parentNode.sectionRowIndex > 1) {
				var sec_indx = element.parentNode.parentNode.sectionRowIndex;
				var table = element.parentNode.parentNode.parentNode;
				var tbl_id = table.parentNode.id;
				
				var cell2_tmp = element.parentNode.parentNode.cells[1].innerHTML;
				var cell2b_tmp = element.parentNode.parentNode.cells[2].innerHTML;
				
				var td_value = element.parentNode.parentNode.cells[1].childNodes[0].value;
				var td_id = element.parentNode.parentNode.cells[1].childNodes[1].value;		
				var name1 = element.parentNode.parentNode.cells[1].childNodes[0].name;
				var name2 = element.parentNode.parentNode.cells[1].childNodes[1].name;

				var tdb_value = element.parentNode.parentNode.cells[2].childNodes[0].value;
				var tdb_id = element.parentNode.parentNode.cells[2].childNodes[1].value;		
				var nameb1 = element.parentNode.parentNode.cells[2].childNodes[0].name;
				var nameb2 = element.parentNode.parentNode.cells[2].childNodes[1].name;
				
				element.parentNode.parentNode.parentNode.deleteRow(element.parentNode.parentNode.sectionRowIndex);
				var row = table.insertRow(sec_indx - 1);
				var cell1 = document.createElement("td");
				var cell2 = document.createElement("td");
				var cell2b = document.createElement("td");
				var cell3 = document.createElement("td");
				var cell4 = document.createElement("td");
				
				var span = document.createElement("span");
				var spanb = document.createElement("span");	
				
				var input_hidden = document.createElement("input");
				var input_hidden_id = document.createElement("input");				
				var input_hidden_alt = document.createElement("input");
				var input_hidden_alt_id = document.createElement("input");
				input_hidden.type = "hidden";
				input_hidden.name = name1;
				input_hidden.value = td_value;			
				input_hidden_id.type = "hidden";
				input_hidden_id.name = name2;
				input_hidden_id.value = td_id;		
					
				input_hidden_alt.type = "hidden";
				input_hidden_alt.name = nameb1;
				input_hidden_alt.value = tdb_value;
				input_hidden_alt_id.type = "hidden";
				input_hidden_alt_id.name = nameb2;
				input_hidden_alt_id.value = tdb_id;
				
				span.innerHTML = td_value;
				spanb.innerHTML = tdb_value;
			
				cell1.align = 'center';
				cell1.innerHTML = 0;
				cell2.align = 'left';
				cell2.appendChild(input_hidden);
				cell2.appendChild(input_hidden_id);
				cell2.appendChild(span);
				
				cell2b.align = 'left';
				cell2b.appendChild(input_hidden_alt);
				cell2b.appendChild(input_hidden_alt_id);
				cell2b.appendChild(spanb);
				
				cell3.innerHTML = '<a href="javascript: void(0);" onClick="javascript:Delete_tbl_row(this); return false;" title="<?php echo JText::_('COM_SF_DELETE'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/publish_x.png"  border="0" alt="<?php echo JText::_('COM_SF_DELETE'); ?>"></a>';
				cell4.innerHTML = '<a href="javascript: void(0);" onClick="javascript:Up_tbl_row(this); return false;" title="<?php echo JText::_('COM_SF_MOVE_UP'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/uparrow.png"  border="0" alt="<?php echo JText::_('COM_SF_MOVE_UP'); ?>"></a>';
				row.appendChild(cell1);
				row.appendChild(cell2);row.appendChild(cell2b);
				row.appendChild(cell3);
				row.appendChild(cell4);
				row.appendChild(document.createElement("td"));
				row.appendChild(document.createElement("td"));
				ReAnalize_tbl_Rows(sec_indx - 2, tbl_id);
			}
		}

		function Down_tbl_row(element) {
			if (element.parentNode.parentNode.sectionRowIndex < element.parentNode.parentNode.parentNode.rows.length - 1) {
				var sec_indx = element.parentNode.parentNode.sectionRowIndex;
				var table = element.parentNode.parentNode.parentNode;
				var tbl_id = table.parentNode.id;
				var cell2_tmp = element.parentNode.parentNode.cells[1].innerHTML;
				var cell2b_tmp = element.parentNode.parentNode.cells[2].innerHTML;
				
				var td_value = element.parentNode.parentNode.cells[1].childNodes[0].value;
				var td_id = element.parentNode.parentNode.cells[1].childNodes[1].value;		
				var name1 = element.parentNode.parentNode.cells[1].childNodes[0].name;
				var name2 = element.parentNode.parentNode.cells[1].childNodes[1].name;

				var tdb_value = element.parentNode.parentNode.cells[2].childNodes[0].value;
				var tdb_id = element.parentNode.parentNode.cells[2].childNodes[1].value;		
				var nameb1 = element.parentNode.parentNode.cells[2].childNodes[0].name;
				var nameb2 = element.parentNode.parentNode.cells[2].childNodes[1].name;
				
				element.parentNode.parentNode.parentNode.deleteRow(element.parentNode.parentNode.sectionRowIndex);
				var row = table.insertRow(sec_indx + 1);
				var cell1 = document.createElement("td");
				var cell2 = document.createElement("td");
				var cell2b = document.createElement("td");
				var cell3 = document.createElement("td");
				var cell4 = document.createElement("td");
				
				var span = document.createElement("span");
				var spanb = document.createElement("span");	
				
				var input_hidden = document.createElement("input");
				var input_hidden_id = document.createElement("input");				
				var input_hidden_alt = document.createElement("input");
				var input_hidden_alt_id = document.createElement("input");
				input_hidden.type = "hidden";
				input_hidden.name = name1;
				input_hidden.value = td_value;			
				input_hidden_id.type = "hidden";
				input_hidden_id.name = name2;
				input_hidden_id.value = td_id;		
					
				input_hidden_alt.type = "hidden";
				input_hidden_alt.name = nameb1;
				input_hidden_alt.value = tdb_value;
				input_hidden_alt_id.type = "hidden";
				input_hidden_alt_id.name = nameb2;
				input_hidden_alt_id.value = tdb_id;
				
				span.innerHTML = td_value;
				spanb.innerHTML = tdb_value;
				
				cell1.align = 'center';
				cell1.innerHTML = 0;
				cell2.align = 'left';
				cell2.appendChild(input_hidden);
				cell2.appendChild(input_hidden_id);
				cell2.appendChild(span);
				
				cell2b.align = 'left';
				cell2b.appendChild(input_hidden_alt);
				cell2b.appendChild(input_hidden_alt_id);
				cell2b.appendChild(spanb);
				
				cell3.innerHTML = '<a href="javascript: void(0);" onClick="javascript:Delete_tbl_row(this); return false;" title="<?php echo JText::_('COM_SF_DELETE'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/publish_x.png"  border="0" alt="<?php echo JText::_('COM_SF_DELETE'); ?>"></a>';
				cell4.innerHTML = '<a href="javascript: void(0);" onClick="javascript:Up_tbl_row(this); return false;" title="<?php echo JText::_('COM_SF_MOVE_UP'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/uparrow.png"  border="0" alt="<?php echo JText::_('COM_SF_MOVE_UP'); ?>"></a>';
				row.appendChild(cell1);
				row.appendChild(cell2);
				row.appendChild(cell2b);
				row.appendChild(cell3);
				row.appendChild(cell4);
				row.appendChild(document.createElement("td"));
				row.appendChild(document.createElement("td"));
				ReAnalize_tbl_Rows(sec_indx, tbl_id);
			}
		}

		function Add_new_tbl_field(elem_field, tbl_id, field_name, field_id, elem_field2, field_name2, field_id2) {
			var new_element_txt = getObj(elem_field).value;
			var new_element_txt_alt = getObj(elem_field2).value;
			if (TRIM_str(new_element_txt) == '' || TRIM_str(new_element_txt_alt) == '') {
				alert("<?php echo JText::_('COM_SF_PLEASE_ENTER_TEXT_TO_FIELD'); ?>");return;
			}
			getObj(elem_field).value = '';
			getObj(elem_field2).value = '';
			var tbl_elem = getObj(tbl_id);
			var row = tbl_elem.insertRow(tbl_elem.rows.length);			
			var cell1 = document.createElement("td");
			var cell2 = document.createElement("td");
			var cell2b = document.createElement("td");
			var cell3 = document.createElement("td");
			var cell4 = document.createElement("td");
			var cell5 = document.createElement("td");
			var cell6 = document.createElement("td");
		
			var span = document.createElement("span");
			var spanb = document.createElement("span");
				
			var input_hidden = document.createElement("input");
			var input_hidden_id = document.createElement("input");
			input_hidden.type = "hidden";
			input_hidden.name = field_name;
			input_hidden.value = new_element_txt;			
			input_hidden_id.type = "hidden";
			input_hidden_id.name = field_id;
			input_hidden_id.value = 0;			
			var input_hidden_alt = document.createElement("input");
			var input_hidden_alt_id = document.createElement("input");
			input_hidden_alt.type = "hidden";
			input_hidden_alt.name = field_name2;
			input_hidden_alt.value = new_element_txt_alt;
			input_hidden_alt_id.type = "hidden";
			input_hidden_alt_id.name = field_id2;
			input_hidden_alt_id.value = 0;
			cell1.align = 'center';
			cell1.innerHTML = 0;
			cell2.setAttribute("ondblclick", "edit_name(event, '"+field_name+"','"+field_id+"');"); 
			span.innerHTML = new_element_txt;
			cell2.appendChild(input_hidden);
			cell2.appendChild(input_hidden_id);
			cell2.appendChild(span);
			cell2b.setAttribute("ondblclick", "edit_name(event, '"+field_name2+"','"+field_id2+"');"); 
			spanb.innerHTML = new_element_txt_alt;
			cell2b.appendChild(input_hidden_alt);
			cell2b.appendChild(input_hidden_alt_id);
			cell2b.appendChild(spanb);
			cell3.innerHTML = '<a href="javascript: void(0);" onClick="javascript:Delete_tbl_row(this); return false;" title="<?php echo JText::_('COM_SF_DELETE'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/publish_x.png"  border="0" alt="<?php echo JText::_('COM_SF_DELETE'); ?>"></a>';
			cell4.innerHTML = '<a href="javascript: void(0);" onClick="javascript:Up_tbl_row(this); return false;" title="<?php echo JText::_('COM_SF_MOVE_UP'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/uparrow.png"  border="0" alt="<?php echo JText::_('COM_SF_MOVE_UP'); ?>"></a>';
			cell5.innerHTML = '';
			row.appendChild(cell1);
			row.appendChild(cell2);
			row.appendChild(cell2b);
			row.appendChild(cell3);
			row.appendChild(cell4);
			row.appendChild(cell5);
			row.appendChild(cell6);
			ReAnalize_tbl_Rows(tbl_elem.rows.length - 2, tbl_id);
		}

		function Delete_tbl_row2(element) {
			var del_index = element.parentNode.parentNode.sectionRowIndex;
			var tbl_id = element.parentNode.parentNode.parentNode.parentNode.id;
			element.parentNode.parentNode.parentNode.deleteRow(del_index);
			ReAnalize_tbl_Rows2(del_index - 1, tbl_id);
		}

		function ReAnalize_tbl_Rows2( start_index, tbl_id ) {
			start_index = 1;
			var tbl_elem = getObj(tbl_id);
			if (tbl_elem.rows[start_index]) {
				var count = start_index; var row_k = 1 - start_index%2;
				for (var i=start_index; i<tbl_elem.rows.length; i++) {
					tbl_elem.rows[i].cells[0].innerHTML = count;
					Redeclare_element_inputs(tbl_elem.rows[i].cells[1]);
					Redeclare_element_inputs(tbl_elem.rows[i].cells[2]);
					tbl_elem.rows[i].className = 'row'+row_k;
					count++;
					row_k = 1 - row_k;
				}
			}
		}

		function Add_new_tbl_field2(elem_field, elem_field3, tbl_id, field_name, elem_field2, field_name2, field_name3) {
			var new_element_txt = getObj(elem_field).value;
			var new_element_txt2 = getObj(elem_field2).value;
			var new_element_txt3 = getObj(elem_field3).value;
			if (getObj(elem_field2).selectedIndex < 0 )
				return;
			if (getObj(elem_field3).selectedIndex < 0 )
				return;
			var new_element_txt2_text = getObj(elem_field2).options[getObj(elem_field2).selectedIndex].innerHTML;
			var new_element_txt3_text = getObj(elem_field3).options[getObj(elem_field3).selectedIndex].innerHTML;
			if (TRIM_str(new_element_txt) == '') {
				alert("<?php echo JText::_('COM_SF_PLEASE_ENTER_TEXT_TO_FIELD'); ?>");return;
			}
			var tbl_elem = getObj(tbl_id);
			var row = tbl_elem.insertRow(tbl_elem.rows.length);
			var cell1 = document.createElement("td");
			var cell2 = document.createElement("td");
			var cell2b = document.createElement("td");
			var cell2bb = document.createElement("td");
			var cell3 = document.createElement("td");
			var cell3b = document.createElement("td");
			var cell4 = document.createElement("td");
			var cell5 = document.createElement("td");
			var input_hidden = document.createElement("input");
			input_hidden.type = "hidden";
			input_hidden.name = field_name;
			input_hidden.value = new_element_txt;
			var input_hidden2 = document.createElement("input");
			input_hidden2.type = "hidden";
			input_hidden2.name = field_name2;
			input_hidden2.value = new_element_txt2;
			var input_hidden3 = document.createElement("input");
			input_hidden3.type = "hidden";
			input_hidden3.name = field_name3;
			input_hidden3.value = new_element_txt3;
			cell1.align = 'center';
			cell1.innerHTML = 0;
			cell2.innerHTML = new_element_txt;
			cell2.appendChild(input_hidden);
			
			cell2b.innerHTML = new_element_txt2_text;
			cell2b.appendChild(input_hidden2);
			
			cell2bb.innerHTML = new_element_txt3_text;
			cell2bb.appendChild(input_hidden3);
			cell3.innerHTML = '<a href="javascript: void(0);" onClick="javascript:Delete_tbl_row2(this); return false;" title="<?php echo JText::_('COM_SF_DELETE'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/publish_x.png"  border="0" alt="<?php echo JText::_('COM_SF_DELETE'); ?>"></a>';
			cell3b.innerHTML = '<input type="text" style="text-align:center" class="text_area" name="priority[]" size="3" value="'+getObj('new_priority').value+'" />';
			getObj('new_priority').value = '0';
			row.appendChild(cell3);
			cell4.innerHTML = '';
			cell5.innerHTML = '';
			row.appendChild(cell1);
			row.appendChild(cell2);
			row.appendChild(cell2bb);
			row.appendChild(cell2b);
			row.appendChild(cell3b);
			row.appendChild(cell3);			
			row.appendChild(cell4);
			row.appendChild(cell5);
			ReAnalize_tbl_Rows2(tbl_elem.rows.length - 2, tbl_id);
		}

		function Add_fields_to_select() {
			var tbl_elem = getObj('qfld_tbl');
			var start_index = 1;
			document.adminForm.sf_field_list.options.length = 0;
			var option_ind = 0;
			if (tbl_elem.rows[start_index]) {
				var count = start_index;
				for (var i=start_index; i<tbl_elem.rows.length; i++) {
					if (tbl_elem.rows[i].cells[1].hasChildNodes()) {
						var children = tbl_elem.rows[i].cells[1].childNodes;
						for (var ii = 0; ii < children.length; ii++) {
							if (children[ii].nodeName.toLowerCase() == 'input' && children[ii].name == 'sf_fields[]') {
								document.adminForm.sf_field_list.options[option_ind] = new Option(children[ii].value, children[ii].value );
								option_ind++;
							}
						}
					}					
				}
			}
			
			var start_index = 1;
			document.adminForm.sf_alt_field_list.options.length = 0;
			var option_ind = 0;
			if (tbl_elem.rows[start_index]) {
				var count = start_index;
				for (var i=start_index; i<tbl_elem.rows.length; i++) {
					if (tbl_elem.rows[i].cells[2].hasChildNodes()) {
						var children = tbl_elem.rows[i].cells[2].childNodes;
						for (var ii = 0; ii < children.length; ii++) {
							if (children[ii].nodeName.toLowerCase() == 'input' && children[ii].name == 'sf_alt_fields[]') {
								document.adminForm.sf_alt_field_list.options[option_ind] = new Option(children[ii].value, children[ii].value );
								option_ind++;
							}
						}
					}					
				}
			}
		}

		Joomla.submitbutton = function(pressbutton) {
			var form = document.adminForm;
			if (pressbutton == 'cancel_quest') {
				submitform( pressbutton );
				return;
			}
			fillTextArea();
			// do field validation
			if (false && form.sf_qtext.value == ""){
				alert( "<?php echo JText::_('COM_SF_QUESTION_MUST_HAVE_TEXT'); ?>" );
			} else {
				submitform( pressbutton );
			}
		}
		
		function fillTextArea () {
			var form = document.adminForm;
			<?php 		
			//print WYSIWYG editor function name to save content to textarea
			$script = '';
			if (_JOOMLA15) {
				$script = $editorz->save('sf_qtext');
			}
			else {
				$results = $_MAMBOTS->trigger( 'onGetEditorContents', array( 'editor2', 'sf_qtext' ) );
				if (trim($results[0])) {
					$script = $results[0];
				}
			}
			if (trim($script))
				echo $script;
			?>
			
			return true;
		}

		//-->
		</script>
		
		<form action="index.php" method="post" name="adminForm">
		<?php if (!class_exists('JToolBarHelper')) { ?>

		<table class="adminheading">
		<tr>
			<th>
			<?php echo _SURVEY_FORCE_COMP_NAME?>:
			<small>
			<?php echo $row->id ? JText::_('COM_SF_EDIT_QUESTION') : JText::_('COM_SF_NEW_QUESTION'); echo " (".JText::_('COM_SF_RANKING')."(($q_rank_type == 5)?".JText::_('COM_SF_DROPDOWN').":".JText::_('COM_SF_DRAG_N_DROP')."))";?>
			</small>
			</th>
		</tr>
		</table>
		<?php } else { 
			JToolBarHelper::title( ($row->id ? JText::_('COM_SF_EDIT_QUESTION') : JText::_('COM_SF_NEW_QUESTION')).' ('.JText::_('COM_SF_RANKING').(($q_rank_type == 5)?JText::_('COM_SF_DROPDOWN'):JText::_('COM_SF_DRAG_N_DROP')).')', 'static.png' );
		}?>
		
		<table width="100%" class="adminform">
			<tr>
				<th colspan="2"><?php echo JText::_('COM_SF_QUESTION_DETAILS')?></th>
			</tr>
			<tr>
				<td align="right" width="20%" valign="top"><?php echo JText::_('COM_SF_QUESTION_TEXT')?></td>
				<td><?php 
				if (_JOOMLA15) {
					echo $editorz->display('sf_qtext', $row->sf_qtext, '100%;', '250', '40', '20', array('pagebreak', 'readmore'));
				}
				else {
					editorArea( 'editor2', $row->sf_qtext, 'sf_qtext', '100%;', '250', '40', '20' ) ;
				}

				?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('COM_SF_SURVEY')?>:
				</td>
				<td>
				<?php echo $lists['survey']; ?>
				</td>
			</tr>
			<tr>
				<td><?php echo JText::_('COM_SF_IMPORTANCE_SCALE')?>:</td><td><?php echo $lists['impscale'];?><input type="button" class="text_area" name="Define new" onClick="javascript: fillTextArea();document.adminForm.task.value='add_iscale_from_quest';document.adminForm.submit();" value="<?php echo JText::_('COM_SF_DEFINE_NEW')?>"></td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('COM_SF_PUBLISHED')?>:
				</td>
				<td>
					<?php echo $lists['published']; ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('COM_SF_ORDERING')?>
				</td>
				<td>
					<?php echo $lists['ordering']; ?>
				</td>
			</tr> 
			<?php if ( $lists['sf_section_id'] != null ) {?>
			<tr>
				<td><?php echo JText::_('COM_SF_SECTION')?>:</td><td><?php echo $lists['sf_section_id'];?></td>
			</tr> 
			<?php }?>
			<tr>
				<td>
					<?php echo JText::_('COM_SF_COMPULSORY_QUESTION'); ?>:
				</td>
				<td>
					<?php echo $lists['compulsory']; ?>
				</td>
			</tr>
			<?php if (!($row->id > 0)) {?>
			<tr>
				<td>
					<?php echo JText::_('COM_SF_INSERT_PAGE_BREAK_AFTER_QUESTION'); ?>
				</td>
				<td>
					<?php echo $lists['insert_pb']; ?>
				</td>
			</tr>
			<?php } ?> 
			<tr>
				<td>
					<?php echo JText::_('COM_SF_HIDDEN_BY_DEFAULT'); ?>
				</td>
				<td>
					<?php echo $lists['sf_default_hided']; ?>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<script type="text/javascript" src="<?php echo $mosConfig_live_site;?>/administrator/components/com_surveyforce/js/jquery.pack.js"></script>
					<script type="text/javascript" language="javascript" >
						jQuery.noConflict();
						var sf_is_loading = false;
					</script>
					<table class="adminlist" id="show_quest">
					<tr>
						<th class="title" colspan="4"><?php echo JText::_('COM_SF_DONT_SHOW_QUESTION'); ?></th>
					</tr>
					<?php if (is_array($lists['quest_show']) && count($lists['quest_show'])) 
							foreach($lists['quest_show'] as $rule) {
								if ( ($rule->sf_qtype == 2) || ($rule->sf_qtype == 3) ) {
							?>
							
							<tr>
								<td width="375px;"> <?php echo JText::_('COM_SF_FOR_QUESTION'); ?> "<?php echo $rule->sf_qtext;?>" <input type="hidden" name="sf_hid_rule2_id[]" value="<?php echo $rule->bid;?>" /><input type="hidden" name="sf_hid_rule2_alt_id[]" value="<?php echo $rule->did;?>" /><input type="hidden" name="sf_hid_rule2_quest_id[]" value="<?php echo $rule->qid;?>" /></td>
								<td colspan="2"> <?php echo JText::_('COM_SF_ANSWER_IS'); ?> "<?php echo $rule->qoption;?>"</td>
								<td><a href="javascript: void(0);" onclick="javascript:Delete_row(this); return false;" title="<?php echo JText::_('COM_SF_DELETE'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/publish_x.png"  border="0" alt="<?php echo JText::_('COM_SF_DELETE'); ?>"></a></td>
							</tr>
							<?php } elseif (($rule->sf_qtype == 1) || ($rule->sf_qtype == 5) || ($rule->sf_qtype == 6)) {?>
							<tr>
								<td  width="375px;"> <?php echo JText::_('COM_SF_FOR_QUESTION'); ?> "<?php echo $rule->sf_qtext;?>"<input type="hidden" name="sf_hid_rule2_id[]" value="<?php echo $rule->bid;?>" /><input type="hidden" name="sf_hid_rule2_alt_id[]" value="<?php echo ($rule->sf_qtype == 1?$rule->sdid:$rule->fdid);?>" /><input type="hidden" name="sf_hid_rule2_quest_id[]" value="<?php echo $rule->qid;?>" /></td>
								<td> <?php echo JText::_('COM_SF_AND_FOR_OPTION'); ?> "<?php echo $rule->qoption;?>"</td>
								<td> <?php echo JText::_('COM_SF_ANSWER_IS'); ?> "<?php echo ($rule->sf_qtype == 1?$rule->astext:$rule->aftext);?>"</td>
								<td><a href="javascript: void(0);" onclick="javascript:Delete_row(this); return false;" title="<?php echo JText::_('COM_SF_DELETE'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/publish_x.png"  border="0" alt="<?php echo JText::_('COM_SF_DELETE'); ?>"></a></td>
							</tr>
							<?php } elseif ($rule->sf_qtype == 9) {?>
							<tr >
								<td  width="375px;"> <?php echo JText::_('COM_SF_FOR_QUESTION'); ?> "<?php echo $rule->sf_qtext;?>"<input type="hidden" name="sf_hid_rule2_id[]" value="<?php echo $rule->bid;?>" /><input type="hidden" name="sf_hid_rule2_alt_id[]" value="<?php echo $rule->did;?>" /><input type="hidden" name="sf_hid_rule2_quest_id[]" value="<?php echo $rule->qid;?>" /></td>
								<td> <?php echo JText::_('COM_SF_AND_FOR_OPTION'); ?> "<?php echo $rule->qoption;?>"</td>
								<td> <?php echo JText::_('COM_SF_RANK_IS'); ?> "<?php echo $rule->aftext;?>"</td>
								<td><a href="javascript: void(0);" onclick="javascript:Delete_row(this); return false;" title="<?php echo JText::_('COM_SF_DELETE'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/publish_x.png"  border="0" alt="<?php echo JText::_('COM_SF_DELETE'); ?>"></a></td>
							</tr>	
							<?php }
							}?>
					</table>
					<table width="100%"  id="show_quest2">
					<tr>
						<td style="width:70px;"><?php echo JText::_('COM_SF_FOR_QUESTION'); ?> </td><td style="width:15px;"><?php echo $lists['quests3'];?></td>
						<td width="auto" colspan="2" ><div id="quest_show_div"></div>						
						</td>
					</tr>							
					<tr>
						<td colspan="4" style="text-align:left;"><input id="add_button" type="button" name="add" value="<?php echo JText::_('COM_SF_ADD'); ?>" onclick="javascript: if(!sf_is_loading) addRow();"  />
						</td>
					</tr>
					</table>
					<script type="text/javascript" language="javascript">
						function Delete_row(element) {
							var del_index = element.parentNode.parentNode.sectionRowIndex;
							var tbl_id = element.parentNode.parentNode.parentNode.parentNode.id;
							element.parentNode.parentNode.parentNode.deleteRow(del_index);							
						}
	
						function addRow(){
							var qtype = jQuery('#sf_qtype2').get(0).value;
							var sf_field_data_m = jQuery('#sf_field_data_m').get(0).options[jQuery('#sf_field_data_m').get(0).selectedIndex].value;
							var q_id = jQuery('#sf_quest_list3').get(0).options[jQuery('#sf_quest_list3').get(0).selectedIndex].value;
							if (qtype != 2 && qtype != 3){
								if (qtype == 1)
									var sf_field_data_a = jQuery('#f_scale_data').get(0).options[jQuery('#f_scale_data').get(0).selectedIndex].value;
								else
									var sf_field_data_a = jQuery('#sf_field_data_a').get(0).options[jQuery('#sf_field_data_a').get(0).selectedIndex].value;
							}else{
								var sf_field_data_a = 0;
							}
							
							var tbl_elem = jQuery('#show_quest').get(0);
							var row = tbl_elem.insertRow(tbl_elem.rows.length);
									
							var cell1 = document.createElement("td");
							var cell2 = document.createElement("td");
							var cell3 = document.createElement("td");
							var cell4 = document.createElement("td");
							var input_hidden = document.createElement("input");
							var input_hidden2 = document.createElement("input");
							var input_hidden3 = document.createElement("input");
							input_hidden.type = "hidden";
							input_hidden.name = 'sf_hid_rule2_id[]';
							input_hidden.value = sf_field_data_m;
							
							input_hidden2.type = "hidden";
							input_hidden2.name = 'sf_hid_rule2_alt_id[]';
							input_hidden2.value = sf_field_data_a;
							
							input_hidden3.type = "hidden";
							input_hidden3.name = 'sf_hid_rule2_quest_id[]';
							input_hidden3.value = q_id;
							cell1.width = '375px';
							cell1.innerHTML = '<?php echo JText::_('COM_SF_FOR_QUESTION'); ?> "'+jQuery('#sf_quest_list3').get(0).options[jQuery('#sf_quest_list3').get(0).selectedIndex].innerHTML+'"';
							cell1.appendChild(input_hidden);
							cell1.appendChild(input_hidden2);
							cell1.appendChild(input_hidden3);
							if (qtype != 2 && qtype != 3) {
								cell2.innerHTML = ' <?php echo JText::_('COM_SF_AND_FOR_OPTION'); ?> "'+jQuery('#sf_field_data_m').get(0).options[jQuery('#sf_field_data_m').get(0).selectedIndex].innerHTML+'"';				
								if (qtype != 9){
									if (qtype == 1)
										cell3.innerHTML = ' <?php echo JText::_('COM_SF_ANSWER_IS'); ?> "'+jQuery('#f_scale_data').get(0).options[jQuery('#f_scale_data').get(0).selectedIndex].innerHTML+'"';
									else
										cell3.innerHTML = ' <?php echo JText::_('COM_SF_ANSWER_IS'); ?> "'+jQuery('#sf_field_data_a').get(0).options[jQuery('#sf_field_data_a').get(0).selectedIndex].innerHTML+'"';
								}else { 
									cell3.innerHTML = ' <?php echo JText::_('COM_SF_RANK_IS'); ?> "'+jQuery('#sf_field_data_a').get(0).options[jQuery('#sf_field_data_a').get(0).selectedIndex].innerHTML+'"';
								}
							} else {
								cell2.innerHTML = ' <?php echo JText::_('COM_SF_ANSWER_IS'); ?> "'+jQuery('#sf_field_data_m').get(0).options[jQuery('#sf_field_data_m').get(0).selectedIndex].innerHTML+'"';	
							}
							
							cell4.innerHTML = '<a href="javascript: void(0);" onclick="javascript:Delete_row(this); return false;" title="<?php echo JText::_('COM_SF_DELETE'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/publish_x.png"  border="0" alt="<?php echo JText::_('COM_SF_DELETE'); ?>"></a>';							
							row.appendChild(cell1);
							row.appendChild(cell2);							
							row.appendChild(cell3);
							row.appendChild(cell4);						
						}
						function processReq(http_request) {
							if (http_request.readyState == 4) {
								if ((http_request.status == 200)) {									
									var response = http_request.responseXML.documentElement;
									var text = '<?php echo JText::_('COM_SF_REQUEST_ERROR'); ?>';
									try {
										text = response.getElementsByTagName('data')[0].firstChild.data;
									} catch(e) {}
									jQuery('div#quest_show_div').html(text);							
								}
							}
						}
						function showOptions(val) {
							
							jQuery('input#add_button').get(0).style.display = 'none';
							
							jQuery('div#quest_show_div').html("<?php echo JText::_('COM_SF_PLEASE_WAIT_LOADING'); ?>");
							
							var http_request = false;
							if (window.XMLHttpRequest) { // Mozilla, Safari,...
								http_request = new XMLHttpRequest();
								if (http_request.overrideMimeType) {
									http_request.overrideMimeType('text/xml');
								}
							} else if (window.ActiveXObject) { // IE
								try { http_request = new ActiveXObject("Msxml2.XMLHTTP");
								} catch (e) {
									try { http_request = new ActiveXObject("Microsoft.XMLHTTP");
									} catch (e) {}
								}
							}
							if (!http_request) {
								return false;
							}

							http_request.onreadystatechange = function() { processReq(http_request); };

<?php 
$live_site = $GLOBALS['mosConfig_live_site'];
if (substr($_SERVER['HTTP_HOST'],0,4) == 'www.') {
	if (strpos($GLOBALS['mosConfig_live_site'], 'www.') !== false)
		$live_site = $GLOBALS['mosConfig_live_site'];
	else {
		$live_site = str_replace(substr($_SERVER['HTTP_HOST'],4), $_SERVER['HTTP_HOST'], $GLOBALS['mosConfig_live_site']);
	}
} else { 
	if (strpos($GLOBALS['mosConfig_live_site'], 'www.') !== false) 
		$live_site = str_replace('www.'.$_SERVER['HTTP_HOST'], $_SERVER['HTTP_HOST'], $GLOBALS['mosConfig_live_site']);
	else
		$live_site = $GLOBALS['mosConfig_live_site'];
}

$live_site_parts = parse_url($live_site); 

$live_url = $live_site_parts['scheme'].'://'.$live_site_parts['host'].(isset($live_site_parts['port'])?':'.$live_site_parts['port']:'').(isset($live_site_parts['path'])?$live_site_parts['path']:'/');

if ( substr($live_url, strlen($live_url)-1, 1) !== '/')
	$live_url .= '/';
?>

							http_request.open('GET', '<?php echo $live_url;?>administrator/index.php?no_html=1&option=com_surveyforce&task=get_options&rand=<?php echo time();?>&quest_id='+val, true);
							http_request.send(null);

							sf_is_loading = false;
						}					
						if (jQuery('#sf_quest_list3').get(0).options.length > 0)
							showOptions(jQuery('#sf_quest_list3').get(0).options[jQuery('#sf_quest_list3').get(0).selectedIndex].value);
						else {
							jQuery('table#show_quest').get(0).style.display = 'none';
							jQuery('table#show_quest2').get(0).style.display = 'none';
						}
					</script>
				</td>
			</tr>
		</table>
		<br />
		<small><?php echo JText::_('COM_SF_DOUBLE_CLICK_TO_NAME'); ?></small>
		<table class="adminlist" id="qfld_tbl">
		<tr>
			<th width="20px" align="center">#</th>
			<th class="title" width="200px"><?php echo JText::_('COM_SF_NAME'); ?></th>
			<th class="title" width="200px"><?php echo JText::_('COM_SF_ALT_NAME'); ?></th>
			<th width="20px" align="center" class="title"></th>
			<th width="20px" align="center" class="title"></th>
			<th width="20px" align="center" class="title"></th>		
			<th></th>
		</tr>
		<?php
		$k = 0; $ii = 1; $ind_last = count($lists['sf_fields']);
		foreach ($lists['sf_fields'] as $frow) { ?>
			<input type="hidden" name="old_sf_field_ids[]" value="<?php echo $frow->id?>">
			<input type="hidden" name="old_sf_alt_field_ids[]" value="<?php echo $frow->alt_field_id?>">
			<tr class="<?php echo "row$k"; ?>">
				<td align="center"><?php echo $ii?></td>
				<td align="left" ondblclick="edit_name(event, 'sf_fields[]', 'sf_field_ids[]');"><input type="hidden" name="sf_fields[]" value="<?php echo $frow->ftext?>"><input type="hidden" name="sf_field_ids[]" value="<?php echo $frow->id?>">
					<?php echo $frow->ftext?>
					
				</td>
				<td align="left" ondblclick="edit_name(event, 'sf_alt_fields[]', 'sf_alt_field_ids[]');"><input type="hidden" name="sf_alt_fields[]" value="<?php echo $frow->alt_field_full?>"><input type="hidden" name="sf_alt_field_ids[]" value="<?php echo $frow->alt_field_id?>">
					<?php echo $frow->alt_field_full?>
					
				</td>
				<td><a href="" onClick="javascript:Delete_tbl_row(this); return false;" title="<?php echo JText::_('COM_SF_DELETE'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/publish_x.png"  border="0" alt="<?php echo JText::_('COM_SF_DELETE'); ?>"></a></td>
				<td><?php if ($ii > 1) { ?><a href="" onClick="javascript:Up_tbl_row(this); return false;" title="<?php echo JText::_('COM_SF_MOVE_UP'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/uparrow.png"  border="0" alt="<?php echo JText::_('COM_SF_MOVE_UP'); ?>"></a><?php } ?></td>
				<td><?php if ($ii < $ind_last) { ?><a href="" onClick="javascript:Down_tbl_row(this); return false;" title="<?php echo JText::_('COM_SF_MOVE_DOWN'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/downarrow.png"  border="0" alt="<?php echo JText::_('COM_SF_MOVE_DOWN'); ?>"></a><?php } ?></td>
				<td></td>
			</tr>
		<?php
		$k = 1 - $k; $ii ++;
		 } ?>
		</table><br>
		<div style="text-align:left; padding-left:30px ">
			<input id="new_field" class="text_area" style="width:205px " type="text" name="new_field">
			<input id="new_alt_field" class="text_area" style="width:205px " type="text" name="new_alt_field">
			<input class="text_area" type="button" name="add_new_field" style="width:70px " value="<?php echo JText::_('COM_SF_ADD'); ?>" onClick="javascript:Add_new_tbl_field('new_field', 'qfld_tbl', 'sf_fields[]', 'sf_field_ids[]', 'new_alt_field', 'sf_alt_fields[]', 'sf_alt_field_ids[]');">
			<br/><br/>
			<input class="button" type="button" name="set_default" value="<?php echo JText::_('COM_SF_SET_DEFAULT'); ?>" onClick="javascript: <?php echo ($row->id > 0?"submitbutton('set_default');":"alert(.JText::_('COM_SF_YOU_CAN_SET_DEFAULT_ANSWERS').);")?>">
		</div>
		<br />
		<table class="adminlist">
		<tr>
			<th width="20px" align="center">#</th>
			<th class="title" width="200px"><?php echo JText::_('COM_SF_QUESTION_RULES'); ?></th>
			<th width="20px" align="center" class="title"></th>
			<th width="20px" align="center" class="title"></th>
			<th width="20px" align="center" class="title"></th>
			<th width="auto"></th>
		</tr></table>
		<table class="adminlist" id="qfld_tbl_rule">
		<tr>
			<th width="2%" align="center">#</th>
			<th class="title" width="14%"><?php echo JText::_('COM_SF_NAME'); ?></th>
			<th class="title" width="14%"><?php echo JText::_('COM_SF_ANSWER'); ?></th>
			<th class="title" width="22%"><?php echo JText::_('COM_SF_QUESTION'); ?></th>
			<th class="title" width="14%"><?php echo JText::_('COM_SF_PRIORITY'); ?></th>
			<th width="2%" align="left" class="title"></th>
			<th width="2%" align="left" class="title"></th>
			<th width="auto"></th>
		</tr>

			<?php
			$k = 0; $ii = 1; $ind_last = count($lists['sf_fields_rule']);
			foreach ($lists['sf_fields_rule'] as $rrow) { ?>
				<tr class="<?php echo "row$k"; ?>">
					<td align="center"><?php echo $ii?></td>
					<td align="left">
						<?php echo $rrow->ftext;?>
						<input type="hidden" name="sf_hid_rule[]" value="<?php echo $rrow->ftext?>">
					</td>
					<td align="left">
						<?php echo $rrow->alt_ftext;?>
						<input type="hidden" name="sf_hid_rule_alt[]" value="<?php echo $rrow->alt_ftext?>">
					</td>
					<td align="left">
						<?php echo $rrow->next_quest_id . ' - ' . (strlen(strip_tags($rrow->sf_qtext)) > 50? mb_substr(strip_tags($rrow->sf_qtext), 0, 50).'...': strip_tags($rrow->sf_qtext))?>
						<input type="hidden" name="sf_hid_rule_quest[]" value="<?php echo $rrow->next_quest_id?>">
					</td>
					<td>
						<input type="text" style="text-align:center" class="text_area" name="priority[]" size="3" value="<?php echo $rrow->priority?>" />
					</td>
					<td><a href="javascript: void(0);" onClick="javascript:Delete_tbl_row2(this); return false;" title="<?php echo JText::_('COM_SF_DELETE'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/publish_x.png"  border="0" alt="<?php echo JText::_('COM_SF_DELETE'); ?>"></a></td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
			<?php
			$k = 1 - $k; $ii ++;
			 } ?>
		 </table><br/>
		 <div style="text-align:left; padding-left:30px ">
		<input type="checkbox" name="super_rule" value="1" <?php echo $lists['checked']; ?> /><?php echo JText::_('COM_SF_GO_TO_QUESTION'); echo $lists['quests2']; echo JText::_('COM_SF_NEXT_REGARDLESS_WHAT_ANSWER'); ?> <br />
		<small><?php echo JText::_('COM_SF_TO_OVERRIDE_THIS_RULE'); ?></small>
		</div><br />
		<div style="text-align:left; padding-left:30px "><?php echo JText::_('COM_SF_IF_FOR') . $lists['sf_list_fields'] . JText::_('COM_SF_ANSWER_IS'); ?>
			<?php echo $lists['sf_alt_field_list']; ?>, <?php echo JText::_('COM_SF_GO_TO_QUESTION'); ?>
			<?php echo $lists['quests']; ?>,<?php echo JText::_('COM_SF_S_PRIORITY'); ?> <input type="text" style="text-align:center" class="text_area" name="new_priority" id="new_priority" size="3" value="0" />
			<input class="text_area" type="button" name="add_new_rule"  value="<?php echo JText::_('COM_SF_ADD'); ?>" onClick="javascript:Add_new_tbl_field2('sf_field_list', 'sf_alt_field_list', 'qfld_tbl_rule', 'sf_hid_rule[]', 'sf_quest_list', 'sf_hid_rule_quest[]', 'sf_hid_rule_alt[]');">
		</div>
		<br />
		<input type="hidden" name="sf_qtype" value="<?php echo $q_rank_type; ?>" />
		<input type="hidden" name="option" value="com_surveyforce" />
		<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
		<input type="hidden" name="task" value="" />
		
		<input type="hidden" name="quest_id" value="<?php echo $row->id;?>" />
		<input type="hidden" name="red_task" value="<?php echo $task;?>" />
		</form>
		<?php
		EF_menu_footer();
	}
	
	function SF_editQ_Ranking( &$row, &$lists, $option ) {
		global $mosConfig_live_site, $task, $_MAMBOTS;

		mosCommonHTML::loadOverlib();
		if (_JOOMLA15) {
			jimport( 'joomla.html.editor' );
	
			$conf =& JFactory::getConfig();
			$editor = $conf->getValue('config.editor');
			$editorz =& JEditor::getInstance($editor);
			$editorz =& JFactory::getEditor();
		}
		
		survey_force_adm_html::SF_JS_getObj();
		EF_menu_header();
		?>
		<script language="javascript" type="text/javascript">
		<!--
		var field_name = '';
		var field_id = '';
		function Redeclare_element_inputs2(object) {			
			if (object.hasChildNodes()) {
				var children = object.childNodes;
				for (var i = 0; i < children.length; i++) {
					if (children[i].nodeName.toLowerCase() == 'input') {						
							var inp_name = children[i].name;
						
							var inp_value = children[i].value;
							object.removeChild(object.childNodes[i]);							
							var input_hidden = document.createElement("input");
							input_hidden.type = "hidden";
							input_hidden.name = inp_name;
							input_hidden.value = inp_value;
							object.appendChild(input_hidden);						
					}
				}
			}
		}

		function analyze_cat(){
			var element = getObj('inp_tmp');
			if (element){
				var parent = element.parentNode;
				
				var inpu_value = element.value;
				parent.removeChild(element);
				var  cat_id_sss = '0';
				if (parent.hasChildNodes()) {
					var children = parent.childNodes;
					for (var i = 0; i < children.length; i++) {
						if (children[i].nodeName.toLowerCase() == 'input') {
							if (children[i].name == field_id) {
								cat_id_sss = children[i].value;
							}
						}
					}
				}
				var input_cat2 = document.createElement("input");
				input_cat2.type = "hidden";
				input_cat2.name = field_name;
				input_cat2.value = inpu_value;
				var input_id2 = document.createElement("input");
				input_id2.type = "hidden";
				input_id2.name = field_id;
				input_id2.value = cat_id_sss;
				
				var span = document.createTextNode(inpu_value);
				parent.innerHTML = '';

				parent.appendChild(input_cat2);
				parent.appendChild(input_id2);
				parent.appendChild(span);				
			}
		}

		function edit_name(e, field, field2){
			analyze_cat();
			field_name = field;
			field_id = field2;			
					
			if (!e) { e = window.event;}
				var cat2=e.target?e.target:e.srcElement;			
			Redeclare_element_inputs2(cat2);
			var cat_name_value = '';
			var found = false;
			if (cat2.hasChildNodes()) {
				var children = cat2.childNodes;
				var children_count = children.length;
				for (var i = 0; i < children_count; i++) {
					if (children[i].nodeName.toLowerCase() == 'input') {						
						if (children[i].name == field_name) {
							cat_name_value = children[i].value;
							found = true;
						}
					}
				}
				if (!found) return; 
				for (var i = 0; i < children.length; i++) {
					if (children[i].nodeName.toLowerCase() != 'input') {						
						cat2.removeChild(cat2.childNodes[i]);
					}
				}
			}
			var input_cat3 = document.createElement("input");
			input_cat3.type = "text";
			input_cat3.id = "inp_tmp";
			input_cat3.name = "inp_tmp";
			
			input_cat3.value = cat_name_value;
			input_cat3.setAttribute("style","z-index:5000");
			if (window.addEventListener) { input_cat3.addEventListener('dblclick', analyze_cat, false);}else { input_cat3.attachEvent('ondblclick', analyze_cat );}
			cat2.appendChild(input_cat3);			
		}

		function ReAnalize_tbl_Rows( start_index, tbl_id ) {
			start_index = 1;
			var tbl_elem = getObj(tbl_id);			
			if (tbl_elem.rows[start_index]) {
				var count = start_index; var row_k = 1 - start_index%2;
				for (var i=start_index; i<tbl_elem.rows.length; i++) {
					tbl_elem.rows[i].cells[0].innerHTML = count;					
					if (i > 1) { 
						tbl_elem.rows[i].cells[3].innerHTML = '<a href="javascript: void(0);" onClick="javascript:Up_tbl_row(this); return false;" title="<?php echo JText::_('COM_SF_MOVE_UP'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/uparrow.png"  border="0" alt="<?php echo JText::_('COM_SF_MOVE_UP'); ?>"></a>';
					} else { tbl_elem.rows[i].cells[3].innerHTML = ''; }
					if (i < (tbl_elem.rows.length - 1)) {
						tbl_elem.rows[i].cells[4].innerHTML = '<a href="javascript: void(0);" onClick="javascript:Down_tbl_row(this); return false;" title="<?php echo JText::_('COM_SF_MOVE_DOWN'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/downarrow.png"  border="0" alt="<?php echo JText::_('COM_SF_MOVE_DOWN'); ?>"></a>';;
					} else { tbl_elem.rows[i].cells[4].innerHTML = ''; }
					tbl_elem.rows[i].className = 'row'+row_k;
					count++;
					row_k = 1 - row_k;
				}
			}
			Add_fields_to_select();
		}
		
		function Redeclare_element_inputs(object) {
			if (object.hasChildNodes()) {
				var children = object.childNodes;
				for (var i = 0; i < children.length; i++) {
					if (children[i].nodeName.toLowerCase() == 'input') {
						var inp_name = children[i].name;
						var inp_value = children[i].value;
						object.removeChild(object.childNodes[i]);
						var input_hidden = document.createElement("input");
						input_hidden.type = "hidden";
						input_hidden.name = inp_name;
						input_hidden.value = inp_value;
						object.appendChild(input_hidden);
					}
				};
			};
		}


		function Delete_tbl_row(element) {
			var del_index = element.parentNode.parentNode.sectionRowIndex;
			var tbl_id = element.parentNode.parentNode.parentNode.parentNode.id;
			element.parentNode.parentNode.parentNode.deleteRow(del_index);
			ReAnalize_tbl_Rows(del_index - 1, tbl_id);
		}

		function Up_tbl_row(element) {			
			if (element.parentNode.parentNode.sectionRowIndex > 1) {
				var sec_indx = element.parentNode.parentNode.sectionRowIndex;
				var table = element.parentNode.parentNode.parentNode;
				var tbl_id = table.parentNode.id;
				var cell2_tmp = element.parentNode.parentNode.cells[1].innerHTML;
				
				var td_value = element.parentNode.parentNode.cells[1].childNodes[0].value;
				var td_id = element.parentNode.parentNode.cells[1].childNodes[1].value;
				
				var name1 = element.parentNode.parentNode.cells[1].childNodes[0].name;
				var name2 = element.parentNode.parentNode.cells[1].childNodes[1].name;

				element.parentNode.parentNode.parentNode.deleteRow(element.parentNode.parentNode.sectionRowIndex);				
				var row = table.insertRow(sec_indx - 1);
				var cell1 = document.createElement("td");
				var cell2 = document.createElement("td");
				var cell3 = document.createElement("td");
				var cell4 = document.createElement("td");
				
				var input_hidden = document.createElement("input");
				var input_hidden2 = document.createElement("input");
				var span = document.createElement("span");

				cell1.align = 'center';
				cell1.innerHTML = 0;
				cell2.align = 'left';
				
				input_hidden.type = "hidden";				
				input_hidden.value = td_value;
				input_hidden.name = name1;
				input_hidden.setAttribute('name', name1);
				
				input_hidden2.type = "hidden";				
				input_hidden2.value = td_id;
				input_hidden2.name = name2;
				input_hidden2.setAttribute('name', name2);
				
				span.innerHTML = td_value;				
				cell2.appendChild(input_hidden);
				cell2.appendChild(input_hidden2);
				cell2.appendChild(span);
			
				cell3.innerHTML = '<a href="javascript: void(0);" onClick="javascript:Delete_tbl_row(this); return false;" title="<?php echo JText::_('COM_SF_DELETE'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/publish_x.png"  border="0" alt="<?php echo JText::_('COM_SF_DELETE'); ?>"></a>';
				cell4.innerHTML = '<a href="javascript: void(0);" onClick="javascript:Up_tbl_row(this); return false;" title="<?php echo JText::_('COM_SF_MOVE_UP'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/uparrow.png"  border="0" alt="<?php echo JText::_('COM_SF_MOVE_UP'); ?>"></a>';
				row.appendChild(cell1);
				row.appendChild(cell2);
				row.appendChild(cell3);
				row.appendChild(cell4);
				row.appendChild(document.createElement("td"));
				row.appendChild(document.createElement("td"));
				ReAnalize_tbl_Rows(sec_indx - 2, tbl_id);
			}
		}

		function Down_tbl_row(element) {
			if (element.parentNode.parentNode.sectionRowIndex < element.parentNode.parentNode.parentNode.rows.length - 1) {
				var sec_indx = element.parentNode.parentNode.sectionRowIndex;
				var table = element.parentNode.parentNode.parentNode;
				var tbl_id = table.parentNode.id;
				var cell2_tmp = element.parentNode.parentNode.cells[1].innerHTML;
				
				var td_value = element.parentNode.parentNode.cells[1].childNodes[0].value;
				var td_id = element.parentNode.parentNode.cells[1].childNodes[1].value;
				
				var name1 = element.parentNode.parentNode.cells[1].childNodes[0].name;
				var name2 = element.parentNode.parentNode.cells[1].childNodes[1].name;
				
				element.parentNode.parentNode.parentNode.deleteRow(element.parentNode.parentNode.sectionRowIndex);
				var row = table.insertRow(sec_indx + 1);
				var cell1 = document.createElement("td");
				var cell2 = document.createElement("td");
				var cell3 = document.createElement("td");
				var cell4 = document.createElement("td");
				
				var input_hidden = document.createElement("input");
				var input_hidden2 = document.createElement("input");
				var span = document.createElement("span");
				
				cell1.align = 'center';
				cell1.innerHTML = 0;
				cell2.align = 'left';
				
				input_hidden.type = "hidden";				
				input_hidden.value = td_value;
				input_hidden.name = name1;
				input_hidden.setAttribute('name', name1);
				
				input_hidden2.type = "hidden";				
				input_hidden2.value = td_id;
				input_hidden2.name = name2;
				input_hidden2.setAttribute('name', name2);
				
				span.innerHTML = td_value;				
				cell2.appendChild(input_hidden);
				cell2.appendChild(input_hidden2);
				cell2.appendChild(span);
				
				cell3.innerHTML = '<a href="javascript: void(0);" onClick="javascript:Delete_tbl_row(this); return false;" title="<?php echo JText::_('COM_SF_DELETE'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/publish_x.png"  border="0" alt="<?php echo JText::_('COM_SF_DELETE'); ?>"></a>';
				cell4.innerHTML = '<a href="javascript: void(0);" onClick="javascript:Up_tbl_row(this); return false;" title="<?php echo JText::_('COM_SF_MOVE_UP'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/uparrow.png"  border="0" alt="<?php echo JText::_('COM_SF_MOVE_UP'); ?>"></a>';
				row.appendChild(cell1);
				row.appendChild(cell2);
				row.appendChild(cell3);
				row.appendChild(cell4);
				row.appendChild(document.createElement("td"));
				row.appendChild(document.createElement("td"));
				ReAnalize_tbl_Rows(sec_indx, tbl_id);
			}
		}

		function Add_new_tbl_field(elem_field, tbl_id, field_name, field_name2) {
			var new_element_txt = getObj(elem_field).value;
			if (TRIM_str(new_element_txt) == '') {
				alert("<?php echo JText::_('COM_SF_PLEASE_ENTER_TEXT_TO_FIELD'); ?>");return;
			}
			getObj(elem_field).value = '';
			var tbl_elem = getObj(tbl_id);
			var row = tbl_elem.insertRow(tbl_elem.rows.length);			
					
			var cell1 = document.createElement("td");
			var cell2 = document.createElement("td");
			var cell3 = document.createElement("td");
			var cell4 = document.createElement("td");
			var cell5 = document.createElement("td");
			var cell6 = document.createElement("td");
			var input_hidden = document.createElement("input");
			var input_hidden2 = document.createElement("input");
			var span = document.createElement("span");
			
			input_hidden.type = "hidden";
			input_hidden.name = field_name;
			input_hidden.value = new_element_txt;
			
			input_hidden2.type = "hidden";
			input_hidden2.name = field_name2;
			input_hidden2.value = 0;
			cell1.align = 'center';
			cell1.innerHTML = 0;
			cell2.setAttribute("ondblclick", "javascript:edit_name(event, '"+field_name+"','"+field_name2+"');"); 
			span.innerHTML = new_element_txt;
			cell2.appendChild(input_hidden);
			cell2.appendChild(input_hidden2);
			cell2.appendChild(span);
			cell3.innerHTML = '<a href="javascript: void(0);" onClick="javascript:Delete_tbl_row(this); return false;" title="<?php echo JText::_('COM_SF_DELETE'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/publish_x.png"  border="0" alt="<?php echo JText::_('COM_SF_DELETE'); ?>"></a>';
			cell4.innerHTML = '<a href="javascript: void(0);" onClick="javascript:Up_tbl_row(this); return false;" title="<?php echo JText::_('COM_SF_MOVE_UP'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/uparrow.png"  border="0" alt="<?php echo JText::_('COM_SF_MOVE_UP'); ?>"></a>';
			cell5.innerHTML = '';
			row.appendChild(cell1);
			row.appendChild(cell2);
			row.appendChild(cell3);
			row.appendChild(cell4);
			row.appendChild(cell5);
			row.appendChild(cell6);
			ReAnalize_tbl_Rows(tbl_elem.rows.length - 2, tbl_id);
		}
		
		function Delete_tbl_row2(element) {
			var del_index = element.parentNode.parentNode.sectionRowIndex;
			var tbl_id = element.parentNode.parentNode.parentNode.parentNode.id;
			element.parentNode.parentNode.parentNode.deleteRow(del_index);
			ReAnalize_tbl_Rows2(del_index - 1, tbl_id);
		}

		function ReAnalize_tbl_Rows2( start_index, tbl_id ) {
			start_index = 1;
			var tbl_elem = getObj(tbl_id);
			if (tbl_elem.rows[start_index]) {
				var count = start_index; var row_k = 1 - start_index%2;//0;
				for (var i=start_index; i<tbl_elem.rows.length; i++) {
					tbl_elem.rows[i].cells[0].innerHTML = count;
					Redeclare_element_inputs(tbl_elem.rows[i].cells[1]);
					Redeclare_element_inputs(tbl_elem.rows[i].cells[2]);
					tbl_elem.rows[i].className = 'row'+row_k;
					count++;
					row_k = 1 - row_k;
				}
			}
		}
		
		function Add_new_tbl_field2(elem_field, elem_field3, tbl_id, field_name, elem_field2, field_name2, field_name3) {
			var new_element_txt = getObj(elem_field).value;
			var new_element_txt2 = getObj(elem_field2).value;
			var new_element_txt3 = getObj(elem_field3).value;
			if (getObj(elem_field2).selectedIndex < 0 )
				return;
			if (getObj(elem_field3).selectedIndex < 0 )
				return;
			var new_element_txt2_text = getObj(elem_field2).options[getObj(elem_field2).selectedIndex].innerHTML;
			var new_element_txt3_text = getObj(elem_field3).options[getObj(elem_field3).selectedIndex].innerHTML;
			if (TRIM_str(new_element_txt) == '') {
				alert("<?php echo JText::_('COM_SF_PLEASE_ENTER_TEXT_TO_FIELD'); ?>");return;
			}
			var tbl_elem = getObj(tbl_id);
			var row = tbl_elem.insertRow(tbl_elem.rows.length);
			var cell1 = document.createElement("td");
			var cell2 = document.createElement("td");
			var cell2b = document.createElement("td");
			var cell2bb = document.createElement("td");
			var cell3 = document.createElement("td");
			var cell3b = document.createElement("td");
			var cell4 = document.createElement("td");
			var cell5 = document.createElement("td");
			var input_hidden = document.createElement("input");
			input_hidden.type = "hidden";
			input_hidden.name = field_name;
			input_hidden.value = new_element_txt;
			var input_hidden2 = document.createElement("input");
			input_hidden2.type = "hidden";
			input_hidden2.name = field_name2;
			input_hidden2.value = new_element_txt2;
			var input_hidden3 = document.createElement("input");
			input_hidden3.type = "hidden";
			input_hidden3.name = field_name3;
			input_hidden3.value = new_element_txt3;
			cell1.align = 'center';
			cell1.innerHTML = 0;
			cell2.innerHTML = new_element_txt;
			cell2.appendChild(input_hidden);
			
			cell2b.innerHTML = new_element_txt2_text;
			cell2b.appendChild(input_hidden2);
			
			cell2bb.innerHTML = new_element_txt3_text;
			cell2bb.appendChild(input_hidden3);
			cell3.innerHTML = '<a href="javascript: void(0);" onClick="javascript:Delete_tbl_row2(this); return false;" title="<?php echo JText::_('COM_SF_DELETE'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/publish_x.png"  border="0" alt="<?php echo JText::_('COM_SF_DELETE'); ?>"></a>';
			cell3b.innerHTML = '<input type="text" style="text-align:center" class="text_area" name="priority[]" size="3" value="'+getObj('new_priority').value+'" />';
			getObj('new_priority').value = '0';
			row.appendChild(cell3);
			cell4.innerHTML = '';
			cell5.innerHTML = '';
			row.appendChild(cell1);
			row.appendChild(cell2);
			row.appendChild(cell2bb);
			row.appendChild(cell2b);
			row.appendChild(cell3b);
			row.appendChild(cell3);			
			row.appendChild(cell4);
			row.appendChild(cell5);
			ReAnalize_tbl_Rows2(tbl_elem.rows.length - 2, tbl_id);
		}
		
		function Add_fields_to_select() {
			
			var tbl_elem = getObj('qfld_tbl');
			var start_index = 1;
			document.adminForm.sf_field_list.options.length = 0;
			var option_ind = 0;
			if (tbl_elem.rows[start_index]) {
				var count = start_index;
				for (var i=start_index; i<tbl_elem.rows.length; i++) {
					if (tbl_elem.rows[i].cells[1].hasChildNodes()) {
						var children = tbl_elem.rows[i].cells[1].childNodes;
						for (var ii = 0; ii < children.length; ii++) {
							if (children[ii].nodeName.toLowerCase() == 'input' && children[ii].name == 'sf_hid_fields[]') {
								document.adminForm.sf_field_list.options[option_ind] = new Option(children[ii].value, children[ii].value );
								option_ind++;
							}
						};						
					};
					
				}
			}
			if (getObj('other_option_cb').checked){
				document.adminForm.sf_field_list.options[option_ind] = new Option(getObj('other_option').value, getObj('other_option').value );
				option_ind++;
			}
			
			tbl_elem = getObj('qfld_tbl_rank');
			start_index = 1;
			document.adminForm.sf_list_rank_fields.options.length = 0;
			option_ind = 0;
			if (tbl_elem.rows[start_index]) {
				var count = start_index;
				for (var i=start_index; i<tbl_elem.rows.length; i++) {
					if (tbl_elem.rows[i].cells[1].hasChildNodes()) {
						var children = tbl_elem.rows[i].cells[1].childNodes;
						for (var ii = 0; ii < children.length; ii++) {
							if (children[ii].nodeName.toLowerCase() == 'input' && children[ii].name == 'sf_hid_rank[]') {
								document.adminForm.sf_list_rank_fields.options[option_ind] = new Option(children[ii].value, children[ii].value );
								option_ind++;
							}
						};
					};
					
				}
			}
		}

		Joomla.submitbutton = function(pressbutton) {
			var form = document.adminForm;
			if (pressbutton == 'cancel_quest') {
				submitform( pressbutton );
				return;
			}
			
			fillTextArea();
			// do field validation
			if (false && form.sf_qtext.value == ""){
				alert("<?php echo JText::_('COM_SF_QUESTION_MUST_HAVE_TEXT'); ?>");
			} 
			else {
				submitform( pressbutton );
			}
		}
		function fillTextArea () {
			var form = document.adminForm;
			<?php 		
			//print WYSIWYG editor function name to save content to textarea
			$script = '';
			if (_JOOMLA15) {
				$script = $editorz->save('sf_qtext');
			}
			else {
				$results = $_MAMBOTS->trigger( 'onGetEditorContents', array( 'editor2', 'sf_qtext' ) );
				if (trim($results[0])) {
					$script = $results[0];
				}
			}
			if (trim($script))
				echo $script;
			?>
			
			return true;
		}

		//-->
		</script>
		
		<form action="index.php" method="post" name="adminForm">
		<?php if (!class_exists('JToolBarHelper')) { ?>

		<table class="adminheading">
		<tr>
			<th>
			<?php echo _SURVEY_FORCE_COMP_NAME?>:
			<small>
			<?php echo $row->id ? JText::_('COM_SF_NEW_QUESTION') : JText::_('COM_SF_EDIT_QUESTION'); echo "(".JText::_('COM_SF_RANKING').")";?>
			</small>
			</th>
		</tr>
		</table>
		<?php } else { 
			JToolBarHelper::title( ($row->id ? JText::_('COM_SF_NEW_QUESTION') : JText::_('COM_SF_EDIT_QUESTION'))."(".JText::_('COM_SF_RANKING').")", 'static.png' );
		}?> 
		
		<table width="100%" class="adminform">
			<tr>
				<th colspan="2"><?php echo JText::_('COM_SF_QUESTION_DETAILS'); ?></th>
			</tr>
			<tr>
				<td align="right" width="20%" valign="top"><?php echo JText::_('COM_SF_QUESTION_TEXT'); ?>:</td>
				<td><?php 
				if (_JOOMLA15) {
					echo $editorz->display('sf_qtext', $row->sf_qtext, '100%;', '250', '40', '20', array('pagebreak', 'readmore'));
				}
				else {
					editorArea( 'editor2', $row->sf_qtext, 'sf_qtext', '100%;', '250', '40', '20' ) ; 
				}

				?>
				</td>
			</tr>
			<tr>
				<td><?php echo JText::_('COM_SF_SURVEY'); ?>:</td><td><?php echo $lists['survey']; ?></td>
			</tr>
			<tr>
				<td><?php echo JText::_('COM_SF_IMPORTANCE_SCALE'); ?>:</td><td><?php echo $lists['impscale'];?><input type="button" class="text_area" name="Define new" onClick="javascript: fillTextArea();document.adminForm.task.value='add_iscale_from_quest';document.adminForm.submit();" value="<?php echo JText::_('COM_SF_DEFINE_NEW'); ?>"></td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('COM_SF_PUBLISHED'); ?>:
				</td>
				<td>
					<?php echo $lists['published']; ?>:
				</td>
			</tr>
			<tr>
				<td><?php echo JText::_('COM_SF_ORDERING'); ?></td><td><?php echo $lists['ordering']; ?></td>
			</tr> 
			<?php if ( $lists['sf_section_id'] != null ) {?>
			<tr>
				<td><?php echo JText::_('COM_SF_SECTION'); ?>:</td><td><?php echo $lists['sf_section_id'];?></td>
			</tr> 
			<?php }?>
			<tr>
				<td>
					<?php echo JText::_('COM_SF_COMPULSORY_QUESTION'); ?>:
				</td>
				<td>
					<?php echo $lists['compulsory']; ?>				
				</td>
			</tr> 
			<?php if (!($row->id > 0)) {?>
			<tr>
				<td>
					<?php echo JText::_('COM_SF_INSERT_PAGE_BREAK_AFTER_QUESTION'); ?>
				</td>
				<td>
					<?php echo $lists['insert_pb']; ?>
				</td>
			</tr>
			<?php } ?>
			<tr>
				<td>
					<?php echo JText::_('COM_SF_HIDDEN_BY_DEFAULT'); ?>
				</td>
				<td>
					<?php echo $lists['sf_default_hided']; ?>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<script type="text/javascript" src="<?php echo $mosConfig_live_site;?>/administrator/components/com_surveyforce/js/jquery.pack.js"></script>
					<script type="text/javascript" language="javascript" >
						jQuery.noConflict();
						var sf_is_loading = false;
					</script>
					<table class="adminlist" id="show_quest">
					<tr>
						<th class="title" colspan="4"><?php echo JText::_('COM_SF_DONT_SHOW_QUESTION'); ?></th>
					</tr>
					<?php if (is_array($lists['quest_show']) && count($lists['quest_show'])) 
							foreach($lists['quest_show'] as $rule) {
								if ( ($rule->sf_qtype == 2) || ($rule->sf_qtype == 3) ) {
							?>
							
							<tr>
								<td width="375px;"> <?php echo JText::_('COM_SF_FOR_QUESTION'); ?> "<?php echo $rule->sf_qtext;?>" <input type="hidden" name="sf_hid_rule2_id[]" value="<?php echo $rule->bid;?>" /><input type="hidden" name="sf_hid_rule2_alt_id[]" value="<?php echo $rule->did;?>" /><input type="hidden" name="sf_hid_rule2_quest_id[]" value="<?php echo $rule->qid;?>" /></td>
								<td colspan="2"> <?php echo JText::_('COM_SF_ANSWER_IS'); ?> "<?php echo $rule->qoption;?>"</td>
								<td><a href="javascript: void(0);" onclick="javascript:Delete_row(this); return false;" title="<?php echo JText::_('COM_SF_DELETE'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/publish_x.png"  border="0" alt="<?php echo JText::_('COM_SF_DELETE'); ?>"></a></td>
							</tr>
							<?php } elseif (($rule->sf_qtype == 1) || ($rule->sf_qtype == 5) || ($rule->sf_qtype == 6)) {?>
							<tr>
								<td  width="375px;"> <?php echo JText::_('COM_SF_FOR_QUESTION'); ?> "<?php echo $rule->sf_qtext;?>"<input type="hidden" name="sf_hid_rule2_id[]" value="<?php echo $rule->bid;?>" /><input type="hidden" name="sf_hid_rule2_alt_id[]" value="<?php echo ($rule->sf_qtype == 1?$rule->sdid:$rule->fdid);?>" /><input type="hidden" name="sf_hid_rule2_quest_id[]" value="<?php echo $rule->qid;?>" /></td>
								<td> <?php echo JText::_('COM_SF_AND_FOR_OPTION'); ?> "<?php echo $rule->qoption;?>"</td>
								<td> <?php echo JText::_('COM_SF_ANSWER_IS'); ?> "<?php echo ($rule->sf_qtype == 1?$rule->astext:$rule->aftext);?>"</td>
								<td><a href="javascript: void(0);" onclick="javascript:Delete_row(this); return false;" title="<?php echo JText::_('COM_SF_DELETE'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/publish_x.png"  border="0" alt="<?php echo JText::_('COM_SF_DELETE'); ?>"></a></td>
							</tr>
							<?php } elseif ($rule->sf_qtype == 9) {?>
							<tr >
								<td  width="375px;"> <?php echo JText::_('COM_SF_FOR_QUESTION'); ?> "<?php echo $rule->sf_qtext;?>"<input type="hidden" name="sf_hid_rule2_id[]" value="<?php echo $rule->bid;?>" /><input type="hidden" name="sf_hid_rule2_alt_id[]" value="<?php echo $rule->did;?>" /><input type="hidden" name="sf_hid_rule2_quest_id[]" value="<?php echo $rule->qid;?>" /></td>
								<td> <?php echo JText::_('COM_SF_AND_FOR_OPTION'); ?> "<?php echo $rule->qoption;?>"</td>
								<td> <?php echo JText::_('COM_SF_RANK_IS'); ?> "<?php echo $rule->aftext;?>"</td>
								<td><a href="javascript: void(0);" onclick="javascript:Delete_row(this); return false;" title="<?php echo JText::_('COM_SF_DELETE'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/publish_x.png"  border="0" alt="<?php echo JText::_('COM_SF_DELETE'); ?>"></a></td>
							</tr>	
							<?php }
							}?>
					</table>
					<table width="100%"  id="show_quest2">
					<tr>
						<td style="width:70px;"><?php echo JText::_('COM_SF_FOR_QUESTION'); ?> </td><td style="width:15px;"><?php echo $lists['quests3'];?></td>
						<td width="auto" colspan="2" ><div id="quest_show_div"></div>						
						</td>
					</tr>							
					<tr>
						<td colspan="4" style="text-align:left;"><input id="add_button" type="button" name="add" value="<?php echo JText::_('COM_SF_ADD'); ?>" onclick="javascript: if(!sf_is_loading) addRow();"  />
						</td>
					</tr>
					</table>
					<script type="text/javascript" language="javascript">
						function Delete_row(element) {
							var del_index = element.parentNode.parentNode.sectionRowIndex;
							var tbl_id = element.parentNode.parentNode.parentNode.parentNode.id;
							element.parentNode.parentNode.parentNode.deleteRow(del_index);							
						}
	
						function addRow(){
							var qtype = jQuery('#sf_qtype2').get(0).value;
							var sf_field_data_m = jQuery('#sf_field_data_m').get(0).options[jQuery('#sf_field_data_m').get(0).selectedIndex].value;
							var q_id = jQuery('#sf_quest_list3').get(0).options[jQuery('#sf_quest_list3').get(0).selectedIndex].value;
							if (qtype != 2 && qtype != 3){
								if (qtype == 1)
									var sf_field_data_a = jQuery('#f_scale_data').get(0).options[jQuery('#f_scale_data').get(0).selectedIndex].value;
								else
									var sf_field_data_a = jQuery('#sf_field_data_a').get(0).options[jQuery('#sf_field_data_a').get(0).selectedIndex].value;
							}else{
								var sf_field_data_a = 0;
							}
							
							var tbl_elem = jQuery('#show_quest').get(0);
							var row = tbl_elem.insertRow(tbl_elem.rows.length);
									
							var cell1 = document.createElement("td");
							var cell2 = document.createElement("td");
							var cell3 = document.createElement("td");
							var cell4 = document.createElement("td");
							var input_hidden = document.createElement("input");
							var input_hidden2 = document.createElement("input");
							var input_hidden3 = document.createElement("input");
							input_hidden.type = "hidden";
							input_hidden.name = 'sf_hid_rule2_id[]';
							input_hidden.value = sf_field_data_m;
							
							input_hidden2.type = "hidden";
							input_hidden2.name = 'sf_hid_rule2_alt_id[]';
							input_hidden2.value = sf_field_data_a;
							
							input_hidden3.type = "hidden";
							input_hidden3.name = 'sf_hid_rule2_quest_id[]';
							input_hidden3.value = q_id;
							cell1.width = '375px';
							cell1.innerHTML = '<?php echo JText::_('COM_SF_FOR_QUESTION'); ?> "'+jQuery('#sf_quest_list3').get(0).options[jQuery('#sf_quest_list3').get(0).selectedIndex].innerHTML+'"';
							cell1.appendChild(input_hidden);
							cell1.appendChild(input_hidden2);
							cell1.appendChild(input_hidden3);
							if (qtype != 2 && qtype != 3) {
								cell2.innerHTML = ' <?php echo JText::_('COM_SF_AND_FOR_OPTION'); ?> "'+jQuery('#sf_field_data_m').get(0).options[jQuery('#sf_field_data_m').get(0).selectedIndex].innerHTML+'"';				
								if (qtype != 9){
									if (qtype == 1)
										cell3.innerHTML = ' <?php echo JText::_('COM_SF_ANSWER_IS'); ?> "'+jQuery('#f_scale_data').get(0).options[jQuery('#f_scale_data').get(0).selectedIndex].innerHTML+'"';
									else
										cell3.innerHTML = ' <?php echo JText::_('COM_SF_ANSWER_IS'); ?> "'+jQuery('#sf_field_data_a').get(0).options[jQuery('#sf_field_data_a').get(0).selectedIndex].innerHTML+'"';
								}else {
									cell3.innerHTML = ' <?php echo JText::_('COM_SF_RANK_IS'); ?> "'+jQuery('#sf_field_data_a').get(0).options[jQuery('#sf_field_data_a').get(0).selectedIndex].innerHTML+'"';
								}
							} else {
								cell2.innerHTML = ' <?php echo JText::_('COM_SF_ANSWER_IS'); ?> "'+jQuery('#sf_field_data_m').get(0).options[jQuery('#sf_field_data_m').get(0).selectedIndex].innerHTML+'"';	
							}
							
							cell4.innerHTML = '<a href="javascript: void(0);" onclick="javascript:Delete_row(this); return false;" title="<?php echo JText::_('COM_SF_DELETE'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/publish_x.png"  border="0" alt="<?php echo JText::_('COM_SF_DELETE'); ?>"></a>';							
							row.appendChild(cell1);
							row.appendChild(cell2);							
							row.appendChild(cell3);
							row.appendChild(cell4);						
						}
						function processReq(http_request) {
							if (http_request.readyState == 4) {
								if ((http_request.status == 200)) {									
									var response = http_request.responseXML.documentElement;
									var text = '<?php echo JText::_('COM_SF_REQUEST_ERROR'); ?>';
									try {
										text = response.getElementsByTagName('data')[0].firstChild.data;
									} catch(e) {}
									jQuery('div#quest_show_div').html(text);							
								}
							}
						}
						function showOptions(val) {
							
							jQuery('input#add_button').get(0).style.display = 'none';
							
							jQuery('div#quest_show_div').html("<?php echo JText::_('COM_SF_PLEASE_WAIT_LOADING'); ?>");
							
							var http_request = false;
							if (window.XMLHttpRequest) { // Mozilla, Safari,...
								http_request = new XMLHttpRequest();
								if (http_request.overrideMimeType) {
									http_request.overrideMimeType('text/xml');
								}
							} else if (window.ActiveXObject) { // IE
								try { http_request = new ActiveXObject("Msxml2.XMLHTTP");
								} catch (e) {
									try { http_request = new ActiveXObject("Microsoft.XMLHTTP");
									} catch (e) {}
								}
							}
							if (!http_request) {
								return false;
							}

							http_request.onreadystatechange = function() { processReq(http_request); };

<?php 
$live_site = $GLOBALS['mosConfig_live_site'];
if (substr($_SERVER['HTTP_HOST'],0,4) == 'www.') {
	if (strpos($GLOBALS['mosConfig_live_site'], 'www.') !== false)
		$live_site = $GLOBALS['mosConfig_live_site'];
	else {
		$live_site = str_replace(substr($_SERVER['HTTP_HOST'],4), $_SERVER['HTTP_HOST'], $GLOBALS['mosConfig_live_site']);
	}
} else { 
	if (strpos($GLOBALS['mosConfig_live_site'], 'www.') !== false) 
		$live_site = str_replace('www.'.$_SERVER['HTTP_HOST'], $_SERVER['HTTP_HOST'], $GLOBALS['mosConfig_live_site']);
	else
		$live_site = $GLOBALS['mosConfig_live_site'];
}

$live_site_parts = parse_url($live_site); 

$live_url = $live_site_parts['scheme'].'://'.$live_site_parts['host'].(isset($live_site_parts['port'])?':'.$live_site_parts['port']:'').(isset($live_site_parts['path'])?$live_site_parts['path']:'/');

if ( substr($live_url, strlen($live_url)-1, 1) !== '/')
	$live_url .= '/';
?>

							http_request.open('GET', '<?php echo $live_url;?>administrator/index.php?no_html=1&option=com_surveyforce&task=get_options&rand=<?php echo time();?>&quest_id='+val, true);
							http_request.send(null);

							sf_is_loading = false;
						}					
						if (jQuery('#sf_quest_list3').get(0).options.length > 0)
							showOptions(jQuery('#sf_quest_list3').get(0).options[jQuery('#sf_quest_list3').get(0).selectedIndex].value);
						else {
							jQuery('table#show_quest').get(0).style.display = 'none';
							jQuery('table#show_quest2').get(0).style.display = 'none';
						}
					</script>
				</td>
			</tr>
		</table>
		<br />
		<small><?php echo JText::_('COM_SF_DOUBLE_CLICK_TO_EDIT_RANK'); ?></small>
		<table class="adminlist" id="qfld_tbl_rank">
		<tr>
			<th width="20px" align="center">#</th>
			<th class="title" width="200px"><?php echo JText::_('COM_SF_RANKS'); ?></th>
			<th width="20px" align="center" class="title"></th>
			<th width="20px" align="center" class="title"></th>
			<th width="20px" align="center" class="title"></th>
			<th width="auto"></th>
		</tr>
			<?php
			$k = 0; $ii = 1; $ind_last = count($lists['sf_fields_rank']);
			foreach ($lists['sf_fields_rank'] as $frow) { 
			?>	<input type="hidden" name="old_sf_hid_rank_id[]" value="<?php echo $frow->id?>">
				<tr class="<?php echo "row$k"; ?>">
					<td align="center"><?php echo $ii?></td>
					<td align="left"  ondblclick="edit_name(event, 'sf_hid_rank[]', 'sf_hid_rank_id[]');"><input type="hidden" name="sf_hid_rank[]" value="<?php echo $frow->ftext?>"><input type="hidden" name="sf_hid_rank_id[]" value="<?php echo $frow->id?>">
						<?php echo $frow->ftext?>
						
					</td>
					<td><a href="javascript: void(0);" onClick="javascript:Delete_tbl_row(this); return false;" title="<?php echo JText::_('COM_SF_DELETE'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/publish_x.png"  border="0" alt="<?php echo JText::_('COM_SF_DELETE'); ?>"></a></td>
					<td><?php if ($ii > 1) { ?><a href="javascript: void(0);" onClick="javascript:Up_tbl_row(this); return false;" title="<?php echo JText::_('COM_SF_MOVE_UP'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/uparrow.png"  border="0" alt="<?php echo JText::_('COM_SF_MOVE_UP'); ?>"></a><?php } ?></td>
					<td><?php if ($ii < $ind_last) { ?><a href="javascript: void(0);" onClick="javascript:Down_tbl_row(this); return false;" title="<?php echo JText::_('COM_SF_MOVE_DOWN'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/downarrow.png"  border="0" alt="<?php echo JText::_('COM_SF_MOVE_DOWN'); ?>"></a><?php } ?></td>
					<td></td>
				</tr>
			<?php
			$k = 1 - $k; $ii ++;
			 } ?>
		 </table><br>
		<div style="text-align:left; padding-left:30px ">
			<input id="new_rank" class="text_area" style="width:205px " type="text" name="new_rank">
			<input class="text_area" type="button" name="add_new_rank" style="width:70px " value="<?php echo JText::_('COM_SF_ADD'); ?>" onClick="javascript:Add_new_tbl_field('new_rank', 'qfld_tbl_rank', 'sf_hid_rank[]', 'sf_hid_rank_id[]');">
		</div>
		<br />
		<small><?php echo JText::_('COM_SF_DOUBLE_CLICK_TO_EDIT_OPTION'); ?></small>
		<table class="adminlist" id="qfld_tbl">
		<tr>
			<th width="20px" align="center">#</th>
			<th class="title" width="200px"><?php echo JText::_('COM_SF_QUESTION_OPTIONS'); ?></th>
			<th width="20px" align="center" class="title"></th>
			<th width="20px" align="center" class="title"></th>
			<th width="20px" align="center" class="title"></th>
			<th width="auto"></th>
		</tr>
		<?php
		$k = 0; $ii = 1; $ind_last = count($lists['sf_fields']);
		$other_option = null;
		foreach ($lists['sf_fields'] as $frow) { 
			if (isset($frow->is_true) && $frow->is_true == 2) {
				$other_option = $frow;
				continue;
			}
		?>
			<input type="hidden" name="old_sf_hid_field_ids[]" value="<?php echo $frow->id?>"/>
			<tr class="<?php echo "row$k"; ?>">
				<td align="center"><?php echo $ii?></td>
				<td align="left" onDblClick="edit_name(event, 'sf_hid_fields[]', 'sf_hid_field_ids[]');"><input type="hidden" name="sf_hid_fields[]" value="<?php echo $frow->ftext?>"/><input type="hidden" name="sf_hid_field_ids[]" value="<?php echo $frow->id?>"/>
					<?php echo $frow->ftext?>
					
				</td>
				<td><a href="javascript: void(0);" onClick="javascript:Delete_tbl_row(this); return false;" title="<?php echo JText::_('COM_SF_DELETE'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/publish_x.png"  border="0" alt="<?php echo JText::_('COM_SF_DELETE'); ?>"></a></td>
				<td><?php if ($ii > 1) { ?><a href="javascript: void(0);" onClick="javascript:Up_tbl_row(this); return false;" title="<?php echo JText::_('COM_SF_MOVE_UP'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/uparrow.png"  border="0" alt="<?php echo JText::_('COM_SF_MOVE_UP'); ?>"></a><?php } ?></td>
				<td><?php if ($ii < $ind_last) { ?><a href="javascript: void(0);" onClick="javascript:Down_tbl_row(this); return false;" title="<?php echo JText::_('COM_SF_MOVE_DOWN'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/downarrow.png"  border="0" alt="<?php echo JText::_('COM_SF_MOVE_DOWN'); ?>"></a><?php } ?></td>
				<td></td>
			</tr>
		<?php
		$k = 1 - $k; $ii ++;
		 } ?>
		</table>
		<table width="100%" class="adminlist" >
		<tr class="<?php echo "row$k"; ?>">
			<td width="20px" align="center"><input type="checkbox" onchange="javascipt: Add_fields_to_select();" name="other_option_cb" id="other_option_cb" value="2"  <?php echo (($other_option != null && !isset($lists['other_option'])) || (isset($lists['other_option']) && $lists['other_option'] == 1) ?'checked="checked"':'')?> /></td>
			<td align="left" colspan="5"><?php echo JText::_('COM_SF_OTHERS_OPTION'); ?> <input class="text_area" onkeyup="javascipt: Add_fields_to_select();" style="width:120px " type="text" name="other_option" id="other_option" value="<?php echo ($other_option == null?'Other':$other_option->ftext)?>">		
			<input type="hidden" name="other_op_id" id="other_op_id" value="<?php echo ($other_option == null?'0':$other_option->id)?>"/>
			</td>
		</tr>
		</table>
		<br>
		<div style="text-align:left; padding-left:30px ">
			<input id="new_field" class="text_area" style="width:205px " type="text" name="new_field">
			<input class="text_area" type="button" name="add_new_field" style="width:70px " value="<?php echo JText::_('COM_SF_ADD'); ?>" onClick="javascript:Add_new_tbl_field('new_field', 'qfld_tbl', 'sf_hid_fields[]', 'sf_hid_field_ids[]');">
			<br/><br/>
			<input class="button" type="button" name="set_default" value="<?php echo JText::_('COM_SF_SET_DEFAULT'); ?>" onClick="javascript: <?php echo ($row->id > 0?"submitbutton('set_default');":"alert('".JText::_('COM_SF_YOU_CAN_SET_DEFAULT_ANSWERS')."');")?>">
			
		</div>
		<br />
				
		<table class="adminlist">
		<tr>
			<th width="20px" align="center">#</th>
			<th class="title" width="200px"><?php echo JText::_('COM_SF_QUESTION_RULES'); ?></th>
			<th width="20px" align="center" class="title"></th>
			<th width="20px" align="center" class="title"></th>
			<th width="20px" align="center" class="title"></th>
			<th width="auto"></th>
		</tr></table>

		<table class="adminlist" id="qfld_tbl_rule">
		<tr>
			<th width="2%" align="center">#</th>
			<th class="title" width="20%"><?php echo JText::_('COM_SF_QUESTION_OPTIONS'); ?></th>
			<th class="title" width="4%"><?php echo JText::_('COM_SF_RANK'); ?></th>
			<th class="title" width="30%"><?php echo JText::_('COM_SF_QUESTION'); ?></th>
			<th class="title" width="14%"><?php echo JText::_('COM_SF_PRIORITY'); ?></th>
			<th width="2%" align="left" class="title"></th>
			<th width="2%" align="left" class="title"></th>
			<th width="auto"></th>
		</tr>

			<?php
			$k = 0; $ii = 1; $ind_last = count($lists['sf_fields_rule']);
			foreach ($lists['sf_fields_rule'] as $rrow) { ?>
				<tr class="<?php echo "row$k"; ?>">
					<td align="center"><?php echo $ii?></td>
					<td align="left">
						<?php echo $rrow->ftext;?>
						<input type="hidden" name="sf_hid_rule[]" value="<?php echo $rrow->ftext?>">
					</td>
					<td align="left">
						<?php echo $rrow->alt_ftext;?>
						<input type="hidden" name="sf_hid_rule_alt[]" value="<?php echo $rrow->alt_ftext?>">
					</td>
					<td align="left">
						<?php echo $rrow->next_quest_id . ' - ' . (strlen(strip_tags($rrow->sf_qtext)) > 50? mb_substr(strip_tags($rrow->sf_qtext), 0, 50).'...': strip_tags($rrow->sf_qtext))?>
						<input type="hidden" name="sf_hid_rule_quest[]" value="<?php echo $rrow->next_quest_id?>">
					</td>
					<td>
						<input type="text" style="text-align:center" class="text_area" name="priority[]" size="3" value="<?php echo $rrow->priority?>" />
					</td>
					<td><a href="javascript: void(0);" onClick="javascript:Delete_tbl_row2(this); return false;" title="<?php echo JText::_('COM_SF_DELETE'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/publish_x.png"  border="0" alt="<?php echo JText::_('COM_SF_DELETE'); ?>"></a></td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
			<?php
			$k = 1 - $k; $ii ++;
			 } ?>
		 </table><br/>	
		 <div style="text-align:left; padding-left:30px ">
		<input type="checkbox" name="super_rule" value="1" <?php echo $lists['checked']; ?> /><?php echo JText::_('COM_SF_GO_TO_QUESTION'); echo $lists['quests2']; echo JText::_('COM_SF_NEXT_REGARDLESS_WHAT_ANSWER'); ?><br />
		<small><?php echo JText::_('COM_SF_TO_OVERRIDE_THIS_RULE'); ?></small>
		</div><br />
		<div style="text-align:left; padding-left:30px "> 	
		<?php echo JText::_('COM_SF_IF_FOR'); echo $lists['sf_list_fields']; echo JText::_('COM_SF_RANK_IS'); echo $lists['sf_list_rank_fields']; ?>, 
		<?php echo JText::_('COM_SF_GO_TO_QUESTION'); echo $lists['quests']; ?>, <?php echo JText::_('COM_SF_S_PRIORITY'); ?> <input type="text" style="text-align:center" class="text_area" name="new_priority" id="new_priority" size="3" value="0" />
		<input class="text_area" type="button" name="add_new_rule"  value="<?php echo JText::_('COM_SF_ADD'); ?>" onClick="javascript:Add_new_tbl_field2('sf_field_list', 'sf_list_rank_fields', 'qfld_tbl_rule', 'sf_hid_rule[]', 'sf_quest_list', 'sf_hid_rule_quest[]', 'sf_hid_rule_alt[]');">
		</div>
		<br />

		<input type="hidden" name="sf_qtype" value="9" />
		<input type="hidden" name="option" value="com_surveyforce" />
		<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
		<input type="hidden" name="task" value="" />
		
		<input type="hidden" name="quest_id" value="<?php echo $row->id;?>" />
		<input type="hidden" name="red_task" value="<?php echo $task;?>" />
		</form>
		<?php
		EF_menu_footer();
	}

	function SF_moveQ_Select( $option, $cid, $sec, $SurveyList, $items ) {
		global $task; 
		EF_menu_header();
		?>
		<form action="index.php" method="post" name="adminForm">
		<?php if (!class_exists('JToolBarHelper')) { ?>
		<table class="adminheading">
		<tr>
			<th>
			<?php echo _SURVEY_FORCE_COMP_NAME?>:
			<small>
			<?php if ($task == 'move_quest_sel') { ?>
				<?php echo JText::_('COM_SF_MOVE_QUESTION'); ?>
			<?php } elseif ($task == 'copy_quest_sel') { ?>
				<?php echo JText::_('COM_SF_COPY_QUESTION'); ?>
			<?php } ?>
			</small>
			</th>
		</tr>
		</table>
		<?php } else { 
			if ($task == 'move_quest_sel') {
				JToolBarHelper::title( JText::_('COM_SF_MOVE_QUESTION'), 'static.png' );
			} elseif ($task == 'copy_quest_sel') {
				JToolBarHelper::title( JText::_('COM_SF_COPY_QUESTION'), 'static.png' );
			}
		}?> 
		<table class="adminform">
		<tr>
			<td width="3%"></td>
			<td align="left" valign="top" width="20%">
			<strong><?php echo JText::_('COM_SF_COPY_MOVE_TO_SURVEY'); ?></strong>
			<br />
			<?php echo $SurveyList ?>
			<br /><br />
			</td>
			<td align="left" valign="top" width="40%">
			<strong><?php echo JText::_('COM_SF_QUESTIONS_BEIGN_COPIED'); ?></strong>
			<br />
			<?php
			echo "<ol>";
			foreach ( $items as $item ) {
				echo "<li>". $item->sf_qtext ." (".$item->survey_name.")</li>";
			}
			echo "</ol>";
			?>
			</td>
			<td valign="top">
			<?php echo JText::_('COM_SF_THIS_WILL_COPY_QUESTIONS'); ?>
			</td>
		</tr>
		</table>
		<br /><br />

		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="task" value="" />
		<?php
		foreach ( $cid as $id ) {
			echo "\n <input type=\"hidden\" name=\"cid[]\" value=\"$id\" />";
		}
		foreach ( $sec as $id ) {
			echo "\n <input type=\"hidden\" name=\"sec[]\" value=\"$id\" />";
		}
		?>
		</form>
		<?php
		EF_menu_footer();
	}

	function SF_showSetDefault( &$row, &$lists, $option ) {
		global $mosConfig_live_site;
		survey_force_adm_html::SF_JS_getObj();
		mosCommonHTML::loadOverlib();
		mosCommonHTML::loadCalendar();
		EF_menu_header();
		?>
		<script type="text/javascript" src="/includes/js/joomla.javascript.js "></script>
		<form action="index.php" method="post" name="adminForm">
		<?php if (!class_exists('JToolBarHelper')) { ?>
		<table class="adminheading">
		<tr>
			<th class="edit">
			<?php echo _SURVEY_FORCE_COMP_NAME?>:
			<small>
			<?php echo JText::_('COM_SF_SET_DEFAULT_ANSWERS'); ?>
			</small>
			</th>
		</tr>
		</table>	
		<?php } else { 
			JToolBarHelper::title( JText::_('COM_SF_SET_DEFAULT_ANSWERS'), 'static.png' );
		}
		
		switch ($row->sf_qtype) {
			case '1': ?>
				<div align='left' style='padding-left:5px;text-align:left;'>
				<table cellpadding=0 cellspacing=0 class='adminform' style="width: 100%;">
				<tr>
					<th valign="top" class="title" ><?php echo $row->sf_qtext?></th>
				</tr>
				<tr><td>
				<table border=1 cellpadding=3 cellspacing=0 class='adminform' style="width: auto;">
				<tr><td >&nbsp;</td>
				<?php foreach($lists['scale_data'] as $scale) { ?>
					<td align='center' style='text-align:center'><?php echo $scale->stext?></td>
				<?php } ?>
				</tr>
				<?php foreach($lists['main_data'] as $main) { ?>
					<tr><td align='left' style='text-align:left'>&nbsp;&nbsp;<?php echo $main->ftext?>
						<input type="hidden" name="scale_id[]" value="<?php echo $main->id?>" />
						</td>
					<?php foreach($lists['scale_data'] as $scale) { 
							$selected = '';
							if ($main->ans_field == $scale->id)
								$selected = ' checked="checked" ';
					?>
						<td align='center' style='text-align:center'>
							<input type='radio' <?php echo $selected?> name='quest_radio_<?php echo $main->id?>' value='<?php echo $scale->id?>' >
						</td>
					<?php } ?>
					</tr>
				<?php } ?>
				</table>
				</td></tr>
				</table>
				</div>
			<?php
				break;
			case '2': ?>
				<div align='left' style='padding-left:5px;text-align:left;'>
				<table width='100%' class='adminform'>
				<tr>
					<th valign="top" class="title" colspan="2"><?php echo $row->sf_qtext?></th>
				</tr>
				<?php foreach($lists['main_data'] as $main) {
						$selected = '';
						if (in_array($main->id, $lists['answer_data']))
							$selected = ' checked="checked" ';
				 ?>
					<tr><td width='20px' align='left'>
							<input type='radio' name='quest_radio' <?php echo $selected?> value='<?php echo $main->id?>'>
						</td>
						<td align='left'><?php echo $main->ftext?><br></td>
					</tr>
				<?php } ?>
				</table>
				</div>
			<?php
				break;
			case '3': ?>
				<div align='left' style='padding-left:5px;text-align:left;'>
				<table class='adminform' width='100%'>
				<tr>
					<th valign="top" class="title" colspan="2"><?php echo $row->sf_qtext?></th>
				</tr>
				<?php foreach($lists['main_data'] as $main) { 
						$selected = '';
						if (in_array($main->id, $lists['answer_data']))
							$selected = ' checked="checked" ';
				?>
						<tr><td width='20px' align='left'>
								<input type='checkbox' name='quest_check[]' <?php echo $selected?> value='<?php echo $main->id?>' >
							</td>
							<td align='left'><?php echo $main->ftext?><br></td>
						</tr>
				<?php } ?>
				</table>
				</div>
			<?php
				break;
			case '5':
			case '6':?>
				<div align='left' style='padding-left:5px;text-align:left;'>
				<table class='adminform' width='100%'>
				<tr>
					<th valign="top" class="title"><?php echo $row->sf_qtext?></th>
				</tr>
				<tr><td>
				<table style="width: auto;" border="0">
				<?php 
				$make_select = array();
				foreach($lists['main_data'] as $main) { 
					$tmp = '';
					foreach($lists['alt_data'] as $adata) { 
						$selected = '';
						if ($adata->id == $main->ans_field)
							$selected = ' selected="selected" ';
						$tmp .= "<option value ='". $adata->id ."' ". $selected ." >". $adata->ftext ."</option>";
					}
					$make_select[] = $tmp;
				}
				?>
				<?php
				$i = 0;
				foreach($lists['main_data'] as $main) { 
				?>
					<tr><td align='left'><?php echo $main->ftext?></td>
						<td>&nbsp;&nbsp;<input type="hidden" name="main_id[]" value="<?php echo $main->id?>" /></td>
						<td align='left'><select class='inputbox' name='quest_select_<?php echo $main->id?>'><?php echo $make_select[$i]?></select></td>
					</tr>
				<?php
					$i++;
				}
				?>
				</table>
				</td></tr>
				</table>
				</div>
				<?php
				break;
			case '9':?>
				<script language="javascript" type="text/javascript">
					var main_ids = new Array(<?php echo count($lists['main_data']);?>);
					<?php						
						$i = 0;
						foreach($lists['main_data'] as $main) {
							echo "main_ids[$i] = {$main->id};";
							$i++;
						}
					?>
					function removeSameRank(e, n){
						var targ = e;
						if (targ.id.substring(0, 12) != 'quest_select') {return;}	
						var cur = targ.value;
						var mcount = <?php echo count($lists['main_data']);?>;
						var sel = null;
						for (i = 0; i < mcount; i++) {				
							sel = getObj("quest_select_"+main_ids[i]); 
							if (sel.id != targ.id && sel.value == cur)
								sel.value = 0;
						}
					}
				</script>
				<div align='left' style='padding-left:5px;text-align:left;'>
				<table class='adminform' width='100%'>
				<tr>
					<th valign="top" class="title"><?php echo $row->sf_qtext?></th>
				</tr>
				<tr><td>
				<table style="width: auto;" border="0">
				<?php 
				$make_select = array();
				foreach($lists['main_data'] as $main) { 
					$tmp = '';
					$tmp .= "<option value ='0' selected='selected' > - Select Rank - </option>";
					foreach($lists['alt_data'] as $adata) { 
						$selected = '';						
						if ($adata->id == $main->ans_field)
							$selected = ' selected="selected" ';
						$tmp .= "<option value ='". $adata->id ."' ". $selected ." >". $adata->ftext ."</option>";
					}
					$make_select[] = $tmp;
				}
				?>
				<?php
				$i = 0;
				foreach($lists['main_data'] as $main) { 
				?>
					<tr><td align='left'><?php echo $main->ftext?></td>
						<td>&nbsp;&nbsp;<input type="hidden" name="main_id[]" value="<?php echo $main->id?>" /></td>
						<td align='left'><select onchange='javascript:removeSameRank(this, <?php echo $main->id?>);' class='inputbox' name='quest_select_<?php echo $main->id?>' id='quest_select_<?php echo $main->id?>'><?php echo $make_select[$i]?></select></td>
					</tr>
				<?php
					$i++;
				}
				?>
				</table>
				</td></tr>
				</table>
				</div>
				<?php
				break;
		}
	?>
		<input type="hidden" name="sf_qtype" value="<?php echo $row->sf_qtype; ?>" />
		<input type="hidden" name="option" value="com_surveyforce" />
		<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
		<input type="hidden" name="task" value="" />
		</form>
	<?php
	EF_menu_footer();
	}
			#######################################
			###	---  MANAGE LISTS OF USERS  --- ###
	
	function SF_showListAuthors( &$rows, &$pageNav, $option ) {
		global $my, $task, $mosConfig_live_site;

		mosCommonHTML::loadOverlib();
		EF_menu_header();
		?>
		<form action="index.php" method="post" name="adminForm">
		<?php if (!class_exists('JToolBarHelper')) { ?> 
		<table class="adminheading">
		<tr>
			<th class="user">
			<?php echo _SURVEY_FORCE_COMP_NAME?>: <small><?php echo JText::_('COM_SF_MANAGE_AUTHORS'); ?></small>
			</th>
		</tr>
		</table>
		<?php } else { 
			JToolBarHelper::title( JText::_('COM_SF_MANAGE_AUTHORS'), 'user.png' );
		}?> 
		<br/>
		<table class="adminlist">
		<tr>
			<th width="20">#</th>
			<th width="20" class="title"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($rows); ?>);" /></th>
			<th class="title"><?php echo JText::_('COM_SF_NAME'); ?></th>
			<th class="title"><?php echo JText::_('COM_SF_USERNAME'); ?></th>
			<th class="title"><?php echo JText::_('COM_SF_EMAIL'); ?></th>
			<th class="title"><?php echo JText::_('COM_SF_LAST_VISIT'); ?></th>
		</tr>
		<?php
		$k = 0;
		for ($i=0, $n=count($rows); $i < $n; $i++) {
			$row = $rows[$i];
			$checked = mosHTML::idBox( $i, $row->id);
			?>
			<tr class="<?php echo "row$k"; ?>">
				<td align="center"><?php echo $pageNav->rowNumber( $i ); ?></td>
				<td><?php echo $checked; ?></td>
				<td align="left"><?php echo $row->name; ?></td>
				<td><?php echo $row->username; ?></td>
				<td><a href="mailto:<?php echo $row->email; ?>"/><?php echo $row->email; ?></a></td>
				<td align="left">
					<?php echo ($row->lastvisitDate == '0000-00-00 00:00:00')?'-':mosFormatDate( $row->lastvisitDate , _CURRENT_SERVER_TIME_FORMAT ); ?>
				</td>
			</tr>
			<?php
			$k = 1 - $k;
		}
		?>
		</table>
		<?php echo $pageNav->getListFooter(); ?>

		<input type="hidden" name="option" value="com_surveyforce" />
		<input type="hidden" name="task" value="authors" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="hidemainmenu" value="0">
		</form>
		<?php
		EF_menu_footer();
	}
	
	function SF_showAddAuthors( &$rows, &$pageNav, $option ) {
		global $my, $task, $mosConfig_live_site;

		mosCommonHTML::loadOverlib();
		EF_menu_header();
		?>
		<form action="index.php" method="post" name="adminForm">
		<?php if (!class_exists('JToolBarHelper')) { ?>

		<table class="adminheading">
		<tr>
			<th class="user">
			<?php echo _SURVEY_FORCE_COMP_NAME?>: <small><?php echo JText::_('COM_SF_ADD_USERS_TO_AUTHORS'); ?></small>
			</th>
		</tr>
		</table>
		<?php } else { 
			JToolBarHelper::title( JText::_('COM_SF_ADD_USERS_TO_AUTHORS'), 'user.png' );
		}?> 
		<br/>
		<table class="adminlist">
		<tr>
			<th width="20">#</th>
			<th width="20" class="title"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($rows); ?>);" /></th>
			<th class="title"><?php echo JText::_('COM_SF_NAME'); ?></th>
			<th class="title"><?php echo JText::_('COM_SF_USERNAME'); ?></th>
			<th class="title"><?php echo JText::_('COM_SF_EMAIL'); ?></th>
			<th class="title"><?php echo JText::_('COM_SF_LAST_VISIT'); ?></th>
		</tr>
		<?php
		$k = 0;
		for ($i=0, $n=count($rows); $i < $n; $i++) {
			$row = $rows[$i];
			$checked = mosHTML::idBox( $i, $row->id);
			?>
			<tr class="<?php echo "row$k"; ?>">
				<td align="center"><?php echo $pageNav->rowNumber( $i ); ?></td>
				<td><?php echo $checked; ?></td>
				<td align="left"><?php echo $row->name; ?></td>
				<td><?php echo $row->username; ?></td>
				<td><a href="mailto:<?php echo $row->email; ?>"/><?php echo $row->email; ?></a></td>
				<td align="left">
					<?php echo ($row->lastvisitDate == '0000-00-00 00:00:00')?'-':mosFormatDate( $row->lastvisitDate , _CURRENT_SERVER_TIME_FORMAT ); ?>
				</td>
			</tr>
			<?php
			$k = 1 - $k;
		}
		?>
		</table>
		<?php echo $pageNav->getListFooter(); ?>

		<input type="hidden" name="option" value="com_surveyforce" />
		<input type="hidden" name="task" value="add_author" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="hidemainmenu" value="0">
		</form>
		<?php
		EF_menu_footer();
	}
	
	function SF_showListUsers( &$rows, &$lists, &$pageNav, $option ) {
		global $my, $task, $mosConfig_live_site;

		mosCommonHTML::loadOverlib();
		EF_menu_header();
		?>
		<form action="index.php" method="post" name="adminForm">
		<?php if (!class_exists('JToolBarHelper')) { ?>

		<table class="adminheading">
		<tr>
			<th class="user">
			<?php echo _SURVEY_FORCE_COMP_NAME?>: <small><?php echo JText::_('COM_SF_MANAGE_USERS_LIST'); ?></small>
			</th>
		</tr>
		</table>
		<?php } else { 
			JToolBarHelper::title( JText::_('COM_SF_MANAGE_USERS_LIST'), 'user.png' );
		}?>
		<br/>
		<table class="adminlist">
		<tr>
			<th width="20">#</th>
			<th width="20" class="title"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($rows); ?>);" /></th>
			<th class="title"><?php echo JText::_('COM_SF_LIST_OF_USERS'); ?></th>
			<th class="title"><?php echo JText::_('COM_SF_USERS'); ?></th>
			<th class="title"><?php echo JText::_('COM_SF_STARTS'); ?></th>
			<th class="title"><?php echo JText::_('COM_SF_SURVEY'); ?></th>
			<th class="title"><?php echo JText::_('COM_SF_AUTHOR'); ?></th>
			<th class="title"><?php echo JText::_('COM_SF_CREATED'); ?></th>
			<th class="title"><?php echo JText::_('COM_SF_INVITED'); ?></th>
			<th class="title"><?php echo JText::_('COM_SF_REMINDED'); ?></th>
		</tr>
		<?php
		$k = 0;
		for ($i=0, $n=count($rows); $i < $n; $i++) {
			$row = $rows[$i];
			$link = '#';
			if ($task == 'users') {
				$link 	= 'index.php?option='.$option.'&task=view_users&list_id='. $row->id;
			} elseif ($task == 'rep_list') {
				$link 	= 'index.php?option='.$option.'&task=view_rep_listA&id='. $row->id;
			}
			$checked = mosHTML::idBox( $i, $row->id);
			?>
			<tr class="<?php echo "row$k"; ?>">
				<td align="center"><?php echo $pageNav->rowNumber( $i ); ?></td>
				<td><?php echo $checked; ?></td>
				<td align="left">
					<a href="<?php echo $link; ?>" title="<?php echo JText::_('COM_SF_VIEW_USERS'); ?>">
					<?php echo $row->listname; ?>
					</a>
				</td>
				<td><?php echo $row->users_count; ?></td>
				<td><?php echo $row->total_starts; ?></td>
				<td align="left">
					<?php echo $row->survey_name; ?>
				</td>
				<td align="left">
					<?php echo $row->author; ?>
				</td>
				<td align="left">
					<?php echo mosFormatDate( $row->date_created, _CURRENT_SERVER_TIME_FORMAT ); ?>
				</td>
				<td align="left">
					<?php echo ($row->date_invited == '0000-00-00 00:00:00')?'-':mosFormatDate( $row->date_invited, _CURRENT_SERVER_TIME_FORMAT ); ?>
				</td>
				<td align="left">
					<?php echo ($row->date_remind == '0000-00-00 00:00:00')?'-':mosFormatDate( $row->date_remind , _CURRENT_SERVER_TIME_FORMAT ); ?>
				</td>
			</tr>
			<?php
			$k = 1 - $k;
		}
		?>
		</table>
		<?php echo $pageNav->getListFooter(); ?>

		<input type="hidden" name="option" value="com_surveyforce" />
		<input type="hidden" name="task" value="<?php echo $task?>" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="hidemainmenu" value="0">
		</form>
		<?php
		EF_menu_footer();
	}

	function SF_editListUsers( &$row, &$lists, &$sf_config, $option ) {
		global $mosConfig_live_site, $task, $my;

		mosCommonHTML::loadOverlib();
		survey_force_adm_html::SF_JS_getObj();
		EF_menu_header();
		?>
		<script language="javascript" type="text/javascript">
		<!--
	<?php if ($task == 'add_list') { ?>
		function ReAnalize_tbl_Rows( start_index ) {
			var tbl_elem = getObj('qfld_tbl');
			if (!start_index) { start_index = 1; }
			if (start_index < 0) { start_index = 1; }
			if (tbl_elem.rows[start_index]) {
				var count = start_index; var row_k = 1 - start_index%2;
				for (var i=start_index; i<tbl_elem.rows.length; i++) {
					tbl_elem.rows[i].cells[0].innerHTML = count;
					tbl_elem.rows[i].className = 'row'+row_k;
					count++;
					row_k = 1 - row_k;
				}
			}
		}

		function Delete_tbl_row(element) {
			var del_index = element.parentNode.parentNode.sectionRowIndex;
			element.parentNode.parentNode.parentNode.deleteRow(del_index);
			ReAnalize_tbl_Rows(del_index - 1);
		}

		function Add_new_tbl_field() {
			
			var new_user_name = getObj('new_name').value;
			var new_user_lastname = getObj('new_lastname').value;
			var new_user_email = getObj('new_email').value;
			if (new_user_name == '') {
				alert('<?php echo JText::_('COM_SF_PLEASE_ENTER_USER_NAME');?>');
				return false;
			}
			if (new_user_lastname == '') {
				alert('<?php echo JText::_('COM_SF_PLEASE_ENTER_USER_LASTNAME');?>');
				return false;
			}
			var reg_email = /[0-9a-z_]+@[0-9a-z_^.]+.[a-z]{2,3}/;
			if (!reg_email.test(new_user_email)) {
				alert('<?php echo JText::_('COM_SF_PLEASE_ENTER_VALID_EMAIL');?>');
				return false;
			}
			var tbl_elem = getObj('qfld_tbl');
			var row = tbl_elem.insertRow(tbl_elem.rows.length);
			var cell1 = document.createElement("td");
			var cell2 = document.createElement("td");
			var cell3 = document.createElement("td");
			var cell4 = document.createElement("td");
			var cell5 = document.createElement("td");
			var cell6 = document.createElement("td");
			cell1.align = 'center';
			cell1.innerHTML = 0;
			cell2.innerHTML = new_user_name + '<input type="hidden" name="sf_hid_names[]" value="' + new_user_name + '">';
			cell3.innerHTML = new_user_lastname + '<input type="hidden" name="sf_hid_lastnames[]" value="' + new_user_lastname + '">';
			cell4.innerHTML = new_user_email + '<input type="hidden" name="sf_hid_emails[]" value="' + new_user_email + '">';
			cell5.innerHTML = '<a href="" onClick="javascript:Delete_tbl_row(this); return false;" title="<?php echo JText::_('COM_SF_DELETE'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/publish_x.png"  border="0" alt="<?php echo JText::_('COM_SF_DELETE'); ?>"></a>';
			row.appendChild(cell1);
			row.appendChild(cell2);
			row.appendChild(cell3);
			row.appendChild(cell4);
			row.appendChild(cell5);
			row.appendChild(cell6);
			ReAnalize_tbl_Rows(tbl_elem.rows.length - 2);
		}
	<?php } ?>
		Joomla.submitbutton = function(pressbutton) {
			var form = document.adminForm;
			if (pressbutton == 'cancel_list') {
				<?php if ($task == 'add_list') { ?>
				if ( (form.new_name.value != "") && (form.new_lastname.value != "") && (form.new_email.value != "") ) {
					if ( confirm('<?php echo JText::_('COM_SF_ARE_YOU_SURE_WANT_CANCEL');?>') ) {
						submitform( pressbutton );
						return;
					}
				} else {
					submitform( pressbutton );
					return;
				}
				<?php }
				else { ?>
				submitform( pressbutton );
				<?php }?>
				return;
			}
			<?php if ($task == 'add_list') { ?>
			// do field validation
			if (form.listname.value == ""){
				alert( "<?php echo JText::_('COM_SF_LIST_MUST_HAVE_NAME'); ?>" );
			} 
			else { if (form.is_import_csv.checked && form.csv_file.value == "") {
				alert("<?php echo JText::_('COM_SF_SELECT_CSV_FILE'); ?>");
				}			
				else {
					submitform( pressbutton );
				}
			}
			<?php }
			else { ?>
			if (form.listname.value == ""){
				alert( "<?php echo JText::_('COM_SF_LIST_MUST_HAVE_NAME'); ?>" );
			} 
			else {
				submitform( pressbutton );
			}
			<?php }
			?>
		}
		//-->
		</script>
		
		<form action="index.php" method="post" name="adminForm" enctype="multipart/form-data">
		<?php if (!class_exists('JToolBarHelper')) { ?>
		<table class="adminheading">
		<tr>
			<th class="user">
			<?php echo _SURVEY_FORCE_COMP_NAME?>:
			<small>
			<?php echo $row->id ? JText::_('COM_SF_EDIT_LIST_OF_USERS') : JText::_('COM_SF_NEW_LIST_OF_USERS'); ?>
			</small>
			</th>
		</tr>
		</table>
		<?php } else { 
			JToolBarHelper::title( ($row->id ? JText::_('COM_SF_EDIT_LIST_OF_USERS') : JText::_('COM_SF_NEW_LIST_OF_USERS')), 'user.png' );
		}?>
		<table width="100%" class="adminform">
			<tr>
				<th colspan="2"><?php echo JText::_('COM_SF_LIST_DETAILS'); ?></th>
			</tr>
			<tr>
				<td align="right" width="20%" valign="top"><?php echo JText::_('COM_SF_LIST_NAME'); ?></td>
				<td><input type="text" class="text_area" size="35" name="listname" value="<?php echo $row->listname;?>"></td>
			</tr>
			<tr>
				<td><?php echo JText::_('COM_SF_SURVEY'); ?>:</td><td><?php echo $lists['survey']; ?></td>
			</tr>
			<tr>
				<td><?php echo JText::_('COM_SF_CREATED'); ?>:</td><td><?php echo $row->date_created;?></td>
			</tr>
	<?php if ($task == 'add_list') { ?>
			<tr>
				<td><input type="checkbox" name="is_add_reg" value="1" checked><?php echo JText::_('COM_SF_ADD_REGISTERED_USERS'); ?></td><td></td>
			</tr> 
			<tr>
				<td><input type="checkbox" name="is_import_csv" id="is_import_csv" value="1" checked="checked"/><?php echo JText::_('COM_SF_IMPORT_FROM_CSV'); ?>
				<?php 
					$csv_descr = JText::_('COM_SF_THE_CSV_FILE_IS_USED')
					. "\n <b>".JText::_('COM_SF_THE_STRUCTURE_OF_CSV_FILE')."</b>"
					. "\n ".JText::_('COM_SF_LASTNAME_NAME_EMAIL')
					. "\n Klinton,Bill,email_bill@email.tst"
					. "\n Bush,George,email_george@email.tst"
					. "\n <b>".JText::_('COM_SF_YOU_CAN_FIND_EXAMPLE_OF_CSV')."</b>"
					. "\n `".$mosConfig_live_site."/administrator/components/com_surveyforce/includes/example_users.csv`";
					echo mosToolTip( nl2br($csv_descr), JText::_('COM_SF_CSV_IMPORT_DESCRIPTION'), 280, 'tooltip.png', '', '' );
				?>
				</td>
				<td><input class="text_area" type="file" name="csv_file" id="csv_file" /></td>
			</tr>
			<?php if ($sf_config->get('sf_enable_lms_integration')) { ?>
			<tr>
				<td valign="top"><input type="checkbox" name="is_add_lms" value="1" checked><?php echo JText::_('COM_SF_ADD_ALL_USERS_FROM_LM'); ?></td>
				<td><?php echo $lists['lms_groups']?></td>
			</tr>
			<?php }?>
			<tr>
				<td><input type="checkbox" name="is_add_manually" value="1" checked><?php echo JText::_('COM_SF_ADD_USERS_MANUALLY'); ?></td><td></td>
			</tr>
	<?php } ?>
		</table>
	<?php if ($task == 'add_list') { ?>
		<br />
		<table class="adminlist" id="qfld_tbl">
		<tr>
			<th width="20px" align="center">#</th>
			<th class="title" width="200px"><?php echo JText::_('COM_SF_NAME'); ?></th>
			<th class="title" width="200px"><?php echo JText::_('COM_SF_LASTNAME'); ?></th>
			<th class="title" width="200px"><?php echo JText::_('COM_SF_EMAIL'); ?></th>
			<th width="20px" align="center" class="title"></th>
			<th width="auto"></th>
		</tr>	
		</table><br>
		<div style="text-align:left; padding-left:30px ">
			<input id="new_name" class="text_area" style="width:203px " type="text" name="new_name">
			<input id="new_lastname" class="text_area" style="width:203px " type="text" name="new_lastname">
			<input id="new_email" class="text_area" style="width:203px " type="text" name="new_email">
			<input class="text_area" type="button" name="add_new_field" style="width:70px " value="<?php echo JText::_('COM_SF_ADD'); ?>" onClick="javascript:Add_new_tbl_field();">
		</div>
		<br />
	<?php } ?>
		<input type="hidden" name="option" value="com_surveyforce" />
		<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
		<input type="hidden" name="sf_author_id" value="<?php echo $my->id; ?>" />
		<input type="hidden" name="task" value="" />
		</form>
		<?php
		EF_menu_footer();
	}

			#######################################
			###	--- ---   INVITATIONS   --- --- ###
	
	function SF_inviteUsers( &$row, &$lists, $option ) {
		global $mosConfig_live_site;

		mosCommonHTML::loadOverlib();
		survey_force_adm_html::SF_JS_getObj();
		EF_menu_header();
		?>
		<script language="javascript" type="text/javascript">
		<!--
		function StartInvitation() {
			var form = document.adminForm;
			var inv_frame = getObj('invite_frame');
			inv_frame.src = 'index.php?option=com_surveyforce&no_html=1&task=invitation_start&email='+form.email_id.value+'&list='+<?php echo $row->id?>;
		}
		
		function StopInvitation() {
			var form = document.adminForm;
			form.Start.value = 'Resume';
			if (!document.all)
				for (var i=0;i<top.frames.length;i++)
				  top.frames[i].stop()
			else
				for (var i=0;i<top.frames.length;i++)
				  top.frames[i].document.execCommand('Stop')
		}
		
		Joomla.submitbutton = function(pressbutton) {
			var form = document.adminForm;
			submitform( pressbutton );
		}
		//-->
		</script>
		
		<form action="index.php" method="post" name="adminForm">
		<?php if (!class_exists('JToolBarHelper')) { ?>
		<table class="adminheading">
		<tr>
			<th class="massemail">
			<?php echo _SURVEY_FORCE_COMP_NAME?>:
			<small>
			<?php echo JText::_('COM_SF_INVITE_USERS'); ?>
			</small>
			</th>
		</tr>
		</table>
		<?php } else { 
			JToolBarHelper::title( JText::_('COM_SF_INVITE_USERS'), 'massemail.png' );
		}?> 
		<table width="100%" class="adminform">
			<tr>
				<th colspan="2"><?php echo JText::_('COM_SF_INVITATION_DETAILS'); ?></th>
			</tr>
			<tr>
				<td align="right" width="20%"><?php echo JText::_('COM_SF_LIST_OF_USERS'); ?>:</td>
				<td><?php echo $row->listname; ?></td>
			</tr>
			<tr>
				<td align="right" width="20%" valign="top"><?php echo JText::_('COM_SF_EMAIL'); ?>:</td>
				<td><?php echo $lists['email_list']; ?></td>
			</tr>
		</table>
		<br />
		<input type="hidden" name="option" value="com_surveyforce" />
		<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
		<input type="hidden" name="task" value="" />
		<table width="100%" class="adminform">
			<tr>
				<td width="20%">
					<input type="button" class="text_area" name="Start" value="Start" id="Start_button" onClick="javascript:StartInvitation();">
					<input type="button" class="text_area" name="Stop" value="Stop" onClick="javascript:StopInvitation();">
				</td>
				<td width="80%" align="left">
					<div id="div_invite_log" style="width:0px; background-color:#000000; color:#FFFFFF; text-align:center">
					</div>
					<div id="div_invite_log_txt" style="width:600px; text-align:left">
						<?php if ($row->is_invited == 0) { ?>
						<?php echo JText::_('COM_SF_PRESS_START_TO_BEGIN_INVITATIONS'); ?>
						<?php } elseif ($row->is_invited == 1) { ?>
						<?php echo JText::_('COM_SF_USERS_FROM_LIST_HAD_BEEN_SENT_INVITATIONS'); ?>
						<?php } elseif ($row->is_invited == 2) { ?>
						<?php echo JText::_('COM_SF_PRESS_START_TO_CONTINUE_INVITATIONS'); ?>
						<?php } ?>
					</div>
				</td>
			</tr>
		</table>
		</form>

		<iframe src="" style="display:none " id="invite_frame">
		</iframe>
		<?php
		EF_menu_footer();
	}

	function SF_remindUsers( &$row, &$lists, $option ) {
		global $mosConfig_live_site;

		mosCommonHTML::loadOverlib();
		survey_force_adm_html::SF_JS_getObj();
		EF_menu_header();
		?>
		<script language="javascript" type="text/javascript">
		<!--
		function StartRemind() {
			var form = document.adminForm;
			var inv_frame = getObj('invite_frame');
			inv_frame.src = 'index.php?option=com_surveyforce&no_html=1&task=remind_start&email='+form.email_id.value+'&list='+<?php echo $row->id?>;
		}
		
		function StopRemind() {
			var form = document.adminForm;
			form.Start.value = '<?php echo JText::_('COM_SF_RESUME'); ?>';
			if (!document.all)
				for (var i=0;i<top.frames.length;i++)
				  top.frames[i].stop()
			else
				for (var i=0;i<top.frames.length;i++)
				  top.frames[i].document.execCommand('Stop')
		}
		
		Joomla.submitbutton = function(pressbutton) {
			var form = document.adminForm;
			submitform( pressbutton );
		}
		//-->
		</script>
		
		<form action="index.php" method="post" name="adminForm">
		<?php if (!class_exists('JToolBarHelper')) { ?>
		<table class="adminheading">
		<tr>
			<th class="massemail">
			<?php echo _SURVEY_FORCE_COMP_NAME?>:
			<small>
			<?php echo JText::_('COM_SF_REMIND_USERS'); ?>
			</small>
			</th>
		</tr>
		</table>
		<?php } else { 
			JToolBarHelper::title( JText::_('COM_SF_REMIND_USERS'), 'massemail.png' );
		}?> 
		<table width="100%" class="adminform">
			<tr>
				<th colspan="2"><?php echo JText::_('COM_SF_REMINDER_DETAILS'); ?></th>
			</tr>
			<tr>
				<td align="right" width="20%"><?php echo JText::_('COM_SF_LIST_OF_USERS'); ?>:</td>
				<td><?php echo $row->listname; ?></td>
			</tr>
			<tr>
				<td align="right" width="20%" valign="top"><?php echo JText::_('COM_SF_EMAIL'); ?>:</td>
				<td><?php echo $lists['email_list']; ?></td>
			</tr>
		</table>
		<br />
		<input type="hidden" name="option" value="com_surveyforce" />
		<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
		<input type="hidden" name="task" value="" />
		<table width="100%" class="adminform">
			<tr>
				<td width="20%">
					<input type="button" class="text_area" name="Start" value="Start" id="Start_button" onClick="javascript:StartRemind();">
					<input type="button" class="text_area" name="Stop" value="Stop" onClick="javascript:StopRemind();">
				</td>
				<td width="80%" align="left">
					<div id="div_invite_log" style="width:0px; background-color:#000000; color:#FFFFFF; text-align:center">
					</div>
					<div id="div_invite_log_txt" style="width:600px; text-align:left">
						<?php echo JText::_('COM_SF_PRESS_START_TO_BEGIN_REMINDERS'); ?>
					</div>
				</td>
			</tr>
		</table>
		</form>

		<iframe src="" style="display:none " id="invite_frame">
		</iframe>
		<?php
		EF_menu_footer();
	}

			#######################################
			###	---  MANAGE USERS IN LISTS  --- ###
	
	function SF_show_Users( &$rows, &$lists, &$pageNav, $option ) {
		global $my;

		mosCommonHTML::loadOverlib();
		EF_menu_header();
		?>
		<form action="index.php" method="post" name="adminForm">
		<?php if (!class_exists('JToolBarHelper')) { ?>
		<table class="adminheading">
		<tr>
			<th class="user">
			<?php echo _SURVEY_FORCE_COMP_NAME?>: <small><?php echo JText::_('COM_SF_USERS'); ?> ( <?php echo $lists['listname']?> )</small>
			</th>
			<td width="right">
			<?php echo $lists['userlists'];?>
			</td> 			
		</tr>
		</table>
		<?php } else { 
			JToolBarHelper::title( JText::_('COM_SF_USERS').' ( '.$lists['listname'].' )', 'user.png' ); ?>
			<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td align="left" width="100%">&nbsp;</td>
				<td nowrap="nowrap">
					<?php echo $lists['userlists'];?>
				</td> 			
			</tr>
			</table>

		<?php }?> 
		<table class="adminlist">
		<tr>
			<th width="20">#</th>
			<th width="20" class="title"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($rows); ?>);" /></th>
			<th class="title"><?php echo JText::_('COM_SF_NAME'); ?></th>
			<th class="title"><?php echo JText::_('COM_SF_LASTNAME'); ?></th>
			<th class="title"><?php echo JText::_('COM_SF_EMAIL'); ?></th>
			<th class="title"></th>
		</tr>
		<?php
		$k = 0;
		for ($i=0, $n=count($rows); $i < $n; $i++) {
			$row = $rows[$i];

			$checked = mosHTML::idBox( $i, $row->id);
			?>
			<tr class="<?php echo "row$k"; ?>">
				<td align="center"><?php echo $pageNav->rowNumber( $i ); ?></td>
				<td><?php echo $checked; ?></td>
				<td align="left">
					<?php echo $row->name; ?>
				</td>
				<td align="left">
					<?php echo $row->lastname; ?>
				</td>
				<td align="left">
					<?php echo $row->email; ?>
				</td>
				<td align="left">
					
				</td>
			</tr>
			<?php
			$k = 1 - $k;
		}
		?>
		</table>
		<?php echo $pageNav->getListFooter(); ?>

		<input type="hidden" name="option" value="com_surveyforce" />
		<input type="hidden" name="task" value="view_users" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="hidemainmenu" value="0">
		</form>
		<?php
		EF_menu_footer();
	}
	
	function SF_editUser( &$row, &$lists, $option ) {
		global $mosConfig_live_site;

		mosCommonHTML::loadOverlib();
		survey_force_adm_html::SF_JS_getObj();
		EF_menu_header();
		?>
		<script language="javascript" type="text/javascript">
		<!--
		userArray = new Array;
		
		<?php 
			foreach($lists['users'] as $user) {
				echo "userArray[".$user->value."] = ['".$user->name."','".$user->text."','".$user->email."']; ";
			}
		?>
		
		Joomla.submitbutton = function(pressbutton) {
			var form = document.adminForm;
			if (pressbutton == 'cancel_user') {
				submitform( pressbutton );
				return;
			}
			
			// do field validation
			var reg_email = /[0-9a-z_]+@[0-9a-z_^.]+.[a-z]{2,3}/;
			if (form.name.value == ""){
				alert( "<?php echo JText::_('COM_SF_USER_MUST_HAVE_NAME'); ?>" );
			} else if (form.lastname.value == ""){
				alert( "<?php echo JText::_('COM_SF_USER_MUST_HAVE_LASTNAME'); ?>" );
			} else if (form.email.value == ""){
				alert( "<?php echo JText::_('COM_SF_USER_MUST_HAVE_EMAIL'); ?>" );
			} else if (!reg_email.test(form.email.value)) {
				alert("<?php echo JText::_('COM_SF_PLEASE_ENTER_VALID_EMAIL'); ?>");
			} else {
				submitform( pressbutton );
			}
		}
		
		function changeUserSelect(c_e) {
			var form = document.adminForm;
			var sel_value = c_e.options[c_e.selectedIndex].value;			
			form.name.value = '';
			form.lastname.value = '';
			form.email.value = '';
			if (sel_value > 0) {
				form.name.value = userArray[sel_value][1];
				form.lastname.value = userArray[sel_value][0];
				form.email.value = userArray[sel_value][2];
			}			
		}
		
		//-->
		</script>
		
		<form action="index.php" method="post" name="adminForm" >
		<?php if (!class_exists('JToolBarHelper')) { ?>
		<table class="adminheading">
		<tr>
			<th class="user">
			<?php echo _SURVEY_FORCE_COMP_NAME?>:
			<small>
			<?php echo $row->id ? JText::_('COM_SF_EDIT_USER') : JText::_('COM_SF_NEW_USER'); ?>
			</small>
			</th>
		</tr>
		</table>
		<?php } else { 
			JToolBarHelper::title( ($row->id ? JText::_('COM_SF_EDIT_USER') : JText::_('COM_SF_NEW_USER')), 'user.png' );
		}?> 
		<table width="100%" class="adminform">
			<tr>
				<th colspan="4"><?php echo JText::_('COM_SF_USER_DETAILS'); ?></th>
			</tr>
			<tr>
				<td align="right" width="20%" valign="top"><?php echo JText::_('COM_SF_USER_NAME'); ?>:</td>
				<td><input type="text" class="text_area" size="35" name="name" value="<?php echo $row->name;?>"></td>
				<td><?php echo JText::_('COM_SF_SELECT_REGISTERED'); ?>:</td>
				<td><?php echo $lists['reg_users']; ?></td>
			</tr>
			<tr>
				<td align="right" width="20%" valign="top"><?php echo JText::_('COM_SF_USER_LASTNAME'); ?>:</td>
				<td><input type="text" class="text_area" size="35" name="lastname" value="<?php echo $row->lastname;?>"></td>
			</tr>
			<tr>
				<td align="right" width="20%" valign="top"><?php echo JText::_('COM_SF_USER_EMAIL'); ?>:</td>
				<td><input type="text" class="text_area" size="35" name="email" value="<?php echo $row->email;?>"></td>
			</tr>
			<tr>
				<td><?php echo JText::_('COM_SF_LIST'); ?>:</td><td><?php echo $lists['userlist']; ?></td>
			</tr>
		</table>
		<br />
		<input type="hidden" name="option" value="com_surveyforce" />
		<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
		<input type="hidden" name="task" value="" />
		</form>
		<?php
		EF_menu_footer();
	}
	
	function SF_moveUser_Select( $option, $cid, $listusersList, $items ) {
		global $task; 
		EF_menu_header();
		?>
		<form action="index.php" method="post" name="adminForm">
		<?php if (!class_exists('JToolBarHelper')) { ?>
		<table class="adminheading">
		<tr>
			<th class="user">
			<?php echo _SURVEY_FORCE_COMP_NAME?>:
			<small>
			<?php if ($task == 'move_user_sel') { ?>
				<?php echo JText::_('COM_SF_MOVE_USER'); ?>
			<?php } elseif ($task == 'copy_user_sel') { ?>
				<?php echo JText::_('COM_SF_COPY_USER'); ?>
			<?php } ?>
			</small>
			</th>
		</tr>
		</table>
		<?php } else { 
			if ($task == 'move_user_sel') {
				JToolBarHelper::title( JText::_('COM_SF_MOVE_USER'), 'user.png' );
			} elseif ($task == 'copy_user_sel') {
				JToolBarHelper::title( JText::_('COM_SF_COPY_USER'), 'user.png' );
			}
		}?> 
		<table class="adminform">
		<tr>
			<td width="3%"></td>
			<td align="left" valign="top" width="30%">
			<strong><?php echo JText::_('COM_SF_MOVE_TO_LIST'); ?>:</strong>
			<br />
			<?php echo $listusersList ?>
			<br /><br />
			</td>
			<td align="left" valign="top" width="20%">
			<strong><?php echo JText::_('COM_SF_USERS_BEIGN_MOVED'); ?>:</strong>
			<br />
			<?php
			echo "<ol>";
			foreach ( $items as $item ) {
				echo "<li>". $item->name .", " . $item->lastname . "(".$item->listname.")</li>";
			}
			echo "</ol>";
			?>
			</td>
			<td valign="top">
			<?php echo JText::_('COM_SF_THIS_MOVE_USERS_TO_LIST'); ?>
			</td>
		</tr>
		</table>
		<br /><br />

		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="task" value="" />
		<?php
		foreach ( $cid as $id ) {
			echo "\n <input type=\"hidden\" name=\"cid[]\" value=\"$id\" />";
		}
		?>
		</form>
		<?php
		EF_menu_footer();
	} 	
	
			#######################################
			###	--- ---  MANAGE EMAILS	--- --- ###
	
	function SF_showEmailsList( &$rows, &$pageNav, $option ) {
		global $my;

		mosCommonHTML::loadOverlib();
		EF_menu_header();
		?>
		<form action="index.php" method="post" name="adminForm">
		<?php if (!class_exists('JToolBarHelper')) { ?>
		<table class="adminheading">
		<tr>
			<th>
			<?php echo _SURVEY_FORCE_COMP_NAME?>: <small><?php echo JText::_('COM_SF_EMAILS'); ?></small>
			</th>
		</tr>
		</table>
		<?php } else { 
			JToolBarHelper::title( JText::_('COM_SF_EMAILS'), 'inbox.png' );
		}?> 
		<table class="adminlist">
		<tr>
			<th width="20">#</th>
			<th width="20" class="title"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($rows); ?>);" /></th>
			<th class="title"><?php echo JText::_('COM_SF_SUBJECT'); ?></th>
			<th class="title"><?php echo JText::_('COM_SF_BODY'); ?></th>
			<th class="title"><?php echo JText::_('COM_SF_REPLY_TO'); ?></th>
		</tr>
		<?php
		$k = 0;
		for ($i=0, $n=count($rows); $i < $n; $i++) {
			$row = $rows[$i];

			$link = "index.php?option=com_surveyforce&task=editA_email&id=". $row->id;

			$checked = mosHTML::idBox( $i, $row->id);
			?>
			<tr class="<?php echo "row$k"; ?>">
				<td><?php echo $pageNav->rowNumber( $i ); ?></td>
				<td><?php echo $checked; ?></td>
				<td align="left">
					<a href="<?php echo $link; ?>" title="<?php echo JText::_('COM_SF_EDIT_EMAIL'); ?>">
					<?php echo $row->email_subject; ?>
					</a>
				</td>
				<td align="left">
					<?php echo strip_tags($row->email_body); ?>
				</td>
				<td align="left">
					<?php echo $row->email_reply; ?>
				</td>
			</tr>
			<?php
			$k = 1 - $k;
		}
		?>
		</table>
		<?php echo $pageNav->getListFooter(); ?>

		<input type="hidden" name="option" value="com_surveyforce" />
		<input type="hidden" name="task" value="emails" />
		
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="hidemainmenu" value="0">
		</form>
		<?php
		EF_menu_footer();
	}
	
	function SF_editEmail( &$row, &$lists, $option ) {
		global $mosConfig_live_site,$my;

		mosCommonHTML::loadOverlib();
		EF_menu_header();
		?>
		<script language="javascript" type="text/javascript">
		<!--
		Joomla.submitbutton = function(pressbutton) {
			var form = document.adminForm;

			if (pressbutton == 'cancel_email') {
				submitform( pressbutton );
				return;
			}
			// do field validation
			var reg_email = /[0-9a-z_]+@[0-9a-z_^.]+.[a-z]{2,3}/;

			if (form.email_subject.value == ""){
				alert( "<?php echo JText::_('COM_SF_EMAIL_MUST_HAVE_SUBJECT'); ?>" );
			} else if (form.email_body.value == ""){
				alert( "<?php echo JText::_('COM_SF_EMAIL_MUST_HAVE_BODY'); ?>" );
			} else if (form.email_reply.value == ""){
				alert( "<?php echo JText::_('COM_SF_EMAIL_MUST_HAVE_REPLY'); ?>" );
			} else if (!reg_email.test(form.email_reply.value)) {
				alert( "<?php echo JText::_('COM_SF_PLEASE_ENTER_VALID_REPLY'); ?>" );
			} else {
				submitform( pressbutton );
			}
		}
		//-->
		</script>
		
		<form action="index.php" method="post" name="adminForm">
		<?php if (!class_exists('JToolBarHelper')) { ?>
		<table class="adminheading">
		<tr>
			<th class="edit">
			<?php echo _SURVEY_FORCE_COMP_NAME?>:
			<small>
			<?php echo $row->id ? JText::_('COM_SF_EDIT_EMAIL') : JText::_('COM_SF_NEW_EMAIL');?>
			</small>
			</th>
		</tr>
		</table>
		<?php } else { 
			JToolBarHelper::title( ($row->id ? JText::_('COM_SF_EDIT_EMAIL') : JText::_('COM_SF_NEW_EMAIL')), 'inbox.png' );
		}?>
		<table width="100%" class="adminform">
			<tr>
				<th colspan="2"><?php echo JText::_('COM_SF_EMAIL_DETAILS'); ?></th>
			<tr>
			<tr>
				<td align="right" width="20%"><?php echo JText::_('COM_SF_SUBJECT'); ?>:</td>
				<td><input class="text_area" type="text" name="email_subject" size="50" maxlength="100" value="<?php echo $row->email_subject; ?>" /></td>
			</tr>
			<tr>
				<td align="right" width="20%" valign="top"><?php echo JText::_('COM_SF_BODY'); ?>:</td>
				<td><textarea class="text_area" name="email_body" cols="36" rows="5"><?php echo $row->email_body; ?></textarea>
				<br><?php echo JText::_('COM_SF_USE_THE_FOLLOWING'); ?> #name#, #link#.</td>
			</tr>
			<tr>
				<td align="right" width="20%"><?php echo JText::_('COM_SF_REPLY_TO'); ?>:</td>
				<td><input class="text_area" type="text" name="email_reply" size="50" maxlength="100" value="<?php echo $row->email_reply; ?>" /></td>
			</tr>
		</table>
		<br />
		<input type="hidden" name="option" value="com_surveyforce" />
		<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="user_id" value="<?php echo $my->id;?>" />
		</form>
		<?php
		EF_menu_footer();
	}
	
			#######################################
			###	--- ---   	REPORTS 	--- --- ###
	
	function SF_ViewReports( &$rows, &$lists, &$pageNav, $option ) {
		global $my;

		mosCommonHTML::loadOverlib();
		EF_menu_header();
		?>

		<form action="index.php" method="post" name="adminForm" target="">
		<?php  if (!class_exists('JToolBarHelper')) { ?>
		<table class="adminheading">
		<tr>
			<th class="edit">
			<?php echo _SURVEY_FORCE_COMP_NAME?>: <small><?php echo JText::_('COM_SF_REPORTS'); ?></small>
			</th>
			<td align="right" nowrap>
				<table>
				<tr><td nowrap align="right">
					<?php echo $lists['filt_status'];?>
					<?php echo $lists['survey'];?>
					<?php echo $lists['filt_utype'];?>
				</td></tr>
				<tr><td align="right" nowrap>
					<?php echo $lists['filt_ulist'];?>
				</td></tr>
				<tr><td align="right" nowrap>
					<table>
					<?php
						$jj = 0;
						foreach ($lists['filter_quest'] as $list1) { ?>
						<tr>
							<td valign="top">
								<?php echo JText::_('COM_SF_CHOOSE_FROM_QUESTION'); ?>
							</td>
							<td valign="top"><?php echo $list1;?></td>
							<td valign="top">
							<?php if (isset($lists['filter_quest_ans'][$jj])) { ?>
								<?php echo JText::_('COM_SF_WHERE_THE_ANSWER_IS'); ?></td><td><?php echo $lists['filter_quest_ans'][$jj];?>
							<?php } else { echo "</td>&nbsp;<td>&nbsp;";}
						echo "</td></tr>";
						 $jj ++;?>
					<?php }?>
					</table>
				</td></tr>
				</table>
			</td> 			
		</tr>
		</table>
		<?php } else { 
			JToolBarHelper::title( JText::_('COM_SF_REPORTS'), 'print.png' );?>
			<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td align="left" width="100%">&nbsp;</td>
				<td nowrap="nowrap">
					<table>
					<tr><td nowrap align="right">
						<?php echo $lists['filt_status'];?>
						<?php echo $lists['survey'];?>
						<?php echo $lists['filt_utype'];?>
					</td></tr>
					<tr><td align="right" nowrap>
						<?php echo $lists['filt_ulist'];?>
					</td></tr>
					<tr><td align="right" nowrap>
						<table>
						<?php
							$jj = 0;
							foreach ($lists['filter_quest'] as $list1) { ?>
							<tr>
								<td valign="top">
									<?php echo JText::_('COM_SF_CHOOSE_FROM_QUESTION'); ?>
								</td>
								<td valign="top"><?php echo $list1;?></td>
								<td valign="top">
								<?php if (isset($lists['filter_quest_ans'][$jj])) { ?>
									<?php echo JText::_('COM_SF_WHERE_THE_ANSWER_IS'); ?></td><td><?php echo $lists['filter_quest_ans'][$jj];?>
								<?php } else { echo "</td>&nbsp;<td>&nbsp;";}
							echo "</td></tr>";
							 $jj ++;?>
						<?php }?>
						</table>
					</td></tr>
					</table>
				</td> 			
			</tr>
			</table>

		<?php }?>
		<table class="adminlist">
		<tr>
			<th width="20">#</th>
			<th width="20" class="title"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($rows); ?>);" /></th>
			<th class="title"><?php echo JText::_('COM_SF_DATE'); ?></th>
			<th class="title"><?php echo JText::_('COM_SF_STATUS'); ?></th>
			<th class="title"><?php echo JText::_('COM_SF_SURVEY'); ?></th>
			<th class="title"><?php echo JText::_('COM_SF_USERTYPE'); ?></th>
			<th class="title"><?php echo JText::_('COM_SF_USER_INFO'); ?></th>
		</tr>
		<?php
		$k = 0;
		for ($i=0, $n=count($rows); $i < $n; $i++) {
			$row = $rows[$i];

			$link 	= 'index.php?option='.$option.'&task=view_result&id='. $row->id;
			$checked = mosHTML::idBox( $i, $row->id);
			?>
			<tr class="<?php echo "row$k"; ?>">
				<td align="center"><?php echo $pageNav->rowNumber( $i ); ?></td>
				<td><?php echo $checked; ?></td>
				<td align="left">
					<a href="<?php echo $link; ?>" title="<?php echo JText::_('COM_SF_VIEW_RESULTS'); ?>">
						<?php echo mosFormatDate( $row->sf_time, _CURRENT_SERVER_TIME_FORMAT ); ?>
					</a>
				</td>
				<td align="left">
					<?php echo ($row->is_complete)?JText::_('COM_SF_COMPLETED'):JText::_('COM_SF_NOT_COMPLETED'); ?>
				</td>
				<td align="left">
					<?php echo $row->survey_name; ?>
				</td>
				<td align="left">
					<?php switch($row->usertype) {
							case '0': echo JText::_('COM_SF_GUEST'); break;
							case '1': echo JText::_('COM_SF_REGISTERED_USER'); break;
							case '2': echo JText::_('COM_SF_INVITED_USER'); break;
						} ?>
				</td>
				<td align="left">
					<?php switch($row->usertype) {
							case '0': echo JText::_('COM_SF_ANONYMOUS'); break;
							case '1': echo $row->reg_username.", ".$row->reg_name." (".$row->reg_email.")"; break;
							case '2': echo $row->inv_name." ".$row->inv_lastname." (".$row->inv_email.")"; break;
						} ?>
				</td>
			</tr>
			<?php
			$k = 1 - $k;
		}
		?>
		</table>
		<?php echo $pageNav->getListFooter(); ?>

		<input type="hidden" name="option" value="com_surveyforce" />
		<input type="hidden" name="task" value="reports" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="hidemainmenu" value="0">
		</form>
		
		<script language="javascript" type="text/javascript">		

		
		Joomla.submitbutton = function(pressbutton) {
			var form = document.adminForm;

			if (pressbutton == 'rep_pdf' || pressbutton == 'rep_pdf_sum' || pressbutton == 'rep_pdf_sum_pc') { 
				if (form.surv_id.selectedIndex<1) {
					alert("<?php echo JText::_('COM_SF_SELECT_SURVEY'); ?>");
					return;
				}
				form.target = '_blank';
				submitform( pressbutton );
				return;
			}
			
			if (pressbutton == 'del_rep_all' ) {
				if (confirm("<?php echo JText::_('COM_SF_REALLY_WANT_DELETE_RESULTS'); ?>")) {
					form.target = '';
					submitform( pressbutton );
				}
				return;
			}
			
			form.target = '';
			submitform( pressbutton );
		}
	
		</script>
		<?php
		EF_menu_footer();
	}
	
	//PARTE NUEVA
	
	function SF_GenerateExcelNL( &$rows, &$lists, &$pageNav, $option ) {
global $my;
	
		mosCommonHTML::loadOverlib();
		EF_menu_header();
		?>
		
				<form action="index.php" method="post" name="adminForm" target="">
				<?php  if (!class_exists('JToolBarHelper')) { ?>
				
				<?php } else { 
					JToolBarHelper::title( JText::_('Excel Report'), 'print.png' );?>
					
		
				<?php }?>
				
				
						<table class="adminlist">
		<tr>
			<th width="20">#</th>
			<th width="20" class="title"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($rows); ?>);" /></th>
			<th class="title"><?php echo JText::_('COM_SF_DATE'); ?></th>
			<th class="title"><?php echo JText::_('COM_SF_STATUS'); ?></th>
			<th class="title"><?php echo JText::_('COM_SF_SURVEY'); ?></th>
			<th class="title"><?php echo JText::_('COM_SF_USERTYPE'); ?></th>
			<th class="title"><?php echo JText::_('COM_SF_USER_INFO'); ?></th>
		</tr>
		<?php
		$k = 0;
		for ($i=0, $n=count($rows); $i < $n; $i++) {
			$row = $rows[$i];

			$link 	= 'index.php?option='.$option.'&task=view_result&id='. $row->id;
			$checked = mosHTML::idBox( $i, $row->id);
			?>
			<tr class="<?php echo "row$k"; ?>">
				<td align="center"><?php echo $pageNav->rowNumber( $i ); ?></td>
				<td><?php echo $checked; ?></td>
				<td align="left">
					<a href="<?php echo $link; ?>" title="<?php echo JText::_('COM_SF_VIEW_RESULTS'); ?>">
						<?php echo mosFormatDate( $row->sf_time, _CURRENT_SERVER_TIME_FORMAT ); ?>
					</a>
				</td>
				<td align="left">
					<?php echo ($row->is_complete)?JText::_('COM_SF_COMPLETED'):JText::_('COM_SF_NOT_COMPLETED'); ?>
				</td>
				<td align="left">
					<?php echo $row->survey_name; ?>
				</td>
				<td align="left">
					<?php switch($row->usertype) {
							case '0': echo JText::_('COM_SF_GUEST'); break;
							case '1': echo JText::_('COM_SF_REGISTERED_USER'); break;
							case '2': echo JText::_('COM_SF_INVITED_USER'); break;
						} ?>
				</td>
				<td align="left">
					<?php switch($row->usertype) {
							case '0': echo JText::_('COM_SF_ANONYMOUS'); break;
							case '1': echo $row->reg_username.", ".$row->reg_name." (".$row->reg_email.")"; break;
							case '2': echo $row->inv_name." ".$row->inv_lastname." (".$row->inv_email.")"; break;
						} ?>
				</td>
			</tr>
			<?php
			$k = 1 - $k;
		}
		?>
		</table>
		<?php echo $pageNav->getListFooter(); ?>
				
				<br/><br/>
				<input type="submit" value="SEND REPORT!" name="send"/><br/>
				<br/>
				<?php echo $lists['surveySend'];?> 
		
				<input type="hidden" name="option" value="com_surveyforce" />
				<input type="hidden" name="task" value="generateExcelNL" />
				<input type="hidden" name="boxchecked" value="0" />
				<input type="hidden" name="hidemainmenu" value="0">
				</form>
				
				<script language="javascript" type="text/javascript">		

		
		Joomla.submitbutton = function(pressbutton) {
			var form = document.adminForm;

			if (pressbutton == 'rep_pdf' || pressbutton == 'rep_pdf_sum' || pressbutton == 'rep_pdf_sum_pc') { 
				if (form.surv_id.selectedIndex<1) {
					alert("<?php echo JText::_('COM_SF_SELECT_SURVEY'); ?>");
					return;
				}
				form.target = '_blank';
				submitform( pressbutton );
				return;
			}
			
			if (pressbutton == 'del_rep_all' ) {
				if (confirm("<?php echo JText::_('COM_SF_REALLY_WANT_DELETE_RESULTS'); ?>")) {
					form.target = '';
					submitform( pressbutton );
				}
				return;
			}
			
			form.target = '';
			submitform( pressbutton );
		}
	
		</script>
		<?php
		EF_menu_footer();		
			}
			
			function SF_GenerateExcel( &$rows, &$lists, &$pageNav, $option ) {
				global $my;
			
				mosCommonHTML::loadOverlib();
				EF_menu_header();
				?>
					
							<form action="index.php" method="post" name="adminForm" target="">
							<?php  if (!class_exists('JToolBarHelper')) { ?>
							
							<?php } else { 
								JToolBarHelper::title( JText::_('Excel Report'), 'print.png' );?>
								
					
							<?php }?>
							
							
									<table class="adminlist">
					<tr>
						<th width="20">#</th>
						<th width="20" class="title"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($rows); ?>);" /></th>
						<th class="title"><?php echo JText::_('COM_SF_DATE'); ?></th>
						<th class="title"><?php echo JText::_('COM_SF_STATUS'); ?></th>
						<th class="title"><?php echo JText::_('COM_SF_SURVEY'); ?></th>
						<th class="title"><?php echo JText::_('COM_SF_USERTYPE'); ?></th>
						<th class="title"><?php echo JText::_('COM_SF_USER_INFO'); ?></th>
					</tr>
					<?php
					$k = 0;
					for ($i=0, $n=count($rows); $i < $n; $i++) {
						$row = $rows[$i];
			
						$link 	= 'index.php?option='.$option.'&task=view_result&id='. $row->id;
						$checked = mosHTML::idBox( $i, $row->id);
						?>
						<tr class="<?php echo "row$k"; ?>">
							<td align="center"><?php echo $pageNav->rowNumber( $i ); ?></td>
							<td><?php echo $checked; ?></td>
							<td align="left">
								<a href="<?php echo $link; ?>" title="<?php echo JText::_('COM_SF_VIEW_RESULTS'); ?>">
									<?php echo mosFormatDate( $row->sf_time, _CURRENT_SERVER_TIME_FORMAT ); ?>
								</a>
							</td>
							<td align="left">
								<?php echo ($row->is_complete)?JText::_('COM_SF_COMPLETED'):JText::_('COM_SF_NOT_COMPLETED'); ?>
							</td>
							<td align="left">
								<?php echo $row->survey_name; ?>
							</td>
							<td align="left">
								<?php switch($row->usertype) {
										case '0': echo JText::_('COM_SF_GUEST'); break;
										case '1': echo JText::_('COM_SF_REGISTERED_USER'); break;
										case '2': echo JText::_('COM_SF_INVITED_USER'); break;
									} ?>
							</td>
							<td align="left">
								<?php switch($row->usertype) {
										case '0': echo JText::_('COM_SF_ANONYMOUS'); break;
										case '1': echo $row->reg_username.", ".$row->reg_name." (".$row->reg_email.")"; break;
										case '2': echo $row->inv_name." ".$row->inv_lastname." (".$row->inv_email.")"; break;
									} ?>
							</td>
						</tr>
						<?php
						$k = 1 - $k;
					}
					?>
					</table>
					<?php echo $pageNav->getListFooter(); ?>
							
							<br/><br/>
							<input type="submit" value="SEND REPORT!" name="send"/><br/>
							<br/>
							<?php echo $lists['surveySend'];?> 
					
							<input type="hidden" name="option" value="com_surveyforce" />
							<input type="hidden" name="task" value="generateExcel" />
							<input type="hidden" name="boxchecked" value="0" />
							<input type="hidden" name="hidemainmenu" value="0">
							</form>
							
							<script language="javascript" type="text/javascript">		
			
					
					Joomla.submitbutton = function(pressbutton) {
						var form = document.adminForm;
			
						if (pressbutton == 'rep_pdf' || pressbutton == 'rep_pdf_sum' || pressbutton == 'rep_pdf_sum_pc') { 
							if (form.surv_id.selectedIndex<1) {
								alert("<?php echo JText::_('COM_SF_SELECT_SURVEY'); ?>");
								return;
							}
							form.target = '_blank';
							submitform( pressbutton );
							return;
						}
						
						if (pressbutton == 'del_rep_all' ) {
							if (confirm("<?php echo JText::_('COM_SF_REALLY_WANT_DELETE_RESULTS'); ?>")) {
								form.target = '';
								submitform( pressbutton );
							}
							return;
						}
						
						form.target = '';
						submitform( pressbutton );
					}
				
					</script>
					<?php
					EF_menu_footer();		
						}
					
					//FIN PARTE NUEVA
						
		
						
						
						
						
						
		function SF_GenerateExcelSP( &$rows, &$lists, &$pageNav, $option, $template, $surveyID ) {
		global $my;
	
		mosCommonHTML::loadOverlib();
		EF_menu_header();
		
		echo "<h2>".$template."</h2>";
		
		?>
		
				<form action="index.php" method="post" name="adminForm" target="">
				<?php  if (!class_exists('JToolBarHelper')) { ?>
				
				<?php } else { 
					JToolBarHelper::title( JText::_('Excel Report'), 'print.png' );?>
					
		
				<?php }?>
				
				
						<table class="adminlist">
		<tr>
			<th width="20">#</th>
			<th width="20" class="title"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($rows); ?>);" /></th>
			<th class="title"><?php echo JText::_('COM_SF_DATE'); ?></th>
			<th class="title"><?php echo JText::_('COM_SF_STATUS'); ?></th>
			<th class="title"><?php echo JText::_('COM_SF_SURVEY'); ?></th>
			<th class="title"><?php echo JText::_('COM_SF_USERTYPE'); ?></th>
			<th class="title"><?php echo JText::_('COM_SF_USER_INFO'); ?></th>
		</tr>
		<?php
		$k = 0;
		for ($i=0, $n=count($rows); $i < $n; $i++) {
			$row = $rows[$i];

			$link 	= 'index.php?option='.$option.'&task=view_result&id='. $row->id;
			$checked = mosHTML::idBox( $i, $row->id);
			?>
			<tr class="<?php echo "row$k"; ?>">
				<td align="center"><?php echo $pageNav->rowNumber( $i ); ?></td>
				<td><?php echo $checked; ?></td>
				<td align="left">
					<a href="<?php echo $link; ?>" title="<?php echo JText::_('COM_SF_VIEW_RESULTS'); ?>">
						<?php echo mosFormatDate( $row->sf_time, _CURRENT_SERVER_TIME_FORMAT ); ?>
					</a>
				</td>
				<td align="left">
					<?php echo ($row->is_complete)?JText::_('COM_SF_COMPLETED'):JText::_('COM_SF_NOT_COMPLETED'); ?>
				</td>
				<td align="left">
					<?php echo $row->survey_name; ?>
				</td>
				<td align="left">
					<?php switch($row->usertype) {
							case '0': echo JText::_('COM_SF_GUEST'); break;
							case '1': echo JText::_('COM_SF_REGISTERED_USER'); break;
							case '2': echo JText::_('COM_SF_INVITED_USER'); break;
						} ?>
				</td>
				<td align="left">
					<?php switch($row->usertype) {
							case '0': echo JText::_('COM_SF_ANONYMOUS'); break;
							case '1': echo $row->reg_username.", ".$row->reg_name." (".$row->reg_email.")"; break;
							case '2': echo $row->inv_name." ".$row->inv_lastname." (".$row->inv_email.")"; break;
						} ?>
				</td>
			</tr>
			<?php
			$k = 1 - $k;
		}
		?>
		</table>
		<?php echo $pageNav->getListFooter(); ?>
				
				<br/><br/>
				<input type="submit" value="SEND REPORT!" name="send"/><br/>
				<br/>
				<?php echo $lists['surveySend'];?> 
		
				<input type="hidden" name="option" value="com_surveyforce" />
				<input type="hidden" name="task" value="generateExcelSP" />
				<input type="hidden" name="template" value="<?php echo $template; ?>" />
				<inpu ttype="hidden" name="surv_id" value="$surveyID" />
				<input type="hidden" name="boxchecked" value="0" />
				<input type="hidden" name="hidemainmenu" value="0">
				</form>
				
				<script language="javascript" type="text/javascript">		

		
		Joomla.submitbutton = function(pressbutton) {
			var form = document.adminForm;

			if (pressbutton == 'rep_pdf' || pressbutton == 'rep_pdf_sum' || pressbutton == 'rep_pdf_sum_pc') { 
				if (form.surv_id.selectedIndex<1) {
					alert("<?php echo JText::_('COM_SF_SELECT_SURVEY'); ?>");
					return;
				}
				form.target = '_blank';
				submitform( pressbutton );
				return;
			}
			
			if (pressbutton == 'del_rep_all' ) {
				if (confirm("<?php echo JText::_('COM_SF_REALLY_WANT_DELETE_RESULTS'); ?>")) {
					form.target = '';
					submitform( pressbutton );
				}
				return;
			}
			
			form.target = '';
			submitform( pressbutton );
		}
	
		</script>
		<?php
		EF_menu_footer();		
			}
			
		
						
						
						
						
		//FIN PARTE NUEVA
	
	function SF_ViewRepResult( $option, $start_data, $survey_data, $questions_data )
	{
		EF_menu_header();
		?>
		<script language="javascript" type="text/javascript">
		<!--
		Joomla.submitbutton = function(pressbutton) {
			var form = document.adminForm;

			if (pressbutton == 'rep_print') { 
				form.target = '_blank';
				submitform( pressbutton );
				return;
			}
			
			form.target = '';
			submitform( pressbutton );
		}
		//-->
		</script>
		<form action="index.php" method="post" name="adminForm">
		<?php if (!class_exists('JToolBarHelper')) { ?>
		<table class="adminheading">
		<tr>
			<th class="edit">
			<?php echo _SURVEY_FORCE_COMP_NAME?>:
			<small>
				<?php echo JText::_('COM_SF_RESULTS'); ?>
			</small>
			</th>
		</tr>
		</table>
		<?php } else { 
			JToolBarHelper::title( JText::_('COM_SF_RESULTS'), 'print.png' );
		}?> 

		<input type="hidden" name="option" value="com_surveyforce" />
		<input type="hidden" name="id" value="<?php echo $start_data[0]->id; ?>" />
		<input type="hidden" name="task" value="" />
		</form>
		<table class="adminlist">
		<tr>
			<th colspan="2" align="left"><?php echo JText::_('COM_SF_SURVEY_INFORMATION'); ?></th>
		</tr>
		<tr><td><b><?php echo JText::_('COM_SF_NAME'); ?>: </b><?php echo $survey_data[0]->sf_name?><br>
		<b><?php echo JText::_('COM_SF_DESCRIPTION'); ?></b><br><?php echo nl2br($survey_data[0]->sf_descr)?><br>
		<b><?php echo JText::_('COM_SF_START_AT'); ?>: </b><?php echo mosFormatDate( $start_data[0]->sf_time, _CURRENT_SERVER_TIME_FORMAT )?><br>
		<b><?php echo JText::_('COM_SF_USER'); ?> :</b>
						<?php switch($start_data[0]->usertype) {
							case '0': echo JText::_('COM_SF_ANONYMOUS'); break;
							case '1': echo JText::_('COM_SF_REGISTERED_USER').": ".$start_data[0]->reg_username.", ".$start_data[0]->reg_name." (".$start_data[0]->reg_email.")"; break;
							case '2': echo JText::_('COM_SF_INVITED_USER').": ".$start_data[0]->inv_name." ".$start_data[0]->inv_lastname." (".$start_data[0]->inv_email.")"; break;
						} ?>

		</td></tr>
		</table>
		<br>

		<?php
		foreach ($questions_data as $qrow) { 
			$k = 1;?>
		<table class="adminlist">
		<tr>
			<th colspan="2" align="left"><?php echo $qrow->sf_qtext?></th>
		</tr>
		<?php
			switch ($qrow->sf_qtype) {
				case 2:
				case 3:
					foreach ($qrow->answer as $arow) {
						$img_ans = $arow->alt_text ? "<img src='".JURI::root()."administrator/components/com_surveyforce/images/tick.png'  border='0' />" : '';
						echo "<tr class='row".$k."'><td width='300px'>" . $arow->f_text . "</td><td>" . $img_ans . "</td></tr>";
						$k = 1 - $k;
					}
				break;
				case 1:	echo "<tr class='row".$k."'><td colspan=2><b>Scale: </b>" . $qrow->scale . "</td></tr>";$k = 1 - $k;
				case 5:
				case 6:
				case 9:
					foreach ($qrow->answer as $arow) {
						echo "<tr class='row".$k."'><td width='300px'>" . $arow->f_text . "</td><td>" . $arow->alt_text . "</td></tr>";
						$k = 1 - $k;
					}
				break;
				case 4:
					if (isset($qrow->answer_count)){
						$tmp = JText::_('COM_SF_1ST_ANSWER');
						for($ii = 1; $ii <= $qrow->answer_count; $ii++) {
							if ($ii == 2) $tmp = JText::_('COM_SF_SECOND_ANSWER');
							elseif($ii == 3)	$tmp = JText::_('COM_SF_THIRD_ANSWER');
							elseif ($ii > 3) $tmp = $ii. JText::_('COM_SF_X_ANSWER');
							foreach($qrow->answer as $answer) {
								if ($answer->ans_field == $ii) {
									echo "<tr class='row".$k."'><td width='300px'>".$tmp.nl2br(($answer->ans_txt == ''? JText::_('COM_SF_NO_ANSWER'):$answer->ans_txt))."</td><td>&nbsp;</td></tr>";
									$k = 1 - $k;
									$tmp = -1;
									}
							}
							if ($tmp != -1)	{
								echo "<tr class='row".$k."'><td width='300px'>".$tmp.JText::_('COM_SF_NO_ANSWER')."</td><td>&nbsp;</td></tr>";
								$k = 1 - $k;
							}
						}
					}
					else {
						echo "<tr class='row".$k."'><td width='300px'>".nl2br($qrow->answer)."</td><td>&nbsp;</td></tr>";
					}
					break;
				default:
					echo "<tr class='row".$k."'><td width='300px'>".nl2br($qrow->answer)."</td><td>&nbsp;</td></tr>";
				break;
			}
			?>
		</table>
		<?php if ($qrow->sf_impscale) {?>
			<table class="adminlist">
			<tr>
				<td colspan="2" align="left"><b><?php echo $qrow->iscale_name?></b></td>
			</tr>
			<?php
				foreach ($qrow->answer_imp as $arow) {
					$img_ans = $arow->alt_text ? "<img src='".JURI::root()."administrator/components/com_surveyforce/images/tick.png'  border='0' />" : '';
					echo "<tr class='row".$k."'><td width='300px'>" . $arow->f_text . "</td><td>" . $img_ans . "</td></tr>";
					$k = 1 - $k;
				}
			?>
			</table>
		<?php } ?>
		<br>
		<?php }
		EF_menu_footer();
	}

	function SF_ViewRepSurv_List( $option, $survey_data, $questions_data, $is_list = 0, $list_id = 0)
	{
		global $task, $mosConfig_live_site;
		EF_menu_header();
		?>
		<script language="javascript" type="text/javascript">
		<!--
		Joomla.submitbutton = function(pressbutton) {
			var form = document.adminForm;

			if (pressbutton == 'rep_surv_print') { 
				form.target = '_blank';				
				submitform( pressbutton );
				return;
			}
			if (pressbutton == 'rep_list_print') { 
				form.target = '_blank';				
				submitform( pressbutton );
				return;
			}

			
			form.target = '';
			submitform( pressbutton );
		}
		//-->
		</script>
		<form action="index.php" method="post" name="adminForm">
		<?php if (!class_exists('JToolBarHelper')) { ?>
		<table class="adminheading">
		<tr>
			<th class="edit">
			<?php echo _SURVEY_FORCE_COMP_NAME?>:
			<small>
				<?php if ($is_list == 1) { echo JText::_('COM_SF_USERS');} else { echo JText::_('COM_SF_SURVEY');}?><?php echo JText::_('COM_SF_RESULTS'); ?>
			</small>
			</th>
		</tr>
		</table>
		<?php } else { 		
			JToolBarHelper::title( ($is_list == 1? JText::_('COM_SF_USERS'): JText::_('COM_SF_SURVEY')).JText::_('COM_SF_RESULTS'), 'print.png' );
		}?> 

		<input type="hidden" name="option" value="com_surveyforce" />
		<input type="hidden" name="id" value="<?php echo ($task == 'view_rep_list')?$list_id:$survey_data->id; ?>" />
		<input type="hidden" name="task" value="" />
		</form>
		<table class="adminlist">
		<tr>
			<th colspan="2" align="left"><?php echo JText::_('COM_SF_SURVEY_INFORMATION'); ?></th>
		</tr>
		<tr><td><b><?php echo JText::_('COM_SF_NAME'); ?>: </b><?php echo $survey_data->sf_name?><br>
		<b><?php echo JText::_('COM_SF_DESCRIPTION'); ?> </b><br><?php echo nl2br($survey_data->sf_descr)?><br>
		</td></tr>
		</table>
		<br>
		<table class="adminlist">
		<tr>
			<th align="left"><?php echo JText::_('COM_SF_SURVEY_INFORMATION'); ?></th>
		</tr>
		</table>
		<?php if ($is_list == 1) { ?>
			<table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="250px" valign="top">
			<img src="<?php echo $mosConfig_live_site?>/administrator/components/com_surveyforce/includes/draw_grid.php?total=<?php echo ($survey_data->total_starts > $survey_data->total_inv_users)?$survey_data->total_starts:$survey_data->total_inv_users?>&grids=<?php echo $survey_data->total_inv_users.','.$survey_data->total_starts.','.$survey_data->total_completes?>">
	
			</td><td valign="top"><div style="padding-top:1px ">
			<table class="adminlist" cellpadding="0" cellspacing="0">
			<tr class="row1" height="25px"><td><b><?php echo $survey_data->total_inv_users?></b> - <?php echo JText::_('COM_SF_TOTAL_INVITED_USERS'); ?></td></tr>
			<tr class="row1" height="25px"><td><b><?php echo $survey_data->total_starts?></b> - <?php echo JText::_('COM_SF_TOTAL_STARTS_OF_SURVEY'); ?></td></tr>
			<tr class="row1" height="25px"><td><b><?php echo $survey_data->total_completes?></b> - <?php echo JText::_('COM_SF_TOTAL_COMPLETES_OF_SURVEY'); ?></td></tr>
			</table>
			</td></tr></table>
		<?php } else { ?>
			<table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="250px" valign="top">
			<img src="<?php echo $mosConfig_live_site?>/administrator/components/com_surveyforce/includes/draw_grid.php?total=<?php echo $survey_data->total_starts?>&grids=<?php echo $survey_data->total_starts.','.$survey_data->total_gstarts.','.$survey_data->total_rstarts.','.$survey_data->total_istarts.','.$survey_data->total_completes.','.$survey_data->total_gcompletes.','.$survey_data->total_rcompletes.','.$survey_data->total_icompletes?>">
	
			</td><td valign="top"><div style="padding-top:1px ">
			<table class="adminlist" cellpadding="0" cellspacing="0">
			<tr class="row1" height="25px"><td><b><?php echo $survey_data->total_starts?></b> - <?php echo JText::_('COM_SF_TOTAL_STARTS_OF_SURVEY'); ?></td></tr>
			<tr class="row1" height="25px"><td><b><?php echo $survey_data->total_gstarts?></b> - <?php echo JText::_('COM_SF_TOTAL_STARTS_OF_SURVEY_GUEST'); ?></td></tr>
			<tr class="row1" height="25px"><td><b><?php echo $survey_data->total_rstarts?></b> - <?php echo JText::_('COM_SF_TOTAL_STARTS_OF_SURVEY_REGISTERED'); ?></td></tr>
			<tr class="row1" height="25px"><td><b><?php echo $survey_data->total_istarts?></b> - <?php echo JText::_('COM_SF_TOTAL_STARTS_OF_SURVEY_INVITED'); ?></td></tr>
			<tr class="row1" height="25px"><td><b><?php echo $survey_data->total_completes?></b> - <?php echo JText::_('COM_SF_TOTAL_COMPLETES_OF_SURVEY'); ?></td></tr>
			<tr class="row1" height="25px"><td><b><?php echo $survey_data->total_gcompletes?></b> - <?php echo JText::_('COM_SF_TOTAL_COMPLETES_OF_SURVEY_GUEST'); ?></td></tr>
			<tr class="row1" height="25px"><td><b><?php echo $survey_data->total_rcompletes?></b> - <?php echo JText::_('COM_SF_TOTAL_COMPLETES_OF_SURVEY_REGISTERED'); ?></td></tr>
			<tr class="row1" height="25px"><td><b><?php echo $survey_data->total_icompletes?></b> - <?php echo JText::_('COM_SF_TOTAL_COMPLETES_OF_SURVEY_INVITED'); ?></td></tr>
			</table>
			</td></tr></table>
		<?php 
		}
		$tmp_data = array();
		$total = 0;
		$i = 0;
		foreach ($questions_data as $qrow) {
			switch ($qrow->sf_qtype) {
				case 2:
				case 3:
				case 4:
					if (isset($qrow->answer_count)) {
						$tmp = JText::_('COM_SF_1ST_ANSWER');
						?><table class="adminlist"><tr><th align="left"><?php echo $qrow->sf_qtext?></th></tr></table><?php
						for($ii = 1; $ii <= $qrow->answer_count; $ii++) {
							if ($ii == 2) $tmp = JText::_('COM_SF_SECOND_ANSWER');
							elseif($ii == 3)	$tmp = JText::_('COM_SF_THIRD_ANSWER');
							elseif ($ii > 3) $tmp = $ii.JText::_('COM_SF_TH_ANSWER');
							$total = $qrow->total_answers;
							$i = 0;
							$tmp_data = array();
							if (count($qrow->answer[$ii-1]) > 0 ) {
								foreach ($qrow->answer[$ii-1] as $arow) {
									$tmp_data[$i] = $arow->ans_count;
									$i++;
								}
								?>
								<br>
								<table class="adminlist"><tr><td align="left"><b><?php echo $tmp?></b></td></tr></table>
								<table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="250px" valign="top">
								<img src="<?php echo $mosConfig_live_site?>/administrator/components/com_surveyforce/includes/draw_grid.php?total=<?php echo $total?>&grids=<?php echo implode(',',$tmp_data)?>">
								</td><td valign="top"><div style="padding-top:1px ">
								<table class="adminlist" cellpadding="0" cellspacing="0">
								<?php foreach ($qrow->answer[$ii-1] as $arow) {
									echo "<tr class='row1' height='25px'><td><b>".$arow->ans_count."</b> ".$arow->ftext."</td></tr>";
									}?>
								</table></td></tr>
								<?php echo "<tr><td colspan='2'><b>".JText::_('COM_SF_OTHER_ANSWERS').": </b>" . $qrow->answers_top100[$ii-1] . "</td></tr>"; ?>
								</table>
								<?php
							}
						}
					}
					else {
						$total = $qrow->total_answers;
						$i = 0;
						$tmp_data = array();
						foreach ($qrow->answer as $arow) {
							$tmp_data[$i] = $arow->ans_count;
							$i++;
						}
						?>
						<br>
						<table class="adminlist"><tr><th align="left"><?php echo $qrow->sf_qtext?></th></tr></table>
						<table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="250px" valign="top">
						<img src="<?php echo $mosConfig_live_site?>/administrator/components/com_surveyforce/includes/draw_grid.php?total=<?php echo $total?>&grids=<?php echo implode(',',$tmp_data)?>">
						</td><td valign="top"><div style="padding-top:1px ">
						<table class="adminlist" cellpadding="0" cellspacing="0">
						<?php foreach ($qrow->answer as $arow) {
							echo "<tr class='row1' height='25px'><td><b>".$arow->ans_count."</b> ".$arow->ftext."</td></tr>";
							}?>
						</table></td></tr>
						<?php if ($qrow->sf_qtype == 4) {echo "<tr><td colspan='2'><b>".JText::_('COM_SF_OTHER_ANSWERS').": </b>" . $qrow->answers_top100 . "</td></tr>";} ?>
						</table>
						<?php
					}
				break;
				case 1:
				case 5:
				case 6:
				case 9:
					$total = $qrow->total_answers;
					?>
					<br>
					<table class="adminlist"><tr><th align="left"><?php echo $qrow->sf_qtext?></th></tr></table>
					<?php foreach ($qrow->answer as $arows) { 
					$i = 0;
					$tmp_data = array();
					foreach ($arows->full_ans as $arow) {
						$tmp_data[$i] = $arow->ans_count;
						$i++;
					}?>
					<table class="adminlist"><tr><th align="left"><?php echo $arows->ftext?></th></tr></table>
					<table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="250px" valign="top">
					<img src="<?php echo $mosConfig_live_site?>/administrator/components/com_surveyforce/includes/draw_grid.php?total=<?php echo $total?>&grids=<?php echo implode(',',$tmp_data)?>">
					</td><td valign="top"><div style="padding-top:1px ">
					<table class="adminlist" cellpadding="0" cellspacing="0">
					<?php foreach ($arows->full_ans as $arow) {
						echo "<tr class='row1' height='25px'><td><b>".$arow->ans_count."</b> ".$arow->ftext."</td></tr>";
						}?>
					</table></td></tr>
					</table>
					<?php }
				break;
			}
			if ($qrow->sf_impscale) {
				$total = $qrow->total_iscale_answers;
				$i = 0;
				$tmp_data = array();
				foreach ($qrow->answer_imp as $arow) {
					$tmp_data[$i] = $arow->ans_count;
					$i++;
				}
				?>
				<table class="adminlist"><tr><th align="left"><?php echo $qrow->iscale_name?></th></tr></table>
				<table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="250px" valign="top">
				<img src="<?php echo $mosConfig_live_site?>/administrator/components/com_surveyforce/includes/draw_grid.php?total=<?php echo $total?>&grids=<?php echo implode(',',$tmp_data)?>">
				</td><td valign="top"><div style="padding-top:1px ">
				<table class="adminlist" cellpadding="0" cellspacing="0">
				<?php foreach ($qrow->answer_imp as $arow) {
					echo "<tr class='row1' height='25px'><td><b>".$arow->ans_count."</b> ".$arow->ftext."</td></tr>";
					}?>
				</table></td></tr>
				</table>
				<?php
			}
		}
		EF_menu_footer();
	}
	
	function SF_ViewRepList( $option, $survey_data, $questions_data)
	{
		global $mosConfig_live_site;
		EF_menu_header();
		?>
		<script language="javascript" type="text/javascript">
		<!--
		Joomla.submitbutton = function(pressbutton) {
			var form = document.adminForm;

			if (pressbutton == 'rep_surv_print') { 
				form.target = '_blank';
				submitform( pressbutton );
				return;
			}
			
			form.target = '';
			submitform( pressbutton );
		}
		//-->
		</script>
		<form action="index.php" method="post" name="adminForm">
		<?php if (!class_exists('JToolBarHelper')) { ?>
		<table class="adminheading">
		<tr>
			<th class="edit">
			<?php echo _SURVEY_FORCE_COMP_NAME?>:
			<small>
				<?php echo JText::_('COM_SF_USERS_RESULTS'); ?>
			</small>
			</th>
		</tr>
		</table>
		<?php } else { 
			JToolBarHelper::title( JText::_('COM_SF_USERS_RESULTS'), 'print.png' );
		}?> 

		<input type="hidden" name="option" value="com_surveyforce" />
		<input type="hidden" name="id" value="<?php echo $survey_data->id; ?>" />
		<input type="hidden" name="task" value="" />
		</form>
		<table class="adminlist">
		<tr>
			<th colspan="2" align="left"><?php echo JText::_('COM_SF_SURVEY_INFORMATION'); ?></th>
		</tr>
		<tr><td><b><?php echo JText::_('COM_SF_NAME'); ?>: </b><?php echo $survey_data->sf_name?><br>
		<b><?php echo JText::_('COM_SF_DESCRIPTION'); ?>: </b><?php echo $survey_data->sf_descr?><br>
		</td></tr>
		</table>
		<br>
		<table class="adminlist">
		<tr>
			<th align="left"><?php echo JText::_('COM_SF_SURVEY_INFORMATION'); ?></th>
		</tr>
		</table>
		<table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="250px" valign="top">
		<img src="<?php echo $mosConfig_live_site?>/administrator/components/com_surveyforce/includes/draw_grid.php?total=<?php echo ($survey_data->total_starts > $survey_data->total_inv_users)?$survey_data->total_starts:$survey_data->total_inv_users?>&grids=<?php echo $survey_data->total_inv_users.','.$survey_data->total_starts.','.$survey_data->total_completes?>">

		</td><td valign="top"><div style="padding-top:1px ">
		<table class="adminlist" cellpadding="0" cellspacing="0">
		<tr class="row1" height="25px"><td><b><?php echo $survey_data->total_inv_users?></b> - <?php echo JText::_('COM_SF_TOTAL_INVITED_USERS'); ?></td></tr>
		<tr class="row1" height="25px"><td><b><?php echo $survey_data->total_starts?></b> - <?php echo JText::_('COM_SF_TOTAL_STARTS_OF_SURVEY'); ?></td></tr>
		<tr class="row1" height="25px"><td><b><?php echo $survey_data->total_completes?></b> - <?php echo JText::_('COM_SF_TOTAL_COMPLETES_OF_SURVEY'); ?></td></tr>
		</table>
		</td></tr></table>
		<?php
		$tmp_data = array();
		$total = 0;
		$i = 0;
		foreach ($questions_data as $qrow) {
			switch ($qrow->sf_qtype) {
				case 2:
				case 3:
				case 4:
					$total = $qrow->total_answers;
					$i = 0;
					$tmp_data = array();
					foreach ($qrow->answer as $arow) {
						$tmp_data[$i] = $arow->ans_count;
						$i++;
					}
					?>
					<br>
					<table class="adminlist"><tr><th align="left"><?php echo $qrow->sf_qtext?></th></tr></table>
					<table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="250px" valign="top">
					<img src="<?php echo $mosConfig_live_site?>/administrator/components/com_surveyforce/includes/draw_grid.php?total=<?php echo $total?>&grids=<?php echo implode(',',$tmp_data)?>">
					</td><td valign="top"><div style="padding-top:1px ">
					<table class="adminlist" cellpadding="0" cellspacing="0">
					<?php foreach ($qrow->answer as $arow) {
						echo "<tr class='row1' height='25px'><td><b>".$arow->ans_count."</b> ".$arow->ftext."</td></tr>";
						}?>
					</table></td></tr>
					<?php if ($qrow->sf_qtype == 4) {echo "<tr><td colspan='2'><b>Other answers: </b>" . $qrow->answers_top100 . "</td></tr>";} ?>
					</table>
					<?php
				break;
				case 1:
				case 5:
				case 6:
					$total = $qrow->total_answers;
					?>
					<br>
					<table class="adminlist"><tr><th align="left"><?php echo $qrow->sf_qtext?></th></tr></table>
					<?php foreach ($qrow->answer as $arows) { 
					$i = 0;
					$tmp_data = array();
					foreach ($arows->full_ans as $arow) {
						$tmp_data[$i] = $arow->ans_count;
						$i++;
					}?>
					<table class="adminlist"><tr><th align="left"><?php echo $arows->ftext?></th></tr></table>
					<table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="250px" valign="top">
					<img src="<?php echo $mosConfig_live_site?>/administrator/components/com_surveyforce/includes/draw_grid.php?total=<?php echo $total?>&grids=<?php echo implode(',',$tmp_data)?>">
					</td><td valign="top"><div style="padding-top:1px ">
					<table class="adminlist" cellpadding="0" cellspacing="0">
					<?php foreach ($arows->full_ans as $arow) {
						echo "<tr class='row1' height='25px'><td><b>".$arow->ans_count."</b> ".$arow->ftext."</td></tr>";
						}?>
					</table></td></tr>
					</table>
					<?php }
				break;
			}
		}
		EF_menu_footer();
	}
	

			#######################################
			###	--- ---  CONFIGURATION  --- --- ###
	
	function SF_viewConfig( $sf_config, $lists, $option ) {
		global $mosConfig_live_site;

		mosCommonHTML::loadOverlib();
		survey_force_adm_html::SF_JS_getObj();
		$tabs = new mosTabs(1); 
		EF_menu_header();
		?>
		<script language="javascript" type="text/javascript">
		<!--
		function ValidateColor(string) {
			string = string || '';
			string = string + "";
			string = string.toUpperCase();
			var chars = '0123456789ABCDEF';
			var out   = '';
			for (var i=0; i<string.length; i++) {
				var schar = string.charAt(i);
				if (chars.indexOf(schar) != -1) { out += schar; }
			}
			if (out.length != 6) { return false; }
		  return true;
		}

		Joomla.submitbutton = function(pressbutton) {
			var form = document.adminForm;
			if (form.color_cont.value.length == 0) {
				
				tabPane1.setSelectedIndex(0);
				alert("<?php echo JText::_('COM_SF_PLEASE_ENTER_VALID_COLOR'); ?>");form.color_cont.focus();
			} else if (!ValidateColor(form.color_cont.value)) {
				alert("<?php echo JText::_('COM_SF_PLEASE_ENTER_VALID_COLOR'); ?>");form.color_cont.focus();
			} else if (form.color_drag.value.length == 0) {
				alert("<?php echo JText::_('COM_SF_PLEASE_ENTER_VALID_COLOR'); ?>");form.color_drag.focus();
			} else if (!ValidateColor(form.color_drag.value)) {
				alert("<?php echo JText::_('COM_SF_PLEASE_ENTER_VALID_COLOR'); ?>");form.color_drag.focus();
			} else if (form.color_highlight.value.length == 0) {
				alert("<?php echo JText::_('COM_SF_PLEASE_ENTER_VALID_COLOR'); ?>");form.color_highlight.focus();
			} else if (!ValidateColor(form.color_highlight.value)) {
				alert("<?php echo JText::_('COM_SF_PLEASE_ENTER_VALID_COLOR'); ?>");form.color_highlight.focus();
			} else {
				submitform( pressbutton );
			}
		}
		
		function Change_color(num) {
			var ttt = getObj("inp_color_"+num);
			var rrr = getObj("div_color_"+num);
			if (ValidateColor(ttt.value)) {
				rrr.style['background'] = '#'+ttt.value;
			} else {rrr.style['background'] = '#000000';}
			if (num > 3 && num < 8 && ValidateColor(ttt.value)) {
				var tmp = getObj("progress");
				tmp.innerHTML = "<div style='border: 1px solid #"+getObj("inp_color_4").value+"; width:100%; background-color:#"+getObj("inp_color_7").value+"; height:15px;'><div id='progress_bar' style='width:49%; background-color:#"+getObj("inp_color_6").value+"; color:#"+getObj("inp_color_5").value+"; text-align:center; height:15px;'>&nbsp;</div></div><div id='progress_bar_txt'  style='color:#"+getObj("inp_color_5").value+"; float: center; position:relative; top:-16px'><?php echo JText::_('COM_SF_PROGRESS'); ?> 49%</div>";				
			}
		}
		
		function showPopup(type) {
			var form = document.adminForm;
			var url = 'index.php?option=<?php echo $option?>&no_html=1&task=show_preview';			
			var width = 600;
			var height = 250;
			url = url + '&type=' + type;
			
			if (type == 'Bar') { 

				
				width = form.b_width.value;
				height = form.b_height.value;
				
				url = url + '&width=' + width;
				url = url + '&height=' + height;
			}
			else {

	
				width = form.p_width.value;
				height = form.p_height.value;
				
				url = url + '&width=' + width;
				url = url + '&height=' + height;
			}
			popupWindow(url, 'show_preview' , (parseInt(width)*1.5 + 20) , (parseInt(height)*2 + 20), 1 );
		}
		//-->
		</script>
		
		<form action="index.php" method="post" name="adminForm" >
		<?php if (!class_exists('JToolBarHelper')) { ?>
		<table class="adminheading">
		<tr>
			<th class="config">
			<?php echo _SURVEY_FORCE_COMP_NAME?>:
			<small>
			<?php echo JText::_('COM_SF_CONFIGURATION'); ?>
			</small>
			</th>
		</tr>
		</table>
		<?php } else { 
			JToolBarHelper::title( JText::_('COM_SF_CONFIGURATION'), 'config.png' );
		}?>
		<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminheading">
		  <tr>
			<td width="10%"><strong><?php echo JText::_('COM_SF_YOUR_VERSION_IS'); ?></strong> </td>
			<td><strong><?php echo $sf_config->get('sf_version')?></strong></td>
		  </tr>
		</table>
		<br/>
		<?php
		$tabs->startPane("labelsPane");
		$tabs->startTab(JText::_('COM_SF_BASIC'),"userdetails-page");
		?>
			<table class="adminform">
			<tr>
				<th colspan="1"><?php echo JText::_('COM_SF_JOMSOCIAL_INTEGRATION'); ?></th>
			</tr>
			<tr>
				<td><input type="checkbox" name="_sf_enable_jomsocial_integration" value="1"  class="inputbox"  onclick="javascript: getObj('sf_enable_jomsocial_integration').value = (this.checked)?1:0;"  <?php echo ($sf_config->get('sf_enable_jomsocial_integration')==1?'checked="checked"':'')?> />
					<input type="hidden" id="sf_enable_jomsocial_integration" name="sf_enable_jomsocial_integration" value="<?php echo $sf_config->get('sf_enable_jomsocial_integration')?>" />
					<?php echo JText::_('COM_SF_ENABLE_INTEGRATION'); ?>
					
				</td>
			</tr>
			<tr>
				<th colspan="1"><?php echo JText::_('COM_SF_JOOMLALMS_INTEGRATION'); ?></th>
			</tr>
			<tr>
				<td><input type="checkbox" name="_enable_lms_integration" value="1"  class="inputbox"  onclick="javascript: getObj('sf_enable_lms_integration').value = (this.checked)?1:0;"  <?php echo ($sf_config->get('sf_enable_lms_integration')==1?'checked="checked"':'')?> />
					<input type="hidden" id="sf_enable_lms_integration" name="sf_enable_lms_integration" value="<?php echo $sf_config->get('sf_enable_lms_integration')?>" />
					<?php echo JText::_('COM_SF_ENABLE_INTEGRATION_ALLOW_LMS'); ?><br /><br />
				</td>
			</tr>
			<tr>
				<th colspan="1"><?php echo JText::_('COM_SF_MAIL_SENDING'); ?></th>
			</tr>
			<tr>
				<td><?php echo JText::_('COM_SF_DO_PAUSE'); ?><input type="text" size="3" class="text_area" name="sf_mail_pause" value="<?php echo intval($sf_config->get('sf_mail_pause'))?>"  /> <?php echo JText::_('COM_SF_SECONDS_BETWEEN'); ?><input type="text" class="text_area" size="3" name="sf_mail_count" value="<?php echo intval($sf_config->get('sf_mail_count'))?>"  /> <?php echo JText::_('COM_SF_MAILS'); ?><br />
					<small><?php echo JText::_('COM_SF_IF_ONE_OF_VALUES_EQUAL_ZERO'); ?></small><br /><br />
					<?php echo JText::_('COM_SF_MAXIMAL_COUNT_OF_MAILS'); ?><input type="text" size="3" class="text_area" name="sf_mail_maximum" value="<?php echo intval($sf_config->get('sf_mail_maximum'))?>"  /><br />
					<small><?php echo JText::_('COM_SF_IF_EQUAL_ZERO'); ?></small><br />
				</td>
			</tr>
			<tr>
				<th colspan="1"><?php echo JText::_('COM_SF_FORCE_SSL_FRONT_END'); ?></th>
			</tr>
			<tr>
				<td><input type="checkbox" name="_sf_force_ssl" value="1"  class="inputbox"  onclick="javascript: getObj('sf_force_ssl').value = (this.checked)?1:0;"  <?php echo ($sf_config->get('sf_force_ssl',0)==1?'checked="checked"':'')?> />
					<input type="hidden" id="sf_force_ssl" name="sf_force_ssl" value="<?php echo $sf_config->get('sf_force_ssl',0)?>" />
					<?php echo JText::_('COM_SF_FORCE_SSL_SURVEYFORCE_FRONT_END'); ?><br /><br />
				</td>
			</tr>
			<tr>
				<th colspan="1"><?php echo JText::_('COM_SF_OTHER_SETTINGS'); ?></th>
			</tr>
			<tr>
				<td><input type="checkbox" name="_show_dev_info" value="1"  class="inputbox"  onclick="javascript: getObj('sf_show_dev_info').value = (this.checked)?1:0;"  <?php echo ($sf_config->get('sf_show_dev_info',1)==1?'checked="checked"':'')?> />
					<input type="hidden" id="sf_show_dev_info" name="sf_show_dev_info" value="<?php echo $sf_config->get('sf_show_dev_info',1)?>" />
					<?php echo JText::_('COM_SF_SHOW_COMPONENT_INFO'); ?><br /><br />
				</td>
			</tr>
			</table>
		
		<?php		
		$tabs->endTab();		
		$tabs->startTab(JText::_('COM_SF_ELEMENT_COLORS'),"labeldetails-page");
		?> 
		<table width="100%" class="adminform">
			<tr>
				<th colspan="3"><?php echo JText::_('COM_SF_COLORS_DRAG_AND_DROP'); ?></th>
			</tr>
			<tr>
				<td width="20%"><?php echo JText::_('COM_SF_LEFT_PANEL_COLOR'); ?></td>
				<td width="20px"><div id="div_color_1" style="background-color:#<?php echo $sf_config->get('color_cont')?>; width:20px; height:18px"></div></td>
				<td><input onKeyUp="Change_color(1);" id="inp_color_1" class="text_area" size="35" type="text" name="color_cont" value="<?php echo $sf_config->get('color_cont')?>"></td>
			</tr>
			<tr>
				<td><?php echo JText::_('COM_SF_RIGHT_PANEL_COLOR'); ?></td>
				<td width="20px"><div id="div_color_2" style="background-color:#<?php echo $sf_config->get('color_drag')?>; width:20px; height:18px"></div></td>
				<td><input onKeyUp="Change_color(2);" id="inp_color_2" class="text_area" size="35" type="text" name="color_drag" value="<?php echo $sf_config->get('color_drag')?>"></td>
			</tr>
			<tr>
				<td><?php echo JText::_('COM_SF_HIGHLIGHTED_COLOR'); ?></td>
				<td width="20px"><div id="div_color_3" style="background-color:#<?php echo $sf_config->get('color_highlight')?>; width:20px; height:18px"></div></td>
				<td><input onKeyUp="Change_color(3);" id="inp_color_3" class="text_area" size="35" type="text" name="color_highlight" value="<?php echo $sf_config->get('color_highlight')?>"></td>
			</tr>
			<tr>
				<th colspan="3"><?php echo JText::_('COM_SF_COLORS_FOR_PROGRESS_BAR'); ?></th>
			</tr>
			<tr>
				<td width="20%"><?php echo JText::_('COM_SF_BORDER_COLOR'); ?></td>
				<td width="20px"><div id="div_color_4" style="background-color:#<?php echo $sf_config->get('color_border')?>; width:20px; height:18px"></div></td>
				<td><input onKeyUp="Change_color(4);" id="inp_color_4" class="text_area" size="35" type="text" name="color_border" value="<?php echo $sf_config->get('color_border')?>"></td>
			</tr>
			<tr>
				<td width="20%"><?php echo JText::_('COM_SF_TEXT_COLOR'); ?></td>
				<td width="20px"><div id="div_color_5" style="background-color:#<?php echo $sf_config->get('color_text')?>; width:20px; height:18px"></div></td>
				<td><input onKeyUp="Change_color(5);" id="inp_color_5" class="text_area" size="35" type="text" name="color_text" value="<?php echo $sf_config->get('color_text')?>"></td>
			</tr>
			<tr>
				<td width="20%"><?php echo JText::_('COM_SF_COMPLETED_COLOR'); ?></td>
				<td width="20px"><div id="div_color_6" style="background-color:#<?php echo $sf_config->get('color_completed')?>; width:20px; height:18px"></div></td>
				<td><input onKeyUp="Change_color(6);" id="inp_color_6" class="text_area" size="35" type="text" name="color_completed" value="<?php echo $sf_config->get('color_completed')?>"></td>
			</tr>
			<tr>
				<td width="20%"><?php echo JText::_('COM_SF_UNCOMPLETED_COLOR'); ?></td>
				<td width="20px"><div id="div_color_7" style="background-color:#<?php echo $sf_config->get('color_uncompleted')?>; width:20px; height:18px"></div></td>
				<td><input onKeyUp="Change_color(7);" id="inp_color_7" class="text_area" size="35" type="text" name="color_uncompleted" value="<?php echo $sf_config->get('color_uncompleted')?>"></td>
			</tr>
			<tr>
				<td width="20%"><?php echo JText::_('COM_SF_EXAMPLE'); ?>:</td>
				<td colspan="2" width="100%" align="center" style="text-align:center" >
				<div id="progress">
					<div style="border: 1px solid #<?php echo $sf_config->get('color_border')?>; width:100%; background-color:#<?php echo $sf_config->get('color_uncompleted')?>; height:15px;">
						<div id="progress_bar" style="width:49%; background-color:#<?php echo $sf_config->get('color_completed')?>; color:#<?php echo $sf_config->get('color_text')?>; text-align:center; height:15px;">&nbsp;</div>		
					</div>
					<div id="progress_bar_txt"  style="float: center; position:relative; top:-16px;color:#<?php echo $sf_config->get('color_text')?>;"><?php echo JText::_('COM_SF_PROGRESS'); ?> 49%</div>		
				</div>
				</td>
			</tr>
		</table>
		<?php
		$tabs->endTab();		
		$tabs->startTab(JText::_('COM_SF_RESULTS_SHOW'),"resultdetails-page");
		?>
		<table class="adminform" >
			<tr>
				<th colspan="4" width="50%"><?php echo JText::_('COM_SF_TYPE_AND_COLORS_RESULT'); ?></th>
				<th  width="auto"></th>
			</tr>
			<tr><td colspan="2"><input type="radio" name="sf_result_type" value="Bar" <?php echo ($sf_config->get('sf_result_type') == 'Bar'?'checked="checked"':'')?> /><?php echo JText::_('COM_SF_SHOW_RESULTS_IN_BARCHART'); ?></td>
				<td><a href="javascript:showPopup('Bar');" style="text-decoration:none;" >
				<img src="<?php echo JURI::root();?>administrator/components/com_surveyforce/images/search.png" alt="<?php echo JText::_('COM_SF_PREVIEW'); ?>" name="Preview" border="0" /><?php echo JText::_('COM_SF_PREVIEW'); ?></a>
				</td>
				<td colspan="2"><input type="radio" name="sf_result_type" value="Pie" <?php echo ($sf_config->get('sf_result_type') == 'Pie'?'checked="checked"':'')?> /><?php echo JText::_('COM_SF_SHOW_RESULTS_IN_PIECHART'); ?></td>
				<td><a href="javascript:showPopup('Pie');" style="text-decoration:none;" >
				<img src="<?php echo JURI::root();?>administrator/components/com_surveyforce/images/search.png" alt="<?php echo JText::_('COM_SF_PREVIEW'); ?>" border="0"/><?php echo JText::_('COM_SF_PREVIEW'); ?></a>
				</td>
				<td rowspan="3"></td>
			</tr>
			<tr><td style="text-align:right"><?php echo JText::_('COM_SF_WIDTH_ONE_CHART'); ?></td>
				<td colspan="2"><input id="b_width" class="text_area" size="12" type="text" name="b_width" value="<?php echo $sf_config->get('b_width')?>"></td>
				<td style="text-align:right"><?php echo JText::_('COM_SF_WIDTH_ONE_CHART'); ?></td>
				<td colspan="2"><input id="p_width" class="text_area" size="12" type="text" name="p_width" value="<?php echo $sf_config->get('p_width')?>"></td>
			</tr>
			<tr><td style="text-align:right"><?php echo JText::_('COM_SF_HEIGHT_ONE_CHART'); ?></td>
				<td colspan="2"><input id="b_height" class="text_area" size="12" type="text" name="b_height" value="<?php echo $sf_config->get('b_height')?>"></td>
				<td style="text-align:right"><?php echo JText::_('COM_SF_HEIGHT_ONE_CHART'); ?></td>
				<td colspan="2"><input id="p_height" class="text_area" size="12" type="text" name="p_height" value="<?php echo $sf_config->get('p_height')?>"></td>
			</tr>			
		</table>
		<?php
		$tabs->endTab();		
		$tabs->startTab(JText::_('COM_SF_EMAILS'),"emails-page");
		?>
			<table class="adminform">
			<tr>
				<th colspan="1"><?php echo JText::_('COM_SF_EMAIL_SENDING'); ?></th>
			</tr>
			<tr>
				<td><input type="checkbox" name="_an_mail" value="1"  class="inputbox"  onclick="javascript: getObj('sf_an_mail').value = (this.checked)?1:0;"  <?php echo ($sf_config->get('sf_an_mail')==1?'checked="checked"':'')?> />
					<input type="hidden" id="sf_an_mail" name="sf_an_mail" value="<?php echo $sf_config->get('sf_an_mail')?>" />
					<?php echo JText::_('COM_SF_SEND_EMAIL_TO_AUTHOR'); ?><br /><br />
					
					<input type="checkbox" name="_an_mail_others" value="1"  class="inputbox"  onclick="javascript: getObj('sf_an_mail_others').value = (this.checked)?1:0;"  <?php echo ($sf_config->get('sf_an_mail_others')==1?'checked="checked"':'')?> />
					<input type="hidden" id="sf_an_mail_others" name="sf_an_mail_others" value="<?php echo $sf_config->get('sf_an_mail_others')?>" />
					<?php echo JText::_('COM_SF_SEND_EMAIL_WHEN_USER'); ?>&nbsp;<input class="text_area" name="sf_an_mail_other_emails" value="<?php echo $sf_config->get('sf_an_mail_other_emails')?>" size="50" /><br /><br />
					<small><?php echo JText::_('COM_SF_ENTER_SOME_EMAIL_SEPARATED_BY_COMMA'); ?></small>
				</td>
			</tr>
			<tr>
				<th colspan="1"><?php echo JText::_('COM_SF_EMAIL_DETAILS'); ?></th>
			</tr>
			<tr>
				<td><?php echo JText::_('COM_SF_SUBJECT'); ?>:&nbsp; <input type="text" size="40" class="text_area" name="sf_an_mail_subject" value="<?php echo $sf_config->get('sf_an_mail_subject')?>" /> <br />
					<?php echo JText::_('COM_SF_TEXT_BEFORE_USER_RESULTS'); ?>&nbsp;<br />
					<textarea class="text_area" name="sf_an_mail_text" rows="5" style=" width:70%;"><?php echo $sf_config->get('sf_an_mail_text')?></textarea><br />
					<small><?php echo JText::_('COM_SF_THIS_TEXT_WILL_BE_ADDED'); ?></small>
				</td>
			</tr>			
			</table>
		<?php
		$tabs->endTab();		
		$tabs->endPane();
		?>
		<input type="hidden" name="option" value="com_surveyforce" />
		<input type="hidden" name="id" value="1" />
		<input type="hidden" name="task" value="" />
		</form>
		<?php
		EF_menu_footer();
	}

			#######################################
			###	--- ---    IMP SCALES   --- --- ###

	function SF_viewIScales( &$rows, &$pageNav, $option ) {
		global $my;

		mosCommonHTML::loadOverlib();
		EF_menu_header();
		?>
		<form action="index.php" method="post" name="adminForm">
		<?php if (!class_exists('JToolBarHelper')) { ?>
		<table class="adminheading">
		<tr>
			<th>
			<?php echo _SURVEY_FORCE_COMP_NAME?>: <small><?php echo JText::_('COM_SF_IMPORTANCE_SCALES'); ?></small>
			</th>
		</tr>
		</table>
		<?php } else { 
			JToolBarHelper::title( JText::_('COM_SF_IMPORTANCE_SCALES'), 'static.png' );
		}?>
		<table class="adminlist">
		<tr>
			<th width="20">#</th>
			<th width="20" class="title"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($rows); ?>);" /></th>
			<th class="title"><?php echo JText::_('COM_SF_NAME'); ?></th>
		</tr>
		<?php
		$k = 0;
		for ($i=0, $n=count($rows); $i < $n; $i++) {
			$row = $rows[$i];
			$link 	= 'index.php?option=com_surveyforce&task=editA_iscale&id='. $row->id;
			$checked = mosHTML::idBox( $i, $row->id);?>
			<tr class="<?php echo "row$k"; ?>">
				<td><?php echo $pageNav->rowNumber( $i ); ?></td>
				<td><?php echo $checked; ?></td>
				<td align="left">
					<span>
						<?php echo mosToolTip(mysql_escape_string(nl2br($row->iscale_descr)), JText::_('COM_SF_SCALE_DESCRIPTION'), 280, 'tooltip.png', $row->iscale_name, $link );?>
					</span>
				</td>
			</tr>
			<?php
			$k = 1 - $k;
		}?>
		</table>
		<?php echo $pageNav->getListFooter(); ?>
		<input type="hidden" name="option" value="com_surveyforce" />
		<input type="hidden" name="task" value="iscales" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="hidemainmenu" value="0">
		</form>
		<?php
		EF_menu_footer();
	}
	function SF_editIScale( &$row, &$lists, $option ) {
		global $mosConfig_live_site;

		mosCommonHTML::loadOverlib();
		
		survey_force_adm_html::SF_JS_getObj();
		EF_menu_header();
		?>
		<script language="javascript" type="text/javascript">
		<!--

		function ReAnalize_tbl_Rows( start_index, tbl_id ) {
			start_index = 1;
			var tbl_elem = getObj(tbl_id);
			if (tbl_elem.rows[start_index]) {
				var count = start_index; var row_k = 1 - start_index%2;
				for (var i=start_index; i<tbl_elem.rows.length; i++) {
					tbl_elem.rows[i].cells[0].innerHTML = count;
					Redeclare_element_inputs(tbl_elem.rows[i].cells[1]);
					if (i > 1) { 
						tbl_elem.rows[i].cells[3].innerHTML = '<a href="javascript: void(0);" onClick="javascript:Up_tbl_row(this); return false;" title="<?php echo JText::_('COM_SF_MOVE_UP'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/uparrow.png"  border="0" alt="<?php echo JText::_('COM_SF_MOVE_UP'); ?>"></a>';
					} else { tbl_elem.rows[i].cells[3].innerHTML = ''; }
					if (i < (tbl_elem.rows.length - 1)) {
						tbl_elem.rows[i].cells[4].innerHTML = '<a href="javascript: void(0);" onClick="javascript:Down_tbl_row(this); return false;" title="<?php echo JText::_('COM_SF_MOVE_DOWN'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/downarrow.png"  border="0" alt="<?php echo JText::_('COM_SF_MOVE_DOWN'); ?>"></a>';;
					} else { tbl_elem.rows[i].cells[4].innerHTML = ''; }
					tbl_elem.rows[i].className = 'row'+row_k;
					count++;
					row_k = 1 - row_k;
				}
			}
		}
		
		function Redeclare_element_inputs(object) {
			if (object.hasChildNodes()) {
				var children = object.childNodes;
				for (var i = 0; i < children.length; i++) {
					if (children[i].nodeName.toLowerCase() == 'input') {
						var inp_name = children[i].name;
						var inp_value = children[i].value;
						object.removeChild(object.childNodes[i]);
						var input_hidden = document.createElement("input");
						input_hidden.type = "hidden";
						input_hidden.name = inp_name;
						input_hidden.value = inp_value;
						object.appendChild(input_hidden);
					}
				};
			};
		}


		function Delete_tbl_row(element) {
			var del_index = element.parentNode.parentNode.sectionRowIndex;
			var tbl_id = element.parentNode.parentNode.parentNode.parentNode.id;
			element.parentNode.parentNode.parentNode.deleteRow(del_index);
			ReAnalize_tbl_Rows(del_index - 1, tbl_id);
		}

		function Up_tbl_row(element) {
			if (element.parentNode.parentNode.sectionRowIndex > 1) {
				var sec_indx = element.parentNode.parentNode.sectionRowIndex;
				var table = element.parentNode.parentNode.parentNode;
				var tbl_id = table.parentNode.id;
				var cell2_tmp = element.parentNode.parentNode.cells[1].innerHTML;
				element.parentNode.parentNode.parentNode.deleteRow(element.parentNode.parentNode.sectionRowIndex);
				var row = table.insertRow(sec_indx - 1);
				var cell1 = document.createElement("td");
				var cell2 = document.createElement("td");
				var cell3 = document.createElement("td");
				var cell4 = document.createElement("td");
				cell1.align = 'center';
				cell1.innerHTML = 0;
				cell2.align = 'left';
				cell2.innerHTML = cell2_tmp;
				cell3.innerHTML = '<a href="javascript: void(0);" onClick="javascript:Delete_tbl_row(this); return false;" title="<?php echo JText::_('COM_SF_DELETE'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/publish_x.png"  border="0" alt="<?php echo JText::_('COM_SF_DELETE'); ?>"></a>';
				cell4.innerHTML = '<a href="javascript: void(0);" onClick="javascript:Up_tbl_row(this); return false;" title="<?php echo JText::_('COM_SF_MOVE_UP'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/uparrow.png"  border="0" alt="<?php echo JText::_('COM_SF_MOVE_UP'); ?>"></a>';
				row.appendChild(cell1);
				row.appendChild(cell2);
				row.appendChild(cell3);
				row.appendChild(cell4);
				row.appendChild(document.createElement("td"));
				row.appendChild(document.createElement("td"));
				ReAnalize_tbl_Rows(sec_indx - 2, tbl_id);
			}
		}

		function Down_tbl_row(element) {
			if (element.parentNode.parentNode.sectionRowIndex < element.parentNode.parentNode.parentNode.rows.length - 1) {
				var sec_indx = element.parentNode.parentNode.sectionRowIndex;
				var table = element.parentNode.parentNode.parentNode;
				var tbl_id = table.parentNode.id;
				var cell2_tmp = element.parentNode.parentNode.cells[1].innerHTML;
				element.parentNode.parentNode.parentNode.deleteRow(element.parentNode.parentNode.sectionRowIndex);
				var row = table.insertRow(sec_indx + 1);
				var cell1 = document.createElement("td");
				var cell2 = document.createElement("td");
				var cell3 = document.createElement("td");
				var cell4 = document.createElement("td");
				cell1.align = 'center';
				cell1.innerHTML = 0;
				cell2.align = 'left';
				cell2.innerHTML = cell2_tmp;
				cell3.innerHTML = '<a href="javascript: void(0);" onClick="javascript:Delete_tbl_row(this); return false;" title="<?php echo JText::_('COM_SF_DELETE'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/publish_x.png"  border="0" alt="<?php echo JText::_('COM_SF_DELETE'); ?>"></a>';
				cell4.innerHTML = '<a href="javascript: void(0);" onClick="javascript:Up_tbl_row(this); return false;" title="<?php echo JText::_('COM_SF_MOVE_UP'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/uparrow.png"  border="0" alt="<?php echo JText::_('COM_SF_MOVE_UP'); ?>"></a>';
				row.appendChild(cell1);
				row.appendChild(cell2);
				row.appendChild(cell3);
				row.appendChild(cell4);
				row.appendChild(document.createElement("td"));
				row.appendChild(document.createElement("td"));
				ReAnalize_tbl_Rows(sec_indx, tbl_id);
			}
		}

		function Add_new_tbl_field(elem_field, tbl_id, field_name) {
			var new_element_txt = getObj(elem_field).value;
			if (TRIM_str(new_element_txt) == '') {
				alert("<?php echo JText::_('COM_SF_PLEASE_ENTER_TEXT_TO_FIELD'); ?>");return;
			}
			getObj(elem_field).value = '';
			var tbl_elem = getObj(tbl_id);
			var row = tbl_elem.insertRow(tbl_elem.rows.length);
			var cell1 = document.createElement("td");
			var cell2 = document.createElement("td");
			var cell3 = document.createElement("td");
			var cell4 = document.createElement("td");
			var cell5 = document.createElement("td");
			var cell6 = document.createElement("td");
			var input_hidden = document.createElement("input");
			input_hidden.type = "hidden";
			input_hidden.name = field_name;
			input_hidden.value = new_element_txt;
			cell1.align = 'center';
			cell1.innerHTML = 0;
			cell2.innerHTML = new_element_txt;
			cell2.appendChild(input_hidden);
			cell3.innerHTML = '<a href="javascript: void(0);" onClick="javascript:Delete_tbl_row(this); return false;" title="<?php echo JText::_('COM_SF_DELETE'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/publish_x.png"  border="0" alt="<?php echo JText::_('COM_SF_DELETE'); ?>"></a>';
			cell4.innerHTML = '<a href="javascript: void(0);" onClick="javascript:Up_tbl_row(this); return false;" title="<?php echo JText::_('COM_SF_MOVE_UP'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/uparrow.png"  border="0" alt="<?php echo JText::_('COM_SF_MOVE_UP'); ?>"></a>';
			cell5.innerHTML = '';
			row.appendChild(cell1);
			row.appendChild(cell2);
			row.appendChild(cell3);
			row.appendChild(cell4);
			row.appendChild(cell5);
			row.appendChild(cell6);
			ReAnalize_tbl_Rows(tbl_elem.rows.length - 2, tbl_id);
		}

		Joomla.submitbutton = function(pressbutton) {
			var form = document.adminForm;
			if (pressbutton == 'cancel_iscale') {
				submitform( pressbutton );
				return;
			}
			if (pressbutton == 'cancel_iscale_A') {
				submitform( pressbutton );
				return;
			}
			// do field validation
			if (form.iscale_name.value == ""){
				alert( "<?php echo JText::_('COM_SF_IMPORTANCE_SCALE_MUST_HAVE_TEXT'); ?>" );
			} 
			else {
				submitform( pressbutton );
			}
		}
		//-->
		</script>
		
		<form action="index.php" method="post" name="adminForm">
		<?php if (!class_exists('JToolBarHelper')) { ?>
		<table class="adminheading">
		<tr>
			<th>
			<?php echo _SURVEY_FORCE_COMP_NAME?>:
			<small>
			<?php echo $row->id ? JText::_('COM_SF_EDIT_IMPORTANCE_SCALE') : JText::_('COM_SF_NEW_IMPORTANCE_SCALE');?>
			</small>
			</th>
		</tr>
		</table>
		<?php } else { 
			JToolBarHelper::title( ($row->id ? JText::_('COM_SF_EDIT_IMPORTANCE_SCALE') : JText::_('COM_SF_NEW_IMPORTANCE_SCALE')), 'static.png' );
		}?>
		<table width="100%" class="adminform">
			<tr>
				<th colspan="2"><?php echo JText::_('COM_SF_IMPORTANCE_SCALE_DETAILS'); ?></th>
			</tr>
			<tr>
				<td align="right" width="20%" valign="top"><?php echo JText::_('COM_SF_QUESTION_TEXT'); ?>:</td>
				<td><textarea class="text_area" rows="6" cols="60" name="iscale_name"><?php echo $row->iscale_name;?></textarea></td>
			</tr>
		</table>
		<br />
		<table class="adminlist" id="qfld_tbl">
		<tr>
			<th width="20px" align="center">#</th>
			<th class="title" width="200px"><?php echo JText::_('COM_SF_SCALE_OPTIONS'); ?></th>
			<th width="20px" align="center" class="title"></th>
			<th width="20px" align="center" class="title"></th>
			<th width="20px" align="center" class="title"></th>
			<th width="auto"></th>
		</tr>
		<?php
		$k = 0; $ii = 1; $ind_last = count($lists['sf_fields']);
		foreach ($lists['sf_fields'] as $frow) { ?>
			<tr class="<?php echo "row$k"; ?>">
				<td align="center"><?php echo $ii?></td>
				<td align="left">
					<?php echo $frow->isf_name?>
					<input type="hidden" name="sf_hid_fields[]" value="<?php echo $frow->isf_name?>">
				</td>
				<td><a href="javascript: void(0);" onClick="javascript:Delete_tbl_row(this); return false;" title="<?php echo JText::_('COM_SF_DELETE'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/publish_x.png"  border="0" alt="<?php echo JText::_('COM_SF_DELETE'); ?>"></a></td>
				<td><?php if ($ii > 1) { ?><a href="javascript: void(0);" onClick="javascript:Up_tbl_row(this); return false;" title="<?php echo JText::_('COM_SF_MOVE_UP'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/uparrow.png"  border="0" alt="<?php echo JText::_('COM_SF_MOVE_UP'); ?>"></a><?php } ?></td>
				<td><?php if ($ii < $ind_last) { ?><a href="javascript: void(0);" onClick="javascript:Down_tbl_row(this); return false;" title="<?php echo JText::_('COM_SF_MOVE_DOWN'); ?>"><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/downarrow.png"  border="0" alt="<?php echo JText::_('COM_SF_MOVE_DOWN'); ?>"></a><?php } ?></td>
				<td></td>
			</tr>
		<?php
		$k = 1 - $k; $ii ++;
		 } ?>
		</table><br>
		<div style="text-align:left; padding-left:30px ">
			<input id="new_field" class="text_area" style="width:205px " type="text" name="new_field">
			<input class="text_area" type="button" name="add_new_field" style="width:70px " value="<?php echo JText::_('COM_SF_ADD'); ?>" onClick="javascript:Add_new_tbl_field('new_field', 'qfld_tbl', 'sf_hid_fields[]');">
		</div>
		<br />
		<input type="hidden" name="option" value="com_surveyforce" />
		<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
		<input type="hidden" name="task" value="" />
		</form>
		<?php
		EF_menu_footer();
	}
			#######################################
			###	--- ---   VSYAKI TRASH 	--- --- ###
	
	function View_AboutPage()
	{
		EF_menu_header();
	 
	 if (!class_exists('JToolBarHelper')) { ?>
	 <table class="adminheading">
		<tr>
			<th>
			<?php echo _SURVEY_FORCE_COMP_NAME?><?php echo JText::_('COM_SF_DISCLAIMER'); ?>
			</th>
		</tr>
	</table>
<?php } else { 
	JToolBarHelper::title( JText::_('COM_SF_DISCLAIMER'), 'static.png' );
}
		/*
		?>
		<table class="adminlist">
		<tr><td>
		<div style="text-align:left; padding:5px; font-family: verdana, arial, sans-serif; font-size: 9pt; ">
			<h3><?php echo JText::_('COM_SF_SURVEYFORCE_COMPONENT_AGREE'); ?></h3>
			<p>1. <?php echo JText::_('COM_SF_LIMITED_USAGE_GRANTED'); ?><br>
			<?php echo JText::_('COM_SF_YOU_MAY_USE_AND_INSTALL'); ?><br>
			</p><p>2. <?php echo JText::_('COM_SF_MODIFICATIONS'); ?><br>
			<?php echo JText::_('COM_SF_YOU_ARE_AUTHORIZED_TO_MAKE'); ?><br>
			</p><p>3. <?php echo JText::_('COM_SF_UNAUTHORIZED_USE'); ?><br>
			<?php echo JText::_('COM_SF_YOU_MAY_NOT_PLACE'); ?><br>
			</p><p>4. <?php echo JText::_('COM_SF_UPDATES'); ?><br>
			<?php echo JText::_('COM_SF_JOOMPLACE_HAS_NO_INFLUENCE_ON_MAMBA'); ?><br>
			</p><p>5. <?php echo JText::_('COM_SF_SHIPPING'); ?><br>
			<?php echo JText::_('COM_SF_AFTER_YOU_PROCEED_TO_CHECKOUT'); ?><br>
			</p><p>6. <?php echo JText::_('COM_SF_ASSIGNABILITY'); ?><br>
			<?php echo JText::_('COM_SF_YOU_MAY_NOT_SUBLICENSE'); ?><br>
			</p><p>7. <?php echo JText::_('COM_SF_OWNERSHIP'); ?><br>
			<?php echo JText::_('COM_SF_YOU_MAY_NOT_CLAIM_INTELLECTUAL'); ?><br>
			</p><p>8. <?php echo JText::_('COM_SF_REFUND_POLICY'); ?><br>
			<?php echo JText::_('COM_SF_SINCE_JOOMPLACE_IS_OFFERING'); ?><br>
			</p><p><?php echo JText::_('COM_SF_OUR_COMPANY_RESERVES'); ?><br>
			</p>		
		</div>
		</td></tr></table>
	<?php
	*/
		EF_menu_footer();
	}
	function View_HelpPage()
	{
		EF_menu_header();
	 ?>
	 <?php if (!class_exists('JToolBarHelper')) { ?>
	 <table class="adminheading">
		<tr>
			<th>
			<?php echo _SURVEY_FORCE_COMP_NAME?> <?php echo JText::_('COM_SF_HELP'); ?>
			</th>
		</tr>
		</table>
		<?php } else { 
	JToolBarHelper::title( JText::_('COM_SF_S_HELP'), 'help_header.png' );
}?>
		<table class="adminlist">
		<tr><td>
		<div style="text-align:left; padding:3px 5px 0px 5px; font-family: verdana, arial, sans-serif; font-size: 9pt;">
		<a href="index.php?option=com_surveyforce&task=history"><?php echo _SURVEY_FORCE_COMP_NAME . " <?php echo JText::_('COM_SF_VERSION_HISTORY'); ?>";?></a>
		</div>
		<?php include_once(_SURVEY_FORCE_ADMIN_HOME.'/manual.php'); ?>
		</td></tr></table>
	<?php
		EF_menu_footer();
	}
	function View_HistoryPage()
	{
		EF_menu_header();
	 ?>
	 <?php if (!class_exists('JToolBarHelper')) { ?>
	 <table class="adminheading">
		<tr>
			<th>
			<?php echo _SURVEY_FORCE_COMP_NAME?> <?php echo JText::_('COM_SF_VERSION_HISTORY'); ?>
			</th>
		</tr>
		</table>
<?php } else { 
	JToolBarHelper::title( JText::_('COM_SF_VERSION_HISTORY'), 'systeminfo.png' );
}?>		
		<table class="adminlist">
		<tr><td>
		<div style="text-align:left; padding:5px; font-family: verdana, arial, sans-serif; font-size: 9pt;">
		<?php include_once(_SURVEY_FORCE_ADMIN_HOME.'/changelog.php'); ?>
		</div>
		</td></tr></table>
	<?php
		EF_menu_footer();
	}
	function View_FAQPage()
	{
		EF_menu_header();
	 ?>
	 <?php if (!class_exists('JToolBarHelper')) { ?>
	 <table class="adminheading">
		<tr>
			<th>
			<?php echo _SURVEY_FORCE_COMP_NAME?> <?php echo JText::_('COM_SF_FAQ'); ?>
			</th>
		</tr>
		</table>
		<?php } else { 
	JToolBarHelper::title( JText::_('COM_SF_FAQ'), 'help_header.png' );
}?>
		<table class="adminlist">
		<tr><td>
		<?php include_once(_SURVEY_FORCE_ADMIN_HOME.'/manual_faq.php'); ?>
		</td></tr></table>
	<?php
		EF_menu_footer();
	}
	function View_SupportPage()
	{
		EF_menu_header();
	 ?>
	 <?php if (!class_exists('JToolBarHelper')) { ?>
	 <table class="adminheading">
		<tr>
			<th>
			<?php echo _SURVEY_FORCE_COMP_NAME?> <?php echo JText::_('COM_SF_SUPPORT'); ?>
			</th>
		</tr>
		</table>
	<?php } else { 
	JToolBarHelper::title( JText::_('COM_SF_SUPPORT'), 'help_header.png' );
}?>
		<table class="adminlist">
		<tr><td>
		<div style="text-align:left; padding:5px; font-family: verdana, arial, sans-serif; font-size: 9pt;">
			<h3>1. <b><?php echo JText::_('COM_SF_SUPPORT_FORUM'); ?></b></h3>
			<p><?php echo JText::_('COM_SF_SUPPORT_FORUM_FOR_THIS_COMPONENT'); ?><a target="_blank" href="http://www.JoomPlace.com/support">http://www.JoomPlace.com/support</a></p>
			<h3>2. <b><?php echo JText::_('COM_SF_FIX_DO_MY_JOOMLA'); ?></b></h3>
			<p><?php echo JText::_('COM_SF_THIS_SERVICE_FOR_ANYONE'); ?></p>
			<p><?php echo JText::_('COM_SF_ALL_OUR_DEVELOPERS'); ?></p>
			<p><?php echo JText::_('COM_SF_WE_FIX_YOUR_PROBLEM'); ?></p>
			<p><?php echo JText::_('COM_SF_IF_THE_PROBLEM_NOT_FIXED'); ?></p>
			<p><?php echo JText::_('COM_SF_READ_MORE_HERE'); ?><a href='http://www.joomplace.com/component/option,com_support/Itemid,143/'>http://www.joomplace.com/component/option,com_support/Itemid,143/</a></p>
		</div>
		</td></tr></table>
	<?php
		EF_menu_footer();
	}	
	
	function SF_showCrossReport( $lists, $option ) {

		mosCommonHTML::loadOverlib();
		mosCommonHTML::loadCalendar(); 
		EF_menu_header();
		?>
		<form action="index.php" method="post" name="adminForm">
		<?php if (!class_exists('JToolBarHelper')) { ?>
		<table class="adminheading">
		<tr>
			<th class="edit">
			<?php echo _SURVEY_FORCE_COMP_NAME?>:
			<small>
				<?php echo JText::_('COM_SF_CROSS_REPORT'); ?>
			</small>
			</th>
		</tr>
		</table>
		<?php } else { 
	JToolBarHelper::title( JText::_('COM_SF_CROSS_REPORT'), 'print.png' );
}?> 
		<table class="adminform">
		<tr>
			<th colspan="2" align="left"><?php echo JText::_('COM_SF_REPORT_DETAILS'); ?></th>
		</tr>
		<tr><td width="20%" valign="top"><?php echo JText::_('COM_SF_SELECT_SURVEY'); ?>:</td>
			<td><?php echo $lists['surveys']?></td>
		</tr>
		<?php if ($lists['mquest_id'] != '') {?>
		<tr><td valign="top"><?php echo JText::_('COM_SF_SELECT_COLUMN_QUESTION'); ?></td>
			<td><?php echo $lists['mquest_id']?></td>
		</tr>
		<tr><td valign="top"><?php echo JText::_('COM_SF_SELECT_QUESTION_YOU_WOULD_LIKE'); ?></td>
			<td><?php echo $lists['cquest_id']?></td>
		</tr>
		<tr><td valign="top"><?php echo JText::_('COM_SF_FROM_DATE'); ?></td>
			<td>
			<?php if (!_JOOMLA15) {?>
			<input class="text_area" type="text" name="start_date" id="start_date" size="15" maxlength="19" value="" />
				<input name="reset" type="reset" class="button" onclick="return showCalendar('start_date', 'y-mm-dd');" value="..." />
			<?php } else {
						echo JHTML::calendar('', 'start_date', 'start_date', '%Y-%m-%d',' class="text_area" size="15" maxlength="19" ');
					}
			?>
			</td>
		</tr>
		<tr><td valign="top"><?php echo JText::_('COM_SF_TO_DATE'); ?></td>
			<td>
			<?php if (!_JOOMLA15) {?>
			<input class="text_area" type="text" name="end_date" id="end_date" size="15" maxlength="19" value="" />
				<input name="reset" type="reset" class="button" onclick="return showCalendar('end_date', 'y-mm-dd');" value="..." />
			<?php } else {
						echo JHTML::calendar('', 'end_date', 'end_date', '%Y-%m-%d',' class="text_area" size="15" maxlength="19" ');
					}
			?>
			</td>
		</tr>
		<tr><td valign="top"><?php echo JText::_('COM_SF_INCLUDE_COMPLETE'); ?></td>
			<td><input type="checkbox" name="is_complete" checked="checked" value="1" /></td>
		</tr>
		<tr><td valign="top"><?php echo JText::_('COM_SF_INCLUDE_NOT_COMPLETE'); ?></td>
			<td><input type="checkbox" name="is_notcomplete" checked="checked" value="1" /></td>
		</tr>
		<?php }
		else 
			echo "<tr><td colspan='2'>".JText::_('COM_SF_CROSS_REPORT_CAN_NOT_BE_CREATED')."</td></tr>";
		?>		
		</table>
		<input type="hidden" name="option" value="com_surveyforce" />
		<input type="hidden" name="id" value="0" />
		<input type="hidden" name="task" value="cross_rep" />
		</form>
<?php
	EF_menu_footer();
	}
	
	function SF_showAdvReport( $rows, $lists, $pageNav, $option ) {
		global $my, $task;

		mosCommonHTML::loadOverlib();
		mosCommonHTML::loadCalendar(); 
		survey_force_adm_html::SF_JS_getObj();
		$tabs = new mosTabs(1);
		EF_menu_header(); 
		?>
		<script type="text/javascript" language="javascript">
		function submitbutton(pressbutton) {
				var form = document.adminForm;
				if (pressbutton == 'view_advrep' && $$('.cross-page').hasClass('open')[0] == true) {
					submitform( 'get_cross_rep' );
					return;
				}

				if (pressbutton == 'view_advrep'&& $$('.csv-page').hasClass('open')[0] == true && parseInt(form.boxchecked.value) == 0) {
					alert("<?php echo JText::_('COM_SF_PLEASE_MAKE_SELECTION_FROM_LIST'); ?>");
					return;
				}
				else if(pressbutton == 'view_advrep'&& $$('.csv-page').hasClass('open')[0] == true) {
					submitform( 'view_irep_surv' );
					return;
				}
			
		}

		</script>
		<form action="index.php" method="post" name="adminForm">
		<?php if (!class_exists('JToolBarHelper')) { ?>
		<table class="adminheading">
		<tr>
			<th>
			<?php echo _SURVEY_FORCE_COMP_NAME?>: <small><?php echo JText::_('COM_SF_ADVANCED_REPORTS'); ?></small>
			</th> 			
		</tr>
		</table>	
		<?php } else { 
	JToolBarHelper::title( JText::_('COM_SF_ADVANCED_REPORTS'), 'print.png' );
}?> 
		<?php
		$tabs->startPane("advPane");
		$tabs->startTab(JText::_('COM_SF_CROSS_REPORT'),"cross-page");
		?>
		<table class="adminform">
		<tr>
			<th colspan="2" align="left"><?php echo JText::_('COM_SF_REPORT_DETAILS'); ?></th>
		</tr>
		<tr><td width="20%" valign="top"><?php echo JText::_('COM_SF_SELECT_SURVEY'); ?>:</td>
			<td><?php echo $lists['surveys']?></td>
		</tr>
		<?php if ($lists['mquest_id'] != '') {?>
		<tr><td valign="top"><?php echo JText::_('COM_SF_SELECT_COLUMN_QUESTION'); ?></td>
			<td><?php echo $lists['mquest_id']?></td>
		</tr>
		<tr><td valign="top"><?php echo JText::_('COM_SF_SELECT_QUESTION_YOU_WOULD_LIKE'); ?></td>
			<td><?php echo $lists['cquest_id']?></td>
		</tr>
		<tr><td valign="top"><?php echo JText::_('COM_SF_FROM_DATE'); ?></td>
			<td>
			<?php if (!_JOOMLA15) {?>
			<input class="text_area" type="text" name="start_date" id="start_date" size="15" maxlength="19" value="" />
				<input name="reset" type="reset" class="button" onclick="return showCalendar('start_date', 'y-mm-dd');" value="..." />
				<?php } else {
						echo JHTML::calendar('', 'start_date', 'start_date', '%Y-%m-%d',' class="text_area" size="15" maxlength="19" ');
					}
			?>
			</td>
		</tr>
		<tr><td valign="top"><?php echo JText::_('COM_SF_TO_DATE'); ?></td>
			<td>
			<?php if (!_JOOMLA15) {?>
			<input class="text_area" type="text" name="end_date" id="end_date" size="15" maxlength="19" value="" />
				<input name="reset" type="reset" class="button" onclick="return showCalendar('end_date', 'y-mm-dd');" value="..." />
				<?php } else {
						echo JHTML::calendar('', 'end_date', 'end_date', '%Y-%m-%d',' class="text_area" size="15" maxlength="19" ');
					}
			?>
			</td>
		</tr>
		<tr><td valign="top"><?php echo JText::_('COM_SF_INCLUDE_COMPLETE'); ?></td>
			<td><input type="checkbox" name="is_complete" checked="checked" value="1" /></td>
		</tr>
		<tr><td valign="top"><?php echo JText::_('COM_SF_INCLUDE_NOT_COMPLETE'); ?></td>
			<td><input type="checkbox" name="is_notcomplete" checked="checked" value="1" /></td>
		</tr>
		<tr><td valign="top"><?php echo JText::_('COM_SF_GET_REPORT_IN'); ?></td>
			<td><select name="rep_type" class="inputbox" >
				<option value="pdf" selected="selected">Acrobat (PDF)</option>
				<option value="csv">Excel (CSV)</option>
				</select>
			</td>
		</tr>
		<?php }
		else 
			echo "<tr><td colspan='2'>". JText::_('COM_SF_CROSS_REPORT_CAN_NOT_BE_CREATED')."</td></tr>";
		?>		
		</table>
		<?php
		$tabs->endTab();
		$tabs->startTab(JText::_('COM_SF_CSV_REPORT'),"csv-page");
		?>
		<table width="100%" >
		<tr><td align="left">
			<label><input type="checkbox" name="inc_imp" value="1"><?php echo JText::_('COM_SF_INCLUDE_IMP_SCALE'); ?></label><br />
			<label><input type="checkbox" name="add_info" checked="checked" value="1"><?php echo JText::_('COM_SF_INCLUDE_DATE_STATUS_OR_COMPLETION'); ?></label>
			</td><td  align="right" nowrap>
			<?php echo $lists['category'];?>
			</td></tr>
		</table>
		<table class="adminlist">
		<tr>
			<th width="20">#</th>
			<th width="20" class="title"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($rows); ?>);" /></th>
			<th class="title"><?php echo JText::_('COM_SF_NAME'); ?></th>
			<th class="title"><?php echo JText::_('COM_SF_ACTIVE'); ?></th>
			<th class="title"><?php echo JText::_('COM_SF_CATEGORY'); ?></th>
			<th class="title"><?php echo JText::_('COM_SF_AUTHOR'); ?></th>
			<th class="title"><?php echo JText::_('COM_SF_PUBLIC'); ?></th>
			<th class="title"><?php echo JText::_('COM_SF_FOR_INVITED'); ?></th>
			<th class="title"><?php echo JText::_('COM_SF_FOR_REG'); ?></th>
			<th class="title"><?php echo JText::_('COM_SF_FOR_USERS_IN_LISTS'); ?></th>
			<th class="title"><?php echo JText::_('COM_SF_EXPIRED_ON'); ?></th>
		</tr>
		<?php
		$k = 0;
		for ($i=0, $n=count($rows); $i < $n; $i++) {
			$row = $rows[$i];
			$link = '#';
			$img_published	= $row->published ? 'tick.png' : 'publish_x.png';
			$task_published	= $row->published ? 'unpublish_surv' : 'publish_surv';
			$alt_published 	= $row->published ? 'Published' : 'Unpublished';
			$img_public		= $row->sf_public ? 'tick.png' : 'publish_x.png';
			$img_invite		= $row->sf_invite ? 'tick.png' : 'publish_x.png';
			$img_reg		= $row->sf_reg ? 'tick.png' : 'publish_x.png';
			$img_spec		= $row->sf_special ? 'tick.png' : 'publish_x.png';
			$checked = mosHTML::idBox( $i, $row->id);
			?>
			<tr class="<?php echo "row$k"; ?>">
				<td><?php echo $pageNav->rowNumber( $i ); ?></td>
				<td><?php echo $checked; ?></td>
				<td align="left">					
					<span>
					<?php if (!_JOOMLA15) { ?>
					<script language="javascript" type="text/javascript">
						var des<?php echo $row->id;?> = '<?php echo str_replace("'","&#039;", str_replace("\r",'', str_replace("\n",'', nl2br($row->sf_descr))))?>';
					</script>
					<a href="<?php echo $link;?>" onmouseover="return overlib(des<?php echo $row->id;?>, CAPTION, <?php echo JText::_('COM_SF_SURVEY_DESCRIPTION')?>, BELOW, RIGHT, WIDTH, '280');" onmouseout="return nd();" ><?php echo $row->sf_name ?></a>
					<?php } else { ?>
						<?php echo mosToolTip( strip_tags(txt2overlib(mysql_escape_string(nl2br($row->sf_descr)))), JText::_('COM_SF_SURVEY_DESCRIPTION'), 280, 'tooltip.png', $row->sf_name, $link );
						}
						?>			
					</span>
				</td>
				<td align="left">
					<a href="javascript: void(0);" onClick="return listItemTask('cb<?php echo $i;?>','<?php echo $task_published;?>')">
						<img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/<?php echo $img_published;?>"  border="0" alt="<?php echo $alt_published; ?>" />
					</a>
				</td>
				<td align="left">
					<?php echo $row->sf_catname; ?>
				</td>
				<td align="left">
					<?php echo $row->username; ?>
				</td>
				<td align="left">
						<img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/<?php echo $img_public;?>"  border="0" alt="<?php echo $alt_published; ?>" />
				</td>
				<td align="left">
						<img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/<?php echo $img_invite;?>"  border="0" alt="<?php echo $alt_published; ?>" />
				</td>
				<td align="left">
						<img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/<?php echo $img_reg;?>"  border="0" alt="<?php echo $alt_published; ?>" />
				</td>
				<td align="left">
						<img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/<?php echo $img_spec;?>"  border="0" alt="<?php echo $alt_published; ?>" />
				</td>				
				<td align="left">
						<?php echo $row->sf_date == '0000-00-00 00:00:00'? '' :mosFormatDate($row->sf_date, "Y-m-d");?>
				</td>
			</tr>
			<?php
			$k = 1 - $k;
		}
		?>
		</table>
		<?php echo $pageNav->getListFooter(); 
		?><br/><?php
		$tabs->endTab();
		?><br/><?php
		$tabs->endPane();
		?>
		<input type="hidden" name="option" value="com_surveyforce" />
		<input type="hidden" name="task" value="adv_report" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="hidemainmenu" value="0">
		</form>
		<?php
		EF_menu_footer();
	}
	
	function show_results( $rows, $lists, $option ) {
		global $mosConfig_live_site;
		EF_menu_header();
	?>
		<form action="index.php" method="post" name="adminForm">
		<?php if (!class_exists('JToolBarHelper')) { ?>
		<table class="adminheading">
		<tr>
			<th>
			<?php echo _SURVEY_FORCE_COMP_NAME?>: <small><?php echo JText::_('COM_SF_SURVEY_RESULTS'); ?></small>
			</th> 			
			<td width="right" nowrap><?php echo JText::_('COM_SF_SURVEY'); ?>:<?php echo $lists['survey']?></td>
		</tr>
		</table>
		<?php } else { 
	JToolBarHelper::title( JText::_('COM_SF_SURVEY_RESULTS'), 'print.png' ); ?>
		<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td align="left" width="100%">&nbsp;</td>
			<td nowrap="nowrap">
				<?php echo JText::_('COM_SF_SURVEY'); ?>:<?php echo $lists['survey']?>
			</td> 			
		</tr>
		</table>

<?php }?> 
		<table class="adminform">
		<tr>
			<th colspan="2" align="left"><?php echo JText::_('COM_SF_SURVEY_RESULTS'); ?> - <?php echo $lists['sname']?></th>
		</tr>
		</table>
		<?php foreach( $rows as $row ){
			if ($row) {	?>			
			<?php echo $row?><hr/>
		<?php } 
		} ?>
		
		<input type="hidden" name="option" value="com_surveyforce" />
		<input type="hidden" name="task" value="show_results" />
		<input type="hidden" name="boxchecked" value="0" />
		</form>
	<?php
		EF_menu_footer();
	}
		function View_AboutPage_HTML() {
		global $mosConfig_live_site, $survey_version;		
		$version = $survey_version;
		EF_menu_header();
	 ?>
	 	<form action="index.php" method="post" name="adminForm">
	 	<input type="hidden" name="option" value="com_surveyforce" />
		<input type="hidden" name="task" value="about" />

		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="hidemainmenu" value="0">
		</form>
		<?php if (!class_exists('JToolBarHelper')) { ?>
			 <table class="adminheading">
				<tr>
					<th>
					<?php echo _SURVEY_FORCE_COMP_NAME?>: <?php echo JText::_('COM_SF_ABOUT'); ?>
					</th>
				</tr>
			</table>	
			<?php } else { 
	JToolBarHelper::title( JText::_('COM_SF_ABOUT'), 'systeminfo.png' );
}?> 
			 <table width="100%" border="0">	
				<tr>
					<td valign="top">
					<table  width="100%" style="background-color: #F7F8F9; border: solid 1px #d5d5d5; width: 100%; padding: 10px; border-collapse: collapse;">
						<tr>
							<td style="text-align:left; font-size:14px; font-weight:400; line-height:18px " colspan="2"><strong><?php echo JText::_('COM_SF_SURVEYFORCE'); ?></strong> <?php echo JText::_('COM_SF_COMPONENT_OF_JOOMLA'); ?><a href="http://www.JoomPlace.com">JoomPlace</a>.</td>
						</tr>
						<tr>
							<td width="120" bgcolor="#FFFFFF" align="left" style="border: solid 1px #d5d5d5;"><?php echo JText::_('COM_SF_INSTALLED_VERSION'); ?></td>
							<td bgcolor="#FFFFFF" align="left" style="border: solid 1px #d5d5d5;"> &nbsp;<b><?php echo $version;?></b></td>
						</tr>
						<tr>
							<td bgcolor="#FFFFFF" align="left" style="border: solid 1px #d5d5d5;"><?php echo JText::_('COM_SF_LATEST_VERSION'); ?></td>
							<td style="border: solid 1px #d5d5d5;"><?php echo ep_update_checker();?></td>
						</tr>
						<tr>
							<td valign="top" bgcolor="#FFFFFF" align="left" style="border: solid 1px #d5d5d5;"><?php echo JText::_('COM_SF_ABOUT'); ?>:</td>
							<td bgcolor="#FFFFFF" align="left" style="border: solid 1px #d5d5d5;">
							<?php echo JText::_('COM_SF_SURVEYFORCE_COMPONENT_ALLOWS'); ?>
							</td>
						</tr>
						<tr>
							<td bgcolor="#FFFFFF" align="left" style="border: solid 1px #d5d5d5;"><?php echo JText::_('COM_SF_SUPPORT_FORUM'); ?>:</td>
							<td bgcolor="#FFFFFF" align="left" style="border: solid 1px #d5d5d5;">
							<a target="_blank" href="http://www.joomplace.com/support">http://www.JoomPlace.com/support</a>
							</td>
						</tr>
						<tr>
							<td bgcolor="#FFFFFF" align="left" style="border: solid 1px #d5d5d5;"><?php echo JText::_('COM_SF_DISCLAIMER_LICENSE'); ?></td>
							<td bgcolor="#FFFFFF" align="left" style="border: solid 1px #d5d5d5;">
							<a target="_blank" href="http://www.joomplace.com/disclaimer.html">http://www.JoomPlace.com/disclaimer.html</a>
							</td>
						</tr>
					</table>
					<br/>
					<table border="1" cellpadding="5" width="100%" style="background-color: #F7F8F9; border: solid 1px #d5d5d5; width: 100%; padding: 10px; border-collapse: collapse;">						
						<tr>
							<td colspan="2" style="background-color: #e7e8e9;text-align:left; font-size:16px; font-weight:400; line-height:18px "><strong><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/tick.png"><?php echo JText::_('COM_SF_SAY_YOUR_THANK_YOU'); ?></strong></td>
						</tr>
						<tr>
							<td colspan="2" style="padding-left:20px">			
							<div style="float:left; width:720px;">
							<p style="font-size:12px; font-weight:800;"><?php echo JText::_('COM_SF_SAY_YOUR_THANK_YOU_AND'); ?> <span style="font-size:14pt;font-weight:bold"><?php echo JText::_('COM_SF_HELP_IT'); ?></span> <?php echo JText::_('COM_SF_BY_SHARING_YOUR_EXPIRIENCE'); ?><a href="http://extensions.joomla.org/extensions/contacts-and-feedback/surveys/11301" target="_blank">http://extensions.joomla.org/</a> <?php echo JText::_('COM_SF_AND_THREE_MINUTES'); ?></p>
							</div>
							<div style="float:left;margin:5px">
							<a href="http://extensions.joomla.org/extensions/contacts-and-feedback/surveys/11301" target="_blank"><img src="<?php echo $mosConfig_live_site;?>/administrator/components/com_surveyforce/images/rate_us.png" title="<?php echo JText::_('COM_SF_RATE_US'); ?>" alt="<?php echo JText::_('COM_SF_RATE_US_AT_EXTENSIONS'); ?>"  style="padding-top:5px;"/></a>	
							</div>
							
							<div style="clear:both; margin:5px; padding-top:5px;"><hr style="color:#CCCCCC;"/></div>
							<div style="float:left; width:680px;">
								<p style="font-size:12px; font-weight:800;"><?php echo JText::_('COM_SF_ALTERNATIVELY_HERE_QUICK_WAY'); ?></p>
							</div>
							<div style="float:left;display: block; width: 234px; height: 60px; background: transparent url(http://cdn.hotscripts.com/img/widgets/rt_234x60-1.gif) 0 0 no-repeat; font: normal 11px/12px Arial, Helvetica, sans-serif; color: #fff; text-align: left;"><form action="http://www.hotscripts.com/rate/73019/?RID=N578805" method="post" style="display: block; position: relative; left: 79px; margin: 0; padding: 8px 0 0; width: 153px; overflow: hidden; text-align: left;" target="_blank"><strong><?php echo JText::_('COM_SF_LIKE_OUR_SCRIPT'); ?></strong> <?php echo JText::_('COM_SF_RATE_IT_AT'); ?><a target="_blank" href="http://www.hotscripts.com/listing/surveyforce-deluxe/?RID=N578805" style="color: #fff; text-decoration: none;" >PHP</a> > <a target="_blank" href="http://www.hotscripts.com/?RID=N578805" style="color: #fff; text-decoration: none;"><?php echo JText::_('COM_SF_HOT_SCRIPTS'); ?></a><br /><select name="rate" style="width: 98px; overflow: hidden; font: normal 11px/12px Arial, Helvetica, sans-serif; color: #000; float: left; margin: 5px 4px 0 0; padding: 0; clear: none;"><option value="5"><?php echo JText::_('COM_SF_EXCELLENT'); ?></option><option value="4"><?php echo JText::_('COM_SF_VERY_GOOD'); ?></option><option value="3"><?php echo JText::_('COM_SF_GOOD'); ?></option><option value="2"><?php echo JText::_('COM_SF_FAIR'); ?></option><option value="1"><?php echo JText::_('COM_SF_POOR'); ?></option></select><input type="image" src="http://cdn.hotscripts.com/img/widgets/btn_vote-3.gif" style="width: 49px; height: 22px; overflow: hidden; float: left; margin: 4px 0 0; clear: none; padding: 0; border: 0;" /></form></div>
	
							
							<div style="clear:both">
							<!--x-->
							</div>


							</td>
						</tr>	
						<tr>
							<td colspan="2" style="background-color: #e7e8e9;text-align:left; font-size:14px; font-weight:400; line-height:18px "><strong><img src="<?php echo JURI::root()?>administrator/components/com_surveyforce/images/tick.png"><?php echo JText::_('COM_SF_JOOMPLACE_NEWS'); ?></strong></td>
						</tr>
						<tr>
							<td colspan="2" style="padding-left:20px" align="justify"><div id="ep_LatestNews" style="width:539px;"><?php echo ep_news();?></div></td>
						</tr>					
					</table>			
					</td>
				</tr>
			</table>
	<?php
	EF_menu_footer();
	}
	
	
	function SF_ShowTemplates( &$rows, &$pageNav, $option ) {
		global $my;

		mosCommonHTML::loadOverlib();
		EF_menu_header();
		?>
		<form action="index.php" method="post" name="adminForm">
		<?php if (!class_exists('JToolBarHelper')) { ?>
		<table class="adminheading" >
		<tr>
			<th class="categories">
			<?php echo _SURVEY_FORCE_COMP_NAME?>: <small><?php echo JText::_('COM_SF_TEMPLACE_LIST'); ?></small>
			</th>
		</tr>
		</table>
		<?php } else { 
			JToolBarHelper::title( JText::_('COM_SF_TEMPLACE_LIST'), 'thememanager.png' );
		}?>
		<table class="adminlist">
		<tr>
			<th width="20">#</th>
			<th width="20" class="title"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($rows); ?>);" /></th>
			<th class="title"><?php echo JText::_('COM_SF_NAME'); ?></th>
		</tr>
		<?php
		$k = 0;
		for ($i=0, $n=count($rows); $i < $n; $i++) {
			$row = $rows[$i];
			$link 	= 'index.php?option=com_surveyforce&task=editA_template&id='. $row->id;
			$checked = mosHTML::idBox( $i, $row->id);?>
			<tr class="<?php echo "row$k"; ?>">
				<td><?php echo $pageNav->rowNumber( $i ); ?></td>
				<td><?php echo $checked; ?></td>
				<td align="left">
						<?php echo $row->sf_name;?>
				</td>
			</tr>
			<?php
			$k = 1 - $k;
		}?>
		</table>
		<table class="adminform" style="margin-top:2px; margin-bottom:2px; ">
			<tr><td><small style="padding-left:20px ">
			<?php echo JText::_('COM_SF_IF_YOU_HAVE_ZIP_FILE'); ?>
			</small></td></tr>
		</table>
		<?php echo $pageNav->getListFooter(); ?>
		<input type="hidden" name="option" value="com_surveyforce" />
		<input type="hidden" name="task" value="templates" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="hidemainmenu" value="0">
		</form>
		<?php
		EF_menu_footer();
	}
	
	function SF_editTemplate( &$row, &$lists, $option ) {
		global $mosConfig_live_site; 
		mosCommonHTML::loadOverlib();
		EF_menu_header();
		?>
		<style type="text/css">
			div.fileinputs {
				position: relative;
			}
			
			div.fakefile {
				position: absolute;
				top: 0px;
				left: 2px;
				z-index: 2;
			}
			
			div.fakefile_inp {
				position: relative;
				text-align: left;
				z-index: 10;
			}
			
			input.file {
				-moz-opacity:0 ;
				filter:alpha(opacity: 0);
				opacity: 0;
			}
		</style>
		<script language="javascript" type="text/javascript">
		<!--
		var W3CDOM = (document.createElement && document.getElementsByTagName);
		
		function initFileUploads() {
			if (!W3CDOM) return;
			var fakeFileUpload = document.createElement('div');
			fakeFileUpload.className = 'fakefile';
			var f_input = document.createElement('input');
			f_input.className = 'text_area';
			f_input.size = 50;
			fakeFileUpload.appendChild(f_input);
			var image = document.createElement('img');
			
			image.src='<?php echo $mosConfig_live_site;?>/administrator/components/com_surveyforce/images/filesave.png';
			image.style['position'] = 'absolute';
			fakeFileUpload.appendChild(image);
			var x = document.getElementsByTagName('input');
			for (var i=0;i<x.length;i++) {
				if (x[i].type != 'file') continue;
				if (x[i].parentNode.className != 'fakefile_inp') continue;
				if (x[i].parentNode.parentNode.className != 'fileinputs') continue;
				x[i].className = 'file hidden';
				var clone = fakeFileUpload.cloneNode(true);
				x[i].parentNode.parentNode.appendChild(clone);
				x[i].relatedElement = clone.getElementsByTagName('input')[0];
				x[i].onchange = x[i].onmouseout = function () {
					this.relatedElement.value = this.value;
				}
			}
		}
		Joomla.submitbutton = function(pressbutton) {
			var form = document.adminForm;
			if (pressbutton == 'cancel_template') {
				submitform( pressbutton );
				return;
			}
			// do field validation
			if (form.userfile.value == ""){
				alert( "<?php echo JText::_('COM_SF_PLEASE_SELECT_FILE'); ?>" );
			} else {
				form.submit();
			}
		}
		//-->
		</script>

		<form enctype="multipart/form-data" action="index.php" method="post" name="adminForm">
		<?php if (!class_exists('JToolBarHelper')) { ?> 
		<table class="adminheading">
		<tr>
			<th class="install">
			<?php echo _SURVEY_FORCE_COMP_NAME?>: <small><?php echo JText::_('COM_SF_INSTALL_TEMPLATE'); ?></small>	
			</th>
		</tr>
		</table>
		<?php } else { 
			JToolBarHelper::title( JText::_('COM_SF_INSTALL_TEMPLATE'), 'thememanager.png' );
		}?>
		<table class="adminform">
		<tr>
			<th colspan="2">
			<?php echo JText::_('COM_SF_UPLOAD_TEMPLATE_PACKAGE'); ?>
			</th>
		</tr>
		<tr>
			<td align="left" width="20%">
				<?php echo JText::_('COM_SF_PACKAGE_FILE'); ?>
			</td>
			<td align="left" valign="top">
				<div class="fileinputs">
					<div class="fakefile_inp">
						<input type="file" size="50" name="userfile" >
					</div>
				</div>
			</td>
		</tr>
		</table>
		<script type="text/javascript">
		<!--
		initFileUploads();
		//-->
		</script>
		<input type="hidden" name="task" value="uploadtemplate"/>
		<input type="hidden" name="option" value="<?php echo $option;?>"/>
		</form>	
		<table class="content">
		<?php 
		mosHTML::writableCell('media/surveyforce',1);
		mosHTML::writableCell( 'media' ); ?>
		</table>
		<?php
		EF_menu_footer();
	}
	
	function SF_editCSSSource( $template_id, $template, &$content, $option ) {
		global $mosConfig_absolute_path;
		$css_path = $mosConfig_absolute_path . '/media/surveyforce/' . $template . '/surveyforce.css';
		EF_menu_header();
		?>
		<form action="index.php" method="post" name="adminForm">				
		<?php if (!class_exists('JToolBarHelper')) { ?> 
		<table class="adminheading"><tr>
			<th class="templates">
				<?php echo _SURVEY_FORCE_COMP_NAME?>: <small><?php echo JText::_('COM_SF_TEMPLATE_CSS_EDITOR'); ?></small>
			</th></tr></table>
		<?php } else { 
			JToolBarHelper::title( JText::_('COM_SF_INSTALL_TEMPLATE'), 'thememanager.png' );
		}?>
		<table cellpadding="1" cellspacing="1" border="0" width="100%">
		<tr>
			<td width="280">
			
			
			
			</td>
			<td width="260">
				<span class="componentheading">surveyforce.css is :
				<b><?php echo is_writable($css_path) ? JText::_('COM_SF_WRITEABLE') : JText::_('COM_SF_UNWRITEABLE') ?></b>
				</span>
			</td>
			<?php
			if (mosIsChmodable($css_path)) {
				if (is_writable($css_path)) {
			?>
			<td>
				<input type="checkbox" id="disable_write" name="disable_write" value="1"/>
				<label for="disable_write"><?php echo JText::_('COM_SF_MAKE_UNWRITEABLE'); ?></label>
			</td>
			<?php
				} else {
			?>
			<td>
				<input type="checkbox" id="enable_write" name="enable_write" value="1"/>
				<label for="enable_write"><?php echo JText::_('COM_SF_OVERRIDE_WRITE_PROTECTION'); ?></label>
			</td>
			<?php
				} // if
			} // if
			?>
		</tr>
		</table>
		<table class="adminform">
			<tr><th><?php echo $css_path; ?></th></tr>
			<tr><td><textarea style="width:100%;height:500px" cols="110" rows="25" name="filecontent" class="inputbox"><?php echo $content; ?></textarea></td></tr>
		</table>
		<input type="hidden" name="template" value="<?php echo $template_id; ?>" />
		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="task" value="" />
		</form>			
		<?php
		EF_menu_footer();
	}
	
	function View_Samples() {
		global $mosConfig_absolute_path, $mosConfig_live_site, $database;
		
		$query = "SELECT `id` FROM `#__survey_force_survs` WHERE `sf_name` = 'Customer Service Satisfaction Survey'";
		$database->setQuery($query);
		$is_sample1 = $database->loadResult();
		
		$query = "SELECT `id` FROM `#__survey_force_survs` WHERE `sf_name` = 'Sample Branching Survey'";
		$database->setQuery($query);
		$is_sample2 = $database->loadResult();

		EF_menu_header();
		?>
		<form action="index.php" method="post" name="adminForm">				
		<?php if (!class_exists('JToolBarHelper')) { ?> 
		<table class="adminheading"><tr>
			<th >
				<?php echo _SURVEY_FORCE_COMP_NAME?>: <small><?php echo JText::_('COM_SF_INSTALL_SAMPLE_SURVEY'); ?></small>
			</th></tr></table>
		<?php } else { 
			JToolBarHelper::title( JText::_('COM_SF_INSTALL_SAMPLE_SURVEYS'), 'help_header.png' );
		}?>
		<table width="100%" class="adminlist">
			<tr>
				<th class="title" width="50%" valign="top">
					<strong><?php echo JText::_('COM_SF_SAMPLE_CUSTOMER_SERVICE'); ?></strong>
				</th>
				<th class="title" width="50%" valign="top">
					<strong><?php echo JText::_('COM_SF_SAMPLE_BRANCHING_SURVEY'); ?></strong>
				</th>
			</tr>
			<tr>
				<td valign="top"><img src="<?php echo $mosConfig_live_site;?>/administrator/components/com_surveyforce/images/sample1.png" title="<?php echo JText::_('COM_SF_CUSTOMER_SERVICE_SATISFACTION'); ?>" alt="<?php echo JText::_('COM_SF_CUSTOMER_SERVICE_SATISFACTION'); ?>"  style="padding-top:5px;"/></td>
				<td valign="top"><img src="<?php echo $mosConfig_live_site;?>/administrator/components/com_surveyforce/images/sample2.png" title="<?php echo JText::_('COM_SF_S_SAMPLE_BRANCHING_SURVEY'); ?>" alt="<?php echo JText::_('COM_SF_S_SAMPLE_BRANCHING_SURVEY'); ?>"  style="padding-top:5px;"/></td>
			</tr>
			<tr>
				<td valign="top">
					<?php if ($is_sample1) {?>
					<?php echo JText::_('COM_SF_DIRECT_LINK_TO_FRONT_END'); ?><br />
					<a href="<?php echo JURI::root().'index.php?option=com_surveyforce&survey='.$is_sample1; ?>" target="_blank"><?php echo JText::_('COM_SF_CUSTOMER_SERVICE_SATISFACTION'); ?></a>
					<?php } else { ?>
					<form name="adminForm1" action="index.php" method="post">
						<input type="submit" name="install" value="<?php echo JText::_('COM_SF_INSTALL_THIS_SAMPLE'); ?>" />
						
						<input type="hidden" name="option" value="com_surveyforce" />
						<input type="hidden" name="task" value="installsample1" />
					</form>
					<p>
					<?php echo JText::_('COM_SF_AFTER_YOU_INSTALL'); ?>
					</p>
					<?php } ?>
				</td>
				<td valign="top">
					<?php if ($is_sample2) {?>
					<?php echo JText::_('COM_SF_DIRECT_LINK_TO_FRONT_END'); ?><br />
					<a href="<?php echo JURI::root().'index.php?option=com_surveyforce&survey='.$is_sample2; ?>" target="_blank"><?php echo JText::_('COM_SF_S_SAMPLE_BRANCHING_SURVEY'); ?></a>
					<?php } else { ?>
					<form name="adminForm2" action="index.php" method="post">
						<input type="submit" name="install" value="<?php echo JText::_('COM_SF_INSTALL_THIS_SAMPLE'); ?>" />
						
						<input type="hidden" name="option" value="com_surveyforce" />
						<input type="hidden" name="task" value="installsample2" />
					</form>
					<p>
					<?php echo JText::_('COM_SF_AFTER_YOU_INSTALL'); ?>
					</p>
					<?php } ?>
				</td>
			</tr>
		</table>
		<?php
		EF_menu_footer();
	}

}

function EF_menu_header(){
	global $mosConfig_absolute_path, $mosConfig_live_site;
	$task = mosGetParam($_REQUEST, 'task', '');
	$repTemplate = mosGetParam($_REQUEST, 'template', '');
	$mode = mosGetParam($_REQUEST, 'mode', '');
	JHTML::_('behavior.modal', 'a.modal');
	
	JHTML::_('behavior.mootools');

	$show = 0;
	if ($task == 'about' || $task == 'license'|| $task == 'disclaimer'){
		$show = 0;
	}elseif ($task == 'categories'  || $task == 'add_cat' || $task ==  'edit_cat'  || $task == 'editA_cat' ) {
		$show = 1;
	}elseif ($task == 'surveys' || $task == 'add_surv' || $task == 'edit_surv' || $task == 'editA_surv' || $task == 'publish_surv' || $task == 'unpublish_surv' || $task == 'move_surv_sel' || $task == 'copy_surv_sel' || $task == 'show_results' || $task == 'questions' || $task == 'add_new_section' || $task == 'editA_sec' || $task == 'add_ranking' || $task == 'add_pagebreak' || $task == 'add_boilerplate' || $task == 'add_likert' || $task == 'add_pickone' || $task == 'add_pickmany' || $task == 'add_short' || $task == 'add_drp_dwn' || $task == 'add_drg_drp' || $task == 'set_default' || $task == 'edit_quest' || $task == 'editA_quest' || $task == 'move_quest_sel' || $task == 'copy_quest_sel' || $task == 'iscales' || $task == 'add_iscale' || $task == 'add_iscale_from_quest'  || $task == 'edit_iscale' || $task == 'editA_iscale') {
		$show = 2;
	}elseif ($task == 'generate_invitations' || $task == 'users'  || $task == 'add_list' || $task == 'edit_list' || $task == 'view_users' || $task == 'add_user' || $task == 'edit_user' || $task == 'editA_user' || $task == 'move_user_sel' || $task == 'copy_user_sel' || $task == 'invite_users' || $task == 'remind_users') {
		$show = 3;
	}elseif ($task == 'authors' || $task == 'add_author') {
		$show = 4;
	}elseif ($task == 'emails' || $task == 'add_email' || $task == 'edit_email' || $task == 'editA_email') {
		$show = 5;
	}elseif ($task == 'reports' || $task == 'rep_pdf' || $task == 'rep_csv' || $task == 'view_result' || $task == 'view_result_c' || $task == 'rep_surv' || $task == 'view_rep_surv' || $task == 'view_rep_survA' || $task == 'rep_surv_print' || $task == 'rep_print' || $task == 'rep_list' || $task == 'view_rep_list' || $task == 'view_rep_listA' || $task == 'rep_list_print' || $task == 'adv_report' || $task == 'view_irep_surv' || $task == 'get_cross_rep' || $task == 'generateExcel' || $task == 'generateExcelNL'|| $task == 'generateExcelSP' ) {
		$show = 6;
	}elseif ($task == 'config' || $task == 'menu_man' || $task == 'no_menu' || $task == 'templates' || $task == 'add_template' || $task == 'edit_template' || $task == 'editA_template' ) {
		$show = 7;
	}elseif ($task == 'sample') {		
		$show = 8;	
	}elseif ($task == 'help' || $task == 'support' || $task == 'faq'  || $task == 'history') {
		$show = 9;					
	}
	?>
	<table width="100%">
		<tr>
			<td valign="top" width="220">
			<div>
	<style>
	.icon-48-static 		{ background-image: url(./templates/bluestork/images/header/icon-48-static.png); } 
	.icon-48-print 		{ background-image: url(./templates/bluestork/images/header/icon-48-print.png); } 
	.icon-32-cpanel 		{ background-image: url(./templates/bluestork/images/toolbar/icon-32-send.png); } 
	.icon-32-print 		{ background-image: url(./templates/bluestork/images/toolbar/icon-32-print.png); }
	.icon-32-search 		{ background-image: url(./templates/bluestork/images/toolbar/icon-32-search.png); }

	h3 { font-size:13px !important;}
	
	a.menu_link:link, a.menu_link:visited {font-weight:bold;color:#000000;	}
	a.menu_link:hover{ color:#3366CC;	text-decoration:none; }
	/* pane-sliders  */
	.pane-sliders .title {	margin: 0;padding: 2px;color: #666;	cursor: pointer;}	
	.pane-sliders .panel   { border: 1px solid #ccc; margin-bottom: 3px; text-align:left;}
	
	.pane-sliders .panel h3 { background: #f6f6f6; color: #666}
	
	.pane-sliders .content { background: #f6f6f6; }
	
	.pane-sliders .adminlist     { border: 0 none; }
	.pane-sliders .adminlist td  { border: 0 none; }
	
	.jpane-toggler  span     { background: transparent url(<?php echo $mosConfig_live_site;?>/administrator/components/com_surveyforce/images/j_arrow.png) 5px 50% no-repeat; padding-left: 20px;}
	.jpane-toggler-down span { background: transparent url(<?php echo $mosConfig_live_site;?>/administrator/components/com_surveyforce/images/j_arrow_down.png) 5px 50% no-repeat; padding-left: 20px;}
	
	.jpane-toggler-down {  border-bottom: 1px solid #ccc;  }
	
	.jpane-toggler .adminlist tr { text-align:left;}
	
	div.current input, div.current textarea, div.current select {
		float: none !important;
	}
	</style>
	<script type="text/javascript">
    // <!--	
		window.addEvent('domready', function() {
			//new Accordion($$('.panel h3.jpane-toggler'), $$('.panel div.jpane-slider'), {show:<?php echo $show;?>,onActive: function(toggler, i) { toggler.addClass('jpane-toggler-down'); toggler.removeClass('jpane-toggler'); },onBackground: function(toggler, i) { toggler.addClass('jpane-toggler'); toggler.removeClass('jpane-toggler-down'); },duration: 300,opacity: false}); 

			var myAccordion = new Fx.Accordion($$('.panel h3.jpane-toggler'), $$('.panel div.jpane-slider'), {show:<?php echo $show;?>,onActive: function(toggler, i) { toggler.addClass('jpane-toggler-down'); toggler.removeClass('jpane-toggler'); },onBackground: function(toggler, i) { toggler.addClass('jpane-toggler'); toggler.removeClass('jpane-toggler-down'); },duration: 300,opacity: false});

		});

		function TRIM_str(sStr) {
			return (sStr.replace(/^[\s\xA0]+/, "").replace(/[\s\xA0]+$/, ""));
		}
    // -->
	</script>
	<table width="202px" height="100%" cellpadding="0" cellspacing="0" >
	<tr><td style="height:7px; width:200px;background:url(<?php echo $mosConfig_live_site;?>/administrator/components/com_surveyforce/images/top_menu_bg.jpg) no-repeat bottom left ">
	<img src="<?php echo $mosConfig_live_site;?>/components/com_surveyforce/images/blank.png" />
	</td></tr>
	<tr>
		<td style="border-left:1px solid #cccccc;border-right:1px solid #cccccc" align="center"><img src="<?php echo $mosConfig_live_site;?>/administrator/components/com_surveyforce/images/EF_logo.jpg" alt="JoomPlace.com" title="" border="0"/></td>
	</tr>
	<tr>
		<td style="border-left:1px solid #cccccc;border-right:1px solid #cccccc; text-align:center;" align="center"><h3 style='margin:0; padding:0; padding-bottom:5px; color:#5c82c3;font-style:italic;'><?php echo _SURVEY_FORCE_COMP_NAME;?></h3></td>
	</tr>
	<tr>
		<td style="border-left:1px solid #cccccc;border-right:1px solid #cccccc">
		<div id="_content-pane" class="pane-sliders">
			<div class="panel">
				<h3 class="jpane-toggler title" id="cpanel-panel">
					<span><?php echo JText::_('COM_SF_ABOUT'); ?></span>
				</h3>
				<div class="jpane-slider content">
				<table class="adminlist">
					<tr>
						<td width="16px"><img src="<?php echo $mosConfig_live_site;?>/administrator/components/com_surveyforce/images/default.png" ></td>
						<td class="title">
							<a class="menu_link" href="index.php?option=com_surveyforce&task=about"><?php echo JText::_('COM_SF_ABOUT_SURVEYFORCE'); ?></a>
						</td>
					</tr>	
					
				</table>
				</div>
			</div>			
			<div class="panel">
				<h3 class="jpane-toggler title" id="cpanel-panel">
				<span><?php echo JText::_('COM_SF_CATEGORIES'); ?></span>
				</h3>
				<div class="jpane-slider content">
				<table class="adminlist">
					<tr>					
						<td width="16px"><img src="<?php echo $mosConfig_live_site;?>/administrator/components/com_surveyforce/images/content.png" ></td>
						<td class="title">
							<a class="menu_link" href="index.php?option=com_surveyforce&task=categories"><?php echo JText::_('COM_SF_LIST_OF_CATEGORIES'); ?></a>
						</td>
					</tr>
					<tr>
						<td width="16px"><img src="<?php echo $mosConfig_live_site;?>/administrator/components/com_surveyforce/images/new.png" ></td>
						<td class="title">
				<a class="menu_link" href="index.php?option=com_surveyforce&task=add_cat"><?php echo JText::_('COM_SF_NEW_CATEGORY'); ?></a>
						</td>
					</tr>									
				</table>
				</div>
			</div>
			<div class="panel">
				<h3 class="jpane-toggler title" id="cpanel-panel">
				<span><?php echo JText::_('COM_SF_SURVEYS'); ?></span>
				</h3>
				<div class="jpane-slider content">
				<table class="adminlist">
					<tr>					
						<td width="16px"><img src="<?php echo $mosConfig_live_site;?>/administrator/components/com_surveyforce/images/content.png" ></td>
						<td class="title">
							<a class="menu_link" href="index.php?option=com_surveyforce&task=surveys"><?php echo JText::_('COM_SF_LIST_OF_SURVEYS'); ?></a>
						</td>
					</tr>
					<tr>
						<td width="16px"><img src="<?php echo $mosConfig_live_site;?>/administrator/components/com_surveyforce/images/new.png" ></td>
						<td class="title">
				<a class="menu_link" href="index.php?option=com_surveyforce&task=add_surv"><?php echo JText::_('COM_SF_NEW_SURVEY'); ?></a>
						</td>
					</tr>	
					<tr>					
						<td width="16px"><img src="<?php echo $mosConfig_live_site;?>/administrator/components/com_surveyforce/images/content.png" ></td>
						<td class="title">
							<a class="menu_link" href="index.php?option=com_surveyforce&task=iscales"><?php echo JText::_('COM_SF_IMPORTANCE_SCALES'); ?></a>
						</td>
					</tr>								
				</table>
				</div>
			</div>
			<div class="panel">
				<h3 class="jpane-toggler title" id="cpanel-panel">
				<span><?php echo JText::_('COM_SF_MANAGE_USERS'); ?></span>
				</h3>
				<div class="jpane-slider content">
				<table class="adminlist">
					<tr>					
						<td width="16px"><img src="<?php echo $mosConfig_live_site;?>/administrator/components/com_surveyforce/images/users.png" ></td>
						<td class="title">
							<a class="menu_link" href="index.php?option=com_surveyforce&task=users"><?php echo JText::_('COM_SF_MANAGE_USERS'); ?></a>
						</td>
					</tr>
					<tr>
						<td width="16px"><img src="<?php echo $mosConfig_live_site;?>/administrator/components/com_surveyforce/images/newuser.png" ></td>
						<td class="title">
				<a class="menu_link" href="index.php?option=com_surveyforce&task=add_list"><?php echo JText::_('COM_SF_NEW_LIST_OF_USERS'); ?></a>
						</td>
					</tr>	
					<tr>
						<td width="16px"><img src="<?php echo $mosConfig_live_site;?>/administrator/components/com_surveyforce/images/copyuser.png" ></td>
						<td class="title">
				<a class="menu_link" href="index.php?option=com_surveyforce&task=copy_all"><?php echo JText::_('COM_SF_COPY_ALL_LISTS'); ?></a>
						</td>
					</tr>
					<tr>
						<td width="16px"><img src="<?php echo $mosConfig_live_site;?>/administrator/components/com_surveyforce/images/generate.png" ></td>
						<td class="title">
				<a class="menu_link" href="index.php?option=com_surveyforce&task=generate_invitations"><?php echo JText::_('COM_SF_GENERATE_INVITATIONS'); ?></a>
						</td>
					</tr>									
				</table>
				</div>
			</div>
			<div class="panel">
				<h3 class="jpane-toggler title" id="cpanel-panel">
				<span><?php echo JText::_('COM_SF_MANAGE_AUTHORS'); ?></span>
				</h3>
				<div class="jpane-slider content">
				<table class="adminlist">
					<tr>					
						<td width="16px"><img src="<?php echo $mosConfig_live_site;?>/administrator/components/com_surveyforce/images/users.png" ></td>
						<td class="title">
							<a class="menu_link" href="index.php?option=com_surveyforce&task=authors"><?php echo JText::_('COM_SF_LIST_OF_AUTHORS'); ?></a>
						</td>
					</tr>
					<tr>
						<td width="16px"><img src="<?php echo $mosConfig_live_site;?>/administrator/components/com_surveyforce/images/newuser.png" ></td>
						<td class="title">
				<a class="menu_link" href="index.php?option=com_surveyforce&task=add_author"><?php echo JText::_('COM_SF_ADD_AUTHOR'); ?></a>
						</td>
					</tr>									
				</table>
				</div>
			</div>
			<div class="panel">
				<h3 class="jpane-toggler title" id="cpanel-panel">
				<span><?php echo JText::_('COM_SF_MANAGE_EMAILS'); ?></span>
				</h3>
				<div class="jpane-slider content">
				<table class="adminlist">
					<tr>					
						<td width="16px"><img src="<?php echo $mosConfig_live_site;?>/administrator/components/com_surveyforce/images/content.png" ></td>
						<td class="title">
							<a class="menu_link" href="index.php?option=com_surveyforce&task=emails"><?php echo JText::_('COM_SF_MANAGE_EMAILS'); ?></a>
						</td>
					</tr>
					<tr>
						<td width="16px"><img src="<?php echo $mosConfig_live_site;?>/administrator/components/com_surveyforce/images/new.png" ></td>
						<td class="title">
							<a class="menu_link" href="index.php?option=com_surveyforce&task=add_email"><?php echo JText::_('COM_SF_NEW_EMAIL'); ?></a>
						</td>
					</tr>									
				</table>
				</div>
			</div>
			<div class="panel">
				<h3 class="jpane-toggler title" id="cpanel-panel">
				<span><?php echo JText::_('COM_SF_REPORTS'); ?></span>
				</h3>
				<div class="jpane-slider content">
				<table class="adminlist">
					<tr>					
						<td width="16px"><img src="<?php echo $mosConfig_live_site;?>/administrator/components/com_surveyforce/images/report.png" ></td>
						<td class="title">
							<a class="menu_link" href="index.php?option=com_surveyforce&task=reports"><?php echo JText::_('COM_SF_REPORTS'); ?></a>
						</td>
					</tr>
					<tr>
						<td width="16px"><img src="<?php echo $mosConfig_live_site;?>/administrator/components/com_surveyforce/images/report.png" ></td>
						<td class="title">
							<a class="menu_link" href="index.php?option=com_surveyforce&task=adv_report"><?php echo JText::_('COM_SF_ADVANCED_REPORTS'); ?></a>
						</td>
					</tr>
					
					<!--
					<tr>
						<td width="16px"><img src="<?php echo $mosConfig_live_site;?>/administrator/components/com_surveyforce/images/report.png" ></td>
						<td class="title">
							<a class="menu_link" href="index.php?option=com_surveyforce&task=generateExcel">Generate Excel (EN)</a>
						</td>
					</tr>
					<tr>
						<td width="16px"><img src="<?php echo $mosConfig_live_site;?>/administrator/components/com_surveyforce/images/report.png" ></td>
						<td class="title">
							<a class="menu_link" href="index.php?option=com_surveyforce&task=generateExcelNL">Generate Excel (NL)</a>
						</td>
					</tr> -->
					
					
					<?php 
						foreach ( unserialize( EXCEL_REPORT_TEMPLATES ) as $k => $v ) { ?>
					
					<tr>
						<td width="16px"><img src="<?php echo $mosConfig_live_site;?>/administrator/components/com_surveyforce/images/report.png" ></td>
						<td class="title">
						
							<a class="menu_link" href="index.php?option=com_surveyforce&task=generateExcelSP&template=<?php echo $k; ?>&survey_id=<?php echo $v[1]; ?>">Generate Excel (<?php echo $k; ?>)</a>
						</td>
					</tr>
						<?php }
						
						?>
							
							
				</table>
				</div>
			</div>
			<div class="panel">
				<h3 class="jpane-toggler title" id="cpanel-panel">
				<span><?php echo JText::_('COM_SF_CONFIGURATION'); ?></span>
				</h3>
				<div class="jpane-slider content">
				<table class="adminlist">
					<tr>
						<td width="16px"><img src="<?php echo $mosConfig_live_site;?>/administrator/components/com_surveyforce/images/config.png" ></td>
						<td class="title">
							<a class="menu_link" href="index.php?option=com_surveyforce&task=config"><?php echo JText::_('COM_SF_CONFIGURATION'); ?></a>
						</td>
					</tr>
					<tr>
						<td width="16px"><img src="<?php echo $mosConfig_live_site;?>/administrator/components/com_surveyforce/images/templates.png" ></td>
						<td class="title">
							<a class="menu_link" href="index.php?option=com_surveyforce&task=templates"><?php echo JText::_('COM_SF_MANAGE_TEMPLATES'); ?></a>
						</td>
					</tr>
				</table>
				</div>
			</div>
			
			<div class="panel">
				<h3 class="jpane-toggler title" id="cpanel-panel">
					<span><?php echo JText::_('COM_SF_SAMPLE_SURVEYS'); ?></span>
				</h3>
				<div class="jpane-slider content">
				<table class="adminlist">
					<tr>
						<td width="16px"><img src="<?php echo $mosConfig_live_site;?>/administrator/components/com_surveyforce/images/generate.png" ></td>
						<td class="title">
							<a class="menu_link" href="index.php?option=com_surveyforce&task=sample"><?php echo JText::_('COM_SF_SAMPLE_SURVEYS'); ?></a>
						</td>
					</tr>					
				</table>
				</div>
			</div>
					
			<div class="panel">
				<h3 class="jpane-toggler title" id="cpanel-panel">
					<span><?php echo JText::_('COM_SF_S_HELP'); ?></span>
				</h3>
				<div class="jpane-slider content">
				<table class="adminlist">
					<tr>
						<td width="16px"><img src="<?php echo $mosConfig_live_site;?>/administrator/components/com_surveyforce/images/help.png" ></td>
						<td class="title">
							<a class="menu_link" href="index.php?option=com_surveyforce&task=help"><?php echo JText::_('COM_SF_S_HELP'); ?></a>
						</td>
					</tr>		
					<tr>
						<td width="16px"><img src="<?php echo $mosConfig_live_site;?>/administrator/components/com_surveyforce/images/help.png" ></td>
						<td class="title">
							<a class="menu_link" href="index.php?option=com_surveyforce&task=faq"><?php echo JText::_('COM_SF_FAQ'); ?></a>
						</td>
					</tr>
					<tr>
						<td width="16px"><img src="<?php echo $mosConfig_live_site;?>/administrator/components/com_surveyforce/images/help.png" ></td>
						<td class="title">
							<a class="menu_link" href="index.php?option=com_surveyforce&task=support"><?php echo JText::_('COM_SF_SUPPORT'); ?></a>
						</td>
					</tr>			
				</table>
				</div>
			</div>
			
			   <div style="padding:5px;"><a target="_blank" href="http://extensions.joomla.org/extensions/contacts-and-feedback/surveys/11301"><img src="<?php echo $mosConfig_live_site;?>/administrator/components/com_surveyforce/images/rate_us.png" title="<?php echo JText::_('COM_SF_RATE_US'); ?>" alt="<?php echo JText::_('COM_SF_RATE_US_AT_EXTENSIONS'); ?>"/></a>
			  <br />
			  <br />
	  
			  &laquo; <?php echo JText::_('COM_SF_I_AM_OF_THE_OPINION'); ?><a class="modal" href="http://www.joomplace.com/testimonials-31.html" rel="{handler: 'iframe', size: {x: 800, y: 600}}"><strong><?php echo JText::_('COM_SF_CONTINUE'); ?></strong></a>&raquo;
			   </div>


			<div class="clr"></div>
		</div>
	</td>
	</tr>
	<tr><td style="height:8px; width:200px;background:url(<?php echo $mosConfig_live_site;?>/administrator/components/com_surveyforce/images/bottom_menu_bg.jpg) no-repeat top left ">
		<br /><img src="<?php echo $mosConfig_live_site;?>/components/com_surveyforce/images/blank.png" />
	</td></tr>
</table>
</div>
			</td>
			<td valign="top">
	<?php
}

function EF_menu_footer(){
?>
</td></tr></table>
<?php
}

function ep_news(){
			?>
			<script type="text/javascript" language="javascript"><!--//--><![CDATA[//><!--
				ep_CheckNews();
			//--><!]]></script>
			<?php
	
	}

function ep_update_checker(){
	?>
	<table cellpadding="0" cellspacing="0" border="0" width="100%" class="adminheading">
		<tr>
			<td><div id="ep_LatestVersion"><a href="check_now" onclick="return ep_CheckVersion();" style="cursor: pointer; text-decoration:underline;">&nbsp;<?php echo JText::_('COM_SF_CHECK_NOW'); ?></a></div>
			</td>
		</tr>
    </table>

	<script type="text/javascript" language="javascript"><!--//--><![CDATA[//><!--

	function makeRequest(url) {

		var http_request = false;
	
		if (window.XMLHttpRequest) { // Mozilla, Safari,...
			http_request = new XMLHttpRequest();
			if (http_request.overrideMimeType) {
				http_request.overrideMimeType('text/xml');
				// See note below about this line
			}
		} else if (window.ActiveXObject) { // IE
			try {
				http_request = new ActiveXObject("Msxml2.XMLHTTP");
			} catch (e) {
				try {
					http_request = new ActiveXObject("Microsoft.XMLHTTP");
				} catch (e) {}
			}
		}
	
		if (!http_request) {			
			return false;
		}
		
		if (url.indexOf('latestNews') == -1){
			http_request.onreadystatechange = function() { alertContents(http_request); }
		} else{
			http_request.onreadystatechange = function() { alertContentsNews(http_request); }
		}
		
		http_request.open('GET', url, true);
		http_request.send(null);
	}

	function alertContentsNews(http_request) {

        if (http_request.readyState == 4) {
            if ((http_request.status == 200) && (http_request.responseText.length < 1025)) {
				document.getElementById('ep_LatestNews').innerHTML = '&nbsp;'+http_request.responseText;
            } else {
                document.getElementById('ep_LatestNews').innerHTML = "<?php echo JText::_('COM_SF_THERE_WAS_PROBLEM_REQUEST'); ?>";
            }
        }

    }

    function alertContents(http_request) {

        if (http_request.readyState == 4) {
            if ((http_request.status == 200) && (http_request.responseText.length < 1025)) {
				document.getElementById('ep_LatestVersion').innerHTML = '&nbsp;'+http_request.responseText;
            } else {
                document.getElementById('ep_LatestVersion').innerHTML = "<?php echo JText::_('COM_SF_THERE_WAS_PROBLEM_REQUEST'); ?>";
            }
        }

    }

	function ep_CheckNews(){
		document.getElementById('ep_LatestNews').innerHTML = "<?php echo JText::_('COM_SF_CHECKING_LATEST_NEWS'); ?>";
    	makeRequest('<?php 
    		echo "index.php?option=com_surveyforce&task=latestNews&no_html=1";
    		?>');
    	return false;
	}

    function ep_CheckVersion() {
    	document.getElementById('ep_LatestVersion').innerHTML = "<?php echo JText::_('COM_SF_CHECKING_LATEST_VERSION'); ?>";
    	makeRequest('<?php 
    		echo "index.php?option=com_surveyforce&task=latestVersion&no_html=1";
    		?>');
    	return false;
    }
    function ep_InitAjax() {
    	makeRequest('<?php 
    		echo "index.php?option=com_surveyforce&task=latestVersion&no_html=1";
    		?>');
    }

//--><!]]></script>
<?php
	}

?>