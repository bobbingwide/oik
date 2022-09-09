import {
    TextControl,
    TextareaControl,
    ToggleControl,
    SelectControl } from '@wordpress/components';

const ContactFieldControl = ( { setAttributes, type, name, required }) => {
    if ( type === 'textarea' ) {
        return(
        <TextareaControl type={type} name={name} required={required}/>
        );
    } else {
        return(

            <TextControl type={type} name={name} required={required}/>

        );
    }
}

export { ContactFieldControl };