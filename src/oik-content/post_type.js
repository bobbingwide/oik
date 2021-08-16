/**
 * Implements the PostTypes selection list
 *
 * @copyright (C) Copyright Bobbing Wide 2020
 * @author Herb Miller @bobbingwide
 */
const { select, subscribe } = wp.data;
const { Component } = wp.element;
const { SelectControl } = wp.components;

export class PostTypes extends Component {
    constructor() {
        super( ...arguments );
        this.state = {
            postTypes: [],

        };
        //console.log( this.state);
        //console.log( this );
    }

    componentDidMount() {
        const unsubscribe = subscribe( () => {
            const postTypes = select( 'core').getPostTypes( { per_page: -1 });
            //console.group( "PT");
            //console.log( this.state.postTypes);
            //console.log( postTypes );
            //console.groupEnd();
            this.setState( { postTypes } );
        })
    }

    postTypeList() {
        var postTypes = this.state.postTypes;
        if ( postTypes ) {
        return(
        <ul>
            { postTypes.map((  postType ) => this.postTypeMap( postType ) )}
        </ul>
        ) } else {
            return( <p>Post type</p>)
        }
    }


    postTypeListSelect( props ) {
        var postTypes = this.state.postTypes;
        if ( postTypes ) {
            var options = postTypes.map(( postType ) => this.postTypeOption( postType ) );
            return(
                <SelectControl label="Post Type" value={this.props.postType}
                           options={options}
                           onChange={ this.props.onChange}
                />
            );
        } else {
            return( <p>Loading post types</p>);
        }
    }

    /**
     * Map the postTypes to a select list
     * @param postType
     * @returns {*}
     */

    postTypeMap( postType ) {
        console.log( postType );
        return( <li>{postType.slug}</li>);
    }

    /**
     * Map the postType to a select list option
     *
     * @param postType
     * @returns {{label: *, value: *}}
     */
    postTypeOption( postType ) {
        return( { value: postType.slug, label: postType.name });
    }

    render() {
        return( this.postTypeListSelect()
        );
    }
}