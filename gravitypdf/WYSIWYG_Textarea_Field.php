<?php

namespace GFPDF\Helper\Fields;

use GFPDF\Helper\Helper_Abstract_Form;
use GFPDF\Helper\Helper_Misc;
use GFPDF\Helper\Helper_Abstract_Fields;

/* Exit if accessed directly */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Controls the display and output of the textarea field
 *
 * @since 4.0
 */
class WYSIWYG_Textarea_Field extends Helper_Abstract_Fields {

	/**
	 * Check the appropriate variables are parsed in send to the parent construct
	 *
	 * @param object               $field The GF_Field_* Object
	 * @param array                $entry The Gravity Forms Entry
	 *
	 * @param \GFPDF\Helper\Helper_Abstract_Form $form
	 * @param \GFPDF\Helper\Helper_Misc          $misc
	 *
	 */
	public function __construct( $field, $entry, Helper_Abstract_Form $form, Helper_Misc $misc ) {
		/* call our parent method */
		parent::__construct( $field, $entry, $form, $misc );
	}

	/**
	 * Display the HTML version of this field
	 *
	 * @param string $value
	 * @param bool   $label
	 *
	 * @return string
	 *
	 * @since 4.0
	 */
	public function html( $value = '', $label = true ) {
		$value = html_entity_decode($this->value());

		return parent::html( $value );
	}

	/**
	 * Get the standard GF value of this field
	 *
	 * @return string|array
	 *
	 * @since 4.0
	 */
	public function value() {
return html_entity_decode($this->get_value());
		if ( $this->has_cache() ) {
			return $this->cache();
		}

		/* Don't do any formatting on our value */
		$this->cache( $this->get_value() );

		return  htmlspecialchars( wp_kses_post( $this->cache() ) );
	}
}