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
	'cht'	=>	'p'
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
$labels = array();

foreach ($vars['data'] as $data_point) {
	if (!array_key_exists('value', $data_point)) {
		continue;
	}
	
	$data[] = $data_point['value'];
//	
//	if (array_key_exists('color', $data_point) && !empty($data_point['color'])) {
//		$colors[] = $data_point['color'];
//	}
	if (array_key_exists('label', $data_point) && !empty($data_point['label'])) {
		$labels[] = $data_point['label'];
	}
}

// load data
$params['chd'] = 't:' .  implode(',', $data);

// colors and labels must match the data points.
if (array_key_exists('colors', $vars) && count($data) == count($vars['colors'])) {
	$params['chco'] = implode('|', $vars['colors']);
}

if (count($data) == count($labels)) {
	$params['chl'] = implode('|', $labels);
}

// allow overrides and extra params
if (is_array($vars['params'])) {
	$vars['params'] = array_merge($params, $vars['params']);
} else {
	$vars['params'] = $params;
}

// get chart
echo elgg_view('oi/charts/google_charts', $vars);