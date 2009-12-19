<?php
/**
 * Omni Inviter -- Offers multiple, extendable ways of inviting new users
 * 
 * @package Omni Inviter
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Brett Profitt <brett.profitt@gmail.com>
 */

if ($user = get_loggedin_user()) {
		$who = ($user->guardians_user_type == 'ward') ? 'My guardian.' : 'A student as their guardian.';
} else {
		$who = 'Friends.';
}

$en = array(
	'oi:method:fusefly:description' => 'Invite a Fusefly Guardian or Student',
	'oi:method:fusefly:invite_who' => $who,

	'oi:method:fusefly:guardian_name' => 'Guardian Name',
	'oi:method:fusefly:guardian_email' => 'Guardian Email',

	'oi:method:fusefly:ward_name' => 'Student\'s Name',
	'oi:method:fusefly:ward_email' => 'Student\'s Email'
 
);

add_translation("en", $en);
