<?php
/**
* Survey Force component for Joomla
* @version $Id: toolbar.surveyforce.html.php 2009-11-16 17:30:15
* @package Survey Force
* @subpackage toolbar.surveyforce.html.php
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined( '_VALID_MOS' ) or die( 'Restricted access' );

class TOOLBAR_Survey_Force {
	
	function _TEMPLATESLIST() {
		mosMenuBar::startTable();
		if (class_exists('JToolBarHelper')) {
			mosMenuBar::custom('edit_css','css','icon-32-css.png',JText::_('COM_SF_EDIT_CSS'),false);	
		}
		else{
			mosMenuBar::editCssX( 'edit_css' );
		}
		mosMenuBar::spacer();
		mosMenuBar::divider();
		mosMenuBar::spacer();
		mosMenuBar::deleteList('', 'del_template', JText::_('COM_SF_UNINSTALL'));
		mosMenuBar::spacer();
		if (class_exists('JToolBarHelper')) {
			mosMenuBar::custom('add_template','upload','icon-32-upload.png',JText::_('COM_SF_INSTALL'),false);	
		}
		else{
			mosMenuBar::custom('add_template','upload_f2.png','upload_f2.png',JText::_('COM_SF_INSTALL'),false);
		}
		mosMenuBar::endTable();
	}
	function _TEMPLATESEDIT() {		
		mosMenuBar::startTable();
		if (class_exists('JToolBarHelper')) {
			mosMenuBar::custom('save_template','upload','icon-32-upload.png',JText::_('COM_SF_INSTALL'),false);	
		}
		else{
			mosMenuBar::custom('save_template','upload_f2.png','upload_f2.png',JText::_('COM_SF_INSTALL'),false);
		}
		mosMenuBar::spacer();
		mosMenuBar::cancel('cancel_template');
		mosMenuBar::endTable();
	}
	function _EDIT_CSS(){
		mosMenuBar::startTable();
		mosMenuBar::save( 'save_css' );
		mosMenuBar::spacer();
		mosMenuBar::cancel('cancel_template');
		mosMenuBar::endTable();
	} 
	
	function _GENERATE_INVITATIONS() {
		mosMenuBar::startTable();
		mosMenuBar::custom( 'make_inv_list', 'cpanel.png', 'cpanel.png', JText::_('COM_SF_GENERATE'), false );
		mosMenuBar::endTable();
	}
	
	function _CATSLIST() {
		mosMenuBar::startTable();
		mosMenuBar::deleteList('', 'del_cat', JText::_('COM_SF_DELETE')); 
		mosMenuBar::spacer();
		mosMenuBar::editList('edit_cat');
		mosMenuBar::spacer();
		mosMenuBar::addNew('add_cat');
		mosMenuBar::endTable();
	}
	function _ISCALESLIST() {
		mosMenuBar::startTable();
		mosMenuBar::deleteList('', 'del_iscale', JText::_('COM_SF_DELETE')); 
		mosMenuBar::spacer();
		mosMenuBar::editList('edit_iscale');
		mosMenuBar::spacer();
		mosMenuBar::addNew('add_iscale');
		mosMenuBar::endTable();
	}
	function _USERSLIST() {
		mosMenuBar::startTable();
		mosMenuBar::custom( 'invite_users', 'cpanel.png', 'cpanel.png', JText::_('COM_SF_INVITE'), true );
		mosMenuBar::spacer();
		mosMenuBar::custom( 'remind_users', 'cpanel.png', 'cpanel.png', JText::_('COM_SF_REMIND'), true );
		mosMenuBar::spacer();
		mosMenuBar::divider();
		mosMenuBar::spacer();
		mosMenuBar::custom( 'copy_all', 'copy.png', 'copy_f2.png', JText::_('COM_SF_COPY_ALL'), false );
		mosMenuBar::spacer();
		mosMenuBar::custom( 'copy_list', 'copy.png', 'copy_f2.png', JText::_('COM_SF_COPY'), true );
		mosMenuBar::spacer();
		mosMenuBar::deleteList('', 'del_list', JText::_('COM_SF_DELETE')); 
		mosMenuBar::spacer();
		mosMenuBar::editList('edit_list');
		mosMenuBar::spacer();
		mosMenuBar::addNew('add_list');
		mosMenuBar::endTable();
	}

	function _EMAILSLIST() {
		mosMenuBar::startTable();
		mosMenuBar::deleteList('', 'del_email', JText::_('COM_SF_DELETE')); 
		mosMenuBar::spacer();
		mosMenuBar::editList('edit_email');
		mosMenuBar::spacer();
		mosMenuBar::addNew('add_email');
		mosMenuBar::endTable();
	}
	function _VIEWRESULT() {
		mosMenuBar::startTable();
		mosMenuBar::custom( 'rep_print', 'preview.png', 'preview_f2.png', 'PDF', false );
		mosMenuBar::spacer();
		mosMenuBar::custom( 'reports', 'back.png', 'back_f2.png', JText::_('COM_SF_BACK'), false );
		mosMenuBar::endTable();
	}
	function _VIEWREPSURV() {
		mosMenuBar::startTable();
		mosMenuBar::custom( 'rep_surv_print', 'preview.png', 'preview_f2.png', 'PDF', false );
		mosMenuBar::spacer();
		mosMenuBar::custom( 'rep_surv', 'back.png', 'back_f2.png', JText::_('COM_SF_BACK'), false );
		mosMenuBar::endTable();
	}
	function _VIEWREPLIST() {
		mosMenuBar::startTable();
		mosMenuBar::custom( 'rep_list_print', 'preview.png', 'preview_f2.png', 'PDF', false );
		mosMenuBar::spacer();
		mosMenuBar::custom( 'rep_list', 'back.png', 'back_f2.png', JText::_('COM_SF_BACK'), false );
		mosMenuBar::endTable();
	}
	function _USERSINVITE() {
		mosMenuBar::startTable();
		mosMenuBar::custom( 'users', 'back.png', 'back_f2.png', JText::_('COM_SF_BACK'), false );
		mosMenuBar::endTable();
	}
	function _USERSREMIND() {
		mosMenuBar::startTable();
		mosMenuBar::custom( 'users', 'back.png', 'back_f2.png', JText::_('COM_SF_BACK'), false );
		mosMenuBar::endTable();
	}
	function _VIEWUSERS() {
		mosMenuBar::startTable();
		mosMenuBar::custom( 'move_user_sel', 'move.png', 'move_f2.png', JText::_('COM_SF_MOVE'), true );
		mosMenuBar::spacer();
		mosMenuBar::custom( 'copy_user_sel', 'copy.png', 'copy_f2.png', JText::_('COM_SF_COPY'), true );
		mosMenuBar::spacer();
		mosMenuBar::deleteList('', 'del_user', JText::_('COM_SF_DELETE')); 
		mosMenuBar::spacer();
		mosMenuBar::editList('edit_user');
		mosMenuBar::spacer();
		mosMenuBar::addNew('add_user');
		mosMenuBar::spacer();
		mosMenuBar::custom( 'users', 'back.png', 'back_f2.png', JText::_('COM_SF_BACK'), false );
		mosMenuBar::endTable();
	}
	function _VIEWREPORTS() {
		mosMenuBar::startTable();
		mosMenuBar::custom( 'rep_list', 'preview.png', 'preview_f2.png', JText::_('COM_SF_USERLISTS'), false );
		mosMenuBar::spacer();
		mosMenuBar::custom( 'rep_surv', 'preview.png', 'preview_f2.png', JText::_('COM_SF_SURVEYS'), false );
		mosMenuBar::spacer();
		mosMenuBar::divider();
		mosMenuBar::spacer();
		mosMenuBar::custom( 'rep_pdf_sum', 'preview.png', 'preview_f2.png', 'PDF(sum)', false );
		mosMenuBar::spacer();
		mosMenuBar::custom( 'rep_pdf_sum_pc', 'preview.png', 'preview_f2.png', 'PDF(sum %)', false );
		mosMenuBar::spacer();
		mosMenuBar::custom( 'rep_csv', 'preview.png', 'preview_f2.png', 'CSV(sum)', false );
		mosMenuBar::spacer();
		mosMenuBar::divider();
		mosMenuBar::custom( 'rep_pdf', 'preview.png', 'preview_f2.png', 'PDF', true );
		mosMenuBar::spacer();
		mosMenuBar::custom( 'view_result_c', 'preview.png', 'preview_f2.png', JText::_('COM_SF_REPORT'), true );
		mosMenuBar::spacer();
		mosMenuBar::custom( 'del_rep_all', 'delete.png', 'delete_f2.png', JText::_('COM_SF_BACK'), false );
		mosMenuBar::spacer();
		mosMenuBar::deleteList('', 'del_rep', 'Delete'); 
		mosMenuBar::endTable();
	}
	function _VIEW_REP_SURVLIST() {
		mosMenuBar::startTable();
		mosMenuBar::custom( 'view_rep_surv', 'preview.png', 'preview_f2.png', JText::_('COM_SF_REPORT'), true );
		mosMenuBar::spacer();
		mosMenuBar::custom( 'reports', 'back.png', 'back_f2.png', JText::_('COM_SF_BACK'), false );
		mosMenuBar::endTable();
	}
	function _VIEW_ADVREP() {
		mosMenuBar::startTable();
		mosMenuBar::custom( 'view_advrep', 'preview.png', 'preview_f2.png', JText::_('COM_SF_REPORT'), false );
		mosMenuBar::endTable();
	}
	function _VIEW_REP_LISTSLIST() {
		mosMenuBar::startTable();
		mosMenuBar::custom( 'view_rep_list', 'preview.png', 'preview_f2.png', JText::_('COM_SF_REPORT'), true );
		mosMenuBar::spacer();
		mosMenuBar::custom( 'reports', 'back.png', 'back_f2.png', JText::_('COM_SF_BACK'), false );
		mosMenuBar::endTable();
	}
	function _SURVEYSLIST() {
		mosMenuBar::startTable();
		mosMenuBar::custom( 'preview_survey', 'preview.png', 'preview_f2.png', JText::_('COM_SF_PREVIEW'), true );
		mosMenuBar::spacer();
		mosMenuBar::divider();
		mosMenuBar::custom( 'show_results', 'preview.png', 'preview_f2.png', JText::_('COM_SF_VIEW_RESULTS'), true );
		mosMenuBar::spacer();
		mosMenuBar::divider();
		mosMenuBar::spacer();
		mosMenuBar::publishList('publish_surv');
		mosMenuBar::spacer();
		mosMenuBar::unpublishList('unpublish_surv');
		mosMenuBar::spacer();
		mosMenuBar::custom( 'move_surv_sel', 'move.png', 'move_f2.png', JText::_('COM_SF_MOVE'), true );
		mosMenuBar::spacer();
		mosMenuBar::custom( 'copy_surv_sel', 'copy.png', 'copy_f2.png', JText::_('COM_SF_COPY'), true );
		mosMenuBar::spacer();
		mosMenuBar::deleteList('', 'del_surv', JText::_('COM_SF_DELETE')); 
		mosMenuBar::spacer();
		mosMenuBar::editList('edit_surv');
		mosMenuBar::spacer();
		mosMenuBar::addNew('add_surv');
		mosMenuBar::spacer();
		mosMenuBar::divider();
		mosMenuBar::spacer();
		mosMenuBar::custom( 'categories', 'back.png', 'back_f2.png', JText::_('COM_SF_CATEGORIES'), false );
		mosMenuBar::endTable();
	}

	function _QUESTSLIST() {
		mosMenuBar::startTable();
		mosMenuBar::custom( 'move_quest_sel', 'move.png', 'move_f2.png', JText::_('COM_SF_MOVE'), true );
		mosMenuBar::spacer();
		mosMenuBar::custom( 'copy_quest_sel', 'copy.png', 'copy_f2.png', JText::_('COM_SF_COPY'), true );
		mosMenuBar::spacer();
		mosMenuBar::publishList('publish_quest');
		mosMenuBar::spacer();
		mosMenuBar::unpublishList('unpublish_quest');
		mosMenuBar::spacer();
		mosMenuBar::deleteList('', 'del_quest', JText::_('COM_SF_DELETE'));
		mosMenuBar::spacer();
		mosMenuBar::editList('edit_quest');
		mosMenuBar::spacer();
		mosMenuBar::divider();
		mosMenuBar::spacer();		
		mosMenuBar::custom('add_new_section','new.png','new_f2.png',JText::_('COM_SF_SECTION'),false);
		mosMenuBar::spacer();
		
		//mosMenuBar::custom('add_new','new.png','new_f2.png',"New",false);
		$bar = & JToolBar::getInstance('toolbar');
		$bar->appendButton( 'Popup', 'new', 'New', 'index.php?option=com_surveyforce&task=new_question_type&tmpl=component' );
		
		mosMenuBar::spacer();		
		mosMenuBar::divider();
		mosMenuBar::spacer();
		mosMenuBar::custom( 'surveys', 'back.png', 'back_f2.png', JText::_('COM_SF_SURVEYS'), false );
		mosMenuBar::endTable();
	}

	function _LISTSEDIT() {
		global $id;

		mosMenuBar::startTable();
		mosMenuBar::save('save_list');
		mosMenuBar::spacer();		
		if ( $id ) {
			mosMenuBar::cancel( 'cancel_list', JText::_('COM_SF_CLOSE') );
		} else {
			mosMenuBar::cancel('cancel_list');
		}
		mosMenuBar::endTable();
	}
	function _USERSEDIT() {
		global $id;

		mosMenuBar::startTable();
		mosMenuBar::save('save_user');
		mosMenuBar::spacer();
		mosMenuBar::apply('apply_user');
		mosMenuBar::spacer();		
		if ( $id ) {
			mosMenuBar::cancel( 'cancel_user', JText::_('COM_SF_CLOSE') );
		} else {
			mosMenuBar::cancel('cancel_user');
		}
		mosMenuBar::endTable();
	}
	function _EMAILSEDIT() {
		global $id;

		mosMenuBar::startTable();
		mosMenuBar::save('save_email');
		mosMenuBar::spacer();
		mosMenuBar::apply('apply_email');
		mosMenuBar::spacer();
		if ( $id ) {
			mosMenuBar::cancel( 'cancel_email', JText::_('COM_SF_CLOSE') );
		} else {
			mosMenuBar::cancel('cancel_email');
		}
		mosMenuBar::endTable();
	}
	
	function _EDITSECTION() {
		global $id;

		mosMenuBar::startTable();
		mosMenuBar::save('save_section');
		mosMenuBar::spacer();
		mosMenuBar::apply('apply_section');
		mosMenuBar::spacer();
		if ( $id ) {
			mosMenuBar::cancel( 'cancel_section', JText::_('COM_SF_CLOSE') );
		} else {
			mosMenuBar::cancel('cancel_section');
		}
		mosMenuBar::endTable();
	}

	function _QUESTSEDIT() {
		global $id;

		mosMenuBar::startTable();
		mosMenuBar::save('save_quest');
		mosMenuBar::spacer();
		mosMenuBar::apply('apply_quest');
		mosMenuBar::spacer();
		if ( $id ) {
			mosMenuBar::cancel( 'cancel_quest', JText::_('COM_SF_CLOSE') );
		} else {
			mosMenuBar::cancel('cancel_quest');
		}
		mosMenuBar::endTable();
	}
	function _ISCALESEDIT() {
		global $id;

		mosMenuBar::startTable();
		mosMenuBar::save('save_iscale');
		mosMenuBar::spacer();
		mosMenuBar::apply('apply_iscale');
		mosMenuBar::spacer();
		if ( $id ) {
			mosMenuBar::cancel( 'cancel_iscale', JText::_('COM_SF_CLOSE') );
		} else {
			mosMenuBar::cancel('cancel_iscale');
		}
		mosMenuBar::endTable();
	}
	function _ISCALESEDITA() {
		global $id;

		mosMenuBar::startTable();
		mosMenuBar::save('save_iscale_A', JText::_('COM_SF_OK'));
		mosMenuBar::spacer();
		mosMenuBar::cancel( 'cancel_iscale_A', JText::_('COM_SF_BACK') );
		mosMenuBar::endTable();
	}
	function _SURVEYSEDIT() {
		global $id;

		mosMenuBar::startTable();
		mosMenuBar::save('save_surv');
		mosMenuBar::spacer();
		mosMenuBar::apply('apply_surv');
		mosMenuBar::spacer();
		if ( $id ) {
			mosMenuBar::cancel( 'cancel_surv', JText::_('COM_SF_CLOSE') );
		} else {
			mosMenuBar::cancel('cancel_surv');
		}
		mosMenuBar::endTable();
	}function _CATSEDIT() {
		global $id;

		mosMenuBar::startTable();
		mosMenuBar::save('save_cat');
		mosMenuBar::spacer();
		mosMenuBar::apply('apply_cat');
		mosMenuBar::spacer();
		if ( $id ) {
			mosMenuBar::cancel( 'cancel_cat', JText::_('COM_SF_CLOSE') );
		} else {
			mosMenuBar::cancel('cancel_cat');
		}
		mosMenuBar::endTable();
	}

	
	
	function _EDIT() {
		global $id;

		mosMenuBar::startTable();
		mosMenuBar::save();
		mosMenuBar::spacer();
		mosMenuBar::apply();
		mosMenuBar::spacer();
		if ( $id ) {
			mosMenuBar::cancel( 'cancel', JText::_('COM_SF_CLOSE') );
		} else {
			mosMenuBar::cancel();
		}
		mosMenuBar::endTable();
	}
	
	function _QUESTMOVE() {
		mosMenuBar::startTable();
		mosMenuBar::save( 'move_quest_save' );
		mosMenuBar::spacer();
		mosMenuBar::cancel('cancel_quest');
		mosMenuBar::endTable();
	}
	function _QUESTCOPY() {
		mosMenuBar::startTable();
		mosMenuBar::save( 'copy_quest_save' );
		mosMenuBar::spacer();
		mosMenuBar::cancel('cancel_quest');
		mosMenuBar::endTable();
	}
	function _SURVMOVE() {
		mosMenuBar::startTable();
		mosMenuBar::save( 'move_surv_save' );
		mosMenuBar::spacer();
		mosMenuBar::cancel('cancel_surv');
		mosMenuBar::endTable();
	} 
	function _SURVCOPY() {
		mosMenuBar::startTable();
		mosMenuBar::save( 'copy_surv_save' );
		mosMenuBar::spacer();
		mosMenuBar::cancel('cancel_surv');
		mosMenuBar::endTable();
	} 
	function _USERMOVE() {
		mosMenuBar::startTable();
		mosMenuBar::save( 'move_user_save' );
		mosMenuBar::spacer();
		mosMenuBar::cancel('cancel_user');
		mosMenuBar::endTable();
	} 	
	function _USERCOPY() {
		mosMenuBar::startTable();
		mosMenuBar::save( 'copy_user_save' );
		mosMenuBar::spacer();
		mosMenuBar::cancel('cancel_user');
		mosMenuBar::endTable();
	}
	function _VIEWCONFIG() {
		mosMenuBar::startTable();
		mosMenuBar::save( 'save_config' );
		mosMenuBar::endTable();
	}
	function _SETDEFAULT() {
		mosMenuBar::startTable();
		mosMenuBar::save( 'save_default' );
		mosMenuBar::spacer();
		mosMenuBar::cancel('cancel_default');
		mosMenuBar::endTable();
	}
	function _VIEWAUTHORS() {
		mosMenuBar::startTable();
		mosMenuBar::deleteList('', 'del_author', JText::_('COM_SF_DELETE')); 
		mosMenuBar::spacer();
		mosMenuBar::addNew('add_author');
		mosMenuBar::endTable();
	}
	function _ADDAUTHORS() {
		mosMenuBar::startTable();		
		mosMenuBar::custom( 'save_author', 'addusers.png', 'addusers.png', JText::_('COM_SF_ADD'), true );
		mosMenuBar::spacer();
		mosMenuBar::cancel('cancel_author');
		mosMenuBar::endTable();
	}
	function _CROSSREPORT() {
		mosMenuBar::startTable();
		mosMenuBar::custom( 'cross_rep_pdf', 'preview.png', 'preview_f2.png', 'PDF', false );
		mosMenuBar::spacer();
		mosMenuBar::custom( 'cross_rep_csv', 'preview.png', 'preview_f2.png', 'CSV', false );
		mosMenuBar::spacer();
		mosMenuBar::custom( 'cancel_cross', 'back.png', 'back_f2.png', JText::_('COM_SF_REPORTS'), false );
		mosMenuBar::endTable();
	}
	
	function _MENU_MANAGER() {
		mosMenuBar::startTable();
		mosMenuBar::save('save_menus');
		mosMenuBar::endTable();
	}
	
	function _SHOW_RESULTS() {
		mosMenuBar::startTable();
		mosMenuBar::custom( 'surveys', 'back.png', 'back_f2.png', JText::_('COM_SF_BACK'), false );
		mosMenuBar::endTable();
	}
	
	function _LICENSE_PRESENTATION(){
		//null
	}
	
}
?>