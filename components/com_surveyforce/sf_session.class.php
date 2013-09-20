<?php 
/**
* Survey Force component for Joomla
* @version $Id: sf_session.class.php 2009-11-16 17:30:15
* @package Survey Force
* @subpackage sf_session.class.php
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

/**
 * This class handles the session initialization, restart
 * and the re-init of a session after redirection to a Shared SSL domain
 *
 */
class SF_Session extends SF_Object {

	var $component_name = "option=com_surveyforce";
	var $_session_name = 'surveyforce';
	/**
     * Initialize the Session environment for VirtueMart
     *
     */
	function SF_Session( ) {
		$this->initSession();
	}

	function __construct( ) {
		$this->initSession();
	}

	/**
     * Initiate the Session
     *
     */
	function initSession() {
		global $my, $mainframe;
		
		// Some servers start the session before we can, so close those and start again		
		if(!empty($_SESSION)) {
			session_write_close();
			unset( $_SESSION );
		}
		if( empty( $_SESSION )) {
			// Session not yet started!			
			session_name( $this->_session_name );
			
			if (session_id() == "") @session_start();			
		}
		
		if (empty($_SESSION['jlms_auth_user'])) {
			$_SESSION['jlms_auth_user'] = $my->id;
		} else {
			if( ( @$_SESSION['jlms_auth_user'] != $my->id )) {
				// If the user ID has changed (after logging out)
				// empty the session!
				$this->emptySession();
			}
		}

	}
		
	/**
	 * Returns the Joomla/Mambo Session ID
	 *
	 */
	function getSessionId() {
		global $mainframe;
		// Joomla >= 1.0.8
		if( is_callable( array( 'mosMainframe', 'sessionCookieName'))) {			
			// Session Cookie `name`
			$sessionCookieName 	= mosMainFrame::sessionCookieName();
			// Get Session Cookie `value`
			$sessionCookie 		= mosGetParam( $_COOKIE, $sessionCookieName, null );
			// Session ID / `value`
			return mosMainFrame::sessionCookieValue( $sessionCookie );
		}
		// Mambo 4.6
		elseif( is_callable( array('mosSession', 'getCurrent' ))) {
			$session =& mosSession::getCurrent();
			return $session->session_id;
		}
		// Mambo <= 4.5.2.3 and Joomla <= 1.0.7
		elseif( !empty( $mainframe->_session->session_id )) {
			// Set the sessioncookie if its missing
			// this is needed for joomla sites only
			return $mainframe->_session->session_id;
		}
		
	}
	function restartSession( $sid = '') {
		
		// Save the session data and close the session
		session_write_close();
		
		// Prepare the new session
		if( $sid != '' ) {
			session_id( $sid );
		}
		session_name( $this->_session_name );
		// Start the new Session.
		session_start();
		
	}
	function emptySession() {
		global $mainframe;
		$_SESSION = array();
		$_COOKIE[$this->_session_name] = md5( $this->getSessionId() );
	}
	 /**
     * Get DATA from session
     */
	function &get($name, $default = null) {
		if (isset($_SESSION[$name])) {
			return $_SESSION[$name];
		}
		return $default;
	}
    /**
     * Save DATA into session
     */
	function set($name, $value) {
		$old = isset($_SESSION[$name]) ?  $_SESSION[$name] : null;
		if (null === $value) {
			unset($_SESSION[$name]);
		} else {
			$_SESSION[$name] = $value;
		}
		return $old;
	}
	/**
	* Check wheter a session value exists
	*/
	function has( $name ) {
		return isset( $_SESSION[$name] );
	}
	/**
	* Unset data from session
	*/
	function clear( $name ) {
		$value	=	null;
		if( isset( $_SESSION[$name] ) ) {
			$value	=	$_SESSION[$name];
			unset( $_SESSION[$name] );
		}
		return $value;
	}
} // end of class session
?>
