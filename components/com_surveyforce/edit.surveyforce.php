<?php
/**
* Survey Force component for Joomla
* @version $Id: edit.surveyforce.php 2009-11-16 17:30:15
* @package Survey Force
* @subpackage edit.surveyforce.php
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );
$GLOBALS['SF_SESSION'] = null;
global $SF_SESSION;
global $my, $database, $mainframe, $mosConfig_absolute_path, $mosConfig_live_site;

$GLOBALS['front_end'] = true;
global $front_end;

if (_JOOMLA15) {
	$GLOBALS['Itemid_s'] = '';
	global $Itemid_s;
}else {
	global $Itemid;
	$GLOBALS['Itemid_s'] = "&Itemid=$Itemid";
	global $Itemid_s;
}

require_once ( $mosConfig_absolute_path . '/components/com_surveyforce/edit.surveyforce.html.php' );	
require_once ( $mosConfig_absolute_path . '/administrator/components/com_surveyforce/admin.surveyforce.php' );	

function SF_analizeTask($task = ''){
	if ($task != 'view_irep_surv' && $task != 'rep_pdf' && $task != 'rep_csv'  && $task != 'get_cross_rep' && $task !=  'rep_print'  && $task != 'rep_surv_print' && $task != 'get_options') {
		echo '<script language="JavaScript" src="components/com_surveyforce/sf_main_js.js" type="text/javascript"></script>';
		echo '<!--[if lt IE 7]>';
		echo '<style type="text/css">';
		echo 'img.SF_png {';
		echo 'behavior:	url("components/com_surveyforce/includes/pngbehavior.htc");';
		echo '}';
		echo '</style>';
		echo '<![endif]-->';		
	}
	
	global $option, $database, $mosConfig_absolute_path, $sf_lang, $front_end, $Itemid, $my, $mainframe;
	$id 	= intval( mosGetParam( $_REQUEST, 'id', 0 ) );
	
	$cid 	= mosGetParam( $_REQUEST, 'cid', array(0) );
	if (!is_array( $cid )) {
		$cid = array(0);
	} 
	
	$sec 	= mosGetParam( $_REQUEST, 'sec', array() );
	if (!is_array( $sec )) {
		$sec = array(0);
	}
	elseif (count($sec) > 0) {
		$query = "SELECT id FROM #__survey_force_quests WHERE sf_section_id IN (". implode(',', $sec) .") ";
		$database->setQuery($query);
		$cid = array_merge($cid, $database->loadResultArray());
	}
	$document 	=& JFactory::getDocument();
	$document->addStyleSheet(JURI::root().'components/com_surveyforce/surveyforce.css');
	
	$sf_config = new mos_Survey_Force_Config( );
	if ($sf_config->get('sf_enable_jomsocial_integration')) {
		$query = "SELECT id FROM #__survey_force_authors WHERE user_id = '".$my->id."'";
		$database->SetQuery( $query );
		$a_id = $database->LoadResult();
		if (!$a_id) {
			$document 	=& JFactory::getDocument();
			$document->addStyleSheet(JURI::root().'templates/system/css/system.css');
			$document->addStyleSheet(JURI::root().'templates/system/css/general.css');			
			$_REQUEST['tmpl'] = 'component';
		}
	}	

	global $SF_SESSION;

	require_once($mosConfig_absolute_path . "/components/com_surveyforce/sf_session.class.php");
	if (_JOOMLA15) {
		$options = array();
		$options['name'] = "surveyforce_session";
		$SF_SESSION = JFactory::getSession($options);	
	}
	else
		$SF_SESSION = new SF_Session();

	
	if (!function_exists('clearPreviews')) {
		function clearPreviews() {
			global $database;
			$query = "SELECT `start_id` FROM `#__survey_force_previews` WHERE `time` < '".(time()-4000)."'";
			$database->SetQuery($query);
			$start_ids = $database->loadResultArray();
			
			if (is_array($start_ids) && count($start_ids) > 0) {
				$start_id_str = implode("','", $start_ids);
				
				$query = "DELETE FROM #__survey_force_previews WHERE start_id IN ( '{$start_id_str}' )";
				$database->setQuery( $query );
				$database->query();
				
				$query = "DELETE FROM #__survey_force_user_chain WHERE start_id IN ( '{$start_id_str}' )";
				$database->setQuery( $query );
				$database->query();
				
				$query = "DELETE FROM #__survey_force_user_answers WHERE start_id IN ( '{$start_id_str}' )";
				$database->setQuery( $query );
				$database->query();
				
				$query = "DELETE FROM #__survey_force_user_ans_txt WHERE start_id IN ( '{$start_id_str}' )";
				$database->setQuery( $query );
				$database->query();
				
				$query = "DELETE FROM #__survey_force_user_answers_imp WHERE start_id IN ( '{$start_id_str}' )";
				$database->setQuery( $query );
				$database->query();
			}
		}
	}


	clearPreviews();

	switch ($task) {
		case 'uploadimage': 	SF_uploadImage($option);				 			break;	
		# --- CATEGORIES --- #
		case 'categories':		SF_ListCategories($option);							break;
		case 'add_cat':			SF_editCategory( '0', $option);						break;
		case 'edit_cat':		SF_editCategory( intval( $cid[0] ), $option );		break;
		case 'editA_cat':		SF_editCategory( $id, $option );					break;
		case 'apply_cat':
		case 'save_cat':		SF_saveCategory( $option );							break;
		case 'del_cat':			SF_removeCategory( $cid, $option );					break; 		
		case 'cancel_cat':		SF_cancelCategory( $option );						break;		
		# ---   SURVEYS  --- #
		case 'surveys':			SF_ListSurveys($option);							break;
		case 'add_surv':		SF_editSurvey( '0', $option);						break;
		case 'edit_surv':		SF_editSurvey( intval( $cid[0] ), $option );		break;
		case 'editA_surv':		SF_editSurvey( $id, $option );						break;
		case 'apply_surv':
		case 'save_surv':		SF_saveSurvey( $option );							break;
		case 'del_surv':		SF_removeSurvey( $cid, $option );					break; 		
		case 'cancel_surv':		SF_cancelSurvey( $option );							break;
		case 'publish_surv':	SF_changeSurvey( $cid, 1, $option );				break;
		case 'unpublish_surv':	SF_changeSurvey( $cid, 0, $option );				break;
		case 'move_surv_sel':	SF_moveSurveySelect( $option, $cid );				break;
		case 'move_surv_save':	SF_moveSurveySave( $cid );							break;
		case 'copy_surv_sel':	SF_moveSurveySelect( $option, $cid );				break;
		case 'copy_surv_save':	SF_copySurveySave( $cid );							break;
		case 'show_results': 	SF_show_results (intval( $cid[0] ), $option);		break;
		case 'preview_survey':	SF_preview_survey (intval( $cid[0] ), $option);		break;
		# ---  QUESTIONS  --- #
		case 'publish_quest':	SF_changeQuestion( $cid, 1, $option );				break;
		case 'unpublish_quest':	SF_changeQuestion( $cid, 0, $option );				break;				
		case 'questions':		SF_ListQuestions( $option );						break;
		case 'new_question_type': SF_new_question_type();							break;
		case 'add_new_section':	SF_editSection( '0', $option );						break;
		case 'editA_sec':		SF_editSection( $id, $option );						break;
		case 'apply_section':
		case 'save_section':	SF_saveSection( $option );							break;
		case 'cancel_section':	global $Itemid,$Itemid_s;
								mosRedirect(SFRoute("index.php?option=$option{$Itemid_s}&task=questions"));					
																					break;
		case 'add_new':			$new_qtype_id = intval( $mainframe->getUserStateFromRequest( "new_qtype_id{$option}", 'new_qtype_id', 0 ) );
								SF_editQuestion( '0', $option, $new_qtype_id );		break;																					
		case 'add_ranking': 	SF_editQuestion( '0', $option, 9 );					break;
		case 'add_pagebreak': 	SF_editQuestion( '0', $option, 8 );					break;
		case 'add_boilerplate': SF_editQuestion( '0', $option, 7 );					break;
		case 'add_likert':		SF_editQuestion( '0', $option, 1 );					break;
		case 'add_pickone':		SF_editQuestion( '0', $option, 2 );					break;
		case 'add_pickmany':	SF_editQuestion( '0', $option, 3 );					break;
		case 'add_short':		SF_editQuestion( '0', $option, 4 );					break;
		case 'add_drp_dwn':		SF_editQuestion( '0', $option, 5 );					break;
		case 'add_drg_drp':		SF_editQuestion( '0', $option, 6 );					break;
		case 'set_default':		SF_setDefault( $id, $option );						break;
		case 'save_default':	SF_saveDefault( $id, $option );						break;
		case 'cancel_default':  SF_cancelDefault( $id, $option );					break;
		case 'edit_quest':		if (isset($cid[0]) && intval( $cid[0] ) > 0) {
									SF_editQuestion( intval( $cid[0] ), $option );
									break;
								}		
								if (isset($sec[0]) && intval( $sec[0] ) > 0) {
									global $Itemid,$Itemid_s;
									mosRedirect(SFRoute("index.php?option=$option{$Itemid_s}&task=editA_sec&hidemainmenu=1&id=".intval( $sec[0] )));
									break;
								}								
								break;
		case 'editA_quest':		SF_editQuestion( $id, $option );					break;
		case 'apply_quest':
		case 'save_quest':		SF_saveQuestion( $option );							break;
		case 'del_quest':		SF_removeQuestion( $cid, $sec, $option );			break;
		case 'cancel_quest':	SF_cancelQuestion( $option );						break;
		case 'orderup':			SF_orderQuestion( intval( $cid[0] ), -1, $option );	break;
		case 'orderdown':		SF_orderQuestion( intval( $cid[0] ), 1, $option );	break;
		case 'orderupS':		SF_orderSection( intval( $sec[0] ), -1, $option );	break;
		case 'orderdownS':		SF_orderSection( intval( $sec[0] ), 1, $option );	break;
		case 'saveorder':		SF_saveOrderQuestion( $cid, $sec);					break;
		case 'move_quest_sel':	SF_moveQuestionSelect( $option, $cid, $sec );		break;
		case 'move_quest_save':	SF_moveQuestionSave( $cid, $sec );					break;
		case 'copy_quest_sel':	SF_moveQuestionSelect( $option, $cid, $sec );		break;
		case 'copy_quest_save':	SF_copyQuestionSave( $cid, 0, 0, $sec );			break;
		case 'add_iscale_from_quest':
								$SF_SESSION->set('quest_redir',intval(mosGetParam($_REQUEST, 'quest_id', 0)));
								$SF_SESSION->set('task_redir',strval(mosGetParam($_REQUEST, 'red_task', '')));
								SF_editIScale( '0', $option );						break;

		case 'save_iscale_A':	SF_saveIScale( $option );							break;
		case 'cancel_iscale_A':	SF_cancelIScale( $option );							break;
		### USERGROUPS ###
		case 'usergroups':		SF_manageUsers( $option ); 							break;
		case 'add_list':		SF_editUsergroup( 0, $option );						break;
		case 'edit_list':		SF_editUsergroup( intval( $cid[0] ) , $option );	break;
		case 'save_list':
		case 'apply_list':		SF_saveUsergroup( $cid, $option );					break;
		case 'del_list':		SF_delUsergroup( $cid, $option );					break;
		case 'cancel_list':		mosRedirect(SFRoute("index.php?option=$option{$Itemid_s}&task=usergroups"));
																					break;
		case 'view_users': 		SF_viewUsers( $option );							break;
		case 'add_user':		SF_addUser2Group( $option );						break;
		case 'save_user':		SF_saveUsergroup( $cid, $option );					break;
		case 'del_user':		SF_delUserFromGroup( $cid, $option );				break;
		case 'cancel_user':		SF_cancelViewUsers( $option );						break;
		
		# ---  REPORTS  --- #
		case 'reports':			SF_ViewReports($option);							break;
		case 'rep_pdf':			SF_ViewReportsPDF_full($option, $cid, 1);			break;
		case 'rep_csv':			SF_ViewReportsCSV_full($option, $cid);				break;
		case 'del_rep':			SF_removeRep( $cid, $option);						break;
		case 'view_result':		SF_ViewRepResult( $id, $option);					break;
		case 'view_result_c':	SF_ViewRepResult( intval( $cid[0] ), $option);		break;
		case 'rep_surv':		SF_ListSurveys($option);							break;
		case 'view_rep_surv':	SF_ViewRepSurv( intval( $cid[0] ), $option);		break;
		case 'view_rep_survA':	SF_ViewRepSurv( $id, $option);						break;
		case 'rep_surv_print':	SF_ViewRepSurv( $id, $option, 1);					break;
		case 'rep_print':		SF_ViewRepResult( $id, $option, 1);					break;
		case 'rep_list':		SF_manageUsers( $option );							break;
		case 'view_rep_list':	SF_ViewRepList( intval( $cid[0] ), $option);		break;
		case 'view_rep_listA':	SF_ViewRepList( $id, $option);						break;
		case 'rep_list_print':	SF_ViewRepList( $id, $option, 1);					break;
				
		case 'i_report':		SF_ListSurveys($option, true);						break;
		case 'view_irep_surv':	SF_ViewIRepSurv( intval( $cid[0] ), $option);		break;
		case 'cross_rep':		SF_showCrossReport( $option );						break;
		case 'get_cross_rep':	SF_getCrossReport( $option );						break;
		case 'get_options':
			@session_start();


		
			if(true||@$_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'){ 
				$quest_id 	= (int)mosGetParam( $_REQUEST, 'quest_id', '0' );
				if ($quest_id) {
					$quest = new mos_Survey_Force_Question( $database );
					$quest->load($quest_id);
					$return = '';
					if ($quest->sf_qtype == 1) {	
						$query = "SELECT id AS value, stext AS text FROM `#__survey_force_scales` WHERE quest_id = '".$quest_id."' ORDER BY ordering";
						$database->SetQuery($query);
						$f_scale_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
						
						$f_scale_data = mosHTML::selectList( $f_scale_data, 'f_scale_data', 'class="text_area" id="f_scale_data" size="1" ', 'value', 'text', null ); 
						
						$query = "SELECT id AS value, ftext AS text FROM `#__survey_force_fields` WHERE quest_id = '".$quest_id."' ORDER BY ordering";
						$database->SetQuery($query);
						$f_fields_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
						
						$i =0;
						while ($i < count($f_fields_data)) {
							$f_fields_data[$i]->text = strip_tags($f_fields_data[$i]->text);
							if (strlen($f_fields_data[$i]->text) > 55)
								$f_fields_data[$i]->text = substr($f_fields_data[$i]->text, 0, 55).'...';
							$f_fields_data[$i]->text = $f_fields_data[$i]->value . ' - ' . $f_fields_data[$i]->text;
							$i ++;
						}
						
						$f_fields_data = mosHTML::selectList( $f_fields_data, 'sf_field_data_m', 'class="text_area" id="sf_field_data_m" size="1" ', 'value', 'text', null ); 						
						if ($front_end) {
							global $sf_lang;
							$return = ' '.$sf_lang['SF_AND_OPTION'].' "'.$f_fields_data.'"  '.$sf_lang['SF_ANSWER_IS'].' "'.$f_scale_data.'" <input type="hidden" name="sf_qtype2" id="sf_qtype2" value="'.$quest->sf_qtype.'"/>';
						} else
						$return = ' and for option "'.$f_fields_data.'"  answer is "'.$f_scale_data.'" <input type="hidden" name="sf_qtype2" id="sf_qtype2" value="'.$quest->sf_qtype.'"/>';
					} elseif ($quest->sf_qtype == 2 || $quest->sf_qtype == 3) {
						
						$query = "SELECT id AS value, ftext AS text FROM `#__survey_force_fields` WHERE quest_id = '".$quest_id."' ORDER BY ordering";
						$database->SetQuery($query);
						$f_fields_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
						
						$i =0;
						while ($i < count($f_fields_data)) {
							$f_fields_data[$i]->text = strip_tags($f_fields_data[$i]->text);
							if (strlen($f_fields_data[$i]->text) > 55)
								$f_fields_data[$i]->text = substr($f_fields_data[$i]->text, 0, 55).'...';
							$f_fields_data[$i]->text = $f_fields_data[$i]->value . ' - ' . $f_fields_data[$i]->text;
							$i ++;
						}
						
						$f_fields_data = mosHTML::selectList( $f_fields_data, 'sf_field_data_m', 'class="text_area" id="sf_field_data_m" size="1" ', 'value', 'text', null ); 
						if ($front_end){
							global $sf_lang;
							$return = ' '.$sf_lang['SF_ANSWER_IS'].' "'.$f_fields_data.'" <input type="hidden" name="sf_qtype2" id="sf_qtype2" value="'.$quest->sf_qtype.'"/>';
						}else
						$return = ' answer is "'.$f_fields_data.'" <input type="hidden" name="sf_qtype2" id="sf_qtype2" value="'.$quest->sf_qtype.'"/>';
					} elseif ($quest->sf_qtype == 5 || $quest->sf_qtype == 6 ) {
											
						$query = "SELECT id AS value, ftext AS text FROM `#__survey_force_fields` WHERE quest_id = '".$quest_id."' AND is_main = 1 ORDER BY ordering";
						$database->SetQuery($query);
						$f_fields_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
						
						$i =0;
						while ($i < count($f_fields_data)) {
							$f_fields_data[$i]->text = strip_tags($f_fields_data[$i]->text);
							if (strlen($f_fields_data[$i]->text) > 55)
								$f_fields_data[$i]->text = substr($f_fields_data[$i]->text, 0, 55).'...';
							$f_fields_data[$i]->text = $f_fields_data[$i]->value . ' - ' . $f_fields_data[$i]->text;
							$i ++;
						}
						
						$f_fields_data = mosHTML::selectList( $f_fields_data, 'sf_field_data_m', 'class="text_area" id="sf_field_data_m" size="1" ', 'value', 'text', null );  
						
						$query = "SELECT id AS value, ftext AS text FROM `#__survey_force_fields` WHERE quest_id = '".$quest_id."' AND is_main = 0 ORDER BY ordering";
						$database->SetQuery($query);						
						$f_fields_data2 = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
						
						$i =0;
						while ($i < count($f_fields_data2)) {
							$f_fields_data2[$i]->text = strip_tags($f_fields_data2[$i]->text);
							if (strlen($f_fields_data2[$i]->text) > 55)
								$f_fields_data2[$i]->text = substr($f_fields_data2[$i]->text, 0, 55).'...';
							$f_fields_data2[$i]->text = $f_fields_data2[$i]->value . ' - ' . $f_fields_data2[$i]->text;
							$i ++;
						}
						
						$f_fields_data2 = mosHTML::selectList( $f_fields_data2, 'sf_field_data_a', 'class="text_area" id="sf_field_data_a" size="1" ', 'value', 'text', null );  
						if ($front_end){
							global $sf_lang;
							$return = ' '.$sf_lang['SF_AND_OPTION'].' "'.$f_fields_data.'" '.$sf_lang['SF_ANSWER_IS'].' "'.$f_fields_data2.'" <input type="hidden" name="sf_qtype2" id="sf_qtype2" value="'.$quest->sf_qtype.'"/>';
						}else
						$return = ' and for option "'.$f_fields_data.'"  answer is "'.$f_fields_data2.'" <input type="hidden" name="sf_qtype2" id="sf_qtype2" value="'.$quest->sf_qtype.'"/>';
					} elseif ( $quest->sf_qtype == 9) {
					
						$query = "SELECT id AS value, ftext AS text FROM `#__survey_force_fields` WHERE quest_id = '".$quest_id."' AND is_main = 1 ORDER BY ordering";
						$database->SetQuery($query);
						$f_fields_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
						
						$i =0;
						while ($i < count($f_fields_data)) {
							$f_fields_data[$i]->text = strip_tags($f_fields_data[$i]->text);
							if (strlen($f_fields_data[$i]->text) > 55)
								$f_fields_data[$i]->text = substr($f_fields_data[$i]->text, 0, 55).'...';
							$f_fields_data[$i]->text = $f_fields_data[$i]->value . ' - ' . $f_fields_data[$i]->text;
							$i ++;
						}
						
						$f_fields_data = mosHTML::selectList( $f_fields_data, 'sf_field_data_m', 'class="text_area" id="sf_field_data_m" size="1" ', 'value', 'text', null );  
						$query = "SELECT id AS value, ftext AS text FROM `#__survey_force_fields` WHERE quest_id = '".$quest_id."' AND is_main = 0 ORDER BY ordering";
						$database->SetQuery($query);
						$f_fields_data2 = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
						
						$i =0;
						while ($i < count($f_fields_data2)) {
							$f_fields_data2[$i]->text = strip_tags($f_fields_data2[$i]->text);
							if (strlen($f_fields_data2[$i]->text) > 55)
								$f_fields_data2[$i]->text = substr($f_fields_data2[$i]->text, 0, 55).'...';
							$f_fields_data2[$i]->text = $f_fields_data2[$i]->value . ' - ' . $f_fields_data2[$i]->text;
							$i ++;
						}
						
						$f_fields_data2 = mosHTML::selectList( $f_fields_data2, 'sf_field_data_a', 'class="text_area" id="sf_field_data_a" size="1" ', 'value', 'text', null );  
						if ($front_end){
							global $sf_lang;
							$return = ' '.$sf_lang['SF_AND_OPTION'].' "'.$f_fields_data.'" '.$sf_lang['SF_RANK_IS'].' "'.$f_fields_data2.'" <input type="hidden" name="sf_qtype2" id="sf_qtype2" value="'.$quest->sf_qtype.'"/>';
						}else
						$return = ' and for option "'.$f_fields_data.'" rank is "'.$f_fields_data2.'" <input type="hidden" name="sf_qtype2" id="sf_qtype2" value="'.$quest->sf_qtype.'"/>';
					}
					@ob_end_clean();@ob_end_clean();
								
					@header ('Expires: Fri, 14 Mar 1980 20:53:00 GMT');
					@header ('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
					@header ('Cache-Control: no-cache, must-revalidate');
					@header ('Pragma: no-cache');
					@header ('Content-Type: text/xml charset='. _ISO);			
					@ob_end_clean();@ob_end_clean();

					echo '<?xml version="1.0" standalone="yes"?>';
					echo '<response>' . "\n";
					echo "\t" . '<data><![CDATA[';					
					echo $return."<script  type=\"text/javascript\" language=\"javascript\">jQuery('input#add_button').get(0).style.display = '';</script>";
					echo ']]></data>' . "\n";
					echo '</response>' . "\n";
					exit();
				}
			}
			break;

			# ---	EMAILS	 --- #
			case 'emails':			SF_ListEmails( $option );							break;
			case 'add_email':		SF_editEmail( '0', $option );						break;
			case 'edit_email':		SF_editEmail( intval( $cid[0] ), $option );			break;
			case 'editA_email':		SF_editEmail( $id, $option );						break;
			case 'apply_email':
			case 'save_email':		SF_saveEmail( $option );							break;
			case 'del_email':		SF_removeEmail( $cid, $option );					break;
			case 'cancel_email':	SF_cancelEmail( $option );							break;
			# --- INVITATIONS --- #
			case 'generate_invitations':  SF_genInvitations( $option ); 				break;
			case 'make_inv_list': 	SF_makeInvList(); break;
			case 'invite_users':	SF_inviteUsers( intval( $cid[0] ), $option );		break;
			case 'remind_users':	SF_remindUsers( intval( $cid[0] ), $option );		break;
			# --- TASKS from IFRAME	--- #
			case 'invitation_start':SF_startInvitation( $option );						break;
			case 'invitation_stop':	@ob_end_clean();	die();							break;
			case 'remind_start':	SF_startRemind( $option );							break;
			case 'remind_stop':		@ob_end_clean();	die();							break;

	}
}

function SF_editUsergroup( $id, $option ){
	global $database, $my, $SF_SESSION, $mainframe;
	$sf_config = new mos_Survey_Force_Config( );

	$limit		= intval( mosGetParam( $_REQUEST, 'limit', $SF_SESSION->get('list_limit',$mainframe->getCfg('list_limit')) ) );
	$SF_SESSION->set('list_limit', $limit);
	$limitstart = intval( mosGetParam( $_REQUEST, 'limitstart', 0 ) );
	$listname		=  mosGetParam( $_REQUEST, 'listname', '');

	// get the total number of records
	$query = "SELECT COUNT(*) FROM #__users ORDER BY username ";
	$database->setQuery( $query );
	$total = $database->loadResult();

	$pageNav = new SFPageNav( $total, $limitstart, $limit  );
	
	$lists = array();
	$lists['listname'] = $listname;
	$lists['listid'] = 0;
	if ($id) {
		$query = "SELECT * FROM #__survey_force_listusers WHERE id = $id ";
		$database->setQuery( $query );
		$list = null;
		$database->loadObject($list);
		if ( $listname == '' )
			$lists['listname'] = $list->listname;
		$lists['listid'] = $list->id;
	}
	$query = "SELECT id AS value, sf_name AS text"
	. "\n FROM #__survey_force_survs WHERE published = 1"
	. ($my->usertype != 'Super Administrator'? " AND sf_author = '".$my->id."' ": '')
	. "\n ORDER BY sf_name"
	;
	$database->setQuery( $query );
	$surveys = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
	
	$database->setQuery("SELECT `survey_id` FROM #__survey_force_listusers WHERE `id` = '".$id."'");
	$surv_id = $database->loadResult();
	
	$survey = mosHTML::selectList( $surveys, 'survey_id', 'class="text_area" size="1" ', 'value', 'text', (isset($surv_id) ? $surv_id : null ) ); 
	$lists['survey'] = $survey; 
	$lists['date_created'] = '';
	
	if ($sf_config->get('sf_enable_lms_integration')) {
		$query = "SELECT lms_usertype_id FROM #__lms_users WHERE user_id = '{$my->id}'";
		$database->SetQuery( $query );
		$is_super = $database->LoadResult();
		
		$query = "SELECT id FROM `#__lms_courses` ";
		$database->SetQuery( $query );
		$courses = @array_merge(array(0=>0), ($database->LoadResultArray() == null? array(): $database->LoadResultArray()) );
		$usergroups = array();
		foreach($courses as $course_id) {
			if ($is_super == 5) {
				$query = "SELECT DISTINCT a.id AS value, concat(c.course_name, ' (', a.ug_name, ')') AS text "
						."FROM #__lms_usergroups AS a, #__lms_user_courses AS b, #__lms_courses AS c "
						."WHERE a.course_id = '{$course_id}' AND b.course_id = a.course_id AND c.id = a.course_id ORDER BY c.course_name";
			} 
			else {
				$query = "SELECT DISTINCT a.id AS value, concat(c.course_name, ' (', a.ug_name, ')') AS text "
						."FROM #__lms_usergroups AS a, #__lms_user_courses AS b, #__lms_courses AS c "
						."WHERE a.course_id = '{$course_id}' AND b.course_id = a.course_id AND b.user_id = '{$my->id}' AND b.role_id IN (1,4) AND c.id = a.course_id ORDER BY c.course_name";
			}
			
			$query2 = "SELECT DISTINCT concat('0_', c.id) AS value, concat(c.course_name, ' (Users without group)') AS text "
					. "FROM #__lms_user_courses AS b, #__lms_courses AS c "
					. "WHERE b.course_id = '{$course_id}' AND  c.id = b.course_id ORDER BY c.course_name";
			$database->SetQuery( $query2 );	
			$usergroups = @array_merge($usergroups, ($database->LoadObjectList() == null? array(): $database->LoadObjectList()));
			
			$database->SetQuery( $query );

			$usergroups = @array_merge($usergroups, ($database->LoadObjectList() == null? array(): $database->LoadObjectList()));
		}
		$usergroups = mosHTML::selectList( $usergroups, 'lms_groups[]', 'class="text_area" size="4" multiple="multiple" ', 'value', 'text', 0 ); 
		$lists['lms_groups'] = $usergroups; 
	}
	
	$query = "SELECT * FROM #__users ORDER BY username"
			."\n LIMIT $pageNav->limitstart, $pageNav->limit";
	if ($sf_config->get('sf_enable_jomsocial_integration')) {
		$query = "SELECT u.* FROM #__users AS u, #__community_connection AS j WHERE u.id = j.connect_to AND j.status = 1 AND j.connect_from = '{$my->id}' ORDER BY u.username"
			."\n LIMIT $pageNav->limitstart, $pageNav->limit";
	}
	$database->SetQuery($query);
	$row = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
	survey_force_front_html::SF_editListUsers( $row, $lists, $sf_config, $pageNav, $option );
}

function SF_saveUsergroup( &$cid, $option ){
	global $database, $task, $Itemid,$Itemid_s, $my;

	$row = new mos_Survey_Force_ListUsers( $database );
	if (!$row->bind( $_POST )) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	// pre-save checks
	if (!$row->check()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	if (!$row->id) {
		$row->date_created = date( 'Y-m-d H:i:s' );
	}
	// save the changes
	if (!$row->store()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	$list_id 	= $row->id;
	$is_add_man	= intval( mosGetParam( $_POST, 'is_add_manually', 0 ) );
	$is_add_lms	= intval( mosGetParam( $_POST, 'is_add_lms', 0 ) );
	if ($is_add_man && count($cid) > 0) {
		$query = "SELECT name, username, email FROM #__users WHERE id IN (".implode(',',$cid).") ";
		$database->SetQuery($query);
		$mos_users = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
		foreach ($mos_users as $mos_user) {
			$row_user = new mos_Survey_Force_UserInfo( $database );
			$row_user->name = $mos_user->username;
			$row_user->lastname = $mos_user->name;
			$row_user->email = $mos_user->email;
			$row_user->list_id = $list_id;
			if (!$row_user->check()) {
				continue;
			}
			elseif (!$row_user->store()) {
				echo "<script> alert('".$row_user->getError()."'); window.history.go(-1); </script>\n";
				exit();
			}
		}
	}
	if ($is_add_lms) {
		$lms_groups = mosGetParam( $_POST, 'lms_groups', array() );
		if (count($lms_groups) > 0) {
			$query = "SELECT lms_usertype_id FROM #__lms_users WHERE user_id = '".$my->id."'";
			$database->SetQuery( $query );
			$is_super = $database->LoadResult();			
			if ($is_super == 5) {
			$query = "SELECT course_id FROM #__lms_user_courses";
			} else {
			$query = "SELECT distinct course_id FROM #__lms_user_courses WHERE user_id = '".$my->id."' AND role_id IN (1,4)";
			}
			$database->SetQuery( $query );
			$teacher_in_courses = $database->LoadResultArray();
			$teacher_in_courses_str = implode(',', $teacher_in_courses);
			$lms_group_str = "'-1',";
			$teacher_in_courses_str2 = '';
			foreach($lms_groups as $lms_group){
				if (strpos($lms_group,'_') > 0) {
					$teacher_in_courses_str2 .= substr($lms_group, 2).',';
				}
				else 
					$lms_group_str .= $lms_group.',';
			}
			$lms_group_str = substr($lms_group_str, 0, -1);
			$teacher_in_courses_str2 = substr($teacher_in_courses_str2, 0, -1);
			$query = "SELECT user_id FROM #__lms_users_in_groups WHERE (group_id IN ({$lms_group_str}) AND course_id IN ({$teacher_in_courses_str})) "
					.($teacher_in_courses_str2 != ''? " OR (group_id = 0 AND course_id IN ({$teacher_in_courses_str2}))":'');
			$database->SetQuery($query);
			
			$lms_users = $database->LoadResultArray();
			$query = "SELECT name, username, email FROM #__users WHERE id IN (".implode(',', $lms_users).")";
			$database->SetQuery($query);
			$mos_users = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
			foreach ($mos_users as $mos_user) {
				$row_user = new mos_Survey_Force_UserInfo( $database );
				$row_user->name = '';
				$row_user->lastname = $mos_user->name;
				$row_user->email = $mos_user->email;
				$row_user->list_id = $list_id;
				if (!$row_user->check()) {
					continue;
				}
				elseif (!$row_user->store()) {
					echo "<script> alert('".$row_user->getError()."'); window.history.go(-1); </script>\n";
					exit();
				}
			}
		}
	}	
	if ($task == 'save_list')
		mosRedirect(SFRoute("index.php?option=$option{$Itemid_s}&task=usergroups"));	
	elseif ($task == 'apply_list')
		mosRedirect(SFRoute("index.php?option=$option{$Itemid_s}&task=edit_list&id=".$list_id));
	elseif ($task == 'save_user')
		mosRedirect(SFRoute("index.php?option=$option{$Itemid_s}&task=view_users&list_id=".$list_id));
}


function SF_addUser2Group( $option ) {
	global $Itemid,$Itemid_s, $SF_SESSION;
	$listid 	= intval( mosGetParam( $_REQUEST, 'list_id', $SF_SESSION->get('list_list_id',0) ) );	
	if ($listid)
		SF_editUsergroup( $listid , $option );
	else
		mosRedirect(SFRoute("index.php?option=$option{$Itemid_s}&task=usergroups"));
}

function SF_delUserFromGroup( $cid, $option ) {
	global $database, $task, $Itemid,$Itemid_s;
	if (count($cid)) {
		$cids = implode(',', $cid);
		$query = "DELETE FROM #__survey_force_users WHERE id IN ($cids) ";
		$database->setQuery( $query );
		if (!$database->query()) {
			echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		}
	}
	mosRedirect(SFRoute("index.php?option=$option{$Itemid_s}&task=view_users"));
}

function SF_delUsergroup( $cid, $option ){
	global $database, $task, $Itemid,$Itemid_s;
	if (count($cid)) {
		$cids = implode(',', $cid);
		$query = "DELETE FROM #__survey_force_users WHERE list_id IN ($cids) ";
		$database->setQuery( $query );
		if (!$database->query()) {
			echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		}
		$query = "DELETE FROM #__survey_force_listusers WHERE id IN ($cids) ";
		$database->setQuery( $query );
		if (!$database->query()) {
			echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		}
	}
	mosRedirect(SFRoute("index.php?option=$option{$Itemid_s}&task=usergroups"));
	
}
function SF_cancelViewUsers( $option ){
	global $database, $task, $Itemid, $Itemid_s;
	mosRedirect(SFRoute("index.php?option=$option{$Itemid_s}&task=view_users"));
}

class SFPageNav {
	/** The record number to start dislpaying from 
	 *  @var int */
	var $limitstart 	= null;
	/** Number of rows to display per page
	 * @var int */
	var $limit 			= null;
	/** Total number of rows
	 * @var int */
	var $total 			= null;

	var $prefix = null;

	var $_viewall = false;

	function SFPageNav( $total, $limitstart, $limit ) {
		$this->total 		= (int) $total;
		$this->limitstart 	= (int) max( $limitstart, 0 );
		$this->limit 		= (int) max( $limit, 1 );
		if ($this->limit > $this->total) {
			$this->limitstart = 0;
		}
		if (($this->limit-1)*$this->limitstart > $this->total) {
			$this->limitstart -= $this->limitstart % $this->limit;
		}
	}
	/**
	* @return string The html for the limit # input box
	*/
	function getLimitBox ($link) {
		global $mosConfig_list_limit;
		$limits = array();
		foreach ( array(5,10,15,20,25,50,100) as $i ) {
			$limits[] = mosHTML::makeOption( "$i" );
		}

		// build the html select list
		$link = $link ."&amp;limit='+this.options[selectedIndex].value+'&amp;limitstart=". $this->limitstart;
		//$link = sefRelToAbs( $link );
		$link = str_replace('%5C%27',"'", $link);$link = str_replace('%5B',"[", $link);$link = str_replace('%5D',"]", $link);
		return mosHTML::selectList( $limits, 'limit', 'class="inputbox" size="1" onchange="document.location.href=\''. $link .'\';"', 'value', 'text', $this->limit); 

	}
	/**
	* Writes the html limit # input box
	*/
	function writeLimitBox ($link) {
		echo mosPageNav::getLimitBox($link);
	}
	function writePagesCounter() {
		echo $this->getPagesCounter();
	}
	/**
	* @return string The html for the pages counter, eg, Results 1-10 of x
	*/
	function getPagesCounter() {
		$html = '';
		$from_result = $this->limitstart+1;
		if ($this->limitstart + $this->limit < $this->total) {
			$to_result = $this->limitstart + $this->limit;
		} else {
			$to_result = $this->total;
		}
		if ($this->total > 0) {
			$html .= "\nResults <strong>" . $from_result . " - " . $to_result . "</strong> of total <strong>" . $this->total . "</strong>";

		} else {
			$html .= "\nNo results.";
		}
		return $html;
	}
	/**
	* Writes the html for the pages counter, eg, Results 1-10 of x
	*/
	function writePagesLinks($link) {
		echo $this->getPagesLinks($link);
	}
	/**
	* @return string The html links for pages, eg, previous, next, 1 2 3 ... x
	*/
	function getPagesLinks($link) {
		$limitstart = max( (int) $this->limitstart, 0 );
		$limit		= max( (int) $this->limit, 1 );
		$total		= (int) $this->total;
		$html 				= '';
		$displayed_pages 	= 10;		// set how many pages you want displayed in the menu (not including first&last, and ev. ... repl by single page number.
		$total_pages = ceil( $total / $limit );
		$this_page = ceil( ($limitstart+1) / $limit );

		$start_loop = $this_page-floor($displayed_pages/2);
		if ($start_loop < 1) {
			$start_loop = 1;
		}
		if ($start_loop == 3) {
			$start_loop = 2;
		}
		if ( $start_loop + $displayed_pages - 1 < $total_pages - 2 ) {
			$stop_loop = $start_loop + $displayed_pages - 1;
		} else {
			$stop_loop = $total_pages;
		}

		if ($this_page > 1) {
			$page = ($this_page - 2) * $this->limit;
			$html .= "\n<a href=\"". sefRelToAbs( "$link&amp;limitstart=0" ) ."\" class=\"pagenav\" title=\"First\">&lt;&lt;&nbsp;First</a>";
			$html .= "\n<a href=\"".sefRelToAbs( "$link&amp;limitstart=$page" )."\" class=\"pagenav\" title=\"Prev\">&lt;&nbsp;Prev</a>";
			if ($start_loop > 1) {
				$html .= "\n<a href=\"". sefRelToAbs( "$link&amp;limitstart=0" ) ."\" class=\"pagenav\" title=\"First\">&nbsp;1</a>";
			}
			if ($start_loop > 2) {
				$ret .= "\n<span class=\"pagenav\"> <strong>...</strong> </span>";
			}
		} else {
			$html .= "\n<span class=\"pagenav\">&lt;&lt;&nbsp;First</span>";
			$html .= "\n<span class=\"pagenav\">&lt;&nbsp;Prev</span>";
		}

		for ($i=$start_loop; $i <= $stop_loop; $i++) {
			$page = ($i - 1) * $this->limit;
			if ($i == $this_page) {
				$html .= "\n<span class=\"pagenav\"> $i </span>";
			} else {
				$html .= "\n<a href=\"".sefRelToAbs( "$link&amp;limitstart=$page" )."\" class=\"pagenav\"><strong>$i</strong></a>";
			}
		}

		if ($this_page < $total_pages) {
			$page = $this_page * $this->limit;
			$end_page = ($total_pages-1) * $this->limit;
			if ($stop_loop < $total_pages-1) {
				$html .= "\n<span class=\"pagenav\"> <strong>...</strong> </span>";
			}
			if ($stop_loop < $total_pages) {
				$html .= "\n<a href=\"".sefRelToAbs( "$link&amp;limitstart=$end_page" )."\" class=\"pagenav\" title=\"End\"> <strong>" . $total_pages."</strong></a>";
			}
			$html .= "\n<a href=\"".sefRelToAbs( "$link&amp;limitstart=$page" )."\" class=\"pagenav\" title=\"Next\"> Next&nbsp;&gt;</a>";
			$html .= "\n<a href=\"".sefRelToAbs( "$link&amp;limitstart=$end_page" )."\" class=\"pagenav\" title=\"End\"> End&nbsp;&gt;&gt;</a>";
		} else {
			$html .= "\n<span class=\"pagenav\">Next&nbsp;&gt;</span>";
			$html .= "\n<span class=\"pagenav\">End&nbsp;&gt;&gt;</span>";
		}
		return $html;
	}
	function getListFooter($link) {
		$html = '<table class="adminlist"><tr><th colspan="3" style="text-align:center;">';
		$html .= $this->getPagesLinks($link);
		$html .= '</th></tr><tr>';
		$html .= '<td nowrap="nowrap" width="48%" align="right">Display #</td>';
		$html .= '<td>' .$this->getLimitBox($link) . '</td>';
		$html .= '<td nowrap="nowrap" width="48%" align="left">' . $this->getPagesCounter() . '</td>';
		$html .= '</tr></table>';
  		return $html;
	}
	/**
	 * Sets the vars for the page navigation template
	 */
	function setTemplateVars( &$tmpl, $link='', $name = 'admin-list-footer' ) {
		$tmpl->addVar( $name, 'PAGE_LINKS', $this->getPagesLinks($link) );
		$tmpl->addVar( $name, 'PAGE_LIST_OPTIONS', $this->getLimitBox($link) );
		$tmpl->addVar( $name, 'PAGE_COUNTER', $this->getPagesCounter() );
	}

	function isMultiPages() {
		return ($this->total > $this->limit );
	}
	
	function rowNumber($i) {
		return $i + 1 + $this->limitstart; 
	}
}

?>