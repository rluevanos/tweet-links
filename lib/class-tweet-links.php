<?php

class Tweet_Links {
	public function __construct( $plugin_directory ) {
		add_action( 'admin_menu', array( __CLASS__, 'add_settings_menu' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'add_scripts' ) );
		add_action( 'add_meta_boxes', array( __CLASS__, 'add_meta' ) );
		add_action( 'save_post', array( __CLASS__, 'save_meta' ) );
		add_action( 'the_content', array( __CLASS__, 'show_custom_message' ) );
		add_filter( 'plugin_action_links_' . $plugin_directory, array( __CLASS__, 'add_settings_link' ) );
		add_shortcode( 'tweetlinks', array( __CLASS__, 'add_shortcode' ) );
	}

	/**
	 * Add settings page in menu
	 */
	public static function add_settings_menu() {
		add_options_page( 'Tweet Links', 'Tweet Links', 'manage_options', 'tweet-links-settings', array(
			__CLASS__, 'show_settings_page'	
		) );
	}

	/**
	 * Add settings link in plugin list
	 *
	 * @param  mixed $links links
	 * @return mixed $links links
	 */
	public static function add_settings_link( $links ) {
		$link = '<a href="options-general.php?page=tweet-links-settings.php">Settings</a>';
		array_unshift( $links, $link );

		return $links;
	}

	/**
	 * Display Settings page
	 */
	public static function show_settings_page() {
		if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
			self::save_data( $_POST ); 
		}

		$data = self::get_data(); 

		require_once dirname( __FILE__ ) . '/../views/tweet_links_settings.php';
	}

	/**
	 * Add scripts
	 */
	public static function add_scripts() {
		wp_enqueue_style( 'tweet-links', plugin_dir_url( __FILE__ ) . '../views/tweet-links.css' );
	}

	/**
	 * Render shortcode
	 *
	 * @param  array $atts associative array of attributes
	 * @return mixed $output html snippet to render
	 */
	public static function add_shortcode( $atts ) {
		global $post;

		$data = self::get_data(); 

		$blank = '';
		if ( ! empty( $data['blank_on'] ) ) {
			$blank = ' target="_blank"';
		}	

		extract( shortcode_atts( array(
			'type' => 'tweet',
			'text' => 'Tweet'
		), $atts) );

		if ( 'retweet' == $type || 'reply' == $type ) {
			if ( empty( $data['handle'] ) ) {
				return '<span style="color:#ff0000;"><strong>Tweet Links Error:</strong> Twitter handle missing in <a href="/wp-admin/options-general.php?page=tweet-links-settings">settings</a>!</span>';
			}	

			if ( empty( get_post_meta( $post->ID, 'tweet-links-tweet-id', true ) ) ) {
				return '<span style="color:#ff0000;"><strong>Tweet Links Error:</strong> Tweet ID missing for post!</span>';
			}	

			$output = '<a href="https://twitter.com/' . esc_html( $data['handle'] ) . '/status/' . esc_html( get_post_meta( $post->ID, 'tweet-links-tweet-id', true ) ) . '"';
		} else {
			$output = '<a href="https://twitter.com/intent/tweet?text=';
			$output .= urlencode( '"' . get_the_title( $post->ID ) . '"' );

			if ( ! empty( $data['handle'] ) ) {
				$output .= ' by @' . urlencode( esc_html( $data['handle'] ) );
			}

			if ( ! empty( $data['shortener_on'] ) ) {
				$output .= ', ' . urlencode( wp_get_shortlink( $post->ID ) );
			} else {
				$output .= ', ' . urlencode( get_the_permalink( $post->ID ) );
			}

			if ( wp_get_post_terms( $post->ID, 'post_tag' ) ) {
				$output .= ', ' . urlencode( '#' . implode( ' #', wp_get_post_terms( $post->ID, 'post_tag', array( "fields" => "names" ) ) ) ); 
			}

			$output .= '"';
		}

		$output .= $blank . '>' . esc_html( $text ) . '</a>';

		return $output;
	}

	/**
	 * Add meta box to post 
	 */
	public static function add_meta() {
		add_meta_box( 
			'tweet-links-meta-box',
			__( 'Tweet Links ( tweet id )' ),
			array( __CLASS__, 'show_meta' ),
			array( 'post', 'page' ),
			'normal',
			'high'
		);
	}

	/**
	 * Render meta box content 
	 *
	 * @param object $post post data
	 */
	public static function show_meta( $post ) {
		$output = '<input name="tweet-links-tweet-id" id="tweet-links-tweet-id" type="text" value="' . esc_html(  get_post_meta( $post->ID, 'tweet-links-tweet-id', true) ) . '" />';
		$output .= '<br /><br /><i>https://twitter.com/username/status/<span style="padding: 0 3px;font-weight: bold;background-color: #ddf9f9;">0123456789012345678</span></i>';

		echo $output;
	}

	/**
	 * Show custom tweet message
	 *
	 * @param  mixed $content post/page content
	 * @return mixed $content post/page content with added message
	 */
	public static function show_custom_message( $content ) {
		global $post;

		if ( is_home() || is_front_page() ) {
			return $content;
		}

		$data = self::get_data(); 

		$post_type = get_post_type( $post->ID);

		if ( 'post' == $post_type && empty( $data['message_post_on'] ) ) {
			return $content;
		}

		if ( 'page' == $post_type && empty( $data['message_page_on'] ) ) {
			return $content;
		}

		if ( ! empty( $data['message_on'] ) ) {
			$message = $data['custom_message'];
			$message = str_replace( '{TWEET}', do_shortcode( '[tweetlinks type="tweet" text="tweet"]' ) , $message );	
			$message = str_replace( '{RETWEET}', do_shortcode( '[tweetlinks type="retweet" text="retweet"]' ) , $message );	
			$message = str_replace( '{REPLY}', do_shortcode( '[tweetlinks type="reply" text="reply"]' ) , $message );	
			$content .= '<p class="tweet-links-custom-message">' . $message  . '</p>';
		}

		return $content;
	}

	/**
	 * Save meta box input 
	 *
	 * @param string $post_id post id
	 * @param object $post post object
	 * @param bool   $update existing post being updated or not
	 */
	public static function save_meta( $post_id, $post = null, $update = null ) {
		if ( ! empty( $post ) ) {
			if ( 'post' != $post->post_type && 'page' != $post->post_type ) {
				return;
			}
		}

		if ( ! empty( $_POST['tweet-links-tweet-id'] ) ) {
			update_post_meta( $post_id, 'tweet-links-tweet-id', sanitize_text_field( $_POST['tweet-links-tweet-id'] ) );
		}
	}

	/**
	 * Save options data
	 *
	 * @param  array $params form data
	 * @return array         wp_options array for plugin key
	 */
	private static function save_data( $params ) {
		$data = array();

		if ( ! empty( $params['tweet_links_handle'] ) ) {
			$data['handle'] = sanitize_text_field( str_replace('@', '', $params['tweet_links_handle'] ) );
		}

		if ( ! empty( $params['tweet_links_shortener_on'] ) ) {
			$data['shortener_on'] = sanitize_text_field( $params['tweet_links_shortener_on'] );
		}

		if ( ! empty( $params['tweet_links_blank_on'] ) ) {
			$data['blank_on'] = sanitize_text_field( $params['tweet_links_blank_on'] );
		}

		if ( ! empty( $params['tweet_links_tags_on'] ) ) {
			$data['tags_on'] = sanitize_text_field( $params['tweet_links_tags_on'] );
		}

		if ( ! empty( $params['tweet_links_message_on'] ) ) {
			$data['message_on'] = sanitize_text_field( $params['tweet_links_message_on'] );
		}

		if ( ! empty( $params['tweet_links_message_post_on'] ) ) {
			$data['message_post_on'] = sanitize_text_field( $params['tweet_links_message_post_on'] );
		}

		if ( ! empty( $params['tweet_links_message_page_on'] ) ) {
			$data['message_page_on'] = sanitize_text_field( $params['tweet_links_message_page_on'] );
		}

		if ( ! empty( $params['tweet_links_custom_message'] ) ) {
			$data['custom_message'] = wp_kses_post( $params['tweet_links_custom_message'] );
		}

		update_option( 'tweet_links', $data );
	}

	/**
	 * Get options data
	 *
	 * @return array wp_options array for plugin key
	 */
	private static function get_data() {
		return get_option( 'tweet_links' );
	}
}
