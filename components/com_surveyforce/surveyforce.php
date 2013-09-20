<?php
/**
* Survey Force component for Joomla
* @version $Id: surveyforce.php 2009-11-16 17:30:15
* @package Survey Force
* @subpackage surveyforce.php
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

// no direct access
defined( '_VALID_MOS' ) or defined( '_JEXEC' ) or die( 'Restricted access' );
require_once ( realpath(dirname(__FILE__).'/../../administrator/components/com_surveyforce/component.legacy.php') );
$TB_iframe = JRequest::getVar('TB_iframe', 'false');
$view = JRequest::getVar('view', '');
if($TB_iframe == 'true'){
	$_SESSION['TB_iframe'] = 'true';
}
if($view && $view != 'featured'){
	unset($_SESSION['TB_iframe']);
}
if(isset($_SESSION['TB_iframe']) && $_SESSION['TB_iframe'] == 'true'){
	$_REQUEST['tmpl'] = 'component';
}

if (!defined('_JOOMLA15')) {
	if (defined( '_JLEGACY' ) or defined( '_BITS_LEGACY' ))
		define( '_JOOMLA15', 1 );
	else
		define( '_JOOMLA15', 0 );
}

if (!defined('_SURVEY_FORCE_FRONT_HOME')) {
	define('_SURVEY_FORCE_FRONT_HOME', dirname(__FILE__));
} 

if (!defined('_SURVEY_FORCE_ADMIN_HOME')) {
	define('_SURVEY_FORCE_ADMIN_HOME', realpath(_SURVEY_FORCE_FRONT_HOME.'/../../administrator/components/com_surveyforce/'));
} 

if (_JOOMLA15) {
	if (!defined('_SEL_CATEGORY')) define( '_SEL_CATEGORY', '- '.JText::_('Select Category').' -');
	if (!defined('_CMN_NEW_ITEM_FIRST')) define( '_CMN_NEW_ITEM_FIRST', 'New items default to the first place. Ordering can be changed after this item is saved.');
	if (!defined('_PDF_GENERATED')) define('_PDF_GENERATED','Generated:');
	if (!defined('_CURRENT_SERVER_TIME_FORMAT')) define( '_CURRENT_SERVER_TIME_FORMAT', '%Y-%m-%d %H:%M:%S' );
	$now = date( 'Y-m-d H:i', time() ); 
	if (!defined('_CURRENT_SERVER_TIME')) define( '_CURRENT_SERVER_TIME', $now ); 
	if (!defined('_PN_DISPLAY_NR')) define('_PN_DISPLAY_NR','Display #');
}

global $my;
global $database, $mainframe, $mosConfig_absolute_path, $mosConfig_live_site;	

require_once ( dirname(__FILE__).'/surveyforce.html.php' );
require_once ( dirname(__FILE__).'/surveyforce.class.php' );
sf_getLanguage();
global $sf_lang;

$sf_config = new mos_Survey_Force_Config( );

$app =& JFactory::getApplication();
$uri =& JFactory::getURI(); 
if($sf_config->get('sf_force_ssl',0) && strtolower($uri->getScheme()) != 'https') {
	//forward to https
	$uri->setScheme('https');
	$app->redirect($uri->toString());
	die;
} 

require_once( $mosConfig_absolute_path . '/administrator/components/com_surveyforce/def.php' );

	$task 	= mosGetParam( $_REQUEST, 'task', '' );
	$option 	= mosGetParam( $_REQUEST, 'option', 'com_surveyforce' );
	
	if ($option == 'com_surveyforce')
	switch ($task) {
		case 'start_invited':	SF_ShowSurvey_Invited();	break;
		case 'ajax_action':		SF_analizeAjaxRequest();	break; 
		
		case 'help':			include(dirname(__FILE__).'/help/help.php');	break; 
		case 'insert_tag': 		die; 						break;
		
		case 'uploadimage':  		
		## CATEGORIES ##
		case 'categories':case 'add_cat':case 'editA_cat':case 'edit_cat':case 'save_cat':
		case 'apply_cat':case 'del_cat':case 'cancel_cat':		
		## SURVEYS ##
		case 'surveys':case 'add_surv':case 'edit_surv':case 'editA_surv':case 'apply_surv':
		case 'save_surv':case 'del_surv':case 'cancel_surv':case 'publish_surv':case 'unpublish_surv':	
		case 'move_surv_sel':case 'move_surv_save':case 'copy_surv_sel':case 'copy_surv_save': case 'show_results': case 'preview_survey':
		## QUESTIONS ##
		case 'publish_quest':case 'unpublish_quest': case 'new_question_type': case 'add_new':
		case 'questions':case 'add_new_section':case 'editA_sec':case 'apply_section':case 'save_section':	
		case 'cancel_section':case 'add_ranking':case 'add_pagebreak':case 'add_boilerplate':case 'add_likert':case 'add_pickone':	
		case 'add_pickmany':case 'add_short':case 'add_drp_dwn':case 'add_drg_drp':case 'set_default':	
		case 'save_default':case 'cancel_default':case 'edit_quest':case 'editA_quest':case 'apply_quest':
		case 'save_quest':case 'del_quest':case 'cancel_quest':case 'orderup':case 'orderdown':		
		case 'orderupS':case 'orderdownS':case 'saveorder':case 'move_quest_sel':case 'move_quest_save':
		case 'copy_quest_sel':case 'copy_quest_save':case 'add_iscale_from_quest':case 'save_iscale_A':
		case 'cancel_iscale_A': 
		### USERGROUPS ###
		case 'usergroups':case 'add_list':case 'edit_list':case 'save_list':case 'apply_list':case 'view_users':
		case 'add_user':case 'save_user':case 'del_user':case 'del_list':case 'cancel_user':case 'cancel_list':
		### REPORTS ###
		case 'reports': case 'view_result': case 'view_result_c': case 'rep_pdf': case 'rep_csv':case 'rep_surv':
		case 'view_rep_surv': case 'view_rep_survA': case 'rep_surv_print': case 'rep_print': case 'rep_list':
		case 'cross_rep': case 'i_report': case 'get_cross_rep': case 'view_irep_surv': case 'del_rep':case 'get_options':
		# ---	EMAILS	 --- #
		case 'emails': case 'add_email': case 'edit_email':	case 'editA_email':	case 'apply_email': case 'save_email': 
		case 'del_email': case 'cancel_email':
		# --- INVITATIONS --- #
		case 'generate_invitations': case 'make_inv_list': case 'invite_users': case 'remind_users':
		case 'invitation_start': case 'invitation_stop': case 'remind_start': case 'remind_stop':
			if (SF_GetUserType($my->id) == 1 || SF_GetUserType($my->id) == 2) {
				require_once ( $mosConfig_absolute_path . '/components/com_surveyforce/edit.surveyforce.php' );
				SF_analizeTask($task); 		
			} else 
				survey_force_html::Survey_blocked($sf_config);
			break;
		
		default:				SF_ShowSurvey();			break;
	}

function SFRoute_fe($url, $xhtml = null) {
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
	
function SF_analizeAjaxRequest() {
	$sf_task = mosGetParam($_REQUEST, 'action', '');
	require_once(dirname(__FILE__).'/survey.php');
	SF_process_ajax($sf_task);
	exit();
}

function SF_ShowSurvey($surv_id = null)
{
	global  $Itemid, $my, $database, $mosConfig_absolute_path, $sf_lang, $task, $option;
	
	$menu = new mosMenu( $database );
	$menu->load( $Itemid );
	$params = new mosParameters( $menu->params );
	$preview = mosGetParam( $_REQUEST, 'preview', false );

	$template = intval( mosGetParam( $_REQUEST, 'survey_template', 0 ) );
	if ($surv_id < 1)
		$survey_id = intval( mosGetParam( $_REQUEST, 'survey', $params->get('surv_id') ) );
	else 
		$survey_id = $surv_id;
	$cat_id = $params->get('cat_id');
	$view = JRequest::getVar('view');
	$cat_id = ($view == 'authoring') ? 0 : $cat_id;
	
	if ($cat_id && !$survey_id) {
		showSurveyCat($cat_id);
		return;
	}
	
	$survey = new mos_Survey_Force_Survey( $database );
	$survey->load($survey_id);
	$sf_config = new mos_Survey_Force_Config( );	
	$survey->is_complete = 0;
	
	$survey->sf_descr = sfPrepareText($survey->sf_descr);
	$survey->surv_short_descr = sfPrepareText($survey->surv_short_descr);
	
	if ($template > 0)
		$survey->sf_template = $template;
		
	$query = "SELECT `sf_name` FROM `#__survey_force_templates` WHERE `id` = '{$survey->sf_template}' ";
	$database->SetQuery($query);
	$survey->template = $database->LoadResult();
	
	//if no template
	if (strlen($survey->template) < 1) {
		require_once ( realpath(dirname(__FILE__).'/template.php') );
	} else {
		if (file_exists($mosConfig_absolute_path.'/media/surveyforce/'.$survey->template.'/template.php')) {
			require_once ($mosConfig_absolute_path.'/media/surveyforce/'.$survey->template.'/template.php');
		} else {
			require_once ( realpath(dirname(__FILE__).'/template.php') );
		}
	}
	
	if ($my->id) {
		$query = "SELECT 1 FROM `#__survey_force_user_starts` WHERE survey_id = {$survey_id} AND user_id = '".$my->id."' AND is_complete = 1 ORDER BY id DESC";
		$database->SetQuery($query);
		$survey->is_complete = (int)$database->LoadResult();
	} elseif ($survey->sf_pub_control > 0) {
		$ip = $_SERVER["REMOTE_ADDR"];
		$cookie = isset($_COOKIE[md5('survey'.$survey->id)])? $_COOKIE[md5('survey'.$survey->id)]: '';
		
		if ($survey->sf_pub_control == 1) {
			$query = "SELECT 1 FROM `#__survey_force_user_starts` WHERE survey_id = {$survey_id} AND user_id = '0' AND `sf_ip_address` = '{$ip}' AND is_complete = 1  ORDER BY id DESC";
		} elseif ($survey->sf_pub_control == 2) {
			$query = "SELECT 1 FROM `#__survey_force_user_starts` WHERE survey_id = {$survey_id} AND user_id = '0' AND `unique_id` = '{$cookie}' AND is_complete = 1  ORDER BY id DESC";
		} elseif ($survey->sf_pub_control == 3) {
			$query = "SELECT 1 FROM `#__survey_force_user_starts` WHERE survey_id = {$survey_id} AND user_id = '0' AND `unique_id` = '{$cookie}' AND `sf_ip_address` = '{$ip}' AND is_complete = 1  ORDER BY id DESC";
		}
		$database->SetQuery($query);
		$survey->is_complete = (int)$database->LoadResult();
	}
	
	$query = " SELECT * FROM `#__survey_force_quest_show` WHERE `survey_id` = '".$survey->id."' ";
	$database->SetQuery($query);
	$rules = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());


	if ($preview){
		$query = "SELECT `id` FROM `#__survey_force_previews` WHERE `preview_id` = '".$preview."'";
		$database->SetQuery($query);
		if ($database->loadResult()) {
			$query = "DELETE FROM `#__survey_force_previews` WHERE `preview_id` = '".$preview."'";
			$database->SetQuery($query);
			$database->query();
			
			$survey->is_complete = 0;
			$query = " SELECT sf_qtype FROM #__survey_force_quests WHERE published = 1 AND sf_survey = {$survey->id} ORDER BY ordering, id ";
			$database->SetQuery( $query );
			$q_data = $database->LoadResultArray();
			for($i = 0, $n = count($q_data); $i<$n; $i++) {
				if ($survey->sf_auto_pb == 0 && $q_data[$i] != 8 && isset($q_data[$i+1]) && $q_data[$i+1] != 8)
					$survey->sf_image = '';
			}
		
			survey_force_html::PreLoadSurvey($survey, $sf_config,0,'',$rules, 1);
			return;
		}
		else {
			survey_force_html::Survey_blocked($sf_config);
			return;
		}
	}
				
	$now = _CURRENT_SERVER_TIME;
	if ( ($survey->published) && ($survey->sf_date == '0000-00-00 00:00:00' || intval(strtotime($survey->sf_date)) >= intval(strtotime($now))) ) {
		$query = " SELECT sf_qtype FROM #__survey_force_quests WHERE published = 1 AND sf_survey = {$survey->id} ORDER BY ordering, id ";
		$database->SetQuery( $query );
		$q_data = $database->LoadResultArray();
		for($i = 0, $n = count($q_data); $i<$n; $i++) {
			if ($survey->sf_auto_pb == 0 && $q_data[$i] != 8 && isset($q_data[$i+1]) && $q_data[$i+1] != 8)
				$survey->sf_image = '';
		}
		$sf_special = false;
		if (($my->id) && ($survey->sf_special)) {
			$query = "SELECT COUNT(*) FROM #__survey_force_users AS a "
					."\n WHERE a.list_id IN ({$survey->sf_special}) "
					."\n AND a.lastname = '{$my->name}' AND a.email = '{$my->email}' ";
			$database->SetQuery( $query );
			if ($database->LoadResult() > 0 )
				$sf_special = true;
			elseif (SF_GetUserType($my->id,$survey->id) == 1 )
				$sf_special = true;
				
		}
		
		$friends = array();
		if ($sf_config->get('sf_enable_jomsocial_integration')) { 
			$query = "SELECT j.connect_to FROM #__community_connection AS j WHERE j.status = 1 AND j.connect_from = '{$survey->sf_author}'";
			$database->SetQuery( $query );
			$friends = $database->LoadResultArray();
		}
			
		if ( ($my->id) && ($survey->sf_reg) ) {
			survey_force_html::PreLoadSurvey($survey, $sf_config,0,'',$rules);
		} elseif (($my->id) && ($survey->sf_friend) && $sf_config->get('sf_enable_jomsocial_integration') && in_array($my->id, $friends)) {
			survey_force_html::PreLoadSurvey($survey, $sf_config,0,'',$rules);
		} elseif ( $sf_special ) {
			$survey->is_complete = 0;
			survey_force_html::PreLoadSurvey($survey, $sf_config,0,'',$rules);
		} elseif ($survey->sf_public) {
			survey_force_html::PreLoadSurvey($survey, $sf_config,0,'',$rules);
		} elseif ($my->id && SF_GetUserType($my->id, $survey->id) == 1 ) {
			survey_force_html::PreLoadSurvey($survey, $sf_config,0,'',$rules);
		} else {
			survey_force_html::Survey_blocked($sf_config);
		}
	} else {
		if ($survey->id && $my->id && SF_GetUserType($my->id, $survey->id) == 1 ) {			 	
			survey_force_html::PreLoadSurvey($survey, $sf_config,0,'',$rules);
		} elseif (!$survey->id &&  $my->id && (SF_GetUserType($my->id) == 1 || SF_GetUserType($my->id) == 2)) {
			$task = 'surveys';
			require_once ( $mosConfig_absolute_path . '/components/com_surveyforce/edit.surveyforce.php' );
			SF_analizeTask('surveys');
		}else
			survey_force_html::Survey_blocked($sf_config);
	}
}

function SF_ShowSurvey_Invited()
{
	global  $Itemid, $my, $database, $sf_lang, $mosConfig_absolute_path, $sf_lang;
	$survey_id = intval( mosGetParam( $_GET, 'survey', 0 ) );
	$invite_num = strval( mosGetParam( $_GET, 'invite', '' ) );
	$template = intval( mosGetParam( $_REQUEST, 'survey_template', 0 ) );
	$survey = new mos_Survey_Force_Survey( $database );
	$survey->load($survey_id);

	$survey->sf_descr = sfPrepareText($survey->sf_descr);
	$survey->surv_short_descr = sfPrepareText($survey->surv_short_descr);
	
	$sf_config = new mos_Survey_Force_Config( );
	
	if ($template > 0)
		$survey->sf_template = $template;
		
	$query = "SELECT `sf_name` FROM `#__survey_force_templates` WHERE `id` = '{$survey->sf_template}' ";
	$database->SetQuery($query);
	$survey->template = $database->LoadResult();
	
	//if no template
	if (strlen($survey->template) < 1) {
		require_once ( realpath(dirname(__FILE__).'/template.php') );
	} else {
		if (file_exists($mosConfig_absolute_path.'/media/surveyforce/'.$survey->template.'/template.php')) {
			require_once ($mosConfig_absolute_path.'/media/surveyforce/'.$survey->template.'/template.php');
		} else {
			require_once ( realpath(dirname(__FILE__).'/template.php') );
		}
	}

	
	$survey->is_complete = 0;
	if ($invite_num != '') {
		$query = "SELECT 1 FROM `#__survey_force_invitations` AS a, `#__survey_force_user_starts` AS b WHERE a.invite_num = '{$invite_num}' AND b.invite_id = a.id AND b.is_complete = 1";
		$database->SetQuery($query);
		$survey->is_complete = (int)$database->LoadResult();
	}
	

	$query = " SELECT * FROM `#__survey_force_quest_show` WHERE `survey_id` = '".$survey->id."' ";
	$database->SetQuery($query);
	$rules = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
	
	$now = _CURRENT_SERVER_TIME;
	if ( ($survey->published) && ($survey->sf_date == '0000-00-00 00:00:00' || intval(strtotime($survey->sf_date)) >= intval(strtotime($now))) ) {
		$query = " SELECT sf_qtype FROM #__survey_force_quests WHERE published = 1 AND sf_survey = {$survey->id} ORDER BY ordering, id ";
		$database->SetQuery( $query );
		$q_data = $database->LoadResultArray();
		for($i = 0, $n = count($q_data); $i<$n; $i++) {
			if ($survey->sf_auto_pb == 0 && $q_data[$i] != 8 && isset($q_data[$i+1]) && $q_data[$i+1] != 8)
				$survey->sf_image = '';
		}
		$sf_special = false;
		if (($my->id) && ($survey->sf_special)) {
			$query = "SELECT DISTINCT b.id FROM #__survey_force_users AS a, #__users AS b "
					."\n WHERE a.list_id IN ({$survey->sf_special}) AND b.id = {$my->id} "
					."\n AND a.name = b.username AND a.email = b.email AND a.lastname = b.name ";
			$database->SetQuery( $query );
			if ($database->LoadResult() > 0 )
				$sf_special = true;
		}
		
		$friends = array();
		if ($sf_config->get('sf_enable_jomsocial_integration')) { 
			$query = "SELECT j.connect_to FROM #__community_connection AS j WHERE j.status = 1 AND j.connect_from = '{$survey->sf_author}'";
			$database->SetQuery( $query );
			$friends = $database->LoadResultArray();
		}
		
		if ($survey->sf_invite) {
			survey_force_html::PreLoadSurvey($survey, $sf_config, 1, $invite_num,$rules);//second parameter: 1 - invited.
		} elseif ( ($my->id) && ($survey->sf_reg) ) {
			survey_force_html::PreLoadSurvey($survey, $sf_config,0,'',$rules);		
		}elseif (($my->id) && ($survey->sf_friend) && $sf_config->get('sf_enable_jomsocial_integration') && in_array($my->id, $friends)) {
			survey_force_html::PreLoadSurvey($survey, $sf_config,0,'',$rules);
		} elseif ( $sf_special ) {
			survey_force_html::PreLoadSurvey($survey, $sf_config,0,'',$rules);
		} elseif ($survey->sf_public) {
			survey_force_html::PreLoadSurvey($survey, $sf_config,0,'',$rules);
		} else {
			survey_force_html::Survey_blocked($sf_config);
		}
	} else {
		survey_force_html::Survey_blocked($sf_config);
	}
}

# 1 - owner/admin
# 2 - lms teacher/author
# 3 - other
# 0 - not logged
function SF_GetUserType($user_id, $survey_id = 0) {
	global $database, $my;
	if ( !($user_id > 0) )
		return 0;
	$query = "SELECT usertype FROM #__users WHERE id = '".$user_id."'";
	$database->SetQuery( $query );
  	$usertype = $database->LoadResult();
	if ($usertype == 'Super Administrator')
		return 1;
	$sf_config = new mos_Survey_Force_Config( );
	$enable_lms_integration = $sf_config->get('sf_enable_lms_integration');
	$sf_enable_jomsocial_integration = $sf_config->get('sf_enable_jomsocial_integration');
	
	$query = "SELECT id FROM #__components WHERE `link` = 'option=com_joomla_lms' AND `option` = 'com_joomla_lms' ";
	$database->SetQuery( $query );
	$is_lms = ($database->LoadResult() > 0 && $enable_lms_integration? true: false);
	
	$query = "SELECT id FROM #__components WHERE `link` = 'option=com_community' AND `option` = 'com_community' ";
	$database->SetQuery( $query );
	$is_jomsocial = ($database->LoadResult() > 0 && $sf_enable_jomsocial_integration? true: false);
	
	if ($is_lms) {
		$query = "SELECT lms_usertype_id FROM #__lms_users WHERE user_id = '".$user_id."'";
  		$database->SetQuery( $query );
  		$lms_usertype = $database->LoadResult();
		if ($survey_id < 1) {
			if ($lms_usertype == 1 || $lms_usertype == 5) 
				return 2;
		}
		else {
			$query = "SELECT sf_author FROM #__survey_force_survs WHERE id = '".$survey_id."'";
			$database->SetQuery( $query );
			$author_id = $database->LoadResult();
			if ($author_id == $user_id && ($lms_usertype == 1 || $lms_usertype == 5))
				return 1;
			elseif ($author_id != $user_id && ($lms_usertype == 1 || $lms_usertype == 5))
				return 2;
		}
	}
	
	$query = "SELECT id FROM #__survey_force_authors WHERE user_id = '".$user_id."'";
	$database->SetQuery( $query );
	$a_id = $database->LoadResult();
	if ( $survey_id < 1 ) {
		if ( $a_id > 0  || ($is_jomsocial && $user_id))
			return 2;
	}
	else {
		
		$query = "SELECT `sf_author` FROM #__survey_force_survs WHERE id = '".$survey_id."'";
		$database->SetQuery( $query );
		$author_id = $database->LoadResult();
		if ($author_id == $user_id && ( $a_id > 0 || ($is_jomsocial && $user_id)))
			return 1;
		elseif ($author_id != $user_id && ( $a_id > 0 || ($is_jomsocial && $user_id)))
			return 2;
	}
	return 3;
}

function showSurveyCat($cat_id=0) {	
	global  $database, $mosConfig_absolute_path;
	if (!$cat_id) {
		return;
	}
	$sf_config = new mos_Survey_Force_Config( );
	
	require_once ( realpath(dirname(__FILE__).'/template.php') );
	
	$query = "SELECT * FROM `#__survey_force_cats` WHERE `id` = '$cat_id'";
	$database->SetQuery( $query );
	$cat = $database->loadObjectList();
	$cat = $cat[0];
	
	$query = "SELECT * FROM `#__survey_force_survs` WHERE `sf_cat` = '$cat_id' AND `published` = 1";
	$database->SetQuery( $query );
	$rows = $database->loadObjectList();
	
	if(is_array($rows) && count($rows))
	foreach($rows as $i=>$row){
		$rows[$i]->sf_descr = sfPrepareText($rows[$i]->sf_descr);
		$rows[$i]->surv_short_descr = sfPrepareText($rows[$i]->surv_short_descr);
	}
	
	survey_force_html::showCategory($cat, $rows, $sf_config);
}

function sfPrepareText($text, $force_compatibility = false) {
	global $mosConfig_absolute_path;
	// Black list of mambots:
	
	$banned_bots = array();
	if ($force_compatibility) {
		/* Fix of the excellent :) "EOLAS - no click to activate" plugin */
		// 26.02.2007 - function "writethis(jsval);" generates opening of new window during processing DATA within LP Ajax procedures (document.write() fails)
		$banned_bots[] = 'botgznoclicktoactivate';
	}
	$row = new stdclass();
	$row->text = $text;
	$row->introtext = '';
	$params = new mosParameters('');
	$new_text = $text;
	
	if (class_exists('JFactory')) { // Joomla 1.5
		if (class_exists('JEventDispatcher')) { // 1.5 RC3
			$dispatcher	=& JEventDispatcher::getInstance();
		} elseif(class_exists('JDispatcher')) { // 1.5 RC4, 1.5 Stable, 1.5.1
			$dispatcher	=& JDispatcher::getInstance();
		}

		JPluginHelper::importPlugin('content');
		if ($force_compatibility) {
			$onPrepareContent_bots = $dispatcher->_observers;
			$onPrepareContent_bots_allowed = array();
			foreach ($onPrepareContent_bots as $oPCb) {
				if (is_array($oPCb) && isset($oPCb['event']) && $oPCb['event'] == 'onPrepareContent' && isset($oPCb['handler']) && in_array($oPCb['handler'], $banned_bots) ) {
					
				} else {
					$onPrepareContent_bots_allowed[] = $oPCb;
				}
			}
			$dispatcher->_observers = $onPrepareContent_bots_allowed;
		}

		$results = $dispatcher->trigger('onPrepareContent', array (& $row, & $params, 0));
		$new_text = $row->text;
	} else { // Joomla 1.0.x
		global $_MAMBOTS;
		$_MAMBOTS->loadBotGroup( 'content' );

		if ($force_compatibility) {
			$onPrepareContent_bots = $_MAMBOTS->_events['onPrepareContent'];
			$onPrepareContent_bots_allowed = array();
			foreach ($onPrepareContent_bots as $oPCb) {
				if (!in_array($oPCb[0], $banned_bots)) {
					$onPrepareContent_bots_allowed[] = $oPCb;
				}
			}
			$_MAMBOTS->_events['onPrepareContent'] = $onPrepareContent_bots_allowed;
		}
	
		$results = $_MAMBOTS->trigger( 'onPrepareContent', array( &$row, &$params, 0 ), true );
		$new_text = $row->text;
	}
	return $new_text;
}

function sf_getLanguage(){
	global $mosConfig_absolute_path, $sf_lang, $sf_constants, $sf_js_constants; 
	
	$lang =& JFactory::getLanguage();
	$lang->load('com_surveyforce', JPATH_SITE);

	require( $mosConfig_absolute_path . '/components/com_surveyforce/language/default.php' );
	
	foreach($sf_constants as $sf_constant) {
		$sf_lang[$sf_constant] = stripslashes(JText::_($sf_constant));
		$sf_lang[strtolower($sf_constant)] = stripslashes(JText::_($sf_constant));
	}
	foreach($sf_js_constants as $sf_js_constant) {
		$sf_lang[$sf_js_constant] = JText::_($sf_js_constant,true);
		$sf_lang[strtolower($sf_js_constant)] = JText::_($sf_js_constant,true);
	}
	
}

?>