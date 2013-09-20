<?php
/**
* Survey Force component for Joomla
* @version $Id: edit.surveyforce.html.php 2009-11-16 17:30:15
* @package Survey Force
* @subpackage edit.surveyforce.html.php
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

function txt2overlib($string){
	$string = str_replace(array("\r\n", "\r", "\n"), "<br />", $string);
	$string = str_replace('\"','"',$string);
	$string = str_replace('"','\"',$string);
	$string = str_replace("'","&#039;",$string);
	$string = str_replace("'","&#39;",$string);
	$string = str_replace('&quot;','\"',$string);
	return $string;
}

function SF_showHeadPicture( $pic_type ) {
	global $sf_lang;
	$comp_folder = 'com_surveyforce';
	$html_output = '';	
	
	switch ($pic_type) {
		case 'categories':
			$html_output = '<img class="SF_png" src="components/'.$comp_folder.'/images/headers/head_courses.png" width="48" height="48" border="0" title="'.$sf_lang['SF_CATEGORIES'].'" alt="'.$sf_lang['SF_CATEGORIES'].'" />';
		break;
		case 'surveys':
			$html_output = '<img class="SF_png" src="components/'.$comp_folder.'/images/headers/head_quiz.png" width="48" height="48" border="0" title="'.$sf_lang['SF_SURVEYS'].'" alt="'.$sf_lang['SF_SURVEYS'].'" />';
		break;
		case 'usergroup':
			$html_output = '<img class="SF_png" src="components/'.$comp_folder.'/images/headers/head_usergroup.png" width="48" height="48" border="0" title="'.$sf_lang['SF_USERGROUPS'].'" alt="'.$sf_lang['SF_USERGROUPS'].'" />';
		break;
		case 'report':			
				$html_output = '<img class="SF_png" src="components/'.$comp_folder.'/images/headers/head_certificate.png" width="48" height="48" border="0" title="'.$sf_lang["SF_REPORTS"].'" alt="'.$sf_lang["SF_REPORTS"].'" />';			
		break;
	}
	return $html_output;
}

function SF_showTopMenu() {
	global $sf_lang, $Itemid_s, $option, $mosConfig_live_site;
	JHtml::_('behavior.modal', 'a.modal'); 
	$sf_config = new mos_Survey_Force_Config( );
	?>
	<script language="javascript" type="text/javascript">
		function TRIM_str(sStr) {
			return (sStr.replace(/^[\s\xA0]+/, "").replace(/[\s\xA0]+$/, ""));
		}
	</script>
	<table cellpadding="0" cellspacing="0" border="0" align="right">
		<tr><td nowrap style="white-space:nowrap; text-align:right;" align="right">
		<?php if (!$sf_config->get('sf_enable_jomsocial_integration')) { ?>
		<a href="<?php echo SFRoute("index.php?option=$option{$Itemid_s}&task=categories")?>" title=""><img class='SF_png' src="<?php echo $mosConfig_live_site;?>/components/com_surveyforce/images/toolbar/tlb_courses.png" border="0" width="16" height="16" alt="" title="" style="vertical-align:middle"/>&nbsp;<?php echo $sf_lang['SF_CATEGORIES']?></a>
		<img src="components/com_surveyforce/images/spacer.png" border="0" width="2" height="16" style="background-color:#666666;  vertical-align:middle" alt="spacer" />
		<?php } ?>
		<a href="<?php echo SFRoute("index.php?option=$option{$Itemid_s}&task=surveys")?>" title=""><img class='SF_png' src="<?php echo $mosConfig_live_site;?>/components/com_surveyforce/images/toolbar/tlb_quiz.png" border="0" width="16" height="16" alt="" title="" style="vertical-align:middle" />&nbsp;<?php echo $sf_lang['SF_SURVEYS']?></a>
		<?php if (!$sf_config->get('sf_enable_jomsocial_integration')) { ?>
		<img src="components/com_surveyforce/images/spacer.png" border="0" width="2" height="16" style="background-color:#666666;  vertical-align:middle"  alt="spacer" />
		<a href="<?php echo SFRoute("index.php?option=$option{$Itemid_s}&task=usergroups")?>" title=""><img class='SF_png' src="<?php echo $mosConfig_live_site;?>/components/com_surveyforce/images/toolbar/tlb_users.png" border="0" width="16" height="16" alt="" title="" style="vertical-align:middle" />&nbsp;<?php echo $sf_lang['SF_USERGROUPS']?></a>		
		<?php } ?>
		<img src="components/com_surveyforce/images/spacer.png" border="0" width="2" height="16" style="background-color:#666666;  vertical-align:middle"  alt="spacer" />
		<a href="<?php echo SFRoute("index.php?option=$option{$Itemid_s}&task=reports")?>" title=""><img class='SF_png' src="<?php echo $mosConfig_live_site;?>/components/com_surveyforce/images/toolbar/tlb_docs.png" border="0" width="16" height="16" alt="" title="" style="vertical-align:middle" />&nbsp;<?php echo $sf_lang['SF_REPORTS']?></a>
		<?php if ($sf_config->get('sf_enable_jomsocial_integration')) { ?>
		<img src="components/com_surveyforce/images/spacer.png" border="0" width="2" height="16" style="background-color:#666666;  vertical-align:middle"  alt="spacer" />
		<a href="<?php echo SFRoute("index.php?option=$option{$Itemid_s}&task=help&tmpl=component");?>" class="modal" rel="{handler: 'iframe', size: {x:800, y:600}}" title=""><img class='SF_png' src="<?php echo $mosConfig_live_site;?>/components/com_surveyforce/images/toolbar/tlb_help.png" border="0" width="16" height="16" alt="" title="" style="vertical-align:middle" />&nbsp;<?php echo $sf_lang['SF_HELP']?></a>
		<?php } ?>
		<br />
		</td></tr>
	</table>
	<?php	
}

function ShowToolbar($toolbar) {
	global $Itemid_s, $option, $sf_lang, $Itemid, $mosConfig_live_site;
	$toolbar_thml = "<table align='center' cellpadding='2' cellspacing='2' border='0' class='jq_fe_toolbar'><tr>";
	foreach ($toolbar as $toolbar_btn) {
		$toolbar_thml .= "<td>";
		$btn_w = "22";$btn_h = "22";
		switch ($toolbar_btn['btn_type']) {
			case 'move':
				$btn_img = 'btn_move.png';
				$btn_str = isset($toolbar_btn['btn_str']) ? $toolbar_btn['btn_str'] : $sf_lang['SF_MOVE'];
			break;
			case 'copy':
				$btn_img = 'btn_copy.png';
				$btn_str = isset($toolbar_btn['btn_str']) ? $toolbar_btn['btn_str'] : $sf_lang['SF_COPY'];
			break;
			case 'save':
				$btn_img = 'btn_save.png';
				$btn_str = isset($toolbar_btn['btn_str']) ? $toolbar_btn['btn_str'] : $sf_lang['SF_SAVE'];
			break;
			case 'apply':
				$btn_img = 'btn_apply.png';
				$btn_str = isset($toolbar_btn['btn_str']) ? $toolbar_btn['btn_str'] : $sf_lang['SF_APPLY'];
			break;
			case 'back':
				$btn_img = 'btn_back.png';
				$btn_str = isset($toolbar_btn['btn_str']) ? $toolbar_btn['btn_str'] : $sf_lang['SF_BACK'];
			break;
			case 'cancel':
				$btn_img = 'btn_cancel.png';
				$btn_str = isset($toolbar_btn['btn_str']) ? $toolbar_btn['btn_str'] : $sf_lang['SF_CANCEL'];
			break;
			case 'del':
				$btn_img = 'btn_delete.png';
				$btn_str = isset($toolbar_btn['btn_str']) ? $toolbar_btn['btn_str'] : $sf_lang['SF_DELETE'];
			break;
			case 'edit':
				$btn_img = 'btn_edit.png';
				$btn_str = isset($toolbar_btn['btn_str']) ? $toolbar_btn['btn_str'] : $sf_lang['SF_EDIT'];
			break;
			case 'publish':
				$btn_img = 'btn_accept2.png';
				$btn_str = isset($toolbar_btn['btn_str']) ? $toolbar_btn['btn_str'] : $sf_lang['SF_PUBLISH'];
			break;
			case 'unpublish':
				$btn_img = 'btn_cancel2.png';
				$btn_str = isset($toolbar_btn['btn_str']) ? $toolbar_btn['btn_str'] : $sf_lang['SF_UNPUBLISH'];
			break;
			case 'new_s':
				$btn_img = 'btn_new_s.png';
				$btn_str = isset($toolbar_btn['btn_str']) ? $toolbar_btn['btn_str'] : $sf_lang['SF_NEW'];
			break;
			case 'report':
				$btn_img = "btn_reports.png";
				$btn_str = isset($toolbar_btn['btn_str']) ? $toolbar_btn['btn_str'] : $sf_lang['SF_REPORT'];
			break;
			case 'preview':
				$btn_img = "btn_preview.png";
				$btn_str = isset($toolbar_btn['btn_str']) ? $toolbar_btn['btn_str'] : $sf_lang['SF_PREVIEW'];
			break;
			
			case 'invite':
				$btn_img = "btn_send.png";
				$btn_str = isset($toolbar_btn['btn_str']) ? $toolbar_btn['btn_str'] : $sf_lang['SF_INVITE'];
			break;			
			case 'remaind':
				$btn_img = "btn_remaind.png";
				$btn_str = isset($toolbar_btn['btn_str']) ? $toolbar_btn['btn_str'] : $sf_lang['SF_REMAIND'];
			break;
			case 'email':
				$btn_img = "btn_letter.png";
				$btn_str = isset($toolbar_btn['btn_str']) ? $toolbar_btn['btn_str'] : $sf_lang['SF_CR_EMAIL'];
			break;
			case 'demo':
				$btn_img = "btn_demo.png";
				$btn_str = isset($toolbar_btn['btn_str']) ? $toolbar_btn['btn_str'] : '';
			break;			
			case 'results':
				$btn_img = "btn_results.png";
				$btn_str = isset($toolbar_btn['btn_str']) ? $toolbar_btn['btn_str'] : '';
			break;
			
			case 'new':
			default:
				$btn_img = 'btn_new.png';
				$btn_str = isset($toolbar_btn['btn_str']) ? $toolbar_btn['btn_str'] : $sf_lang['SF_NEW'];
			break;
		
		}
		if ($toolbar_btn['btn_type'] != 'spacer') {
			$toolbar_thml .= "<a ".(isset($toolbar_btn['btn_class']) ? ' class="'.$toolbar_btn['btn_class'].'"' : '').(isset($toolbar_btn['btn_class']) && $toolbar_btn['btn_class']=='modal'?' rel="{handler: \'iframe\', size: {x:800, y:600}}"': '')." href=\"".$toolbar_btn['btn_js']."\" title=\"".$btn_str."\" onMouseOver=\"SF_ShowTBToolTip_quiz('".$btn_str."');return true;\" onMouseOut=\"SF_ShowTBToolTip_quiz('');return true;\">";
			$toolbar_thml .= "<img class='SF_png' src='{$mosConfig_live_site}/components/com_surveyforce/images/buttons/".$btn_img."' width='".$btn_w."' height='".$btn_h."' border='0' alt=\"".$btn_str."\" title=\"".$btn_str."\" />";
			$toolbar_thml .= "</a>";
		}
		else {
			$toolbar_thml .= "<img class='SF_png' src='{$mosConfig_live_site}/components/com_surveyforce/images/buttons/spacer.png' border='0' width='2px' height='22px' style='background-color:#666666 '   />";
		}
		$toolbar_thml .= "</td>";
	}
	$toolbar_thml .= "</tr></table>";
	return $toolbar_thml;
}

class survey_force_front_html {
	
		function SF_uploadImage( $option ) {
		
		$css = mosGetParam($_REQUEST,'t','');
		if (_JOOMLA15) {
		?>
			<link href="administrator/templates/khepri/css/template.css" rel="stylesheet" type="text/css" />
			<link href="administrator/templates/khepri/css/rounded.css" rel="stylesheet" type="text/css" />
		<?php } else {?>
			<link rel="stylesheet" href="../../templates/<?php echo $css; ?>/css/template_css.css" type="text/css" />
		<?php }?>
		<form method="post" action="index.php" enctype="multipart/form-data" name="filename">
		<table class="adminform">
		<tr>
			<th class="title"> 
				File Upload : 
			</th>
		</tr>
		<tr>
			<td align="center">
				<input class="inputbox" name="userfile" type="file" />
			</td>
		</tr>
		<tr>
			<td>
				<input class="button" type="submit" value="Upload" name="fileupload" />
				Max size = <?php echo ini_get( 'post_max_size' );?>
			</td>
		</tr>
		</table>
		
		<input type="hidden" name="directory" value="<?php echo $directory;?>" />
		<input type="hidden" name="t" value="<?php echo $css?>">
		<input type="hidden" name="task" value="uploadimage">
		<input type="hidden" name="option" value="com_surveyforce">
		<input type="hidden" name="no_html" value="1">
		<input type="hidden" name="tmpl" value="component">
		</form><br />
<br />

		<?php
	} 

	
	function SF_showCatsList( &$rows, &$pageNav, $option ) {
		global $Itemid_s, $Itemid, $my, $sf_lang;
		?>
		<script type="text/javascript" src="/media/system/js/core.js"></script>
		<script type="text/javascript" src="components/com_surveyforce/overlib_mini.js"></script>
		<script type="text/javascript" language="javascript">
			function submitbutton(pressbutton) {
				var form = document.adminForm;
				if ( ((pressbutton == 'edit_cat') || (pressbutton == 'del_cat') ) && (form.boxchecked.value == "0")) {
					alert('<?php echo $sf_lang['SF_ALERT_SELECT_ITEM'];?>');
				} else {
					form.task.value = pressbutton;
					form.submit();
				}
			} 
		</script>
		<div class="contentpane surveyforce">
		<form action="<?php echo SFRoute("index.php?option=$option{$Itemid_s}")?>" method="post" name="adminForm" id="adminForm">
		<table width="100%" cellpadding="0" cellspacing="0" border="0"  >
			<tr>
				<td width="auto">
					<table width="100%" cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td width="48px">
								<?php echo SF_showHeadPicture('categories');?>
							</td>
							<td class="contentheading" width="100%" valign="middle" style="vertical-align:middle ">
								&nbsp;<?php echo $sf_lang['SF_CAT_LIST']?>
							</td>
							<td align="right" valign="top" style="vertical-align:top ">
								<?php SF_showTopMenu();?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td align="center" style="text-align:center">
				<span id="SF_toolbar_tooltip">&nbsp;</span><br/>
				<?php $toolbar = array();
					  $toolbar[] = array('btn_type' => 'new', 'btn_js' => "javascript:submitbutton('add_cat');");
					  $toolbar[] = array('btn_type' => 'edit', 'btn_js' => "javascript:submitbutton('edit_cat');"); 
					  if (SF_GetUserType($my->id) == 1) {						  
						  $toolbar[] = array('btn_type' => 'del', 'btn_js' => "javascript:submitbutton('del_cat');");
					  }
					  echo ShowToolbar($toolbar); 
				?>
				</td>
			</tr>
			<tr>
				<td width="100%">
				<table width="100%"><tr><td align="left" style="text-align:left">&nbsp;</td>
				<td align="right" style="text-align:right"><?php
					$link = "index.php?option=$option{$Itemid_s}&amp;task=categories"; 
					echo _PN_DISPLAY_NR . $pageNav->getLimitBox( $link ) . '&nbsp;' ;
					echo $pageNav->writePagesCounter(). '&nbsp;&nbsp;';
				?></td>
				</tr>
				</table>
				</td>
			</tr>
		</table>
		<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr >
			<td width="20px" class="sectiontableheader" >#</td>
			<td width="20px" class="sectiontableheader" ><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($rows); ?>);" /></td>
			<td align="left" width="60%" class="sectiontableheader" ><?php echo $sf_lang['SF_NAME']?></td>
			<td align="left" width="30%" class="sectiontableheader" ><?php echo $sf_lang['SF_USERNAME']?></td>
		</tr>
		<?php
		$k = 2;
		for ($i=0, $n=count($rows); $i < $n; $i++) {
			$row = $rows[$i];
			$link 	= SFRoute("index.php?option=$option{$Itemid_s}&task=surveys&catid=". $row->id);
			$checked = mosHTML::idBox( $i, $row->id);?>
			<tr class="<?php echo "sectiontableentry$k"; ?>">
				<td><?php echo $pageNav->rowNumber( $i ); ?></td>
				<td><?php echo $checked; ?></td>
				<td align="left">
					<span>
						<?php echo mosToolTip( mysql_escape_string(nl2br($row->sf_catdescr)), $sf_lang['SF_CAT_DESCRIPTION'], 280, 'tooltip.png', $row->sf_catname, $link );?>
					</span>
				</td>
				<td><?php echo $row->name; ?></td>
			</tr>
			<?php
			$k = 3 - $k;
		}?>
		</table>
		<div align="center">
		<?php 
		$link = "index.php?option=$option{$Itemid_s}&amp;task=categories"; 
		echo $pageNav->writePagesLinks($link).'<br/>';
		?>
		</div>
		<input type="hidden" name="option" value="com_surveyforce" />
		<input type="hidden" name="task" value="categories" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="hidemainmenu" value="0">
		<input type="hidden" name="Itemid" value="<?php echo $Itemid?>" />
		</form><br />
<br />

		</div>
		<?php
	}
	
	function SF_editCategory( &$row, &$lists, $option ) {
		global $Itemid_s, $Itemid, $my, $sf_lang, $mosConfig_live_site;

		?>
		<script language="javascript" type="text/javascript">
		<!--
		function submitbutton(pressbutton) {
			var form = document.adminForm;

			if (pressbutton == 'cancel_cat') {
				form.task.value = pressbutton;
				form.submit();
				return;
			}
			// do field validation
			if (TRIM_str(form.sf_catname.value) == ""){
				alert( "<?php echo $sf_lang['SF_ALERT_ENTER_CAT_NAME'] ?>" );
			} else {
				form.task.value = pressbutton;
				form.submit();
			}
		}
		//-->
		</script>
		<div class="contentpane surveyforce">
		<form action="<?php echo SFRoute("index.php?option=$option{$Itemid_s}")?>" method="post" name="adminForm" id="adminForm">
		<table width="100%" cellpadding="0" cellspacing="0" border="0"  >
			<tr>
				<td width="auto">
					<table width="100%" cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td width="48px">
								<?php echo SF_showHeadPicture('categories');?>
							</td>
							<td class="contentheading" width="100%" valign="middle" style="vertical-align:middle ">
								&nbsp;<?php echo $row->id ? $sf_lang['SF_EDIT_CAT'] : $sf_lang['SF_NEW_CAT']; ?>
							</td>
							<td align="right" valign="top" style="vertical-align:top ">
								<?php SF_showTopMenu();?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td align="center" style="text-align:center">
				<span id="SF_toolbar_tooltip">&nbsp;</span><br/>
				<?php $toolbar = array();
					  $toolbar[] = array('btn_type' => 'save', 'btn_js' => "javascript:submitbutton('save_cat');");
					  $toolbar[] = array('btn_type' => 'apply', 'btn_js' => "javascript:submitbutton('apply_cat');"); 
					  $toolbar[] = array('btn_type' => 'back', 'btn_js' => "javascript:submitbutton('cancel_cat');", 'btn_str' => $sf_lang['SF_CANCEL']);
					  echo ShowToolbar($toolbar); 
				?>
				</td>
			</tr>
			<tr>
				<td width="100%">
					<table width="100%"><tr><td align="left">&nbsp;</td>
					<td align="right">&nbsp;</td>
					</tr>
					</table>
				</td>
			</tr>
		</table>
		
		<table width="100%" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td colspan="2" class="sectiontableheader"><?php echo $sf_lang['SF_CAT_DETAILS']?></td>
			</tr>
			<tr>
				<td align="left" width="15%"><?php echo $sf_lang['SF_NAME']?>:</td>
				<td><input class="inputbox" type="text" name="sf_catname" size="30" maxlength="100" value="<?php echo $row->sf_catname; ?>" /></td>
			</tr>
			<tr>
				<td align="left" width="15%" valign="top"><?php echo $sf_lang['SF_DESCRIPTION']?>:</td>
				<td><textarea class="text_area" name="sf_catdescr" cols="60" rows="5"><?php echo $row->sf_catdescr; ?></textarea></td>
			</tr>
		</table>
		<br />
		<input type="hidden" name="option" value="com_surveyforce" />
		<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
		<input type="hidden" name="user_id" value="<?php echo $row->user_id; ?>" />
		<input type="hidden" name="task" value="" />		
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="hidemainmenu" value="0">
		<input type="hidden" name="Itemid" value="<?php echo $Itemid?>" />
		</form><br />
<br />

		</div>
		<?php
	}
	
	function SF_showSurvsList( &$rows, &$lists, &$pageNav, $option, $is_i = false ) {
		global $Itemid_s, $Itemid, $my, $sf_lang, $task;
		
		$sf_config = new mos_Survey_Force_Config( );
		?>
		<script type="text/javascript" src="/media/system/js/core.js"></script>
		<script type="text/javascript" src="components/com_surveyforce/overlib_mini.js"></script>
		
		<script type="text/javascript" language="javascript">
			function submitbutton(pressbutton) {
				var form = document.adminForm;
				if ( ((pressbutton == 'preview_survey') || (pressbutton == 'show_results') || (pressbutton == 'view_rep_surv') || (pressbutton == 'edit_surv') || (pressbutton == 'del_surv') || (pressbutton == 'copy_surv_sel') || (pressbutton == 'move_surv_sel') || (pressbutton == 'unpublish_surv') || (pressbutton == 'publish_surv') ) && (form.boxchecked.value == "0")) {
					alert('<?php echo $sf_lang['SF_ALERT_SELECT_ITEM'];?>');
				} else {
					if (pressbutton == 'del_surv') {
						if(confirm("<?php echo $sf_lang["SF_ARE_SURE_TO_DELETE"];?>")) {
							form.task.value = pressbutton;
							form.submit();
							form.target = "";
							form.task.value = 'surveys';
							return;
						}
						return;
					}
					
					if (pressbutton == 'preview_survey') {
						form.target = "_blank";	
					}
					form.task.value = pressbutton;
					form.submit();
					form.target = "";
					form.task.value = 'surveys';
				}
			} 
		</script>
		<div class="contentpane surveyforce">
		<form action="<?php echo SFRoute("index.php?option=$option{$Itemid_s}")?>" method="post" name="adminForm" id="adminForm">
		<table width="100%" cellpadding="0" cellspacing="0" border="0"  >
			<tr>
				<td width="auto">
					<table width="100%" cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td width="48px">
								<?php echo SF_showHeadPicture('surveys');?>
							</td>
							<td class="contentheading" width="100%" valign="middle" style="vertical-align:middle ">
								&nbsp;<?php echo $sf_lang['SF_SURV_LIST']?>
							</td>
							<td align="right" valign="top" style="vertical-align:top ">
								<?php SF_showTopMenu();?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td align="center" style="text-align:center">
				<span id="SF_toolbar_tooltip">&nbsp;</span><br/>
				<?php $toolbar = array();
					  if ($task == 'surveys') {
					 	  $toolbar[] = array('btn_type' => 'preview', 'btn_js' => "javascript:submitbutton('preview_survey');", 'btn_str' => $sf_lang['SF_PREVIEW']);
					  	  $toolbar[] = array('btn_type' => 'results', 'btn_js' => "javascript:submitbutton('show_results');", 'btn_str' => $sf_lang['SF_VIEW_SURVEY_RESULTS']);
						  $toolbar[] = array('btn_type' => 'publish', 'btn_js' => "javascript:submitbutton('publish_surv');");
						  $toolbar[] = array('btn_type' => 'unpublish', 'btn_js' => "javascript:submitbutton('unpublish_surv');"); 
						  if (!$sf_config->get('sf_enable_jomsocial_integration')) { 
							 $toolbar[] = array('btn_type' => 'move', 'btn_js' => "javascript:submitbutton('move_surv_sel');");
						 	 $toolbar[] = array('btn_type' => 'copy', 'btn_js' => "javascript:submitbutton('copy_surv_sel');");
						  }
						  $toolbar[] = array('btn_type' => 'del', 'btn_js' => "javascript:submitbutton('del_surv');"); 
						  $toolbar[] = array('btn_type' => 'edit', 'btn_js' => "javascript:submitbutton('edit_surv');");
						  $toolbar[] = array('btn_type' => 'new', 'btn_js' => "javascript:submitbutton('add_surv');"); 
						  if (!$sf_config->get('sf_enable_jomsocial_integration')) { 
							  $toolbar[] = array('btn_type' => 'back', 'btn_js' => "javascript:submitbutton('categories');", 'btn_str' => $sf_lang['SF_CATEGORIES']);
						  }
					  }
					  elseif ($task == 'rep_surv') {
					  	  $toolbar[] = array('btn_type' => 'report', 'btn_js' => "javascript:submitbutton('view_rep_surv');");
						  $toolbar[] = array('btn_type' => 'back', 'btn_js' => "javascript:submitbutton('reports');"); 
					  }
					  echo ShowToolbar($toolbar); 
				?>
				</td>
			</tr>
			<tr>
				<td width="100%">
				<table width="100%"><tr><td align="left" style="text-align:left">&nbsp;
				<?php if (!$sf_config->get('sf_enable_jomsocial_integration')) { 
						echo $sf_lang['SF_FILTER'];?>:<?php echo $lists['category'];
				}?>
				</td>
				<td align="right" style="text-align:right"><?php
					$link = "index.php?option=$option{$Itemid_s}&amp;task=surveys"; 
					echo _PN_DISPLAY_NR . $pageNav->getLimitBox( $link ) . '&nbsp;' ;
					echo $pageNav->writePagesCounter(). '&nbsp;&nbsp;';
				?></td>
				</tr>
				</table>
				</td>
			</tr>
		</table>

		<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr >
			<td width="20px" class="sectiontableheader" >id</td>
			<td width="20" class="sectiontableheader"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($rows); ?>);" /></td>
			<td width="40%" class="sectiontableheader"><?php echo $sf_lang['SF_NAME']?></td>
			<td class="sectiontableheader"><?php echo $sf_lang['SF_ACTIVE']?></td>
			<?php if (!$sf_config->get('sf_enable_jomsocial_integration')) { ?>
				<td class="sectiontableheader"><?php echo $sf_lang['SF_CATEGORY']?></td>
				<td class="sectiontableheader"><?php echo $sf_lang['SF_AUTHOR']?></td>
			<?php }?>
			<td class="sectiontableheader"><?php echo $sf_lang['SF_PUBLIC']?></td>
			<?php if (!$sf_config->get('sf_enable_jomsocial_integration')) { ?>
				<td class="sectiontableheader"><?php echo $sf_lang['SF_AUTO_PB']?></td>			
				<td class="sectiontableheader"><?php echo $sf_lang['SF_FOR_INVITED']?></td>
			<?php }?>
			<td class="sectiontableheader"><?php echo $sf_lang['SF_FOR_REG']?></td>
			<?php if ($sf_config->get('sf_enable_jomsocial_integration')) { ?>
				<td class="sectiontableheader"><?php echo $sf_lang['SF_FOR_FRIENDS']?></td>
			<?php }?>
			<?php if ($lists['userlists']) { ?>
				<td class="sectiontableheader"><?php echo $sf_lang["SF_FOR_USER_IN_LISTS"]?></td>
			<?php }?>
			<td class="sectiontableheader"><?php echo $sf_lang['SF_EXPIRED_ON']?>:</td>
		</tr>
		<?php
		$k = 1;
		for ($i=0, $n=count($rows); $i < $n; $i++) {
			$row = $rows[$i];			
			if ($task == 'surveys') {
				$link2 	= SFRoute("index.php?option=$option{$Itemid_s}&task=edit_surv&cid[]=". $row->id);
				$link 	= SFRoute("index.php?option=$option{$Itemid_s}&task=questions&surv_id=". $row->id);
			} elseif ($task == 'rep_surv') {
				$link2 	= SFRoute("index.php?option=$option{$Itemid_s}&task=view_rep_survA&id=". $row->id);
				$link 	= '#';
			}
			$img_published	= $row->published ? 'btn_accept.png' : 'btn_cancel.png';
			$task_published	= $row->published ? 'unpublish_surv' : 'publish_surv';
			$alt_published 	= $row->published ? $sf_lang['SF_PUBLISHED']  : $sf_lang['SF_UNPUBLISHED'] ;
			$img_public		= $row->sf_public ? 'btn_accept.png' : 'btn_cancel.png';
			$img_invite		= $row->sf_invite ? 'btn_accept.png' : 'btn_cancel.png';
			$img_reg		= $row->sf_reg ? 'btn_accept.png' : 'btn_cancel.png';
			$img_friend		= $row->sf_friend ? 'btn_accept.png' : 'btn_cancel.png';			
			$img_spec		= $row->sf_special ? 'btn_accept.png' : 'btn_cancel.png';
			$img_auto_pb 	= $row->sf_auto_pb  ? 'btn_accept.png' : 'btn_cancel.png';
			$checked = mosHTML::idBox( $i, $row->id);
			?>
			<tr class="<?php echo "sectiontableentry$k"; ?>">
				<td><?php echo $row->id;?></td>
				<td><?php echo $checked; ?></td>
				<td align="left">
					<span>
					<script language="javascript" type="text/javascript">
						var des<?php echo $row->id;?> = '<?php echo str_replace("'","&#039;", str_replace("\r",'', str_replace("\n",'', nl2br($row->sf_descr))))?>';
					</script>
					<a href="<?php echo $link2?>" onmouseover="return overlib(des<?php echo $row->id;?>, CAPTION, '<?php echo $sf_lang['SF_SURV_DESCRIPTION']?>', BELOW, RIGHT, WIDTH, '280');" onmouseout="return nd();" ><?php echo $row->sf_name ?></a>&nbsp;&nbsp;<a href="<?php echo $link?>">[<?php echo $sf_lang["SF_VIEW_QUESTIONS"];?>]</a>
					</span>
				</td>
				<td align="left">
					<a href="javascript: void(0);" onClick="return listItemTask('cb<?php echo $i;?>','<?php echo $task_published;?>')">
						<img src="components/com_surveyforce/images/toolbar/<?php echo $img_published;?>" width="16" height="16" border="0" alt="<?php echo $alt_published; ?>" />
					</a>
				</td>
				<?php if (!$sf_config->get('sf_enable_jomsocial_integration')) { ?>
				<td align="left">
					<?php echo $row->sf_catname; ?>
				</td>
				<td align="left">
					<?php echo $row->username; ?>
				</td>
				<?php } ?>
				<td align="left">
						<img src="components/com_surveyforce/images/toolbar/<?php echo $img_public;?>" width="16" height="16" border="0" alt="<?php echo $alt_published; ?>" />
				</td>
				<?php if (!$sf_config->get('sf_enable_jomsocial_integration')) { ?>
				<td align="left">
						<img src="components/com_surveyforce/images/toolbar/<?php echo $img_auto_pb;?>" width="16" height="16" border="0" alt="<?php echo $alt_published; ?>" />
				</td>
				<td align="left">
						<img src="components/com_surveyforce/images/toolbar/<?php echo $img_invite;?>" width="16" height="16" border="0" alt="<?php echo $alt_published; ?>" />
				</td>
				<?php } ?>
				<td align="left">
						<img src="components/com_surveyforce/images/toolbar/<?php echo $img_reg;?>" width="16" height="16" border="0" alt="<?php echo $alt_published; ?>" />
				</td>		
				<?php if ($sf_config->get('sf_enable_jomsocial_integration')) { ?>
				<td align="left">
						<img src="components/com_surveyforce/images/toolbar/<?php echo $img_friend;?>" width="16" height="16" border="0" alt="<?php echo $alt_published; ?>" />
				</td>
				<?php } ?>
				<?php if ($lists['userlists']) { ?>
				<td align="left">
						<img src="components/com_surveyforce/images/toolbar/<?php echo $img_spec;?>" width="16" height="16" border="0" alt="<?php echo $alt_published; ?>" />
				</td>
				<?php } ?>
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
			$k = 3 - $k;
		}
		?>
		</table>
		<div align="center">
		<?php 
		$link = "index.php?option=$option{$Itemid_s}&amp;task=surveys"; 
		echo $pageNav->writePagesLinks($link).'<br/>';
		?>
		</div>
		<input type="hidden" name="option" value="com_surveyforce" />
		<input type="hidden" name="task" value="<?php echo $task?>" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="hidemainmenu" value="0">
		<input type="hidden" name="Itemid" value="<?php echo $Itemid?>" />
		</form><br />
<br />

		</div>
		<?php
	}
	
	function SF_editSurvey( &$row, &$lists, $option ) {
		global $Itemid, $Itemid_s, $my, $sf_lang,$mosConfig_live_site, $database;
		if ($row->id && SF_GetUserType($my->id, $row->id) != 1)
			mosRedirect( SFRoute("index.php?option=$option&task=surveys{$Itemid_s}"));
		$query = "SELECT id FROM #__components WHERE link LIKE '%sf_score%' ";
		$database->setQuery( $query );
		$is_surveyforce_score = false;
		if ($database->LoadResult()) 
			$is_surveyforce_score = true;
		
		$sf_config = new mos_Survey_Force_Config( );
	
		mosCommonHTML::loadCalendar();?>
		<script type="text/javascript" src="/media/system/js/core.js"></script>
		<script type="text/javascript" src="components/com_surveyforce/overlib_mini.js"></script>
		<script language="javascript" type="text/javascript">
		<!--
		function submitbutton(pressbutton) {
			var form = document.adminForm;

			if (pressbutton == 'cancel_surv') {
				form.task.value = pressbutton;
				form.submit();
				return;
			}
			// do field validation
			if (form.sf_name.value == ""){
				alert( "Survey must have a name." );
			} else {
				form.task.value = pressbutton;
				form.submit();
			}
		}
		//-->
		</script>
		<div class="contentpane surveyforce">
		<form action="<?php echo SFRoute("index.php?option=$option{$Itemid_s}")?>" method="post" name="adminForm" id="adminForm">
		<table width="100%" cellpadding="0" cellspacing="0" border="0"  >
			<tr>
				<td width="auto">
					<table width="100%" cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td width="48px">
								<?php echo SF_showHeadPicture('surveys');?>
							</td>
							<td class="contentheading" width="100%" valign="middle" style="vertical-align:middle ">
								&nbsp;<?php echo $row->id ? $sf_lang['SF_EDIT_SURVEY'] : $sf_lang['SF_NEW_SURVEY'];?>
							</td>
							<td align="right" valign="top" style="vertical-align:top ">
								<?php SF_showTopMenu();?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td align="center" style="text-align:center">
				<span id="SF_toolbar_tooltip">&nbsp;</span><br/>
				<?php $toolbar = array();
					  $toolbar[] = array('btn_type' => 'save', 'btn_js' => "javascript:submitbutton('save_surv');");
					  $toolbar[] = array('btn_type' => 'apply', 'btn_js' => "javascript:submitbutton('apply_surv');");
					  $toolbar[] = array('btn_type' => 'cancel', 'btn_js' => "javascript:submitbutton('cancel_surv');");
					  echo ShowToolbar($toolbar); 
				?>
				</td>
			</tr>
			<tr>
				<td width="100%">
				<table width="100%"><tr><td align="left">&nbsp;</td>
				<td align="right">&nbsp;</td>
				</tr>
				</table>
				</td>
			</tr>
		</table>
		<?php 
		jimport('joomla.html.pane');
		$pane	=& JPane::getInstance('Tabs');
		
		echo $pane->startPane( 'survey-edit' );
		echo $pane->startPanel(  $sf_lang['SF_SURVEY_DETAILS'], 'details-page' );
		?>
		<table width="100%" cellpadding="0" cellspacing="2" border="0">
			<tr>
				<td colspan="3" class="sectiontableheader"><?php echo $sf_lang['SF_SURVEY_DETAILS']?></td>
			</tr>
			<tr>
				<td align="left" width="20%"><?php echo $sf_lang['SF_NAME']?>:</td>
				<td colspan="2"><input class="inputbox" type="text" name="sf_name" size="50" maxlength="100" value="<?php echo $row->sf_name; ?>" /></td>
			</tr>
			<tr>
				<td align="left" width="20%" valign="top"><?php echo $sf_lang['SF_DESCRIPTION']?>:</td>
				<td colspan="2"><?php SF_editorArea( 'editor2', $row->sf_descr, 'sf_descr', '100%;', '250', '40', '20' ) ; ?></td>
			</tr>
			<tr>
				<td align="left" width="20%" valign="top"><?php echo $sf_lang['SF_SHORT_DESCRIPTION']?>:</td>
				<td colspan="2"><?php SF_editorArea( 'editor2', $row->surv_short_descr, 'surv_short_descr', '100%;', '250', '40', '20' ) ; ?></td>
			</tr>
			
			<tr>
				<td align="left" width="20%"><?php echo $sf_lang["SF_PUBLISHED"]?>:</td>
				<td  colspan="2"><?php echo $lists['published'] ?></td>
			</tr>
			<tr>
				<td align="left" width="20%"><?php echo $sf_lang['SF_EXPIRED_ON'];?>:</td>
				<td colspan="2"> <?php 
									if ($row->sf_date && $row->sf_date != '0000-00-00 00:00:00')
										$sf_date = mosFormatDate($row->sf_date, "Y-m-d"); 
									else 
										$sf_date = '';
					
									echo JHTML::_('calendar',(($sf_date != '-')?$sf_date:''), 'sf_date','start_date','%Y-%m-%d' , array('size'=>10,'maxlength'=>"10"));

					
			?>
				</td>
			</tr>	
		</table>
		<?php
		echo $pane->endPanel();	
		
		echo $pane->startPanel( $sf_lang["SF_OPTIONS"], 'options-page' );
		?>
		<table width="100%" cellpadding="0" cellspacing="2" border="0">
			<tr>
				<td align="left" width="35%"><?php echo $sf_lang['SF_ENABLE_DESCR']; ?>:</td>
				<td  colspan="2"><?php echo $lists['sf_enable_descr'] ?></td>
			</tr>
			<tr>
				<td align="left"><?php echo $sf_lang['SF_IMAGE']?>:</td>
				<td colspan="2">
				<?php $directory = 'surveyforce';
				global $mainframe;
				$cur_template = $mainframe->getTemplate();
				?>
				<table cellpadding="0" cellspacing="0" border="0"><tr><td>
				<?php echo $lists['images']?></td><td><a style="cursor:pointer;" onclick="popupWindow('index.php?tmpl=component&option=com_surveyforce&amp;task=uploadimage&amp;directory=<?php echo $directory; ?>&amp;t=<?php echo $cur_template; ?>','win1',250,100,'no');"><img src="components/com_surveyforce/images/filesave.png" border="0" width="16" height="16" alt="Upload" /></a>				
				</td></tr></table>
				</td>
			</tr>
			<tr><td></td>
				<td colspan="2"><img src="<?php echo ($row->sf_image)?('../images/surveyforce/'.$row->sf_image): JURI::root().'components/com_surveyforce/images/blank.png'?>" name="imagelib">
				</td>
			</tr>
			<tr>
				<td align="left" ><?php echo $sf_lang['SF_SHOW_PROGRESS'];?>:</td>
				<td  colspan="2"><?php echo $lists['sf_progressbar'] ?></td>
			</tr>
			<tr>
				<td align="left" ><?php echo $sf_lang['SF_TEMPLATE'];?>:</td>
				<td  colspan="2"><?php echo $lists['sf_templates']; ?></td>
			</tr>	
			<?php if (!$sf_config->get('sf_enable_jomsocial_integration')) { ?>		
			<tr>
				<td align="left"><?php echo $sf_lang['SF_CATEGORY']?>:</td>
				<td colspan="2"><?php echo $lists['sf_categories']; ?></td>
			</tr>
			<?php }?>
			<tr>
				<td align="left" valign="top"><?php echo $sf_lang["SF_RANDOM_ORDER"]?>:</td>
				<td  colspan="2"><?php echo $lists['sf_random']; ?><?php if (!$sf_config->get('sf_enable_jomsocial_integration')) { ?><br/><small><?php echo $sf_lang["SF_RANDOM_WARNING"];?></small><?php } ?></td>
			</tr>
			<?php if (!$sf_config->get('sf_enable_jomsocial_integration')) { ?>
			<tr>
				<td align="left"><?php echo $sf_lang['SF_AUTO_INSERT_PB']?>:</td>
				<td  colspan="2"><?php echo $lists['sf_auto_pb'] ?></td>
			</tr>
			<?php } else { ?>
				<input type="hidden" name="sf_auto_pb" value="0" />
			<?php } ?>
			<tr>
				<td align="left" valign="top"><?php echo $sf_lang['SURVEY_AFTER_START']?>:</td>
				<td  colspan="2" valign="top">
					<label for="sf_after_start0"><input type="radio" <?php echo ($row->sf_after_start == 0 ? 'checked="checked"': '')?> name="sf_after_start" id="sf_after_start0" value="0"/><?php echo $sf_lang['SURVEY_AS_SHOW_MES']?></label><br />
					<label for="sf_after_start1"><input type="radio" <?php echo ($row->sf_after_start == 1 ? 'checked="checked"': '')?> name="sf_after_start" id="sf_after_start1" value="1"/><?php echo $sf_lang['SURVEY_AS_SHOW_RES']?></label>
				</td>
			</tr>
		</table>
		<?php
		echo $pane->endPanel();	
		
		echo $pane->startPanel( $sf_lang["SF_ACCESS"] , 'access-page' );
		?>
		<table width="100%" cellpadding="0" cellspacing="2" border="0">
			<tr>
				<td align="left" width="20%"><?php echo $sf_lang['SF_PUBLIC']?>:</td>
				<td colspan="2">
					<input type="hidden" name="sf_public" value="<?php echo $row->sf_public; ?>">
					<input type="checkbox" name="sf_public_chk" onClick="javascript: this.form['sf_public'].value = (this.checked)?1:0;" <?php echo ($row->sf_public == 1)?"checked":""; ?>>
					&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $sf_lang['SF_VOTING']; ?>:&nbsp;
					<?php echo $lists['sf_pub_voting']; ?>
					&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $sf_lang['SF_CONTROL']; ?>:&nbsp;
					<?php echo $lists['sf_pub_control']; ?>
					&nbsp;&nbsp;<?php 
					$tip = 'Voting option will be enabled only if some control type is selected.<br><strong>Note that none of this control types ensures single voting by an advaced user. But in most cases it works.</strong> ';
					echo mosToolTip( $tip );?>
				</td>
			</tr>
			<?php if (!$sf_config->get('sf_enable_jomsocial_integration')) { ?>
			<tr>
				<td align="left" width="20%"><?php echo $sf_lang['SF_FOR_INVITED']?>:</td>
				<td colspan="2">
					<input type="hidden" name="sf_invite" value="<?php echo $row->sf_invite; ?>">
					<input type="checkbox" name="sf_invite_chk" onClick="javascript: this.form['sf_invite'].value = (this.checked)?1:0;" <?php echo ($row->sf_invite == 1)?"checked":""; ?>>
					&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $sf_lang['SF_VOTING']; ?>:&nbsp;
				<?php echo $lists['sf_inv_voting']; ?>
				</td>
			</tr>
			<?php }?>
			<tr>
				<td align="left" width="20%"><?php echo $sf_lang['SF_FOR_REG_FULL']?>:</td>
				<td colspan="2">
					<input type="hidden" name="sf_reg" value="<?php echo $row->sf_reg; ?>">
					<input type="checkbox" name="sf_reg_chk" onClick="javascript: this.form['sf_reg'].value = (this.checked)?1:0;" <?php echo ($row->sf_reg == 1)?"checked":""; ?>>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $sf_lang['SF_VOTING']; ?>:&nbsp;
				<?php echo $lists['sf_reg_voting']; ?>
				</td>
			</tr>
			
			<?php if ($sf_config->get('sf_enable_jomsocial_integration')) { ?>
			<tr>
				<td align="left" width="20%"><?php echo $sf_lang["SF_FOR_FRIENDS"]?>:</td>
				<td colspan="2">
					<input type="hidden" name="sf_friend" value="<?php echo $row->sf_friend; ?>">
					<input type="checkbox" name="sf_friend_chk" onClick="javascript: this.form['sf_friend'].value = (this.checked)?1:0;" <?php echo ($row->sf_friend == 1)?"checked":""; ?>>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $sf_lang['SF_VOTING']; ?>:&nbsp;
				<?php echo $lists['sf_friend_voting']; ?>
				</td>
			</tr>
			<?php } ?>
			<?php if ($lists['userlists'] != null) {?>
			<tr>
				<td align="left" width="20%" valign="top"><?php echo $sf_lang["SF_FOR_USER_IN_LISTS"];?>:</td>
				<td valign="top" style="vertical-align:top; width:20px" width="20px">
					<input type="hidden" name="sf_special" value="<?php echo $row->sf_special; ?>">
					<input type="checkbox" name="sf_special_chk" onClick="javascript: this.form['sf_special'].value = (this.checked)?1:0;" <?php echo ($row->sf_special)?"checked":""; ?>>
				</td>
				<td style="text-align:left">
					<?php echo $lists['userlists'] ?>
				</td>
			</tr>
			<?php } ?>
		</table>
		<?php
		echo $pane->endPanel();	
		
		echo $pane->startPanel( $sf_lang['SF_FINAL_PAGE'], 'final-page' );
		?>
		<table width="100%" cellpadding="0" cellspacing="2" border="0">
			<tr>
				<td align="left" width="20%"><?php echo $sf_lang['SF_FINAL_PAGE']?>:</td>
				<td valign="top" colspan="2"><input type="radio" <?php echo ($row->sf_fpage_type == 1 ? 'checked="checked"': '')?> name="sf_fpage_type" value="1"/><?php echo $sf_lang['SF_SHOW_RESULTS']?></td>
			</tr>
			
			<?php if ($is_surveyforce_score) {?>
			<tr valign="top">
				<td colspan="1"></td><td valign="top" colspan="2"><input type="radio" <?php echo ($row->sf_fpage_type == 2 ? 'checked="checked"': '')?> name="sf_fpage_type" value="1"/><?php echo $sf_lang['SF_SHOW_SCORE_RESULTS']?></td>
				
			</tr>
			<?php }?>
			<tr valign="top">
				<td></td><td valign="top" colspan="2"><input type="radio"  <?php echo ($row->sf_fpage_type == 0 ? 'checked="checked"': '')?> name="sf_fpage_type" value="0"/><?php echo $sf_lang['SF_SHOW_THIS']?>:</td>
				
			</tr>
			<tr valign="top">
				<td align="left" width="20%"><?php echo $sf_lang['SF_FINAL_PAGE'].' '.$sf_lang["SF_TEXT"]?>:</td><td colspan="2"><?php SF_editorArea( 'editor3', ($row->sf_fpage_text == null ? '<strong>End of the survey � Thank you for your time.</strong>' : $row->sf_fpage_text), 'sf_fpage_text', '100%;', '250', '40', '20' ) ; ?></td>
			</tr>			
		</table>
		<?php 
		echo $pane->endPane();
		?>
		<br />
		<input type="hidden" name="sf_author" value="<?php echo $row->sf_author; ?>" />
		<input type="hidden" name="option" value="com_surveyforce" />
		<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="Itemid" value="<?php echo $Itemid?>" />
		</form><br/><br/>
		</div>
		<?php
	}
	
	function SF_moveSurvey_Select( $option, $cid, $CategoryList, $items ) {
		global $task, $Itemid, $Itemid_s, $my, $sf_lang, $mosConfig_live_site, $database;
		if ($task == 'move_surv_sel' && SF_GetUserType($my->id, $row->id) != 1)
			mosRedirect( SFRoute("index.php?option=$option&task=surveys{$Itemid_s}"));
		?>
		<script type="text/javascript" src="/media/system/js/core.js"></script>
		<script type="text/javascript" src="components/com_surveyforce/overlib_mini.js"></script>
		<div class="contentpane surveyforce">
		<form action="<?php echo SFRoute("index.php?option=$option{$Itemid_s}")?>" method="post" name="adminForm" id="adminForm">
		<table width="100%" cellpadding="0" cellspacing="0" border="0"  >
			<tr>
				<td width="auto">
					<table width="100%" cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td width="48px">
								<?php echo SF_showHeadPicture('surveys');?>
							</td>
							<td class="contentheading" width="100%" valign="middle" style="vertical-align:middle ">
								&nbsp;  <?php if ($task == 'move_surv_sel') { 
											echo $sf_lang['SF_MOVE_SURVEY'];
										 } elseif ($task == 'copy_surv_sel') { 
											echo $sf_lang['SF_COPY_SURVEY'];
										 } ?>
							</td>
							<td align="right" valign="top" style="vertical-align:top ">
								<?php SF_showTopMenu();?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td align="center" style="text-align:center">
				<span id="SF_toolbar_tooltip">&nbsp;</span><br/>
				<?php $toolbar = array();
					  $toolbar[] = array('btn_type' => 'save', 'btn_js' => "javascript:submitbutton('".($task == 'move_surv_sel'?'move_surv_save':'copy_surv_save')."');");
					  $toolbar[] = array('btn_type' => 'cancel', 'btn_js' => "javascript:submitbutton('cancel_surv');");
					  echo ShowToolbar($toolbar); 
				?>
				</td>
			</tr>
			<tr>
				<td width="100%">
				<table width="100%"><tr><td align="left">&nbsp;</td>
				<td align="right">&nbsp;</td>
				</tr>
				</table>
				</td>
			</tr>
		</table>
		
		<table width="100%" cellpadding="0" cellspacing="0" border="0"  >
		<tr>
			<td width="3%"></td>
			<td align="left" valign="top" width="30%">
			<strong><?php echo $sf_lang['SF_COPYMOVE_TO']?>:</strong>
			<br />
			<?php echo $CategoryList ?>
			<br /><br />
			</td>
			<td align="left" valign="top" width="20%">
			<strong><?php echo $sf_lang['SF_SURVEYS_BEING']?>:</strong>
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
			<?php echo $sf_lang['SF_THIS_WILL_COPYMOVE']?>
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
		<input type="hidden" name="Itemid" value="<?php echo $Itemid?>" />
		</form><br/><br/>
		</div>
		<?php
	}
	
	function SF_showQuestsList( &$rows, &$lists, &$pageNav, $option ) {
		global $task, $Itemid, $Itemid_s, $my, $sf_lang, $mosConfig_live_site, $database;
		$owner = SF_GetUserType($my->id, $lists['survid']) == 1;
		$sf_config = new mos_Survey_Force_Config( );
		?>
		<script type="text/javascript" src="/media/system/js/core.js"></script>
		<script type="text/javascript" src="components/com_surveyforce/overlib_mini.js"></script>
		<script language="javascript" type="text/javascript">
		<!--
		function submitbutton(pressbutton) {
			var form = document.adminForm;
			
			if ( ((pressbutton == 'publish_quest') || (pressbutton == 'unpublish_quest') || (pressbutton == 'del_quest') || (pressbutton == 'edit_quest') || (pressbutton == 'move_quest_sel') || (pressbutton == 'copy_quest_sel') ) && (form.boxchecked.value == "0")) {
					alert('<?php echo $sf_lang['SF_ALERT_SELECT_ITEM'];?>');
					return;
			}
				
			if (pressbutton == 'add_new') {
				/*
				switch (form.qtypes_id.value) {
					case '1': pressbutton = 'add_likert'; 		break;
					case '2': pressbutton = 'add_pickone'; 		break;
					case '3': pressbutton = 'add_pickmany'; 	break;
					case '4': pressbutton = 'add_short'; 		break;
					case '5': pressbutton = 'add_drp_dwn'; 		break;
					case '6': pressbutton = 'add_drg_drp'; 		break;
					case '7': pressbutton = 'add_boilerplate';  break;
					case '8': pressbutton = 'add_pagebreak'; 	break;
					case '9': pressbutton = 'add_ranking'; 		break;
				}
				*/
				form = document.adminForm2;
			}
			if ( ((pressbutton == 'move_quest_sel') || (pressbutton == 'copy_quest_sel')) && (form.boxchecked.value == "0")) {
				alert('<?php echo $sf_lang['SF_ALERT_SELECT_ITEM'];?>');
			} else {
				form.task.value = pressbutton;
				form.submit();
			}
		}
		function saveorder( n ) {
			checkAll_button( n );
		}

		//needed by saveorder function
		function checkAll_button( n ) {
			var form = document.adminForm;
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
			form.task.value = 'saveorder';
			form.submit();
		} 
		//-->
		</script>
		<div class="contentpane surveyforce">
		<form action="<?php echo SFRoute("index.php?option=$option{$Itemid_s}")?>" method="post" name="adminForm" id="adminForm">
		<table width="100%" cellpadding="0" cellspacing="0" border="0"  >
			<tr>
				<td width="auto">
					<table width="100%" cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td width="48px">
								<?php echo SF_showHeadPicture('surveys');?>
							</td>
							<td class="contentheading" width="100%" valign="middle" style="vertical-align:middle ">
								&nbsp;<?php echo $sf_lang['SF_LIST_QUESTS']?>
							</td>
							<td align="right" valign="top" style="vertical-align:top ">
								<?php SF_showTopMenu();?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr align="center">
				<td align="center" style="text-align:center">
				<span id="SF_toolbar_tooltip" style="text-align:center">&nbsp;</span><br/>
				<?php $toolbar = array();
					  if (!$sf_config->get('sf_enable_jomsocial_integration')) { 
						  if ($owner) {
							$toolbar[] = array('btn_type' => 'move', 'btn_js' => "javascript:submitbutton('move_quest_sel');");
						  }
						  $toolbar[] = array('btn_type' => 'copy', 'btn_js' => "javascript:submitbutton('copy_quest_sel');");
					  }
					  if ($owner) {	
					  	$toolbar[] = array('btn_type' => 'publish', 'btn_js' => "javascript:submitbutton('publish_quest');");
						$toolbar[] = array('btn_type' => 'unpublish', 'btn_js' => "javascript:submitbutton('unpublish_quest');"); 
					  	$toolbar[] = array('btn_type' => 'del', 'btn_js' => "javascript:submitbutton('del_quest');"); 
					  	$toolbar[] = array('btn_type' => 'edit', 'btn_js' => "javascript:submitbutton('edit_quest');");
						if (!$sf_config->get('sf_enable_jomsocial_integration')) { 
					  		$toolbar[] = array('btn_type' => 'new_s', 'btn_js' => "javascript:submitbutton('add_new_section');", 'btn_str' => $sf_lang['SF_NEW_SECTION']); 
						}
					  	$toolbar[] = array('btn_type' => 'new', 'btn_js' => "index.php?option=com_surveyforce&task=new_question_type&tmpl=component", 'btn_str' => $sf_lang['SF_NEW_QUESTION'], 'btn_class'=>'modal'); 
					  }
					  $toolbar[] = array('btn_type' => 'back', 'btn_js' => "javascript:submitbutton('surveys');", 'btn_str' => $sf_lang['SF_SURVEYS']);
					  echo ShowToolbar($toolbar);
					  /*
					  if ($owner) { 
					  	echo $sf_lang['SF_NEW_QUEST'].':';
					  	echo $lists['qtypes'];
					  }
					  */
				?>
				</td>
			</tr>
			<tr>
				<td width="100%">
				<table width="100%"><tr><td align="left" style="text-align:left">&nbsp;<?php echo $sf_lang['SF_FILTER']?>:<?php echo $lists['survey'];?></td>
				<td align="right" style="text-align:right"><?php
					$link = "index.php?option=$option{$Itemid_s}&amp;task=questions&surv_id=".$lists['survid'];
					echo _PN_DISPLAY_NR . $pageNav->getLimitBox( $link ) . '&nbsp;' ;
					echo $pageNav->writePagesCounter(). '&nbsp;&nbsp;';
				?></td>
				</tr>
				</table>
				</td>
			</tr>
		</table>
		<table width="100%" border="0">
		<tr>	
			<td align="left"><?php if (!$sf_config->get('sf_enable_jomsocial_integration')) { echo $lists['sf_auto_pb_on']; }?></td>
			<td align="right"><?php if ($lists['survid'] > 0) {?>Link for this survey: <a href="<?php echo SFRoute('index.php?option='.$option.$Itemid_s.'&survey='.$lists['survid']);?>"><?php echo SFRoute('index.php?option='.$option.$Itemid_s.'&survey='.$lists['survid']);?></a><?php }?></td>
		</tr>
		</table>

		<table width="100%" cellpadding="2" cellspacing="2" border="0"   >
		<tr>
			<td width="15px" class="sectiontableheader">#</td>
			<td width="20px" class="sectiontableheader"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($rows); ?>);" /></td>
			
			<td class="sectiontableheader" width="2%"  ><?php echo $sf_lang['SF_TEXT']?></td>
			<td class="sectiontableheader" width="32%"  >&nbsp;</td>
			<td class="sectiontableheader"><?php echo ucfirst($sf_lang['SF_PUBLISHED']);?></td>
			<td class="sectiontableheader" colspan="2" width="5%"><?php echo $sf_lang['SF_REORDER']?></td>
			<td width="2%" class="sectiontableheader"><?php echo $sf_lang['SF_ORDER']?></td>
			<td width="1%" class="sectiontableheader">
				<a href="javascript: <?php echo ($lists['survid'] > 0 && $owner? 'saveorder('.count( $rows ).')' : ' void(0); ' )?>"><img src="components/com_surveyforce/images/filesave.png" border="0" width="16" height="16" alt="Save Order" /></a>
			</td> 
			<td class="sectiontableheader"><?php echo $sf_lang['SF_TYPE']?></td>
			<td class="sectiontableheader"><?php echo $sf_lang['SF_SURVEY']?></td>
		</tr>
		<?php
		$k = 1;
		$s = 1;
		$ii = 0;
		$jj = 0;
		$first = true;
		$last = true;
		for ($i=0, $n=count($rows); $i < $n; $i++) {
			$row = $rows[$i];
			$last = (isset($rows->last)?$rows->last:true);
			$img_published	= $row->published ? 'btn_accept.png' : 'btn_cancel.png';
			$task_published	= $row->published ? 'unpublish_quest' : 'publish_quest';
			$alt_published 	= $row->published ? $sf_lang['SF_PUBLISHED']  : $sf_lang['SF_UNPUBLISHED'] ;
			if ( !isset($row->sf_section_id)) { 				
				$checked = '<input type="checkbox" id="cbs'.$ii.'" name="sec[]" value="'.$row['id'].'" onclick="isChecked(this.checked);" />';				
				$link = SFRoute('index.php?option=com_surveyforce'.$Itemid_s.'&task=questions#');
				if ($owner)
					$link = SFRoute('index.php?option='.$option.$Itemid_s.'&task=editA_sec&hidemainmenu=1&id='.$row['id']);
				?>
				<tr class="<?php echo "sectiontableentry$k"; ?>">
					<td>&nbsp;</td>
					<td><?php echo $checked; ?></td>
					
					<td align="left" colspan="2"><a title="Edit Section" href='<?php echo $link?>'><?php echo $row['sf_name']?></a></td>
					<td align="center"></td>
					<td align="center"><?php if (!isset($row['first']) && $owner  && $row['quest_id'] != '') echo '<a href="#reorder" onClick="return listItemTask(\'cbs'.$ii.'\',\'orderupS\')" title="Move Up Section"><img src="components/com_surveyforce/images/toolbar/btn_uparrow_s.png" width="16" height="16" border="0" alt="Move Up Section"></a>'; ?></td>
					<td align="center"><?php if (!isset($row['end']) && $owner  && $row['quest_id'] != '') echo '<a href="#reorder" onClick="return listItemTask(\'cbs'.$ii.'\',\'orderdownS\')" title="Move Down Section"><img src="components/com_surveyforce/images/toolbar/btn_downarrow_s.png" width="16" height="16" border="0" alt="Move Down Section"></a>'; ?></td>
					<td align="center" colspan="2">
					<input type="text" name="orderS[]" size="4" value="<?php echo $row['ordering'] ?>" <?php echo ($owner  && $row['quest_id'] != ''?'':'disabled="disabled"')?> class="inputbox" style="text-align: center; background-color: #FFFAEC;" />
					</td>
					<td align="left">&nbsp;Section</td>
					<td align="left"><?php echo $row['survey_name']; ?></td>
				</tr>
				<?php
				$ii++;
				$k = 3 - $k;
			}
			else {
				$link 	= SFRoute('index.php?option=com_surveyforce'.$Itemid_s.'&task=editA_quest&hidemainmenu=1&id='. $row->id);
				$checked = mosHTML::idBox( $jj, $row->id);
				?>
				<tr class="<?php echo "sectiontableentry$k"; ?>">
					<td align="center"><?php echo $pageNav->rowNumber( $jj ); ?></td>
					<td align="center"><?php					
						if ($lists['survid'] > 0 && $i > 0 && $row->sf_section_id == $rows[$i-1]->sf_section_id && $row->sf_section_id > 0) {
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
					<?php echo ($row->sf_section_id > 0 && $lists['survid'] > 0 ? "<td>$checked</td>":''); ?>
					<td align="left" <?php echo ($row->sf_section_id > 0 && $lists['survid'] > 0? '':'colspan="2"')?>>
					<?php
					$txt_for_tip = '';
					if (!$sf_config->get('sf_enable_jomsocial_integration')) {
						if ( $row->sf_qtype == 7 )
							$txt_for_tip = '<b>'.$sf_lang['SF_QUEST_TEXT'].':</b><br/>'.$row->sf_qtext;	
						elseif ( $row->sf_qtype == 8 )
							$txt_for_tip = '<b>'.trim(strip_tags($row->sf_qtext)).'</b><br/>';	
						else
							$txt_for_tip = '<b>'.$sf_lang['SF_IMP_SCALE_NOT_DEF'].'</b>';
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
						<a href="<?php echo $link; ?>" onmouseover='overlib("<?php echo txt2overlib($txt_for_tip); ?>", CAPTION, "<?php echo $sf_lang['SF_QUEST_RANK'] ?>")' onmouseout="return nd();"><?php echo (strlen(trim(strip_tags($row->sf_qtext))) > 100 ? substr(trim(strip_tags($row->sf_qtext)), 0, 100).'...': trim(strip_tags($row->sf_qtext))); ?></a>
					<?php } else { ?>
						<a href="<?php echo $link; ?>" ><?php echo (strlen(trim(strip_tags($row->sf_qtext))) > 100 ? substr(trim(strip_tags($row->sf_qtext)), 0, 100).'...': trim(strip_tags($row->sf_qtext))); ?></a>
					<?php }?>
					</td>
					<td align="left">
						<?php if ( $owner) {?>
						<a href="javascript: void(0);" onClick="return listItemTask('cb<?php echo $jj;?>','<?php echo $task_published;?>')">
							<img src="components/com_surveyforce/images/toolbar/<?php echo $img_published;?>" width="16" height="16" border="0" alt="<?php echo $alt_published; ?>" />
						</a>
						<?php }?>
					</td>

					<td align="center">
					<?php if ((($jj+$pageNav->limitstart > 0)) && $first && $lists['survid'] > 0 && $owner) 
							echo '<a href="#reorder" onClick="return listItemTask(\'cb'.$jj.'\',\'orderup\')" title="Move Up"><img src="components/com_surveyforce/images/toolbar/btn_uparrow.png" width="16" height="16" border="0" alt="Move Up"></a>';
					?>
					</td>
					<td align="center">
					<?php if (($jj+$pageNav->limitstart < $pageNav->total-1) && $lists['survid'] > 0 && $owner) 
								echo '<a href="#reorder" onClick="return listItemTask(\'cb'.$jj.'\',\'orderdown\')" title="Move Down"><img src="components/com_surveyforce/images/toolbar/btn_downarrow.png" width="16" height="16" border="0" alt="Move Down"></a>';
					?>
					</td>
					<td align="center" colspan="2">
					<input type="text" name="order[]" size="4" value="<?php echo ($row->sf_section_id > 0 && $lists['survid'] > 0? $s-1: $row->ordering);?>" class="inputbox" style="text-align: center; " <?php echo ($lists['survid'] > 0 && $owner? '' : ' disabled="disabled" ' )?>  />
					</td>
					<td align="left">&nbsp;
						<?php echo $row->qtype_full; ?>
					</td>
					<td align="left">
						<?php echo $row->survey_name; ?>
					</td>
				</tr>
				<?php				
				$last = true;
				$k = 3 - $k;
				$jj++;
			}
		}?>		
		</table>
		<div align="center">
		<?php
		$link = "index.php?option=$option{$Itemid_s}&amp;task=questions&surv_id=".$lists['survid']; 
		echo $pageNav->writePagesLinks($link).'<br/>';
		?>
		</div>
		<input type="hidden" name="option" value="com_surveyforce" />
		<input type="hidden" name="task" value="questions" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="hidemainmenu" value="0">
		<input type="hidden" name="Itemid" value="<?php echo $Itemid?>" />
		</form><br/><br/>
		</div>
		<?php
	}
	
	function SF_editSection( &$row, &$lists, $option ) {
		global $task, $Itemid, $Itemid_s, $my, $sf_lang, $mosConfig_live_site, $database;
		?>
		<script type="text/javascript" src="/media/system/js/core.js"></script>
		<script type="text/javascript" src="components/com_surveyforce/overlib_mini.js"></script>
		<script language="javascript" type="text/javascript">
		<!--
		function submitbutton(pressbutton) {
			var form = document.adminForm;

			if (pressbutton == 'cancel_section') {
				form.task.value = pressbutton;
				form.submit();
				return;
			}
			// do field validation
			if (form.sf_name.value == ""){
				alert( "<?php echo $sf_lang['SF_ALERT_SEC_MUST_HAVE_NAME']?>" );
			} else {
				form.task.value = pressbutton;
				form.submit();
			}
		}
		//-->
		</script>
		<div class="contentpane surveyforce">
		<form action="<?php echo SFRoute("index.php?option=$option{$Itemid_s}")?>" method="post" name="adminForm" id="adminForm">
		<table width="100%" cellpadding="0" cellspacing="0" border="0"  >
			<tr>
				<td width="auto">
					<table width="100%" cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td width="48px">
								<?php echo SF_showHeadPicture('surveys');?>
							</td>
							<td class="contentheading" width="100%" valign="middle" style="vertical-align:middle ">
								&nbsp;<?php echo $row->id ? $sf_lang['SF_EDIT_SEC'] : $sf_lang['SF_NEW_SEC'];?>
							</td>
							<td align="right" valign="top" style="vertical-align:top ">
								<?php SF_showTopMenu();?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td align="center" style="text-align:center">
				<span id="SF_toolbar_tooltip">&nbsp;</span><br/>
				<?php $toolbar = array();
					  $toolbar[] = array('btn_type' => 'save', 'btn_js' => "javascript:submitbutton('save_section');");
					  $toolbar[] = array('btn_type' => 'apply', 'btn_js' => "javascript:submitbutton('apply_section');");
					  $toolbar[] = array('btn_type' => 'cancel', 'btn_js' => "javascript:submitbutton('cancel_section');"); 
					  echo ShowToolbar($toolbar); 
				?>
				</td>
			</tr>
			<tr>
				<td width="100%">
				<table width="100%"><tr><td align="left">&nbsp;</td>
				<td align="right">&nbsp;</td>
				</tr>
				</table>
				</td>
			</tr>
		</table>
		
		<table width="100%" cellpadding="2" cellspacing="0" border="0"  >
			<tr>
				<td colspan="3" class="sectiontableheader"><?php echo $sf_lang['SF_SEC_DETAILS']?></td>
			</tr>
			<tr>
				<td align="left" width="15%"><?php echo $sf_lang['SF_NAME']?>:</td>
				<td><input class="inputbox" type="text" name="sf_name" size="30" maxlength="100" value="<?php echo $row->sf_name; ?>" /></td>
				<td >
				</td>
			</tr>
			<tr>
				<td align="left" width="15%"><?php echo $sf_lang['SF_SURVEY']?>:</td>
				<td><?php echo $lists['sf_surveys']; ?></td>
			</tr>
			<tr>
				<td align="left" width="15%"><?php echo $sf_lang['SF_QUESTIONS']?>:</td>
				<td><?php echo $lists['sf_questions']; ?></td>
			</tr>
			<tr>
				<td align="left" width="15%"><?php echo $sf_lang['SF_ORDERING']?>:</td>
				<td><?php echo $lists['ordering']; ?></td>
			</tr>
			
		</table>
		<br />
		<input type="hidden" name="option" value="com_surveyforce" />
		<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="hidemainmenu" value="0">
		<input type="hidden" name="Itemid" value="<?php echo $Itemid?>" />
		</form><br/><br/>
		</div>
		<?php
	
	}
	
	function SF_editQ_Short( &$row, &$lists, $option ) {
		global $task, $Itemid, $Itemid_s,$my, $sf_lang, $mosConfig_live_site, $_MAMBOTS;
		$owner = SF_GetUserType($my->id, $lists['survid']) == 1;
		$sf_config = new mos_Survey_Force_Config( );
		if (_JOOMLA15) {
			jimport( 'joomla.html.editor' );
	
			$conf =& JFactory::getConfig();
			$editor = $conf->getValue('config.editor');
			$editorz =& JEditor::getInstance($editor);
			$editorz =& JFactory::getEditor();
		} 
		?>
		<script type="text/javascript" src="/media/system/js/core.js"></script>
		<script type="text/javascript" src="components/com_surveyforce/overlib_mini.js"></script>
		<script language="javascript" type="text/javascript">
		<!--
		function submitbutton(pressbutton) {
			var form = document.adminForm;

			if (pressbutton == 'cancel_quest') {
				form.task.value = pressbutton;
				form.submit();
				return;
			}
			// do field validation
			
			fillTextArea();
			if (false && form.sf_qtext.value == ""){
				alert( "<?php echo $sf_lang['SF_ALERT_QUEST_MUST_HAVE_TEXT']?>" );
			} else {
				form.task.value = pressbutton;
				form.submit();
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
		<div class="contentpane surveyforce">
		<form action="<?php echo SFRoute("index.php?option=$option{$Itemid_s}")?>" method="post" name="adminForm" id="adminForm">
		<table width="100%" cellpadding="0" cellspacing="0" border="0"  >
			<tr>
				<td width="auto">
					<table width="100%" cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td width="48px">
								<?php echo SF_showHeadPicture('surveys');?>
							</td>
							<td class="contentheading" width="100%" valign="middle" style="vertical-align:middle ">
								&nbsp;<?php echo $row->id ? $sf_lang['SF_EDIT_QUEST'] : $sf_lang['SF_NEW_QUEST']; echo ' ('.$sf_lang['SF_SHORT_ANSWER'].')';?>
							</td>
							<td align="right" valign="top" style="vertical-align:top ">
								<?php SF_showTopMenu();?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td align="center" style="text-align:center">
				<span id="SF_toolbar_tooltip">&nbsp;</span><br/>
				<?php $toolbar = array();
					if ($owner) {
					  $toolbar[] = array('btn_type' => 'save', 'btn_js' => "javascript:submitbutton('save_quest');");
					  $toolbar[] = array('btn_type' => 'apply', 'btn_js' => "javascript:submitbutton('apply_quest');");
					}
					  $toolbar[] = array('btn_type' => 'cancel', 'btn_js' => "javascript:submitbutton('cancel_quest');"); 
					  echo ShowToolbar($toolbar); 
				?>
				</td>
			</tr>
			<tr>
				<td width="100%">
				<table width="100%"><tr><td align="left">&nbsp;</td>
				<td align="right">&nbsp;</td>
				</tr>
				</table>
				</td>
			</tr>
		</table>
		
		<table width="100%" cellpadding="2" cellspacing="0" border="0"  >
			<tr>
				<td colspan="2"  class="sectiontableheader"><?php echo $sf_lang['SF_QUEST_DETAILS']?></td>
			</tr>
			<tr>
				<td align="left" width="20%" valign="top" colspan="2"><?php echo $sf_lang['SF_QUEST_TEXT']?>:</td>
			</tr>
			<tr>
				<td colspan="2"  align="left"><?php 
					if ($owner)
						SF_editorArea( 'editor2', $row->sf_qtext, 'sf_qtext', '100%;', '250', '40', '20' ); 
					else
						echo $row->sf_qtext;
				?>
				</td>
			</tr>
			<tr>			
			<td colspan="2"><b><small><?php echo $sf_lang['SF_SHORT_ANS_TOOLTIP']?></small></b></td>
			</tr>
			<tr>
				<td><?php echo $sf_lang['SF_SURVEY']?>:</td><td><?php echo $lists['survey'];?></td>
			</tr>
			<?php if (!$sf_config->get('sf_enable_jomsocial_integration')) { ?>
			<tr>
				<td><?php echo $sf_lang['SF_IMP_SCALE']?>:</td><td><?php echo $lists['impscale'];?> <?php if ($owner) {?><input type="button" class="button" name="<?php echo $sf_lang['SF_DEFINE_NEW']?>" onClick="javascript: fillTextArea();document.adminForm.task.value='add_iscale_from_quest';document.adminForm.submit();" value="Define new"><?php }?></td>
			</tr>
			<?php }?>
			<tr>
				<td>
					<?php echo $sf_lang["SF_PUBLISHED"];?>:
				</td>
				<td>
					<?php echo $lists['published']; ?>
				</td>
			</tr>
			<tr>
				<td><?php echo $sf_lang['SF_ORDERING']?>:</td><td><?php echo $lists['ordering'];?></td>
			</tr> 
			<?php if ( $lists['sf_section_id'] != null ) {?>
			<tr>
				<td><?php echo $sf_lang['SF_SECTION']?>:</td><td><?php echo $lists['sf_section_id'];?></td>
			</tr> 
			<?php }?>
			<tr>
				<td><?php echo $sf_lang['SF_COMPULSORY']?>:</td>
				<td>
					<?php echo $lists['compulsory']; ?>
				</td>
			</tr> 
			<?php if (!($row->id > 0)) {?>
			<tr>
				<td><?php echo $sf_lang['SF_INSERT_PAGE_BREAK']?>:</td>
				<td>
					<?php echo $lists['insert_pb']; ?>
				</td>
			</tr>
			<?php } ?>
			<?php if (!$sf_config->get('sf_enable_jomsocial_integration')) { ?>
			<tr>
				<td colspan="2">
					<script type="text/javascript" src="<?php echo $mosConfig_live_site;?>/administrator/components/com_surveyforce/js/jquery.pack.js"></script>
					<script type="text/javascript" language="javascript" >
						jQuery.noConflict();
						var sf_is_loading = false;
					</script>
					<table class="adminlist" id="show_quest">
					<tr>
						<th class="title" colspan="4"><?php echo $sf_lang['SF_DONT_SHOW']?>:</th>
					</tr>
					<?php if (is_array($lists['quest_show']) && count($lists['quest_show'])) 
							foreach($lists['quest_show'] as $rule) {
								if ( ($rule->sf_qtype == 2) || ($rule->sf_qtype == 3) ) {
							?>
							
							<tr>
								<td width="375px;"> <?php echo $sf_lang['SF_FOR_QUESTION']?> "<?php echo $rule->sf_qtext;?>" <input type="hidden" name="sf_hid_rule2_id[]" value="<?php echo $rule->bid;?>" /><input type="hidden" name="sf_hid_rule2_alt_id[]" value="<?php echo $rule->did;?>" /><input type="hidden" name="sf_hid_rule2_quest_id[]" value="<?php echo $rule->qid;?>" /></td>
								<td colspan="2"> <?php echo $sf_lang['SF_ANSWER_IS']?> "<?php echo $rule->qoption;?>"</td>
								<td><a href="javascript: void(0);" onclick="javascript:Delete_row(this); return false;" title="Delete"><img src="/administrator/images/publish_x.png"  border="0" alt="Delete"></a></td>
							</tr>
							<?php } elseif (($rule->sf_qtype == 1) || ($rule->sf_qtype == 5) || ($rule->sf_qtype == 6)) {?>
							<tr>
								<td  width="375px;"> <?php echo $sf_lang['SF_FOR_QUESTION']?> "<?php echo $rule->sf_qtext;?>" <input type="hidden" name="sf_hid_rule2_id[]" value="<?php echo $rule->bid;?>" /><input type="hidden" name="sf_hid_rule2_alt_id[]" value="<?php echo ($rule->sf_qtype == 1?$rule->sdid:$rule->fdid);?>" /><input type="hidden" name="sf_hid_rule2_quest_id[]" value="<?php echo $rule->qid;?>" /></td>
								<td> <?php echo $sf_lang['SF_AND_OPTION']?> "<?php echo $rule->qoption;?>"</td>
								<td> <?php echo $sf_lang['SF_ANSWER_IS']?> "<?php echo ($rule->sf_qtype == 1?$rule->astext:$rule->aftext);?>"</td>
								<td><a href="javascript: void(0);" onclick="javascript:Delete_row(this); return false;" title="Delete"><img src="/administrator/images/publish_x.png"  border="0" alt="Delete"></a></td>
							</tr>
							<?php } elseif ($rule->sf_qtype == 9) {?>
							<tr >
								<td  width="375px;"> <?php echo $sf_lang['SF_FOR_QUESTION']?> "<?php echo $rule->sf_qtext;?>" <input type="hidden" name="sf_hid_rule2_id[]" value="<?php echo $rule->bid;?>" /><input type="hidden" name="sf_hid_rule2_alt_id[]" value="<?php echo $rule->did;?>" /><input type="hidden" name="sf_hid_rule2_quest_id[]" value="<?php echo $rule->qid;?>" /></td>
								<td> <?php echo $sf_lang['SF_AND_OPTION']?> "<?php echo $rule->qoption;?>"</td>
								<td> <?php echo $sf_lang['SF_RANK_IS']?> "<?php echo $rule->aftext;?>"</td>
								<td><a href="javascript: void(0);" onclick="javascript:Delete_row(this); return false;" title="Delete"><img src="/administrator/images/publish_x.png"  border="0" alt="Delete"></a></td>
							</tr>	
							<?php }
							}?>
					</table>
					<table width="100%"  id="show_quest2">
					<tr>
						<td style="width:70px;"><?php echo $sf_lang['SF_FOR_QUESTION']?> </td><td style="width:15px;"><?php echo $lists['quests3'];?></td>
						<td width="auto" colspan="2" ><div id="quest_show_div"></div>						
						</td>
					</tr>							
					<tr>
						<td colspan="4" style="text-align:left;"><input id="add_button" type="button" name="add" value="<?php echo $sf_lang["SF_ADD"]?>" onclick="javascript: if(!sf_is_loading) addRow();"  />
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
							cell1.innerHTML = '<?php echo $sf_lang['SF_FOR_QUESTION']?> "'+jQuery('#sf_quest_list3').get(0).options[jQuery('#sf_quest_list3').get(0).selectedIndex].innerHTML+'"';
							cell1.appendChild(input_hidden);
							cell1.appendChild(input_hidden2);
							cell1.appendChild(input_hidden3);
							if (qtype != 2 && qtype != 3) {
								cell2.innerHTML = ' <?php echo $sf_lang['SF_AND_OPTION']?> "'+jQuery('#sf_field_data_m').get(0).options[jQuery('#sf_field_data_m').get(0).selectedIndex].innerHTML+'"';				
								if (qtype != 9){
									if (qtype == 1)
										cell3.innerHTML = ' <?php echo $sf_lang['SF_ANSWER_IS']?> "'+jQuery('#f_scale_data').get(0).options[jQuery('#f_scale_data').get(0).selectedIndex].innerHTML+'"';
									else
										cell3.innerHTML = ' <?php echo $sf_lang['SF_ANSWER_IS']?> "'+jQuery('#sf_field_data_a').get(0).options[jQuery('#sf_field_data_a').get(0).selectedIndex].innerHTML+'"';
								}else {
									cell3.innerHTML = ' <?php echo $sf_lang['SF_RANK_IS']?> "'+jQuery('#sf_field_data_a').get(0).options[jQuery('#sf_field_data_a').get(0).selectedIndex].innerHTML+'"';
								}
							} else {
								cell2.innerHTML = ' <?php echo $sf_lang['SF_ANSWER_IS']?> "'+jQuery('#sf_field_data_m').get(0).options[jQuery('#sf_field_data_m').get(0).selectedIndex].innerHTML+'"';	
							}
							
							cell4.innerHTML = '<a href="javascript: void(0);" onclick="javascript:Delete_row(this); return false;" title="Delete"><img src="/administrator/images/publish_x.png"  border="0" alt="Delete"></a>';							
							row.appendChild(cell1);
							row.appendChild(cell2);							
							row.appendChild(cell3);
							row.appendChild(cell4);						
						}
						function processReq(http_request) {
							if (http_request.readyState == 4) {
								if ((http_request.status == 200)) {									
									var response = http_request.responseXML.documentElement;
									var text = 'Request Error';
									try {
										text = response.getElementsByTagName('data')[0].firstChild.data;
									} catch(e) {}
									jQuery('div#quest_show_div').html(text);							
								}
							}
						}
						function showOptions(val) {
							
							jQuery('input#add_button').get(0).style.display = 'none';
							
							jQuery('div#quest_show_div').html("Please wait... Loading...");
							
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

$live_url = $live_site_parts['scheme'].'://'.$live_site_parts['host'].(isset($live_site_parts['path'])?$live_site_parts['path']:'/');

if ( substr($live_url, strlen($live_url)-1, 1) !== '/')
	$live_url .= '/';

?>
							http_request.open('GET', '<?php echo $live_url;?>index.php?no_html=1&option=com_surveyforce&task=get_options&rand=<?php echo time();?>&quest_id='+val, true);
							http_request.send(null);						
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
			<?php }?>
		</table>
		<br />
		<input type="hidden" name="sf_qtype" value="4" />
		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="id" value="<?php echo $row->id;?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="Itemid" value="<?php echo $Itemid?>" />
		
		<input type="hidden" name="quest_id" value="<?php echo $row->id;?>" />
		<input type="hidden" name="red_task" value="<?php echo $task;?>" />
		</form><br/><br/>
		</div>
		<?php
	}
	function SF_editQ_Boilerplate( &$row, &$lists, $option, $q_om_type ) {
		global $task, $Itemid, $Itemid_s, $my, $sf_lang, $mosConfig_live_site, $_MAMBOTS;
		$owner = SF_GetUserType($my->id, $lists['survid']) == 1;
		$sf_config = new mos_Survey_Force_Config( );
		if (_JOOMLA15) {
			jimport( 'joomla.html.editor' );
	
			$conf =& JFactory::getConfig();
			$editor = $conf->getValue('config.editor');
			$editorz =& JEditor::getInstance($editor);
			$editorz =& JFactory::getEditor();
		}
		?>
		<script type="text/javascript" src="/media/system/js/core.js"></script>
		<script type="text/javascript" src="components/com_surveyforce/overlib_mini.js"></script>
		<script language="javascript" type="text/javascript">
		<!--
		function submitbutton(pressbutton) {
			var form = document.adminForm;

			if (pressbutton == 'cancel_quest') {
				form.task.value = pressbutton;
				form.submit();
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
				alert( "<?php echo $sf_lang['SF_ALERT_QUEST_MUST_HAVE_TEXT']?>" );
			} else {
				form.task.value = pressbutton;
				form.submit();
			}
		}
		
		
		//-->
		</script>
		<div class="contentpane surveyforce">
		<form action="<?php echo SFRoute("index.php?option=$option{$Itemid_s}")?>" method="post" name="adminForm" id="adminForm">
		<table width="100%" cellpadding="0" cellspacing="0" border="0"  >
			<tr>
				<td width="auto">
					<table width="100%" cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td width="48px">
								<?php echo SF_showHeadPicture('surveys');?>
							</td>
							<td class="contentheading" width="100%" valign="middle" style="vertical-align:middle ">
								&nbsp;<?php echo $row->id ? $sf_lang['SF_EDIT_QUEST'] : $sf_lang['SF_NEW_QUEST']; echo ' ('.$sf_lang['SF_BOILERPLATE'].')';?>
							</td>
							<td align="right" valign="top" style="vertical-align:top ">
								<?php SF_showTopMenu();?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td align="center" style="text-align:center">
				<span id="SF_toolbar_tooltip">&nbsp;</span><br/>
				<?php $toolbar = array();
					if ($owner) {
					  $toolbar[] = array('btn_type' => 'save', 'btn_js' => "javascript:submitbutton('save_quest');");
					  $toolbar[] = array('btn_type' => 'apply', 'btn_js' => "javascript:submitbutton('apply_quest');");
					}
					  $toolbar[] = array('btn_type' => 'cancel', 'btn_js' => "javascript:submitbutton('cancel_quest');"); 
					  echo ShowToolbar($toolbar); 
				?>
				</td>
			</tr>
			<tr>
				<td width="100%">
				<table width="100%"><tr><td align="left">&nbsp;</td>
				<td align="right">&nbsp;</td>
				</tr>
				</table>
				</td>
			</tr>
		</table>
		
		<table width="100%" cellpadding="2" cellspacing="0" border="0"  >
			<tr>
				<td colspan="2" class="sectiontableheader"><?php echo $sf_lang['SF_QUEST_DETAILS']?></td>
			</tr>
			<tr>
				<td colspan="2" align="left" width="20%" valign="top"><?php echo $sf_lang['SF_QUEST_TEXT']?>:</td>
			</tr>
			<tr>
				<td colspan="2" align="left"><?php 
					if ($owner) 
						SF_editorArea( 'editor2', $row->sf_qtext, 'sf_qtext', '100%;', '250', '40', '20' ) ;
					else 
						echo $row->sf_qtext; ?>
				</td>
			</tr>
			<tr>
				<td><?php echo $sf_lang['SF_SURVEY']?>:</td><td><?php echo $lists['survey'];?></td>
			</tr>
			<tr>
				<td>
					<?php echo $sf_lang["SF_PUBLISHED"];?>:
				</td>
				<td>
					<?php echo $lists['published']; ?>
				</td>
			</tr>
			<tr>
				<td><?php echo $sf_lang['SF_ORDERING']?>:</td><td><?php echo $lists['ordering'];?></td>
			</tr> 
			<?php if ( $lists['sf_section_id'] != null ) {?>
			<tr>
				<td><?php echo $sf_lang['SF_SECTION']?>:</td><td><?php echo $lists['sf_section_id'];?></td>
			</tr> 
			<?php }?>
			<?php if (!($row->id > 0)) {?>
			<tr>
				<td><?php echo $sf_lang['SF_INSERT_PAGE_BREAK']?>:</td>
				<td>
					<?php echo $lists['insert_pb']; ?>
				</td>
			</tr>
			<?php } ?>
			<?php if (!$sf_config->get('sf_enable_jomsocial_integration')) { ?>
			<tr>
				<td colspan="2">
					<script type="text/javascript" src="<?php echo $mosConfig_live_site;?>/administrator/components/com_surveyforce/js/jquery.pack.js"></script>
					<script type="text/javascript" language="javascript" >
						jQuery.noConflict();
						var sf_is_loading = false;
					</script>
					<table class="adminlist" id="show_quest">
					<tr>
						<th class="title" colspan="4"><?php echo $sf_lang['SF_DONT_SHOW']?>:</th>
					</tr>
					<?php if (is_array($lists['quest_show']) && count($lists['quest_show'])) 
							foreach($lists['quest_show'] as $rule) {
								if ( ($rule->sf_qtype == 2) || ($rule->sf_qtype == 3) ) {
							?>
							
							<tr>
								<td width="375px;"> <?php echo $sf_lang['SF_FOR_QUESTION']?> "<?php echo $rule->sf_qtext;?>" <input type="hidden" name="sf_hid_rule2_id[]" value="<?php echo $rule->bid;?>" /><input type="hidden" name="sf_hid_rule2_alt_id[]" value="<?php echo $rule->did;?>" /><input type="hidden" name="sf_hid_rule2_quest_id[]" value="<?php echo $rule->qid;?>" /></td>
								<td colspan="2"> <?php echo $sf_lang['SF_ANSWER_IS']?> "<?php echo $rule->qoption;?>"</td>
								<td><a href="javascript: void(0);" onclick="javascript:Delete_row(this); return false;" title="Delete"><img src="/administrator/images/publish_x.png"  border="0" alt="Delete"></a></td>
							</tr>
							<?php } elseif (($rule->sf_qtype == 1) || ($rule->sf_qtype == 5) || ($rule->sf_qtype == 6)) {?>
							<tr>
								<td  width="375px;"> <?php echo $sf_lang['SF_FOR_QUESTION']?> "<?php echo $rule->sf_qtext;?>"<input type="hidden" name="sf_hid_rule2_id[]" value="<?php echo $rule->bid;?>" /><input type="hidden" name="sf_hid_rule2_alt_id[]" value="<?php echo ($rule->sf_qtype == 1?$rule->sdid:$rule->fdid);?>" /><input type="hidden" name="sf_hid_rule2_quest_id[]" value="<?php echo $rule->qid;?>" /></td>
								<td> <?php echo $sf_lang['SF_AND_OPTION']?> "<?php echo $rule->qoption;?>"</td>
								<td> <?php echo $sf_lang['SF_ANSWER_IS']?> "<?php echo ($rule->sf_qtype == 1?$rule->astext:$rule->aftext);?>"</td>
								<td><a href="javascript: void(0);" onclick="javascript:Delete_row(this); return false;" title="Delete"><img src="/administrator/images/publish_x.png"  border="0" alt="Delete"></a></td>
							</tr>
							<?php } elseif ($rule->sf_qtype == 9) {?>
							<tr >
								<td  width="375px;"> <?php echo $sf_lang['SF_FOR_QUESTION']?> "<?php echo $rule->sf_qtext;?>"<input type="hidden" name="sf_hid_rule2_id[]" value="<?php echo $rule->bid;?>" /><input type="hidden" name="sf_hid_rule2_alt_id[]" value="<?php echo $rule->did;?>" /><input type="hidden" name="sf_hid_rule2_quest_id[]" value="<?php echo $rule->qid;?>" /></td>
								<td> <?php echo $sf_lang['SF_AND_OPTION']?> "<?php echo $rule->qoption;?>"</td>
								<td> <?php echo $sf_lang['SF_RANK_IS']?> "<?php echo $rule->aftext;?>"</td>
								<td><a href="javascript: void(0);" onclick="javascript:Delete_row(this); return false;" title="Delete"><img src="/administrator/images/publish_x.png"  border="0" alt="Delete"></a></td>
							</tr>	
							<?php }
							}?>
					</table>
					<table width="100%"  id="show_quest2"> 
					<tr>
						<td style="width:70px;"><?php echo $sf_lang['SF_FOR_QUESTION']?> </td><td style="width:15px;"><?php echo $lists['quests3'];?></td>
						<td width="auto" colspan="2" ><div id="quest_show_div"></div>						
						</td>
					</tr>							
					<tr>
						<td colspan="4" style="text-align:left;"><input id="add_button" type="button" name="add" value="<?php echo $sf_lang["SF_ADD"];?>" onclick="javascript: if(!sf_is_loading) addRow();"  />
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
							cell1.innerHTML = '<?php echo $sf_lang['SF_FOR_QUESTION']?> "'+jQuery('#sf_quest_list3').get(0).options[jQuery('#sf_quest_list3').get(0).selectedIndex].innerHTML+'"';
							cell1.appendChild(input_hidden);
							cell1.appendChild(input_hidden2);
							cell1.appendChild(input_hidden3);
							if (qtype != 2 && qtype != 3) {
								cell2.innerHTML = ' <?php echo $sf_lang['SF_AND_OPTION']?> "'+jQuery('#sf_field_data_m').get(0).options[jQuery('#sf_field_data_m').get(0).selectedIndex].innerHTML+'"';				
								if (qtype != 9){
									if (qtype == 1)
										cell3.innerHTML = ' <?php echo $sf_lang['SF_ANSWER_IS']?> "'+jQuery('#f_scale_data').get(0).options[jQuery('#f_scale_data').get(0).selectedIndex].innerHTML+'"';
									else
										cell3.innerHTML = ' <?php echo $sf_lang['SF_ANSWER_IS']?> "'+jQuery('#sf_field_data_a').get(0).options[jQuery('#sf_field_data_a').get(0).selectedIndex].innerHTML+'"';
								}else {
									cell3.innerHTML = ' <?php echo $sf_lang['SF_RANK_IS']?> "'+jQuery('#sf_field_data_a').get(0).options[jQuery('#sf_field_data_a').get(0).selectedIndex].innerHTML+'"';
								}
							} else {
								cell2.innerHTML = ' <?php echo $sf_lang['SF_ANSWER_IS']?> "'+jQuery('#sf_field_data_m').get(0).options[jQuery('#sf_field_data_m').get(0).selectedIndex].innerHTML+'"';	
							}
							
							cell4.innerHTML = '<a href="javascript: void(0);" onclick="javascript:Delete_row(this); return false;" title="Delete"><img src="/administrator/images/publish_x.png"  border="0" alt="Delete"></a>';							
							row.appendChild(cell1);
							row.appendChild(cell2);							
							row.appendChild(cell3);
							row.appendChild(cell4);						
						}
						function processReq(http_request) {
							if (http_request.readyState == 4) {
								if ((http_request.status == 200)) {									
									var response = http_request.responseXML.documentElement;
									var text = 'Request Error';
									try {
										text = response.getElementsByTagName('data')[0].firstChild.data;
									} catch(e) {}
									jQuery('div#quest_show_div').html(text);							
								}
							}
						}
						function showOptions(val) {
							
							jQuery('input#add_button').get(0).style.display = 'none';
							
							jQuery('div#quest_show_div').html("Please wait... Loading...");
							
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

$live_url = $live_site_parts['scheme'].'://'.$live_site_parts['host'].(isset($live_site_parts['path'])?$live_site_parts['path']:'/');

if ( substr($live_url, strlen($live_url)-1, 1) !== '/')
	$live_url .= '/';
?>
							http_request.open('GET', '<?php echo $live_url;?>index.php?no_html=1&option=com_surveyforce&task=get_options&rand=<?php echo time();?>&quest_id='+val, true);
							http_request.send(null);						
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
			<?php } ?>
		</table>
		<br />
		<input type="hidden" name="sf_impscale" value="0" />
		<input type="hidden" name="sf_compulsory" value="0" />
		<input type="hidden" name="sf_qtype" value="7" />
		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="id" value="<?php echo $row->id;?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="Itemid" value="<?php echo $Itemid?>" />
		</form><br/><br/>
		</div>
		<?php
	}
	
	function SF_editQ_Likert_PickOneMany( &$row, &$lists, $option, $q_om_type ) {
		global $task, $Itemid,$Itemid_s, $my, $sf_lang, $mosConfig_live_site, $_MAMBOTS;
		$owner = SF_GetUserType($my->id, $lists['survid']) == 1;
		$sf_config = new mos_Survey_Force_Config( );
		if (_JOOMLA15) {
			jimport( 'joomla.html.editor' );
	
			$conf =& JFactory::getConfig();
			$editor = $conf->getValue('config.editor');
			$editorz =& JEditor::getInstance($editor);
			$editorz =& JFactory::getEditor();
		} 
		?>
		<script type="text/javascript" src="/media/system/js/core.js"></script>
		<script type="text/javascript" src="components/com_surveyforce/overlib_mini.js"></script>
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
						tbl_elem.rows[i].cells[3].innerHTML = '<a href="javascript: void(0);" onClick="javascript:Up_tbl_row(this); return false;" title="Move Up"><img src="components/com_surveyforce/images/toolbar/btn_uparrow.png" width="16" height="16" border="0" alt="Move Up"></a>';
					} else { tbl_elem.rows[i].cells[3].innerHTML = ''; }
					if (i < (tbl_elem.rows.length - 1)) {
						tbl_elem.rows[i].cells[4].innerHTML = '<a href="javascript: void(0);" onClick="javascript:Down_tbl_row(this); return false;" title="Move Down"><img src="components/com_surveyforce/images/toolbar/btn_downarrow.png" width="16" height="16" border="0" alt="Move Down"></a>';;
					} else { tbl_elem.rows[i].cells[4].innerHTML = ''; }
					tbl_elem.rows[i].className = 'row'+row_k;
					count++;
					row_k = 1 - row_k;
				}
			}
			<?php if ($q_om_type == 1 && $row->id || $q_om_type != 1) {?>
				Add_fields_to_select();
			<?php } ?>	
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
				
				cell3.innerHTML = '<a href="javascript: void(0);" onClick="javascript:Delete_tbl_row(this); return false;" title="Delete"><img src="components/com_surveyforce/images/publish_x.png"  border="0" alt="Delete"></a>';
				cell4.innerHTML = '<a href="javascript: void(0);" onClick="javascript:Up_tbl_row(this); return false;" title="Move Up"><img src="components/com_surveyforce/images/toolbar/btn_uparrow.png"  border="0" alt="Move Up"></a>';
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
				
				cell3.innerHTML = '<a href="javascript: void(0);" onClick="javascript:Delete_tbl_row(this); return false;" title="Delete"><img src="components/com_surveyforce/images/publish_x.png"  border="0" alt="Delete"></a>';
				cell4.innerHTML = '<a href="javascript: void(0);" onClick="javascript:Up_tbl_row(this); return false;" title="Move Up"><img src="components/com_surveyforce/images/toolbar/btn_uparrow.png"  border="0" alt="Move Up"></a>';
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
				alert("<?php echo $sf_lang['SF_ALERT_ENTER_TEXT']?>");return;
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
			cell3.innerHTML = '<a href="javascript: void(0);" onClick="javascript:Delete_tbl_row(this); return false;" title="Delete"><img src="components/com_surveyforce/images/publish_x.png" width="12" height="12" border="0" alt="Delete"></a>';
			cell4.innerHTML = '<a href="javascript: void(0);" onClick="javascript:Up_tbl_row(this); return false;" title="Move Up"><img src="components/com_surveyforce/images/toolbar/btn_uparrow.png" width="16" height="16" border="0" alt="Move Up"></a>';
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
				var count = start_index; var row_k = 2 - start_index%2;
				for (var i=start_index; i<tbl_elem.rows.length; i++) {
					tbl_elem.rows[i].cells[0].innerHTML = count;
					Redeclare_element_inputs(tbl_elem.rows[i].cells[1]);
					Redeclare_element_inputs(tbl_elem.rows[i].cells[2]);
					tbl_elem.rows[i].className = 'sectiontableentry'+row_k;
					count++;
					row_k = 3 - row_k;
				}
			}
		}
<?php if ($q_om_type != 1) {?>
		function Add_new_tbl_field2(elem_field, tbl_id, field_name, elem_field2, field_name2) {
			var new_element_txt = getObj(elem_field).value;
			var new_element_txt2 = getObj(elem_field2).value;
			var new_element_txt2_text = getObj(elem_field2).options[getObj(elem_field2).selectedIndex].innerHTML;
			if (TRIM_str(new_element_txt) == '') {
				alert("<?php echo $sf_lang['SF_ALERT_ENTER_TEXT']?>");return;
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
			cell3.innerHTML = '<a href="javascript: void(0);" onClick="javascript:Delete_tbl_row2(this); return false;" title="Delete"><img src="components/com_surveyforce/images/publish_x.png" width="12" height="12" border="0" alt="Delete"></a>';
			cell3b.innerHTML = '<input type="text" style="text-align:center" class="inputbox" name="priority[]" size="3" value="'+getObj('new_priority').value+'" />';
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
			try {
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
			} catch(e){}
		}
<?php } elseif ($q_om_type == 1 && $row->id) { ?>	
		
		function Add_new_tbl_field2(elem_field, elem_field3, tbl_id, field_name, elem_field2, field_name2, field_name3) {
			var new_element_txt = getObj(elem_field).value;
			var new_element_txt2 = getObj(elem_field2).value;
			var new_element_txt3 = getObj(elem_field3).value;
			var new_element_txt2_text = getObj(elem_field2).options[getObj(elem_field2).selectedIndex].innerHTML;
			var new_element_txt3_text = getObj(elem_field3).options[getObj(elem_field3).selectedIndex].innerHTML;
			if (TRIM_str(new_element_txt) == '') {
				alert("Please enter text to the field.");return;
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
			cell3.innerHTML = '<a href="javascript: void(0);" onClick="javascript:Delete_tbl_row2(this); return false;" title="Delete"><img src="components/com_surveyforce/images/publish_x.png" width="12" height="12" border="0" alt="Delete"></a>';
			cell3b.innerHTML = '<input type="text" style="text-align:center" class="inputbox" name="priority[]" size="3" value="'+getObj('new_priority').value+'" />';
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
<?php } ?>

		function submitbutton(pressbutton) {
			var form = document.adminForm;
			if (pressbutton == 'cancel_quest') {
				submitform( pressbutton );
				return;
			}
			
			fillTextArea();
			// do field validation
			if (false && form.sf_qtext.value == ""){
				alert( "<?php echo $sf_lang['SF_ALERT_QUEST_MUST_HAVE_TEXT']?>" );
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
		<div class="contentpane surveyforce">
		<form action="<?php echo SFRoute("index.php?option=$option{$Itemid_s}")?>" method="post" name="adminForm" id="adminForm">
		<table width="100%" cellpadding="0" cellspacing="0" border="0"  >
			<tr>
				<td width="auto">
					<table width="100%" cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td width="48px">
								<?php echo SF_showHeadPicture('surveys');?>
							</td>
							<td class="contentheading" width="100%" valign="middle" style="vertical-align:middle ">
								&nbsp;<?php echo $row->id ? $sf_lang['SF_EDIT_QUEST'] : $sf_lang['SF_NEW_QUEST']; echo ' ('.(($q_om_type == 1)?$sf_lang['SF_LIKERT_SCALE']:((($q_om_type == 2)?$sf_lang['SF_PICK_ONE']:$sf_lang['SF_PICK_MANY'])) ) .')';?>
							</td>
							<td align="right" valign="top" style="vertical-align:top ">
								<?php SF_showTopMenu();?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td align="center" style="text-align:center">
				<span id="SF_toolbar_tooltip">&nbsp;</span><br/>
				<?php $toolbar = array();
					if ($owner) {
					  $toolbar[] = array('btn_type' => 'save', 'btn_js' => "javascript:submitbutton('save_quest');");
					  $toolbar[] = array('btn_type' => 'apply', 'btn_js' => "javascript:submitbutton('apply_quest');");
					}
					  $toolbar[] = array('btn_type' => 'cancel', 'btn_js' => "javascript:submitbutton('cancel_quest');"); 
					  echo ShowToolbar($toolbar); 
				?>
				</td>
			</tr>
			<tr>
				<td width="100%">
				<table width="100%"><tr><td align="left">&nbsp;</td>
				<td align="right">&nbsp;</td>
				</tr>
				</table>
				</td>
			</tr>
		</table>		
		<table width="100%" cellpadding="2" cellspacing="0" border="0"  >
			<tr>
				<td colspan="2" class="sectiontableheader"><?php echo $sf_lang['SF_QUEST_DETAILS']?></td>
			</tr>
			<tr>
				<td align="left" valign="top" colspan="2"><?php echo $sf_lang['SF_QUEST_TEXT']?>:</td>
				</tr>
				<tr>
				<td colspan="2"  align="left"><?php 
					if ($owner) 
						SF_editorArea( 'editor2', $row->sf_qtext, 'sf_qtext', '100%;', '250', '40', '20' ) ; 
					else 
						echo $row->sf_qtext;
					?>
				</td>
			</tr>
			<tr>
				<td><?php echo $sf_lang['SF_SURVEY']?>:</td><td><?php echo $lists['survey']; ?></td>
			</tr>
			<?php if (!$sf_config->get('sf_enable_jomsocial_integration')) { ?>
			<tr>
				<td><?php echo $sf_lang['SF_IMP_SCALE']?>:</td><td><?php echo $lists['impscale'];?><?php if ($owner) {?><input type="button" class="button" name="Define new" onClick="javascript: fillTextArea();document.adminForm.task.value='add_iscale_from_quest';document.adminForm.submit();" value="<?php echo $sf_lang['SF_DEFINE_NEW']?>"><?php }?></td>
			</tr>
			<?php } ?>
			<tr>
				<td>
					<?php echo $sf_lang["SF_PUBLISHED"];?>:
				</td>
				<td>
					<?php echo $lists['published']; ?>
				</td>
			</tr>
			<tr>
				<td><?php echo $sf_lang['SF_ORDERING']?>:</td><td><?php echo $lists['ordering']; ?></td>
			</tr> 
			<?php if ( $lists['sf_section_id'] != null ) {?>
			<tr>
				<td><?php echo $sf_lang['SF_SECTION']?>:</td><td><?php echo $lists['sf_section_id'];?></td>
			</tr> 
			<?php }?>
			<tr>
				<td><?php echo $sf_lang['SF_COMPULSORY']?>:</td>
				<td>
					<?php echo $lists['compulsory']; ?>				
				</td>
			</tr> 
			<?php if ($q_om_type == 1) {?>
			<tr>
				<td>
					<?php echo $sf_lang['SF_FACTOR_NAME'];?>:
				</td>
				<td>
					<input id="sf_fieldtype" class="inputbox" style="width:120px " type="text" name="sf_fieldtype" value="<?php echo $row->sf_fieldtype?>">
				</td>
			</tr>
			<?php } 
			if ($q_om_type == 2) { ?>
			<tr>
				<td>
					<?php echo $sf_lang['SF_USE_DROP_DOWN'];?>:
				</td>
				<td>
					<?php echo $lists['use_drop_down']; ?>
				</td>
			</tr>
			<?php }?>
			<?php if (!($row->id > 0)) {?>
			<tr>
				<td><?php echo $sf_lang['SF_INSERT_PAGE_BREAK']?>:
				</td>
				<td>
					<?php echo $lists['insert_pb']; ?>
				</td>
			</tr>
			<?php } ?>
			<?php if (!$sf_config->get('sf_enable_jomsocial_integration')) { ?>
			<tr>
				<td colspan="2">
					<script type="text/javascript" src="<?php echo $mosConfig_live_site;?>/administrator/components/com_surveyforce/js/jquery.pack.js"></script>
					<script type="text/javascript" language="javascript" >
						jQuery.noConflict();
						var sf_is_loading = false;
					</script>
					<table class="adminlist" id="show_quest">
					<tr>
						<th class="title" colspan="4"><?php echo $sf_lang['SF_DONT_SHOW']?>:</th>
					</tr>
					<?php if (is_array($lists['quest_show']) && count($lists['quest_show'])) 
							foreach($lists['quest_show'] as $rule) {
								if ( ($rule->sf_qtype == 2) || ($rule->sf_qtype == 3) ) {
							?>
							
							<tr>
								<td width="375px;"> <?php echo $sf_lang['SF_FOR_QUESTION']?> "<?php echo $rule->sf_qtext;?>" <input type="hidden" name="sf_hid_rule2_id[]" value="<?php echo $rule->bid;?>" /><input type="hidden" name="sf_hid_rule2_alt_id[]" value="<?php echo $rule->did;?>" /><input type="hidden" name="sf_hid_rule2_quest_id[]" value="<?php echo $rule->qid;?>" /></td>
								<td colspan="2"> <?php echo $sf_lang['SF_ANSWER_IS']?> "<?php echo $rule->qoption;?>"</td>
								<td><a href="javascript: void(0);" onclick="javascript:Delete_row(this); return false;" title="Delete"><img src="/administrator/images/publish_x.png"  border="0" alt="Delete"></a></td>
							</tr>
							<?php } elseif (($rule->sf_qtype == 1) || ($rule->sf_qtype == 5) || ($rule->sf_qtype == 6)) {?>
							<tr>
								<td  width="375px;"> <?php echo $sf_lang['SF_FOR_QUESTION']?> "<?php echo $rule->sf_qtext;?>"<input type="hidden" name="sf_hid_rule2_id[]" value="<?php echo $rule->bid;?>" /><input type="hidden" name="sf_hid_rule2_alt_id[]" value="<?php echo ($rule->sf_qtype == 1?$rule->sdid:$rule->fdid);?>" /><input type="hidden" name="sf_hid_rule2_quest_id[]" value="<?php echo $rule->qid;?>" /></td>
								<td> <?php echo $sf_lang['SF_AND_OPTION']?> "<?php echo $rule->qoption;?>"</td>
								<td> <?php echo $sf_lang['SF_ANSWER_IS']?> "<?php echo ($rule->sf_qtype == 1?$rule->astext:$rule->aftext);?>"</td>
								<td><a href="javascript: void(0);" onclick="javascript:Delete_row(this); return false;" title="Delete"><img src="/administrator/images/publish_x.png"  border="0" alt="Delete"></a></td>
							</tr>
							<?php } elseif ($rule->sf_qtype == 9) {?>
							<tr >
								<td  width="375px;"> <?php echo $sf_lang['SF_FOR_QUESTION']?> "<?php echo $rule->sf_qtext;?>"<input type="hidden" name="sf_hid_rule2_id[]" value="<?php echo $rule->bid;?>" /><input type="hidden" name="sf_hid_rule2_alt_id[]" value="<?php echo $rule->did;?>" /><input type="hidden" name="sf_hid_rule2_quest_id[]" value="<?php echo $rule->qid;?>" /></td>
								<td> <?php echo $sf_lang['SF_AND_OPTION']?> "<?php echo $rule->qoption;?>"</td>
								<td> <?php echo $sf_lang['SF_RANK_IS']?> "<?php echo $rule->aftext;?>"</td>
								<td><a href="javascript: void(0);" onclick="javascript:Delete_row(this); return false;" title="Delete"><img src="/administrator/images/publish_x.png"  border="0" alt="Delete"></a></td>
							</tr>	
							<?php }
							}?>
					</table>
					<table width="100%"  id="show_quest2">
					<tr>
						<td style="width:70px;"><?php echo $sf_lang['SF_FOR_QUESTION']?> </td><td style="width:15px;"><?php echo $lists['quests3'];?></td>
						<td width="auto" colspan="2" ><div id="quest_show_div"></div>						
						</td>
					</tr>							
					<tr>
						<td colspan="4" style="text-align:left;"><input id="add_button" type="button" name="add" value="<?php echo $sf_lang["SF_ADD"];?>" onclick="javascript: if(!sf_is_loading) addRow();"  />
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
							cell1.innerHTML = '<?php echo $sf_lang['SF_FOR_QUESTION']?> "'+jQuery('#sf_quest_list3').get(0).options[jQuery('#sf_quest_list3').get(0).selectedIndex].innerHTML+'"';
							cell1.appendChild(input_hidden);
							cell1.appendChild(input_hidden2);
							cell1.appendChild(input_hidden3);
							if (qtype != 2 && qtype != 3) {
								cell2.innerHTML = ' <?php echo $sf_lang['SF_AND_OPTION']?> "'+jQuery('#sf_field_data_m').get(0).options[jQuery('#sf_field_data_m').get(0).selectedIndex].innerHTML+'"';				
								if (qtype != 9){
									if (qtype == 1)
										cell3.innerHTML = ' <?php echo $sf_lang['SF_ANSWER_IS']?> "'+jQuery('#f_scale_data').get(0).options[jQuery('#f_scale_data').get(0).selectedIndex].innerHTML+'"';
									else
										cell3.innerHTML = ' <?php echo $sf_lang['SF_ANSWER_IS']?> "'+jQuery('#sf_field_data_a').get(0).options[jQuery('#sf_field_data_a').get(0).selectedIndex].innerHTML+'"';
								}else {
									cell3.innerHTML = ' <?php echo $sf_lang['SF_RANK_IS']?> "'+jQuery('#sf_field_data_a').get(0).options[jQuery('#sf_field_data_a').get(0).selectedIndex].innerHTML+'"';
								}
							} else {
								cell2.innerHTML = ' <?php echo $sf_lang['SF_ANSWER_IS']?> "'+jQuery('#sf_field_data_m').get(0).options[jQuery('#sf_field_data_m').get(0).selectedIndex].innerHTML+'"';	
							}
							
							cell4.innerHTML = '<a href="javascript: void(0);" onclick="javascript:Delete_row(this); return false;" title="Delete"><img src="/administrator/images/publish_x.png"  border="0" alt="Delete"></a>';							
							row.appendChild(cell1);
							row.appendChild(cell2);							
							row.appendChild(cell3);
							row.appendChild(cell4);						
						}
						function processReq(http_request) {
							if (http_request.readyState == 4) {
								if ((http_request.status == 200)) {									
									var response = http_request.responseXML.documentElement;
									var text = 'Request Error';
									try {
										text = response.getElementsByTagName('data')[0].firstChild.data;
									} catch(e) {}
									jQuery('div#quest_show_div').html(text);							
								}
							}
						}
						function showOptions(val) {
							
							jQuery('input#add_button').get(0).style.display = 'none';
							
							jQuery('div#quest_show_div').html("Please wait... Loading...");
							
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

$live_url = $live_site_parts['scheme'].'://'.$live_site_parts['host'].(isset($live_site_parts['path'])?$live_site_parts['path']:'/');

if ( substr($live_url, strlen($live_url)-1, 1) !== '/')
	$live_url .= '/';
?>
							http_request.open('GET', '<?php echo $live_url;?>index.php?no_html=1&option=com_surveyforce&task=get_options&rand=<?php echo time();?>&quest_id='+val, true);
							http_request.send(null);						
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
			<?php }?>
		</table>
		<br />
		<?php if ($q_om_type == 1) {?>
		<table width="100%" cellpadding="2" cellspacing="0" border="0"  >
		<tr><td width="20%">
				<input type="radio" name="is_likert_predefined" value="1" <?php echo ($row->is_likert_predefined == 1)?'checked':''?>> <?php echo $sf_lang['SF_USE_PREDEF_SCALE']?>:
			</td>
			<td><?php echo $lists['likert_scale']; ?>
			</td>
		</tr><tr><td>
			<input type="radio" name="is_likert_predefined" value="0" <?php echo ($row->is_likert_predefined == 0)?'checked':''?>> <?php echo $sf_lang['SF_DEF_SCALE']?>:
			</td><td>
		</td></tr></table>
		<br>
		<table width="100%" cellpadding="2" cellspacing="0" border="0"   id="qfld_tbl_scale">
		<tr>
			<td width="20px" align="center" class="sectiontableheader">#</td>
			<td class="sectiontableheader" width="200px"><?php echo $sf_lang['SF_SCALE_OPTION']?></td>
			<td width="20px" align="center" class="sectiontableheader"></td>
			<td width="20px" align="center" class="sectiontableheader"></td>
			<td width="20px" align="center" class="sectiontableheader"></td>
			<td width="auto" class="sectiontableheader"></td>
		</tr>
			<?php
			$k = 1; $ii = 1; $ind_last = count($lists['sf_fields_scale']);
			foreach ($lists['sf_fields_scale'] as $frow) { 
			?>	<input type="hidden" name="old_sf_hid_scale_id[]" value="<?php echo $frow->id?>">
				<tr class="<?php echo "sectiontableentry$k"; ?>">
					<td align="center"><?php echo $ii?></td>
					<td align="left"  ondblclick="edit_name(event, 'sf_hid_scale[]', 'sf_hid_scale_id[]');"><input type="hidden" name="sf_hid_scale[]" value="<?php echo $frow->stext?>"><input type="hidden" name="sf_hid_scale_id[]" value="<?php echo $frow->id?>">
						<?php echo $frow->stext?>
						
					</td>
					<td><a href="javascript: void(0);" onClick="javascript:Delete_tbl_row(this); return false;" title="Delete"><img src="components/com_surveyforce/images/publish_x.png" width="12" height="12" border="0" alt="Delete"></a></td>
					<td><?php if ($ii > 1) { ?><a href="javascript: void(0);" onClick="javascript:Up_tbl_row(this); return false;" title="Move Up"><img src="components/com_surveyforce/images/toolbar/btn_uparrow.png" width="16" height="16" border="0" alt="Move Up"></a><?php } ?></td>
					<td><?php if ($ii < $ind_last) { ?><a href="javascript: void(0);" onClick="javascript:Down_tbl_row(this); return false;" title="Move Down"><img src="components/com_surveyforce/images/toolbar/btn_downarrow.png" width="16" height="16" border="0" alt="Move Down"></a><?php } ?></td>
					<td></td>
				</tr>
			<?php
			$k = 3 - $k; $ii ++;
			 } ?>
		 </table><br>
		<?php if ($owner) {?>
		<div style="text-align:left; padding-left:30px ">
			<input id="new_scale" class="inputbox" style="width:205px " type="text" name="new_scale">
			<input class="button" type="button" name="add_new_scale"  value="<?php echo $sf_lang['SF_ADD']?>" onClick="javascript:Add_new_tbl_field('new_scale', 'qfld_tbl_scale', 'sf_hid_scale[]');">
		</div>
		<?php }?>
		<br />
		<?php } ?>

		<table width="100%" cellpadding="2" cellspacing="0" border="0"   id="qfld_tbl">
		<tr>
			<td class="sectiontableheader" width="20px" align="center">#</td>
			<td class="sectiontableheader" width="200px"><?php echo $sf_lang['SF_QUEST_OPTION']?></td>
			<td width="20px" align="center" class="sectiontableheader"></td>
			<td width="20px" align="center" class="sectiontableheader"></td>
			<td width="20px" align="center" class="sectiontableheader"></td>
			<td width="auto" class="sectiontableheader"></td>
		</tr>
		<?php
		$k = 1; $ii = 1; $ind_last = count($lists['sf_fields']);
		$other_option = null;
		foreach ($lists['sf_fields'] as $frow) { 
			if (isset($frow->is_main) && $frow->is_main == 0) {
				$other_option = $frow;
				continue;
			}
		?>
			<input type="hidden" name="old_sf_hid_field_ids[]" value="<?php echo $frow->id?>"/>
			<tr class="<?php echo "sectiontableentry$k"; ?>">
				<td align="center"><?php echo $ii?></td>
				<td align="left" onDblClick="edit_name(event, 'sf_hid_fields[]', 'sf_hid_field_ids[]');"><input type="hidden" name="sf_hid_fields[]" value="<?php echo $frow->ftext?>"/><input type="hidden" name="sf_hid_field_ids[]" value="<?php echo $frow->id?>"/>
					<?php echo $frow->ftext?>
					
				</td>
				<td><a href="javascript: void(0);" onClick="javascript:Delete_tbl_row(this); return false;" title="Delete"><img src="components/com_surveyforce/images/publish_x.png" width="12" height="12" border="0" alt="Delete"></a></td>
				<td><?php if ($ii > 1) { ?><a href="javascript: void(0);" onClick="javascript:Up_tbl_row(this); return false;" title="Move Up"><img src="components/com_surveyforce/images/toolbar/btn_uparrow.png" width="16" height="16" border="0" alt="Move Up"></a><?php } ?></td>
				<td><?php if ($ii < $ind_last) { ?><a href="javascript: void(0);" onClick="javascript:Down_tbl_row(this); return false;" title="Move Down"><img src="components/com_surveyforce/images/toolbar/btn_downarrow.png" width="16" height="16" border="0" alt="Move Down"></a><?php } ?></td>
				<td></td>
			</tr>
		<?php
		$k = 3 - $k; $ii ++;
		 } ?>
		</table>
		<?php 
		if ($q_om_type == 3) { ?>
		<table width="100%" class="adminlist" >
			<tr>
				<td align="right" width="15%" valign="top">Max number of checked options:<br /><small>Set `0` if there is no maximum</small></td>
				<td><input type="text" class="text_area" size="4" name="sf_num_options" value="<?php echo $row->sf_num_options;?>"/>
				</td>
			</tr>
		</table>
		<?php }
		
		if ($q_om_type == 2 || $q_om_type == 3) {?>
		<table width="100%" class="adminlist" >
		<tr class="<?php echo "row$k"; ?>">
			<td width="20px" align="center"><input type="checkbox" onchange="javascript:Add_fields_to_select();" name="other_option_cb" id="other_option_cb" value="2"  <?php echo (($other_option != null && !isset($lists['other_option'])) || (isset($lists['other_option']) && $lists['other_option'] == 1) ?'checked="checked"':'')?> /></td>
			<td align="left" colspan="5"><?php echo $sf_lang['SF_OTHER_OPTION'];?> <input onkeyup="javascipt: Add_fields_to_select();"  class="inputbox" style="width:120px " type="text" name="other_option" id="other_option" value="<?php echo ($other_option == null?'Other':$other_option->ftext)?>">		
			<input type="hidden" name="other_op_id" value="<?php echo ($other_option == null?'0':$other_option->id)?>"/>
			</td>
		</tr>
		</table>
		<?php }?>
		<br>
		<?php if ($owner) {?>
		<div style="text-align:left; padding-left:30px ">
			<input id="new_field" class="inputbox" style="width:205px " type="text" name="new_field">
			<input class="button" type="button" name="add_new_field" value="<?php echo $sf_lang['SF_ADD']?>" onClick="javascript:Add_new_tbl_field('new_field', 'qfld_tbl', 'sf_hid_fields[]', 'sf_hid_field_ids[]');">
			<?php if (!$sf_config->get('sf_enable_jomsocial_integration')) { ?>
			<br/><br/>
			<input class="button" type="button" name="set_default" value="<?php echo $sf_lang['SF_SET_DEFAULT']?>" onClick="javascript: <?php echo ($row->id > 0?"submitbutton('set_default');":"alert('{$sf_lang['SF_YOU_VAN_SET_DEFAULT_AFTER_SAVING']}');")?>">
			<?php }?>
		</div>
		<?php }?>
		<?php if (!$sf_config->get('sf_enable_jomsocial_integration')) { ?>
		<br />
		<table width="100%" cellpadding="2" cellspacing="0" border="0"  >
		<tr>
			<td width="20px" align="center" class="sectiontableheader">#</td>
			<td class="sectiontableheader" width="200px"><?php echo $sf_lang['SF_QUEST_RULES']?></td>
			<td width="20px" align="center" class="sectiontableheader"></td>
			<td width="20px" align="center" class="sectiontableheader"></td>
			<td width="20px" align="center" class="sectiontableheader"></td>
			<td width="auto" class="sectiontableheader"></td>
		</tr></table>
		<?php if ($q_om_type == 1 && $row->id || $q_om_type != 1) {?>
		<table width="100%" cellpadding="2" cellspacing="0" border="0"   id="qfld_tbl_rule">
		<tr>
			<td class="sectiontableheader" width="2%" align="center">#</td>
			<?php if ($q_om_type == 1) { ?>
			<td class="sectiontableheader" width="22%"><?php echo $sf_lang["SF_QUEST_OPTION"]?></td>
			<?php }?>
			<td class="sectiontableheader" width="22%"><?php echo $sf_lang['SF_ANSWER']?></td>
			<td class="sectiontableheader" width="22%"><?php echo $sf_lang['SF_QUESTION']?></td>
			<td class="sectiontableheader" width="22%"><?php echo $sf_lang['SF_PRIORITY_C']?></td>
			<td width="2%" align="center" class="sectiontableheader"></td>
			<td width="2%" align="center" class="sectiontableheader"></td>
			<td width="auto" class="sectiontableheader"></td>
			<?php if ($q_om_type != 1) { ?>
			<td width="auto" class="sectiontableheader"></td>
			<?php }?>
		</tr>
			<?php
			$k = 1; $ii = 1; $ind_last = count($lists['sf_fields_rule']);
			foreach ($lists['sf_fields_rule'] as $rrow) { ?>
				<tr class="<?php echo "sectiontableentry$k"; ?>">
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
						<?php echo $rrow->next_quest_id . ' - ' . (strlen(strip_tags($rrow->sf_qtext)) > 50? substr(strip_tags($rrow->sf_qtext), 0, 50).'...': strip_tags($rrow->sf_qtext))?>
						<input type="hidden" name="sf_hid_rule_quest[]" value="<?php echo $rrow->next_quest_id?>">
					</td>
					<td>
						<input type="text" style="text-align:center" class="inputbox" name="priority[]" size="3" value="<?php echo $rrow->priority?>" />
					</td>
					<td><a href="javascript: void(0);" onClick="javascript:Delete_tbl_row2(this); return false;" title="Delete"><img src="components/com_surveyforce/images/publish_x.png" width="12" height="12" border="0" alt="Delete"></a></td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<?php if ($q_om_type != 1) { ?>	
					<td>&nbsp;</td>
					<?php }?>
				</tr>
			<?php
			$k = 3 - $k; $ii ++;
			 } ?>
		 </table><br>
		<?php if ($owner) {?>
		
			<div style="text-align:left; padding-left:30px ">
		<input type="checkbox" name="super_rule" value="1" <?php echo $lists['checked']; ?> /><?php echo $sf_lang['SF_GO_TO_QUEST21'];?> <?php echo $lists['quests2']; ?> <?php echo $sf_lang['SF_GO_TO_QUEST22'];?>
		</div><br />
			<div style="text-align:left; padding-left:10px ">
			<?php if ($q_om_type == 1) { ?>
				<?php echo $sf_lang['SF_IF_FOR']?> <?php echo $lists['sf_list_fields']; ?> <?php echo $sf_lang['SF_ANSWER_IS']?> <?php echo $lists['sf_list_scale_fields']; ?>
			<?php } else {?>
				<?php echo $sf_lang['SF_IF_ANS_IS']?> <?php echo $lists['sf_list_fields']; ?>
			<?php }?>	
				, <?php echo $sf_lang['SF_GO_TO_QUEST']?> <?php echo $lists['quests']; ?>, <?php echo $sf_lang['SF_PRIORITY']?> <input type="text" style="text-align:center" class="inputbox" name="new_priority" id="new_priority" size="3" value="0" />
			<?php if ($q_om_type == 1) { ?>
				<input class="button" type="button" name="add_new_rule"  value="<?php echo $sf_lang['SF_ADD']?>" onClick="javascript:Add_new_tbl_field2('sf_field_list', 'sf_list_scale_fields', 'qfld_tbl_rule', 'sf_hid_rule[]', 'sf_quest_list', 'sf_hid_rule_quest[]', 'sf_hid_rule_alt[]');">
			<?php } else {?>	
				<input class="button" type="button" name="add_new_rule" value="<?php echo $sf_lang['SF_ADD']?>" onClick="javascript:Add_new_tbl_field2('sf_field_list', 'qfld_tbl_rule', 'sf_hid_rule[]', 'sf_quest_list', 'sf_hid_rule_quest[]');">
			<?php }?>	
			</div>
			<?php } ?>
			<br />
		<?php } 
		else
			echo "<div align='left'>".$sf_lang['SF_YOU_CAN_DEFINE_RULES_AFTER_SAVE']."</div><br/>";
		}
		?>
		<input type="hidden" name="sf_qtype" value="<?php echo $q_om_type; ?>" />
		<input type="hidden" name="option" value="com_surveyforce" />
		<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="Itemid" value="<?php echo $Itemid?>" />
		
		<input type="hidden" name="quest_id" value="<?php echo $row->id;?>" />
		<input type="hidden" name="red_task" value="<?php echo $task;?>" />
		</form><br/><br/>
		</div>
		<?php
	}
	
	function SF_editQ_Rankings( &$row, &$lists, $option, $q_rank_type ) {
		global $task, $Itemid, $Itemid_s, $my, $sf_lang, $mosConfig_live_site, $_MAMBOTS;
		$owner = SF_GetUserType($my->id, $lists['survid']) == 1;
		$sf_config = new mos_Survey_Force_Config( );
		if (_JOOMLA15) {
			jimport( 'joomla.html.editor' );
	
			$conf =& JFactory::getConfig();
			$editor = $conf->getValue('config.editor');
			$editorz =& JEditor::getInstance($editor);
			$editorz =& JFactory::getEditor();
		} 	
		?>
		<script type="text/javascript" src="/media/system/js/core.js"></script>
		<script type="text/javascript" src="components/com_surveyforce/overlib_mini.js"></script>
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
				var count = start_index; var row_k = 2 - start_index%2;
				for (var i=start_index; i<tbl_elem.rows.length; i++) {
					tbl_elem.rows[i].cells[0].innerHTML = count;					
					if (i > 1) { 
						tbl_elem.rows[i].cells[4].innerHTML = '<a href="javascript: void(0);" onClick="javascript:Up_tbl_row(this); return false;" title="Move Up"><img src="components/com_surveyforce/images/toolbar/btn_uparrow.png" width="16" height="16" border="0" alt="Move Up"></a>';
					} else { tbl_elem.rows[i].cells[4].innerHTML = ''; }
					if (i < (tbl_elem.rows.length - 1)) {
						tbl_elem.rows[i].cells[5].innerHTML = '<a href="javascript: void(0);" onClick="javascript:Down_tbl_row(this); return false;" title="Move Down"><img src="components/com_surveyforce/images/toolbar/btn_downarrow.png" width="16" height="16" border="0" alt="Move Down"></a>';;
					} else { tbl_elem.rows[i].cells[5].innerHTML = ''; }
					tbl_elem.rows[i].className = 'sectiontableentry'+row_k;
					count++;
					row_k = 3 - row_k;
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
				
				cell3.innerHTML = '<a href="javascript: void(0);" onClick="javascript:Delete_tbl_row(this); return false;" title="Delete"><img src="components/com_surveyforce/images/publish_x.png"  border="0" alt="Delete"></a>';
				cell4.innerHTML = '<a href="javascript: void(0);" onClick="javascript:Up_tbl_row(this); return false;" title="Move Up"><img src="components/com_surveyforce/images/toolbar/btn_uparrow.png"  border="0" alt="Move Up"></a>';
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
				
				cell3.innerHTML = '<a href="javascript: void(0);" onClick="javascript:Delete_tbl_row(this); return false;" title="Delete"><img src="components/com_surveyforce/images/publish_x.png"  border="0" alt="Delete"></a>';
				cell4.innerHTML = '<a href="javascript: void(0);" onClick="javascript:Up_tbl_row(this); return false;" title="Move Up"><img src="components/com_surveyforce/images/toolbar/btn_uparrow.png"  border="0" alt="Move Up"></a>';
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
				alert("<?php echo $sf_lang['SF_ALERT_ENTER_TEXT']?>");return;
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
			cell3.innerHTML = '<a href="javascript: void(0);" onClick="javascript:Delete_tbl_row(this); return false;" title="Delete"><img src="components/com_surveyforce/images/publish_x.png" width="12" height="12" border="0" alt="Delete"></a>';
			cell4.innerHTML = '<a href="javascript: void(0);" onClick="javascript:Up_tbl_row(this); return false;" title="Move Up"><img src="components/com_surveyforce/images/toolbar/btn_uparrow.png" width="16" height="16" border="0" alt="Move Up"></a>';
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
				var count = start_index; var row_k = 2 - start_index%2;
				for (var i=start_index; i<tbl_elem.rows.length; i++) {
					tbl_elem.rows[i].cells[0].innerHTML = count;
					Redeclare_element_inputs(tbl_elem.rows[i].cells[1]);
					Redeclare_element_inputs(tbl_elem.rows[i].cells[2]);
					tbl_elem.rows[i].className = 'sectiontableentry'+row_k;
					count++;
					row_k = 3 - row_k;
				}
			}
		}

		function Add_new_tbl_field2(elem_field, elem_field3, tbl_id, field_name, elem_field2, field_name2, field_name3) {
			var new_element_txt = getObj(elem_field).value;
			var new_element_txt2 = getObj(elem_field2).value;
			var new_element_txt3 = getObj(elem_field3).value;
			var new_element_txt2_text = getObj(elem_field2).options[getObj(elem_field2).selectedIndex].innerHTML;
			var new_element_txt3_text = getObj(elem_field3).options[getObj(elem_field3).selectedIndex].innerHTML;
			if (TRIM_str(new_element_txt) == '') {
				alert("<?php echo $sf_lang['SF_ALERT_ENTER_TEXT']?>");return;
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
			cell3.innerHTML = '<a href="javascript: void(0);" onClick="javascript:Delete_tbl_row2(this); return false;" title="Delete"><img src="components/com_surveyforce/images/publish_x.png" width="12" height="12" border="0" alt="Delete"></a>';
			cell3b.innerHTML = '<input type="text" style="text-align:center" class="inputbox" name="priority[]" size="3" value="'+getObj('new_priority').value+'" />';
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
			try{
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
			} catch(e){}
		}
	
		function submitbutton(pressbutton) {
			var form = document.adminForm;
			if (pressbutton == 'cancel_quest') {
				submitform( pressbutton );
				return;
			}
			
			fillTextArea();
			// do field validation
			if (false && form.sf_qtext.value == ""){
				alert( "<?php echo $sf_lang["SF_ALERT_QUEST_MUST_HAVE_TEXT"]?>" );
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
		<div class="contentpane surveyforce">
		<form action="<?php echo SFRoute("index.php?option=$option{$Itemid_s}")?>" method="post" name="adminForm" id="adminForm">
		<table width="100%" cellpadding="0" cellspacing="0" border="0"  >
			<tr>
				<td width="auto">
					<table width="100%" cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td width="48px">
								<?php echo SF_showHeadPicture('surveys');?>
							</td>
							<td class="contentheading" width="100%" valign="middle" style="vertical-align:middle ">
								&nbsp;<?php echo $row->id ? $sf_lang["SF_EDIT_QUEST"] : $sf_lang["SF_NEW_QUEST"]; echo ' ('.(($q_rank_type == 5)?$sf_lang["SF_RANK_DROPDOWN"]:$sf_lang["SF_RANK_DRAGDROP"]).')';?>
							</td>
							<td align="right" valign="top" style="vertical-align:top ">
								<?php SF_showTopMenu();?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td align="center" style="text-align:center">
				<span id="SF_toolbar_tooltip">&nbsp;</span><br/>
				<?php $toolbar = array();
					if ($owner) {
					  $toolbar[] = array('btn_type' => 'save', 'btn_js' => "javascript:submitbutton('save_quest');");
					  $toolbar[] = array('btn_type' => 'apply', 'btn_js' => "javascript:submitbutton('apply_quest');");
					}
					  $toolbar[] = array('btn_type' => 'cancel', 'btn_js' => "javascript:submitbutton('cancel_quest');"); 
					  echo ShowToolbar($toolbar); 
				?>
				</td>
			</tr>
			<tr>
				<td width="100%">
				<table width="100%"><tr><td align="left">&nbsp;</td>
				<td align="right">&nbsp;</td>
				</tr>
				</table>
				</td>
			</tr>
		</table>
		
		<table width="100%" cellpadding="2" cellspacing="0" border="0"  >
			<tr>
				<td colspan="2" class="sectiontableheader"><?php echo $sf_lang["SF_QUEST_DETAILS"]?></td>
			</tr>
			<tr>
				<td colspan="2" align="left" width="20%" valign="top"><?php echo $sf_lang["SF_QUEST_TEXT"]?>:</td>
			</tr>
			<tr>
				<td colspan="2"  align="left"><?php 
					if ($owner) 
						SF_editorArea( 'editor2', $row->sf_qtext, 'sf_qtext', '100%;', '250', '40', '20' ) ; 
					else
						echo $row->sf_qtext;?>
				</td>
			</tr>
			<tr>
				<td><?php echo $sf_lang["SF_SURVEY"]?>:</td>
				<td>
				<?php echo $lists['survey']; ?>
				</td>
			</tr>
			<?php if (!$sf_config->get('sf_enable_jomsocial_integration')) { ?>
			<tr>
				<td><?php echo $sf_lang["SF_IMP_SCALE"]?>:</td><td><?php echo $lists['impscale'];?><?php if ($owner) {?><input type="button" class="button" name="Define new" onClick="javascript: fillTextArea();document.adminForm.task.value='add_iscale_from_quest';document.adminForm.submit();" value="<?php echo $sf_lang["SF_DEFINE_NEW"]?>"><?php }?></td>
			</tr>
			<?php } ?>
			<tr>
				<td>
					<?php echo $sf_lang["SF_PUBLISHED"];?>:
				</td>
				<td>
					<?php echo $lists['published']; ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo $sf_lang["SF_ORDERING"]?>:
				</td>
				<td>
					<?php echo $lists['ordering']; ?>
				</td>
			</tr> 
			<?php if ( $lists['sf_section_id'] != null ) {?>
			<tr>
				<td><?php echo $sf_lang['SF_SECTION']?>:</td><td><?php echo $lists['sf_section_id'];?></td>
			</tr> 
			<?php }?>
			<tr>
				<td>
					<?php echo $sf_lang["SF_COMPULSORY"]?>:
				</td>
				<td>
					<?php echo $lists['compulsory']; ?>
				</td>
			</tr>
			<?php if (!($row->id > 0)) {?>
			<tr>
				<td>
					<?php echo $sf_lang["SF_INSERT_PAGE_BREAK"]?>:
				</td>
				<td>
					<?php echo $lists['insert_pb']; ?>
				</td>
			</tr>
			<?php } ?> 
			<?php if (!$sf_config->get('sf_enable_jomsocial_integration')) { ?>
			<tr>
				<td colspan="2">
					<script type="text/javascript" src="<?php echo $mosConfig_live_site;?>/administrator/components/com_surveyforce/js/jquery.pack.js"></script>
					<script type="text/javascript" language="javascript" >
						jQuery.noConflict();
						var sf_is_loading = false;
					</script>
					<table class="adminlist" id="show_quest">
					<tr>
						<th class="title" colspan="4"><?php echo $sf_lang['SF_DONT_SHOW']?>:</th>
					</tr>
					<?php if (is_array($lists['quest_show']) && count($lists['quest_show'])) 
							foreach($lists['quest_show'] as $rule) {
								if ( ($rule->sf_qtype == 2) || ($rule->sf_qtype == 3) ) {
							?>
							
							<tr>
								<td width="375px;"> <?php echo $sf_lang['SF_FOR_QUESTION']?> "<?php echo $rule->sf_qtext;?>" <input type="hidden" name="sf_hid_rule2_id[]" value="<?php echo $rule->bid;?>" /><input type="hidden" name="sf_hid_rule2_alt_id[]" value="<?php echo $rule->did;?>" /><input type="hidden" name="sf_hid_rule2_quest_id[]" value="<?php echo $rule->qid;?>" /></td>
								<td colspan="2"> <?php echo $sf_lang['SF_ANSWER_IS']?> "<?php echo $rule->qoption;?>"</td>
								<td><a href="javascript: void(0);" onclick="javascript:Delete_row(this); return false;" title="Delete"><img src="/administrator/images/publish_x.png"  border="0" alt="Delete"></a></td>
							</tr>
							<?php } elseif (($rule->sf_qtype == 1) || ($rule->sf_qtype == 5) || ($rule->sf_qtype == 6)) {?>
							<tr>
								<td  width="375px;"> <?php echo $sf_lang['SF_FOR_QUESTION']?> "<?php echo $rule->sf_qtext;?>" <input type="hidden" name="sf_hid_rule2_id[]" value="<?php echo $rule->bid;?>" /><input type="hidden" name="sf_hid_rule2_alt_id[]" value="<?php echo ($rule->sf_qtype == 1?$rule->sdid:$rule->fdid);?>" /><input type="hidden" name="sf_hid_rule2_quest_id[]" value="<?php echo $rule->qid;?>" /></td>
								<td> <?php echo $sf_lang['SF_AND_OPTION']?> "<?php echo $rule->qoption;?>"</td>
								<td> <?php echo $sf_lang['SF_ANSWER_IS']?> "<?php echo ($rule->sf_qtype == 1?$rule->astext:$rule->aftext);?>"</td>
								<td><a href="javascript: void(0);" onclick="javascript:Delete_row(this); return false;" title="Delete"><img src="/administrator/images/publish_x.png"  border="0" alt="Delete"></a></td>
							</tr>
							<?php } elseif ($rule->sf_qtype == 9) {?>
							<tr >
								<td  width="375px;"> <?php echo $sf_lang['SF_FOR_QUESTION']?> "<?php echo $rule->sf_qtext;?>" <input type="hidden" name="sf_hid_rule2_id[]" value="<?php echo $rule->bid;?>" /><input type="hidden" name="sf_hid_rule2_alt_id[]" value="<?php echo $rule->did;?>" /><input type="hidden" name="sf_hid_rule2_quest_id[]" value="<?php echo $rule->qid;?>" /></td>
								<td> <?php echo $sf_lang['SF_AND_OPTION']?> "<?php echo $rule->qoption;?>"</td>
								<td> <?php echo $sf_lang['SF_RANK_IS']?> "<?php echo $rule->aftext;?>"</td>
								<td><a href="javascript: void(0);" onclick="javascript:Delete_row(this); return false;" title="Delete"><img src="/administrator/images/publish_x.png"  border="0" alt="Delete"></a></td>
							</tr>	
							<?php }
							}?>
					</table>
					<table width="100%"  id="show_quest2">
					<tr>
						<td style="width:70px;"><?php echo $sf_lang['SF_FOR_QUESTION']?> </td><td style="width:15px;"><?php echo $lists['quests3'];?></td>
						<td width="auto" colspan="2" ><div id="quest_show_div"></div>						
						</td>
					</tr>							
					<tr>
						<td colspan="4" style="text-align:left;"><input id="add_button" type="button" name="add" value="<?php echo $sf_lang["SF_ADD"]; ?>" onclick="javascript: if(!sf_is_loading) addRow();"  />
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
							cell1.innerHTML = '<?php echo $sf_lang['SF_FOR_QUESTION']?> "'+jQuery('#sf_quest_list3').get(0).options[jQuery('#sf_quest_list3').get(0).selectedIndex].innerHTML+'"';
							cell1.appendChild(input_hidden);
							cell1.appendChild(input_hidden2);
							cell1.appendChild(input_hidden3);
							if (qtype != 2 && qtype != 3) {
								cell2.innerHTML = ' <?php echo $sf_lang['SF_AND_OPTION']?> "'+jQuery('#sf_field_data_m').get(0).options[jQuery('#sf_field_data_m').get(0).selectedIndex].innerHTML+'"';				
								if (qtype != 9){
									if (qtype == 1)
										cell3.innerHTML = ' <?php echo $sf_lang['SF_ANSWER_IS']?> "'+jQuery('#f_scale_data').get(0).options[jQuery('#f_scale_data').get(0).selectedIndex].innerHTML+'"';
									else
										cell3.innerHTML = ' <?php echo $sf_lang['SF_ANSWER_IS']?> "'+jQuery('#sf_field_data_a').get(0).options[jQuery('#sf_field_data_a').get(0).selectedIndex].innerHTML+'"';
								}else {
									cell3.innerHTML = ' <?php echo $sf_lang['SF_RANK_IS']?> "'+jQuery('#sf_field_data_a').get(0).options[jQuery('#sf_field_data_a').get(0).selectedIndex].innerHTML+'"';
								}
							} else {
								cell2.innerHTML = ' <?php echo $sf_lang['SF_ANSWER_IS']?> "'+jQuery('#sf_field_data_m').get(0).options[jQuery('#sf_field_data_m').get(0).selectedIndex].innerHTML+'"';	
							}
							
							cell4.innerHTML = '<a href="javascript: void(0);" onclick="javascript:Delete_row(this); return false;" title="Delete"><img src="/administrator/images/publish_x.png"  border="0" alt="Delete"></a>';							
							row.appendChild(cell1);
							row.appendChild(cell2);							
							row.appendChild(cell3);
							row.appendChild(cell4);						
						}
						function processReq(http_request) {
							if (http_request.readyState == 4) {
								if ((http_request.status == 200)) {									
									var response = http_request.responseXML.documentElement;
									var text = 'Request Error';
									try {
										text = response.getElementsByTagName('data')[0].firstChild.data;
									} catch(e) {}
									jQuery('div#quest_show_div').html(text);							
								}
							}
						}
						function showOptions(val) {
							
							jQuery('input#add_button').get(0).style.display = 'none';
							
							jQuery('div#quest_show_div').html("Please wait... Loading...");
							
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

$live_url = $live_site_parts['scheme'].'://'.$live_site_parts['host'].(isset($live_site_parts['path'])?$live_site_parts['path']:'/');

if ( substr($live_url, strlen($live_url)-1, 1) !== '/')
	$live_url .= '/';
?>
							http_request.open('GET', '<?php echo $live_url;?>index.php?no_html=1&option=com_surveyforce&task=get_options&rand=<?php echo time();?>&quest_id='+val, true);
							http_request.send(null);						
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
			<?php } ?>
		</table>
		<br />
		
		<table width="100%" cellpadding="2" cellspacing="0" border="0"   id="qfld_tbl">
		<tr>
			<td class="sectiontableheader" width="20px" align="center">#</td>
			<td class="sectiontableheader" width="200px"><?php echo $sf_lang["SF_NAME"]?></td>
			<td class="sectiontableheader" width="200px"><?php echo $sf_lang["SF_ALT_NAME"]?></td>
			<td width="20px" align="center" class="sectiontableheader"></td>
			<td width="20px" align="center" class="sectiontableheader"></td>
			<td width="20px" align="center" class="sectiontableheader"></td>		
			<td class="sectiontableheader"></td>
		</tr>
		<?php
		$k = 1; $ii = 1; $ind_last = count($lists['sf_fields']);
		foreach ($lists['sf_fields'] as $frow) { ?>
			<input type="hidden" name="old_sf_field_ids[]" value="<?php echo $frow->id?>">
			<input type="hidden" name="old_sf_alt_field_ids[]" value="<?php echo $frow->alt_field_id?>">
			<tr class="<?php echo "sectiontableentry$k"; ?>">
				<td align="center"><?php echo $ii?></td>
				<td align="left" ondblclick="edit_name(event, 'sf_fields[]', 'sf_field_ids[]');"><input type="hidden" name="sf_fields[]" value="<?php echo $frow->ftext?>"><input type="hidden" name="sf_field_ids[]" value="<?php echo $frow->id?>">
					<?php echo $frow->ftext?>
					
				</td>
				<td align="left" ondblclick="edit_name(event, 'sf_alt_fields[]', 'sf_alt_field_ids[]');"><input type="hidden" name="sf_alt_fields[]" value="<?php echo $frow->alt_field_full?>"><input type="hidden" name="sf_alt_field_ids[]" value="<?php echo $frow->alt_field_id?>">
					<?php echo $frow->alt_field_full?>
					
				</td>
				<td><a  href="javascript: void(0);" onClick="javascript:Delete_tbl_row(this); return false;" title="Delete"><img src="components/com_surveyforce/images/publish_x.png" width="12" height="12" border="0" alt="Delete"></a></td>
				<td><?php if ($ii > 1) { ?><a href="" onClick="javascript:Up_tbl_row(this); return false;" title="Move Up"><img src="components/com_surveyforce/images/toolbar/btn_uparrow.png" width="16" height="16" border="0" alt="Move Up"></a><?php } ?></td>
				<td><?php if ($ii < $ind_last) { ?><a href="" onClick="javascript:Down_tbl_row(this); return false;" title="Move Down"><img src="components/com_surveyforce/images/toolbar/btn_downarrow.png" width="16" height="16" border="0" alt="Move Down"></a><?php } ?></td>
				<td></td>
			</tr>
		<?php
		$k = 3 - $k; $ii ++;
		 } ?>
		</table><br>
		
		<?php if ($owner) {?>
		<div style="text-align:left; padding-left:30px ">
			<input id="new_field" class="inputbox" style="width:205px " type="text" name="new_field">
			<input id="new_alt_field" class="inputbox" style="width:205px " type="text" name="new_alt_field">
			<input class="button" type="button" name="add_new_field"  value="<?php echo $sf_lang["SF_ADD"]?>" onClick="javascript:Add_new_tbl_field('new_field', 'qfld_tbl', 'sf_fields[]', 'sf_field_ids[]', 'new_alt_field', 'sf_alt_fields[]', 'sf_alt_field_ids[]');">
			<?php if (!$sf_config->get('sf_enable_jomsocial_integration')) { ?>
			<br/><br/>
			<input class="button" type="button" name="set_default" value="<?php echo $sf_lang['SF_SET_DEFAULT']?>" onClick="javascript: <?php echo ($row->id > 0?"submitbutton('set_default');":"alert('{$sf_lang['SF_YOU_VAN_SET_DEFAULT_AFTER_SAVING']}');")?>">
			<?php }?>
		</div>
		<?php }?>
		<br />
		<?php if (!$sf_config->get('sf_enable_jomsocial_integration')) { ?>
		<table width="100%" cellpadding="2" cellspacing="0" border="0"   >
		<tr>
			<td class="sectiontableheader" width="20px" align="center">#</td>
			<td class="sectiontableheader" width="200px"><?php echo $sf_lang["SF_QUEST_RULES"]?></td>
			<td width="20px" align="center" class="sectiontableheader"></td>
			<td width="20px" align="center" class="sectiontableheader"></td>
			<td width="20px" align="center" class="sectiontableheader"></td>
			<td width="auto" class="sectiontableheader"></td>
		</tr></table>
		<table width="100%" cellpadding="2" cellspacing="0" border="0"   id="qfld_tbl_rule">
		<tr>
			<td class="sectiontableheader" width="2%" align="center">#</td>
			<td class="sectiontableheader" width="22%"><?php echo $sf_lang['SF_NAME']?></td>
			<td class="sectiontableheader" width="22%"><?php echo $sf_lang['SF_ANSWER']?></td>
			<td class="sectiontableheader" width="22%"><?php echo $sf_lang['SF_QUESTION']?></td>
			<td class="sectiontableheader" width="22%"><?php echo $sf_lang['SF_PRIORITY_C']?></td>
			<td width="2%" align="left" class="sectiontableheader"></td>
			<td width="2%" align="left" class="sectiontableheader"></td>
			<td width="auto" class="sectiontableheader"></td>
		</tr>

			<?php
			$k = 1; $ii = 1; $ind_last = count($lists['sf_fields_rule']);
			foreach ($lists['sf_fields_rule'] as $rrow) { ?>
				<tr class="<?php echo "sectiontableentry$k"; ?>">
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
						<?php echo $rrow->next_quest_id . ' - ' . (strlen(strip_tags($rrow->sf_qtext)) > 50? substr(strip_tags($rrow->sf_qtext), 0, 50).'...': strip_tags($rrow->sf_qtext))?>
						<input type="hidden" name="sf_hid_rule_quest[]" value="<?php echo $rrow->next_quest_id?>">
					</td>
					<td>
						<input type="text" style="text-align:center" class="inputbox" name="priority[]" size="3" value="<?php echo $rrow->priority?>" />
					</td>
					<td><a href="javascript: void(0);" onClick="javascript:Delete_tbl_row2(this); return false;" title="Delete"><img src="components/com_surveyforce/images/publish_x.png" width="12" height="12" border="0" alt="Delete"></a></td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
			<?php
			$k = 3 - $k; $ii ++;
			 } ?>
		 </table><br>
		<?php if ($owner) {?>
		<div style="text-align:left; padding-left:30px ">
		<input type="checkbox" name="super_rule" value="1" <?php echo $lists['checked']; ?> /><?php echo $sf_lang['SF_GO_TO_QUEST21'];?> <?php echo $lists['quests2']; ?> <?php echo $sf_lang['SF_GO_TO_QUEST22'];?>
		</div><br />
		<div style="text-align:left; padding-left:10px "><?php echo $sf_lang['SF_IF_FOR']?> <?php echo $lists['sf_list_fields']; ?> <?php echo $sf_lang['SF_ANSWER_IS']?>
			<?php echo $lists['sf_alt_field_list']; ?>, <?php echo $sf_lang["SF_GO_TO_QUEST"]?>
			<?php echo $lists['quests']; ?>, <?php echo $sf_lang['SF_PRIORITY']?> <input type="text" style="text-align:center" class="inputbox" name="new_priority" id="new_priority" size="3" value="0" />
			<input class="button" type="button" name="add_new_rule"  value="<?php echo $sf_lang["SF_ADD"]?>" onClick="javascript:Add_new_tbl_field2('sf_field_list', 'sf_alt_field_list', 'qfld_tbl_rule', 'sf_hid_rule[]', 'sf_quest_list', 'sf_hid_rule_quest[]', 'sf_hid_rule_alt[]');">
		</div>
		<?php }
		}?>
		<br />
		<input type="hidden" name="sf_qtype" value="<?php echo $q_rank_type; ?>" />
		<input type="hidden" name="option" value="com_surveyforce" />
		<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="Itemid" value="<?php echo $Itemid?>" />
		
		<input type="hidden" name="quest_id" value="<?php echo $row->id;?>" />
		<input type="hidden" name="red_task" value="<?php echo $task;?>" />
		</form><br/><br/>
		</div>
		<?php
	}
	
	function SF_editQ_Ranking( &$row, &$lists, $option ) {
		global $task, $Itemid,$Itemid_s, $my, $sf_lang, $mosConfig_live_site, $_MAMBOTS;
		
		$owner = SF_GetUserType($my->id, $lists['survid']) == 1;
		$sf_config = new mos_Survey_Force_Config( );
		if (_JOOMLA15) {
			jimport( 'joomla.html.editor' );
	
			$conf =& JFactory::getConfig();
			$editor = $conf->getValue('config.editor');
			$editorz =& JEditor::getInstance($editor);
			$editorz =& JFactory::getEditor();
		} 

		?>
		<script type="text/javascript" src="/media/system/js/core.js"></script>
		<script type="text/javascript" src="components/com_surveyforce/overlib_mini.js"></script>
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
				var count = start_index; var row_k = 2 - start_index%2;
				for (var i=start_index; i<tbl_elem.rows.length; i++) {
					tbl_elem.rows[i].cells[0].innerHTML = count;
					
					if (i > 1) { 
						tbl_elem.rows[i].cells[3].innerHTML = '<a href="javascript: void(0);" onClick="javascript:Up_tbl_row(this); return false;" title="Move Up"><img src="components/com_surveyforce/images/toolbar/btn_uparrow.png" width="16" height="16" border="0" alt="Move Up"></a>';
					} else { tbl_elem.rows[i].cells[3].innerHTML = ''; }
					if (i < (tbl_elem.rows.length - 1)) {
						tbl_elem.rows[i].cells[4].innerHTML = '<a href="javascript: void(0);" onClick="javascript:Down_tbl_row(this); return false;" title="Move Down"><img src="components/com_surveyforce/images/toolbar/btn_downarrow.png" width="16" height="16" border="0" alt="Move Down"></a>';;
					} else { tbl_elem.rows[i].cells[4].innerHTML = ''; }
					tbl_elem.rows[i].className = 'sectiontableentry'+row_k;
					count++;
					row_k = 3 - row_k;
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
			
				cell3.innerHTML = '<a href="javascript: void(0);" onClick="javascript:Delete_tbl_row(this); return false;" title="Delete"><img src="components/com_surveyforce/images/publish_x.png"  border="0" alt="Delete"></a>';
				cell4.innerHTML = '<a href="javascript: void(0);" onClick="javascript:Up_tbl_row(this); return false;" title="Move Up"><img src="components/com_surveyforce/images/toolbar/btn_uparrow.png"  border="0" alt="Move Up"></a>';
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
				
				cell3.innerHTML = '<a href="javascript: void(0);" onClick="javascript:Delete_tbl_row(this); return false;" title="Delete"><img src="components/com_surveyforce/images/publish_x.png"  border="0" alt="Delete"></a>';
				cell4.innerHTML = '<a href="javascript: void(0);" onClick="javascript:Up_tbl_row(this); return false;" title="Move Up"><img src="components/com_surveyforce/images/toolbar/btn_uparrow.png"  border="0" alt="Move Up"></a>';
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
				alert("<?php echo $sf_lang["SF_ALERT_ENTER_TEXT"]?>");return;
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
			
			cell3.innerHTML = '<a href="javascript: void(0);" onClick="javascript:Delete_tbl_row(this); return false;" title="Delete"><img src="components/com_surveyforce/images/publish_x.png" width="12" height="12" border="0" alt="Delete"></a>';
			cell4.innerHTML = '<a href="javascript: void(0);" onClick="javascript:Up_tbl_row(this); return false;" title="Move Up"><img src="components/com_surveyforce/images/toolbar/btn_uparrow.png" width="16" height="16" border="0" alt="Move Up"></a>';
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
				alert("Please enter text to the field.");return;
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
			cell3.innerHTML = '<a href="javascript: void(0);" onClick="javascript:Delete_tbl_row2(this); return false;" title="Delete"><img src="components/com_surveyforce/images/publish_x.png" width="12" height="12" border="0" alt="Delete"></a>';
			cell3b.innerHTML = '<input type="text" style="text-align:center" class="inputbox" name="priority[]" size="3" value="'+getObj('new_priority').value+'" />';
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
			try{
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
			} catch(e){}
		}

		function submitbutton(pressbutton) {
			var form = document.adminForm;
			if (pressbutton == 'cancel_quest') {
				submitform( pressbutton );
				return;
			}
			
			fillTextArea();
			// do field validation
			if (false && form.sf_qtext.value == ""){
				alert( "<?php echo $sf_lang["SF_ALERT_QUEST_MUST_HAVE_TEXT"]?>" );
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
		<div class="contentpane surveyforce">
		<form action="<?php echo SFRoute("index.php?option=$option{$Itemid_s}")?>" method="post" name="adminForm" id="adminForm">
		<table width="100%" cellpadding="0" cellspacing="0" border="0"  >
			<tr>
				<td width="auto">
					<table width="100%" cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td width="48px">
								<?php echo SF_showHeadPicture('surveys');?>
							</td>
							<td class="contentheading" width="100%" valign="middle" style="vertical-align:middle ">
								&nbsp;<?php echo $row->id ? $sf_lang["SF_EDIT_QUEST"] : $sf_lang["SF_NEW_QUEST"]; echo ' ('.$sf_lang['SF_RANKING'].')';?>
							</td>
							<td align="right" valign="top" style="vertical-align:top ">
								<?php SF_showTopMenu();?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td align="center" style="text-align:center">
				<span id="SF_toolbar_tooltip">&nbsp;</span><br/>
				<?php $toolbar = array();
					if ($owner) {
					  $toolbar[] = array('btn_type' => 'save', 'btn_js' => "javascript:submitbutton('save_quest');");
					  $toolbar[] = array('btn_type' => 'apply', 'btn_js' => "javascript:submitbutton('apply_quest');");
					}
					  $toolbar[] = array('btn_type' => 'cancel', 'btn_js' => "javascript:submitbutton('cancel_quest');"); 
					  echo ShowToolbar($toolbar); 
				?>
				</td>
			</tr>
			<tr>
				<td width="100%">
				<table width="100%"><tr><td align="left">&nbsp;</td>
				<td align="right">&nbsp;</td>
				</tr>
				</table>
				</td>
			</tr>
		</table>
				
		<table width="100%" cellpadding="0" cellspacing="0" border="0"  >
			<tr>
				<td colspan="2" class="sectiontableheader"><?php echo $sf_lang["SF_QUEST_DETAILS"];?></td>
			</tr>
			<tr>
				<td align="left" width="20%" valign="top" colspan="2"><?php echo $sf_lang["SF_QUEST_TEXT"];?>:</td>
			</tr>
			<tr>
				<td colspan="2"><?php 
					if ($owner)
						SF_editorArea( 'editor2', $row->sf_qtext, 'sf_qtext', '100%;', '250', '40', '20' ) ; 
					else
						echo $row->sf_qtext;
					?>					
				</td>
			</tr>
			<tr>
				<td><?php echo $sf_lang["SF_SURVEY"];?>:</td><td><?php echo $lists['survey']; ?></td>
			</tr>
			<?php if (!$sf_config->get('sf_enable_jomsocial_integration')) { ?>
			<tr>
				<td><?php echo $sf_lang["SF_IMP_SCALE"];?>:</td><td><?php echo $lists['impscale'];?><?php if ($owner) {?><input type="button" class="inputbox" name="Define new" onClick="javascript: fillTextArea();document.adminForm.task.value='add_iscale_from_quest';document.adminForm.submit();" value="<?php echo $sf_lang["SF_DEFINE_NEW"];?>"><?php }?></td>
			</tr>
			<?php }?>
			<tr>
				<td>
					<?php echo $sf_lang["SF_PUBLISHED"];?>:
				</td>
				<td>
					<?php echo $lists['published']; ?>
				</td>
			</tr>
			<tr>
				<td><?php echo $sf_lang["SF_ORDERING"];?>:</td><td><?php echo $lists['ordering']; ?></td>
			</tr> 
			<?php if ( $lists['sf_section_id'] != null ) {?>
			<tr>
				<td><?php echo $sf_lang["SF_SECTION"];?>:</td><td><?php echo $lists['sf_section_id'];?></td>
			</tr> 
			<?php }?>
			<tr>
				<td>
					<?php echo $sf_lang["SF_COMPULSORY"];?>:
				</td>
				<td>
					<?php echo $lists['compulsory']; ?>				
				</td>
			</tr> 
			<?php if (!($row->id > 0)) {?>
			<tr>
				<td>
					<?php echo $sf_lang["SF_INSERT_PAGE_BREAK"];?>:
				</td>
				<td>
					<?php echo $lists['insert_pb']; ?>
				</td>
			</tr>
			<?php } ?>
			<?php if (!$sf_config->get('sf_enable_jomsocial_integration')) { ?>
			<tr>
				<td colspan="2">
					<script type="text/javascript" src="<?php echo $mosConfig_live_site;?>/administrator/components/com_surveyforce/js/jquery.pack.js"></script>
					<script type="text/javascript" language="javascript" >
						jQuery.noConflict();
						var sf_is_loading = false;
					</script>
					<table class="adminlist" id="show_quest">
					<tr>
						<th class="title" colspan="4"><?php echo $sf_lang['SF_DONT_SHOW']?>:</th>
					</tr>
					<?php if (is_array($lists['quest_show']) && count($lists['quest_show'])) 
							foreach($lists['quest_show'] as $rule) {
								if ( ($rule->sf_qtype == 2) || ($rule->sf_qtype == 3) ) {
							?>
							
							<tr>
								<td width="375px;"> <?php echo $sf_lang['SF_FOR_QUESTION']?> "<?php echo $rule->sf_qtext;?>" <input type="hidden" name="sf_hid_rule2_id[]" value="<?php echo $rule->bid;?>" /><input type="hidden" name="sf_hid_rule2_alt_id[]" value="<?php echo $rule->did;?>" /><input type="hidden" name="sf_hid_rule2_quest_id[]" value="<?php echo $rule->qid;?>" /></td>
								<td colspan="2"> <?php echo $sf_lang['SF_ANSWER_IS']?> "<?php echo $rule->qoption;?>"</td>
								<td><a href="javascript: void(0);" onclick="javascript:Delete_row(this); return false;" title="Delete"><img src="/administrator/images/publish_x.png"  border="0" alt="Delete"></a></td>
							</tr>
							<?php } elseif (($rule->sf_qtype == 1) || ($rule->sf_qtype == 5) || ($rule->sf_qtype == 6)) {?>
							<tr>
								<td  width="375px;"> <?php echo $sf_lang['SF_FOR_QUESTION']?> "<?php echo $rule->sf_qtext;?>"<input type="hidden" name="sf_hid_rule2_id[]" value="<?php echo $rule->bid;?>" /><input type="hidden" name="sf_hid_rule2_alt_id[]" value="<?php echo ($rule->sf_qtype == 1?$rule->sdid:$rule->fdid);?>" /><input type="hidden" name="sf_hid_rule2_quest_id[]" value="<?php echo $rule->qid;?>" /></td>
								<td> <?php echo $sf_lang['SF_AND_OPTION']?> "<?php echo $rule->qoption;?>"</td>
								<td> <?php echo $sf_lang['SF_ANSWER_IS']?> "<?php echo ($rule->sf_qtype == 1?$rule->astext:$rule->aftext);?>"</td>
								<td><a href="javascript: void(0);" onclick="javascript:Delete_row(this); return false;" title="Delete"><img src="/administrator/images/publish_x.png"  border="0" alt="Delete"></a></td>
							</tr>
							<?php } elseif ($rule->sf_qtype == 9) {?>
							<tr >
								<td  width="375px;"> <?php echo $sf_lang['SF_FOR_QUESTION']?> "<?php echo $rule->sf_qtext;?>"<input type="hidden" name="sf_hid_rule2_id[]" value="<?php echo $rule->bid;?>" /><input type="hidden" name="sf_hid_rule2_alt_id[]" value="<?php echo $rule->did;?>" /><input type="hidden" name="sf_hid_rule2_quest_id[]" value="<?php echo $rule->qid;?>" /></td>
								<td> <?php echo $sf_lang['SF_AND_OPTION']?> "<?php echo $rule->qoption;?>"</td>
								<td> <?php echo $sf_lang['SF_RANK_IS']?> "<?php echo $rule->aftext;?>"</td>
								<td><a href="javascript: void(0);" onclick="javascript:Delete_row(this); return false;" title="Delete"><img src="/administrator/images/publish_x.png"  border="0" alt="Delete"></a></td>
							</tr>	
							<?php }
							}?>
					</table>
					<table width="100%"  id="show_quest2">
					<tr>
						<td style="width:70px;"><?php echo $sf_lang['SF_FOR_QUESTION']?> </td><td style="width:15px;"><?php echo $lists['quests3'];?></td>
						<td width="auto" colspan="2" ><div id="quest_show_div"></div>						
						</td>
					</tr>							
					<tr>
						<td colspan="4" style="text-align:left;"><input id="add_button" type="button" name="add" value="<?php echo $sf_lang["SF_ADD"];?>" onclick="javascript: if(!sf_is_loading) addRow();"  />
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
							cell1.innerHTML = '<?php echo $sf_lang['SF_FOR_QUESTION']?> "'+jQuery('#sf_quest_list3').get(0).options[jQuery('#sf_quest_list3').get(0).selectedIndex].innerHTML+'"';
							cell1.appendChild(input_hidden);
							cell1.appendChild(input_hidden2);
							cell1.appendChild(input_hidden3);
							if (qtype != 2 && qtype != 3) {
								cell2.innerHTML = ' <?php echo $sf_lang['SF_AND_OPTION']?> "'+jQuery('#sf_field_data_m').get(0).options[jQuery('#sf_field_data_m').get(0).selectedIndex].innerHTML+'"';				
								if (qtype != 9){
									if (qtype == 1)
										cell3.innerHTML = ' <?php echo $sf_lang['SF_ANSWER_IS']?> "'+jQuery('#f_scale_data').get(0).options[jQuery('#f_scale_data').get(0).selectedIndex].innerHTML+'"';
									else
										cell3.innerHTML = ' <?php echo $sf_lang['SF_ANSWER_IS']?> "'+jQuery('#sf_field_data_a').get(0).options[jQuery('#sf_field_data_a').get(0).selectedIndex].innerHTML+'"';
								}else {
									cell3.innerHTML = ' <?php echo $sf_lang['SF_RANK_IS']?> "'+jQuery('#sf_field_data_a').get(0).options[jQuery('#sf_field_data_a').get(0).selectedIndex].innerHTML+'"';
								}
							} else {
								cell2.innerHTML = ' <?php echo $sf_lang['SF_ANSWER_IS']?> "'+jQuery('#sf_field_data_m').get(0).options[jQuery('#sf_field_data_m').get(0).selectedIndex].innerHTML+'"';	
							}
							
							cell4.innerHTML = '<a href="javascript: void(0);" onclick="javascript:Delete_row(this); return false;" title="Delete"><img src="/administrator/images/publish_x.png"  border="0" alt="Delete"></a>';							
							row.appendChild(cell1);
							row.appendChild(cell2);							
							row.appendChild(cell3);
							row.appendChild(cell4);						
						}
						function processReq(http_request) {
							if (http_request.readyState == 4) {
								if ((http_request.status == 200)) {									
									var response = http_request.responseXML.documentElement;
									var text = 'Request Error';
									try {
										text = response.getElementsByTagName('data')[0].firstChild.data;
									} catch(e) {}
									jQuery('div#quest_show_div').html(text);							
								}
							}
						}
						function showOptions(val) {
							
							jQuery('input#add_button').get(0).style.display = 'none';
							
							jQuery('div#quest_show_div').html("Please wait... Loading...");
							
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

$live_url = $live_site_parts['scheme'].'://'.$live_site_parts['host'].(isset($live_site_parts['path'])?$live_site_parts['path']:'/');

if ( substr($live_url, strlen($live_url)-1, 1) !== '/')
	$live_url .= '/';
?>
							http_request.open('GET', '<?php echo $live_url;?>index.php?no_html=1&option=com_surveyforce&task=get_options&rand=<?php echo time();?>&quest_id='+val, true);
							http_request.send(null);						
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
			<?php }?>
		</table>
		<br />
		<table  width="100%" cellpadding="0" cellspacing="0" border="0"   id="qfld_tbl_rank">
		<tr>
			<td width="20px" align="center" class="sectiontableheader">#</td>
			<td class="sectiontableheader" width="200px"><?php echo $sf_lang['SF_RANKS'];?></td>
			<td width="20px" align="center" class="sectiontableheader"></td>
			<td width="20px" align="center" class="sectiontableheader"></td>
			<td width="20px" align="center" class="sectiontableheader"></td>
			<td width="auto" class="sectiontableheader"></td>
		</tr>
			<?php
			$k = 1; $ii = 1; $ind_last = count($lists['sf_fields_rank']);
			foreach ($lists['sf_fields_rank'] as $frow) { 
			?>	<input type="hidden" name="old_sf_hid_rank_id[]" value="<?php echo $frow->id?>">
				<tr class="<?php echo "sectiontableentry$k"; ?>">
					<td align="center"><?php echo $ii?></td>
					<td align="left"  ondblclick="edit_name(event, 'sf_hid_rank[]', 'sf_hid_rank_id[]');"><input type="hidden" name="sf_hid_rank[]" value="<?php echo $frow->ftext?>"><input type="hidden" name="sf_hid_rank_id[]" value="<?php echo $frow->id?>">
						<?php echo $frow->ftext?>
						
					</td>
					<td><a href="javascript: void(0);" onClick="javascript:Delete_tbl_row(this); return false;" title="Delete"><img src="components/com_surveyforce/images/publish_x.png" width="12" height="12" border="0" alt="Delete"></a></td>
					<td><?php if ($ii > 1) { ?><a href="javascript: void(0);" onClick="javascript:Up_tbl_row(this); return false;" title="Move Up"><img src="components/com_surveyforce/images/toolbar/btn_uparrow.png" width="16" height="16" border="0" alt="Move Up"></a><?php } ?></td>
					<td><?php if ($ii < $ind_last) { ?><a href="javascript: void(0);" onClick="javascript:Down_tbl_row(this); return false;" title="Move Down"><img src="components/com_surveyforce/images/toolbar/btn_downarrow.png" width="16" height="16" border="0" alt="Move Down"></a><?php } ?></td>
					<td></td>
				</tr>
			<?php
			$k = 3 - $k; $ii ++;
			 } ?>
		 </table><br>
		 <?php if ($owner) {?>
		<div style="text-align:left; padding-left:30px ">
			<input id="new_rank" class="inputbox" style="width:205px " type="text" name="new_rank">
			<input class="inputbox" type="button" name="add_new_rank" style="width:70px " value="<?php echo $sf_lang['SF_ADD'];?>" onClick="javascript:Add_new_tbl_field('new_rank', 'qfld_tbl_rank', 'sf_hid_rank[]', 'sf_hid_rank_id[]');">
		</div>
		<br />
		<?php }?>
		<table  width="100%" cellpadding="0" cellspacing="0" border="0"   id="qfld_tbl">
		<tr>
			<td width="20px" align="center" class="sectiontableheader">#</td>
			<td class="sectiontableheader" width="200px"><?php echo $sf_lang["SF_QUEST_OPTION"];?></td>
			<td width="20px" align="center" class="sectiontableheader"></td>
			<td width="20px" align="center" class="sectiontableheader"></td>
			<td width="20px" align="center" class="sectiontableheader"></td>
			<td width="auto" class="sectiontableheader"></td>
		</tr>
		<?php
		$k = 1; $ii = 1; $ind_last = count($lists['sf_fields']);
		$other_option = null;
		foreach ($lists['sf_fields'] as $frow) { 
			if (isset($frow->is_true) && $frow->is_true == 2) {
				$other_option = $frow;
				continue;
			}
		?>
			<input type="hidden" name="old_sf_hid_field_ids[]" value="<?php echo $frow->id?>"/>
			<tr class="<?php echo "sectiontableentry$k"; ?>">
				<td align="center"><?php echo $ii?></td>
				<td align="left" onDblClick="edit_name(event, 'sf_hid_fields[]', 'sf_hid_field_ids[]');"><input type="hidden" name="sf_hid_fields[]" value="<?php echo $frow->ftext?>"/><input type="hidden" name="sf_hid_field_ids[]" value="<?php echo $frow->id?>"/>
					<?php echo $frow->ftext?>
					
				</td>
				<td><a href="javascript: void(0);" onClick="javascript:Delete_tbl_row(this); return false;" title="Delete"><img src="components/com_surveyforce/images/publish_x.png" width="12" height="12" border="0" alt="Delete"></a></td>
				<td><?php if ($ii > 1) { ?><a href="javascript: void(0);" onClick="javascript:Up_tbl_row(this); return false;" title="Move Up"><img src="components/com_surveyforce/images/toolbar/btn_uparrow.png" width="16" height="16" border="0" alt="Move Up"></a><?php } ?></td>
				<td><?php if ($ii < $ind_last) { ?><a href="javascript: void(0);" onClick="javascript:Down_tbl_row(this); return false;" title="Move Down"><img src="components/com_surveyforce/images/toolbar/btn_downarrow.png" width="16" height="16" border="0" alt="Move Down"></a><?php } ?></td>
				<td></td>
			</tr>
		<?php
		$k = 3 - $k; $ii ++;
		 } ?>
		</table>
		<table  width="100%" cellpadding="0" cellspacing="0" border="0"   >
		<tr class="<?php echo "sectiontableentry$k"; ?>">
			<td width="20px" align="center"><input type="checkbox" onchange="javascipt: Add_fields_to_select();" name="other_option_cb" id="other_option_cb" value="2"  <?php echo (($other_option != null && !isset($lists['other_option'])) || (isset($lists['other_option']) && $lists['other_option'] == 1) ?'checked="checked"':'')?> /></td>
			<td align="left" colspan="5"><?php echo $sf_lang['SF_OTHER_OPTION'];?> <input class="inputbox" onkeyup="javascipt: Add_fields_to_select();" style="width:120px " type="text" name="other_option" id="other_option" value="<?php echo ($other_option == null?'Other':$other_option->ftext)?>">		
			<input type="hidden" name="other_op_id" id="other_op_id" value="<?php echo ($other_option == null?'0':$other_option->id)?>"/>
			</td>
		</tr>
		</table>
		<br>
		<?php if ($owner) {?>
		<div style="text-align:left; padding-left:30px ">
			<input id="new_field" class="inputbox" style="width:205px " type="text" name="new_field">
			<input class="inputbox" type="button" name="add_new_field" style="width:70px " value="<?php echo $sf_lang["SF_ADD"];?>" onClick="javascript:Add_new_tbl_field('new_field', 'qfld_tbl', 'sf_hid_fields[]', 'sf_hid_field_ids[]');">
			<?php if (!$sf_config->get('sf_enable_jomsocial_integration')) { ?>
			<br/><br/>
			<input class="button" type="button" name="set_default" value="<?php echo $sf_lang["SF_SET_DEFAULT"];?>" onClick="javascript: <?php echo ($row->id > 0?"submitbutton('set_default');":"alert('".$sf_lang['SF_YOU_VAN_SET_DEFAULT_AFTER_SAVING'] ."');")?>">
			<?php }?>
		</div>
		<br />
		<?php }?>	
		<?php if (!$sf_config->get('sf_enable_jomsocial_integration')) { ?>	
		<table  width="100%" cellpadding="0" cellspacing="0" border="0"  >
		<tr>
			<td width="20px" align="center" class="sectiontableheader">#</td>
			<td class="sectiontableheader" width="200px"><?php echo $sf_lang["SF_QUEST_RULES"];?></td>
			<td width="20px" align="center" class="sectiontableheader"></td>
			<td width="20px" align="center" class="sectiontableheader"></td>
			<td width="20px" align="center" class="sectiontableheader"></td>
			<td width="auto" class="sectiontableheader"></td>
		</tr></table>

		<table  width="100%" cellpadding="0" cellspacing="0" border="0"   id="qfld_tbl_rule">
		<tr>
			<td width="2%" align="center" class="sectiontableheader">#</td>
			<td class="sectiontableheader" width="20%"><?php echo $sf_lang["SF_QUEST_OPTION"];?></td>
			<td class="sectiontableheader" width="4%"><?php echo $sf_lang["SF_RANK"];?></td>
			<td class="sectiontableheader" width="30%"><?php echo $sf_lang["SF_QUESTION"];?></td>
			<td class="sectiontableheader" width="14%"><?php echo $sf_lang['SF_PRIORITY_C'];?></td>
			<td width="2%" align="left" class="sectiontableheader"></td>
			<td width="2%" align="left" class="sectiontableheader"></td>
			<td width="auto" class="sectiontableheader"></td>
		</tr>

			<?php
			$k = 1; $ii = 1; $ind_last = count($lists['sf_fields_rule']);
			foreach ($lists['sf_fields_rule'] as $rrow) { ?>
				<tr class="<?php echo "sectiontableentry$k"; ?>">
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
						<?php echo $rrow->next_quest_id . ' - ' . (strlen(strip_tags($rrow->sf_qtext)) > 50? substr(strip_tags($rrow->sf_qtext), 0, 50).'...': strip_tags($rrow->sf_qtext))?>
						<input type="hidden" name="sf_hid_rule_quest[]" value="<?php echo $rrow->next_quest_id?>">
					</td>
					<td>
						<input type="text" style="text-align:center" class="inputbox" name="priority[]" size="3" value="<?php echo $rrow->priority?>" />
					</td>
					<td><a href="javascript: void(0);" onClick="javascript:Delete_tbl_row2(this); return false;" title="Delete"><img src="components/com_surveyforce/images/publish_x.png" width="12" height="12" border="0" alt="Delete"></a></td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
			<?php
			$k = 3 - $k; $ii ++;
			 } ?>
		 </table><br>	
		 <?php if ($owner) {?>
		 <div style="text-align:left; padding-left:30px ">
		<input type="checkbox" name="super_rule" value="1" <?php echo $lists['checked']; ?> /><?php echo $sf_lang['SF_GO_TO_QUEST21'];?> <?php echo $lists['quests2']; ?> <?php echo $sf_lang['SF_GO_TO_QUEST22'];?>
		</div><br />
		<div style="text-align:left; padding-left:30px "> 	
		<?php echo $sf_lang['SF_IF_FOR'];?> <?php echo $lists['sf_list_fields']; ?><?php echo $sf_lang['SF_RANK_IS'];?><?php echo $lists['sf_list_rank_fields']; ?>, 
		<?php echo $sf_lang["SF_GO_TO_QUEST"];?> <?php echo $lists['quests']; ?>, <?php echo $sf_lang['SF_PRIORITY'];?> <input type="text" style="text-align:center" class="inputbox" name="new_priority" id="new_priority" size="3" value="0" />
		<input class="inputbox" type="button" name="add_new_rule"  value="<?php echo $sf_lang['SF_ADD'];?>" onClick="javascript:Add_new_tbl_field2('sf_field_list', 'sf_list_rank_fields', 'qfld_tbl_rule', 'sf_hid_rule[]', 'sf_quest_list', 'sf_hid_rule_quest[]', 'sf_hid_rule_alt[]');">
		</div>
		<br />
		<?php }
		}?>
		<input type="hidden" name="sf_qtype" value="9" />
		<input type="hidden" name="option" value="com_surveyforce" />
		<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
		<input type="hidden" name="task" value="" />
		
		<input type="hidden" name="quest_id" value="<?php echo $row->id;?>" />
		<input type="hidden" name="red_task" value="<?php echo $task;?>" />
		<input type="hidden" name="Itemid" value="<?php echo $Itemid?>" />
		</form><br/><br/>
		
		</div>
		<?php
	}
	
	function SF_moveQ_Select( $option, $cid, $sec, $SurveyList, $items ) {
		global $task, $Itemid,$Itemid_s, $my, $sf_lang, $mosConfig_live_site;

		?>
		<script type="text/javascript" src="/media/system/js/core.js"></script>
		<script type="text/javascript" src="components/com_surveyforce/overlib_mini.js"></script>
		<div class="contentpane surveyforce">
		<form action="<?php echo SFRoute("index.php?option=$option{$Itemid_s}")?>" method="post" name="adminForm" id="adminForm">
		<table width="100%" cellpadding="0" cellspacing="0" border="0"  >
			<tr>
				<td width="auto">
					<table width="100%" cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td width="48px">
								<?php echo SF_showHeadPicture('surveys');?>
							</td>
							<td class="contentheading" width="100%" valign="middle" style="vertical-align:middle ">
								&nbsp;<?php if ($task == 'move_quest_sel') { 
												echo $sf_lang["SF_MOVE_QUEST"];
											} elseif ($task == 'copy_quest_sel') { 
												echo $sf_lang["SF_COPY_QUEST"];
											} ?>
							</td>
							<td align="right" valign="top" style="vertical-align:top ">
								<?php SF_showTopMenu();?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td align="center" style="text-align:center">
				<span id="SF_toolbar_tooltip">&nbsp;</span><br/>
				<?php $toolbar = array();
					  $toolbar[] = array('btn_type' => 'save', 'btn_js' => "javascript:submitbutton('".($task == 'move_quest_sel'?'move_quest_save':'copy_quest_save')."');");
					  $toolbar[] = array('btn_type' => 'cancel', 'btn_js' => "javascript:submitbutton('cancel_quest');"); 
					  echo ShowToolbar($toolbar); 
				?>
				</td>
			</tr>
			<tr>
				<td width="100%">
				<table width="100%"><tr><td align="left">&nbsp;</td>
				<td align="right">&nbsp;</td>
				</tr>
				</table>
				</td>
			</tr>
		</table>

		<table width="100%" cellpadding="2" cellspacing="0" border="0"  >
		<tr>
			<td width="3%" ></td>
			<td align="left" valign="top" width="25%">
			<strong><?php echo $sf_lang["SF_COPYMOVE_TO_SURVEY"]?>:</strong>
			<br />
			<?php echo $SurveyList ?>
			<br /><br />
			</td>
			<td align="left" valign="top" width="40%">
			<strong><?php echo $sf_lang["SF_QUEST_BEING_COPYMOVE"]?>:</strong>
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
			<?php echo $sf_lang["SF_THIS_WILL_COPYMOVE_QUESTS"]?>
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
		<input type="hidden" name="Itemid" value="<?php echo $Itemid?>" />
		</form><br/><br/>
		</div>
		<?php
	}
	
	function SF_showSetDefault( &$row, &$lists, $option ) {
		global $task, $Itemid,$Itemid_s, $my, $sf_lang, $mosConfig_live_site;

		mosCommonHTML::loadCalendar();?>
		<script type="text/javascript" src="/media/system/js/core.js"></script>
		<script type="text/javascript" src="components/com_surveyforce/overlib_mini.js"></script>
		<div class="contentpane surveyforce">		
		<form action="<?php echo SFRoute("index.php?option=$option{$Itemid_s}")?>" method="post" name="adminForm" id="adminForm">
		<table width="100%" cellpadding="0" cellspacing="0" border="0"  >
			<tr>
				<td width="auto">
					<table width="100%" cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td width="48px">
								<?php echo SF_showHeadPicture('surveys');?>
							</td>
							<td class="contentheading" width="100%" valign="middle" style="vertical-align:middle ">
								&nbsp;<?php echo $sf_lang["SF_SET_DEF_ANSWERS"]?>
							</td>
							<td align="right" valign="top" style="vertical-align:top ">
								<?php SF_showTopMenu();?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td align="center" style="text-align:center">
				<span id="SF_toolbar_tooltip">&nbsp;</span><br/>
				<?php $toolbar = array();
					  $toolbar[] = array('btn_type' => 'save', 'btn_js' => "javascript:submitbutton('save_default');");
					  $toolbar[] = array('btn_type' => 'cancel', 'btn_js' => "javascript:submitbutton('cancel_default');"); 
					  echo ShowToolbar($toolbar); 
				?>
				</td>
			</tr>
			<tr>
				<td width="100%">
				<table width="100%"><tr><td align="left">&nbsp;</td>
				<td align="right">&nbsp;</td>
				</tr>
				</table>
				</td>
			</tr>
		</table>	
	<?php
		switch ($row->sf_qtype) {
			case '1': ?>
				<div align='left' style='padding-left:5px;text-align:left;'>
				<table cellpadding=0 cellspacing=0 style="width: 100%;"  >
				<tr>
					<td valign="top" class="sectiontableheader" ><?php echo $row->sf_qtext?></td>
				</tr>
				<tr><td>
				<table border=1 cellpadding=3 cellspacing=2 style="width: auto;">
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
				<table cellpadding=0 cellspacing=0 style="width: 100%;"  >
				<tr>
					<td valign="top" class="sectiontableheader" colspan="2"><?php echo $row->sf_qtext?></td>
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
				<table cellpadding=0 cellspacing=0 style="width: 100%;"  >
				<tr>
					<td valign="top" class="sectiontableheader" colspan="2"><?php echo $row->sf_qtext?></td>
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
				<table cellpadding=0 cellspacing=0 style="width: 100%;"  >
				<tr>
					<td valign="top" class="sectiontableheader"><?php echo $row->sf_qtext?></td>
				</tr>
				<tr><td>
				<table cellpadding=0 cellspacing=0  style="width: auto;"  >
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
				<table cellpadding=0 cellspacing=0 style="width: 100%;"  >
				<tr>
					<td valign="top" class="sectiontableheader"><?php echo $row->sf_qtext?></td>
				</tr>
				<tr><td>
				<table style="width: auto;" border="0">
				<?php 
				$make_select = array();
				foreach($lists['main_data'] as $main) { 
					$tmp = '';
					$tmp .= "<option value ='0' selected='selected' > ".$sf_lang['SF_SELECT_RANK'] ." </option>";
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
		<input type="hidden" name="Itemid" value="<?php echo $Itemid?>" />
		</form><br/><br/>
		</div>
	<?php
	}
	function SF_editIScale( &$row, &$lists, $option ) {
		global $task, $Itemid, $Itemid_s, $my, $sf_lang, $mosConfig_live_site;

		?>
		<script type="text/javascript" src="/media/system/js/core.js"></script>
		<script type="text/javascript" src="components/com_surveyforce/overlib_mini.js"></script>
		<script language="javascript" type="text/javascript">
		<!--

		function ReAnalize_tbl_Rows( start_index, tbl_id ) {
			start_index = 1;
			var tbl_elem = getObj(tbl_id);
			if (tbl_elem.rows[start_index]) {
				var count = start_index; var row_k = 2 - start_index%2;
				for (var i=start_index; i<tbl_elem.rows.length; i++) {
					tbl_elem.rows[i].cells[0].innerHTML = count;
					Redeclare_element_inputs(tbl_elem.rows[i].cells[1]);
					if (i > 1) { 
						tbl_elem.rows[i].cells[3].innerHTML = '<a href="javascript: void(0);" onClick="javascript:Up_tbl_row(this); return false;" title="Move Up"><img src="components/com_surveyforce/images/toolbar/btn_uparrow.png" width="16" height="16" border="0" alt="Move Up"></a>';
					} else { tbl_elem.rows[i].cells[3].innerHTML = ''; }
					if (i < (tbl_elem.rows.length - 1)) {
						tbl_elem.rows[i].cells[4].innerHTML = '<a href="javascript: void(0);" onClick="javascript:Down_tbl_row(this); return false;" title="Move Down"><img src="components/com_surveyforce/images/toolbar/btn_downarrow.png" width="16" height="16" border="0" alt="Move Down"></a>';;
					} else { tbl_elem.rows[i].cells[4].innerHTML = ''; }
					tbl_elem.rows[i].className = 'sectiontableentry'+row_k;
					count++;
					row_k = 3 - row_k;
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
				cell3.innerHTML = '<a href="javascript: void(0);" onClick="javascript:Delete_tbl_row(this); return false;" title="Delete"><img src="components/com_surveyforce/images/publish_x.png" width="12" height="12" border="0" alt="Delete"></a>';
				cell4.innerHTML = '<a href="javascript: void(0);" onClick="javascript:Up_tbl_row(this); return false;" title="Move Up"><img src="components/com_surveyforce/images/toolbar/btn_uparrow.png" width="16" height="16" border="0" alt="Move Up"></a>';
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
				cell3.innerHTML = '<a href="javascript: void(0);" onClick="javascript:Delete_tbl_row(this); return false;" title="Delete"><img src="components/com_surveyforce/images/publish_x.png" width="12" height="12" border="0" alt="Delete"></a>';
				cell4.innerHTML = '<a href="javascript: void(0);" onClick="javascript:Up_tbl_row(this); return false;" title="Move Up"><img src="components/com_surveyforce/images/toolbar/btn_uparrow.png" width="16" height="16" border="0" alt="Move Up"></a>';
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
				alert("Please enter text to the field.");return;
			}
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
			cell3.innerHTML = '<a href="javascript: void(0);" onClick="javascript:Delete_tbl_row(this); return false;" title="Delete"><img src="components/com_surveyforce/images/publish_x.png" width="12" height="12" border="0" alt="Delete"></a>';
			cell4.innerHTML = '<a href="javascript: void(0);" onClick="javascript:Up_tbl_row(this); return false;" title="Move Up"><img src="components/com_surveyforce/images/toolbar/btn_uparrow.png" width="16" height="16" border="0" alt="Move Up"></a>';
			cell5.innerHTML = '';
			row.appendChild(cell1);
			row.appendChild(cell2);
			row.appendChild(cell3);
			row.appendChild(cell4);
			row.appendChild(cell5);
			row.appendChild(cell6);
			ReAnalize_tbl_Rows(tbl_elem.rows.length - 2, tbl_id);
		}

		function submitbutton(pressbutton) {
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
				alert( "<?php echo $sf_lang["SF_ALERT_IMP_SCALE_MUST_HAVE"]?>" );
			} 
			else {
				submitform( pressbutton );
			}
		}
		//-->
		</script>
		<div class="contentpane surveyforce">
		<form action="<?php echo SFRoute("index.php?option=$option{$Itemid_s}")?>" method="post" name="adminForm" id="adminForm">
		<table width="100%" cellpadding="0" cellspacing="0" border="0"  >
			<tr>
				<td width="auto">
					<table width="100%" cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td width="48px">
								<?php echo SF_showHeadPicture('surveys');?>
							</td>
							<td class="contentheading" width="100%" valign="middle" style="vertical-align:middle ">
								&nbsp;<?php echo $sf_lang["SF_NEW_IMP_SCALE"]?>
							</td>
							<td align="right" valign="top" style="vertical-align:top ">
								<?php SF_showTopMenu();?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td align="center" style="text-align:center">
				<span id="SF_toolbar_tooltip">&nbsp;</span><br/>
				<?php $toolbar = array();
					  $toolbar[] = array('btn_type' => 'save', 'btn_js' => "javascript:submitbutton('save_iscale_A');");
					  $toolbar[] = array('btn_type' => 'back', 'btn_js' => "javascript:submitbutton('cancel_iscale_A');"); 
					  echo ShowToolbar($toolbar); 
				?>
				</td>
			</tr>
			<tr>
				<td width="100%">
				<table width="100%"><tr><td align="left">&nbsp;</td>
				<td align="right">&nbsp;</td>
				</tr>
				</table>
				</td>
			</tr>
		</table>
		
		<table width="100%" cellpadding="2" cellspacing="0" border="0"  >
			<tr>
				<td colspan="2" class="sectiontableheader"><?php echo $sf_lang["SF_IMP_SCALE_DETAILS"]?></td>
			</tr>
			<tr>
				<td align="left" width="20%" valign="top"><?php echo $sf_lang["SF_QUEST_TEXT"]?>:</td>
				<td><textarea class="text_area" rows="6" cols="60" name="iscale_name"><?php echo $row->iscale_name;?></textarea></td>
			</tr>
		</table>
		<br />
		<table width="100%" cellpadding="2" cellspacing="0" border="0"   id="qfld_tbl">
		<tr>
			<td class="sectiontableheader" width="20px" align="center">#</td>
			<td class="sectiontableheader" width="200px"><?php echo $sf_lang["SF_SCALE_OPTION"]?></td>
			<td width="20px" align="center" class="sectiontableheader"></td>
			<td width="20px" align="center" class="sectiontableheader"></td>
			<td width="20px" align="center" class="sectiontableheader"></td>
			<td class="sectiontableheader" width="auto"></td>
		</tr>
		<?php
		$k = 1; $ii = 1; $ind_last = count($lists['sf_fields']);
		foreach ($lists['sf_fields'] as $frow) { ?>
			<tr class="<?php echo "sectiontableentry$k"; ?>">
				<td align="center"><?php echo $ii?></td>
				<td align="left">
					<?php echo $frow->isf_name?>
					<input type="hidden" name="sf_hid_fields[]" value="<?php echo $frow->isf_name?>">
				</td>
				<td><a href="javascript: void(0);" onClick="javascript:Delete_tbl_row(this); return false;" title="Delete"><img src="components/com_surveyforce/images/publish_x.png" width="12" height="12" border="0" alt="Delete"></a></td>
				<td><?php if ($ii > 1) { ?><a href="javascript: void(0);" onClick="javascript:Up_tbl_row(this); return false;" title="Move Up"><img src="components/com_surveyforce/images/toolbar/btn_uparrow.png" width="16" height="16" border="0" alt="Move Up"></a><?php } ?></td>
				<td><?php if ($ii < $ind_last) { ?><a href="javascript: void(0);" onClick="javascript:Down_tbl_row(this); return false;" title="Move Down"><img src="components/com_surveyforce/images/toolbar/btn_downarrow.png" width="16" height="16" border="0" alt="Move Down"></a><?php } ?></td>
				<td></td>
			</tr>
		<?php
		$k = 3 - $k; $ii ++;
		 } ?>
		</table><br>
		<div style="text-align:left; padding-left:30px ">
			<input id="new_field" class="inputbox" style="width:205px " type="text" name="new_field">
			<input class="button" type="button" name="add_new_field" value="<?php echo $sf_lang["SF_ADD"]?>" onClick="javascript:Add_new_tbl_field('new_field', 'qfld_tbl', 'sf_hid_fields[]');">
		</div>
		<br />
		<input type="hidden" name="option" value="com_surveyforce" />
		<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="Itemid" value="<?php echo $Itemid?>" />
		</form><br/><br/>
		</div>
		<?php
	}
	
	function SF_showListUsers( &$rows, &$lists, &$pageNav, $option ) {
		global $task, $Itemid,$Itemid_s, $my, $sf_lang, $mosConfig_live_site;

		?>
		<script type="text/javascript" src="/media/system/js/core.js"></script>
		<script type="text/javascript" src="components/com_surveyforce/overlib_mini.js"></script>
		<script language="javascript" type="text/javascript">
			function submitbutton(pressbutton) {
				var form = document.adminForm;
				if ( ( (pressbutton == 'view_rep_list')|| (pressbutton == 'invite_users')|| (pressbutton == 'remind_users') || (pressbutton == 'edit_list') || (pressbutton == 'del_list') ) && (form.boxchecked.value == "0")) {
					alert('<?php echo $sf_lang['SF_ALERT_SELECT_ITEM'];?>');
				} else {
					form.task.value = pressbutton;
					form.submit();
				}
			}
		</script>
		<div class="contentpane surveyforce">
		<form action="<?php echo SFRoute("index.php?option=$option{$Itemid_s}")?>" method="post" name="adminForm" id="adminForm">
		<table width="100%" cellpadding="0" cellspacing="0" border="0"  >
			<tr>
				<td width="auto">
					<table width="100%" cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td width="48px">
								<?php echo SF_showHeadPicture('usergroup');?>
							</td>
							<td class="contentheading" width="100%" valign="middle" style="vertical-align:middle ">
								&nbsp;<?php echo $sf_lang["SF_USER_LISTS"]?>
							</td>
							<td align="right" valign="top" style="vertical-align:top ">
								<?php SF_showTopMenu();?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td align="center" style="text-align:center">
				<span id="SF_toolbar_tooltip">&nbsp;</span><br/>
				<?php $toolbar = array();
					  if ($task == 'usergroups') {
					  	$toolbar[] = array('btn_type' => 'email', 'btn_js' => "javascript:submitbutton('emails');");				  	
						$toolbar[] = array('btn_type' => 'spacer', 'btn_js' => "javascript:void(0);");
						$toolbar[] = array('btn_type' => 'invite', 'btn_js' => "javascript:submitbutton('invite_users');");
						$toolbar[] = array('btn_type' => 'remaind', 'btn_js' => "javascript:submitbutton('remind_users');");
						$toolbar[] = array('btn_type' => 'spacer', 'btn_js' => "javascript:void(0);");
						$toolbar[] = array('btn_type' => 'new', 'btn_js' => "javascript:submitbutton('add_list');");
						$toolbar[] = array('btn_type' => 'edit', 'btn_js' => "javascript:submitbutton('edit_list');"); 
						$toolbar[] = array('btn_type' => 'del', 'btn_js' => "javascript:submitbutton('del_list');"); 
					  } elseif ($task == 'rep_list') {
					  	  $toolbar[] = array('btn_type' => 'report', 'btn_js' => "javascript:submitbutton('view_rep_list');");
						  $toolbar[] = array('btn_type' => 'back', 'btn_js' => "javascript:submitbutton('reports');"); 
					  }
					  echo ShowToolbar($toolbar); 
				?>
				</td>
			</tr>
			<tr>
				<td width="100%">
				<table width="100%"><tr><td align="left">&nbsp;</td>
				<td align="right" style="text-align:right"><?php
					$link = "index.php?option=$option{$Itemid_s}&amp;task=usergroups"; 
					echo _PN_DISPLAY_NR . $pageNav->getLimitBox( $link ) . '&nbsp;' ;
					echo $pageNav->writePagesCounter(). '&nbsp;&nbsp;';?>
				</td>
				</tr>
				</table>
				</td>
			</tr>
		</table>
		<br/>
		<table width="100%" cellpadding="0" cellspacing="0" border="0"  >
		<tr>
			<td class="sectiontableheader" width="20px">#</td>
			<td width="20px" class="sectiontableheader"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($rows); ?>);" /></td>
			<td class="sectiontableheader"><?php echo $sf_lang["SF_USER_LISTS"]?></td>
			<td class="sectiontableheader"><?php echo $sf_lang["SF_USERS"]?></td>
			<td class="sectiontableheader"><?php echo $sf_lang["SF_STARTS"]?></td>
			<td class="sectiontableheader"><?php echo $sf_lang["SF_SURVEY"]?></td>
			<td class="sectiontableheader"><?php echo $sf_lang["SF_AUTHOR"]?></td>
			<td class="sectiontableheader"><?php echo $sf_lang["SF_CREATED"]?></td>
		</tr>
		<?php
		$k = 1;
		for ($i=0, $n=count($rows); $i < $n; $i++) {
			$row = $rows[$i];
			$link 	= "#";
			if ($task == 'usergroups') {
				$link 	= SFRoute('index.php?option='.$option."{$Itemid_s}&task=view_users&list_id=". $row->id);
			} elseif ($task == 'rep_list') {
				$link 	= SFRoute('index.php?option='.$option."{$Itemid_s}&task=view_rep_listA&id=". $row->id);
			}
			
			$checked = mosHTML::idBox( $i, $row->id);
			?>
			<tr class="<?php echo "sectiontableentry$k"; ?>">
				<td align="center"><?php echo $pageNav->rowNumber( $i ); ?></td>
				<td><?php echo $checked; ?></td>
				<td align="left">
					<a href="<?php echo $link; ?>" title="<?php echo $sf_lang["SF_VIEWUSERS"]?>">
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
			</tr>
			<?php
			$k = 3 - $k;
		}
		?>
		</table>
		<div align="center">
		<?php 
			$link = "index.php?option=$option{$Itemid_s}&amp;task=usergroups"; 
			echo $pageNav->writePagesLinks($link).'<br/>';
		?>
		</div>
		<input type="hidden" name="option" value="com_surveyforce" />
		<input type="hidden" name="task" value="<?php echo $task?>" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="hidemainmenu" value="0">
		<input type="hidden" name="Itemid" value="<?php echo $Itemid?>" />
		</form><br/><br/>
		</div>
		<?php
	}
	
	function SF_editListUsers( &$rows, &$lists, &$sf_config, $pageNav, $option ) {
		global $task, $Itemid,$Itemid_s, $my, $sf_lang, $mosConfig_live_site;
		?>
		<script type="text/javascript" src="/media/system/js/core.js"></script>
		<script type="text/javascript" src="components/com_surveyforce/overlib_mini.js"></script>
		<script language="javascript" type="text/javascript">
		function submitbutton(pressbutton) {
			var form = document.adminForm;
			if (pressbutton == 'cancel_list') {
				submitform( pressbutton );
				return;
			}
			// do field validation
			if (form.listname.value == ""){
				alert( "<?php echo $sf_lang["SF_ALERT_LIST_MUST_HAVE_NAME"]?>" );
			} else {
				submitform( pressbutton );
			}
		}
		</script>
		<div class="contentpane surveyforce">
		<form action="<?php echo SFRoute("index.php?option=$option{$Itemid_s}")?>" method="post" name="adminForm" id="adminForm">
		<table width="100%" cellpadding="0" cellspacing="0" border="0"  >
			<tr>
				<td width="auto">
					<table width="100%" cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td width="48px">
								<?php echo SF_showHeadPicture('usergroup');?>
							</td>
							<td class="contentheading" width="100%" valign="middle" style="vertical-align:middle ">
								&nbsp;<?php echo ($task == 'add_user'? $sf_lang["SF_LIST_OF_USER"].' - '.$sf_lang["SF_ADD_USERS"]: $sf_lang["SF_LIST_OF_USER"] )?>
							</td>
							<td align="right" valign="top" style="vertical-align:top ">
								<?php SF_showTopMenu();?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td align="center" style="text-align:center">
				<span id="SF_toolbar_tooltip">&nbsp;</span><br/>
				<?php $toolbar = array();
					  $toolbar[] = array('btn_type' => 'save', 'btn_js' => "javascript:submitbutton('".($task == 'add_user'?'save_user':'save_list')."');");
					  if ($task != 'add_user')
						  $toolbar[] = array('btn_type' => 'apply', 'btn_js' => "javascript:submitbutton('apply_list');"); 
					  $toolbar[] = array('btn_type' => 'cancel', 'btn_js' => "javascript:submitbutton('".($task == 'add_user'?'cancel_user':'cancel_list')."');"); 
					  echo ShowToolbar($toolbar); 
				?>
				</td>
			</tr>
			<tr>
				<td width="100%">
				<table width="100%"><tr><td align="left">&nbsp;</td>
				<td align="right" style="text-align:right">&nbsp;</td>
				</tr>
				</table>
				</td>
			</tr>
		</table>
		<table width="100%" cellpadding="2" cellspacing="2" border="0"  >
		<tr>
			<td colspan="2" class="sectiontableheader"><?php echo $sf_lang["SF_LIST_DETAILS"]?></td>
		</tr>
		<tr>
			<td align="left" width="35%" valign="top"><?php echo $sf_lang["SF_LIST_NAME"]?>:</td>
			<td><input type="text" class="inputbox" size="35" name="listname" value="<?php echo $lists['listname'] ?>"></td>
		</tr>
		<tr>
		<td><?php echo $sf_lang["SF_SURVEY"]?>:</td><td><?php echo $lists['survey']; ?></td>
		</tr>
			<?php if ($sf_config->get('sf_enable_lms_integration')) { ?>
		<tr>
			<td valign="top"><input type="checkbox" name="is_add_lms" value="1" checked><?php echo $sf_lang["SF_ADD_LMS_GROUP"]?>:</td>
			<td><?php echo $lists['lms_groups']?></td>
		</tr>
		<?php }?>
		<tr>
			<td><input type="checkbox" name="is_add_manually" value="1" checked><?php echo $sf_lang["SF_ADD_MANUALLY"]?>:</td><td></td>
		</tr>
		</table>		
		<table width="100%" cellpadding="0" cellspacing="0" border="0"  >
		<tr>
			<td class="sectiontableheader" width="20px">#</td>
			<td width="20px" class="sectiontableheader"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($rows); ?>);" /></td>
			<td class="sectiontableheader"><?php echo $sf_lang["SF_NAME"]?></td>
			<td class="sectiontableheader"><?php echo $sf_lang["SF_USERNAME"]?></td>
			<td class="sectiontableheader"><?php echo $sf_lang["SF_EMAIL"]?></td>
			<td class="sectiontableheader"><?php echo $sf_lang["SF_LAST_VISIT"]?></td>
			<td class="sectiontableheader"></td>
		</tr>
		<?php
		$k = 1;
		for ($i=0, $n=count($rows); $i < $n; $i++) {
			$row = $rows[$i];

			$checked = mosHTML::idBox( $i, $row->id);
			?>
			<tr class="<?php echo "sectiontableentry$k"; ?>">
				<td align="center"><?php echo $pageNav->rowNumber( $i ); ?></td>
				<td><?php echo $checked; ?></td>
				<td align="left">
					<?php echo $row->name; ?>
				</td>
				<td align="left">
					<?php echo $row->username; ?>
				</td>
				<td align="left">
					<?php echo $row->email; ?>
				</td>
				<td align="left">
					<?php echo $row->lastvisitDate; ?>
				</td>
				<td align="left">
					
				</td>
			</tr>
			<?php
			$k = 3 - $k;
		}
		?>
		</table>
		<div align="center">
		<?php 
		
			$link = "index.php?option=$option&amp;task=add_list{$Itemid_s}"; 
			echo $pageNav->writePagesLinks($link).'<br/>';
			echo _PN_DISPLAY_NR . $pageNav->writeLimitBox( $link ) . '&nbsp;' ;
			echo $pageNav->writePagesCounter(). '&nbsp;&nbsp;';
		?>
		</div>
		<input type="hidden" name="option" value="com_surveyforce" />
		<input type="hidden" name="task" value="add_list" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="hidemainmenu" value="0">
		<input type="hidden" name="id" value="<?php echo $lists['listid']; ?>" />
		<input type="hidden" name="Itemid" value="<?php echo $Itemid?>" />
		<input type="hidden" name="sf_author_id" value="<?php echo $my->id?>" />				
		</form><br/><br/>
		</div>
		<?php
	}
	
	function SF_show_Users( &$rows, &$lists, &$pageNav, $option ) {
		global $task, $Itemid, $Itemid_s, $my, $sf_lang, $mosConfig_live_site;

		?>
		<script type="text/javascript" src="/media/system/js/core.js"></script>
		<script type="text/javascript" src="components/com_surveyforce/overlib_mini.js"></script>
		<script language="javascript" type="text/javascript">
			function submitbutton(pressbutton) {
				var form = document.adminForm;
				if ( ((pressbutton == 'del_user') ) && (form.boxchecked.value == "0")) {
					alert('<?php echo $sf_lang['SF_ALERT_SELECT_ITEM'];?>');
				} else {
					form.task.value = pressbutton;
					form.submit();
				}
			}
		</script>
		<div class="contentpane surveyforce">
		<form action="<?php echo SFRoute("index.php?option=$option{$Itemid_s}")?>" method="post" name="adminForm" id="adminForm">
		<table width="100%" cellpadding="0" cellspacing="0" border="0"  >
			<tr>
				<td width="auto">
					<table width="100%" cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td width="48px">
								<?php echo SF_showHeadPicture('usergroup');?>
							</td>
							<td class="contentheading" width="100%" valign="middle" style="vertical-align:middle ">
								&nbsp;<?php echo $sf_lang["SF_USERS"]?> ( <?php echo $lists['listname']?> )
							</td>
							<td align="right" valign="top" style="vertical-align:top ">
								<?php SF_showTopMenu();?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td align="center" style="text-align:center">
				<span id="SF_toolbar_tooltip">&nbsp;</span><br/>
				<?php $toolbar = array();
					  $toolbar[] = array('btn_type' => 'new', 'btn_js' => "javascript:submitbutton('add_user');", 'btn_str' => 'Add New User');
					  $toolbar[] = array('btn_type' => 'del', 'btn_js' => "javascript:submitbutton('del_user');"); 
					  $toolbar[] = array('btn_type' => 'back', 'btn_js' => "javascript:submitbutton('cancel_list');"); 
					  echo ShowToolbar($toolbar); 
				?>
				</td>
			</tr>
			<tr>
				<td width="100%">
				<table width="100%"><tr><td align="left"><?php echo $lists['userlists'];?></td>
				<td align="right" style="text-align:right"><?php
					$link = "index.php?option=$option{$Itemid_s}&amp;task=view_users"; 
					echo _PN_DISPLAY_NR . $pageNav->getLimitBox( $link ) . '&nbsp;' ;
					echo $pageNav->writePagesCounter(). '&nbsp;&nbsp;';?></td>
				</tr>
				</table>
				</td>
			</tr>
		</table>
				
		<table width="100%" cellpadding="0" cellspacing="0" border="0"  >
		<tr>
			<td class="sectiontableheader" width="20px">#</td>
			<td class="sectiontableheader" width="20px"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($rows); ?>);" /></td>
			<td class="sectiontableheader"><?php echo $sf_lang["SF_NAME"]?></td>
			<td class="sectiontableheader"><?php echo $sf_lang["SF_USERNAME"]?></td>
			<td class="sectiontableheader"><?php echo $sf_lang["SF_EMAIL"]?></td>
			<td class="sectiontableheader"></td>
		</tr>
		<?php
		$k = 1;
		for ($i=0, $n=count($rows); $i < $n; $i++) {
			$row = $rows[$i];

			$checked = mosHTML::idBox( $i, $row->id);
			?>
			<tr class="<?php echo "sectiontableentry$k"; ?>">
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
			$k = 3 - $k;
		}
		?>
		</table>
		<div align="center">
		<?php 
			$link = "index.php?option=$option{$Itemid_s}&amp;task=view_users"; 
			echo $pageNav->writePagesLinks($link).'<br/>';
		?>
		</div>

		<input type="hidden" name="option" value="com_surveyforce" />
		<input type="hidden" name="task" value="view_users" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="hidemainmenu" value="0">
		<input type="hidden" name="Itemid" value="<?php echo $Itemid?>" />
		<input type="hidden" name="list_id" value="<?php echo $lists['listid']?>" />		
		</form><br/><br/>
		</div>
		<?php
	}
	
	function SF_ViewReports( &$rows, &$lists, &$pageNav, $option ) {
		global $task, $Itemid, $Itemid_s,$my, $sf_lang, $mosConfig_live_site;
		$sf_config = new mos_Survey_Force_Config( );
		?>
		<script type="text/javascript" src="/media/system/js/core.js"></script>
		<script type="text/javascript" src="components/com_surveyforce/overlib_mini.js"></script>
		<script language="javascript" type="text/javascript">
		<!--
		function submitbutton(pressbutton) {
			var form = document.adminForm;
			
			if (pressbutton == 'rep_pdf') { 
				form.target = '_blank';
				submitform( pressbutton );
				return;
			}
			
			if ( ((pressbutton == 'view_result_c')||(pressbutton == 'del_rep')) && (form.boxchecked.value == "0")) {
				alert('<?php echo $sf_lang['SF_ALERT_SELECT_ITEM'];?>');
			}
			else {
				form.target = '';
				submitform( pressbutton );
			}
		}
		//-->
		</script>
		<div class="contentpane surveyforce">
		<form action="<?php echo SFRoute("index.php?option=$option{$Itemid_s}")?>" method="post" name="adminForm" id="adminForm">
		<table width="100%" cellpadding="0" cellspacing="0" border="0"  >
			<tr>
				<td width="auto">
					<table width="100%" cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td width="48px">
								<?php echo SF_showHeadPicture('report');?>
							</td>
							<td class="contentheading" width="100%" valign="middle" style="vertical-align:middle ">
								&nbsp;<?php echo $sf_lang["SF_REPORTS"]?>
							</td>
							<td align="right" valign="top" style="vertical-align:top ">
								<?php SF_showTopMenu();?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td align="center" style="text-align:center">
				<span id="SF_toolbar_tooltip">&nbsp;</span><br/>
				<?php $toolbar = array();
					if (!$sf_config->get('sf_enable_jomsocial_integration')) { 
						$toolbar[] = array('btn_type' => 'report', 'btn_js' => "javascript:submitbutton('cross_rep');", 'btn_str' => $sf_lang['SF_CROSS_REPORT']);
						$toolbar[] = array('btn_type' => 'report', 'btn_js' => "javascript:submitbutton('i_report');", 'btn_str' => $sf_lang['SF_CSV_REPORT']);
						$toolbar[] = array('btn_type' => 'spacer', 'btn_js' => "javascript:void(0);");	  
						$toolbar[] = array('btn_type' => 'preview', 'btn_js' => "javascript:submitbutton('rep_surv');", 'btn_str' => $sf_lang["SF_REP_SURVEYS"]);
						$toolbar[] = array('btn_type' => 'report', 'btn_js' => "javascript:submitbutton('rep_pdf');", 'btn_str' => $sf_lang['SF_PDF_REPORT']);
						$toolbar[] = array('btn_type' => 'report', 'btn_js' => "javascript:submitbutton('rep_csv');", 'btn_str' => $sf_lang['SF_CSV_REPORT_SUM']);
						$toolbar[] = array('btn_type' => 'preview', 'btn_js' => "javascript:submitbutton('view_result_c');", 'btn_str' => $sf_lang['SF_REPORT']);
						$toolbar[] = array('btn_type' => 'del', 'btn_js' => "javascript:submitbutton('del_rep');", 'btn_str' => $sf_lang["SF_DELETE"]);
					 } else {
					 	$toolbar[] = array('btn_type' => 'report', 'btn_js' => "javascript:submitbutton('rep_csv');", 'btn_str' => $sf_lang['SF_CSV_REPORT']);
					    $toolbar[] = array('btn_type' => 'preview', 'btn_js' => "javascript:submitbutton('view_result_c');", 'btn_str' => $sf_lang['SF_REPORT']);
					 	$toolbar[] = array('btn_type' => 'del', 'btn_js' => "javascript:submitbutton('del_rep');", 'btn_str' => $sf_lang["SF_DELETE"]);
					 }
					  echo ShowToolbar($toolbar); 
				?>
				</td>
			</tr>
			<tr>
				<td width="100%">
				<table width="100%"><tr><td align="left" >
					<table border="0"><tr>
						<td><?php echo $lists['filt_status'];?></td>
						<td><?php echo $lists['survey'];?></td>
						<td><?php echo $lists['filt_utype'];?></td>
						</tr>
						<tr>
						<td colspan="2" align="right"></td>
						<td><?php echo $lists['filt_ulist'];?></td>
						</tr>
						<tr><td colspan="3">
							<table width="100%">
							<?php
								$jj = 0;
								foreach ($lists['filter_quest'] as $list1) { ?>
								<tr>
									<td width="20%">
										<?php echo $sf_lang['SF_CHOOSE_FROM_QUEST']?>
									</td>
									<td><?php echo $list1;?></td>
									<td width="20%">
									<?php if (isset($lists['filter_quest_ans'][$jj])) { ?>
										<?php echo $sf_lang['SF_WHERE_THE_ANSWER']?></td><td><?php echo $lists['filter_quest_ans'][$jj];?>
									<?php } else { echo "</td>&nbsp;<td>&nbsp;";}
								?></td></tr><?php
								 $jj ++;?>
							<?php }?>
							</table>
						</td></tr>
					</table>
					</td>
				<td align="right" style="text-align:right"><?php
					$link = "index.php?option=$option{$Itemid_s}&amp;task=reports"; 
					echo _PN_DISPLAY_NR . $pageNav->getLimitBox( $link ) . '<br/>' ;
					echo $pageNav->writePagesCounter(). '&nbsp;&nbsp;';?></td>
				</tr>
				</table>
				</td>
			</tr>
		</table>

		<table width="100%" cellpadding="2" cellspacing="0" border="0"  >
		<tr>
			<td class="sectiontableheader" width="20">#</td>
			<td width="20" class="sectiontableheader"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($rows); ?>);" /></td>
			<td class="sectiontableheader"><?php echo $sf_lang['SF_DATE']?></td>
			<td class="sectiontableheader"><?php echo $sf_lang['SF_STATUS']?></td>
			<td class="sectiontableheader"><?php echo $sf_lang['SF_SURVEY']?></td>
			<td class="sectiontableheader"><?php echo $sf_lang['SF_USERTYPE']?></td>
			<td class="sectiontableheader"><?php echo $sf_lang['SF_USER_INFO']?></td>
		</tr>
		<?php
		$k = 1;
		for ($i=0, $n=count($rows); $i < $n; $i++) {
			$row = $rows[$i];

			$link 	= SFRoute('index.php?option='.$option.'&task=view_result'.$Itemid_s.'&id='. $row->id);
			$checked = mosHTML::idBox( $i, $row->id);
			?>
			<tr class="<?php echo "sectiontableentry$k"; ?>">
				<td align="center"><?php echo $pageNav->rowNumber( $i ); ?></td>
				<td><?php echo $checked; ?></td>
				<td align="left">
					<a href="<?php echo $link; ?>" title="View Results">
						<?php echo mosFormatDate( $row->sf_time, _CURRENT_SERVER_TIME_FORMAT ); ?>
					</a>
				</td>
				<td align="left">
					<?php echo ($row->is_complete)?$sf_lang['SF_COMPLETED']:$sf_lang['SF_NOT_COMPLETED']; ?>
				</td>
				<td align="left">
					<?php echo $row->survey_name; ?>
				</td>
				<td align="left">
					<?php switch($row->usertype) {
							case '0': echo $sf_lang['SF_GUEST']; break;
							case '1': echo $sf_lang['SF_REGISTERED_USER']; break;
							case '2': echo $sf_lang['SF_INVITED_USER']; break;
						} ?>
				</td>
				<td align="left">
					<?php switch($row->usertype) {
							case '0': echo $sf_lang['SF_ANONYMOUS']; break;
							case '1': echo $row->reg_username.", ".$row->reg_name." (".$row->reg_email.")"; break;
							case '2': echo $row->inv_name." ".$row->inv_lastname." (".$row->inv_email.")"; break;
						} ?>
				</td>
			</tr>
			<?php
			$k = 3 - $k;
		}
		?>
		</table>
		<div align="center">
		<?php 
			$link = "index.php?option=$option{$Itemid_s}&amp;task=reports"; 
			echo $pageNav->writePagesLinks($link).'<br/>';
		?>
		</div>

		<input type="hidden" name="option" value="com_surveyforce" />
		<input type="hidden" name="task" value="reports" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="hidemainmenu" value="0">
		<input type="hidden" name="Itemid" value="<?php echo $Itemid?>" />
		</form><br/><br/>
		</div>
		<?php
	}
	
	function SF_ViewRepResult( $option, $start_data, $survey_data, $questions_data ) {
		global $task, $Itemid,$Itemid_s, $my, $sf_lang, $mosConfig_live_site;
		
		?>
		<script type="text/javascript" src="/media/system/js/core.js"></script>
		<script type="text/javascript" src="components/com_surveyforce/overlib_mini.js"></script>
		<script language="javascript" type="text/javascript">
		<!--
		function submitbutton(pressbutton) {
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
		<div class="contentpane surveyforce">
		<form action="<?php echo SFRoute("index.php?option=$option{$Itemid_s}")?>" method="post" name="adminForm" id="adminForm">
		<table width="100%" cellpadding="0" cellspacing="0" border="0"  >
			<tr>
				<td width="auto">
					<table width="100%" cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td width="48px">
								<?php echo SF_showHeadPicture('report');?>
							</td>
							<td class="contentheading" width="100%" valign="middle" style="vertical-align:middle ">
								&nbsp;<?php echo $sf_lang['SF_RESULTS']?>
							</td>
							<td align="right" valign="top" style="vertical-align:top ">
								<?php SF_showTopMenu();?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td align="center" style="text-align:center">
				<span id="SF_toolbar_tooltip">&nbsp;</span><br/>
				<?php $toolbar = array();
					  $toolbar[] = array('btn_type' => 'report', 'btn_js' => "javascript:submitbutton('rep_print');", 'btn_str' => $sf_lang['SF_PDF_REPORT'] );
					  $toolbar[] = array('btn_type' => 'back', 'btn_js' => "javascript:submitbutton('reports');"); 
					  echo ShowToolbar($toolbar); 
				?>
				</td>
			</tr>
			<tr>
				<td width="100%">
				<table width="100%"><tr><td align="left">&nbsp;</td>
				<td align="right" style="text-align:right">&nbsp;</td>
				</tr>
				</table>
				</td>
			</tr>
		</table>
		
		<input type="hidden" name="option" value="com_surveyforce" />
		<input type="hidden" name="id" value="<?php echo $start_data[0]->id; ?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="Itemid" value="<?php echo $Itemid?>" />
		</form><br/><br/>
		<table width="100%" cellpadding="2" cellspacing="0" border="0"  >
		<tr>
			<td colspan="2" align="left" class="sectiontableheader"><?php echo $sf_lang['SF_SURVEY_INFORMATION']?></td>
		</tr>
		<tr><td><b><?php echo $sf_lang["SF_NAME"]?>: </b><?php echo $survey_data[0]->sf_name?><br>
		<b><?php echo $sf_lang["SF_DESCRIPTION"]?>: </b><br><?php echo nl2br($survey_data[0]->sf_descr)?><br>
		<b><?php echo $sf_lang['SF_START_AT']?>: </b><?php echo mosFormatDate( $start_data[0]->sf_time, _CURRENT_SERVER_TIME_FORMAT )?><br>
		<b><?php echo $sf_lang['SF_USER']?>:</b>
						<?php switch($start_data[0]->usertype) {
							case '0': echo $sf_lang['SF_ANONYMOUS']; break;
							case '1': echo $sf_lang['SF_REGISTERED_USER'].": ".$start_data[0]->reg_username.", ".$start_data[0]->reg_name." (".$start_data[0]->reg_email.")"; break;
							case '2': echo $sf_lang['SF_INVITED_USER'].": ".$start_data[0]->inv_name." ".$start_data[0]->inv_lastname." (".$start_data[0]->inv_email.")"; break;
						} ?>

		</td></tr>
		</table>
		<br>

		<?php
		foreach ($questions_data as $qrow) { 
			$k = 1;?>
		<table width="100%" cellpadding="2" cellspacing="0" border="0"  >
		<tr>
			<td colspan="2" align="left" class="sectiontableheader"><?php echo $qrow->sf_qtext?></td>
		</tr>
		<?php 
			switch ($qrow->sf_qtype) {
				case 2:
				case 3:
					foreach ($qrow->answer as $arow) {
						$img_ans = $arow->alt_text ? "<img src='components/com_surveyforce/images/buttons/btn_apply.png' width='12' height='12' border='0' />" : '';
						echo "<tr class='sectiontableentry".$k."'><td width='300px'>" . $arow->f_text . "</td><td>" . $img_ans . "</td></tr>";
						$k = 3 - $k;
					}
				break;
				case 1:	echo "<tr class='sectiontableentry".$k."'><td colspan=2><b>Scale: </b>" . $qrow->scale . "</td></tr>";$k = 1 - $k;
				case 5:
				case 6:
				case 9:
					foreach ($qrow->answer as $arow) {
						echo "<tr class='sectiontableentry".$k."'><td width='300px'>" . $arow->f_text . "</td><td>" . $arow->alt_text . "</td></tr>";
						$k = 3 - $k;
					}
					break;
				case 4:
					if (isset($qrow->answer_count)){
						$tmp = $sf_lang['COM_SF_FIRST_ANSWER'];
						for($ii = 1; $ii <= $qrow->answer_count; $ii++) {
							if ($ii == 2) $tmp = $sf_lang['COM_SF_SECOND_ANSWER'];
							elseif($ii == 3)	$tmp = $sf_lang['COM_SF_THIRD_ANSWER'];
							elseif ($ii > 3) $tmp = $ii . $sf_lang['COM_SF_X_ANSWER'];
							foreach($qrow->answer as $answer) {
								if ($answer->ans_field == $ii) {
									echo "<tr class='sectiontableentry".$k."'><td width='300px'>".$tmp.nl2br(($answer->ans_txt == ''?' '.$sf_lang['SURVEY_NO_ANSWER']:$answer->ans_txt))."</td><td>&nbsp;</td></tr>";
									$k = 3 - $k;
									$tmp = -1;
									}
							}
							if ($tmp != -1)	{
								echo "<tr class='sectiontableentry".$k."'><td width='300px'>".$tmp." ".$sf_lang['SURVEY_NO_ANSWER']."</td><td>&nbsp;</td></tr>";
								$k = 3 - $k;
							}
						}
					}
					else {
						echo "<tr class='sectiontableentry".$k."'><td width='300px'>".nl2br($qrow->answer)."</td><td>&nbsp;</td></tr>";
					}
					break;
				default:
					echo "<tr class='sectiontableentry".$k."'><td width='300px'>".nl2br($qrow->answer)."</td><td>&nbsp;</td></tr>";
				break;
			}
			?>
		</table>
		<?php if ($qrow->sf_impscale) {?>
			<table width="100%" cellpadding="2" cellspacing="0" border="0"  >
			<tr>
				<td colspan="2" align="left"><b><?php echo $qrow->iscale_name?></b></td>
			</tr>
			<?php
				foreach ($qrow->answer_imp as $arow) {
					$img_ans = $arow->alt_text ? "<img src='components/com_surveyforce/images/buttons/btn_apply.png' width='12' height='12' border='0' />" : '';
					echo "<tr class='sectiontableentry".$k."'><td width='300px'>" . $arow->f_text . "</td><td>" . $img_ans . "</td></tr>";
					$k = 3 - $k;
				}
			?>
			</table>
		<?php } ?>
		<br>
		<?php }
		?></div><?php
	}
	
	function SF_ViewRepSurv_List( $option, $survey_data, $questions_data, $is_list = 0, $list_id = 0){
		global $task, $Itemid,$Itemid_s, $my, $sf_lang, $mosConfig_live_site;
		
		?>
		<script type="text/javascript" src="/media/system/js/core.js"></script>
		<script type="text/javascript" src="components/com_surveyforce/overlib_mini.js"></script>
		<script language="javascript" type="text/javascript">
		<!--
		function submitbutton(pressbutton) {
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
		<div class="contentpane surveyforce">
		<form action="<?php echo SFRoute("index.php?option=$option{$Itemid_s}")?>" method="post" name="adminForm" id="adminForm">
		<table width="100%" cellpadding="0" cellspacing="0" border="0"  >
			<tr>
				<td width="auto">
					<table width="100%" cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td width="48px">
								<?php echo SF_showHeadPicture('report');?>
							</td>
							<td class="contentheading" width="100%" valign="middle" style="vertical-align:middle ">
								&nbsp;<?php if ($is_list == 1) { echo $sf_lang['SF_USERS'];} else { echo $sf_lang['SF_SURVEY'];}?> <?php echo $sf_lang['SF_RESULTS']?>
							</td>
							<td align="right" valign="top" style="vertical-align:top ">
								<?php SF_showTopMenu();?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td align="center" style="text-align:center">
				<span id="SF_toolbar_tooltip">&nbsp;</span><br/>
				<?php $toolbar = array();
					  $toolbar[] = array('btn_type' => 'report', 'btn_js' => "javascript:submitbutton('rep_surv_print');", 'btn_str' => $sf_lang['SF_PDF_REPORT']);
					  $toolbar[] = array('btn_type' => 'back', 'btn_js' => "javascript:submitbutton('rep_surv');"); 
					  echo ShowToolbar($toolbar); 
				?>
				</td>
			</tr>
			<tr>
				<td width="100%">
				<table width="100%"><tr><td align="left">&nbsp;</td>
				<td align="right" style="text-align:right">&nbsp;</td>
				</tr>
				</table>
				</td>
			</tr>
		</table>
		<input type="hidden" name="option" value="com_surveyforce" />
		<input type="hidden" name="id" value="<?php echo ($task == 'view_rep_list')?$list_id:$survey_data->id; ?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="Itemid" value="<?php echo $Itemid?>" />
		</form>
		<table width="100%" cellpadding="0" cellspacing="0" border="0"  >
		<tr>
			<td class="sectiontableheader" colspan="2" align="left"><?php echo $sf_lang['SF_SURVEY_INFORMATION']?></td>
		</tr>
		<tr><td><b><?php echo $sf_lang['SF_NAME']?>: </b><?php echo $survey_data->sf_name?><br>
		<b><?php echo $sf_lang['SF_DESCRIPTION']?>: </b><br><?php echo nl2br($survey_data->sf_descr)?><br>
		</td></tr>
		</table>
		<br>
		<table width="100%" cellpadding="0" cellspacing="0" border="0"  >
		<tr>
			<td class="sectiontableheader" align="left"><?php echo $sf_lang['SF_SURVEY_INFORMATION']?></td>
		</tr>
		</table>
		<?php if ($is_list == 1) { ?>
			<table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="250px" valign="top">
			<img src="<?php echo $mosConfig_live_site?>/administrator/components/com_surveyforce/includes/draw_grid.php?total=<?php echo ($survey_data->total_starts > $survey_data->total_inv_users)?$survey_data->total_starts:$survey_data->total_inv_users?>&grids=<?php echo $survey_data->total_inv_users.','.$survey_data->total_starts.','.$survey_data->total_completes?>">
	
			</td><td valign="top"><div style="padding-top:1px ">
			<table   cellpadding="0" cellspacing="0">
			<tr class="row1" height="25px"><td><b><?php echo $survey_data->total_inv_users?></b> - <?php echo $sf_lang['SF_TOTAL_INVITED']?></td></tr>
			<tr class="row1" height="25px"><td><b><?php echo $survey_data->total_starts?></b> - <?php echo $sf_lang['SF_TOTAL_STARTS']?></td></tr>
			<tr class="row1" height="25px"><td><b><?php echo $survey_data->total_completes?></b> - <?php echo $sf_lang['SF_TOTAL_COMPLETES']?></td></tr>
			</table>
			</td></tr></table>
		<?php } else { ?>
			<table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="250px" valign="top">
			<img src="<?php echo $mosConfig_live_site?>/administrator/components/com_surveyforce/includes/draw_grid.php?total=<?php echo $survey_data->total_starts?>&grids=<?php echo $survey_data->total_starts.','.$survey_data->total_gstarts.','.$survey_data->total_rstarts.','.$survey_data->total_istarts.','.$survey_data->total_completes.','.$survey_data->total_gcompletes.','.$survey_data->total_rcompletes.','.$survey_data->total_icompletes?>">
	
			</td><td valign="top"><div style="padding-top:1px ">
			<table   cellpadding="0" cellspacing="0">
			<tr class="sectiontableentry2" height="25px"><td><b><?php echo $survey_data->total_starts?></b> - <?php echo $sf_lang['SF_TOTAL_STARTS']?></td></tr>
			<tr class="sectiontableentry2" height="25px"><td><b><?php echo $survey_data->total_gstarts?></b> - <?php echo $sf_lang['SF_TOTAL_STRST_GUEST']?></td></tr>
			<tr class="sectiontableentry2" height="25px"><td><b><?php echo $survey_data->total_rstarts?></b> - <?php echo $sf_lang['SF_TOTAL_STRAT_REG']?></td></tr>
			<tr class="sectiontableentry2" height="25px"><td><b><?php echo $survey_data->total_istarts?></b> - <?php echo $sf_lang['SF_TOTAL_START_INVITED']?></td></tr>
			<tr class="sectiontableentry2" height="25px"><td><b><?php echo $survey_data->total_completes?></b> - <?php echo $sf_lang['SF_TOTAL_COMPLETES']?></td></tr>
			<tr class="sectiontableentry2" height="25px"><td><b><?php echo $survey_data->total_gcompletes?></b> - <?php echo $sf_lang['SF_TOTAL_COMPL_GUEST']?></td></tr>
			<tr class="sectiontableentry2" height="25px"><td><b><?php echo $survey_data->total_rcompletes?></b> - <?php echo $sf_lang['SF_TOTAL_COMPL_REG']?></td></tr>
			<tr class="sectiontableentry2" height="25px"><td><b><?php echo $survey_data->total_icompletes?></b> - <?php echo $sf_lang['SF_TOTAL_COMPL_INVITED']?></td></tr>
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
						$tmp = $sf_lang['COM_SF_FIRST_ANSWER'];
						?><table  ><tr><td class="sectiontableheader" align="left"><?php echo $qrow->sf_qtext?></th></tr></table><?php
						for($ii = 1; $ii <= $qrow->answer_count; $ii++) {
							if ($ii == 2) $tmp = $sf_lang['COM_SF_SECOND_ANSWER'];
							elseif($ii == 3)	$tmp = $sf_lang['COM_SF_THIRD_ANSWER'];
							elseif ($ii > 3) $tmp = $ii.$sf_lang['COM_SF_X_ANSWER'];
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
								<table  ><tr><td class="sectiontableheader" align="left"><b><?php echo $tmp?></b></td></tr></table>
								<table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="250px" valign="top">
								<img src="<?php echo $mosConfig_live_site?>/administrator/components/com_surveyforce/includes/draw_grid.php?total=<?php echo $total?>&grids=<?php echo implode(',',$tmp_data)?>">
								</td><td valign="top"><div style="padding-top:1px ">
								<table   cellpadding="0" cellspacing="0">
								<?php foreach ($qrow->answer[$ii-1] as $arow) {
									echo "<tr class='sectiontableentry2' height='25px'><td><b>".$arow->ans_count."</b> ".$arow->ftext."</td></tr>";
									}?>
								</table></td></tr>
								<?php echo "<tr><td colspan='2'><b>Other answers: </b>" . $qrow->answers_top100[$ii-1] . "</td></tr>"; ?>
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
						<table  ><tr><td class="sectiontableheader" align="left"><?php echo $qrow->sf_qtext?></td></tr></table>
						<table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="250px" valign="top">
						<img src="<?php echo $mosConfig_live_site?>/administrator/components/com_surveyforce/includes/draw_grid.php?total=<?php echo $total?>&grids=<?php echo implode(',',$tmp_data)?>">
						</td><td valign="top"><div style="padding-top:1px ">
						<table   cellpadding="0" cellspacing="0">
						<?php foreach ($qrow->answer as $arow) {
							echo "<tr class='sectiontableentry2' height='25px'><td><b>".$arow->ans_count."</b> ".$arow->ftext."</td></tr>";
							}?>
						</table></td></tr>
						<?php if ($qrow->sf_qtype == 4) {echo "<tr><td colspan='2'><b>Other answers: </b>" . $qrow->answers_top100 . "</td></tr>";} ?>
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
					<table  ><tr><td class="sectiontableheader" align="left"><?php echo $qrow->sf_qtext?></td></tr></table>
					<?php foreach ($qrow->answer as $arows) { 
					$i = 0;
					$tmp_data = array();
					foreach ($arows->full_ans as $arow) {
						$tmp_data[$i] = $arow->ans_count;
						$i++;
					}?>
					<table  ><tr><td class="sectiontableheader" align="left"><?php echo $arows->ftext?></td></tr></table>
					<table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="250px" valign="top">
					<img src="<?php echo $mosConfig_live_site?>/administrator/components/com_surveyforce/includes/draw_grid.php?total=<?php echo $total?>&grids=<?php echo implode(',',$tmp_data)?>">
					</td><td valign="top"><div style="padding-top:1px ">
					<table   cellpadding="0" cellspacing="0">
					<?php foreach ($arows->full_ans as $arow) {
						echo "<tr class='sectiontableentry2' height='25px'><td><b>".$arow->ans_count."</b> ".$arow->ftext."</td></tr>";
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
				<table  ><tr><td class="sectiontableheader" align="left"><?php echo $qrow->iscale_name?></td></tr></table>
				<table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td width="250px" valign="top">
				<img src="<?php echo $mosConfig_live_site?>/administrator/components/com_surveyforce/includes/draw_grid.php?total=<?php echo $total?>&grids=<?php echo implode(',',$tmp_data)?>">
				</td><td valign="top"><div style="padding-top:1px ">
				<table   cellpadding="0" cellspacing="0">
				<?php foreach ($qrow->answer_imp as $arow) {
					echo "<tr class='sectiontableentry2' height='25px'><td><b>".$arow->ans_count."</b> ".$arow->ftext."</td></tr>";
					}?>
				</table></td></tr>
				</table>
				<?php
			}
		}
		?></div><?php
	}
	
	function SF_showCrossReport( $lists, $option ) {
		global $task, $Itemid,$Itemid_s, $my, $sf_lang, $mosConfig_live_site;
		mosCommonHTML::loadCalendar(); 
		?>
		<script type="text/javascript" src="/media/system/js/core.js"></script>
		<script type="text/javascript" src="components/com_surveyforce/overlib_mini.js"></script>
		<div class="contentpane surveyforce">
		<form action="<?php echo SFRoute("index.php?option=$option{$Itemid_s}")?>" method="post" name="adminForm" id="adminForm">
		<table width="100%" cellpadding="0" cellspacing="0" border="0"  >
			<tr>
				<td width="auto">
					<table width="100%" cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td width="48px">
								<?php echo SF_showHeadPicture('report');?>
							</td>
							<td class="contentheading" width="100%" valign="middle" style="vertical-align:middle ">
								&nbsp;<?php echo $sf_lang['SF_CROSS_REPORT']?>
							</td>
							<td align="right" valign="top" style="vertical-align:top ">
								<?php SF_showTopMenu();?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td align="center" style="text-align:center">
				<span id="SF_toolbar_tooltip">&nbsp;</span><br/>
				<?php $toolbar = array();
					  $toolbar[] = array('btn_type' => 'report', 'btn_js' => "javascript:submitbutton('get_cross_rep');", 'btn_str' => $sf_lang['SF_REPORT']);
					  $toolbar[] = array('btn_type' => 'back', 'btn_js' => "javascript:submitbutton('reports');"); 
					  echo ShowToolbar($toolbar); 
				?>
				</td>
			</tr>
			<tr>
				<td width="100%">
				<table width="100%"><tr><td align="left">&nbsp;</td>
				<td align="right" style="text-align:right">&nbsp;</td>
				</tr>
				</table>
				</td>
			</tr>
		</table>
		
		<table width="100%" cellpadding="2" cellspacing="0" border="0"  >
		<tr>
			<td colspan="2" class="sectiontableheader"  align="left"><?php echo $sf_lang['SF_REPORT_DETAILS']?></td>
		</tr>
		<tr><td width="20%" valign="top"><?php echo $sf_lang['SF_SELECT_SURVEY']?>:</td>
			<td><?php echo $lists['surveys']?></td>
		</tr>
		<?php if ($lists['mquest_id'] != '') {?>
		<tr><td valign="top"><?php echo $sf_lang['SF_SEL_COL_QUEST']?>:</td>
			<td><?php echo $lists['mquest_id']?></td>
		</tr>
		<tr><td valign="top"><?php echo $sf_lang['SF_SEL_QUEST_INCLUDED']?>:</td>
			<td><?php echo $lists['cquest_id']?></td>
		</tr>
		<tr><td valign="top"><?php echo $sf_lang['SF_FROM_DATE']?>:</td>
			<td>
			<?php echo JHTML::_('calendar','', 'start_date','start_date','%Y-%m-%d' , array('size'=>15,'maxlength'=>"19"));	?>
			</td>
		</tr>
		<tr><td valign="top"><?php echo $sf_lang['SF_TO_DATE']?>:</td>
			<td>
			<?php echo JHTML::_('calendar','', 'end_date','end_date','%Y-%m-%d' , array('size'=>15,'maxlength'=>"19"));?>
			</td>
		</tr>
		<tr><td valign="top"><?php echo $sf_lang['SF_INCLUDE_COMPL']?></td>
			<td><input type="checkbox" name="is_complete" checked="checked" value="1" /></td>
		</tr>
		<tr><td valign="top"><?php echo $sf_lang['SF_INCLUDE_INCOMPL']?></td>
			<td><input type="checkbox" name="is_notcomplete" checked="checked" value="1" /></td>
		</tr>
		<tr><td valign="top"><?php echo $sf_lang['SF_GET_REP_IN']?>:</td>
			<td><select name="rep_type" class="inputbox" >
				<option value="pdf" selected="selected"><?php echo $sf_lang['SF_ACROBAT_PDF']?></option>
				<option value="csv"><?php echo $sf_lang['SF_EXCEL_CSV']?></option>
				</select>
			</td>
		</tr>
		<?php }
		else 
			echo "<tr><td colspan='2'>".$sf_lang['SF_CROSS_REP_NOT_CREATED']."</td></tr>";
		?>		
		</table>
		<input type="hidden" name="option" value="com_surveyforce" />
		<input type="hidden" name="id" value="0" />
		<input type="hidden" name="task" value="cross_rep" />
		<input type="hidden" name="Itemid" value="<?php echo $Itemid?>" />
		</form><br/><br/></div>
<?php
	}
	
	function SF_showIReport( &$rows, &$lists, &$pageNav, $option, $is_i = false ) {
		global $task, $Itemid,$Itemid_s, $my, $sf_lang, $mosConfig_live_site;
		
		?>
		<script type="text/javascript" src="/media/system/js/core.js"></script>
		<script type="text/javascript" src="components/com_surveyforce/overlib_mini.js"></script>
		<script type="text/javascript" language="javascript">
		function submitbutton(pressbutton) {
			var form = document.forms.adminForm;
			if ( ((pressbutton == 'view_irep_surv')) && (form.boxchecked.value == "0")) {
				alert('<?php echo $sf_lang['SF_ALERT_SELECT_ITEM'];?>');
			}
			else {
				submitform( pressbutton );
				return;
			}
		}
		</script>
		<div class="contentpane surveyforce">
		<form action="<?php echo SFRoute("index.php?option=$option{$Itemid_s}")?>" method="post" name="adminForm" id="adminForm">
		<table width="100%" cellpadding="0" cellspacing="0" border="0"  >
			<tr>
				<td width="auto">
					<table width="100%" cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td width="48px">
								<?php echo SF_showHeadPicture('report');?>
							</td>
							<td class="contentheading" width="100%" valign="middle" style="vertical-align:middle ">
								&nbsp;<?php echo $sf_lang['SF_CSV_REP_SEL_SURVEY']?>
							</td>
							<td align="right" valign="top" style="vertical-align:top ">
								<?php SF_showTopMenu();?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td align="center" style="text-align:center">
				<span id="SF_toolbar_tooltip">&nbsp;</span><br/>
				<?php $toolbar = array();
					  $toolbar[] = array('btn_type' => 'report', 'btn_js' => "javascript:submitbutton('view_irep_surv');", 'btn_str' => $sf_lang['SF_REPORT']);
					  $toolbar[] = array('btn_type' => 'back', 'btn_js' => "javascript:submitbutton('reports');"); 
					  echo ShowToolbar($toolbar); 
				?>
				</td>
			</tr>
			<tr>
				<td width="100%">
				<table width="100%"><tr><td align="left"><input type="checkbox" name="inc_imp" value="1"><?php echo $sf_lang['SF_INCLUDE_IMP_SCALE']?>
				<br />
				<?php echo $lists['category'];?>
				</td>
				<td align="right" style="text-align:right"><?php
					$link = "index.php?option=$option{$Itemid_s}&amp;task=usergroups"; 
					echo _PN_DISPLAY_NR . $pageNav->getLimitBox( $link ) . '&nbsp;' ;
					echo $pageNav->writePagesCounter(). '&nbsp;&nbsp;';?></td>
				</tr>
				</table>
				</td>
			</tr>
		</table>
		<table width="100%" cellpadding="0" cellspacing="0" border="0"  >
		<tr>
			<td class="sectiontableheader" width="20">#</td>
			<td class="sectiontableheader" width="20" ><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($rows); ?>);" /></td>
			<td class="sectiontableheader" ><?php echo $sf_lang["SF_NAME"]?></td>
			<td class="sectiontableheader" ><?php echo $sf_lang["SF_ACTIVE"]?></td>
			<td class="sectiontableheader"><?php echo $sf_lang["SF_CATEGORY"]?></td>
			<td class="sectiontableheader"><?php echo $sf_lang["SF_AUTHOR"]?></td>
			<td class="sectiontableheader"><?php echo $sf_lang["SF_PUBLIC"]?></td>
			<td class="sectiontableheader"><?php echo $sf_lang["SF_FOR_INVITED"]?></td>
			<td class="sectiontableheader"><?php echo $sf_lang["SF_FOR_REG"]?></td>
			<td class="sectiontableheader"><?php echo $sf_lang["SF_FOR_USER_IN_LISTS"]?></td>
			<td class="sectiontableheader"><?php echo $sf_lang["SF_EXPIRED_ON"]?>:</td>
		</tr>
		<?php
		$k = 1;
		for ($i=0, $n=count($rows); $i < $n; $i++) {
			$row = $rows[$i];
			$link = '#';
			$img_published	= $row->published ? 'btn_accept.png' : 'btn_cancel.png';
			$task_published	= $row->published ? 'unpublish_surv' : 'publish_surv';
			$alt_published 	= $row->published ? $sf_lang['SF_PUBLISHED']  : $sf_lang['SF_UNPUBLISHED'] ;
			$img_public		= $row->sf_public ? 'btn_accept.png' : 'btn_cancel.png';
			$img_invite		= $row->sf_invite ? 'btn_accept.png' : 'btn_cancel.png';
			$img_reg		= $row->sf_reg ? 'btn_accept.png' : 'btn_cancel.png';
			$img_spec		= $row->sf_special ? 'btn_accept.png' : 'btn_cancel.png';
			$checked = mosHTML::idBox( $i, $row->id);
			?>
			<tr class="<?php echo "sectiontableentry$k"; ?>">
				<td><?php echo $pageNav->rowNumber( $i ); ?></td>
				<td><?php echo $checked; ?></td>
				<td align="left">
					<span>
						<script language="javascript" type="text/javascript">
						var des<?php echo $row->id;?> = '<?php echo str_replace("'","&#039;", str_replace("\r",'', str_replace("\n",'', nl2br($row->sf_descr))))?>';
					</script>
					<a href="<?php echo $link?>" onmouseover="return overlib(des<?php echo $row->id;?>, CAPTION, '<?php echo $sf_lang['SF_SURV_DESCRIPTION']?>', BELOW, RIGHT, WIDTH, '280');" onmouseout="return nd();" ><?php echo $row->sf_name ?></a>						
					</span>
				</td>
				<td align="left">
					<a href="javascript: void(0);" onClick="return listItemTask('cb<?php echo $i;?>','<?php echo $task_published;?>')">
						<img src="components/com_surveyforce/images/toolbar/<?php echo $img_published;?>" width="16" height="16" border="0" alt="<?php echo $alt_published; ?>" />
					</a>
				</td>
				<td align="left">
					<?php echo $row->sf_catname; ?>
				</td>
				<td align="left">
					<?php echo $row->username; ?>
				</td>
				<td align="left">
						<img src="components/com_surveyforce/images/toolbar/<?php echo $img_public;?>" width="16" height="16" border="0" alt="<?php echo $alt_published; ?>" />
				</td>
				<td align="left">
						<img src="components/com_surveyforce/images/toolbar/<?php echo $img_invite;?>" width="16" height="16" border="0" alt="<?php echo $alt_published; ?>" />
				</td>
				<td align="left">
						<img src="components/com_surveyforce/images/toolbar/<?php echo $img_reg;?>" width="16" height="16" border="0" alt="<?php echo $alt_published; ?>" />
				</td>
				<td align="left">
						<img src="components/com_surveyforce/images/toolbar/<?php echo $img_spec;?>" width="16" height="16" border="0" alt="<?php echo $alt_published; ?>" />
				</td>				
				<td align="left">
						<?php echo mosFormatDate($row->sf_date, "Y-m-d");?>
				</td>
			</tr>
			<?php
			$k = 3 - $k;
		}
		?>
		</table>
		<div align="center">
		<?php 
			$link = "index.php?option=$option{$Itemid_s}&amp;task=usergroups"; 
			echo $pageNav->writePagesLinks($link).'<br/>';
		?>
		</div>

		<input type="hidden" name="option" value="com_surveyforce" />
		<input type="hidden" name="task" value="<?php echo $task?>" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="Itemid" value="<?php echo $Itemid?>" />
		<input type="hidden" name="hidemainmenu" value="0">
		</form><br/><br/></div>
		<?php
	}
	
	function show_results( $rows, $lists, $option ) {
		global $task, $Itemid,$Itemid_s, $my, $sf_lang, $mosConfig_live_site;
	?>
		<div class="contentpane surveyforce">
		<form action="<?php echo SFRoute("index.php?option=$option{$Itemid_s}")?>" method="post" name="adminForm" id="adminForm">
		<table width="100%" cellpadding="0" cellspacing="0" border="0"  >
			<tr>
				<td width="auto">
					<table width="100%" cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td width="48px">
								<?php echo SF_showHeadPicture('report');?>
							</td>
							<td class="contentheading" width="100%" valign="middle" style="vertical-align:middle ">
								&nbsp;<?php echo $sf_lang['SF_SURVEY_RESULTS']?>
							</td>
							<td align="right" valign="top" style="vertical-align:top ">
								<?php SF_showTopMenu();?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td align="center" style="text-align:center">
				<span id="SF_toolbar_tooltip">&nbsp;</span><br/>
				<?php $toolbar = array();
					  $toolbar[] = array('btn_type' => 'back', 'btn_js' => "javascript:submitbutton('surveys');"); 
					  echo ShowToolbar($toolbar); 
				?>
				</td>
			</tr>
			<tr>
				<td width="100%">
				<table width="100%"><tr><td align="left"><?php echo $sf_lang['SF_SURVEY'].':'.$lists['survey'];?></td>
				<td align="right" style="text-align:right">&nbsp;</td>
				</tr>
				</table>
				</td>
			</tr>
		</table>
		
		<table width="100%" cellpadding="0" cellspacing="0" border="0"  >
		<tr>
			<td colspan="2" align="left" class="sectiontableheader"><?php echo $sf_lang['SF_SURVEY_RESULTS']?> - <?php echo $lists['sname']?></td>
		</tr>
		</table>
		<?php foreach( $rows as $row ){
			if ($row) {	?>			
			<?php echo $row;?><br/>
		<?php } 
		} ?>
		
		<input type="hidden" name="option" value="com_surveyforce" />
		<input type="hidden" name="task" value="show_results" />
		<input type="hidden" name="Itemid" value="<?php echo $Itemid?>" />
		<input type="hidden" name="boxchecked" value="0" />
		</form><br/><br/></div>
	<?php
	}
	
	function SF_showEmailsList( &$rows, &$pageNav, $option ) {
		global $task, $Itemid,$Itemid_s, $my, $sf_lang, $mosConfig_live_site, $sf_lang;

		?>
		<div class="contentpane surveyforce">
		<form action="<?php echo SFRoute("index.php?option=$option{$Itemid_s}")?>" method="post" name="adminForm" id="adminForm">
		<script type="text/javascript" src="/media/system/js/core.js"></script>
		<script type="text/javascript" src="components/com_surveyforce/overlib_mini.js"></script>
		<script type="text/javascript" language="javascript">
		function submitbutton(pressbutton) {
			var form = document.forms.adminForm;
			if ( ((pressbutton == 'edit_email') || (pressbutton == 'del_email')) && (form.boxchecked.value == "0")) {
				alert('<?php echo $sf_lang['SF_ALERT_SELECT_ITEM'];?>');
			}
			else {
				submitform( pressbutton );
				return;
			}
		}
		</script>

		<table width="100%" cellpadding="0" cellspacing="0" border="0"  >
			<tr>
				<td width="auto">
					<table width="100%" cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td width="48px">
								<?php echo SF_showHeadPicture('usergroup');?>
							</td>
							<td class="contentheading" width="100%" valign="middle" style="vertical-align:middle ">
								&nbsp;<?php echo $sf_lang['SF_CR_EMAIL']?>
							</td>
							<td align="right" valign="top" style="vertical-align:top ">
								<?php SF_showTopMenu();?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td align="center" style="text-align:center">
				<span id="SF_toolbar_tooltip">&nbsp;</span><br/>
				<?php $toolbar = array();
					  $toolbar[] = array('btn_type' => 'edit', 'btn_js' => "javascript:submitbutton('edit_email');", 'btn_str' => $sf_lang['SF_EDIT']);
					  $toolbar[] = array('btn_type' => 'new', 'btn_js' => "javascript:submitbutton('add_email');", 'btn_str' => $sf_lang['SF_NEW']);
					  $toolbar[] = array('btn_type' => 'del', 'btn_js' => "javascript:submitbutton('del_email');", 'btn_str' => $sf_lang['SF_DELETE']); 
					  $toolbar[] = array('btn_type' => 'back', 'btn_js' => "javascript:submitbutton('usergroups');", 'btn_str' => $sf_lang['SF_BACK']); 
					  echo ShowToolbar($toolbar); 
				?>
				</td>
			</tr>			
		</table>
		<table width="100%" cellpadding="0" cellspacing="0" border="0"  >
		<tr>
			<td class="sectiontableheader" width="20">#</td>
			<td class="sectiontableheader"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($rows); ?>);" /></td>
			<td class="sectiontableheader"><?php echo $sf_lang['SUBJECT'] ?></td>
			<td class="sectiontableheader"><?php echo $sf_lang['BODY'] ?></td>
			<td class="sectiontableheader"><?php echo $sf_lang['REPLY_TO'] ?></td>
		</tr>
		<?php
		$k = 0;
		for ($i=0, $n=count($rows); $i < $n; $i++) {
			$row = $rows[$i];

			$link = SFRoute( "index.php?option=com_surveyforce&task=editA_email&id=". $row->id);

			$checked = mosHTML::idBox( $i, $row->id);
			?>
			<tr class="<?php echo "sectiontableentry$k"; ?>">
				<td><?php echo $pageNav->rowNumber( $i ); ?></td>
				<td><?php echo $checked; ?></td>
				<td align="left">
					<a href="<?php echo $link; ?>" title="Edit email">
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
		<div align="center">
		<?php 
			$link = "index.php?option=$option{$Itemid_s}&amp;task=emails"; 
			echo $pageNav->writePagesLinks($link).'<br/>';
		?>
		</div>
		<input type="hidden" name="option" value="com_surveyforce" />
		<input type="hidden" name="task" value="emails" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="Itemid" value="<?php echo $Itemid?>" />
		<input type="hidden" name="hidemainmenu" value="0">
		</form><br/><br/>
		</div>
		<?php
	}
	
	function SF_editEmail( &$row, &$lists, $option ) {
		global $mosConfig_live_site,$my, $sf_lang;
		global $task, $Itemid,$Itemid_s, $my, $sf_lang, $mosConfig_live_site, $sf_lang;

		mosCommonHTML::loadOverlib();
		?>
		<script language="javascript" type="text/javascript">
		<!--
		function submitbutton(pressbutton) {
			var form = document.adminForm;

			if (pressbutton == 'cancel_email') {
				submitform( pressbutton );
				return;
			}
			// do field validation
			var reg_email = /[0-9a-z_]+@[0-9a-z_^.]+.[a-z]{2,3}/;

			if (form.email_subject.value == ""){
				alert( "<?php echo $sf_lang['EMAIL_MUST_NAME'] ?>" );
			} else if (form.email_body.value == ""){
				alert( "<?php echo $sf_lang['EMAIL_MUST_BODY'] ?>" );
			} else if (form.email_reply.value == ""){
				alert( "<?php echo $sf_lang['EMAIL_MUST_REMAIL'] ?>" );
			} else if (!reg_email.test(form.email_reply.value)) {
				alert( "<?php echo $sf_lang['EMAIL_MUST_VALID'] ?>" );
			} else {
				submitform( pressbutton );
			}
		}
		//-->
		</script>
		<div class="contentpane surveyforce">
		<form action="<?php echo SFRoute("index.php?option=$option{$Itemid_s}")?>" method="post" name="adminForm" id="adminForm">
		<table width="100%" cellpadding="0" cellspacing="0" border="0"  >
			<tr>
				<td width="auto">
					<table width="100%" cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td width="48px">
								<?php echo SF_showHeadPicture('usergroup');?>
							</td>
							<td class="contentheading" width="100%" valign="middle" style="vertical-align:middle ">
								&nbsp;<?php echo ($row->id ? $sf_lang['EDIT_EMAIL'] : $sf_lang['NEW_EMAIL']); ?>
							</td>
							<td align="right" valign="top" style="vertical-align:top ">
								<?php SF_showTopMenu();?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td align="center" style="text-align:center">
				<span id="SF_toolbar_tooltip">&nbsp;</span><br/>
				<?php $toolbar = array();
					  $toolbar[] = array('btn_type' => 'save', 'btn_js' => "javascript:submitbutton('save_email');", 'btn_str' => $sf_lang['SF_SAVE']);
					  $toolbar[] = array('btn_type' => 'apply', 'btn_js' => "javascript:submitbutton('apply_email');", 'btn_str' => $sf_lang['SF_APPLY']);
					  $toolbar[] = array('btn_type' => 'cancel', 'btn_js' => "javascript:submitbutton('cancel_email');", 'btn_str' => $sf_lang['SF_CANCEL']); 
					  echo ShowToolbar($toolbar); 
				?>
				</td>
			</tr>			
		</table>
		<table width="100%" cellpadding="0" cellspacing="0" border="0"  >
			<tr>
				<th colspan="2" class="sectiontableheader">Email Details</th>
			<tr>
			<tr>
				<td align="right" width="20%">Subject:</td>
				<td><input class="text_area" type="text" name="email_subject" size="50" maxlength="100" value="<?php echo $row->email_subject; ?>" /></td>
			</tr>
			<tr>
				<td align="right" width="20%" valign="top">Body:</td>
				<td><textarea class="text_area" name="email_body" cols="36" rows="5"><?php echo $row->email_body; ?></textarea>
				<br>Use the following constants: #name#, #link#.</td>
			</tr>
			<tr>
				<td align="right" width="20%">Reply to:</td>
				<td><input class="text_area" type="text" name="email_reply" size="50" maxlength="100" value="<?php echo $row->email_reply; ?>" /></td>
			</tr>
		</table>
		<br />
		<input type="hidden" name="option" value="com_surveyforce" />
		<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
		<input type="hidden" name="user_id" value="<?php echo $my->id; ?>" />
		<input type="hidden" name="Itemid" value="<?php echo $Itemid?>" />
		<input type="hidden" name="task" value="" />
		</form><br/><br/>
		</div>
		<?php
	}
	
	function SF_inviteUsers( &$row, &$lists, $option ) {
		global $task, $Itemid,$Itemid_s, $my, $sf_lang, $mosConfig_live_site, $sf_lang;

		mosCommonHTML::loadOverlib();

		?>
		<script language="javascript" type="text/javascript">
		function getObj(name)
		{
		  if (document.getElementById)  {  return document.getElementById(name);  }
		  else if (document.all)  {  return document.all[name];  }
		  else if (document.layers)  {  return document.layers[name];  }
		}
		</script>
		<script language="javascript" type="text/javascript">
		<!--
		function StartInvitation() {
			var form = document.adminForm;
			var inv_frame = getObj('invite_frame');
			inv_frame.src = '<?php echo $mosConfig_live_site;?>/index.php?no_html=1&option=com_surveyforce&task=invitation_start&email='+form.email_id.value+'&list='+<?php echo $row->id?>;
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
		
		function submitbutton(pressbutton) {
			var form = document.adminForm;
			submitform( pressbutton );
		}
		//-->
		</script>
		<div class="contentpane surveyforce">
		<form action="<?php echo SFRoute("index.php?option=$option{$Itemid_s}")?>" method="post" name="adminForm" id="adminForm">
		<table width="100%" cellpadding="0" cellspacing="0" border="0"  >
			<tr>
				<td width="auto">
					<table width="100%" cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td width="48px">
								<?php echo SF_showHeadPicture('usergroup');?>
							</td>
							<td class="contentheading" width="100%" valign="middle" style="vertical-align:middle ">
								&nbsp;<?php echo $sf_lang['INVITE_USERS']; ?>
							</td>
							<td align="right" valign="top" style="vertical-align:top ">
								<?php SF_showTopMenu();?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td align="center" style="text-align:center">
				<span id="SF_toolbar_tooltip">&nbsp;</span><br/>
				<?php $toolbar = array();
					  $toolbar[] = array('btn_type' => 'back', 'btn_js' => "javascript:submitbutton('usergroups');", 'btn_str' => $sf_lang['SF_BACK']); 
					  echo ShowToolbar($toolbar); 
				?>
				</td>
			</tr>			
		</table>
		<table width="100%" cellpadding="0" cellspacing="0" border="0"  >
			<tr>
				<td colspan="2" class="sectiontableheader">Invitation Details</td>
			</tr>
			<tr>
				<td align="right" width="20%">List of users:</td>
				<td><?php echo $row->listname; ?></td>
			</tr>
			<tr>
				<td align="right" width="20%" valign="top">Email:</td>
				<td><?php echo $lists['email_list']; ?></td>
			</tr>
		</table>
		<br />
		<input type="hidden" name="option" value="com_surveyforce" />
		<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
		<input type="hidden" name="Itemid" value="<?php echo $Itemid?>" />
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
						Press Start to begin invitations sending process.
						<?php } elseif ($row->is_invited == 1) { ?>
						Users from the following list had been sent invitations before.
						<?php } elseif ($row->is_invited == 2) { ?>
						Press Start to continue invitations sending process.
						<?php } ?>
					</div>
				</td>
			</tr>
		</table>
		</form><br/><br/>
		</div>
		<iframe src="" style="display:none " id="invite_frame">
		</iframe>
		<?php
	
	}
	
	function SF_remindUsers( &$row, &$lists, $option ) {
		global $task, $Itemid,$Itemid_s, $my, $sf_lang, $mosConfig_live_site, $sf_lang;

		mosCommonHTML::loadOverlib();

		?>
		<script language="javascript" type="text/javascript">
		function getObj(name)
		{
		  if (document.getElementById)  {  return document.getElementById(name);  }
		  else if (document.all)  {  return document.all[name];  }
		  else if (document.layers)  {  return document.layers[name];  }
		}
		</script>
		<script language="javascript" type="text/javascript">
		<!--
		function StartRemind() {
			var form = document.adminForm;
			var inv_frame = getObj('invite_frame');
			inv_frame.src = '<?php echo $mosConfig_live_site;?>/index.php?no_html=1&option=com_surveyforce&task=remind_start&email='+form.email_id.value+'&list='+<?php echo $row->id?>;
		}
		
		function StopRemind() {
			var form = document.adminForm;
			form.Start.value = 'Resume';
			if (!document.all)
				for (var i=0;i<top.frames.length;i++)
				  top.frames[i].stop()
			else
				for (var i=0;i<top.frames.length;i++)
				  top.frames[i].document.execCommand('Stop')
		}
		
		function submitbutton(pressbutton) {
			var form = document.adminForm;
			submitform( pressbutton );
		}
		//-->
		</script>
		<div class="contentpane surveyforce">
		<form action="<?php echo SFRoute("index.php?option=$option{$Itemid_s}")?>" method="post" name="adminForm" id="adminForm">
		<table width="100%" cellpadding="0" cellspacing="0" border="0"  >
			<tr>
				<td width="auto">
					<table width="100%" cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td width="48px">
								<?php echo SF_showHeadPicture('usergroup');?>
							</td>
							<td class="contentheading" width="100%" valign="middle" style="vertical-align:middle ">
								&nbsp;<?php echo $sf_lang['REMIND_USERS']; ?>
							</td>
							<td align="right" valign="top" style="vertical-align:top ">
								<?php SF_showTopMenu();?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td align="center" style="text-align:center">
				<span id="SF_toolbar_tooltip">&nbsp;</span><br/>
				<?php $toolbar = array();
					  $toolbar[] = array('btn_type' => 'back', 'btn_js' => "javascript:submitbutton('usergroups');", 'btn_str' => $sf_lang['SF_BACK']); 
					  echo ShowToolbar($toolbar); 
				?>
				</td>
			</tr>			
		</table>
		<table width="100%" cellpadding="0" cellspacing="0" border="0"  >
			<tr>
				<td class="sectiontableheader" colspan="2">Reminder Details</td>
			</tr>
			<tr>
				<td align="right" width="20%">List of users:</td>
				<td><?php echo $row->listname; ?></td>
			</tr>
			<tr>
				<td align="right" width="20%" valign="top">Email:</td>
				<td><?php echo $lists['email_list']; ?></td>
			</tr>
		</table>
		<br />
		<input type="hidden" name="option" value="com_surveyforce" />
		<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
		<input type="hidden" name="Itemid" value="<?php echo $Itemid?>" />
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
						Press Start to begin reminders sending process.
					</div>
				</td>
			</tr>
		</table>
		</form><br/><br/>
		</div>
		<iframe src="" style="display:none " id="invite_frame">
		</iframe>
		<?php
	}

}


function SF_editorArea( $name, $content, $hiddenField, $width, $height, $col, $row ) {

	if (_JOOMLA15) {
			jimport( 'joomla.html.editor' );
	
			$conf =& JFactory::getConfig();
			$editor = $conf->getValue('config.editor');
			$editorz =& JEditor::getInstance($editor);
			$editorz =& JFactory::getEditor();
			echo $editorz->display($hiddenField, $content, $width, $height, $col, $row, array('pagebreak', 'readmore'));
		} else {
			global $mainframe, $_MAMBOTS, $my;

			$mainframe->set( 'loadEditor', true );
	
			$results = $_MAMBOTS->trigger( 'onEditorArea', array( $name, $content, $hiddenField, $width, $height, $col, $row ) );
			foreach ($results as $result) {
				if (trim($result)) {
					echo $result;
				}
			}
		}
}
?>