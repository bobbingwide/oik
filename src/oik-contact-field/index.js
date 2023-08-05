/**
 * Implements the Contact field block
 *
 * Equivalent to [bw_contact_field label="Name *" type=text name="name" required=y ]
 *
 * @copyright (C) Copyright Bobbing Wide 2022
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
    ToggleControl,
    SelectControl } from '@wordpress/components';
import { Fragment} from '@wordpress/element';
import { map, partial } from 'lodash';
import { ContactFieldControl } from './contact-field-control';

const fieldTypes =

    [
        {
            label: __( 'Text', 'oik' ),
            value: 'text',
        },
        {
            label: __( 'Textarea', 'oik' ),
            value: 'textarea',
        },
        {
            label: __( 'Email', 'oik' ),
            value: 'email',
        },
        {
            label: __( 'Checkbox', 'oik' ),
            value: 'checkbox',
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
            const blockProps = useBlockProps();
            const onChangeLabel = (event) => {
                props.setAttributes({label: event});
            };
            const onChangeType = (event) => {
                props.setAttributes({type: event});
            };
            const onChangeRequired = (event) => {
                props.setAttributes({required: event});
            };
            const onChangeRequiredIndicator = (event) => {
                props.setAttributes({requiredIndicator: event});
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
                        <PanelRow>
                            <TextControl label={__("Required indicator", "oik" )} value={attributes.requiredIndicator} onChange={onChangeRequiredIndicator}/>

                        </PanelRow>
                    </PanelBody>
                </InspectorControls>

                    <div { ...blockProps}>
                        <div>
                        <div className="label">
                            <label htmlFor={ attributes.name }>{attributes.label}
                                { attributes.required && <span className="required">{attributes.requiredIndicator}</span> }
                            </label>
                        </div>
                        <div className="field">
                            <ContactFieldControl type={attributes.type} name={attributes.name} required={attributes.required} />
                        </div>
                    </div>
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