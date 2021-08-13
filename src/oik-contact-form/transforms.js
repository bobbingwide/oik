/**
 * Transformation to oik/contact-form of oik-blocks/contact-form and [bw_contact_form].
 */
const { createBlock
} = wp.blocks;

const transforms = {
    from: [
        {
            type: 'block',
            blocks: ['oik-block/contact-form'],
            transform: function (attributes) {
                return createBlock('oik/contact-form', {
                                   });
            },
        },
        {
            type: 'shortcode',
            tag: 'bw_contact_form',
            attributes: {
                user: {
                    type: 'string',
                    shortcode: ( { named: { user } } ) => {
                        return user;
                    },
                },
                contact: {
                    type: 'string',
                    shortcode: ( { named: { contact } } ) => {
                        return contact;
                    },
                },
                email: {
                    type: 'string',
                    shortcode: ( { named: { email } } ) => {
                        return email;
                    },
                },

            },
        },
    ]
};

export { transforms } ;