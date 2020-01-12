/* Transformation of oik-blocks/address to oik/address
 *
 */
const { createBlock
} = wp.blocks;

const transforms = {
    from: [
        {
            type: 'block',
            blocks: ['oik-block/address'],
            transform: function (attributes) {
                return createBlock('oik/address', {
                    tag: attributes.tag,
                });
            },
        },
        {
            type: 'shortcode',
            tag: 'bw_address',
            attributes: {
                tag: {
                    type: 'string',
                    shortcode: ( { named: { tag } } ) => {
                        return tag;
                    },
                },

            },
        },
        ]
};

export { transforms } ;