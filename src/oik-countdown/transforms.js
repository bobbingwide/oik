/**
 * Transformation to oik/countdown of oik-blocks/countdown and [bw_coundtown].
 */
const { createBlock
} = wp.blocks;

const transforms = {
    from: [
        {
            type: 'block',
            blocks: ['oik-block/countdown'],
            transform: function (attributes) {
                return createBlock('oik/countdown', attributes  );
            },
        },
        {
            type: 'shortcode',
            tag: 'bw_countdown',
            attributes: {
                since: {
                    type: 'string',
                    shortcode: ( { named: { since } } ) => {
                        return since;
                    },
                },
                until: {
                    type: 'string',
                    shortcode: ( { named: { until } } ) => {
                        return until;
                    },
                },
                url: {
                    type: 'string',
                    shortcode: ( { named: { url } } ) => {
                        return url;
                    },
                },
                description: {
                    type: 'string',
                    shortcode: ( { named: { description } } ) => {
                        return description;
                    },
                },
                expirytext: {
                    type: 'string',
                    shortcode: ( { named: { expirytext } } ) => {
                        return expirytext;
                    },
                },
                format: {
                    type: 'string',
                    shortcode: ( { named: { format } } ) => {
                        return format;
                    },
                },

            },
        },
    ]
};

export { transforms } ;