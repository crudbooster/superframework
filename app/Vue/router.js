/* System Import Router */
import dashboard from "./dashboard/index.vue.js";
import profile from "./user/profile.vue.js";
/* User Custom Import Router */

const router_list = [
    /* System router */
{
    path: '/dashboard',
    component: dashboard
},{
    path: '/profile',
    component: profile
},
    /* User Custom Router */

];
export{ router_list }