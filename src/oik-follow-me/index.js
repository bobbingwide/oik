/**
 * Follow me block for the [bw_follow_me] shortcode
 *
 * @copyright (C) Copyright Bobbing Wide 2018, 2019, 2020, 2021, 2022
 * @author Herb Miller @bobbingwide
 */
import './style.scss';
import './editor.scss';

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
	TextControl,
	TextareaControl,
	SelectControl } from '@wordpress/components';
import { Fragment} from '@wordpress/element';
import { map, partial } from 'lodash';
import { transforms } from './transforms.js';

import { themeOptions} from './themeOptions.js';

/**
 * Register the block
 */
registerBlockType(
    // Namespaced, hyphens, lowercase, unique name
    'oik/follow-me',
	{
		example: {},
		transforms,

		edit: props => {
			const {attributes, setAttributes, instanceId, focus, isSelected} = props;
			const {textAlign, label} = props.attributes;
			const blockProps = useBlockProps({
				className: clsx({
					[`has-text-align-${textAlign}`]: textAlign,
				}),
			});

			const onChangeUser = (event) => {
				props.setAttributes({user: event});
			};

			const onChangeAlt = (event) => {
				props.setAttributes({alt: event});
			};

			const onChangeNetwork = (event) => {
				props.setAttributes({network: event});
			};

			const onChangeTheme = (event) => {
				props.setAttributes({theme: event});
			}

			var atts = props.attributes;
			var chatts = '';
			for (var key of Object.keys(atts)) {
				var value = atts[key];
				if (value) {
					var chatts = chatts.concat(" ", key, "=", value);
				}
			}
			var lsb = '['; // &#91;
			var rsb = ']'; // &#93;
			var user = props.attributes.user;

			var equivalent_shortcode = `${lsb}bw_follow_me${chatts}${rsb}`;
			//console.log(chatts);
			//console.log(equivalent_shortcode);

			return (

				<Fragment>
					<InspectorControls>
						<PanelBody key="pb">
							<PanelRow>
								<TextControl label={__("User", "oik" )}value={props.attributes.user} onChange={onChangeUser}/>
							</PanelRow>
							<PanelRow>
								<TextControl label={__("Alt", "oik" )}value={props.attributes.alt} onChange={onChangeAlt}/>
							</PanelRow>
							<PanelRow>
								<TextControl label={__("Network(s)", "oik" )}value={props.attributes.network}
											 onChange={onChangeNetwork}/>
							</PanelRow>
							<PanelRow>
								<SelectControl label={__("Theme", "oik" )}value={props.attributes.theme}
											   options={map(themeOptions, (key, label) => ({value: label, label: key}))}
											   onChange={onChangeTheme}
								/>

							</PanelRow>

							<PanelRow>
								{__("Equivalent shortcode", "oik" )}<br/>
								{equivalent_shortcode}
							</PanelRow>
						</PanelBody>
					</InspectorControls>
					<div {...blockProps}>
						<ServerSideRender
							block="oik/follow-me" attributes={props.attributes}
						/>
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