
/**
* First we will load all of this project's JavaScript dependencies which
* includes Vue and other libraries. It is a great starting point when
* building robust, powerful web applications using Vue and Laravel.
*/

require('./bootstrap');

window.Vue = require('vue');
window.VueRouter = require('vue-router').default;
window.VueAxios = require('vue-axios').default;
window.Axios = require('axios').default;

// let AppLayout = require('./components/Display.vue');

// // register Modules
// Vue.use(VueRouter, VueAxios, axios);

// const router = new VueRouter({ mode: 'history', routes: routes });

// new Vue(
// 	Vue.util.extend(
// 		{ router },
// 		AppLayout
// 		)
// 	).$mount('#app');



/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example-component', require('./components/ExampleComponent.vue'));
Vue.component('display', require('./components/Display.vue'));

const display = new Vue({
    el: '#app',
    data:{
    	messages: []
    },
    created() {
		Echo.join('displayChannel.' + branchId)
    		.listen('DisplayEvent', (e) => {
    			this.messages = [];
    			this.messages.push({
    				message: e.message
    			});

                console.log(e.message);
    		});
    }
});

const call = new Vue({
    el: '#call',
    data:{
        messages: []
    },
    created() {

        Echo.join('newQueueChannel.' + branchId)
        .listen('NewQueueEvent', (e) => {
            console.log(window.location.pathname);
            if(window.location.pathname == "/call"){
                alert("New queue coming in at " + e.serviceName)
                window.location.reload(true);
            }
        });

        Echo.join('cancelTicketChannel.' + callingId)
        .listen('CancelTicketEvent', (e) => {
            console.log(window.location.pathname);
            if(window.location.pathname == "/call"){
                alert("User cancel ticket.")
                window.location.reload(true);
            }
        });
    }
});
