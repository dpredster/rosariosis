<?php
/**
 * Password Validation functions
 *
 * @since 3.4
 *
 * @author @dpredster
 *
 * @package RosarioSIS
 * @subpackage functions
 *
 * Validate Password Strength
 *
 */
 
	function validate_password( $plain )
	{
			if ( ! $plain )
	{
		return '';
	}
		if( strlen($plain) < SecurityConfig('PASSWORD_MIN_LENGTH') ) 
		{
			$error .= "";
		}

		if( !preg_match_all("$\S*(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$", $plain) ) 
		{
			$error .= "";
		}
		
		$error_mess[] = _('Your password does not meet the password policy requirements!</br>');
		if($error)
		{
			echo ErrorMessage( $error_mess );
		} 
		else 
		{
			$note[] = _( 'Congratulations. Your password have met the password policy requirements.' );
			echo ErrorMessage( $note, 'note' );
			return (string) $plain;
		}
	}
