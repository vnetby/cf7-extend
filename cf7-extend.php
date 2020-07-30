<?php

/**
 * 
 * CF7 extend
 *
 * Plugin Name: CF7 extend
 * Plugin URI:  https://vnetby.net
 * Description: Расширяет базовые возможности плагина contact form 7
 * Version:     1.0
 * Author:      Vadzim Kananovich
 * Author URI: https://vk.com/vadzim1995
 * Text Domain: cfextend
 * Domain Path: /languages
 * 
 */

define('CFEXT_PATH', dirname(__FILE__) . '/');

require_once CFEXT_PATH . 'includes/class-cfext-common.php';
require_once CFEXT_PATH . 'includes/class-cfext-load.php';

$cfextend = new CFext_Load;


$cfextend->setup();
