/**
 * Implements the edit part of oik/content-block
 *
 * @copyright (C) Copyright Bobbing Wide 2020, 2021
 * @author Herb Miller @bobbingwide
 */

import { __ } from '@wordpress/i18n';
import clsx from 'clsx';

import { registerBlockType, createBlock } from '@wordpress/blocks';
import {AlignmentControl, BlockControls, InspectorControls, useBlockProps, PlainText, BlockIcon} from '@wordpress/block-editor';
import ServerSideRender from '@wordpress/server-side-render';
import {
    Toolbar,
    PanelBody,
    PanelRow,
    FormToggle,
    RangeControl,
    TextControl,
    TextareaControl,
    ToggleControl,
    SelectControl } from '@wordpress/components';
import { Fragment} from '@wordpress/element';
import { map, partial } from 'lodash';

import {bw_shortcodes, getAttributes} from "./bw_shortcodes";
import{ NumberPosts } from './numberposts';
import { orderby, order } from './attributes';
import { Formats } from './formats';
import { SelectTextControlCombo } from './SelectTextControlCombo';

//import { BwQueryControls } from './query_controls';

//import GenericAttrs from './GenericAttrs';
import { PostTypes } from './post_type';

export default function Edit ( props ) {
    const { attributes, setAttributes, instanceId, focus, isSelected } = props;
    const { textAlign, label } = props.attributes;
    const blockProps = useBlockProps( {
        className: clsx( {
            [ `has-text-align-${ textAlign }` ]: textAlign,
        } ),
    } );

        const onChangeContent = ( value ) => {
            setAttributes( { content: value } );
        };

        const onChangeParameters = ( value ) => {
            setAttributes( { parameters: value } );
        }

        const onChangeShortcode = ( value ) => {

            //attributes = getAttributes( value );
            setAttributes( { shortcode: value } );
        };


        function onChangeAttr( key, value ) {
            //var nextAttributes = {};
            //nextAttributes[ key ] = value;
            //setAttributes( nextAttributes );
            setAttributes( { [key] : value } );
        };

        const onChangePostType = ( value ) => {
            //attributes = getAttributes( value );
            setAttributes( { post_type: value });
        };

        const onChangePostParent = ( value ) => {
            setAttributes( { post_parent: value });
        };

        const onChangeNumberPosts = ( value ) => {
            setAttributes( { numberposts: value } );
        };

        const onChangeOrderBy = ( value ) => {
            setAttributes( { orderby: value } );
        };

        const onChangeOrder = ( value ) => {
            setAttributes( { order: value } );
        };

        const onChangeFormat = ( value ) => {
            setAttributes( { format: value } );
        };

        /*
                           <GenericAttrs value={attributes.shortcode} />
        */
        return (
            <Fragment>

                <InspectorControls>
                    <PanelBody>
                        <SelectControl label="Display" value={attributes.shortcode}
                                       options={ map( bw_shortcodes, ( key, label ) => ( { value: label, label: key + ' - ' + label } ) ) }
                                       onChange={partial( onChangeAttr, 'shortcode' )}
                        />

                        <PostTypes value={ attributes.post_type } onChange={ onChangePostType } />
                        <SelectTextControlCombo label="Order by" value={ attributes.orderby} options={ orderby} onChange={onChangeOrderBy} />
                        <SelectControl label="Order" value={ attributes.order} options={ order} onChange={onChangeOrder} />
                        <RangeControl label="Number posts" value={ attributes.numberposts } onChange={ onChangeNumberPosts } min={-1} max={100} />
                        <TextControl value={ attributes.post_parent} onChange={ onChangePostParent } label="Post Parent" />
                        <Formats value={attributes.format} onChange={onChangeFormat}  />

                        <TextareaControl label="Advanced Parameters"
                                         value={ attributes.parameters }
                                         placeholder={ __( 'Enter your advanced shortcode parameters' ) }
                                         onChange={onChangeParameters}
                                         rows="1"
                        />







                    </PanelBody>
                </InspectorControls>


                <div className="wp-block-oik-block-shortcode wp-block-shortcode">
                    <SelectControl label="Display" value={attributes.shortcode}
                                   options={ map( bw_shortcodes, ( key, label ) => ( { value: label, label: key + ' - ' + label } ) ) }
                                   onChange={partial( onChangeAttr, 'shortcode' )}
                    />



                </div>
                <div { ...blockProps}>
                    <ServerSideRender
                        block="oik/content-block" attributes={ attributes }
                    />
                </div>
            </Fragment>

        );
    }