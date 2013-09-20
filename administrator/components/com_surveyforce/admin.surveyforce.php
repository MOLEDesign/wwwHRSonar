<?php
/**
* Survey Force component for Joomla
* @version $Id: admin.surveyforce.php 2009-11-16 17:30:15
* @package Survey Force
* @subpackage admin.surveyforce.php
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined( '_VALID_MOS' ) or defined( '_JEXEC' ) or die( 'Restricted access' );


define ( 'EXCEL_REPORT_TEMPLATES', serialize( array ( 
							"CBC-Dutch" => array("CBC-Dutch-Final.xlsx",8),
							"CBC-English" => array("CBC-English-Final.xlsx",1),
							"CBC-French" => array("CBC-French-Final.xlsx",10),
							"CWC-Dutch" => array("CWC-Dutch-Final.xlsx",14),
							"CWC-English" => array("CWC-English-Final.xlsx",13),
							"CWC-French" => array("CWC-French-Final.xlsx",15),
							"SBC-Dutch" => array("SBC-Dutch-Final.xlsx",16),
							"SBC-English" => array("SBC-English-Final.xlsx",11),
							"SBC-French" => array("SBC-French-Final.xlsx",17),
							"SWC-Dutch" => array("SWC-Dutch-Final.xlsx",18),
							"SWC-English" => array("SWC-English-Final.xlsx",12),
							"SWC-French" => array("SWC-French-Final.xlsx",19)
)));


if ((int)ini_get('memory_limit') < 32) {
	ini_set("memory_limit","32M");
}
require_once (dirname(__FILE__).'/component.legacy.php');

if (!defined('_JOOMLA15')) {
	if (defined( '_JLEGACY' ) or defined( '_BITS_LEGACY' ))
		define( '_JOOMLA15', 1 );
	else
		define( '_JOOMLA15', 0 );
}
global $mosConfig_absolute_path, $mainframe;

if (_JOOMLA15) {
	require_once( $mosConfig_absolute_path . '/administrator/components/com_surveyforce/def.php' );

	if (!defined('_SEL_CATEGORY')) define( '_SEL_CATEGORY', '- '.JText::_('COM_SF_SELECT_CATEGORY').' -');
	if (!defined('_CMN_NEW_ITEM_FIRST')) define( '_CMN_NEW_ITEM_FIRST', JText::_('COM_SF_NEW_ITEMS_DEFAULT_TO_THE_FIRST_PLACE'));
	if (!defined('_PDF_GENERATED')) define('_PDF_GENERATED',JText::_('COM_SF_GENERATED'));
	if (!defined('_CURRENT_SERVER_TIME_FORMAT')) define( '_CURRENT_SERVER_TIME_FORMAT', '%Y-%m-%d %H:%M:%S' );
	$now = date( 'Y-m-d H:i', time() ); 
	if (!defined('_CURRENT_SERVER_TIME')) define( '_CURRENT_SERVER_TIME', $now ); 
	if (!defined('_PN_DISPLAY_NR')) define('_PN_DISPLAY_NR',JText::_('COM_SF_DISPLAY'));
}

$GLOBALS['sf_allow15'] = 1;
global $sf_allow15;

$GLOBALS['survey_version'] = '3.0.6';
global $survey_version;
	
if (!defined('_SURVEY_FORCE_COMP_NAME')) {
	define( '_SURVEY_FORCE_COMP_NAME', JText::_('COM_SF_SURVEYFORCE_DELUXE_VER').$survey_version );
}
global $my, $mosConfig_absolute_path, $database, $sf_version;
$sf_version = $survey_version;

if (!isset($front_end))
	$front_end = false;

if (!_JOOMLA15 && $front_end && !function_exists('SFRoute')) {
	require_once($mosConfig_absolute_path."/includes/sef.php");
}

if ($my->id && !$front_end)
{
	if (!defined('_SURVEY_FORCE_ADMIN_HOME')) {
		define('_SURVEY_FORCE_ADMIN_HOME', dirname(__FILE__));
	}
	
	if (!defined('_SURVEY_FORCE_AFTER_SAVE')) {
		define('_SURVEY_FORCE_AFTER_SAVE', JText::_('COM_SF_THIS_OPTION_AVAILABLE_AFTER_SAVE'));
	}
	
	require_once(_SURVEY_FORCE_ADMIN_HOME.'/../../../components/com_surveyforce/surveyforce.class.php');
	require_once( $mainframe->getPath( 'admin_html' ) );
	$sf_config = new mos_Survey_Force_Config( );
	$sf_version = $sf_config->get('sf_version');

	require_once( $mosConfig_absolute_path . '/administrator/components/com_surveyforce/def.php' );

	global $survey;

	$task 	= mosGetParam( $_REQUEST, 'task', '' );
	$template 	= mosGetParam( $_REQUEST, 'template', '' );
	#echo $task;
	$id 	= intval( mosGetParam( $_REQUEST, 'id', 0 ) );
	
	$surveyID = intval( mosGetParam( $_REQUEST, 'surv_id', 0 ) );
	
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
		$cid = @array_merge($cid, $database->loadResultArray());
	}	
	
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
	switch ($task)
	{
		
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
		case 'new_question_type':SF_new_question_type();							break;
		case 'publish_quest':	SF_changeQuestion( $cid, 1, $option );				break;
		case 'unpublish_quest':	SF_changeQuestion( $cid, 0, $option );				break;
		case 'compulsory_quest': 	SF_changeCompulsory( $cid, 1, $option );		break;
		case 'uncompulsory_quest':	SF_changeCompulsory( $cid, 0, $option );		break;
		case 'questions':		SF_ListQuestions( $option );						break;
		case 'add_new_section':	SF_editSection( '0', $option );						break;
		case 'editA_sec':		SF_editSection( $id, $option );						break;
		case 'apply_section':
		case 'save_section':	SF_saveSection( $option );							break;
		case 'cancel_section':	if (!$front_end )
									mosRedirect("index2.php?option=$option&task=questions");
								else {
									global $Itemid,$Itemid_s;
									mosRedirect(SFRoute("index.php?option=$option{$Itemid_s}&task=questions"));
								}								
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
		case 'edit_quest':		if (intval( $cid[0] ) > 0) {
									SF_editQuestion( intval( $cid[0] ), $option );
									break;
								}		
								if (intval( $sec[0] ) > 0) {
									if (!$front_end )
										mosRedirect("index2.php?option=$option&task=editA_sec&id=".intval( $sec[0] ));	
									else {
										global $Itemid,$Itemid_s;
										mosRedirect(SFRoute("index.php?option=$option{$Itemid_s}&task=editA_sec&id=".intval( $sec[0] )));
									}
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
					@header ('Expires: Fri, 14 Mar 1980 20:53:00 GMT');
					@header ('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
					@header ('Cache-Control: no-cache, must-revalidate');
					@header ('Pragma: no-cache');
					@header ('Content-Type: text/xml charset='. _ISO);			
					
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
# ---    USERS    --- #
		case 'users':			SF_manageUsers( $option );							break;
		case 'add_list':		SF_editListUsers( '0', $option );					break;
		case 'edit_list':		SF_editListUsers( intval( $cid[0] ) , $option );	break;
		case 'save_list':		SF_saveListUsers( $option );						break;
		case 'del_list':		SF_removeListUsers( $cid, $option );				break;
		case 'cancel_list':		SF_cancelListUsers( $option );						break;
		case 'view_users':		SF_viewUsers( $option );							break;
		case 'add_user':		SF_editUser( '0', $option );						break;
		case 'edit_user':		SF_editUser( intval( $cid[0] ), $option );			break;
		case 'editA_user':		SF_editUser( $id, $option );						break;
		case 'cancel_user':		SF_cancelUser( $option );							break;
		case 'apply_user':
		case 'save_user':		SF_saveUser( $option );								break;
		case 'del_user':		SF_removeUser( $cid, $option );						break;
		case 'move_user_sel':	SF_moveUserSelect( $cid, $option );					break;
		case 'move_user_save':	SF_moveUserSave( $cid );							break;
		case 'copy_user_sel':	SF_moveUserSelect( $cid, $option );					break;
		case 'copy_user_save':	SF_copyUserSave( $cid, $option );					break;
		case 'copy_list':		SF_copyListUsers( $cid, $option );					break;
		case 'copy_all':		SF_copyListUsers( -1, $option );					break;
		case 'authors':			SF_manageAuthors( $option );						break;
		case 'del_author':		SF_delAuthors( $cid, $option );						break;
		case 'save_author':		SF_saveAuthors( $cid, $option );					break;
		case 'cancel_author':   SF_cancelAuthors( $option );						break;
		case 'add_author':		SF_showAddAuthors( $option );						break;
# --- INVITATIONS --- #
		case 'generate_invitations':  SF_genInvitations( $option ); 				break;
		case 'make_inv_list': 	SF_makeInvList(); break;
		case 'invite_users':	SF_inviteUsers( intval( $cid[0] ), $option );		break;
		case 'remind_users':	SF_remindUsers( intval( $cid[0] ), $option );		break;
# ---	EMAILS	 --- #
		case 'emails':			SF_ListEmails( $option );							break;
		case 'add_email':		SF_editEmail( '0', $option );						break;
		case 'edit_email':		SF_editEmail( intval( $cid[0] ), $option );			break;
		case 'editA_email':		SF_editEmail( $id, $option );						break;
		case 'apply_email':
		case 'save_email':		SF_saveEmail( $option );							break;
		case 'del_email':		SF_removeEmail( $cid, $option );					break;
		case 'cancel_email':	SF_cancelEmail( $option );							break;
# --- TASKS from IFRAME	--- #
		case 'invitation_start':SF_startInvitation( $option );						break;
		case 'invitation_stop':	@ob_end_clean();	die();							break;
		case 'remind_start':	SF_startRemind( $option );							break;
		case 'remind_stop':		@ob_end_clean();	die();							break;
# ---  REPORTS  --- #
		case 'reports':			set_time_limit(0);SF_ViewReports($option);							break;
		case 'generateExcel':	set_time_limit(0);SF_GenerateExcel($option);							break;
		case 'generateExcelNL':	set_time_limit(0);SF_GenerateExcelNL($option);							break;
		case 'generateExcelSP':	set_time_limit(0);SF_GenerateExcelSP($option, 0, $template, $surveyID );							break;
		case 'rep_pdf':			set_time_limit(0);SF_ViewReportsPDF_full($option, $cid, 1);			break;
		case 'rep_csv':			set_time_limit(0);SF_ViewReportsCSV_full($option, $cid);			break;
		case 'rep_pdf_sum': 	set_time_limit(0);SF_ViewRepUsers( $cid, $option, 1 );				break;
		case 'rep_pdf_sum_pc': 	set_time_limit(0);SF_ViewRepUsers( $cid, $option, 1, 1 );			break;
		case 'del_rep':			set_time_limit(0);SF_removeRep( $cid, $option);						break;
		case 'del_rep_all':		set_time_limit(0);SF_removeRepAll( $option );						break;
		case 'view_result':		set_time_limit(0);SF_ViewRepResult( $id, $option);					break;
		case 'view_result_c':	set_time_limit(0);SF_ViewRepResult( intval( $cid[0] ), $option);	break;
		case 'rep_surv':		SF_ListSurveys($option);											break;
		case 'view_rep_surv':	set_time_limit(0);SF_ViewRepSurv( intval( $cid[0] ), $option);		break;
		case 'view_rep_survA':	set_time_limit(0);SF_ViewRepSurv( $id, $option);					break;
		case 'rep_surv_print':	set_time_limit(0);SF_ViewRepSurv( $id, $option, 1);					break;
		case 'rep_print':		set_time_limit(0);SF_ViewRepResult( $id, $option, 1);				break;
		case 'rep_list':		set_time_limit(0);SF_manageUsers( $option );						break;
		case 'view_rep_list':	set_time_limit(0);SF_ViewRepList( intval( $cid[0] ), $option);		break;
		case 'view_rep_listA':	set_time_limit(0);SF_ViewRepList( $id, $option);					break;
		case 'rep_list_print':	set_time_limit(0);SF_ViewRepList( $id, $option, 1);					break;
			
		case 'adv_report':		set_time_limit(0);SF_ViewAdvReport( $option ); 						break;	
		case 'view_irep_surv':	set_time_limit(0);SF_ViewIRepSurv( intval( $cid[0] ), $option);		break;
		case 'get_cross_rep':	@set_time_limit(0);SF_getCrossReport( $option );					break;
		
# ---    CONFIG    --- #
		case 'config':			SF_viewConfig( $option );							break;
		case 'save_config':		SF_saveConfig( $option );							break;
		case 'show_preview':	SF_showPreview( $option );							break;
# ---    IMP SCALES    --- #
		case 'iscales':			SF_viewIScales( $option );							break;
		case 'add_iscale':		SF_editIScale( '0', $option );						break;
		case 'add_iscale_from_quest':
								$mainframe->setUserState( "quest_redir", intval(mosGetParam($_REQUEST, 'quest_id', 0)));
								$mainframe->setUserState( "task_redir", strval(mosGetParam($_REQUEST, 'red_task', '')));
								SF_editIScale( '0', $option );						break;
		case 'edit_iscale':		SF_editIScale( intval( $cid[0] ), $option );		break;
		case 'editA_iscale':	SF_editIScale( $id, $option );						break;
		case 'apply_iscale':
		case 'save_iscale_A':
		case 'save_iscale':		SF_saveIScale( $option );							break;
		case 'del_iscale':		SF_removeIScale( $cid, $option );					break;
		case 'cancel_iscale_A':
		case 'cancel_iscale':	SF_cancelIScale( $option );							break;
# ---   TEMPLATES    --- #
		case 'templates':		SF_templateManager( $option );						break;
		case 'add_template':	SF_editTemplate( '0', $option );					break;
		case 'apply_template':
		case 'uploadtemplate':
		case 'save_template':	SF_saveTemplate( $option );							break;
		case 'del_template':	SF_removeTemplate( $cid, $option );					break;
		case 'cancel_template':	mosRedirect("index2.php?option=$option&task=templates"); break;
		case 'edit_css':		SF_editTemplateCSS( intval( $cid[0] ), $option );	break;
		case 'save_css':		SF_saveTemplateCSS( $option );						break;	
		
# ---    XREHb    --- #
		case 'sample':			survey_force_adm_html::View_Samples();				break;
		case 'installsample1':	SF_installSample( 1 );								break;	
		case 'installsample2':	SF_installSample( 2 );								break;	
		case 'insert_tag':		insertSurvey();										break;
		case 'about':			survey_force_adm_html::View_AboutPage_HTML();		break;
		case 'disclaimer':		survey_force_adm_html::View_AboutPage();			break;
		case 'license':			survey_force_adm_html::standart_LicenseFile();		break;
		case 'support':			survey_force_adm_html::View_SupportPage();			break;
		case 'help':			survey_force_adm_html::View_HelpPage();				break;
		case 'history':			survey_force_adm_html::View_HistoryPage();			break;
		case 'faq':				survey_force_adm_html::View_FAQPage();				break;
		case 'latestVersion': 	ep_latestVersion();									break;
		case 'latestNews':		SF_latestNews();									break;

		default:				survey_force_adm_html::View_AboutPage_HTML();		break;
	}
}

function SF_installSample( $type ){
	global $database, $mainframe;
	
	if ($type == 1){
		
		$query = "INSERT INTO `#__survey_force_survs` (`id`, `sf_name`, `sf_descr`, `sf_image`, `sf_cat`, `sf_lang`, `sf_date`, `sf_author`, `sf_public`, `sf_invite`, `sf_reg`, `sf_friend`, `published`, `sf_fpage_type`, `sf_fpage_text`, `sf_special`, `sf_auto_pb`, `sf_progressbar`, `sf_progressbar_type`, `sf_use_css`, `sf_enable_descr`, `sf_reg_voting`, `sf_friend_voting`, `sf_inv_voting`, `sf_template`, `sf_pub_voting`, `sf_pub_control`, `surv_short_descr`, `sf_after_start`, `sf_anonymous`, `sf_random`) VALUES (NULL, 'Customer Service Satisfaction Survey', '<img src=\"http://demo.joomplace.com/images/survey_icon.jpg\" align=\"left\" height=\"200px\"><p style=\"text-align:justify\">\r\nWe all know customer satisfaction is essential to the survival of our businesses. How do we find out whether our customers are satisfied? The best way to find out whether your customers are satisfied is to ask them.\r\n</p>\r\n<p style=\"text-align:justify\">\r\nWhen you conduct a customer satisfaction survey, what you ask the customers is important. How, when , and how often you ask these questions are also important. However, the most important thing about conducting a customer satisfaction survey is what you do with their answers. \r\n</p>', '', 1, 1, '0000-00-00 00:00:00', 62, 1, 0, 1, 0, 1, 0, '<strong>End of the survey - Thank you for your time.</strong>', '0', 0, 0, 0, 0, 1, 2, 0, 1, 1, 2, 3, NULL, 0, 0, 0)";
		$database->setQuery($query);
		$database->query();		
		$new_survey_id = $database->insertid();

		$query = "INSERT INTO `#__survey_force_quests` (`id`, `sf_survey`, `sf_qtype`, `sf_qtext`, `sf_impscale`, `sf_rule`, `sf_fieldtype`, `ordering`, `sf_compulsory`, `sf_section_id`, `published`, `sf_qstyle`, `sf_num_options`, `sf_default_hided`) VALUES (NULL, {$new_survey_id}, 4, '<b>What was your main reason for contacting technical support?</b>   &nbsp;', 0, 0, '', 1, 1, 0, 1, 0, 0, 0)";
		$database->setQuery($query);
		$database->query();	
		
		$query = "INSERT INTO `#__survey_force_quests` (`id`, `sf_survey`, `sf_qtype`, `sf_qtext`, `sf_impscale`, `sf_rule`, `sf_fieldtype`, `ordering`, `sf_compulsory`, `sf_section_id`, `published`, `sf_qstyle`, `sf_num_options`, `sf_default_hided`) VALUES (NULL, {$new_survey_id}, 3, '<b>How did you contact technical support?</b>  &nbsp;', 0, 0, '', 2, 1, 0, 1, 0, 0, 0)";
		$database->setQuery($query);
		$database->query();
		$new_id = $database->insertid();
		
		$query = "INSERT INTO `#__survey_force_fields` (`id`, `quest_id`, `ftext`, `alt_field_id`, `is_main`, `is_true`, `ordering`) VALUES (NULL, {$new_id}, 'phone', 0, 1, 1, 2)";
		$database->setQuery($query);
		$database->query();
		$query = "INSERT INTO `#__survey_force_fields` (`id`, `quest_id`, `ftext`, `alt_field_id`, `is_main`, `is_true`, `ordering`) VALUES (NULL, {$new_id}, 'Other', 0, 0, 1, 3)";
		$database->setQuery($query);
		$database->query();

	
		$query = "INSERT INTO `#__survey_force_quests` (`id`, `sf_survey`, `sf_qtype`, `sf_qtext`, `sf_impscale`, `sf_rule`, `sf_fieldtype`, `ordering`, `sf_compulsory`, `sf_section_id`, `published`, `sf_qstyle`, `sf_num_options`, `sf_default_hided`) VALUES (NULL, {$new_survey_id}, 7, '<b>PLEASE TELL US HOW MUCH YOU AGREE OR DISAGREE WITH THE FOLLOWING STATEMENTS:</b> &nbsp;', 0, 0, '', 3, 0, 0, 1, 0, 0, 0)";
		$database->setQuery($query);
		$database->query();
		
		$query = "INSERT INTO `#__survey_force_quests` (`id`, `sf_survey`, `sf_qtype`, `sf_qtext`, `sf_impscale`, `sf_rule`, `sf_fieldtype`, `ordering`, `sf_compulsory`, `sf_section_id`, `published`, `sf_qstyle`, `sf_num_options`, `sf_default_hided`) VALUES (NULL, {$new_survey_id}, 1, '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', 0, 0, '', 4, 1, 0, 1, 0, 0, 0)";
		$database->setQuery($query);
		$database->query();
		$new_id = $database->insertid();

		$query = "INSERT INTO `#__survey_force_fields` (`id`, `quest_id`, `ftext`, `alt_field_id`, `is_main`, `is_true`, `ordering`) VALUES (NULL, {$new_id}, 'How fast did you get a reply from a technical support staff member?', 0, 1, 1, 0)";
		$database->setQuery($query);
		$database->query();
		
		$query = "INSERT INTO `#__survey_force_scales` (`id`, `quest_id`, `stext`, `ordering`) VALUES (NULL, {$new_id}, 'extremely slow', 0)";
		$database->setQuery($query);
		$database->query();
		$query = "INSERT INTO `#__survey_force_scales` (`id`, `quest_id`, `stext`, `ordering`) VALUES (NULL, {$new_id}, 'slow', 1)";
		$database->setQuery($query);
		$database->query();
		$query = "INSERT INTO `#__survey_force_scales` (`id`, `quest_id`, `stext`, `ordering`) VALUES (NULL, {$new_id}, 'fairly fast', 2)";
		$database->setQuery($query);
		$database->query();
		$query = "INSERT INTO `#__survey_force_scales` (`id`, `quest_id`, `stext`, `ordering`) VALUES (NULL, {$new_id}, 'fast', 3)";
		$database->setQuery($query);
		$database->query();
		$query = "INSERT INTO `#__survey_force_scales` (`id`, `quest_id`, `stext`, `ordering`) VALUES (NULL, {$new_id}, 'extremely fast', 4)";
		$database->setQuery($query);
		$database->query();


		$query = "INSERT INTO `#__survey_force_quests` (`id`, `sf_survey`, `sf_qtype`, `sf_qtext`, `sf_impscale`, `sf_rule`, `sf_fieldtype`, `ordering`, `sf_compulsory`, `sf_section_id`, `published`, `sf_qstyle`, `sf_num_options`, `sf_default_hided`) VALUES (NULL, {$new_survey_id}, 1, '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', 0, 0, '', 5, 1, 0, 1, 0, 0, 0)";
		$database->setQuery($query);
		$database->query();
		$new_id = $database->insertid();
		
		$query = "INSERT INTO `#__survey_force_fields` (`id`, `quest_id`, `ftext`, `alt_field_id`, `is_main`, `is_true`, `ordering`) VALUES (NULL, {$new_id}, 'The technical support staff was helpful.', 0, 1, 1, 0)";
		$database->setQuery($query);
		$database->query();
		
		$query = "INSERT INTO `#__survey_force_scales` (`id`, `quest_id`, `stext`, `ordering`) VALUES (NULL, {$new_id}, 'strongly disagree', 0)";
		$database->setQuery($query);
		$database->query();
		$query = "INSERT INTO `#__survey_force_scales` (`id`, `quest_id`, `stext`, `ordering`) VALUES (NULL, {$new_id}, 'disagree', 1)";
		$database->setQuery($query);
		$database->query();
		$query = "INSERT INTO `#__survey_force_scales` (`id`, `quest_id`, `stext`, `ordering`) VALUES (NULL, {$new_id}, 'more or less agree', 2)";
		$database->setQuery($query);
		$database->query();
		$query = "INSERT INTO `#__survey_force_scales` (`id`, `quest_id`, `stext`, `ordering`) VALUES (NULL, {$new_id}, 'agree', 3)";
		$database->setQuery($query);
		$database->query();
		$query = "INSERT INTO `#__survey_force_scales` (`id`, `quest_id`, `stext`, `ordering`) VALUES (NULL, {$new_id}, 'totally agree', 4)";
		$database->setQuery($query);
		$database->query();
	

		$query = "INSERT INTO `#__survey_force_quests` (`id`, `sf_survey`, `sf_qtype`, `sf_qtext`, `sf_impscale`, `sf_rule`, `sf_fieldtype`, `ordering`, `sf_compulsory`, `sf_section_id`, `published`, `sf_qstyle`, `sf_num_options`, `sf_default_hided`) VALUES (NULL, {$new_survey_id}, 1, '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', 0, 0, '', 6, 1, 0, 1, 0, 0, 0)";
		$database->setQuery($query);
		$database->query();
		$new_id = $database->insertid();
		
		$query = "INSERT INTO `#__survey_force_fields` (`id`, `quest_id`, `ftext`, `alt_field_id`, `is_main`, `is_true`, `ordering`) VALUES (NULL, {$new_id}, 'Overall, how would you rate the quality of the assistance you received from technical support?', 0, 1, 1, 0)";
		$database->setQuery($query);
		$database->query();


		$query = "INSERT INTO `#__survey_force_scales` (`id`, `quest_id`, `stext`, `ordering`) VALUES (NULL, {$new_id}, 'pure', 0)";
		$database->setQuery($query);
		$database->query();
		$query = "INSERT INTO `#__survey_force_scales` (`id`, `quest_id`, `stext`, `ordering`) VALUES (NULL, {$new_id}, 'not very good', 1)";
		$database->setQuery($query);
		$database->query();
		$query = "INSERT INTO `#__survey_force_scales` (`id`, `quest_id`, `stext`, `ordering`) VALUES (NULL, {$new_id}, 'good enough', 2)";
		$database->setQuery($query);
		$database->query();
		$query = "INSERT INTO `#__survey_force_scales` (`id`, `quest_id`, `stext`, `ordering`) VALUES (NULL, {$new_id}, 'very good', 3)";
		$database->setQuery($query);
		$database->query();
		$query = "INSERT INTO `#__survey_force_scales` (`id`, `quest_id`, `stext`, `ordering`) VALUES (NULL, {$new_id}, 'excellent', 4)";
		$database->setQuery($query);
		$database->query();
		
		$query = "INSERT INTO `#__survey_force_quests` (`id`, `sf_survey`, `sf_qtype`, `sf_qtext`, `sf_impscale`, `sf_rule`, `sf_fieldtype`, `ordering`, `sf_compulsory`, `sf_section_id`, `published`, `sf_qstyle`, `sf_num_options`, `sf_default_hided`) VALUES (NULL, {$new_survey_id}, 4, '<b>Do you have any suggestions for improvement of our technical support services?</b>&nbsp;', 0, 0, '', 7, 0, 0, 1, 0, 0, 0)";
		$database->setQuery($query);
		$database->query();
	}
	
	if ($type == 2){	
		$query = "INSERT INTO `#__survey_force_survs` (`id`, `sf_name`, `sf_descr`, `sf_image`, `sf_cat`, `sf_lang`, `sf_date`, `sf_author`, `sf_public`, `sf_invite`, `sf_reg`, `sf_friend`, `published`, `sf_fpage_type`, `sf_fpage_text`, `sf_special`, `sf_auto_pb`, `sf_progressbar`, `sf_progressbar_type`, `sf_use_css`, `sf_enable_descr`, `sf_reg_voting`, `sf_friend_voting`, `sf_inv_voting`, `sf_template`, `sf_pub_voting`, `sf_pub_control`, `surv_short_descr`, `sf_after_start`, `sf_anonymous`, `sf_random`) VALUES (NULL, 'Sample Branching Survey', '<p>\r\nThis survey presents another template and question rules you can use in your survey like:\r\n<ul>\r\n<li>If the answer is ...  don''t show question ...</li>\r\n<li>If the answer is ... go to question ...</li>\r\n</ul>\r\n</p>\r\n<p>\r\nBy the way you can enable this welcome screen but can also disable it so users won''t see it. This survey will also show the progress bar and all questions on different pages. And when you''re done answering questions, you''ll see the survey results page at the end instead of the thank you message (you can choose whether to show them in a graph charts or pie charts, and chenge the size and colors of those).\r\n</p>', '', 1, 1, '0000-00-00 00:00:00', 62, 1, 0, 1, 0, 1, 1, '<strong>End of the survey - Thank you for your time.</strong>', '0', 1, 1, 0, 0, 1, 0, 0, 1, 2, 0, 0, NULL, 0, 0, 0)";
		$database->setQuery($query);
		$database->query();		
		$new_survey_id = $database->insertid();

		$query = "INSERT INTO `#__survey_force_quests` (`id`, `sf_survey`, `sf_qtype`, `sf_qtext`, `sf_impscale`, `sf_rule`, `sf_fieldtype`, `ordering`, `sf_compulsory`, `sf_section_id`, `published`, `sf_qstyle`, `sf_num_options`, `sf_default_hided`) VALUES (NULL, {$new_survey_id}, 4, '<p>\r\nHow would you describe our organization to a friend in a couple of words?\r\n</p>\r\n<p>\r\n{x}<br />{x}\r\n</p>\r\n<p style=\"font-size:0.8em\">\r\nYou can skip this question if you don''t want to answer - it wasn''t made compulsory.\r\n</p>', 0, 0, '', 1, 0, 0, 1, 0, 0, 0)";
		$database->setQuery($query);
		$database->query();	

		$query = "INSERT INTO `#__survey_force_quests` (`id`, `sf_survey`, `sf_qtype`, `sf_qtext`, `sf_impscale`, `sf_rule`, `sf_fieldtype`, `ordering`, `sf_compulsory`, `sf_section_id`, `published`, `sf_qstyle`, `sf_num_options`, `sf_default_hided`) VALUES (NULL, {$new_survey_id}, 8, 'Page Break', 0, 0, '', 2, 0, 0, 1, 0, 0, 0)";
		$database->setQuery($query);
		$database->query();	

		$query = "INSERT INTO `#__survey_force_quests` (`id`, `sf_survey`, `sf_qtype`, `sf_qtext`, `sf_impscale`, `sf_rule`, `sf_fieldtype`, `ordering`, `sf_compulsory`, `sf_section_id`, `published`, `sf_qstyle`, `sf_num_options`, `sf_default_hided`) VALUES (NULL, {$new_survey_id}, 2, '<p>\r\nIs this your first visit to our organization?\r\n</p>\r\n<p style=\"font-size:0.8em\">\r\nIf the answer is yes, you''ll be redirected to the question ''Will you recommend it to a friend or relative'', in case it''s no, you''ll be directed to ''How many times do you usually visit each year'' question.\r\n</p>', 0, 0, '', 3, 1, 0, 1, 0, 0, 0)";
		$database->setQuery($query);
		$database->query();	
		$new_id73 = $database->insertid();
		
		$query = "INSERT INTO `#__survey_force_fields` (`id`, `quest_id`, `ftext`, `alt_field_id`, `is_main`, `is_true`, `ordering`) VALUES (NULL, {$new_id73}, 'Yes', 0, 1, 1, 0)";
		$database->setQuery($query);
		$database->query();	
		$new_id196 = $database->insertid();
		$query = "INSERT INTO `#__survey_force_fields` (`id`, `quest_id`, `ftext`, `alt_field_id`, `is_main`, `is_true`, `ordering`) VALUES (NULL, {$new_id73}, 'No', 0, 1, 1, 1)";
		$database->setQuery($query);
		$database->query();	
		$new_id197 = $database->insertid();

		$query = "INSERT INTO `#__survey_force_quests` (`id`, `sf_survey`, `sf_qtype`, `sf_qtext`, `sf_impscale`, `sf_rule`, `sf_fieldtype`, `ordering`, `sf_compulsory`, `sf_section_id`, `published`, `sf_qstyle`, `sf_num_options`, `sf_default_hided`) VALUES (NULL, {$new_survey_id}, 8, 'Page Break', 0, 0, '', 4, 0, 0, 1, 0, 0, 0)";
		$database->setQuery($query);
		$database->query();	

		$query = "INSERT INTO `#__survey_force_quests` (`id`, `sf_survey`, `sf_qtype`, `sf_qtext`, `sf_impscale`, `sf_rule`, `sf_fieldtype`, `ordering`, `sf_compulsory`, `sf_section_id`, `published`, `sf_qstyle`, `sf_num_options`, `sf_default_hided`) VALUES (NULL, {$new_survey_id}, 2, '<p>\r\nHow many times do you usually visit each year?\r\n</p>\r\n<p style=\"font-size:0.8em\">\r\nIf the answer is once a year, you''ll be redirected to ''Will you recommend it to a friend or relative'' question,\r\nall other options will lead you to whether you are a member question.\r\n</p>', 0, 0, '', 5, 1, 0, 1, 0, 0, 0)";
		$database->setQuery($query);
		$database->query();	
		$new_id75 = $database->insertid();
		
		$query = "INSERT INTO `#__survey_force_fields` (`id`, `quest_id`, `ftext`, `alt_field_id`, `is_main`, `is_true`, `ordering`) VALUES (NULL, {$new_id75}, 'Once a year', 0, 1, 1, 0)";
		$database->setQuery($query);
		$database->query();
		$new_id198 = $database->insertid();
		$query = "INSERT INTO `#__survey_force_fields` (`id`, `quest_id`, `ftext`, `alt_field_id`, `is_main`, `is_true`, `ordering`) VALUES (NULL, {$new_id75}, '2-3 times', 0, 1, 1, 1)";
		$database->setQuery($query);
		$database->query();
		$new_id199 = $database->insertid();
		$query = "INSERT INTO `#__survey_force_fields` (`id`, `quest_id`, `ftext`, `alt_field_id`, `is_main`, `is_true`, `ordering`) VALUES (NULL, {$new_id75}, '5-10 times', 0, 1, 1, 2)";
		$database->setQuery($query);
		$database->query();
		$new_id200 = $database->insertid();
		$query = "INSERT INTO `#__survey_force_fields` (`id`, `quest_id`, `ftext`, `alt_field_id`, `is_main`, `is_true`, `ordering`) VALUES (NULL, {$new_id75}, 'More than 10 times', 0, 1, 1, 3)";
		$database->setQuery($query);
		$database->query();
		$new_id201 = $database->insertid();
		

		$query = "INSERT INTO `#__survey_force_quests` (`id`, `sf_survey`, `sf_qtype`, `sf_qtext`, `sf_impscale`, `sf_rule`, `sf_fieldtype`, `ordering`, `sf_compulsory`, `sf_section_id`, `published`, `sf_qstyle`, `sf_num_options`, `sf_default_hided`) VALUES (NULL, {$new_survey_id}, 8, 'Page Break', 0, 0, '', 6, 0, 0, 1, 0, 0, 0)";
		$database->setQuery($query);
		$database->query();	

		$query = "INSERT INTO `#__survey_force_quests` (`id`, `sf_survey`, `sf_qtype`, `sf_qtext`, `sf_impscale`, `sf_rule`, `sf_fieldtype`, `ordering`, `sf_compulsory`, `sf_section_id`, `published`, `sf_qstyle`, `sf_num_options`, `sf_default_hided`) VALUES (NULL, {$new_survey_id}, 2, '<p>\r\nWill you recommend it to a friend or relative?�\r\n</p>\r\n<p style=\"font-size:0.8em\">\r\nIf you answer Yes - you end the survey and are presented with the survey results.<br />\r\nIf no, it''s next question then.\r\n</p>', 0, 0, '', 9, 0, 0, 1, 0, 0, 0)";
		$database->setQuery($query);
		$database->query();
		$new_id77 = $database->insertid();
		
		$query = "INSERT INTO `#__survey_force_fields` (`id`, `quest_id`, `ftext`, `alt_field_id`, `is_main`, `is_true`, `ordering`) VALUES (NULL, {$new_id77}, 'Yes', 0, 1, 1, 0)";
		$database->setQuery($query);
		$database->query();
		$new_id202 = $database->insertid();		
		$query = "INSERT INTO `#__survey_force_fields` (`id`, `quest_id`, `ftext`, `alt_field_id`, `is_main`, `is_true`, `ordering`) VALUES (NULL, {$new_id77}, 'No', 0, 1, 1, 1)";
		$database->setQuery($query);
		$database->query();

		$query = "INSERT INTO `#__survey_force_quests` (`id`, `sf_survey`, `sf_qtype`, `sf_qtext`, `sf_impscale`, `sf_rule`, `sf_fieldtype`, `ordering`, `sf_compulsory`, `sf_section_id`, `published`, `sf_qstyle`, `sf_num_options`, `sf_default_hided`) VALUES (NULL, {$new_survey_id}, 8, 'Page Break', 0, 0, '', 8, 0, 0, 1, 0, 0, 0)";
		$database->setQuery($query);
		$database->query();

		$query = "INSERT INTO `#__survey_force_quests` (`id`, `sf_survey`, `sf_qtype`, `sf_qtext`, `sf_impscale`, `sf_rule`, `sf_fieldtype`, `ordering`, `sf_compulsory`, `sf_section_id`, `published`, `sf_qstyle`, `sf_num_options`, `sf_default_hided`) VALUES (NULL, {$new_survey_id}, 2, 'Are you a member?\r\n&nbsp;', 0, 0, '', 7, 1, 0, 1, 0, 0, 0)";
		$database->setQuery($query);
		$database->query();
		$new_id79 = $database->insertid();
		
		$query = "INSERT INTO `#__survey_force_fields` (`id`, `quest_id`, `ftext`, `alt_field_id`, `is_main`, `is_true`, `ordering`) VALUES (NULL, {$new_id79}, 'Yes', 0, 1, 1, 0)";
		$database->setQuery($query);
		$database->query();
		$query = "INSERT INTO `#__survey_force_fields` (`id`, `quest_id`, `ftext`, `alt_field_id`, `is_main`, `is_true`, `ordering`) VALUES (NULL, {$new_id79}, 'No', 0, 1, 1, 1)";
		$database->setQuery($query);
		$database->query();
		$query = "INSERT INTO `#__survey_force_fields` (`id`, `quest_id`, `ftext`, `alt_field_id`, `is_main`, `is_true`, `ordering`) VALUES (NULL, {$new_id79}, 'In the past, but not now', 0, 1, 1, 2)";
		$database->setQuery($query);
		$database->query();

		$query = "INSERT INTO `#__survey_force_quests` (`id`, `sf_survey`, `sf_qtype`, `sf_qtext`, `sf_impscale`, `sf_rule`, `sf_fieldtype`, `ordering`, `sf_compulsory`, `sf_section_id`, `published`, `sf_qstyle`, `sf_num_options`, `sf_default_hided`) VALUES (NULL, {$new_survey_id}, 8, 'Page Break', 0, 0, '', 10, 0, 0, 1, 0, 0, 0)";
		$database->setQuery($query);
		$database->query();

		$query = "INSERT INTO `#__survey_force_quests` (`id`, `sf_survey`, `sf_qtype`, `sf_qtext`, `sf_impscale`, `sf_rule`, `sf_fieldtype`, `ordering`, `sf_compulsory`, `sf_section_id`, `published`, `sf_qstyle`, `sf_num_options`, `sf_default_hided`) VALUES (81, {$new_survey_id}, 2, '<p>\r\nWhat would encourage you to come back here?\r\n</p>\r\n<p style=\"font-size:0.8em\">\r\nThis question is not asked if you sais you''d recommend this to friends previously.\r\n</p>', 0, 0, '', 11, 1, 0, 1, 0, 0, 0)";
		$database->setQuery($query);
		$database->query();
		$new_id81 = $database->insertid();
		
		$query = "INSERT INTO `#__survey_force_quest_show` (`id`, `quest_id`, `survey_id`, `quest_id_a`, `answer`, `ans_field`) VALUES (NULL, {$new_id81}, {$new_survey_id}, {$new_id77}, {$new_id202}, 0)";
		$database->setQuery($query);
		$database->query();
		
		$query = "INSERT INTO `#__survey_force_fields` (`id`, `quest_id`, `ftext`, `alt_field_id`, `is_main`, `is_true`, `ordering`) VALUES (NULL, {$new_id81}, 'Extended hours', 0, 1, 1, 0)";
		$database->setQuery($query);
		$database->query();		
		$query = "INSERT INTO `#__survey_force_fields` (`id`, `quest_id`, `ftext`, `alt_field_id`, `is_main`, `is_true`, `ordering`) VALUES (NULL, {$new_id81}, 'Discount', 0, 1, 1, 1)";
		$database->setQuery($query);
		$database->query();		
		$query = "INSERT INTO `#__survey_force_fields` (`id`, `quest_id`, `ftext`, `alt_field_id`, `is_main`, `is_true`, `ordering`) VALUES (NULL, {$new_id81}, 'Nothing', 0, 1, 1, 2)";
		$database->setQuery($query);
		$database->query();
		$query = "INSERT INTO `#__survey_force_fields` (`id`, `quest_id`, `ftext`, `alt_field_id`, `is_main`, `is_true`, `ordering`) VALUES (NULL, {$new_id81}, 'Other', 0, 0, 1, 3)";
		$database->setQuery($query);
		$database->query();

		$query = "INSERT INTO `#__survey_force_quests` (`id`, `sf_survey`, `sf_qtype`, `sf_qtext`, `sf_impscale`, `sf_rule`, `sf_fieldtype`, `ordering`, `sf_compulsory`, `sf_section_id`, `published`, `sf_qstyle`, `sf_num_options`, `sf_default_hided`) VALUES (NULL, {$new_survey_id}, 8, 'Page Break', 0, 0, '', 12, 0, 0, 1, 0, 0, 0)";
		$database->setQuery($query);
		$database->query();


		$query = "INSERT INTO `#__survey_force_rules` (`id`, `quest_id`, `answer_id`, `next_quest_id`, `alt_field_id`, `priority`) VALUES (NULL, {$new_id73}, {$new_id197}, {$new_id75}, 0, 0)";
		$database->setQuery($query);
		$database->query();
		$query = "INSERT INTO `#__survey_force_rules` (`id`, `quest_id`, `answer_id`, `next_quest_id`, `alt_field_id`, `priority`) VALUES (NULL, {$new_id73}, {$new_id196}, {$new_id77}, 0, 0)";
		$database->setQuery($query);
		$database->query();
		$query = "INSERT INTO `#__survey_force_rules` (`id`, `quest_id`, `answer_id`, `next_quest_id`, `alt_field_id`, `priority`) VALUES (NULL, {$new_id75}, {$new_id198}, {$new_id77}, 0, 0)";
		$database->setQuery($query);
		$database->query();
		$query = "INSERT INTO `#__survey_force_rules` (`id`, `quest_id`, `answer_id`, `next_quest_id`, `alt_field_id`, `priority`) VALUES (NULL, {$new_id75}, {$new_id199}, {$new_id79}, 0, 0)";
		$database->setQuery($query);
		$database->query();
		$query = "INSERT INTO `#__survey_force_rules` (`id`, `quest_id`, `answer_id`, `next_quest_id`, `alt_field_id`, `priority`) VALUES (NULL, {$new_id75}, {$new_id200}, {$new_id79}, 0, 0)";
		$database->setQuery($query);
		$database->query();
		$query = "INSERT INTO `#__survey_force_rules` (`id`, `quest_id`, `answer_id`, `next_quest_id`, `alt_field_id`, `priority`) VALUES (NULL, {$new_id75}, {$new_id201}, {$new_id79}, 0, 0)"; 
		$database->setQuery($query);
		$database->query();
	}
	
	mosRedirect( "index2.php?option=com_surveyforce&task=sample" );	
}

function insertSurvey(){
	global $database;
	
	$eName	= JRequest::getVar('e_name');
	$eName	= preg_replace( '#[^A-Z0-9\-\_\[\]]#i', '', $eName );
	
	$query = "SELECT * "
		. "\n FROM #__survey_force_survs"
		. "\n ORDER BY sf_name"
	;
	$database->setQuery( $query );
	$surveys = $database->loadObjectList();
	
	?>
	<script type="text/javascript">
		var survey_id = 0;

		function insert() {			
			if (!survey_id ) {
				alert("<?php echo JText::_('COM_SF_PLEASE_SELECT_SURVEY_TO_INSERT'); ?>");
				return;
			}
			
			var tag = 'id='+survey_id;
			
			tag = '{surveyforce '+tag+' }';

			window.parent.jInsertEditorText(tag, '<?php echo $eName; ?>');
            timeoutId = setTimeout(parent.SqueezeBox.close(), 2000);
			return false;
		}
		
		function getObj(name) {
		  if (document.getElementById)  {  return document.getElementById(name);  }
		  else if (document.all)  {  return document.all[name];  }
		  else if (document.layers)  {  return document.layers[name];  }
		}
		
		function refresh_info(){
			return;		
		}
	</script>
	<link type="text/css" rel="stylesheet" href="templates/khepri/css/general.css"></link>
	<link type="text/css" rel="stylesheet" href="templates/khepri/css/component.css"></link>
	<form name="adminform"> 
	<h2>Select survey to insert:</h2>
	<table width="100%" align="left" class="adminlist" height="100%">
	<?php
	foreach ($surveys as $survey) {			
			?>
			<tr> 
				<td width="20" valign="top"><input type="radio" name="survey" id="survey<?php echo $survey->id;?>" onchange="if(this.checked){survey_id = <?php echo $survey->id;?>; refresh_info();}else{refresh_info();}" /></td>
				<td><label for="survey<?php echo $survey->id;?>" style="cursor:pointer;"><?php echo $survey->sf_name;	?></label></td>
			</tr><?php
	}
	?>
	</table>
	</form>
	<br />
	<br />
	<div style="float:right; clear:both;">
		<br />
		<button onclick="insert();">Insert Tag</button>
	</div>
	<?php
} 


function SF_new_question_type() {
	global $mosConfig_live_site, $mainframe, $option, $front_end;
	$new_qtype_id = intval( $mainframe->getUserStateFromRequest( "new_qtype_id{$option}", 'new_qtype_id', 0 ) );
	$lang = JFactory::getLanguage();
	$lang->load('com_surveyforce', JPATH_BASE.DS.'administrator');
	if (class_exists('JToolBar')) {
		$bar = & JToolBar::getInstance('toolbar');
		// Add a cancel button
		//$bar->appendButton( 'Standard', 'forward', 'Next', 'add_new', false, true );
		$bar->appendButton( 'Custom', '<td id="toolbar-next" class="button"><a onclick="javascript:try{hideMainMenu();}catch(e){} submitbutton(\'add_new\');" href="javascript:void(0);" class="toolbar"><span title="'.JText::_('COM_SF_NEXT').'" class="icon-32-forward"></span>'.JText::_('COM_SF_NEXT').'</a></td>' ); 
		if ($front_end) {
			$bar->appendButton( 'Custom', '<td id="toolbar-cancel" class="button"><a onclick="javascript: window.SqueezeBox.close();" href="javascript:void(0);" class="toolbar"><span title="'.JText::_('COM_SF_CANCEL').'" class="icon-32-cancel"></span>'.JText::_('COM_SF_CANCEL').'</a></td>' ); 
		} else {
			$bar->appendButton( 'Custom', '<td id="toolbar-cancel" class="button"><a onclick="javascript: window.parent.SqueezeBox.close();" href="javascript:void(0);" class="toolbar"><span title="'.JText::_('COM_SF_CANCEL').'" class="icon-32-cancel"></span>'.JText::_('COM_SF_CANCEL').'</a></td>' ); 
		}
	}
	
	?>
	
	<style type="text/css" >
		label { cursor:pointer;}
	</style>
	<script>
		function submitbutton(pressbutton) { 
			var form = document.adminForm;
			if (pressbutton == 'cancel') {				
				return;
			}			
			if (pressbutton == 'add_new') {
				switch (form.new_qtype_id.value) {
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
	</script>
	<?php if ($front_end) { ?>
	<form action="index.php" method="post" name="adminForm2">
	<?php } else { ?>
	<form action="index2.php" method="post" name="adminForm" target="_parent">
	<?php }?>
	<fieldset class="adminform">
	<legend><?php echo JText::_('COM_SF_SELECT_NEW_QUESTION_TYPE'); ?></legend>
	
	<?php if (class_exists('JToolBar')) { echo $bar->render(); } else {
	
		mosMenuBar::startTable();
		mosMenuBar::customX( 'add_quest', 'next.png', 'next_f2.png', 'Next', false ); 
	
		$image2 = mosAdminMenus::ImageCheckAdmin( 'cancel_f2.png', '/administrator/images/', NULL, NULL, $alt, $task, 1, 'middle', $alt ); 
		?>
		<td>
			<a class="toolbar" href="javascript: parent.tb_remove();">
				<?php echo $image2; ?>
				<br />Cancel</a>
		</td>
		<?php 
		mosMenuBar::endTable();
	}?>
	<table width="100%" cellpadding="2" cellspacing="2" class="admintable">
		<tr>
			<td width="50%"><label for="new_qtype_id_1"><input type="radio" onclick="isChecked(this.checked);" name="new_qtype_id" id="new_qtype_id_1" value="1" <?php echo ($new_qtype_id == 1? ' checked="checked" ': '')?> /><?php echo JText::_('COM_SF_LIKERTSCALE'); ?></label></td>
			<td><label for="new_qtype_id_2"><input onclick="isChecked(this.checked);" type="radio" name="new_qtype_id" id="new_qtype_id_2" value="2" <?php echo ($new_qtype_id == 2? ' checked="checked" ': '')?> /><?php echo JText::_('COM_SF_PICKONE'); ?></label></td>
		</tr>
		<tr>
			<td width="50%"><label for="new_qtype_id_3"><input onclick="isChecked(this.checked);" type="radio" name="new_qtype_id" id="new_qtype_id_3" value="3" <?php echo ($new_qtype_id == 3? ' checked="checked" ': '')?> /><?php echo JText::_('COM_SF_PICKMANY'); ?></label></td>
			<td><label for="new_qtype_id_4"><input onclick="isChecked(this.checked);" type="radio" name="new_qtype_id" id="new_qtype_id_4" value="4" <?php echo ($new_qtype_id == 4? ' checked="checked" ': '')?> /><?php echo JText::_('COM_SF_SHORTANSWER'); ?></label></td>
		</tr>
		<tr>
			<td width="50%"><label for="new_qtype_id_5"><input onclick="isChecked(this.checked);" type="radio" name="new_qtype_id" id="new_qtype_id_5" value="5" <?php echo ($new_qtype_id == 5? ' checked="checked" ': '')?> /><?php echo JText::_('COM_SF_RANKING_DROPDOWN'); ?></label></td>
			<td><label for="new_qtype_id_6"><input onclick="isChecked(this.checked);" type="radio" name="new_qtype_id" id="new_qtype_id_6" value="6" <?php echo ($new_qtype_id == 6? ' checked="checked" ': '')?> /><?php echo JText::_('COM_SF_RANKING_DRAGNDROP'); ?></label></td>
		</tr>
		<tr>
			<td width="50%"><label for="new_qtype_id_7"><input onclick="isChecked(this.checked);" type="radio" name="new_qtype_id" id="new_qtype_id_7" value="7" <?php echo ($new_qtype_id == 7? ' checked="checked" ': '')?> /><?php echo JText::_('COM_SF_BOILERPLATE'); ?></label></td>
			<td><label for="new_qtype_id_8"><input onclick="isChecked(this.checked);" type="radio" name="new_qtype_id" id="new_qtype_id_8" value="8" <?php echo ($new_qtype_id == 8? ' checked="checked" ': '')?> /><?php echo JText::_('COM_SF_PAGE_BREAK'); ?></label></td>
		</tr>
		<tr>
			<td width="50%"><label for="new_qtype_id_9"><input onclick="isChecked(this.checked);" type="radio" name="new_qtype_id" id="new_qtype_id_9" value="9" <?php echo ($new_qtype_id == 9? ' checked="checked" ': '')?> /><?php echo JText::_('COM_SF_S_RANKING'); ?></label></td>
			<td>&nbsp;</td>
		</tr>
		
	</table>
	</fieldset>
	
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="option" value="com_surveyforce" />
	<input type="hidden" name="c_id" value="0" />
	<input type="hidden" name="task" value="" />
	</form>
	<?php
}

function SF_draw_grid($options = array()) {
	// Add values to the graph
	$graphValues=explode(',',$options['grids']);
	$kol_items = count($graphValues);
	
	$imgWidth=250;
	$imgHeight=25*$kol_items;
	$imgLineWdth = 25;
	$imgLineWdth_Offset = 3;
	$max_value = intval($options['total']);

	for ($i=0;$i<$kol_items;$i++) {
		if ($max_value != 0) { $graphValues[$i] = intval($graphValues[$i]*$imgWidth/$max_value); }
		if ($graphValues[$i] >= $imgWidth) $graphValues[$i] = $imgWidth - 1;
	}
	// Create image and define colors
	$image=imagecreate($imgWidth, $imgHeight);
	$colorWhite=imagecolorallocate($image, 255, 255, 255);
	$colorGrey=imagecolorallocate($image, 192, 192, 192);
	$colorDarkBlue=imagecolorallocate($image, 104, 157, 228);
	$colorLightBlue=imagecolorallocate($image, 184, 212, 250);
	// Create border around image
	imageline($image, 0, 0, 0, $imgHeight, $colorGrey);
	imageline($image, 0, 0, $imgWidth, 0, $colorGrey);
	imageline($image, $imgWidth - 1, 0, $imgWidth - 1, $imgHeight - 1, $colorGrey);
	imageline($image, 0, $imgHeight - 1, $imgWidth - 1, $imgHeight - 1, $colorGrey);
	// Create grid
	for ($i=1; $i<11; $i++){
		$stw = ($i*25 > $imgWidth)?$imgWidth:$i*25;
		$sth = ($i*25 > $imgHeight)?$imgHeight:$i*25;
		imageline($image, $stw, 0, $stw, $imgHeight, $colorGrey);
		imageline($image, 0, $sth, $imgWidth, $sth, $colorGrey);
	}
	// Create bar charts
	for ($i=0; $i<$kol_items; $i++){
		if ($graphValues[$i] > 0) {
			imagefilledrectangle($image, 0, (($i)*$imgLineWdth) + $imgLineWdth_Offset, $graphValues[$i], (($i+1)*$imgLineWdth) - $imgLineWdth_Offset, $colorDarkBlue);
			imagefilledrectangle($image, 1, (($i)*$imgLineWdth) + $imgLineWdth_Offset + 1, $graphValues[$i] - 1, (($i+1)*$imgLineWdth) - $imgLineWdth_Offset - 1, $colorLightBlue);
		}
	}
	// Output graph and clear image from memory
	
	imagepng($image, $options['fileName']);
	imagedestroy($image);
}

function clearOldImages($day = null){
	global $mosConfig_absolute_path;
	$image_path = $mosConfig_absolute_path."/images/surveyforce/gen_images/";

	if ($day == null)
		$day = (strlen(date('d')) < 2? '0'.date('d'): date('d'));
	elseif (strlen($day) < 2 )
		$day = '0'.$day;
		
	$current_dir = opendir( $image_path );
	$old_umask = umask(0);
	while (false !== ($entryname = readdir( $current_dir ))) {
		if ($entryname != '.' and $entryname != '..') {
			if (!is_dir( $image_path . $entryname ) && substr($entryname, 0, 2) != $day) {
				@chmod( $image_path . $entryname, 0757);
				unlink( $image_path . $entryname );
			}
		}
	}
	umask($old_umask);
	closedir( $current_dir );
}


function SF_genInvitations( $option ) {
	global $mosConfig_absolute_path, $database;
	
	$query = "SELECT id AS value, sf_name AS text"
	. "\n FROM #__survey_force_survs"
	. "\n ORDER BY sf_name"
	;
	$database->setQuery( $query );

	$surveys = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
	$survey = mosHTML::selectList( $surveys,'surv_id', 'class="text_area" size="1" ', 'value', 'text', 0 ); 
	
	survey_force_adm_html::SF_genInvitations( $option, $survey );
}

function SF_makeInvList() {
	global $mosConfig_absolute_path, $mosConfig_live_site, $database;
	$number = intval( mosGetParam( $_REQUEST, 'number', 0 ) );
	$surv_id = intval( mosGetParam( $_REQUEST, 'surv_id', 0 ) );
	if ($number > 0 && $surv_id  > 0) {
		$query = "SELECT id "
				."\n FROM #__survey_force_listusers"
				."\n WHERE  listname = '_generated_users_' "
				;
		$database->setQuery( $query );
		$list_id = (int)$database->loadResult();
		if ( $list_id < 1) {
			$row = new mos_Survey_Force_ListUsers( $database ); 
			$row->listname = '_generated_users_';
			$row->date_created = date( 'Y-m-d H:i:s' );
			$row->date_invited = date( 'Y-m-d H:i:s' );
			$row->survey_id = 0;
			$row->store();
			$list_id = $row->id;
		}
		
		$query = "SELECT MAX(id) "
				."\n FROM #__survey_force_users"
				."\n WHERE  list_id = '{$list_id}' "
				;
		$database->setQuery( $query );
		$max_id = (int)$database->loadResult();
		$max_id++;
		$csvdata = '';
		$dlm = ',';
		for ($i = 0; $i < $number; $i++) {
			$row_user = new mos_Survey_Force_UserInfo( $database );
			$row_user->name = 'Name '.$max_id;
			$row_user->lastname = 'Lastname '.$max_id;
			$row_user->email = 'email@email.email';
			$row_user->list_id = $list_id;
			$row_user->store();
			
			$user_invite_num = md5(uniqid(rand().mktime(), true));
			
			$link = $mosConfig_live_site . "/index.php?option=com_surveyforce&task=start_invited&survey=".$surv_id."&invite=".$user_invite_num;
			
			$query = "INSERT INTO `#__survey_force_invitations` (invite_num, user_id, inv_status) VALUES ('". $user_invite_num ."', '".$row_user->id."', 0)";
			$database->SetQuery($query);
			$database->query();
			$user_invite_id = $database->insertid();
				
			$query = "UPDATE `#__survey_force_users` SET is_invited = '1', invite_id = '". $user_invite_id ."' WHERE id ='".$row_user->id."'";
			$database->SetQuery($query);
			$database->query();
			$csvdata .= $row_user->name.$dlm.$row_user->lastname.$dlm.$row_user->email.$dlm.$link."\n";
			$max_id++;
		}
		@ob_end_clean();
		header("Content-type: application/csv");
		header("Content-Length: ".strlen(ltrim($csvdata)));
		header("Content-Disposition: inline; filename=invitations.csv");
		echo $csvdata; 
		die;
	}
	mosRedirect( "index2.php?option=com_surveyforce&task=generate_invitations" );
}


function SF_uploadImage($option) {
	global $mosConfig_absolute_path, $front_end;
	
	$userfile2=(isset($_FILES['userfile']['tmp_name']) ? $_FILES['userfile']['tmp_name'] : "");
	$userfile_name=(isset($_FILES['userfile']['name']) ? $_FILES['userfile']['name'] : "");
	$directory = 'surveyforce';
	if (isset($_FILES['userfile'])) {
		$base_Dir = $mosConfig_absolute_path."/images/surveyforce/";
		
		if (empty($userfile_name)) {
			echo "<script>alert('".JText::_('COM_SF_PLEASE_SELECT_AN_IMAGE')."'); document.location.href='index3.php?option=com_surveyforce&amp;task=uploadimage&amp;directory=".$directory."&t=".$css."';</script>";
		}
	
		$filename = explode(".", $userfile_name);
	
		if(preg_match('/[^0-9a-zA-Z_]/', $filename[0])) {
			mosErrorAlert(JText::_('COM_SF_FILE_MUST_CONTAIN_ONLY_ALPHANUMERIC'));
		}
	
		if (file_exists($base_Dir.$userfile_name)) {
			mosErrorAlert("Image ".$userfile_name.JText::_('COM_SF_ALREADY_EXISTS'));
		}
	
		if ((strcasecmp(substr($userfile_name,-4),".gif")) && (strcasecmp(substr($userfile_name,-4),".jpg")) && (strcasecmp(substr($userfile_name,-4),".png")) && (strcasecmp(substr($userfile_name,-4),".bmp")) ) {
			mosErrorAlert(JText::_('COM_SF_THE_FILE_MUST_BE_GIF'));
		}	
	
		if (!move_uploaded_file ($_FILES['userfile']['tmp_name'],$base_Dir.$_FILES['userfile']['name']) || !mosChmod($base_Dir.$_FILES['userfile']['name'])) {
			mosErrorAlert(JText::_('COM_SF_UPLOAD_OF').$userfile_name.JText::_('COM_SF_FAILED'));
		} else {
			mosErrorAlert(JText::_('COM_SF_UPLOAD_OF').$userfile_name." to ".$base_Dir.JText::_('COM_SF_SUCCESSFULL'));
		}
	}
	if (!$front_end) {
		survey_force_adm_html::SF_uploadImage( $option );
	}
	else {
		survey_force_front_html::SF_uploadImage( $option );
	}
}


			#######################################
			###	--- --- 	BUG FIXES	--- --- ###

#######################################
###	Function added 02.02.2007 (FIX bug then slashes added to each quote)

function SF_processGetField($field_text) {
	$field_text = (get_magic_quotes_gpc()) ? mosStripslashes( $field_text ) : $field_text; 
	$field_text = ampReplace($field_text);
	$field_text = str_replace( '"', '&quot;', $field_text );
	$field_text = str_replace( "'", '&#039;', $field_text );
	return $field_text;
}

#######################################
###	Function added 02.02.2007 (FIX bug with embedded quotas and commas in CSV report fields)

function SF_processCSVField($field_text) {
	$field_text = strip_tags($field_text);
	$field_text = str_replace( '&#039;', "'", $field_text );
	$field_text = str_replace( '&#39;', "'", $field_text );
	$field_text = str_replace('&quot;',  '"', $field_text );
	$field_text = str_replace( '"', '""', $field_text );
	$field_text = str_replace( "\n", ' ', $field_text );
	$field_text = str_replace( "\r", ' ', $field_text );
	$field_text = rel_decodeHTML( $field_text );
	$field_text = '"'.$field_text.'"';
	return $field_text;
}

function SF_processCSVField_noquot($field_text) {
	$field_text = strip_tags($field_text);
	$field_text = str_replace( '&#039;', "'", $field_text );
	$field_text = str_replace( '&#39;', "'", $field_text );
	$field_text = str_replace('&quot;',  '"', $field_text );
	$field_text = str_replace( '"', '""', $field_text );
	$field_text = str_replace( "\n", ' ', $field_text );
	$field_text = str_replace( "\r", ' ', $field_text );
	$field_text = rel_decodeHTML( $field_text );
	return $field_text;
}

			#######################################
			###	--- --- 	CATEGORIES	--- --- ###

function SF_ListCategories( $option )
{
	global $database, $mainframe, $mosConfig_list_limit, $front_end;

	$limit 		= intval( $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', $mosConfig_list_limit ) );
	$limitstart = intval( $mainframe->getUserStateFromRequest( "view{$option}limitstart", 'limitstart', 0 ) );
	if ($limit == 0) $limit = 999999;
	if ($front_end) {
		global $SF_SESSION;
		$limit		= intval( mosGetParam( $_REQUEST, 'limit', $SF_SESSION->get('list_limit',$mainframe->getCfg('list_limit')) ) );
		if ($limit == 0) $limit = 999999;
		$SF_SESSION->set('list_limit', $limit);
		$limitstart = intval( mosGetParam( $_REQUEST, 'limitstart', 0 ) );
	}
	
	// get the total number of records
	$query = "SELECT COUNT(*)"
	. "\n FROM #__survey_force_cats";
	$database->setQuery( $query );
	$total = $database->loadResult();

	require_once( $GLOBALS['mosConfig_absolute_path'] . '/administrator/includes/pageNavigation.php' );
	if ($front_end)
		$pageNav = new SFPageNav( $total, $limitstart, $limit  );
	else
		$pageNav = new mosPageNav( $total, $limitstart, ($limit==999999?0:$limit) );

	// get the subset (based on limits) of required records
	$query = "SELECT a.*, b.name "
	. "\n FROM #__survey_force_cats AS a"
	. "\n LEFT JOIN #__users AS b ON a.user_id = b.id"
	. "\n ORDER BY a.user_id, a.sf_catname"
	. "\n LIMIT $pageNav->limitstart, $pageNav->limit"
	;
	$database->setQuery( $query );
	$rows = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
	if (!$front_end)
		survey_force_adm_html::SF_showCatsList( $rows, $pageNav, $option);
	else
		survey_force_front_html::SF_showCatsList( $rows, $pageNav, $option);
}

function SF_editCategory( $id, $option ) {
	global $database, $my, $front_end;

	$row = new mos_Survey_Force_Cat( $database );
	// load the row from the db table
	$row->load( $id );
	
	if ($id > 0 && $row->user_id != $my->id && ($front_end && $my->usertype != 'Super Administrator')) {
		echo "<script> alert('".JText::_('COM_SF_YOU_CANNOT_EDIT_CATEGORY')."'); window.history.go(-1); </script>\n";
		exit();
	}
	
	$row->user_id = $my->id;
	
	if ($id) {
		// do stuff for existing records
		$row->checkout($my->id);
	} else {
		// do stuff for new records
		$row->published = 1;
	}
	$lists = array();

	
	if (!$front_end)
		survey_force_adm_html::SF_editCategory( $row, $lists, $option );
	else
		survey_force_front_html::SF_editCategory( $row, $lists, $option );
}

function SF_saveCategory( $option ) {
	global $database, $front_end, $my;

	$row = new mos_Survey_Force_Cat( $database );
	@$_POST['sf_catname'] = SF_processGetField(@$_POST['sf_catname']);
	@$_POST['sf_catdescr'] = nl2br(trim(SF_processGetField(@$_POST['sf_catdescr'])));
	if (!$row->bind( $_POST )) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	// pre-save checks
	if (!$row->check()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}

	// save the changes
	if (!$row->store()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	$row->checkin();
	#$row->updateOrder();
	global $task;
	if (!$front_end){
		if ($task == 'apply_cat') {
		mosRedirect( "index2.php?option=$option&task=editA_cat&id=". $row->id, $msg );
		} else {
			mosRedirect( "index2.php?option=$option&task=categories" );
		}
	}
	else {
		global $Itemid,$Itemid_s;
		if ($task == 'apply_cat') {
			mosRedirect( SFRoute("index.php?option=$option{$Itemid_s}&task=editA_cat&id=".$row->id) );
		} else {
			mosRedirect( SFRoute("index.php?option=$option{$Itemid_s}&task=categories") );
		}
	}

}

function SF_removeCategory( &$cid, $option ) {
	global $database, $front_end;
	if (count( $cid )) {
		$cids = implode( ',', $cid );
		$query = "DELETE FROM #__survey_force_cats"
		. "\n WHERE id IN ( $cids )"
		;
		$database->setQuery( $query );
		if (!$database->query()) {
			echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		}
	}
	if (!$front_end)
		mosRedirect( "index2.php?option=$option&task=categories" );
	else {
		global $Itemid,$Itemid_s;
		mosRedirect( SFRoute("index.php?option=$option{$Itemid_s}&task=categories") );	
	}
}

function SF_cancelCategory($option) {
	global $database, $front_end;

	$row = new mos_Survey_Force_Cat( $database );
	$row->bind( $_POST );
	$row->checkin();
	if (!$front_end)
		mosRedirect("index2.php?option=$option&task=categories");
	else {
		global $Itemid,$Itemid_s;
		mosRedirect( SFRoute("index.php?option=$option{$Itemid_s}&task=categories") );	
	}
}

			#######################################
			###	--- --- 	SURVEYS 	--- --- ###

function SF_ListSurveys( $option, $is_i = false )
{
	global $database, $mainframe, $mosConfig_list_limit, $front_end, $task, $my;
	
	$catid 		= intval( $mainframe->getUserStateFromRequest( "catid{$option}", 'catid', 0 ) ); 
	$limit 		= intval( $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', $mosConfig_list_limit ) );
	$limitstart = intval( $mainframe->getUserStateFromRequest( "view{$option}limitstart", 'limitstart', 0 ) );
	if ($limit == 0) $limit = 999999;
	if ($front_end) {
		global $SF_SESSION;
		$limit		= intval( mosGetParam( $_REQUEST, 'limit', $SF_SESSION->get('list_limit',$mainframe->getCfg('list_limit')) ) );
		if ($limit == 0) $limit = 999999;
		$SF_SESSION->set('list_limit', $limit);
		$limitstart = intval( mosGetParam( $_REQUEST, 'limitstart', 0 ) );
		$catid		= intval( mosGetParam( $_REQUEST, 'catid', $SF_SESSION->get('list_catid', 0) ) );
		$SF_SESSION->set('list_catid', $catid);
	}
	// get the total number of records
	$query = "SELECT COUNT(*)"
	. "\n FROM #__survey_force_survs WHERE 1=1 "
	. ( $catid ? "\n AND sf_cat = $catid" : '' )
	. ( $front_end && $my->usertype != 'Super Administrator'? " AND sf_author = '{$my->id}' ": " ")
	;
	$database->setQuery( $query );
	$total = $database->loadResult();

	require_once( $GLOBALS['mosConfig_absolute_path'] . '/administrator/includes/pageNavigation.php' );
	if ($front_end)
		$pageNav = new SFPageNav( $total, $limitstart, $limit  );
	else
		$pageNav = new mosPageNav( $total, $limitstart, ($limit==999999?0:$limit) );

	// get the subset (based on limits) of required records
	$query = "SELECT a.*, b.sf_catname, us.username "
	. "\n FROM #__survey_force_survs a LEFT JOIN #__survey_force_cats b ON a.sf_cat = b.id LEFT JOIN #__users as us ON a.sf_author = us.id WHERE 1=1 "
	. ( $catid ? "\n AND a.sf_cat = $catid " : '' )
	. ( $front_end && $my->usertype != 'Super Administrator'? " AND sf_author = '{$my->id}' ": " ")
	. "\n ORDER BY a.sf_name, b.sf_catname "
	. "\n LIMIT $pageNav->limitstart, $pageNav->limit"
	;
	$database->setQuery( $query );
	$rows = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
	
	$query = " SELECT COUNT(*) FROM #__survey_force_listusers "
			. ($front_end && $my->usertype != 'Super Administrator'? "WHERE sf_author_id = '{$my->id}' ": '')
			." ORDER BY listname ";
	$database->setQuery( $query );
	$lists['userlists'] = $database->LoadResult();
	
	$javascript = 'onchange="document.adminForm.submit();"';
	$query = "SELECT id AS value, sf_catname AS text"
	. "\n FROM #__survey_force_cats"
	. "\n ORDER BY sf_catname"
	;
	$database->setQuery( $query );
	if (!$front_end)
		$categories[] = mosHTML::makeOption( '0', _SEL_CATEGORY );
	else {
		global $sf_lang;
		$categories[] = mosHTML::makeOption( '0', $sf_lang["SF_SELECT_CATEGORY"] );
	}
	$categories = @array_merge( $categories, ($database->LoadObjectList() == null? array(): $database->LoadObjectList()) );
	$category = mosHTML::selectList( $categories,'catid', 'class="text_area" size="1" '. $javascript, 'value', 'text', $catid ); 
	$lists['category'] = $category; 
	if (!$front_end)
		survey_force_adm_html::SF_showSurvsList( $rows, $lists, $pageNav, $option, $is_i);
	else {
		if ($task == 'i_report')
			survey_force_front_html::SF_showIReport( $rows, $lists, $pageNav, $option, $is_i);
		else
			survey_force_front_html::SF_showSurvsList( $rows, $lists, $pageNav, $option, $is_i);
	}
}

function SF_editSurvey( $id, $option ) {
	global $database, $my, $front_end;;
	
	$lang = JFactory::getLanguage();
	$lang->load('com_surveyforce', JPATH_BASE.DS.'administrator');
	
	$row = new mos_Survey_Force_Survey( $database );
	// load the row from the db table
	$row->load( $id );

	if ($id) {
		// do stuff for existing records
		$row->checkout($my->id);
	} else {
		// do stuff for new records
		#$row->published = 1;
		$row->sf_author = $my->id;
		$row->sf_special = 0;
		$row->sf_auto_pb = 1;
	}
	if (!$row->sf_author) 
		$row->sf_author = $my->id;
	$lists = array();
	$query2 = "SELECT * FROM #__survey_force_cats order by sf_catname";
	$database->setQuery( $query2 );
	$sf_cats = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
	$lists['sf_categories']	= mosHTML::selectList( $sf_cats, 'sf_cat', 'class="text_area" size="1"', 'id', 'sf_catname', $row->sf_cat );
		
	$row->sf_template = ($row->sf_template? $row->sf_template: 1);
	$query2 = "SELECT `id` AS `value`, `sf_name` AS `text` FROM `#__survey_force_templates` ORDER BY `sf_name`";
	$database->setQuery( $query2 );
	$templates = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
	$lists['sf_templates']	= mosHTML::selectList( $templates, 'sf_template', 'class="text_area" size="1"', 'value', 'text', $row->sf_template );
	
	// build the html radio buttons for published
	$lists['published'] 		= mosHTML::yesnoradioList( 'published', '', $row->published );
	$directory = '/images/surveyforce/';
	global $mosConfig_absolute_path;
	$javascript = "onchange=\"javascript:if (document.adminForm.sf_image.options[selectedIndex].value!='') {"
	. " document.imagelib.src='..$directory/' + document.adminForm.sf_image.options[selectedIndex].value; } else {"
	. " document.imagelib.src='../images/blank.png'}\""; 
	$lists['images'] 		= sfm_Images('sf_image', $row->sf_image, $javascript, $directory); 
	
	$query = " SELECT id AS value, listname AS text FROM #__survey_force_listusers "
			. ($front_end && $my->usertype != 'Super Administrator'? "WHERE sf_author_id = '{$my->id}' ": '')
			." ORDER BY listname ";
	$database->setQuery( $query );
	$userlists =  ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
	$selected = null;
	if ($row->sf_special) {
		$tmp = explode(',', $row->sf_special);
		$selected = array();
		foreach($tmp as $list_id){
			$selected[]->value = $list_id;
		}
	}
	
	if (!$front_end){
		$yes_no[] = mosHTML::makeOption( '1', JText::_('COM_SF_YES') );
		$yes_no[] = mosHTML::makeOption( '0', JText::_('COM_SF_NO') );
	}
	else {
		$yes_no[] = mosHTML::makeOption( '1', JText::_('COM_SF_YES') );
		$yes_no[] = mosHTML::makeOption( '0', JText::_('COM_SF_NO') );
	}
	$lists['sf_auto_pb'] = mosHTML::selectList( $yes_no, 'sf_auto_pb', 'class="text_area" size="1" ', 'value', 'text', intval($row->sf_auto_pb) ); 

	$lists['published'] = mosHTML::selectList( $yes_no, 'published', 'class="text_area" size="1" ', 'value', 'text', intval($row->published) ); 
	
	$lists['sf_enable_descr'] = mosHTML::selectList( $yes_no, 'sf_enable_descr', 'class="text_area" size="1" ', 'value', 'text', intval($row->sf_enable_descr) );
	
	$lists['sf_progressbar'] = mosHTML::selectList( $yes_no, 'sf_progressbar', 'class="text_area" size="1" ', 'value', 'text', intval($row->sf_progressbar) );
	
	$bartype[] = mosHTML::makeOption( '0', JText::_('COM_SF_COUNT_BY_QUESTIONS') );
	$bartype[] = mosHTML::makeOption( '1', JText::_('COM_SF_COUNT_BY_PAGES') );	
	
	$lists['sf_progressbar_type'] = mosHTML::selectList( $bartype, 'sf_progressbar_type', 'class="text_area" size="1" ', 'value', 'text', intval($row->sf_progressbar_type) );
	
	$yes_no_anonimous = array();
    $yes_no_anonimous[] = mosHTML::makeOption( '1', JText::_('COM_SF_NO') );
    $yes_no_anonimous[] = mosHTML::makeOption( '0', JText::_('COM_SF_YES') );

	
	$lists['sf_anonymous'] = mosHTML::selectList( $yes_no_anonimous, 'sf_anonymous', 'class="text_area" size="1" ', 'value', 'text', intval($row->sf_anonymous) ); 
	
	$random = array();
	if (!$front_end){
		$random[] = mosHTML::makeOption( '0', JText::_('COM_SF_NO'));
		$random[] = mosHTML::makeOption( '1', JText::_('COM_SF_RANDOMIZE_PAGES'));
		$random[] = mosHTML::makeOption( '2', JText::_('COM_SF_RANDOMIZE_QUESTIONS'));
		$random[] = mosHTML::makeOption( '3', JText::_('COM_SF_RANDOMIZE_PAGES_AND_QUESTIONS'));
	}
	else {	
		$random[] = mosHTML::makeOption( '0', JText::_('COM_SF_NO') );
		$random[] = mosHTML::makeOption( '1', JText::_('COM_SF_RANDOMIZE_PAGES') );
		$random[] = mosHTML::makeOption( '2', JText::_('COM_SF_RANDOMIZE_QUESTIONS') );
		$random[] = mosHTML::makeOption( '3', JText::_('COM_SF_RANDOMIZE_PAGES_AND_QUESTIONS') );
	}
	$lists['sf_random'] = mosHTML::selectList( $random, 'sf_random', 'class="text_area" size="1" ', 'value', 'text', intval($row->sf_random) );
	
	$voting = array();
	if (!$front_end){
		$voting[] = mosHTML::makeOption( '0', JText::_('COM_SF_MULTIPLE_VOTING'));
		$voting[] = mosHTML::makeOption( '1', JText::_('COM_SF_ONCE_VOTING'));
		$voting[] = mosHTML::makeOption( '2', JText::_('COM_SF_ONCE_VOTING_REPLACE'));
		$voting[] = mosHTML::makeOption( '3', JText::_('COM_SF_ALLOW_EDIT_ANSWERS'));
	}
	else {
		global $sf_lang;
		$voting[] = mosHTML::makeOption( '0', $sf_lang['SF_MULTIPLE_VOTING']);
		$voting[] = mosHTML::makeOption( '1', $sf_lang['SF_ONCE_VOTING']);
		$voting[] = mosHTML::makeOption( '2', $sf_lang['SF_ONCE_VOTING_REPLACE']);
		$voting[] = mosHTML::makeOption( '3', $sf_lang['SF_ALLOW_EDIT_ANSWERS']);
	}
	
	$lists['sf_reg_voting'] = mosHTML::selectList( $voting, 'sf_reg_voting', 'class="text_area" size="1" ', 'value', 'text', intval($row->sf_reg_voting) );
	$lists['sf_friend_voting'] = mosHTML::selectList( $voting, 'sf_friend_voting', 'class="text_area" size="1" ', 'value', 'text', intval($row->sf_friend_voting) );
	$lists['sf_inv_voting'] = mosHTML::selectList( $voting, 'sf_inv_voting', 'class="text_area" size="1" ', 'value', 'text', intval($row->sf_inv_voting) );
	
	$voting = array();
	if (!$front_end){
		$voting[] = mosHTML::makeOption( '0', JText::_('COM_SF_MULTIPLE_VOTING'));
		$voting[] = mosHTML::makeOption( '1', JText::_('COM_SF_SINGLE_VOTING'));
		$voting[] = mosHTML::makeOption( '2', JText::_('COM_SF_SINGLE_VOTING_REPLACE_ANSWERS'));
	}
	else {
		global $sf_lang;
		$voting[] = mosHTML::makeOption( '0', $sf_lang['SF_MULTIPLE_VOTING']);
		$voting[] = mosHTML::makeOption( '1', $sf_lang['SF_ONCE_VOTING']);
		$voting[] = mosHTML::makeOption( '2', $sf_lang['SF_ONCE_VOTING_REPLACE']);
	}
	$disabled = (intval($row->sf_pub_control)? '': ' disabled="disabled" ');
	$lists['sf_pub_voting'] = mosHTML::selectList( $voting, 'sf_pub_voting', 'class="text_area" size="1" id="sf_pub_voting" '.$disabled, 'value', 'text', intval($row->sf_pub_voting) );
	
	
	$control = array();
	if (!$front_end){
		$control[] = mosHTML::makeOption( '0', JText::_('COM_SF_NONE'));
		$control[] = mosHTML::makeOption( '1', JText::_('COM_SF_BY_IP_ADDRESS'));
		$control[] = mosHTML::makeOption( '2', JText::_('COM_SF_BY_COOKIE'));
		$control[] = mosHTML::makeOption( '3', JText::_('COM_SF_BOTH_COOKIE_AND_IP'));
	}
	else {
		global $sf_lang;
		$control[] = mosHTML::makeOption( '0', $sf_lang['SF_NONE']);
		$control[] = mosHTML::makeOption( '1', $sf_lang['SF_IP_ADDR']);
		$control[] = mosHTML::makeOption( '2', $sf_lang['SF_COOKIE']);
		$control[] = mosHTML::makeOption( '3', $sf_lang['SF_BOTH']);
	}
	$jscript = ' onchange="javascript: if (this.selectedIndex == 0) {getObj(\'sf_pub_voting\').disabled=\'disabled\';}else{getObj(\'sf_pub_voting\').disabled=\'\';}" ';
	$lists['sf_pub_control'] = mosHTML::selectList( $control, 'sf_pub_control', 'class="text_area" size="1" '.$jscript, 'value', 'text', intval($row->sf_pub_control) );
	
	
	$lists['sf_use_css'] = mosHTML::selectList( $yes_no, 'sf_use_css', 'class="text_area" size="1" ', 'value', 'text', intval($row->sf_use_css) );
	
	if (count($userlists) > 0){
		$lists['userlists']	= mosHTML::selectList( $userlists, 'userlists[]', 'class="text_area" size="4"  multiple="multiple" ', 'value', 'text', $selected );
	}
	else {
		$lists['userlists']	= null;
	}
	if (!$front_end)
		survey_force_adm_html::SF_editSurvey( $row, $lists, $option );
	else
		survey_force_front_html::SF_editSurvey( $row, $lists, $option );
}

function sfm_Images( $name, &$active, $javascript=NULL, $directory=NULL ) {
		global $mosConfig_absolute_path;

		if ( !$directory ) {
			$directory = '/images/stories';
		}

		if ( !$javascript ) {
			$javascript = "onchange=\"javascript:if (document.forms[0].image.options[selectedIndex].value!='') {document.imagelib.src='..$directory/' + document.forms[0].image.options[selectedIndex].value} else {document.imagelib.src='../images/blank.png'}\"";
		}

		$imageFiles = mosReadDirectory( $mosConfig_absolute_path . $directory );
		$images 	= array(  mosHTML::makeOption( '', '- Select Image -' ) );
		foreach ( $imageFiles as $file ) {
			if(preg_match('/bmp|gif|jpg|png/', $file)) {
				$images[] = mosHTML::makeOption( $file );
			}
		}
		$images = mosHTML::selectList( $images, $name, 'class="inputbox" size="1" '. $javascript, 'value', 'text', $active );

		return $images;
	} 

function SF_saveSurvey( $option ) {
	global $database, $mosConfig_offset, $front_end;;

	$row = new mos_Survey_Force_Survey( $database );
	@$_POST['sf_name'] = SF_processGetField(@$_POST['sf_name']);
		
	if (!$row->bind( $_POST )) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	if (_JOOMLA15)
		$row->sf_descr = JRequest::getVar( 'sf_descr', '', 'post', 'string', JREQUEST_ALLOWRAW );
	
	if ($row->sf_special) {
		$userlists = mosGetParam( $_REQUEST, 'userlists', array());
		if (count($userlists) > 0) {
			$row->sf_special = implode(',', $userlists);
		}		
	}
	// pre-save checks
	if (!$row->check()) { 
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	// save the changes
	if (!$row->store()) { 
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	$row->checkin();
	SF_alterXML();
	updateJSRules($row->id);
	#$row->updateOrder();
	global $task;
	if (!$front_end) {
		if ($task == 'apply_surv') {
			mosRedirect( "index2.php?option=$option&task=editA_surv&id=". $row->id, $msg );
		} else {
			mosRedirect( "index2.php?option=$option&task=surveys&catid={$row->sf_cat}" ); 
		}
	}
	else {
		global $Itemid,$Itemid_s;
		if ($task == 'apply_surv') {
			mosRedirect( SFRoute("index.php?option=$option{$Itemid_s}&task=editA_surv&id=". $row->id) );
		} else {
			mosRedirect( SFRoute("index.php?option=$option&task=surveys{$Itemid_s}&catid={$row->sf_cat}") );
		}

	}
}

function SF_removeSurvey( &$cid, $option ) {
	global $database, $front_end, $my;
	if ($front_end && (is_array( $cid ) && count( $cid ) > 0)) {
		for($i = 0, $n = count($cid); $i<$n; $i++){
			if (SF_GetUserType($my->id, $cid[$i]) != 1)
				unset($cid[$i]);
		}
		if (!is_array( $cid ) || count( $cid ) < 1) {
			global $Itemid,$Itemid_s;
			mosRedirect( SFRoute("index.php?option=$option&task=surveys{$Itemid_s}"));
		}
	}
	if (count( $cid )) {
		$cids = implode( ',', $cid );
		$query = "DELETE FROM #__survey_force_survs"
		. "\n WHERE id IN ( $cids )"
		;
		
		$database->setQuery( $query );
		if (!$database->query()) {
			echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		}

		$query = "SELECT id FROM #__survey_force_quests WHERE sf_survey IN ( $cids ) ";
		$database->setQuery( $query );
		$qids = $database->loadResultArray();
		if (count($qids)) {
			$sec = array();
			SF_removeQuestion( $qids, $sec, $option, true );
		}


	}
	SF_alterXML();
	if (!$front_end) {
		mosRedirect( "index2.php?option=$option&task=surveys" );
	}
	else {
		global $Itemid, $Itemid_s;
		mosRedirect( SFRoute("index.php?option=$option&task=surveys{$Itemid_s}") );
	}
}

function SF_cancelSurvey($option) {
	global $database, $front_end;;

	$row = new mos_Survey_Force_Survey( $database );
	$row->bind( $_POST );
	$row->checkin();
	if (!$front_end) {
		mosRedirect("index2.php?option=$option&task=surveys");
	}
	else {
		global $Itemid, $Itemid_s;
		mosRedirect( SFRoute("index.php?option=$option&task=surveys{$Itemid_s}"));
	}
}

function SF_changeSurvey( $cid=null, $state=0, $option ) {
	global $database, $my, $front_end;
	
	if ($front_end && (is_array( $cid ) && count( $cid ) > 0)) {
		for($i = 0, $n = count($cid); $i<$n; $i++){
			if (SF_GetUserType($my->id, $cid[$i]) != 1)
				unset($cid[$i]);
		}
		if (!is_array( $cid ) || count( $cid ) < 1) {
			global $Itemid, $Itemid_s;
			mosRedirect( SFRoute("index.php?option=$option&task=surveys{$Itemid_s}"));
		}
	}	
	if (!is_array( $cid ) || count( $cid ) < 1) {
		$action = $publish ? 'publish_surv' : 'unpublish_surv';
		echo "<script> alert('".JText::_('COM_SF_SELECT_AN_ITEM_TO'). $action."'); window.history.go(-1);</script>\n";
		exit();
	}

	$cids = implode( ',', $cid );

	$query = "UPDATE #__survey_force_survs"
	. "\n SET published = " . intval( $state )
	. "\n WHERE id IN ( $cids )"
	;
	$database->setQuery( $query );
	if (!$database->query()) {
		echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		exit();
	}
	SF_alterXML();
	if (!$front_end) {
		mosRedirect( "index2.php?option=$option&task=surveys" );
	}
	else {
		global $Itemid, $Itemid_s;
		mosRedirect( SFRoute("index.php?option=$option&task=surveys{$Itemid_s}") );
	}
}

function SF_moveSurveySelect( $option, $cid ) {
	global $database, $front_end;;

	if (!is_array( $cid ) || count( $cid ) < 1) {
		echo "<script> alert('".JText::_('COM_SF_SELECT_AN_ITEM_TO_MOVE')."'); window.history.go(-1);</script>\n";
		exit;
	}

	## query to list selected surveys
	$cids = implode( ',', $cid );
	$query = "SELECT a.sf_name, b.sf_catname"
	. "\n FROM #__survey_force_survs AS a LEFT JOIN #__survey_force_cats AS b ON b.id = a.sf_cat"
	. "\n WHERE a.id IN ( $cids )"
	;
	$database->setQuery( $query );
	$items = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());

	## query to choose category to move to
	$query = "SELECT a.sf_catname AS text, a.id AS value"
	. "\n FROM #__survey_force_cats AS a"
	. "\n ORDER BY a.sf_catname"
	;
	$database->setQuery( $query );
	$categories = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());

	// build the html select list
	$CategoryList = mosHTML::selectList( $categories, 'categorymove', 'class="text_area" size="10"', 'value', 'text', null );
	if (!$front_end)
		survey_force_adm_html::SF_moveSurvey_Select( $option, $cid, $CategoryList, $items );
	else
		survey_force_front_html::SF_moveSurvey_Select( $option, $cid, $CategoryList, $items );
}

function SF_moveSurveySave( $cid ) {
	global $database, $front_end;;

	$categoryMove = strval( mosGetParam( $_REQUEST, 'categorymove', '' ) );

	$cids = implode( ',', $cid );
	$total = count( $cid );

	$query = "UPDATE #__survey_force_survs"
	. "\n SET sf_cat = '$categoryMove'"
	. "WHERE id IN ( $cids )"
	;
	$database->setQuery( $query );
	if ( !$database->query() ) {
		echo "<script> alert('". $database->getErrorMsg() ."'); window.history.go(-1); </script>\n";
		exit();
	}
	SF_alterXML();
	$categoryNew = new mos_Survey_Force_Cat ( $database );
	$categoryNew->load( $categoryMove );
	
	$msg = $total .JText::_('COM_SF_SUERVEYS_MOVED_TO'). $categoryNew->sf_catname;
	if (!$front_end)
		mosRedirect( 'index2.php?option=com_surveyforce&task=surveys', $msg);
	else {
		global $Itemid, $Itemid_s;
		mosRedirect( SFRoute("index.php?option=com_surveyforce{$Itemid_s}&task=surveys") );
	}
}

function SF_copySurveySave( $cid ) {
	global $database, $front_end;

	$categoryMove = strval( mosGetParam( $_REQUEST, 'categorymove', '' ) );

	$cids = implode( ',', $cid );
	$total = count( $cid );

	$query = "SELECT * FROM #__survey_force_survs WHERE id IN ( $cids )";
	$database->setQuery( $query );
	$survs_to_copy = $database->loadAssocList();
	foreach ($survs_to_copy as $surv2copy) {
		$new_surv = new mos_Survey_Force_Survey( $database );
		if (!$new_surv->bind( $surv2copy )) { echo "<script> alert('".$new_surv->getError()."'); window.history.go(-1); </script>\n"; exit(); }
		$new_surv->id = 0; $new_surv->sf_cat = $categoryMove; $new_surv->sf_name = 'Copy of ' . $new_surv->sf_name;
		if (!$new_surv->check()) { echo "<script> alert('".$new_surv->getError()."'); window.history.go(-1); </script>\n"; exit(); }
		if (!$new_surv->store()) { echo "<script> alert('".$new_surv->getError()."'); window.history.go(-1); </script>\n"; exit(); }
		$new_surv_id = $new_surv->id;
		$query = "SELECT id FROM #__survey_force_quests WHERE sf_survey = '".$surv2copy['id']."' ORDER BY ordering, id";
		$database->SetQuery( $query );
		$cid = $database->LoadResultArray();
		if (!is_array( $cid )) {
			$cid = array(0);
		}
		
		$query = "SELECT id FROM #__survey_force_qsections WHERE sf_survey_id = '".$surv2copy['id']."' ORDER BY ordering, id";
		$database->SetQuery( $query );
		$sec = $database->LoadResultArray();
		if (!is_array( $sec )) {
			$sec = array();
		}
		
		SF_copyQuestionSave( $cid, 1, $new_surv_id, $sec );
	}
	SF_alterXML();
	$categoryNew = new mos_Survey_Force_Cat ( $database );
	$categoryNew->load( $categoryMove );
	
	$msg = $total .JText::_('COM_SF_SURVEYS_INCLUDING_ALL_QUESIONS'). $categoryNew->sf_catname;
	if (!$front_end)
		mosRedirect( 'index2.php?option=com_surveyforce&task=surveys', $msg);
	else {
		global $Itemid,$Itemid_s;
		mosRedirect( SFRoute("index.php?option=com_surveyforce{$Itemid_s}&task=surveys") );
	}
}

			#######################################
			###	--- ---     QUESTIONS 	--- --- ###
			
function SF_editSection( $id, $option ) {
	global $database, $my, $mainframe, $front_end;

	$row = new mos_Survey_Force_Sections( $database );
	// load the row from the db table
	$row->load( $id );
	
	if ($id) {
		// do stuff for existing records
		$row->checkout($my->id);
	} else {
		// do stuff for new records
		$row->ordering 		= 0;
		$row->sf_survey_id	= intval( $mainframe->getUserStateFromRequest( "surv_id{$option}", 'surv_id', 0 ) );
		
		if ($front_end) {
			global $SF_SESSION;
			$row->sf_survey_id	= intval( mosGetParam( $_REQUEST, 'surv_id', $SF_SESSION->get('list_surv_id', 0) ) );
		}
	}

	$lists = array();
	$query2 = "SELECT * FROM #__survey_force_survs order by sf_name";
	$database->setQuery( $query2 );
	$sf_survs = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
	$lists['sf_surveys']	= mosHTML::selectList( $sf_survs, 'sf_survey_id', 'class="text_area" size="1"', 'id', 'sf_name', $row->sf_survey_id );
	
	$query2 = "SELECT id AS value, sf_qtext AS text FROM #__survey_force_quests WHERE sf_survey = {$row->sf_survey_id} ORDER BY ordering, id";
	$database->setQuery( $query2 );
	$questions = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
	foreach($questions as $f=>$item) {
		
		$questions[$f]->text = strip_tags($item->text);
		if (strlen($item->text) > 255)
			$questions[$f]->text = substr($item->text, 0, 255).'...';
	}
	$selected_q  = 0;
	if ($id) {
		$query2 = "SELECT id AS value FROM #__survey_force_quests WHERE sf_survey = {$row->sf_survey_id} AND sf_section_id = {$id} ORDER BY ordering, id";
		$database->setQuery( $query2 );
		$selected_q = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
		if (count($selected_q) < 1)
			$selected_q  = 0;
	}
	$no_quest = array();
	$no_quest[] = mosHTML::makeOption( '0', ' - '.JText::_('COM_SF_NO_QUESTIONS').' - ' );
	$questions = @array_merge($no_quest, $questions);
	$lists['sf_questions']	= mosHTML::selectList( $questions, 'sf_quest[]', 'class="text_area" size="5" style="width:300px" multiple="multiple"', 'value', 'text', $selected_q );
	
	$query = "SELECT a.ordering AS value, a.sf_name AS text"
	. "\n FROM #__survey_force_qsections AS a"
	. ($row->sf_survey_id ? "\n WHERE a.sf_survey_id = '".$row->sf_survey_id."' " :'')
	. "\n ORDER BY a.ordering"
	;
	if (!$front_end)
		$text_new_order = _CMN_NEW_ITEM_FIRST;
	else {
		global $sf_lang;
		$text_new_order = $sf_lang["SF_NEW_ITEM"];
	}
	if ( $id ) {
		$order = mosGetOrderingList( $query );
		$order = array_slice ($order, 1, -1);
		$ordering = mosHTML::selectList( $order, 'ordering', 'class="text_area" size="1"', 'value', 'text', intval( $row->ordering ) );
	} else {
		$ordering = '<input type="hidden" name="ordering" value="'. $row->ordering .'" />'. $text_new_order;
	}
	$lists['ordering'] = $ordering; 
	
	if (!$front_end){
		$yes_no[] = mosHTML::makeOption( '1', JText::_('COM_SF_YES') );
		$yes_no[] = mosHTML::makeOption( '0', JText::_('COM_SF_NO') );
	}
	else {
		global $sf_lang;
		$yes_no[] = mosHTML::makeOption( '1', $sf_lang["SF_YES"] );
		$yes_no[] = mosHTML::makeOption( '0', $sf_lang["SF_NO"] );
	}
	$lists['addname'] = mosHTML::selectList( $yes_no, 'addname', 'class="text_area" size="1" ', 'value', 'text', intval( $row->addname )); 
	
	if (!$front_end)
		survey_force_adm_html::SF_editSection( $row, $lists, $option );
	else
		survey_force_front_html::SF_editSection( $row, $lists, $option );
}	

function SF_saveSection ( $option ) {
	global $database, $mosConfig_offset, $front_end;

	$row = new mos_Survey_Force_Sections( $database );
	@$_POST['sf_name'] = SF_processGetField(@$_POST['sf_name']);
	if (!$row->bind( $_POST )) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	// pre-save checks
	if (!$row->check()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	// save the changes
	if (!$row->store()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	$row->checkin();
	
	$questions = mosGetParam( $_REQUEST, 'sf_quest', array(0) ); 

	$query = "UPDATE #__survey_force_quests SET sf_section_id = 0 WHERE sf_section_id = {$row->id}";
	$database->setQuery( $query );
	$database->Query( );
	$query = "UPDATE #__survey_force_quests SET sf_section_id = {$row->id} WHERE id IN ( ".implode(',', $questions)." )";
	$database->setQuery( $query );
	$database->Query( );

	SF_refreshSection($row->id);
	SF_refreshOrder($row->sf_survey_id);
	
	global $task;
	if (!$front_end){
		if ($task == 'apply_section') {
			mosRedirect( "index2.php?option=$option&task=editA_sec&id=". $row->id, $msg );
		} else {
			mosRedirect( "index2.php?option=$option&task=questions" );
		}
	}
	else {
		global $Itemid,$Itemid_s;
		if ($task == 'apply_section') {
			mosRedirect( SFRoute("index.php?option=$option{$Itemid_s}&task=editA_sec&id=". $row->id) );
		} else {
			mosRedirect( SFRoute("index.php?option=$option&task=questions{$Itemid_s}") );
		}
	}
}

function SF_ListQuestions( $option )
{
	global $database, $mainframe, $mosConfig_list_limit, $front_end, $my;
	
	$survid		= intval( $mainframe->getUserStateFromRequest( "surv_id{$option}", 'surv_id', 0 ) );
	$limit 		= intval( $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', $mosConfig_list_limit ) );
	$limitstart = intval( $mainframe->getUserStateFromRequest( "view{$option}limitstart", 'limitstart', 0 ) );
	if ($limit == 0) $limit = 999999;
	if ( $front_end ) {
		global $SF_SESSION, $sf_lang;
		$limit		= intval( mosGetParam( $_REQUEST, 'limit', $SF_SESSION->get('list_limit',$mainframe->getCfg('list_limit')) ) );
		if ($limit == 0) $limit = 999999;
		$SF_SESSION->set('list_limit', $limit);
		$limitstart = intval( mosGetParam( $_REQUEST, 'limitstart', $SF_SESSION->get('list_limitstart', 0) ) );
		$SF_SESSION->set('list_limitstart', $limitstart);
		$survid		= intval( mosGetParam( $_REQUEST, 'surv_id', $SF_SESSION->get('list_surv_id', 0) ) );
		$SF_SESSION->set('list_surv_id', $survid);
	}
	$lists = array();
	$lists['sf_auto_pb_on'] = '';
	if ($survid) {
		$query = "SELECT sf_auto_pb "
		. "\n FROM #__survey_force_survs"
		. "\n WHERE id = $survid"
		;
		$database->setQuery( $query );
		if ($database->loadResult() > 0)
			if ( $front_end ) 
				$lists['sf_auto_pb_on'] = '<small>'.$sf_lang['SF_AUTO_PB_IS_ON'].'</small>';
			else
				$lists['sf_auto_pb_on'] = '<small>'.JText::_('COM_SF_SURVEY_OPTION_AUTO_INSERT').'</small>';
	}
	
	// get the total number of records
	$query = "SELECT COUNT(*)"
	. "\n FROM #__survey_force_quests"
	. ( $survid ? "\n WHERE sf_survey = $survid" : '' )
	;
	$database->setQuery( $query );
	$total = $database->loadResult();

	require_once( $GLOBALS['mosConfig_absolute_path'] . '/administrator/includes/pageNavigation.php' );
	if ($front_end)
		$pageNav = new SFPageNav( $total, $limitstart, $limit  );
	else
		$pageNav = new mosPageNav( $total, $limitstart, ($limit==999999?0:$limit) );

	// get the subset (based on limits) of required records
	$query = "SELECT a.*, b.sf_qtype as qtype_full, c.sf_name as survey_name"
	. "\n FROM #__survey_force_quests a LEFT JOIN #__survey_force_qtypes b ON b.id = a.sf_qtype LEFT JOIN #__survey_force_survs c ON a.sf_survey = c.id"
	. ( $survid ? "\n WHERE a.sf_survey = $survid " : '' )
	. "\n ORDER BY a.ordering, a.id "
	. "\n LIMIT $pageNav->limitstart, $pageNav->limit"
	;
	$database->setQuery( $query );
	$quests = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
	$lists['survid'] = $survid ? $survid : 0;
	if ($survid) {
		$query = " SELECT a.*, c.sf_name AS survey_name, b.id AS quest_id "
				." FROM #__survey_force_qsections AS a "
				." LEFT JOIN #__survey_force_survs AS c ON a.sf_survey_id = c.id "
				." LEFT JOIN #__survey_force_quests AS b ON b.sf_section_id = a.id "
				." WHERE 1=1 "
				.( $survid ? "\n AND a.sf_survey_id = $survid" : '' )
				." ORDER BY a.ordering DESC, a.id DESC";
		$database->setQuery( $query );	
		
		$sections = $database->loadAssocList('id');
		$sections = (is_array($sections)? $sections : array());
		$first_sec = end($sections);
		$end_sec = reset($sections);
		$rows = array();
		$last_sid = 0;
		foreach($quests as $n=>$quest){
			if (!isset($first_quest_sec) && $pageNav->limitstart == 0 ) {
				$first_quest_sec = $quest->sf_section_id;
			}

			if ($quest->sf_section_id == 0) {
				$rows[] = $quest;
				continue;
			}
			if ($quest->sf_section_id != $last_sid) {
				foreach($sections as $section) {
					if ($section['id'] == $quest->sf_section_id){
						if (isset($first_quest_sec) && $first_quest_sec == $section['id'])
							$section['first'] = true;

						$rows[] = $section;
						unset($sections[$section['id']]);
					}					
				}
			}
			$last_sid = $quest->sf_section_id;			
			$rows[] = $quest;
		}
		if ($pageNav->limitstart + $pageNav->limit >= $total) {
			$sections = array_reverse($sections);
			foreach($sections as $section) {
				if ($section['quest_id'] == '') {				
					if ($first_sec['id'] == $section['id'])
						$section['first'] = true;
					if ($end_sec['id'] == $section['id'])
						$section['end'] = true;
					$rows[] = $section;
				}
			}
		}
	}
	else
		$rows = $quests;
	$i = 0;
	while ($i < count($rows)) {
		if (isset($rows[$i]->sf_impscale) && $rows[$i]->sf_impscale) {
			
			$query	= "SELECT `id` FROM `#__survey_force_user_starts` WHERE `survey_id` = '{$rows[$i]->sf_survey}'";
			$database->SetQuery( $query );
			$all_start_ids = $database->LoadResultArray();
			
			$query = "SELECT iscale_name FROM #__survey_force_iscales WHERE id = '".$rows[$i]->sf_impscale."'";
			$database->SetQuery( $query );
			$rows[$i]->iscale_name = $database->loadResult();
	
			$query = "SELECT count(id) FROM #__survey_force_user_answers_imp"
			. "\n WHERE quest_id = '".$rows[$i]->id."' and survey_id = '".$rows[$i]->sf_survey."'"
			. "\n AND iscale_id = '".$rows[$i]->sf_impscale."' AND `start_id` IN ('".implode("','", $all_start_ids)."')";
			$database->SetQuery( $query );
			$rows[$i]->total_iscale_answers = $database->LoadResult();
	
			$query = "SELECT b.isf_name, count(a.id) as ans_count FROM #__survey_force_iscales_fields as b LEFT JOIN #__survey_force_user_answers_imp as a ON ( a.quest_id = '".$rows[$i]->id."' and a.survey_id = '".$rows[$i]->sf_survey."' and a.iscale_id = '".$rows[$i]->sf_impscale."' and a.iscalefield_id = b.id AND `a`.`start_id` IN ('".implode("','", $all_start_ids)."'))"
			. "\n WHERE b.iscale_id = '".$rows[$i]->sf_impscale."'"
			. "\n GROUP BY b.isf_name ORDER BY b.ordering";
			$database->SetQuery( $query );
			$ans_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
			
			$rows[$i]->answer_imp = array();
			$j = 0;
			while ( $j < count($ans_data) ) {
				$rows[$i]->answer_imp[$j]->num = $j;
				$rows[$i]->answer_imp[$j]->ftext = $ans_data[$j]->isf_name;
				$rows[$i]->answer_imp[$j]->ans_count = $ans_data[$j]->ans_count;
				$j ++;
			}
		}
		$i ++;
	}
	
	$javascript = 'onchange="document.adminForm.submit();"';
	
	$query = "SELECT id AS value, sf_name AS text"
	. "\n FROM #__survey_force_survs"
	.( $front_end && $my->usertype != 'Super Administrator'? " WHERE sf_author = '{$my->id}' ": ' ')
	. "\n ORDER BY sf_name"
	;
	$database->setQuery( $query );
	if (!$front_end)
		$surveys[] = mosHTML::makeOption( '0', JText::_('COM_SF_S_SELECT_SURVEY') );
	else {
		global $sf_lang;
		$surveys[] = mosHTML::makeOption( '0', $sf_lang["SF_SELECT_SURVEY"] );
	}
	$surveys = @array_merge( $surveys, ($database->LoadObjectList() == null? array(): $database->LoadObjectList()) );
	$survey = mosHTML::selectList( $surveys,'surv_id', 'class="text_area" size="1" '. $javascript, 'value', 'text', $survid ); 
	$lists['survey'] = $survey; 
	
	$query = "SELECT id AS value, sf_qtype AS text"
	. "\n FROM #__survey_force_qtypes"
	. "\n ORDER BY id"
	;
	$database->setQuery( $query );
	$qtypes = array();
	$qtypes = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
	$qtypes = mosHTML::selectList( $qtypes,'qtypes_id', 'class="text_area" size="1" ', 'value', 'text', 1 ); 
	$lists['qtypes'] = $qtypes; 
	if ( !$front_end ) {
		survey_force_adm_html::SF_showQuestsList( $rows, $lists, $pageNav, $option);
	}
	else {
		survey_force_front_html::SF_showQuestsList( $rows, $lists, $pageNav, $option);
	}
}

function SF_editQuestion( $id, $option, $qtype = 0 ) {
	global $database, $my, $mainframe, $front_end;;
	$new_qtype_id	= intval( $mainframe->getUserStateFromRequest( "new_qtype_id{$option}", 'new_qtype_id', 0 ) );
		
	if ( $qtype == 8 ) {
		$sf_survey = intval( $mainframe->getUserStateFromRequest( "surv_id{$option}", 'surv_id', 0 ) );
		if ( $front_end ) {
			global $SF_SESSION;
			$sf_survey	= intval( mosGetParam( $_REQUEST, 'surv_id', $SF_SESSION->get('list_surv_id', 0) ) );
		}
		$query = "SELECT MAX(ordering) FROM #__survey_force_quests WHERE sf_survey = {$sf_survey}";
		$database->SetQuery( $query );
		$max_ord = $database->LoadResult();

		$query = "INSERT INTO #__survey_force_quests (sf_survey, sf_qtype, sf_compulsory, sf_qtext, ordering, published ) VALUES ($sf_survey, 8, 0, 'Page Break', ".($max_ord + 1).", 1) ";
		$database->setQuery( $query );
		$database->query();
		if (!$front_end)
			mosRedirect( "index2.php?option=$option&task=questions" );
		else {
			global $Itemid, $Itemid_s;
			mosRedirect( SFRoute("index.php?option=$option{$Itemid_s}&task=questions") );
		}
	}
	$is_return = intval(getSessionValue('is_return_sf')) > 0? true: false;
	setSessionValue('is_return_sf', -1);
	$row = new mos_Survey_Force_Question( $database );
	// load the row from the db table
	$row->load( $id );

	if ($id) {
		// do stuff for existing records
		if ($row->sf_qtype == 8) {
			if (!$front_end)
				mosRedirect( "index2.php?option=$option&task=questions" );
			else {
				global $Itemid, $Itemid_s;
				mosRedirect( SFRoute("index.php?option=$option{$Itemid_s}&task=questions") );
			}
		}
		$row->checkout($my->id);
	} else {
		// do stuff for new records
		$row->ordering 		= 0;
		$row->sf_survey		= intval( $mainframe->getUserStateFromRequest( "surv_id{$option}", 'surv_id', 0 ) );
		if ($front_end) {
			global $SF_SESSION;
			$row->sf_survey	=  intval( mosGetParam( $_REQUEST, 'surv_id', $SF_SESSION->get('list_surv_id', 0) ) );
		}
	}
	$lists = array();
	$lists['survid'] = ($row->sf_survey ? $row->sf_survey :0);
	$row->sf_qtext = $is_return ? getSessionValue('sf_qtext_sf'): $row->sf_qtext;
	// build the html select list for ordering
	if ($id) {
		$query = "SELECT a.ordering AS value, a.sf_qtext AS text"
		. "\n FROM #__survey_force_quests AS a"
		. ($row->sf_survey ? "\n WHERE a.sf_survey = '".$row->sf_survey."' " :'')
		. " AND sf_section_id = '".$row->sf_section_id."' "
		. "\n ORDER BY a.ordering, a.id ";
	}
	else {
		$query = "SELECT a.ordering AS value, a.sf_qtext AS text"
		. "\n FROM #__survey_force_quests AS a"
		. ($row->sf_survey ? "\n WHERE a.sf_survey = '".$row->sf_survey."' " :'')
		. "\n ORDER BY a.ordering, a.id ";
	}

	if (!$front_end)
		$text_new_order = _CMN_NEW_ITEM_FIRST;
	else {
		global $sf_lang;
		$text_new_order = $sf_lang["SF_NEW_ITEM"];
	}
	if ( $id ) {
		$order = sfGetOrderingList( $query );		
		$order = array_slice ($order, 1, -1);  
		$sel_value = $is_return ? getSessionValue('ordering_sf'): $row->ordering;   
		$ordering = mosHTML::selectList( $order, 'ordering', 'class="text_area" size="1"', 'value', 'text', intval( $sel_value ) );
	} else {
		$ordering = '<input type="hidden" name="ordering" value="'. $row->ordering .'" />'. $text_new_order;
	}
	$lists['ordering'] = $ordering; 
	
	//build list of surveys
	$query = "SELECT id AS value, sf_name AS text"
	. "\n FROM #__survey_force_survs"
	. ( $front_end && $my->usertype != 'Super Administrator'? " WHERE sf_author = '{$my->id}' ": " ")
	. "\n ORDER BY sf_name"
	;
	$database->setQuery( $query );
	$surveys = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
	$disable = '';
	$sel_value = $is_return ? getSessionValue('sf_survey_sf'): $row->sf_survey;
	$survey = mosHTML::selectList( $surveys,'sf_survey', $disable.' class="text_area" size="1" ', 'value', 'text', intval( $sel_value ) ); 
	$lists['survey'] = $survey; 
	
	//build list of imp.scales
	$query = "SELECT id AS value, iscale_name AS text"
	. "\n FROM #__survey_force_iscales"
	. "\n ORDER BY iscale_name"
	;
	$database->setQuery( $query );
	if (!$front_end)
		$impscales[] = mosHTML::makeOption( '0', '- '.JText::_('COM_SF_SELECT_IMP_SCALE').' -' );
	else {
		global $sf_lang;
		$impscales[] = mosHTML::makeOption( '0', $sf_lang["SF_SELECT_IMP_SCALE"] );
	}
	$impscales = @array_merge( $impscales, ($database->LoadObjectList() == null? array(): $database->LoadObjectList()) );
	$sel_value = $is_return ? getSessionValue('sf_impscale_sf'): $row->sf_impscale;
	if ($is_return) {
		$query = "SELECT id FROM #__survey_force_iscales ORDER BY id DESC";
		$database->setQuery( $query );
		$sel_value = $database->loadResult();
	}
	$impscale = mosHTML::selectList( $impscales, 'sf_impscale', 'class="text_area" size="1" ', 'value', 'text', intval( $sel_value ) ); 
	$lists['impscale'] = $impscale; 
	if (!$front_end){
		$yes_no[] = mosHTML::makeOption( '1', JText::_('COM_SF_YES') );
		$yes_no[] = mosHTML::makeOption( '0', JText::_('COM_SF_NO') );
	}
	else {
		global $sf_lang;
		$yes_no[] = mosHTML::makeOption( '1', $sf_lang["SF_YES"] );
		$yes_no[] = mosHTML::makeOption( '0', $sf_lang["SF_NO"] );
	}
	$sel_value = $is_return ? getSessionValue('sf_compulsory_sf'): $row->sf_compulsory;
	$lists['compulsory'] = mosHTML::selectList( $yes_no, 'sf_compulsory', 'class="text_area" size="1" ', 'value', 'text', intval( $sel_value ) ); 
	$sel_value = $is_return ? getSessionValue('insert_pb_sf'): 1;
	$lists['insert_pb'] = mosHTML::selectList( $yes_no, 'insert_pb', 'class="text_area" size="1" ', 'value', 'text', intval( $sel_value ) ); 
	
	$lists['use_drop_down'] = mosHTML::selectList( $yes_no, 'sf_qstyle', 'class="text_area" size="1" ', 'value', 'text', intval($row->sf_qstyle) );
	
	$sel_value = $is_return ? getSessionValue('published'): 1;
	$lists['published'] = mosHTML::selectList( $yes_no, 'published', 'class="text_area" size="1" ', 'value', 'text', intval( $sel_value ) ); 
	
	$lists['sf_default_hided'] = mosHTML::selectList( $yes_no, 'sf_default_hided', 'class="text_area" size="1" ', 'value', 'text', intval( $row->sf_default_hided ) ); 
		
	//build list of sections
	$query = "SELECT id AS value, sf_name AS text"
	. "\n FROM #__survey_force_qsections"
	. "\n WHERE sf_survey_id = $survey "
	. "\n ORDER BY sf_name "
	;
	$database->setQuery( $query );
	if (!$front_end)
		$sf_sections[] = mosHTML::makeOption( '0', JText::_('COM_SF_SELECT_SECTION') );
	else {
		global $sf_lang;
		$sf_sections[] = mosHTML::makeOption( '0', $sf_lang["SF_SELECT_SECTION"] );
	}
	$sf_sections = @array_merge( $sf_sections, ($database->LoadObjectList() == null? array(): $database->LoadObjectList()) );
	$sel_value = $is_return ? getSessionValue('sf_section_id_sf'): $row->sf_section_id;
	if ( count($sf_sections) > 2 ) {
		$sf_sections = mosHTML::selectList( $sf_sections, 'sf_section_id', 'class="text_area" size="1" ', 'value', 'text', intval( $sel_value )); 
		$lists['sf_section_id'] = $sf_sections; 	
	}
	else {	
		$lists['sf_section_id'] = null;
	}
	
	if (!$qtype) {	
		$qtype = $row->sf_qtype;	
	}
	
	$query = "SELECT id AS value, sf_qtext AS text"
	. "\n FROM #__survey_force_quests WHERE id <> '".$id."' AND sf_qtype <> 8 "
	. ($row->sf_survey ? "\n and sf_survey = '".$row->sf_survey."'" :'')
	. "\n ORDER BY ordering, id "
	;
	$database->setQuery( $query );
	$quests = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
	$i =0;
	while ($i < count($quests)) {
		$quests[$i]->text = strip_tags($quests[$i]->text);
		if (strlen($quests[$i]->text) > 55)
			$quests[$i]->text = substr($quests[$i]->text, 0, 55).'...';
		$quests[$i]->text = $quests[$i]->value . ' - ' . $quests[$i]->text;
		$i ++;
	}
	$quest = mosHTML::selectList( $quests,'sf_quest_list', 'class="text_area" id="sf_quest_list" size="1" ', 'value', 'text', 0 ); 
	$lists['quests'] = $quest;
	
	
	$query = "SELECT id AS value, sf_qtext AS text"
	. "\n FROM #__survey_force_quests WHERE id <> '".$id."' AND sf_qtype NOT IN (4, 7, 8) "
	. ($row->sf_survey ? "\n and sf_survey = '".$row->sf_survey."'" :'')
	. "\n ORDER BY ordering, id "
	;
	$database->setQuery( $query );
	$quests3 = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
	$i =0;
	while ($i < count($quests3)) {
		$quests3[$i]->text = strip_tags($quests3[$i]->text);
		if (strlen($quests3[$i]->text) > 55)
			$quests3[$i]->text = substr($quests3[$i]->text, 0, 55).'...';
		$quests3[$i]->text = $quests3[$i]->value . ' - ' . $quests3[$i]->text;
		$i ++;
	}
	
	$quest = mosHTML::selectList( $quests3,'sf_quest_list3', 'class="text_area" id="sf_quest_list3" size="1" onchange="javascript: showOptions(this.value);" ', 'value', 'text', 0 ); 
	$lists['quests3'] = $quest;
	
	
	$query = "SELECT a.*, c.sf_qtext, c.sf_qtype, c.id AS qid,  d.ftext AS aftext, e.stext AS astext, b.ftext AS qoption, b.id AS bid, d.id AS fdid, e.id AS sdid FROM  #__survey_force_fields AS b, #__survey_force_quests AS c, #__survey_force_quest_show AS a LEFT JOIN #__survey_force_fields AS d ON a.ans_field = d.id LEFT JOIN #__survey_force_scales AS e ON a.ans_field = e.id WHERE a.quest_id = '".$id."' AND a.answer = b.id AND a.quest_id_a = c.id ";
	$database->setQuery( $query );

	$lists['quest_show'] = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
	
	$i =0;
	while ($i < count($lists['quest_show'])) {
		$lists['quest_show'][$i]->sf_qtext = strip_tags($lists['quest_show'][$i]->sf_qtext);
		if (strlen($lists['quest_show'][$i]->sf_qtext) > 55)
			$lists['quest_show'][$i]->sf_qtext = substr($lists['quest_show'][$i]->sf_qtext, 0, 55).'...';
		$lists['quest_show'][$i]->sf_qtext = $lists['quest_show'][$i]->qid . ' - ' . $lists['quest_show'][$i]->sf_qtext;
		$i ++;
	}
	
	$query = "SELECT next_quest_id "
	. "\n FROM #__survey_force_rules WHERE quest_id = '".$row->id."' and answer_id = 9999997 ";
	$database->setQuery( $query );
	$squest = (int) $database->LoadResult();
	$quest = mosHTML::selectList( $quests,'sf_quest_list2', 'class="text_area" id="sf_quest_list2" size="1" ', 'value', 'text', $squest ); 
	$lists['quests2'] = $quest;
	$lists['checked'] = '';
	if ($squest) $lists['checked'] = ' checked = "checked" ';
	
	$lists['sf_fields_rule'] = array();
	$query = "SELECT b.ftext, c.sf_qtext, c.id as next_quest_id, a.priority, d.".($qtype == 1?'s':'f')."text as alt_ftext "
	. "\n FROM  #__survey_force_fields as b, #__survey_force_quests as c, #__survey_force_rules as a LEFT JOIN ".($qtype == 1?"#__survey_force_scales as d ":"#__survey_force_fields as d ")." ON a.alt_field_id = d.id "
	. "\n WHERE a.quest_id = '".$row->id."' and a.answer_id <> 9999997 and a.answer_id = b.id and a.next_quest_id = c.id ";
	$database->SetQuery($query);
	$lists['sf_fields_rule'] = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
	if ($is_return) {
		$lists['sf_fields_rule'] = array();
		$sf_hid_rule = getSessionValue('sf_hid_rule_sf');
		$sf_hid_rule_quest = getSessionValue('sf_hid_rule_quest_sf');
		$sf_hid_rule_alt = getSessionValue('sf_hid_rule_alt_sf');
		$priority = getSessionValue('priority_sf');
		for($i = 0, $n = count($sf_hid_rule); $i<$n; $i++ ) {
			$tmp = new stdClass();
			$tmp->next_quest_id = $sf_hid_rule_quest[$i];				
			$tmp->ftext = $sf_hid_rule[$i];
			$tmp->alt_ftext = $sf_hid_rule_alt[$i];
			$tmp->priority = $priority[$i];
			$query = "SELECT c.sf_qtext FROM #__survey_force_quests as c WHERE c.id = ".$sf_hid_rule_quest[$i];
			$database->SetQuery($query);
			$tmp->sf_qtext = $database->LoadResult();
			$lists['sf_fields_rule'][] = $tmp;	
		}
	}
	
	if (!is_array($lists['sf_fields_rule']) || count($lists['sf_fields_rule']) < 1)
		$lists['sf_fields_rule'] = array();
		
	if (($qtype == 1) || ($qtype == 2) || ($qtype == 3)) { //1- Likert; 2 - PickOne; 3 - PickMany
		if ($qtype == 1) {
			$row->is_likert_predefined = ($id)? 0: 1;
			$row->is_likert_predefined = $is_return ? getSessionValue('is_likert_predefined_sf'): $row->is_likert_predefined;
		}
		if ($qtype == 1) {			
			if ($front_end && $my->usertype != 'Super Administrator') 
				$query = "SELECT a.id AS value, a.sf_qtext AS text, sf_survey, sf_name"
				. "\n FROM #__survey_force_quests AS a,  #__survey_force_survs AS b "
				. "\n WHERE a.sf_qtype = 1 and a.id != ".intval($row->id)
				. "\n AND b.sf_author = '{$my->id}'  AND a.sf_survey = b.id "
				. "\n ORDER BY sf_qtext"
				;
			else
				$query = "SELECT q.id AS value, q.sf_qtext AS text, q.sf_survey, s.sf_name"
				. "\n FROM #__survey_force_quests AS q LEFT JOIN #__survey_force_survs AS s ON s.id = sf_survey" 
				. "\n WHERE q.sf_qtype = 1 and q.id != ".intval($row->id)
				. "\n ORDER BY q.sf_qtext"
				;
			$database->setQuery( $query );
			if (!$front_end)
				$likerts[] = mosHTML::makeOption( '0', JText::_('COM_SF_SELECT_LIKERT_SCALE') );
			else {
				global $sf_lang;
				$likerts[] = mosHTML::makeOption( '0', $sf_lang["SF_SELECT_LIKERT_SCALE"] );			
			}
			$likerts = @array_merge( $likerts, ($database->LoadObjectList() == null? array(): $database->LoadObjectList()) );
			$i = 0;
			while ($i < count($likerts)) {
				if ($likerts[$i]->value) {
					$likerts[$i]->text = strip_tags($likerts[$i]->text);
					if (strlen($likerts[$i]->text) > 75)
						$likerts[$i]->text = substr($likerts[$i]->text, 0, 75).'...';
					$likerts[$i]->text = $likerts[$i]->value . ' - ' . $likerts[$i]->text;
				} else {
					$likerts[$i]->sf_survey = 0;
					$likerts[$i]->sf_name = '';
				}
				$i ++;
			}
			
			$first_sec = -1;
			$likerts2 = array();
			foreach($likerts as $cl_tmp) {
				if ($first_sec != $cl_tmp->sf_survey) {
					if ($first_sec != -1) {
						$tmp = new stdClass();
						$tmp->value = '</OPTGROUP>';
						$tmp->text = $cl_tmp->text;
						$tmp->sf_survey = -1;
						$likerts2[] = $tmp;
					}		
					$tmp = new stdClass();
					$tmp->value = '<OPTGROUP>';
					$tmp->text = $cl_tmp->sf_name;
					$tmp->sf_survey = -1;
					$likerts2[] = $tmp;
					$first_sec = $cl_tmp->sf_survey;
				}	
				$tmp = new stdClass();
				$tmp->value = $cl_tmp->value;
				$tmp->text = $cl_tmp->text;
				$tmp->sf_survey = $cl_tmp->sf_survey;
				$likerts2[] = $tmp;
			}
			if ($first_sec != -1) {
				$tmp = new stdClass();
				$tmp->value = '</OPTGROUP>';
				$tmp->text = '';
				$tmp->sf_survey = -1;
				$likerts2[] = $tmp;
			}
	
			$likert_scale = mosHTML::selectList( $likerts2,'sf_likert_scale', 'class="text_area" size="1" ', 'value', 'text', 0 ); 
			$lists['likert_scale'] = $likert_scale; 
		}
		$lists['sf_fields_scale'] = array();
		$query = "SELECT * FROM #__survey_force_scales WHERE quest_id = '".$row->id."' ORDER BY ordering";
		$database->SetQuery($query);
		$lists['sf_fields_scale'] = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
		
		$fields_scale = mosHTML::selectList( $lists['sf_fields_scale'],'sf_list_scale_fields', 'class="text_area" size="1" id="sf_list_scale_fields"', 'stext', 'stext', 0 ); 
		$lists['sf_list_scale_fields'] = $fields_scale; 
			
		if ($is_return) {
			$lists['sf_fields_scale'] = array();
			$sf_hid_scale = getSessionValue('sf_hid_scale_sf');
			$sf_hid_scale_id = getSessionValue('sf_hid_scale_id_sf');
			for($i = 0, $n = count($sf_hid_scale); $i<$n; $i++ ) {
				$tmp = new stdClass();
				$tmp->id = $sf_hid_scale_id[$i]; 
				$tmp->ordering = 0; $tmp->quest_id = 0;
				$tmp->stext = $sf_hid_scale[$i];
				$lists['sf_fields_scale'][] = $tmp;	
			}
		}
		
		$lists['sf_fields'] = array();
		$query = "SELECT * FROM #__survey_force_fields WHERE quest_id = '".$row->id."' ORDER BY ordering";
		$database->SetQuery($query);
		$lists['sf_fields'] = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
		if ($is_return) {
			$lists['sf_fields'] = array();
			$sf_hid_fields = getSessionValue('sf_hid_fields_sf');
			$sf_hid_field_ids = getSessionValue('sf_hid_field_ids_sf');
			for($i = 0, $n = count($sf_hid_fields); $i<$n; $i++ ) {
				$tmp = new stdClass();
				$tmp->id = $sf_hid_field_ids[$i];				
				$tmp->ftext = $sf_hid_fields[$i];
				$lists['sf_fields'][] = $tmp;	
			}
			if (getSessionValue('other_option_cb_sf') == 2) 
				$lists['other_option'] = 1;
			
			else
				$lists['other_option'] = 0;
				
			$tmp = new stdClass();
			$tmp->id = getSessionValue('other_op_id_sf');			
			$tmp->ftext = getSessionValue('other_option_sf');
			$tmp->is_main = 0;
			$lists['sf_fields'][] = $tmp;			
		}		
		$list_fields = mosHTML::selectList( $lists['sf_fields'], 'sf_field_list', 'class="text_area" id="sf_field_list" size="1" ', 'ftext', 'ftext', 0 );
		$lists['sf_list_fields'] = $list_fields;
		if (!$front_end)
			survey_force_adm_html::SF_editQ_Likert_PickOneMany( $row, $lists, $option, $qtype );
		else
			survey_force_front_html::SF_editQ_Likert_PickOneMany( $row, $lists, $option, $qtype );
	} elseif ($qtype == 4) { // 4 - Short Answer
		if (!$front_end)
			survey_force_adm_html::SF_editQ_Short( $row, $lists, $option );
		else
			survey_force_front_html::SF_editQ_Short( $row, $lists, $option );
	} elseif (($qtype == 5) || ($qtype == 6)) { // 5 - Drop-Down; 6 - Drag'N'Drop
		$lists['sf_fields_rule'] = array();
		$query = "SELECT b.ftext, c.sf_qtext, c.id as next_quest_id, a.priority, d.ftext as alt_ftext  "
		. "\n FROM #__survey_force_rules as a, #__survey_force_fields as b, #__survey_force_quests as c, #__survey_force_fields as d"
		. "\n WHERE a.quest_id = '".$row->id."' and a.answer_id = b.id and a.next_quest_id = c.id and a.alt_field_id = d.id";
		$database->SetQuery($query);
		$lists['sf_fields_rule'] = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
		
		$lists['sf_fields'] = array();
		$query = "SELECT * FROM #__survey_force_fields WHERE quest_id = '".$row->id."' AND is_main = 1 ORDER BY ordering";
		$database->SetQuery($query);
		$lists['sf_fields'] = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
		if ($is_return) {
			$lists['sf_fields'] = array();
			$sf_fields = getSessionValue('sf_fields_sf');
			$sf_field_ids = getSessionValue('sf_field_ids_sf');
			for($i = 0, $n = count($sf_fields); $i<$n; $i++ ) {
				$tmp = new stdClass();
				$tmp->id = $sf_field_ids[$i];				
				$tmp->ftext = $sf_fields[$i];
				$lists['sf_fields'][] = $tmp;	
			}
		}
		$list_fields = mosHTML::selectList( $lists['sf_fields'], 'sf_field_list', 'class="text_area" id="sf_field_list" size="1" ', 'ftext', 'ftext', 0 );
		$lists['sf_list_fields'] = $list_fields;
		
		$lists['sf_alt_fields'] = array();
		$query = "SELECT * FROM #__survey_force_fields WHERE quest_id = '".$row->id."' AND is_main = 0 ORDER BY ordering";
		$database->SetQuery($query);
		$lists['sf_alt_fields'] = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
		if ($is_return) {
			$lists['sf_alt_fields'] = array();
			$sf_alt_fields = getSessionValue('sf_alt_fields_sf');
			$sf_alt_field_ids = getSessionValue('sf_alt_field_ids_sf');
			for($i = 0, $n = count($sf_alt_fields); $i<$n; $i++ ) {
				$tmp = new stdClass();
				$tmp->id = $sf_alt_field_ids[$i];				
				$tmp->ftext = $sf_alt_fields[$i];
				$lists['sf_alt_fields'][] = $tmp;	
			}
		}
		$list_fields = mosHTML::selectList( $lists['sf_alt_fields'], 'sf_alt_field_list', 'class="text_area" id="sf_alt_field_list" size="1" ', 'ftext', 'ftext', 0 );
		$lists['sf_alt_field_list'] = $list_fields;
		
		$sf_fields = $sf_fields_full = array();
		$query = "SELECT * FROM #__survey_force_fields WHERE quest_id = '".$row->id."' and is_main = 1 ORDER BY ordering";
		$database->SetQuery($query);
		$sf_fields = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
		$ii = 0;
		foreach ($sf_fields as $qrow) {
			$sf_fields_full[$ii]->id = $qrow->id;
			$sf_fields_full[$ii]->quest_id = $qrow->quest_id;
			$sf_fields_full[$ii]->ftext = $qrow->ftext;
			$sf_fields_full[$ii]->alt_field_id = $qrow->alt_field_id;
			$database->SetQuery("SELECT ftext FROM #__survey_force_fields WHERE is_main = 0 and quest_id = '".$qrow->quest_id."' and id = '".$qrow->alt_field_id."'");
			$sf_fields_full[$ii]->alt_field_full = $database->LoadResult();
			$sf_fields_full[$ii]->is_main = $qrow->is_main;
			$sf_fields_full[$ii]->is_true = $qrow->is_true;
			$ii++;
		}
		$lists['sf_fields'] = $sf_fields_full;
		if ($is_return) {
			$lists['sf_fields'] = array();
			$sf_fields = getSessionValue('sf_fields_sf');
			$sf_field_ids = getSessionValue('sf_field_ids_sf');
			$sf_alt_fields = getSessionValue('sf_alt_fields_sf');
			$sf_alt_field_ids = getSessionValue('sf_alt_field_ids_sf');
			for($i = 0, $n = count($sf_fields); $i<$n; $i++ ) {
				$tmp = new stdClass();
				$tmp->ftext = $sf_fields[$i];			
				$tmp->id = $sf_field_ids[$i];
				$tmp->alt_field_full = $sf_alt_fields[$i];			
				$tmp->alt_field_id = $sf_alt_field_ids[$i];
				$lists['sf_fields'][] = $tmp;	
			}
		}
		if (!$front_end)
			survey_force_adm_html::SF_editQ_Rankings( $row, $lists, $option, $qtype );
		else
			survey_force_front_html::SF_editQ_Rankings( $row, $lists, $option, $qtype );
	} elseif ($qtype == 7) { // 7 - Boilerplate
		if (!$front_end)
			survey_force_adm_html::SF_editQ_Boilerplate( $row, $lists, $option, $qtype );	
		else
			survey_force_front_html::SF_editQ_Boilerplate( $row, $lists, $option, $qtype );
	} elseif ($qtype == 9) { // 9 - Ranking

		$lists['sf_fields_rank'] = array();
		$query = "SELECT * FROM #__survey_force_fields WHERE quest_id = '".$row->id."' AND is_main = 0 ORDER BY ordering";
		$database->SetQuery($query);
		$lists['sf_fields_rank'] = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
		
		$fields_scale = mosHTML::selectList( $lists['sf_fields_rank'],'sf_list_rank_fields', 'class="text_area" size="1" id="sf_list_rank_fields"', 'ftext', 'ftext', 0 ); 
		$lists['sf_list_rank_fields'] = $fields_scale; 
			
		if ($is_return) {
			$lists['sf_fields_rank'] = array();
			$sf_hid_rank = getSessionValue('sf_hid_rank_sf');
			$sf_hid_rank_id = getSessionValue('sf_hid_rank_id_sf');
			for($i = 0, $n = count($sf_hid_rank); $i<$n; $i++ ) {
				$tmp = new stdClass();
				$tmp->id = $sf_hid_rank_id[$i]; 
				$tmp->ordering = 0; $tmp->quest_id = 0;
				$tmp->ftext = $sf_hid_rank[$i];
				$lists['sf_fields_rank'][] = $tmp;	
			}
		}
		
		$lists['sf_fields'] = array();
		$query = "SELECT * FROM #__survey_force_fields WHERE quest_id = '".$row->id."' AND is_main = 1 ORDER BY ordering";
		$database->SetQuery($query);
		$lists['sf_fields'] = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
		if ($is_return) {
			$lists['sf_fields'] = array();
			$sf_hid_fields = getSessionValue('sf_hid_fields_sf');
			$sf_hid_field_ids = getSessionValue('sf_hid_field_ids_sf');
			for($i = 0, $n = count($sf_hid_fields); $i<$n; $i++ ) {
				$tmp = new stdClass();
				$tmp->id = $sf_hid_field_ids[$i];				
				$tmp->ftext = $sf_hid_fields[$i];
				$lists['sf_fields'][] = $tmp;	
			}
			if (getSessionValue('other_option_cb_sf') == 2) 
				$lists['other_option'] = 1;
			
			else
				$lists['other_option'] = 0;
				
			$tmp = new stdClass();
			$tmp->id = getSessionValue('other_op_id_sf');			
			$tmp->ftext = getSessionValue('other_option_sf');
			$tmp->is_main = 0;
			$lists['sf_fields'][] = $tmp;			
		}		
		$list_fields = mosHTML::selectList( $lists['sf_fields'], 'sf_field_list', 'class="text_area" id="sf_field_list" size="1" ', 'ftext', 'ftext', 0 );
		$lists['sf_list_fields'] = $list_fields;
		if (!$front_end)
			survey_force_adm_html::SF_editQ_Ranking( $row, $lists, $option, $qtype );
		else
			survey_force_front_html::SF_editQ_Ranking( $row, $lists, $option, $qtype );
	}
	
}

function getSessionValue($name = null){
	global $mainframe, $front_end;
	if ($name != null) {
		if ($front_end) {
			global $SF_SESSION;
			return $SF_SESSION->get($name, null);
		}
		else {
			if (_JOOMLA15){
				return $mainframe->getUserStateFromRequest($name, '', '');
			}else {
				if (is_array( $mainframe->_userstate )) {				

					return (isset($mainframe->_userstate[$name]) ? $mainframe->_userstate[$name]: null);
				} else {
					return null;
				}

			}
		}
	}
	return null;
}

function setSessionValue($name = null, $value = null){
	global $mainframe, $front_end;

	if ($name != null ) {
		if ($front_end) {
			global $SF_SESSION;
			$SF_SESSION->set( $name, $value );
		}
		else {			
			$mainframe->setUserState( $name, $value );
		}
					
	}
	return null;
}

function SF_saveQuestion( $option ) {
	global $database, $mainframe, $front_end;;
	
	
	$row = new mos_Survey_Force_Question( $database );
	if (!$row->bind( $_POST )) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	if (_JOOMLA15)
		$row->sf_qtext = JRequest::getVar( 'sf_qtext', '', 'post', 'string', JREQUEST_ALLOWRAW );

	if ($row->id < 1) {
		$query = "SELECT MAX(ordering) FROM #__survey_force_quests WHERE sf_survey = {$row->sf_survey}";
		$database->SetQuery( $query );
		$max_ord = $database->LoadResult();
		$row->ordering = $max_ord + 1;
	}

	$query = "SELECT count(*) FROM #__survey_force_user_answers WHERE quest_id = '".$row->id."'";
	$database->SetQuery( $query );
	$ans_count = $database->LoadResult();
	$is_update = false;
	if ($ans_count > 0) {
		$is_update = true;
	}
	// pre-save checks
	if (!$row->check()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	// save the changes
	if (!$row->store()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	$row->checkin();
	#$row->updateOrder();
	$qid = $row->id;
	
	$query = "DELETE FROM #__survey_force_rules WHERE quest_id = '".$qid."'";
	$database->setQuery( $query );
	$database->query();

	$rules_ar = array();
	$rules_count = 0;
	$sf_hid_rule =  JRequest::getVar( 'sf_hid_rule', array(), 'default', 'array', JREQUEST_ALLOWRAW );
	$sf_hid_rule_alt = mosGetParam( $_REQUEST, 'sf_hid_rule_alt', array() );
	$sf_hid_rule_quest = mosGetParam( $_REQUEST, 'sf_hid_rule_quest', array() );
	
	
	$query = "DELETE FROM #__survey_force_quest_show WHERE quest_id = '".$qid."'";
	$database->setQuery( $query );
	$database->query();
	
	$sf_hid_rule2_id = mosGetParam( $_REQUEST, 'sf_hid_rule2_id', array() );
	$sf_hid_rule2_alt_id = mosGetParam( $_REQUEST, 'sf_hid_rule2_alt_id', array() );
	$sf_hid_rule2_quest_ids = mosGetParam( $_REQUEST, 'sf_hid_rule2_quest_id', array() );

	if (is_array($sf_hid_rule2_quest_ids) && count($sf_hid_rule2_quest_ids)) {
		foreach ($sf_hid_rule2_quest_ids as $ij => $sf_hid_rule2_quest_id) {
			$query = "INSERT INTO `#__survey_force_quest_show` (quest_id, survey_id, quest_id_a, answer, ans_field)
				VALUES('".$qid."','".$row->sf_survey."', '".$sf_hid_rule2_quest_id."', '".(isset($sf_hid_rule2_id[$ij])?$sf_hid_rule2_id[$ij]:0)."', '".(isset($sf_hid_rule2_alt_id[$ij])?$sf_hid_rule2_alt_id[$ij]:0)."')";
			$database->setQuery( $query );
			$database->query();
		}
	}

	$priority = mosGetParam( $_REQUEST, 'priority', array() );
	if (is_array($sf_hid_rule) && count($sf_hid_rule)) {
		foreach ($sf_hid_rule as $f_rule) {
			$rules_ar[$rules_count]->rul_txt = SF_processGetField($f_rule);
			$rules_ar[$rules_count]->answer_id = 0;
			$rules_ar[$rules_count]->rul_txt_alt = SF_processGetField((isset($sf_hid_rule_alt[$rules_count])?$sf_hid_rule_alt[$rules_count]:0));
			$rules_ar[$rules_count]->answer_id_alt = 0;
			$rules_ar[$rules_count]->quest_id = isset($sf_hid_rule_quest[$rules_count])?$sf_hid_rule_quest[$rules_count]:0;
			$rules_ar[$rules_count]->priority = isset($priority[$rules_count])?$priority[$rules_count]:0;
			$rules_count++;
		}
	}
	if ($row->sf_qtype == 1) {
		$new_scale = array();
		$is_likert_predef = intval( mosGetParam( $_POST, 'is_likert_predefined', 0 ));
		$likert_id = intval( mosGetParam( $_POST, 'sf_likert_scale', 0 ));
		if ($is_likert_predef && $likert_id) {
			$query = "DELETE FROM #__survey_force_scales WHERE quest_id = '".$qid."'";
			$database->setQuery( $query );
			$database->query();
			$query = "SELECT * FROM #__survey_force_scales WHERE quest_id = '".$likert_id."' ORDER BY ordering";
			$database->setQuery( $query );
			$new_scale = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
			$field_order = 0;
			foreach ($new_scale as $f_row) {
				$new_field = new mos_Survey_Force_Scale_Field( $database );
				$new_field->quest_id = $qid;
				$new_field->stext = $f_row->stext;
				$new_field->ordering = $field_order;
				if (!$new_field->check()) { echo "<script> alert('".$new_field->getError()."'); window.history.go(-1); </script>\n"; exit(); }
				if (!$new_field->store()) { echo "<script> alert('".$new_field->getError()."'); window.history.go(-1); </script>\n"; exit(); }
				$j = 0;
				while ($j < $rules_count) {
					if ($rules_ar[$j]->rul_txt_alt == $new_field->stext) {
						$rules_ar[$j]->answer_id_alt = $new_field->id;
					}
					$j++;
				}
				$field_order ++ ;
			}
		} else {
			$field_order = 0;
			$scale =  JRequest::getVar( 'sf_hid_scale', array(), 'default', 'array', JREQUEST_ALLOWRAW );
			$scale_id = mosGetParam( $_POST, 'sf_hid_scale_id', array(0) );
			$old_scale_id = mosGetParam( $_POST, 'old_sf_hid_scale_id', array(0) );
			$old_scale_id = @array_merge(array(0=>0),$old_scale_id);
			for ($i = 0, $n = count($old_scale_id); $i < $n; $i++) {
				if (in_array($old_scale_id[$i], $scale_id))	
					unset($old_scale_id[$i]);
			}
			$query = "DELETE FROM #__survey_force_scales WHERE quest_id = '".$qid."' AND id IN ( ".implode(', ', $old_scale_id)." )";
			$database->setQuery( $query );
			$database->query();
			
			for ($i = 0, $n = count($scale); $i < $n; $i++) {
				$f_row = $scale[$i];
				$new_field = new mos_Survey_Force_Scale_Field( $database );
				if ($scale_id[$i] > 0 ) {
					$new_field->id = $scale_id[$i];
				}
				$new_field->quest_id = $qid;
				$new_field->stext = SF_processGetField($f_row);
				$new_field->ordering = $field_order;
				if (!$new_field->check()) { echo "<script> alert('".$new_field->getError()."'); window.history.go(-1); </script>\n"; exit(); }
				if (!$new_field->store()) { echo "<script> alert('".$new_field->getError()."'); window.history.go(-1); </script>\n"; exit(); }
				$j = 0;
				while ($j < $rules_count) {
					if ($rules_ar[$j]->rul_txt_alt == $new_field->stext) {
						$rules_ar[$j]->answer_id_alt = $new_field->id;
					}
					$j++;
				}
				$field_order ++ ;
			}
		}
		
		$field_order = 0;
		$sf_hid_fields = (!empty($_POST['sf_hid_fields'])) ? $_POST['sf_hid_fields'] : array();
		$sf_hid_field_ids = mosGetParam( $_POST, 'sf_hid_field_ids', array(0) );
		$old_sf_hid_field_ids = mosGetParam( $_POST, 'old_sf_hid_field_ids', array(0) );
		$old_sf_hid_field_ids = @array_merge(array(0=>0),$old_sf_hid_field_ids);
		for ($i = 0, $n = count($old_sf_hid_field_ids); $i < $n; $i++) {
			if (in_array($old_sf_hid_field_ids[$i], $sf_hid_field_ids))	
				unset($old_sf_hid_field_ids[$i]);
		}
		$query = "DELETE FROM #__survey_force_fields WHERE quest_id = '".$qid."' AND id IN ( ".implode(', ', $old_sf_hid_field_ids)." ) ";
		$database->setQuery( $query );
		$database->query();
		
		for($i = 0, $n = count($sf_hid_fields); $i < $n; $i++) {
			$f_row = $sf_hid_fields[$i];
			$new_field = new mos_Survey_Force_Field( $database );
			if ($sf_hid_field_ids[$i] > 0 ) {
				$new_field->id = $sf_hid_field_ids[$i];
			}
			$new_field->quest_id = $qid;
			$new_field->ftext = SF_processGetField($f_row);
			$new_field->alt_field_id = 0;
			$new_field->is_main = 1;
			$new_field->ordering = $field_order;
			$new_field->is_true = 1;//(only for pickone)($f_row == $_POST['sf_fields'])?1:0;#(only for pickone)
			
			if (!$new_field->check()) { echo "<script> alert('".$new_field->getError()."'); window.history.go(-1); </script>\n"; exit(); }
			if (!$new_field->store()) { echo "<script> alert('".$new_field->getError()."'); window.history.go(-1); </script>\n"; exit(); }
			$j = 0;
				while ($j < $rules_count) {
					if ($rules_ar[$j]->rul_txt == $new_field->ftext) {
						$rules_ar[$j]->answer_id = $new_field->id;
					}
					$j++;
				}
			$field_order ++ ;
		}
	} elseif ($row->sf_qtype == 2) {
		$field_order = 0;
		$other_option_cb = intval(mosGetParam( $_POST, 'other_option_cb', 0 ));
		
		$sf_hid_fields = (!empty($_POST['sf_hid_fields'])) ? $_POST['sf_hid_fields'] : array();
		
		$sf_hid_field_ids = mosGetParam( $_POST, 'sf_hid_field_ids', array(0) );
		$old_sf_hid_field_ids = mosGetParam( $_POST, 'old_sf_hid_field_ids', array(0) );
		$old_sf_hid_field_ids = @array_merge(array(0 => 0),$old_sf_hid_field_ids);
		for ($i = 0, $n = count($old_sf_hid_field_ids); $i < $n; $i++) {
			if (in_array($old_sf_hid_field_ids[$i], $sf_hid_field_ids))	
				unset($old_sf_hid_field_ids[$i]);
		}
		$query = "DELETE FROM #__survey_force_fields WHERE quest_id = '".$qid."' AND ( id IN ( ".implode(', ', $old_sf_hid_field_ids)." ) ".($other_option_cb != 2? ' OR is_main = 0 ': '')." )";
		$database->setQuery( $query );
		$database->query();
		for($i = 0, $n = count($sf_hid_fields); $i < $n; $i++) {
			$f_row = $sf_hid_fields[$i];
			$new_field = new mos_Survey_Force_Field( $database );
			if ($sf_hid_field_ids[$i] > 0 ) {
				$new_field->id = $sf_hid_field_ids[$i];
			}
			$new_field->quest_id = $qid;
			$new_field->ftext = SF_processGetField($f_row);
			$new_field->alt_field_id = 0;
			$new_field->is_main = 1;
			$new_field->ordering = $field_order;
			$new_field->is_true = 1;//(only for pickone)($f_row == $_POST['sf_fields'])?1:0;#(only for pickone)		
			if (!$new_field->check()) { echo "<script> alert('".$new_field->getError()."'); window.history.go(-1); </script>\n"; exit(); }
			if (!$new_field->store()) { echo "<script> alert('".$new_field->getError()."'); window.history.go(-1); </script>\n"; exit(); }
			$j = 0;
			while ($j < $rules_count) {			
				if ($rules_ar[$j]->rul_txt == $new_field->ftext) {				
					$rules_ar[$j]->answer_id = $new_field->id;
				}
				$j++;
			}
			$field_order ++ ;
		}
		
		if ($other_option_cb == 2) {
			$other_text = $_POST['other_option'];
			
			$other_id = mosGetParam( $_POST, 'other_op_id', 0 );
			$new_field = new mos_Survey_Force_Field( $database );
			if ($other_id > 0 ) {
					$new_field->id = $other_id;
			}
			$new_field->quest_id = $qid;
			$new_field->ftext = SF_processGetField($other_text);
			$new_field->alt_field_id = 0;
			$new_field->is_main = 0;
			$new_field->ordering = $field_order;
			$new_field->is_true = 1;
			if (!$new_field->check()) { echo "<script> alert('".$new_field->getError()."'); window.history.go(-1); </script>\n"; exit(); }
			if (!$new_field->store()) { echo "<script> alert('".$new_field->getError()."'); window.history.go(-1); </script>\n"; exit(); }
			$j = 0;
			while ($j < $rules_count) {			
				if ($rules_ar[$j]->rul_txt == $new_field->ftext) {				
					$rules_ar[$j]->answer_id = $new_field->id;
				}
				$j++;
			}
		}
	 } elseif ($row->sf_qtype == 3) {	
		$field_order = 0;
		$other_option_cb = intval(mosGetParam( $_POST, 'other_option_cb', 0 ));
		
		$sf_hid_fields = (!empty($_POST['sf_hid_fields'])) ? $_POST['sf_hid_fields'] : array();
		$sf_hid_field_ids = mosGetParam( $_POST, 'sf_hid_field_ids', array(0) );
		$old_sf_hid_field_ids = mosGetParam( $_POST, 'old_sf_hid_field_ids', array(0) );
		$old_sf_hid_field_ids = @array_merge(array(0=>0),$old_sf_hid_field_ids);
		for ($i = 0, $n = count($old_sf_hid_field_ids); $i < $n; $i++) {
			if (in_array($old_sf_hid_field_ids[$i], $sf_hid_field_ids))	{				
				unset($old_sf_hid_field_ids[$i]);
				}
		}
		$query = "DELETE FROM #__survey_force_fields WHERE quest_id = '".$qid."' AND ( id IN ( ".implode(', ', $old_sf_hid_field_ids)." ) ".($other_option_cb != 2? ' OR is_main = 0 ': '')." )";
		$database->setQuery( $query );
		$database->query();
		
		for($i = 0, $n = count($sf_hid_fields); $i < $n; $i++) {
			$f_row = $sf_hid_fields[$i];
			$new_field = new mos_Survey_Force_Field( $database );
			if ($sf_hid_field_ids[$i] > 0 ) {
				$new_field->id = $sf_hid_field_ids[$i];
			}
			$new_field->quest_id = $qid;
			$new_field->ftext = SF_processGetField($f_row);
			$new_field->alt_field_id = 0;
			$new_field->is_main = 1;
			$new_field->ordering = $field_order;
			$new_field->is_true = 1;//(only for pickone)($f_row == $_POST['sf_fields'])?1:0;#(only for pickone)
			if (!$new_field->check()) { echo "<script> alert('".$new_field->getError()."'); window.history.go(-1); </script>\n"; exit(); }
			if (!$new_field->store()) { echo "<script> alert('".$new_field->getError()."'); window.history.go(-1); </script>\n"; exit(); }
			$j = 0;
			while ($j < $rules_count) {
				if ($rules_ar[$j]->rul_txt == $new_field->ftext) {
					$rules_ar[$j]->answer_id = $new_field->id;
				}
				$j++;
			}
			$field_order ++ ;
		}
		
		if ($other_option_cb == 2) {
			$other_text = $_POST['other_option'];
			$other_id = mosGetParam( $_POST, 'other_op_id', 0 );
			$new_field = new mos_Survey_Force_Field( $database );
			if ($other_id > 0 ) {
					$new_field->id = $other_id;
			}
			$new_field->quest_id = $qid;
			$new_field->ftext = SF_processGetField($other_text);
			$new_field->alt_field_id = 0;
			$new_field->is_main = 0;
			$new_field->ordering = $field_order;
			$new_field->is_true = 1;
			if (!$new_field->check()) { echo "<script> alert('".$new_field->getError()."'); window.history.go(-1); </script>\n"; exit(); }
			if (!$new_field->store()) { echo "<script> alert('".$new_field->getError()."'); window.history.go(-1); </script>\n"; exit(); }
			$j = 0;
			while ($j < $rules_count) {
				if ($rules_ar[$j]->rul_txt == $new_field->ftext) {
					$rules_ar[$j]->answer_id = $new_field->id;
				}
				$j++;
			}
		}
	} elseif (($row->sf_qtype == 5) or ($row->sf_qtype == 6)) {		
		$ii = 0;
		/*	***   	***   	***   	***
		1. Massiv alt-fieldov soxranyaetsa (zapominayutsa vse id)
		2. Zatem on peremeshivaetsa (shuffle)
		3. Zatem sozranyayutsa main fields (+ dlya niz berutsa alt-fields id iz soxranennyx peremeshannyx (no poziciya pregnyaya)
		Dlya chego vsya eta labuda:
			Sdelano na budushee, esli pravil'nost' otvetov pol'zovatelya vagna (t.e. eto ne prosto survey, a tipa quiz)
				to vo F.E. pri otvete v JS-debuggere mogno prosmotret' id poley, vystavit' ix po poryadku i poluchit' vernyi otvet.
				(posle peremeshivaniya takogo ne sluchitsa)
			***   	***   	***   	***  */

		$sf_fields =  JRequest::getVar( 'sf_fields', array(), 'default', 'array', JREQUEST_ALLOWRAW );	
		$sf_field_ids = mosGetParam( $_POST, 'sf_field_ids', array(0) );
		$old_sf_field_ids = mosGetParam( $_POST, 'old_sf_field_ids', array(0) );
		
		$sf_alt_fields =  JRequest::getVar( 'sf_alt_fields', array(), 'default', 'array', JREQUEST_ALLOWRAW );
		$sf_alt_field_ids = mosGetParam( $_POST, 'sf_alt_field_ids', array(0) );
		$old_sf_alt_field_ids = mosGetParam( $_POST, 'old_sf_alt_field_ids', array(0) );
		
		for ($i = 0, $n = count($old_sf_field_ids); $i < $n; $i++) {
			if (in_array($old_sf_field_ids[$i], $sf_field_ids))	
				unset($old_sf_field_ids[$i]);
		}
		for ($i = 0, $n = count($old_sf_alt_field_ids); $i < $n; $i++) {
			if (in_array($old_sf_alt_field_ids[$i], $sf_alt_field_ids))	
				unset($old_sf_alt_field_ids[$i]);
		}
		
		$old_id = @array_merge(array(0 => 0), $old_sf_field_ids, $old_sf_alt_field_ids);
		$query = "DELETE FROM #__survey_force_fields WHERE quest_id = '".$qid."' AND id IN ( ".implode(', ',$old_id)." )";
		$database->setQuery( $query );
		$database->query();
		
		$new_alt_field_nums = array();
		
		for ($i = 0, $n = count($sf_alt_fields); $i < $n; $i++) {
			$f_row = $sf_alt_fields[$i];
			$new_field = new mos_Survey_Force_Field( $database );
			if ($sf_alt_field_ids[$i] > 0 ) {
				$new_field->id = $sf_alt_field_ids[$i];
			}
			$new_field->quest_id = $qid;
			$new_field->ftext = SF_processGetField($f_row);
			$new_field->alt_field_id = 0;
			$new_field->is_main = 0;
			$new_field->is_true = 1;
			$new_alt_field[$ii]->f_nom = $ii;
			if (!$new_field->check()) { echo "<script> alert('".$new_field->getError()."'); window.history.go(-1); </script>\n"; exit(); }
			if (!$new_field->store()) { echo "<script> alert('".$new_field->getError()."'); window.history.go(-1); </script>\n"; exit(); }
						
			if ($sf_alt_field_ids[$i] > 0 ) {
				$new_alt_field[$ii]->alt_field_id = $sf_alt_field_ids[$i];
			}
			else {
				$new_alt_field[$ii]->alt_field_id = $database->insertid();
			}
			
			$j = 0;
			while ($j < $rules_count) {
				if ($rules_ar[$j]->rul_txt_alt == $new_field->ftext) {
					$rules_ar[$j]->answer_id_alt = $new_field->id;
				}
				$j++;
			}
			$ii++;
		}
		shuffle($new_alt_field);
		$field_order = 0;
		
		for ($i = 0, $n = count($sf_fields); $i < $n; $i++) {
			$f_row = $sf_fields[$i];
			$jj = 0;$alt_f_index = 0;
			foreach ($new_alt_field as $fa_row) {
				if ($fa_row->f_nom == $field_order) { $alt_f_index = $jj; }
				$jj++;
			}
			$new_field = new mos_Survey_Force_Field( $database );
			if ($sf_field_ids[$i] > 0 ) {
				$new_field->id = $sf_field_ids[$i];
			}
			$new_field->quest_id = $qid;
			$new_field->ftext = SF_processGetField($f_row);
			$new_field->alt_field_id = $new_alt_field[$alt_f_index]->alt_field_id;
			$new_field->is_main = 1;
			$new_field->is_true = 1;
			$new_field->ordering = $field_order;
			if (!$new_field->check()) { echo "<script> alert('".$new_field->getError()."'); window.history.go(-1); </script>\n"; exit(); }
			if (!$new_field->store()) { echo "<script> alert('".$new_field->getError()."'); window.history.go(-1); </script>\n"; exit(); }
			$field_order++;
			$j = 0;
			while ($j < $rules_count) {
				if ($rules_ar[$j]->rul_txt == $new_field->ftext) {
					$rules_ar[$j]->answer_id = $new_field->id;
				}
				$j++;
			}
		}
	} elseif ($row->sf_qtype == 9) {
		$other_option_cb = intval(mosGetParam( $_POST, 'other_option_cb', 0 ));
		$other_text = $_POST['other_option'];
		$other_id = mosGetParam( $_POST, 'other_op_id', 0 );
		
		$field_order = 0;
		$rank =  JRequest::getVar( 'sf_hid_rank', array(), 'default', 'array', JREQUEST_ALLOWRAW );
		$rank_id = mosGetParam( $_POST, 'sf_hid_rank_id', array(0) );
		$old_rank_id = mosGetParam( $_POST, 'old_sf_hid_rank_id', array(0) );
		for ($i = 0, $n = count($old_rank_id); $i < $n; $i++) {
			if (in_array($old_rank_id[$i], $rank_id))	
				unset($old_rank_id[$i]);
		}

		$sf_hid_fields =  JRequest::getVar( 'sf_hid_fields', array(), 'default', 'array', JREQUEST_ALLOWRAW );			
		$sf_hid_field_ids = mosGetParam( $_POST, 'sf_hid_field_ids', array(0) );
		$old_sf_hid_field_ids = mosGetParam( $_POST, 'old_sf_hid_field_ids', array(0) );
		for ($i = 0, $n = count($old_sf_hid_field_ids); $i < $n; $i++) {
			if (in_array($old_sf_hid_field_ids[$i], $sf_hid_field_ids))	
				unset($old_sf_hid_field_ids[$i]);
		}
		if ($other_option_cb != 2) 
			$old_ids = @array_merge(array(0=>0), array(0=>$other_id), $old_rank_id, $old_sf_hid_field_ids);
		else
			$old_ids = @array_merge(array(0=>0), $old_rank_id, $old_sf_hid_field_ids);
			
		$query = "DELETE FROM #__survey_force_fields WHERE quest_id = '".$qid."' AND id IN ( ".implode(', ', $old_ids)." ) ";
		$database->setQuery( $query );
		$database->query();
		
		
		for ($i = 0, $n = count($rank); $i < $n; $i++) {
			$f_row = $rank[$i];
			$new_field = new mos_Survey_Force_Field( $database );
			if ($rank_id[$i] > 0 ) {
				$new_field->id = $rank_id[$i];
			}
			$new_field->quest_id = $qid;
			$new_field->ftext = SF_processGetField($f_row);
			$new_field->alt_field_id = 0;
			$new_field->is_main = 0;
			$new_field->ordering = $field_order;
			$new_field->is_true = 1;
			if (!$new_field->check()) { echo "<script> alert('".$new_field->getError()."'); window.history.go(-1); </script>\n"; exit(); }
			if (!$new_field->store()) { echo "<script> alert('".$new_field->getError()."'); window.history.go(-1); </script>\n"; exit(); }
			$j = 0;
			while ($j < $rules_count) {
				if ($rules_ar[$j]->rul_txt_alt == $new_field->ftext) {
					$rules_ar[$j]->answer_id_alt = $new_field->id;
				}
				$j++;
			}
			$field_order ++ ;
		}

		$field_order = 0;
		for($i = 0, $n = count($sf_hid_fields); $i < $n; $i++) {
			$f_row = $sf_hid_fields[$i];
			$new_field = new mos_Survey_Force_Field( $database );
			if ($sf_hid_field_ids[$i] > 0 ) {
				$new_field->id = $sf_hid_field_ids[$i];
			}
			$new_field->quest_id = $qid;
			$new_field->ftext = SF_processGetField($f_row);
			$new_field->alt_field_id = 0;
			$new_field->is_main = 1;
			$new_field->ordering = $field_order;
			$new_field->is_true = 1;//(only for pickone)($f_row == $_POST['sf_fields'])?1:0;#(only for pickone)
			if (!$new_field->check()) { echo "<script> alert('".$new_field->getError()."'); window.history.go(-1); </script>\n"; exit(); }
			if (!$new_field->store()) { echo "<script> alert('".$new_field->getError()."'); window.history.go(-1); </script>\n"; exit(); }
			$j = 0;
			while ($j < $rules_count) {
				if ($rules_ar[$j]->rul_txt == $new_field->ftext) {
					$rules_ar[$j]->answer_id = $new_field->id;
				}
				$j++;
			}
			$field_order ++ ;
		}
		
		if ($other_option_cb == 2) {
			$new_field = new mos_Survey_Force_Field( $database );
			if ($other_id > 0 ) {
					$new_field->id = $other_id;
			}
			$new_field->quest_id = $qid;
			$new_field->ftext = SF_processGetField($other_text);
			$new_field->alt_field_id = 0;
			$new_field->is_main = 1;
			$new_field->ordering = $field_order;
			$new_field->is_true = 2;
			if (!$new_field->check()) { echo "<script> alert('".$new_field->getError()."'); window.history.go(-1); </script>\n"; exit(); }
			if (!$new_field->store()) { echo "<script> alert('".$new_field->getError()."'); window.history.go(-1); </script>\n"; exit(); }
			$j = 0;
			while ($j < $rules_count) {
				if ($rules_ar[$j]->rul_txt == $new_field->ftext) {
					$rules_ar[$j]->answer_id = $new_field->id;
				}
				$j++;
			}
		}
	}
	
	if (is_array($rules_ar) && count($rules_ar) > 0) {
		foreach ($rules_ar as $rule_one) { 
			if ($rule_one->answer_id) { 
				$new_rule = new mos_Survey_Force_Rule_Field( $database );
				$new_rule->quest_id = $qid;
				$new_rule->next_quest_id = $rule_one->quest_id;
				$new_rule->answer_id = $rule_one->answer_id;
				
				$new_rule->alt_field_id = $rule_one->answer_id_alt;
				$new_rule->priority = $rule_one->priority;
				if (!$new_rule->check()) { echo "<script> alert('".$new_rule->getError()."'); window.history.go(-1); </script>\n"; exit(); }
				if (!$new_rule->store()) { echo "<script> alert('".$new_rule->getError()."'); window.history.go(-1); </script>\n"; exit(); }
			}
		}
	}
	$super_rule = intval( mosGetParam( $_REQUEST, 'super_rule', 0 ) );
	$sf_quest_list2 = intval( mosGetParam( $_REQUEST, 'sf_quest_list2', 0 ) );
	
	if ($super_rule && $sf_quest_list2) {
		$new_rule = new mos_Survey_Force_Rule_Field( $database );
		$new_rule->quest_id = $qid;
		$new_rule->next_quest_id = $sf_quest_list2;
		$new_rule->answer_id = 9999997;
				
		$new_rule->alt_field_id = 9999997;
		$new_rule->priority = 1000;
		if (!$new_rule->check()) { echo "<script> alert('".$new_rule->getError()."'); window.history.go(-1); </script>\n"; exit(); }
		if (!$new_rule->store()) { echo "<script> alert('".$new_rule->getError()."'); window.history.go(-1); </script>\n"; exit(); }
	}
	
	$insert_pb = intval( mosGetParam( $_REQUEST, 'insert_pb', 1 ) );
	$q_id = intval( mosGetParam( $_REQUEST, 'id', 0 ) );
	if ( $q_id == 0 && $insert_pb == 1 ) {
		$sf_survey = intval( $mainframe->getUserStateFromRequest( "surv_id{$option}", 'surv_id', $row->sf_survey ) );
		if ( $front_end ) {
			global $SF_SESSION;
			$sf_survey	= intval( mosGetParam( $_REQUEST, 'surv_id', $SF_SESSION->get('list_surv_id', $row->sf_survey) ) );
		}
		$query = "INSERT INTO #__survey_force_quests (sf_survey, sf_qtype, sf_compulsory, sf_qtext, ordering, published) VALUES ({$sf_survey}, 8, 0, 'Page Break', ".($max_ord + 2).", ".$row->published.") ";
		$database->setQuery( $query );
		$database->query();
	}
	
	SF_refreshSection($row->sf_section_id);
	SF_refreshOrder($row->sf_survey);
	
	global $task;
	if (!$front_end) {
		if ($task == 'apply_quest') {
			mosRedirect( "index2.php?option=$option&task=editA_quest&id=". $row->id, $msg );	
		} else {
			mosRedirect( "index2.php?option=$option&task=questions&surv_id=".$row->sf_survey );
		}
	}
	else {
		global $Itemid,$Itemid_s;
		if ($task == 'apply_quest') {
			mosRedirect( SFRoute("index.php?option=$option{$Itemid_s}&task=editA_quest&id=". $row->id) );
		} else {
			mosRedirect( SFRoute("index.php?option=$option{$Itemid_s}&task=questions&surv_id=".$row->sf_survey) );
		}
	}
}

function SF_removeQuestion( &$cid, &$sec, $option, $no_redirect=false ) {
	global $database, $front_end;;
	if (count( $cid )) {
		$cids = implode( ',', $cid );
		$query = "DELETE FROM #__survey_force_quests"
		. "\n WHERE id IN ( $cids )"
		;
		$database->setQuery( $query );
		if (!$database->query()) {
			echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		} else {
			$query = "DELETE FROM #__survey_force_fields WHERE quest_id IN ( $cids )";
			$database->setQuery( $query );
			$database->query();

			$query = "DELETE FROM #__survey_force_scales WHERE quest_id IN ( $cids )";
			$database->setQuery( $query );
			$database->query();
			
			$query = "DELETE FROM #__survey_force_quest_show WHERE quest_id IN ( $cids )";
			$database->setQuery( $query );
			$database->query();
		}
	}	
	if (count( $sec )) {
		$secs = implode( ',', $sec );
		$query = "DELETE FROM #__survey_force_qsections"
		. "\n WHERE id IN ( $secs )"
		;
		$database->setQuery( $query );
		if (!$database->query()) {
			echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		}
	}

	if ($no_redirect) return;


	if (!$front_end)
		mosRedirect( "index2.php?option=$option&task=questions" );
	else {
		global $Itemid, $Itemid_s;
		mosRedirect( SFRoute("index.php?option=$option{$Itemid_s}&task=questions") );
	}
}

function SF_cancelQuestion($option) {
	global $database, $front_end;;

	$row = new mos_Survey_Force_Question( $database );
	$row->bind( $_POST );
	$row->checkin();
	if (!$front_end)
		mosRedirect("index2.php?option=$option&task=questions");
	else {
		global $Itemid, $Itemid_s;
		mosRedirect( SFRoute("index.php?option=$option{$Itemid_s}&task=questions") );
	}
}

function SF_orderQuestion( $id, $inc, $option ) {
	global $database, $front_end;

	$limit 		= intval( mosGetParam( $_REQUEST, 'limit', 0 ) );
	$limitstart = intval( mosGetParam( $_REQUEST, 'limitstart', 0 ) );
	$survid 	= intval( mosGetParam( $_REQUEST, 'surv_id', 0 ) );
	$msg 	= '';
	$row = new mos_Survey_Force_Question( $database );
	$row->load( $id );
	if ($limit == 0) $limit = 999999;
	
	if ($inc < 0) { #orderup 
		$query = "SELECT id, ordering, sf_section_id FROM #__survey_force_quests "
				." WHERE id <> $id AND ordering <= {$row->ordering} ".($survid?" AND sf_survey = $survid ":'')
				." ORDER BY ordering DESC, id DESC LIMIT 1 ";
		
	}
	elseif ($inc > 0) { #orderdown 
		$query = "SELECT id, ordering, sf_section_id FROM #__survey_force_quests "
				." WHERE id <> $id AND ordering >= {$row->ordering} ".($survid?" AND sf_survey = $survid ":'')
				." ORDER BY ordering, id LIMIT 1 ";
		
	}
	$database->setQuery( $query );
	$r_row = null;
	$database->loadObject($r_row);
	if ($r_row != null) {
		if ($row->sf_section_id == $r_row->sf_section_id)
			$row->move( $inc, ($survid?" sf_survey = $survid ":'') );
		elseif($row->sf_section_id != $r_row->sf_section_id && $row->sf_section_id == 0) 
			$row->moves($inc, " sf_section_id = {$r_row->sf_section_id} ".($survid?" AND sf_survey = $survid ":''));
		elseif($row->sf_section_id != $r_row->sf_section_id && $row->sf_section_id != 0) {
			SF_orderSection( $row->sf_section_id, $inc, $option );	
			return;
		}
		SF_refreshSection($row->sf_section_id);
		SF_refreshSection($r_row->sf_section_id);
		SF_refreshOrder($row->sf_survey);
		$msg 	= JText::_('COM_SF_NEW_QUESTION_ORDER_WAS_SAVED');		
	}
	
	if (!$front_end)
		mosRedirect( 'index2.php?option='. $option . '&task=questions', $msg );
	else {
		global $Itemid, $Itemid_s;
		mosRedirect( SFRoute("index.php?option=$option{$Itemid_s}&task=questions") );
	}
}

function SF_orderSection( $id, $inc, $option ) {
	global $database, $front_end;

	$limit 		= intval( mosGetParam( $_REQUEST, 'limit', 0 ) );
	$limitstart = intval( mosGetParam( $_REQUEST, 'limitstart', 0 ) );
	$survid 	= intval( mosGetParam( $_REQUEST, 'surv_id', 0 ) );
	$msg 		= '';
	$row = new mos_Survey_Force_Sections( $database );
	$row->load( $id );
	if ($limit == 0) $limit = 999999;
	if ($inc < 0) { #orderup 
		$query = "SELECT id, ordering, sf_section_id FROM #__survey_force_quests "
				." WHERE sf_section_id <> $id AND ordering <= {$row->ordering} ".($survid?" AND sf_survey = $survid ":'')
				." ORDER BY ordering DESC, id DESC LIMIT 1 ";
		
	}
	elseif ($inc > 0) { #orderdown 
		$query = "SELECT id, ordering, sf_section_id FROM #__survey_force_quests "
				." WHERE sf_section_id <> $id AND ordering >= {$row->ordering} ".($survid?" AND sf_survey = $survid ":'')
				." ORDER BY ordering, id LIMIT 1 ";
		
	}
	$database->setQuery( $query );
	
	$r_row = null;
	$database->loadObject($r_row);
	if ($r_row != null) {
		if ($r_row->sf_section_id == 0) {
			$row_quest = new mos_Survey_Force_Question( $database );
			$row_quest->load( $r_row->id );
			$row_quest->moves(-$inc, " sf_section_id = {$id} ".($survid?" AND sf_survey = $survid ":''));
		}
		elseif ($r_row->sf_section_id != 0) {
			$query = "SELECT id FROM #__survey_force_quests WHERE sf_section_id = '$id' ORDER BY ordering, id " ;
			$database->setQuery( $query );
			$quests = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
			foreach ($quests as $quest) {
				$row_quest = new mos_Survey_Force_Question( $database );
				$row_quest->load( $quest->id );
				$row_quest->moves($inc, " sf_section_id = {$r_row->sf_section_id} ".($survid?" AND sf_survey = $survid ":''));
			}
			$row->move( $inc, ($survid?" sf_survey_id  = $survid ":'') );
		}
		$msg 	= JText::_('COM_SF_NEW_SECTION_ORDER_WAS_SAVED');
		SF_refreshSection($id);
	}
	
	SF_refreshOrder($row->sf_survey_id);
	
	
	if (!$front_end)
		mosRedirect( 'index2.php?option='. $option . '&task=questions', $msg );
	else {
		global $Itemid, $Itemid_s;
		mosRedirect( SFRoute("index.php?option=$option{$Itemid_s}&task=questions") );
	}
}

function SF_saveOrderQuestion( &$cidz, &$secz ) {
	global $database, $front_end;
	
	$cid 	= mosGetParam( $_REQUEST, 'cid', array() );
	if (!is_array( $cid )) {
		$cid = array();
	} 
	
	$sec 	= mosGetParam( $_REQUEST, 'sec', array() );
	if (!is_array( $sec )) {
		$sec = array();
	}
	$survid 	= intval( mosGetParam( $_REQUEST, 'surv_id', 0 ) );
	
	$order 		= mosGetParam( $_REQUEST, 'order', array(0) );
	$orderS		= mosGetParam( $_REQUEST, 'orderS', array(0) );
	
	$query = "SELECT id, ordering FROM #__survey_force_qsections WHERE id NOT IN ('".@implode("','", $sec)."') AND sf_survey_id = '{$survid}'";
	$database->setQuery($query);
	$other_sections = $database->loadObjectList();
	if (is_array($other_sections) && count($other_sections))
	foreach($other_sections as $other_section) {
		$sec[] = $other_section->id;
		$orderS[] = $other_section->ordering;
	}
	
	$query = "SELECT id, ordering FROM #__survey_force_quests WHERE id NOT IN ('".@implode("','", $cid)."') AND sf_survey = '{$survid}'";
	$database->setQuery($query);
	$other_quests = $database->loadObjectList();
	if (is_array($other_quests) && count($other_quests))
	foreach($other_quests as $other_quest) {
		$cid[] = $other_quest->id;
		$order[] = $other_quest->ordering;
	}
	
	$total		= count( $cid );
	$totalS		= count( $sec );

	$row 		= new mos_Survey_Force_Question( $database );
	$rowS 		= new mos_Survey_Force_Sections( $database );
	$conditions = array();
	$sf_survey_id = 0;
	//sort order and cid
	$tmp = array($order, $cid);
	array_multisort( $tmp[0], SORT_ASC, SORT_NUMERIC,
					 $tmp[1], SORT_ASC, SORT_NUMERIC );
	$order = $tmp[0];
	$cid = $tmp[1];
	
	$order_t = array();
	$cid_t = array();
	$type = array();
	foreach($cid as $i=>$id) {
		$row->load( $id );
		if ($row->sf_section_id == 0) {
			$order_t[] = $order[$i];
			$cid_t[] = $cid[$i];
			$type[] = 0;
		}			
	}
		
	// update ordering values	
	for( $i=0; $i < $totalS; $i++ ) {
		$rowS->load( $sec[$i] );
		$order_t[] = $orderS[$i];
		$cid_t[] = $sec[$i];
		$type[] = $sec[$i];
		if ($rowS->ordering != $orderS[$i]) {
			$rowS->ordering = $orderS[$i];
			if (!$rowS->store()) {
				echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
				exit();
			}
		}
	}
	
	$tmp = array($order_t, $cid_t, $type);
	array_multisort( $tmp[0], SORT_ASC, SORT_NUMERIC,
					 $tmp[1], SORT_ASC, SORT_NUMERIC,
					 $tmp[2], SORT_ASC, SORT_NUMERIC );
	$order_t = $tmp[0];
	$cid_t = $tmp[1];
	$type = $tmp[2];
	
	$order_max = $order_t[0];

	for( $i=0, $n=count($cid_t); $i < $n; $i++ ) {
		if ($type[$i] == 0) {
			$row->load( $cid_t[$i] );
			$sf_survey_id = $row->sf_survey;
			$row->ordering = $order_max++;
			if (!$row->store()) {
				echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
				exit();
			}
		}
		else {
			for($j=0, $m=count($cid); $j<$m; $j++){
				$row->load( $cid[$j] );
				$sf_survey_id = $row->sf_survey;
				if ($row->sf_section_id == $type[$i]) {
					$row->ordering = $order_max++;
					if (!$row->store()) {
						echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
						exit();
					}
				}
			}
		}		
	}
	SF_refreshOrder($sf_survey_id);	
	$msg 	= JText::_('COM_SF_NEW_QUESTION_ORDER_WAS_SAVED');
	
	if (!$front_end)
		mosRedirect( 'index2.php?option=com_surveyforce&task=questions', $msg );
	else {
		global $Itemid,$Itemid_s;
		mosRedirect( SFRoute("index.php?option=com_surveyforce{$Itemid_s}&task=questions") );
	}
}

function SF_refreshSection($section_id = 0) {
	global $database;
	
	$query = "SELECT ordering FROM #__survey_force_quests "
			." WHERE sf_section_id = ".$section_id
			." ORDER BY ordering, id  LIMIT 1";
	$database->setQuery($query);
	$quest_ord = $database->loadResult();
	
	$row = new mos_Survey_Force_Sections();
	$row->load($section_id);
	
	if ($quest_ord != $row->ordering){
		$row->ordering = $quest_ord;
		if (!$row->store()) {
			echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
			exit();
		}
	}
		
}

function SF_refreshOrder($sf_survey_id = 0) {
	global $database;
	
	$query = "SELECT id, ordering, sf_section_id FROM #__survey_force_quests "
			." WHERE sf_survey = ".$sf_survey_id
			." ORDER BY ordering, id ";
	$database->setQuery($query);
	$questions = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
	if (count($questions) > 0 ) {
		$last_sec = $questions[0]->sf_section_id;
		$sections = array();
		if ($last_sec != 0)
			$sections[$last_sec] = 1;		
		$s = 0;
		foreach($questions as $question){
			if( $question->sf_section_id == $last_sec ) {
				continue;
			}
			else {
				$last_sec = $question->sf_section_id;
				if (!isset($sections[$question->sf_section_id]))
					$sections[$question->sf_section_id] = 0;
				$sections[$question->sf_section_id]++;
			}				
		}
		foreach($sections as $id => $count){
			if ($count > 1 && $id > 0) {
				$t = 0;
				foreach($questions as $question){
					if ( $t == 0 && $question->sf_section_id == $id ) {
						$first_order = $question->ordering;
						$t = 1;
						continue;
					}
					if ($t == 1 && $question->sf_section_id == $id) {
						$row = new mos_Survey_Force_Question( $database );
						$row->load($question->id);
						$row->moves(-1, " ordering > $first_order AND sf_survey = $sf_survey_id ");
						$first_order = $row->ordering;
					}
				}
			}
		}
		$query = "SELECT id, ordering, sf_section_id FROM #__survey_force_quests "
			." WHERE sf_survey = ".$sf_survey_id
			." ORDER BY ordering, id ";
		$database->setQuery($query);
		$questions = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
		$s = 1;
		foreach($questions as $question){
			$row = new mos_Survey_Force_Question( $database );
			$row->load($question->id);
			$row->ordering = $s++;
			if (!$row->store()) {
				echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
				exit();
			}
		}
	}
}

function SF_moveQuestionSelect( $option, $cid, $sec ) {
	global $database, $front_end, $my;

	if (!is_array( $cid ) || count( $cid ) < 1) {
		echo "<script> alert('".JText::_('COM_SF_SELECT_AN_ITEM_TO_MOVE')."'); window.history.go(-1);</script>\n";
		exit;
	}

	## query to list selected questions
	$cids = implode( ',', $cid );
	$secs = implode( ',', $sec );
	$query = "SELECT CONCAT('Section: ', a.sf_name) AS sf_qtext, b.sf_name AS survey_name"
	. "\n FROM #__survey_force_qsections AS a LEFT JOIN #__survey_force_survs AS b ON b.id = a.sf_survey_id "
	. "\n WHERE a.id IN ( $secs )"
	;
	$database->setQuery( $query );
	$items = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
	
	$query = "SELECT a.sf_qtext, b.sf_name as survey_name"
	. "\n FROM #__survey_force_quests AS a LEFT JOIN #__survey_force_survs AS b ON b.id = a.sf_survey"
	. "\n WHERE a.id IN ( $cids )"
	;
	$database->setQuery( $query );
	$items = @array_merge($items, ($database->LoadObjectList() == null? array(): $database->LoadObjectList()));
	
	## query to choose survey to move to
	$query = "SELECT a.sf_name AS text, a.id AS value"
	. "\n FROM #__survey_force_survs AS a"
	. ( $front_end && $my->usertype != 'Super Administrator'? " WHERE sf_author = '{$my->id}' ": " ")
	. "\n ORDER BY a.sf_name"
	;
	$database->setQuery( $query );
	$surveys = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());

	// build the html select list
	$SurveyList = mosHTML::selectList( $surveys, 'surveymove', 'class="text_area" size="10"', 'value', 'text', null );
	if (!$front_end)
		survey_force_adm_html::SF_moveQ_Select( $option, $cid, $sec, $SurveyList, $items );
	else
		survey_force_front_html::SF_moveQ_Select( $option, $cid, $sec, $SurveyList, $items );
}

function SF_moveQuestionSave( $cid, $sec ) {
	global $database, $front_end;

	$surveyMove = strval( mosGetParam( $_REQUEST, 'surveymove', '' ) );

	$cids = implode( ',', $cid );
	$total = count( $cid );

	$query = "UPDATE #__survey_force_quests"
	. "\n SET sf_survey = '$surveyMove'"
	. "WHERE id IN ( $cids )"
	;
	$database->setQuery( $query );
	if ( !$database->query() ) {
		echo "<script> alert('". $database->getErrorMsg() ."'); window.history.go(-1); </script>\n";
		exit();
	}
	
	$query = "DELETE FROM #__survey_force_quest_show WHERE quest_id IN ( $cids )";
	$database->setQuery( $query );
	$database->query();

	$surveyNew = new mos_Survey_Force_Survey ( $database );
	$surveyNew->load( $surveyMove );
	
	if (count( $sec )) {
		$secs = implode( ',', $sec );
		$query = "UPDATE #__survey_force_qsections SET sf_survey_id = ".$surveyMove
		. "\n WHERE id IN ( $secs )"
		;
		$database->setQuery( $query );
		if (!$database->query()) {
			echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		}
	}
	SF_refreshOrder($surveyMove);
	$msg = $total .JText::_('COM_SF_QUESTIONS_MOVED_TO'). $surveyNew->sf_name;
	if (!$front_end)
		mosRedirect( 'index2.php?option=com_surveyforce&task=questions', $msg );
	else {
		global $Itemid, $Itemid_s;
		mosRedirect( SFRoute("index.php?option=com_surveyforce{$Itemid_s}&task=questions") );
	}
}

function SF_copyQuestionSave( $cid, $run_from_surv_copy = 0, $surveyMove = 0, $sec = array() ) {
	global $database, $front_end;
	$total = 0;
	$rules_data = array();
	$rules_count = 0;
	$copy_rules = 0;//only in 'copy quest' mode (not for 'copy survey' mode)
	if (!$run_from_surv_copy) {
		$surveyMove = intval( mosGetParam( $_REQUEST, 'surveymove', 0 ) );
	}

	if (count( $sec )) {
		$new_sec_id = array();
		foreach ($sec as $s_id)	{
			$row = new mos_Survey_Force_Sections( $database );
			$row->load( $s_id );
			$row->id = 0;
			$row->sf_survey_id = $surveyMove;
			if (!$row->store()) {
				echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
				exit();
			}
			$new_sec_id[$s_id] = $row->id;
			$row->checkin();
		}
	}

	$cids = implode( ',', $cid );
	$total = count( $cid );
	$query = "SELECT * FROM #__survey_force_quests WHERE id IN ( $cids ) ORDER BY ordering, id";
	$database->setQuery( $query );
	$quests_to_copy = $database->loadAssocList();
	$query = "SELECT MAX(ordering) FROM #__survey_force_quests WHERE sf_survey = {$surveyMove}";
	$database->SetQuery( $query );
	$new_order = (int)$database->LoadResult() + 1; 
	$quests_ids_map = array();
	$scales_ids_map = array();
	$fields_ids_map = array();
	$fields2_ids_map = array();
	$altfields_ids_map = array();
	if ($total > 0) {
	foreach ($quests_to_copy as $quest2copy) {
		$old_quest_id = $quest2copy['id'];
		
		if (!$run_from_surv_copy) { 
			$rules_data = array();}
		$new_quest = new mos_Survey_Force_Question( $database );
		if (!$new_quest->bind( $quest2copy )) { 
			echo "<script> alert('".$new_quest->getError()."'); window.history.go(-1); </script>\n"; exit(); }
		if ($new_quest->sf_survey == $surveyMove) {
			$copy_rules = 1;} 
		else {
			$copy_rules = 0;}
		$new_quest->id = 0; 
		$new_quest->ordering = $new_order; 
		$new_quest->sf_survey = $surveyMove;
		$new_quest->sf_rule = 0;
		$new_quest->sf_section_id = ($new_sec_id[$new_quest->sf_section_id]? $new_sec_id[$new_quest->sf_section_id]: 0);
		if ($run_from_surv_copy) { $new_order++; }
		if (!$new_quest->check()) { echo "<script> alert('".$new_quest->getError()."'); window.history.go(-1); </script>\n"; exit(); }
		if (!$new_quest->store()) { echo "<script> alert('".$new_quest->getError()."'); window.history.go(-1); </script>\n"; exit(); }
		
		$new_quest_id = $new_quest->id;
		$quests_ids_map[$old_quest_id] = $new_quest_id;

		if ( ($quest2copy['sf_qtype'] == 1) || ($quest2copy['sf_qtype'] == 2) || ($quest2copy['sf_qtype'] == 3) ) {
			$doing_rule = 0;
			if ($run_from_surv_copy && ($quest2copy['sf_qtype'] == 2)) {
				$query = "SELECT count(*) FROM #__survey_force_rules WHERE quest_id = '".$old_quest_id."'";
				$database->SetQuery( $query );
				$c_rules = $database->loadResult();
				if ($c_rules) {
					$query = "SELECT * FROM #__survey_force_rules WHERE quest_id = '".$old_quest_id."'";
					$database->SetQuery( $query );
					$q_rules = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
					foreach ($q_rules as $q_rule) {
						$new_rule = new stdClass();
						$new_rule = $q_rule;
						$new_rule->id = 0;
						$new_rule->quest_id = $new_quest_id;
						$new_rule->is_ready = 0;
						$rules_data[$rules_count] = $new_rule;
						$rules_count ++;
						$doing_rule = 1;
					}
				}
			} elseif ( ($copy_rules) && (!$run_from_surv_copy) && ($quest2copy['sf_qtype'] == 2) ) {
				$rules_data = array();
				$query = "SELECT * FROM #__survey_force_rules WHERE quest_id = '".$old_quest_id."'";
				$database->SetQuery( $query );
				$q_rules = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
				foreach ($q_rules as $q_rule) {
					$new_rule = new stdClass();
					$new_rule->id = 0;
					$new_rule->quest_id = $new_quest_id;
					$new_rule->next_quest_id = $q_rule->next_quest_id;
					$new_rule->answer_id = $q_rule->answer_id;
					$new_rule->is_ready = 0;
					$rules_data[] = $new_rule;
					$doing_rule = 1;
				}
			}
			$query = "SELECT * FROM #__survey_force_fields WHERE quest_id = '".$old_quest_id."'";
			$database->setQuery( $query );
			$fields_to_copy = $database->loadAssocList();
			foreach ($fields_to_copy as $field2copy) {
				$new_field = new mos_Survey_Force_Field( $database );
				if (!$new_field->bind( $field2copy )) { echo "<script> alert('".$new_field->getError()."'); window.history.go(-1); </script>\n"; exit(); }
				$old_field_id = $new_field->id;
				$new_field->id = 0;
				//$new_quest->ordering = 0;
				$new_field->quest_id = $new_quest_id;
				if (!$new_field->check()) { echo "<script> alert('".$new_field->getError()."'); window.history.go(-1); </script>\n"; exit(); }
				if (!$new_field->store()) { echo "<script> alert('".$new_field->getError()."'); window.history.go(-1); </script>\n"; exit(); }
				$fields_ids_map[$old_field_id] = $new_field->id;
				
				if ($run_from_surv_copy && $doing_rule) {
					$i = 0;
					while ($i < count($rules_data)) {
						if ( (!$rules_data[$i]->is_ready) && ($rules_data[$i]->answer_id == $old_field_id) ) {
							$rules_data[$i]->answer_id = $new_field->id;
							$rules_data[$i]->is_ready = 1;
						}
						$i ++;
					}
				} elseif ((!$run_from_surv_copy) && $doing_rule && $copy_rules && (count($rules_data))) {
					$i = 0;
					while ($i < count($rules_data)) {
						if ( (!$rules_data[$i]->is_ready) && ($rules_data[$i]->answer_id == $old_field_id) ) {
							$rules_data[$i]->answer_id = $new_field->id;
							$rules_data[$i]->is_ready = 1;
						}
						$i ++;
					}
				}
			}
			if ((!$run_from_surv_copy) && $doing_rule && $copy_rules && (count($rules_data)) && ($quest2copy['sf_qtype'] == 2)) {
				$i = 0;
				while ($i < count($rules_data)) {
					if ($rules_data[$i]->is_ready) {
						$new_rule = new mos_Survey_Force_Rule_Field( $database );
						$new_rule->id = 0;
						$new_rule->answer_id = $rules_data[$i]->answer_id;
						$new_rule->quest_id = $new_quest_id;
						$new_rule->next_quest_id = $rules_data[$i]->next_quest_id;
						if (!$new_rule->check()) { echo "<script> alert('".$new_field->getError()."'); window.history.go(-1); </script>\n"; exit(); }
						if (!$new_rule->store()) { echo "<script> alert('".$new_field->getError()."'); window.history.go(-1); </script>\n"; exit(); }
					}
					$i ++;
				}
			}

		}
		if ($quest2copy['sf_qtype'] == 1) {
			$query = "SELECT * FROM #__survey_force_scales WHERE quest_id = '".$old_quest_id."' ORDER BY ordering";
			$database->setQuery( $query );
			$scales_to_copy = $database->loadAssocList();
			foreach ($scales_to_copy as $scale2copy) {
				$new_scale = new mos_Survey_Force_Scale_Field( $database );
				if (!$new_scale->bind( $scale2copy )) { echo "<script> alert('".$new_scale->getError()."'); window.history.go(-1); </script>\n"; exit(); }
				$new_scale->id = 0; 
				//$new_scale->ordering = 0;
				$new_scale->quest_id = $new_quest_id;
				if (!$new_scale->check()) { echo "<script> alert('".$new_scale->getError()."'); window.history.go(-1); </script>\n"; exit(); }
				if (!$new_scale->store()) { echo "<script> alert('".$new_scale->getError()."'); window.history.go(-1); </script>\n"; exit(); }
				
				$scales_ids_map[$scale2copy['id']] = $new_scale->id;	
			}
		}
		if ( ($quest2copy['sf_qtype'] == 5) || ($quest2copy['sf_qtype'] == 6) ) {
			$query = "SELECT * FROM #__survey_force_fields WHERE quest_id = '".$old_quest_id."' and is_main = 1";
			$database->setQuery( $query );
			$fields_to_copy = $database->loadAssocList();
			foreach ($fields_to_copy as $field2copy) {
				$new_field = new mos_Survey_Force_Field( $database );
				if (!$new_field->bind( $field2copy )) { echo "<script> alert('".$new_field->getError()."'); window.history.go(-1); </script>\n"; exit(); }
				$new_field->id = 0;
				//$new_field->ordering = 0;
				$new_field->quest_id = $new_quest_id;

				$alt_field_id = $new_field->alt_field_id;
				$query = "SELECT * FROM #__survey_force_fields WHERE id='".$alt_field_id."' and quest_id = '".$old_quest_id."' and is_main = 0";
				$database->setQuery( $query );
				$alt_field_to_copy = $database->loadAssocList();
				$new_alt_field = new mos_Survey_Force_Field( $database );
				if (!$new_alt_field->bind( $alt_field_to_copy[0] )) { echo "<script> alert('".$new_alt_field->getError()."'); window.history.go(-1); </script>\n"; exit(); }
				$new_alt_field->id = 0;
				//$new_alt_field->ordering = 0;
				$new_alt_field->quest_id = $new_quest_id;
				
				if (!$new_alt_field->check()) { echo "<script> alert('".$new_alt_field->getError()."'); window.history.go(-1); </script>\n"; exit(); }
				if (!$new_alt_field->store()) { echo "<script> alert('".$new_alt_field->getError()."'); window.history.go(-1); </script>\n"; exit(); }
				$new_alt_field_id = $new_alt_field->id;
				$altfields_ids_map[$alt_field_to_copy[0]['id']] = $new_alt_field->id;
				
				$new_field->alt_field_id = $new_alt_field_id;
				if (!$new_field->check()) { echo "<script> alert('".$new_field->getError()."'); window.history.go(-1); </script>\n"; exit(); }
				if (!$new_field->store()) { echo "<script> alert('".$new_field->getError()."'); window.history.go(-1); </script>\n"; exit(); }
				$fields2_ids_map[$field2copy['id']] = $new_field->id;
			}
		}
		if ($quest2copy['sf_qtype'] == 9) {	
			$query = "SELECT * FROM `#__survey_force_fields` WHERE quest_id = '".$old_quest_id."'";
			$database->setQuery( $query );
			$fields_to_copy = $database->loadAssocList();

			foreach ($fields_to_copy as $field2copy) {
				$new_field = new mos_Survey_Force_Field( $database );
				if (!$new_field->bind( $field2copy )) { echo "<script> alert('".$new_field->getError()."'); window.history.go(-1); </script>\n"; exit(); }
				$new_field->id = 0;
				$new_field->quest_id = $new_quest_id;
				if (!$new_field->check()) { echo "<script> alert('".$new_field->getError()."'); window.history.go(-1); </script>\n"; exit(); }
				if (!$new_field->store()) { echo "<script> alert('".$new_field->getError()."'); window.history.go(-1); </script>\n"; exit(); }
				$fields_ids_map[$field2copy['id']] = $new_field->id;
			}
		}
		
	}

		if ($run_from_surv_copy) {
			$query = "SELECT * FROM #__survey_force_quest_show WHERE quest_id IN ('".implode("','", array_keys($quests_ids_map))."')";
			$database->setQuery($query);
			$ds_rules = $database->loadObjectList();
			if (is_array($ds_rules))
			foreach($ds_rules as $ds_rule) {
				$ds_rule->id = null;
				if (!isset($quests_ids_map[$ds_rule->quest_id])) continue;
				foreach ($quests_to_copy as $quest2copy) {
					if ($quest2copy['id'] == $ds_rule->quest_id_a){
						if ($quest2copy['sf_qtype'] == 1){
							$ds_rule->ans_field = $scales_ids_map[$ds_rule->ans_field];
							$ds_rule->answer = $fields_ids_map[$ds_rule->answer];
						} elseif ($quest2copy['sf_qtype'] == 2 || $quest2copy['sf_qtype'] == 3){
							$ds_rule->answer = $fields_ids_map[$ds_rule->answer];
						} elseif ($quest2copy['sf_qtype'] == 5 || $quest2copy['sf_qtype'] == 6){
							$ds_rule->answer = $fields2_ids_map[$ds_rule->answer];
							$ds_rule->ans_field = $altfields_ids_map[$ds_rule->ans_field];
						} elseif ($quest2copy['sf_qtype'] == 9) {
							$ds_rule->answer = $fields_ids_map[$ds_rule->answer];
							$ds_rule->ans_field = $fields_ids_map[$ds_rule->ans_field];
						}
					}
				}				
				$ds_rule->survey_id = $surveyMove;
				$ds_rule->quest_id = $quests_ids_map[$ds_rule->quest_id];
				$ds_rule->quest_id_a = $quests_ids_map[$ds_rule->quest_id_a];
				
				$database->insertObject('#__survey_force_quest_show', $ds_rule, 'id');
			}
			
			
			$query = "SELECT * FROM #__survey_force_rules WHERE quest_id IN ('".implode("','", array_keys($quests_ids_map))."')";
			$database->setQuery($query);
			$rules_data = $database->loadObjectList();
			
			foreach ($rules_data as $rule_data) {
				foreach ($quests_to_copy as $quest2copy) {
					if ($quest2copy['id'] == $rule_data->quest_id){
					
						$new_rule = new mos_Survey_Force_Rule_Field( $database );
						$new_rule->id = 0;
						
						if ($quest2copy['sf_qtype'] == 1){
							$new_rule->answer_id = $fields_ids_map[$rule_data->answer_id];
							$new_rule->alt_field_id = $scales_ids_map[$rule_data->alt_field_id];
						} elseif ($quest2copy['sf_qtype'] == 2 || $quest2copy['sf_qtype'] == 3){
							$new_rule->answer_id = $fields_ids_map[$rule_data->answer_id];
						} elseif ($quest2copy['sf_qtype'] == 5 || $quest2copy['sf_qtype'] == 6){
							$new_rule->answer_id = $fields2_ids_map[$rule_data->answer_id];
							$new_rule->alt_field_id = $altfields_ids_map[$rule_data->alt_field_id];
						} elseif ($quest2copy['sf_qtype'] == 9) {
							$new_rule->answer_id = $fields_ids_map[$rule_data->answer_id];
							$new_rule->alt_field_id = $fields_ids_map[$rule_data->alt_field_id];
						}					
						
						$new_rule->quest_id = $quests_ids_map[$rule_data->quest_id];
						$new_rule->next_quest_id = $quests_ids_map[$rule_data->next_quest_id];
						
						if (!$new_rule->check()) { echo "<script> alert('".$new_field->getError()."'); window.history.go(-1); </script>\n"; exit(); }
						if (!$new_rule->store()) { echo "<script> alert('".$new_field->getError()."'); window.history.go(-1); </script>\n"; exit(); }
					}
				}
			}			
		}
	}
	
	if (!$run_from_surv_copy) {
		$surveyNew = new mos_Survey_Force_Survey ( $database );
		$surveyNew->load( $surveyMove );
		SF_refreshOrder($surveyMove);
		$msg = $total .JText::_('COM_SF_QUESTIONS_COPIED_TO'). $surveyNew->sf_name;
		if (!$front_end)
			mosRedirect( 'index2.php?option=com_surveyforce&task=questions', $msg );
		else {
			global $Itemid, $Itemid_s;
			mosRedirect( SFRoute("index.php?option=com_surveyforce{$Itemid_s}&task=questions") );
		}
	}
}

function SF_setDefault( $id, $option ) {
	global $database, $mainframe, $front_end;
	
	$row = new mos_Survey_Force_Question( $database );
	if (!$row->bind( $_POST )) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	// pre-save checks
	if (!$row->check()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	$lists = array();
	$lists['answer_data'] = array();
	$query = "SELECT * FROM #__survey_force_fields WHERE quest_id = '".$row->id."' AND is_main = '1' ORDER BY ordering";
	$database->SetQuery($query);
	$lists['main_data'] = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
	
	$query = "SELECT answer FROM #__survey_force_def_answers WHERE quest_id = '".$row->id."' ";
	$database->SetQuery($query);
	$lists['answer_data'] = $database->LoadResultArray();
	switch ($row->sf_qtype) {
		case '1':			
			$query = "SELECT a.*, b.ans_field FROM #__survey_force_fields AS a "
					."LEFT JOIN #__survey_force_def_answers AS b ON a.quest_id = b.quest_id AND b.answer = a.id "
					."WHERE a.quest_id = '".$row->id."' AND a.is_main = '1' ORDER BY a.ordering";
			$database->SetQuery($query);
			$lists['main_data'] = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
			
			$query = "SELECT * FROM #__survey_force_scales WHERE quest_id = '".$row->id."' ORDER BY ordering";
			$database->SetQuery($query);
			$lists['scale_data'] = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
			break;
		case '5':
		case '6':
			$query = "SELECT a.*, b.ans_field FROM #__survey_force_fields AS a " 
					."LEFT JOIN #__survey_force_def_answers AS b ON a.quest_id = b.quest_id AND b.answer = a.id "
					."WHERE a.quest_id = '".$row->id."' AND a.is_main = '1' ORDER BY a.ordering ";
			$database->SetQuery($query);
			$lists['main_data'] = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
			
			$query = "SELECT * FROM #__survey_force_fields WHERE quest_id = '".$row->id."' AND is_main = '0'";
			$database->SetQuery($query);
			$lists['alt_data'] = ($database->LoadObjectList() == null? array(): $database->LoadObjectList()); 
			break;
		case '9':
			$query = "SELECT a.*, b.ans_field FROM #__survey_force_fields AS a " 
					."LEFT JOIN #__survey_force_def_answers AS b ON a.quest_id = b.quest_id AND b.answer = a.id "
					."WHERE a.quest_id = '".$row->id."' AND a.is_main = '1' AND a.is_true <> 2 ORDER BY a.ordering ";
			$database->SetQuery($query);
			$lists['main_data'] = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
			
			$query = "SELECT * FROM #__survey_force_fields WHERE quest_id = '".$row->id."' AND is_main = '0'";
			$database->SetQuery($query);
			$lists['alt_data'] = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
			break;
	}
	if (!$front_end)
		survey_force_adm_html::SF_showSetDefault( $row, $lists, $option );
	else 
		survey_force_front_html::SF_showSetDefault( $row, $lists, $option );
}	

function SF_saveDefault( $quest_id = 0, $option ) {
	global $database, $front_end;
	$query = "SELECT sf_survey FROM #__survey_force_quests WHERE id = $quest_id ";
	$database->SetQuery($query);
	$survey_id = $database->LoadResult(); 
	
	if ( $quest_id > 0 && $survey_id > 0) {
	
		$sf_qtype 	= mosGetParam( $_REQUEST, 'sf_qtype', 0 );
		$query = "DELETE FROM #__survey_force_def_answers WHERE survey_id = $survey_id AND quest_id = $quest_id ";
		$database->SetQuery($query);
		$database->Query();
		switch ($sf_qtype) {
				case '1':
					$scale_ids 	= mosGetParam( $_REQUEST, 'scale_id', array() );
					foreach($scale_ids as $scale_id) {
						$ans_id = mosGetParam( $_REQUEST, 'quest_radio_'.$scale_id, 0 );
						$query = "INSERT INTO #__survey_force_def_answers (survey_id, quest_id, answer, ans_field) "
								." VALUES($survey_id, $quest_id, $scale_id, $ans_id) ";
						$database->SetQuery($query);
						$database->Query();
					}				
					break;
					
				case '2':
					$ans_id = mosGetParam( $_REQUEST, 'quest_radio', 0 );
					$query = "INSERT INTO #__survey_force_def_answers (survey_id, quest_id, answer, ans_field) "
							." VALUES($survey_id, $quest_id, $ans_id, 0) ";
					$database->SetQuery($query);
					$database->Query();
					break;
					
				case '3':
					$ans_ids = mosGetParam( $_REQUEST, 'quest_check', array() );
					foreach($ans_ids as $ans_id){
						$query = "INSERT INTO #__survey_force_def_answers (survey_id, quest_id, answer, ans_field) "
								." VALUES($survey_id, $quest_id, $ans_id, 0) ";
						$database->SetQuery($query);
						$database->Query();
					}
					
					break;
					
				case '5':
				case '6':
				case '9':
					$main_ids 	= mosGetParam( $_REQUEST, 'main_id', array() );
					foreach($main_ids as $main_id) {
						$ans_id = mosGetParam( $_REQUEST, 'quest_select_'.$main_id, 0 );
						$query = "INSERT INTO #__survey_force_def_answers (survey_id, quest_id, answer, ans_field) "
								." VALUES($survey_id, $quest_id, $main_id, $ans_id) ";
						$database->SetQuery($query);
						$database->Query();
					}				
					break;			
		}
	}
	if (!$front_end)
		mosRedirect( "index2.php?option=$option&task=editA_quest&id=$quest_id" );
	else {
		global $Itemid, $Itemid_s;
		mosRedirect( SFRoute("index.php?option=$option{$Itemid_s}&task=editA_quest&id=$quest_id") );
	}
}

function SF_cancelDefault( $id, $option ) {
	global $front_end;
	if (!$front_end)
		mosRedirect( "index2.php?option=$option&task=editA_quest&id=$id" );
	else {
		global $Itemid, $Itemid_s;
		mosRedirect( SFRoute("index.php?option=$option{$Itemid_s}&task=editA_quest&id=$id") );
	}
}

function SF_changeCompulsory( $cid=null, $state=0, $option ) {
	global $database, $my, $front_end;
	$surveyid = strval( mosGetParam( $_REQUEST, 'surv_id', 0 ) );
	if ($front_end && (is_array( $cid ) && count( $cid ) > 0)) {
		if (!is_array( $cid ) || count( $cid ) < 1) {
			global $Itemid, $Itemid_s;
			mosRedirect( SFRoute("index.php?option=$option&task=questions{$Itemid_s}&surv_id=$surveyid"));
		}
	}	
	if (!is_array( $cid ) || count( $cid ) < 1) {
		$action = $compulsory ? 'compulsory_quest' : 'uncompulsory_quest';
		echo "<script> alert('".JText::_('COM_SF_SELECT_AN_ITEM_TO').$action."'); window.history.go(-1);</script>\n";
		exit();
	}

	$cids = implode( ',', $cid );

	$query = "UPDATE #__survey_force_quests"
	. "\n SET sf_compulsory = " . intval( $state )
	. "\n WHERE id IN ( $cids )"
	;
	$database->setQuery( $query );
	if (!$database->query()) {
		echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		exit();
	}
	if (!$front_end) {
		mosRedirect( "index2.php?option=$option&task=questions" );
	}
	else {
		global $Itemid, $Itemid_s;
		mosRedirect( SFRoute("index.php?option=$option&task=questions{$Itemid_s}&surv_id=$surveyid") );
	}
}

function SF_changeQuestion( $cid=null, $state=0, $option ) {
	global $database, $my, $front_end;
	$surveyid = strval( mosGetParam( $_REQUEST, 'surv_id', 0 ) );
	if ($front_end && (is_array( $cid ) && count( $cid ) > 0)) {
		if (!is_array( $cid ) || count( $cid ) < 1) {
			global $Itemid, $Itemid_s;
			mosRedirect( SFRoute("index.php?option=$option&task=questions{$Itemid_s}&surv_id=$surveyid"));
		}
	}	
	if (!is_array( $cid ) || count( $cid ) < 1) {
		$action = $publish ? 'publish_quest' : 'unpublish_quest';
		echo "<script> alert('".JText::_('COM_SF_SELECT_AN_ITEM_TO').$action."'); window.history.go(-1);</script>\n";
		exit();
	}

	$cids = implode( ',', $cid );

	$query = "UPDATE #__survey_force_quests"
	. "\n SET published = " . intval( $state )
	. "\n WHERE id IN ( $cids )"
	;
	$database->setQuery( $query );
	if (!$database->query()) {
		echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		exit();
	}
	if (!$front_end) {
		mosRedirect( "index2.php?option=$option&task=questions" );
	}
	else {
		global $Itemid, $Itemid_s;
		mosRedirect( SFRoute("index.php?option=$option&task=questions{$Itemid_s}&surv_id=$surveyid") );
	}
}


			#######################################
			###	---  MANAGE LISTS OF USERS  --- ###

function SF_manageAuthors( $option ) {
	global $database, $mainframe, $mosConfig_list_limit;
	$limit 		= intval( $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', $mosConfig_list_limit ) );
	$limitstart = intval( $mainframe->getUserStateFromRequest( "view{$option}limitstart", 'limitstart', 0 ) );
	if ($limit == 0) $limit = 999999;
	// get the total number of records
	$query = "SELECT COUNT(*) FROM #__survey_force_authors";
	$database->setQuery( $query );
	$total = $database->loadResult();

	
	require_once( $GLOBALS['mosConfig_absolute_path'] . '/administrator/includes/pageNavigation.php' );
	$pageNav = new mosPageNav( $total, $limitstart, ($limit==999999?0:$limit) );
	
	// get the subset (based on limits) of required records
	$query = "SELECT b.id, a.name, a.username, a.email, a.lastvisitDate "
	. " FROM #__users AS a, #__survey_force_authors AS b "
	. " WHERE a.id = b.user_id "
	. "\n LIMIT $pageNav->limitstart, $pageNav->limit"
	;
	$database->setQuery( $query );
	$rows = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
	survey_force_adm_html::SF_showListAuthors( $rows, $pageNav, $option);
}

function SF_showAddAuthors( $option ) {
	global $database, $mainframe, $mosConfig_list_limit;
	$limit 		= intval( $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', $mosConfig_list_limit ) );
	$limitstart = intval( $mainframe->getUserStateFromRequest( "view{$option}limitstart", 'limitstart', 0 ) );
	if ($limit == 0) $limit = 999999;
	$query = "SELECT user_id FROM #__survey_force_authors ";
	$database->setQuery( $query );
	$authors = @array_merge(array('0'=>0),$database->loadResultArray());


	// get the total number of records
	$query = "SELECT COUNT(*) FROM #__users WHERE id NOT IN ( ".implode(',', $authors).") AND usertype <> 'Super Administrator'";
	$database->setQuery( $query );
	$total = $database->loadResult();
	
	require_once( $GLOBALS['mosConfig_absolute_path'] . '/administrator/includes/pageNavigation.php' );
	$pageNav = new mosPageNav( $total, $limitstart, ($limit==999999?0:$limit) );
	
	// get the subset (based on limits) of required records
	$query = "SELECT a.id, a.name, a.username, a.email, a.lastvisitDate "
	. " FROM #__users AS a"
	. " WHERE a.id NOT IN (".implode(',', $authors).") AND a.usertype <> 'Super Administrator'"
	. "\n LIMIT $pageNav->limitstart, $pageNav->limit"
	;
	$database->setQuery( $query );
	$rows = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
	survey_force_adm_html::SF_showAddAuthors( $rows, $pageNav, $option);
}

function SF_delAuthors( $cid, $option ) {
	global $database;
	$msg = '';
	if (count( $cid )) {
		$total = count( $cid );
		$cids = implode( ',', $cid );
		$query = "DELETE FROM #__survey_force_authors"
		. "\n WHERE id IN ( $cids )"
		;
		$database->setQuery( $query );
		if (!$database->query()) {
			echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		}
		$msg = $total .JText::_('COM_SF_SELECTED_AUTHORS_DELETED');
	}
	mosRedirect( "index2.php?option=$option&task=authors", $msg );
}

function SF_saveAuthors( $cid, $option ) {
	global $database;
	$msg = '';
	if (count( $cid )) {
		$total = count( $cid );
		$cids = implode( ',', $cid );
		$query = "DELETE FROM #__survey_force_authors"
		. "\n WHERE user_id IN ( $cids )"
		;
		$database->setQuery( $query );
		if (!$database->query()) {
			echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		}
		
		foreach($cid as $user_id) {
			$query = "INSERT INTO #__survey_force_authors (user_id) VALUES ($user_id)";
			$database->setQuery( $query );
			if (!$database->query()) {
				echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
			}
		}
		$msg = $total .JText::_('COM_SF_SELECTED_USERS_ADDED');
	}
	mosRedirect( "index2.php?option=$option&task=authors", $msg );
}

function SF_cancelAuthors( $option ) {
	mosRedirect( "index2.php?option=$option&task=authors" );
}

function SF_manageUsers( $option ) {
	global $database, $mainframe, $mosConfig_list_limit, $front_end, $my;
	
	$limit 		= intval( $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', $mosConfig_list_limit ) );
	$limitstart = intval( $mainframe->getUserStateFromRequest( "view{$option}limitstart", 'limitstart', 0 ) );
	if ($limit == 0) $limit = 999999;
	if ($front_end) {
		global $SF_SESSION;
		$limit		= intval( mosGetParam( $_REQUEST, 'limit', $SF_SESSION->get('list_limit',$mainframe->getCfg('list_limit')) ) );
		if ($limit == 0) $limit = 999999;
		$SF_SESSION->set('list_limit', $limit);
		$limitstart = intval( mosGetParam( $_REQUEST, 'limitstart', 0 ) );
	}
	// get the total number of records
	$query = "SELECT COUNT(*)"
	. "\n FROM #__survey_force_listusers "
	.($front_end && $my->usertype != 'Super Administrator'?" WHERE  sf_author_id = '{$my->id}' ": '')
	;
	$database->setQuery( $query );
	$total = $database->loadResult();

	require_once( $GLOBALS['mosConfig_absolute_path'] . '/administrator/includes/pageNavigation.php' );
	if ($front_end)
		$pageNav = new SFPageNav( $total, $limitstart, $limit  );
	else
		$pageNav = new mosPageNav( $total, $limitstart, ($limit==999999?0:$limit) );

	// get the subset (based on limits) of required records
	$query = "SELECT a.id, a.listname, a.survey_id, a.date_created, a.date_invited, a.date_remind,"
	. "\n a.is_invited, b.sf_name as survey_name, count(c.id) as users_count, d.name AS author "
	. "\n FROM #__survey_force_listusers a LEFT JOIN #__survey_force_survs b ON b.id = a.survey_id"
	. "\n LEFT JOIN #__survey_force_users c ON c.list_id = a.id"
	. "\n LEFT JOIN #__users AS d ON d.id = a.sf_author_id "
	.($front_end && $my->usertype != 'Super Administrator'?" WHERE  a.sf_author_id = '{$my->id}' ": '')
	. "\n GROUP BY a.id, a.listname, a.survey_id, a.date_created, a.date_invited, a.date_remind,"
	. "\n a.is_invited, b.sf_name "
	. "\n ORDER BY a.listname"
	. "\n LIMIT $pageNav->limitstart, $pageNav->limit"
	;
	$database->setQuery( $query );
	$rows = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
	$i = 0;
	while ($i < count($rows)) {
		$list_id = $rows[$i]->id;
		$query = "SELECT count(a.id) FROM #__survey_force_invitations as a, #__survey_force_users as b"
		. "\n WHERE b.id = a.user_id AND (a.inv_status=1 OR a.inv_status=3) AND b.list_id = '".$list_id."'";
		$database->SetQuery( $query );
		$rows[$i]->total_starts = $database->LoadResult();
		$i ++;
	}

	if (!$front_end)
		survey_force_adm_html::SF_showListUsers( $rows, $lists, $pageNav, $option);
	else
		survey_force_front_html::SF_showListUsers( $rows, $lists, $pageNav, $option);
}

function SF_editListUsers( $id, $option ) {
	global $database, $my;
	$sf_config = new mos_Survey_Force_Config( );

	$row = new mos_Survey_Force_ListUsers( $database );
	// load the row from the db table
	$row->load( $id );

	if ($id) {
		// do stuff for existing records
		$row->checkout($my->id);
	} else {
		// do stuff for new records
		$row->published = 1;
	}
	$lists = array();
	
	$query = "SELECT id AS value, sf_name AS text"
	. "\n FROM #__survey_force_survs WHERE published = 1"
	
	. "\n ORDER BY sf_name"
	;
	$database->setQuery( $query );
	$surveys = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
	$survey = mosHTML::selectList( $surveys,'survey_id', 'class="text_area" size="1" ', 'value', 'text', intval( $row->survey_id ) ); 
	$lists['survey'] = $survey; 
	$lists['date_created'] = '';

	$query = "SELECT id FROM `#__lms_courses` ";
	$database->SetQuery( $query );
	$courses = @array_merge(array(0=>0), ($database->LoadResultArray() == null? array(): $database->LoadResultArray()) );
	$usergroups = array();
	foreach($courses as $course_id) {
		$query = "SELECT a.id AS value, concat(b.course_name, ' (', a.ug_name, ')') AS text FROM #__lms_usergroups AS a, #__lms_courses AS b WHERE b.id = a.course_id AND a.course_id = '{$course_id}' ";
		
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

	survey_force_adm_html::SF_editListUsers( $row, $lists, $sf_config, $option );
}

function SF_createListUsers($data)
{
	global $database;
	$row = new mos_Survey_Force_ListUsers( $database );
	if (!$row->bind( $data )) {
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
	
	return $list_id;
}

function SF_saveListUsers( $option ) {
	global $database;

	$is_add_reg	= intval( mosGetParam( $_POST, 'is_add_reg', 0 ) );
	$is_add_csv	= intval( mosGetParam( $_POST, 'is_import_csv', 0 ) );
	$is_add_man	= intval( mosGetParam( $_POST, 'is_add_manually', 0 ) );
	$is_add_lms	= intval( mosGetParam( $_POST, 'is_add_lms', 0 ) );
	$is_create = false;
	
	if ($is_add_csv) {
		// make arrays of valid fields for error checking
		$fieldDescriptors	= new DeImportFieldDescriptors();
		$fieldDescriptors->addRequired('lastname');
		$fieldDescriptors->addRequired('name');
		$fieldDescriptors->addRequired('email');
		
		$userfile			= mosGetParam($_FILES, 'csv_file', null);
		$userfileTempName	= $userfile['tmp_name'];
		$userfileName 		= $userfile['name'];
		
		$loader		= new DeCsvLoader();
		$loader->setFileName($userfileTempName);
		if (!$loader->load()) {
			echo "<script> alert('".JText::_('COM_SF_IMPORT_FAILED').":".$loader->getErrorMessage()."'); window.history.go(-1); </script>\n"; exit(); 
		}
		
		if (!SF_prepareImport($loader, $fieldDescriptors)) {
			echo "<script> alert('".JText::_('COM_SF_IMPORT_FAILED')."'); window.history.go(-1); </script>\n"; exit();
		}

		$requiredFieldNames	= $fieldDescriptors->getRequiredFieldNames();
		$allFieldNames		= $fieldDescriptors->getFieldNames();
		
		//check validate csv file
		$ii = 0;
		while(!$loader->isEof()) {
			$values		= $loader->getNextValues();
			if (!SF_prepareImportRow($loader, $fieldDescriptors, $values, $requiredFieldNames, $allFieldNames)) {
				echo "<script> alert('".$ii.JText::_('COM_SF_ROW_IMPORT_FAILED')."'); window.history.go(-1); </script>\n"; exit();
			}
			
			$ii ++;
		}
		
		if(!$is_create)
		{
			$list_id = SF_createListUsers($_POST);
			$is_create = true;
		}
		
		// Prepare the data to be imported but first validate all entries before eventually importing
		// (a bit like a software transaction)
		$rows	= array();
		$ii = 0;
		$loader->rowIndex = 1;
		
		while(!$loader->isEof()) {
			$values		= $loader->getNextValues();
			if (!SF_prepareImportRow($loader, $fieldDescriptors, $values, $requiredFieldNames, $allFieldNames)) {
				echo "<script> alert('".$ii.JText::_('COM_SF_ROW_IMPORT_FAILED')."'); window.history.go(-1); </script>\n"; exit();
			}
			$row_user = new mos_Survey_Force_UserInfo($database);
			if (!$row_user->bind( $values )) { echo "<script> alert('".$row_user->getError()."'); window.history.go(-1); </script>\n"; exit(); }
			$row_user->list_id = $list_id;
			if (!$row_user->check()) {				
				continue;
			}
			else{				
				if (function_exists('clone')){ 
					$rows[]	= clone($row_user);
				} else {
					$rows[]	= $row_user;
				}
			}
			$ii ++;
		}

		// Finally import the data
		foreach(array_keys($rows) as $k) {
			$row_user	=& $rows[$k];
			if (!$row_user->store()) { echo "<script> alert('".$row_user->getError()."'); window.history.go(-1); </script>\n"; exit(); }
		}
	}
	if(!$is_create) $list_id = SF_createListUsers($_POST);
	if ($is_add_reg) {
		$query = "SELECT name, username, email FROM #__users";// WHERE block = 0"; //FIX: add usertype(gid) checking
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
	
	if ($is_add_man) {
		$ind = 0;
		foreach ($_POST['sf_hid_names'] as $man_user) {
			$row_user = new mos_Survey_Force_UserInfo( $database );
			$row_user->name = $man_user;
			$row_user->lastname = $_POST['sf_hid_lastnames'][$ind];
			$row_user->email = $_POST['sf_hid_emails'][$ind];
			$row_user->list_id = $list_id;
			if (!$row_user->check()) {				
				continue;
			}
			elseif (!$row_user->store()) {
				echo "<script> alert('".$row_user->getError()."'); window.history.go(-1); </script>\n";
				exit();
			}
			$ind ++;
		}
	}
	if ($is_add_lms) {
		$lms_groups = mosGetParam( $_POST, 'lms_groups', array() );
		if (count($lms_groups) > 0) {
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
			$query = "SELECT user_id FROM #__lms_users_in_groups WHERE (group_id IN ({$lms_group_str})) "
					.($teacher_in_courses_str2 != ''? " OR (group_id = 0 AND course_id IN ({$teacher_in_courses_str2}))":'');
			$database->SetQuery($query);		
		
			$lms_users = $database->LoadResultArray();
			$query = "SELECT name, username, email FROM #__users WHERE id IN (".implode(',', $lms_users).")";
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
	}
	mosRedirect( "index2.php?option=$option&task=users" );
}


function SF_removeListUsers( &$cid, $option ) {
	global $database;
	$msg = '';
	if (count( $cid )) {
		$total = count( $cid );
		$cids = implode( ',', $cid );
		$query = "DELETE FROM #__survey_force_listusers"
		. "\n WHERE id IN ( $cids )"
		;
		$database->setQuery( $query );
		if (!$database->query()) {
			echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		}
		$query = "DELETE FROM #__survey_force_users"
		. "\n WHERE list_id IN ( $cids )"
		;
		$database->setQuery( $query );
		if (!$database->query()) {
			echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		}
		$msg = $total .JText::_('COM_SF_LISTS_INCLUDING_USERS_DELETED');
	}
	mosRedirect( "index2.php?option=$option&task=users", $msg );
}

function SF_cancelListUsers($option) {
	global $database;

	$row = new mos_Survey_Force_ListUsers( $database );
	$row->bind( $_POST );
	$row->checkin();
	mosRedirect("index2.php?option=$option&task=users");
}

function SF_copyListUsers( $cid, $option ) {
	global $database;

	$listuserMove = strval( mosGetParam( $_REQUEST, 'listmove', '' ) );
	if ($cid == -1) {
		$query = "SELECT id FROM #__survey_force_listusers  WHERE listname <> '_generated_users_'";
		$database->setQuery( $query );
		$cid = $database->loadResultArray();
	}
	
	$cids = implode( ',', $cid );
	$total = count( $cid );
	$query = "SELECT * FROM #__survey_force_listusers WHERE id IN ( $cids )  AND listname <> '_generated_users_'";

	$database->setQuery( $query );
	$lists_to_copy = $database->LoadAssocList();
	foreach ($lists_to_copy as $list2copy) {
		$new_list = new mos_Survey_Force_ListUsers( $database );
		if (!$new_list->bind( $list2copy )) { echo "<script> alert('".$new_list->getError()."'); window.history.go(-1); </script>\n"; exit(); }
		$new_list->id = 0;
		$new_list->date_created = date( 'Y-m-d H:i:s' );
		$new_list->date_invited = '';
		$new_list->date_remind = '';
		$new_list->is_invited = 0;
		$new_list->listname = 'Copy of ' . $new_list->listname;
		if (!$new_list->check()) { echo "<script> alert('".$new_list->getError()."'); window.history.go(-1); </script>\n"; exit(); }
		if (!$new_list->store()) { echo "<script> alert('".$new_list->getError()."'); window.history.go(-1); </script>\n"; exit(); }
		$new_list_id = $new_list->id;
		
		$query = "SELECT id FROM #__survey_force_users WHERE list_id = '".$list2copy['id']."'";
		$database->SetQuery( $query );
		$cid = $database->LoadResultArray();
		SF_copyUserSave( $cid, $option, 1, $new_list_id );
	}

	$msg = $total .JText::_('COM_SF_LISTS_INCLUDING_USERS_COPIED');
	mosRedirect( 'index2.php?option=com_surveyforce&task=users', $msg);
}

			#######################################
			###	--- --  SEND INVITATIONS -- --- ###
function SF_inviteUsers( $id, $option ) {
	global $database, $my, $mainframe, $front_end;

	$row = new mos_Survey_Force_ListUsers( $database );
	// load the row from the db table
	$row->load( $id );

	$lists = array();
	
	$query = "SELECT id AS value, email_subject AS text"
	. "\n FROM #__survey_force_emails "
	. ($front_end? "\n WHERE user_id = '{$my->id}' ":'')
	. "\n ORDER BY email_subject"
	;
	$database->setQuery( $query );
	$email_lists = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
	$email_list = mosHTML::selectList( $email_lists, 'email_id', 'class="text_area" size="1" ', 'value', 'text', 0 ); 
	$lists['email_list'] = $email_list; 
	#$lists['date_created'] = '';
	if (!$front_end)
		survey_force_adm_html::SF_inviteUsers( $row, $lists, $option );
	else
		survey_force_front_html::SF_inviteUsers( $row, $lists, $option );
}

function SF_remindUsers( $id, $option ) {
	global $database, $my, $mainframe, $front_end;

	$row = new mos_Survey_Force_ListUsers( $database );
	// load the row from the db table
	$row->load( $id );

	$lists = array();
	
	$query = "SELECT id AS value, email_subject AS text"
	. "\n FROM #__survey_force_emails "
	. ($front_end? "\n WHERE user_id = '{$my->id}' ":'')
	. "\n ORDER BY email_subject"
	;
	$database->setQuery( $query );
	$email_lists = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
	$email_list = mosHTML::selectList( $email_lists, 'email_id', 'class="text_area" size="1" ', 'value', 'text', 0 ); 
	$lists['email_list'] = $email_list; 
	if (!$front_end)
		survey_force_adm_html::SF_remindUsers( $row, $lists, $option );
	else
		survey_force_front_html::SF_remindUsers( $row, $lists, $option );
}
			#######################################
			###	---  MANAGE USERS IN LISTS  --- ###

function SF_viewUsers( $option ) {
	global $database, $mainframe, $mosConfig_list_limit, $front_end, $my;
	
	$listid 	= intval( $mainframe->getUserStateFromRequest( "list_id{$option}", 'list_id', 0 ) ); 
	$limit 		= intval( $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', $mosConfig_list_limit ) );
	$limitstart = intval( $mainframe->getUserStateFromRequest( "view{$option}limitstart", 'limitstart', 0 ) );
	if ($limit == 0) $limit = 999999;
	if ($front_end) {
		global $SF_SESSION;
		$listid 	= intval( mosGetParam( $_REQUEST, 'list_id', $SF_SESSION->get('list_list_id',0) ) );
		$SF_SESSION->set('list_list_id', $listid);
		$limit		= intval( mosGetParam( $_REQUEST, 'limit', $SF_SESSION->get('list_limit',$mainframe->getCfg('list_limit')) ) );
		if ($limit == 0) $limit = 999999;
		$SF_SESSION->set('list_limit', $limit);
		$limitstart = intval( mosGetParam( $_REQUEST, 'limitstart', 0 ) );
		
	}
	// get the total number of records
	$query = "SELECT COUNT(*)"
	. "\n FROM #__survey_force_users WHERE list_id = '".$listid."'"
	;
	$database->setQuery( $query );
	$total = $database->loadResult();

	require_once( $GLOBALS['mosConfig_absolute_path'] . '/administrator/includes/pageNavigation.php' );
	if ($front_end)
		$pageNav = new SFPageNav( $total, $limitstart, $limit  );
	else
		$pageNav = new mosPageNav( $total, $limitstart, ($limit==999999?0:$limit) );

	// get the subset (based on limits) of required records
	$query = "SELECT * "
	. "\n FROM #__survey_force_users WHERE list_id = '".$listid."'"
	. "\n ORDER BY name, lastname, email "
	. "\n LIMIT $pageNav->limitstart, $pageNav->limit"
	;
	$database->setQuery( $query );
	$rows = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
	$query = "SELECT listname FROM #__survey_force_listusers WHERE id = '".$listid."'";
	$database->setQuery( $query );
	$lists['listname'] = $database->loadResult();
	$lists['listid'] = $listid;
	$javascript = 'onchange="document.adminForm.submit();"';
	$query = "SELECT id AS value, listname AS text"
	. "\n FROM #__survey_force_listusers"
	.($front_end && $my->usertype != 'Super Administrator'?" WHERE  sf_author_id = '{$my->id}' ": '')
	. "\n ORDER BY listname"
	;
	$database->setQuery( $query );
	$userlists = array();
	if (!$front_end)
		$userlists[] = mosHTML::makeOption( '0', '- Select List -');
	$userlists = @array_merge( $userlists, ($database->LoadObjectList() == null? array(): $database->LoadObjectList()) );
	$userlist = mosHTML::selectList( $userlists,'list_id', 'class="text_area" size="1" '. $javascript, 'value', 'text', $listid ); 
	$lists['userlists'] = $userlist; 
	
	if (!$front_end)
		survey_force_adm_html::SF_show_Users( $rows, $lists, $pageNav, $option);
	else
		survey_force_front_html::SF_show_Users( $rows, $lists, $pageNav, $option);

}

function SF_editUser( $id, $option ) {
	global $database, $my, $mainframe;

	$row = new mos_Survey_Force_UserInfo( $database );
	// load the row from the db table
	$row->load( $id );

	if ($id) {
		// do stuff for existing records
		$row->checkout($my->id);
	} else {
		// do stuff for new records
		//$row->published = 1;
		$row->list_id = intval( $mainframe->getUserStateFromRequest( "list_id{$option}", 'list_id', 0 ) );
	}
	$lists = array();
	
	$query = "SELECT id AS value, listname AS text"
	. "\n FROM #__survey_force_listusers "
	. "\n ORDER BY listname"
	;
	$database->setQuery( $query );
	$userlists = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
	$userlist = mosHTML::selectList( $userlists,'list_id', 'class="text_area" size="1" ', 'value', 'text', intval( $row->list_id ) ); 
	$lists['userlist'] = $userlist; 

	$query = "SELECT id as value, username as text, name, email FROM #__users ORDER BY username";
	$database->SetQuery($query);
	$list_users = array();
	$list_users[] = mosHTML::makeOption( '0', JText::_('COM_SF_SELECT_USER'));
	$pr = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
	$lists['users'] = $pr;
	$i = 0;
	while ($i < count($pr)) {
		$pr[$i]->text = $pr[$i]->text . " (".$pr[$i]->name.", ".$pr[$i]->email.")";
		$i ++;
	}
	$list_users = @array_merge( $list_users, $pr );
	$lists['reg_users'] = mosHTML::selectList( $list_users, 'reg_users', 'class="text_area" style="width:300px" size="1" onChange="changeUserSelect(this);" ', 'value', 'text', null );
	
	survey_force_adm_html::SF_editUser( $row, $lists, $option );
}

function SF_saveUser( $option ) {
	global $database;

	$row = new mos_Survey_Force_UserInfo( $database );
	if (!$row->bind( $_POST )) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	// pre-save checks
	if (!$row->check()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	// save the changes
	if (!$row->store()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	global $task;
	if ($task == 'apply_user') {
		mosRedirect( "index2.php?option=$option&task=editA_user&id=". $row->id );
	} else {
		mosRedirect( "index2.php?option=$option&task=view_users" );
	}
}

function SF_removeUser( &$cid, $option ) {
	global $database;
	if (count( $cid )) {
		$cids = implode( ',', $cid );
		$query = "DELETE FROM #__survey_force_users"
		. "\n WHERE id IN ( $cids )"
		;
		$database->setQuery( $query );
		if (!$database->query()) {
			echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		}
	}
	mosRedirect( "index2.php?option=$option&task=view_users" );
}

function SF_cancelUser($option) {
	global $database;

	$row = new mos_Survey_Force_UserInfo( $database );
	$row->bind( $_POST );
	$row->checkin();
	mosRedirect("index2.php?option=$option&task=view_users");
}

function SF_moveUserSelect( $cid, $option ) {
	global $database;

	if (!is_array( $cid ) || count( $cid ) < 1) {
		echo "<script> alert('".JText::_('COM_SF_SELECT_AN_ITEM_TO_MOVE')."'); window.history.go(-1);</script>\n";
		exit;
	}

	$cids = implode( ',', $cid );
	$query = "SELECT a.name, a.lastname, b.listname"
	. "\n FROM #__survey_force_users AS a LEFT JOIN #__survey_force_listusers AS b ON b.id = a.list_id"
	. "\n WHERE a.id IN ( $cids )"
	;
	$database->setQuery( $query );
	$items = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());

	## query to choose category to move(copy) to
	$query = "SELECT a.listname AS text, a.id AS value"
	. "\n FROM #__survey_force_listusers AS a"
	. "\n ORDER BY a.listname"
	;
	$database->setQuery( $query );
	$lisusers = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());

	// build the html select list
	$UserlistsList = mosHTML::selectList( $lisusers, 'listmove', 'class="text_area" size="10"', 'value', 'text', null );

	survey_force_adm_html::SF_moveUser_Select( $option, $cid, $UserlistsList, $items );
}

function SF_moveUserSave( $cid ) {
	global $database;

	$listuserMove = strval( mosGetParam( $_REQUEST, 'listmove', '' ) );

	$cids = implode( ',', $cid );
	$total = count( $cid );

	$query = "UPDATE #__survey_force_users"
	. "\n SET list_id = '$listuserMove'"
	. "WHERE id IN ( $cids )"
	;
	$database->setQuery( $query );
	if ( !$database->query() ) {
		echo "<script> alert('". $database->getErrorMsg() ."'); window.history.go(-1); </script>\n";
		exit();
	}

	$listuserNew = new mos_Survey_Force_ListUsers ( $database );
	$listuserNew->load( $listuserMove );
	
	$msg = $total .JText::_('COM_SF_USERS_MOVED_TO'). $listuserNew->listname;
	mosRedirect( 'index2.php?option=com_surveyforce&task=view_users', $msg);
}

function SF_copyUserSave( $cid, $option, $run_from_listcopy = 0, $listuserMove = 0) {
	global $database;

	if (!$run_from_listcopy) {
		$listuserMove = strval( mosGetParam( $_REQUEST, 'listmove', '' ) );
	}

	$cids = implode( ',', $cid );
	$total = count( $cid );

	$query = "SELECT * FROM #__survey_force_users WHERE id IN ( $cids )";
	$database->setQuery( $query );
	$users_to_copy = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
	foreach ($users_to_copy as $user2copy) {
		$query = "INSERT INTO #__survey_force_users (name, lastname, email, list_id, invite_id, is_invited)"
		. "\n VALUES ('".$user2copy->name."', '".$user2copy->lastname."', '".$user2copy->email."', '".$listuserMove."', 0, 0)";
		$database->setQuery( $query );
		if ( !$database->query() ) {
			echo "<script> alert('". $database->getErrorMsg() ."'); window.history.go(-1); </script>\n";
			exit();
		}
	}

	if (!$run_from_listcopy) {
		$listuserNew = new mos_Survey_Force_ListUsers ( $database );
		$listuserNew->load( $listuserMove );
		
		$msg = $total .JText::_('COM_SF_USERS_COPIED_TO'). $listuserNew->listname;
		mosRedirect( 'index2.php?option=com_surveyforce&task=view_users', $msg);
	}
}

			#######################################
			###	--- ---  MANAGE EMAILS	--- --- ###

function SF_ListEmails( $option )
{
	global $database, $mainframe, $mosConfig_list_limit, $front_end, $my;;

	$limit 		= intval( $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', $mosConfig_list_limit ) );
	$limitstart = intval( $mainframe->getUserStateFromRequest( "view{$option}limitstart", 'limitstart', 0 ) );
	if ($limit == 0) $limit = 999999;
	if ($front_end) {
		global $SF_SESSION;
		$limit		= intval( mosGetParam( $_REQUEST, 'limit', $SF_SESSION->get('list_limit',$mainframe->getCfg('list_limit')) ) );
		if ($limit == 0) $limit = 999999;
		$SF_SESSION->set('list_limit', $limit);
		$limitstart = intval( mosGetParam( $_REQUEST, 'limitstart', 0 ) );
	}
	
	// get the total number of records
	$query = "SELECT COUNT(*)"
	. "\n FROM #__survey_force_emails"
	.($front_end? " WHERE user_id ='{$my->id}'": "");
	$database->setQuery( $query );
	$total = $database->loadResult();

	require_once( $GLOBALS['mosConfig_absolute_path'] . '/administrator/includes/pageNavigation.php' );
	if ($front_end)
		$pageNav = new SFPageNav( $total, $limitstart, $limit  );
	else
		$pageNav = new mosPageNav( $total, $limitstart, ($limit==999999?0:$limit) );

	// get the subset (based on limits) of required records
	$query = "SELECT * "
	. "\n FROM #__survey_force_emails"
	.($front_end? " WHERE user_id ='{$my->id}' ": "")
	. "\n ORDER BY email_subject"
	. "\n LIMIT $pageNav->limitstart, $pageNav->limit"
	;
	$database->setQuery( $query );
	$rows = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
	
	if (!$front_end) {
		survey_force_adm_html::SF_showEmailsList( $rows, $pageNav, $option);
	}
	else {
		survey_force_front_html::SF_showEmailsList( $rows, $pageNav, $option);
	}
}

function SF_editEmail( $id, $option ) {
	global $database, $my, $front_end;

	$row = new mos_Survey_Force_Email( $database );
	// load the row from the db table
	$row->load( $id );

	if ($id) {
		// do stuff for existing records
		$row->checkout($my->id);
	} else {
		// do stuff for new records
		global $mosConfig_mailfrom;
		$row->email_reply = ($front_end? $my->email: $mosConfig_mailfrom);
		$row->published = 1;
	}
	$lists = array();

	if (!$front_end) {
		survey_force_adm_html::SF_editEmail( $row, $lists, $option );
	}
	else {
		survey_force_front_html::SF_editEmail( $row, $lists, $option );
	}
	
}

function SF_saveEmail( $option ) {
	global $database, $front_end;

	$row = new mos_Survey_Force_Email( $database );
	if (!$row->bind( $_POST )) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	// pre-save checks
	if (!$row->check()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	// save the changes
	if (!$row->store()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	$row->checkin();
	global $task;
	
	if (!$front_end) {
		if ($task == 'apply_email') {
			mosRedirect( "index2.php?option=$option&task=editA_email&id=". $row->id, $msg );
		} else {
			mosRedirect( "index2.php?option=$option&task=emails" );
		}
	} else {
		global $Itemid,$Itemid_s;
		if ($task == 'apply_email') {
			mosRedirect( SFRoute("index.php?option=$option{$Itemid_s}&task=editA_email&id=". $row->id), $msg );
		} else {
			mosRedirect( SFRoute("index.php?option=$option{$Itemid_s}&task=emails") );
		}
	}
}

function SF_removeEmail( &$cid, $option ) {
	global $database, $front_end;
	if (count( $cid )) {
		$cids = implode( ',', $cid );
		$query = "DELETE FROM #__survey_force_emails"
		. "\n WHERE id IN ( $cids )"
		;
		$database->setQuery( $query );
		if (!$database->query()) {
			echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		}
	}
	if (!$front_end) {
		mosRedirect( "index2.php?option=$option&task=emails" );
	} else {
		global $Itemid,$Itemid_s;
		mosRedirect( SFRoute("index.php?option=$option{$Itemid_s}&task=emails") );
	}
}

function SF_cancelEmail($option) {
	global $database, $front_end;

	$row = new mos_Survey_Force_Email( $database );
	$row->bind( $_POST );
	$row->checkin();
	if (!$front_end)
		mosRedirect("index2.php?option=$option&task=emails");
	else {
		global $Itemid,$Itemid_s;
		mosRedirect(SFRoute("index.php?option=$option{$Itemid_s}&task=emails"));
	}
}

			#######################################
			###	--- -- SEND INVITATIONS  -- --- ###

function SF_startInvitation( $option ) {
	global $database;
	$sf_config = new mos_Survey_Force_Config();
	$mail_pause = intval($sf_config->get('sf_mail_pause'));
	$mail_count = intval($sf_config->get('sf_mail_count'));
	$mail_max = intval($sf_config->get('sf_mail_maximum'));
	ignore_user_abort(false); // STOP script if User press 'STOP' button
	set_time_limit(0);
	@ob_end_clean();
	@ob_start();
	echo "<script>function getObj_frame(name) {"
		. " if (parent.document.getElementById) { return parent.document.getElementById(name); }"
		. "	else if (parent.document.all) { return parent.document.all[name]; }"
		. "	else if (parent.document.layers) { return parent.document.layers[name]; }}</script>";

	$list_id	= intval( mosGetParam( $_GET, 'list', 0 ) );
	$email_id	= intval( mosGetParam( $_GET, 'email', 0 ) );
	
	$query = "SELECT * FROM #__survey_force_emails WHERE id ='".$email_id."'";
	$database->SetQuery($query);
	$Send_email = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
	
	$query = "SELECT count(*) FROM `#__survey_force_users` WHERE `list_id`= '".$list_id."' AND `is_invited` = 0 ";
	$database->SetQuery($query);
	$is_invited = intval($database->LoadResult());
	
	$query = "SELECT survey_id FROM #__survey_force_listusers WHERE id = '".$list_id."'";
	$database->SetQuery($query);
	$survey_id = intval($database->LoadResult());
	
	if ($is_invited < 1) {
		echo "<script>var div_log = getObj_frame('div_invite_log_txt'); if (div_log) {"
			. "div_log.innerHTML = '".JText::_('COM_SF_ALL_USERS_FROM_THE_FOLLOWING_LIST')."';"
			. "}</script>";
		@flush();
		@ob_end_flush();
		die();
	}

	$query = "SELECT count(*) FROM #__survey_force_users WHERE list_id ='".$list_id."'";
	$database->SetQuery($query);
	$Users_count = $database->LoadResult();
	$query = "SELECT * FROM #__survey_force_users WHERE list_id ='".$list_id."' and is_invited = '0'";
	$database->SetQuery($query);
	$UsersList = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
	$Users_to_invite = count($UsersList);
	global $mosConfig_sitename;
	global $mosConfig_fromname;
	global $mosConfig_live_site;
	#$message_header 	= sprintf( 'Invitation for Survey on site ', $mosConfig_sitename );
	$message 			= $Send_email[0]->email_body;
	$subject 			= $mosConfig_sitename . ' / ' . stripslashes( $Send_email[0]->email_subject);
	$email_reply		= $Send_email[0]->email_reply;
	$ii = 1;
	
	$query = "UPDATE #__survey_force_listusers SET is_invited = '2', date_invited = '".date( 'Y-m-d H:i:s' )."' WHERE id ='".$list_id."'";
	$database->SetQuery($query);
	$database->query();
	$send_count = 0;
	$counter = 0;
	foreach ($UsersList as $user_row) {
		if ($mail_max && $send_count == $mail_max) {
			echo "<script>var st_but = getObj_frame('Start_button');"
				. "var div_log_txt = getObj_frame('div_invite_log_txt');"
				. "st_but.value = 'Resume';"
				. " if (div_log_txt) {"
				. "div_log_txt.innerHTML = '".JText::_('COM_SF_MAXIMUM_NUMBER_MAILS_EXCEED')."';"
				. "}"
				. "</script>";
			@flush();
			@ob_flush();
			die;
		}
		$user_invite_num = md5(uniqid(rand(), true));
		$link = ' <a href="'.$mosConfig_live_site . "/index.php?option=com_surveyforce&task=start_invited&survey=".$survey_id."&invite=".$user_invite_num.'">'.$mosConfig_live_site . "/index.php?option=com_surveyforce&task=start_invited&survey=".$survey_id."&invite=".$user_invite_num.'</a>';
		$user_name =  ' '.$user_row->name.' '.$user_row->lastname.' ' ;
		$message_user = str_replace('#link#', $link, $message);
		$message_user = str_replace('#name#', $user_name, $message_user);
		
		$query = "INSERT INTO #__survey_force_invitations (invite_num, user_id, inv_status) VALUES ('". $user_invite_num ."', '".$user_row->id."', 0)";
		$database->SetQuery($query);
		$database->query();
		$user_invite_id = $database->insertid();
		
		mosMail( $email_reply , $mosConfig_fromname, $user_row->email, $subject, nl2br($message_user), 1 ); //1 - in HTML mode
		
		$query = "UPDATE #__survey_force_users SET is_invited = '1', invite_id = '". $user_invite_id ."' WHERE id ='".$user_row->id."'";
		$database->SetQuery($query);
		$database->query();
		if (($mail_pause && $mail_count) && $counter == ($mail_count - 1)){
			$counter = -1;
			for($jj = $mail_pause; $jj > 1; $jj--) {
				echo "<script>var div_log = getObj_frame('div_invite_log');"
				. "var div_log_txt = getObj_frame('div_invite_log_txt');"
				. " if (div_log) {"
				. "div_log.innerHTML = '".intval(($ii - $Users_to_invite + $Users_count)*100/$Users_count)."%';"
				. "div_log.style.width = '".intval(($ii - $Users_to_invite + $Users_count)*600/$Users_count)."px';"
				. "}"
				. " if (div_log_txt) {"
				. "div_log_txt.innerHTML =  '" . ($ii - $Users_to_invite + $Users_count) . JText::_('COM_SF_USERS_INVITED_PAUSE')."$jj" .JText::_('COM_SF_SECONDS')."';"
				. "}"
				. "</script>";
				@flush();
				@ob_flush();
				sleep(1);
			}
		}
		else {
			echo "<script>var div_log = getObj_frame('div_invite_log');"
				. "var div_log_txt = getObj_frame('div_invite_log_txt');"
				. " if (div_log) {"
				. "div_log.innerHTML = '".intval(($ii - $Users_to_invite + $Users_count)*100/$Users_count)."%';"
				. "div_log.style.width = '".intval(($ii - $Users_to_invite + $Users_count)*600/$Users_count)."px';"
				. "}"
				. " if (div_log_txt) {"
				. "div_log_txt.innerHTML = '" . ($ii - $Users_to_invite + $Users_count) . JText::_('COM_SF_USERS_INVITED')."';"
				. "}"
				. "</script>";
			@flush();
			@ob_flush();
		}
		$ii++;
		$send_count++;
		$counter++;
		sleep(1);
	}
	$query = "UPDATE #__survey_force_listusers SET is_invited = '1' WHERE id ='".$list_id."'";
	$database->SetQuery($query);
	$database->query();
	echo "<script>var div_log = getObj_frame('div_invite_log'); if (div_log) {"
		. "div_log.innerHTML = '100%';"
		. "div_log.style.width = '600px';"
		. "}</script>";
	@flush();
	@ob_end_flush();

	die();
}
function SF_startRemind( $option ) {
	global $database;
	$sf_config = new mos_Survey_Force_Config();
	$mail_pause = intval($sf_config->get('sf_mail_pause'));
	$mail_count = intval($sf_config->get('sf_mail_count'));
	$mail_max = intval($sf_config->get('sf_mail_maximum'));
	ignore_user_abort(false); // STOP script if User press 'STOP' button
	set_time_limit(0);
	@ob_end_clean();
	@ob_start();
	echo "<script>function getObj_frame(name) {"
		. " if (parent.document.getElementById) { return parent.document.getElementById(name); }"
		. "	else if (parent.document.all) { return parent.document.all[name]; }"
		. "	else if (parent.document.layers) { return parent.document.layers[name]; }}</script>";

	$list_id	= intval( mosGetParam( $_GET, 'list', 0 ) );
	$email_id	= intval( mosGetParam( $_GET, 'email', 0 ) );
	
	$query = "SELECT * FROM #__survey_force_emails WHERE id ='".$email_id."'";
	$database->SetQuery($query);
	$Send_email = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
	
	$query = "SELECT is_invited, survey_id FROM #__survey_force_listusers WHERE id = '".$list_id."'";
	$database->SetQuery($query);
	$list_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
	
	$is_invited = $list_data[0]->is_invited;
	$survey_id = $list_data[0]->survey_id;

	$query = "SELECT count(a.id) FROM #__survey_force_users as a, #__survey_force_invitations as b WHERE a.list_id ='".$list_id."' and a.is_reminded = 0 and a.invite_id = b.id and b.inv_status = 0";
	$database->SetQuery($query);
	$Users_count = $database->LoadResult();
	$query = "SELECT a.* FROM #__survey_force_users as a, #__survey_force_invitations as b WHERE a.list_id ='".$list_id."' and a.is_reminded = 0 and a.invite_id = b.id and b.inv_status = 0";
	$database->SetQuery($query);
	$UsersList = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
	$Users_to_remind = count($UsersList);
	global $mosConfig_sitename;
	global $mosConfig_fromname;
	global $mosConfig_live_site;
	#$message_header 	= sprintf( 'Invitation for Survey on site ', $mosConfig_sitename );
	$message 			= $Send_email[0]->email_body;
	$subject 			= $mosConfig_sitename . ' / ' . stripslashes( $Send_email[0]->email_subject);
	$email_reply		= $Send_email[0]->email_reply;
	$ii = 1;
	
	$query = "UPDATE #__survey_force_listusers SET date_remind = '".date( 'Y-m-d H:i:s' )."' WHERE id ='".$list_id."'";
	$database->SetQuery($query);
	$database->query();
	$send_rem = 0;
	$counter = 0;
	foreach ($UsersList as $user_row) {
		if ($mail_max && $send_count == $mail_max) {
			echo "<script>var st_but = getObj_frame('Start_button');"
				. "var div_log_txt = getObj_frame('div_invite_log_txt');"
				. "st_but.value = 'Resume';"
				. " if (div_log_txt) {"
				. "div_log_txt.innerHTML = '".JText::_('COM_SF_MAXIMUM_NUMBER_MAILS_EXCEED')."';"
				. "}"
				. "</script>";
			@flush();
			@ob_flush();
			die;
		}
		$query = "SELECT invite_num FROM #__survey_force_invitations WHERE id = '".$user_row->invite_id."'";
		$database->SetQuery( $query );
		$user_invite_num = $database->LoadResult();
		$link = '<a href="'.$mosConfig_live_site . "/index.php?option=com_surveyforce&task=start_invited&survey=".$survey_id."&invite=".$user_invite_num.'">'.$mosConfig_live_site . "/index.php?option=com_surveyforce&task=start_invited&survey=".$survey_id."&invite=".$user_invite_num.'</a>';
		$user_name = ' '.$user_row->name.' '.$user_row->lastname . ' ';
		$message_user = str_replace('#link#', $link, $message);
		$message_user = str_replace('#name#', $user_name, $message_user);
		
		mosMail( $email_reply , $mosConfig_fromname, $user_row->email, $subject, nl2br($message_user), 1 ); //1 - in HTML mode
		$query = "UPDATE #__survey_force_users SET is_reminded = '1' WHERE id ='".$user_row->id."'";
		$database->SetQuery($query);
		$database->query();
		if (($mail_pause && $mail_count) && $counter == ($mail_count - 1)){
			$counter = -1;
			for($jj = $mail_pause; $jj > 1; $jj--) {
				echo "<script>var div_log = getObj_frame('div_invite_log');"
				. "var div_log_txt = getObj_frame('div_invite_log_txt');"
				. " if (div_log) {"
				. "div_log.innerHTML = '".intval(($ii)*100/$Users_count)."%';"
				. "div_log.style.width = '".intval(($ii)*600/$Users_count)."px';"
				. "}"
				. " if (div_log_txt) {"
				. "div_log_txt.innerHTML =  '" . ($ii) . JText::_('COM_SF_USERS_REMINDED_PAUSE')."$jj" .JText::_('COM_SF_SECONDS')."';"
				. "}"
				. "</script>";
				@flush();
				@ob_flush();
				sleep(1);
			}
		}
		else {
			echo "<script>var div_log = getObj_frame('div_invite_log');"
				. "var div_log_txt = getObj_frame('div_invite_log_txt');"
				. " if (div_log) {"
				. "div_log.innerHTML = '".intval(($ii)*100/$Users_count)."%';"
				. "div_log.style.width = '".intval(($ii)*600/$Users_count)."px';"
				. "}"
				. " if (div_log_txt) {"
				. "div_log_txt.innerHTML = '" . ($ii) . JText::_('COM_SF_USERS_REMINDED')."';"
				. "}"
				. "</script>";
			@flush();
			@ob_flush();
			sleep(1);
		}
		$ii ++;
		$send_rem ++;
		$counter++;
	}
	$query = "UPDATE #__survey_force_users SET is_reminded = '0' WHERE list_id ='".$list_id."'";
	$database->SetQuery($query);
	$database->query();

	echo "<script>var div_log = getObj_frame('div_invite_log'); if (div_log) {"
		. "div_log.innerHTML = '100%';"
		. "div_log.style.width = '600px';"
		. "}</script>";
	@flush();
	@ob_end_flush();

	die();
}
			

			#######################################
			###	--- ---		REPORTS 	--- --- ###

function SF_ViewReports( $option, $is_pdf = 0 ) {
	global $database, $mainframe, $mosConfig_list_limit, $front_end, $my;
	$sf_config = new mos_Survey_Force_Config( );
	
	$limit 			= intval( $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', $mosConfig_list_limit ) );
	$limitstart 	= intval( $mainframe->getUserStateFromRequest( "view{$option}limitstart", 'limitstart', 0 ) );
	$surv_id 		= intval( $mainframe->getUserStateFromRequest( "surv_id{$option}", 'surv_id', 0 ) );
	$filt_status	= intval( $mainframe->getUserStateFromRequest( "filt_status{$option}", 'filt_status', 2 ) );
	$filt_utype		= intval( $mainframe->getUserStateFromRequest( "filt_utype{$option}", 'filt_utype', 0 ) );
	$filt_ulist		= intval( $mainframe->getUserStateFromRequest( "filt_ulist{$option}", 'filt_ulist', 0 ) );	
	if ($limit == 0) $limit = 999999;
	if ( $front_end ) {
		global $SF_SESSION;
		$limit		= intval( mosGetParam( $_REQUEST, 'limit', $SF_SESSION->get('list_limit',$mainframe->getCfg('list_limit')) ) );
		if ($limit == 0) $limit = 999999;
		$SF_SESSION->set('list_limit', $limit);
		$limitstart = intval( mosGetParam( $_REQUEST, 'limitstart', $SF_SESSION->get('list_limitstart', 0) ) );
		$SF_SESSION->set('list_limitstart', $limitstart);
		$surv_id		= intval( mosGetParam( $_REQUEST, 'surv_id', $SF_SESSION->get('list_surv_id', 0) ) );
		$SF_SESSION->set('list_surv_id', $surv_id);
		
		$filt_status	= intval( mosGetParam( $_REQUEST, 'filt_status', $SF_SESSION->get('list_filt_status', 2) ) );		
		$SF_SESSION->set('list_filt_status', $filt_status);
		$filt_utype		= intval( mosGetParam( $_REQUEST, 'filt_utype', $SF_SESSION->get('list_filt_utype', 0) ) );	
		$SF_SESSION->set('list_filt_utype', $filt_utype);
		$filt_ulist		= intval( mosGetParam( $_REQUEST, 'filt_ulist', $SF_SESSION->get('list_filt_ulist', 0) ) );	
		$SF_SESSION->set('list_filt_ulist', $filt_ulist);
	}
	
	#$javascript = 'onchange="document.adminForm.submit();"';
	$javascript = 'onchange="submitbutton(\'reports\');"';
	$filter_quest = array();
	$filter_ans = array(0);
	$i = 0;
	$j = 0;
	$lists['filter_quest'] = array();
	$lists['filter_quest_ans'] = array();
	if ($surv_id) {
		$query = "SELECT count(*) FROM #__survey_force_quests WHERE  published = 1 AND sf_survey = '".$surv_id."' and id = '".$filter_quest."' and sf_qtype IN (2,3)";
		$database->setQuery( $query );
		if (!$database->LoadResult()) {
			if (isset($_REQUEST['filter_quest'])) {
				$k = 0;
				foreach ($_REQUEST['filter_quest'] as $filt_row) {
					if ($filt_row) {
						$qlists = array();
						$query = "SELECT id AS value, sf_qtext AS text"
						. "\n FROM #__survey_force_quests WHERE  published = 1 AND sf_survey = '".$surv_id."' and sf_qtype IN (2,3)"
						. "\n ORDER BY ordering"
						;
						$database->setQuery( $query );
						$quests33 = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
						$ji =0;

						while ($ji < count($quests33)) {
							$quests33[$ji]->text = strip_tags($quests33[$ji]->text);
							if (strlen($quests33[$ji]->text) > 55)
								$quests33[$ji]->text = substr($quests33[$ji]->text, 0, 55).'...';
							$quests33[$ji]->text = $quests33[$ji]->value . ' - ' . $quests33[$ji]->text;

							$ji ++;
						}

						$qlists[] = mosHTML::makeOption( '0', JText::_('COM_SF_SELECT_QUESTION') );
						$qlists = @array_merge( $qlists, $quests33 );
						$qlist = mosHTML::selectList( $qlists,'filter_quest[]', 'class="text_area" size="1" '. $javascript, 'value', 'text', $filt_row );
						$filter_quest[$i] = $filt_row;
						$lists['filter_quest'][$i] = $qlist;
						$sel_ans = array(0);
						if (isset($_REQUEST['filter_ans'][$filt_row]) && $_REQUEST['filter_ans'][$filt_row]) {
							$sel_ans = $_REQUEST['filter_ans'][$filt_row];
						}
						$sel_ans2 = null;
						if (is_array($sel_ans) && count($sel_ans))
						foreach($sel_ans as $sel_an) {
							$tmp = new stdClass;
							$tmp->value = $sel_an;
							$sel_ans2[] = $tmp;
						}
						
						$query = "SELECT distinct a.answer AS value, b.ftext AS text"
						. "\n FROM #__survey_force_user_answers as a, #__survey_force_fields as b, #__survey_force_quests as c WHERE c.published = 1 AND a.quest_id = '".$filt_row."' and a.survey_id = '".$surv_id."' and a.quest_id = c.id and c.sf_qtype IN (2,3) and a.answer <> 0 and a.answer = b.id"
						;
						$database->setQuery( $query );
						$alists = array();
						$alists[] = mosHTML::makeOption( '0', '- Select Answer -' );
						$alists = @array_merge( $alists, ($database->LoadObjectList() == null? array(): $database->LoadObjectList()) );
						$alist = mosHTML::selectList( $alists,"filter_ans[$filt_row][]", 'class="text_area" size="3" multiple="multiple" '. $javascript, 'value', 'text', $sel_ans2 );
						$filter_ans[$i] = implode(',', $sel_ans);
						$lists['filter_quest_ans'][$i] = $alist;
						$i ++;
						$k ++;
					}
				}
			}
	$qlists = array();
	$query = "SELECT id AS value, sf_qtext AS text"
	. "\n FROM #__survey_force_quests WHERE  published = 1 AND sf_survey = '".$surv_id."' and sf_qtype IN (2,3)"
	. "\n ORDER BY ordering, id "
	;
	$database->setQuery( $query );

	$quests34 = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());

	$ji =0;
	while ($ji < count($quests34)) {
		$quests34[$ji]->text = strip_tags($quests34[$ji]->text);
		if (strlen($quests34[$ji]->text) > 55)
		$quests34[$ji]->text = substr($quests34[$ji]->text, 0, 55).'...';
		$quests34[$ji]->text = $quests34[$ji]->value . ' - ' . $quests34[$ji]->text;
		$ji ++;
	}

	$qlists[] = mosHTML::makeOption( '0', JText::_('COM_SF_SELECT_QUESTION') );
	$qlists = @array_merge( $qlists, $quests34 );
	$qlist = mosHTML::selectList( $qlists,'filter_quest[]', 'class="text_area" size="1" '. $javascript, 'value', 'text', '0' ); 
	$lists['filter_quest'][$i] = $qlist;
	$lists['filter_quest_ans'][$i] = '';
		}
	}

	if (($filt_utype - 1) != 2) {$filt_ulist = 0;}
	$query = "SELECT count(sf_ust.id) FROM #__survey_force_user_starts as sf_ust, #__survey_force_survs as sf_s"
	. "\n WHERE sf_ust.survey_id = sf_s.id"
	. ( $surv_id ? "\n and sf_s.id = $surv_id" : '' )
	. ( $filt_status ? "\n and sf_ust.is_complete = '".($filt_status - 1)."'" : '' )
	. ( $filt_utype ? "\n and sf_ust.usertype = '".($filt_utype -1)."'" : '' );
	$database->setQuery( $query );
	
	$total = $database->loadResult();

	

	// get the subset (based on limits) of required records
	$query = "SELECT sf_ust.*, sf_s.sf_name as survey_name, u.username reg_username, u.name reg_name, u.email reg_email,"
	. "\n sf_u.name as inv_name, sf_u.lastname as inv_lastname, sf_u.email as inv_email"
	. "\n FROM (#__survey_force_user_starts as sf_ust, #__survey_force_survs as sf_s";
	$r = 0;
	foreach ($filter_ans as $filt_ans) {
		$query .= ($filt_ans != '0' ? ", #__survey_force_user_answers as sf_ans".$r : '');
		$r ++;
	}
	
	$query .= ")"
	. "\n LEFT JOIN #__users as u ON u.id = sf_ust.user_id and sf_ust.usertype=1"
	. "\n LEFT JOIN #__survey_force_users as sf_u ON sf_u.id = sf_ust.user_id and sf_ust.usertype=2"
	. "\n WHERE sf_ust.survey_id = sf_s.id"
	. ( $surv_id ? "\n and sf_s.id = $surv_id" : '' )
	. ( $front_end && $my->usertype != 'Super Administrator'? " AND sf_s.sf_author = '{$my->id}' ": ' ')
	. ( $filt_status ? "\n and sf_ust.is_complete = '".($filt_status - 1)."'" : '' )
	. ( $filt_utype ? "\n and sf_ust.usertype = '".($filt_utype -1)."'" : '' )
	. ( $filt_ulist ? "\n and sf_u.list_id = '".($filt_ulist)."'" : '' );
	$r = 0;
	foreach ($filter_ans as $filt_ans) {
		$query .= ( $filt_ans != '0' ? "\n and sf_ans".$r.".start_id = sf_ust.id and sf_ans".$r.".answer IN (".($filt_ans).")" : '' );
		$r ++;
	
	}	
	$query .= "\n ORDER BY sf_ust.sf_time DESC"	
	;	
	$database->SetQuery($query);

	$rows = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
	$total = count($rows);
	$rows = @array_slice($rows, $limitstart, $limit);
	
	require_once( $GLOBALS['mosConfig_absolute_path'] . '/administrator/includes/pageNavigation.php' );
	if ($front_end)
		$pageNav = new SFPageNav( $total, $limitstart, $limit  );
	else
		$pageNav = new mosPageNav( $total, $limitstart, ($limit==999999?0:$limit) );

	$query = "SELECT id AS value, sf_name AS text"
	. "\n FROM #__survey_force_survs"
	.( $front_end && $my->usertype != 'Super Administrator'? " WHERE sf_author = '{$my->id}' ": ' ')
	. "\n ORDER BY sf_name"
	;
	$database->setQuery( $query );
	
	$surveys[] = mosHTML::makeOption( '0', JText::_('COM_SF_S_SELECT_SURVEY') );
	$surveys = @array_merge( $surveys, ($database->LoadObjectList() == null? array(): $database->LoadObjectList()) );
	$survey = mosHTML::selectList( $surveys,'surv_id', 'class="text_area" size="1" '. $javascript, 'value', 'text', $surv_id ); 
	$lists['survey'] = $survey;
	
	$statuses1 = array();
	$statuses1[0]->value = 2; $statuses1[0]->text = JText::_('COM_SF_COMPLETED');
	$statuses1[1]->value = 1; $statuses1[1]->text = JText::_('COM_SF_NOT_COMPLETED');
	$statuses[] = mosHTML::makeOption( '0', JText::_('COM_SF_SELECT_STATUS') );
	$statuses = @array_merge( $statuses,$statuses1 );
	$f_status = mosHTML::selectList( $statuses,'filt_status', 'class="text_area" size="1" '. $javascript, 'value', 'text', $filt_status );
	$lists['filt_status'] = $f_status;
	
	$u_types1 = array();
	if (!$sf_config->get('sf_enable_jomsocial_integration')) { 
		$u_types1[0]->value = 3; $u_types1[0]->text = JText::_('COM_SF_INVITED_USERS');
	}
	$u_types1[1]->value = 2; $u_types1[1]->text = JText::_('COM_SF_REGISTERED_USERS');
	$u_types1[2]->value = 1; $u_types1[2]->text = JText::_('COM_SF_GUESTS');
	$u_types[] = mosHTML::makeOption( '0', JText::_('COM_SF_SELECT_USERTYPE') );
	$u_types = @array_merge( $u_types,$u_types1 );
	$f_utypes = mosHTML::selectList( $u_types,'filt_utype', 'class="text_area" size="1" '. $javascript, 'value', 'text', $filt_utype );
	$lists['filt_utype'] = $f_utypes;
	
	$lists['filt_ulist'] = '';
	if (($filt_utype - 1) == 2) {
		$query = "SELECT id AS value, listname AS text"
		. "\n FROM #__survey_force_listusers"
		. "\n ORDER BY listname"
		;
		$database->setQuery( $query );
		$ulists[] = mosHTML::makeOption( '0', JText::_('COM_SF_SELECT_USERLIST') );
		$ulists = @array_merge( $ulists, ($database->LoadObjectList() == null? array(): $database->LoadObjectList()) );
		$ulist = mosHTML::selectList( $ulists,'filt_ulist', 'class="text_area" size="1" '. $javascript, 'value', 'text', $filt_ulist ); 
		$lists['filt_ulist'] = $ulist;
	}
	if ( !$front_end ) {
		if ($is_pdf) {
			SF_PrintReports( $rows );
		} else {
			survey_force_adm_html::SF_ViewReports( $rows, $lists, $pageNav, $option);
		}
	}
	else {
		if ($is_pdf) {
			SF_PrintReports( $rows );
		} else {
			survey_force_front_html::SF_ViewReports( $rows, $lists, $pageNav, $option);
		}
	}
}


//PARTE NUEVA

function getTextResponse($start,$id, $surv_id){
	global $database;
	$query = "SELECT f.ftext as text FROM #__survey_force_quests q, #__survey_force_user_answers ua, #__survey_force_fields f WHERE f.id = ua.answer AND ua.quest_id = q.id AND q.sf_survey = '".$surv_id."' AND ua.start_id = '".$start."' AND q.id='".$id."'";
	 
	$database->setQuery($query);
	$answers = $database->LoadObjectList();
	$answer = $answers[0];
	$text = $answer->text;
	if($option != '' && $text != $option ){
		return '';
	}else{
		return $text;
	}
}

function getTextResponse2($start_id, $data, $surv_id=1,$test=0){
	global $database;
	//obtengo las preguntas
	$query = "SELECT q.id, q.sf_qtype FROM #__survey_force_quests q WHERE q.sf_survey = '".$surv_id."' ORDER BY q.ordering";
	$database->setQuery($query);
	$questions = $database->LoadObjectList();
	foreach($questions as $question){
		//dependiendo de la pregunta
		switch ($question->sf_qtype){
			case "1":
				$query = 'SELECT f.id,  f.ordering as ordering1, s.ordering as ordering2, 
							IFNULL((SELECT s2.stext FROM #__survey_force_user_answers ua,  #__survey_force_scales s2
 							WHERE f.id = ua.answer AND ua.start_id = "'.$start_id.'" AND ua.ans_field = s2.id AND s.id = s2.id  ) , "") as text
						  FROM #__survey_force_fields f, #__survey_force_scales s
						  WHERE f.quest_id = "'.$question->id.'" AND s.quest_id = "'.$question->id.'" ORDER BY f.ordering, s.ordering';
				$database->setQuery($query);
				$answers = $database->LoadObjectList();
				foreach($answers as $answer){
					$data["q".$question->id."_".$answer->ordering1."_".$answer->ordering2] = $answer->text;
					if($test){
						echo "[general.q".$question->id."_".$answer->ordering1."_".$answer->ordering2."],";
					}
				}
				break;
			case "2": case "3":
				$query = 'SELECT f.id,  f.ordering, 
							IFNULL((SELECT f2.ftext FROM #__survey_force_fields f2, #__survey_force_user_answers ua WHERE
							ua.quest_id = f.quest_id AND f2.id = ua.answer AND ua.start_id = "'.$start_id.'" AND f2.id = f.id  ) , "") as text
						  FROM #__survey_force_fields f
						  WHERE f.quest_id = "'.$question->id.'" ORDER BY f.ordering';
				$database->setQuery($query);
				$answers = $database->LoadObjectList();
				foreach($answers as $answer){
					$data["q".$question->id."_".$answer->ordering] = $answer->text;
					if($test){
						echo "[general.q".$question->id."_".$answer->ordering."],";
					}
				}
				break;
			case "9":
				$query = 'SELECT f.id, f.ordering as ordering1, f2.ordering as ordering2, 
						IF( (SELECT ua.id FROM jos_survey_force_user_answers ua 
						WHERE ua.start_id = "'.$start_id.'" AND ua.answer = f.id AND ua.ans_field = f2.id) , f2.ftext ,"" ) as text
						FROM jos_survey_force_fields f, jos_survey_force_fields f2 
						WHERE f.quest_id = "'.$question->id.'" AND f2.quest_id = "'.$question->id.'" AND f.is_main = 1 AND f2.is_main = 0
						ORDER BY f.ordering, f2.ordering';
				$database->setQuery($query);
				$answers = $database->LoadObjectList();
				foreach($answers as $answer){
					$data["q".$question->id."_".$answer->ordering1."_".$answer->ordering2] = $answer->text;
					if($test){
						echo "[general.q".$question->id."_".$answer->ordering1."_".$answer->ordering2."],";
					}
				}
				break;
		}
	}
	return $data;
}

function SF_generateExcel( $option, $is_pdf = 0 ) {

	global $database, $mainframe, $mosConfig_list_limit, $front_end, $my, $mosConfig_mailfrom, $mosConfig_sitename;
	$sf_config = new mos_Survey_Force_Config( );
	
	$limit 			= intval( $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', $mosConfig_list_limit ) );
	$limitstart 	= intval( $mainframe->getUserStateFromRequest( "view{$option}limitstart", 'limitstart', 0 ) );
	$surv_id 		= intval( $mainframe->getUserStateFromRequest( "surv_id{$option}", 'surv_id', 1 ) );
	$filt_status	= intval( $mainframe->getUserStateFromRequest( "filt_status{$option}", 'filt_status', 2 ) );
	$filt_utype		= intval( $mainframe->getUserStateFromRequest( "filt_utype{$option}", 'filt_utype', 0 ) );
	$filt_ulist		= intval( $mainframe->getUserStateFromRequest( "filt_ulist{$option}", 'filt_ulist', 0 ) );
	if ($limit == 0) $limit = 999999;
	
	$javascript = 'onchange="submitbutton(\'reports\');"';
	$filter_quest = array();
	$filter_ans = array(0);
	$i = 0;
	$j = 0;
	$lists['filter_quest'] = array();
	$lists['filter_quest_ans'] = array();
	if ($surv_id) {
		$query = "SELECT count(*) FROM #__survey_force_quests WHERE  published = 1 AND sf_survey = '".$surv_id."' and id = '".$filter_quest."' and sf_qtype IN (2,3)";
		$database->setQuery( $query );
		if (!$database->LoadResult()) {
			if (isset($_REQUEST['filter_quest'])) {
				$k = 0;
				foreach ($_REQUEST['filter_quest'] as $filt_row) {
					if ($filt_row) {
						$qlists = array();
						$query = "SELECT id AS value, sf_qtext AS text"
								. "\n FROM #__survey_force_quests WHERE  published = 1 AND sf_survey = '".$surv_id."' and sf_qtype IN (2,3)"
										. "\n ORDER BY ordering"
												;
												$database->setQuery( $query );
												$quests33 = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
												$ji =0;

												while ($ji < count($quests33)) {
													$quests33[$ji]->text = strip_tags($quests33[$ji]->text);
													if (strlen($quests33[$ji]->text) > 55)
														$quests33[$ji]->text = substr($quests33[$ji]->text, 0, 55).'...';
													$quests33[$ji]->text = $quests33[$ji]->value . ' - ' . $quests33[$ji]->text;

													$ji ++;
												}

												$qlists[] = mosHTML::makeOption( '0', JText::_('COM_SF_SELECT_QUESTION') );
												$qlists = @array_merge( $qlists, $quests33 );
												$qlist = mosHTML::selectList( $qlists,'filter_quest[]', 'class="text_area" size="1" '. $javascript, 'value', 'text', $filt_row );
												$filter_quest[$i] = $filt_row;
												$lists['filter_quest'][$i] = $qlist;
												$sel_ans = array(0);
												if (isset($_REQUEST['filter_ans'][$filt_row]) && $_REQUEST['filter_ans'][$filt_row]) {
													$sel_ans = $_REQUEST['filter_ans'][$filt_row];
												}
												$sel_ans2 = null;
												if (is_array($sel_ans) && count($sel_ans))
													foreach($sel_ans as $sel_an) {
													$tmp = new stdClass;
													$tmp->value = $sel_an;
													$sel_ans2[] = $tmp;
												}

												$query = "SELECT distinct a.answer AS value, b.ftext AS text"
														. "\n FROM #__survey_force_user_answers as a, #__survey_force_fields as b, #__survey_force_quests as c WHERE c.published = 1 AND a.quest_id = '".$filt_row."' and a.survey_id = '".$surv_id."' and a.quest_id = c.id and c.sf_qtype IN (2,3) and a.answer <> 0 and a.answer = b.id"
																;
																$database->setQuery( $query );
																$alists = array();
																$alists[] = mosHTML::makeOption( '0', '- Select Answer -' );
																$alists = @array_merge( $alists, ($database->LoadObjectList() == null? array(): $database->LoadObjectList()) );
																$alist = mosHTML::selectList( $alists,"filter_ans[$filt_row][]", 'class="text_area" size="3" multiple="multiple" '. $javascript, 'value', 'text', $sel_ans2 );
																$filter_ans[$i] = implode(',', $sel_ans);
																$lists['filter_quest_ans'][$i] = $alist;
																$i ++;
																$k ++;
					}
				}
			}
			$qlists = array();
			$query = "SELECT id AS value, sf_qtext AS text"
					. "\n FROM #__survey_force_quests WHERE  published = 1 AND sf_survey = '".$surv_id."' and sf_qtype IN (2,3)"
							. "\n ORDER BY ordering, id "
									;
									$database->setQuery( $query );

									$quests34 = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());

									$ji =0;
									while ($ji < count($quests34)) {
										$quests34[$ji]->text = strip_tags($quests34[$ji]->text);
										if (strlen($quests34[$ji]->text) > 55)
											$quests34[$ji]->text = substr($quests34[$ji]->text, 0, 55).'...';
										$quests34[$ji]->text = $quests34[$ji]->value . ' - ' . $quests34[$ji]->text;
										$ji ++;
									}

									$qlists[] = mosHTML::makeOption( '0', JText::_('COM_SF_SELECT_QUESTION') );
									$qlists = @array_merge( $qlists, $quests34 );
									$qlist = mosHTML::selectList( $qlists,'filter_quest[]', 'class="text_area" size="1" '. $javascript, 'value', 'text', '0' );
									$lists['filter_quest'][$i] = $qlist;
									$lists['filter_quest_ans'][$i] = '';
		}
	}
	
	if (($filt_utype - 1) != 2) {$filt_ulist = 0;}
	$query = "SELECT count(sf_ust.id) FROM #__survey_force_user_starts as sf_ust, #__survey_force_survs as sf_s"
			. "\n WHERE sf_ust.survey_id = sf_s.id"
					. ( $surv_id ? "\n and sf_s.id = $surv_id" : '' )
					. ( $filt_status ? "\n and sf_ust.is_complete = '".($filt_status - 1)."'" : '' )
					. ( $filt_utype ? "\n and sf_ust.usertype = '".($filt_utype -1)."'" : '' );
	$database->setQuery( $query );

	$total = $database->loadResult();
	
	
	
	// get the subset (based on limits) of required records
	$query = "SELECT sf_ust.*, sf_s.sf_name as survey_name, u.username reg_username, u.name reg_name, u.email reg_email,"
			. "\n sf_u.name as inv_name, sf_u.lastname as inv_lastname, sf_u.email as inv_email"
					. "\n FROM (#__survey_force_user_starts as sf_ust, #__survey_force_survs as sf_s";
	$r = 0;
	foreach ($filter_ans as $filt_ans) {
		$query .= ($filt_ans != '0' ? ", #__survey_force_user_answers as sf_ans".$r : '');
		$r ++;
	}
	
	$query .= ")"
			. "\n LEFT JOIN #__users as u ON u.id = sf_ust.user_id and sf_ust.usertype=1"
					. "\n LEFT JOIN #__survey_force_users as sf_u ON sf_u.id = sf_ust.user_id and sf_ust.usertype=2"
							. "\n WHERE sf_ust.survey_id = sf_s.id"
									. ( $surv_id ? "\n and sf_s.id = $surv_id" : '' )
									. ( $front_end && $my->usertype != 'Super Administrator'? " AND sf_s.sf_author = '{$my->id}' ": ' ')
									. ( $filt_status ? "\n and sf_ust.is_complete = '".($filt_status - 1)."'" : '' )
									. ( $filt_utype ? "\n and sf_ust.usertype = '".($filt_utype -1)."'" : '' )
									. ( $filt_ulist ? "\n and sf_u.list_id = '".($filt_ulist)."'" : '' );
	$r = 0;
	foreach ($filter_ans as $filt_ans) {
		$query .= ( $filt_ans != '0' ? "\n and sf_ans".$r.".start_id = sf_ust.id and sf_ans".$r.".answer IN (".($filt_ans).")" : '' );
		$r ++;

	}
			$query .= "\n ORDER BY sf_ust.sf_time DESC"
					;
					$database->SetQuery($query);
	
					$rows = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
					$total = count($rows);
					$rows = @array_slice($rows, $limitstart, $limit);
	
					require_once( $GLOBALS['mosConfig_absolute_path'] . '/administrator/includes/pageNavigation.php' );
					if ($front_end)
						$pageNav = new SFPageNav( $total, $limitstart, $limit  );
					else
						$pageNav = new mosPageNav( $total, $limitstart, ($limit==999999?0:$limit) );
	
					$query = "SELECT id AS value, sf_name AS text"
							. "\n FROM #__survey_force_survs"
									.( $front_end && $my->usertype != 'Super Administrator'? " WHERE sf_author = '{$my->id}' ": ' ')
									. "\n ORDER BY sf_name"
											;
											$database->setQuery( $query );
	
											$surveys[] = mosHTML::makeOption( '0', JText::_('COM_SF_S_SELECT_SURVEY') );
											$surveys = @array_merge( $surveys, ($database->LoadObjectList() == null? array(): $database->LoadObjectList()) );
											$survey = mosHTML::selectList( $surveys,'surv_id', 'class="text_area" size="1" '. $javascript, 'value', 'text', $surv_id );
											$lists['survey'] = $survey;
	
											$statuses1 = array();
											$statuses1[0]->value = 2; $statuses1[0]->text = JText::_('COM_SF_COMPLETED');
											$statuses1[1]->value = 1; $statuses1[1]->text = JText::_('COM_SF_NOT_COMPLETED');
											$statuses[] = mosHTML::makeOption( '0', JText::_('COM_SF_SELECT_STATUS') );
											$statuses = @array_merge( $statuses,$statuses1 );
											$f_status = mosHTML::selectList( $statuses,'filt_status', 'class="text_area" size="1" '. $javascript, 'value', 'text', $filt_status );
											$lists['filt_status'] = $f_status;
	
											$u_types1 = array();
											if (!$sf_config->get('sf_enable_jomsocial_integration')) {
												$u_types1[0]->value = 3; $u_types1[0]->text = JText::_('COM_SF_INVITED_USERS');
											}
											$u_types1[1]->value = 2; $u_types1[1]->text = JText::_('COM_SF_REGISTERED_USERS');
											$u_types1[2]->value = 1; $u_types1[2]->text = JText::_('COM_SF_GUESTS');
											$u_types[] = mosHTML::makeOption( '0', JText::_('COM_SF_SELECT_USERTYPE') );
											$u_types = @array_merge( $u_types,$u_types1 );
											$f_utypes = mosHTML::selectList( $u_types,'filt_utype', 'class="text_area" size="1" '. $javascript, 'value', 'text', $filt_utype );
											$lists['filt_utype'] = $f_utypes;
	
											$lists['filt_ulist'] = '';
											if (($filt_utype - 1) == 2) {
												$query = "SELECT id AS value, listname AS text"
														. "\n FROM #__survey_force_listusers"
																. "\n ORDER BY listname"
																		;
																		$database->setQuery( $query );
																		$ulists[] = mosHTML::makeOption( '0', JText::_('COM_SF_SELECT_USERLIST') );
																		$ulists = @array_merge( $ulists, ($database->LoadObjectList() == null? array(): $database->LoadObjectList()) );
																		$ulist = mosHTML::selectList( $ulists,'filt_ulist', 'class="text_area" size="1" '. $javascript, 'value', 'text', $filt_ulist );
																		$lists['filt_ulist'] = $ulist;
											}
											

	
	
	

	
	if($_REQUEST["send"]){
		//print_r($_REQUEST[cid]);die;
	  if(count($_REQUEST[cid])){
		$cadena = "";
	  	foreach($_REQUEST[cid] as $start){
	  		$cadena .= $start.",";
	  	}
		$cadena = substr($cadena, 0, -1);
		$lists["surveySend"] = "<span style='color:#22AA22;'>Report was sent to Admin's Email!</span>";

		//Envio reporte a administrador
		$query = "SELECT id, survey_id, sf_time, sf_ip_address FROM #__survey_force_user_starts WHERE is_complete = 1 and survey_id = 1 AND id IN ( ".$cadena." )";
		$database->setQuery( $query );
		$surveys = $database->LoadObjectList();
		
		$subject = 'Excel Report';
		$message = 'Excel Report';
		$dVar=new JConfig();
		$email = $dVar->mailfrom;
		
		include_once(JPATH_ROOT.'/libraries/tbs/tbs_class.php');
		include_once(JPATH_ROOT.'/libraries/tbs/tbs_plugin_opentbs.php');

		$TBS = new clsTinyButStrong;
		$TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);
		$template = JPATH_ROOT."/doc/report.xlsx";
		$TBS->LoadTemplate($template);
		
		
		$data = array();
		foreach($surveys as $survey){
			
			$row = array('id'=>$survey->id ,
						'survey'=>$survey->survey_id, 
						'start'=>$survey->sf_time, 
						'ip'=>$survey->sf_ip_address,
						'q349'=>getTextResponse($survey->id,349),
						'q351'=>getTextResponse($survey->id,351),
						'q352'=>getTextResponse($survey->id,352)	
					);
			$row = getTextResponse2($survey->id, $row);
			
			$data[] = $row;
		}
		
		//print_r($data);die;
		
		$TBS->MergeBlock('user', $data);
		$TBS->PlugIn(OPENTBS_SELECT_SHEET, "0. General company charact.");
		$TBS->MergeBlock('general', $data);
		$TBS->PlugIn(OPENTBS_SELECT_SHEET, "5. Strategic");
		$TBS->MergeBlock('general', $data);
		$TBS->PlugIn(OPENTBS_SELECT_SHEET, "4. Organisation");
		$TBS->MergeBlock('general', $data);
		$TBS->PlugIn(OPENTBS_SELECT_SHEET, "3. Systems");
		$TBS->MergeBlock('general', $data);
		$TBS->PlugIn(OPENTBS_SELECT_SHEET, "2. Processen");
		$TBS->MergeBlock('general', $data);
		$TBS->PlugIn(OPENTBS_SELECT_SHEET, "1. Key elements");
		$TBS->MergeBlock('general', $data);

		$attachment = JPATH_ROOT."/tmp/Report_".time().".xlsx";
		$TBS->Show(OPENTBS_FILE, $attachment);

		//mosMail( $mosConfig_mailfrom,  $mosConfig_sitename, 'jfalconavila@hotmail.com' , $subject, $message, 1, null,null,$attachment);
		mosMail( $mosConfig_mailfrom,  $mosConfig_sitename, $email , $subject, $message, 1, null,null,$attachment);
		
	  }else{
	  	$lists["surveySend"] = "<span style='color:#AA2222;'>Select surveys first!</span>";
	  }
	}

	survey_force_adm_html::SF_generateExcel( $rows, $lists, $pageNav, $option);

}


function SF_generateExcelNL( $option, $is_pdf = 0) {

	global $database, $mainframe, $mosConfig_list_limit, $front_end, $my, $mosConfig_mailfrom, $mosConfig_sitename;
	$sf_config = new mos_Survey_Force_Config( );

	$limit 			= intval( $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', $mosConfig_list_limit ) );
	$limitstart 	= intval( $mainframe->getUserStateFromRequest( "view{$option}limitstart", 'limitstart', 0 ) );
	$surv_id 		= intval( $mainframe->getUserStateFromRequest( "surv_id{$option}", 'surv_id', 8 ) );
	$filt_status	= intval( $mainframe->getUserStateFromRequest( "filt_status{$option}", 'filt_status', 2 ) );
	$filt_utype		= intval( $mainframe->getUserStateFromRequest( "filt_utype{$option}", 'filt_utype', 0 ) );
	$filt_ulist		= intval( $mainframe->getUserStateFromRequest( "filt_ulist{$option}", 'filt_ulist', 0 ) );
	if ($limit == 0) $limit = 999999;

	$javascript = 'onchange="submitbutton(\'reports\');"';
	$filter_quest = array();
	$filter_ans = array(0);
	$i = 0;
	$j = 0;
	$lists['filter_quest'] = array();
	$lists['filter_quest_ans'] = array();
	if ($surv_id) {
		$query = "SELECT count(*) FROM #__survey_force_quests WHERE  published = 1 AND sf_survey = '".$surv_id."' and id = '".$filter_quest."' and sf_qtype IN (2,3)";
		$database->setQuery( $query );
		if (!$database->LoadResult()) {
			if (isset($_REQUEST['filter_quest'])) {
				$k = 0;
				foreach ($_REQUEST['filter_quest'] as $filt_row) {
					if ($filt_row) {
						$qlists = array();
						$query = "SELECT id AS value, sf_qtext AS text"
								. "\n FROM #__survey_force_quests WHERE  published = 1 AND sf_survey = '".$surv_id."' and sf_qtype IN (2,3)"
										. "\n ORDER BY ordering"
												;
												$database->setQuery( $query );
												$quests33 = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
												$ji =0;

												while ($ji < count($quests33)) {
													$quests33[$ji]->text = strip_tags($quests33[$ji]->text);
													if (strlen($quests33[$ji]->text) > 55)
														$quests33[$ji]->text = substr($quests33[$ji]->text, 0, 55).'...';
													$quests33[$ji]->text = $quests33[$ji]->value . ' - ' . $quests33[$ji]->text;

													$ji ++;
												}

												$qlists[] = mosHTML::makeOption( '0', JText::_('COM_SF_SELECT_QUESTION') );
												$qlists = @array_merge( $qlists, $quests33 );
												$qlist = mosHTML::selectList( $qlists,'filter_quest[]', 'class="text_area" size="1" '. $javascript, 'value', 'text', $filt_row );
												$filter_quest[$i] = $filt_row;
												$lists['filter_quest'][$i] = $qlist;
												$sel_ans = array(0);
												if (isset($_REQUEST['filter_ans'][$filt_row]) && $_REQUEST['filter_ans'][$filt_row]) {
													$sel_ans = $_REQUEST['filter_ans'][$filt_row];
												}
												$sel_ans2 = null;
												if (is_array($sel_ans) && count($sel_ans))
													foreach($sel_ans as $sel_an) {
													$tmp = new stdClass;
													$tmp->value = $sel_an;
													$sel_ans2[] = $tmp;
												}

												$query = "SELECT distinct a.answer AS value, b.ftext AS text"
														. "\n FROM #__survey_force_user_answers as a, #__survey_force_fields as b, #__survey_force_quests as c WHERE c.published = 1 AND a.quest_id = '".$filt_row."' and a.survey_id = '".$surv_id."' and a.quest_id = c.id and c.sf_qtype IN (2,3) and a.answer <> 0 and a.answer = b.id"
																;
																$database->setQuery( $query );
																$alists = array();
																$alists[] = mosHTML::makeOption( '0', '- Select Answer -' );
																$alists = @array_merge( $alists, ($database->LoadObjectList() == null? array(): $database->LoadObjectList()) );
																$alist = mosHTML::selectList( $alists,"filter_ans[$filt_row][]", 'class="text_area" size="3" multiple="multiple" '. $javascript, 'value', 'text', $sel_ans2 );
																$filter_ans[$i] = implode(',', $sel_ans);
																$lists['filter_quest_ans'][$i] = $alist;
																$i ++;
																$k ++;
					}
				}
			}
			$qlists = array();
			$query = "SELECT id AS value, sf_qtext AS text"
					. "\n FROM #__survey_force_quests WHERE  published = 1 AND sf_survey = '".$surv_id."' and sf_qtype IN (2,3)"
							. "\n ORDER BY ordering, id "
									;
									$database->setQuery( $query );

									$quests34 = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());

									$ji =0;
									while ($ji < count($quests34)) {
										$quests34[$ji]->text = strip_tags($quests34[$ji]->text);
										if (strlen($quests34[$ji]->text) > 55)
											$quests34[$ji]->text = substr($quests34[$ji]->text, 0, 55).'...';
										$quests34[$ji]->text = $quests34[$ji]->value . ' - ' . $quests34[$ji]->text;
										$ji ++;
									}

									$qlists[] = mosHTML::makeOption( '0', JText::_('COM_SF_SELECT_QUESTION') );
									$qlists = @array_merge( $qlists, $quests34 );
									$qlist = mosHTML::selectList( $qlists,'filter_quest[]', 'class="text_area" size="1" '. $javascript, 'value', 'text', '0' );
									$lists['filter_quest'][$i] = $qlist;
									$lists['filter_quest_ans'][$i] = '';
		}
	}

	if (($filt_utype - 1) != 2) {$filt_ulist = 0;}
	$query = "SELECT count(sf_ust.id) FROM #__survey_force_user_starts as sf_ust, #__survey_force_survs as sf_s"
			. "\n WHERE sf_ust.survey_id = sf_s.id"
					. ( $surv_id ? "\n and sf_s.id = $surv_id" : '' )
					. ( $filt_status ? "\n and sf_ust.is_complete = '".($filt_status - 1)."'" : '' )
					. ( $filt_utype ? "\n and sf_ust.usertype = '".($filt_utype -1)."'" : '' );
	$database->setQuery( $query );

	$total = $database->loadResult();



	// get the subset (based on limits) of required records
	$query = "SELECT sf_ust.*, sf_s.sf_name as survey_name, u.username reg_username, u.name reg_name, u.email reg_email,"
			. "\n sf_u.name as inv_name, sf_u.lastname as inv_lastname, sf_u.email as inv_email"
					. "\n FROM (#__survey_force_user_starts as sf_ust, #__survey_force_survs as sf_s";
	$r = 0;
	foreach ($filter_ans as $filt_ans) {
		$query .= ($filt_ans != '0' ? ", #__survey_force_user_answers as sf_ans".$r : '');
		$r ++;
	}

	$query .= ")"
			. "\n LEFT JOIN #__users as u ON u.id = sf_ust.user_id and sf_ust.usertype=1"
					. "\n LEFT JOIN #__survey_force_users as sf_u ON sf_u.id = sf_ust.user_id and sf_ust.usertype=2"
							. "\n WHERE sf_ust.survey_id = sf_s.id"
									. ( $surv_id ? "\n and sf_s.id = $surv_id" : '' )
									. ( $front_end && $my->usertype != 'Super Administrator'? " AND sf_s.sf_author = '{$my->id}' ": ' ')
									. ( $filt_status ? "\n and sf_ust.is_complete = '".($filt_status - 1)."'" : '' )
									. ( $filt_utype ? "\n and sf_ust.usertype = '".($filt_utype -1)."'" : '' )
									. ( $filt_ulist ? "\n and sf_u.list_id = '".($filt_ulist)."'" : '' );
	$r = 0;
	foreach ($filter_ans as $filt_ans) {
		$query .= ( $filt_ans != '0' ? "\n and sf_ans".$r.".start_id = sf_ust.id and sf_ans".$r.".answer IN (".($filt_ans).")" : '' );
		$r ++;

	}
	$query .= "\n ORDER BY sf_ust.sf_time DESC"
			;
			$database->SetQuery($query);

			$rows = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
			$total = count($rows);
			$rows = @array_slice($rows, $limitstart, $limit);

			require_once( $GLOBALS['mosConfig_absolute_path'] . '/administrator/includes/pageNavigation.php' );
			if ($front_end)
				$pageNav = new SFPageNav( $total, $limitstart, $limit  );
			else
				$pageNav = new mosPageNav( $total, $limitstart, ($limit==999999?0:$limit) );

			$query = "SELECT id AS value, sf_name AS text"
					. "\n FROM #__survey_force_survs"
							.( $front_end && $my->usertype != 'Super Administrator'? " WHERE sf_author = '{$my->id}' ": ' ')
							. "\n ORDER BY sf_name"
									;
									$database->setQuery( $query );

									$surveys[] = mosHTML::makeOption( '0', JText::_('COM_SF_S_SELECT_SURVEY') );
									$surveys = @array_merge( $surveys, ($database->LoadObjectList() == null? array(): $database->LoadObjectList()) );
									$survey = mosHTML::selectList( $surveys,'surv_id', 'class="text_area" size="1" '. $javascript, 'value', 'text', $surv_id );
									$lists['survey'] = $survey;

									$statuses1 = array();
									$statuses1[0]->value = 2; $statuses1[0]->text = JText::_('COM_SF_COMPLETED');
									$statuses1[1]->value = 1; $statuses1[1]->text = JText::_('COM_SF_NOT_COMPLETED');
									$statuses[] = mosHTML::makeOption( '0', JText::_('COM_SF_SELECT_STATUS') );
									$statuses = @array_merge( $statuses,$statuses1 );
									$f_status = mosHTML::selectList( $statuses,'filt_status', 'class="text_area" size="1" '. $javascript, 'value', 'text', $filt_status );
									$lists['filt_status'] = $f_status;

									$u_types1 = array();
									if (!$sf_config->get('sf_enable_jomsocial_integration')) {
										$u_types1[0]->value = 3; $u_types1[0]->text = JText::_('COM_SF_INVITED_USERS');
									}
									$u_types1[1]->value = 2; $u_types1[1]->text = JText::_('COM_SF_REGISTERED_USERS');
									$u_types1[2]->value = 1; $u_types1[2]->text = JText::_('COM_SF_GUESTS');
									$u_types[] = mosHTML::makeOption( '0', JText::_('COM_SF_SELECT_USERTYPE') );
									$u_types = @array_merge( $u_types,$u_types1 );
									$f_utypes = mosHTML::selectList( $u_types,'filt_utype', 'class="text_area" size="1" '. $javascript, 'value', 'text', $filt_utype );
									$lists['filt_utype'] = $f_utypes;

									$lists['filt_ulist'] = '';
									if (($filt_utype - 1) == 2) {
										$query = "SELECT id AS value, listname AS text"
												. "\n FROM #__survey_force_listusers"
														. "\n ORDER BY listname"
																;
																$database->setQuery( $query );
																$ulists[] = mosHTML::makeOption( '0', JText::_('COM_SF_SELECT_USERLIST') );
																$ulists = @array_merge( $ulists, ($database->LoadObjectList() == null? array(): $database->LoadObjectList()) );
																$ulist = mosHTML::selectList( $ulists,'filt_ulist', 'class="text_area" size="1" '. $javascript, 'value', 'text', $filt_ulist );
																$lists['filt_ulist'] = $ulist;
									}
										






									if($_REQUEST["send"]){
										//print_r($_REQUEST[cid]);die;
										if(count($_REQUEST[cid])){
											$cadena = "";
											foreach($_REQUEST[cid] as $start){
												$cadena .= $start.",";
											}
											$cadena = substr($cadena, 0, -1);
											$lists["surveySend"] = "<span style='color:#22AA22;'>Report was sent to Admin's Email!</span>";

											//Envio reporte a administrador
											$query = "SELECT id, survey_id, sf_time, sf_ip_address FROM #__survey_force_user_starts WHERE is_complete = 1 and survey_id = 8 AND id IN ( ".$cadena." )";
											$database->setQuery( $query );
											$surveys = $database->LoadObjectList();

											$subject = 'Excel Report';
											$message = 'Excel Report';
											$dVar=new JConfig();
											$email = $dVar->mailfrom;

											include_once(JPATH_ROOT.'/libraries/tbs/tbs_class.php');
											include_once(JPATH_ROOT.'/libraries/tbs/tbs_plugin_opentbs.php');

											$TBS = new clsTinyButStrong;
											$TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);
											$template = JPATH_ROOT."/doc/report_du.xlsx";
											$TBS->LoadTemplate($template);


											$data = array();
											foreach($surveys as $survey){
													
												$row = array('id'=>$survey->id ,
														'survey'=>$survey->survey_id,
														'start'=>$survey->sf_time,
														'ip'=>$survey->sf_ip_address,
														'total'=>count($surveys),
														'q946'=>getTextResponse($survey->id,946,0,8),
														'q947'=>getTextResponse($survey->id,947,0,8)
												);
												$row = getTextResponse2($survey->id, $row,8);
													
												$data[] = $row;
											}

											//print_r($data);die;

											$TBS->MergeBlock('user', $data);
											$TBS->PlugIn(OPENTBS_SELECT_SHEET, "0. General company charact.");
											$TBS->MergeBlock('general', $data);
											$TBS->PlugIn(OPENTBS_SELECT_SHEET, "5. Strategic");
											$TBS->MergeBlock('general', $data);
											$TBS->PlugIn(OPENTBS_SELECT_SHEET, "4. Organisation");
											$TBS->MergeBlock('general', $data);
											$TBS->PlugIn(OPENTBS_SELECT_SHEET, "3. Systems");
											$TBS->MergeBlock('general', $data);
											$TBS->PlugIn(OPENTBS_SELECT_SHEET, "2. Processen");
											$TBS->MergeBlock('general', $data);
											$TBS->PlugIn(OPENTBS_SELECT_SHEET, "1. Key elements");
											$TBS->MergeBlock('general', $data);

											$attachment = JPATH_ROOT."/tmp/Report_".time().".xlsx";
											$TBS->Show(OPENTBS_FILE, $attachment);

											//mosMail( $mosConfig_mailfrom,  $mosConfig_sitename, 'jfalconavila@hotmail.com' , $subject, $message, 1, null,null,$attachment);
											mosMail( $mosConfig_mailfrom,  $mosConfig_sitename, $email , $subject, $message, 1, null,null,$attachment);

										}else{
											$lists["surveySend"] = "<span style='color:#AA2222;'>Select surveys first!</span>";
										}
									}

									survey_force_adm_html::SF_generateExcelNL( $rows, $lists, $pageNav, $option);

}
//FIN PARTE NUEVA









function SF_generateExcelSP( $option, $is_pdf = 0, $template ) {

	$templateFiles = unserialize( EXCEL_REPORT_TEMPLATES );
	
	global $database, $mainframe, $mosConfig_list_limit, $front_end, $my, $mosConfig_mailfrom, $mosConfig_sitename;
	$sf_config = new mos_Survey_Force_Config( );

	$limit 			= intval( $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', $mosConfig_list_limit ) );
	$limitstart 	= intval( $mainframe->getUserStateFromRequest( "view{$option}limitstart", 'limitstart', 0 ) );
	$surv_id 		= intval( $mainframe->getUserStateFromRequest( "surv_id{$option}", 'survey_id', 8 ) );

	$filt_status	= intval( $mainframe->getUserStateFromRequest( "filt_status{$option}", 'filt_status', 2 ) );
	$filt_utype		= intval( $mainframe->getUserStateFromRequest( "filt_utype{$option}", 'filt_utype', 0 ) );
	$filt_ulist		= intval( $mainframe->getUserStateFromRequest( "filt_ulist{$option}", 'filt_ulist', 0 ) );
	if ($limit == 0) $limit = 999999;

	$javascript = 'onchange="submitbutton(\'reports\');"';
	$filter_quest = array();
	$filter_ans = array(0);
	$i = 0;
	$j = 0;
	$lists['filter_quest'] = array();
	$lists['filter_quest_ans'] = array();
	if ($surv_id) {
		$query = "SELECT count(*) FROM #__survey_force_quests WHERE  published = 1 AND sf_survey = '".$surv_id."' and id = '".$filter_quest."' and sf_qtype IN (2,3)";
		$database->setQuery( $query );
		if (!$database->LoadResult()) {
			if (isset($_REQUEST['filter_quest'])) {
				$k = 0;
				foreach ($_REQUEST['filter_quest'] as $filt_row) {
					if ($filt_row) {
						$qlists = array();
						$query = "SELECT id AS value, sf_qtext AS text"
								. "\n FROM #__survey_force_quests WHERE  published = 1 AND sf_survey = '".$surv_id."' and sf_qtype IN (2,3)"
										. "\n ORDER BY ordering"
												;
												$database->setQuery( $query );
												$quests33 = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
												$ji =0;

												while ($ji < count($quests33)) {
													$quests33[$ji]->text = strip_tags($quests33[$ji]->text);
													if (strlen($quests33[$ji]->text) > 55)
														$quests33[$ji]->text = substr($quests33[$ji]->text, 0, 55).'...';
													$quests33[$ji]->text = $quests33[$ji]->value . ' - ' . $quests33[$ji]->text;

													$ji ++;
												}

												$qlists[] = mosHTML::makeOption( '0', JText::_('COM_SF_SELECT_QUESTION') );
												$qlists = @array_merge( $qlists, $quests33 );
												$qlist = mosHTML::selectList( $qlists,'filter_quest[]', 'class="text_area" size="1" '. $javascript, 'value', 'text', $filt_row );
												$filter_quest[$i] = $filt_row;
												$lists['filter_quest'][$i] = $qlist;
												$sel_ans = array(0);
												if (isset($_REQUEST['filter_ans'][$filt_row]) && $_REQUEST['filter_ans'][$filt_row]) {
													$sel_ans = $_REQUEST['filter_ans'][$filt_row];
												}
												$sel_ans2 = null;
												if (is_array($sel_ans) && count($sel_ans))
													foreach($sel_ans as $sel_an) {
													$tmp = new stdClass;
													$tmp->value = $sel_an;
													$sel_ans2[] = $tmp;
												}

												$query = "SELECT distinct a.answer AS value, b.ftext AS text"
														. "\n FROM #__survey_force_user_answers as a, #__survey_force_fields as b, #__survey_force_quests as c WHERE c.published = 1 AND a.quest_id = '".$filt_row."' and a.survey_id = '".$surv_id."' and a.quest_id = c.id and c.sf_qtype IN (2,3) and a.answer <> 0 and a.answer = b.id"
																;
																$database->setQuery( $query );
																$alists = array();
																$alists[] = mosHTML::makeOption( '0', '- Select Answer -' );
																$alists = @array_merge( $alists, ($database->LoadObjectList() == null? array(): $database->LoadObjectList()) );
																$alist = mosHTML::selectList( $alists,"filter_ans[$filt_row][]", 'class="text_area" size="3" multiple="multiple" '. $javascript, 'value', 'text', $sel_ans2 );
																$filter_ans[$i] = implode(',', $sel_ans);
																$lists['filter_quest_ans'][$i] = $alist;
																$i ++;
																$k ++;
					}
				}
			}
			$qlists = array();
			$query = "SELECT id AS value, sf_qtext AS text"
					. "\n FROM #__survey_force_quests WHERE  published = 1 AND sf_survey = '".$surv_id."' and sf_qtype IN (2,3)"
							. "\n ORDER BY ordering, id "
									;
									$database->setQuery( $query );

									$quests34 = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());

									$ji =0;
									while ($ji < count($quests34)) {
										$quests34[$ji]->text = strip_tags($quests34[$ji]->text);
										if (strlen($quests34[$ji]->text) > 55)
											$quests34[$ji]->text = substr($quests34[$ji]->text, 0, 55).'...';
										$quests34[$ji]->text = $quests34[$ji]->value . ' - ' . $quests34[$ji]->text;
										$ji ++;
									}

									$qlists[] = mosHTML::makeOption( '0', JText::_('COM_SF_SELECT_QUESTION') );
									$qlists = @array_merge( $qlists, $quests34 );
									$qlist = mosHTML::selectList( $qlists,'filter_quest[]', 'class="text_area" size="1" '. $javascript, 'value', 'text', '0' );
									$lists['filter_quest'][$i] = $qlist;
									$lists['filter_quest_ans'][$i] = '';
		}
	}

	if (($filt_utype - 1) != 2) {$filt_ulist = 0;}
	$query = "SELECT count(sf_ust.id) FROM #__survey_force_user_starts as sf_ust, #__survey_force_survs as sf_s"
			. "\n WHERE sf_ust.survey_id = sf_s.id"
					. ( $surv_id ? "\n and sf_s.id = $surv_id" : '' )
					. ( $filt_status ? "\n and sf_ust.is_complete = '".($filt_status - 1)."'" : '' )
					. ( $filt_utype ? "\n and sf_ust.usertype = '".($filt_utype -1)."'" : '' );
	$database->setQuery( $query );

	$total = $database->loadResult();



	// get the subset (based on limits) of required records
	$query = "SELECT sf_ust.*, sf_s.sf_name as survey_name, u.username reg_username, u.name reg_name, u.email reg_email,"
			. "\n sf_u.name as inv_name, sf_u.lastname as inv_lastname, sf_u.email as inv_email"
					. "\n FROM (#__survey_force_user_starts as sf_ust, #__survey_force_survs as sf_s";
	$r = 0;
	foreach ($filter_ans as $filt_ans) {
		$query .= ($filt_ans != '0' ? ", #__survey_force_user_answers as sf_ans".$r : '');
		$r ++;
	}

	$query .= ")"
			. "\n LEFT JOIN #__users as u ON u.id = sf_ust.user_id and sf_ust.usertype=1"
					. "\n LEFT JOIN #__survey_force_users as sf_u ON sf_u.id = sf_ust.user_id and sf_ust.usertype=2"
							. "\n WHERE sf_ust.survey_id = sf_s.id"
									. ( $surv_id ? "\n and sf_s.id = $surv_id" : '' )
									. ( $front_end && $my->usertype != 'Super Administrator'? " AND sf_s.sf_author = '{$my->id}' ": ' ')
									. ( $filt_status ? "\n and sf_ust.is_complete = '".($filt_status - 1)."'" : '' )
									. ( $filt_utype ? "\n and sf_ust.usertype = '".($filt_utype -1)."'" : '' )
									. ( $filt_ulist ? "\n and sf_u.list_id = '".($filt_ulist)."'" : '' );
	$r = 0;
	foreach ($filter_ans as $filt_ans) {
		$query .= ( $filt_ans != '0' ? "\n and sf_ans".$r.".start_id = sf_ust.id and sf_ans".$r.".answer IN (".($filt_ans).")" : '' );
		$r ++;

	}
	$query .= "\n ORDER BY sf_ust.sf_time DESC"
			;
			$database->SetQuery($query);

			$rows = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
			$total = count($rows);
			$rows = @array_slice($rows, $limitstart, $limit);

			require_once( $GLOBALS['mosConfig_absolute_path'] . '/administrator/includes/pageNavigation.php' );
			if ($front_end)
				$pageNav = new SFPageNav( $total, $limitstart, $limit  );
			else
				$pageNav = new mosPageNav( $total, $limitstart, ($limit==999999?0:$limit) );

			$query = "SELECT id AS value, sf_name AS text"
					. "\n FROM #__survey_force_survs"
							.( $front_end && $my->usertype != 'Super Administrator'? " WHERE sf_author = '{$my->id}' ": ' ')
							. "\n ORDER BY sf_name"
									;
									$database->setQuery( $query );

									$surveys[] = mosHTML::makeOption( '0', JText::_('COM_SF_S_SELECT_SURVEY') );
									$surveys = @array_merge( $surveys, ($database->LoadObjectList() == null? array(): $database->LoadObjectList()) );
									$survey = mosHTML::selectList( $surveys,'surv_id', 'class="text_area" size="1" '. $javascript, 'value', 'text', $surv_id );
									$lists['survey'] = $survey;

									$statuses1 = array();
									$statuses1[0]->value = 2; $statuses1[0]->text = JText::_('COM_SF_COMPLETED');
									$statuses1[1]->value = 1; $statuses1[1]->text = JText::_('COM_SF_NOT_COMPLETED');
									$statuses[] = mosHTML::makeOption( '0', JText::_('COM_SF_SELECT_STATUS') );
									$statuses = @array_merge( $statuses,$statuses1 );
									$f_status = mosHTML::selectList( $statuses,'filt_status', 'class="text_area" size="1" '. $javascript, 'value', 'text', $filt_status );
									$lists['filt_status'] = $f_status;

									$u_types1 = array();
									if (!$sf_config->get('sf_enable_jomsocial_integration')) {
										$u_types1[0]->value = 3; $u_types1[0]->text = JText::_('COM_SF_INVITED_USERS');
									}
									$u_types1[1]->value = 2; $u_types1[1]->text = JText::_('COM_SF_REGISTERED_USERS');
									$u_types1[2]->value = 1; $u_types1[2]->text = JText::_('COM_SF_GUESTS');
									$u_types[] = mosHTML::makeOption( '0', JText::_('COM_SF_SELECT_USERTYPE') );
									$u_types = @array_merge( $u_types,$u_types1 );
									$f_utypes = mosHTML::selectList( $u_types,'filt_utype', 'class="text_area" size="1" '. $javascript, 'value', 'text', $filt_utype );
									$lists['filt_utype'] = $f_utypes;

									$lists['filt_ulist'] = '';
									if (($filt_utype - 1) == 2) {
										$query = "SELECT id AS value, listname AS text"
												. "\n FROM #__survey_force_listusers"
														. "\n ORDER BY listname"
																;
																$database->setQuery( $query );
																$ulists[] = mosHTML::makeOption( '0', JText::_('COM_SF_SELECT_USERLIST') );
																$ulists = @array_merge( $ulists, ($database->LoadObjectList() == null? array(): $database->LoadObjectList()) );
																$ulist = mosHTML::selectList( $ulists,'filt_ulist', 'class="text_area" size="1" '. $javascript, 'value', 'text', $filt_ulist );
																$lists['filt_ulist'] = $ulist;
									}
										






									if($_REQUEST["send"]){
										//print_r($_REQUEST[cid]);die;
										if(count($_REQUEST[cid])){
											$cadena = "";
											foreach($_REQUEST[cid] as $start){
												$cadena .= $start.",";
											}
											$cadena = substr($cadena, 0, -1);
											$lists["surveySend"] = "<span style='color:#22AA22;'>Report was sent to Admin's Email!</span>";

											//Envio reporte a administrador
											$query = "SELECT id, survey_id, sf_time, sf_ip_address FROM #__survey_force_user_starts WHERE is_complete = 1 and survey_id = '" . $surv_id . "' AND id IN ( ".$cadena." )";
											$database->setQuery( $query );
											$surveys = $database->LoadObjectList();

											$subject = 'Excel Report';
											$message = "Please find attached the Excel Report\n\n";
											$dVar=new JConfig();
											$email = $dVar->mailfrom;

											include_once(JPATH_ROOT.'/libraries/tbs/tbs_class.php');
											include_once(JPATH_ROOT.'/libraries/tbs/tbs_plugin_opentbs.php');

											$TBS = new clsTinyButStrong;
											$TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);
//											$template = JPATH_ROOT."/doc/report_du.xlsx";
											$template_name = JPATH_ROOT."/doc/".$templateFiles[$template][0];
											$TBS->LoadTemplate($template_name);


											$data = array();
											foreach($surveys as $survey){
													
												$row = array('id'=>$survey->id ,
														'survey'=>$survey->survey_id,
														'start'=>$survey->sf_time,
														'ip'=>$survey->sf_ip_address,
														'total'=>count($surveys),
														'q945'=>getTextResponse($survey->id,945,$survey->survey_id),
														'q946'=>getTextResponse($survey->id,946,$survey->survey_id), //-- JUAN: wat is this?
														'q947'=>getTextResponse($survey->id,947,$survey->survey_id), //-- JUAN: wat is this?
														'q349'=>getTextResponse($survey->id,349,$survey->survey_id),
														'q351'=>getTextResponse($survey->id,351,$survey->survey_id),
														'q352'=>getTextResponse($survey->id,352,$survey->survey_id),
														'q2832'=>getTextResponse($survey->id,2832,$survey->survey_id),
														'q1920'=>getTextResponse($survey->id,1920,$survey->survey_id),
														'q2603'=>getTextResponse($survey->id,2603,$survey->survey_id),
														'q2604'=>getTextResponse($survey->id,2604,$survey->survey_id),
														'q2605'=>getTextResponse($survey->id,2605,$survey->survey_id),
														'q3062'=>getTextResponse($survey->id,3062,$survey->survey_id),
														'q3291'=>getTextResponse($survey->id,3291,$survey->survey_id),
														'q2149'=>getTextResponse($survey->id,2149,$survey->survey_id),
														'q4215'=>getTextResponse($survey->id,4215,$survey->survey_id),
														'q3750'=>getTextResponse($survey->id,3750,$survey->survey_id),
														'q2378'=>getTextResponse($survey->id,2378,$survey->survey_id),
														'q3976'=>getTextResponse($survey->id,3976,$survey->survey_id)
												);
												//$row = getTextResponse2($survey->id, $row, $surv_id,1); //TEST MODE
												$row = getTextResponse2($survey->id, $row, $surv_id ); //NORMAL MODE
											
												
												$data[] = $row;
											}
											//die;//TEST MODE
											//print_r($data);die;

											$TBS->MergeBlock('user', $data);
											$TBS->PlugIn(OPENTBS_SELECT_SHEET, "0. General company charact.");
											$TBS->MergeBlock('general', $data);
											$TBS->PlugIn(OPENTBS_SELECT_SHEET, "5. Strategic");
											$TBS->MergeBlock('general', $data);
											$TBS->PlugIn(OPENTBS_SELECT_SHEET, "4. Organisation");
											$TBS->MergeBlock('general', $data);
											$TBS->PlugIn(OPENTBS_SELECT_SHEET, "3. Systems");
											$TBS->MergeBlock('general', $data);
											$TBS->PlugIn(OPENTBS_SELECT_SHEET, "2. Processen");
											$TBS->MergeBlock('general', $data);
											$TBS->PlugIn(OPENTBS_SELECT_SHEET, "1. Key elements");
											$TBS->MergeBlock('general', $data);

											$attachment = JPATH_ROOT."/tmp/".$template."_".time().".xlsx";
											$TBS->Show(OPENTBS_FILE, $attachment);

											mosMail( $mosConfig_mailfrom,  $mosConfig_sitename, 'jfalconavila@hotmail.com' , $subject, $message, 1, null,null,$attachment);
											mosMail( $mosConfig_mailfrom,  $mosConfig_sitename, $email , $subject, $message, 1, null,null,$attachment);
											mosMail( $mosConfig_mailfrom,  $mosConfig_sitename, 'manfred@jamaza.com' , $subject, $message, 1, null,null,$attachment);

										}else{
											$lists["surveySend"] = "<span style='color:#AA2222;'>Select surveys first!</span>";
										}
									}

									survey_force_adm_html::SF_generateExcelSP( $rows, $lists, $pageNav, $option, $template, $surv_id);

}




function SF_ViewReportsPDF_full( $option, $cid, $is_pdf ) {
	global $database, $mainframe, $mosConfig_list_limit, $front_end, $my;
	
	$limit 			= intval( $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', $mosConfig_list_limit ) );
	$limitstart 	= intval( $mainframe->getUserStateFromRequest( "view{$option}limitstart", 'limitstart', 0 ) );
	$surv_id 		= intval( $mainframe->getUserStateFromRequest( "surv_id{$option}", 'surv_id', 0 ) );
	$filt_status	= intval( $mainframe->getUserStateFromRequest( "filt_status{$option}", 'filt_status', 2 ) );
	$filt_utype		= intval( $mainframe->getUserStateFromRequest( "filt_utype{$option}", 'filt_utype', 0 ) );
	$filt_ulist		= intval( $mainframe->getUserStateFromRequest( "filt_ulist{$option}", 'filt_ulist', 0 ) );
	$filter_quest	= intval( $mainframe->getUserStateFromRequest( "filter_quest{$option}", 'filter_quest', 0 ) );
	$filter_ans		= intval( $mainframe->getUserStateFromRequest( "filter_ans{$option}", 'filter_ans', 0 ) );
	if ($limit == 0) $limit = 999999;
	$no_answer_str = JText::_('COM_SF_NO_ANSWER');
	if ( $front_end ) {
		global $SF_SESSION;
		$limit		= intval( mosGetParam( $_REQUEST, 'limit', $SF_SESSION->get('list_limit',$mainframe->getCfg('list_limit')) ) );
		if ($limit == 0) $limit = 999999;
		$SF_SESSION->set('list_limit', $limit);
		$limitstart = intval( mosGetParam( $_REQUEST, 'limitstart', $SF_SESSION->get('list_limitstart', 0) ) );
		$SF_SESSION->set('list_limitstart', $limitstart);
		$surv_id		= intval( mosGetParam( $_REQUEST, 'surv_id', $SF_SESSION->get('list_surv_id', 0) ) );
		$SF_SESSION->set('list_surv_id', $surv_id);
		
		$filt_status	= intval( mosGetParam( $_REQUEST, 'filt_status', $SF_SESSION->get('list_filt_status', 2) ) );		
		$SF_SESSION->set('list_filt_status', $filt_status);
		$filt_utype		= intval( mosGetParam( $_REQUEST, 'filt_utype', $SF_SESSION->get('list_filt_utype', 0) ) );	
		$SF_SESSION->set('list_filt_utype', $filt_utype);
		$filt_ulist		= intval( mosGetParam( $_REQUEST, 'filt_ulist', $SF_SESSION->get('list_filt_ulist', 0) ) );	
		$SF_SESSION->set('list_filt_ulist', $filt_ulist);
		$filter_quest	= intval( mosGetParam( $_REQUEST, 'filter_quest', $SF_SESSION->get('list_filter_quest', 0) ) );
		$SF_SESSION->set('list_filter_quest', $filter_quest);
		$filter_ans		= intval( mosGetParam( $_REQUEST, 'filter_ans', $SF_SESSION->get('list_filter_ans', 0) ) );
		$SF_SESSION->set('list_filter_ans', $filter_ans);
		global $sf_lang;
		$no_answer_str = $sf_lang['SURVEY_NO_ANSWER'];

	}
	
	$javascript = 'onchange="submitbutton(\'reports\');"';
	$filter_quest = array();
	$filter_ans = array();
	$i = 0;
	$j = 0;
	$lists['filter_quest'] = array();
	$lists['filter_quest_ans'] = array();
	if ($surv_id) {
		$query = "SELECT count(*) FROM #__survey_force_quests WHERE published = 1 AND sf_survey = '".$surv_id."' and id = '".$filter_quest."' and sf_qtype IN (2,3)";
		$database->setQuery( $query );
		if (!$database->LoadResult()) {
			if (isset($_REQUEST['filter_quest'])) {
				$k = 0;
				foreach ($_REQUEST['filter_quest'] as $filt_row) {
					if ($filt_row) {
						$qlists = array();
						$query = "SELECT id AS value, sf_qtext AS text"
						. "\n FROM #__survey_force_quests WHERE published = 1 AND sf_survey = '".$surv_id."' and sf_qtype IN (2,3)"
						. "\n ORDER BY ordering, id "
						;
						$database->setQuery( $query );
						$qlists[] = mosHTML::makeOption( '0', JText::_('COM_SF_SELECT_QUESTION') );
						$qlists = @array_merge( $qlists, ($database->LoadObjectList() == null? array(): $database->LoadObjectList()) );
						$qlist = mosHTML::selectList( $qlists,'filter_quest[]', 'class="text_area" size="1" '. $javascript, 'value', 'text', $filt_row );
						$filter_quest[$i] = $filt_row;
						$lists['filter_quest'][$i] = $qlist;
						$sel_ans = 0;
						if (isset($_REQUEST['filter_ans'][$k]) && $_REQUEST['filter_ans'][$k]) {
							$sel_ans = $_REQUEST['filter_ans'][$k];
						}
						$query = "SELECT distinct a.answer AS value, b.ftext AS text"
						. "\n FROM #__survey_force_user_answers as a, #__survey_force_fields as b, #__survey_force_quests as c WHERE c.published = 1 AND a.quest_id = '".$filt_row."' and a.survey_id = '".$surv_id."' and a.quest_id = c.id and c.sf_qtype IN (2,3) and a.answer <> 0 and a.answer = b.id"
						;
						$database->setQuery( $query );
						$alists = array();
						$alists[] = mosHTML::makeOption( '0', JText::_('COM_SF_SELECT_ANSWER') );
						$alists = @array_merge( $alists, ($database->LoadObjectList() == null? array(): $database->LoadObjectList()) );
						$alist = mosHTML::selectList( $alists,'filter_ans[]', 'class="text_area" size="1" '. $javascript, 'value', 'text', $sel_ans );
						$filter_ans[$i] = $sel_ans;
						$lists['filter_quest_ans'][$i] = $alist;
						$i ++;
						$k ++;
					}
				}
			}
			$qlists = array();
			$query = "SELECT id AS value, sf_qtext AS text"
			. "\n FROM #__survey_force_quests WHERE published = 1 AND sf_survey = '".$surv_id."' and sf_qtype IN (2,3)"
			. "\n ORDER BY ordering, id "
			;
			$database->setQuery( $query );
			$qlists[] = mosHTML::makeOption( '0', JText::_('COM_SF_SELECT_QUESTION') );
			$qlists = @array_merge( $qlists, ($database->LoadObjectList() == null? array(): $database->LoadObjectList()) );
			$qlist = mosHTML::selectList( $qlists,'filter_quest[]', 'class="text_area" size="1" '. $javascript, 'value', 'text', '0' ); 
			$lists['filter_quest'][$i] = $qlist;
			$lists['filter_quest_ans'][$i] = '';
		}
	}	
	
	if (($filt_utype - 1) != 2) {$filt_ulist = 0;}
	$query = "SELECT count(sf_ust.id) FROM #__survey_force_user_starts as sf_ust, #__survey_force_survs as sf_s"
	. "\n WHERE sf_ust.survey_id = sf_s.id"
	. ( $surv_id ? "\n and sf_s.id = $surv_id" : '' )
	. ( $filt_status ? "\n and sf_ust.is_complete = '".($filt_status - 1)."'" : '' )
	. ( $filt_utype ? "\n and sf_ust.usertype = '".($filt_utype -1)."'" : '' );
	if ((count($cid) > 0) && ($cid[0] != 0)) {
		$cids =implode(',', $cid);
		$query .= "\n and sf_ust.id in (".$cids.")";
	}

	$database->setQuery( $query );
	$total = $database->loadResult();

	require_once( $GLOBALS['mosConfig_absolute_path'] . '/administrator/includes/pageNavigation.php' );
	if ($front_end)
		$pageNav = new SFPageNav( $total, $limitstart, $limit  );
	else
		$pageNav = new mosPageNav( $total, $limitstart, ($limit==999999?0:$limit) );

	// get the subset (based on limits) of required records
	$query = "SELECT sf_ust.*, sf_s.sf_name as survey_name, u.username reg_username, u.name reg_name, u.email reg_email,"
	. "\n sf_u.name as inv_name, sf_u.lastname as inv_lastname, sf_u.email as inv_email"
	. "\n FROM (#__survey_force_user_starts as sf_ust, #__survey_force_survs as sf_s";
	$r = 0;
	foreach ($filter_ans as $filt_ans) {
		$query .= ($filt_ans ? ", #__survey_force_user_answers as sf_ans".$r : '');
		$r ++;
	}
	$query .= ")"
	. "\n LEFT JOIN #__users as u ON u.id = sf_ust.user_id and sf_ust.usertype=1"
	. "\n LEFT JOIN #__survey_force_users as sf_u ON sf_u.id = sf_ust.user_id and sf_ust.usertype=2"
	. "\n WHERE sf_ust.survey_id = sf_s.id"
	. ( $surv_id ? "\n and sf_s.id = $surv_id" : '' )
	. ( $front_end && $my->usertype != 'Super Administrator'? " AND sf_s.sf_author = '{$my->id}' ": '')
	. ( $filt_status ? "\n and sf_ust.is_complete = '".($filt_status - 1)."'" : '' )
	. ( $filt_utype ? "\n and sf_ust.usertype = '".($filt_utype -1)."'" : '' )
	. ( $filt_ulist ? "\n and sf_u.list_id = '".($filt_ulist)."'" : '' );
	if ((count($cid) > 0) && ($cid[0] != 0)) {
		$cids =implode(',', $cid);
		$query .= "\n and sf_ust.id in (".$cids.")";
	}

	$r = 0;
	foreach ($filter_ans as $filt_ans) {
		$query .= ( $filt_ans ? "\n and sf_ans".$r.".start_id = sf_ust.id and sf_ans".$r.".answer = '".($filt_ans)."'" : '' );
		$r ++;
	
	}
	$query .= "\n ORDER BY sf_ust.sf_time DESC"
	;
	$database->SetQuery($query);
	$rows = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
	$ri = 0;
	while ($ri < count($rows)) {
	
	
			$query = "SELECT s.*, u.username reg_username, u.name reg_name, u.email reg_email,"
			. "\n sf_u.name as inv_name, sf_u.lastname as inv_lastname, sf_u.email as inv_email"
			. "\n FROM #__survey_force_user_starts as s"
			. "\n LEFT JOIN #__users as u ON u.id = s.user_id and s.usertype=1"
			. "\n LEFT JOIN #__survey_force_users as sf_u ON sf_u.id = s.user_id and s.usertype=2"
			. "\n WHERE s.id = '".$rows[$ri]->id."'";
			$database->SetQuery( $query );
			$rows[$ri]->start_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
			$rows[$ri]->count_start_data = count($rows[$ri]->start_data);
	
	if ($rows[$ri]->count_start_data) {
		
			$query = "SELECT * FROM #__survey_force_survs WHERE id = '".$rows[$ri]->start_data[0]->survey_id."'";
			$database->SetQuery( $query );
			$rows[$ri]->survey_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
		
			$query = "SELECT q.*"
			. "\n FROM #__survey_force_quests as q"
			. "\n WHERE q.published = 1 AND q.sf_survey = '".$rows[$ri]->start_data[0]->survey_id."' AND sf_qtype NOT IN (7, 8)"
			. "\n ORDER BY q.ordering, q.id";
			$database->SetQuery( $query );
			$rows[$ri]->questions_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
		
			$qi = 0;
			$rows[$ri]->questions_data[$qi]->answer = '';
			while ( $qi < count($rows[$ri]->questions_data) ) {
				if ($rows[$ri]->questions_data[$qi]->sf_impscale) {
					$query = "SELECT iscale_name FROM #__survey_force_iscales WHERE id = '".$rows[$ri]->questions_data[$qi]->sf_impscale."'";
					$database->SetQuery( $query );
					$rows[$ri]->questions_data[$qi]->iscale_name = $database->loadResult();
		
					$query = "SELECT iscalefield_id FROM #__survey_force_user_answers_imp"
					. "\n WHERE quest_id = '".$rows[$ri]->questions_data[$qi]->id."' and survey_id = '".$rows[$ri]->questions_data[$qi]->sf_survey."'"
					. "\n and iscale_id = '".$rows[$ri]->questions_data[$qi]->sf_impscale."'"
					. "\n AND start_id = '".$rows[$ri]->id."'";
					$database->SetQuery( $query );
					$ans_inf = $database->LoadResult();
					
					$rows[$ri]->questions_data[$qi]->answer_imp = array();
					$query = "SELECT * FROM #__survey_force_iscales_fields WHERE iscale_id = '".$rows[$ri]->questions_data[$qi]->sf_impscale."'"
					. "\n ORDER BY ordering";
					$database->SetQuery( $query );
					$tmp_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
					$j = 0;
					while ( $j < count($tmp_data) ) {
						$rows[$ri]->questions_data[$qi]->answer_imp[$j]->num = $j;
						$rows[$ri]->questions_data[$qi]->answer_imp[$j]->f_id = $tmp_data[$j]->id;
						$rows[$ri]->questions_data[$qi]->answer_imp[$j]->f_text = $tmp_data[$j]->isf_name;
						$rows[$ri]->questions_data[$qi]->answer_imp[$j]->alt_text = '';
						if ($ans_inf == $tmp_data[$j]->id) {
							$rows[$ri]->questions_data[$qi]->answer_imp[$j]->alt_text = '1';
							$rows[$ri]->questions_data[$qi]->answer_imp[$j]->alt_id = $ans_inf;
						}
						$j ++;
					}
				}
				$rows[$ri]->questions_data[$qi]->sf_qtext = trim(strip_tags($rows[$ri]->questions_data[$qi]->sf_qtext,'<a><b><i><u>'));
				switch ($rows[$ri]->questions_data[$qi]->sf_qtype) {
					case 1:
						$rows[$ri]->questions_data[$qi]->answer = array();
						$rows[$ri]->questions_data[$qi]->scale = '';
						$query = "SELECT stext FROM #__survey_force_scales WHERE quest_id = '".$rows[$ri]->questions_data[$qi]->id."'"
						. "\n and quest_id = '".$rows[$ri]->questions_data[$qi]->id."'"
						. "\n ORDER BY ordering";
						$database->SetQuery( $query );
						$tmp_data = $database->loadResultArray();
						$rows[$ri]->questions_data[$qi]->scale = implode(', ', $tmp_data);
						
						$query = "SELECT * FROM #__survey_force_fields WHERE quest_id = '".$rows[$ri]->questions_data[$qi]->id."'"
						. "\n and is_main = 1 ORDER BY ordering";
						$database->SetQuery( $query );
						$tmp_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
						
						$query = "SELECT * FROM #__survey_force_user_answers WHERE quest_id = '".$rows[$ri]->questions_data[$qi]->id."' and survey_id = '".$rows[$ri]->questions_data[$qi]->sf_survey."' and start_id = '".$rows[$ri]->id."'";
						$database->SetQuery( $query );
						$ans_inf_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
						
						$j = 0;
						while ( $j < count($tmp_data) ) {
							$rows[$ri]->questions_data[$qi]->answer[$j]->num = $j;
							$rows[$ri]->questions_data[$qi]->answer[$j]->f_id = $tmp_data[$j]->id;
							$rows[$ri]->questions_data[$qi]->answer[$j]->f_text = $tmp_data[$j]->ftext;
							$rows[$ri]->questions_data[$qi]->answer[$j]->alt_text = $no_answer_str;
							foreach ($ans_inf_data as $ans_data) {
								if ($ans_data->answer == $tmp_data[$j]->id) {
									$query = "SELECT * FROM #__survey_force_scales WHERE id = '".$ans_data->ans_field."'"
									. "\n and quest_id = '".$rows[$ri]->questions_data[$qi]->id."'"
									. "\n ORDER BY ordering";
									$database->SetQuery( $query );
									$alt_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
									$rows[$ri]->questions_data[$qi]->answer[$j]->alt_text = ($ans_data->ans_field == 0?$no_answer_str:$alt_data[0]->stext);
									$rows[$ri]->questions_data[$qi]->answer[$j]->alt_id = $ans_data->ans_field;
								}
							}
							$j ++;
						}
					break;
					case 2:
						$query = "SELECT a.answer, b.ans_txt FROM ( #__survey_force_user_answers AS a, #__survey_force_quests AS c ) LEFT JOIN #__survey_force_user_ans_txt AS b ON ( a.ans_field = b.id AND c.sf_qtype = 2 ) WHERE c.published = 1 AND a.quest_id = '".$rows[$ri]->questions_data[$qi]->id."' and a.survey_id = '".$rows[$ri]->questions_data[$qi]->sf_survey."' and a.start_id = '".$rows[$ri]->id."' AND c.id = a.quest_id ";
						$database->SetQuery( $query );
						$ans_inf = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
		
						$rows[$ri]->questions_data[$qi]->answer = array();
						$query = "SELECT * FROM #__survey_force_fields WHERE quest_id = '".$rows[$ri]->questions_data[$qi]->id."'"
						. "\n ORDER BY ordering";
						$database->SetQuery( $query );
						$tmp_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
						$j = 0;
						while ( $j < count($tmp_data) ) {
							$rows[$ri]->questions_data[$qi]->answer[$j]->num = $j;
							$rows[$ri]->questions_data[$qi]->answer[$j]->f_id = $tmp_data[$j]->id;
							$rows[$ri]->questions_data[$qi]->answer[$j]->f_text = $tmp_data[$j]->ftext;
							$rows[$ri]->questions_data[$qi]->answer[$j]->alt_text = '';
							if (count($ans_inf) > 0 && $ans_inf[0]->answer == $tmp_data[$j]->id) {
								$rows[$ri]->questions_data[$qi]->answer[$j]->f_text = $tmp_data[$j]->ftext. ($ans_inf[0]->ans_txt != ''? ' ('.$ans_inf[0]->ans_txt.')':'');
								$rows[$ri]->questions_data[$qi]->answer[$j]->alt_text = '1';
								$rows[$ri]->questions_data[$qi]->answer[$j]->alt_id = $ans_inf;
							}
							$j ++;
						}
					break;
					case 3:
						$query = "SELECT a.answer, b.ans_txt FROM ( #__survey_force_user_answers AS a, #__survey_force_quests AS c ) LEFT JOIN #__survey_force_user_ans_txt AS b ON ( a.ans_field = b.id AND c.sf_qtype = 3 ) WHERE c.published = 1 AND a.quest_id = '".$rows[$ri]->questions_data[$qi]->id."' and a.survey_id = '".$rows[$ri]->questions_data[$qi]->sf_survey."' and a.start_id = '".$rows[$ri]->id."'  AND c.id = a.quest_id ";
						$database->SetQuery( $query );
						$ans_inf_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
						
						$questions_data[$i]->answer = array();
						$query = "SELECT * FROM #__survey_force_fields WHERE quest_id = '".$rows[$ri]->questions_data[$qi]->id."'"
						. "\n ORDER BY ordering";
						$database->SetQuery( $query );
						$tmp_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
						$j = 0;
						while ( $j < count($tmp_data) ) {
							$rows[$ri]->questions_data[$qi]->answer[$j]->num = $j;
							$rows[$ri]->questions_data[$qi]->answer[$j]->f_id = $tmp_data[$j]->id;
							$rows[$ri]->questions_data[$qi]->answer[$j]->f_text = $tmp_data[$j]->ftext;
							$rows[$ri]->questions_data[$qi]->answer[$j]->alt_text = '';
							foreach ($ans_inf_data as $ans_data) {
								if ($ans_data->answer == $tmp_data[$j]->id) {
									$rows[$ri]->questions_data[$qi]->answer[$j]->f_text = $tmp_data[$j]->ftext. ($ans_data->ans_txt != ''? ' ('.$ans_data->ans_txt.')':'');
									$rows[$ri]->questions_data[$qi]->answer[$j]->alt_text = '1';
									$rows[$ri]->questions_data[$qi]->answer[$j]->alt_id = $ans_data->answer;
								}
							}
							$j ++;
						}
					break;
					case 4: 
						$n = substr_count($rows[$ri]->questions_data[$qi]->sf_qtext, "{x}")+substr_count($rows[$ri]->questions_data[$qi]->sf_qtext, "{y}");
						if ($n > 0) {
							$query = "SELECT b.ans_txt, a.ans_field FROM #__survey_force_user_answers as a LEFT JOIN #__survey_force_user_ans_txt as b ON a.answer = b.id	WHERE a.quest_id = '".$rows[$ri]->questions_data[$qi]->id."' AND a.survey_id = '".$rows[$ri]->questions_data[$qi]->sf_survey."' AND a.start_id = '".$rows[$ri]->id."' ORDER BY a.ans_field ";
							$database->SetQuery( $query );
							$ans_inf_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
							$rows[$ri]->questions_data[$qi]->answer = $ans_inf_data;
							$rows[$ri]->questions_data[$qi]->answer_count = $n;					
						}
						else {
							$query = "SELECT b.ans_txt FROM #__survey_force_user_answers as a, #__survey_force_user_ans_txt as b WHERE a.quest_id = '".$rows[$ri]->questions_data[$qi]->id."' and a.survey_id = '".$rows[$ri]->questions_data[$qi]->sf_survey."' and a.start_id = '".$rows[$ri]->id."' and a.answer = b.id";
							$database->SetQuery( $query );
							$ans_inf_data = $database->LoadResult();
							$rows[$ri]->questions_data[$qi]->answer = ($ans_inf_data == '')?$no_answer_str:$ans_inf_data;
						}
					break;
					case 5:
					case 6:
					case 9:
						$query = "SELECT a.*, b.ans_txt FROM ( #__survey_force_user_answers AS a, #__survey_force_quests AS c ) LEFT JOIN #__survey_force_user_ans_txt AS b ON ( a.next_quest_id = b.id AND c.sf_qtype = 9 ) WHERE c.published = 1 AND a.quest_id = '".$rows[$ri]->questions_data[$qi]->id."' and a.survey_id = '".$rows[$ri]->questions_data[$qi]->sf_survey."' and a.start_id = '".$rows[$ri]->id."' AND c.id=a.quest_id ";
						$database->SetQuery( $query );
						$ans_inf_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
		
						$rows[$ri]->questions_data[$qi]->answer = array();
						$query = "SELECT * FROM #__survey_force_fields WHERE quest_id = '".$rows[$ri]->questions_data[$qi]->id."'"
						. "\n and is_main = 1 ORDER BY ordering";
						$database->SetQuery( $query );
						$tmp_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
						$j = 0;
						while ( $j < count($tmp_data) ) {
							$rows[$ri]->questions_data[$qi]->answer[$j]->num = $j;
							$rows[$ri]->questions_data[$qi]->answer[$j]->f_id = $tmp_data[$j]->id;
							$rows[$ri]->questions_data[$qi]->answer[$j]->f_text = $tmp_data[$j]->ftext;
							$rows[$ri]->questions_data[$qi]->answer[$j]->alt_text = ($rows[$ri]->questions_data[$qi]->sf_qtype==9?$no_answer_str:$no_answer_str);
							foreach ($ans_inf_data as $ans_data) {
								if ($ans_data->answer == $tmp_data[$j]->id) {
									$rows[$ri]->questions_data[$qi]->answer[$j]->f_text = $tmp_data[$j]->ftext. ($ans_data->ans_txt != ''? ' ('.$ans_data->ans_txt.')':'');

									$query = "SELECT * FROM #__survey_force_fields WHERE id = '".$ans_data->ans_field."'"
									. "\n and quest_id = '".$rows[$ri]->questions_data[$qi]->id."'"
									. "\n and is_main = 0 ORDER BY ordering";
									$database->SetQuery( $query );
									$alt_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
									$rows[$ri]->questions_data[$qi]->answer[$j]->alt_text = ($ans_data->ans_field==0?($rows[$ri]->questions_data[$qi]->sf_qtype==9?$no_answer_str:$no_answer_str):$alt_data[0]->ftext);
									$rows[$ri]->questions_data[$qi]->answer[$j]->alt_id = $ans_data->ans_field;
								}
							}
							$j ++;
						}
					break;
					
					default:
						if (!$rows[$ri]->questions_data[$qi]->answer) $rows[$ri]->questions_data[$qi]->answer = $no_answer_str;
					break;
				}
				$qi ++;
			}	
		} // end if (count(start_data);//
	
	
		$ri ++;
	}

	if ($is_pdf) {
		SF_PrintReportsPDF_full( $rows );
	} else {
		SF_PrintReportsCSV_full( $rows );
	}	
}

function SF_ViewReportsCSV_full( $option, $cid ) {
	global $database, $mainframe, $mosConfig_list_limit, $front_end, $my;
	
	$limit 			= intval( $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', $mosConfig_list_limit ) );
	$limitstart 	= intval( $mainframe->getUserStateFromRequest( "view{$option}limitstart", 'limitstart', 0 ) );
	$surv_id 		= intval( $mainframe->getUserStateFromRequest( "surv_id{$option}", 'surv_id', 0 ) );
	$filt_status	= intval( $mainframe->getUserStateFromRequest( "filt_status{$option}", 'filt_status', 2 ) );
	$filt_utype		= intval( $mainframe->getUserStateFromRequest( "filt_utype{$option}", 'filt_utype', 0 ) );
	$filt_ulist		= intval( $mainframe->getUserStateFromRequest( "filt_ulist{$option}", 'filt_ulist', 0 ) );
	$filter_quest	= intval( $mainframe->getUserStateFromRequest( "filter_quest{$option}", 'filter_quest', 0 ) );
	$filter_ans		= intval( $mainframe->getUserStateFromRequest( "filter_ans{$option}", 'filter_ans', 0 ) );
	if ($limit == 0) $limit = 999999;
	if ( $front_end ) {
		global $SF_SESSION;
		$limit		= intval( mosGetParam( $_REQUEST, 'limit', $SF_SESSION->get('list_limit',$mainframe->getCfg('list_limit')) ) );
		if ($limit == 0) $limit = 999999;
		$SF_SESSION->set('list_limit', $limit);
		$limitstart = intval( mosGetParam( $_REQUEST, 'limitstart', $SF_SESSION->get('list_limitstart', 0) ) );
		$SF_SESSION->set('list_limitstart', $limitstart);
		$surv_id		= intval( mosGetParam( $_REQUEST, 'surv_id', $SF_SESSION->get('list_surv_id', 0) ) );
		$SF_SESSION->set('list_surv_id', $surv_id);
		
		$filt_status	= intval( mosGetParam( $_REQUEST, 'filt_status', $SF_SESSION->get('list_filt_status', 2) ) );		
		$SF_SESSION->set('list_filt_status', $filt_status);
		$filt_utype		= intval( mosGetParam( $_REQUEST, 'filt_utype', $SF_SESSION->get('list_filt_utype', 0) ) );	
		$SF_SESSION->set('list_filt_utype', $filt_utype);
		$filt_ulist		= intval( mosGetParam( $_REQUEST, 'filt_ulist', $SF_SESSION->get('list_filt_ulist', 0) ) );	
		$SF_SESSION->set('list_filt_ulist', $filt_ulist);
		$filter_quest	= intval( mosGetParam( $_REQUEST, 'filter_quest', $SF_SESSION->get('list_filter_quest', 0) ) );
		$SF_SESSION->set('list_filter_quest', $filter_quest);
		$filter_ans		= intval( mosGetParam( $_REQUEST, 'filter_ans', $SF_SESSION->get('list_filter_ans', 0) ) );
		$SF_SESSION->set('list_filter_ans', $filter_ans);
	}
	#$javascript = 'onchange="document.adminForm.submit();"';
	$javascript = 'onchange="submitbutton(\'reports\');"';
	$filter_quest = array();
	$filter_ans = array();
	$i = 0;
	$j = 0;
	if ($surv_id) {
		$query = "SELECT count(*) FROM #__survey_force_quests WHERE published = 1 AND sf_survey = '".$surv_id."' and id = '".$filter_quest."' and sf_qtype IN (2,3)";
		$database->setQuery( $query );
		if (!$database->LoadResult()) {
			if (isset($_REQUEST['filter_quest'])) {
				$k = 0;
				foreach ($_REQUEST['filter_quest'] as $filt_row) {
					if ($filt_row) {
						$filter_quest[$i] = $filt_row;
						$sel_ans = 0;
						if (isset($_REQUEST['filter_ans'][$k]) && $_REQUEST['filter_ans'][$k]) {
							$sel_ans = $_REQUEST['filter_ans'][$k];
						}
						$filter_ans[$i] = $sel_ans;
						$i ++;
						$k ++;
					}
				}
			}
		}
	}	
	
	if (($filt_utype - 1) != 2) {$filt_ulist = 0;}

	// get the subset (based on limits) of required records
	$query = "SELECT distinct sf_s.sf_name as survey_name, sf_s.id as survey_id "
	. "\n FROM #__survey_force_user_starts as sf_ust, #__survey_force_survs as sf_s";
	$r = 0;
	foreach ($filter_ans as $filt_ans) {
		$query .= ($filt_ans ? ", #__survey_force_user_answers as sf_ans".$r : '');
		$r ++;
	}
	$query .= ""
	. "\n WHERE sf_ust.survey_id = sf_s.id"
	. ( $surv_id ? "\n and sf_s.id = $surv_id" : '' )
	. ( $front_end && $my->usertype != 'Super Administrator'? " AND sf_s.sf_author = '{$my->id}' ": ' ')
	. ( $filt_status ? "\n and sf_ust.is_complete = '".($filt_status - 1)."'" : '' )
	. ( $filt_utype ? "\n and sf_ust.usertype = '".($filt_utype -1)."'" : '' )
	. ( $filt_ulist ? "\n and sf_u.list_id = '".($filt_ulist)."'" : '' );

	if ((count($cid) > 0) && ($cid[0] != 0)) {
		$cids =implode(',', $cid);
		$query .= "\n and sf_ust.id in (".$cids.")";
	}

	$r = 0;
	foreach ($filter_ans as $filt_ans) {
		$query .= ( $filt_ans ? "\n and sf_ans".$r.".start_id = sf_ust.id and sf_ans".$r.".answer = '".($filt_ans)."'" : '' );
		$r ++;
	
	}
	$query .= "\n ORDER BY sf_s.sf_name"
	;
	$database->SetQuery($query);
	$rows = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
	
	$query = "SELECT distinct sf_ust.id "
	. "\n FROM #__survey_force_user_starts as sf_ust, #__survey_force_survs as sf_s";
	$r = 0;
	foreach ($filter_ans as $filt_ans) {
		$query .= ($filt_ans ? ", #__survey_force_user_answers as sf_ans".$r : '');
		$r ++;
	}
	$query .= ""
	. "\n WHERE sf_ust.survey_id = sf_s.id"
	. ( $surv_id ? "\n and sf_s.id = $surv_id" : '' )
	. ( $filt_status ? "\n and sf_ust.is_complete = '".($filt_status - 1)."'" : '' )
	. ( $filt_utype ? "\n and sf_ust.usertype = '".($filt_utype -1)."'" : '' )
	. ( $filt_ulist ? "\n and sf_u.list_id = '".($filt_ulist)."'" : '' );

	if ((count($cid) > 0) && ($cid[0] != 0)) {
		$cids =implode(',', $cid);
		$query .= "\n and sf_ust.id in (".$cids.")";
	}

	$r = 0;
	foreach ($filter_ans as $filt_ans) {
		$query .= ( $filt_ans ? "\n and sf_ans".$r.".start_id = sf_ust.id and sf_ans".$r.".answer = '".($filt_ans)."'" : '' );
		$r ++;
	
	}
	$query .= "\n ORDER BY sf_ust.id";
	$database->SetQuery( $query );
	$start_ids = array();
	$start_ids = $database->LoadResultArray();
	$start_ids[] = 0;
	$starts_str = implode(',',$start_ids);
	
	$ri = 0;
	while ($ri < count($rows)) {
		
			$query = "SELECT * FROM #__survey_force_survs WHERE id = '".$rows[$ri]->survey_id."'";
			$database->SetQuery( $query );
			$rows[$ri]->survey_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
		
			$query = "SELECT q.*"
			. "\n FROM #__survey_force_quests as q"
			. "\n WHERE q.published = 1 AND q.sf_survey = '".$rows[$ri]->survey_id."' AND sf_qtype NOT IN (7, 8)"
			. "\n ORDER BY q.ordering, q.id ";
			$database->SetQuery( $query );
			$rows[$ri]->questions_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
			$qi = 0;
			$rows[$ri]->questions_data[$qi]->answer = '';
					while ( $qi < count($rows[$ri]->questions_data) ) {
						if ($rows[$ri]->questions_data[$qi]->sf_impscale) {
							$query = "SELECT iscale_name FROM #__survey_force_iscales WHERE id = '".$rows[$ri]->questions_data[$qi]->sf_impscale."'";
							$database->SetQuery( $query );
							$rows[$ri]->questions_data[$qi]->iscale_name = $database->loadResult();
				
							$query = "SELECT count(id) FROM #__survey_force_user_answers_imp"
							. "\n WHERE quest_id = '".$rows[$ri]->questions_data[$qi]->id."' and survey_id = '".$rows[$ri]->questions_data[$qi]->sf_survey."'"
							. "\n AND iscale_id = '".$rows[$ri]->questions_data[$qi]->sf_impscale."' and start_id IN (".$starts_str.")";
							$database->SetQuery( $query );
							$rows[$ri]->questions_data[$qi]->total_iscale_answers = $database->LoadResult();
				
							$query = "SELECT b.isf_name, count(a.id) as ans_count FROM #__survey_force_iscales_fields as b"
							. "\n LEFT JOIN #__survey_force_user_answers_imp as a ON"
							. "\n a.quest_id = '".$rows[$ri]->questions_data[$qi]->id."'"
							. "\n and a.survey_id = '".$rows[$ri]->questions_data[$qi]->sf_survey."'"
							. "\n and a.iscale_id = '".$rows[$ri]->questions_data[$qi]->sf_impscale."'"
							. "\n and a.start_id IN (".$starts_str.") and a.iscalefield_id = b.id "
							. "\n WHERE b.iscale_id = '".$rows[$ri]->questions_data[$qi]->sf_impscale."'"
							. "\n GROUP BY b.isf_name ORDER BY  b.ordering";//ans_count DESC,
							$database->SetQuery( $query );
							$ans_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
							
							$rows[$ri]->questions_data[$qi]->answer_imp = array();
							$j = 0;
							while ( $j < count($ans_data) ) {
								$rows[$ri]->questions_data[$qi]->answer_imp[$j]->num = $j;
								$rows[$ri]->questions_data[$qi]->answer_imp[$j]->ftext = $ans_data[$j]->isf_name;
								$rows[$ri]->questions_data[$qi]->answer_imp[$j]->ans_count = $ans_data[$j]->ans_count;
								$j ++;
							}
						}
						$rows[$ri]->questions_data[$qi]->sf_qtext = trim(strip_tags($rows[$ri]->questions_data[$qi]->sf_qtext,'<a><b><i><u>'));
						switch ($rows[$ri]->questions_data[$qi]->sf_qtype) {
							case 2:
								$query = "SELECT count(id) FROM #__survey_force_user_answers"
								. "\n WHERE quest_id = '".$rows[$ri]->questions_data[$qi]->id."'"
								. "\n and survey_id = '".$rows[$ri]->questions_data[$qi]->sf_survey."'"
								. "\n and start_id IN (".$starts_str.") ";
								$database->SetQuery( $query );
								$rows[$ri]->questions_data[$qi]->total_answers = $database->LoadResult();
				
								$query = "SELECT b.ftext, count(a.answer) as ans_count FROM #__survey_force_fields as b"
								. "\n LEFT JOIN #__survey_force_user_answers as a ON ( a.answer = b.id and a.start_id IN (".$starts_str.") AND a.quest_id = '".$rows[$ri]->questions_data[$qi]->id."' )"
								. "\n WHERE b.quest_id = '".$rows[$ri]->questions_data[$qi]->id."'"
								. "\n GROUP BY b.ftext ORDER BY b.ordering";//ans_count DESC
								$database->SetQuery( $query );
								$ans_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
								$rows[$ri]->questions_data[$qi]->answer = array();
								$j = 0;
								while ( $j < count($ans_data) ) {
									$rows[$ri]->questions_data[$qi]->answer[$j]->num = $j;
									$rows[$ri]->questions_data[$qi]->answer[$j]->ftext = $ans_data[$j]->ftext;
									$rows[$ri]->questions_data[$qi]->answer[$j]->ans_count = $ans_data[$j]->ans_count;
									$j ++;
								}
							break;
							case 3:
								$query = "SELECT count(distinct start_id) FROM #__survey_force_user_answers"
								. "\n WHERE quest_id = '".$rows[$ri]->questions_data[$qi]->id."'"
								. "\n and survey_id = '".$rows[$ri]->questions_data[$qi]->sf_survey."' "
								. "\n and start_id IN (".$starts_str.") ";
								$database->SetQuery( $query );
								$rows[$ri]->questions_data[$qi]->total_answers = $database->LoadResult();
				
								$query = "SELECT b.ftext, count(a.answer) as ans_count FROM #__survey_force_fields as b"
								. "\n LEFT JOIN #__survey_force_user_answers as a ON ( a.answer = b.id and a.start_id IN (".$starts_str.") AND a.quest_id = '".$rows[$ri]->questions_data[$qi]->id."' )"
								. "\n WHERE b.quest_id = '".$rows[$ri]->questions_data[$qi]->id."'"
								. "\n GROUP BY b.ftext ORDER BY b.ordering";//ans_count DESC
								$database->SetQuery( $query );
								$ans_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
								$rows[$ri]->questions_data[$qi]->answer = array();
								$j = 0;
								while ( $j < count($ans_data) ) {
									$rows[$ri]->questions_data[$qi]->answer[$j]->num = $j;
									$rows[$ri]->questions_data[$qi]->answer[$j]->ftext = $ans_data[$j]->ftext;
									$rows[$ri]->questions_data[$qi]->answer[$j]->ans_count = $ans_data[$j]->ans_count;
									$j ++;
								}
							break;
							case 4:
								$n = substr_count($rows[$ri]->questions_data[$qi]->sf_qtext, '{x}')+substr_count($rows[$ri]->questions_data[$qi]->sf_qtext, '{y}');
								if ($n > 0) {
									$query = "SELECT id FROM #__survey_force_user_answers"
									. "\n WHERE quest_id = '".$rows[$ri]->questions_data[$qi]->id."'"
									. "\n and survey_id = '".$rows[$ri]->questions_data[$qi]->sf_survey."'"
									. "\n and start_id IN (".$starts_str.") GROUP BY start_id, quest_id";
									$database->SetQuery( $query );
									$rows[$ri]->questions_data[$qi]->total_answers = count($database->LoadResultArray());
									$rows[$ri]->questions_data[$qi]->answer = array();									
									$rows[$ri]->questions_data[$qi]->answers_top100 = array();
									$rows[$ri]->questions_data[$qi]->answer_count = $n;
									for($j = 0; $j < $n; $j++) {
										$query = "SELECT answer FROM #__survey_force_user_answers WHERE ans_field = ".($j+1)
												." AND quest_id = '".$rows[$ri]->questions_data[$qi]->id."'"
												." AND survey_id = '".$rows[$ri]->questions_data[$qi]->sf_survey."'"
												." AND start_id IN (".$starts_str.") ";
										$database->SetQuery( $query );
										$ans_txt_data = @array_merge(array(0=>0),$database->LoadResultArray());
										
										$query = "SELECT b.ans_txt, count(a.answer) as ans_count FROM #__survey_force_user_ans_txt as b,"
										. "\n #__survey_force_user_answers as a"
										. "\n WHERE a.quest_id = '".$rows[$ri]->questions_data[$qi]->id."'"
										. "\n and a.answer = b.id and a.start_id IN (".$starts_str.")"
										. "\n AND a.answer IN (".implode(',', $ans_txt_data).") "
										. "\n GROUP BY b.ans_txt ORDER BY ans_count DESC LIMIT 0,5";
										$database->SetQuery( $query );
										$ans_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
										$jj = 0;
										$tmp = array();
										while ( $jj < count($ans_data) ) {
											$tmp[$jj]->num = $jj;
											$tmp[$jj]->ftext = $ans_data[$jj]->ans_txt;
											$tmp[$jj]->ans_count = $ans_data[$jj]->ans_count;
											$jj ++;
										}
										$rows[$ri]->questions_data[$qi]->answer[$j] = $tmp;
										
										$query = "SELECT b.ans_txt FROM #__survey_force_user_ans_txt as b, #__survey_force_user_answers as a"
												. "\n WHERE a.quest_id = '".$rows[$ri]->questions_data[$qi]->id."' and a.answer = b.id"
												. "\n and a.start_id IN (".$starts_str.")"
												. "\n AND a.answer IN (".implode(',', $ans_txt_data).") "
												. "\n ORDER BY a.sf_time DESC LIMIT 0,100";
										$database->SetQuery( $query );
										$ans_data = $database->loadResultArray();
										$rows[$ri]->questions_data[$qi]->answers_top100[$j] = implode(', ',$ans_data);
									}
								}
								else {
									$query = "SELECT id FROM #__survey_force_user_answers"
									. "\n WHERE quest_id = '".$rows[$ri]->questions_data[$qi]->id."'"
									. "\n and survey_id = '".$rows[$ri]->questions_data[$qi]->sf_survey."'"
									. "\n and start_id IN (".$starts_str.") GROUP BY start_id, quest_id";
									$database->SetQuery( $query );
									$rows[$ri]->questions_data[$qi]->total_answers = count($database->LoadResultArray());
					
									$query = "SELECT b.ans_txt, count(a.answer) as ans_count FROM #__survey_force_user_ans_txt as b,"
									. "\n #__survey_force_user_answers as a"
									. "\n WHERE a.quest_id = '".$rows[$ri]->questions_data[$qi]->id."'"
									. "\n and a.answer = b.id and a.start_id IN (".$starts_str.")"
									. "\n GROUP BY b.ans_txt ORDER BY ans_count DESC LIMIT 0,5";
									$database->SetQuery( $query );
									$ans_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
									$rows[$ri]->questions_data[$qi]->answer = array();
									$j = 0;
									while ( $j < count($ans_data) ) {
										$rows[$ri]->questions_data[$qi]->answer[$j]->num = $j;
										$rows[$ri]->questions_data[$qi]->answer[$j]->ftext = $ans_data[$j]->ans_txt;
										$rows[$ri]->questions_data[$qi]->answer[$j]->ans_count = $ans_data[$j]->ans_count;
										$j ++;
									}
									$query = "SELECT b.ans_txt FROM #__survey_force_user_ans_txt as b, #__survey_force_user_answers as a"
									. "\n WHERE a.quest_id = '".$rows[$ri]->questions_data[$qi]->id."' and a.answer = b.id"
									. "\n and a.start_id IN (".$starts_str.")"
									. "\n ORDER BY a.sf_time DESC LIMIT 0,100";
									$database->SetQuery( $query );
									$ans_data = $database->loadResultArray();
									$rows[$ri]->questions_data[$qi]->answers_top100 = implode(', ',$ans_data);
								}
							break;
							case 1:
								$query = "SELECT count(distinct start_id) FROM #__survey_force_user_answers"
								. "\n WHERE quest_id = '".$rows[$ri]->questions_data[$qi]->id."'"
								. "\n and survey_id = '".$rows[$ri]->questions_data[$qi]->sf_survey."'"
								. "\n and start_id IN (".$starts_str.")";
								$database->SetQuery( $query );
								$rows[$ri]->questions_data[$qi]->total_answers = $database->LoadResult();
								
								$query = "SELECT * FROM #__survey_force_fields"
								. "\n WHERE quest_id = '".$rows[$ri]->questions_data[$qi]->id."' ORDER by ordering";
								$database->SetQuery( $query );
								$f_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
								$j = 0;
								$rows[$ri]->questions_data[$qi]->answer = array();
								while ( $j < count($f_data) ) {
									$query = "SELECT b.stext, count(a.answer) as ans_count FROM #__survey_force_scales as b"
									. "\n LEFT JOIN #__survey_force_user_answers as a"
									. "\n ON ( a.ans_field = b.id and a.answer = '".$f_data[$j]->id."' "
									. "\n and a.start_id IN (".$starts_str.") AND a.quest_id = '".$rows[$ri]->questions_data[$qi]->id."' )"
									. "\n WHERE b.quest_id = '".$rows[$ri]->questions_data[$qi]->id."'"
									. "\n GROUP BY b.stext ORDER BY b.ordering";
									$database->SetQuery( $query );
									$ans_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
									$rows[$ri]->questions_data[$qi]->answer[$j]->full_ans = array();
									$jj = 0;
									$rows[$ri]->questions_data[$qi]->answer[$j]->ftext = $f_data[$j]->ftext; 
									while ( $jj < count($ans_data) ) {
										$rows[$ri]->questions_data[$qi]->answer[$j]->full_ans[$jj]->ftext = $ans_data[$jj]->stext;
										$rows[$ri]->questions_data[$qi]->answer[$j]->full_ans[$jj]->ans_count = $ans_data[$jj]->ans_count;
										$jj ++;
									}
									$j++;
								}
							break;
							case 5:
							case 6:
							case 9:
								$query = "SELECT count(distinct start_id) FROM #__survey_force_user_answers"
								. "\n WHERE quest_id = '".$rows[$ri]->questions_data[$qi]->id."'"
								. "\n and survey_id = '".$rows[$ri]->questions_data[$qi]->sf_survey."'"
								. "\n and start_id IN (".$starts_str.")";
								$database->SetQuery( $query );
								$rows[$ri]->questions_data[$qi]->total_answers = $database->LoadResult();
								
								$query = "SELECT * FROM #__survey_force_fields"
								. "\n WHERE quest_id = '".$rows[$ri]->questions_data[$qi]->id."' and is_main = '1' ORDER by ordering";
								$database->SetQuery( $query );
								$f_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
								$j = 0;
								$rows[$ri]->questions_data[$qi]->answer = array();
								while ( $j < count($f_data) ) {
									$query = "SELECT b.ftext, count(a.answer) as ans_count FROM #__survey_force_fields as b"
									. "\n LEFT JOIN #__survey_force_user_answers as a ON a.ans_field = b.id"
									. "\n and a.answer = '".$f_data[$j]->id."'"
									. "\n and a.quest_id = '".$rows[$ri]->questions_data[$qi]->id."'"
									. "\n and a.survey_id = '".$rows[$ri]->questions_data[$qi]->sf_survey."'"
									. "\n and a.start_id IN (".$starts_str.")"
									. "\n WHERE b.quest_id = '".$rows[$ri]->questions_data[$qi]->id."' and b.is_main = '0'"
									. "\n GROUP BY b.ftext ORDER BY b.ordering ";//ans_count DESC
									
									$database->SetQuery( $query );
									$ans_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
									$rows[$ri]->questions_data[$qi]->answer[$j]->full_ans = array();
									$jj = 0;
									$rows[$ri]->questions_data[$qi]->answer[$j]->ftext = $f_data[$j]->ftext; 
									while ( $jj < count($ans_data) ) {
										$rows[$ri]->questions_data[$qi]->answer[$j]->full_ans[$jj]->ftext = $ans_data[$jj]->ftext;
										$rows[$ri]->questions_data[$qi]->answer[$j]->full_ans[$jj]->ans_count = $ans_data[$jj]->ans_count;
										$jj ++;
									}
									$j++;
								}
							break;
						}
					$qi++;
					}
	
		$ri ++;
	}
	SF_PrintReportsCSV_sum( $rows );
}

function SF_removeRep( &$cid, $option ) {
	global $database, $front_end;
	if (count( $cid )) {
		$cids = implode( ',', $cid );
		$query = "DELETE FROM #__survey_force_user_starts"
		. "\n WHERE id IN ( $cids )";
		$database->setQuery( $query );
		if (!$database->query()) {
			echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		}
		
		$query = "DELETE FROM #__survey_force_user_chain "
		. "\n WHERE start_id IN ( $cids )";
		$database->setQuery( $query );
		if (!$database->query()) {
			echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		}
		
		$query = "DELETE FROM #__survey_force_user_answers"
		. "\n WHERE start_id IN ( $cids )";
		$database->setQuery( $query );
		if (!$database->query()) {
			echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		}
		$query = "DELETE FROM #__survey_force_user_ans_txt"
		. "\n WHERE start_id IN ( $cids )";
		$database->setQuery( $query );
		if (!$database->query()) {
			echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		}
		$query = "DELETE FROM #__survey_force_user_answers_imp"
		. "\n WHERE start_id IN ( $cids )";
		$database->setQuery( $query );
		if (!$database->query()) {
			echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		}
	}
	if (!$front_end)
		mosRedirect( "index2.php?option=$option&task=reports" );
	else {
		global $Itemid, $Itemid_s;
		mosRedirect( SFRoute("index.php?option=$option&task=reports{$Itemid_s}") );
	}
}

function SF_removeRepAll( $option ) {
	global $database, $mainframe, $mosConfig_list_limit, $front_end, $my;
	
	$limit 			= intval( $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', $mosConfig_list_limit ) );
	$limitstart 	= intval( $mainframe->getUserStateFromRequest( "view{$option}limitstart", 'limitstart', 0 ) );
	$surv_id 		= intval( $mainframe->getUserStateFromRequest( "surv_id{$option}", 'surv_id', 0 ) );
	$filt_status	= intval( $mainframe->getUserStateFromRequest( "filt_status{$option}", 'filt_status', 2 ) );
	$filt_utype		= intval( $mainframe->getUserStateFromRequest( "filt_utype{$option}", 'filt_utype', 0 ) );
	$filt_ulist		= intval( $mainframe->getUserStateFromRequest( "filt_ulist{$option}", 'filt_ulist', 0 ) );
	if ($limit == 0) $limit = 999999;
	if ( $front_end ) {
		global $SF_SESSION;
		$limit		= intval( mosGetParam( $_REQUEST, 'limit', $SF_SESSION->get('list_limit',$mainframe->getCfg('list_limit')) ) );
		if ($limit == 0) $limit = 999999;
		$SF_SESSION->set('list_limit', $limit);
		$limitstart = intval( mosGetParam( $_REQUEST, 'limitstart', $SF_SESSION->get('list_limitstart', 0) ) );
		$SF_SESSION->set('list_limitstart', $limitstart);
		$surv_id		= intval( mosGetParam( $_REQUEST, 'surv_id', $SF_SESSION->get('list_surv_id', 0) ) );
		$SF_SESSION->set('list_surv_id', $surv_id);
		
		$filt_status	= intval( mosGetParam( $_REQUEST, 'filt_status', $SF_SESSION->get('list_filt_status', 2) ) );		
		$SF_SESSION->set('list_filt_status', $filt_status);
		$filt_utype		= intval( mosGetParam( $_REQUEST, 'filt_utype', $SF_SESSION->get('list_filt_utype', 0) ) );	
		$SF_SESSION->set('list_filt_utype', $filt_utype);
		$filt_ulist		= intval( mosGetParam( $_REQUEST, 'filt_ulist', $SF_SESSION->get('list_filt_ulist', 0) ) );	
		$SF_SESSION->set('list_filt_ulist', $filt_ulist);
	}
	
	$filter_ans = array();
	$i = 0;
	$j = 0;
	if ($surv_id) {		
		if (isset($_REQUEST['filter_quest'])) {
			$k = 0;
			foreach ($_REQUEST['filter_quest'] as $filt_row) {
				if ($filt_row) {
					$sel_ans = 0;
					if (isset($_REQUEST['filter_ans'][$k]) && $_REQUEST['filter_ans'][$k]) {
						$sel_ans = $_REQUEST['filter_ans'][$k];
					}						
					$filter_ans[$i] = $sel_ans;
					$i ++;
					$k ++;
				}
			}
		}
	}

	if (($filt_utype - 1) != 2) {$filt_ulist = 0;}
	
	// get the subset (based on limits) of required records
	$query = "SELECT sf_ust.id "
	. "\n FROM (#__survey_force_user_starts as sf_ust, #__survey_force_survs as sf_s";
	$r = 0;
	foreach ($filter_ans as $filt_ans) {
		$query .= ($filt_ans ? ", #__survey_force_user_answers as sf_ans".$r : '');
		$r ++;
	}
	$query .= ")"
	. "\n LEFT JOIN #__users as u ON u.id = sf_ust.user_id and sf_ust.usertype=1"
	. "\n LEFT JOIN #__survey_force_users as sf_u ON sf_u.id = sf_ust.user_id and sf_ust.usertype=2"
	. "\n WHERE sf_ust.survey_id = sf_s.id"
	. ( $surv_id ? "\n and sf_s.id = $surv_id" : '' )
	. ( $front_end && $my->usertype != 'Super Administrator'? " AND sf_s.sf_author = {$my->id} ": ' ')
	. ( $filt_status ? "\n and sf_ust.is_complete = '".($filt_status - 1)."'" : '' )
	. ( $filt_utype ? "\n and sf_ust.usertype = '".($filt_utype -1)."'" : '' )
	. ( $filt_ulist ? "\n and sf_u.list_id = '".($filt_ulist)."'" : '' );
	$r = 0;
	foreach ($filter_ans as $filt_ans) {
		$query .= ( $filt_ans ? "\n and sf_ans".$r.".start_id = sf_ust.id and sf_ans".$r.".answer = '".($filt_ans)."'" : '' );
		$r ++;
	
	}
	$query .= "\n ORDER BY sf_ust.sf_time DESC ";
	$database->SetQuery($query);
	$cid = $database->LoadResultArray();
	
	if (count( $cid )) {
		$cids = implode( ',', $cid );
		$query = "DELETE FROM #__survey_force_user_starts"
		. "\n WHERE id IN ( $cids )";
		$database->setQuery( $query );
		if (!$database->query()) {
			echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		}
		
		$query = "DELETE FROM #__survey_force_user_chain "
		. "\n WHERE start_id IN ( $cids )";
		$database->setQuery( $query );
		if (!$database->query()) {
			echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		}
		
		$query = "DELETE FROM #__survey_force_user_answers"
		. "\n WHERE start_id IN ( $cids )";
		$database->setQuery( $query );
		if (!$database->query()) {
			echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		}
		$query = "DELETE FROM #__survey_force_user_ans_txt"
		. "\n WHERE start_id IN ( $cids )";
		$database->setQuery( $query );
		if (!$database->query()) {
			echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		}
		$query = "DELETE FROM #__survey_force_user_answers_imp"
		. "\n WHERE start_id IN ( $cids )";
		$database->setQuery( $query );
		if (!$database->query()) {
			echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		}
	}
	if (!$front_end)
		mosRedirect( "index2.php?option=$option&task=reports" );
	else {
		global $Itemid, $Itemid_s;
		mosRedirect( SFRoute("index.php?option=$option&task=reports{$Itemid_s}") );
	}
}


function SF_ViewRepResult( $id, $option, $is_pdf = 0) {
	global $database, $front_end, $my;

	$query = "SELECT s.*, u.username reg_username, u.name reg_name, u.email reg_email,"
	. "\n sf_u.name as inv_name, sf_u.lastname as inv_lastname, sf_u.email as inv_email"
	. "\n FROM #__survey_force_user_starts as s"
	. "\n LEFT JOIN #__users as u ON u.id = s.user_id and s.usertype=1"
	. "\n LEFT JOIN #__survey_force_users as sf_u ON sf_u.id = s.user_id and s.usertype=2"
	. "\n WHERE s.id = '".$id."'";
	$database->SetQuery( $query );
	$start_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
	if (!count($start_data)) {
		echo "<script> alert('".JText::_('COM_SF_NO_RESULTS_FOUND')."'); window.history.go(-1);</script>\n";
		exit;
	}
	
	$no_answer_str = JText::_('COM_SF_NO_ANSWER');
	if ( $front_end ) {
		global $sf_lang;
		$no_answer_str = $sf_lang['SURVEY_NO_ANSWER'];
	}


	$query = "SELECT * FROM #__survey_force_survs WHERE id = '".$start_data[0]->survey_id."' "
			.($front_end && $my->usertype != 'Super Administrator'? " AND sf_author = '{$my->id}' ": ' ');
	$database->SetQuery( $query );
	$survey_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());

	$query = "SELECT q.*"
	. "\n FROM #__survey_force_quests as q"
	. "\n WHERE q.published = 1 AND q.sf_survey = '".$start_data[0]->survey_id."' AND sf_qtype NOT IN (7, 8) "
	. "\n ORDER BY q.ordering, q.id ";
	$database->SetQuery( $query );
	$questions_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());

	$i = 0;
	$questions_data[$i]->answer = '';
	if (is_array($questions_data) && count($questions_data) > 0)
	while ( $i < count($questions_data) ) {
		$questions_data[$i]->sf_qtext = trim(strip_tags(@$questions_data[$i]->sf_qtext, '<a><b><i><u>'));
		if (@$questions_data[$i]->sf_impscale) {
			$query = "SELECT iscale_name FROM #__survey_force_iscales WHERE id = '".$questions_data[$i]->sf_impscale."'";
			$database->SetQuery( $query );
			$questions_data[$i]->iscale_name = $database->loadResult();

			$query = "SELECT iscalefield_id FROM #__survey_force_user_answers_imp"
			. "\n WHERE quest_id = '".$questions_data[$i]->id."' and survey_id = '".$questions_data[$i]->sf_survey."'"
			. "\n AND iscale_id = '".$questions_data[$i]->sf_impscale."'"
			. "\n and start_id = '".$id."'";
			$database->SetQuery( $query );
			$ans_inf = $database->LoadResult();
			
			$questions_data[$i]->answer_imp = array();
			$query = "SELECT * FROM #__survey_force_iscales_fields WHERE iscale_id = '".$questions_data[$i]->sf_impscale."'"
			. "\n ORDER BY ordering";
			$database->SetQuery( $query );
			$tmp_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
			$j = 0;
			while ( $j < count($tmp_data) ) {
				$questions_data[$i]->answer_imp[$j]->num = $j;
				$questions_data[$i]->answer_imp[$j]->f_id = $tmp_data[$j]->id;
				$questions_data[$i]->answer_imp[$j]->f_text = $tmp_data[$j]->isf_name;
				$questions_data[$i]->answer_imp[$j]->alt_text = '';
				if ($ans_inf == $tmp_data[$j]->id) {
					$questions_data[$i]->answer_imp[$j]->alt_text = '1';
					$questions_data[$i]->answer_imp[$j]->alt_id = $ans_inf;
				}
				$j ++;
			}
		}

		switch (@$questions_data[$i]->sf_qtype) {
			case 1:
				$questions_data[$i]->answer = array();
				$questions_data[$i]->scale = '';
				$query = "SELECT stext FROM #__survey_force_scales WHERE quest_id = '".$questions_data[$i]->id."'"
				. "\n and quest_id = '".$questions_data[$i]->id."'"
				. "\n ORDER BY ordering";
				$database->SetQuery( $query );
				$tmp_data = $database->loadResultArray();
				$questions_data[$i]->scale = implode(', ', $tmp_data);
				
				$query = "SELECT * FROM #__survey_force_fields WHERE quest_id = '".$questions_data[$i]->id."'"
				. "\n and is_main = 1 ORDER BY ordering";
				$database->SetQuery( $query );
				$tmp_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
				
				$query = "SELECT * FROM #__survey_force_user_answers WHERE quest_id = '".$questions_data[$i]->id."' and survey_id = '".$questions_data[$i]->sf_survey."' and start_id = '".$id."'";
				$database->SetQuery( $query );
				$ans_inf_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
		
				$j = 0;
				while ( $j < count($tmp_data) ) {
					$questions_data[$i]->answer[$j]->num = $j;
					$questions_data[$i]->answer[$j]->f_id = $tmp_data[$j]->id;
					$questions_data[$i]->answer[$j]->f_text = $tmp_data[$j]->ftext;
					$questions_data[$i]->answer[$j]->alt_text = $no_answer_str;
					foreach ($ans_inf_data as $ans_data) {
						if ($ans_data->answer == $tmp_data[$j]->id) {
							$query = "SELECT * FROM #__survey_force_scales WHERE id = '".$ans_data->ans_field."'"
							. "\n and quest_id = '".$questions_data[$i]->id."'"
							. "\n ORDER BY ordering";
							$database->SetQuery( $query );
							$alt_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
							$questions_data[$i]->answer[$j]->alt_text = ($ans_data->ans_field==0?$no_answer_str:$alt_data[0]->stext);
							$questions_data[$i]->answer[$j]->alt_id = $ans_data->ans_field;
						}
					}
					$j ++;
				}
			break;
			case 2:
				$query = "SELECT a.answer, b.ans_txt FROM ( #__survey_force_user_answers AS a, #__survey_force_quests AS c ) LEFT JOIN #__survey_force_user_ans_txt AS b ON ( a.ans_field = b.id AND c.sf_qtype = 2 ) WHERE c.published = 1 AND a.quest_id = '".$questions_data[$i]->id."' AND a.survey_id = '".$questions_data[$i]->sf_survey."' AND a.start_id = '".$id."' AND c.id = a.quest_id ";
				$database->SetQuery( $query );
				$ans_inf = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());

				$questions_data[$i]->answer = array();
				$query = "SELECT * FROM #__survey_force_fields WHERE quest_id = '".$questions_data[$i]->id."'"
				. "\n ORDER BY ordering";
				$database->SetQuery( $query );
				$tmp_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
				$j = 0;
				while ( $j < count($tmp_data) ) {
					$questions_data[$i]->answer[$j]->num = $j;
					$questions_data[$i]->answer[$j]->f_id = $tmp_data[$j]->id;
					$questions_data[$i]->answer[$j]->f_text = $tmp_data[$j]->ftext;
					$questions_data[$i]->answer[$j]->alt_text = '';
					if (count($ans_inf) > 0 && $ans_inf[0]->answer == $tmp_data[$j]->id) {
						$questions_data[$i]->answer[$j]->f_text = $tmp_data[$j]->ftext.($ans_inf[0]->ans_txt != '' ?' ('.$ans_inf[0]->ans_txt.')':'');
						$questions_data[$i]->answer[$j]->alt_text = '1';
						$questions_data[$i]->answer[$j]->alt_id = $ans_inf;
					}
					$j ++;
				}
			break;
			case 3:
				$query = "SELECT a.answer, b.ans_txt FROM ( #__survey_force_user_answers AS a, #__survey_force_quests AS c ) LEFT JOIN #__survey_force_user_ans_txt AS b ON ( a.ans_field = b.id AND c.sf_qtype = 3 )	WHERE c.published = 1 AND a.quest_id = '".$questions_data[$i]->id."' AND a.survey_id = '".$questions_data[$i]->sf_survey."' AND a.start_id = '".$id."' AND c.id = a.quest_id ";
				$database->SetQuery( $query );
				$ans_inf_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
				
				$questions_data[$i]->answer = array();
				$query = "SELECT * FROM #__survey_force_fields WHERE quest_id = '".$questions_data[$i]->id."'"
				. "\n ORDER BY ordering";
				$database->SetQuery( $query );
				$tmp_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
				$j = 0;
				while ( $j < count($tmp_data) ) {
					$questions_data[$i]->answer[$j]->num = $j;
					$questions_data[$i]->answer[$j]->f_id = $tmp_data[$j]->id;
					$questions_data[$i]->answer[$j]->f_text = $tmp_data[$j]->ftext;
					$questions_data[$i]->answer[$j]->alt_text = '';
					foreach ($ans_inf_data as $ans_data) {
						if ($ans_data->answer == $tmp_data[$j]->id) {
							$questions_data[$i]->answer[$j]->f_text = $tmp_data[$j]->ftext . ($ans_data->ans_txt != '' ?' ('.$ans_data->ans_txt.')':'');
							$questions_data[$i]->answer[$j]->alt_text = '1';
							$questions_data[$i]->answer[$j]->alt_id = $ans_data->answer;
						}
					}
					$j ++;
				}
			break;
			case 4:
				$n = substr_count($questions_data[$i]->sf_qtext, "{x}")+substr_count($questions_data[$i]->sf_qtext, "{y}");
				if ($n > 0) {
					$query = "SELECT b.ans_txt, a.ans_field FROM #__survey_force_user_answers as a LEFT JOIN #__survey_force_user_ans_txt as b ON a.answer = b.id	WHERE a.quest_id = '".$questions_data[$i]->id."' AND a.survey_id = '".$questions_data[$i]->sf_survey."' AND a.start_id = '".$id."' ORDER BY a.ans_field ";
					$database->SetQuery( $query );
					$ans_inf_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
					$questions_data[$i]->answer = $ans_inf_data;
					$questions_data[$i]->answer_count = $n;					
				}
				else {
					$query = "SELECT b.ans_txt FROM #__survey_force_user_answers as a, #__survey_force_user_ans_txt as b WHERE a.quest_id = '".$questions_data[$i]->id."' and a.survey_id = '".$questions_data[$i]->sf_survey."' and a.start_id = '".$id."' and a.answer = b.id";
					$database->SetQuery( $query );
					$ans_inf_data = $database->LoadResult();
					$questions_data[$i]->answer = ($ans_inf_data == '')?$no_answer_str:$ans_inf_data;
				}
			break;
			case 5:
			case 6:
			case 9:
				$query = "SELECT a.* , b.ans_txt FROM ( #__survey_force_user_answers AS a, #__survey_force_quests AS c )
LEFT JOIN #__survey_force_user_ans_txt AS b ON ( a.next_quest_id = b.id AND c.sf_qtype = 9 ) WHERE c.published = 1 AND a.quest_id = '".$questions_data[$i]->id."' AND a.survey_id = '".$questions_data[$i]->sf_survey."' AND a.start_id = '".$id."' AND c.id = a.quest_id";
				$database->SetQuery( $query );		
				$ans_inf_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());

				$questions_data[$i]->answer = array();
				$query = "SELECT * FROM #__survey_force_fields WHERE quest_id = '".$questions_data[$i]->id."'"
				. "\n and is_main = 1 ORDER BY ordering";
				$database->SetQuery( $query );
				$tmp_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
				$j = 0;
				while ( $j < count($tmp_data) ) {
					$questions_data[$i]->answer[$j]->num = $j;
					$questions_data[$i]->answer[$j]->f_id = $tmp_data[$j]->id;
					$questions_data[$i]->answer[$j]->f_text = $tmp_data[$j]->ftext;
					$questions_data[$i]->answer[$j]->alt_text = ($questions_data[$i]->sf_qtype == 9?'':$no_answer_str);
					foreach ($ans_inf_data as $ans_data) {
						if ($ans_data->answer == $tmp_data[$j]->id) {
							$questions_data[$i]->answer[$j]->f_text = $tmp_data[$j]->ftext .($ans_data->ans_txt != '' ?' ('.$ans_data->ans_txt.')':'');
							$query = "SELECT * FROM #__survey_force_fields WHERE id = '".$ans_data->ans_field."'"
							. "\n and quest_id = '".$questions_data[$i]->id."'"
							. "\n and is_main = 0 ORDER BY ordering";
							$database->SetQuery( $query );
							$alt_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
							if (count($alt_data) > 0 ) {
								$questions_data[$i]->answer[$j]->alt_text = ($ans_data->ans_field==0?($questions_data[$i]->sf_qtype == 9?'':$no_answer_str):$alt_data[0]->ftext);
								$questions_data[$i]->answer[$j]->alt_id = $ans_data->ans_field;
							}
						}
					}
					$j ++;
				}
			break;
			case 7:
			case 8:
				break;
			default:
				if (!$questions_data[$i]->answer) $questions_data[$i]->answer = $no_answer_str;
			break;
		}
		$i ++;
	}
	if (!$front_end) {
		if ($is_pdf) {
			SF_PrintRepResult( $start_data, $survey_data, $questions_data );
		} else{
			survey_force_adm_html::SF_ViewRepResult( $option, $start_data, $survey_data, $questions_data );
		}
	}
	else {
		if ($is_pdf) {
			SF_PrintRepResult( $start_data, $survey_data, $questions_data );
		} else{
			survey_force_front_html::SF_ViewRepResult( $option, $start_data, $survey_data, $questions_data );
		}
	}
}

function SF_ViewRepSurv( $id, $option, $is_pdf = 0 ) {
	global $database, $front_end;

	$query = "SELECT * FROM `#__survey_force_survs` WHERE id = '".$id."'";
	$database->SetQuery( $query );
	$survey_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
	if (!count($survey_data)) {
		echo "<script> alert('".JText::_('COM_SF_NO_RESULTS_FOUND')."'); window.history.go(-1);</script>\n";
		exit;
	}
	$survey_data = $survey_data[0];

	$query	= "SELECT `id` FROM `#__survey_force_user_starts` WHERE `survey_id` = '{$id}'";
	$database->SetQuery( $query );
	$start_ids = $database->LoadResultArray();

	$query = "SELECT count(*) FROM #__survey_force_user_starts WHERE survey_id = '".$id."'";
	$database->SetQuery( $query );
	$survey_data->total_starts = $database->LoadResult();
		
	$query = "SELECT count(*) FROM #__survey_force_user_starts WHERE survey_id = '".$id."' and usertype = 0";
	$database->SetQuery( $query );
	$survey_data->total_gstarts = $database->LoadResult();
	$query = "SELECT count(*) FROM #__survey_force_user_starts WHERE survey_id = '".$id."' and usertype = 1";
	$database->SetQuery( $query );
	$survey_data->total_rstarts = $database->LoadResult();
	$query = "SELECT count(*) FROM #__survey_force_user_starts WHERE survey_id = '".$id."' and usertype = 2";
	$database->SetQuery( $query );
	$survey_data->total_istarts = $database->LoadResult();

	$query = "SELECT count(*) FROM #__survey_force_user_starts WHERE survey_id = '".$id."' and is_complete = 1";
	$database->SetQuery( $query );
	$survey_data->total_completes = $database->LoadResult();
	$query = "SELECT count(*) FROM #__survey_force_user_starts WHERE survey_id = '".$id."' and is_complete = 1 and usertype = 0";
	$database->SetQuery( $query );
	$survey_data->total_gcompletes = $database->LoadResult();
	$query = "SELECT count(*) FROM #__survey_force_user_starts WHERE survey_id = '".$id."' and is_complete = 1 and usertype = 1";
	$database->SetQuery( $query );
	$survey_data->total_rcompletes = $database->LoadResult();
	$query = "SELECT count(*) FROM #__survey_force_user_starts WHERE survey_id = '".$id."' and is_complete = 1 and usertype = 2";
	$database->SetQuery( $query );
	$survey_data->total_icompletes = $database->LoadResult();
	
	$query = "SELECT q.*"
	. "\n FROM #__survey_force_quests as q"
	. "\n WHERE q.published = 1 AND q.sf_survey = '".$id."'"
	. "\n ORDER BY q.ordering, q.id ";
	$database->SetQuery( $query );
	$questions_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
	$i = 0;
	while ( $i < count($questions_data) ) {
		if ($questions_data[$i]->sf_impscale) {
			$query = "SELECT iscale_name FROM #__survey_force_iscales WHERE id = '".$questions_data[$i]->sf_impscale."'";
			$database->SetQuery( $query );
			$questions_data[$i]->iscale_name = $database->loadResult();

			$query = "SELECT count(id) FROM #__survey_force_user_answers_imp"
			. "\n WHERE quest_id = '".$questions_data[$i]->id."' and survey_id = '".$questions_data[$i]->sf_survey."'"
			. "\n AND iscale_id = '".$questions_data[$i]->sf_impscale."' AND `start_id` IN ('".implode("','", $start_ids)."')";
			$database->SetQuery( $query );
			$questions_data[$i]->total_iscale_answers = $database->LoadResult();

			$query = "SELECT b.isf_name, count(a.id) as ans_count FROM #__survey_force_iscales_fields as b LEFT JOIN #__survey_force_user_answers_imp as a ON ( a.quest_id = '".$questions_data[$i]->id."' and a.iscalefield_id = b.id AND `a`.`start_id` IN ('".implode("','", $start_ids)."'))"
			. "\n WHERE b.iscale_id = '".$questions_data[$i]->sf_impscale."'"
			. "\n GROUP BY b.isf_name ORDER BY  b.ordering";//ans_count DESC,
			$database->SetQuery( $query );
			$ans_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
			
			$questions_data[$i]->answer_imp = array();
			$j = 0;
			while ( $j < count($ans_data) ) {
				$questions_data[$i]->answer_imp[$j]->num = $j;
				$questions_data[$i]->answer_imp[$j]->ftext = $ans_data[$j]->isf_name;
				$questions_data[$i]->answer_imp[$j]->ans_count = $ans_data[$j]->ans_count;
				$j ++;
			}
		}
		$questions_data[$i]->sf_qtext = trim(strip_tags($questions_data[$i]->sf_qtext, '<a><b><i><u>'));
		switch ($questions_data[$i]->sf_qtype) {
			case 2:
				$query = "SELECT count(id) FROM #__survey_force_user_answers"
				. "\n WHERE quest_id = '".$questions_data[$i]->id."' and survey_id = '".$questions_data[$i]->sf_survey."' AND `start_id` IN ('".implode("','", $start_ids)."') ";
				$database->SetQuery( $query );
				$questions_data[$i]->total_answers = $database->LoadResult();

				$query = "SELECT b.ftext, count(a.answer) as ans_count FROM #__survey_force_fields as b LEFT JOIN #__survey_force_user_answers as a ON (a.answer = b.id AND a.quest_id = '".$questions_data[$i]->id."' AND `a`.`start_id` IN ('".implode("','", $start_ids)."')) "
				. "\n WHERE b.quest_id = '".$questions_data[$i]->id."'"
				. "\n GROUP BY b.ftext ORDER BY b.ordering";//ans_count DESC
				$database->SetQuery( $query );
				
				$ans_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
				$questions_data[$i]->answer = array();
				$j = 0;
				while ( $j < count($ans_data) ) {
					$questions_data[$i]->answer[$j]->num = $j;
					$questions_data[$i]->answer[$j]->ftext = $ans_data[$j]->ftext;
					$questions_data[$i]->answer[$j]->ans_count = $ans_data[$j]->ans_count;
					$j ++;
				}
			break;
			case 3:
				$query = "SELECT count(distinct start_id) FROM #__survey_force_user_answers"
				. "\n WHERE quest_id = '".$questions_data[$i]->id."' and survey_id = '".$questions_data[$i]->sf_survey."' AND `start_id` IN ('".implode("','", $start_ids)."') ";
				$database->SetQuery( $query );
				$questions_data[$i]->total_answers = $database->LoadResult();

				$query = "SELECT b.ftext, count(a.answer) as ans_count FROM #__survey_force_fields as b LEFT JOIN #__survey_force_user_answers as a ON ( a.answer = b.id AND a.quest_id = '".$questions_data[$i]->id."' AND `a`.`start_id` IN ('".implode("','", $start_ids)."'))"
				. "\n WHERE b.quest_id = '".$questions_data[$i]->id."'"
				. "\n GROUP BY b.ftext ORDER BY b.ordering";//ans_count DESC
				$database->SetQuery( $query );
				$ans_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
				$questions_data[$i]->answer = array();
				$j = 0;
				while ( $j < count($ans_data) ) {
					$questions_data[$i]->answer[$j]->num = $j;
					$questions_data[$i]->answer[$j]->ftext = $ans_data[$j]->ftext;
					$questions_data[$i]->answer[$j]->ans_count = $ans_data[$j]->ans_count;
					$j ++;
				}
			break;
			case 4:
				$n = substr_count($questions_data[$i]->sf_qtext, '{x}')+substr_count($questions_data[$i]->sf_qtext, '{y}');
				if ($n > 0) {
					$query = "SELECT id FROM #__survey_force_user_answers"
					. "\n WHERE quest_id = '".$questions_data[$i]->id."' and survey_id = '".$questions_data[$i]->sf_survey."' AND `start_id` IN ('".implode("','", $start_ids)."') GROUP BY start_id, quest_id ";
					$database->SetQuery( $query );
					$questions_data[$i]->total_answers = count($database->LoadResultArray());
					
					$questions_data[$i]->answer = array();
					$questions_data[$i]->answers_top100 = array();
					$questions_data[$i]->answer_count = $n;
					for($j = 0; $j < $n; $j++) {
						$query = "SELECT answer FROM #__survey_force_user_answers WHERE ans_field = ".($j+1)
								." AND quest_id = '".$questions_data[$i]->id."'"
								." AND survey_id = '".$questions_data[$i]->sf_survey."' AND `start_id` IN ('".implode("','", $start_ids)."') ";
						$database->SetQuery( $query );
						$ans_txt_data = @array_merge(array(0=>0),$database->LoadResultArray());
						
						$query = "SELECT b.ans_txt, count(a.answer) as ans_count FROM #__survey_force_user_ans_txt as b,"
						. "\n #__survey_force_user_answers as a"
						. "\n WHERE a.quest_id = '".$questions_data[$i]->id."' AND `a`.`start_id` IN ('".implode("','", $start_ids)."')"
						. "\n AND a.answer = b.id "
						. "\n AND a.answer IN (".implode(',', $ans_txt_data).") "
						. "\n GROUP BY b.ans_txt ORDER BY ans_count DESC LIMIT 0,5";
						$database->SetQuery( $query );
						$ans_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
						$jj = 0;
						$tmp = array();
						while ( $jj < count($ans_data) ) {
							$tmp[$jj]->num = $jj;
							$tmp[$jj]->ftext = $ans_data[$jj]->ans_txt;
							$tmp[$jj]->ans_count = $ans_data[$jj]->ans_count;
							$jj ++;
						}
						$questions_data[$i]->answer[$j] = $tmp;
						
						$query = "SELECT b.ans_txt FROM #__survey_force_user_ans_txt as b, #__survey_force_user_answers as a"
								. "\n WHERE a.quest_id = '".$questions_data[$i]->id."' AND `a`.`start_id` IN ('".implode("','", $start_ids)."') AND a.answer = b.id"
								. "\n AND a.answer IN (".implode(',', $ans_txt_data).") "
								. "\n ORDER BY a.sf_time DESC LIMIT 0,100";
						$database->SetQuery( $query );
						$ans_data = $database->loadResultArray();
						$questions_data[$i]->answers_top100[$j] = implode(', ',$ans_data);
					}
				}
				else {
					$query = "SELECT id FROM #__survey_force_user_answers"
					. "\n WHERE quest_id = '".$questions_data[$i]->id."' AND `start_id` IN ('".implode("','", $start_ids)."') and survey_id = '".$questions_data[$i]->sf_survey."' GROUP BY start_id, quest_id ";
					$database->SetQuery( $query );
					$questions_data[$i]->total_answers = count($database->LoadResultArray());
	
					$query = "SELECT b.ans_txt, count(a.answer) as ans_count FROM #__survey_force_user_ans_txt as b, #__survey_force_user_answers as a"
					. "\n WHERE a.quest_id = '".$questions_data[$i]->id."' AND `a`.`start_id` IN ('".implode("','", $start_ids)."') and a.answer = b.id"
					. "\n GROUP BY b.ans_txt ORDER BY ans_count DESC LIMIT 0,5";
					$database->SetQuery( $query );
					$ans_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
					$questions_data[$i]->answer = array();
					$j = 0;
					while ( $j < count($ans_data) ) {
						$questions_data[$i]->answer[$j]->num = $j;
						$questions_data[$i]->answer[$j]->ftext = $ans_data[$j]->ans_txt;
						$questions_data[$i]->answer[$j]->ans_count = $ans_data[$j]->ans_count;
						$j ++;
					}
					$query = "SELECT b.ans_txt FROM #__survey_force_user_ans_txt as b, #__survey_force_user_answers as a"
					. "\n WHERE a.quest_id = '".$questions_data[$i]->id."' AND `a`.`start_id` IN ('".implode("','", $start_ids)."') and a.answer = b.id"
					. "\n ORDER BY a.sf_time DESC LIMIT 0,100";
					$database->SetQuery( $query );
					$ans_data = $database->loadResultArray();
					$questions_data[$i]->answers_top100 = implode(', ',$ans_data);
				}
			break;
			case 1:
				$query = "SELECT count(distinct start_id) FROM #__survey_force_user_answers"
				. "\n WHERE quest_id = '".$questions_data[$i]->id."' AND `start_id` IN ('".implode("','", $start_ids)."') and survey_id = '".$questions_data[$i]->sf_survey."' ";
				$database->SetQuery( $query );
				$questions_data[$i]->total_answers = $database->LoadResult();
				
				$query = "SELECT * FROM #__survey_force_fields WHERE quest_id = '".$questions_data[$i]->id."' ORDER by ordering";
				$database->SetQuery( $query );
				$f_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
				$j = 0;
				$questions_data[$i]->answer = array();
				while ( $j < count($f_data) ) {
					$query = "SELECT b.stext, count(a.answer) as ans_count FROM #__survey_force_scales as b LEFT JOIN #__survey_force_user_answers as a ON ( a.ans_field = b.id AND a.answer = '".$f_data[$j]->id."' AND a.quest_id = '".$questions_data[$i]->id."' AND `a`.`start_id` IN ('".implode("','", $start_ids)."'))"
					. "\n WHERE b.quest_id = '".$questions_data[$i]->id."' "
					. "\n GROUP BY b.stext ORDER BY b.ordering";
					$database->SetQuery( $query );
					$ans_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
					$questions_data[$i]->answer[$j]->full_ans = array();
					$jj = 0;
					$questions_data[$i]->answer[$j]->ftext = $f_data[$j]->ftext; 
					while ( $jj < count($ans_data) ) {
						$questions_data[$i]->answer[$j]->full_ans[$jj]->ftext = $ans_data[$jj]->stext;
						$questions_data[$i]->answer[$j]->full_ans[$jj]->ans_count = $ans_data[$jj]->ans_count;
						$jj ++;
					}
					$j++;
				}
			break;
			case 5:
			case 6:
			case 9:
				$query = "SELECT count(distinct start_id) FROM #__survey_force_user_answers"
				. "\n WHERE quest_id = '".$questions_data[$i]->id."' AND `start_id` IN ('".implode("','", $start_ids)."') and survey_id = '".$questions_data[$i]->sf_survey."' ";
				$database->SetQuery( $query );
				$questions_data[$i]->total_answers = $database->LoadResult();
				
				$query = "SELECT * FROM #__survey_force_fields WHERE quest_id = '".$questions_data[$i]->id."' and is_main = '1' ORDER by ordering";
				$database->SetQuery( $query );
				$f_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
				$j = 0;
				$questions_data[$i]->answer = array();
				while ( $j < count($f_data) ) {
					$query = "SELECT b.ftext, count(a.answer) as ans_count FROM #__survey_force_fields as b LEFT JOIN #__survey_force_user_answers as a ON ( a.ans_field = b.id AND a.answer = '".$f_data[$j]->id."' AND a.quest_id = '".$questions_data[$i]->id."' AND `a`.`start_id` IN ('".implode("','", $start_ids)."'))"
					. "\n WHERE b.quest_id = '".$questions_data[$i]->id."' and b.is_main = '0'"
					. "\n GROUP BY b.ftext ORDER BY b.ordering ";//ans_count DESC
					$database->SetQuery( $query );
					$ans_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
					$questions_data[$i]->answer[$j]->full_ans = array();
					$jj = 0;
					$questions_data[$i]->answer[$j]->ftext = $f_data[$j]->ftext; 
					while ( $jj < count($ans_data) ) {
						$questions_data[$i]->answer[$j]->full_ans[$jj]->ftext = $ans_data[$jj]->ftext;
						$questions_data[$i]->answer[$j]->full_ans[$jj]->ans_count = $ans_data[$jj]->ans_count;
						$jj ++;
					}
					$j++;
				}
			break;
		}
	$i++;
	}

	if (!$front_end) {
		if ($is_pdf) {
			SF_PrintRepSurv_List( $survey_data, $questions_data );
		} else{
			survey_force_adm_html::SF_ViewRepSurv_List( $option, $survey_data, $questions_data, 0, 0 );
		}
	}
	else {
		if ($is_pdf) {
			SF_PrintRepSurv_List( $survey_data, $questions_data );
		} else{
			survey_force_front_html::SF_ViewRepSurv_List( $option, $survey_data, $questions_data, 0, 0 );
		}		
	}
}

function SF_ViewRepList( $id, $option, $is_pdf = 0 ) {
	global $database, $front_end, $my;

	$query = "SELECT * FROM #__survey_force_listusers WHERE id = '".$id."'";
	$database->SetQuery( $query );
	$list_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
	if (!count($list_data)) {
		echo "<script> alert('".JText::_('COM_SF_NO_RESULTS_FOUND')."'); window.history.go(-1);</script>\n";
		exit;
	}
	$list_data = $list_data[0];
	
	$query = "SELECT * FROM #__survey_force_survs WHERE id = '".$list_data->survey_id."'";
	$database->SetQuery( $query );
	$survey_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
	if (!count($survey_data)) {
		echo "<script> alert('".JText::_('COM_SF_NO_RESULTS_FOUND')."'); window.history.go(-1);</script>\n";
		exit;
	}
	
	$survey_data = $survey_data[0];
			
	$query = "SELECT count(a.id) FROM #__survey_force_user_starts as a, #__survey_force_users as b"
	. "\n  WHERE a.survey_id = '".$survey_data->id."'"
	. "\n and a.usertype = 2 and a.user_id = b.id and b.list_id = '".$list_data->id."' and b.is_invited = 1 and a.is_complete = 1";
	$database->SetQuery( $query );
	$survey_data->total_completes = $database->LoadResult();
	
	$query = "SELECT count(a.id) FROM #__survey_force_user_starts as a, #__survey_force_users as b"
	. "\n  WHERE a.survey_id = '".$survey_data->id."'"
	. "\n and a.usertype = 2 and a.user_id = b.id and b.list_id = '".$list_data->id."' and b.is_invited = 1";
	$database->SetQuery( $query );
	$survey_data->total_starts = $database->LoadResult();
	
	$query = "SELECT count(b.id) FROM #__survey_force_users as b"
	. "\n  WHERE b.list_id = '".$list_data->id."' and b.is_invited = 1";
	$database->SetQuery( $query );
	$survey_data->total_inv_users = $database->LoadResult();
	
	$query = "SELECT q.*"
	. "\n FROM #__survey_force_quests as q"
	. "\n WHERE q.published = 1 AND q.sf_survey = '".$survey_data->id."'"
	. "\n ORDER BY q.ordering, q.id ";
	$database->SetQuery( $query );
	$questions_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
	$i = 0;
	$query = "SELECT b.id FROM #__survey_force_user_starts as b, #__survey_force_users as c"
	. "\n WHERE b.usertype = 2 and b.user_id = c.id and c.list_id = '".$list_data->id."'";
	$database->SetQuery( $query );
	$start_id_array = $database->LoadResultArray();
	$start_id_array[] = 0;
	$start_ids = @implode(',',$start_id_array);
	
	while ( $i < count($questions_data) ) {
		if ($questions_data[$i]->sf_impscale) {
			$query = "SELECT iscale_name FROM #__survey_force_iscales WHERE id = '".$questions_data[$i]->sf_impscale."'";
			$database->SetQuery( $query );
			$questions_data[$i]->iscale_name = $database->loadResult();

			$query = "SELECT count(a.id) FROM #__survey_force_user_answers_imp as a"
			. "\n WHERE a.quest_id = '".$questions_data[$i]->id."' and a.survey_id = '".$questions_data[$i]->sf_survey."' and a.iscale_id = '".$questions_data[$i]->sf_impscale."'"
			. "\n and a.start_id IN (".$start_ids.")";
			$database->SetQuery( $query );
			$questions_data[$i]->total_iscale_answers = $database->LoadResult();

			$query = "SELECT b.isf_name, count(a.iscalefield_id) as ans_count FROM #__survey_force_iscales_fields as b LEFT JOIN #__survey_force_user_answers_imp as a ON a.quest_id = '".$questions_data[$i]->id."' and a.survey_id = '".$questions_data[$i]->sf_survey."' and a.iscale_id = '".$questions_data[$i]->sf_impscale."' and a.start_id IN (".$start_ids.") and a.iscalefield_id = b.id"
			. "\n WHERE b.iscale_id = '".$questions_data[$i]->sf_impscale."'"
			. "\n GROUP BY b.isf_name ORDER BY  b.ordering";//ans_count DESC,
			$database->SetQuery( $query );
			$ans_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
			$questions_data[$i]->answer_imp = array();
			$j = 0;
			while ( $j < count($ans_data) ) {
				$questions_data[$i]->answer_imp[$j]->num = $j;
				$questions_data[$i]->answer_imp[$j]->ftext = $ans_data[$j]->isf_name;
				$questions_data[$i]->answer_imp[$j]->ans_count = $ans_data[$j]->ans_count;
				$j ++;
			}
		}
		$questions_data[$i]->sf_qtext = trim(strip_tags($questions_data[$i]->sf_qtext,'<a><b><i><u>'));
		switch ($questions_data[$i]->sf_qtype) {
			case 2:
				$query = "SELECT count(a.id) FROM #__survey_force_user_answers as a"
				. "\n WHERE a.quest_id = '".$questions_data[$i]->id."' and a.survey_id = '".$questions_data[$i]->sf_survey."' "
				. "\n and a.start_id IN (".$start_ids.")";
				$database->SetQuery( $query );
				$questions_data[$i]->total_answers = $database->LoadResult();

				$query = "SELECT b.ftext, count(a.answer) as ans_count FROM #__survey_force_fields as b LEFT JOIN #__survey_force_user_answers as a ON ( a.start_id IN (".$start_ids.") AND a.answer = b.id AND a.quest_id = '".$questions_data[$i]->id."' )"
				. "\n WHERE b.quest_id = '".$questions_data[$i]->id."'"
				. "\n GROUP BY b.ftext ORDER BY b.ordering"; //ans_count DESC
				$database->SetQuery( $query );
				$ans_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
				$questions_data[$i]->answer = array();
				$j = 0;
				while ( $j < count($ans_data) ) {
					$questions_data[$i]->answer[$j]->num = $j;
					$questions_data[$i]->answer[$j]->ftext = $ans_data[$j]->ftext;
					$questions_data[$i]->answer[$j]->ans_count = $ans_data[$j]->ans_count;
					$j ++;
				}
			break;
			case 3:
				$query = "SELECT count(distinct start_id) FROM #__survey_force_user_answers"
				. "\n WHERE quest_id = '".$questions_data[$i]->id."' and survey_id = '".$questions_data[$i]->sf_survey."' and start_id IN (".$start_ids.")";
				$database->SetQuery( $query );
				$questions_data[$i]->total_answers = $database->LoadResult();

				$query = "SELECT b.ftext, count(a.answer) as ans_count FROM #__survey_force_fields as b LEFT JOIN #__survey_force_user_answers as a ON ( a.answer = b.id AND a.start_id IN (".$start_ids.") AND a.quest_id = '".$questions_data[$i]->id."' )"
				. "\n WHERE b.quest_id = '".$questions_data[$i]->id."'"
				. "\n GROUP BY b.ftext ORDER BY b.ordering";//ans_count DESC
				$database->SetQuery( $query );
				$ans_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
				$questions_data[$i]->answer = array();
				$j = 0;
				while ( $j < count($ans_data) ) {
					$questions_data[$i]->answer[$j]->num = $j;
					$questions_data[$i]->answer[$j]->ftext = $ans_data[$j]->ftext;
					$questions_data[$i]->answer[$j]->ans_count = $ans_data[$j]->ans_count;
					$j ++;
				}
			break;
			case 4:
				$n = substr_count($questions_data[$i]->sf_qtext, '{x}')+substr_count($questions_data[$i]->sf_qtext, '{y}');
				if ($n > 0) {
					$query = "SELECT id FROM #__survey_force_user_answers"
					. "\n WHERE quest_id = '".$questions_data[$i]->id."' AND survey_id = '".$questions_data[$i]->sf_survey."' AND start_id IN (".$start_ids.")  GROUP BY start_id, quest_id ";
					$database->SetQuery( $query );
					$questions_data[$i]->total_answers = count($database->LoadResultArray());
					
					$questions_data[$i]->answer = array();
					$questions_data[$i]->answers_top100 = array();
					$questions_data[$i]->answer_count = $n;
					for($j = 0; $j < $n; $j++) {
						$query = "SELECT answer FROM #__survey_force_user_answers WHERE ans_field = ".($j+1)
								." AND quest_id = '".$questions_data[$i]->id."' AND a.start_id IN (".$start_ids.") "
								." AND survey_id = '".$questions_data[$i]->sf_survey."' ";
						$database->SetQuery( $query );
						$ans_txt_data = @array_merge(array(0=>0),$database->LoadResultArray());
						
						$query = "SELECT b.ans_txt, count(a.answer) as ans_count FROM #__survey_force_user_ans_txt as b,"
						. "\n #__survey_force_user_answers as a"
						. "\n WHERE a.quest_id = '".$questions_data[$i]->id."'"
						. "\n AND a.answer = b.id AND a.start_id IN (".$start_ids.") "
						. "\n AND a.answer IN (".implode(',', $ans_txt_data).") "
						. "\n GROUP BY b.ans_txt ORDER BY ans_count DESC LIMIT 0,5";
						$database->SetQuery( $query );
						$ans_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
						$jj = 0;
						$tmp = array();
						while ( $jj < count($ans_data) ) {
							$tmp[$jj]->num = $jj;
							$tmp[$jj]->ftext = $ans_data[$jj]->ans_txt;
							$tmp[$jj]->ans_count = $ans_data[$jj]->ans_count;
							$jj ++;
						}
						$questions_data[$i]->answer[$j] = $tmp;
						
						$query = "SELECT b.ans_txt FROM #__survey_force_user_ans_txt as b, #__survey_force_user_answers as a"
								. "\n WHERE a.quest_id = '".$questions_data[$i]->id."' AND a.answer = b.id"
								. "\n AND a.answer IN (".implode(',', $ans_txt_data).") AND a.start_id IN (".$start_ids.") "
								. "\n ORDER BY a.sf_time DESC LIMIT 0,100";
						$database->SetQuery( $query );
						$ans_data = $database->loadResultArray();
						$ans_data = (is_array($ans_data)?$ans_data:array());
						$questions_data[$i]->answers_top100[$j] = implode(', ',$ans_data);
					}
				}
				else {
					$query = "SELECT id FROM #__survey_force_user_answers"
					. "\n WHERE quest_id = '".$questions_data[$i]->id."' AND survey_id = '".$questions_data[$i]->sf_survey."' AND start_id IN (".$start_ids.")  GROUP BY start_id, quest_id ";
					$database->SetQuery( $query );
					$questions_data[$i]->total_answers = count($database->LoadResultArray());
	
					$query = "SELECT b.ans_txt, count(a.answer) as ans_count FROM #__survey_force_user_ans_txt as b, #__survey_force_user_answers as a"
					. "\n WHERE a.quest_id = '".$questions_data[$i]->id."' and a.survey_id = '".$questions_data[$i]->sf_survey."' and a.answer = b.id and a.start_id IN (".$start_ids.")"
					. "\n GROUP BY b.ans_txt ORDER BY ans_count DESC LIMIT 0,5";
					$database->SetQuery( $query );
					$ans_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
					$questions_data[$i]->answer = array();
					$j = 0;
					while ( $j < count($ans_data) ) {
						$questions_data[$i]->answer[$j]->num = $j;
						$questions_data[$i]->answer[$j]->ftext = $ans_data[$j]->ans_txt;
						$questions_data[$i]->answer[$j]->ans_count = $ans_data[$j]->ans_count;
						$j ++;
					}
					$ans_data = array();
					$query = "SELECT b.ans_txt FROM #__survey_force_user_ans_txt as b, #__survey_force_user_answers as a"
					. "\n WHERE a.quest_id = '".$questions_data[$i]->id."' and a.survey_id = '".$questions_data[$i]->sf_survey."' and a.start_id IN (".$start_ids.") and a.answer = b.id "
					. "\n ORDER BY a.sf_time DESC LIMIT 0,100";
					$database->SetQuery( $query );
					$ans_data = $database->loadResultArray();
					if (count($ans_data) > 0) {
						$questions_data[$i]->answers_top100 = implode(', ',$ans_data);
					} else { $questions_data[$i]->answers_top100 = ''; }
				}
			break;
			case 1:
				$query = "SELECT count(distinct start_id) FROM #__survey_force_user_answers"
				. "\n WHERE quest_id = '".$questions_data[$i]->id."' and survey_id = '".$questions_data[$i]->sf_survey."' and start_id IN (".$start_ids.")";
				$database->SetQuery( $query );
				$questions_data[$i]->total_answers = $database->LoadResult();
				
				$query = "SELECT * FROM #__survey_force_fields WHERE quest_id = '".$questions_data[$i]->id."' ORDER by ordering";
				$database->SetQuery( $query );
				$f_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
				$j = 0;
				$questions_data[$i]->answer = array();
				while ( $j < count($f_data) ) {
					$query = "SELECT b.stext, count(a.answer) as ans_count FROM #__survey_force_scales as b LEFT JOIN #__survey_force_user_answers as a ON ( a.ans_field = b.id AND a.answer = '".$f_data[$j]->id."' AND a.start_id IN (".$start_ids.") AND a.quest_id = '".$questions_data[$i]->id."' )"
					. "\n WHERE b.quest_id = '".$questions_data[$i]->id."'"
					. "\n GROUP BY b.stext ORDER BY b.ordering";
					$database->SetQuery( $query );
					$ans_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
					$questions_data[$i]->answer[$j]->full_ans = array();
					$jj = 0;
					$questions_data[$i]->answer[$j]->ftext = $f_data[$j]->ftext; 
					while ( $jj < count($ans_data) ) {
						$questions_data[$i]->answer[$j]->full_ans[$jj]->ftext = $ans_data[$jj]->stext;
						$questions_data[$i]->answer[$j]->full_ans[$jj]->ans_count = $ans_data[$jj]->ans_count;
						$jj ++;
					}
					$j++;
				}
			break;
			case 5:
			case 6:
			case 9:
				$query = "SELECT count(distinct start_id) FROM #__survey_force_user_answers"
				. "\n WHERE quest_id = '".$questions_data[$i]->id."' and survey_id = '".$questions_data[$i]->sf_survey."' and start_id IN (".$start_ids.")";
				$database->SetQuery( $query );
				$questions_data[$i]->total_answers = $database->LoadResult();
				
				$query = "SELECT * FROM #__survey_force_fields WHERE quest_id = '".$questions_data[$i]->id."' and is_main = '1' ORDER by ordering";
				$database->SetQuery( $query );
				$f_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
				$j = 0;
				$questions_data[$i]->answer = array();
				while ( $j < count($f_data) ) {
					$query = "SELECT b.ftext, count(a.answer) as ans_count FROM #__survey_force_fields as b LEFT JOIN #__survey_force_user_answers as a ON ( a.ans_field = b.id AND a.answer = '".$f_data[$j]->id."' AND a.start_id IN (".$start_ids.") AND a.quest_id = '".$questions_data[$i]->id."' )"
					. "\n WHERE b.quest_id = '".$questions_data[$i]->id."' and b.is_main = '0'"
					. "\n GROUP BY b.ftext ORDER BY b.ordering";//ans_count DESC
					$database->SetQuery( $query );
					$ans_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
					$questions_data[$i]->answer[$j]->full_ans = array();
					$jj = 0;
					$questions_data[$i]->answer[$j]->ftext = $f_data[$j]->ftext; 
					while ( $jj < count($ans_data) ) {
						$questions_data[$i]->answer[$j]->full_ans[$jj]->ftext = $ans_data[$jj]->ftext;
						$questions_data[$i]->answer[$j]->full_ans[$jj]->ans_count = $ans_data[$jj]->ans_count;
						$jj ++;
					}
					$j++;
				}
			break;
		}
	$i++;
	}
	if ($is_pdf) {
		SF_PrintRepSurv_List( $survey_data, $questions_data, 1 );
	} else{
		survey_force_adm_html::SF_ViewRepSurv_List( $option, $survey_data, $questions_data, 1, $list_data->id );
	}
}

function SF_ViewRepUsers( $cid, $option, $is_pdf = 0, $is_pc = 0 ) {
	global $database, $front_end, $my, $mainframe;

	$surv_id = intval( $mainframe->getUserStateFromRequest( "surv_id{$option}", 'surv_id', 0 ) );
	$filt_status	= intval( $mainframe->getUserStateFromRequest( "filt_status{$option}", 'filt_status', 2 ) );
	$filt_utype		= intval( $mainframe->getUserStateFromRequest( "filt_utype{$option}", 'filt_utype', 0 ) );
	$filt_ulist		= intval( $mainframe->getUserStateFromRequest( "filt_ulist{$option}", 'filt_ulist', 0 ) );
	
	$query = "SELECT * FROM #__survey_force_survs WHERE id = '".$surv_id."'";
	$database->SetQuery( $query );
	$survey_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
	if (!count($survey_data)) {
		echo "<script> alert('".JText::_('COM_SF_NO_RESULTS_FOUND')."'); window.history.go(-1);</script>\n";
		exit;
	}

	$survey_data = $survey_data[0];
	
	$query = "SELECT count(*) FROM #__survey_force_user_starts WHERE survey_id = '".$surv_id."'";
	$database->SetQuery( $query );
	$survey_data->total_starts = $database->LoadResult();
	$query = "SELECT count(*) FROM #__survey_force_user_starts WHERE survey_id = '".$surv_id."' and usertype = 0";
	$database->SetQuery( $query );
	$survey_data->total_gstarts = $database->LoadResult();
	$query = "SELECT count(*) FROM #__survey_force_user_starts WHERE survey_id = '".$surv_id."' and usertype = 1";
	$database->SetQuery( $query );
	$survey_data->total_rstarts = $database->LoadResult();
	$query = "SELECT count(*) FROM #__survey_force_user_starts WHERE survey_id = '".$surv_id."' and usertype = 2";
	$database->SetQuery( $query );
	$survey_data->total_istarts = $database->LoadResult();

	$query = "SELECT count(*) FROM #__survey_force_user_starts WHERE survey_id = '".$surv_id."' and is_complete = 1";
	$database->SetQuery( $query );
	$survey_data->total_completes = $database->LoadResult();
	$query = "SELECT count(*) FROM #__survey_force_user_starts WHERE survey_id = '".$surv_id."' and is_complete = 1 and usertype = 0";
	$database->SetQuery( $query );
	$survey_data->total_gcompletes = $database->LoadResult();
	$query = "SELECT count(*) FROM #__survey_force_user_starts WHERE survey_id = '".$surv_id."' and is_complete = 1 and usertype = 1";
	$database->SetQuery( $query );
	$survey_data->total_rcompletes = $database->LoadResult();
	$query = "SELECT count(*) FROM #__survey_force_user_starts WHERE survey_id = '".$surv_id."' and is_complete = 1 and usertype = 2";
	$database->SetQuery( $query );
	$survey_data->total_icompletes = $database->LoadResult();

	$query = "SELECT q.*"
	. "\n FROM #__survey_force_quests as q"
	. "\n WHERE q.published = 1 AND q.sf_survey = '".$survey_data->id."'"
	. "\n ORDER BY q.ordering, q.id ";
	$database->SetQuery( $query );
	$questions_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
	$i = 0;
	$query = "SELECT b.id FROM #__survey_force_user_starts as b "
	. "\n WHERE 1=1 "
	. ( $filt_status ? "\n and b.is_complete = '".($filt_status - 1)."'" : '' )
	. ( $filt_utype ? "\n and b.usertype = '".($filt_utype -1)."'" : '' );
	if ((count($cid) > 0) && ($cid[0] != 0)) {
		$cids =implode(',', $cid);
		$query .= "\n AND b.id in (".$cids.")";
	}
	$database->SetQuery( $query );

	$start_id_array = $database->LoadResultArray();
	$start_id_array[] = 0;
	$start_ids = @implode(',',$start_id_array);
	
	while ( $i < count($questions_data) ) {
		if ($questions_data[$i]->sf_impscale) {
			$query = "SELECT iscale_name FROM #__survey_force_iscales WHERE id = '".$questions_data[$i]->sf_impscale."'";
			$database->SetQuery( $query );
			$questions_data[$i]->iscale_name = $database->loadResult();

			$query = "SELECT count(a.id) FROM #__survey_force_user_answers_imp as a"
			. "\n WHERE a.quest_id = '".$questions_data[$i]->id."' and a.survey_id = '".$questions_data[$i]->sf_survey."' and a.iscale_id = '".$questions_data[$i]->sf_impscale."'"
			. "\n and a.start_id IN (".$start_ids.")";
			$database->SetQuery( $query );
			$questions_data[$i]->total_iscale_answers = $database->LoadResult();

			$query = "SELECT b.isf_name, count(a.iscalefield_id) as ans_count FROM #__survey_force_iscales_fields as b LEFT JOIN #__survey_force_user_answers_imp as a ON a.quest_id = '".$questions_data[$i]->id."' and a.survey_id = '".$questions_data[$i]->sf_survey."' and a.iscale_id = '".$questions_data[$i]->sf_impscale."' and a.start_id IN (".$start_ids.") and a.iscalefield_id = b.id"
			. "\n WHERE b.iscale_id = '".$questions_data[$i]->sf_impscale."'"
			. "\n GROUP BY b.isf_name ORDER BY  b.ordering";//ans_count DESC,
			$database->SetQuery( $query );
			$ans_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
			$questions_data[$i]->answer_imp = array();
			$j = 0;
			while ( $j < count($ans_data) ) {
				$questions_data[$i]->answer_imp[$j]->num = $j;
				$questions_data[$i]->answer_imp[$j]->ftext = $ans_data[$j]->isf_name;
				$questions_data[$i]->answer_imp[$j]->ans_count = $ans_data[$j]->ans_count;
				$j ++;
			}
		}
		$questions_data[$i]->sf_qtext = trim(strip_tags($questions_data[$i]->sf_qtext,'<a><b><i><u>'));
		switch ($questions_data[$i]->sf_qtype) {
			case 2:
				$query = "SELECT count(a.id) FROM #__survey_force_user_answers as a"
				. "\n WHERE a.quest_id = '".$questions_data[$i]->id."' and a.survey_id = '".$questions_data[$i]->sf_survey."' "
				. "\n and a.start_id IN (".$start_ids.")";
				$database->SetQuery( $query );
				$questions_data[$i]->total_answers = $database->LoadResult();

				$query = "SELECT b.ftext, count(a.answer) as ans_count FROM #__survey_force_fields as b LEFT JOIN #__survey_force_user_answers as a ON ( a.start_id IN (".$start_ids.") AND a.answer = b.id AND a.quest_id = '".$questions_data[$i]->id."') "
				. "\n WHERE b.quest_id = '".$questions_data[$i]->id."'"
				. "\n GROUP BY b.ftext ORDER BY b.ordering"; //ans_count DESC
				$database->SetQuery( $query );
				$ans_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
				$questions_data[$i]->answer = array();
				$j = 0;
				while ( $j < count($ans_data) ) {
					$questions_data[$i]->answer[$j]->num = $j;
					$questions_data[$i]->answer[$j]->ftext = $ans_data[$j]->ftext;
					$questions_data[$i]->answer[$j]->ans_count = ($is_pc? intval($ans_data[$j]->ans_count/$questions_data[$i]->total_answers*100): $ans_data[$j]->ans_count);
					$j ++;
				}
			break;
			case 3:
				$query = "SELECT count(distinct start_id) FROM #__survey_force_user_answers"
				. "\n WHERE quest_id = '".$questions_data[$i]->id."' and survey_id = '".$questions_data[$i]->sf_survey."' and start_id IN (".$start_ids.")";
				$database->SetQuery( $query );
				$questions_data[$i]->total_answers = $database->LoadResult();

				$query = "SELECT b.ftext, count(a.answer) as ans_count FROM #__survey_force_fields as b LEFT JOIN #__survey_force_user_answers as a ON ( a.answer = b.id AND a.start_id IN (".$start_ids.") AND a.quest_id = '".$questions_data[$i]->id."' )"
				. "\n WHERE b.quest_id = '".$questions_data[$i]->id."'"
				. "\n GROUP BY b.ftext ORDER BY b.ordering";//ans_count DESC
				$database->SetQuery( $query );
				$ans_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
				$questions_data[$i]->answer = array();
				$j = 0;
				while ( $j < count($ans_data) ) {
					$questions_data[$i]->answer[$j]->num = $j;
					$questions_data[$i]->answer[$j]->ftext = $ans_data[$j]->ftext;
					$questions_data[$i]->answer[$j]->ans_count = ($is_pc? intval($ans_data[$j]->ans_count/$questions_data[$i]->total_answers*100) :$ans_data[$j]->ans_count);
					$j ++;
				}
			break;
			case 4:
				$n = substr_count($questions_data[$i]->sf_qtext, '{x}')+substr_count($questions_data[$i]->sf_qtext, '{y}');
				if ($n > 0) {
					$query = "SELECT id FROM #__survey_force_user_answers"
					. "\n WHERE quest_id = '".$questions_data[$i]->id."' AND survey_id = '".$questions_data[$i]->sf_survey."' AND start_id IN (".$start_ids.")  GROUP BY start_id, quest_id ";
					$database->SetQuery( $query );
					$questions_data[$i]->total_answers = count($database->LoadResultArray());
					
					$questions_data[$i]->answer = array();
					$questions_data[$i]->answers_top100 = array();
					$questions_data[$i]->answer_count = $n;
					for($j = 0; $j < $n; $j++) {
						$query = "SELECT answer FROM #__survey_force_user_answers WHERE ans_field = ".($j+1)
								." AND quest_id = '".$questions_data[$i]->id."' AND a.start_id IN (".$start_ids.") "
								." AND survey_id = '".$questions_data[$i]->sf_survey."' ";
						$database->SetQuery( $query );
						$ans_txt_data = @array_merge(array(0=>0),$database->LoadResultArray());
						
						$query = "SELECT b.ans_txt, count(a.answer) as ans_count FROM #__survey_force_user_ans_txt as b,"
						. "\n #__survey_force_user_answers as a"
						. "\n WHERE a.quest_id = '".$questions_data[$i]->id."'"
						. "\n AND a.answer = b.id AND a.start_id IN (".$start_ids.") "
						. "\n AND a.answer IN (".implode(',', $ans_txt_data).") "
						. "\n GROUP BY b.ans_txt ORDER BY ans_count DESC LIMIT 0,5";
						$database->SetQuery( $query );
						$ans_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
						$jj = 0;
						$tmp = array();
						while ( $jj < count($ans_data) ) {
							$tmp[$jj]->num = $jj;
							$tmp[$jj]->ftext = $ans_data[$jj]->ans_txt;
							$tmp[$jj]->ans_count = ($is_pc? intval($ans_data[$jj]->ans_count/$questions_data[$i]->total_answers*100): $ans_data[$jj]->ans_count);
							$jj ++;
						}
						$questions_data[$i]->answer[$j] = $tmp;
						
						$query = "SELECT b.ans_txt FROM #__survey_force_user_ans_txt as b, #__survey_force_user_answers as a"
								. "\n WHERE a.quest_id = '".$questions_data[$i]->id."' AND a.answer = b.id"
								. "\n AND a.answer IN (".implode(',', $ans_txt_data).") AND a.start_id IN (".$start_ids.") "
								. "\n ORDER BY a.sf_time DESC LIMIT 0,100";
						$database->SetQuery( $query );
						$ans_data = $database->loadResultArray();
						$ans_data = (is_array($ans_data)?$ans_data:array());
						$questions_data[$i]->answers_top100[$j] = implode(', ',$ans_data);
					}
				}
				else {
					$query = "SELECT id FROM #__survey_force_user_answers"
					. "\n WHERE quest_id = '".$questions_data[$i]->id."' AND survey_id = '".$questions_data[$i]->sf_survey."' AND start_id IN (".$start_ids.")  GROUP BY start_id, quest_id ";
					$database->SetQuery( $query );
					$questions_data[$i]->total_answers = count($database->LoadResultArray());
	
					$query = "SELECT b.ans_txt, count(a.answer) as ans_count FROM #__survey_force_user_ans_txt as b, #__survey_force_user_answers as a"
					. "\n WHERE a.quest_id = '".$questions_data[$i]->id."' and a.survey_id = '".$questions_data[$i]->sf_survey."' and a.answer = b.id and a.start_id IN (".$start_ids.")"
					. "\n GROUP BY b.ans_txt ORDER BY ans_count DESC LIMIT 0,5";
					$database->SetQuery( $query );
					$ans_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
					$questions_data[$i]->answer = array();
					$j = 0;
					while ( $j < count($ans_data) ) {
						$questions_data[$i]->answer[$j]->num = $j;
						$questions_data[$i]->answer[$j]->ftext = $ans_data[$j]->ans_txt;
						$questions_data[$i]->answer[$j]->ans_count = ($is_pc? intval($ans_data[$j]->ans_count/$questions_data[$i]->total_answers*100):$ans_data[$j]->ans_count);
						$j ++;
					}
					$ans_data = array();
					$query = "SELECT b.ans_txt FROM #__survey_force_user_ans_txt as b, #__survey_force_user_answers as a"
					. "\n WHERE a.quest_id = '".$questions_data[$i]->id."' and a.survey_id = '".$questions_data[$i]->sf_survey."' and a.start_id IN (".$start_ids.") and a.answer = b.id "
					. "\n ORDER BY a.sf_time DESC LIMIT 0,100";
					$database->SetQuery( $query );
					$ans_data = $database->loadResultArray();
					if (count($ans_data) > 0) {
						$questions_data[$i]->answers_top100 = implode(', ',$ans_data);
					} else { $questions_data[$i]->answers_top100 = ''; }
				}
			break;
			case 1:
				$query = "SELECT count(distinct start_id) FROM #__survey_force_user_answers"
				. "\n WHERE quest_id = '".$questions_data[$i]->id."' and survey_id = '".$questions_data[$i]->sf_survey."' and start_id IN (".$start_ids.")";
				$database->SetQuery( $query );
				$questions_data[$i]->total_answers = $database->LoadResult();
				
				$query = "SELECT * FROM #__survey_force_fields WHERE quest_id = '".$questions_data[$i]->id."' ORDER by ordering";
				$database->SetQuery( $query );
				$f_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
				$j = 0;
				$questions_data[$i]->answer = array();
				while ( $j < count($f_data) ) {
					$query = "SELECT b.stext, count(a.answer) as ans_count FROM #__survey_force_scales as b LEFT JOIN #__survey_force_user_answers as a ON ( a.ans_field = b.id AND a.answer = '".$f_data[$j]->id."' AND a.start_id IN (".$start_ids.") AND a.quest_id = '".$questions_data[$i]->id."' )"
					. "\n WHERE b.quest_id = '".$questions_data[$i]->id."'"
					. "\n GROUP BY b.stext ORDER BY b.ordering";
					$database->SetQuery( $query );
					$ans_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
					$questions_data[$i]->answer[$j]->full_ans = array();
					$jj = 0;
					$questions_data[$i]->answer[$j]->ftext = $f_data[$j]->ftext; 
					while ( $jj < count($ans_data) ) {
						$questions_data[$i]->answer[$j]->full_ans[$jj]->ftext = $ans_data[$jj]->stext;
						$questions_data[$i]->answer[$j]->full_ans[$jj]->ans_count = ($is_pc? intval($ans_data[$jj]->ans_count/$questions_data[$i]->total_answers*100):$ans_data[$jj]->ans_count);
						$jj ++;
					}
					$j++;
				}
			break;
			case 5:
			case 6:
			case 9:
				$query = "SELECT count(distinct start_id) FROM #__survey_force_user_answers"
				. "\n WHERE quest_id = '".$questions_data[$i]->id."' and survey_id = '".$questions_data[$i]->sf_survey."' and start_id IN (".$start_ids.")";
				$database->SetQuery( $query );
				$questions_data[$i]->total_answers = $database->LoadResult();
				
				$query = "SELECT * FROM #__survey_force_fields WHERE quest_id = '".$questions_data[$i]->id."' and is_main = '1' ORDER by ordering";
				$database->SetQuery( $query );
				$f_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
				$j = 0;
				$questions_data[$i]->answer = array();
				while ( $j < count($f_data) ) {
					$query = "SELECT b.ftext, count(a.answer) as ans_count FROM #__survey_force_fields as b LEFT JOIN #__survey_force_user_answers as a ON ( a.ans_field = b.id AND a.answer = '".$f_data[$j]->id."' AND a.start_id IN (".$start_ids.") AND a.quest_id = '".$questions_data[$i]->id."' )"
					. "\n WHERE b.quest_id = '".$questions_data[$i]->id."' and b.is_main = '0'"
					. "\n GROUP BY b.ftext ORDER BY b.ordering";//ans_count DESC
					$database->SetQuery( $query );
					$ans_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
					$questions_data[$i]->answer[$j]->full_ans = array();
					$jj = 0;
					$questions_data[$i]->answer[$j]->ftext = $f_data[$j]->ftext; 
					while ( $jj < count($ans_data) ) {
						$questions_data[$i]->answer[$j]->full_ans[$jj]->ftext = $ans_data[$jj]->ftext;
						$questions_data[$i]->answer[$j]->full_ans[$jj]->ans_count = ($is_pc? intval($ans_data[$jj]->ans_count/$questions_data[$i]->total_answers*100):$ans_data[$jj]->ans_count);
						$jj ++;
					}
					$j++;
				}
			break;
		}
	$i++;
	}

	if ($is_pdf) {
		SF_PrintRepSurv_List( $survey_data, $questions_data, 0, $is_pc );
	} else{
		return;
		survey_force_adm_html::SF_ViewRepSurv_List( $option, $survey_data, $questions_data, 1, $list_data->id );
	}
}

			#######################################
			###	--- ---  PRINT PDF's	--- --- ###
function SF_Print_PDF_Footers(&$pdf) {
	global $mosConfig_sitename, $mosConfig_live_site, $mosConfig_offset;
	$pdf->addText( 250, 822, 6, $mosConfig_sitename );
	$pdf->line( 10, 40, 578, 40 );
	$pdf->line( 10, 818, 578, 818 );
	$pdf->addText( 30, 34, 6, $mosConfig_live_site );
	$pdf->addText( 250, 34, 6, _SURVEY_FORCE_COMP_NAME );
	$pdf->addText( 450, 34, 6, _PDF_GENERATED .' '. date( 'j F, Y, H:i', time() + $mosConfig_offset * 60 * 60 ) );
}

function SF_PrintReports( $rows ) {
	global $mainframe, $mosConfig_offset;
	global $mosConfig_absolute_path;
	chdir($mosConfig_absolute_path );
	include( _SURVEY_FORCE_ADMIN_HOME . '/includes/class.ezpdf.php' );
	//
	$pdf = new Cezpdf( 'a4', 'P' );  //A4 Portrait
	$pdf -> ezSetCmMargins( 2, 1.5, 1, 1);
	$pdf->selectFont( $mosConfig_absolute_path.'/media/Helvetica.afm' ); //choose font
	//
	$all = $pdf->openObject();
	$pdf->saveState();
	$pdf->setStrokeColor( 0, 0, 0, 1 );
	// footer and header
	SF_Print_PDF_Footers($pdf);
	//
	$pdf->restoreState();
	$pdf->closeObject();
	$pdf->addObject( $all, 'all' );
	$pdf->ezSetDy( 30 );

	//get PDF content

	for ($i=0, $n=count($rows); $i < $n; $i++) {
		$row = $rows[$i];
		$text_to_pdf = mosFormatDate( $row->sf_time, _CURRENT_SERVER_TIME_FORMAT ,$mosConfig_offset) . "  - " . (($row->is_complete)?'completed':'not completed') . "\n";
		$text_to_pdf .= $row->survey_name . "\n";
		switch($row->usertype) {
			case '0': $text_to_pdf .= JText::_('COM_SF_GUEST')." - "; break;
			case '1': $text_to_pdf .= JText::_('COM_SF_REGISTERED_USER')." - "; break;
			case '2': $text_to_pdf .= JText::_('COM_SF_INVITED_USER')." - "; break;
		}
		switch($row->usertype) {
			case '0': $text_to_pdf .= JText::_('COM_SF_ANONYMOUS'); break;
			case '1': $text_to_pdf .= $row->reg_username.", ".$row->reg_name." (".$row->reg_email.")"; break;
			case '2': $text_to_pdf .= $row->inv_name." ".$row->inv_lastname." (".$row->inv_email.")"; break;
		}
		$pdf->ezText( $text_to_pdf, 12 );
		$pdf->line( 10, $pdf->y - 10, 578, $pdf->y - 10);
		$text_to_pdf = "\n";
		$pdf->ezText( $text_to_pdf, 6 );
	}	
	
	$filedata = $pdf->ezOutput();
	@ob_end_clean();
	header("Content-type: application/pdf");
	header("Content-Length: ".strlen(ltrim($filedata)));
	header("Content-Disposition: attachment; filename=report.pdf");
	echo $filedata;
	die;
}

function SF_PrintReportsPDF_full( $rows ) {
	global $mainframe, $front_end, $mosConfig_offset;
	global $mosConfig_absolute_path;
	chdir($mosConfig_absolute_path );
	
	/*
	 * Create the pdf document
	 */

$no_answer_str = JText::_('COM_SF_NO_ANSWER');
	if ( $front_end ) {
		global $sf_lang;
		$no_answer_str = $sf_lang['SURVEY_NO_ANSWER'];

	}

	require_once(_SURVEY_FORCE_ADMIN_HOME . '/tcpdf/sf_pdf.php');
	
	$pdf_doc = new sf_pdf();

	$pdf = &$pdf_doc->_engine;

	$pdf->AliasNbPages();
	$pdf->AddPage();
	
	$cur_survey = -1;
	$is_first = 1;
	for ($i=0, $n=count($rows); $i < $n; $i++) {
		$row = $rows[$i];
		if ($cur_survey != $row->survey_id) {
		if (!$is_first) {$pdf->AddPage();}
			$is_first = 0;
			$pdf->SetFontSize(10);
			$pdf->setStyle('b', true);
			$pdf->Write(5,text::_('COM_SF_SURVEY_INFORMATION'), '', 0);
			$pdf->Ln();$pdf->Ln();
			
			$pdf->SetFontSize(8);	
			$pdf->Write(5,JText::_('COM_SF_NAME').": ", '', 0);
			
			$pdf->setStyle('b', false);
			$pdf->Write(5, $pdf_doc->cleanText($row->survey_data[0]->sf_name), '', 0);
			$pdf->Ln();
			
			$pdf->setStyle('b', true);
			$pdf->Write(5,JText::_('COM_SF_DESCRIPTION'), '', 0);
			
			$pdf->setStyle('b', false);
			$pdf->Write(5, $pdf_doc->cleanText($row->survey_data[0]->sf_descr), '', 0);
			$pdf->Ln();

			$pdf->line( 15, $pdf->GetY(), 200, $pdf->GetY());
			$pdf->line( 15, $pdf->GetY()+2, 200, $pdf->GetY()+2);
			$pdf->Ln();
		}
		$cur_survey = $row->survey_id;
		
		$pdf->SetFontSize(10);
		$pdf->setStyle('b', true);
		$pdf->Write(5,JText::_('COM_SF_USER_INFORMATION'), '', 0);
		$pdf->Ln();
		
		$pdf->SetFontSize(8);	
		$pdf->Write(5,JText::_('COM_SF_START_AT').": ", '', 0);	
		$pdf->Ln();
		
		$text_to_pdf = mosFormatDate( $row->start_data[0]->sf_time, _CURRENT_SERVER_TIME_FORMAT, $mosConfig_offset ) . (($row->is_complete)?' (completed)':' (not completed)'); 
		$text_to_pdf = $pdf_doc->cleanText($text_to_pdf);
		$pdf->setStyle('b', false);
		$pdf->Write(5, $text_to_pdf, '', 0);
		$pdf->Ln();	
		
		$pdf->setStyle('b', true);
		$pdf->Write(5,JText::_('COM_SF_USER').": ", '', 0);
		
		$pdf->setStyle('b', false);
		$text_to_pdf = '';
		switch($row->usertype) {
			case '0': $text_to_pdf .= JText::_('COM_SF_GUEST')." - "; break;
			case '1': $text_to_pdf .= JText::_('COM_SF_REGISTERED_USER')." - "; break;
			case '2': $text_to_pdf .= JText::_('COM_SF_INVITED_USER')." - "; break;
		}
		switch($row->usertype) {
			case '0': $text_to_pdf .= JText::_('COM_SF_ANONYMOUS'); break;
			case '1': $text_to_pdf .= $row->reg_username.", ".$row->reg_name." (".$row->reg_email.")"; break;
			case '2': $text_to_pdf .= $row->inv_name." ".$row->inv_lastname." (".$row->inv_email.")"; break;
		}
		$text_to_pdf = $pdf_doc->cleanText($text_to_pdf);
		$pdf->Write(5, $pdf_doc->cleanText($text_to_pdf), '', 0);
		$pdf->Ln();		

		$pdf->line( 15, $pdf->GetY(), 200, $pdf->GetY());
		$pdf->line( 15, $pdf->GetY()+2, 200, $pdf->GetY()+2);
		$pdf->Ln();	
		
		$pdf->setStyle('b', true);
		$pdf->Write(5,JText::_('COM_SF_USER_ANSWERS'), '', 0);
		$pdf->Ln();
		$pdf->line( 15, $pdf->GetY(), 200, $pdf->GetY());
		$pdf->Ln();
		$pdf->setStyle('b', false);
		
		foreach ($row->questions_data as $qrow) {
			$text_to_pdf = $pdf_doc->cleanText($qrow->sf_qtext);
			$pdf->SetFontSize(10);	
			$pdf->Write(5, $text_to_pdf, '', 0); 
			$pdf->Ln();
		
			switch ($qrow->sf_qtype) {
				case 2:
				case 3:
					$text_to_pdf = '';
					foreach ($qrow->answer as $arow) {
						$img_ans = $arow->alt_text ? " - ".JText::_('COM_SF_USER_CHOICE') : '';
						$text_to_pdf .= $arow->f_text . $img_ans . "\n";
					}	
					$text_to_pdf 	= $pdf_doc->cleanText( $text_to_pdf );
					$pdf->SetFontSize(8);
	
					$pdf->Write(5, $text_to_pdf, '', 0); 
					$pdf->Ln();
				break;
				case 1:	$text_to_pdf = JText::_('COM_SF_SCALE').": " . $qrow->scale; 
						$text_to_pdf 	= $pdf_doc->cleanText( $text_to_pdf );
						$pdf->SetFontSize(8);	
						$pdf->Write(5, $text_to_pdf, '', 0); 
						$pdf->Ln();
				case 5:
				case 6:
				case 9:
					$text_to_pdf = '';
					foreach ($qrow->answer as $arow) {
						$text_to_pdf .= $arow->f_text . " - " . $arow->alt_text . "\n";
					}
					$text_to_pdf 	= $pdf_doc->cleanText( $text_to_pdf );
					$pdf->SetFontSize(8);	
					$pdf->Write(5, $text_to_pdf, '', 0); 
					$pdf->Ln();
				break;
				case 4:
					if (isset($qrow->answer_count)){
						$tmp = JText::_('COM_SF_1ST_ANSWER');
						for($ii = 1; $ii <= $qrow->answer_count; $ii++) {
							if ($ii == 2) $tmp = JText::_('COM_SF_SECOND_ANSWER');
							elseif($ii == 3)	$tmp = JText::_('COM_SF_THIRD_ANSWER');
							elseif ($ii > 3) $tmp = $ii.JText::_('COM_SF_TH_ANSWER');
							foreach($qrow->answer as $answer) {
								if ($answer->ans_field == $ii) {
									$text_to_pdf = $tmp.($answer->ans_txt == ''?' '.$no_answer_str:$answer->ans_txt)."\n";
									$text_to_pdf = $pdf_doc->cleanText($text_to_pdf);
									$pdf->SetFontSize(8);
									$pdf->Write(5, $text_to_pdf, '', 0);
									$pdf->Ln();
									$tmp = -1;
								}
							}
							if ($tmp != -1)	{
								$text_to_pdf = $tmp." ".$no_answer_str."\n";
								$text_to_pdf = $pdf_doc->cleanText($text_to_pdf);
								$pdf->SetFontSize(8);	
								$pdf->Write(5, $text_to_pdf, '', 0);
								$pdf->Ln();
							}
						}
					}
					else {
						$text_to_pdf = $qrow->answer . "\n";
						$text_to_pdf = $pdf_doc->cleanText($text_to_pdf);
						$pdf->SetFontSize(8);	
						$pdf->Write(5, $text_to_pdf, '', 0);
						$pdf->Ln();

					}
					break;
				default:
					$text_to_pdf = $qrow->answer . "\n";
					$text_to_pdf = $pdf_doc->cleanText($text_to_pdf);
					$pdf->SetFontSize(8);		
					$pdf->Write(5, $text_to_pdf, '', 0);
					$pdf->Ln();
				break;
			}
			if ($qrow->sf_impscale) {
				$text_to_pdf = $qrow->iscale_name;
				$text_to_pdf = $pdf_doc->cleanText($text_to_pdf);
				$pdf->SetFontSize(10);	
				$pdf->Write(5, $text_to_pdf, '', 0);
				$pdf->Ln();
				$text_to_pdf = '';
				foreach ($qrow->answer_imp as $arow) {
					$img_ans = $arow->alt_text ? " - ".JText::_('COM_SF_USER_CHOICE') : '';
					$text_to_pdf .= $arow->f_text . $img_ans . "\n";
				}
				$text_to_pdf = $pdf_doc->cleanText($text_to_pdf);
				$pdf->SetFontSize(8);		
		
				$pdf->Write(5, $text_to_pdf, '', 0);
				$pdf->Ln();
			}
			$pdf->line( 15, $pdf->GetY(), 200, $pdf->GetY());
		}
		$pdf->line( 15, $pdf->GetY(), 200, $pdf->GetY());
	}
	
	$data = $pdf->Output('', 'S'); 
	
	@ob_end_clean();
	header("Content-type: application/pdf");
	header("Content-Length: ".strlen(ltrim($data)));
	header("Content-Disposition: attachment; filename=report.pdf");
	echo $data;
	die;
}

//function not used (01.09.2006) (new in SF_PrintReportsCSV_sum()
function SF_PrintReportsCSV_full( $rows ) {
	global $mainframe, $mosConfig_offset;
	$text_to_csv = "";
	$cur_survey = -1;
	for ($i=0, $n=count($rows); $i < $n; $i++) {
		$row = $rows[$i];
		if ($cur_survey != $row->survey_id) {
			$text_to_csv .= JText::_('COM_SF_SURVEY_INFORMATION').':,'."\n";
			$text_to_csv .= JText::_('COM_SF_NAME').':,';
			$text_to_csv .= $row->survey_data[0]->sf_name.","."\n";
			$text_to_csv .= JText::_('COM_SF_DESCRIPTION').',';
			$text_to_csv .= SF_processPDFField($row->survey_data[0]->sf_descr).","."\n";
			$text_to_csv .= "\n";
		}
		$cur_survey = $row->survey_id;
		$text_to_csv .= "\n";

		$text_to_csv .= JText::_('COM_SF_USER_INFORMATION').','."\n";
		$text_to_csv .= JText::_('COM_SF_START_AT').':,';
		$text_to_csv .= mosFormatDate( $row->start_data[0]->sf_time, _CURRENT_SERVER_TIME_FORMAT, $mosConfig_offset ) ."," . (($row->is_complete)?' (completed)':' (not completed)') . "," . "\n";
		$text_to_csv .= JText::_('COM_SF_USER').':,';
		switch($row->usertype) {
			case '0': $text_to_csv .= JText::_('COM_SF_GUEST').','; break;
			case '1': $text_to_csv .= JText::_('COM_SF_REGISTERED_USER').','; break;
			case '2': $text_to_csv .= JText::_('COM_SF_INVITED_USER').','; break;
			default: $text_to_csv .= ","; break;
		}
		switch($row->usertype) {
			case '0': $text_to_csv .= JText::_('COM_SF_ANONYMOUS').','; break;
			case '1': $text_to_csv .= $row->reg_username.", ".$row->reg_name." (".$row->reg_email."),"; break;
			case '2': $text_to_csv .= $row->inv_name." ".$row->inv_lastname." (".$row->inv_email."),"; break;
			default: $text_to_csv .= ","; break;
		}

		$text_to_csv .= "\n".JText::_('COM_SF_USER_INFORMATION').',' . "\n";
		foreach ($row->questions_data as $qrow) {
			$text_to_csv .= "\n" . SF_processPDFField($qrow->sf_qtext) ."," ."\n";
			switch ($qrow->sf_qtype) {
				case 2:
				case 3:
					foreach ($qrow->answer as $arow) {
						$img_ans = $arow->alt_text ? JText::_('COM_SF_USER_CHOICE')."," : ',';
						$text_to_csv .= $arow->f_text . "," . $img_ans . "," . "\n";
					}
				break;
				case 1:	$text_to_csv .= JText::_('COM_SF_SCALE').":,". $qrow->scale . "," . "\n";
				case 5:
				case 6:
				case 9:
					foreach ($qrow->answer as $arow) {
						$text_to_csv .= $arow->f_text . "," . $arow->alt_text . "," . "\n";
					}
				break;
				case 4:
					if (isset($qrow->answer_count)){
						$tmp = JText::_('COM_SF_1ST_ANSWER');
						for($ii = 1; $ii <= $qrow->answer_count; $ii++) {
							if ($ii == 2) $tmp = JText::_('COM_SF_SECOND_ANSWER');
							elseif($ii == 3)	$tmp = JText::_('COM_SF_THIRD_ANSWER');
							elseif ($ii > 3) $tmp = $ii.JText::_('COM_SF_TH_ANSWER');
							foreach($qrow->answer as $answer) {
								if ($answer->ans_field == $ii) {
									$text_to_csv .= $tmp.($answer->ans_txt == ''?' '.$no_answer_str:SF_processCSVField($answer->ans_txt)) . "," . "\n";
									$tmp = -1;
								}
							}
							if ($tmp != -1)	{
								$text_to_csv .= $tmp." ".$no_answer_str."," . "\n";
							}
						}
					}
					else {
						$text_to_csv .= SF_processCSVField($qrow->answer) . "," . "\n";
					}
				default:
					$text_to_csv .= SF_processPDFField($qrow->answer) . "," . "\n";
				break;
			}
			if ($qrow->sf_impscale) {
				$text_to_csv .= $qrow->iscale_name . "," . "\n";
				foreach ($qrow->answer_imp as $arow) {
					$img_ans = $arow->alt_text ? JText::_('COM_SF_USER_CHOICE')."," : ',';
					$text_to_csv .= $arow->f_text . "," . $img_ans . "," . "\n";
				}
			}
		}
	}
	@ob_end_clean();
	header("Content-type: application/csv");
	header("Content-Length: ".strlen(ltrim($text_to_csv)));
	header("Content-Disposition: inline; filename=report.csv");
	echo $text_to_csv; 
	die;
}

function SF_PrintReportsCSV_sum( $rows ) {
	global $mainframe;
	$text_to_csv = "";
	$cur_survey = -1;
	for ($ij=0, $n=count($rows); $ij < $n; $ij++) {
		$row = $rows[$ij];
		if ($cur_survey != $row->survey_id) {
			$text_to_csv .= JText::_('COM_SF_SURVEY_INFORMATION').':,'."\n";
			$text_to_csv .= JText::_('COM_SF_NAME').':,';
			$text_to_csv .= SF_processCSVField($row->survey_data[0]->sf_name).","."\n";
			$text_to_csv .= JText::_('COM_SF_DESCRIPTION').',';
			$text_to_csv .= SF_processCSVField($row->survey_data[0]->sf_descr).","."\n";
		}
		$cur_survey = $row->survey_id;
		$text_to_csv .= "\n".JText::_('COM_SF_ANSWERS').':,' . "\n";
		foreach ($row->questions_data as $qrow) {
			$text_to_csv .= "\n" . SF_processCSVField($qrow->sf_qtext) ."," ."\n";
			switch ($qrow->sf_qtype) {
				case 2:
				case 3:
				case 4:
					if (isset($qrow->answer_count)) {
						$tmp = JText::_('COM_SF_1ST_ANSWER');
						for($ii = 1; $ii <= $qrow->answer_count; $ii++) {
							if ($ii == 2) $tmp = JText::_('COM_SF_SECOND_ANSWER');
							elseif($ii == 3)	$tmp = JText::_('COM_SF_THIRD_ANSWER');
							elseif ($ii > 3) $tmp = $ii.JText::_('COM_SF_TH_ANSWER');
							$text_to_csv .= $tmp  . "\n";
							$total = $qrow->total_answers;
							$i = 0;
							$tmp_data = array();
							if (count($qrow->answer[$ii-1]) > 0 ) {
							foreach ($qrow->answer[$ii-1] as $arow) {
								$tmp_data[$i] = $arow->ans_count;
								$i++;
							}
							foreach ($qrow->answer[$ii-1] as $arow) {
								$text_to_csv .=  SF_processCSVField($arow->ftext) . ",," . $arow->ans_count . "\n";
							}
							if ($qrow->sf_qtype == 4) {
								$text_to_csv .= JText::_('COM_SF_OTHER_ANSWERS').':,,' . SF_processCSVField($qrow->answers_top100[$ii-1]) . "\n";
							}
															
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
						foreach ($qrow->answer as $arow) {
							$text_to_csv .=  SF_processCSVField($arow->ftext) . ",," . $arow->ans_count . "\n";
						}
						if ($qrow->sf_qtype == 4) {
							$text_to_csv .= JText::_('COM_SF_OTHER_ANSWERS').':,,' . SF_processCSVField($qrow->answers_top100) . "\n";
						}
					}
				break;
				
				case 1:
				case 5:
				case 6:
				case 9:
					$total = $qrow->total_answers;
					foreach ($qrow->answer as $arows) { 
						$i = 0;
						$tmp_data = array();
						foreach ($arows->full_ans as $arow) {
							$tmp_data[$i] = $arow->ans_count;
							$i++;
						}
						if (isset($arows->ftext)) {
							$text_to_csv .= JText::_('COM_SF_OPTION').':,' . SF_processCSVField($arows->ftext) . "\n";
						}
	
						foreach ($arows->full_ans as $arow) {
							$text_to_csv .= SF_processCSVField($arow->ftext) . ",," . $arow->ans_count . "\n";
						}
					}
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
				
				$text_to_csv .= SF_processCSVField($qrow->iscale_name) . "\n";
				foreach ($qrow->answer_imp as $arow) {
					$text_to_csv .= SF_processCSVField($arow->ftext) . ",," . $arow->ans_count . "\n";
				}
			}
		}
		$text_to_csv .= "\n";
	}
	@ob_end_clean();
	header("Content-type: application/csv");
	header("Content-Length: ".strlen(ltrim($text_to_csv)));
	header("Content-Disposition: inline; filename=report.csv");
	echo $text_to_csv; 
	die;
}

function SF_PrintRepResult( $start_data, $survey_data, $questions_data ) {
	global $mainframe, $mosConfig_offset;
	global $mosConfig_absolute_path, $mosConfig_sitename, $mosConfig_live_site;
	chdir($mosConfig_absolute_path );
		
	/*
	 * Create the pdf document
	 */

	require_once(_SURVEY_FORCE_ADMIN_HOME . '/tcpdf/sf_pdf.php');
	
	$pdf_doc = new sf_pdf();

	$pdf = &$pdf_doc->_engine;

	$pdf->AliasNbPages();
	$pdf->AddPage();
	
	$s_user = '';
	switch($start_data[0]->usertype) {
		case '0': $s_user = JText::_('COM_SF_ANONYMOUS'); break;
		case '1': $s_user = JText::_('COM_SF_REGISTERED_USER').": ".$start_data[0]->reg_username.", ".$start_data[0]->reg_name." (".$start_data[0]->reg_email.")"; break;
		case '2': $s_user = JText::_('COM_SF_INVITED_USER').": ".$start_data[0]->inv_name." ".$start_data[0]->inv_lastname." (".$start_data[0]->inv_email.")"; break;
	}
	$s_user = $pdf_doc->cleanText($s_user);
	
	$pdf->SetFontSize(10);
	$pdf->setStyle('b', true);
	$pdf->Write(5,JText::_('COM_SF_SURVEY_INFORMATION'), '', 0);
	$pdf->Ln();$pdf->Ln();
	
	$pdf->SetFontSize(8);	
	$pdf->Write(5,JText::_('COM_SF_NAME').": ", '', 0);
	
	$pdf->setStyle('b', false);
	$pdf->Write(5, $pdf_doc->cleanText($survey_data[0]->sf_name), '', 0);
	$pdf->Ln();
	
	$pdf->setStyle('b', true);
	$pdf->Write(5,JText::_('COM_SF_DESCRIPTION'), '', 0);
	
	$pdf->setStyle('b', false);
	$pdf->Write(5, $pdf_doc->cleanText($survey_data[0]->sf_descr), '', 0);
	$pdf->Ln();
	
	$pdf->setStyle('b', true);
	$pdf->Write(5,JText::_('COM_SF_START_AT').": ", '', 0);
	
	$pdf->setStyle('b', false);
	$pdf->Write(5, $pdf_doc->cleanText(mosFormatDate( $start_data[0]->sf_time, _CURRENT_SERVER_TIME_FORMAT, $mosConfig_offset )), '', 0);
	$pdf->Ln();
	
	$pdf->setStyle('b', true);
	$pdf->Write(5,JText::_('COM_SF_USER').": ", '', 0);
	
	$pdf->setStyle('b', false);
	$pdf->Write(5, $s_user, '', 0);
	$pdf->Ln();$pdf->Ln();
	
	$pdf->setStyle('b', true);
	$pdf->Write(5,JText::_('COM_SF_USER_ANSWERS'), '', 0);
	$pdf->Ln();
	$pdf->line( 15, $pdf->GetY(), 200, $pdf->GetY());
	$pdf->Ln();
	$pdf->setStyle('b', false);
	
	
	foreach ($questions_data as $qrow) {
		$text_to_pdf = $pdf_doc->cleanText($qrow->sf_qtext);
		$pdf->SetFontSize(10);	
		$pdf->Write(5, $text_to_pdf, '', 0); 
		$pdf->Ln();
		switch ($qrow->sf_qtype) {
			case 2:
			case 3:
				$text_to_pdf = '';
				foreach ($qrow->answer as $arow) {
					$img_ans = $arow->alt_text ? " - ".JText::_('COM_SF_USER_CHOICE') : '';
					$text_to_pdf .= $arow->f_text . $img_ans . "\n";
				}
				$text_to_pdf 	= $pdf_doc->cleanText( $text_to_pdf );
				$pdf->SetFontSize(8);

				$pdf->Write(5, $text_to_pdf, '', 0); 
				$pdf->Ln();
			break;
			case 1:	$text_to_pdf = JText::_('COM_SF_SCALE').": " . $qrow->scale;
				$text_to_pdf 	= $pdf_doc->cleanText( $text_to_pdf );
				$pdf->SetFontSize(8);	
				$pdf->Write(5, $text_to_pdf, '', 0); 
				$pdf->Ln();
			case 5:
			case 6:
			case 9:
				$text_to_pdf = '';
				foreach ($qrow->answer as $arow) {
					$text_to_pdf .= $arow->f_text . " - " . $arow->alt_text . "\n";
				}
				$text_to_pdf 	= $pdf_doc->cleanText( $text_to_pdf );
				$pdf->SetFontSize(8);
				$pdf->Write(5, $text_to_pdf, '', 0);
				$pdf->Ln();
			break;
			case 4:
				if (isset($qrow->answer_count)){
					$tmp = JText::_('COM_SF_1ST_ANSWER');
					for($i = 1; $i <= $qrow->answer_count; $i++) {
						if ($i == 2) $tmp = JText::_('COM_SF_SECOND_ANSWER');
						elseif($i == 3)	$tmp = JText::_('COM_SF_THIRD_ANSWER');
						else $tmp = $i.JText::_('COM_SF_TH_ANSWER');
						foreach($qrow->answer as $answer) {
							if ($answer->ans_field == $i) {
								$text_to_pdf = $tmp.($answer->ans_txt == ''?' '.$no_answer_str:$answer->ans_txt). "\n";
								$text_to_pdf = $pdf_doc->cleanText($text_to_pdf);
								$pdf->SetFontSize(8);
								$pdf->Write(5, $text_to_pdf, '', 0);
								$pdf->Ln();
								$tmp = -1;
								}
						}
						if ($tmp != -1)	{
							$text_to_pdf = $tmp." ".$no_answer_str."\n";
							$text_to_pdf = $pdf_doc->cleanText($text_to_pdf);
							$pdf->SetFontSize(8);	
							$pdf->Write(5, $text_to_pdf, '', 0);
							$pdf->Ln();
						}
					}
				}
				else {
					$text_to_pdf = $qrow->answer . "\n";
					$text_to_pdf = $pdf_doc->cleanText($text_to_pdf);
					$pdf->SetFontSize(8);	
					$pdf->Write(5, $text_to_pdf, '', 0);
					$pdf->Ln();
				}
				break;
			default:
				$text_to_pdf = $qrow->answer . "\n";
				$text_to_pdf = $pdf_doc->cleanText($text_to_pdf);
				$pdf->SetFontSize(8);		
				$pdf->Write(5, $text_to_pdf, '', 0);
				$pdf->Ln();
			break;
		}
		if ($qrow->sf_impscale) {
			$text_to_pdf = $qrow->iscale_name;
			$text_to_pdf = $pdf_doc->cleanText($text_to_pdf);
			$pdf->SetFontSize(10);	
			$pdf->Write(5, $text_to_pdf, '', 0);
			$pdf->Ln();
			$text_to_pdf = '';
			foreach ($qrow->answer_imp as $arow) {
				$img_ans = $arow->alt_text ? " - ".JText::_('COM_SF_USER_CHOICE') : '';
				$text_to_pdf .= $arow->f_text . $img_ans . "\n";
			}
			$text_to_pdf = $pdf_doc->cleanText($text_to_pdf);
			$pdf->SetFontSize(8);		
	
			$pdf->Write(5, $text_to_pdf, '', 0);
			$pdf->Ln();
		}
		$pdf->line( 15, $pdf->GetY(), 200, $pdf->GetY());
		$pdf->Ln();		
	}
		
	$data = $pdf->Output('', 'S'); 
	
	@ob_end_clean();
	header("Content-type: application/pdf");
	header("Content-Length: ".strlen(ltrim($data)));
	header("Content-Disposition: attachment; filename=report.pdf");
	echo $data;
	die;
}

function SF_PrintRepSurv_List( $survey_data, $questions_data, $is_list = 0 , $is_pc = 0) {
	global $mainframe, $mosConfig_live_site;
	global $mosConfig_absolute_path;
	chdir($mosConfig_absolute_path );
	clearOldImages();
	/*
	 * Create the pdf document
	 */

	require_once(_SURVEY_FORCE_ADMIN_HOME . '/tcpdf/sf_pdf.php');
	
	$pdf_doc = new sf_pdf();

	$pdf = &$pdf_doc->_engine;

	$pdf->AliasNbPages();
	$pdf->AddPage();

	//get PDF content
	$pdf->SetFontSize(10);
	$pdf->setStyle('b', true);
	$pdf->Write(5,JText::_('COM_SF_SURVEY_INFORMATION'), '', 0);
	$pdf->Ln();$pdf->Ln();
	
	$pdf->SetFontSize(8);	
	$pdf->Write(5,JText::_('COM_SF_NAME').": ", '', 0);
	
	$pdf->setStyle('b', false);
	$pdf->Write(5, $pdf_doc->cleanText($survey_data->sf_name), '', 0);
	$pdf->Ln();
	
	$pdf->setStyle('b', true);
	$pdf->Write(5,JText::_('COM_SF_DESCRIPTION'), '', 0);
	
	$pdf->setStyle('b', false);
	$pdf->Write(5, $pdf_doc->cleanText($survey_data->sf_descr), '', 0);
	$pdf->Ln();$pdf->Ln();

	if ($is_list == 1) {
		$pdf->SetLeftMargin($pdf_doc->_margin_left);
		$options = array('total' => (($survey_data->total_starts > $survey_data->total_inv_users)? $survey_data->total_starts: $survey_data->total_inv_users),
						 'grids' => $survey_data->total_inv_users.','.$survey_data->total_starts.','.$survey_data->total_completes,
						 'fileName' => $mosConfig_absolute_path.'/images/surveyforce/gen_images/'.(strlen(date('d')) < 2? '0'.date('d'): date('d')).'_'.md5(uniqid(mktime())).'.png' );	
		SF_draw_grid($options);
		$pdf->Image($options['fileName'], $pdf->GetX(), $pdf->GetY(), 0, 0, '', '', '', false, 50);
		
		$text_to_pdf = $survey_data->total_inv_users . " - ".JText::_('COM_SF_TOTAL_INVITED_USERS');
		$pdf->SetLeftMargin(60);
		$pdf->setStyle('b', false);
		$pdf->Write(4.5, $pdf_doc->cleanText($text_to_pdf), '', 0);
		$pdf->Ln();
		$pdf->Write(4.5, $pdf_doc->cleanText($survey_data->total_starts . " - ".JText::_('COM_SF_TOTAL_STARTS_OF_SURVEY')), '', 0);
		$pdf->Ln();
		$pdf->Write(4.5, $pdf_doc->cleanText($survey_data->total_completes . " - ".JText::_('COM_SF_TOTAL_COMPLETES_OF_SURVEY')), '', 0);
		$pdf->Ln();
		$pdf->SetLeftMargin($pdf_doc->_margin_left);
	} else {
		$pdf->SetLeftMargin($pdf_doc->_margin_left);
		$options = array('total' => $survey_data->total_starts,
						 'grids' => $survey_data->total_starts.','.$survey_data->total_gstarts.','
		.$survey_data->total_rstarts.','.$survey_data->total_istarts.','.$survey_data->total_completes.','
		.$survey_data->total_gcompletes.','.$survey_data->total_rcompletes.','.$survey_data->total_icompletes,
						 'fileName' => $mosConfig_absolute_path.'/images/surveyforce/gen_images/'.(strlen(date('d')) < 2? '0'.date('d'): date('d')).'_'.md5(uniqid(mktime())).'.png' );	
		SF_draw_grid($options);
		$pdf->Image($options['fileName'], $pdf->GetX(), $pdf->GetY(), 0, 0, '', '', '', false, 50);
		
		$text_to_pdf = $survey_data->total_starts . " - ".JText::_('COM_SF_TOTAL_STARTS_OF_SURVEY');
		$pdf->SetLeftMargin(60);
		$pdf->setStyle('b', false);
		$pdf->Write(4.5, $pdf_doc->cleanText($text_to_pdf), '', 0);
		$pdf->Ln();
		$pdf->Write(4.5, $pdf_doc->cleanText($survey_data->total_gstarts . " - ".JText::_('COM_SF_TOTAL_STARTS_OF_SURVEY_GUEST')), '', 0);
		$pdf->Ln();
		$pdf->Write(4.5, $pdf_doc->cleanText($survey_data->total_rstarts . " - ".JText::_('COM_SF_TOTAL_STARTS_OF_SURVEY_REGISTERED')), '', 0);
		$pdf->Ln();
		$pdf->Write(4.5, $pdf_doc->cleanText($survey_data->total_istarts . " - ".JText::_('COM_SF_TOTAL_STARTS_OF_SURVEY_INVITED')), '', 0);
		$pdf->Ln();
		$pdf->Write(4.5, $pdf_doc->cleanText($survey_data->total_completes . " - ".JText::_('COM_SF_TOTAL_COMPLETES_OF_SURVEY')), '', 0);
		$pdf->Ln();
		$pdf->Write(4.5, $pdf_doc->cleanText($survey_data->total_gcompletes . " - ".JText::_('COM_SF_TOTAL_COMPLETES_OF_SURVEY_GUEST')), '', 0);
		$pdf->Ln();
		$pdf->Write(4.5, $pdf_doc->cleanText($survey_data->total_rcompletes . " - ".JText::_('COM_SF_TOTAL_COMPLETES_OF_SURVEY_REGISTERED')), '', 0);
		$pdf->Ln();
		$pdf->Write(4.5, $pdf_doc->cleanText($survey_data->total_icompletes . " - ".JText::_('COM_SF_TOTAL_COMPLETES_OF_SURVEY_INVITED')), '', 0);
		$pdf->Ln();
		$pdf->SetLeftMargin($pdf_doc->_margin_left);
		
	}
	$pdf->Ln();
	$pdf->line( 15, $pdf->GetY(), 200, $pdf->GetY());
	$pdf->Ln();$pdf->Ln();
	
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

					$text_to_pdf = $pdf_doc->cleanText($qrow->sf_qtext);
					$pdf->SetFontSize(10);	
					$pdf->Write(5, $text_to_pdf, '', 0); 
					$pdf->Ln();

					for($ii = 1; $ii <= $qrow->answer_count; $ii++) {
						if ($ii == 2) $tmp = JText::_('COM_SF_SECOND_ANSWER');
						elseif($ii == 3)	$tmp = JText::_('COM_SF_THIRD_ANSWER');
						elseif ($ii > 3) $tmp = $ii.JText::_('COM_SF_TH_ANSWER');

						$total = $qrow->total_answers;
						$i = 0;
						$tmp_data = array();
						foreach ($qrow->answer[$ii-1] as $arow) {
							$tmp_data[$i] = ($is_pc? round($arow->ans_count*$total/100):$arow->ans_count);
							$i++;
						}
						$rrr = count($tmp_data);						
						
						$text_to_pdf 	= $pdf_doc->cleanText( $tmp );
						$pdf->SetFontSize(8);
		
						$pdf->Write(5, $text_to_pdf, '', 0); 
						$pdf->Ln();
						
						
						$pdf->SetLeftMargin($pdf_doc->_margin_left);
						$options = array('total' => $total,
										 'grids' => implode(',',$tmp_data),
										 'fileName' => $mosConfig_absolute_path.'/images/surveyforce/gen_images/'.(strlen(date('d')) < 2? '0'.date('d'): date('d')).'_'.md5(uniqid(mktime())).'.png' );	
						SF_draw_grid($options);
						$pdf->Image($options['fileName'], $pdf->GetX(), $pdf->GetY(), 0, 0, '', '', '', false, 50);
		
						$pdf->SetLeftMargin(60);
						$pdf->setStyle('b', false);
						$pdf->SetFontSize(8);
						foreach ($qrow->answer[$ii-1] as $arow) {
							$pdf->Write(4.5, $pdf_doc->cleanText($arow->ans_count . ($is_pc?'% ':'')." - " . $arow->ftext), '', 0);
							$pdf->Ln();
						}
						$pdf->SetLeftMargin($pdf_doc->_margin_left);
						if ($qrow->sf_qtype == 4) {
							$pdf->Write(4.5, $pdf_doc->cleanText(JText::_('COM_SF_OTHER_ANSWERS').": " . $qrow->answers_top100[$ii-1]), '', 0);
							$pdf->Ln();
						}						
					}
				}
				else { 
					$total = $qrow->total_answers;
					$i = 0;
					$tmp_data = array();
					foreach ($qrow->answer as $arow) {
						$tmp_data[$i] = ($is_pc? round($arow->ans_count*$total/100):$arow->ans_count);
						$i++;
					}
					$rrr = count($tmp_data);
					
					
					$text_to_pdf 	= $pdf_doc->cleanText( $qrow->sf_qtext );
					$pdf->SetFontSize(10);
		
					$pdf->Write(5, $text_to_pdf, '', 0); 
					$pdf->Ln();
					
					$pdf->SetLeftMargin($pdf_doc->_margin_left);
					$options = array('total' => $total,
									 'grids' => implode(',',$tmp_data),
									 'fileName' => $mosConfig_absolute_path.'/images/surveyforce/gen_images/'.(strlen(date('d')) < 2? '0'.date('d'): date('d')).'_'.md5(uniqid(mktime())).'.png' );	
					SF_draw_grid($options);
					$pdf->Image($options['fileName'], $pdf->GetX(), $pdf->GetY(), 0, 0, '', '', '', false, 50);
						
					$pdf->SetLeftMargin(60);
					$pdf->SetFontSize(8);
					foreach ($qrow->answer as $arow) {
						$pdf->Write(4.5, $pdf_doc->cleanText($arow->ans_count . ($is_pc?'% ':'')." - " . $arow->ftext), '', 0);
						$pdf->Ln();
					}
					$pdf->SetLeftMargin($pdf_doc->_margin_left);
					if ($qrow->sf_qtype == 4) {
						$pdf->Write(4.5, $pdf_doc->cleanText(JText::_('COM_SF_OTHER_ANSWERS').": " . $qrow->answers_top100), '', 0);
						$pdf->Ln();
					}
				}
			break;
			case 1:
			case 5:
			case 6:
			case 9:
				$total = $qrow->total_answers;
				if (count ($qrow->answer) > 0) {
					$rrr = count($qrow->answer[0]->full_ans);					
				}
				$text_to_pdf 	= $pdf_doc->cleanText( $qrow->sf_qtext );
				$pdf->SetFontSize(10);
	
				$pdf->Write(5, $text_to_pdf, '', 0); 
				$pdf->Ln();
								
				foreach ($qrow->answer as $arows) {
					$i = 0;
					$tmp_data = array();
					foreach ($arows->full_ans as $arow) {
						$tmp_data[$i] = ($is_pc? round($arow->ans_count*$total/100):$arow->ans_count);
						$i++;
					}
					$rrr = count($tmp_data);
					
					$text_to_pdf 	= $pdf_doc->cleanText( $arows->ftext );
					$pdf->SetFontSize(10);
		
					$pdf->Write(5, $text_to_pdf, '', 0); 
					$pdf->Ln();
					
					$pdf->SetLeftMargin($pdf_doc->_margin_left);
					$options = array('total' => $total,
									 'grids' => implode(',',$tmp_data),
									 'fileName' => $mosConfig_absolute_path.'/images/surveyforce/gen_images/'.(strlen(date('d')) < 2? '0'.date('d'): date('d')).'_'.md5(uniqid(mktime())).'.png' );	
					SF_draw_grid($options);
					$pdf->Image($options['fileName'], $pdf->GetX(), $pdf->GetY(), 0, 0, '', '', '', false, 50);
					
					$pdf->SetLeftMargin(60);
					$pdf->SetFontSize(8);
					foreach ($arows->full_ans as $arow) {
						$pdf->Write(4.5, $pdf_doc->cleanText($arow->ans_count . ($is_pc?'% ':'')." - " . $arow->ftext), '', 0);
						$pdf->Ln();
					}
					
					$pdf->SetLeftMargin($pdf_doc->_margin_left);
					$pdf->Write(5, '', '', 0); 
					$pdf->Ln();
				}
			break;
		}
		if ($qrow->sf_impscale) {
			$total = $qrow->total_iscale_answers;
			$i = 0;
			$tmp_data = array();
			foreach ($qrow->answer_imp as $arow) {
				$tmp_data[$i] = ($is_pc? round($arow->ans_count*$total/100):$arow->ans_count);
				$i++;
			}
			$rrr = count($tmp_data);
			
			$text_to_pdf 	= $pdf_doc->cleanText( $qrow->iscale_name );
			$pdf->SetFontSize(10);

			$pdf->Ln();
			
			$pdf->SetLeftMargin($pdf_doc->_margin_left);
			$options = array('total' => $total,
							 'grids' => implode(',',$tmp_data),
							 'fileName' => $mosConfig_absolute_path.'/images/surveyforce/gen_images/'.(strlen(date('d')) < 2? '0'.date('d'): date('d')).'_'.md5(uniqid(mktime())).'.png' );	
			SF_draw_grid($options);
			$pdf->Image($options['fileName'], $pdf->GetX(), $pdf->GetY(), 0, 0, '', '', '', false, 50);
					
			$pdf->SetLeftMargin(60);
			$pdf->SetFontSize(8);
			foreach ($qrow->answer_imp as $arow) {
				$pdf->Write(4.5, $pdf_doc->cleanText($arow->ans_count . " - " . $arow->ftext), '', 0);
				$pdf->Ln();
			}
			$pdf->SetLeftMargin($pdf_doc->_margin_left);
		}
		if ($qrow->sf_qtype != 7 && $qrow->sf_qtype != 8 ) {
			$pdf->Ln();
			$pdf->line( 15, $pdf->GetY(), 200, $pdf->GetY());
			$pdf->Ln();
		}
	}
	
	$data = $pdf->Output('', 'S');	
	@ob_end_clean();
	header("Content-type: application/pdf");
	header("Pragma:no-cache");
	header("Content-Length: ".strlen(ltrim($data)));
	header("Content-Disposition: attachment; filename=report.pdf");
	echo $data;
	die;	
}


function get_html_translation_table_my() {
    $trans = get_html_translation_table(HTML_ENTITIES);
    $trans[chr(130)] = '&sbquo;';    // Single Low-9 Quotation Mark
    $trans[chr(131)] = '&fnof;';    // Latin Small Letter F With Hook
    $trans[chr(132)] = '&bdquo;';    // Double Low-9 Quotation Mark
    $trans[chr(133)] = '&hellip;';    // Horizontal Ellipsis
    $trans[chr(134)] = '&dagger;';    // Dagger
    $trans[chr(135)] = '&Dagger;';    // Double Dagger
    $trans[chr(136)] = '&circ;';    // Modifier Letter Circumflex Accent
    $trans[chr(137)] = '&permil;';    // Per Mille Sign
    $trans[chr(138)] = '&Scaron;';    // Latin Capital Letter S With Caron
    $trans[chr(139)] = '&lsaquo;';    // Single Left-Pointing Angle Quotation Mark
    $trans[chr(140)] = '&OElig;    ';    // Latin Capital Ligature OE
    $trans[chr(145)] = '&lsquo;';    // Left Single Quotation Mark
    $trans[chr(146)] = '&rsquo;';    // Right Single Quotation Mark
    $trans[chr(147)] = '&ldquo;';    // Left Double Quotation Mark
    $trans[chr(148)] = '&rdquo;';    // Right Double Quotation Mark
    $trans[chr(149)] = '&bull;';    // Bullet
    $trans[chr(150)] = '&ndash;';    // En Dash
    $trans[chr(151)] = '&mdash;';    // Em Dash
    $trans[chr(152)] = '&tilde;';    // Small Tilde
    $trans[chr(153)] = '&trade;';    // Trade Mark Sign
    $trans[chr(154)] = '&scaron;';    // Latin Small Letter S With Caron
    $trans[chr(155)] = '&rsaquo;';    // Single Right-Pointing Angle Quotation Mark
    $trans[chr(156)] = '&oelig;';    // Latin Small Ligature OE
    $trans[chr(159)] = '&Yuml;';    // Latin Capital Letter Y With Diaeresis
    ksort($trans);
    return $trans;
}

function rel_decodeHTML( $string ) {
	$string = strtr( $string, array_flip(get_html_translation_table_my( ) ) );
	$string = preg_replace( "/&#([0-9]+);/me", "chr('\\1')", $string );
	return $string;
}

function rel_pdfCleaner( $text ) {
	// Ugly but needed to get rid of all the stuff the PDF class cant handle
	$text = str_replace( '<p>', 			"\n\n", 	$text );
	$text = str_replace( '<P>', 			"\n\n", 	$text );
	$text = str_replace( '<br />', 			"\n", 		$text );
	$text = str_replace( '<br>', 			"\n", 		$text );
	$text = str_replace( '<BR />', 			"\n", 		$text );
	$text = str_replace( '<BR>', 			"\n", 		$text );
	$text = str_replace( '<li>', 			"\n - ", 	$text );
	$text = str_replace( '<LI>', 			"\n - ", 	$text );
	$text = str_replace( '{mosimage}', 		'', 		$text );
	$text = str_replace( '{mospagebreak}', 	'',			$text );
	
	$text = strip_tags( $text );
	$text = rel_decodeHTML( $text );

	return $text;
}


//for CSV import
function SF_prepareImport(&$loader, &$fieldDescriptors) {
	$unknownFieldNames	= array();
	$missingFieldNames	= array();
	$requiredFieldNames	= $fieldDescriptors->getRequiredFieldNames();
	$fieldNames	= $loader->getFieldNames();
	foreach($fieldNames as $k => $fieldName) {
		$fieldName			= strtolower(trim($fieldName));
		$fieldNames[$k]		= $fieldName;
		if (!$fieldDescriptors->contains($fieldName)) {
			$unknownFieldNames[]	= $fieldName;
		}
	}
	$loader->setFieldNames($fieldNames);	// set the "normalized" field names
	foreach($requiredFieldNames as $fieldName) {
		if (!in_array($fieldName, $fieldNames)) {
			$missingFieldNames[]	= $fieldName;
		}
	}
	if ((count($unknownFieldNames) > 0) || (count($missingFieldNames) > 0)) {
		return FALSE;
	}
	return TRUE;
}

function SF_prepareImportRow(&$loader, &$fieldDescriptors, $values, $requiredFieldNames, $allFieldNames) {
	$unknownFieldNames	= array();
	$missingFieldNames	= array();
	foreach($requiredFieldNames as $fieldName) {
		if ((!isset($values[$fieldName])) || (trim($values[$fieldName]) == '')) {
			$missingFieldNames[]	= $fieldName;
		}
	}
	if ((count($unknownFieldNames) > 0) || (count($missingFieldNames) > 0)) {
		return FALSE;
	}
	foreach($allFieldNames as $fieldName) {
		if (!isset($values[$fieldName])) {
			$defaultValue		= $fieldDescriptors->getDefaultValue($fieldName);
			if ($defaultValue != '') {
				$values[$fieldName]	= $defaultValue;
			}
		}
	}
	return TRUE;
}

function SF_alterXML()
{
	return;	
}

			#######################################
			###	--- ---  CONFIGURATION  --- --- ###

function SF_viewConfig( $option ) {
	global $database;
	$sf_config = new mos_Survey_Force_Config( );
	// load the row from the db table
	$lists = array();
	survey_force_adm_html::SF_viewConfig( $sf_config, $lists, $option );
}

function SF_saveConfig( $option ) {
	global $database;

	$row = new survey_Force_Adm_Config( );
	$row->loadFromDb();
	if (!$row->bind( $_POST )) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
		
	// save the changes
	if (!$row->saveToDb()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	$msg = JText::_('COM_SF_THE_CONFIGURATION_DETAILS');
	mosRedirect( "index2.php?option=$option&task=config", $msg );
}

function SF_showPreview ( $option ) {
	global $mosConfig_live_site, $mosConfig_absolute_path;
	require ( $mosConfig_absolute_path . '/components/com_surveyforce/language/default.php' );
	require_once(_SURVEY_FORCE_ADMIN_HOME.'/../../../components/com_surveyforce/generate.surveyforce.php');
	$type = mosGetParam($_REQUEST, 'type', '');
	
	$gg = new sf_ImageGenerator(array($type));

	$gg->width = intval(mosGetParam($_REQUEST, 'width', 600));
	$gg->height = intval(mosGetParam($_REQUEST, 'height', 250));
	$rows = array();
	$sections = array();
	$usr_answers = array();
	$tmp = null;$tmp->label = JText::_('COM_SF_NOT_AT_ALL');$tmp->percent = 0;$tmp->number = 10;
	$rows[] = $tmp;		
	$tmp = null;$tmp->label = JText::_('COM_SF_PARENTS');$tmp->percent = 0;$tmp->number = 40;
	$rows[] = $tmp;		
	$tmp = null;$tmp->label = JText::_('COM_SF_GRANDMA_GRANDPA');$tmp->percent = 0;$tmp->number = 25;
	$rows[] = $tmp;		
	$tmp = null;$tmp->label = JText::_('COM_SF_SISTER');$tmp->percent = 0;$tmp->number = 29;
	$rows[] = $tmp;		
	$tmp = null;$tmp->label = JText::_('COM_SF_BROTHER');$tmp->percent = 0;$tmp->number = 19;
	$rows[] = $tmp;		
	$tmp = null;$tmp->label = JText::_('COM_SF_AUNT_UNCLE');$tmp->percent = 0;$tmp->number = 13;
	$rows[] = $tmp;
	
	$sections[1] = $rows;
	$titles[1] = '';
	$maintitle = JText::_('COM_SF_ARE_YOU_CLOSE_TO_ANY');
	$usr_answers[1][] = JText::_('COM_SF_PARENTS');
	
	$gg->clearOldImages();
	echo $gg->createImage($sections, $titles, $usr_answers, $maintitle, 50);
}
			#######################################
			###	--- ---    IMP SCALES   --- --- ###

function SF_viewIScales( $option ) {
	global $database, $mainframe, $mosConfig_list_limit;

	$limit 		= intval( $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', $mosConfig_list_limit ) );
	$limitstart = intval( $mainframe->getUserStateFromRequest( "view{$option}limitstart", 'limitstart', 0 ) );
	if ($limit == 0) $limit = 999999;
	// get the total number of records
	$query = "SELECT COUNT(*)"
	. "\n FROM #__survey_force_iscales";
	$database->setQuery( $query );
	$total = $database->loadResult();

	require_once( $GLOBALS['mosConfig_absolute_path'] . '/administrator/includes/pageNavigation.php' );
	$pageNav = new mosPageNav( $total, $limitstart, ($limit==999999?0:$limit) );

	// get the subset (based on limits) of required records
	$query = "SELECT * "
	. "\n FROM #__survey_force_iscales"
	. "\n ORDER BY iscale_name"
	. "\n LIMIT $pageNav->limitstart, $pageNav->limit"
	;
	$database->setQuery( $query );
	$rows = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
	$i = 0;
	while ($i < count($rows)) {
		$query = "SELECT isf_name FROM #__survey_force_iscales_fields WHERE iscale_id = '".$rows[$i]->id."' ORDER BY ordering";
		$database->SetQuery($query);
		$isf_ar = $database->LoadResultArray();
		$rows[$i]->iscale_descr = implode(', '."\n",$isf_ar);
		$i ++;
	}
	survey_force_adm_html::SF_viewIScales( $rows, $pageNav, $option);
}
function SF_editIScale( $id, $option ) {
	global $database, $task, $my, $front_end;
	if ($task == 'add_iscale_from_quest') {
		setSessionValue('is_return_sf', 1);
		setSessionValue('sf_qtext_sf', $_REQUEST['sf_qtext']);
		setSessionValue('sf_survey_sf', mosGetParam($_REQUEST, 'sf_survey', ''));
		setSessionValue('sf_impscale_sf', mosGetParam($_REQUEST, 'sf_impscale', ''));
		setSessionValue('ordering_sf', mosGetParam($_REQUEST, 'ordering', ''));
		setSessionValue('sf_compulsory_sf', mosGetParam($_REQUEST, 'sf_compulsory', ''));
		setSessionValue('insert_pb_sf', mosGetParam($_REQUEST, 'insert_pb', ''));
		setSessionValue('published', mosGetParam($_REQUEST, 'published', ''));
		setSessionValue('is_likert_predefined_sf', mosGetParam($_REQUEST, 'is_likert_predefined', ''));	
	
		setSessionValue('sf_hid_scale_sf', mosGetParam($_REQUEST, 'sf_hid_scale', array()));
		setSessionValue('sf_hid_scale_id_sf', mosGetParam($_REQUEST, 'sf_hid_scale_id', array()));
		
		setSessionValue('sf_hid_rule_sf', mosGetParam($_REQUEST, 'sf_hid_rule', array()));
		setSessionValue('sf_hid_rule_quest_sf', mosGetParam($_REQUEST, 'sf_hid_rule_quest', array()));		
		setSessionValue('sf_hid_rule_alt_sf', mosGetParam($_REQUEST, 'sf_hid_rule_alt', array()));
		setSessionValue('priority_sf', mosGetParam($_REQUEST, 'priority', array()));
		
		setSessionValue('sf_hid_fields_sf', mosGetParam($_REQUEST, 'sf_hid_fields', array()));
		setSessionValue('sf_hid_field_ids_sf', mosGetParam($_REQUEST, 'sf_hid_field_ids', array()));
		
		setSessionValue('sf_fields_sf', mosGetParam($_REQUEST, 'sf_fields', array()));
		setSessionValue('sf_field_ids_sf', mosGetParam($_REQUEST, 'sf_field_ids', array()));
		setSessionValue('sf_alt_fields_sf', mosGetParam($_REQUEST, 'sf_alt_fields', array()));
		setSessionValue('sf_alt_field_ids_sf', mosGetParam($_REQUEST, 'sf_alt_field_ids', array()));
		
		setSessionValue('other_option_cb_sf', mosGetParam($_REQUEST, 'other_option_cb', 0));
		setSessionValue('other_option_sf', (isset($_REQUEST['other_option'])?$_REQUEST['other_option']:''));
		setSessionValue('other_op_id_sf', mosGetParam($_REQUEST, 'other_op_id', 0));		
		
		setSessionValue('sf_hid_rank_sf', mosGetParam($_REQUEST, 'sf_hid_rank', array()));
		setSessionValue('sf_hid_rank_id_sf', mosGetParam($_REQUEST, 'sf_hid_rank_id', array()));
		
	}
	$row = new mos_Survey_Force_IScale( $database );
	// load the row from the db table
	$row->load( $id );

	$lists = array();

	$lists['sf_fields'] = array();
	$query = "SELECT * FROM #__survey_force_iscales_fields WHERE iscale_id = '".$id."' ORDER BY ordering";
	$database->SetQuery($query);
	$lists['sf_fields'] = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
	
	if (!$front_end)
		survey_force_adm_html::SF_editIScale( $row, $lists, $option );
	else
		survey_force_front_html::SF_editIScale( $row, $lists, $option );
}

function SF_saveIScale( $option ) {
	global $database, $mainframe, $front_end;
	$row = new mos_Survey_Force_IScale( $database );
	if (!$row->bind( $_POST )) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	// pre-save checks
	if (!$row->check()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	// save the changes
	if (!$row->store()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	$row->checkin();
	$is_id = $row->id;

	$query = "DELETE FROM #__survey_force_iscales_fields WHERE iscale_id = '".$is_id."'";
	$database->setQuery( $query );
	$database->query();
	$f_order = 0;
	foreach ($_POST['sf_hid_fields'] as $f_row) {
		$new_field = new mos_Survey_Force_IScaleField( $database );
		$new_field->iscale_id = $is_id;
		$new_field->isf_name = SF_processGetField($f_row);
		$new_field->ordering = $f_order;
		if (!$new_field->check()) { echo "<script> alert('".$new_field->getError()."'); window.history.go(-1); </script>\n"; exit(); }
		if (!$new_field->store()) { echo "<script> alert('".$new_field->getError()."'); window.history.go(-1); </script>\n"; exit(); }
		$f_order ++;
	}
	global $task;
	if (!$front_end) {
		if ($task == 'apply_iscale') {
			$msg = JText::_('COM_SF_THE_IMPORTANT_SCALE_DETAILS');
			mosRedirect( "index2.php?option=$option&task=editA_iscale&id=". $row->id, $msg );
		} elseif ($task == 'save_iscale_A') {
			$quest_redir = $mainframe->getUserState('quest_redir');
			$task_redir = $mainframe->getUserState('task_redir');
			mosRedirect( "index2.php?option=$option&task=".$task_redir."&id=". $quest_redir);
		} else {
			mosRedirect( "index2.php?option=$option&task=iscales" );
		}
	}
	else {
		global $Itemid, $Itemid_s, $SF_SESSION;
		if ($task == 'save_iscale_A') {
			$quest_redir = intval( $SF_SESSION->get('quest_redir') );
			$task_redir = strval( $SF_SESSION->get('task_redir') );
			mosRedirect( SFRoute("index.php?option=$option{$Itemid_s}&task=".$task_redir."&id=". $quest_redir) );
		} else {
			mosRedirect( SFRoute("index.php?option=$option{$Itemid_s}&task=questions") );
		}
	}
}

function SF_removeIscale( &$cid, $option ) {
	global $database;
	if (count( $cid )) {
		$cids = implode( ',', $cid );
		$query = "DELETE FROM #__survey_force_iscales"
		. "\n WHERE id IN ( $cids )"
		;
		$database->setQuery( $query );
		if (!$database->query()) {
			echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		} else {
			$query = "DELETE FROM #__survey_force_iscales_fields WHERE iscale_id IN ( $cids )";
			$database->setQuery( $query );
			$database->query();
		}
	}
	mosRedirect( "index2.php?option=$option&task=iscales" );
}

function SF_cancelIScale($option) {
	global $database, $mainframe, $front_end;
	$row = new mos_Survey_Force_IScale( $database );
	$row->bind( $_POST );
	$row->checkin();
	global $task;
	if ( !$front_end ) {
		if ($task == 'cancel_iscale_A') {
			$quest_redir = $mainframe->getUserState('quest_redir');
			$task_redir = $mainframe->getUserState('task_redir');
			mosRedirect( "index2.php?option=$option&task=".$task_redir."&id=". $quest_redir);
		} else {
			mosRedirect("index2.php?option=$option&task=iscales");
		}
	}
	else {
		global $Itemid, $Itemid_s, $SF_SESSION;
		if ($task == 'cancel_iscale_A') {
			$quest_redir = intval( $SF_SESSION->get('quest_redir') );
			$task_redir = strval( $SF_SESSION->get('task_redir') );
			mosRedirect( SFRoute("index.php?option=$option{$Itemid_s}&task=".$task_redir."&id=". $quest_redir) );
		} else {
			mosRedirect( SFRoute("index.php?option=$option{$Itemid_s}&task=questions") );
		}
	}
}

			#######################################
			###	--- ---    CSV REPORT   --- --- ###

function SF_ViewIRepSurv( $id, $option ) {
	set_time_limit( 3600 );
	global $database, $front_end, $mosConfig_offset;
	
	$max_quest_length = 150;
	
	$show_iscale = intval(mosGetParam($_REQUEST, 'inc_imp', 0));
	$add_info = intval(mosGetParam($_REQUEST, 'add_info', 0));
	$query = "SELECT * FROM #__survey_force_survs WHERE id = '".$id."'";
	$database->SetQuery( $query );
	$database->LoadObject($survey_data);
	if (isset($survey_data->id) && $survey_data->id) {
		$query = "SELECT sf_ust.*, sf_s.sf_name as survey_name, u.username reg_username, u.name reg_name, u.email reg_email,"
		. "\n sf_u.name as inv_name, sf_u.lastname as inv_lastname, sf_u.email as inv_email"
		. "\n FROM (#__survey_force_user_starts as sf_ust, #__survey_force_survs as sf_s)"
		. "\n LEFT JOIN #__users as u ON u.id = sf_ust.user_id and sf_ust.usertype=1"
		. "\n LEFT JOIN #__survey_force_users as sf_u ON sf_u.id = sf_ust.user_id and sf_ust.usertype=2"
		. "\n WHERE sf_ust.survey_id = sf_s.id"
		. "\n and sf_s.id = $id"
		. "\n ORDER BY sf_ust.sf_time DESC, sf_ust.id DESC";
		$database->SetQuery($query);
		$rows = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());



		$query = "SELECT a.*, b.iscale_name FROM #__survey_force_quests as a LEFT JOIN #__survey_force_iscales as b ON b.id=a.sf_impscale WHERE a.published = 1 AND a.sf_survey = $id AND a.sf_qtype IN (1,2,3,4,5,6,9) ORDER BY a.ordering, a.sf_qtext";
		$database->SetQuery($query);
		$sf_quests = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());

		$iii = 0;
		
		$t_fields = array();
		foreach($sf_quests as $key => $sfq) {
			switch ($sfq->sf_qtype) {
				case 1:
					$query = "SELECT id, ftext FROM #__survey_force_fields WHERE quest_id = {$sfq->id} AND is_main = 1 ORDER BY ordering";
					$database->SetQuery( $query );
					$t_fields[$sfq->id.'1'] = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
					
					$tmp_str = '';
					if(count($t_fields[$sfq->id.'1'])) 
					foreach($t_fields[$sfq->id.'1'] as $field) {						
						$tmp_str .= ','.str_replace(',','',SF_processCSVField(str_replace("\r\n","",$sfq->sf_qtext.' - '.$field->ftext)));
					}					
					$sf_quests[$key]->sf_qtext2 = $tmp_str;
				break;
				case 5:
				case 6:
				case 9:
					$query = "SELECT id, ftext FROM #__survey_force_fields AS a WHERE quest_id = {$sfq->id} AND is_main = 1 ORDER BY ordering";
					$database->SetQuery( $query );
					$t_fields[$sfq->id.'569'] = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
				break;	
			}
		}
		
		@ob_end_clean();
		header("Content-type: application/csv");		
		header("Content-Disposition: inline; filename=report.csv");
		
		if ($add_info) {
			echo '"","","","",';
		}
		
		echo '"",""';	
			
		
		$nnn = count($rows);
		
		while ($iii < $nnn) {
			
			$rows[$iii]->questions = array();
			
			foreach($sf_quests as $key => $sfq) {
				$sf_quests[$key]->sf_qtext = trim(strip_tags($sf_quests[$key]->sf_qtext,'<a><b><i><u>'));
				$one_answer = new stdClass();
				$one_answer->quest_id = $sfq->id;
				$user_answer = '';
				if ($sfq->sf_impscale) {
					$query = "SELECT b.isf_name FROM #__survey_force_iscales_fields as b, #__survey_force_user_answers_imp as a"
					. "\n WHERE a.quest_id = '".$sfq->id."' AND a.survey_id = '".$sfq->sf_survey."' AND a.start_id = '".$rows[$iii]->id."' AND a.iscalefield_id = b.id "
					. "\n AND b.iscale_id = '".$sfq->sf_impscale."'";
					$database->SetQuery( $query );
					$user_answer = $database->LoadResult();
				}
				$one_answer->sf_impscale = $sfq->sf_impscale;
				$one_answer->iscale_answer = $user_answer;
				$user_answer = '';
				switch ($sfq->sf_qtype) {
					case 1:
						$fields = $t_fields[$sfq->id.'1'];


						$tmp_str = '';
						foreach($fields as $field){
							$query = "SELECT b.stext as user_answer FROM #__survey_force_user_answers as a, #__survey_force_scales as b"
									. "\n WHERE a.quest_id = '".$sfq->id."' and b.quest_id = a.quest_id and a.answer = {$field->id} and b.id = a.ans_field  and a.survey_id = '".$sfq->sf_survey."' and a.start_id = '".$rows[$iii]->id."' ORDER BY b.ordering";
							$database->SetQuery( $query );
							$user_answer .= SF_processCSVField($database->LoadResult()).',';
							
							$tmp_str .= ','.str_replace(',','',SF_processCSVField(str_replace("\r\n","",$sfq->sf_qtext.' - '.$field->ftext)));
						}
						$sf_quests[$key]->sf_qtext2 = $tmp_str;
						break;
					case 5:
					case 6:
					case 9:						
						$fields = $t_fields[$sfq->id.'569'];
						$user_answer = '"';
						$tmp_str = '';
						foreach($fields as $field){
							$query = "SELECT b.ftext as user_answer, c.ans_txt AS user_text  FROM (#__survey_force_user_answers as a, #__survey_force_fields as b) LEFT JOIN `#__survey_force_user_ans_txt` AS c ON a.next_quest_id = c.id "
									. "\n WHERE a.quest_id = '".$sfq->id."' and b.quest_id = a.quest_id and a.answer = {$field->id} and b.id = a.ans_field  and a.survey_id = '".$sfq->sf_survey."' and a.start_id = '".$rows[$iii]->id."'";
							$database->SetQuery( $query );
							
							$user_answer_ = $database->LoadObjectList();
							if (isset($user_answer_[0])) {
								 
								$user_answer .= $user_answer_[0]->user_answer.($user_answer_[0]->user_text? ' ('.str_replace(',','',SF_processCSVField_noquot(str_replace("\r\n","",$user_answer_[0]->user_text))).')':'').'","';
								
							}
											
														
							$tmp_str .= ','.str_replace(',','',SF_processCSVField(str_replace("\r\n","",$sfq->sf_qtext.' - '.$field->ftext)));					
						}
						$user_answer .= '",';
						$sf_quests[$key]->sf_qtext2 = $tmp_str;
						break;
					case 2:
						$query = "SELECT b.ftext as user_answer, c.ans_txt AS user_text  FROM (#__survey_force_user_answers as a, #__survey_force_fields as b ) LEFT JOIN `#__survey_force_user_ans_txt` AS c ON a.ans_field = c.id "
						. "\n WHERE a.quest_id = '".$sfq->id."' and b.quest_id = a.quest_id and b.id = a.answer and a.survey_id = '".$sfq->sf_survey."' and a.start_id = '".$rows[$iii]->id."'";
						$database->SetQuery( $query );
						$user_answer_ = $database->LoadObjectList();
						$user_answer = '';
						if (isset($user_answer_[0])) {
							$user_answer = $user_answer_[0]->user_answer.($user_answer_[0]->user_text? ' ('.str_replace(',','',SF_processCSVField_noquot(str_replace("\r\n","",$user_answer_[0]->user_text))).')':'');
						}
					break;

					case 3:
						$query = "SELECT b.ftext AS user_answer, c.ans_txt AS user_text FROM (#__survey_force_user_answers as a, #__survey_force_fields as b) LEFT JOIN `#__survey_force_user_ans_txt` AS c ON a.ans_field = c.id "
						. "\n WHERE a.quest_id = '".$sfq->id."' and b.quest_id = a.quest_id and b.id = a.answer and a.survey_id = '".$sfq->sf_survey."' and a.start_id = '".$rows[$iii]->id."'"
						. "\n ORDER BY b.ordering";
						$database->SetQuery( $query );
						$ans_inf_data = $database->LoadObjectList();
						$user_answer = '';
						if (count($ans_inf_data)) {
							foreach($ans_inf_data as $ans_inf_data_) {
								$user_answer .= $ans_inf_data_->user_answer.($ans_inf_data_->user_text? ' ('.str_replace(',','',SF_processCSVField_noquot(str_replace("\r\n","",$ans_inf_data_->user_text))).')':'').';';
							}							
						}
					break;
					case 4: 
						$n = substr_count($sfq->sf_qtext, '{x}')+substr_count($sfq->sf_qtext, '{y}');
						if ($n > 0) {
							$tmp = JText::_('COM_SF_1ST_ANSWER');	
							$tmp_str = '';						
							for($i = 0; $i < $n; $i++){
								if ($i == 1) $tmp = JText::_('COM_SF_SECOND_ANSWER');
								elseif($i == 2)	$tmp = JText::_('COM_SF_THIRD_ANSWER');
								elseif ($i > 2) $tmp = ($i+1).JText::_('COM_SF_TH_ANSWER');
								$query = "SELECT b.ans_txt as user_answer FROM #__survey_force_user_answers as a, #__survey_force_user_ans_txt as b "
										." WHERE a.ans_field = '".($i+1)."' AND a.quest_id = '".$sfq->id."' and a.survey_id = '".$sfq->sf_survey."' and a.start_id = '".$rows[$iii]->id."' and a.answer = b.id";
								$database->SetQuery( $query );
								$user_answer .= '"'.SF_processCSVField_noquot($database->LoadResult()).'",';
							
								$tmp_str .= ',"'.substr(str_replace(',', '', SF_processCSVField_noquot(str_replace("\r\n", "", $sfq->sf_qtext))), 0, $max_quest_length).' - '.$tmp.'"';
							
							}
							$sf_quests[$key]->sf_qtext2 = $tmp_str;
						}
						else {
							$query = "SELECT b.ans_txt as user_answer FROM #__survey_force_user_answers as a, #__survey_force_user_ans_txt as b WHERE a.quest_id = '".$sfq->id."' and a.survey_id = '".$sfq->sf_survey."' and a.start_id = '".$rows[$iii]->id."' and a.answer = b.id";
							$database->SetQuery( $query );
							$user_answer = SF_processCSVField_noquot($database->LoadResult());
							if (!$user_answer) $user_answer = '';
						}
					break;
				}
				$one_answer->answer = $user_answer;
				$one_answer->sf_qtype = (isset($sf_quests[$key]->sf_qtext2) && $sfq->sf_qtype == 4? 41:$sfq->sf_qtype);
				
				$rows[$iii]->questions[] = $one_answer;				
				unset($one_answer);
				
			}
			$row = $rows[$iii];
			
			if ($iii == 0) {
				foreach ($sf_quests as $i=>$sfq) {
					if (!isset($sfq->sf_qtext2))
						echo ','.SF_processCSVField(substr(str_replace("\r\n","",str_replace(',','',$sfq->sf_qtext)),0, $max_quest_length));
					else 
						echo $sfq->sf_qtext2;
					if ($show_iscale && $sfq->sf_impscale) {
						echo ','.str_replace(',','',SF_processCSVField(substr(str_replace("\r\n","",$sfq->iscale_name),0, $max_quest_length)));
					}
				}
				echo "\n";	
			}
			
			if ($add_info) {
				echo '"'.$row->id.'","'.mosFormatDate( $row->sf_time, _CURRENT_SERVER_TIME_FORMAT, $mosConfig_offset ).'","'.($row->is_complete == 0?'Incomplete': 'Complete').'",'.SF_processCSVField(str_replace("\r\n","",$row->survey_name)).',"';
				switch($row->usertype) {
					case '0': echo JText::_('COM_SF_GUEST').'",'; break;
					case '1': echo JText::_('COM_SF_REGISTERED_USER').'",'; break;
					case '2': echo JText::_('COM_SF_INVITED_USER').'",'; break;
					default: echo '",'; break;
				}
			}
			else {
				switch($row->usertype) {
					case '0': echo '"'.JText::_('COM_SF_GUEST').'",'; break;
					case '1': echo '"'.JText::_('COM_SF_REGISTERED_USER').'",'; break;
					case '2': echo '"'.JText::_('COM_SF_INVITED_USER').'",'; break;
					default: echo '"",'; break;
				}
			}
			switch($row->usertype) {
				case '0': echo '"'.JText::_('COM_SF_ANONYMOUS').'",'; break;
				case '1': echo '"'.$row->reg_username."; ".$row->reg_name." (".$row->reg_email.')",'; break;
				case '2': echo '"'.$row->inv_name." ".$row->inv_lastname." (".$row->inv_email.')",'; break;
				default: echo '"",'; break;
			}
			foreach ($row->questions as $rq) {
				if ($rq->sf_qtype != 1 && $rq->sf_qtype != 5 && $rq->sf_qtype != 6 && $rq->sf_qtype !=9 && $rq->sf_qtype != 41)
					echo SF_processCSVField($rq->answer).",";
				else
					echo $rq->answer;
				if ($show_iscale && $rq->sf_impscale) {
					echo SF_processCSVField($rq->iscale_answer).",";
				}
			}
			echo "\n";
			
			unset($row);
			unset($rows[$iii]);
			$iii++;		
		}
		
		unset($t_fields);
			
		die;
	}
	if (!$front_end) {
		mosRedirect("index2.php?option=$option&task=adv_report");
	}
	else {
		global $Itemid, $Itemid_s;
		mosRedirect(SFRoute("index.php?option=$option{$Itemid_s}&task=i_report"));
	}
}

		###############################################
		##			CROSS REPORT 					 ##
		##											 ##
		###############################################
function SF_showCrossReport($option) {
	global $database, $front_end, $my;

	$survid = intval( mosGetParam( $_REQUEST, 'survid', 0) );
	
	$lists = array();
	
	$query = "SELECT id AS value, sf_name AS text FROM #__survey_force_survs "
			.($front_end && $my->usertype != 'Super Administrator'?" WHERE sf_author = '{$my->id}' ": "");
	$database->SetQuery( $query );
	$surveys = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
	if (count($surveys) < 1) {
		$lists['surveys'] = JText::_('COM_SF_NO_SURVEYS');
		$lists['mquest_id'] = '';
		if (!$front_end) {
		survey_force_adm_html::SF_showCrossReport( $lists, $option );
		}
		else {
			survey_force_front_html::SF_showCrossReport( $lists, $option );	
		}
		return;
	}
	$survid = ($survid > 0? $survid: $surveys[0]->value); 
	$surveys = mosHTML::selectList( $surveys, 'survid', 'class="text_area" size="1" onchange="document.adminForm.submit();"', 'value', 'text', $survid); 
	$lists['surveys'] = $surveys;
	
	$query = "SELECT id AS value, SUBSTRING(sf_qtext,1,100) AS text, sf_qtype FROM #__survey_force_quests WHERE published = 1 AND sf_qtype NOT IN (4, 7, 8) AND sf_survey = $survid ORDER BY ordering";
	$database->SetQuery( $query );
	$questions_tmp = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
	$questions = array();
	if (count($questions_tmp)>0) {
		foreach($questions_tmp as $question) {
			if ($question->sf_qtype != 2 && $question->sf_qtype != 3) {
				$query = "SELECT id, ftext FROM #__survey_force_fields WHERE quest_id = {$question->value} AND is_main = 1 ORDER BY ordering";
				$database->SetQuery( $query );
				$fields_tmp = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
				foreach($fields_tmp as $field) {
					$tmp = new stdClass;
					$tmp->value = $question->value.'_'.$field->id;
					$tmp->text = $question->text.'  - '.$field->ftext;
					$questions[] = $tmp;
				}
			}
			else {
				$tmp = new stdClass;
				$tmp->value = $question->value;
				$tmp->text = $question->text;
				$questions[] = $tmp;
			}
		}
		$lists['mquest_id'] = mosHTML::selectList( $questions, 'mquest_id', 'class="text_area" size="4" ', 'value', 'text', $questions[0]->value);
	}
	else
		$lists['mquest_id'] = '';	
	
	$query = "SELECT id AS value, SUBSTRING(sf_qtext,1,100) AS text, sf_qtype FROM #__survey_force_quests WHERE published = 1 AND sf_qtype NOT IN (7, 8) AND sf_survey = $survid ORDER BY ordering";
	$database->SetQuery( $query );
	$questions_tmp = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
	$questions = array();
	if (count($questions_tmp)>0) {
		foreach($questions_tmp as $question) {
			if ($question->sf_qtype != 2 && $question->sf_qtype != 3 && $question->sf_qtype != 4) {
				$query = "SELECT id, ftext FROM #__survey_force_fields WHERE quest_id = {$question->value} AND is_main = 1 ORDER BY ordering";
				$database->SetQuery( $query );
				$fields_tmp = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
				foreach($fields_tmp as $field) {
					$tmp = new stdClass;
					$tmp->value = $question->value.'_'.$field->id;
					$tmp->text = $question->text.'  - '.$field->ftext;
					$questions[] = $tmp;
				}
			}
			else {
				$tmp = new stdClass;
				$tmp->value = $question->value;
				$tmp->text = $question->text;
				$questions[] = $tmp;
			}
		}
	}	
	
	$questions2 = array();
	$questions2[] = mosHTML::makeOption( '0', JText::_('COM_SF_ALL_QUESTIONS') );
	$questions = @array_merge($questions2, $questions);
	$lists['cquest_id'] = mosHTML::selectList( $questions, 'cquest_id[]', 'class="text_area" size="4" multiple="multiple" ', 'value', 'text', 0);
	if (!$front_end) {
		survey_force_adm_html::SF_showCrossReport( $lists, $option );
	}
	else {
		survey_force_front_html::SF_showCrossReport( $lists, $option );	
	}
}

function SF_getCrossReport( $option ) {
	global $front_end, $database, $mosConfig_absolute_path, $mosConfig_offset,  $mosConfig_sitename , $mosConfig_live_site ;
	
	$survid = intval( mosGetParam( $_REQUEST, 'survid', 0) );
	$mquest_id = mosGetParam( $_REQUEST, 'mquest_id', '');
	$cquest_id = mosGetParam( $_REQUEST, 'cquest_id', array());
	$start_date = mosGetParam( $_REQUEST, 'start_date', '');
	$end_date = mosGetParam( $_REQUEST, 'end_date', '');

$no_answer_str =JText::_('COM_SF_NO_ANSWER');
	if ( $front_end ) {
	global $sf_lang;
		$no_answer_str = $sf_lang['SURVEY_NO_ANSWER'];

	}	
	$is_complete = intval( mosGetParam( $_REQUEST, 'is_complete', 0) );
	$is_notcomplete = intval( mosGetParam( $_REQUEST, 'is_notcomplete', 0) );
	$type = strval( mosGetParam( $_REQUEST, 'rep_type', 'csv') );
	if ($survid && $mquest_id != '' && is_array($cquest_id) && (count($cquest_id) > 0 )&& ($is_complete || $is_notcomplete)) {
		$date_where = '';
		if ($start_date != '' && $end_date != '') {
			$date_where = " AND sf_time BETWEEN '$start_date' AND '$end_date' ";
		}
		elseif ($start_date != '' && $end_date == '') {
			$date_where = " AND sf_time > '$start_date' ";
		}
		elseif ($start_date == '' && $end_date != '') {
			$date_where = " AND sf_time < '$end_date' ";
		}
		$query = "SELECT id FROM #__survey_force_user_starts "
				."WHERE survey_id = $survid "
				.($is_complete? ($is_notcomplete? '': " AND is_complete = 1 ") : ($is_notcomplete? " AND is_complete = 0 ": ''))
				.$date_where;
		$database->SetQuery( $query );
		$start_ids = $database->loadResultArray();
		$m_id = intval($mquest_id);
		$f_id = 0;
		if (strpos($mquest_id, '_') > 0) {
			$f_id = intval( substr($mquest_id, strpos($mquest_id, '_') + 1) );
		}
		$query = "SELECT sf_qtype FROM #__survey_force_quests  WHERE published = 1 AND id = $m_id";
		$database->SetQuery( $query );
		$qtype = $database->loadResult();
		
		if ($qtype == 1) {
			
			if ($f_id > 0) {
				$query = "SELECT stext FROM #__survey_force_scales  WHERE id = $f_id ORDER BY ordering";
				$database->SetQuery( $query );
				$f_text = $database->loadResult();
			}
			$query = "SELECT id FROM #__survey_force_scales WHERE quest_id = $m_id ORDER BY ordering";
			
		}
		elseif ($qtype == 2 || $qtype == 3){
			
			if ($f_id > 0) {
				$query = "SELECT ftext FROM #__survey_force_fields  WHERE id = $f_id";
				$database->SetQuery( $query );
				$f_text = $database->loadResult();
			}
			$query = "SELECT id FROM #__survey_force_fields WHERE quest_id = $m_id  ORDER BY ordering";
		}
		elseif ($qtype == 5 || $qtype == 6 || $qtype == 9){
			
			if ($f_id > 0) {
				$query = "SELECT ftext FROM #__survey_force_fields  WHERE id = $f_id";
				$database->SetQuery( $query );
				$f_text = $database->loadResult();
			}
			$query = "SELECT id FROM #__survey_force_fields WHERE quest_id = $m_id AND is_main = 0 ORDER BY ordering";
		}
		$database->SetQuery( $query );		
		$fields_ids = @array_merge($database->loadResultArray(), array(0=>0));
		$starts_by_fields = array();
		foreach($fields_ids as $fields_id) {
			$query = "SELECT start_id FROM #__survey_force_user_answers WHERE start_id IN (".implode(',', $start_ids).") "
					." AND quest_id = $m_id "
					.($qtype == 2 || $qtype == 3? " AND answer = $fields_id ": " AND answer = $f_id AND ans_field = $fields_id ");
			$database->SetQuery( $query );
			$starts_by_fields[$fields_id] = $database->loadResultArray();
			if (count($starts_by_fields[$fields_id]) < 1)
				$starts_by_fields[$fields_id] = array(0);
		}
		
		$all_quests = false;
		if (in_array('0', $cquest_id)){
			$all_quests = true;
			$query = "SELECT id, sf_qtype, sf_qtext FROM #__survey_force_quests  WHERE published = 1 AND sf_survey = $survid AND sf_qtype NOT IN (7,8) ORDER BY ordering, id";
			$database->SetQuery( $query );
			$questions2 = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
			$questions = array();
			foreach($questions2 as $key => $quest){
				$questions2[$key]->answer_count = 0;
				if ($quest->sf_qtype != 2 && $quest->sf_qtype != 3) 
					$query = "SELECT id FROM #__survey_force_fields WHERE quest_id = {$quest->id} AND is_main = 1 ORDER BY ordering";
				else
					$query = "SELECT id FROM #__survey_force_fields WHERE quest_id = {$quest->id} ORDER BY ordering";		
				$database->SetQuery( $query );
				$questions2[$key]->fields = @array_merge($database->loadResultArray(), array(0 => 0));
				
				if ($quest->sf_qtype != 1 && $quest->sf_qtype != 4) {
					$query = "SELECT id FROM #__survey_force_fields WHERE quest_id = {$quest->id} AND is_main = 0 ORDER BY ordering";
				}
				elseif ($quest->sf_qtype == 4) {
					$questions2[$key]->answer_count = substr_count($quest->sf_qtext, '{x}')+substr_count($quest->sf_qtext, '{y}');
					$questions[$quest->id]->answer_count = $questions2[$key]->answer_count;
					if ($questions2[$key]->answer_count > 0) {
						$n = $questions2[$key]->answer_count;
						$questions2[$key]->fields = array();
						$a_fields = array();
						for($i = 1; $i <= $n; $i++){	
							$query = "SELECT `answer` FROM `#__survey_force_user_answers` WHERE survey_id = {$survid} AND quest_id = {$quest->id} AND ans_field = {$i} AND start_id IN (".implode(',', $start_ids).")";
							$database->SetQuery( $query );
							$ans_ids = $database->loadResultArray();
							if (!is_array($ans_ids))
								$ans_ids = array();
														
							$query = "SELECT `ans_txt`, count( * ) num "
									." FROM `#__survey_force_user_ans_txt` "
									." WHERE id IN (".implode(',',$ans_ids).") GROUP BY `ans_txt` ORDER BY num DESC LIMIT 0 , 10";
							$database->SetQuery( $query );
							$ans_txts = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());							
							foreach($ans_txts as $ans_txt) {
								$questions2[$key]->fields = @array_merge($questions2[$key]->fields, array(0 => $ans_txt->ans_txt));
							}
							
							$a_fields[] = $i;
						}
						$questions2[$key]->a_fields = $a_fields;		
					}
					else {
						$query = "SELECT `answer` FROM `#__survey_force_user_answers` WHERE start_id IN (".implode(',', $start_ids).") AND survey_id = {$survid} AND quest_id = {$quest->id} ";
						$database->SetQuery( $query );
						$ans_ids = $database->loadResultArray();
						if (!is_array($ans_ids))
							$ans_ids = array();
						$query = "SELECT `ans_txt`, count( * ) num FROM `#__survey_force_user_ans_txt` WHERE id IN (".implode(',',$ans_ids).") GROUP BY `ans_txt` ORDER BY num DESC LIMIT 0 , 10";
					}
				}
				else{
					$query = "SELECT id FROM #__survey_force_scales WHERE quest_id = {$quest->id} ORDER BY ordering";
				}
				$database->SetQuery( $query );
				if ($quest->sf_qtype == 4 && $questions2[$key]->answer_count < 1) {
					$ans_txts = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
					$questions2[$key]->fields = array();
					foreach($ans_txts as $ans_txt) {
						$questions2[$key]->fields = @array_merge($questions2[$key]->fields, array(0 => $ans_txt->ans_txt));
					}
					
					$questions2[$key]->a_fields = null;
					$questions[$quest->id]->answer_count = $questions2[$key]->answer_count;
				}
				elseif ($quest->sf_qtype != 4)
					$questions2[$key]->a_fields = @array_merge($database->loadResultArray(), array(0 => 0));				
				
				$questions[$quest->id] = $quest;
				$questions[$quest->id]->a_fields = $questions2[$key]->a_fields;
				$questions[$quest->id]->fields = $questions2[$key]->fields;
				$questions[$quest->id]->answer_count = $questions2[$key]->answer_count;
			}
		}
		else {
			$questions = array();
			foreach($cquest_id as $quest) {
				$tmp = new stdClass;
				$tmp->answer_count = 0;
				$tmp->id = intval($quest);
				$query = "SELECT sf_qtype, sf_qtext FROM #__survey_force_quests  WHERE published = 1 AND id = {$tmp->id}";
				$database->SetQuery( $query );
				$n = null;
				$database->loadObject($n);
				$tmp->sf_qtype = $n->sf_qtype;
				$tmp->sf_qtext = $n->sf_qtext;
				if ($tmp->sf_qtype != 1 && $tmp->sf_qtype != 4) {
					$query = "SELECT id FROM #__survey_force_fields WHERE quest_id = {$tmp->id} AND is_main = 0 ORDER BY ordering";
				}
				elseif ($tmp->sf_qtype == 4) {
					$tmp->answer_count = substr_count($tmp->sf_qtext, '{x}')+substr_count($tmp->sf_qtext, '{y}');
					if ($tmp->answer_count > 0) {
						$n = $tmp->answer_count;
						$tmp->fields = array();
						$a_fields = array();
						for($i = 1; $i <= $n; $i++){	
							$query = "SELECT `answer` FROM `#__survey_force_user_answers` WHERE survey_id = {$survid} AND quest_id = {$tmp->id} AND ans_field = {$i} AND start_id IN (".implode(',', $start_ids).")";
							$database->SetQuery( $query );
							$ans_ids = $database->loadResultArray();
							if (!is_array($ans_ids))
								$ans_ids = array();
														
							$query = "SELECT `ans_txt`, count( * ) num "
									." FROM `#__survey_force_user_ans_txt` "
									." WHERE id IN (".implode(',',$ans_ids).") GROUP BY `ans_txt` ORDER BY num DESC LIMIT 0 , 10";
							$database->SetQuery( $query );
							$ans_txts = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());							
							foreach($ans_txts as $ans_txt) {
								$tmp->fields = @array_merge($tmp->fields, array(0 => $ans_txt->ans_txt));
							}
							
							$a_fields[] = $i;
						}
						$tmp->a_fields = $a_fields;		
					}
					else {
						$query = "SELECT `answer` FROM `#__survey_force_user_answers` WHERE quest_id = {$tmp->id} AND start_id IN (".implode(',', $start_ids).")";
						$database->SetQuery( $query );
						$ans_ids = $database->loadResultArray();
						if (!is_array($ans_ids))
							$ans_ids = array();
						$query = "SELECT `ans_txt`, count( * ) num FROM `#__survey_force_user_ans_txt` WHERE id IN (".implode(',',$ans_ids).") GROUP BY `ans_txt` ORDER BY num DESC LIMIT 0 , 10";
					}
				}
				else {
					$query = "SELECT id FROM #__survey_force_scales WHERE quest_id = {$tmp->id} ORDER BY ordering";
				}
				$database->SetQuery( $query );
				if ($tmp->sf_qtype == 4 && $tmp->answer_count < 1) {
					$ans_txts = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
					$tmp->fields = array();
					foreach($ans_txts as $ans_txt) {
						$tmp->fields = @array_merge($tmp->fields, array(0 => $ans_txt->ans_txt));
					}
					
					$tmp->a_fields = null;
				}
				elseif ($tmp->sf_qtype != 4) {
					$tmp->a_fields = @array_merge($database->loadResultArray(), array(0 => 0));
					if (strpos($quest, '_') > 0) {
						$tmp->fields = array(0 => 0, 1 => intval(substr($quest, strpos($quest, '_') + 1)) );
					}
					else {
						$query = "SELECT id FROM #__survey_force_fields WHERE quest_id = {$tmp->id} AND is_main = 1";
						$database->SetQuery( $query );
						$tmp->fields = @array_merge($database->loadResultArray(), array(0 => 0));
					}
					foreach($questions as $key => $question){
						if ($question->id == $tmp->id) {
							$questions[$key]->fields = @array_merge($tmp->fields, $questions[$key]->fields);
							$tmp = null;
							break;
						}
					}
				}
				
				if ($tmp != null)
					$questions[$tmp->id] = $tmp;
			}
		}
		$result_data = array();
		foreach($questions as $question) {
			$tmp = array();			
			foreach($fields_ids as $fields_id) {
				if ( $question->sf_qtype == 2 || $question->sf_qtype == 3 ) {
					$query = "SELECT answer FROM #__survey_force_user_answers "
							." WHERE start_id IN (".implode(',', $starts_by_fields[$fields_id]).") "
						 	." AND quest_id = {$question->id} "
						 	." AND answer IN (".implode(',', $question->fields).") "
							;
				}
				elseif ($question->sf_qtype == 4) {
					if ($question->answer_count > 0) {
						$query = "SELECT a.ans_txt, b.ans_field FROM #__survey_force_user_ans_txt AS a LEFT JOIN #__survey_force_user_answers AS b ON b.answer = a.id AND b.quest_id = {$question->id}"
								." WHERE a.start_id IN (".implode(',', $starts_by_fields[$fields_id]).") "
								." AND a.ans_txt IN ('".implode("', '", $question->fields)."') ORDER BY b.ans_field";
					}
					else {
						$query = "SELECT ans_txt FROM #__survey_force_user_ans_txt "
								." WHERE start_id IN (".implode(',', $starts_by_fields[$fields_id]).") "
								." AND ans_txt IN ('".implode("', '", $question->fields)."') ";
					}
				}
				else {
					$query = "SELECT answer, ans_field FROM #__survey_force_user_answers "
							." WHERE start_id IN (".implode(',', $starts_by_fields[$fields_id]).") "
						 	." AND quest_id = {$question->id} "							
						 	.( $question->sf_qtype == 1?" AND ans_field IN (".implode(',', $question->a_fields).") ":" AND answer IN (".implode(',', $question->fields).") ")
							;
				}
				$database->SetQuery( $query );
				if ( $question->sf_qtype == 2 || $question->sf_qtype == 3 ) {
					$t = array_count_values($database->loadResultArray());
					$tmp[$fields_id] = array();
					foreach($question->fields as $f_id ){
						$tmp[$fields_id][$f_id] = isset($t[$f_id])? $t[$f_id]: 0;
					}
				}
				elseif ($question->sf_qtype == 4) {
					if ($question->answer_count > 0) {
						$tmp_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
						$t_fields = array();
						foreach($tmp_data as $data){
							if (!isset($t_fields[$data->ans_field]))
								$t_fields[$data->ans_field] = array();						
							$t_fields[$data->ans_field][] = $data->ans_txt;
						}
						
						foreach($t_fields as $key => $data){
							$t_fields[$key] = array_count_values($data);
						}													

						$tmp[$fields_id] = $t_fields;
					}
					else {
						$t = array_count_values($database->loadResultArray());
						$tmp[$fields_id] = array();
						foreach($question->fields as $f_id ){
							$tmp[$fields_id][$f_id] = isset($t[$f_id])? $t[$f_id]: 0;
						}							
					}
				}
				else {
					$tmp_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
					$t_fields = array();
					foreach($tmp_data as $data){
						if (!isset($t_fields[$data->answer]))
							$t_fields[$data->answer] = array();						
						$t_fields[$data->answer][] = $data->ans_field;
					}

					foreach($t_fields as $key => $data){
						$t_fields[$key] = array_count_values($data);
					}
					
					foreach($t_fields as $key => $data){
						foreach($question->a_fields as $af_id){
							$t_fields[$key][$af_id] = isset($t_fields[$key][$af_id])? $t_fields[$key][$af_id]: 0;
						}
					}				
					
					$tmp[$fields_id] = $t_fields;
				}
			}
			if ( $question->sf_qtype == 2 || $question->sf_qtype == 3 || $question->sf_qtype == 4 ) {
				if ($question->sf_qtype == 4 && $question->answer_count > 0) {
					$t = array();
					foreach($fields_ids as $fields_id2) {
						foreach($tmp[$fields_id2] as $f_id=>$fields){						
							foreach($fields as $af_id=>$count){
								foreach($fields_ids as $fields_id) {
									$t[$f_id][$af_id][$fields_id] = isset($tmp[$fields_id][$f_id][$af_id])?$tmp[$fields_id][$f_id][$af_id]:'0';
								}
							}
						}
					}
				}
				else {
					$t = array();
					foreach($question->fields as $f_id ){
						$t[$f_id] = array();
						foreach($fields_ids as $fields_id) {
							$t[$f_id][$fields_id] = $tmp[$fields_id][$f_id];
						}
					}
				}
			}
			else {				
				$t = array();
				foreach($question->fields as $f_id){
					foreach($question->a_fields as $af_id){
						foreach($fields_ids as $fields_id) {
							$t[$f_id][$af_id][$fields_id] = isset($tmp[$fields_id][$f_id][$af_id])?$tmp[$fields_id][$f_id][$af_id]:'0';
						}
					}
				}
			}
				
			$result_data[$question->id] = $t;
		}		
		if ($type == 'pdf') {	
			chdir($mosConfig_absolute_path );			
			/*
			 * Create the pdf document
			 */
		
			require_once(_SURVEY_FORCE_ADMIN_HOME . '/tcpdf/sf_pdf.php');
			
			$pdf_doc = new sf_pdf();
		
			$pdf = &$pdf_doc->_engine;
		
			$pdf->AliasNbPages();
			$pdf->AddPage();
			
			$query = "SELECT  sf_qtext   FROM #__survey_force_quests  WHERE published = 1 AND id = {$m_id}";
			$database->SetQuery( $query );
			$main_quest = $pdf_doc->cleanText($database->loadResult().(isset($f_text)?" - $f_text\n":"\n"));
			$start_key = 'dummy';
			reset ($result_data);			

			for($ij = 0, $nm = count($result_data); $ij < $nm; $ij++ ) {
				if ($start_key == 'dummy')
					list($key, $data) = each($result_data);
				$cur_y = $pdf->GetY();
				
				
				if ($cur_y > 240)
					$pdf->AddPage();
				
				$pdf->SetX(60);
				$pdf->SetFontSize(8);
				$pdf->setStyle('b', true);
				$pdf->setStyle('i', true);
				$pdf->MultiCell(0, 0, $main_quest, 0, 'J', 0, 1, 0 ,0, true, 0);
				$pdf->Ln(0.5);
				
				$query = "SELECT  sf_qtext   FROM #__survey_force_quests  WHERE published = 1 AND id = {$key}";
				$database->SetQuery( $query );
				
				$quest = $pdf_doc->cleanText($database->loadResult())."\n";
				$pdf->setStyle('i', false);				
				$pdf->MultiCell(60, 0, $quest , 0, 'J', 0, 1, 0 ,0, true, 0);
				$pdf->Ln(0.5);
				
				$cur_y = $pdf->GetY(); 
				$col_width = 130/(count($fields_ids )+1);
				$pdf->SetFontSize(6);
				
				$pdf->SetX(60);
				$pdf->MultiCell($col_width, 0, "Total" , 0, 'C', 0, 1, 0 ,0, true, 0);				
				
				$i = 1;
				$line_y = 10000;
				foreach($fields_ids as $fields_id) {
					$query = "SELECT ftext FROM #__survey_force_fields WHERE id = {$fields_id}";
					if ($qtype == 1) {
						$query = "SELECT stext FROM #__survey_force_scales WHERE id = {$fields_id} ORDER BY ordering";
					}
					$database->SetQuery( $query );
					$tt = $pdf_doc->cleanText($database->loadResult());
					if ($fields_id == 0)
						$tt = $no_answer_str;
					$pdf->SetY($cur_y);
					$pdf->SetX(60+$col_width*$i);
					$pdf->MultiCell($col_width, 0, $tt , 0, 'C', 0, 1, 0 ,0, true, 0);
					$i++;
				}				
				
				$pdf->line( 60, $pdf->GetY()+2, 200, $pdf->GetY()+2);
				$pdf->Ln();
				$pdf->setStyle('b', false);
				if ( $questions[$key]->sf_qtype == 2 || $questions[$key]->sf_qtype == 3 ) {
					$total_row = array('total'=>0);
					$cur_y2 = $pdf->GetY();
					foreach($data as $k => $item) {				
						$query = "SELECT ftext FROM #__survey_force_fields WHERE id = {$k}";
						$database->SetQuery( $query );
						$tt = $pdf_doc->cleanText($database->loadResult());	
						if ($k == 0)
							$tt = $no_answer_str;	
						$total_col = 0;
												
						$pdf->SetY($cur_y2);
						$cur_y = $pdf->GetY();
						$pdf->SetY($cur_y);
						$pdf->SetX(17);
						$pdf->MultiCell(40, 0, $tt."\n" , 0, 'J', 0, 1, 0 ,0, true, 0);
						$pdf->Ln(0.5);						
						$cur_y2 = $pdf->GetY();
						
						$pdf->SetY($cur_y);
						$i = 1;
						foreach($fields_ids as $fields_id) {
							$pdf->SetY($cur_y);
							$pdf->SetX(60+$col_width*$i);
							$pdf->MultiCell($col_width, 0, "{$item[$fields_id]}" , 0, 'C', 0, 1, 0 ,0, true, 0);
					
							$total_col = $total_col + $item[$fields_id];
							if (!isset($total_row[$fields_id]))
								$total_row[$fields_id] = 0;
							$total_row[$fields_id] = $total_row[$fields_id] + $item[$fields_id];
							$i++;
						}
						$total_row['total'] = $total_row['total'] + $total_col;
						$pdf->SetY($cur_y);
						$pdf->SetX(60);
						$pdf->MultiCell($col_width, 0, "{$total_col}", 0, 'C', 0, 1, 0 ,0, true, 0);						
					}
					$pdf->line( 60, $pdf->GetY()+2, 200, $pdf->GetY()+2);
					$pdf->Ln();
					$cur_y = $pdf->GetY();
					$pdf->SetX(30);
					$pdf->MultiCell(20, 0, "Totals", 0, 'R', 0, 1, 0 ,0, true, 0);
					
					$pdf->SetY($cur_y);
					$pdf->SetX(60);
					$pdf->MultiCell($col_width, 0, "{$total_row['total']}", 0, 'C', 0, 1, 0 ,0, true, 0);
					$i = 1;
					foreach($fields_ids as $fields_id) {
						$pdf->SetY($cur_y);
						$pdf->SetX(60+$col_width*$i);
						$pdf->MultiCell($col_width, 0, "{$total_row[$fields_id]}" , 0, 'C', 0, 1, 0 ,0, true, 0);
						$i++;
					}
				}
				elseif ($questions[$key]->sf_qtype == 4) {
					if ($questions[$key]->answer_count > 0 ) {						
						foreach($data as $nn => $itemz) {	
							$tmp = '';
							if ($nn == 1) $tmp = JText::_('COM_SF_1ST_ANSWER');
							if ($nn == 2) $tmp = JText::_('COM_SF_SECOND_ANSWER');
							if ($nn == 3) $tmp = JText::_('COM_SF_THIRD_ANSWER');
							if ($nn > 3) $tmp = $nn.JText::_('COM_SF_TH_ANSWER');							
							
							$pdf_doc->cleanText($tmp);
							$pdf->SetX(18);
							$pdf->MultiCell(42, 0, $tmp."\n" , 0, 'J', 0, 1, 0 ,0, true, 0);
							
							$total_row = array('total'=>0);
							$cur_y2 = $pdf->GetY();
							foreach($itemz as $k=>$item) {
								$tt = $pdf_doc->cleanText($k);
							
								if ($k === 0)
									$tt = $no_answer_str;	
								$total_col = 0;
								
								if ($cur_y2 > 240) {
									$pdf->AddPage();
									$cur_y2 = $pdf->GetY();
								}
								
								$pdf->SetY($cur_y2);
								$cur_y = $pdf->GetY();
								$pdf->SetY($cur_y);
								$pdf->SetX(17);
								$pdf->MultiCell(40, 0, $tt."\n" , 0, 'J', 0, 1, 0 ,0, true, 0);
								$pdf->Ln(0.5);
								$cur_y2 = $pdf->GetY();

								$i = 1;
								foreach($fields_ids as $fields_id) {
									$pdf->SetY($cur_y);
									$pdf->SetX(60+$col_width*$i);
									$pdf->MultiCell($col_width, 0, "{$item[$fields_id]}" , 0, 'C', 0, 1, 0 ,0, true, 0);				
									$total_col = $total_col + $item[$fields_id];
									if (!isset($total_row[$fields_id]))
										$total_row[$fields_id] = 0;
									$total_row[$fields_id] = $total_row[$fields_id] + $item[$fields_id];
									$i++;
								}
								$total_row['total'] = $total_row['total'] + $total_col;
								$pdf->SetY($cur_y);
								$pdf->SetX(60);
								$pdf->MultiCell($col_width, 0, "{$total_col}", 0, 'C', 0, 1, 0 ,0, true, 0);								
							}
							$pdf->line( 60, $pdf->GetY()+2, 200, $pdf->GetY()+2);
							$pdf->Ln();
							$cur_y = $pdf->GetY();
							$pdf->SetX(30);
							$pdf->MultiCell(20, 0, "Totals", 0, 'R', 0, 1, 0 ,0, true, 0);
							
							$pdf->SetY($cur_y);
							$pdf->SetX(60);
							$pdf->MultiCell($col_width, 0, "{$total_row['total']}", 0, 'C', 0, 1, 0 ,0, true, 0);							
							
							$i = 1;
							foreach($fields_ids as $fields_id) {
								$pdf->SetY($cur_y);
								$pdf->SetX(60+$col_width*$i);
								$pdf->MultiCell($col_width, 0, "{$total_row[$fields_id]}" , 0, 'C', 0, 1, 0 ,0, true, 0);
								$i++;
							}
						}						
					}
					else {
						$total_row = array('total'=>0);
						$cur_y2 = $pdf->GetY();
						foreach($data as $k => $item) {				
							$tt = $pdf_doc->cleanText($k);
							
							if ($k === 0)
								$tt = $no_answer_str;	
							$total_col = 0;
							
							if ($cur_y2 > 240) {
								$pdf->AddPage();
								$cur_y2 = $pdf->GetY();
							}
							
							$pdf->SetY($cur_y2);
							$cur_y = $pdf->GetY();
							$pdf->SetX(17);
							$pdf->MultiCell(40, 0, $tt."\n" , 0, 'J', 0, 1, 0 ,0, true, 0);
							$pdf->Ln(0.5);
							$cur_y2 = $pdf->GetY();
							
							$i = 1;
							foreach($fields_ids as $fields_id) {
								$pdf->SetY($cur_y);
								$pdf->SetX(60+$col_width*$i);
								$pdf->MultiCell($col_width, 0, "{$item[$fields_id]}", 0, 'C', 0, 1, 0 ,0, true, 0);
						
								$total_col = $total_col + $item[$fields_id];
								if (!isset($total_row[$fields_id]))
									$total_row[$fields_id] = 0;
								$total_row[$fields_id] = $total_row[$fields_id] + $item[$fields_id];
								$i++;
							}
							$total_row['total'] = $total_row['total'] + $total_col;
							$pdf->SetY($cur_y);
							$pdf->SetX(60);
							$pdf->MultiCell($col_width, 0, "{$total_col}", 0, 'C', 0, 1, 0 ,0, true, 0);
												
						}
						$pdf->SetY($cur_y2);
						$pdf->line( 60, $pdf->GetY()+2, 200, $pdf->GetY()+2);
						$pdf->Ln();
						$cur_y = $pdf->GetY();
						
						$pdf->SetX(30);
						$pdf->MultiCell(20, 0, "Totals", 0, 'R', 0, 1, 0 ,0, true, 0);
						
						$pdf->SetY($cur_y);
						$pdf->SetX(60);
						$pdf->MultiCell($col_width, 0, "{$total_row['total']}", 0, 'C', 0, 1, 0 ,0, true, 0);				

						$i = 1;
						foreach($fields_ids as $fields_id) {
							$pdf->SetY($cur_y);
							$pdf->SetX(60+$col_width*$i);
							$pdf->MultiCell($col_width, 0, "{$total_row[$fields_id]}" , 0, 'C', 0, 1, 0 ,0, true, 0);							
							$i++;
						}					
					}
				}
				else {					
					foreach($data as $k => $item) {
						$total_row = array('total'=>0);
						$query = "SELECT ftext FROM #__survey_force_fields WHERE id = {$k}";
						$database->SetQuery( $query );
						$tt = $database->loadResult();
						if ($k == 0)
							continue;						
						
						if ($pdf->GetY() > 240) {
							$pdf->AddPage();							
						}
						
						$tt = $pdf_doc->cleanText($tt);
						$pdf->SetX(17);
						$pdf->MultiCell(42, 0, $tt."\n" , 0, 'J', 0, 1, 0 ,0, true, 0);
						$cur_y2 = $pdf->GetY();
						
						foreach($item as $kk => $it) {
							$query = "SELECT ftext FROM #__survey_force_fields WHERE id = {$kk}";
							if ($questions[$key]->sf_qtype == 1) {
								$query = "SELECT stext  FROM #__survey_force_scales WHERE id = {$kk} ORDER BY ordering";
							}						
							$database->SetQuery( $query );
							$tt = $pdf_doc->cleanText($database->loadResult());
							if ($kk == 0)
								$tt = ($questions[$key]->sf_qtype == 9?JText::_('COM_SF_NO_RANK'):$no_answer_str);	
							
							if ($cur_y2 > 240) {
								$pdf->AddPage();
								$cur_y2 = $pdf->GetY();
							}
							
							$pdf->SetY($cur_y2);
							$cur_y = $pdf->GetY();
							$pdf->SetY($cur_y);
							$pdf->SetX(20);
							$pdf->MultiCell(40, 0, $tt."\n" , 0, 'J', 0, 1, 0 ,0, true, 0);
							$pdf->Ln(0.5);
							$cur_y2 = $pdf->GetY();
														
							$total_col = 0;
							$i=1;
							foreach($fields_ids as $fields_id) {
								$pdf->SetY($cur_y);
								$pdf->SetX(60+$col_width*$i);
								$pdf->MultiCell($col_width, 0, "{$it[$fields_id]}" , 0, 'C', 0, 1, 0 ,0, true, 0);
	
								$total_col = $total_col + $it[$fields_id];
								if (!isset($total_row[$fields_id]))
									$total_row[$fields_id] = 0;
								$total_row[$fields_id] = $total_row[$fields_id] + $it[$fields_id];
								$i++;
							}
							$total_row['total'] = $total_row['total'] + $total_col;
							$pdf->SetY($cur_y);
							$pdf->SetX(60);
							$pdf->MultiCell($col_width, 0, "{$total_col}", 0, 'C', 0, 1, 0 ,0, true, 0);
						}
						$pdf->line( 60, $pdf->GetY()+2, 200, $pdf->GetY()+2);
						$pdf->Ln();
						$cur_y = $pdf->GetY();
						
						$pdf->SetX(30);
						$pdf->MultiCell(20, 0, "Totals", 0, 'R', 0, 1, 0 ,0, true, 0);
						
						$pdf->SetY($cur_y);
						$pdf->SetX(60);
						$pdf->MultiCell($col_width, 0, "{$total_row['total']}", 0, 'C', 0, 1, 0 ,0, true, 0);	
					
						$i = 1;
						foreach($fields_ids as $fields_id) {
							$pdf->SetY($cur_y);
							$pdf->SetX(60+$col_width*$i);
							$pdf->MultiCell($col_width, 0, "{$total_row[$fields_id]}", 0, 'C', 0, 1, 0 ,0, true, 0);
							$i++;
						}
					}
				}
				$pdf->line( 15, $pdf->GetY()+2, 200, $pdf->GetY()+2);
				$pdf->Ln();$pdf->Ln();	
			}
									
			$data = $pdf->Output('', 'S');	
			@ob_end_clean();
			header("Content-type: application/pdf");
			header("Content-Length: ".strlen(ltrim($data)));
			header("Content-Disposition: attachment; filename=report.pdf");
			echo $data;			
		} else {
			$csv_data = "";
			$z = ',';
			$query = "SELECT  sf_qtext   FROM #__survey_force_quests  WHERE published = 1 AND id = {$m_id}";
			$database->SetQuery( $query );
			$main_quest = SF_processPDFField($database->loadResult()).(isset($f_text)?" - $f_text":'');
			foreach($result_data  as $key => $data) {
				$csv_data .= $z.$main_quest."\n";
				$query = "SELECT  sf_qtext   FROM #__survey_force_quests  WHERE published = 1 AND id = {$key}";
				$database->SetQuery( $query );
				$csv_data .= SF_processPDFField($database->loadResult())."\n";
				$csv_data .="{$z}Total";
				foreach($fields_ids as $fields_id) {
					$query = "SELECT ftext FROM #__survey_force_fields WHERE id = {$fields_id}";
					if ($qtype == 1) {
						$query = "SELECT stext FROM #__survey_force_scales WHERE id = {$fields_id} ORDER BY ordering";
					}
					$database->SetQuery( $query );
					$tt = SF_processPDFField($database->loadResult());
					if ($fields_id == 0)
						$tt = $no_answer_str;
					$csv_data .="{$z}{$tt}";
				}
				$csv_data .= "\n";
				if ( $questions[$key]->sf_qtype == 2 || $questions[$key]->sf_qtype == 3 ) {
					$total_row = array('s'=>0);
					foreach($data as $k => $item) {				
						$query = "SELECT ftext FROM #__survey_force_fields WHERE id = {$k}";
						$database->SetQuery( $query );
						$tt = SF_processPDFField($database->loadResult());
						if ($k == 0)
							$tt = $no_answer_str;	
						$ech = '';
						$total_col = 0;
						
						foreach($fields_ids as $fields_id) {
							$ech .= "{$z}".$item[$fields_id];
							$total_col = $total_col + $item[$fields_id];
							if (!isset($total_row[$fields_id]))
								$total_row[$fields_id] = 0;
							$total_row[$fields_id] = $total_row[$fields_id] + $item[$fields_id];
						}
						$total_row['s'] = $total_row['s'] + $total_col;
						$csv_data .= "$tt{$z}$total_col".$ech."\n";
					}
					$ech = '';
					foreach($fields_ids as $fields_id) {
								$ech .= "{$z}".$total_row[$fields_id];
					}
					$csv_data .= "Total{$z}{$total_row['s']}".$ech."\n";
				}
				elseif( $questions[$key]->sf_qtype == 4 ) {
					if ($questions[$key]->answer_count > 0 ) {						
						foreach($data as $nn => $itemz) {	
							$tmp = '';
							if ($nn == 1) $tmp = JText::_('COM_SF_1ST_ANSWER');
							if ($nn == 2) $tmp = JText::_('COM_SF_SECOND_ANSWER');
							if ($nn == 3) $tmp = JText::_('COM_SF_THIRD_ANSWER');
							if ($nn > 3) $tmp = $nn.JText::_('COM_SF_TH_ANSWER');							
							$csv_data .= "$tmp\n";
							$total_row = array('s'=>0);
							foreach($itemz as $k=>$item) {
								$tt = SF_processPDFField($k);
								if ($k === 0)
									$tt = $no_answer_str;	
								$ech = '';
								$total_col = 0;
								
								foreach($fields_ids as $fields_id) {
									$ech .= "{$z}".$item[$fields_id];
									$total_col = $total_col + $item[$fields_id];
									if (!isset($total_row[$fields_id]))
										$total_row[$fields_id] = 0;
									$total_row[$fields_id] = $total_row[$fields_id] + $item[$fields_id];
								}
								$total_row['s'] = $total_row['s'] + $total_col;
								$csv_data .= "$tt{$z}$total_col".$ech."\n";
							}
							$ech = '';
							foreach($fields_ids as $fields_id) {
										$ech .= "{$z}".$total_row[$fields_id];
							}
							$csv_data .= "Total{$z}{$total_row['s']}".$ech."\n";
						}						
					}
					else {
						$total_row = array('s'=>0);
						foreach($data as $k => $item) {				
							$tt = SF_processPDFField($k);
							if ($k === 0)
								$tt = $no_answer_str;	
							$ech = '';
							$total_col = 0;
							
							foreach($fields_ids as $fields_id) {
								$ech .= "{$z}".$item[$fields_id];
								$total_col = $total_col + $item[$fields_id];
								if (!isset($total_row[$fields_id]))
									$total_row[$fields_id] = 0;
								$total_row[$fields_id] = $total_row[$fields_id] + $item[$fields_id];
							}
							$total_row['s'] = $total_row['s'] + $total_col;
							$csv_data .= "$tt{$z}$total_col".$ech."\n";
						}
						$ech = '';
						foreach($fields_ids as $fields_id) {
									$ech .= "{$z}".$total_row[$fields_id];
						}
						$csv_data .= "Total{$z}{$total_row['s']}".$ech."\n";
					}
				}
				else {					
					foreach($data as $k => $item) {
						$total_row = array('s'=>0);
						$query = "SELECT ftext FROM #__survey_force_fields WHERE id = {$k}";
						$database->SetQuery( $query );
						$tt = SF_processPDFField($database->loadResult());
						if ($k == 0)
							continue;
							
						$csv_data .= "$tt\n";
						foreach($item as $kk => $it) {
							$query = "SELECT ftext FROM #__survey_force_fields WHERE id = {$kk}";
							if ($questions[$key]->sf_qtype == 1) {
								$query = "SELECT stext  FROM #__survey_force_scales WHERE id = {$kk} ORDER BY ordering";
							}						
							$database->SetQuery( $query );
							$tt = SF_processPDFField($database->loadResult());
							if ($kk == 0)
								$tt = $no_answer_str;	
							$ech = '';
							$total_col = 0;
							
							foreach($fields_ids as $fields_id) {
								$ech .= "{$z}".$it[$fields_id];
								$total_col = $total_col + $it[$fields_id];
								if (!isset($total_row[$fields_id]))
									$total_row[$fields_id] = 0;
								$total_row[$fields_id] = $total_row[$fields_id] + $it[$fields_id];
							}
							$total_row['s'] = $total_row['s'] + $total_col;
							$csv_data .= "$tt{$z}$total_col".$ech."\n";
						}
						$ech = '';
						foreach($fields_ids as $fields_id) {
							$ech .= "{$z}".$total_row[$fields_id];
						}
						$csv_data .= "Total{$z}{$total_row['s']}".$ech."\n";
					}
				}
				$csv_data .= "\n\n";
			}
			$filedata = SF_processField($csv_data);
			@ob_end_clean();
			header("Content-type: application/csv");
			header("Content-Length: ".strlen(ltrim($filedata)));
			header("Content-Disposition: attachment; filename=report.csv");
			echo $filedata;
		} 
		exit;	
	}
	else {
		echo "<script> alert('".JText::_('COM_SF_YOU_NOT_SPECIFY_ENOUGH_DATA')."'); window.history.go(-1); </script>\n";
		exit();
	}
}

function SF_processField($field_text) {
	$field_text = (get_magic_quotes_gpc()) ? mosStripslashes( $field_text ) : $field_text; 
	$field_text = ampReplace($field_text);
	$field_text = str_replace( '&quot;', '"', $field_text );
	$field_text = str_replace( '&#039;', "'", $field_text );
	$field_text = str_replace( '&#39;', "'", $field_text );
	return trim($field_text);
}
function SF_processPDFField($field_text, $allowed_tags = '') {
	$field_text = strip_tags($field_text, $allowed_tags );
	$field_text = rel_pdfCleaner($field_text);
	$field_text = (get_magic_quotes_gpc()) ? mosStripslashes( $field_text ) : $field_text; 	
	$field_text = str_replace( '&quot;', '"', $field_text );
	$field_text = str_replace( '&#039;', "'", $field_text );
	$field_text = str_replace( '&#39;', "'", $field_text );
	return trim($field_text);
}

function SF_ViewAdvReport( $option ) {
	global $database, $mainframe, $mosConfig_list_limit;
	
	$catid 		= intval( $mainframe->getUserStateFromRequest( "catid{$option}", 'catid', 0 ) ); 
	$limit 		= intval( $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', $mosConfig_list_limit ) );
	$limitstart = intval( $mainframe->getUserStateFromRequest( "view{$option}limitstart", 'limitstart', 0 ) );
	if ($limit == 0) $limit = 999999;
	// get the total number of records
	$query = "SELECT COUNT(*)"
	. "\n FROM #__survey_force_survs"
	. ( $catid ? "\n WHERE sf_cat = $catid" : '' )
	;
	$database->setQuery( $query );
	$total = $database->loadResult();

	require_once( $GLOBALS['mosConfig_absolute_path'] . '/administrator/includes/pageNavigation.php' );
	$pageNav = new mosPageNav( $total, $limitstart, ($limit==999999?0:$limit) );

	// get the subset (based on limits) of required records
	$query = "SELECT a.*, b.sf_catname, us.username "
	. "\n FROM #__survey_force_survs a LEFT JOIN #__survey_force_cats b ON a.sf_cat = b.id LEFT JOIN #__users as us ON a.sf_author = us.id"
	. ( $catid ? "\n WHERE a.sf_cat = $catid" : '' )
	. "\n ORDER BY a.sf_name, b.sf_catname"
	. "\n LIMIT $pageNav->limitstart, $pageNav->limit"
	;
	$database->setQuery( $query );
	$rows = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
	
	$javascript = "onchange=\"document.adminForm.task.value='adv_report';document.adminForm.submit();\"";
	$query = "SELECT id AS value, sf_catname AS text"
	. "\n FROM #__survey_force_cats"
	. "\n ORDER BY sf_catname"
	;
	$database->setQuery( $query );

	$lists = array();
	$categories[] = mosHTML::makeOption( '0', _SEL_CATEGORY );
	$categories = @array_merge( $categories, ($database->LoadObjectList() == null? array(): $database->LoadObjectList()) );
	$category = mosHTML::selectList( $categories,'catid', 'class="text_area" size="1" '. $javascript, 'value', 'text', $catid ); 
	$lists['category'] = $category; 

	#################################################################################
	$survid = intval( mosGetParam( $_REQUEST, 'survid', 0) );

	$query = "SELECT id AS value, sf_name AS text FROM #__survey_force_survs ";
	$database->SetQuery( $query );
	$surveys = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
	$survid = ($survid > 0? $survid: (isset($surveys[0]->value)?$surveys[0]->value:null)); 
	$surveys = mosHTML::selectList( $surveys, 'survid', 'class="text_area" size="1" onchange="document.adminForm.task.value=\'adv_report\';document.adminForm.submit();"', 'value', 'text', $survid); 
	$lists['surveys'] = $surveys;
	
	$query = "SELECT id AS value, SUBSTRING(sf_qtext,1,100) AS text, sf_qtype FROM #__survey_force_quests WHERE published = 1 AND sf_qtype NOT IN (4, 7, 8) AND sf_survey = $survid ORDER BY ordering, id";
	$database->SetQuery( $query );
	$questions_tmp = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
	$questions = array();
	if (count($questions_tmp)>0) {
		foreach($questions_tmp as $question) {
			if ($question->sf_qtype != 2 && $question->sf_qtype != 3) {
				$query = "SELECT id, ftext FROM #__survey_force_fields WHERE quest_id = {$question->value} AND is_main = 1 ORDER BY ordering";
				$database->SetQuery( $query );
				$fields_tmp = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
				foreach($fields_tmp as $field) {
					$tmp = new stdClass;
					$tmp->value = $question->value.'_'.$field->id;
					$tmp->text = $question->text.'  - '.$field->ftext;
					$questions[] = $tmp;
				}
			}
			else {
				$tmp = new stdClass;
				$tmp->value = $question->value;
				$tmp->text = $question->text;
				$questions[] = $tmp;
			}
		}
		$lists['mquest_id'] = mosHTML::selectList( $questions, 'mquest_id', 'class="text_area" size="4" ', 'value', 'text', $questions[0]->value);
	}
	else
		$lists['mquest_id'] = '';

	$query = "SELECT id AS value, SUBSTRING(sf_qtext,1,100) AS text, sf_qtype FROM #__survey_force_quests WHERE published = 1 AND sf_qtype NOT IN (7, 8) AND sf_survey = $survid ORDER BY ordering, id";
	$database->SetQuery( $query );
	$questions_tmp = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
	$questions = array();
	if (count($questions_tmp)>0) {
		foreach($questions_tmp as $question) {
			if ($question->sf_qtype != 2 && $question->sf_qtype != 3 && $question->sf_qtype != 4) {
				$query = "SELECT id, ftext FROM #__survey_force_fields WHERE quest_id = {$question->value} AND is_main = 1 ORDER BY ordering";
				$database->SetQuery( $query );
				$fields_tmp = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
				foreach($fields_tmp as $field) {
					$tmp = new stdClass;
					$tmp->value = $question->value.'_'.$field->id;
					$tmp->text = $question->text.'  - '.$field->ftext;
					$questions[] = $tmp;
				}
			}
			else {
				$tmp = new stdClass;
				$tmp->value = $question->value;
				$tmp->text = $question->text;
				$questions[] = $tmp;
			}
		}
	}	
	$questions2 = array();
	$questions2[] = mosHTML::makeOption( '0', JText::_('COM_SF_ALL_QUESTIONS') );
	$questions = @array_merge($questions2, $questions);
	$lists['cquest_id'] = mosHTML::selectList( $questions, 'cquest_id[]', 'class="text_area" size="4" multiple="multiple" ', 'value', 'text', 0);
	
	survey_force_adm_html::SF_showAdvReport( $rows, $lists, $pageNav, $option );
}

function SF_show_results ($surv_id, $option) {
	global $database, $mosConfig_live_site, $mosConfig_absolute_path, $front_end, $my;
	set_time_limit(0);
	global $mosConfig_absolute_path, $sf_lang, $sf_constants; 
	
	$lang =& JFactory::getLanguage();
	$lang->load('com_surveyforce', JPATH_SITE);

	require( $mosConfig_absolute_path . '/components/com_surveyforce/language/default.php' );
	
	foreach($sf_constants as $sf_constant) {
		$sf_lang[$sf_constant] = JText::_($sf_constant);
	}


	require ( $mosConfig_absolute_path . '/components/com_surveyforce/generate.surveyforce.php' );
	$rows = array();			
	$query = "SELECT id FROM #__survey_force_quests WHERE published = 1 AND sf_survey = '".$surv_id."' ORDER BY ordering, id";
	$database->SetQuery($query);
	$questions = $database->loadResultArray();
	
	$sf_config = new mos_Survey_Force_Config( );
	$prefix = $sf_config->get('sf_result_type') == 'Bar'? 'b': 'p';
	$gg = new sf_ImageGenerator(array($sf_config->get('sf_result_type')));
	$gg->colors['axisColor1'] = $sf_config->get($prefix.'_axis_color1');
	$gg->colors['axisColor2'] = $sf_config->get($prefix.'_axis_color2');
	$gg->colors['aquaColor1'] = $sf_config->get($prefix.'_aqua_color1');
	$gg->colors['aquaColor2'] = $sf_config->get($prefix.'_aqua_color2');
	$gg->colors['aquaColor3'] = $sf_config->get($prefix.'_aqua_color3');
	$gg->colors['aquaColor4'] = $sf_config->get($prefix.'_aqua_color4');
	$gg->colors['barColor1'] = $sf_config->get($prefix.'_bar_color1');
	$gg->colors['barColor2'] = $sf_config->get($prefix.'_bar_color2');
	$gg->colors['barColor3'] = $sf_config->get($prefix.'_bar_color3');
	$gg->colors['barColor4'] = $sf_config->get($prefix.'_bar_color4');
	$gg->width = $sf_config->get($prefix.'_width');
	$gg->height = $sf_config->get($prefix.'_height');
	$gg->clearOldImages();//delete yesterday images	
	foreach($questions as $question){
		$img_src = $gg->getImage($surv_id, $question, 1);
		if (is_array($img_src)) {
			foreach($img_src as $imgsrc){
				$rows[] = $imgsrc;
			}
		}
		elseif ($img_src) {
			$rows[] = $img_src;
		}
	}
	$lists = array();
	$query = "SELECT sf_name  FROM #__survey_force_survs WHERE id = ".$surv_id;
	$database->SetQuery($query);
	$lists['sname'] = $database->loadResult();
	
	$javascript = 'onchange="document.adminForm.submit();"';
	$query = "SELECT id AS value, sf_name AS text"
	. "\n FROM #__survey_force_survs"
	.( $front_end && $my->usertype != 'Super Administrator'? " WHERE sf_author = '{$my->id}' ": ' ')
	. "\n ORDER BY sf_name"
	;
	$database->setQuery( $query );
	
	$survey = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
	$survey = mosHTML::selectList( $survey,'cid[]', 'class="text_area" size="1" '. $javascript, 'value', 'text', $surv_id ); 
	$lists['survey'] = $survey; 
	if (!$front_end)
		survey_force_adm_html::show_results($rows, $lists, $option);
	else
		survey_force_front_html::show_results($rows, $lists, $option);
	
}


function SF_preview_survey ($id, $option) {
	global $database, $mainframe, $option, $mosConfig_live_site;
	$unique_id = md5(uniqid(rand(), true));
	$query = "INSERT INTO `#__survey_force_previews` SET `preview_id` = '".$unique_id."', `time` = '".time()."'";
	$database->setQuery( $query );
	$database->query( );
	
	mosRedirect( $mosConfig_live_site."/index.php?option={$option}&survey={$id}&preview=".$unique_id );
}

function SF_templateManager(){
	global $database, $mainframe, $mosConfig_list_limit, $front_end, $option;

	$limit 		= intval( $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', $mosConfig_list_limit ) );
	$limitstart = intval( $mainframe->getUserStateFromRequest( "view{$option}limitstart", 'limitstart', 0 ) );
	if ($limit == 0) $limit = 999999;
	// get the total number of records
	$query = "SELECT COUNT(*)"
	. "\n FROM #__survey_force_templates";
	$database->setQuery( $query );
	$total = $database->loadResult();

	require_once( $GLOBALS['mosConfig_absolute_path'] . '/administrator/includes/pageNavigation.php' );

	$pageNav = new mosPageNav( $total, $limitstart, ($limit==999999?0:$limit) );

	// get the subset (based on limits) of required records
	$query = "SELECT a.* "
	. "\n FROM #__survey_force_templates AS a"
	. "\n ORDER BY a.sf_name "
	. "\n LIMIT {$pageNav->limitstart}, {$pageNav->limit}"
	;
	$database->setQuery( $query );
	$rows = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());

	survey_force_adm_html::SF_ShowTemplates( $rows, $pageNav, $option);
}

function SF_editTemplate( $id, $option ) {
	global $database, $my;
	
	survey_force_adm_html::SF_editTemplate( $row, $lists, $option );
}

function SF_saveTemplate( $option ) {
	global $mosConfig_absolute_path, $database;
	// XML library
	require_once( $mosConfig_absolute_path . '/includes/domit/xml_domit_lite_include.php' ); 	
	require_once(_SURVEY_FORCE_ADMIN_HOME . "/installer.surveyforce.php");

	$installer = new SF_InstallerTemplate();
	// Check if file uploads are enabled
	if (!(bool)ini_get('file_uploads')) {
		survey_force_adm_html::showInstallMessage( JText::_('COM_SF_THE_INSTALLER_CANT_CONTINUE_BEFORE_FILE_UPLOADS'),
			JText::_('COM_SF_INSTALLER_ERROR'), $installer->returnTo( $option ) );
		exit();
	}

	// Check that the zlib is available
	if(!extension_loaded('zlib')) {
		survey_force_adm_html::showInstallMessage( JText::_('COM_SF_THE_INSTALLER_CANT_CONTINUE_BEFORE_ZLIB'),
			JText::_('COM_SF_INSTALLER_ERROR'), $installer->returnTo( $option ) );
		exit();
	}

	$userfile = mosGetParam( $_FILES, 'userfile', null );

	if (!$userfile) {
		survey_force_adm_html::showInstallMessage( JText::_('COM_SF_NO_FILE_SELECTED'), JText::_('COM_SF_UPLOAD_NEW_TEMPLATE_ERROR'),
			$installer->returnTo( $option ));
		exit();
	}

	$userfile_name = $userfile['name'];

	$msg = '';
	$resultdir = SF_uploadFile( $userfile['tmp_name'], $userfile['name'], $msg );

	if ($resultdir !== false) {
		if (!$installer->upload( $userfile['name'] )) {
			survey_force_adm_html::showInstallMessage( $installer->getError(), JText::_('COM_SF_UPLOAD_TEMPLATE_UPLOAD_ERROR'),
				$installer->returnTo( $option ) );
		}
		$ret = $installer->install();

		survey_force_adm_html::showInstallMessage( $installer->getError(), JText::_('COM_SF_UPLOAD_TEMPLATE').' - '.($ret ? JText::_('COM_SF_SUCCESS') : JText::_('COM_SF_FAILED')),
			$installer->returnTo( $option ) );
		cleanupInstall( $userfile['name'], $installer->unpackDir() );
		if ($ret) {
			$database->SetQuery("INSERT INTO `#__survey_force_templates` (sf_name) VALUES('".strtolower(str_replace(" ","_",$installer->elementName()))."')");
			$database->query();
		
		}
	} else {
		survey_force_adm_html::showInstallMessage( $msg, JText::_('COM_SF_UPLOAD_TEMPLATE_UPLOAD_ERROR'),
			$installer->returnTo( $option ) );
	}
}

function SF_removeTemplate( $cid, $option) {
	global $mosConfig_absolute_path, $database;
	// XML library
	require_once( $mosConfig_absolute_path . '/includes/domit/xml_domit_lite_include.php' );	
	require_once(_SURVEY_FORCE_ADMIN_HOME . "/installer.surveyforce.php");

	$installer = new SF_InstallerTemplate();
	$result 	= false;
	if ($cid[0] && $cid[0] != 1) {
		$result = $installer->uninstall( $cid[0], $option );
		$database->SetQuery("DELETE FROM `#__survey_force_templates` WHERE id = '".$cid[0]."' AND id <> '1' ");
		$database->query();
	}

	$msg = $installer->getError();

	mosRedirect( $installer->returnTo( $option ), $result ? JText::_('COM_SF_SUCCESS') . $msg : JText::_('COM_SF_FAILED') . $msg );
}

function SF_editTemplateCSS( $id, $option ) {
	global $mosConfig_absolute_path, $database;

	$query = "SELECT sf_name FROM #__survey_force_templates WHERE id ='".$id."'";
	$database->SetQuery( $query );
	$p_tname = $database->LoadResult();
	$file = $mosConfig_absolute_path .'/media/surveyforce/'. $p_tname .'/surveyforce.css';

	if ($fp = fopen( $file, 'r' )) {
		$content = fread( $fp, filesize( $file ) );
		$content = htmlspecialchars( $content );
		survey_force_adm_html::SF_editCSSSource( $id, $p_tname, $content, $option );
	} else {
		mosRedirect( 'index2.php?option='. $option .'&task=templates', JText::_('COM_SF_OPERATION_FAILED_NOT_OPEN'). $file );
	}
}

function SF_saveTemplateCSS( $option ) {
	global $mosConfig_absolute_path, $database;
	$template_id	= intval( mosGetParam( $_POST, 'template', 0 ) );
	$query = "SELECT sf_name FROM #__survey_force_templates WHERE id = '".$template_id."'";
	$database->SetQuery( $query );
	$template = $database->LoadResult();
	$filecontent 	= mosGetParam( $_POST, 'filecontent', '', _MOS_ALLOWHTML );

	if ( !$template ) {
		mosRedirect( 'index2.php?option='. $option .'&task=templates', JText::_('COM_SF_OPERATION_FAILED_NO_TEMPLATE_SPECIFIED') );
	}

	if ( !$filecontent ) {
		mosRedirect( 'index2.php?option='. $option .'&task=templates', JText::_('COM_SF_OPERATION_FAILED_CONTENT_EMPTY') );
	}

	$file = $mosConfig_absolute_path .'/media/surveyforce/'. $template .'/surveyforce.css';

	$enable_write 	= mosGetParam($_POST,'enable_write',0);
	$oldperms 		= fileperms($file);
	
	if ($enable_write) {
		@chmod($file, $oldperms | 0222);
	}

	clearstatcache();
	if ( is_writable( $file ) == false ) {
		mosRedirect( 'index2.php?option='. $option .'&task=templates', JText::_('COM_SF_OPERATION_FAILED_FILE_NOT_WRITABLE') );
	}

	if ($fp = fopen ($file, 'w')) {
		fputs( $fp, stripslashes( $filecontent ) );
		fclose( $fp );
		if ($enable_write) {
			@chmod($file, $oldperms);
		} else {
			if (mosGetParam($_POST,'disable_write',0))
				@chmod($file, $oldperms & 0777555);
		}
		mosRedirect("index2.php?option=$option&task=templates");
	} else {
		if ($enable_write) @chmod($file, $oldperms);
		mosRedirect( 'index2.php?option='. $option .'&task=templates', JText::_('COM_SF_OPERATION_FAILED_OPEN_FILE_FOR_WRITING') );
	}
}

function sfGetOrderingList( $sql, $chop='55' ) {
	global $database;

	$order = array();
	$database->setQuery( $sql );
	if (!($orders = ($database->LoadObjectList() == null? array(): $database->LoadObjectList()))) {
		if ($database->getErrorNum()) {
			echo $database->stderr();
			return false;
		} else {
			$order[] = mosHTML::makeOption( 1, JText::_('COM_SF_FIRST') );
			return $order;
		}
	}
	$order[] = mosHTML::makeOption( 0, '0 '.JText::_('COM_SF_FIRST') );
	for ($i=0, $n=count( $orders ); $i < $n; $i++) {
		$orders[$i]->text = strip_tags($orders[$i]->text);
		if (strlen($orders[$i]->text) > $chop) {
			$text = substr($orders[$i]->text,0,$chop)."...";
		} else {
			$text = $orders[$i]->text;
		}

		$order[] = mosHTML::makeOption( $orders[$i]->value, $orders[$i]->value.' ('.$text.')' );
	}
	$order[] = mosHTML::makeOption( $orders[$i-1]->value+1, ($orders[$i-1]->value+1).JText::_('COM_SF_LAST') );

	return $order;
} 

function SF_latestNews(){
	global $option, $mainframe, $mosConfig_live_site, $jdd_version;
	
	require_once( $mainframe->getCfg('absolute_path') . '/administrator/components/com_surveyforce/Snoopy.class.php' );

	
	$s = new Snoopy();
	$s->read_timeout = 10;
	$s->referer = $mosConfig_live_site;
	@$s->fetch('http://www.joomplace.com/news_check/componentNewsCheck.php?component=survey_deluxe');
	$news_info = $s->results;
	
	if($s->error || $s->status != 200){
    	echo '<font color="red">'.JText::_('COM_SF_CONNECTION_TO_UPDATE_SERVER_FAILED') . $s->error . ($s->status == -100 ? JText::_('COM_SF_TIMEOUT') : $s->status).'</font>';
    } else {
    	echo $news_info;
    }
}

function ep_latestVersion(){
	global $mainframe, $mosConfig_live_site, $survey_version;
	$ep_version = $survey_version;

	require_once( $mainframe->getCfg('absolute_path') . '/administrator/components/com_surveyforce/Snoopy.class.php' );
	
	$s = new Snoopy();
	$s->read_timeout = 90;
	$s->referer = $mosConfig_live_site;
	@$s->fetch('http://www.joomplace.com/version_check/componentVersionCheck.php?component=survey_deluxe&current_version='.urlencode($ep_version));	
	$version_info = $s->results;
	$version_info_pos = strpos($version_info, ":");
	if ($version_info_pos === false) {
		$version = $version_info;
		$info = null;
	} else {
		$version = substr( $version_info, 0, $version_info_pos );
		$info = substr( $version_info, $version_info_pos + 1 );
	}
	if($s->error || $s->status != 200){
    	echo '<font color="red">'.JText::_('COM_SF_CONNECTION_TO_UPDATE_SERVER_FAILED') . $s->error . ($s->status == -100 ? JText::_('COM_SF_TIMEOUT') : $s->status).'</font>';
    } else if($version == $ep_version){
    	echo '<font color="green">' . $version . '</font>' . $info;
    } else {
    	echo '<font color="red">' . $version . '</font>' . $info;
    }
}

function SFRoute($url, $xhtml = null) {
	if (_JOOMLA15) {
		return JRoute::_($url, false);
		// Replace all &amp; with & as the router doesn't understand &amp;
		$url = str_replace('&amp;', '&', $value);

		$uri    = JURI::getInstance();
		$prefix = $uri->toString(array('scheme', 'host', 'port'));
		return $prefix.JRoute::_($url, false);
		
	}else
		return sefRelToAbs($url);
}

function SF_uploadFile( $filename, $userfile_name, &$msg ) {
	global $mosConfig_absolute_path;
	$baseDir = mosPathName( $mosConfig_absolute_path . '/media' );
	if (file_exists( $baseDir )) {
		if (is_writable( $baseDir )) {
			if (move_uploaded_file( $filename, $baseDir . $userfile_name )) {
				if (mosChmod( $baseDir . $userfile_name )) {
					return true;
				} else {
					$msg = JText::_('COM_SF_FAILED_TO_CHANGE_PERMISSIONS_OF_UPLOAD_FILE');
				}
			} else {
				$msg = JText::_('COM_SF_FAILED_TO_MOVE_UPLOADED_FILE');
			}
		} else {
			$msg = JText::_('COM_SF_UPLOAD_FAILED_MEDIA_DIRECTORY_NOT_WRITABLE');
		}
	} else {
		$msg = JText::_('COM_SF_UPLOAD_FAILED_MEDIA_DIRECTORY_NOT_EXIST');
	}
	return false;
}

function updateJSRules($survey_id) {
	jimport('joomla.filesystem.file');
	jimport('joomla.filesystem.folder');
	
	$rules = JFile::read(JPATH_SITE.'/components/com_surveyforce/jomsocial_rule.xml');
	
	if (strpos($rules, 'completed.survey'.$survey_id) === false) {
		$rules = str_replace('</rules></jomsocial>', '', $rules);
		$new_rule = "\r\n
\t<rule>\r\n
\t\t<name>".JText::_('COM_SF_COMPLETED_SURVEY').$survey_id."</name>\r\n
\t\t<description>".JText::_('COM_SF_GIVE_POINTS_WHEN_REGISTERED_USER_COMPLETE_SURVEY')."</description>\r\n
\t\t<action_string>completed.survey".$survey_id."</action_string>\r\n
\t\t<publish>".JText::_('COM_SF_FALSE')."</publish>\r\n
\t\t<points>0</points>\r\n
\t\t<access_level>1</access_level>\r\n
\t</rule>\r\n
\r\n";
		$rules .= $new_rule.'</rules></jomsocial>';

		JFile::delete(JPATH_SITE.'/components/com_surveyforce/jomsocial_rule.xml');
		JFile::write(JPATH_SITE.'/components/com_surveyforce/jomsocial_rule.xml', $rules);
	}
}

?>