<?php
/**
 * Auto Logout program
 *
 * @since 3.4
 *
 * @author @dpredster
 *
 * @package RosarioSIS
 * @subpackage modules
 */

 // Convert minutes to seconds for auto logout script.
$max_inactive_mins = SecurityConfig( 'MAX_INACTIVE_MINS' ) * 60; 
?>
	<script type="text/javascript">var max_min = '<?php echo $max_inactive_mins; ?>';</script>
<?php
	// Call auto logout script only if inactive minutes are greater than 0.
	if (SecurityConfig('MAX_INACTIVE_MINS') > 0) 
	{
		echo '<script type="text/javascript">autoLogout();</script>'; 
	}
?>