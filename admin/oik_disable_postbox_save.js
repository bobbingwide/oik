/**
 * @since 4.16.0
 * @version 0.0.1
 * Disables the saving of postboxes when used in oik admin pages.
 * The first call prevents saving of the postbox order.
 * The second call prevents saving of the postbox state.
 * @depends Core's postbox.js
 */
postboxes.save_order = function() {};
postboxes.save_state = function() {};