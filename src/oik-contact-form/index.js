/**
 * Implements the Contact form block
 *
 * Equivalent to [bw_contact_form]
 *
 * @copyright (C) Copyright Bobbing Wide 2018, 2020
 * @author Herb Miller @bobbingwide
 */
import './style.scss';
import './editor.scss';

//import Input from './input';
//import TextControl from '@wordpress/components-text

// Get just the __() localization function from wp.i18n
const { __ } = wp.i18n;
// Get registerBlockType and Editable from wp.blocks
const { 
	registerBlockType, 
	Editable,
 } = wp.blocks;
const {
    InspectorControls,
} = wp.blockEditor;
	 
const {
  Toolbar,
  Button,
  Tooltip,
  PanelBody,
  PanelRow,
  FormToggle,
	TextControl,

} = wp.components;
const {
    ServerSideRender,
} = wp.editor;
import { transforms } from './transforms.js';




registerBlockType(
    // Namespaced, hyphens, lowercase, unique name
		'oik/contact-form',
    {
        // Localize title using wp.i18n.__()
        title: __( 'Contact form' ),
				
				description: 'Displays a Contact form',

        // Category Options: common, formatting, layout, widgets, embed
        category: 'common',

        // Dashicons Options - https://goo.gl/aTM1DQ
        icon: 'forms',

        // Limit to 3 Keywords / Phrases
        keywords: [
            __( 'Contact' ),
			__( 'Form' ),
            __( 'oik' ),
        ],
        example: {
        },
        transforms,

        // Set for each piece of dynamic data used in your block
        attributes: {
		/*
          user: {
            type: 'string',
          },

		 */
					
        },
			
				edit: props=> {
					
          return (
					
                       <ServerSideRender
                    block="oik/contact-form" attributes={ props.attributes }
                    />
          );
        },
				
			save() {
				 // Rendering in PHP
					return null;
			},
    }
);

