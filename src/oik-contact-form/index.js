/**
 * Implements the Contact form block
 *
 * Equivalent to [bw_contact_form]
 *
 * @copyright (C) Copyright Bobbing Wide 2018, 2020, 2021
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

          const innerBlocksTemplate = [ ['oik/contact-field', { label: 'Name', type: 'text'}],
                                        ['oik/contact-field', { label: 'Email', type: 'email'}],
                                        ['oik/contact-field', { label: 'Message', type:'textarea'}]
                                    ];
            const onChangeContact = (event) => {
                props.setAttributes({contact: event});
            };
          return (
              <Fragment>
                  <InspectorControls>
                      <PanelBody>
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

                        <table>
                            <tbody>
                                <InnerBlocks template={ innerBlocksTemplate } allowedBlocks={ ['oik/contact-field'] }/>
                            </tbody>
                        </table>
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