const template = `
    <div class="row">
        <div class="col-sm-3"></div>
        <div class="col-sm-6">
            <div class="card">
                <div class="card-header">
                    Login
                </div>
                <form id="form-login">
                <div class="card-body">
                    <div class="form-group">
                        <label>Email address</label>
                        <input type="email" v-model="email" required class="form-control" name="email">
                        <small id="emailHelp" class="form-text text-muted">Enter your registered email address at our system.</small>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" v-model="password" required class="form-control" name="password">
                    </div>
                    
                    <p><small class="text-muted">If you forgot the password <router-link to="/auth/forgot">Click here</router-link></small></p>
                    
                    <button type="button" @click="submitLogin" class="btn btn-block btn-primary">Login</button>
                </div>
                </form>
            </div>
        </div>
        <div class="col-sm-3"></div>
    </div>
`;

export default {
    name: "login",
    template: template,
    data() {
        return {
            password: "",
            email: ""
        }
    },
    created() {
        $('#form-login').validate()
    },
    methods: {
        submitLogin: function() {
            if( $('#form-login').valid() ) {
                this.$parent.is_loading = true
                var formData = new FormData($('#form-login')[0]);
                axios.post(this.$parent.base_api + "/auth/login", formData)
                    .then(async(resp)=>{
                        await this.$parent.checkSession()
                        this.$router.push('/dashboard')
                    })
                    .catch(err=>{
                        this.$parent.alertWarning(err.response.data.message)
                    })
                    .finally(()=>{
                        this.$parent.is_loading = false
                    })
            }
        }
    }
}
