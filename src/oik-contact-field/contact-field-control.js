/**
 * Displays the Contact field control.
 *
 * Simulates the field in the form.
 *
 * @copyright (C) Copyright Bobbing Wide 2022
 * @author Herb Miller @bobbingwide
 */

import {
    CheckboxControl,
    TextControl,
    TextareaControl,
    ToggleControl,
    SelectControl } from '@wordpress/components';

const ContactFieldControl = ( { setAttributes, type, name, required }) => {
    if ( type === 'textarea' ) {
        return(
        <TextareaControl type={type} name={name} required={required}/>
        );
    } else if ( type === 'checkbox' )  {
        return(
            <CheckboxControl name={name} required={required} />
        );
    } else {
        return(
            <TextControl type={type} name={name} required={required}/>
        );
    }
}

export { ContactFieldControl };