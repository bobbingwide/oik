/**
 * Implements Google Maps shortcode block
 * 
 * Uses [bw_show_googlemap] shortcode.
 *
 * @copyright (C) Copyright Bobbing Wide 2018-2021
 * @author Herb Miller @bobbingwide
 */

import './style.scss';
import './editor.scss';
import { transforms } from './transforms.js';

import { __ } from '@wordpress/i18n';
import classnames from 'classnames';

import { registerBlockType, createBlock } from '@wordpress/blocks';
import {AlignmentControl, BlockControls, InspectorControls, useBlockProps, PlainText, BlockIcon} from '@wordpress/block-editor';
import ServerSideRender from '@wordpress/server-side-render';
import {
    Toolbar,
    PanelBody,
    PanelRow,
    FormToggle,
    TextControl,
    TextareaControl,
    SelectControl } from '@wordpress/components';
import { Fragment} from '@wordpress/element';
import { map, partial } from 'lodash';

// Set the header for the block since it is reused
const blockHeader = <h3>{ __( 'Map', 'oik' ) }</h3>;


/**
 * Register the block.
 */
export default registerBlockType(
    // Namespaced, hyphens, lowercase, unique name
    'oik/googlemap',
    {

        transforms,

        edit: props => {
            const { attributes, setAttributes, instanceId, focus, isSelected } = props;
            const { textAlign, label } = props.attributes;
            const blockProps = useBlockProps( {
                className: classnames( {
                    [ `has-text-align-${ textAlign }` ]: textAlign,
                } ),
            } );

          return (
            <div {...blockProps}>
							{blockHeader}
							<p>{__( "This is where the map will appear", "oik" )}</p>
            </div>
          );
        },
        save: props => {
            const blockProps = useBlockProps.save();
					// console.log( props );
					//var shortcode =  {props.attributes.issue} ;
					var lsb = '[';
					var rsb = ']';
          return (
            <div {...blockProps}>
						{blockHeader}
						{lsb}
						bw_show_googlemap
						{rsb}
            </div>
          );
        }
    }
);