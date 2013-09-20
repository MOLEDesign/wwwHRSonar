<?php 
/**
* Survey Force component for Joomla
* @version $Id: default.php 2009-11-16 17:30:15
* @package Survey Force
* @subpackage default.php
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );

global $sf_constants, $sf_js_constants;

$sf_js_constants = array('COMPLETE_DRAG_AND_DROP',
'COMPLETE_SHORT_ANSWER',
'COMPLETE_PICK_ONE',
'COMPLETE_PICK_MANY',
'COMPLETE_DROP_DOWN',
'COMPLETE_RANK' ,
'COMPLETE_LIKERT',
'COMPLETE_DRAG_AND_DROP',
'COMPLETE_IMPORTANT_SCALE',
'SURVEY_LOAD_DATA',
'SURVEY_FAILED',
'SURVEY_INVITED_COMPLETE',
'SURVEY_REG_COMPLETE',
'SURVEY_PUB_COMPLETE',
'SURVEY_PLEASE_WAIT',
'SESSION_TIMED_OUT',
'SURVEY_FAILED_REQUEST',
'SF_ALREADY_COMPLETED_REG',
'SURVEY_UNKNOWN_ERROR',
'SURVEY_NEXT_QUEST',
'SURVEY_PREV_QUEST',
'SURVEY_SUBMIT_SURVEY',
'SF_ALERT_ENTER_CAT_NAME',
'SF_ALERT_SELECT_ITEM',
'SF_ARE_SURE_TO_DELETE',
'SF_SURV_DESCRIPTION'
);

$sf_constants = array(
	'COMPONENT_HEADER'
	,'SURVEY_DONE'
	,'SURVEY_DROP_DOWN_FIRST_ELEMENT'
	,'SURVEY_FINISHED'
	,'SURVEY_NOT_AVAIL'
	,'SURVEY_START'
	,'SURVEY_RANK_FIRST_ELEMENT'
	,'SF_CATEGORIES'
	,'SF_SURVEYS'
	,'SF_USERGROUPS'
	,'SF_MOVE'
	,'SF_COPY'
	,'SF_SAVE'
	,'SF_APPLY'
	,'SF_BACK'
	,'SF_CANCEL'
	,'SF_DELETE'
	,'SF_EDIT'
	,'SF_NEW'
	,'SF_CAT_LIST'
	,'SF_FILTER'
	,'SF_NAME'
	,'SF_CAT_DESCRIPTION'
	,'SF_EDIT_CAT'
	,'SF_NEW_CAT'
	,'SF_CAT_DETAILS'
	,'SF_DESCRIPTION'
	,'SF_SURV_LIST'
	,'SF_ACTIVE'
	,'SF_CATEGORY'
	,'SF_AUTHOR'
	,'SF_PUBLIC'
	,'SF_FOR_INVITED'
	,'SF_FOR_REG'
	,'SF_FOR_REG_FULL'
	,'SF_FOR_FRIENDS'
	,'SF_EXPIRED_ON'
	,'SF_PUBLISHED'
	,'SF_UNPUBLISHED'
	,'SF_PUBLISH'
	,'SF_UNPUBLISH'
	,'SF_EDIT_SURVEY'
	,'SF_NEW_SURVEY'
	,'SF_SURVEY_DETAILS'
	,'SF_IMAGE'
	,'SF_LANGUAGE'
	,'SF_FINAL_PAGE'
	,'SF_SHOW_THIS'
	,'SF_SHOW_RESULTS'
	,'SF_SHOW_SCORE_RESULTS'
	,'SF_MOVE_SURVEY'
	,'SF_COPY_SURVEY'
	,'SF_COPYMOVE_TO'
	,'SF_SURVEYS_BEING'
	,'SF_THIS_WILL_COPYMOVE'
	,'SF_LIST_QUESTS'
	,'SF_NEW_QUEST'
	,'SF_SURVEY'
	,'SF_NEW_SECTION'
	,'SF_NEW_QUESTION'
	,'SF_TEXT'
	,'SF_REORDER'
	,'SF_ORDER'
	,'SF_TYPE'
	,'SF_QUEST_TEXT'
	,'SF_IMP_SCALE_NOT_DEF'
	,'SF_QUEST_RANK'
	,'SF_EDIT_SEC'
	,'SF_NEW_SEC'
	,'SF_SEC_DETAILS'
	,'SF_QUESTIONS'
	,'SF_ORDERING'
	,'SF_EDIT_QUEST'
	,'SF_SHORT_ANSWER'
	,'SF_QUEST_DETAILS'
	,'SF_IMP_SCALE'
	,'SF_DEFINE_NEW'
	,'SF_COMPULSORY'
	,'SF_INSERT_PAGE_BREAK'
	,'SF_BOILERPLATE'
	,'SF_ALERT_ENTER_TEXT'
	,'SF_LIKERT_SCALE'
	,'SF_PICK_ONE'
	,'SF_PICK_MANY'
	,'SF_USE_PREDEF_SCALE'
	,'SF_DEF_SCALE'
	,'SF_SCALE_OPTION'
	,'SF_ADD'
	,'SF_QUEST_RULES'
	,'SF_ANSWER'
	,'SF_QUESTION'
	,'SF_QUEST_OPTION'
	,'SF_IF_ANS_IS'
	,'SF_GO_TO_QUEST'
	,'SF_SET_DEFAULT'
	,'SF_RANK_DROPDOWN'
	,'SF_RANK_DRAGDROP'
	,'SF_ALT_NAME'
	,'SF_MOVE_QUEST'
	,'SF_COPY_QUEST'
	,'SF_COPYMOVE_TO_SURVEY'
	,'SF_QUEST_BEING_COPYMOVE'
	,'SF_THIS_WILL_COPYMOVE_QUESTS'
	,'SF_SET_DEF_ANSWERS'
	,'SF_NEW_IMP_SCALE'
	,'SF_IMP_SCALE_DETAILS'
	,'SF_SELECT_SURVEY'
	,'SF_SELECT_IMP_SCALE'
	,'SF_SELECT_LIKERT_SCALE'
	,'SF_SELECT_CATEGORY'
	,'SF_YES'
	,'SF_NO'
	,'SF_NEW_ITEM'
	,'SF_ALERT_QUEST_MUST_HAVE_TEXT'
	,'SF_ALERT_IMP_SCALE_MUST_HAVE'
	,'SF_ALERT_SEC_MUST_HAVE_NAME'
	,'SF_FOR_USER_IN_LISTS'
	,'SF_USER_LISTS'
	,'SF_USERS'
	,'SF_STARTS'
	,'SF_CREATED'
	,'SF_VIEWUSERS'
	,'SF_EMAIL'
	,'SF_USERNAME'
	,'SF_LAST_VISIT'
	,'SF_ADD_MANUALLY'
	,'SF_ADD_LMS_GROUP'
	,'SF_LIST_NAME'
	,'SF_LIST_DETAILS'
	,'SF_LIST_OF_USER'
	,'SF_ADD_USERS'
	,'SF_ALERT_LIST_MUST_HAVE_NAME'
	,'SF_REPORTS'
	,'SF_REPORT'
	,'SF_PREVIEW'
	,'SF_CROSS_REPORT'
	,'SF_CSV_REPORT'
	,'SF_PDF_REPORT'
	,'SF_REP_SURVEYS'
	,'SF_CSV_REPORT_SUM'
	,'SF_CHOOSE_FROM_QUEST'
	,'SF_WHERE_THE_ANSWER'
	,'SF_DATE'
	,'SF_STATUS'
	,'SF_USERTYPE'
	,'SF_USER_INFO'
	,'SF_COMPLETED'
	,'SF_NOT_COMPLETED'
	,'SF_GUEST'
	,'SF_REGISTERED_USER'
	,'SF_INVITED_USER'
	,'SF_ANONYMOUS'
	,'SF_RESULTS'
	,'SF_SURVEY_INFORMATION'
	,'SF_START_AT'
	,'SF_USER'
	,'SF_TOTAL_INVITED'
	,'SF_TOTAL_STARTS'
	,'SF_TOTAL_COMPLETES'
	,'SF_TOTAL_STRST_GUEST'
	,'SF_TOTAL_STRAT_REG'
	,'SF_TOTAL_START_INVITED'
	,'SF_TOTAL_COMPL_GUEST'
	,'SF_TOTAL_COMPL_REG'
	,'SF_TOTAL_COMPL_INVITED'
	,'SF_REPORT_DETAILS'
	,'SF_SEL_COL_QUEST'
	,'SF_SEL_QUEST_INCLUDED'
	,'SF_FROM_DATE'
	,'SF_TO_DATE'
	,'SF_INCLUDE_COMPL'
	,'SF_INCLUDE_INCOMPL'
	,'SF_GET_REP_IN'
	,'SF_ACROBAT_PDF'
	,'SF_EXCEL_CSV'
	,'SF_CROSS_REP_NOT_CREATED'
	,'SF_CSV_REP_SEL_SURVEY'
	,'SF_INCLUDE_IMP_SCALE'
	,'SF_YOU_VAN_SET_DEFAULT_AFTER_SAVING'
	,'SF_AUTO_INSERT_PB'
	,'SF_IF_FOR'
	,'SF_ANSWER_IS'
	,'SF_PRIORITY'
	,'SF_PRIORITY_C'
	,'SF_YOU_CAN_DEFINE_RULES_AFTER_SAVE'
	,'SF_SELECT_SECTION'
	,'SF_SECTION'
	,'SF_AUTO_PB_IS_ON'
	,'SF_AUTO_PB'
	,'SF_SURVEY_RESULTS'
	,'SF_VIEW_SURVEY_RESULTS'
	,'SF_PROGRESS'
	,'SF_SELECT_RANK'
	,'SF_RANKING'
	,'SF_RANKS'
	,'SF_RANK'
	,'SF_RANK_IS'
	,'SF_OTHER_OPTION'
	,'SF_FACTOR_NAME'
	,'SF_SHOW_PROGRESS'
	,'SF_SHORT_ANS_TOOLTIP'
	,'SF_USE_CSS'
	,'SF_GO_TO_QUEST21'
	,'SF_GO_TO_QUEST22'
	,'SF_USE_DROP_DOWN'
	,'SF_SELECT_ANS'
	,'SF_OTHER_ANSWER'
	,'SF_DONT_SHOW'
	,'SF_FOR_QUESTION'
	,'SF_AND_OPTION'
	,'SF_PLEASE_WAIT'
	,'SF_ENABLE_DESCR'
	,'SF_VOTING'
	,'SF_MULTIPLE_VOTING'
	,'SF_ONCE_VOTING'
	,'SF_ONCE_VOTING_REPLACE'
	,'SF_ALLOW_EDIT_ANSWERS'
	,'SF_INVITE'
	,'SF_REMAIND'
	,'SF_CR_EMAIL'
	,'BODY'
	,'REPLY_TO'
	,'SUBJECT'
	,'EDIT_EMAIL'
	,'NEW_EMAIL'
	,'EMAIL_MUST_NAME'
	,'EMAIL_MUST_BODY'
	,'EMAIL_MUST_REMAIL'
	,'EMAIL_MUST_VALID'
	,'INVITE_USERS'
	,'REMIND_USERS'
	,'CANNOT_CHECK_ANYMORE'
	,'SF_TEMPLATE'
	,'SF_CONTROL'
	,'SF_NONE'
	,'SF_IP_ADDR'
	,'SF_COOKIE'
	,'SF_BOTH'
	,'SF_USER_CHOICE'
	,'SF_SCALE'
	,'SF_SHORT_DESCRIPTION'
	,'SURVEY_CATEGORY'
	,'SURVEY_SURVEY'
	,'SURVEY_NO_ANSWER'
	,'SURVEY_NOT_RANKED'
	,'SURVEY_AFTER_START'
	,'SURVEY_AS_SHOW_MES'
	,'SURVEY_AS_SHOW_RES'
	,'SF_RANDOM_ORDER'
	,'SF_RANDOM_ORDER1'
	,'SF_RANDOM_ORDER2'
	,'SF_RANDOM_ORDER3'
	,'SF_RANDOM_WARNING'
	,'SF_OPTIONS'
	,'SF_ACCESS'
	,'SF_VIEW_QUESTIONS'
	,'SF_HELP'
);


$GLOBALS['sf_lang'] = array();
global $sf_lang;

$sf_lang['null'] = '';

?>