<?php
/**
 * Security Configuration functions
 *
 * @since 3.4
 *
 * @author @dpredster
 *
 * @package RosarioSIS
 * @subpackage functions
 */

function SecurityConfig( $item )
{
	global $_ROSARIO,
		$DefaultSyear;

	if ( ! $item )
	{
		return '';
	}

	// Get Security settings.
	if ( !isset( $_ROSARIO['Security_Settings'][ (string) $item ] ) )
	{
		// General (for every school) Security Settings are stored with SCHOOL_ID=0.
		$school_where = "SCHOOL_ID='0'";

		// If user logged in.
		if ( UserSchool() > 0 )
		{
			$school_where = "SCHOOL_ID='" . UserSchool() . "' OR " . $school_where;
		}

		$_ROSARIO['Security_Settings'] = DBGet( DBQuery( "SELECT TITLE, SETTINGS_VALUE
			FROM SECURITY_SETTINGS
			WHERE " . $school_where ), array(), array( 'TITLE' ) );

		$_ROSARIO['Security_Settings']['SYEAR'][1]['SETTINGS_VALUE'] = $DefaultSyear;
	}
	return $_ROSARIO['Security_Settings'][ (string) $item ][1]['SETTINGS_VALUE'];
}