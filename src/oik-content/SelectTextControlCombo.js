/**
 * Implements a Select Control / Text control combo where the
 * text value overrides the option(s) selected from the SelectControl.
 *
 *
 * @copyright (C) Copyright Bobbing Wide 2020
 * @author Herb Miller @bobbingwide
 */


const { Component, Fragment } = wp.element;
const { SelectControl, TextControl } = wp.components;

export class SelectTextControlCombo extends Component {
    constructor() {
        super(...arguments);

    }

    renderSelect( props ) {

            //var options = formats.map((format) => this.formatsOption(format));
        var custom_label = `Custom value: ${this.props.label}`;
            return (
                <Fragment>
                <SelectControl label={this.props.label} value={this.props.value}
                               options={this.props.options}
                               onChange={this.props.onChange}
                />
                <TextControl label={custom_label} hideLabelFromVision={ true } value={this.props.value} onChange={this.props.onChange} />
                </Fragment>
            );

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
        return( this.renderSelect()
        );
    }
}