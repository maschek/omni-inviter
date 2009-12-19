<?php
/**
 * Omni Inviter -- Offers multiple, extendable ways of inviting new users
 * 
 * @package Omni Inviter
 * @subpackage Fusefly
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Brett Profitt <brett.profitt@gmail.com>
 * @copyright Brett Profitt 2009
 */

$user = get_loggedin_user();

$name_field = elgg_view('input/text', array(
	'internalname' => 'fusefly_name',
));

$email_field = elgg_view('input/text', array(
	'internalname' => 'fusefly_email'
));

if ($user->guardians_account_type == 'ward') {
	$friend_input = '<label>' . elgg_echo('oi:method:fusefly:ward_name') . $name_field . '</label>';
	$friend_input .= '<label>' . elgg_echo('oi:method:fusefly:ward_email') . $email_field . '</label>';
} else {
	$friend_input = '<label>' . elgg_echo('oi:method:fusefly:guardian_name') . $name_field . '</label>';
	$friend_input .= '<label>' . elgg_echo('oi:method:fusefly:guardian_email') . $email_field . '</label>';
}

$friend_input .= elgg_view('input/button', array(
	'value' => elgg_echo('oi:add'),
	'internalname' => 'oi_fusefly_add'
));

// prepare JS i10n...
$notemail = addslashes(elgg_echo('registration:notemail'));
$dupeemail = addslashes(elgg_echo('registration:dupeemail'));
$delete = addslashes(elgg_echo('delete'));

?>
<div id="oi_fusefly_input"><?php echo $friend_input; ?></div>

<div id="oi_fusefly_list"></div>

<script type="text/javascript">
var oiFuseflyList = new Array();

function oiFuseflyAdd() {
	var name = $('input[name=fusefly_name]').val();
	var email = $('input[name=fusefly_email]').val();
	if (!oiIsValidEmail(email)) {
		oiDisplayError('<?php echo $notemail; ?>');
		return false;
	}
	
	if (oiAddInvitedUser(name, email, 'fusefly')) {
		$('input[name=fusefly_name]').val('');
		$('input[name=fusefly_email]').val('');
		oiFuseflyAddListDisplay(name, email);
	} else {
		oiDisplayError('<?php echo $dupeemail; ?>');
		return false;
	}
}

function oiFuseflyRemove(name, email) {
	oiRemoveInvitedUser(name, email, 'fusefly');
}

function oiFuseflyAddListDisplay(name, email) {
	$('#oi_fusefly_list').prepend(oiFuseflyFormatListDisplay(name, email));
	// this doesn't work :(
	// no pretty effects.
	//$('#' + email).slideDown('slow');
	// also means we have to use onClick events in the delete.
}

function oiFuseflyFormatListDisplay(name, email) {
	return '<div id="' + email + '">' + name + ' &lt;' + email + '&gt; ' +
		'<a onClick="$(this.parentNode).fadeOut(); oiFuseflyRemove(\'' + name + '\', \'' + email + '\');">[<?php echo $delete; ?>]</a></div>'; 
}

function oiRedrawFuseflyListDisplay() {
	// fill out all the friends already in this session.
	var oiFuseflyList = oiGetInvitedUsers('fusefly');
	for (i in oiFuseflyList) {
		friend = oiFuseflyList[i];
		oiFuseflyAddListDisplay(friend.name, friend.id);
	}
}

$(document).ready(function() {
	$('input[name=oi_fusefly_add]').click(function() {
		oiFuseflyAdd();
	});

	// bind enter to inputs
	oiBindContentEnter(oiFuseflyAdd);
	oiRedrawFuseflyListDisplay();

});

</script>