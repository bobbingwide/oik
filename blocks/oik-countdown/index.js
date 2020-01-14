/**
 * Implements the Countdown block
 *
 * Equivalent to [bw_countdown]
 * The block is dynamic, but cannot be Server Side Rendered since
 * the original implementation generates inline jQuery and CSS which isn't enqueued in REST processing.
 *
 * @copyright (C) Copyright Bobbing Wide 2019, 2020
 * @author Herb Miller @bobbingwide
 */
import './style.scss';
import './editor.scss';


// Get just the __() localization function from wp.i18n
const { __ } = wp.i18n;

// Get registerBlockType and Editable from wp.blocks
const { 
	registerBlockType, 
} = wp.blocks;

const {
	ServerSideRender
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

const {
	withInstanceId,
} = wp.compose;	

const Fragment = wp.element.Fragment;


/**
 * Attempt to find an easier way to define each attribute
 * which is a shortcode parameter
 */
const blockAttributes = {
	since: {
		type: 'string',
		default: '',
	},
	until: {
		type: 'string',
		default: '',
	},
	url: {
		type: 'string',
		default: '',
	},
	description: {
		type: 'string',
		default: '',
	},
	expirytext: {
		type: 'string',
		default: '',
	},
	format: {
		type: 'string',
		default: '',
	},
	
};



/**
 * Register the Countdown timer
 */
registerBlockType(
    // Namespaced, hyphens, lowercase, unique name
    'oik/countdown',
    {
        // Localize title using wp.i18n.__()
        title: __( 'Countdown' ),
				
		description: 'Countdown timer',

        // Category Options: common, formatting, layout, widgets, embed
        category: 'common',

        // Dashicons Options - https://goo.gl/aTM1DQ
        icon: 'clock',

        // Limit to 3 Keywords / Phrases
        keywords: [
            __( 'Countdown' ),
			__( 'timer' ),
			__( 'since' ),
            __( 'oik' ),
        ],

        // Set for each piece of dynamic data used in your block
        attributes: blockAttributes,
				
		supports: { html: false, className: false},

        edit: props => {
					
					const onChangeSince = ( event ) => {
						props.setAttributes( { since: event } );
					};
					
					const onChangeUntil = ( event ) => {
						props.setAttributes( { until: event } );
					};
					
					
					const onChangeURL = ( event ) => {
						props.setAttributes( { url: event } );
					};
					
					const onChangeDescription = ( event ) => {
						props.setAttributes( { description: event } );
					};
					
					const onChangeExpiryText = ( event ) => {
						props.setAttributes( { expirytext: event } );
					};
					
					const onChangeFormat = ( event ) => {
						props.setAttributes( { format: event } );
					};
					
					// For the time being we'll show the generated shortcode.
					var atts = props.attributes;
					var chatts = '[bw_countdown'; 	
					for (var key of Object.keys( atts )) {
						var value = atts[key];
						if ( value ) {
							chatts = chatts + " " + key + "=\"" + value + '"';
						}
					}
					chatts = chatts + ']';
					
          return (
          	<Fragment>
						
  					  <InspectorControls>
							<PanelBody>
								<PanelRow>
								<TextControl label="Since" value={props.attributes.since} onChange={onChangeSince} />
								</PanelRow>
								<PanelRow>
						  		<TextControl label="Until" value={props.attributes.until} onChange={onChangeUntil} />
								</PanelRow>
								<PanelRow>
								<TextControl label="URL" value={props.attributes.url} onChange={onChangeURL} />
								</PanelRow>
								<PanelRow>
                					<TextControl label="Description" value={props.attributes.description} onChange={onChangeDescription} />
								</PanelRow>
						  		<PanelRow>
								<TextControl label="Expiry Text" value={props.attributes.expirytext} onChange={onChangeExpiryText}  />
						  		</PanelRow>
						  		<PanelRow>
						 		<TextControl label="Format" value={props.attributes.format} onChange={onChangeFormat} />
						  		</PanelRow>
							 </PanelBody>

					
					

          	</InspectorControls>
				<div className={ props.className } >
					<p>The Countdown block will be rendered on the front end. <br />Shortcode equivalent:
					<br />
				{chatts}
					</p>
				</div>

			</Fragment>
			);
		},

		save() {
			// Rendering in PHP
			return null;
		},
        saver: props => {
					var lsb = '[';
					var rsb = ']';
					var atts = props.attributes;
					var chatts = '[bw_countdown'; 	
					for (var key of Object.keys( atts )) {
						var value = atts[key];
						if ( value ) {
							chatts = chatts + " " + key + "=\"" + value + '"';
						}
					}
					chatts = chatts + ']';
					
					
					//const createMarkup()
					
					//props.setAttributes( { content: chatts } );
					//console.log( chatts );
					//console.log( props.attributes.content );
          return( <RawHTML>{chatts}</RawHTML> );
					 
  
        },
    },
);
