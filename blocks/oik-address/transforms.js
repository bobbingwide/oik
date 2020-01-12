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
    ],
};

export { transforms } ;