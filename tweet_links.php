<?php
/*
 * Plugin Name: Tweet Links
 * Plugin URI:  https://github.com/rluevanos/tweet-links
 * Description: Create shortcode links for tweet, retweet, and reply 
 * Version:     1.0.0
 * Author:      Ricardo Luevanos
 * Author URI:  http://ricardoluevanos.com
 * License:     MIT
 * License URI: https://github.com/rluevanos/tweet-links/blob/master/LICENSE
 * Text Domain: tweet-links
 * */

require_once plugin_dir_path( __FILE__ ) . 'lib/class-tweet-links.php';

// Initialize plugin
new Tweet_Links( plugin_basename( __FILE__ ) );
