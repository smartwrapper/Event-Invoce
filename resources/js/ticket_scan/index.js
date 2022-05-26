
/**
 * This is a page specific seperate vue instance initializer
 */

// include vue common libraries, plugins and components
require('../vue_common');

/**
 * Local Third-party Lib Imports
*/
/* Instances */
import VueQrcodeReader from "vue-qrcode-reader";
window.VueQrcodeReader = VueQrcodeReader;
Vue.use(VueQrcodeReader);


/**
 * Local Components 
 */
Vue.component('ticket-scan', require('./components/TicketScan.vue').default);


/**
 * This is where we finally create a page specific
 * vue instance with required configs
 * element=app will remain common for all vue instances
 * 
 */
window.app = new Vue({
    el: '#eventmie_app',
    
});