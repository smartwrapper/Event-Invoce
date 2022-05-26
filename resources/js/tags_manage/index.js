
/**
 * This is a page specific seperate vue instance initializer
 */

// include vue common libraries, plugins and components
require('../vue_common');

/**
 * Local Third-party Lib Imports
*/

/* Components */
import CKEditor from 'ckeditor4-vue';
window.ckeditor = CKEditor.component;
Vue.component('ckeditor', window.ckeditor).default;


/**
 * Local Components 
 */
import Tags from './components/Tags';

/**
 * Local Vue Routes 
 */
const routes = new VueRouter({
    mode: 'history',
    base: '/',
    linkExactActiveClass: 'there',
    routes: [
        {
            path: path ? '/'+path+'/mytags' : '/mytags',
            // Inject  props based on route.query values for pagination
            props: (route) => ({
                page: route.query.page,
                
            }),
            name: 'Tags',
            component: Tags,

        },
    ],
});

/**
 * This is where we finally create a page specific
 * vue instance with required configs
 * element=app will remain common for all vue instances
 * 
 */
window.app = new Vue({
    el: '#eventmie_app',
    router: routes,
});