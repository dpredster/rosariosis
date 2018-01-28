<?php
/**
 * Security Settings
 *
 * @since 3.0
 *
 * @author @dpredster
 *
 * @package RosarioSIS
 * @subpackage modules
 */
 
DrawHeader( ProgramTitle() );
 
	if ( $_REQUEST['modfunc']=='update')
	{

		$sql = '';
		if ( isset( $_REQUEST['values']['SECURITY_SETTINGS'] )
			&& is_array( $_REQUEST['values']['SECURITY_SETTINGS'] ) )
			foreach ( (array) $_REQUEST['values']['SECURITY_SETTINGS'] as $column => $value )
			{
				$sql .= "UPDATE SECURITY_SETTINGS SET
					SETTINGS_VALUE='" . $value . "'
					WHERE TITLE='" . $column . "'";

				// All values are school independent.
				$school_independent_values = array(
					'PASSWORD_MIN_LENGTH',
					'SECURE_PASSWORD',
					'MAX_INACTIVE_MINS',
					'SUSPENSION_DURATION',
				);

				if ( in_array( $column, $school_independent_values ) )
				{
					$sql .= " AND SCHOOL_ID='0';";
				}
				else
				{
					$sql .= " AND SCHOOL_ID='" . UserSchool() . "';";
				}
			}
				
			if ( isset( $_REQUEST['values']['CONFIG'] )
				&& is_array( $_REQUEST['values']['CONFIG'] ) )
			{
				foreach ( (array) $_REQUEST['values']['CONFIG'] as $column => $value )
				{
					$sql .= "UPDATE CONFIG SET
						CONFIG_VALUE='" . $value . "'
						WHERE TITLE='" . $column . "'";

					// Program Title, Program Name, Default Theme, Force Default Theme,
					// Create User Account, Create Student Account, Student email field,
					// Failed login attempts limit, Display Name.
					$school_independant_values = array(
						'FAILED_LOGIN_LIMIT',
					);

					if ( in_array( $column, $school_independant_values ) )
					{
						$sql .= " AND SCHOOL_ID='0';";
					}
					else
					{
						$sql .= " AND SCHOOL_ID='" . UserSchool() . "';";
					}
				}
			}
			if ( $sql != '')
			{
				DBQuery($sql);

				$note[] = button('check') .'&nbsp;'._('The Security Settings have been modified. The page would now reload for the changes to take effect.');
			}

			unset( $_ROSARIO['Config'] ); // update Config var
			unset( $_ROSARIO['Security_Settings'] ); // update SecurityConfig var
	}


		
		unset($_REQUEST['modfunc']);
		unset($_SESSION['_REQUEST_vars']['values']);
		unset($_SESSION['_REQUEST_vars']['modfunc']);
		

	if ( ! $_REQUEST['modfunc'] )
	{
		echo '<form action="Modules.php?modname='.$_REQUEST['modname'].'&modfunc=update" method="POST" enctype="multipart/form-data">';

		if (AllowEdit())
			DrawHeader('',SubmitButton(_('Save')));

		if ( !empty( $note ) )
			// Reloads the page after 5 seconds for changes to take effect.
			echo '<script type="text/javascript">setTimeout(function(){ location.reload(); },5000);</script>';
			echo ErrorMessage( $note, 'note' );

		if ( !empty( $error ) )
			echo ErrorMessage( $error, 'error' );

		echo '<br />';

		PopTable('header','Security Settings Details');
				
		echo '<fieldset><legend>Password Policy</legend><table>';

				echo '<tr><td>' . TextInput(
					SecurityConfig( 'PASSWORD_MIN_LENGTH' ),
					'values[SECURITY_SETTINGS][PASSWORD_MIN_LENGTH]',
					_( 'Minimum Acceptable Password Length.' ) .
						'<div class="tooltip"><i>' .
							_( 'Password lenght must be between 8 and 30 characters.' ) .
						'</i></div>',
					'type=number maxlength=2 size=2 min=8 max=30'
				) . '</td></tr>';		
		
				echo '<tr><td>' . CheckboxInput(
					SecurityConfig( 'SECURE_PASSWORD' ),
					'values[SECURITY_SETTINGS][SECURE_PASSWORD]',
					_( 'Require Passwords To Contain Numbers And Special Characters' ) .
						'<div class="tooltip"><i>' .
							_( 'If checked, password must contain upper and lower case alpha characters, at least one numeric character and at least one special character.' ) .
						'</i></div>',
					'',
					false,
					button( 'check' ),
					button( 'x' )
				) . '</td></tr>';
			
		echo '</table></fieldset>';
		
				// FJ add Security to Configuration.
		echo '<fieldset><legend>' . _( 'Login Management' ) . '</legend><table>';

		// Failed login ban if >= X failed attempts within 10 minutes.
		echo '<tr><td colspan="3">' . TextInput(
					Config( 'FAILED_LOGIN_LIMIT' ),
					'values[CONFIG][FAILED_LOGIN_LIMIT]',
					_( 'Failed Login Attempts Limit' ) .
						'<div class="tooltip"><i>' .
						_( 'Leave the field blank to always allow' ) .
						'</i></div>',
					'type=number maxlength=2 size=2 min=2 max=99'
				) . '</td></tr>';
				
				echo '<tr><td>' . TextInput(
					SecurityConfig( 'SUSPENSION_DURATION' ),
					'values[SECURITY_SETTINGS][SUSPENSION_DURATION]',
					_( 'Time, In Minutes, To Temporarily Suspend Account.' ) .
						'<div class="tooltip"><i>' .
							_( 'Leave the field blank to disable temporary account suspension after failed logins.' ) .
						'</i></div>',
					'type=number maxlength=2 size=2 min=0'
				) . '</td></tr>';
				
		echo '</td></tr></table></fieldset>';
		
		echo '<fieldset><legend>' . _( 'Logout Management' ) . '</legend><table>';		
				echo '<tr><td>' . TextInput(
					SecurityConfig( 'MAX_INACTIVE_MINS' ),
					'values[SECURITY_SETTINGS][MAX_INACTIVE_MINS]',
					_( 'Time, In Minutes, Before Inactivity User Is Automatically Logged Out.' ) .
						'<div class="tooltip"><i>' .
							_( 'Leave the field blank to disable automatic logout.' ) .
						'</i></div>',
				'type=number maxlength=2 size=2 min=0'
			) . '</td></tr>';

		echo '</table></fieldset>';	
		
	}
		PopTable('footer');
		if (AllowEdit())
			echo '<br /><div class="center">' . SubmitButton( _( 'Save' ) ) . '</div>';
		echo '</form>';