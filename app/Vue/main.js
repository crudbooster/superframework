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
        login: function() {
          this.$router.push('/auth/login')
        },
        logout: function() {
          this.is_loading = true
          axios.get(this.base_api + "/auth/logout")
              .then(resp=>{
                  this.users_id = ""
                  this.users_name = ""
                  this.users_email = ""
                  this.login()
              })
              .catch(err=>{
                  alert('Something went wrong!')
              })
              .finally(()=>{
                  this.is_loading = false
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
                        this.login()
                    } else {
                        alert('Something went wrong!');
                    }
                })
        },
        async guard() {
            await this.checkSession()
            if (this.users_id === "") this.login()
            return true;
        }
    }
})