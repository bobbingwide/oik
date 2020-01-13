/**
 * Follow me block for the [bw_follow_me] shortcode
 *
 *
 *
 * @copyright (C) Copyright Bobbing Wide 2018, 2019, 2020
 * @author Herb Miller @bobbingwide
 */
import './style.scss';
import './editor.scss';
import { transforms } from './transforms.js';

// Get just the __() localization function from wp.i18n
const { __ } = wp.i18n;
// Get registerBlockType and Editable from wp.blocks
const { 
	registerBlockType, 
} = wp.blocks;
const {
    Editable,
    PlainText,
    AlignmentToolbar,
    BlockControls,
	ServerSideRender,
} = wp.editor;
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
const Fragment = wp.element.Fragment;
import { map, partial } from 'lodash';

const blockAttributes = {
	user: {
		type: 'string',
		default: '',
	},
	alt: {
		type: 'string',
		default: '',
	},
	network: {
		type: 'string',
		default: '',
	},

	theme: {
		type: 'string',
		default: '',
	}
	
};



/**
 * Register e
 */
registerBlockType(
    // Namespaced, hyphens, lowercase, unique name
    'oik/follow-me',
    {
        // Localize title using wp.i18n.__()
        title: __( 'Follow me' ),
				
		description: 'Displays Social media links',

        // Category Options: common, formatting, layout, widgets, embed
        category: 'common',

        // Dashicons Options - https://goo.gl/aTM1DQ
        icon: 'share',

        // Limit to 3 Keywords / Phrases
        keywords: [
            __( 'Follow' ),
			__( 'Social'),
			__( 'Links'),
            __( 'oik' ),
        ],

        // Set for each piece of dynamic data used in your block
        attributes: blockAttributes,
				
		// supports: { html: false },
		example: {
		},
		supports: {
			customClassName: false,
			className: false,
			html: false,
		},
		transforms,

        edit: props => {
					
					const onChangeUser = ( event ) => {
						props.setAttributes( { user: event } );
					};
					
					const onChangeAlt = ( event ) => {
						props.setAttributes( { alt: event } );
					};
					
					const onChangeNetwork = ( event ) => {
						props.setAttributes( { network: event } );
					};

					const onChangeTheme = ( event ) => {
						props.setAttributes({theme: event});
					}

					//var atts = props.attributes;
					var children = [];
					//for (var key of Object.keys( atts )) {
					//	var value = atts[key];
					//	console.log( value );
					children.push( <TextControl label="User" value={props.attributes.user} id="hm001" instanceId="fm-user" onChange={onChangeUser}  /> );
									
					//}
					
					var atts = props.attributes;
					var chatts = '';
					for (var key of Object.keys( atts )) {
						var value = atts[key];
						if ( value ) {
							var chatts = chatts.concat( " " , key , "=" , value );
						}
					}
			var lsb = '['; // &#91;
			var rsb = ']'; // &#93;
			var user = props.attributes.user;


			var equivalent_shortcode = `${lsb}bw_follow_me${chatts}${rsb}`;
			console.log( chatts );
			console.log( equivalent_shortcode );
					
          return (
						
				<Fragment>
              <InspectorControls>
								<PanelBody key="pb">
								<PanelRow>
								<TextControl label="User" value={props.attributes.user} id="hm001" instanceId="fm-user" onChange={onChangeUser}  />
								</PanelRow>
								<PanelRow>
								<TextControl label="Alt" value={props.attributes.alt} id="hm002" instanceId="fm-alt" onChange={onChangeAlt}  />
								</PanelRow>
								<PanelRow>
								<TextControl label="Network(s)" value={props.attributes.network} id="hm003" instanceId="fm-network" onChange={onChangeNetwork}  />
								</PanelRow>

				  <PanelRow>
					  Equivalent shortcode<br />
					  {equivalent_shortcode}
				  </PanelRow>
								</PanelBody>
              </InspectorControls>
					
					
            <div >

						</div>
					<ServerSideRender
						block="oik/follow-me" attributes={ props.attributes }
					/>
				</Fragment>
		  );
        },



	save() {
		// Rendering in PHP
		return null;
	},

        saver: props => {
					// console.log( props );
					//var shortcode =  {props.attributes.issue} ;
					var lsb = '[';
					var rsb = ']';
					var user = props.attributes.user;
					
					var atts = props.attributes;
					var chatts = [];		
					for (var key of Object.keys( atts )) {
						var value = atts[key];
						if ( value ) {
							chatts.push( " " + key + "=" + value );
						}
					}
          return (
						<div>
						{lsb}
						bw_follow_me {chatts}
						{rsb}
						</div>
          );
        },
    },
);
