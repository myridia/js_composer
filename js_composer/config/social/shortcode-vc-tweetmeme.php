<?php
/**
 * Configuration file for [vc_tweetmeme] shortcode of 'Tweetmeme Button' element.
 *
 * @see https://kb.wpbakery.com/docs/inner-api/vc_map/ for more detailed information about element attributes.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

return [
	'name' => esc_html__( 'X Post Button', 'js_composer' ),
	'base' => 'vc_tweetmeme',
	'icon' => 'icon-wpb-tweetme',
	'element_default_class' => 'wpb_content_element',
	'category' => esc_html__( 'Social', 'js_composer' ),
	'description' => esc_html__( 'X post button', 'js_composer' ),
	'params' => [
		[
			'type' => 'dropdown',
			'param_name' => 'type',
			'heading' => esc_html__( 'Choose a button', 'js_composer' ),
			'value' => [
				esc_html__( 'Share a link', 'js_composer' ) => 'share',
				esc_html__( 'Follow', 'js_composer' ) => 'follow',
				esc_html__( 'Hashtag', 'js_composer' ) => 'hashtag',
				esc_html__( 'Mention', 'js_composer' ) => 'mention',
			],
			'description' => esc_html__( 'Select type of X button.', 'js_composer' ),
		],
		// share type.
		[
			'type' => 'checkbox',
			'heading' => esc_html__( 'Share url: page URL', 'js_composer' ),
			'param_name' => 'share_use_page_url',
			'value' => [
				esc_html__( 'Yes', 'js_composer' ) => 'page_url',
			],
			'std' => 'page_url',
			'dependency' => [
				'element' => 'type',
				'value' => 'share',
			],
			'description' => esc_html__( 'Use the current page url to share?', 'js_composer' ),
		],
		[
			'type' => 'textfield',
			'heading' => esc_html__( 'Share url: custom URL', 'js_composer' ),
			'param_name' => 'share_use_custom_url',
			'value' => '',
			'dependency' => [
				'element' => 'share_use_page_url',
				'value_not_equal_to' => 'page_url',
			],
			'description' => esc_html__( 'Enter custom page url which you like to share on X?', 'js_composer' ),
		],
		[
			'type' => 'checkbox',
			'heading' => esc_html__( 'X post text: page title', 'js_composer' ),
			'param_name' => 'share_text_page_title',
			'value' => [
				esc_html__( 'Yes', 'js_composer' ) => 'page_title',
			],
			'std' => 'page_title',
			'dependency' => [
				'element' => 'type',
				'value' => 'share',
			],
			'description' => esc_html__( 'Use the current page title as X post text?', 'js_composer' ),
		],
		[
			'type' => 'textfield',
			'heading' => esc_html__( 'X post text: custom text', 'js_composer' ),
			'param_name' => 'share_text_custom_text',
			'value' => '',
			'dependency' => [
				'element' => 'share_text_page_title',
				'value_not_equal_to' => 'page_title',
			],
			'description' => esc_html__( 'Enter the text to be used as a X post?', 'js_composer' ),
		],
		[
			'type' => 'textfield',
			'heading' => esc_html__( 'Via @', 'js_composer' ),
			'param_name' => 'share_via',
			'value' => '',
			'dependency' => [
				'element' => 'type',
				'value' => 'share',
			],
			'description' => esc_html__( 'Enter your X username.', 'js_composer' ),
		],
		[
			'type' => 'textfield',
			'heading' => esc_html__( 'Recommend @', 'js_composer' ),
			'param_name' => 'share_recommend',
			'value' => '',
			'dependency' => [
				'element' => 'type',
				'value' => 'share',
			],
			'description' => esc_html__( 'Enter the X username to be recommended.', 'js_composer' ),
		],
		[
			'type' => 'textfield',
			'heading' => esc_html__( 'Hashtag #', 'js_composer' ),
			'param_name' => 'share_hashtag',
			'value' => '',
			'dependency' => [
				'element' => 'type',
				'value' => 'share',
			],
			'description' => esc_html__( 'Add a comma-separated list of hashtags to a X post using the hashtags parameter.', 'js_composer' ),
		],
		// follow type.
		[
			'type' => 'textfield',
			'heading' => esc_html__( 'User @', 'js_composer' ),
			'param_name' => 'follow_user',
			'value' => '',
			'dependency' => [
				'element' => 'type',
				'value' => 'follow',
			],
			'description' => esc_html__( 'Enter username to follow.', 'js_composer' ),
		],
		[
			'type' => 'checkbox',
			'heading' => esc_html__( 'Show username', 'js_composer' ),
			'param_name' => 'follow_show_username',
			'value' => [
				esc_html__( 'Yes', 'js_composer' ) => 'yes',
			],
			'std' => 'yes',
			'dependency' => [
				'element' => 'type',
				'value' => 'follow',
			],
			'description' => esc_html__( 'Do you want to show username in button?', 'js_composer' ),
		],
		[
			'type' => 'checkbox',
			'heading' => esc_html__( 'Show followers count', 'js_composer' ),
			'param_name' => 'show_followers_count',
			'value' => '',
			'dependency' => [
				'element' => 'type',
				'value' => 'follow',
			],
			'description' => esc_html__( 'Do you want to displat the follower count in button?', 'js_composer' ),
		],
		// hashtag type.
		[
			'type' => 'textfield',
			'heading' => esc_html__( 'Hashtag #', 'js_composer' ),
			'param_name' => 'hashtag_hash',
			'value' => '',
			'dependency' => [
				'element' => 'type',
				'value' => 'hashtag',
			],
			'description' => esc_html__( 'Add hashtag to a X Post using the hashtags parameter', 'js_composer' ),
		],
		[
			'type' => 'checkbox',
			'heading' => esc_html__( 'X post text: No default text', 'js_composer' ),
			'param_name' => 'hashtag_no_default',
			'value' => [
				esc_html__( 'Yes', 'js_composer' ) => 'yes',
			],
			'std' => 'yes',
			'dependency' => [
				'element' => 'type',
				'value' => 'hashtag',
			],
			'description' => esc_html__( 'Set no default text for X post?', 'js_composer' ),
		],
		[
			'type' => 'textfield',
			'heading' => esc_html__( 'X post text: custom', 'js_composer' ),
			'param_name' => 'hashtag_custom_tweet_text',
			'value' => '',
			'dependency' => [
				'element' => 'hashtag_no_default',
				'value_not_equal_to' => 'yes',
			],
			'description' => esc_html__( 'Set custom text for X post.', 'js_composer' ),
		],
		[
			'type' => 'textfield',
			'heading' => esc_html__( 'Recommend @', 'js_composer' ),
			'param_name' => 'hashtag_recommend_1',
			'value' => '',
			'dependency' => [
				'element' => 'type',
				'value' => 'hashtag',
			],
			'description' => esc_html__( 'Enter username to be recommended.', 'js_composer' ),
		],
		[
			'type' => 'textfield',
			'heading' => esc_html__( 'Recommend @', 'js_composer' ),
			'param_name' => 'hashtag_recommend_2',
			'value' => '',
			'dependency' => [
				'element' => 'type',
				'value' => 'hashtag',
			],
			'description' => esc_html__( 'Enter username to be recommended.', 'js_composer' ),
		],
		[
			'type' => 'checkbox',
			'heading' => esc_html__( 'X post url: No URL', 'js_composer' ),
			'param_name' => 'hashtag_no_url',
			'value' => [
				esc_html__( 'Yes', 'js_composer' ) => 'yes',
			],
			'std' => 'yes',
			'dependency' => [
				'element' => 'type',
				'value' => 'hashtag',
			],
			'description' => esc_html__( 'Do you want to set no url to be X post?', 'js_composer' ),
		],
		[
			'type' => 'textfield',
			'heading' => esc_html__( 'X post url: custom', 'js_composer' ),
			'param_name' => 'hashtag_custom_tweet_url',
			'value' => '',
			'dependency' => [
				'element' => 'hashtag_no_url',
				'value_not_equal_to' => 'yes',
			],
			'description' => esc_html__( 'Enter custom url to be used in the x post.', 'js_composer' ),
		],
		// mention type.
		[
			'type' => 'textfield',
			'heading' => esc_html__( 'X post to @', 'js_composer' ),
			'param_name' => 'mention_tweet_to',
			'value' => '',
			'dependency' => [
				'element' => 'type',
				'value' => 'mention',
			],
			'description' => esc_html__( 'Enter username where you want to send your X post.', 'js_composer' ),
		],
		[
			'type' => 'checkbox',
			'heading' => esc_html__( 'X post text: No default text', 'js_composer' ),
			'param_name' => 'mention_no_default',
			'value' => [
				esc_html__( 'Yes', 'js_composer' ) => 'yes',
			],
			'std' => 'yes',
			'dependency' => [
				'element' => 'type',
				'value' => 'mention',
			],
			'description' => esc_html__( 'Set no default text of the X post?', 'js_composer' ),
		],
		[
			'type' => 'textfield',
			'heading' => esc_html__( 'X post text: custom', 'js_composer' ),
			'param_name' => 'mention_custom_tweet_text',
			'value' => '',
			'dependency' => [
				'element' => 'mention_no_default',
				'value_not_equal_to' => 'yes',
			],
			'description' => esc_html__( 'Enter custom text for the X post.', 'js_composer' ),
		],
		[
			'type' => 'textfield',
			'heading' => esc_html__( 'Recommend @', 'js_composer' ),
			'param_name' => 'mention_recommend_1',
			'value' => '',
			'dependency' => [
				'element' => 'type',
				'value' => 'mention',
			],
			'description' => esc_html__( 'Enter username to recommend.', 'js_composer' ),
		],
		[
			'type' => 'textfield',
			'heading' => esc_html__( 'Recommend @', 'js_composer' ),
			'param_name' => 'mention_recommend_2',
			'value' => '',
			'dependency' => [
				'element' => 'type',
				'value' => 'mention',
			],
			'description' => esc_html__( 'Enter username to recommend.', 'js_composer' ),
		],
		// general.
		[
			'type' => 'checkbox',
			'heading' => esc_html__( 'Use large button', 'js_composer' ),
			'param_name' => 'large_button',
			'value' => '',
			'description' => esc_html__( 'Do you like to display a larger X button?', 'js_composer' ),
		],
		[
			'type' => 'checkbox',
			'heading' => esc_html__( 'Opt-out of tailoring X', 'js_composer' ),
			'param_name' => 'disable_tailoring',
			'value' => '',
			'description' => esc_html__( 'Tailored suggestions make building a great timeline. Would you like to disable this feature?', 'js_composer' ),
		],
		[
			'type' => 'dropdown',
			'heading' => esc_html__( 'Language', 'js_composer' ),
			'param_name' => 'lang',
			'value' => [
				'Automatic' => '',
				'French - français' => 'fr',
				'English' => 'en',
				'Arabic - العربية' => 'ar',
				'Japanese - 日本語' => 'ja',
				'Spanish - Español' => 'es',
				'German - Deutsch' => 'de',
				'Italian - Italiano' => 'it',
				'Indonesian - Bahasa Indonesia' => 'id',
				'Portuguese - Português' => 'pt',
				'Korean - 한국어' => 'ko',
				'Turkish - Türkçe' => 'tr',
				'Russian - Русский' => 'ru',
				'Dutch - Nederlands' => 'nl',
				'Filipino - Filipino' => 'fil',
				'Malay - Bahasa Melayu' => 'msa',
				'Traditional Chinese - 繁體中文' => 'zh-tw',
				'Simplified Chinese - 简体中文' => 'zh-cn',
				'Hindi - हिन्दी' => 'hi',
				'Norwegian - Norsk' => 'no',
				'Swedish - Svenska' => 'sv',
				'Finnish - Suomi' => 'fi',
				'Danish - Dansk' => 'da',
				'Polish - Polski' => 'pl',
				'Hungarian - Magyar' => 'hu',
				'Farsi - فارسی' => 'fa',
				'Hebrew - עִבְרִית' => 'he',
				'Urdu - اردو' => 'ur',
				'Thai - ภาษาไทย' => 'th',
			],
			'description' => esc_html__( 'Select button display language or allow it to be automatically defined by user preferences.', 'js_composer' ),
		],
		vc_map_add_css_animation(),
		[
			'type' => 'el_id',
			'heading' => esc_html__( 'Element ID', 'js_composer' ),
			'param_name' => 'el_id',
			'description' => sprintf( esc_html__( 'Enter element ID (Note: make sure it is unique and valid according to %1$sw3c specification%2$s).', 'js_composer' ), '<a href="https://www.w3schools.com/tags/att_global_id.asp" target="_blank">', '</a>' ),
		],
		[
			'type' => 'textfield',
			'heading' => esc_html__( 'Extra class name', 'js_composer' ),
			'param_name' => 'el_class',
			'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' ),
		],
		[
			'type' => 'css_editor',
			'heading' => esc_html__( 'CSS box', 'js_composer' ),
			'param_name' => 'css',
			'group' => esc_html__( 'Design Options', 'js_composer' ),
			'value' => [
				'margin-bottom' => '35px',
			],
		],
	],
];
