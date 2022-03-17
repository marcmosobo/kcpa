
require('alpinejs');
require('./bootstrap');
// Require Vue
window.Vue = require('vue').default;

//using vform
import Form from 'vform';
window.Form = Form;

//  //custom event
window.Fire = new Vue();

//progressbar
import VueProgressBar from 'vue-progressbar';
Vue.use(VueProgressBar,{
  color: 'rgb(143, 255, 199)',
  failedColor: 'red',
  height: '3px'
});

//sweetalert2
import Swal from 'sweetalert2';
window.Swal = Swal;

const toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
});

window.toast = toast;
// Register Vue Components
Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('settings-component', require('./components/Settings.vue').default);
Vue.component('fees-component', require('./components/Fees.vue').default);

// Initialize Vue
const app = new Vue({
    el: '#layoutSidenav',
    data(){
      return{
        bladeValue: ''
      }
    }
});
 
 