/**
 * Implements [bw_address] as a server rendered block
 *
 * - Depends on oik
 * -
 *
 * @copyright (C) Copyright Bobbing Wide 2018,2019,2020,2021
 * @author Herb Miller @bobbingwide
 */

import './style.scss';
import './editor.scss';

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
import { transforms } from './transforms.js';

/**
 * These are the different options for the tag attr
 */
const tagOptions =
    { "div": "Block",
       "span": "Inline",
    };

/**
 * Register the block
 */
export default registerBlockType(
    // Namespaced, hyphens, lowercase, unique name
    'oik/address',
    {
        example: {
        },
        transforms,

       edit: props => {
           const { attributes, setAttributes, instanceId, focus, isSelected } = props;
           const { textAlign, label } = props.attributes;
           const blockProps = useBlockProps( {
               className: classnames( {
                   [ `has-text-align-${ textAlign }` ]: textAlign,
               } ),
           } );
            function onChangeAttr( key, value ) {
                props.setAttributes( { [key] : value } );
            };

          return (
            <Fragment>
                <InspectorControls >
                    <PanelBody>
                        <PanelRow>
                            <SelectControl label="Display" value={props.attributes.tag}
                                     options={ map( tagOptions, ( key, label ) => ( { value: label, label: key } ) ) }
                                     onChange={partial( onChangeAttr, 'tag' )}
                            />
                        </PanelRow>
                        <PanelRow>
                            Equivalent shortcode<br />
                            &#91;bw_address tag={props.attributes.tag}&#93;
                        </PanelRow>
                    </PanelBody>
                </InspectorControls>
                <div { ...blockProps}>
                    <ServerSideRender block="oik/address" attributes={ props.attributes } />
                </div>
            </Fragment>
          );
        },

        save() {
                  return null;
        }
    },
);