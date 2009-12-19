<?php
/**
 * Omni Inviter -- Offers multiple, extendable ways of inviting new users
 * 
 * @package Omni Inviter
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Brett Profitt <brett.profitt@gmail.com>
 * @copyright Brett Profitt 2009
 */

/*

title
legend_options = array(
	'visible' => bool
	'show_percent' => bool
	'show_value' => bool
)

chart_options = array( ... ) // exactly what needs to go to pie3d


chart_data -> use to make the flat list too (get % here from values)
	do all values == 100%?
	how does this work with bar charts?


 */

$legend_options = (array_key_exists('legend_options', $vars)) ? $vars['legend_options'] : false;
$chart_options = $vars['chart_options'];

$title = (array_key_exists('title', $vars)) ? $vars['title'] : '';

// get labels, values, and percents
//@todo sort by highest value.
$legend = '';
if ($legend_options) {
	// get full count to determine %s
	$full_count = 0;
	foreach ($chart_options['data'] as $chart_data) {
		$full_count += $chart_data['value'];
	}
	
	// build legend
	if (array_key_exists('override', $legend_options)) {
		$legend = $legend_options['override'];
	} else {
//		// pop on a total field
//		$total = array(
//			'value' => $full_count,
//			'label' => elgg_echo('oi:stats:total'),
//		);
		$legend_data = $chart_options['data'];
		//array_push($legend_data, $total);
		
		$legend .= '
		<div class="oi_stats_legend">
			<table class="oi_stats_table">';
		$row = 'odd';
		foreach($legend_data as $chart_data) {
			$row_class = ' oi_row_' . $row;
			$row = ($row=='odd') ? 'even' : 'odd';
			$percent = '';
			$value = '';
			$label = $chart_data['label'];
			
			if ($legend_options['show_percent']) {
				$percent = round($chart_data['value'] / $full_count * 100, 2);
			}
			
			if ($legend_options['show_value']) {
				$value = $chart_data['value'];
			}
			
			$legend .= '
				<tr class="oi_stats_chunk' . $row_class . '">
					<td class="oi_stats_label">' . $chart_data['label'] . '</td>
					<td class="oi_align_right">
						<span class="oi_stats_percent">' . $percent . '%</span>
					</td>
					<td class="oi_align_right">
						<span class="oi_stats_value">' . $value . '</span>
					</td>
				</tr>';
		}
		
		$legend .= '
			<tr class="oi_stats_chunk">
				<td colspan="3">
					<hr class="oi_stats_total_separator" />
				</td>
			</tr>
				<tr class="oi_stats_chunk oi_stats_total">
					<td class="oi_stats_label">' . elgg_echo('oi:stats:total') . '</td>
					<td class="oi_align_right">
						<span class="oi_stats_percent">100%</span>
					</td>
					<td class="oi_align_right">
						<span class="oi_stats_value">' . $full_count . '</span>
					</td>
				</tr>
			</table>
		</div>';
	}
}

$chart = elgg_view('oi/chart', $chart_options);

echo '
<div class="contentWrapper oi_stats_section_wrapper">
	<div id="content_area_user_title">
	<h2>' . $title . '</h2>
	</div>
	' . $legend . '
	' . $chart . '
	<div class="clearfloat"></div>
</div>
';