const { __ } = wp.i18n;

const shortcode_attributes =
    {
        shortcode: {
            type: 'string',
            default: '',
        },

       parameters: {
            type: 'string',
            default: '',
        },

        post_type: {
            type: 'string',
            default: '',
        },

        // post_parent = ., 0, ID null
        post_parent: {
            type: 'string',
            default: '',
        },

        // number_posts = -1, 1->100
        numberposts: {
            type: 'integer',
            default: 10,
        },

        // orderby =
        orderby: {
            type: 'string',
            default: 'date',
        },

        // order = ASC / DESC
        order: {
            type: 'string',
            default: 'desc',
        },

        format: {
            type: 'string',
            default: 'LIER',
        },

        // categories=
        // category_name=
        // customcategoryname=
        // include
        // exclude
        // offset = null,
        // meta_key
        // meta_value
        // meta_compare
        // post_status
        // id=id1,id2

        // posts_per_page
        //


    };


const orderby = [
    { label: 'Date', value: 'date',},
    { label: 'ID', value: 'ID',},
    { label: 'Title', value: 'title',},
    { label: 'Parent', value: 'parent', },
    { label: 'Random', value: 'rand',},
    { label: 'Menu order', value: 'menu_order',},
    ];

const order =
    [
        {
            label: __( 'Descending order' ),
            value: 'desc',
        },
        {
            label: __( 'Ascending order' ),
            value: 'asc',
        },

    ];



export { shortcode_attributes, orderby, order };
