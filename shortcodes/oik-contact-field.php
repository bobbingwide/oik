<?php
/**
 * @copyright (C) Copyright Bobbing Wide 2022
 * @package oik
 *
 */

/**
 * Implements bw_contact_field shortcode.
 *
 * For use in the bw_contact_form shortcode / block.
 *
 * The idea of this shortcode is to be able to register the fields that will appear in the contact form.
 * By default these will be 'name,email,subject,message', in that order.
 * When the bw_contact_form shortcode contains embedded content then the fields can be overridden.
 * Unless the `name` attribute is set, each bw_contact_field shortcode will register a field with a full name
 * consisting of a prefix ( default `oiku_contact-n` ) and the `name` generated from the `label`.
 * The bw_contact_form shortcode will use these fields to construct the form.
 *
 * Examples:
 * [bw_contact_form][bw_contact_field Email][/bw_contact_form]
 * [bw_contact_form][bw_contact_field Name][bw_contact_field Telephone][/bw_contact_form]
 *
 * @since 4.9.0
 * Note: The Message ( textarea ) field is not automatically added. It's no longer a required field.
 * @param $atts
 * @param $content
 * @param $tag
 * @return string
 */
function bw_contact_field( $atts, $content, $tag ) {
    oik_require( 'includes/bw_register.php');
    oik_require_lib( 'bw_fields');
    oik_require( 'includes/bw_metadata.php');
    oik_require( 'shortcodes/oik-contact-form.php');

    $label = bw_array_get_from( $atts, '0,label', 'field' );
    $name = bw_contact_field_name_from_label( $label );
    $name = bw_array_get_from( $atts, '2,name', $name );
    $type = bw_contact_field_type_from_name( $name );
    $type = bw_array_get_from( $atts, '1,type', $type );
    /**
     * @TODO Complete the logic to set the field size, placeholder value, default value.
     */
    $args = [ '#length' => 30 ];
    $value = bw_array_get( $atts, 'value', null);
    $args['#default'] = $value;
    $placeholder = bw_array_get( $atts, 'placeholder', null );
    $args['#placeholder'] = $placeholder;

    /**
     * Sets the required flag in #extras if the Label contains a '*' or the
     * required attribute is set.
     * If required sets the `requiredindicator` to '*' by default.
     */
    $required = ( false !== strpos( $label, '*' ) ) ? 'y' : 'n';
    $required = bw_array_get( $atts, 'required', $required);
    $args['required'] = $required;
    if ( bw_validate_torf( $required ) ) {
        $label = str_replace( '*', '', $label );
        $args['#extras'] = 'required';
        $requiredIndicator = bw_array_get( $atts, 'requiredindicator', '*');
        if (null !== $requiredIndicator) {
            $label .= '<span class="required">' . $requiredIndicator . '</span>';
        }
    }

    /**
     * The class name needs to be applied to the surrounding div.
     */
    $class = bw_array_get( $atts, 'class', null);
    $args['#class'] = $class . " is-style-$type";


    $full_name = bw_contact_field_full_name( $name );
    bw_register_field( $full_name, $type, $label, $args );
    global $bw_contact_fields;
    $bw_contact_fields[$full_name] = $full_name;
    // The contact field is not expected to produce output itself. It's used in the contact form.
    $html = bw_ret();
    return $html;
}

/**
 * Returns a field name given the label.
 *
 * @param string $label eg 'Name *'
 * @return string lower cased sanitized version of the label
 */
function bw_contact_field_name_from_label( $label  ) {
    $name = $label;
    $name = strtolower( $name );
    $name = sanitize_key( $name);
    return $name;
}

/**
 * Returns a field type given the name.
 *
 * @param $name
 * @return mixed|null
 */
function bw_contact_field_type_from_name( $name ) {
    $types = [ 'name' => 'text'
        , 'email' => 'email'
        , 'subject' => 'text'
        , 'telephone' => 'tel' // @TODO Not yet supported by bw_form_field()
        , 'message' => 'textarea'
        , 'text' => 'textarea'
        ];
    $type = bw_array_get( $types, $name, 'text');
    return $type;
}

/**
 * Returns the full name of the field.
 *
 * @param $prefix
 * @param $name
 * @return string
 */
function bw_contact_field_full_name( $name  ) {
    $prefix = bw_contact_form_id();
    return $prefix . '_' . $name;
}


function bw_contact_field__help( $shortcode='bw_contact_field') {
    return __( "Display a contact form field", "oik" );
}

function bw_contact_field__syntax( $shortcode='bw_contact_field') {
    $syntax = [ "0,label" =>  BW_::bw_skv( 'field', "<i>" . __( "label", "oik" ) . "</i>", __( "Label for the field", "oik" ) )
        , "1,type" => BW_::bw_skv( 'text', "textarea|email|checkbox", __( "Input field type", "oik" ) )
        , "2,name" => BW_::bw_skv( 'field', "<i>" . __( "text", "oik" ) . "</i>", __( "Field name", "oik" ) )
        , "required" => BW_::bw_skv( 'n', 'y', __("Required field") )
        , "requiredindicator" => BW_::bw_skv( null, "<i>" . __( "text", "oik" ) . "</i>", __("Text to indicate the field is required. eg *") )
        ];
    $syntax += _sc_classes( false );
    return $syntax;
}