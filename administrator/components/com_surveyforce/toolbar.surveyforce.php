<?php
/**
* Survey Force component for Joomla
* @version $Id: toolbar.surveyforce.php 2009-11-16 17:30:15
* @package Survey Force
* @subpackage toolbar.surveyforce.php
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined( '_VALID_MOS' ) or die( 'Restricted access' );

require_once( $mainframe->getPath( 'toolbar_html' ) );

switch ( $task ) {	
	case 'templates':		
		TOOLBAR_Survey_Force::_TEMPLATESLIST();	
		break;
	case 'add_template':	
		TOOLBAR_Survey_Force::_TEMPLATESEDIT();	
		break;
	case 'edit_css':		
		TOOLBAR_Survey_Force::_EDIT_CSS();		
		break;
		
	case 'categories':
		TOOLBAR_Survey_Force::_CATSLIST();
		break;
	case 'iscales':
		TOOLBAR_Survey_Force::_ISCALESLIST();
		break;
	case 'import_label':
		TOOLBAR_Survey_Force::_LABELSIMPORT();
		break;
	case 'surveys':
		TOOLBAR_Survey_Force::_SURVEYSLIST();
		break;

	case 'questions':
		TOOLBAR_Survey_Force::_QUESTSLIST();
		break;
		
	case 'users':
		TOOLBAR_Survey_Force::_USERSLIST();
		break;

	case 'emails':
		TOOLBAR_Survey_Force::_EMAILSLIST();
		break;

	case 'view_users':
		TOOLBAR_Survey_Force::_VIEWUSERS();
		break;

	case 'reports':
		TOOLBAR_Survey_Force::_VIEWREPORTS();
		break;
	case 'view_result_c':
	case 'view_result':
		TOOLBAR_Survey_Force::_VIEWRESULT();
		break;
	case 'view_rep_survA':
	case 'view_rep_surv':
		TOOLBAR_Survey_Force::_VIEWREPSURV();
		break;
	case 'view_rep_listA':
	case 'view_rep_list':
		TOOLBAR_Survey_Force::_VIEWREPLIST();
		break;
	case 'rep_surv':
		TOOLBAR_Survey_Force::_VIEW_REP_SURVLIST();
		break;
	case 'adv_report':
		TOOLBAR_Survey_Force::_VIEW_ADVREP();
		break;
	case 'rep_list':
		TOOLBAR_Survey_Force::_VIEW_REP_LISTSLIST();
		break;
	case 'add_user':
	case 'edit_user':
	case 'editA_user':
		TOOLBAR_Survey_Force::_USERSEDIT();
		break;
	case 'invite_users':
		TOOLBAR_Survey_Force::_USERSINVITE();
		break;

	case 'remind_users':
		TOOLBAR_Survey_Force::_USERSREMIND();
		break;

	case 'add_email':
	case 'edit_email':
	case 'editA_email':
		TOOLBAR_Survey_Force::_EMAILSEDIT();
		break;

	case 'edit_list':
	case 'add_list':
		TOOLBAR_Survey_Force::_LISTSEDIT();
		break;
	case 'editA_sec':
	case 'add_new_section':
		TOOLBAR_Survey_Force::_EDITSECTION();
		break;
	
	case 'set_default':
		TOOLBAR_Survey_Force::_SETDEFAULT();
		break;
		
	case 'add_new':
	case 'add_ranking':
	case 'add_boilerplate':
	case 'add_likert':
	case 'add_pickone':
	case 'add_pickmany':
	case 'add_short':
	case 'add_drp_dwn':
	case 'add_drg_drp':
	case 'edit_quest':
	case 'editA_quest':
		TOOLBAR_Survey_Force::_QUESTSEDIT();
		break;
	case 'add_iscale':
	case 'edit_iscale':
	case 'editA_iscale':
		TOOLBAR_Survey_Force::_ISCALESEDIT();
		break;
	case 'add_iscale_from_quest':
			TOOLBAR_Survey_Force::_ISCALESEDITA();
		break;
	case 'move_quest_sel':
		TOOLBAR_Survey_Force::_QUESTMOVE();
		break; 
	case 'copy_quest_sel':
		TOOLBAR_Survey_Force::_QUESTCOPY();
		break; 
	case 'move_surv_sel':
		TOOLBAR_Survey_Force::_SURVMOVE();
		break; 
	case 'copy_surv_sel':
		TOOLBAR_Survey_Force::_SURVCOPY();
		break; 
	case 'move_user_sel':
		TOOLBAR_Survey_Force::_USERMOVE();
		break; 
	
	case 'copy_user_sel':
		TOOLBAR_Survey_Force::_USERCOPY();
		break; 

	case 'add_surv':
	case 'edit_surv':
	case 'editA_surv':
		TOOLBAR_Survey_Force::_SURVEYSEDIT();
		break;

	case 'add_cat':
	case 'edit_cat':
	case 'editA_cat':
		TOOLBAR_Survey_Force::_CATSEDIT();
		break;

	case 'new':
	case 'edit':
	case 'editA':
		TOOLBAR_Survey_Force::_EDIT();
		break;
		
	case 'config':
		TOOLBAR_Survey_Force::_VIEWCONFIG();
		break;
		
	case 'authors':	
		TOOLBAR_Survey_Force::_VIEWAUTHORS();
		break;
		
	case 'add_author':	
		TOOLBAR_Survey_Force::_ADDAUTHORS();
		break;
		
	case 'help':
	case 'about':
	case 'no_menu':
		break;
	
	case 'cross_rep':
		TOOLBAR_Survey_Force::_CROSSREPORT();
		break;
	case 'menu_man':
		TOOLBAR_Survey_Force::_MENU_MANAGER();	
		break;
	case 'show_results':
		TOOLBAR_Survey_Force::_SHOW_RESULTS();
		break;
	
	case 'license':
		TOOLBAR_Survey_Force::_LICENSE_PRESENTATION();
	break;
	
	case 'generate_invitations':
		TOOLBAR_Survey_Force::_GENERATE_INVITATIONS();
		break;
	
	default:
		#TOOLBAR_Survey_Force::_DEFAULT();
		# !!! FIX IT !!! (later)
		break;
}
?>