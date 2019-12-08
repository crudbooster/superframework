/* System Import Router */
import login from "./auth/login.vue.js";
import forgot from "./auth/forgot.vue.js";
import dashboard from "./dashboard/index.vue.js";
import profile from "./user/profile.vue.js";
/* User Custom Import Router */

const router_list = [
    /* System router */
{
    path: '/auth/login',
    component: login
},{
    path: '/auth/forgot',
    component: forgot
},{
    path: '/dashboard',
    component: dashboard
},{
    path: '/profile',
    component: profile
},
    /* User Custom Router */

];
export{ router_list }