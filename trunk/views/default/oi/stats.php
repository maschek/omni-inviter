<?php
/**
 * Omni Inviter -- Offers multiple, extendable ways of inviting new users
 * 
 * @package Omni Inviter
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Brett Profitt <brett.profitt@gmail.com>
 * @copyright Brett Profitt 2009
 */

admin_gatekeeper();

/*

// this goes for previous month 30 days.
// invites expire after 30 days, so it works well.
// might be able to do week, but will have 100%+ cases possible.
quick summary : 
	type=p invites created: errored / used / unused	(below, clicked but not used)
	    
	type=bvs invites created / sent / errored 
	type=bvs invites sent / used / unused
	type=bvs invites
	type=lc invited users over last year

 */

// range needs to be strtotime able and make sense with a - in front.
$range = get_input('range', '1 month');
$start_time = strtotime("-$range");
$end_time = time();

$colors = array(
	'0054a7',
	'2579CC',
	'4A9EF1',
	'6FC3F1',
	'94E8F1',
	'85EAFF'
);

$colors2 = array(
	'999999',
	'aaaaaa',
	'bbbbbb',
	'cccccc',
	'dddddd',
	'eeeeee',
);

$xcolors = array(
	'4D89F9',
	'C6D8FD',
	'2579CC',
	'4A9EF1',
	'6FC3F1',
	'94E8F1',
	'BAFFF1'
);

//C6D9FD, 4D89F9,


$scolors = array(
	'0f3775',
	'a4c6e1',
	'84a4ca'
);

if (!$created_c = oi_get_invitation_count('created', 'all', $start_time, $end_time)) {
	echo '<div class="contentWrapper">' . elgg_echo('oi:admin:no_invitations') . '</div>';
	
	return;
}

$sent_c = oi_get_invitation_count('sent', 'all', $start_time, $end_time);
//$sent_p = round($sent_c / $created_c * 100, 2);

$used_c = oi_get_invitation_count('used', 'all', $start_time, $end_time);
//$used_p_of_sent = round($used_c / $sent_c * 100, 2);
//$used_p_of_all = round($used_c / $created_c * 100, 2);

$error_c  = oi_get_invitation_count('error', 'all', $start_time, $end_time);
//$error_p = round($error_c / $sent_c * 100, 2);

$clicked_c = oi_get_invitation_count('clicked', 'all', $start_time, $end_time);
//$clicked_p_of_sent = round($clicked_c / $sent_c * 100, 2);
//$clicked_p_of_all = round($clicked_c / $created_c * 100, 2);

$clicked_and_ignored_c = oi_get_invitation_count('clicked_and_ignored', 'all', $start_time, $end_time);
//$clicked_and_ignored_p_of_sent = round($clicked_and_ignored_c / $sent_c * 100, 2);
//$clicked_and_ignored_p_of_all = round($clicked_and_ignored_c / $created_c * 100, 2);

$ignored_c = oi_get_invitation_count('ignored', 'all', $start_time, $end_time);
//$ignored_p_of_sent = round($ignored_c / $sent_c * 100, 2);
//$ignored_p_of_all = round($ignored_c / $created_c * 100, 2);

$unsent_c = oi_get_invitation_count('unsent', 'all', $start_time, $end_time);
//$unsent_p = round($unsent_c / $created_c * 100, 2);

echo "<pre>
all: $created_c

sent: $sent_c
sent_p: $sent_p

used: $used_c
used_p_all: $used_p_of_all
used_p_sent: $used_p_of_sent

error: $error_c
error_p: $error_p

clicked: $clicked_c
clicked p/s: $clicked_p_of_sent
clicked p/a: $clicked_p_of_all

c_a_i: $clicked_and_ignored_c
c_a_i p/s: $clicked_and_ignored_p_of_sent
c_a_i p/a: $clicked_and_ignored_p_of_all

ignored: $ignored_c
ignored_pa: $ignored_p_of_all
ignored_ps: $ignored_p_of_sent

unsent: $unsent_c
unsentp: $unsent_p
</pre>
";


$s_sent = elgg_echo('oi:stats:sent'); 
$s_unsent = elgg_echo('oi:stats:unsent');
$s_error = elgg_echo('oi:stats:error');
$s_used = elgg_echo('oi:stats:used'); 
$s_ignored = elgg_echo('oi:stats:ignored');
$s_clicked_and_ignored = elgg_echo('oi:stats:clicked_and_ignored');

// draw and print chart for sent stats
$chart1_options = array(
	'type' => 'pie3d',
	'data' => array(
		array('value' => $sent_c,	'label' => $s_sent, 'color' => $colors[0]),
		array('value' => $unsent_c,	'label' => $s_unsent, 'color' => $colors[1]),
		array('value' => $error_c,	'label' => $s_error, 'color' => $colors[2])
	),
	'colors' => array($colors[0], $colors[1], $colors[2]),
	'options' => array(
		'size' => '425x150',
		'scale' => "0,$created_c"
	)
);

echo elgg_view('oi/stat_section', array(
	'title' => elgg_echo('oi:stats:all_invitations'),
	'chart_options' => $chart1_options,
	'legend_options' => array(
		'show_percent' => true,
		'show_value' => true
	)
));


// draw and print chart for used / clicked stats
$chart2_options = array(
	'type' => 'pie3d',
	'data' => array(
		array('value' => $used_c, 'label' => $s_used),
		array('value' => $ignored_c, 'label' => $s_ignored),
		array('value' => $clicked_and_ignored_c, 'label' => $s_clicked_and_ignored)
	),
	'colors' => array($colors[0], $colors[1], $colors[2]),
	'options' => array(
		'size' => '450x150',
		'scale' => "0,$sent_c"
	)
);

echo elgg_view('oi/stat_section', array(
	'title' => elgg_echo('oi:stats:sent_invitations'),
	'chart_options' => $chart2_options,
	'legend_options' => array(
		'show_percent' => true,
		'show_value' => true
	)
));

// draw and print chart for method stats
$methods = oi_get_supported_methods();
$used_data = array();
$clicked_ai_data = array();
$ignored_data = array();
$unsent_data = array();
$legend_info = array();

foreach($methods as $method) {
	$total = oi_get_invitation_count('created', $method, $start_time, $end_time);
	$used = oi_get_invitation_count('used', $method, $start_time, $end_time);
	$clicked_ai = oi_get_invitation_count('clicked_and_ignored', $method, $start_time, $end_time);
	$ignored = oi_get_invitation_count('ignored', $method, $start_time, $end_time);
	$unsent = oi_get_invitation_count('unsent', $method, $start_time, $end_time);
	$error = oi_get_invitation_count('error', $method, $start_time, $end_time);
	
	$label = ucwords($method);
	
	$used_data[] = array('value'=>$used, 'label'=>$label);
	$clicked_ai_data[] = array('value'=>$clicked_ai, 'label'=>$label);
	$ignored_data[] = array('value'=>$ignored, 'label'=>$label);
	$unsent_data[] = array('value'=>$unsent, 'label'=>$label);
	$error_data[] = array('value'=>$error, 'label'=>$label);
	
	$legend_info[$method] = '
		<td class="oi_align_center"><span class="oi_stats_value">' . $used . '</span></td>
		<td class="oi_align_center"><span class="oi_stats_value">' . $clicked_ai . '</span></td>
		<td class="oi_align_center"><span class="oi_stats_value">' . $ignored . '</span></td>
		<td class="oi_align_center"><span class="oi_stats_value">' . $unsent . '</span></td>
		<td class="oi_align_center"><span class="oi_stats_value">' . $error . '</span></td>
		<td class="oi_align_center oi_stats_total"><span class="oi_stats_value">' . $total . '</span></td>
	';
}

exit;

// adjust for better viewing
$upper_limit = $created_c;

$chart3_options = array(
	'type' => 'bar_vertical_stacked',
	//'data' => $created_data,
	//'data2' => $sent_data,
	'data' => $used_data,
	'data2' => $clicked_ai_data,
	'data3' => $ignored_data,
	'data4' => $unsent_data,
	'data5' => $error_data,
	'colors' => array($colors[0], $colors2[0], $colors[3], $colors2[3], $colors[5]),
	'options' => array(
		'size' => '450x200',
		'scale' => "0,$upper_limit"
	),
	// @todo pull this out.
	// (Labels for the data points)
	'params' => array(
//		'chm'	=> 'N(*f0*),000000,0,-1,11|'
//			. 'N  (*f0*),000000,1,-1,11|'
//			. 'N    (*f0*),000000,2,-1,11|'
//			. 'N      (*f0*),000000,3,-1,11',
		'chdlp'	=> 'r',
		'chdl' => "$s_used|$s_clicked_and_ignored|$s_ignored|$s_unsent|$s_error"
	),
	'css_class' => 'oi_center oi_border'
);

// special oddball legend
$legend .= '
<div class="oi_stats_legend" style="float: none; margin: auto; margin-bottom: 2em;">
	<table class="oi_stats_table">
		<tr class="oi_th_container">
			<th class="oi_align_center"><span class="oi_stats_value">' . elgg_echo('oi:method') . '</span></td>
			<th class="oi_align_center"><span class="oi_stats_value">' . $s_used . '</span></td>
			<th class="oi_align_center"><span class="oi_stats_value">' . $s_clicked_and_ignored . '</span></td>
			<th class="oi_align_center"><span class="oi_stats_value">' . $s_ignored . '</span></td>
			<th class="oi_align_center"><span class="oi_stats_value">' . $s_unsent . '</span></td>
			<th class="oi_align_center"><span class="oi_stats_value">' . $s_error . '</span></td>
			<th class="oi_align_center oi_stats_total"><span class="oi_stats_value">' . elgg_echo('oi:stats:total') . '</span></td>
		</tr>
	';

$row = 'odd';
foreach($legend_info as $method => $data) {
	$row_class = ' oi_row_' . $row;
	$row = ($row=='odd') ? 'even' : 'odd';
	
	$legend .= '
	<tr class="oi_stats_chunk' . $row_class . '">
		<td class="oi_stats_label">' . ucwords($method) . '</td>
		' . $data . '
	</tr>';
}

$legend .= '
	<tr class="oi_stats_chunk">
		<td colspan="6">
			<hr class="oi_stats_total_separator" />
		</td>
		<td class="oi_align_center oi_stats_total"><hr class="oi_stats_total_separator" /></td>
		
	</tr>
		<tr class="oi_stats_chunk oi_stats_total">
			<td class="oi_stats_label">' . elgg_echo('oi:stats:total') . '</td>
			<td class="oi_align_center"><span class="oi_stats_value">' . $used_c . '</span></td>
			<td class="oi_align_center"><span class="oi_stats_value">' . $clicked_and_ignored_c . '</span></td>
			<td class="oi_align_center"><span class="oi_stats_value">' . $ignored_c . '</span></td>
			<td class="oi_align_center"><span class="oi_stats_value">' . $unsent_c . '</span></td>
			<td class="oi_align_center"><span class="oi_stats_value">' . $error_c . '</span></td>
			<td class="oi_align_center"><span class="oi_stats_value">' . $created_c . '</span></td>
		</tr>
	</table>
</div>';

echo elgg_view('oi/stat_section', array(
	'title' => elgg_echo('oi:stats:methods'),
	'chart_options' => $chart3_options,
	'legend_options' => array(
		'show_percent' => true,
		'show_value' => true,
		'override' => $legend,
	),
));