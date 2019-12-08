const template = `
    <div class="row">
        <div class="col-sm-3"></div>
        <div class="col-sm-6">
            <div class="card">
                <div class="card-header">
                    Profile
                </div>
                <div class="card-body">
                <form id="form-profile" action="" method="post">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" v-model="name" required class="form-control" name="name">                        
                    </div>
                    
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" v-model="email" required class="form-control" name="email">                        
                    </div>
                    
                    <div class="form-group">
                        <label>Password</label>
                        <input type="text" v-model="password" placeholder="Please leave empty if not change" class="form-control" name="password">                        
                    </div>
                    
                    <button type="button" @click="submitForm" class="btn btn-block btn-primary">Save Profile</button>
                </form>
                </div>
            </div>
        </div>
        <div class="col-sm-3"></div>
    </div>
`;

export default {
    name: "profile",
    template: template,
    async beforeCreate() {
        await this.$parent.guard()
        this.name = this.$parent.users_name
        this.email = this.$parent.users_email
    },
    created() {
        $('#form-profile').validate()
    },
    data() {
        return {
            name: "",
            email: "",
            password: ""
        }
    },
    methods: {
        submitForm: function() {
            if( $('#form-profile').valid() ) {
                this.$parent.is_loading = true
                var formData = new FormData( $("#form-profile")[0] )
                formData.append("id", this.$parent.users_id)
                axios.post(this.$parent.base_api + "/users/update-profile", formData)
                    .then(resp=>{
                        this.$parent.alertSuccess(resp.data.message)
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
