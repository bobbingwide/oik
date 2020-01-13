/* Transformation to oik-follow-me of oik-block/follow-me and [bw_follow_me]
 *
 */
const { createBlock
} = wp.blocks;

const transforms = {
    from: [
        {
            type: 'block',
            blocks: ['oik-block/follow-me'],
            transform: function (attributes) {
                return createBlock('oik/follow-me', {
                    user: attributes.user,
                    alt: attributes.alt,
                    network: attributes.network,
                    theme: attributes.theme,
                });
            },
        },
        {
            type: 'shortcode',
            tag: 'bw_follow_me',
            attributes: {
                user: {
                    type: 'string',
                    shortcode: ( { named: { user } } ) => {
                        return user;
                    },
                },
                alt: {
                    type: 'string',
                    shortcode: ( { named: { alt } } ) => {
                        return alt;
                    },
                },
                network: {
                    type: 'string',
                    shortcode: ( { named: { network } } ) => {
                        return network;
                    },
                },
                theme: {
                    type: 'string',
                    shortcode: ( { named: { theme } } ) => {
                        return theme;
                    },
                },

            },
        },
    ]
};

export { transforms } ;