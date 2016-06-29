<?php

/*

Plugin Name: Castlegate IT WP Rebuild Image Meta
Plugin URI: http://github.com/castlegateit/cgit-wp-rebuild-image-meta
Description: Rebuilds image meta data, even if none exists
Version: 1.0
Author: Castlegate IT
Author URI: http://www.castlegateit.co.uk/
License: MIT

*/

use Cgit\RebuildImageMeta;

require __DIR__ . '/src/autoload.php';

$rebuild = RebuildImageMeta::getInstance();
