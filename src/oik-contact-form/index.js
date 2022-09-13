/**
 * Implements the Contact form block
 *
 * Originally equivalent to [bw_contact_form]
 * Now equivalent to [bw_contact_form][bw_contact_field "Name"] etc [/bw_contact_form]
 *
 * @copyright (C) Copyright Bobbing Wide 2018, 2020, 2021, 2022
 * @author Herb Miller @bobbingwide
 */
import './style.scss';
import './editor.scss';


import { __ } from '@wordpress/i18n';
import classnames from 'classnames';

import { registerBlockType, createBlock } from '@wordpress/blocks';
import {AlignmentControl, BlockControls, InspectorControls, useBlockProps, PlainText, BlockIcon, InnerBlocks} from '@wordpress/block-editor';
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

registerBlockType(
    // Namespaced, hyphens, lowercase, unique name
		'oik/contact-form',
    {
        example: {
        },
        transforms,

		edit: props=> {
            const { attributes, setAttributes, instanceId, focus, isSelected } = props;
            const { textAlign, label } = props.attributes;
            const blockProps = useBlockProps( {
                className: classnames( {
                    [ `has-text-align-${ textAlign }` ]: textAlign,
                } ),
            } );

          const innerBlocksTemplate = [ ['oik/contact-field', { label: 'Name', type: 'text', 'required': true, 'requiredIndicator': ' *' }],
                                        ['oik/contact-field', { label: 'Email', type: 'email', 'required': true, 'requiredIndicator': ' *'  }],
                                        ['oik/contact-field', { label: 'Subject', type: 'text', 'required': false }],
                                        ['oik/contact-field', { label: 'Message', type:'textarea', 'required': true, 'requiredIndicator': ' *'  }]
                                    ];
            const onChangeContact = (event) => {
                props.setAttributes({contact: event});
            };
            const onChangeEmail = (event) => {
                   props.setAttributes({email: event});

            };
            /**
             * Reset fields to defaults if they're blanked out by the user.
             */
            const { email, contact } = attributes;
            if ( email=== undefined || email.trim() === '') {
                const attributeSettings = wp.data.select('core/blocks').getBlockType('oik/contact-form').attributes;
                props.setAttributes({email: attributeSettings.email.default});
            }
            if ( contact===undefined || contact.trim() === '' ) {
                const attributeSettings = wp.data.select('core/blocks').getBlockType('oik/contact-form').attributes;
                props.setAttributes({contact: attributeSettings.contact.default});
            }
          return (
              <Fragment>
                  <InspectorControls>
                      <PanelBody>
                          <PanelRow>
                              <TextControl label={__("Email address", "oik" )} value={attributes.email} onChange={onChangeEmail}/>
                          </PanelRow>
                          <PanelRow>
                              <TextControl label={__("Text for Submit button", "oik" )} value={attributes.contact} onChange={onChangeContact}/>
                          </PanelRow>
                      </PanelBody>
                  </InspectorControls>

					<div { ...blockProps}>
                        { false &&
                        <ServerSideRender
                            block="oik/contact-form" attributes={props.attributes}
                        />
                        }
                        <InnerBlocks template={ innerBlocksTemplate } allowedBlocks={ ['oik/contact-field'] }/>
                        <input type="submit" value={ props.attributes.contact } />

                    </div>
              </Fragment>
          );
        },
				
		save() {
            const blockProps = useBlockProps.save();

            return (
                <div { ...blockProps }>
                    <InnerBlocks.Content />
                </div>
            );
		}
    }
);