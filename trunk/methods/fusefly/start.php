<?php
/**
 * Omni Inviter -- Offers multiple, extendable ways of inviting new users
 * 
 * @package Omni Inviter
 * @subpackage fusefly
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Brett Profitt <brett.profitt@gmail.com>
 * @copyright Brett Profitt 2009  
 */

$oi_author = 'Brett Profitt';
$oi_description = elgg_echo('oi:method:fusefly:description');
$oi_invite_who = elgg_echo('oi:method:fusefly:invite_who');

// used to print method-specific settings.
$oi_settings_callback = 'oi_settings_fusefly';

// Called during invitation creation
// useful to set info that will be used during send.
// Username, access_id, and method will be automatically set.
// If you want to POST info from content.php, use javascript:oiAddInvitedUser()'s
// params field.  See content.php for more info.
$oi_new_invitation_callback = 'oi_new_invitation_fusefly';

// used to send messages for this type of invite.
$oi_send_invitation_callback = 'oi_send_invitation_fusefly';

// Called during invitation creation and used during invitation use (registration)
// Useful to pass friend_guid and invitecode to Elgg's internal registration system
// or to pass params to the registration callback through $_SESSION
$oi_use_invitation_callback = 'oi_use_invitation_fusefly';

// called after invited user successfully registers.
// Useful to do relationships or garbage collection.
$oi_post_register_callback = 'oi_post_register_fusefly';

/**
 * Sets defaults and return settings config for OI settings page.
 * 
 * @return str
 */
function oi_settings_fusefly() {
	return false;
}

/**
 *  Performs actions after initial object creation
 *  Must return true or inivitation obj will not
 *  be created.
 * 
 * @param obj the populated inviter object 
 * @return true
 */
function oi_new_invitation_fusefly($invite) {
	$user_guid = $invite->owner_guid;
	$user = get_entity($user_guid);
	
	$invite->guardians_related_name = $user->invited_name;
	$invite->guardians_related_email = $user->email;
	$invite->guardians_code = $user->guardians_code;
	
	// force the invitation to be of the opposite type
	$invite->guardians_related_user_type = ($user->guardians_user_type == 'ward') ? 'guardian' : 'ward';
	
	return true;
}

/**
 * Performs actions during use.
 * Useful to set_input() friend_guid and invitecode.
 * If this function returns false, it will stop
 * display of standard reg form and this function will
 * be required to handle form display.
 * 
 * @param $invite Invitation object
 * @return bool
 */
function oi_use_invitation_fusefly($invite) {
	// @todo: have to run this through elgg_echo() because the
	// reject that coded the guardians mod made it pass
	// the formatted string instead of the key.
	set_input('guardians_user_type', elgg_echo('guardians:' . $invite->guardians_related_user_type));
	
	set_input('guardians_related_guid', $invite->owner_guid);
	set_input('guardians_related_guid_code', $invite->guardians_code);
	set_input('guardians_related_info_locked', true);
	
	return true;
}

/**
 * Performed after successful registration.
 * Useful to do any cleanup or friending, notifying, etc.
 * If sensitive information is stored (I'm looking at you, openinviter),
 * please clean it out but do NOT delete the object itself.
 * 
 * Must return true or object will be marked as unsent.
 * 
 * @param $invite Invitation object
 * @return bool
 */
function oi_post_register_fusefly($invite) {
	
	return true;
}


/**
 * Sends an invite using this method.
 * 
 * @param $invite
 * @return bool
 */
function oi_send_invitation_fusefly($invite) {
	$owner = get_entity($invite->owner_guid);
	
	return oi_send_email($owner->email, $owner->name, $invite->invited_account_id, $invite->invited_name, 
		$invite->title, $invite->description);
}
