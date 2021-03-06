<?php
/**
* Survey Force component for Joomla
* @version $Id: surveyforce.class.php 2009-11-16 17:30:15
* @package Survey Force
* @subpackage surveyforce.class.php
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );

class mos_Survey_Force_Template extends mosDBTable {
	var $id 				= null;
	var $sf_name	 		= null;


	function mos_Survey_Force_Template() {
		global $database;
		$this->mosDBTable( '#__survey_force_templates', 'id', $database );
	}

	function check() {	
		global $database, $my;
		return true;
	}
}

class mos_Survey_Force_Cat extends mosDBTable {
	var $id 				= null;
	var $sf_catname	 		= null;
	var $sf_catdescr 		= null;
	var $user_id 			= null;

	function mos_Survey_Force_Cat() {
		global $database;
		$this->mosDBTable( '#__survey_force_cats', 'id', $database );
	}

	function check() {	
		global $database, $my;
		
		if ($this->user_id == 0)
			$this->user_id = $my->id;
			
		$query = "SELECT id FROM `#__survey_force_cats` "
				." WHERE sf_catname = '{$this->sf_catname}' ".($this->id > 0? " AND id <> '{$this->id}'":'');
		$database->SetQuery($query);
		$user = intval($database->LoadResult());
		if (intval($database->LoadResult()) > 0) {
			$this->_error = 'Category with such name already exist.';
			return false;
 		}
		return true;
	}
}

class mos_Survey_Force_Survey extends mosDBTable {
	var $id 				= null;
	var $sf_name			= null;
	var $sf_descr			= null;
	var $sf_image			= null;
	var $sf_cat				= null;
	var $sf_lang			= null;
	var $sf_date			= null;
	var $sf_author			= null;
	var $sf_public			= null;
	var $sf_invite			= null;
	var $sf_reg				= null;
	var $sf_friend			= null;
	var $published	 		= null;
	var $sf_fpage_type		= null;
	var $sf_fpage_text		= '<strong>End of the survey - Thank you for your time.</strong>';
	var $sf_special			= null;
	var $sf_auto_pb 		= null;
	var $sf_progressbar		= null;
	var $sf_progressbar_type = null;
	var $sf_use_css 		= null;
	var $sf_enable_descr	= 1;
	var $sf_reg_voting 		= 0;
	var $sf_friend_voting	= 0;
	var $sf_inv_voting 		= 1;
	var $sf_template		= 1;
	var $sf_pub_voting		= 0;
	var $sf_pub_control		= 0;
	var $surv_short_descr	= '';
	var $sf_after_start		= 0;
	var $sf_anonymous		= 0;
	var $sf_random			= 0;
	
	function mos_Survey_Force_Survey() {
		global $database;
		$this->mosDBTable( '#__survey_force_survs', 'id', $database );
	}

	function check() {
		$this->sf_name = trim($this->sf_name);
		$this->sf_descr = trim($this->sf_descr);
		$this->sf_fpage_text = trim($this->sf_fpage_text);		 
		return true;
	}
}

class mos_Survey_Force_Sections extends mosDBTable {
	var $id 				= null;
	var $sf_name			= null;
	var $sf_survey_id		= null;
	var $ordering 			= null;
	var $addname			= null;


	function mos_Survey_Force_Sections() {
		global $database;
		$this->mosDBTable( '#__survey_force_qsections', 'id', $database );
				
	}

	function check() {
		return true;
	}
}

class mos_Survey_Force_Question extends mosDBTable {
	var $id 				= null;
	var $sf_survey			= null;
	var $sf_qtype			= null;
	var $sf_qtext			= null;
	var $sf_impscale		= null;
	var $sf_rule			= null;
	var $sf_fieldtype		= null;
	var $ordering			= null;
	var $sf_compulsory		= null;
	var $sf_section_id		= null;
	var $published			= null;
	var $sf_qstyle			= null;
	var $sf_num_options		= null;
	var $sf_default_hided	= null;
	
	function mos_Survey_Force_Question() {
		global $database;
		$this->mosDBTable( '#__survey_force_quests', 'id', $database );
	}

	function check() {
		$this->sf_qtext = trim($this->sf_qtext);
		return true;
	}
	
	function moves( $dirn, $where='' ) {
		$k = $this->_tbl_key;
		$row = 1;
		while( $row != null) {
			$sql = "SELECT $this->_tbl_key, ordering FROM $this->_tbl";
	
			if ($dirn < 0) {
				$sql .= "\n WHERE ordering < $this->ordering ";
				$sql .= ($where ? "\n	AND $where" : '');
				$sql .= "\n ORDER BY ordering DESC";
				$sql .= "\n LIMIT 1";
			} else if ($dirn > 0) {
				$sql .= "\n WHERE ordering > $this->ordering ";
				$sql .= ($where ? "\n	AND $where" : '');
				$sql .= "\n ORDER BY ordering";
				$sql .= "\n LIMIT 1";
			} else {
				$sql .= "\nWHERE ordering = $this->ordering  ";
				$sql .= ($where ? "\n AND $where" : '');
				$sql .= "\n ORDER BY ordering";
				$sql .= "\n LIMIT 1";
			}
	
			$this->_db->setQuery( $sql );	
	
			$row = null;
			if ($this->_db->loadObject( $row )) {
				$query = "UPDATE $this->_tbl"
				. "\n SET ordering = '$row->ordering'"
				. "\n WHERE $this->_tbl_key = '". $this->$k ."'"
				;
				$this->_db->setQuery( $query );
	
				if (!$this->_db->query()) {
					$err = $this->_db->getErrorMsg();
					die( $err );
				}	
	
				$query = "UPDATE $this->_tbl"
				. "\n SET ordering = '$this->ordering'"
				. "\n WHERE $this->_tbl_key = '". $row->$k. "'"
				;
				$this->_db->setQuery( $query );	
	
				if (!$this->_db->query()) {
					$err = $this->_db->getErrorMsg();
					die( $err );
				}
	
				$this->ordering = $row->ordering;
			} else {
				$query = "UPDATE $this->_tbl"
				. "\n SET ordering = '$this->ordering'"
				. "\n WHERE $this->_tbl_key = '". $this->$k ."'"
				;
				$this->_db->setQuery( $query );	
	
				if (!$this->_db->query()) {
					$err = $this->_db->getErrorMsg();
					die( $err );
				}
			}
		}//while
	}

}

class mos_Survey_Force_Field extends mosDBTable {
	var $id 				= null;
	var $quest_id			= null;
	var $ftext				= null;
	var $alt_field_id		= null;
	var $is_main			= null;
	var $is_true			= null;
	var $ordering			= null;

	function mos_Survey_Force_Field() {
		global $database;
		$this->mosDBTable( '#__survey_force_fields', 'id', $database );
	}
	
	function check() {
		$this->ftext = trim($this->ftext);
		return true;
	}
}

class mos_Survey_Force_Scale_Field extends mosDBTable {
	var $id 				= null;
	var $quest_id			= null;
	var $stext				= null;
	var $ordering			= null;

	function mos_Survey_Force_Scale_Field() {
		global $database;
		$this->mosDBTable( '#__survey_force_scales', 'id', $database );
	}
	
	function check() {
		$this->stext = trim($this->stext);
		return true;
	}
}

class mos_Survey_Force_Rule_Field extends mosDBTable {
	var $id 				= null;
	var $quest_id			= null;
	var $answer_id			= null;
	var $next_quest_id		= null;
	var $alt_field_id = null;
	var $priority = null;
	
	function mos_Survey_Force_Rule_Field() {
		global $database;
		$this->mosDBTable( '#__survey_force_rules', 'id', $database );
	}
	
	function check() {
		return true;
	}
}
class mos_Survey_Force_ListUsers extends mosDBTable {
	var $id 				= null;
	var $listname			= null;
	var $survey_id			= null;
	var $date_created		= null;
	var $date_invited		= null;
	var $date_remind		= null;
	var $is_invited			= null;
	var	$sf_author_id 		= null;
	
	function mos_Survey_Force_ListUsers() {
		global $database;
		$this->mosDBTable( '#__survey_force_listusers', 'id', $database );
	}
	
	function check() {
		global $database;
		
		$query = "SELECT id FROM #__survey_force_listusers "
				." WHERE listname = '{$this->listname}' ".($this->id > 0? " AND id <> '{$this->id}'":'');
		$database->SetQuery($query);
		$user = intval($database->LoadResult());
		if (intval($database->LoadResult()) > 0) {
			$this->_error = 'List with such name already exist.';
			return false;
 		}
		return true;
	}
}

class mos_Survey_Force_UserInfo extends mosDBTable {
	var $id 				= null;
	var $name				= null;
	var $lastname			= null;
	var $email				= null;
	var $list_id			= null;
	var $invite_id			= null;

	function mos_Survey_Force_UserInfo() {
		global $database;
		$this->mosDBTable( '#__survey_force_users', 'id', $database );
	}
	
	function check() {
		global $database;
		
		$query = "SELECT id FROM #__survey_force_users "
				." WHERE name = '{$this->name}' AND lastname = '{$this->lastname}' "
				." AND email = '{$this->email}' AND list_id = '{$this->list_id}' ";
		$database->SetQuery($query);
		$user = intval($database->LoadResult());
		if ($user > 0) {
			$this->_error = 'User with such name, lastname and email already in list.';
			return false;
		}
		return true;
	}
}

class mos_Survey_Force_Email extends mosDBTable {
	var $id 				= null;
	var $email_subject		= null;
	var $email_body			= null;
	var $email_reply		= null;
	var $user_id			= null;

	function mos_Survey_Force_Email() {
		global $database;
		$this->mosDBTable( '#__survey_force_emails', 'id', $database );
	}
	
	function check() {
		return true;
	}
}

class mos_Survey_Force_Label extends mosDBTable {
	var $id 				= null;
	var $lang_file			= null;

	function mos_Survey_Force_Label() {
		global $database;
		$this->mosDBTable( '#__survey_force_labels', 'id', $database );
	}
	
	function check() {
		// check for valid client email
		global $database;
		
		$query = "SELECT count(*) FROM #__survey_force_labels WHERE id <> '".$this->id."' and lang_file = '".$this->lang_file."'";
		$database->SetQuery( $query );
		$items_count = $database->LoadResult();
		if ($items_count > 0) {
			$this->_error = 'This name for Labels is already exist';
			return false;
		} 
		if ((trim($this->lang_file == '')) || (preg_match("/[0-9a-z]/", $this->lang_file )==false)) {
			$this->_error = 'Please enter valid Labels name';
			return false;
		} 
		return true;
	}
}

class mos_Survey_Force_IScale extends mosDBTable {
	var $id 				= null;
	var $iscale_name 		= null;

	function mos_Survey_Force_IScale() {
		global $database;
		$this->mosDBTable( '#__survey_force_iscales', 'id', $database );
	}

	function check() {
		return true;
	}
}
class mos_Survey_Force_IScaleField extends mosDBTable {
	var $id 				= null;
	var $iscale_id	 		= null;
	var $isf_name	 		= null;
	var $ordering	 		= null;

	function mos_Survey_Force_IScaleField() {
		global $database;
		$this->mosDBTable( '#__survey_force_iscales_fields', 'id', $database );
	}

	function check() {
		return true;
	}
}
//Classes for CSV import
class DeImportFieldDescriptor {
	var $name			= '';
	var $required		= FALSE;
	var $defaultValue	= NULL;
	
	function DeImportFieldDescriptor($name, $required = FALSE, $defaultValue = NULL) {
		$this->name				= $name;
		$this->required			= $required;
		$this->defaultValue		= $defaultValue;
	}
	
	function getName() {
		return $this->name;
	}
	
	function isRequired() {
		return $this->required;
	}
	
	function getDefaultValue() {
		return $this->defaultValue;
	}
}

class DeImportFieldDescriptors {
	var $fieldDescriptorsByName		= array();
	
	function addRequired($name) {
		$this->fieldDescriptorsByName[$name]	= new DeImportFieldDescriptor($name, TRUE);
	}
	
	function addOptional($name, $defaultValue = NULL) {
		$this->fieldDescriptorsByName[$name]	= new DeImportFieldDescriptor($name, FALSE, $defaultValue);
	}
	
	function get($name) {
		$result	= NULL;
		if (isset($this->fieldDescriptorsByName[$name])) {
			$result	= $this->fieldDescriptorsByName[$name];
		}
		return $result;
	}
	
	function getFieldNames() {
		$a		= array();
		foreach(array_keys($this->fieldDescriptorsByName) as $fieldName) {
			$a[]	= $fieldName;
		}
		return $a;
	}
	
	function getRequiredFieldNames() {
		$a		= array();
		foreach(array_keys($this->fieldDescriptorsByName) as $fieldName) {
			$fieldDescriptor	= $this->fieldDescriptorsByName[$fieldName];
			if ($fieldDescriptor->isRequired()) {
				$a[]	= $fieldName;
			}
		}
		return $a;
	}
	
	function contains($name) {
		return isset($this->fieldDescriptorsByName[$name]);
	}
	
	function isRequired($name) {
		$fieldDescriptor	= $this->get($name);
		return ($fieldDescriptor != NULL ? $fieldDescriptor->isRequired() : FALSE);
	}
	
	function getDefaultValue($name) {
		$fieldDescriptor	= $this->get($name);
		return ($fieldDescriptor != NULL ? $fieldDescriptor->getDefaultValue() : FALSE);
	}
	
}

function sf_clearCSVQuotes($str){ 
	$str = trim($str);
	if ($str{0} == '"' && $str{strlen($str)-1} == '"')
		$str = substr($str, 1, strlen($str)-2);
	return $str;
}


class DeCsvLoader {
	
	var $fileName;
	var $delimiter		= ',';
	var $loaded			= FALSE;
	var $fieldNames		= array();
	var $rows			= array();
	var $rowIndex		= 0;
	var $errorMessage	= '';
	var $quote			= '"';
	
	function setFileName($fileName) {
		$this->fileName	= $fileName;
	}
	
	function resetError() {
		$this->setErrorMessage('');
	}
	
	function setDelimiter($delimiter) {
		$this->delimiter	= $delimiter;
	}
	
	function getDelimiter() {
		return $this->delimiter;
	}
	
	function setErrorMessage($errorMessage) {
		$this->errorMessage		= $errorMessage;
	}
	
	function getErrorMessage() {
		return $this->errorMessage;
	}
	
	function load() {
		$this->resetError();
		$this->rowIndex		= 0;
		$this->rows			= array();
		$this->fieldNames	= array();
		$this->loaded		= FALSE;
		if ($this->fileName == '') {
			$this->setErrorMessage('file name missing');
			return FALSE;
		}
		$this->rows		= file($this->fileName);
		if ($this->rows === FALSE) {
			$this->rows	= array();
			$this->setErrorMessage('unable to read file');
			return FALSE;
		}
		if (count($this->rows) < 1) {
			$this->setErrorMessage('header missing');
			return FALSE;
		}
		$this->fieldNames	= $this->getNextValues(FALSE);
		if ($this->fieldNames === FALSE) {
			$this->fieldNames	= array();
			return FALSE;
		}
		$this->loaded	= TRUE;
		return TRUE;
	}
	
	function isEof() {
		return ($this->rowIndex >= count($this->rows));
	}
	
	function getNextRow() {
		if ($this->isEof()) {
			$this->setErrorMessage('end of file reached');
			return FALSE;
		}
		return rtrim($this->rows[$this->rowIndex++]);
	}
	
	function clearQuotes($str){ 
		$str = trim($str);
		if ($str{0} == $this->quote && $str{strlen($str)-1} == $this->quote)
			$str = substr($str, 1, strlen($str)-2);
		return $str;
	}
	
	function getNextValues($fieldNameKeys = TRUE) {
		$row	= $this->getNextRow();
		if ($row === FALSE) {
			return FALSE;
		}
		$a	= explode($this->delimiter, $row);
		$a = array_map("sf_clearCSVQuotes", $a);
		
		if (($fieldNameKeys) && (count($this->fieldNames) > 0)) {
			$a2		= array();
			foreach($this->fieldNames as $k => $fieldName) {
				if (isset($a[$k])) {
					$a2[$fieldName]		= $a[$k];
				}
			}
			return $a2;
		} else {
			return $a;
		}
	}
	
	function getLastLineNumber() {
		return $this->rowIndex;
	}
	
	function getFieldNames() {
		return $this->fieldNames;
	}
	
	function setFieldNames($fieldNames) {
		$this->fieldNames	= $fieldNames;
	}
} 

class mos_Survey_Force_Config extends SF_Object {
	var $config_vars = array();
	
	function mos_Survey_Force_Config( ) {
		$this->initConfig();
	}

	function __construct( ) {
		$this->initConfig();
	}

	function initConfig() {
		global $database, $my, $mainframe, $front_end;
		$query = "SELECT * FROM #__survey_force_config";
		$database->SetQuery( $query );
		$cfg_data = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
		foreach ($cfg_data as $cfg_param) {
			$str_var = 'cfg_'.$cfg_param->config_var;
			$this->config_vars[$str_var] = $cfg_param->config_value;
		}
		
		if ($front_end && $this->get('sf_enable_jomsocial_integration')) {
			$query = "SELECT id FROM #__survey_force_authors WHERE user_id = '".$my->id."'";
			$database->SetQuery( $query );
			if ($database->LoadResult()) {
				$this->set('sf_enable_jomsocial_integration', 0);
			}
		}
	}

	function &get($name, $default = null) {
		$str_var = 'cfg_'.$name;
		if (isset($this->config_vars[$str_var])) {
			return $this->config_vars[$str_var];
		}
		return $default;
	}

	function set($name, $value) {
		$str_var = 'cfg_'.$name;
		$this->config_vars[$str_var] = $value;
		return $value;
	}
}

class survey_Force_Adm_Config
{
	var $color_cont = null;
	var $color_drag = null;
	var $color_highlight = null;

	var $b_axis_color1 = 'C9C9C9';
	var $b_axis_color2 = '9E9E9E';
	var $b_aqua_color1 = 'F2F2F2';
	var $b_aqua_color2 = 'E7E7E7';
	var $b_aqua_color3 = 'EFEFEF';
	var $b_aqua_color4 = 'FDFDFD';
	var $b_bar_color1 = '2A47B5';
	var $b_bar_color2 = '21388F';
	var $b_bar_color3 = 'ACACD2';
	var $b_bar_color4 = '75758F';
	
	var $p_axis_color1 = 'C9C9C9';
	var $p_axis_color2 = '9E9E9E';
	var $p_aqua_color1 = 'F2F2F2';
	var $p_aqua_color2 = 'E7E7E7';
	var $p_aqua_color3 = 'EFEFEF';
	var $p_aqua_color4 = 'FDFDFD';
	
	var $b_width = 600;
	var $p_width = 600;
	var $b_height = 200;
	var $p_height = 250;
	var $sf_result_type = 'Bar';
	var $sf_enable_lms_integration = 0;
	var $sf_enable_jomsocial_integration = 0;
	var $sf_mail_pause = 0;
	var $sf_mail_count = 0;
	var $sf_mail_maximum = 0;
	
	var $fe_lang = 'default';

	var $color_border = '000000';
	var $color_text = '333333';
	var $color_completed = 'cccccc';
	var $color_uncompleted = 'ffffff';
	
	var $sf_an_mail = 0;
	var $sf_an_mail_others = 0;
	var $sf_an_mail_other_emails = 0;
	var $sf_an_mail_subject = 0;
	var $sf_an_mail_text = 0;
	
	var $sf_show_dev_info = 1;
	
	var $sf_force_ssl = 0;
	
	var $_error = null;
	
	function loadFromDb(){
		global $database;
		$query = "SELECT * FROM `#__survey_force_config`";
		$database -> setQuery($query);
		$rows = ($database->LoadObjectList() == null? array(): $database->LoadObjectList());
		$rows1= array();
		foreach ($rows as $row){
			$rows1[$row->config_var] = $row->config_value;
		}
		
		$this->bind($rows1);
	}
	function getPublicVars() {
		$public = array();
		
		$vars = array_keys( get_class_vars( get_class( $this ) ) );
		sort( $vars );
		foreach ($vars as $v) {
			if ($v{0} != '_') {
				$public[] = $v;
			}
		}
		return $public;
	}

	function bind( $array, $ignore='' ) {
		if (!is_array( $array )) {
			$this->_error = strtolower(get_class( $this )).'::bind failed.';
			return false;
		} else {
			return mosBindArrayToObject( $array, $this, $ignore );
		}
	}
	function getError() {
		return $this->_error;
	}
	
	function saveToDb(){
		global $database;
		$query = "DELETE FROM `#__survey_force_config` WHERE config_var != 'sf_version' ";
		$database -> setQuery($query);
		$database->query();
		
		$vars = $this->getPublicVars();
		foreach ($vars as $v) {
			
				$query = "INSERT INTO `#__survey_force_config` (config_var, config_value) "
						."VALUES ('".$v."', '".$this->$v."')";
				$database -> setQuery($query);
				$database->query();
		}
		return true;

	}
}


class SF_Object
{
	/**
	 * A hack to support __construct() on PHP 4
	 * Hint: descendant classes have no PHP4 class_name() constructors,
	 * so this constructor gets called first and calls the top-layer __construct()
	 * which (if present) should call parent::__construct()
	 *
	 * @return Object
	 */
	function SF_Object()
	{
		$args = func_get_args();
		call_user_func_array(array(&$this, '__construct'), $args);
	}

	/**
	 * Class constructor, overridden in descendant classes.
	 *
	 * @access	protected
	 */
	function __construct() {}

	/**
	* @param string The name of the property
	* @param mixed The value of the property to set
	*/
	function set( $property, $value=null ) {
		$this->$property = $value;
	}

	/**
	* @param string The name of the property
	* @param mixed  The default value
	* @return mixed The value of the property
	*/
	function get($property, $default=null)
	{
		if(isset($this->$property)) {
			return $this->$property;
		}
		return $default;
	}

	/**
	 * Returns an array of public properties
	 *
	 * @return array
	 */
	function getPublicProperties()
	{
		static $cache = null;

		if (is_null( $cache )) {
			$cache = array();
			foreach (get_class_vars( get_class( $this ) ) as $key=>$val) {
				if (substr( $key, 0, 1 ) != '_') {
					$cache[] = $key;
				}
			}
		}
		return $cache;
	}

	/**
	 * Object-to-string conversion.
	 * Each class can override it as necessary.
	 *
	 * @return string This name of this class
	 */
	function toString()
	{
		return get_class($this);
	}
}

?>