/**
 * Implements a control to represent the format= parameter to the bw_pages shortcode.
 *
 * @copyright (C) Copyright Bobbing Wide 2020
 * @author Herb Miller @bobbingwide
 */

const format_options = [
    { label: 'Link, image, excerpt, read more', value: 'LIER',},
    { label: 'Link, image', value: 'LI', },
    { label: 'Link, image, excerpt', value: 'LIE' },
    { label: 'Image, link', value: 'IL', },
    { label: 'Image, link, excerpt', value: 'ILE' },
    { label: 'Link, Image, Date, Author', value: 'LI/d/a'},
];

/**
 * Each of the different letters in the format_options value fields has a meaning.
 * It would make sense to help the user to type the right characters when
 * defining a Custom value.
 */
const single_format_options = [
    { label: 'None', value: null, },
    { label: 'Title', value: 'T', },
    { label: 'Link', value: 'L', },
    { label: 'Image', value: 'I', },
    { label: 'Excerpt', value: 'E', },
    { label: 'Read more', value: 'R', },
    { label: 'Div', value: '/', },
    { label: 'Fields', value: '_',},
    { label: 'Space', value: ' ',},
    { label: 'Content', value: 'C',},
    { label: 'Categories', value: 'c', },
    { label: 'Tags', value: 't'},
    { label: 'Author', value: 'a'},
    { label: 'Date', value: 'd' },
    { label: 'Edit', value: 'e' },
    ];

const { Component } = wp.element;
import { SelectTextControlCombo } from './SelectTextControlCombo';

export class Formats extends Component {
    constructor() {
        super(...arguments);
        this.state = {
            formats: format_options,
        };
    }

    formatsSelect( props ) {
        var formats = this.state.formats;
        if (formats) {
            //var options = formats.map((format) => this.formatsOption(format));
            return (
                <SelectTextControlCombo label="Format" value={this.props.format}
                           options={format_options}
                           onChange={this.props.onChange}
                />
            );
        } else {
            return (<p>Loading formats</p>);
        }
    }



        /**
         * Map the format_option to a select list option
         *
         * @param format
         * @returns {{label: *, value: *}}
         */
        formatOption( format ) {
            return( { value: format.value, label: format.label });
        }

        render() {
            return( this.formatsSelect()
            );
        }
    }