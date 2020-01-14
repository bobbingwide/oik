/* Transformation to oik/googlemap of oik-block/googlemap and [bw_show_googlemap]
 *
 */
const { createBlock
} = wp.blocks;

const transforms = {
    from: [
        {
            type: 'block',
            blocks: ['oik-block/googlemap'],
            transform: function (attributes) {
                return createBlock('oik/googlemap', {
                });
            },
        },
        {
            type: 'shortcode',
            tag: 'bw_show_googlemap',
            attributes: {
                },
        },
    ]
};

export { transforms } ;