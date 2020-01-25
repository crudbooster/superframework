import {router_list} from './router.js'

Vue.use(VueRouter)

const router = new VueRouter({routes: router_list})

new Vue({
    el: '#app',
    router,
    mounted() {
        this.checkSession()
        setTimeout(function() {$("#navbar-menu").show()},200)
    },
    data() {
        return {
            is_loading: false,
            base_api: base_api,
            users_id: "",
            users_name: "",
            users_email: ""
        }
    },
    methods: {
        showLoading: function() {
            this.is_loading = true
        },
        hideLoading: function() {
            this.is_loading = false
        },
        alertSuccess: function(message) {
            Swal.fire({
                animation: false,
                title: "Success",
                text: message,
                icon: "success"
            })
        },
        alertFailed: function(message) {
            Swal.fire({
                animation: false,
                title: "Failed",
                text: message,
                icon: "danger"
            })
            Swal.fire("Failed", message, "danger")
        },
        alertWarning: function(message) {
            Swal.fire({
                animation: false,
                title: "Oops",
                text: message,
                icon: "warning"
            })
        },
        confirm: function(message, callback) {
            Swal.fire({
                animation: false,
                title: 'Are you sure?',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.value) {
                    if(callback) {
                        callback();
                    }
                }
            })
        },
        checkSession: function() {
            return axios.get(this.base_api + "/auth/check-session")
                .then(resp=>{
                    this.users_id = resp.data.data.id
                    this.users_name = resp.data.data.name
                    this.users_email = resp.data.data.email
                })
                .catch(err=>{
                    if(err.response.status === 401) {
                        location.href= backend_path + "/login"
                    } else {
                        alert('Something went wrong while checking session :(');
                        location.href= backend_path + "/login"
                    }
                })
        },
        async guard() {
            await this.checkSession()
            if (this.users_id === "") location.href = backend_path + "/login"
            return true;
        }
    }
})