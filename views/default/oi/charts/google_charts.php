<?php
/**
 * Omni Inviter -- Offers multiple, extendable ways of inviting new users
 * 
 * @package Omni Inviter
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Brett Profitt <brett.profitt@gmail.com>
 * @copyright Brett Profitt 2009
 */

$params = $vars['params'];
$class = (array_key_exists('css_class', $vars)) ? ' ' . $vars['css_class'] : '';

$query = http_build_query($params);
$chart_url = 'http://chart.apis.google.com/chart?' . $query;

echo "<img src=\"$chart_url\" class=\"oi_chart$class\" />";