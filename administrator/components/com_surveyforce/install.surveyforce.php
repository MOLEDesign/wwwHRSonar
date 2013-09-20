<?php
/**
* Survey Force component for Joomla
* @version $Id: install.surveyforce.php 2009-11-16 17:30:15
* @package Survey Force
* @subpackage install.surveyforce.php
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

// Don't allow access
defined( '_VALID_MOS' ) or defined( '_JEXEC' ) or die( 'Restricted access' );

if (!defined('_JOOMLA15')) {
	if (defined( '_JLEGACY' ) or defined( '_JEXEC' ))
		define( '_JOOMLA15', 1 );
	else
		define( '_JOOMLA15', 0 );
}

function com_install()
{
	$language = & JFactory::getLanguage();
	$language->load('com_surveyforce', JPATH_SITE);


	if (_JOOMLA15) {
		$mosConfig_absolute_path	= JPATH_SITE;
		$database	=& JFactory::getDBO();
		$mosConfig_live_site = substr_replace(JURI::root(), '', -1, 1);
	} else
		global $database, $mosConfig_absolute_path, $mosConfig_live_site;
		
	$adminDir = dirname(__FILE__);
	@mkdir($mosConfig_absolute_path . "/images/surveyforce");
	@mkdir($mosConfig_absolute_path . "/images/surveyforce/gen_images");
	
	@mkdir($mosConfig_absolute_path . "/media/surveyforce");
	@chmod($mosConfig_absolute_path . "/media/surveyforce", 0757);
	@mkdir($mosConfig_absolute_path . "/media/surveyforce/surveyforce_standart");
	@mkdir($mosConfig_absolute_path . "/media/surveyforce/surveyforce_standart/images");
	
	@mkdir($mosConfig_absolute_path . "/media/surveyforce/surveyforce_new");
	@mkdir($mosConfig_absolute_path . "/media/surveyforce/surveyforce_new/images");
	
	@copy( $adminDir. "/template/surveyforce_standart/index.html", $mosConfig_absolute_path . "/media/surveyforce/surveyforce_standart/index.html");
	@rename( $adminDir. "/template/surveyforce_standart/template.xml", $mosConfig_absolute_path . "/media/surveyforce/surveyforce_standart/template.xml");
	@rename( $adminDir. "/template/surveyforce_standart/surveyforce.css", $mosConfig_absolute_path . "/media/surveyforce/surveyforce_standart/surveyforce.css");
	@rename( $adminDir. "/template/surveyforce_standart/template.php", $mosConfig_absolute_path . "/media/surveyforce/surveyforce_standart/template.php");
	@copy( $adminDir. "/template/surveyforce_standart/images/index.html", $mosConfig_absolute_path . "/media/surveyforce/surveyforce_standart/images/index.html");
	
	@copy( $adminDir. "/template/surveyforce_new/index.html", $mosConfig_absolute_path . "/media/surveyforce/surveyforce_new/index.html");
	@rename( $adminDir. "/template/surveyforce_new/template.xml", $mosConfig_absolute_path . "/media/surveyforce/surveyforce_new/template.xml");
	@rename( $adminDir. "/template/surveyforce_new/surveyforce.css", $mosConfig_absolute_path . "/media/surveyforce/surveyforce_new/surveyforce.css");
	@rename( $adminDir. "/template/surveyforce_new/template.php", $mosConfig_absolute_path . "/media/surveyforce/surveyforce_new/template.php");
	@copy( $adminDir. "/template/surveyforce_new/images/index.html", $mosConfig_absolute_path . "/media/surveyforce/surveyforce_new/images/index.html");
	
	@copy( $adminDir. "/template/surveyforce_new/images/no.gif", $mosConfig_absolute_path . "/media/surveyforce/surveyforce_new/images/no.gif");
	@copy( $adminDir. "/template/surveyforce_new/images/yes.gif", $mosConfig_absolute_path . "/media/surveyforce/surveyforce_new/images/yes.gif");
	@copy( $adminDir. "/template/surveyforce_new/images/finish.png", $mosConfig_absolute_path . "/media/surveyforce/surveyforce_new/images/finish.png");
	@copy( $adminDir. "/template/surveyforce_new/images/start.png", $mosConfig_absolute_path . "/media/surveyforce/surveyforce_new/images/start.png");
	@copy( $adminDir. "/template/surveyforce_new/images/next.png", $mosConfig_absolute_path . "/media/surveyforce/surveyforce_new/images/next.png");
	@copy( $adminDir. "/template/surveyforce_new/images/prev.png", $mosConfig_absolute_path . "/media/surveyforce/surveyforce_new/images/prev.png");	 
	
	@rename($adminDir. "/survey_back.jpg", $mosConfig_absolute_path . "/images/surveyforce/survey_back.jpg");
	
	@copy($adminDir. "/DejaVuSansCondensed-Bold.ttf", $mosConfig_absolute_path . "/media/DejaVuSansCondensed-Bold.ttf");
	@copy($adminDir. "/DejaVuSansCondensed.ttf", $mosConfig_absolute_path . "/media/DejaVuSansCondensed.ttf");

	@chmod( $adminDir. "/surveyforce.xml", 0666);
	
	$database->SetQuery("SELECT COUNT(*) FROM #__survey_force_config ");
	$is_upgrade = (int)$database->loadResult();
	
	//Set up icons for admin area
	$database->setQuery("UPDATE #__components SET admin_menu_img='../administrator/components/com_surveyforce/jp_menu_pic.gif' WHERE admin_menu_link like 'option=com_surveyforce'");
	$database->query();
	$database->setQuery("UPDATE #__components SET admin_menu_img='js/ThemeOffice/content.png' WHERE admin_menu_link='option=com_surveyforce&task=categories'");
	$database->query();
	$database->setQuery("UPDATE #__components SET admin_menu_img='js/ThemeOffice/content.png' WHERE admin_menu_link='option=com_surveyforce&task=surveys'");
	$database->query();
	$database->setQuery("UPDATE #__components SET admin_menu_img='js/ThemeOffice/help.png' WHERE admin_menu_link='option=com_surveyforce&task=support'");
	$database->query();
	$database->setQuery("UPDATE #__components SET admin_menu_img='js/ThemeOffice/help.png' WHERE admin_menu_link='option=com_surveyforce&task=help'");
	$database->query();
	$database->setQuery("UPDATE #__components SET admin_menu_img='js/ThemeOffice/help.png' WHERE admin_menu_link='option=com_surveyforce&task=faq'");
	$database->query();
	
	
	$database->setQuery("UPDATE #__components SET admin_menu_img='js/ThemeOffice/document.png' WHERE admin_menu_link='option=com_surveyforce&task=adv_report'");
	$database->query();
	
	if (_JOOMLA15) {
		$database->setQuery("UPDATE #__components SET admin_menu_img='js/ThemeOffice/user.png' WHERE admin_menu_link='option=com_surveyforce&task=users'");
		$database->query();
		$database->setQuery("UPDATE #__components SET admin_menu_img='js/ThemeOffice/user.png' WHERE admin_menu_link='option=com_surveyforce&task=authors'");
		$database->query();
		$database->setQuery("UPDATE #__components SET admin_menu_img='js/ThemeOffice/messages.png' WHERE admin_menu_link='option=com_surveyforce&task=emails'");
		$database->query();
		$database->setQuery("UPDATE #__components SET admin_menu_img='js/ThemeOffice/stats.png' WHERE admin_menu_link='option=com_surveyforce&task=reports'");
		$database->query();
		$database->setQuery("UPDATE #__components SET admin_menu_img='js/ThemeOffice/info.png' WHERE admin_menu_link='option=com_surveyforce&task=about'");
		$database->query();
	} else {
		$database->setQuery("UPDATE #__components SET admin_menu_img='js/ThemeOffice/users.png' WHERE admin_menu_link='option=com_surveyforce&task=users'");
		$database->query();
		$database->setQuery("UPDATE #__components SET admin_menu_img='js/ThemeOffice/users.png' WHERE admin_menu_link='option=com_surveyforce&task=authors'");
		$database->query();
		$database->setQuery("UPDATE #__components SET admin_menu_img='js/ThemeOffice/messaging_inbox.png' WHERE admin_menu_link='option=com_surveyforce&task=emails'");
		$database->query();
		$database->setQuery("UPDATE #__components SET admin_menu_img='js/ThemeOffice/document.png' WHERE admin_menu_link='option=com_surveyforce&task=reports'");
		$database->query();
		$database->setQuery("UPDATE #__components SET admin_menu_img='js/ThemeOffice/license.png' WHERE admin_menu_link='option=com_surveyforce&task=about'");
		$database->query();
	}
	
	
	$database->setQuery("UPDATE #__components SET admin_menu_img='js/ThemeOffice/language.png' WHERE admin_menu_link='option=com_surveyforce&task=labels'");
	$database->query();
	$database->setQuery("UPDATE #__components SET admin_menu_img='js/ThemeOffice/config.png' WHERE admin_menu_link='option=com_surveyforce&task=config'");
	$database->query();
	$database->setQuery("UPDATE #__components SET admin_menu_img='js/ThemeOffice/config.png' WHERE admin_menu_link='option=com_surveyforce&task=menu_man'");
	$database->query();
	$database->setQuery("UPDATE #__components SET admin_menu_img='js/ThemeOffice/config.png' WHERE admin_menu_link='option=com_surveyforce&task=iscales'");
	$database->query();
	
	$database->SetQuery("ALTER TABLE `#__survey_force_quests` CHANGE `sf_fieldtype` `sf_fieldtype` VARCHAR( 255 ) NOT NULL;");
	$database->query();
	$database->SetQuery("ALTER TABLE `#__survey_force_survs` ADD `sf_progressbar` TINYINT( 4 ) DEFAULT '1' NOT NULL ;");
	$database->query();
	$database->SetQuery("ALTER TABLE `#__survey_force_survs` ADD `sf_use_css` TINYINT( 4 ) DEFAULT '0' NOT NULL ;");
	$database->query();
	$database->SetQuery("ALTER TABLE `#__survey_force_survs` ADD `sf_enable_descr` TINYINT( 4 ) DEFAULT '1' NOT NULL ;");
	$database->query();
	$database->SetQuery("ALTER TABLE `#__survey_force_survs` ADD `sf_progressbar_type` TINYINT( 1 ) DEFAULT '0' NOT NULL AFTER `sf_progressbar` ;");
	$database->query();
	
	$database->SetQuery("ALTER TABLE `#__survey_force_survs` DROP `sf_disable_multiple_voting`");
	$database->query();
	$database->SetQuery("ALTER TABLE `#__survey_force_survs` ADD `sf_reg_voting` TINYINT( 4 ) DEFAULT '0' NOT NULL ;");
	$database->query();
	$database->SetQuery("ALTER TABLE `#__survey_force_survs` ADD `sf_inv_voting` TINYINT( 4 ) DEFAULT '1' NOT NULL ;");
	$database->query();
	$database->SetQuery("ALTER TABLE `#__survey_force_survs` ADD `sf_template` INT( 11 ) DEFAULT '1' NOT NULL ;");
	$database->query();
	
	$database->SetQuery("ALTER TABLE `#__survey_force_survs` ADD `sf_pub_voting` TINYINT( 4 ) DEFAULT '0' NOT NULL ;");
	$database->query();
	$database->SetQuery("ALTER TABLE `#__survey_force_survs` ADD `sf_pub_control` TINYINT( 4 ) DEFAULT '0' NOT NULL ;");
	$database->query();
	
	$database->SetQuery("ALTER TABLE `#__survey_force_survs` ADD `surv_short_descr` TEXT ;");
	$database->query();
	
	$database->SetQuery("ALTER TABLE `#__survey_force_user_starts` ADD `sf_ip_address` VARCHAR(255) DEFAULT '' NOT NULL ;");
	$database->query();
	
	
	$database->SetQuery("ALTER TABLE `#__survey_force_qsections` CHANGE `sf_survey` `addname` TINYINT( 4 ) DEFAULT '0' NOT NULL ");
	$database->query();
	
	$database->SetQuery("ALTER TABLE `#__survey_force_quests` ADD `published` TINYINT( 4 ) DEFAULT '1' NOT NULL ;");
	$database->query();
	
	$database->SetQuery("ALTER TABLE `#__survey_force_quests` ADD `sf_qstyle` INT( 11 ) DEFAULT '0' NOT NULL ;");
	$database->query();
	
	$database->SetQuery("ALTER TABLE `#__survey_force_quests` ADD `sf_num_options` TINYINT( 4 ) DEFAULT '0' NOT NULL ;");
	$database->query();
	
	
	$database->SetQuery("ALTER TABLE `#__survey_force_user_ans_txt` CHANGE `ans_txt` `ans_txt` TEXT NOT NULL ;");
	$database->query();

	$database->SetQuery("ALTER TABLE `#__survey_force_emails` ADD `user_id` INT( 11 ) DEFAULT '0' NOT NULL ;");
	$database->query();
	
	$database->SetQuery("ALTER TABLE `#__survey_force_cats` ADD `user_id` INT( 11 ) DEFAULT '0' NOT NULL ;");
	$database->query();

	$database->SetQuery("ALTER TABLE `#__survey_force_survs` ADD `sf_after_start` TINYINT( 4 ) DEFAULT '0' NOT NULL ;");
	$database->query(); 
	
	//add question types
	$database->SetQuery("SELECT count(*) FROM #__survey_force_qtypes WHERE id IN (1, 2, 3, 4, 5, 6, 7, 8, 9)");
	if ($database->LoadResult() != 9) {
		$database->SetQuery("DELETE FROM #__survey_force_qtypes ");
		$database->query();
		$database->SetQuery("INSERT INTO #__survey_force_qtypes (id, sf_qtype) VALUES (1, 'LikertScale')");
		$database->query();
		$database->SetQuery("INSERT INTO #__survey_force_qtypes (id, sf_qtype) VALUES (2, 'PickOne')");
		$database->query();
		$database->SetQuery("INSERT INTO #__survey_force_qtypes (id, sf_qtype) VALUES (3, 'PickMany')");
		$database->query();
		$database->SetQuery("INSERT INTO #__survey_force_qtypes (id, sf_qtype) VALUES (4, 'ShortAnswer')");
		$database->query();
		$database->SetQuery("INSERT INTO #__survey_force_qtypes (id, sf_qtype) VALUES (5, 'Ranking DropDown')");
		$database->query();
		$database->SetQuery("INSERT INTO #__survey_force_qtypes (id, sf_qtype) VALUES (6, 'Ranking Drag''AND''Drop')");
		$database->query();
		$database->SetQuery("INSERT INTO #__survey_force_qtypes (id, sf_qtype) VALUES (7, 'Boilerplate')");
		$database->query();
		$database->SetQuery("INSERT INTO #__survey_force_qtypes (id, sf_qtype) VALUES (8, 'Page Break')");
		$database->query();
		$database->SetQuery("INSERT INTO #__survey_force_qtypes (id, sf_qtype) VALUES (9, 'Ranking')");
		$database->query();
	}
	
	//add config record
	$database->SetQuery("SELECT config_value FROM #__survey_force_config WHERE config_var = 'sf_version'");
	if (!$database->LoadResult()) {
		$database->SetQuery("DROP TABLE #__survey_force_config");
		$database->query();
		$query = " CREATE TABLE IF NOT EXISTS `#__survey_force_config` ( "
  				." `config_var` varchar(50) NOT NULL default '', "
 				." `config_value` text NOT NULL );";
		$database->SetQuery($query);
		$database->query();		

		$database->SetQuery("INSERT INTO #__survey_force_config (`config_var`, `config_value`) VALUES ('sf_version', '3.0.6')");
		$database->query();
		$database->SetQuery("INSERT INTO #__survey_force_config (`config_var`, `config_value`) VALUES ('b_aqua_color1', 'F2F2F2')");
		$database->query();
		$database->SetQuery("INSERT INTO #__survey_force_config (`config_var`, `config_value`) VALUES ('b_aqua_color2', 'E7E7E7')");
		$database->query();
		$database->SetQuery("INSERT INTO #__survey_force_config (`config_var`, `config_value`) VALUES ('b_aqua_color3', 'EFEFEF')");
		$database->query();
		$database->SetQuery("INSERT INTO #__survey_force_config (`config_var`, `config_value`) VALUES ('b_aqua_color4', 'FDFDFD')");
		$database->query();
		$database->SetQuery("INSERT INTO #__survey_force_config (`config_var`, `config_value`) VALUES ('b_axis_color1', 'C9C9C9')");
		$database->query();
		$database->SetQuery("INSERT INTO #__survey_force_config (`config_var`, `config_value`) VALUES ('b_axis_color2', '9E9E9E')");
		$database->query();
		$database->SetQuery("INSERT INTO #__survey_force_config (`config_var`, `config_value`) VALUES ('b_bar_color1', '2A47B5')");
		$database->query();
		$database->SetQuery("INSERT INTO #__survey_force_config (`config_var`, `config_value`) VALUES ('b_bar_color2', '21388F')");
		$database->query();
		$database->SetQuery("INSERT INTO #__survey_force_config (`config_var`, `config_value`) VALUES ('b_bar_color3', 'ACACD2')");
		$database->query();
		$database->SetQuery("INSERT INTO #__survey_force_config (`config_var`, `config_value`) VALUES ('b_bar_color4', '75758F')");
		$database->query();
		$database->SetQuery("INSERT INTO #__survey_force_config (`config_var`, `config_value`) VALUES ('b_height', '300')");
		$database->query();
		$database->SetQuery("INSERT INTO #__survey_force_config (`config_var`, `config_value`) VALUES ('b_width', '500')");
		$database->query();
		$database->SetQuery("INSERT INTO #__survey_force_config (`config_var`, `config_value`) VALUES ('color_cont', '666666')");
		$database->query();
		$database->SetQuery("INSERT INTO #__survey_force_config (`config_var`, `config_value`) VALUES ('color_drag', 'cccccc')");
		$database->query();
		$database->SetQuery("INSERT INTO #__survey_force_config (`config_var`, `config_value`) VALUES ('color_highlight', 'eeeeee')");
		$database->query();
		$database->SetQuery("INSERT INTO #__survey_force_config (`config_var`, `config_value`) VALUES ('p_aqua_color1', 'F2F2F2')");
		$database->query();
		$database->SetQuery("INSERT INTO #__survey_force_config (`config_var`, `config_value`) VALUES ('p_aqua_color2', 'E7E7E7')");
		$database->query();
		$database->SetQuery("INSERT INTO #__survey_force_config (`config_var`, `config_value`) VALUES ('p_aqua_color3', 'EFEFEF')");
		$database->query();
		$database->SetQuery("INSERT INTO #__survey_force_config (`config_var`, `config_value`) VALUES ('p_aqua_color4', 'FDFDFD')");
		$database->query();
		$database->SetQuery("INSERT INTO #__survey_force_config (`config_var`, `config_value`) VALUES ('p_axis_color1', 'C9C9C9')");
		$database->query();
		$database->SetQuery("INSERT INTO #__survey_force_config (`config_var`, `config_value`) VALUES ('p_axis_color2', '9E9E9E')");
		$database->query();
		$database->SetQuery("INSERT INTO #__survey_force_config (`config_var`, `config_value`) VALUES ('p_height', '300')");
		$database->query();
		$database->SetQuery("INSERT INTO #__survey_force_config (`config_var`, `config_value`) VALUES ('p_width', '500')");
		$database->query();
		$database->SetQuery("INSERT INTO #__survey_force_config (`config_var`, `config_value`) VALUES ('sf_enable_lms_integration', '0')");
		$database->query();
		$database->SetQuery("INSERT INTO #__survey_force_config (`config_var`, `config_value`) VALUES ('sf_enable_jomsocial_integration', '0')");
		$database->query();
		$database->SetQuery("INSERT INTO #__survey_force_config (`config_var`, `config_value`) VALUES ('sf_result_type', 'Bar')");
		$database->query();
		
		$database->SetQuery("INSERT INTO #__survey_force_config (`config_var`, `config_value`) VALUES ('fe_lang', 'default')");
		$database->query();
		
		$database->SetQuery("INSERT INTO #__survey_force_config (`config_var`, `config_value`) VALUES ('color_border', '000000')");
		$database->query();
		$database->SetQuery("INSERT INTO #__survey_force_config (`config_var`, `config_value`) VALUES ('color_text', '333333')");
		$database->query();
		$database->SetQuery("INSERT INTO #__survey_force_config (`config_var`, `config_value`) VALUES ('color_completed', 'cccccc')");
		$database->query();
		$database->SetQuery("INSERT INTO #__survey_force_config (`config_var`, `config_value`) VALUES ('color_uncompleted', 'ffffff')");
		$database->query();

		
		$database->SetQuery("ALTER TABLE `#__survey_force_survs` ADD `sf_fpage_type` TINYINT DEFAULT '0' NOT NULL, ADD `sf_fpage_text` TEXT;");
		$database->query();
		$database->SetQuery("ALTER TABLE `#__survey_force_quests` ADD `sf_compulsory` TINYINT DEFAULT '1' NOT NULL;");
		$database->query();
		$database->SetQuery("ALTER TABLE `#__survey_force_quests` ADD `sf_section_id` INT DEFAULT '0' NOT NULL;");
		$database->query();
		$database->SetQuery("ALTER TABLE `#__survey_force_listusers` ADD `sf_author_id` int(11) NOT NULL;");
		$database->query();
		$database->SetQuery("ALTER TABLE `#__survey_force_survs` ADD `sf_special` TEXT NOT NULL;");
		$database->query();
		
		
		$database->SetQuery("ALTER TABLE `#__survey_force_rules` ADD `alt_field_id` INT( 11 ) DEFAULT '0' NOT NULL ;");
		$database->query();
		$database->SetQuery("ALTER TABLE `#__survey_force_rules` ADD `priority` INT( 11 ) DEFAULT '0' NOT NULL ;");
		$database->query();
		$database->SetQuery("ALTER TABLE `#__survey_force_survs` ADD `sf_auto_pb` TINYINT( 4 ) DEFAULT '1' NOT NULL ;");
		$database->query();
	} else {
		$database->SetQuery("SELECT config_value FROM #__survey_force_config WHERE config_var = 'fe_lang'");
		if (!$database->LoadResult()) {
			$database->SetQuery("INSERT INTO #__survey_force_config (`config_var`, `config_value`) VALUES ('fe_lang', 'default')");
			$database->query();
		}
		$database->SetQuery("UPDATE #__survey_force_config SET config_value = '3.0.6' WHERE config_var = 'sf_version'");
		$database->query();
		
		$database->SetQuery("ALTER TABLE `#__survey_force_rules` ADD `alt_field_id` INT( 11 ) DEFAULT '0' NOT NULL ;");
		$database->query();
		$database->SetQuery("ALTER TABLE `#__survey_force_rules` ADD `priority` INT( 11 ) DEFAULT '0' NOT NULL ;");
		$database->query();
		$database->SetQuery("ALTER TABLE `#__survey_force_survs` ADD `sf_auto_pb` TINYINT( 4 ) DEFAULT '1' NOT NULL ;");
		$database->query();
	}

	//add imp.scale
	$database->SetQuery("SELECT count(*) FROM #__survey_force_iscales WHERE id = 1");
	if (!$database->LoadResult()) {
		$database->SetQuery("INSERT INTO #__survey_force_iscales (id, iscale_name) VALUES (1, 'How important is this question for you?')");
		$database->query();
		$database->SetQuery("INSERT INTO #__survey_force_iscales_fields (iscale_id, isf_name, ordering) VALUES (1, 'Not at all', 0)");
		$database->query();
		$database->SetQuery("INSERT INTO #__survey_force_iscales_fields (iscale_id, isf_name, ordering) VALUES (1, 'Not important', 1)");
		$database->query();
		$database->SetQuery("INSERT INTO #__survey_force_iscales_fields (iscale_id, isf_name, ordering) VALUES (1, 'Neutral', 2)");
		$database->query();
		$database->SetQuery("INSERT INTO #__survey_force_iscales_fields (iscale_id, isf_name, ordering) VALUES (1, 'Important', 3)");
		$database->query();
		$database->SetQuery("INSERT INTO #__survey_force_iscales_fields (iscale_id, isf_name, ordering) VALUES (1, 'Very important', 4)");
		$database->query();
	}

	
	/* Correcting of menu items */
	$query = "SELECT id FROM #__components WHERE `option` = 'com_surveyforce' AND `link` = 'option=com_surveyforce'";
	$database->SetQuery($query);
	$surveyforce_comp_id = intval($database->LoadResult());
	if ($surveyforce_comp_id) {
		$query = "UPDATE #__menu SET `componentid` = $surveyforce_comp_id WHERE `type` = 'components' AND `link` = 'index.php?option=com_surveyforce'";
		$database->SetQuery($query);
		$database->query();
	}
	
	$database->SetQuery("ALTER TABLE `#__survey_force_survs` ADD `sf_anonymous` TINYINT( 4 ) DEFAULT '0' NOT NULL ;");
	$database->query();
	$database->SetQuery("ALTER TABLE `#__survey_force_survs` ADD `sf_friend` TINYINT( 4 ) DEFAULT '0' NOT NULL AFTER `sf_reg`");
	$database->query();
	$database->SetQuery("ALTER TABLE `#__survey_force_survs` ADD `sf_friend_voting` TINYINT( 4 ) DEFAULT '0' NOT NULL AFTER `sf_reg_voting`");
	$database->query();
	
	$database->SetQuery("ALTER TABLE `#__survey_force_user_answers` ADD INDEX `surv_ind` ( `survey_id` )");
	$database->query();
	$database->SetQuery("ALTER TABLE `#__survey_force_user_answers` ADD INDEX `quest_ind` ( `quest_id` )");
	$database->query();
	$database->SetQuery("ALTER TABLE `#__survey_force_user_answers` ADD INDEX `ans_ind` ( `answer` )");
	$database->query();
	
	$database->SetQuery("ALTER TABLE `#__survey_force_fields` ADD INDEX `quest_ind` ( `quest_id` )");
	$database->query();
	$database->SetQuery("ALTER TABLE `#__survey_force_scales` ADD INDEX `quest_ind` ( `quest_id` )");
	$database->query(); 
	
	$database->SetQuery("INSERT INTO `#__survey_force_templates` (id, sf_name) VALUES (1, 'surveyforce_standart');");
	$database->query();
	
	$query = "SELECT `id` FROM #__survey_force_templates WHERE `sf_name` = 'surveyforce_new'";
	$database->SetQuery($query);
	if (!intval($database->LoadResult()) ) {
		$database->SetQuery("INSERT INTO `#__survey_force_templates` (sf_name) VALUES ('surveyforce_new');");
		$database->query();
	}
	
	$database->SetQuery("ALTER TABLE `#__survey_force_survs` ADD `sf_random` TINYINT( 4 ) DEFAULT '0' NOT NULL;");
	$database->query();
	
	$database->SetQuery("ALTER TABLE `#__survey_force_quests` ADD `sf_default_hided` TINYINT( 4 ) DEFAULT '0' NOT NULL ;");
	$database->query();
		
	?>
	<font style="font-size:2em; color:#55AA55;" ><?php echo JText::_('SURVEYFORCE_COMPONENT_SUCCESSFULLY').($is_upgrade? JText::_('UPGRADED'): JText::_('INSTALLED'));?>.</font><br/><br/>
	<table border="1" cellpadding="5" width="100%" style="background-color: #F7F8F9; border: solid 1px #d5d5d5; width: 100%; padding: 10px; border-collapse: collapse;">		
		<tr>
			<td colspan="2" style="background-color: #e7e8e9;text-align:left; font-size:16px; font-weight:400; line-height:18px "><strong><img src="images/tick.png"><?php echo JText::_('GETTING_STARTED'). "</strong> ". JText::_('HELPFULL_LINKS').":"?></td>
		</tr>
		<tr>
			<td colspan="2" style="padding-left:20px">
				<div style="font-size:1.2em">
				<ul>
					<li><a href="index.php?option=com_surveyforce&task=sample"><?php echo JText::_('SAMPLE_SURVEYS'); ?></a></li>
					<li><a href="index.php?option=com_surveyforce&task=help"><?php echo JText::_('COMPONENTS_HELP'); ?></a></li>
					<li><a href="http://www.joomplace.com/forum/joomla-components/surveyforce-deluxe.html" target="_blank"><?php echo JText::_('SUPPORT_FORUM'); ?></a></li>
					<li><a href="http://www.joomplace.com/helpdesk/ticket_submit.php" target="_blank"><?php echo JText::_('SUBMIT_REQUEST_TO_OUR_TECHNICIANS'); ?></a></li>
				</ul>
				</div>
			</td>
		</tr>
		<tr>
			<td colspan="2" style="background-color: #e7e8e9;text-align:left; font-size:16px; font-weight:400; line-height:18px "><strong><img src="images/tick.png"><?php echo JText::_('SAY_YOUR_THANK_YOU'); ?></strong></td>
		</tr>
		<tr>
			<td colspan="2" style="padding-left:20px">
			<div style="font-size:1.2em">
			<span style="font-size:1.5em;font-weight:bold"><?php echo JText::_('SAY_YOUR_THANK_YOU_TO_JOOMLA')."</span>".JText::_('FOR_WONDERFULL_JOOMLA'); ?><span style="font-size:1.5em;font-weight:bold"><?php echo JText::_('HELP_IT')."</span>". JText::_('BY_SHARING_YOUR_EXPIRIENCE'); ?><a href="http://extensions.joomla.org/extensions/contacts-and-feedback/surveys/11301" target="_blank">http://extensions.joomla.org/</a><?php echo JText::_('AND_THREE_MINUTES'); ?><br />
			<a href="http://extensions.joomla.org/extensions/contacts-and-feedback/surveys/11301" target="_blank"><img src="<?php echo $mosConfig_live_site;?>/administrator/components/com_surveyforce/images/rate_us.png" title="<?php echo JText::_('RATE_US'); ?>" alt="<?php echo JText::_('RATE_US_AT_EXTENSIONS'); ?>"  style="padding-top:5px;"/></a>
			</div>
			</td>
		</tr>
		<tr>
			<td colspan="2" style="background-color: #e7e8e9;text-align:left; font-size:14px; font-weight:400; line-height:18px "><strong><img src="images/tick.png"><?php echo JText::_('LATEST_CHANGES'); ?>: </strong></td>
		<tr>
			<td colspan="2" style="padding-left:20px" align="justify">
---------------- 3.0.6 Released -- [22-August-2012] --------------------<br />
Added Community Builder Plug-in<br/>
---------------- 3.0.5 Released -- [10-July-2012] --------------------<br />
# Added the integration with AlphaUserPoints with Joomla 1.5 - 2.5.<br />
---------------- 3.0.4 Released -- [30-April-2012] --------------------<br />
# Added the ability to translate Back-end of the component to the needed language.<br />
---------------- 3.0.3 Released -- [27-March-2012] --------------------<br />
Joomla! 2.5 compatible release<br/>
---------------- 3.0.2 Released -- [17-November-2011] --------------------<br />
# Added: Compulsory button in questions list <br/>
---------------- 3.0.1 Released -- [17-August-2011] --------------------<br />
Joomla 1.7 compatible version<br />
---------------- 3.0.0 Released -- [16-April-2011] --------------------<br />
^ Changed version numeration. Version 2.2.6 renamed to 3.0.0<br />
---------------- 2.2.6 Released -- [21-October-2010] ------------------<br />
^ Changed : Menu management is redone to support standard way in Joomla 1.5 (it's possible to create a menu item for a survey or category).<br />
---------------- 2.2.5 Released -- [12-October-2010] ------------------<br />
^ Added : New plugin for editor allows easily to insert a survey in article.<br />
---------------- 2.2.4 Released -- [16-August-2010] ------------------<br />
^ Added : Brazilian portuguese language file (pt-BR.com_surveyforce.ini).<br />
---------------- 2.2.3 Released -- [5-August-2010] ------------------<br />
^ Changed : SurveyForce language system to Joomla! default language sustem (with ini-files).<br />
---------------- 2.2.2 Released -- [14-July-2010] ------------------<br />
# Added : Integration with JomSocial (with special plugin).<br />
^ Changed : Improved graphical view of user results (pie/bar charts).<br />
# Fixed : Some bugs.<br />
			</td>
		</tr>
	</table>
	<?php
}
?>