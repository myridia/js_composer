<?php
/**
 * Handles shortcode and attachment remapping during WXR imports.
 * Processes shortcodes and updates post content with new URLs.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Class Vc_WXR_Parser_Plugin
 */
class Vc_WXR_Parser_Plugin {

	/**
	 * Shortcodes to process.
	 *
	 * @var array
	 */
	public $shortcodes = [
		'gallery' => [
			'ids',
		],
		'vc_single_image' => [
			'image',
		],
		'vc_gallery' => [
			'images',
		],
		'vc_images_carousel' => [
			'images',
		],
		'vc_media_grid' => [
			'include',
		],
		'vc_masonry_media_grid' => [
			'include',
		],
	];

	/**
	 * Remaps.
	 *
	 * @var int
	 */
	protected $remaps = 0;

	/**
	 * IDs remap.
	 *
	 * @var array
	 */
	private $idsRemap = [];

	/**
	 * Vc_WXR_Parser_Plugin constructor.
	 */
	public function __construct() {
		$this->shortcodes = apply_filters( 'vc_shared_templates_import_shortcodes', $this->shortcodes );
		add_filter( 'vc_import_post_data_processed', [
			$this,
			'processPostContent',
		] );

		add_action( 'vc_import_pre_end', [
			$this,
			'remapIdsInPosts',
		] );
	}

	/**
	 * Process post content and parse shortcodes.
	 *
	 * @param array $postdata
	 *
	 * @return array
	 */
	public function processPostContent( $postdata ) {
		if ( ! empty( $postdata['post_content'] ) && 'vc4_templates' === $postdata['post_type'] ) {
			$this->parseShortcodes( $postdata['post_content'] );
		}

		return $postdata;
	}

	/**
	 * Remap attachment IDs in post content.
	 *
	 * @param Vc_WP_Import $importer
	 */
	public function remapIdsInPosts( $importer ) {
		$currentPost = reset( $importer->processed_posts );
		// Nothing to remap or something wrong.
		if ( ! $currentPost ) {
			return;
		}
		$post = get_post( $currentPost );
		if ( empty( $post ) || ! is_object( $post ) || 'vc4_templates' !== $post->post_type ) {
			return;
		}
		// We ready to remap attributes in processed attachments.
		$attachments = $importer->processed_attachments;
		$this->remaps = 0;
		$newContent = $this->processAttachments( $attachments, $post->post_content );

		if ( $this->remaps ) {
			$post->post_content = $newContent;
			wp_update_post( $post );
		}
	}

	/**
	 * Process attachments and remap IDs.
	 *
	 * @param array $attachments
	 * @param string $content
	 * @return mixed
	 */
	protected function processAttachments( $attachments, $content ) {
		if ( ! empty( $this->idsRemap ) ) {
			foreach ( $this->idsRemap as $shortcode ) {
				$tag = $shortcode['tag'];
				$attributes = $this->shortcodes[ $tag ];
				$rawQuery = $shortcode['attrs_query'];
				$newQuery = $this->shortcodeAttributes( $shortcode, $attributes, $rawQuery, $attachments );

				if ( $newQuery ) {
					$content = str_replace( $rawQuery, $newQuery, $content );
					$this->remaps++;
				}
			}
		}
		$urlRegex = '#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|(?:[^[:punct:]\s]|/))#';
		$urlMatches = [];
		preg_match_all( $urlRegex, $content, $urlMatches );
		if ( ! empty( $urlMatches[0] ) ) {
			foreach ( $urlMatches[0] as $url ) {
				$idsMatches = [];
				preg_match_all( '/id\=(?P<id>\d+)/', $url, $idsMatches );
				if ( ! empty( $idsMatches['id'] ) ) {
					$this->remaps = true;
					$vals = array_map( 'intval', $idsMatches['id'] );
					$content = $this->remapAttachmentUrls( $attachments, $content, $url, $vals );
				}
			}
		}

		return $content;
	}

	/**
	 * Remap attachment URLs.
	 *
	 * @param array $attachments
	 * @param mixed $content
	 * @param string $url
	 * @param array $vals
	 * @return mixed
	 */
	protected function remapAttachmentUrls( $attachments, $content, $url, $vals ) {
		foreach ( $vals as $oldAttachmentId ) {
			if ( isset( $attachments[ $oldAttachmentId ] ) ) {
				$newUrl = wp_get_attachment_url( $attachments[ $oldAttachmentId ] );
				$content = str_replace( $url, $newUrl . '?id=' . $attachments[ $oldAttachmentId ], $content );
			}
		}

		return $content;
	}

	/**
	 * Remap shortcode attributes.
	 *
	 * @param array $shortcode
	 * @param array $attributes
	 * @param string $newQuery
	 * @param array $attachments
	 * @return bool|mixed
	 */
	protected function shortcodeAttributes( $shortcode, $attributes, $newQuery, $attachments ) {
		$replacements = 0;
		foreach ( $attributes as $attribute ) {
			// for example in vc_single_image 'image' attribute.
			if ( isset( $shortcode['attrs'][ $attribute ] ) ) {
				$attributeValue = $shortcode['attrs'][ $attribute ];
				$attributeValues = explode( ',', $attributeValue );
				$newValues = $attributeValues;
				array_walk( $newValues, [
					$this,
					'attributesWalker',
				], [
					'attachments' => $attachments,
				] );
				$newAttributeValue = implode( ',', $newValues );
				$newQuery = str_replace( sprintf( '%s="%s"', $attribute, $attributeValue ), sprintf( '%s="%s"', $attribute, $newAttributeValue ), $newQuery );
				$replacements++;
			}
		}
		if ( $replacements ) {
			return $newQuery;
		}

		return false;
	}

	/**
	 * Walk through attributes and remap IDs.
	 *
	 * @param mixed $attributeValue
	 * @param string $key
	 * @param array $data
	 */
	public function attributesWalker( &$attributeValue, $key, $data ) {
		$intValue = intval( $attributeValue );
		if ( array_key_exists( $intValue, $data['attachments'] ) ) {
			$attributeValue = $data['attachments'][ $intValue ];
		}
	}

	/**
	 * Parse shortcodes.
	 *
	 * @param string $content
	 * @return array
	 */
	private function parseShortcodes( $content ) {
		WPBMap::addAllMappedShortcodes();
		preg_match_all( '/' . get_shortcode_regex() . '/', trim( $content ), $found );

		if ( count( $found[2] ) === 0 ) {
			return $this->idsRemap;
		}
		foreach ( $found[2] as $index => $tag ) {
			$content = $found[5][ $index ];
			$shortcode = [
				'tag' => $tag,
				'attrs_query' => $found[3][ $index ],
				'attrs' => shortcode_parse_atts( $found[3][ $index ] ),
			];
			if ( array_key_exists( $tag, $this->shortcodes ) ) {
				$this->idsRemap[] = $shortcode;
			}
			$this->idsRemap = $this->parseShortcodes( $content );
		}

		return $this->idsRemap;
	}
}
