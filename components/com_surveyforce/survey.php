<?php
/**
* Survey Force component for Joomla
* @version $Id: survey.php 2009-11-16 17:30:15
* @package Survey Force
* @subpackage survey.php
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/


// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );

function SF_process_ajax($sf_task,$limit=0,$count=0,$survey_id,$pagination ) {
	@ob_start();
	$ret_str = '';
	switch ($sf_task) {
		case 'start':			$ret_str = SF_StartSurvey();		break;
		case 'next':			$ret_str = SF_NextQuestion();		break;
		case 'prev':			$ret_str = SF_PrevQuestion();		break;
        case 'result':			$ret_str = get_final_result($limit,$count,$survey_id,$pagination);		break;
		default:	break;
	}
	$iso = explode( '=', _ISO );
	echo "\n".date('Y-m-d H:i:s');
	$debug_str = ob_get_contents();
	
	@ob_end_clean();
	@ob_end_clean();
	if ($ret_str != "") {
		header ('Expires: Fri, 14 Mar 1980 20:53:00 GMT');
		header ('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
		header ('Cache-Control: no-cache, must-revalidate');
		header ('Pragma: no-cache');
		header ('Content-Type: text/xml');
		echo '<?xml version="1.0" encoding="'.$iso[1].'" standalone="yes"?>';
		echo '<response>' . "\n";
		echo $ret_str;
		echo "\t" . '<debug><![CDATA['.$debug_str.'&nbsp;]]></debug>' . "\n";
		echo '</response>' . "\n";
	}
	else {
		header ('Expires: Fri, 14 Mar 1980 20:53:00 GMT');
		header ('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
		header ('Cache-Control: no-cache, must-revalidate');
		header ('Pragma: no-cache');
		header ('Content-Type: text/xml');
		echo '<?xml version="1.0" encoding="'.$iso[1].'" standalone="yes"?>';
		echo '<response>' . "\n";
		echo "\t" . '<task>failed</task>' . "\n";
		echo "\t" . '<quest_count>0</quest_count>' . "\n";
		echo "\t" . '<info>boom</info>' . "\n";
		echo "\t" . '<debug><![CDATA['.$debug_str.'&nbsp;]]></debug>' . "\n";
		echo '</response>' . "\n";
	}
}

function SF_StartSurvey() {
	global $database, $mosConfig_offset, $my, $sf_lang, $mosConfig_absolute_path;
	$ret_str = '';
	$preview = intval( mosGetParam( $_REQUEST, 'preview', 0 ) );
	$survey_id = intval( mosGetParam( $_REQUEST, 'survey', 0 ) );
	$query = "SELECT * FROM #__survey_force_survs WHERE id = '".$survey_id."'";
	$database->SetQuery ($query );
	$survey = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
	$survey = $survey[0];
	$sf_config = new mos_Survey_Force_Config( );
	
	$friends = array();
	if ($sf_config->get('sf_enable_jomsocial_integration')) { 
		$query = "SELECT j.connect_to FROM #__community_connection AS j WHERE j.status = 1 AND j.connect_from = '{$survey->sf_author}'";
		$database->SetQuery( $query );
		$friends = $database->LoadResultArray();
	}

	$auto_pb = $survey->sf_auto_pb;
	$invite_num = strval(mosGetParam($_REQUEST, 'invite', ''));
	$now = _CURRENT_SERVER_TIME;
	$special = false;

	if (!$preview) { 
		if ( ($survey->published) && ($survey->sf_date == '0000-00-00 00:00:00' || intval(strtotime($survey->sf_date)) >= intval(strtotime($now))) ) {
			if ( ($my->id) && ($survey->sf_reg) ) {
				//null;
			} elseif (($my->id) && ($survey->sf_friend) && $sf_config->get('sf_enable_jomsocial_integration') && in_array($my->id, $friends) ) {
				//null;
			} elseif ($my->id == $survey->sf_author) {
				//null;
			} elseif ($survey->sf_public) {
				//null;
			} elseif ($survey->sf_invite && ($invite_num != '')) {
				$query = "SELECT inv_status FROM #__survey_force_invitations WHERE invite_num = '". $invite_num."'";
				$database->SetQuery($query);
				$inv_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
				if (count($inv_data) == 1) {
					if($inv_data[0]->inv_status != 1) {
						// Continue
					} elseif ($inv_data[0]->inv_status == 1 && $survey->sf_inv_voting == 1) {
						// Invitation completed
						if ($survey->sf_after_start) {
							$query = "SELECT a.id FROM #__survey_force_user_starts AS a, #__survey_force_invitations AS b WHERE b.invite_num = '". $invite_num."' AND b.id = a.invite_id ORDER BY a.id DESC";
							$database->SetQuery($query);
							$inv_start_id = $database->loadResult();
							$ret_str .= get_graph_results ($survey_id, $inv_start_id);
						}
						$ret_str .= "\t" . '<task>invite_complete</task>' . "\n";
						$ret_str .= "\t" . '<quest_count>0</quest_count>' . "\n";				
						return $ret_str;
					}
				} else { 
					return $ret_str;
				}
			} elseif (($my->id > 0) && ($survey->sf_special > 0)) {
				$query = "SELECT DISTINCT b.id FROM #__survey_force_users AS a, #__users AS b "
						."\n WHERE a.list_id IN ({$survey->sf_special}) AND b.id = '{$my->id}' "
						."\n AND a.name = b.username AND a.email = b.email AND a.lastname = b.name ";
				$database->SetQuery( $query );
				
				if (intval($database->LoadResult()) < 1 ) {
					if (SF_GetUserType($my->id,$survey->id) != 1 && SF_GetUserType($my->id,$survey->id) != 2)
						return $ret_str;
				}
				$special = true;
			} else {
				return $ret_str;
			}
		} else {
			return $ret_str;
		}
		
		$invite_num = strval( mosGetParam( $_REQUEST, 'invite', '' ) );
		$surv_usertype = 0;
		$surv_user_id = 0;
		$surv_invite_id = 0;
		if ($my->id) {
			$surv_usertype = 1;
			$surv_user_id = $my->id;
		}
		
		if ($survey->sf_anonymous) {
			$surv_usertype = 0;
			$surv_user_id = 0;
		}
		$invited_survey = false;
		if ($invite_num != '') {
			$query = "SELECT inv_status, user_id, id FROM #__survey_force_invitations WHERE invite_num = '".$invite_num."'";
			$database->SetQuery($query);
			$inv_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
			if (($inv_data[0]->inv_status == 1 || ($survey->sf_anonymous && $inv_data[0]->inv_status == 3)) && $survey->sf_inv_voting == 1) {
				if ($survey->sf_after_start && !$survey->sf_anonymous) {
							$query = "SELECT a.id FROM #__survey_force_user_starts AS a, #__survey_force_invitations AS b WHERE b.invite_num = '". $invite_num."' AND b.id = a.invite_id ORDER BY a.id DESC";
							$database->SetQuery($query);
							$inv_start_id = $database->loadResult();
							$ret_str .= get_graph_results ($survey_id, $inv_start_id);
						}
				$ret_str .= "\t" . '<task>invite_complete</task>' . "\n";
				$ret_str .= "\t" . '<quest_count>0</quest_count>' . "\n";
				return $ret_str;
			}
			$surv_usertype = 2;
			$surv_invite_id = $inv_data[0]->id;
			$surv_user_id = $inv_data[0]->user_id;
			if ($survey->sf_anonymous)
				$query = "UPDATE #__survey_force_invitations SET inv_status = 3 WHERE invite_num = '".$invite_num."'";
			else
				$query = "UPDATE #__survey_force_invitations SET inv_status = 2 WHERE invite_num = '".$invite_num."'";
			$database->SetQuery($query);
			$database->query();
			$invited_survey = true;
			if ($survey->sf_anonymous) {
				$surv_usertype = 0;
				$surv_user_id = 0;
				$surv_invite_id = 0;
			}
		}
	
		if (($my->id > 0) && ($survey->sf_reg_voting == 1)) {
			$query = "SELECT id FROM `#__survey_force_user_starts` WHERE survey_id = {$survey_id} AND user_id = '".$my->id."' AND is_complete = 1 ORDER BY id DESC";
			$database->SetQuery($query);
			$reg_start_id = $database->LoadResult();
			if ($reg_start_id > 0) {
				if ($survey->sf_after_start && !$survey->sf_anonymous) {
					$ret_str .= get_graph_results ($survey_id, $reg_start_id);
				}
				$ret_str .= "\t" . '<task>reg_complete</task>' . "\n";
				$ret_str .= "\t" . '<quest_count>0</quest_count>' . "\n";
				return $ret_str;
			}
		}	

		if (($my->id) && ($survey->sf_friend_voting == 1) && ($survey->sf_friend) && $sf_config->get('sf_enable_jomsocial_integration') && in_array($my->id, $friends)) {
			$query = "SELECT id FROM `#__survey_force_user_starts` WHERE survey_id = {$survey_id} AND user_id = '".$my->id."' AND is_complete = 1 ORDER BY id DESC";
			$database->SetQuery($query);
			$reg_start_id = $database->LoadResult();
			if ($reg_start_id > 0) {
				if ($survey->sf_after_start && !$survey->sf_anonymous) {
					$ret_str .= get_graph_results ($survey_id, $reg_start_id);
				}
				$ret_str .= "\t" . '<task>reg_complete</task>' . "\n";
				$ret_str .= "\t" . '<quest_count>0</quest_count>' . "\n";
				return $ret_str;
			}
		}
		
		if (($my->id < 1 || ($my->id > 0 && $survey->sf_anonymous)) && ($survey->sf_pub_control > 0) && ($survey->sf_pub_voting == 1) && !$invited_survey) {
			$ip = $_SERVER["REMOTE_ADDR"];
			$cookie = isset($_COOKIE[md5('survey'.$survey_id)])? $_COOKIE[md5('survey'.$survey_id)]: '';
			
			if ($survey->sf_pub_control == 1) {
				$query = "SELECT id FROM `#__survey_force_user_starts` WHERE survey_id = {$survey_id} AND user_id = '0' AND `sf_ip_address` = '{$ip}' AND is_complete = 1 ORDER BY id DESC";
			} elseif ($survey->sf_pub_control == 2) {
				$query = "SELECT id FROM `#__survey_force_user_starts` WHERE survey_id = {$survey_id} AND user_id = '0' AND `unique_id` = '{$cookie}' AND is_complete = 1 ORDER BY id DESC";
			} elseif ($survey->sf_pub_control == 3) {
				$query = "SELECT id FROM `#__survey_force_user_starts` WHERE survey_id = {$survey_id} AND user_id = '0' AND `unique_id` = '{$cookie}' AND `sf_ip_address` = '{$ip}' AND is_complete = 1 ORDER BY id DESC ";
			}
			
			$database->SetQuery($query);
			$pub_start_id = $database->LoadResult();
			if ($pub_start_id > 0) {
				if ($survey->sf_after_start) {
					$ret_str .= get_graph_results ($survey_id, $pub_start_id);
				}
				$ret_str .= "\t" . '<task>pub_complete</task>' . "\n";
				$ret_str .= "\t" . '<quest_count>0</quest_count>' . "\n";
				return $ret_str;
			}
		}
	}
	
	$is_edit_voting = 	($survey->sf_reg_voting == 3 && $my->id > 0) || 
						($survey->sf_inv_voting == 3 && $invite_num != '' ) ||
						($survey->sf_friend_voting == 3 && $my->id > 0 && $sf_config->get('sf_enable_jomsocial_integration') && in_array($my->id, $friends));
	
	if ($survey_id) {
		$usr_data = null;
		
		if (!$preview) { 
			if ($invite_num != '') {
				
				if ($survey->sf_inv_voting == 2) {
					$query = "SELECT id FROM #__survey_force_user_starts WHERE survey_id = {$survey_id} AND usertype = '".$surv_usertype."' AND invite_id = ".$surv_invite_id." AND user_id = '".$surv_user_id."'  AND is_complete = 1 ORDER BY id DESC";
					$database->SetQuery($query);
					$usr_starts = $database->LoadResultArray();
	
					$query = "DELETE FROM `#__survey_force_user_ans_txt` WHERE  start_id  IN (".implode(',', $usr_starts).")";
					$database->SetQuery($query);
					$database->query();
					$query = "DELETE FROM `#__survey_force_user_answers` WHERE  start_id  IN (".implode(',', $usr_starts).")";
					$database->SetQuery($query);
					$database->query();
					$query = "DELETE FROM `#__survey_force_user_answers_imp` WHERE  start_id  IN (".implode(',', $usr_starts).")";
					$database->SetQuery($query);
					$database->query();	
					$query = "DELETE FROM `#__survey_force_user_chain` WHERE  start_id  IN (".implode(',', $usr_starts).")";
					$database->SetQuery($query);
					$database->query();				
					$query = "DELETE FROM `#__survey_force_user_starts` WHERE survey_id = {$survey_id} AND usertype = '".$surv_usertype."' AND invite_id = ".$surv_invite_id." AND user_id = '".$surv_user_id."' AND is_complete = 1";
					$database->SetQuery($query);
					$database->query();			
				}
				
				if ($survey->sf_inv_voting == 3) {
					$survey_time = date( 'Y-m-d H:i:s', time()); 
					$query = "UPDATE `#__survey_force_user_starts` SET is_complete = 0, sf_time = '{$survey_time}' WHERE survey_id = $survey_id AND usertype = ".$surv_usertype." AND invite_id = ".$surv_invite_id." AND user_id = '".$surv_user_id."' AND is_complete = 1";
					$database->SetQuery($query);
					$database->query();		
				}
				
				$query = "SELECT * FROM #__survey_force_user_starts WHERE survey_id = $survey_id AND usertype = ".$surv_usertype." AND invite_id = ".$surv_invite_id." AND user_id = '".$surv_user_id."' AND is_complete = 0 ORDER BY id DESC";
				$database->SetQuery($query);
				$usr_data = $database->LoadObject();
			}
			elseif ($my->id > 0) {
				
				if (!$special && ($survey->sf_reg_voting == 2 || ($survey->sf_friend_voting==2&&$sf_config->get('sf_enable_jomsocial_integration')&&in_array($my->id, $friends)) )) {
				
					$query = "SELECT id FROM #__survey_force_user_starts WHERE survey_id = {$survey_id} AND user_id = '".$my->id."' AND is_complete = 1 ORDER BY id DESC";
					$database->SetQuery($query);
					$usr_starts = $database->LoadResultArray();
	
					$query = "DELETE FROM `#__survey_force_user_ans_txt` WHERE  start_id  IN (".implode(',', $usr_starts).")";
					$database->SetQuery($query);
					$database->query();
					
					$query = "DELETE FROM `#__survey_force_user_answers` WHERE  start_id  IN (".implode(',', $usr_starts).")";
					$database->SetQuery($query);
					$database->query();
					$query = "DELETE FROM `#__survey_force_user_answers_imp` WHERE  start_id  IN (".implode(',', $usr_starts).")";
					$database->SetQuery($query);
					$database->query();	
					$query = "DELETE FROM `#__survey_force_user_chain` WHERE  start_id  IN (".implode(',', $usr_starts).")";
					$database->SetQuery($query);
					$database->query();				
					$query = "DELETE FROM `#__survey_force_user_starts` WHERE survey_id = {$survey_id} AND user_id = '".$my->id."' AND is_complete = 1";
					$database->SetQuery($query);
					$database->query();
				}
				
				if (!$special && ($survey->sf_reg_voting == 3 || ($survey->sf_friend_voting==3&&$sf_config->get('sf_enable_jomsocial_integration')&&in_array($my->id, $friends))) ) {
					$survey_time = date( 'Y-m-d H:i:s', time()); 
					$query = "UPDATE `#__survey_force_user_starts` SET is_complete = 0, sf_time = '{$survey_time}' WHERE survey_id = $survey_id AND user_id = '".$my->id."' AND is_complete = 1";
					$database->SetQuery($query);
					$database->query();		
				}			
				
				$query = "SELECT * FROM #__survey_force_user_starts WHERE survey_id = $survey_id AND user_id = '".$my->id."' AND is_complete = 0 ORDER BY id DESC";
				$database->SetQuery($query);
				$usr_data = $database->LoadObject();
				
			} elseif (($my->id < 1 || ($my->id > 0 && $survey->sf_anonymous)) && $survey->sf_pub_control > 0 ) {
				$ip = $_SERVER["REMOTE_ADDR"];
				$cookie = isset($_COOKIE[md5('survey'.$survey_id)])? $_COOKIE[md5('survey'.$survey_id)]: '';
				
				if ($survey->sf_pub_voting == 2) {
					if ($survey->sf_pub_control == 1) {
						$query = "SELECT id FROM `#__survey_force_user_starts` WHERE survey_id = {$survey_id} AND user_id = '0' AND `sf_ip_address` = '{$ip}' AND is_complete = 1  ORDER BY id DESC";
					} elseif ($survey->sf_pub_control == 2) {
						$query = "SELECT id FROM `#__survey_force_user_starts` WHERE survey_id = {$survey_id} AND user_id = '0' AND `unique_id` = '{$cookie}' AND is_complete = 1  ORDER BY id DESC";
					} elseif ($survey->sf_pub_control == 3) {
						$query = "SELECT id FROM `#__survey_force_user_starts` WHERE survey_id = {$survey_id} AND user_id = '0' AND `unique_id` = '{$cookie}' AND `sf_ip_address` = '{$ip}' AND is_complete = 1  ORDER BY id DESC";
					}
					$database->SetQuery($query);
					$usr_starts = $database->LoadResultArray();
	
					$query = "DELETE FROM `#__survey_force_user_ans_txt` WHERE  start_id  IN (".implode(',', $usr_starts).")";
					$database->SetQuery($query);
					$database->query();
					$query = "DELETE FROM `#__survey_force_user_answers` WHERE  start_id  IN (".implode(',', $usr_starts).")";
					$database->SetQuery($query);
					$database->query();
					$query = "DELETE FROM `#__survey_force_user_answers_imp` WHERE  start_id  IN (".implode(',', $usr_starts).")";
					$database->SetQuery($query);
					$database->query();				
					$query = "DELETE FROM `#__survey_force_user_chain` WHERE  start_id  IN (".implode(',', $usr_starts).")";
					$database->SetQuery($query);
					$database->query();	
					$query = "DELETE FROM `#__survey_force_user_starts` WHERE survey_id = {$survey_id} AND id  IN (".implode(',', $usr_starts).") AND is_complete = 1";
					$database->SetQuery($query);
					$database->query();
				}
				
				if ($survey->sf_pub_control == 1) {
						$query = "SELECT * FROM `#__survey_force_user_starts` WHERE survey_id = {$survey_id} AND user_id = '0' AND `sf_ip_address` = '{$ip}' AND is_complete = 0  ORDER BY id DESC";
					} elseif ($survey->sf_pub_control == 2) {
						$query = "SELECT * FROM `#__survey_force_user_starts` WHERE survey_id = {$survey_id} AND user_id = '0' AND `unique_id` = '{$cookie}' AND is_complete = 0  ORDER BY id DESC";
					} elseif ($survey->sf_pub_control == 3) {
						$query = "SELECT * FROM `#__survey_force_user_starts` WHERE survey_id = {$survey_id} AND user_id = '0' AND `unique_id` = '{$cookie}' AND `sf_ip_address` = '{$ip}' AND is_complete = 0  ORDER BY id DESC";
					}
	
				$database->SetQuery($query);
				$usr_data = $database->LoadObject();
			}
		}//if not preview

		$last_page_quest_id = 0;
		if ($usr_data == null) {
			$user_unique_id = md5(uniqid(rand(), true));				
				
			$survey_time = date( 'Y-m-d H:i:s', time()); 
			$query = "INSERT INTO #__survey_force_user_starts (unique_id, usertype, user_id, invite_id, sf_time, survey_id, is_complete, sf_ip_address) "
			. "\n VALUES ('".$user_unique_id."', '".$surv_usertype."', '".$surv_user_id."', '".$surv_invite_id."', '".$survey_time."', '".$survey_id."', 0, '".$_SERVER["REMOTE_ADDR"]."')";
			$database->SetQuery($query);

			$database->query();
			$start_id = $database->insertid();
			
			if ($preview) {
				$query = "INSERT INTO `#__survey_force_previews` SET `start_id` = '{$start_id}', `time` = '".time()."', `survey_id` = '{$survey_id}', `unique_id` = '{$user_unique_id}'";
				$database->SetQuery($query);
				$database->query();
				
				$query = "DELETE FROM `#__survey_force_user_starts` WHERE `id` = '{$start_id}'";
				$database->SetQuery($query);
				$database->query();	
			} else {			
				setcookie(md5('survey'.$survey_id), $user_unique_id, time() + 31536000);
			}
			
			$sf_chain = create_chain($survey_id);
			$query = "INSERT INTO `#__survey_force_user_chain` SET `start_id` = '{$start_id}', `sf_time` = '".time()."', `survey_id` = '{$survey_id}', `unique_id` = '{$user_unique_id}', `sf_chain` = '{$sf_chain}', `invite_id` = '{$surv_invite_id}'";
			$database->SetQuery($query);
			$database->query();
			
			$query = " SELECT * FROM #__survey_force_quests WHERE published = 1 AND sf_survey = '".$survey_id."' ".($auto_pb?" AND sf_qtype <> 8 ":'')." ORDER BY ordering, id ";
			$database->SetQuery($query);
			$q_data = ($database->LoadObjectList('id') == null? array(): $database->LoadObjectList('id'));
			
			$ret_str .= "\t" . '<user_id>'.$user_unique_id.'</user_id>' . "\n";
			$ret_str .= "\t" . '<start_id>'.$start_id.'</start_id>' . "\n";
			$ret_str .= "\t" . '<is_resume>0</is_resume>' . "\n";
			
			if ($sf_chain){
				$n = 0;
				$last_page_quest_id =  0;
				$pages = explode('*#*', $sf_chain);
				$questions = explode('*', $pages[0]);
				foreach($questions as $question) {
					if (isset($q_data[$question])) {
						$ret_str .= "\t" . '<question_data>' . "\n";
						$ret_str .= SF_GetQuestData($q_data[$question], $start_id);
						$ret_str .= "\t" . '</question_data>' . "\n";
						$last_page_quest_id = $question;
						$n++;
					}
				}
				
				if ( $n > 0 ) {
					$page_task = 'start';
					if ($last_page_quest_id) {		
						$questions = explode('*', $sf_chain);				
						if (end($questions) == $last_page_quest_id) {
							$page_task = 'start_last_question';
						}												
					}
					
					$ret_str .= "\t" . '<task>'.$page_task.'</task>' . "\n";
					$ret_str .= "\t" . '<quest_count>'.$n.'</quest_count>' . "\n";
						
					$ret_str .= "\t" . '<progress_bar>0%</progress_bar>' . "\n";
					$ret_str .= "\t" . '<progress_bar_txt><![CDATA['.$sf_lang['SF_PROGRESS'] .' 0%]]></progress_bar_txt>' . "\n";
				} else {
					$ret_str = '';
				}
				
			} else {
				$ret_str = '';
			}
		}
		else { 
			$user_unique_id = $usr_data->unique_id;
			setcookie(md5('survey'.$survey_id), $user_unique_id, time() + 31536000);
			$start_id = $usr_data->id;
			$query = "SELECT a.quest_id FROM #__survey_force_user_answers AS a, #__survey_force_quests AS b WHERE b.published = 1 AND b.id = a.quest_id AND survey_id = '$survey_id' AND start_id = '$start_id' ORDER BY b.ordering DESC, b.id DESC ";		
			$database->SetQuery($query);
			
			$quest_ids = $database->loadResultArray();		
			
			
			$sf_chain = create_chain($survey_id);
			$query = "UPDATE `#__survey_force_user_chain` SET `sf_chain` = '{$sf_chain}' WHERE `start_id` = '{$start_id}' AND `survey_id` = '{$survey_id}' AND `unique_id` = '{$user_unique_id}'";
			$database->SetQuery($query);
			$database->query();
			// we create new chain - may be new quest added, or paging changed
			//$query = "SELECT sf_chain FROM #__survey_force_user_chain WHERE start_id = '$start_id' ";
			//$database->SetQuery($query);
			//$sf_chain = $database->loadResult();
			
			$chain_questions = explode('*', str_replace('*#*', '*', $sf_chain));
			
			$query = "SELECT a.quest_id FROM `#__survey_force_quest_show` AS a, #__survey_force_user_answers AS b, #__survey_force_quests c WHERE a.survey_id = '".$survey_id."' AND c.id = a.quest_id_a AND ((c.sf_qtype NOT IN (2, 3) AND a.answer = b.answer AND a.ans_field = b.ans_field) OR (c.sf_qtype IN (2, 3) AND a.answer = b.answer)) AND b.start_id = '".$start_id."' ";
			$database->SetQuery($query);

			$not_shown = $database->LoadResultArray();
			if (!count($not_shown))
				$not_shown = array(0);
				
			if ($survey->sf_random) {
				$not_shown = array(0);				
			}
			
			$chain_questions = array_diff($chain_questions, $not_shown);
			
			$quest_id = $chain_questions[0];
			foreach($chain_questions as $c=>$chain_question){
				if (in_array($chain_question, $quest_ids)) {
					$quest_id = (isset($chain_questions[$c+1])? $chain_questions[$c+1]: $chain_question);
				}
			}
			
			if (($my->id && $survey->sf_reg_voting == 3) || ($survey->sf_inv_voting == 3 && $invite_num != '') || ($my->id&&$survey->sf_friend_voting==3&&$sf_config->get('sf_enable_jomsocial_integration')&&in_array($my->id, $friends))) {				
				$quest_id = 0;
			}

			$query = " SELECT * FROM #__survey_force_quests WHERE published = 1 AND sf_survey = '".$survey_id."' ".($auto_pb?" AND sf_qtype <> 8 ":'')." AND id IN ('".@implode("','", $chain_questions)."') ORDER BY ordering, id ";
			$database->SetQuery($query);
			$q_data = ($database->LoadObjectList('id') == null? array(): $database->LoadObjectList('id'));
			$task = 0;

			
			$ret_str .= "\t" . '<user_id>'.$user_unique_id.'</user_id>' . "\n";
			$ret_str .= "\t" . '<start_id>'.$start_id.'</start_id>' . "\n";
			
			$tmp = 0;
			$tmp_str = '';
			$first_pb = 0;
			$first_quest_id = -1;

			$pages = explode('*#*', clear_chain($sf_chain, $not_shown));
			foreach($pages as $p=>$page) {
				$questions = explode('*', $page);
				$questions = array_diff($questions, $not_shown);
				if (in_array($quest_id, $questions)) {
					foreach($questions as $question) {
						if (isset($q_data[$question])) {
							if ($first_quest_id == -1) $first_quest_id = $question;
							$tmp_str .= "\t" . '<question_data>' . "\n";
							$tmp_str .= SF_GetQuestData($q_data[$question], $start_id);
							$tmp_str .= "\t" . '</question_data>' . "\n";
							$last_page_quest_id = $question;
							$tmp++;
						}
					}
					$first_pb = $p;
				}
			}
			
			if ($is_edit_voting && $tmp == 0) {
				if (count($pages) > 0) {
					$tmp = 0;
					
					$questions = explode('*', $pages[0]);
					$questions = array_diff($questions, $not_shown);
					foreach($questions as $question) {
						if (isset($q_data[$question])) {
							if ($first_quest_id == -1) $first_quest_id = $question;
							$ret_str .= "\t" . '<question_data>' . "\n";
							$ret_str .= SF_GetQuestData($q_data[$question], $start_id);
							$ret_str .= "\t" . '</question_data>' . "\n";
							$last_page_quest_id = $question;
							$tmp++;
						}
					}
			
				}
				$first_pb = 0;
			}
			
			if ($first_pb > 0 )
				$task = 1;
			
				
			$ret_str .= "\t" . '<is_resume>'.$task.'</is_resume>' . "\n";

			if ( $tmp > 0 ) {
				
				$nn = 0;
				
				if ($survey->sf_progressbar_type == '0') {
					foreach($chain_questions as $chain_question) {
						if ($chain_question == $first_quest_id)
							break;
						$nn++;
					}
					
					$nn = floor(100*$nn/count($q_data));
					
				} elseif ($survey->sf_progressbar_type == '1') {
					$pages = explode('*#*', clear_chain($sf_chain, $not_shown));
					foreach($pages as $p=>$page) {
						$questions = explode('*', $page);
						if (in_array($first_quest_id, $questions))
							break;
						$nn++;
					}
					$nn = floor(100*$nn/count($pages));
				}				
				
				$page_task = 'start';
				if ($last_page_quest_id) {			
									
					if (end($chain_questions) == $last_page_quest_id) {
						if (!$task) {
							$page_task = 'start_last_question';
						} else {
							$page_task = 'last_question';
						}
					}	
				}	
				
				$ret_str .= $tmp_str;
				$ret_str .= "\t" . '<task>'.$page_task.'</task>' . "\n";
				$ret_str .= "\t" . '<quest_count>'.$tmp.'</quest_count>' . "\n";
				
				$ret_str .= "\t" . '<progress_bar>'.$nn.'%</progress_bar>' . "\n";
				$ret_str .= "\t" . '<progress_bar_txt><![CDATA['.$sf_lang['SF_PROGRESS'] .' '.(int)$nn.'%]]></progress_bar_txt>' . "\n";
				
			} else {
				$ret_str = '';		
			}
		}		
	}
	return $ret_str;
}


function SF_NextQuestion($limit=0, $page=0) {
	global $database, $mosConfig_offset, $my, $mosConfig_live_site, $mosConfig_absolute_path, $sf_lang, $mosConfig_mailfrom,  $mosConfig_sitename;
	$ret_str = '';
	$query = "SELECT id FROM #__components WHERE link LIKE '%sf_score%' ";
	$database->setQuery( $query );
	$is_surveyforce_score = false;
	if ($database->LoadResult()) 
		$is_surveyforce_score = true; 
	
	$preview = intval( mosGetParam( $_REQUEST, 'preview', 0 ) );
	
	$sf_config = new mos_Survey_Force_Config( );	

	$survey_id = intval( mosGetParam( $_REQUEST, 'survey', 0 ) );	// id of the survey from 'survs' table
	$invite_num = strval(mosGetParam($_REQUEST, 'invite', ''));
	$query = "SELECT * FROM #__survey_force_survs WHERE id = '".$survey_id."'";
	$database->SetQuery ($query );
	$survey = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
	$survey = $survey[0];
	
	$friends = array();
	if ($sf_config->get('sf_enable_jomsocial_integration')) { 
		$query = "SELECT j.connect_to FROM #__community_connection AS j WHERE j.status = 1 AND j.connect_from = '{$survey->sf_author}'";
		$database->SetQuery( $query );
		$friends = $database->LoadResultArray();
	}

	$auto_pb = $survey->sf_auto_pb;
	$now = _CURRENT_SERVER_TIME;
	if (!$preview) {
		if ( ($survey->published) && ($survey->sf_date == '0000-00-00 00:00:00' || intval(strtotime($survey->sf_date)) >= intval(strtotime($now))) ) {
			if ( ($my->id) && ($survey->sf_reg) ) {
			
			} elseif (($my->id) && ($survey->sf_friend) && $sf_config->get('sf_enable_jomsocial_integration') && in_array($my->id, $friends) ) {
			
			} elseif ($my->id == $survey->sf_author) {
			
			} elseif ($survey->sf_public) {
			
			} elseif (($my->id > 0) && ($survey->sf_special > 0)) {
			
			} elseif ($survey->sf_invite && ($invite_num != '')) {
				$query = "SELECT inv_status FROM #__survey_force_invitations WHERE invite_num = '". $invite_num."'";
				$database->SetQuery($query);
				$inv_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
				if (count($inv_data) == 1) {
					if($inv_data[0]->inv_status != 1) {
						// Continue
					} elseif ($inv_data[0]->inv_status == 1 && $survey->sf_inv_voting == 1) {
						// Invitation completed
						if ($survey->sf_after_start) {
							$query = "SELECT a.id FROM #__survey_force_user_starts AS a, #__survey_force_invitations AS b WHERE b.invite_num = '". $invite_num."' AND b.id = a.invite_id ORDER BY a.id DESC";
							$database->SetQuery($query);
							$inv_start_id = $database->loadResult();
							$ret_str .= get_graph_results ($survey_id, $inv_start_id);
						}
						$ret_str .= "\t" . '<task>invite_complete</task>' . "\n";
						return $ret_str;
					}
				} else {
					//bad $invite_num
					return $ret_str;
				}
			} else {
				if ( (!$my->id) && ($survey->sf_reg || $survey->sf_friend) ) {
					$ret_str .= "\t" . '<task>timed_out</task>' . "\n";
					$ret_str .=  "\t" . '<quest_count>0</quest_count>' . "\n";
				}			
				return $ret_str;
			}
		} else {
			return $ret_str;
		}
	}
	
	$user_id = strval( mosGetParam( $_REQUEST, 'user_id', '' ) );	// unique id from 'starts' table
	$start_id = intval( mosGetParam( $_REQUEST, 'start_id', 0 ) );	// id from 'starts' table	

	$quest_ids = mosGetParam( $_REQUEST, 'quest_id', array() );	// ids of the previous questions from the 'quests' table
	$answers =  mosGetParam( $_REQUEST, 'answer', array() );		// answers of the previous questions (for inserting into 'user_answers' table)
	$is_imp_scales = mosGetParam( $_REQUEST, 'is_imp_scale', array() ); //1 - if imp.scale answer is SET
	$imp_scale_choices = mosGetParam( $_REQUEST, 'imp_scale', array() );

	if (!_JOOMLA15) {
		for($hi=0, $hn=count($answers); $hi<$hn; $hi++) {	
			$answers[$hi] = urldecode($answers[$hi]);
		}
	}
		
	if (($survey_id) && ($user_id) && is_array($quest_ids) && ($start_id)) {
		if ($preview) {
			$query = "SELECT survey_id, unique_id FROM #__survey_force_previews WHERE `start_id` = '".$start_id."'";
		} else {
			$query = "SELECT survey_id, unique_id FROM #__survey_force_user_starts WHERE id = '".$start_id."'";
		}
		$database->SetQuery($query);
		$st_surv_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());

		$survey_time = date( 'Y-m-d H:i:s', time());

		$start_survey = $st_surv_data[0]->survey_id;
		$un_id = $st_surv_data[0]->unique_id;
		if ( ($survey_id == $start_survey) && ($user_id == $un_id) ) {		
			
			$query = "SELECT sf_chain FROM #__survey_force_user_chain WHERE start_id = '".$start_id."'";
			$database->SetQuery($query);
			$sf_chain = $database->LoadResult();
			$chain_questions = explode('*', str_replace('*#*', '*', $sf_chain));
			
			for($ii = 0, $nn = count($is_imp_scales); $ii < $nn; $ii++){
				$is_imp_scale = $is_imp_scales[$ii];
				$imp_scale_choice = $imp_scale_choices[$ii];
				$quest_id = $quest_ids[$ii];
				// write info to 'answer_imp.scale' table
				if ($is_imp_scale) {
					if (!$imp_scale_choice)
						$imp_scale_choice = 0;
					$query = "SELECT sf_impscale from #__survey_force_quests WHERE published = 1 AND id = '".$quest_id."'";
					$database->SetQuery( $query );
					$q_imp_scale = $database->LoadResult();
					if ($q_imp_scale) {
						$query = "SELECT count(*) from #__survey_force_iscales_fields WHERE id = '".$imp_scale_choice."' AND iscale_id = '".$q_imp_scale."'";
						$database->SetQuery( $query );
						$q_count_iscale = $database->LoadResult();
						if ($q_count_iscale == 1) {
							$query = "DELETE FROM #__survey_force_user_answers_imp WHERE start_id = '$start_id' AND survey_id = '$survey_id' AND quest_id = '$quest_id' AND iscale_id = '$q_imp_scale' ";
							$database->SetQuery($query);
							$database->query();
							
							$query = "INSERT INTO #__survey_force_user_answers_imp (start_id, survey_id, quest_id, iscale_id, iscalefield_id, sf_imptime) "
							. "\n VALUES ('".$start_id."', '".$survey_id."', '".$quest_id."', '".$q_imp_scale."', '".$imp_scale_choice."', '".$survey_time."')";
							$database->SetQuery($query);
							$database->query();
						}
					}
				}
			}
			$next_id = null;
			for($ii = 0, $nn = count($quest_ids); $ii < $nn; $ii++){
				$quest_id = $quest_ids[$ii];
				$answer = $answers[$ii];
				// get question type
				$query = "SELECT sf_qtype from #__survey_force_quests WHERE published = 1 AND id = '".$quest_id."'";
				$database->SetQuery( $query );
				$qtype = $database->LoadResult();				
				///////////////////////////////
				
				if ( $next_id == null && !$survey->sf_random ) {
					switch ($qtype) {
						case 1:
						case 5:
						case 6:
							$tmp_data = explode(',',$answer);
							$i = 0;
							$priority = 0;
							while ($i < count($tmp_data)) {
								$ttt = explode('-',$tmp_data[$i]);
								$query = "SELECT * FROM #__survey_force_rules WHERE quest_id = '".$quest_id."' AND ( (answer_id = ".(isset($ttt[0])? $ttt[0]: '0')." AND alt_field_id = ".(isset($ttt[1])? $ttt[1]: '0')." ) OR (answer_id = 9999997 AND alt_field_id = 9999997) ) ORDER BY priority DESC, id DESC LIMIT 0,1 ";
								$database->SetQuery($query);
								$rule_data = null;
								$rule_data = $database->LoadObject();
								if ( $rule_data != null && $rule_data->priority > $priority) {
									$next_id = $rule_data->next_quest_id;
									$priority = $rule_data->priority;
									if (in_array($next_id, $quest_ids)) {
										$next_id = null;				
									}					
								}
								$i++;
							}
							break;
						case 9:
							$tmp_data = explode('!!,!!',$answer);
							if (strpos($answer,'!!-,-!!') > 0) {								
								for($i = 0, $n = count($tmp_data); $i < $n; $i++){
									if (strpos($tmp_data[$i],'!!-,-!!') > 0) {
										$tmp = explode('!!-,-!!', $tmp_data[$i]);
										$tmp_data[$i] = $tmp[0];
										break;
									}
								}
							}
							
							$i = 0;
							$priority = 0;
							while ($i < count($tmp_data)) {
								$ttt = explode('!!--!!',$tmp_data[$i]);
								$query = "SELECT * FROM #__survey_force_rules WHERE quest_id = '".$quest_id."' AND ( (answer_id = ".(isset($ttt[0])? $ttt[0]: '0')." AND alt_field_id = ".(isset($ttt[1])? $ttt[1]: '0').") OR (answer_id = 9999997 AND alt_field_id = 9999997) ) ORDER BY priority DESC, id DESC LIMIT 0,1 ";
								$database->SetQuery($query);
								$rule_data = null;
								$rule_data = $database->LoadObject();
								if ( $rule_data != null && $rule_data->priority > $priority) {
									$next_id = $rule_data->next_quest_id;
									$priority = $rule_data->priority;
									if (in_array($next_id, $quest_ids)) {
										$next_id = null;				
									}									
								}
								$i++;
							}
							break;
						case 2:
							if (strpos($answer,'!!--!!') > 0) {
								$answer_id = explode('!!--!!', $answer);
								$answer_id = intval($answer_id[0]);
							}
							else
								$answer_id = $answer;								
							$query = "SELECT * FROM #__survey_force_rules WHERE quest_id = '".$quest_id."' AND ( answer_id = ".intval($answer_id)." OR answer_id = 9999997 )";
							$database->SetQuery($query);
							$rule_data = null;
							$rule_data = $database->LoadObject();							
							if ( $rule_data != null ) {
								$next_id = $rule_data->next_quest_id;
								if (in_array($next_id, $quest_ids)) 
									$next_id = null;
							}
							break;
						case 3:
							$answer_str = '';
							if (strpos($answer,'!!--!!') > 0) {
								$tmp_data = explode('!!,!!',$answer);
								foreach($tmp_data as $i=>$data) {
									if (strpos($data,'!!--!!') > 0) {
										$answer_id = explode('!!--!!', $answer);
										$answer_str .= intval($answer_id[0]).',';
									}
									else
										$answer_str .= intval($data).',';
								}
								$answer_str = substr($answer_str, 0, -1);  
							}
							else {
								$answer_str = str_replace('!!,!!', ',', $answer);
							}
							$query = "SELECT * FROM #__survey_force_rules WHERE quest_id = '".$quest_id."' AND answer_id IN ( ".$answer_str.", 9999997 ) ORDER BY priority DESC, id DESC LIMIT 0,1 ";
							$database->SetQuery($query);
							$rule_data = null;
							$rule_data = $database->LoadObject();
							if ( $rule_data != null ) {
								$next_id = $rule_data->next_quest_id;
								if (in_array($next_id, $quest_ids)) 
									$next_id = null;
							}
							break;	
					}
					
					if ($next_id != null) {						
						$quest_data  = $chain_questions;
						
						$nxt = 0;
						foreach($quest_data as $q_id) {
							if ($q_id != $quest_id && $nxt == 0) {
								continue;
							}
							elseif ($q_id == $quest_id && $nxt == 0) {
								$nxt = 1;
								continue;
							}
							if ($q_id == $next_id) {
								break;
							}
							if (!in_array($q_id, $quest_ids)) { //insert data only if question not on current page
								$query = "DELETE FROM #__survey_force_user_answers WHERE start_id = $start_id AND survey_id = $survey_id AND quest_id = ".$q_id;
								$database->SetQuery($query);
								$database->query();
								$query = "INSERT INTO #__survey_force_user_answers (start_id, survey_id, quest_id, answer, ans_field, next_quest_id, sf_time) "
								. "\n VALUES ('".$start_id."', '".$survey_id."', '".$q_id."', '0', '0', '0', '".$survey_time."')";
								$database->SetQuery($query);
								$database->query();
							}
							
						}
					}					
				}
				// insert results to the Database
				switch ($qtype) {
					case 1:
					case 5:
					case 6:
						$tmp_data = explode(',',$answer);
						$i = 0;
						$query = "DELETE FROM #__survey_force_user_answers WHERE start_id = $start_id AND survey_id = $survey_id AND quest_id = $quest_id ";
						$database->SetQuery($query);
						$database->query();
						while ($i < count($tmp_data)) {
							$ttt = explode('-',$tmp_data[$i]);
							$query = "INSERT INTO #__survey_force_user_answers (start_id, survey_id, quest_id, answer, ans_field, next_quest_id, sf_time) "
							. "\n VALUES ('".$start_id."', '".$survey_id."', '".$quest_id."', '".(isset($ttt[0])? $ttt[0]: '0')."', '".(isset($ttt[1])? $ttt[1]: '0')."', '".(int)$next_id."', '".$survey_time."')";
							$database->SetQuery($query);
							$database->query();
							$i++;
						}
					break;
					case 9:
						$tmp_data = explode('!!,!!',$answer);
						$other_id = -1;
						$other_txt = '';
						$atxt_id = 0;$atxt_id2 = 0;
						
						$query = "SELECT a.next_quest_id FROM #__survey_force_user_answers AS a WHERE a.start_id = $start_id AND a.survey_id = $survey_id AND a.quest_id = $quest_id AND a.answer > 0  AND a.next_quest_id > 0 ";
						$database->SetQuery($query);
						$answer_id = $database->loadResult();
						if ( $answer_id > 0 ) {
							$query = "DELETE FROM #__survey_force_user_ans_txt WHERE id = '".$answer_id."' AND start_id = $start_id ";
							$database->SetQuery($query);
							$database->query();
						}
						
						$query = "DELETE FROM #__survey_force_user_answers WHERE start_id = $start_id AND survey_id = $survey_id AND quest_id = $quest_id ";
						$database->SetQuery($query);
						$database->query();
						
						if (strpos($answer,'!!-,-!!') > 0) {								
							for($i = 0, $n = count($tmp_data); $i < $n; $i++){
								if (strpos($tmp_data[$i],'!!-,-!!') > 0) {
									$tmp = explode('!!-,-!!', $tmp_data[$i]);
									$tmp_data[$i] = $tmp[0];
									$other_txt = $tmp[1];
									$tmp = explode('!!--!!', $tmp[0]);
									$other_id = intval($tmp[0]);
									break;
								}
							}
							
							$query = "INSERT INTO #__survey_force_user_ans_txt (ans_txt, start_id) "
									. "\n VALUES ('".$other_txt."', '".$start_id."')";
							$database->SetQuery($query);
							$database->query();
							$atxt_id2 = $database->insertid();							
						}
						$i = 0;
						while ($i < count($tmp_data)) {
							$ttt = explode('!!--!!',$tmp_data[$i]);
							if ($other_id == (isset($ttt[0])? $ttt[0]: '0'))
								$atxt_id = $atxt_id2;
							$query = "INSERT INTO #__survey_force_user_answers (start_id, survey_id, quest_id, answer, ans_field, next_quest_id, sf_time) "
							. "\n VALUES ('".$start_id."', '".$survey_id."', '".$quest_id."', '".(isset($ttt[0])? $ttt[0]: '0')."', '".(isset($ttt[1])? $ttt[1]: '0')."', '".$atxt_id."', '".$survey_time."')";
							$database->SetQuery($query);
							$database->query();
							$atxt_id = 0;
							$i++;
						}
					break;
					case 2:
						$query = "SELECT ans_field FROM #__survey_force_user_answers WHERE start_id = $start_id AND survey_id = $survey_id AND quest_id = $quest_id ";
						$database->SetQuery($query);
						$answer_id = $database->loadResult();
						if ( $answer_id > 0 ) {
							$query = "DELETE FROM #__survey_force_user_ans_txt WHERE id = $answer_id AND start_id = $start_id ";
							$database->SetQuery($query);
							$database->query();
						}
						$query = "DELETE FROM #__survey_force_user_answers WHERE start_id = $start_id AND survey_id = $survey_id AND quest_id = $quest_id ";
						$database->SetQuery($query);
						$database->query();
						
						$tmp_txt = '';
						$atxt_id = 0;
						if (strpos($answer,'!!--!!') > 0) {
							$tmp_data = explode('!!--!!',$answer);							
							$tmp_txt = strval($tmp_data[1]);
							$tmp_data = intval($tmp_data[0]);
							if (strlen($tmp_txt) > 0) {
								$query = "INSERT INTO #__survey_force_user_ans_txt (ans_txt, start_id) "
								. "\n VALUES ('".$tmp_txt."', '".$start_id."')";
								$database->SetQuery($query);
								$database->query();
								$atxt_id = $database->insertid();
							}
							else {
								$tmp_data = 0;
							}
						}
						else
							$tmp_data = intval($answer);
						
						
						$query = "INSERT INTO #__survey_force_user_answers (start_id, survey_id, quest_id, answer, ans_field, next_quest_id, sf_time) "
						. "\n VALUES ('".$start_id."', '".$survey_id."', '".$quest_id."', '".$tmp_data."', '".$atxt_id."', '0', '".$survey_time."')";
						$database->SetQuery($query);
						$database->query();
					break;
					case 3:
						$tmp_data = explode('!!,!!',$answer);
						$i = 0;
						$query = "SELECT ans_field FROM #__survey_force_user_answers WHERE start_id = $start_id AND survey_id = $survey_id AND quest_id = $quest_id ";
						$database->SetQuery($query);
						$answer_id = $database->loadResult();
						if ( $answer_id > 0 ) {
							$query = "DELETE FROM #__survey_force_user_ans_txt WHERE id = $answer_id AND start_id = $start_id ";
							$database->SetQuery($query);
							$database->query();
						}
						$query = "DELETE FROM #__survey_force_user_answers WHERE start_id = $start_id AND survey_id = $survey_id AND quest_id = $quest_id ";
						$database->SetQuery($query);
						$database->query();
						
						while ($i < count($tmp_data)) {
							$tmp_txt = '';
							$atxt_id = 0;
							if (strpos($tmp_data[$i],'!!--!!') > 0) {
								$tmp_datas = explode('!!--!!',$tmp_data[$i]);
								$tmp_txt = strval($tmp_datas[1]);
								$tmp_datas = intval($tmp_datas[0]);
								
								if (strlen($tmp_txt) > 0) {
									$query = "INSERT INTO #__survey_force_user_ans_txt (ans_txt, start_id) "
									. "\n VALUES ('".$tmp_txt."', '".$start_id."')";
									$database->SetQuery($query);
									$database->query();
									$atxt_id = $database->insertid();
								}
								else {
									$tmp_datas = 0;
								}
							}
							else
								$tmp_datas = intval($tmp_data[$i]);				

									
							$query = "INSERT INTO #__survey_force_user_answers (start_id, survey_id, quest_id, answer, ans_field, next_quest_id, sf_time) "
							. "\n VALUES ('".$start_id."', '".$survey_id."', '".$quest_id."', '".$tmp_datas."', '".$atxt_id."', '0', '".$survey_time."')";
							$database->SetQuery($query);
							$database->query();
							$i++;
						}
					break;
					case 4:
						$query = "SELECT answer FROM #__survey_force_user_answers WHERE start_id = $start_id AND survey_id = $survey_id AND quest_id = $quest_id ";
						$database->SetQuery($query);
						$answer_id = $database->loadResultArray();
						if ( count($answer_id) > 0 ) {
							$query = "DELETE FROM #__survey_force_user_ans_txt WHERE id IN (".implode(',', $answer_id).") AND start_id = $start_id ";
							$database->SetQuery($query);
							$database->query();
							$query = "DELETE FROM #__survey_force_user_answers WHERE start_id = $start_id AND survey_id = $survey_id AND quest_id = $quest_id ";
							$database->SetQuery($query);
							$database->query();
						}
						$tmp_data = mysql_escape_string(urldecode($answer));
						if (strpos($answer,'!!--!!') > 0) {							
							$tmp_data = explode('!!,!!',$tmp_data);
							$i = 0;
							while ($i < count($tmp_data)) {
								$tmp_datas = explode('!!--!!',$tmp_data[$i]);
								$tmp_txt = strval($tmp_datas[1]);
								$tmp_datas = intval($tmp_datas[0]) + 1;
								
								if (strlen($tmp_txt) > 0) {
									$query = "INSERT INTO #__survey_force_user_ans_txt (ans_txt, start_id) "
									. "\n VALUES ('".$tmp_txt."', '".$start_id."')";
									$database->SetQuery($query);
									$database->query();
									$atxt_id = $database->insertid();
								}
								else
									$atxt_id = 0;
								
								$query = "INSERT INTO #__survey_force_user_answers (start_id, survey_id, quest_id, answer, ans_field, next_quest_id, sf_time) "
								. "\n VALUES ('".$start_id."', '".$survey_id."', '".$quest_id."', '".$atxt_id."', '".$tmp_datas."', '0', '".$survey_time."')";
								$database->SetQuery($query);
								$database->query();
								$i++;
							}
						}
						else {
							if (strlen($tmp_data) > 0) {
								$query = "INSERT INTO #__survey_force_user_ans_txt (ans_txt, start_id) "
								. "\n VALUES ('".$tmp_data."', '".$start_id."')";
								$database->SetQuery($query);
								$database->query();
								$atxt_id = $database->insertid();
							}
							else
								$atxt_id = 0;
								
							$query = "INSERT INTO #__survey_force_user_answers (start_id, survey_id, quest_id, answer, ans_field, next_quest_id, sf_time) "
							. "\n VALUES ('".$start_id."', '".$survey_id."', '".$quest_id."', '".$atxt_id."', 0, '0', '".$survey_time."')";
							$database->SetQuery($query);
							$database->query();
						}
					break;	
				}
			}
			
			
			
			$query = "SELECT a.quest_id FROM `#__survey_force_quest_show` AS a, #__survey_force_user_answers AS b, #__survey_force_quests c WHERE a.survey_id = '".$survey_id."' AND c.id = a.quest_id_a AND ((c.sf_qtype NOT IN (2, 3) AND a.answer = b.answer AND a.ans_field = b.ans_field) OR (c.sf_qtype IN (2, 3) AND a.answer = b.answer)) AND b.start_id = '".$start_id."' ";
			$database->SetQuery($query);

			$not_shown = $database->LoadResultArray();

			if (!count($not_shown))
				$not_shown = array(0);
				
			if ($survey->sf_random) {
				$not_shown = array(0);
				$next_id = null;
			}
			$chain_questions = array_diff($chain_questions, $not_shown);

			$not_shown_str = implode(',', $not_shown);
			$query = "SELECT * FROM #__survey_force_quests WHERE published = 1 AND sf_survey = '".$survey_id."' ".($auto_pb?" AND sf_qtype <> 8 ":'')." AND id IN ('".@implode("','", $chain_questions)."') AND id NOT IN (".$not_shown_str.") ORDER BY ordering, id ";			
			$database->SetQuery($query);
			$q_data = ($database->LoadObjectList('id') == null? array(): $database->LoadObjectList('id'));
			
			$query = "SELECT id FROM #__survey_force_quests WHERE published = 1 AND sf_survey = $survey_id AND id IN ( ".implode(', ', $quest_ids)." ) AND id IN ('".@implode("','", $chain_questions)."') ORDER BY ordering DESC, id DESC LIMIT 0 , 1";
			$database->SetQuery($query);
			$last_id = $database->loadResult();
			$tmp_str = '';
			$tmp = 0;
			$first_quest_id = -1;
			// id of last question that would displayed
			$last_page_quest_id = 0;
		
			if ($next_id == null) {		
				$pages = explode('*#*', clear_chain($sf_chain, $not_shown));
				foreach($pages as $p=>$page) {					
					$questions = explode('*', $page);
					$questions = array_diff($questions, $not_shown);
					if (!count($questions)) continue;
					
					if (in_array($last_id, $questions)) {
						$last_id = 0;
						continue;
					}
					
					if ($last_id == 0) {
						foreach($questions as $question) {
							if (isset($q_data[$question])) {
								if ($first_quest_id == -1) $first_quest_id = $question;
								$tmp_str .= "\t" . '<question_data>' . "\n";
								$tmp_str .= SF_GetQuestData($q_data[$question], $start_id);
								$tmp_str .= "\t" . '</question_data>' . "\n";
								$last_page_quest_id = $question;
								$tmp++;
							}
						}
						break;
					}
				}

			}
			else {
			
				$pages = explode('*#*', $sf_chain);
				foreach($pages as $p=>$page) {
					$questions = explode('*', $page);
					$questions = array_diff($questions, $not_shown);
					if (in_array($next_id, $questions)) {
						foreach($questions as $question) {
							if (isset($q_data[$question])) {
								if ($first_quest_id == -1) $first_quest_id = $question;
								$tmp_str .= "\t" . '<question_data>' . "\n";
								$tmp_str .= SF_GetQuestData($q_data[$question], $start_id);
								$tmp_str .= "\t" . '</question_data>' . "\n";
								$last_page_quest_id = $question;
								$tmp++;
							}
						}
						break;
					}
				}

							
			}
			
			if ($tmp > 0) {
				
				$nn = 0;
				
				if ($survey->sf_progressbar_type == '0') {
					foreach($chain_questions as $chain_question) {
						if ($chain_question == $first_quest_id)
							break;
						$nn++;
					}
					
					$nn = floor(100*$nn/count($q_data));				
				} elseif ($survey->sf_progressbar_type == '1') {
					$pages = explode('*#*', clear_chain($sf_chain, $not_shown));
					foreach($pages as $p=>$page) {
						$questions = explode('*', $page);
						if (in_array($first_quest_id, $questions))
							break;
						$nn++;
					}
					$nn = floor(100*$nn/count($pages));
				}
				
				$page_task = 'next';
				if ($last_page_quest_id) {					
					if (end($chain_questions) == $last_page_quest_id) {
						$page_task = 'last_question';
					}					
				
				}	
				
					 
			
				$ret_str .= "\t" . '<task>'.$page_task.'</task>' . "\n";
				$ret_str .= "\t" . '<quest_count>'.$tmp.'</quest_count>' . "\n";
				
				$ret_str .= "\t" . '<progress_bar>'.$nn.'%</progress_bar>' . "\n";
				$ret_str .= "\t" . '<progress_bar_txt><![CDATA['.$sf_lang['SF_PROGRESS'] .' '.(int)$nn.'%]]></progress_bar_txt>' . "\n";
				$ret_str .= $tmp_str;
			} else {
			
			
				$ret_str .= "\t" . '<task>finish</task>' . "\n";
				$ret_str .= "\t" . '<quest_count>0</quest_count>' . "\n";
				$query = "SELECT usertype, invite_id FROM #__survey_force_user_starts WHERE id = '".$start_id."' and unique_id = '".$user_id."'";
				$database->SetQuery($query);
				$surv_start_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
				if (isset($surv_start_data[0]) && $surv_start_data[0]->usertype == 2) {
					if($surv_start_data[0]->invite_id) {
						$query = "UPDATE #__survey_force_invitations SET inv_status = 1 WHERE id = '".$surv_start_data[0]->invite_id."'";
						$database->SetQuery($query);
						$database->query();
					}
				}
				$query = "UPDATE #__survey_force_user_starts SET is_complete = 1 WHERE id = '".$start_id."' and unique_id = '".$user_id."'";
				$database->SetQuery($query);
				$database->query();
				
				$query = "UPDATE #__survey_force_user_starts SET is_complete = 1 WHERE id = '".$start_id."' and unique_id = '".$user_id."'";
				$database->SetQuery($query);
				$database->query();
				
				$query = "SELECT `a`.`sf_fpage_type`, `a`.`sf_fpage_text`, `b`.`email` FROM `#__survey_force_survs` AS `a` LEFT JOIN `#__users` AS `b` ON `a`.`sf_author` = `b`.`id` WHERE `a`.`id` = '$survey_id' ";
				$database->SetQuery($query);
				$fpage = null;
				$fpage = $database->loadObject();
				$ret_str .= "\t" . '<fpage_type>'.$fpage->sf_fpage_type.'</fpage_type>' . "\n";
				
				if (!$preview) {					
					if ($sf_config->get('sf_an_mail') || $sf_config->get('sf_an_mail_others')) {
						$message = get_user_result($start_id);
						$message = $sf_config->get('sf_an_mail_text') . " \n\n " . $message;
						
						$subject = '[SURVEY] '.$sf_config->get('sf_an_mail_subject');
						
						$emails = explode(',', $sf_config->get('sf_an_mail_other_emails'));
						if (!count($emails))
							$emails = array();
						
						
						if ($sf_config->get('sf_an_mail') && isset($fpage->email) && $fpage->email) {
							$emails[] = $fpage->email;
						}
						
						if (count($emails))
						foreach($emails as $email)
							mosMail( $mosConfig_mailfrom,  $mosConfig_sitename, $email , $subject, $message, 1); 
					} 
				}
				

                $fpage_text = get_final_result(1,20,$survey_id,0);
                $ret_str .= "\t" . '<fpage_text><![CDATA['.stripslashes($fpage_text).'&nbsp;]]></fpage_text>' . "\n";

				if (file_exists(JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'userpoints.php')) {
					include_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'userpoints.php');
					
					CuserPoints::assignPoint("completed.survey".$survey_id);					
				}

			}
		}
	}
	return $ret_str;
}

function get_final_result($limit, $count, $survey_id, $pagination)
{
    global $database, $mosConfig_offset, $my, $mosConfig_live_site, $mosConfig_absolute_path, $sf_lang, $mosConfig_mailfrom, $mosConfig_sitename;
    $ret_str = '';
    $user_id = strval(mosGetParam($_REQUEST, 'user_id', '')); // unique id from 'starts' table
    $start_id = intval(mosGetParam($_REQUEST, 'start_id', 0)); // id from 'starts' table

    $quest_ids = mosGetParam($_REQUEST, 'quest_id', array()); // ids of the previous questions from the 'quests' table
    $answers = mosGetParam($_REQUEST, 'answer', array()); // answers of the previous questions (for inserting into 'user_answers' table)
    $is_imp_scales = mosGetParam($_REQUEST, 'is_imp_scale', array()); //1 - if imp.scale answer is SET
    $imp_scale_choices = mosGetParam($_REQUEST, 'imp_scale', array());

    $query = "SELECT id FROM #__components WHERE link LIKE '%sf_score%' ";
    $database->setQuery($query);
    $is_surveyforce_score = false;
    if ($database->LoadResult())
        $is_surveyforce_score = true;


    $query = "SELECT `a`.`sf_fpage_type`, `a`.`sf_fpage_text`, `b`.`email` FROM `#__survey_force_survs` AS `a` LEFT JOIN `#__users` AS `b` ON `a`.`sf_author` = `b`.`id` WHERE `a`.`id` = '$survey_id' ";
    $database->SetQuery($query);
    $fpage = null;
    $fpage = $database->loadObject();
    $ret_str = $fpage->sf_fpage_type;

    if ($fpage->sf_fpage_type == 0) {
        $ret_str = $fpage->sf_fpage_text;
    }
    elseif ($fpage->sf_fpage_type == 1) {
        $query = "SELECT id FROM #__survey_force_quests WHERE published = 1 AND sf_survey = '" . $survey_id . "' ORDER BY ordering, id ";

        $database->SetQuery($query);
        $questions = $database->loadResultArray();
        //$query = "SELECT id FROM #__survey_force_quests WHERE published = 1 AND sf_survey = '" . $survey_id . "' AND sf_qtype<>4 AND sf_qtype<>3 AND sf_qtype<>7 ORDER BY ordering, id ";
        $database->SetQuery($query);
        $questions_all = $database->loadResultArray();
        set_time_limit(0);
        require ($mosConfig_absolute_path . '/components/com_surveyforce/generate.surveyforce.php');

        $sf_config = new mos_Survey_Force_Config();
        $prefix = $sf_config->get('sf_result_type') == 'Bar' ? 'b' : 'p';
        $gg = new sf_ImageGenerator(array($sf_config->get('sf_result_type')));
        $gg->width = $sf_config->get($prefix . '_width');
        $gg->height = $sf_config->get($prefix . '_height');
        $gg->clearOldImages(); //delete yesterday images
        $imgs = array();


        $fpage_text = '<p align="left"><strong>' . $sf_lang['SF_SURVEY_RESULTS'] . '</strong></p><br/>';


        foreach ($questions as $question) {
            $img_src = $gg->getImage($survey_id, $question, $start_id);
            if (is_array($img_src)) {
                foreach ($img_src as $imgsrc) {
                    $imgs[] = $imgsrc;
                }
            }
            elseif ($img_src) {
                $imgs[] = $img_src;
            }
        }

        $img_per_page = $count;
        $limitstart = $limit;
        $total = count($imgs);

        $ret_pagination = "<div class='pagination-surv' style='border-top: 1px solid #CCCCCC;margin: 10px 0 5px;
            padding: 10px 0 0;
            text-align: center;
            width: 100%;'>";
        $pages = ceil($total / $img_per_page);
        $page = 1;

        if ($pages > 1) {
            if ($limitstart == 1)
                $ret_pagination .= '&nbsp;&nbsp;' . JText::_('COM_SURVEY_FIRST') . '&nbsp;&nbsp;';
            else
                $ret_pagination .= '&nbsp;&nbsp;<a href="javascript: pagination_go(1,' . $survey_id . ')">' . JText::_('COM_SURVEY_FIRST') . '</a>&nbsp;&nbsp;';
            for ($i = 0; $i < $pages; $i++) {
                if ($limitstart >= ($i * $img_per_page) && $limitstart < ($i + 1) * $img_per_page) {
                    $ret_pagination .= ($i + 1) . '&nbsp;&nbsp;';
                    $page = $i + 1;
                } else {
                    if ($i == 0) {
                        $ret_pagination .= '<a href="javascript: pagination_go(1,' . $survey_id . ')">' . ($i + 1) . '</a>&nbsp;&nbsp;';
                    } else
                        $ret_pagination .= '<a href="javascript: pagination_go(' . $i * $img_per_page . ',' . $survey_id . ')">' . ($i + 1) . '</a>&nbsp;&nbsp;';
                }
            }

            if ($limitstart == $img_per_page * ($pages - 1))
                $ret_pagination .= JText::_('COM_SURVEY_LAST');
            else
                $ret_pagination .= '<a href="javascript: pagination_go(' . $img_per_page * ($pages - 1) . ',' . $survey_id . ')">' . JText::_('COM_SURVEY_LAST') . '</a>';
        }
        $ret_pagination .= "</div>";


        $i = 0;
        if ($limit == 1) {
            for ($i = 0; $i <= $count - 1; $i++) {
                $fpage_text .= $imgs[$i];
            }
        } else {
            for ($i = $limit; $i <= $limit - 1 + $count; $i++) {
                if (isset($imgs[$i])) {
                    $fpage_text .= $imgs[$i];
                }
            }
        }
        $fpage_text .= $ret_pagination;

        if ($fpage_text == '<p align="left"><strong>' . $sf_lang['SF_SURVEY_RESULTS'] . '</strong></p><br/>')
            $fpage_text = 'No graphs available.';
        $ret_str = $fpage_text;
    }
    else {
        $ret_str = '<strong>End of the survey - Thank you for your time.</strong>';
    }
    if ($pagination) {
        echo $ret_str;
        die;
    } else {
        return $ret_str;
    }
}

function SF_PrevQuestion() {
	global $database, $mosConfig_offset, $my, $sf_lang, $mosConfig_absolute_path;
	$ret_str = '';
	$preview = intval( mosGetParam( $_REQUEST, 'preview', 0 ) );
	$survey_id = intval( mosGetParam( $_REQUEST, 'survey', 0 ) );	// id of the survey from 'survs' table
	$invite_num = strval(mosGetParam($_REQUEST, 'invite', ''));
	$query = "SELECT * FROM #__survey_force_survs WHERE id = '".$survey_id."'";
	$database->SetQuery ($query );
	$survey = null;
	$survey = $database->LoadObject();
	
	$sf_config = new mos_Survey_Force_Config( );
	
	$friends = array();
	if ($sf_config->get('sf_enable_jomsocial_integration')) { 
		$query = "SELECT j.connect_to FROM #__community_connection AS j WHERE j.status = 1 AND j.connect_from = '{$survey->sf_author}'";
		$database->SetQuery( $query );
		$friends = $database->LoadResultArray();
	}
	

	$auto_pb = $survey->sf_auto_pb;
	$user_id = strval( mosGetParam( $_REQUEST, 'user_id', '' ) );	// unique id from 'starts' table
	$start_id = intval( mosGetParam( $_REQUEST, 'start_id', 0 ) );	// id from 'starts' table

	$quest_ids = mosGetParam( $_REQUEST, 'quest_id', array());	// id of the previous question from the 'quests' table
	
	
	$now = _CURRENT_SERVER_TIME;
	if (!$preview) {
		if ( ($survey->published) && ($survey->sf_date == '0000-00-00 00:00:00' || intval(strtotime($survey->sf_date)) >= intval(strtotime($now))) ) {
			if ( ($my->id) && ($survey->sf_reg) ) {
			
			} elseif (($my->id) && ($survey->sf_friend) && $sf_config->get('sf_enable_jomsocial_integration') && in_array($my->id, $friends) ) {
			
			} elseif ($my->id == $survey->sf_author) {
			
			} elseif ($survey->sf_public) {
			
			} elseif (($my->id > 0) && ($survey->sf_special > 0)) {
			
			} elseif ($survey->sf_invite && ($invite_num != '')) {
				$query = "SELECT inv_status FROM #__survey_force_invitations WHERE invite_num = '". $invite_num."'";
				$database->SetQuery($query);
				$inv_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
				if (count($inv_data) == 1) {
					if($inv_data[0]->inv_status != 1) {
						// Continue
					} elseif ($inv_data[0]->inv_status == 1 && $survey->sf_inv_voting == 1) {
						// Invitation completed
						if ($survey->sf_after_start) {
							$query = "SELECT a.id FROM #__survey_force_user_starts AS a, #__survey_force_invitations AS b WHERE b.invite_num = '". $invite_num."' AND b.id = a.invite_id ORDER BY a.id DESC";
							$database->SetQuery($query);
							$inv_start_id = $database->loadResult();
							$ret_str .= get_graph_results ($survey_id, $inv_start_id);
						}
						$ret_str .= "\t" . '<task>invite_complete</task>' . "\n";
						return $ret_str;
					}
				} else {
					//bad $invite_num
					return $ret_str;
				}
			} else {
				if ( (!$my->id) && ($survey->sf_reg || $survey->sf_friend) ) {
					$ret_str .= "\t" . '<task>timed_out</task>' . "\n";
					$ret_str .=  "\t" . '<quest_count>0</quest_count>' . "\n";
				}			
				return $ret_str;
			}
		} else {
			return $ret_str;
		}
	}
	
	
	if (($survey_id) && ($user_id) && is_array($quest_ids) && ($start_id)) { 
		if ($preview) {
			$query = "SELECT survey_id, unique_id FROM #__survey_force_previews WHERE `start_id` = '".$start_id."'";
		} else {
			$query = "SELECT survey_id, unique_id FROM #__survey_force_user_starts WHERE id = '".$start_id."'";
		}
		
		$database->SetQuery($query);
		$st_surv_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());

		$survey_time = date( 'Y-m-d H:i:s', time());

		$start_survey = $st_surv_data[0]->survey_id;
		$un_id = $st_surv_data[0]->unique_id;
		
		$query = "SELECT sf_chain FROM #__survey_force_user_chain WHERE start_id = '".$start_id."'";
		$database->SetQuery($query);
		$sf_chain = $database->LoadResult();
		$chain_questions = explode('*', str_replace('*#*', '*', $sf_chain));
		
		if ( ($survey_id == $start_survey) && ($user_id == $un_id) ) {
			$prev_id = null;
			
			$query = "SELECT a.quest_id FROM `#__survey_force_quest_show` AS a, #__survey_force_user_answers AS b, #__survey_force_quests c WHERE a.survey_id = '".$survey_id."' AND c.id = a.quest_id_a AND ((c.sf_qtype NOT IN (2, 3) AND a.answer = b.answer AND a.ans_field = b.ans_field) OR (c.sf_qtype IN (2, 3) AND a.answer = b.answer)) AND b.start_id = '".$start_id."' ";
			$database->SetQuery($query);

			$not_shown = $database->LoadResultArray();
			if (!count($not_shown))
				$not_shown = array(0);
				
			if ($survey->sf_random) {
				$not_shown = array(0);				
			}
			
			$chain_questions = array_diff($chain_questions, $not_shown);
			
			$not_shown_str = implode(',', $not_shown);
			
			$query = "SELECT * FROM #__survey_force_quests WHERE published = 1 AND sf_survey = '".$survey_id."' ".($auto_pb?" AND sf_qtype <> 8 ":'')."  AND id NOT IN ( $not_shown_str ) AND id IN ('".@implode("','", $chain_questions)."') ORDER BY ordering, id ";
			$database->SetQuery($query);
			$q_data = ($database->LoadObjectList('id') == null? array(): $database->LoadObjectList('id'));
			
			$query = "SELECT id FROM #__survey_force_quests WHERE published = 1 AND sf_survey = $survey_id AND id IN ( ".implode(', ', $quest_ids)." ) AND id IN ('".@implode("','", $chain_questions)."')  AND id NOT IN ( $not_shown_str ) ORDER BY ordering ASC, id ASC LIMIT 0 , 1";
			$database->SetQuery($query);
			$first_id2 = $database->loadResult();
			
			$query = "SELECT ordering FROM #__survey_force_quests WHERE published = 1 AND sf_survey = $survey_id AND id = '$first_id2'  AND id NOT IN ( $not_shown_str ) ORDER BY ordering ASC, id ASC LIMIT 0 , 1";
			$database->SetQuery($query);
			$first_id_order = $database->loadResult();
			
			$query = "SELECT id FROM #__survey_force_quests WHERE published = 1 AND sf_survey = $survey_id AND id <> '$first_id2' AND id IN ('".@implode("','", $chain_questions)."') AND id NOT IN ( $not_shown_str ) AND ordering <= $first_id_order AND sf_qtype <> 8 ORDER BY ordering DESC, id ASC LIMIT 0 , 1";
			$database->SetQuery($query);
			$first_id = $database->loadResult();
			
									
			$prev_id = null;
			$query = " SELECT c.id "
					." FROM #__survey_force_rules AS a, #__survey_force_user_answers AS b, #__survey_force_quests AS c "
					." WHERE  c.published = 1 AND a.next_quest_id IN (". implode(',', $quest_ids) .") "
					." AND b.start_id = '$start_id' AND b.survey_id = '$survey_id' AND b.quest_id = a.quest_id  AND (b.answer = a.answer_id OR (a.answer_id = 9999997 AND b.next_quest_id IN (". implode(',', $quest_ids) ."))) AND ( b.ans_field = a.alt_field_id OR (a.alt_field_id = 9999997 AND b.next_quest_id IN (". implode(',', $quest_ids) .") ) OR c.sf_qtype IN ( 2, 3 ) ) "
					." AND c.id = a.quest_id  AND c.id NOT IN ( $not_shown_str ) AND c.id IN ('".@implode("','", $chain_questions)."')"
					." ORDER BY a.priority DESC, a.id DESC, c.ordering, c.id ";
			
			$database->SetQuery($query);
			
			$prev_id = $database->loadResult();
			
			$tmp = 0;
			$tmp_str = '';
			$first_pb = 0;
			$last_pb = 0;
			$first_real_quest = 0;
			$first_quest_id = 0;
			$first_quest_id = -1;
			
			$page_no = 0;
			if ($prev_id == null) {
				
				$pages = explode('*#*', clear_chain($sf_chain, $not_shown));
				foreach($pages as $p=>$page) {
					$questions = explode('*', $page);
					if (in_array($quest_ids[0], $questions) && isset($pages[$p-1])) {
						$questions = explode('*', $pages[$p-1]);
						$questions = array_diff($questions, $not_shown);
						foreach($questions as $question) {
							if (isset($q_data[$question])) {
								if ($first_quest_id == -1) $first_quest_id = $question;
								$tmp_str .= "\t" . '<question_data>' . "\n";
								$tmp_str .= SF_GetQuestData($q_data[$question], $start_id);
								$tmp_str .= "\t" . '</question_data>' . "\n";
								$pq_id = $question;
								$tmp++;
							}
						}	
						$page_no = ($p-1);
						break;					
					}					
				}
			}
			else {
				$pages = explode('*#*', clear_chain($sf_chain, $not_shown));
				foreach($pages as $p=>$page) {
					$questions = explode('*', $page);
					$questions = array_diff($questions, $not_shown);
					if (in_array($prev_id, $questions)) {						
						foreach($questions as $question) {
							if (isset($q_data[$question])) {
								if ($first_quest_id == -1) $first_quest_id = $question;
								$tmp_str .= "\t" . '<question_data>' . "\n";
								$tmp_str .= SF_GetQuestData($q_data[$question], $start_id);
								$tmp_str .= "\t" . '</question_data>' . "\n";
								$pq_id = $question;
								$tmp++;
							}
						}
						$page_no = $p;
						break;						
					}
					
				}
				
			}
			
			if ($tmp > 0) {
			
				if ($page_no == 0)
					$ret_str .= "\t" . '<task>prev0</task>' . "\n";
				else
					$ret_str .= "\t" . '<task>prev</task>' . "\n";
				
				$ret_str .= "\t" . '<quest_count>'.$tmp.'</quest_count>' . "\n";
								
				$nn = 0;
				
				if ($survey->sf_progressbar_type == '0') { 
					foreach($chain_questions as $chain_question) {
						if ($chain_question == $first_quest_id)
							break;
						$nn++;
					}
					
					$nn = floor(100*$nn/count($q_data));				
				} elseif ($survey->sf_progressbar_type == '1') {
					$pages = explode('*#*', clear_chain($sf_chain, $not_shown));
					foreach($pages as $p=>$page) {
						$questions = explode('*', $page);
						if (in_array($first_quest_id, $questions))
							break;
						$nn++;
					}
					$nn = floor(100*$nn/count($pages));
				}
				
				$ret_str .= "\t" . '<progress_bar>'.$nn.'%</progress_bar>' . "\n";
				$ret_str .= "\t" . '<progress_bar_txt><![CDATA['.$sf_lang['SF_PROGRESS'] .' '.(int)$nn.'%]]></progress_bar_txt>' . "\n";
				$ret_str .= $tmp_str;
			} 
			else {
				$ret_str = '';
			}
		}
	}
	return $ret_str;
}

function SF_GetQuestData($q_data, $start_id = 0) {
	global $database;
	$ret_str = '';
	$query = "SELECT * FROM #__survey_force_fields WHERE quest_id = '".$q_data->id."' and is_main = '1' ORDER BY ordering";
	$database->SetQuery($query);
	$f_main_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
	$query = "SELECT * FROM #__survey_force_fields WHERE quest_id = '".$q_data->id."' and is_main = '0' ORDER BY ordering";
	$database->SetQuery($query);
	$f_alt_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
	if ($q_data->sf_qtype != 9)
		shuffle($f_alt_data);
	
	// add answers section for prev/next
	$query = "SELECT * FROM #__survey_force_user_answers WHERE quest_id = '".$q_data->id."' AND start_id = '".$start_id."' ";
	$database->SetQuery($query);
	$f_answ_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
	
	$ret_str .= "\t" . '<quest_type>'.$q_data->sf_qtype.'</quest_type>' . "\n";
	$inp = 0;
	$q_text = $q_data->sf_qtext;
	if ($q_data->sf_qtype == 4) {		
		if (strpos($q_text,'{x}') > 0 || strpos($q_text,'{y}') > 0) {
			$inp = substr_count($q_text, '{x}')+substr_count($q_text, '{y}');								
		}					
	}
	
	if ($q_data->sf_section_id > 0) {
		$query = "SELECT `addname`, `sf_name` FROM `#__survey_force_qsections` WHERE `id` = '".$q_data->sf_section_id."' ";
		$database->SetQuery($query);
		$qsection_t = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
		if (isset($qsection_t[0]->addname) && intval($qsection_t[0]->addname) > 0) {
			$q_text = '<div class="sf_section_name">'.$qsection_t[0]->sf_name."</div><br/>".$q_text;
		}		
	}
	$ret_str .= "\t\t" . '<quest_inp_count>' . $inp . '</quest_inp_count>' . "\n";
	$ret_str .= "\t" . '<quest_text><![CDATA['.sfPrepareText($q_text).'&nbsp;]]></quest_text>' . "\n";
	$ret_str .= "\t" . '<quest_id>'.$q_data->id.'</quest_id>' . "\n";
	$ret_str .= "\t" . '<default_hided>'.(int)$q_data->sf_default_hided.'</default_hided>' . "\n";
	$ret_str .= "\t" . '<main_fields_count>'.count($f_main_data).'</main_fields_count>' . "\n";
	$ret_str .= "\t" . '<compulsory>'.$q_data->sf_compulsory.'</compulsory>' . "\n";
	$ret_str .= "\t" . '<sf_qstyle>'.(int)$q_data->sf_qstyle.'</sf_qstyle>' . "\n";
	$ret_str .= "\t" . '<factor_name><![CDATA['.$q_data->sf_fieldtype.'&nbsp;]]></factor_name>' . "\n";
	$ret_str .= "\t" . '<sf_num_options>'.(int)$q_data->sf_num_options.'</sf_num_options>' . "\n";

	if (count($f_main_data) > 0) {
		$ret_str .= "\t" . '<main_fields>' . "\n";
		foreach ($f_main_data as $f_row) {
			$ret_str .= "\t\t" . '<main_field><mfield_text><![CDATA['.stripslashes($f_row->ftext).'&nbsp;]]></mfield_text>' . "\n";
			$ret_str .= "\t\t\t" . '<mfield_is_true>'.$f_row->is_true.'</mfield_is_true>' . "\n";
			$ret_str .= "\t\t\t" . '<mfield_id>'.$f_row->id.'</mfield_id></main_field>' . "\n";
			if ($f_row->is_true == 2) {
				$query = "SELECT a.ans_txt FROM #__survey_force_user_ans_txt AS a, #__survey_force_user_answers AS b WHERE b.quest_id = '".$q_data->id."' AND b.start_id = '".$start_id."' AND b.answer = '".$f_row->id."' AND a.id = b.next_quest_id";
				$database->SetQuery($query);
				$ans_txt = $database->LoadResult();
				if (strlen($ans_txt) < 1) $ans_txt = '!!!---!!!';
				$ret_str .= "\t\t\t" . '<ans_txt>'.$ans_txt.'</ans_txt>' . "\n";
			}
		}
		$ret_str .= "\t" . '</main_fields>' . "\n";
	}
	$ret_str .= "\t" . '<alt_fields_count>'.count($f_alt_data).'</alt_fields_count>' . "\n";
	if (count($f_alt_data) > 0) {
		$ret_str .= "\t" . '<alt_fields>' . "\n";
		foreach ($f_alt_data as $f_row) {
			$ret_str .= "\t\t" . '<alt_field><afield_text><![CDATA['.stripslashes($f_row->ftext).'&nbsp;]]></afield_text>' . "\n";
			$ret_str .= "\t\t\t" . '<afield_id>'.$f_row->id.'</afield_id></alt_field>' . "\n";
		}
		$ret_str .= "\t" . '</alt_fields>' . "\n";
	}
	if ($q_data->sf_qtype == 1) { //likert scale
		$query = "SELECT * FROM #__survey_force_scales WHERE quest_id = '".$q_data->id."' ORDER BY ordering";
		$database->SetQuery($query);
		$f_scale_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
		$ret_str .= "\t" . '<scale_fields_count>'.count($f_scale_data).'</scale_fields_count>' . "\n";
		if (count($f_scale_data) > 0) {
			$ret_str .= "\t" . '<scale_fields>' . "\n";
			foreach ($f_scale_data as $s_row) {
				$ret_str .= "\t\t" . '<scale_field><sfield_text><![CDATA['.stripslashes($s_row->stext).'&nbsp;]]></sfield_text>' . "\n";
				$ret_str .= "\t\t\t" . '<sfield_id>'.$s_row->id.'</sfield_id></scale_field>' . "\n";
			}
			$ret_str .= "\t" . '</scale_fields>' . "\n";
		}
	}
	if ($q_data->sf_impscale) { //important scale is SET
		$query = "SELECT a.iscale_name, b.* FROM #__survey_force_iscales as a, #__survey_force_iscales_fields as b WHERE a.id = '".$q_data->sf_impscale."' AND a.id = b.iscale_id ORDER BY b.ordering";
		$database->SetQuery($query);
		$f_iscale_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
		$ret_str .= "\t" . '<impscale_fields_count>'.count($f_iscale_data).'</impscale_fields_count>' . "\n";
		if (count($f_iscale_data) > 0) {
			$ret_str .= "\t" . '<impscale_name><![CDATA['.stripslashes($f_iscale_data[0]->iscale_name).'&nbsp;]]></impscale_name>' . "\n";
			$ret_str .= "\t" . '<impscale_fields>' . "\n";
			foreach ($f_iscale_data as $is_row) {
				$ret_str .= "\t\t" . '<impscale_field><isfield_text><![CDATA['.stripslashes($is_row->isf_name).'&nbsp;]]></isfield_text>' . "\n";
				$ret_str .= "\t\t\t" . '<isfield_id>'.$is_row->id.'</isfield_id></impscale_field>' . "\n";
			}
			$ret_str .= "\t" . '</impscale_fields>' . "\n";
		}
	} else {
		$ret_str .= "\t" . '<impscale_fields_count>0</impscale_fields_count>' . "\n";
	}
	
	if (!(count($f_answ_data) > 0)) {
		$query = "SELECT * FROM #__survey_force_def_answers WHERE quest_id = '".$q_data->id."'  ";
		$database->SetQuery($query);
		$f_answ_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
	}
	
	if (count($f_answ_data) > 0) {
		$ret_str .= "\t" . '<answers>' . "\n";
		
		switch ($q_data->sf_qtype) {
			case 1:
				foreach($f_answ_data as $answer){
					$ret_str .= "\t\t" . '<a_quest_id>' . $answer->answer. '</a_quest_id>' . "\n";	
					$ret_str .= "\t\t" . '<ans_id>' . $answer->ans_field . '</ans_id>' . "\n";	
				}
				break;
			case 2:
				$query = "SELECT ans_txt FROM #__survey_force_user_ans_txt WHERE id = '".$f_answ_data[0]->ans_field."' and start_id = '".$start_id."' ";
				$database->SetQuery($query);
				$ans_txt = $database->loadResult();
				if (strlen($ans_txt) < 1) $ans_txt = '!!!---!!!';
				$ret_str .= "\t\t" . '<ans_txt><![CDATA[' . $ans_txt . ']]></ans_txt>' . "\n";		
				$ret_str .= "\t\t" . '<a_quest_id>' . $f_answ_data[0]->answer . '</a_quest_id>' . "\n";						
				
				break;
			case 3:
				foreach($f_answ_data as $answer){
					if (!isset($ans_txt) && $f_answ_data[0]->ans_field > 0) {
						$query = "SELECT ans_txt FROM #__survey_force_user_ans_txt WHERE id = '".$f_answ_data[0]->ans_field."' and start_id = '".$start_id."' ";
						$database->SetQuery($query);
						$ans_txt = $database->loadResult();
						if (strlen($ans_txt) < 1) $ans_txt = '!!!---!!!';
						$ret_str .= "\t\t" . '<ans_txt><![CDATA[' . $ans_txt . ']]></ans_txt>' . "\n";
					}
					$ret_str .= "\t\t" . '<a_quest_id>' . $answer->answer. '</a_quest_id>' . "\n";
				}
				if (!isset($ans_txt)) {
					$ans_txt = '!!!---!!!';
					$ret_str .= "\t\t" . '<ans_txt><![CDATA[' . $ans_txt . ']]></ans_txt>' . "\n";
				}
				break;
			case 4:
				$inp = 0;
				if (strpos($q_text,'{x}') > 0 || strpos($q_text,'{y}') > 0) {
					$inp = substr_count($q_text, '{x}')+substr_count($q_text, '{y}');
					for($ii = 0; $ii < $inp; $ii++) {			
						if (count($f_answ_data) > 0) {						
							foreach($f_answ_data as $data) {
								if ($data->ans_field == ($ii+1)) {
									$query = "SELECT ans_txt FROM #__survey_force_user_ans_txt WHERE id = '".$data->answer."' and start_id = '".$start_id."' ";
									$database->SetQuery($query);
									$ans_txt = $database->loadResult();
									$ret_str .= "\t\t" . '<ans_txt><![CDATA[' . $ans_txt . ']]></ans_txt>' . "\n";
								}
							}
						}						
					}
				} else {				
					$query = "SELECT ans_txt FROM #__survey_force_user_ans_txt WHERE id = '".$f_answ_data[0]->answer."' and start_id = '".$start_id."' ";
					$database->SetQuery($query);
					$ans_txt = $database->loadResult();
					if (strlen($ans_txt) > 0){
						$ret_str .= "\t\t" . '<ans_txt><![CDATA[' . $ans_txt . ']]></ans_txt>' . "\n";
					}
					else 
						$f_answ_data = null;					
				}
				break;
			case 5:
			case 6:
			case 9:
				foreach($f_answ_data as $answer){
					$ret_str .= "\t\t" . '<a_quest_id>' . $answer->answer. '</a_quest_id>' . "\n";	
					$ret_str .= "\t\t" . '<ans_id>' . $answer->ans_field . '</ans_id>' . "\n";	
				}
				break;
		}
		
		$ret_str .= "\t" . '</answers>' . "\n";
	}
	$ret_str .= "\t" . '<ans_count>' . intval(count($f_answ_data)) . '</ans_count>' . "\n";	
	$query = "SELECT * FROM #__survey_force_user_answers_imp WHERE quest_id = '".$q_data->id."' and start_id = '".$start_id."' ";
	$database->SetQuery($query);
	$f_answ_imp_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
	
	$ret_str .= "\t" . '<ans_imp_count>' . intval(count($f_answ_imp_data)) . '</ans_imp_count>' . "\n";	
	
	if (count($f_answ_imp_data) > 0) {
		$ret_str .= "\t" . '<answers_imp>' . "\n";
		$ret_str .= "\t\t" . '<ans_imp_id>' . $f_answ_imp_data[0]->iscalefield_id . '</ans_imp_id>' . "\n";	
		$ret_str .= "\t" . '</answers_imp>' . "\n";
	}
	
	return $ret_str;
}

function get_user_result($id) {
	global $database, $sf_lang, $my;

	$query = "SELECT s.*, u.username reg_username, u.name reg_name, u.email reg_email,"
	. "\n sf_u.name as inv_name, sf_u.lastname as inv_lastname, sf_u.email as inv_email"
	. "\n FROM #__survey_force_user_starts as s"
	. "\n LEFT JOIN #__users as u ON u.id = s.user_id and s.usertype=1"
	. "\n LEFT JOIN #__survey_force_users as sf_u ON sf_u.id = s.user_id and s.usertype=2"
	. "\n WHERE s.id = '".$id."'";
	$database->SetQuery( $query );
	$start_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
	
	$query = "SELECT * FROM #__survey_force_survs WHERE id = '".$start_data[0]->survey_id."' ";			
	$database->SetQuery( $query );
	$survey_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
	
	$query = "SELECT q.*"
	. "\n FROM #__survey_force_quests as q"
	. "\n WHERE q.published = 1 AND q.sf_survey = '".$start_data[0]->survey_id."' AND sf_qtype NOT IN (7, 8) "
	. "\n ORDER BY q.ordering, q.id ";
	$database->SetQuery( $query );
	$questions_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
	$message = '';
	$i = 0;
	$questions_data[$i]->answer = '';
	if (is_array($questions_data) && count($questions_data) > 0)
	while ( $i < count($questions_data) ) {
		$questions_data[$i]->sf_qtext = trim(strip_tags(@$questions_data[$i]->sf_qtext));
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
					$questions_data[$i]->answer[$j]->alt_text = $sf_lang['SURVEY_NO_ANSWER'];
					foreach ($ans_inf_data as $ans_data) {
						if ($ans_data->answer == $tmp_data[$j]->id) {
							$query = "SELECT * FROM #__survey_force_scales WHERE id = '".$ans_data->ans_field."'"
							. "\n and quest_id = '".$questions_data[$i]->id."'"
							. "\n ORDER BY ordering";
							$database->SetQuery( $query );
							$alt_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
							$questions_data[$i]->answer[$j]->alt_text = ($ans_data->ans_field==0?$sf_lang['SURVEY_NO_ANSWER']:$alt_data[0]->stext);
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
					$questions_data[$i]->answer = ($ans_inf_data == '')?$sf_lang['SURVEY_NO_ANSWER']:$ans_inf_data;
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
					$questions_data[$i]->answer[$j]->alt_text = ($questions_data[$i]->sf_qtype == 9?'':$sf_lang['SURVEY_NO_ANSWER']);
					foreach ($ans_inf_data as $ans_data) {
						if ($ans_data->answer == $tmp_data[$j]->id) {
							$questions_data[$i]->answer[$j]->f_text = $tmp_data[$j]->ftext .($ans_data->ans_txt != '' ?' ('.$ans_data->ans_txt.')':'');
							$query = "SELECT * FROM #__survey_force_fields WHERE id = '".$ans_data->ans_field."'"
							. "\n and quest_id = '".$questions_data[$i]->id."'"
							. "\n and is_main = 0 ORDER BY ordering";
							$database->SetQuery( $query );
							$alt_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
							if (count($alt_data) > 0 ) {
								$questions_data[$i]->answer[$j]->alt_text = ($ans_data->ans_field==0?($questions_data[$i]->sf_qtype == 9?'':$sf_lang['SURVEY_NO_ANSWER']):$alt_data[0]->ftext);
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
				if (!$questions_data[$i]->answer) $questions_data[$i]->answer = $sf_lang['SURVEY_NO_ANSWER'];
			break;
		}
		$i ++;
	}
	$message .= "<br/><br/>".$sf_lang['SF_SURVEY_INFORMATION']."<br/><br/>";
	$message .= $sf_lang["SF_NAME"].': '.$survey_data[0]->sf_name."<br/><br/>";
	$message .= $sf_lang["SF_DESCRIPTION"].': '.strip_tags($survey_data[0]->sf_descr)."<br/><br/>";
	$message .= $sf_lang["SF_START_AT"].': '. mosFormatDate( $start_data[0]->sf_time, "Y-m-d H:i:s" )."<br/><br/>";
	$message .= $sf_lang['SF_USER'].': ';
	switch($start_data[0]->usertype) {
		case '0': $message .= $sf_lang['COM_SF_ANON'] . "<br/><br/>"; break;
		case '1': $message .= $sf_lang['COM_SF_REG_USER'] . $start_data[0]->reg_username.", ".$start_data[0]->reg_name." (".$start_data[0]->reg_email.")"."<br/><br/>"; break;
		case '2': $message .= $sf_lang['COM_SF_INV_USER'] . $start_data[0]->inv_name." ".$start_data[0]->inv_lastname." (".$start_data[0]->inv_email.")"."<br/><br/>"; break;
	}
	$message .= "<br/><br/>";
	foreach ($questions_data as $qrow) { 
		$message .= strip_tags($qrow->sf_qtext). "<br/><br/>";
		switch ($qrow->sf_qtype) {
				case 2:
				case 3:
					foreach ($qrow->answer as $arow) {
						$img_ans = $arow->alt_text ? ' - '.$sf_lang['SF_USER_CHOICE'] : '';
						$message .= "<p>".$arow->f_text . " " . $img_ans . "</p><br/><br/>";

					}
				break;
				case 1:	$message .= $sf_lang['SF_SCALE'].": " . $qrow->scale . "<br/><br/>";
				case 5:
				case 6:
				case 9:
					foreach ($qrow->answer as $arow) {
						$message .= "<p>". $arow->f_text . " - " . $arow->alt_text . "</p><br/><br/>";
					}
				break;
				case 4:
					if (isset($qrow->answer_count)){
						$tmp = $sf_lang['COM_SF_FIRST_ANSWER'];
						for($ii = 1; $ii <= $qrow->answer_count; $ii++) {
							if ($ii == 2) $tmp = $sf_lang['COM_SF_SECOND_ANSWER'];
							elseif($ii == 3)	$tmp = $sf_lang['COM_SF_THIRD_ANSWER'];
							elseif ($ii > 3) $tmp = $ii. $sf_lang['COM_SF_X_ANSWER'];
							foreach($qrow->answer as $answer) {
								if ($answer->ans_field == $ii) {
									$message .=  "<p>".$tmp.strip_tags(($answer->ans_txt == ''?$sf_lang['SURVEY_NO_ANSWER']:$answer->ans_txt))."</p><br/><br/>";
									$tmp = -1;
									}
							}
							if ($tmp != -1)	{
								$message .=  "<p>".$tmp." ".$sf_lang['SURVEY_NO_ANSWER']."</p><br/><br/>";
							}
						}
					}
					else {
						$message .= "<p>".($qrow->answer)."</p><br/><br/>";
					}
					break;
				default:
					$message .= "<p>".($qrow->answer)."</p><br/><br/>";
				break;
			}

		$message .= "<br/><br/>";
		if ($qrow->sf_impscale) {
			$message .= strip_tags($qrow->iscale_name)."<br/><br/>";
			foreach ($qrow->answer_imp as $arow) {
				$img_ans = $arow->alt_text ? ' - '.$sf_lang['SF_USER_CHOICE'] : '';
				$message .=  "<p>". $arow->f_text . " " . $img_ans . "</p><br/><br/>";
			}
		} 
	}
	
	return $message;
}

function get_graph_results ($survey_id, $start_id) {
	global $database, $sf_lang, $mosConfig_live_site, $mosConfig_absolute_path;
	$ret_str = '';
	$ret_str .= "\t" . '<fpage_type>1</fpage_type>' . "\n";
	$query = "SELECT id FROM #__survey_force_quests WHERE published = 1 AND sf_survey = '".$survey_id."' ORDER BY ordering, id ";
	$database->SetQuery($query);
	$questions = $database->loadResultArray();	

	require ( $mosConfig_absolute_path . '/components/com_surveyforce/generate.surveyforce.php' );
	
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
	$fpage_text = '<p align="left"><strong>'.$sf_lang['SF_SURVEY_RESULTS'].'</strong></p><br/>';
	foreach($questions as $question){
		$img_src = $gg->getImage($survey_id,$question,$start_id);
		if (is_array($img_src)) {
			foreach($img_src as $imgsrc){
				$fpage_text .= $imgsrc;
			}
		}
		elseif ($img_src) {
			$fpage_text .= $img_src;
		}
	}
	if ($fpage_text == '<p align="left"><strong>'.$sf_lang['SF_SURVEY_RESULTS'].'</strong></p><br/>')
		$fpage_text .= 'No graphs available.'; 
	$ret_str .= "\t" . '<fpage_text><![CDATA['.stripslashes($fpage_text).'&nbsp;]]></fpage_text>' . "\n";
	return $ret_str;
}

function create_chain($survey_id) {
	global  $Itemid, $my, $database, $mosConfig_absolute_path, $sf_lang, $task, $option;
	
	$query = "SELECT * FROM #__survey_force_survs WHERE id = '".$survey_id."'";
	$database->SetQuery ($query );
	$survey = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
	$survey = $survey[0];
	$chain = '';
	if ($survey) {
		$auto_pb = $survey->sf_auto_pb;
		$chaintype = $survey->sf_random;
		
		$query = " SELECT * FROM #__survey_force_quests WHERE published = 1 AND sf_survey = '".$survey_id."' ".($auto_pb?" AND sf_qtype <> 8 ":'')." ORDER BY ordering, id ";
		$database->SetQuery($query);
		$q_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
		
		
		for($i = 0, $n = count($q_data); $i < $n; $i++) {
			if ($q_data[$i]->sf_qtype == 8) {
				$chain .= '#';
			} elseif ($q_data[$i]->sf_qtype != 8){
				$chain .= $q_data[$i]->id;
			}
			if ($auto_pb && ($i+1) < $n) 
				$chain .= '*#';
				
			if (($i+1) < $n)
				$chain .= '*';				
		}
		if (substr($chain, -2) == '*#') {
			$chain = substr($chain, 0, -2);
		}
		if (substr($chain, -3) == '*#*') {
			$chain = substr($chain, 0, -3);
		}
		if (substr($chain, -1) == '*') {
			$chain = substr($chain, 0, -1);
		}
		if (substr($chain, -1) == '#') {
			$chain = substr($chain, 0, -1);
		}
		
		if ($chaintype == 1) { // random pages
			$pages = explode('*#*', $chain);
			srand ((float)microtime()*1000000);
			shuffle ($pages);
			$chain = implode("*#*", $pages);
		} elseif ($chaintype == 2) { //randon questions in pages
			$pages = explode('*#*', $chain);
			for($j=0, $m=count($pages); $j<$m; $j++) {
				$page = explode('*', $pages[$j]);
				srand ((float)microtime()*1000000);
				shuffle ($page);
				$pages[$j] = implode("*", $page);
			}			
			$chain = implode("*#*", $pages);
		} elseif ($chaintype == 3) { //randon questions in page and pages
			$pages = explode('*#*', $chain);
			for($j=0, $m=count($pages); $j<$m; $j++) {
				$page = explode('*', $pages[$j]);
				srand ((float)microtime()*1000000);
				shuffle ($page);
				$pages[$j] = implode("*", $page);
			}
			srand ((float)microtime()*1000000);
			shuffle ($pages);
			$chain = implode("*#*", $pages);
		}
		
	}

	return $chain;
}

function clear_chain($chain, $not_shown) {
	$new_chain = array();
	$pages = explode('*#*', $chain);
	
	for($j=0, $m=count($pages); $j<$m; $j++) {
		$page = explode('*', $pages[$j]);
		$page = array_diff($page, $not_shown);
		if (count($page))
			$new_chain[] = implode("*", $page);
	}
	
	return implode("*#*", $new_chain);
	
}
?>