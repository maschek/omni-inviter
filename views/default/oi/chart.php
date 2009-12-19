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

expects $vars to have...
	* Type of chart
	* data, [data2], [data3], ... (For charts with multiple data sets)
	* options = array(size,scale)

*/
// get the type
$type = $vars['type'];

echo elgg_view('oi/charts/' . $type, $vars);