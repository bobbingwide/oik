const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );
module.exports = {
    ...defaultConfig,
    entry: {
        'address': './src/oik-address',
        'contact-form': './src/oik-contact-form',
        'contact-field': '/src/oik-contact-field',
        'content-block': './src/oik-content',
        'countdown': './src/oik-countdown',
        'follow-me': './src/oik-follow-me',
        'googlemap': './src/oik-googlemap',
        'paypal': './src/oik-paypal',
        'shortcode-block': './src/oik-shortcode'
    },
};