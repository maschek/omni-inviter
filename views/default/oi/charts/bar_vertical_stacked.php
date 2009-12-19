<?php
/**
 * Omni Inviter -- Offers multiple, extendable ways of inviting new users
 * 
 * @package Omni Inviter
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Brett Profitt <brett.profitt@gmail.com>
 * @copyright Brett Profitt 2009
 */

// set type (overridden for pie3d in $vars['params'])
$params = array(
	'cht'	=>	'bvs',
	'chbh'	=> 'a,20,20',
	'chxt'	=> 'x,y,r'
);

// googlifiy options.
foreach ($vars['options'] as $option => $value) {
	// need our 0s.
	$value = (string)$value;
	switch($option) {
		case 'size':
			$params['chs'] = $value;
			break;
			
		case 'scale':
			$params['chds'] = $value;
			break;	
	}
}

// prep data
$data = array();
$colors = array();
$labels = array();


//@todo this can be a function.
foreach ($vars['data'] as $data_point) {
	if (!array_key_exists('value', $data_point)) {
		continue;
	}
	
	$data[] = $data_point['value'];
	
	if (array_key_exists('label', $data_point) && !empty($data_point['label'])) {
		$labels[] = $data_point['label'];
	}
}

// load data
$params['chd'] = 't:' .  implode(',', $data);

// grab any extra data sets
$i = 2;
while (array_key_exists("data$i", $vars)) {
	$data = array();
	
	foreach ($vars["data$i"] as $data_point) {
		$data[] = (string) $data_point['value'];
	}
	
	$params['chd'] .= '|' . implode(',', $data);
	
	$i++;
}

$colors = $vars['colors'];

// @todo colors should match the data sets. ($i - 1)
if ($i-1 == count($colors)) {
	$params['chco'] = implode(',', $colors);
}

//@todo should come up with a better way to get the min and max...
if (count($data) == count($labels)) {
	// the x
	$params['chxl'] = '0:|' . implode('|', $labels);
	
	// the y and r axis
	list($min, $max) = explode(',', $vars['options']['scale']);
	
	$inc = ceil($max / 5);
	
	$scale_arr = array();
	for ($i=$min; $i<$max; $i=$i+$inc) {
		$scale_arr[] = $i;
	}
	
	//$scale_arr[] = $i;
	
	$params['chxl'] .= '|1:|' . implode('|', $scale_arr);
	$params['chxl'] .= '|2:|' . implode('|', $scale_arr);
}

// allow overrides and extra params
if (is_array($vars['params'])) {
	$vars['params'] = array_merge($params, $vars['params']);
} else {
	$vars['params'] = $params;
}
//
//echo '<pre>';
//print_r($vars['params']);

// get chart
echo elgg_view('oi/charts/google_charts', $vars);