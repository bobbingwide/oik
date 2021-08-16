/**
 * Implements the oik dynamic content shortcodes block
 *
 * @copyright (C) Copyright Bobbing Wide 2020,2021
 * @author Herb Miller @bobbingwide
 */
//import './style.scss';
import './editor.scss';


import { registerBlockType } from '@wordpress/blocks';
import Edit from './edit';
/**
 * Register the oik-block/shortcode-block block
 *
 * registerBlockType is a function which takes the name of the block to register
 * and an object that contains the properties of the block.
 * Some of these properties are objects and others are functions
 */
export default registerBlockType(
    // Namespaced, hyphens, lowercase, unique name
    'oik/content-block',
    {
        /**
         * @see ./edit.js
         */
        edit: Edit,
        save( { attributes } ) {
            return null;
        }
    }
);

