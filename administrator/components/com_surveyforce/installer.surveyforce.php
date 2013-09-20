<?php

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );
global $mosConfig_absolute_path;
//require_once($mosConfig_absolute_path . "/administrator/components/com_installer/installer.class.php");


class SF_Installer {
	// name of the XML file with installation information
	var $i_installfilename	= "";
	var $i_installarchive	= "";
	var $i_installdir		= "";
	var $i_iswin			= false;
	var $i_errno			= 0;
	var $i_error			= "";
	var $i_installtype		= "";
	var $i_unpackdir		= "";
	var $i_docleanup		= true;

	/** @var string The directory where the element is to be installed */
	var $i_elementdir 		= '';
	/** @var string The name of the Joomla! element */
	var $i_elementname 		= '';
	/** @var string The name of a special atttibute in a tag */
	var $i_elementspecial 	= '';
	/** @var object A DOMIT XML document */
	var $i_xmldoc			= null;
	var $i_hasinstallfile 	= null;
	var $i_installfile 		= null;

	/**
	* Constructor
	*/
	function SF_Installer() {
		$this->i_iswin = (substr(PHP_OS, 0, 3) == 'WIN');
	}
	/**
	* Uploads and unpacks a file
	* @param string The uploaded package filename or install directory
	* @param boolean True if the file is an archive file
	* @return boolean True on success, False on error
	*/
	function upload($p_filename = null, $p_unpack = true) {
		$this->i_iswin = (substr(PHP_OS, 0, 3) == 'WIN');
		$this->installArchive( $p_filename );

		if ($p_unpack) {
			if ($this->extractArchive()) {
				return $this->findInstallFile();
			} else {
				return false;
			}
		}
	}
	/**
	* Extracts the package archive file
	* @return boolean True on success, False on error
	*/
	function extractArchive() {
		global $mosConfig_absolute_path;

		$base_Dir 		= mosPathName( $mosConfig_absolute_path . '/media' );

		$archivename 	= $base_Dir . $this->installArchive();
		$tmpdir 		= uniqid( 'install_' );

		$extractdir 	= mosPathName( $base_Dir . $tmpdir );
		$archivename 	= mosPathName( $archivename, false );

		$this->unpackDir( $extractdir );

		
		if (preg_match('/.zip$/', $archivename)) {
			// Extract functions
			require_once( $mosConfig_absolute_path . '/administrator/includes/pcl/pclzip.lib.php' );
			require_once( $mosConfig_absolute_path . '/administrator/includes/pcl/pclerror.lib.php' );
			//require_once( $mosConfig_absolute_path . '/administrator/includes/pcl/pcltrace.lib.php' );
			//require_once( $mosConfig_absolute_path . '/administrator/includes/pcl/pcltar.lib.php' );
			$zipfile = new PclZip( $archivename );
			if($this->isWindows()) {
				define('OS_WINDOWS',1);
			} else {
				define('OS_WINDOWS',0);
			}

			$ret = $zipfile->extract( PCLZIP_OPT_PATH, $extractdir );
			if($ret == 0) {
				$this->setError( 1, JText::_('UNRECOVERABLE_ERROR').$zipfile->errorName(true));
				return false;
			}
		} else {
			require_once( $mosConfig_absolute_path . '/includes/Archive/Tar.php' );
			$archive = new Archive_Tar( $archivename );
			$archive->setErrorHandling( PEAR_ERROR_PRINT );

			if (!$archive->extractModify( $extractdir, '' )) {
				$this->setError( 1, JText::_('EXTRACT_ERROR') );
				return false;
			}
		}

		$this->installDir( $extractdir );

		// Try to find the correct install dir. in case that the package have subdirs
		// Save the install dir for later cleanup
		$filesindir = mosReadDirectory( $this->installDir(), '' );

		if (count( $filesindir ) == 1) {
			if (is_dir( $extractdir . $filesindir[0] )) {
				$this->installDir( mosPathName( $extractdir . $filesindir[0] ) );
			}
		}
		return true;
	}
	/**
	* Tries to find the package XML file
	* @return boolean True on success, False on error
	*/
	function findInstallFile() {
		$found = false;
		// Search the install dir for an xml file
		$files = mosReadDirectory( $this->installDir(), '.xml$', true, true );

		if (count( $files ) > 0) {
			foreach ($files as $file) {
				$packagefile = $this->isPackageFile( $file );
				if (!is_null( $packagefile ) && !$found ) {
					$this->xmlDoc( $packagefile );
					return true;
				}
			}
			$this->setError( 1, JText::_('ERROR_COULD_NOT_FIND_JOOMLA_XML_SETUP_FILE') );
			return false;
		} else {
			$this->setError( 1, JText::_('ERROR_COULD_NOT_FIND_XML_SETUP_FILE') );
			return false;
		}
	}
	/**
	* @param string A file path
	* @return object A DOMIT XML document, or null if the file failed to parse
	*/
	function isPackageFile( $p_file ) {
		$xmlDoc = new DOMIT_Lite_Document();
		$xmlDoc->resolveErrors( true );

		if (!$xmlDoc->loadXML( $p_file, false, true )) {
			return null;
		}
		$root = &$xmlDoc->documentElement;

		if ($root->getTagName() != 'mosinstall') {
			return null;
		}
		// Set the type
		$this->installType( $root->getAttribute( 'type' ) );
		$this->installFilename( $p_file );
		return $xmlDoc;
	}
	/**
	* Loads and parses the XML setup file
	* @return boolean True on success, False on error
	*/
	function readInstallFile() {

		if ($this->installFilename() == "") {
			$this->setError( 1, JText::_('NO_FILENAME_SPECIFIED') );
			return false;
		}

		$this->i_xmldoc = new DOMIT_Lite_Document();
		$this->i_xmldoc->resolveErrors( true );
		if (!$this->i_xmldoc->loadXML( $this->installFilename(), false, true )) {
			return false;
		}
		$root = &$this->i_xmldoc->documentElement;

		// Check that it's am installation file
		if ($root->getTagName() != 'mosinstall') {
			$this->setError( 1, JText::_('FILE'). ":" . $this->installFilename() . JText::_('IS_NOT_VALID_JOOMLA_INSTALLATION_FILE'));
			return false;
		}

		$this->installType( $root->getAttribute( 'type' ) );
		return true;
	}
	/**
	* Abstract install method
	*/
	function install() {
		die( JText::_('METHOD_INSTALL_CANNOT_BE_CALLED_BY_CLASS') . strtolower(get_class( $this )) );
	}
	/**
	* Abstract uninstall method
	*/
	function uninstall() {
		die( JText::_('METHOD_UNINSTALL_CANNOT_BE_CALLED_BY_CLASS') . strtolower(get_class( $this )) );
	}
	/**
	* return to method
	*/
	function returnTo( $option, $element ) {
		return "index2.php?option=$option&element=$element";
	}
	/**
	* @param string Install from directory
	* @param string The install type
	* @return boolean
	*/
	function preInstallCheck( $p_fromdir, $type ) {

		if (!is_null($p_fromdir)) {
			$this->installDir($p_fromdir);
		}

		if (!$this->installfile()) {
			$this->findInstallFile();
		}

		if (!$this->readInstallFile()) {
			$this->setError( 1, JText::_('INSTALLATION_FILE_NOT_FOUND').':<br />' . $this->installDir() );
			return false;
		}

		if ($this->installType() != $type) {
			$this->setError( 1, JText::_('XML_SETUP_FILE_NOT_FOR').$type );
			return false;
		}

		// In case there where an error doring reading or extracting the archive
		if ($this->errno()) {
			return false;
		}

		return true;
	}
	/**
	* @param string The tag name to parse
	* @param string An attribute to search for in a filename element
	* @param string The value of the 'special' element if found
	* @param boolean True for Administrator components
	* @return mixed Number of file or False on error
	*/
	function parseFiles( $tagName='files', $special='', $specialError='', $adminFiles=0 ) {
		global $mosConfig_absolute_path;
		// Find files to copy
		$xmlDoc =& $this->xmlDoc();
		$root =& $xmlDoc->documentElement;

		$files_element =& $root->getElementsByPath( $tagName, 1 );
		if (is_null( $files_element )) {
			return 0;
		}

		if (!$files_element->hasChildNodes()) {
			// no files
			return 0;
		}
		$files = $files_element->childNodes;
		$copyfiles = array();
		if (count( $files ) == 0) {
			// nothing more to do
			return 0;
		}

		if ($folder = $files_element->getAttribute( 'folder' )) {
			$temp = mosPathName( $this->unpackDir() . $folder );
			if ($temp == $this->installDir()) {
				// this must be only an admin component
				$installFrom = $this->installDir();
			} else {
				$installFrom = mosPathName( $this->installDir() . $folder );
			}
		} else {
			$installFrom = $this->installDir();
		}

		foreach ($files as $file) {
			if (basename( $file->getText() ) != $file->getText()) {
				$newdir = dirname( $file->getText() );

				if ($adminFiles){
					if (!mosMakePath( $this->componentAdminDir(), $newdir )) {
						$this->setError( 1, JText::_('FAILED_TO_CREATE_DIRECTORY'). $this->componentAdminDir() . $newdir);
						return false;
					}
				} else {
					if (!mosMakePath( $this->elementDir(), $newdir )) {
						$this->setError( 1, JText::_('FAILED_TO_CREATE_DIRECTORY'). $this->elementDir()) . $newdir );
						return false;
					}
				}
			}
			$copyfiles[] = $file->getText();

			// check special for attribute
			if ($file->getAttribute( $special )) {
				$this->elementSpecial( $file->getAttribute( $special ) );
			}
		}

		if ($specialError) {
			if ($this->elementSpecial() == '') {
				$this->setError( 1, $specialError );
				return false;
			}
		}

		if ($tagName == 'media') {
			// media is a special tag
			$installTo = mosPathName( $mosConfig_absolute_path . '/images/stories' );
		} else if ($adminFiles) {
			$installTo = $this->componentAdminDir();
		} else {
			$installTo = $this->elementDir();
		}
		$result = $this->copyFiles( $installFrom, $installTo, $copyfiles );

		return $result;
	}
	/**
	* @param string Source directory
	* @param string Destination directory
	* @param array array with filenames
	* @param boolean True is existing files can be replaced
	* @return boolean True on success, False on error
	*/
	function copyFiles( $p_sourcedir, $p_destdir, $p_files, $overwrite=false ) {
		if (is_array( $p_files ) && count( $p_files ) > 0) {
			foreach($p_files as $_file) {
				$filesource	= mosPathName( mosPathName( $p_sourcedir ) . $_file, false );
				$filedest	= mosPathName( mosPathName( $p_destdir ) . $_file, false );

				if (!file_exists( $filesource )) {
					$this->setError( 1, JText::_('FILE').$filesource.JText::_('DOES_NOT_EXIST') );
					return false;
				} else if (file_exists( $filedest ) && !$overwrite) {
					$this->setError( 1, JText::_('THERE_ALREADY_FILE_CALLED') . $filedest . JText::_('ARE_YOU_TRYING_INSTALL_THE_SAME') );
					return false;
				} else {
                                        $path_info = pathinfo($_file);
                                        if (!is_dir( $path_info['dirname'] )){
                                                mosMakePath( $p_destdir, $path_info['dirname'] );
                                        }
					if( !( copy($filesource,$filedest) && mosChmod($filedest) ) ) {
						$this->setError( 1, JText::_('FAILED_TO_COPY_FILE').":". $filesource. JText::_('TO').$filedest);
						return false;
					}
				}
			}
		} else {
			return false;
		}
		return count( $p_files );
	}
	/**
	* Copies the XML setup file to the element Admin directory
	* Used by Components/Modules/Mambot Installer Installer
	* @return boolean True on success, False on error
	*/
	function copySetupFile( $where='admin' ) {
		if ($where == 'admin') {
			return $this->copyFiles( $this->installDir(), $this->componentAdminDir(), array( basename( $this->installFilename() ) ), true );
		} else if ($where == 'front') {
			return $this->copyFiles( $this->installDir(), $this->elementDir(), array( basename( $this->installFilename() ) ), true );
		}
	}

	/**
	* @param int The error number
	* @param string The error message
	*/
	function setError( $p_errno, $p_error ) {
		$this->errno( $p_errno );
		$this->error( $p_error );
	}
	/**
	* @param boolean True to display both number and message
	* @param string The error message
	* @return string
	*/
	function getError($p_full = false) {
		if ($p_full) {
			return $this->errno() . " " . $this->error();
		} else {
			return $this->error();
		}
	}
	/**
	* @param string The name of the property to set/get
	* @param mixed The value of the property to set
	* @return The value of the property
	*/
	function &setVar( $name, $value=null ) {
		if (!is_null( $value )) {
			$this->$name = $value;
		}
		return $this->$name;
	}

	function installFilename( $p_filename = null ) {
		if(!is_null($p_filename)) {
			if($this->isWindows()) {
				$this->i_installfilename = str_replace('/','\\',$p_filename);
			} else {
				$this->i_installfilename = str_replace('\\','/',$p_filename);
			}
		}
		return $this->i_installfilename;
	}

	function installType( $p_installtype = null ) {
		return $this->setVar( 'i_installtype', $p_installtype );
	}

	function error( $p_error = null ) {
		return $this->setVar( 'i_error', $p_error );
	}

	function &xmlDoc( $p_xmldoc = null ) {
		return $this->setVar( 'i_xmldoc', $p_xmldoc );
	}

	function installArchive( $p_filename = null ) {
		return $this->setVar( 'i_installarchive', $p_filename );
	}

	function installDir( $p_dirname = null ) {
		return $this->setVar( 'i_installdir', $p_dirname );
	}

	function unpackDir( $p_dirname = null ) {
		return $this->setVar( 'i_unpackdir', $p_dirname );
	}

	function isWindows() {
		return $this->i_iswin;
	}

	function errno( $p_errno = null ) {
		return $this->setVar( 'i_errno', $p_errno );
	}

	function hasInstallfile( $p_hasinstallfile = null ) {
		return $this->setVar( 'i_hasinstallfile', $p_hasinstallfile );
	}

	function installfile( $p_installfile = null ) {
		return $this->setVar( 'i_installfile', $p_installfile );
	}

	function elementDir( $p_dirname = null )	{
		return $this->setVar( 'i_elementdir', $p_dirname );
	}

	function elementName( $p_name = null )	{
		return $this->setVar( 'i_elementname', $p_name );
	}
	function elementSpecial( $p_name = null )	{
		return $this->setVar( 'i_elementspecial', $p_name );
	}
}

if (!function_exists('cleanupInstall')) {
	function cleanupInstall( $userfile_name, $resultdir) {
		global $mosConfig_absolute_path;
	
		if (file_exists( $resultdir )) {
			deldir( $resultdir );
			unlink( mosPathName( $mosConfig_absolute_path . '/media/' . $userfile_name, false ) );
		}
	}
}
if (!function_exists('deldir')) {
	function deldir( $dir ) {
		$current_dir = opendir( $dir );
		$old_umask = umask(0);
		while ($entryname = readdir( $current_dir )) {
			if ($entryname != '.' and $entryname != '..') {
				if (is_dir( $dir . $entryname )) {
					deldir( mosPathName( $dir . $entryname ) );
				} else {
					@chmod($dir . $entryname, 0757);
					unlink( $dir . $entryname );
				}
			}
		}
		umask($old_umask);
		closedir( $current_dir );
		return rmdir( $dir );
	}
}

class SF_InstallerTemplate extends SF_Installer {

	function install( $p_fromdir = null ) {
		global $mosConfig_absolute_path,$database;

		if (!$this->preInstallCheck( $p_fromdir, 'template' )) {
			return false;
		}

		$xmlDoc 	=& $this->xmlDoc();
		$mosinstall =& $xmlDoc->documentElement;

		$client = 'admin';

		// Set some vars
		$e = &$mosinstall->getElementsByPath( 'name', 1 );
		$this->elementName($e->getText());
		$this->elementDir( mosPathName( $mosConfig_absolute_path
		. '/media/surveyforce/' . strtolower(str_replace(" ","_",$this->elementName())))
		);

		if (!file_exists( $this->elementDir() ) && !mosMakePath( $this->elementDir() )) {
			$this->setError(1, JText::_('FAILED_TO_CREATE_DIRECTORY'). $this->elementDir() );
			return false;
		}

		if ($this->parseFiles( 'files' ) === false) {
			return false;
		}
		if ($this->parseFiles( 'images' ) === false) {
			return false;
		}
		if ($e = &$mosinstall->getElementsByPath( 'description', 1 )) {
			$this->setError( 0, $this->elementName() . '<p>' . $e->getText() . '</p>' );
		}

		return $this->copySetupFile('front');
	}
	function uninstall( $id, $option ) {
		global $database, $mosConfig_absolute_path;
		$tmpl_name = '';
		$database->SetQuery("SELECT `sf_name` FROM `#__survey_force_templates` WHERE id = '".$id."'");
		$tmpl_name = $database->LoadResult();
		// Delete directories
		$path = $mosConfig_absolute_path
		. '/media/surveyforce/' . $tmpl_name;

		$tmpl_name = str_replace( '..', '', $tmpl_name );
		if ($id == 1) {
			survey_force_adm_html::showInstallMessage( JText::_('YOU_CANNOT_REMOVE_THIS_TEMPLATE'), JText::_('UNINSTALL_ERROR'),
				$this->returnTo( $option ) );
			exit();
		} else {
			if (trim( $tmpl_name )) {
				if (is_dir( $path )) {
					$ret = deldir( mosPathName( $path ) ); //function from installer.class.php joomla file
					return $ret;
				} else {
					survey_force_adm_html::showInstallMessage( JText::_('DIRECTORY_DOES_NOT_EXIST_CANNOT_REMOVE_FILES'), JText::_('UNINSTALL_ERROR'),
						$this->returnTo( $option ) );
				}
			} else {
				survey_force_adm_html::showInstallMessage( JText::_('TEMPLATE_ID_EMPTY'), JText::_('UNINSTALL_ERROR'),
					$this->returnTo( $option ) );
				exit();
			}
		}
	}
	function returnTo( $option ) {
		return "index2.php?option=".$option."&task=templates";
	}
	function isPackageFile( $p_file ) {
		$xmlDoc = new DOMIT_Lite_Document();
		$xmlDoc->resolveErrors( true );

		if (!$xmlDoc->loadXML( $p_file, false, true )) {
			return null;
		}
		$root = &$xmlDoc->documentElement;

		if ($root->getTagName() != 'surveyforce_install') {
			return null;
		}
		// Set the type
		$this->installType( $root->getAttribute( 'type' ) );
		$this->installFilename( $p_file );
		return $xmlDoc;
	}
	function readInstallFile() {

		if ($this->installFilename() == "") {
			$this->setError( 1, JText::_('NO_FILENAME_SPECIFIED') );
			return false;
		}

		$this->i_xmldoc = new DOMIT_Lite_Document();
		$this->i_xmldoc->resolveErrors( true );
		if (!$this->i_xmldoc->loadXML( $this->installFilename(), false, true )) {
			return false;
		}
		$root = &$this->i_xmldoc->documentElement;

		// Check that it's an installation file
		if ($root->getTagName() != 'surveyforce_install') {
			$this->setError( 1, JText::_('FILE'). ":" . $this->installFilename() . JText::_('IS_NOT_VALID_SURVEYFORCE_TEMPLATE') );
			return false;
		}

		$this->installType( $root->getAttribute( 'type' ) );
		return true;
	}
}

?>