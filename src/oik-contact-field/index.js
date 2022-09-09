/**
 * Implements the Contact field block
 *
 * Equivalent to [bw_contact_field label="Name *" type=text name="name" required=y ]
 *
 * @copyright (C) Copyright Bobbing Wide 2022
 * @author Herb Miller @bobbingwide
 */
//import './style.scss';
//import './editor.scss';


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
    ToggleControl,
    SelectControl } from '@wordpress/components';
import { Fragment} from '@wordpress/element';
import { map, partial } from 'lodash';
import { ContactFieldControl } from './contact-field-control';

const fieldTypes =

    [
        {
            label: __( 'Text' ),
            value: 'text',
        },
        {
            label: __( 'Textarea' ),
            value: 'textarea',
        },
        {
            label: __( 'Email' ),
            value: 'email',
        },

    ];

registerBlockType(
    // Namespaced, hyphens, lowercase, unique name
    'oik/contact-field',
    {
        example: {
        },

        edit: props=> {
            const { attributes, setAttributes, instanceId, focus, isSelected } = props;
            const { textAlign, label } = props.attributes;
            const blockProps = useBlockProps( {
                className: classnames( {
                    [ `has-text-align-${ textAlign }` ]: textAlign,
                } ),
            } );
            const onChangeLabel = (event) => {
                props.setAttributes({label: event});
            };
            const onChangeType = (event) => {
                props.setAttributes({type: event});
            };
            const onChangeRequired = (event) => {
                props.setAttributes({required: event});
            };
            return (
                <Fragment>
                <InspectorControls>
                    <PanelBody>
                        <PanelRow>
                            <TextControl label={__("Label", "oik" )} value={attributes.label} onChange={onChangeLabel}/>
                        </PanelRow>
                    </PanelBody>
                    <PanelBody>
                        <PanelRow>
                            <SelectControl label={__("Type", "oik" )} value={attributes.type} onChange={onChangeType} options={fieldTypes}/>
                        </PanelRow>
                    </PanelBody>
                    <PanelBody>
                        <PanelRow>
                            <ToggleControl label={__("Required?", "oik" )} checked={ !! attributes.required } onChange={onChangeRequired}/>
                        </PanelRow>
                    </PanelBody>
                </InspectorControls>

                    <div { ...blockProps}>
                    <tr>
                        <td>
                            <label for={ attributes.name }>{attributes.label}</label>
                        </td>
                        <td>
                            <ContactFieldControl type={attributes.type} name={attributes.name} required={attributes.required} />

                        </td>
                    </tr>
                    </div>


                </Fragment>
            );
        },

        save() {
            // Rendering in PHP
            return null;
        }
    }
);