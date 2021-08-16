/**
 * Implements the NumberPosts control
 *
 * Usage:
 * <NumberPosts value={ attributes.numberposts } onChange={onChangeNumberPosts} />
 *
 * @copyright (C) Copyright Bobbing Wide 2020
 * @author Herb Miller @bobbingwide
 */
const { RangeControl} = wp.components;
const { Component } = wp.element;

export class NumberPosts extends Component {
    constructor() {
        super( ...arguments );
        console.log( this );
    }

    render( ) {

        return(
            <RangeControl
                label="Number Posts"
                value={ this.props.numberposts }
                onChange={ this.props.onChange }
                min={ 1 }
                max={ 100 }
                allowRest={true}
            />
        );
    }
}








