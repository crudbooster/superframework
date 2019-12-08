const template = `
    <div class="row">
        <div class="col-sm-3"></div>
        <div class="col-sm-6">
            <div class="card">
                <div class="card-header">
                    Forgot Password
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Email address</label>
                        <input type="email" v-model="email" class="form-control" name="email">
                        <small id="emailHelp" class="form-text text-muted">Enter your registered email address at our system.</small>
                    </div>
                    <p><small class="text-muted">If you want to re-login <router-link to="/auth/login">Click here</router-link></small></p>
                    <button type="button" @click="submitForgot" class="btn btn-block btn-primary">Submit</button>
                </div>
            </div>
        </div>
        <div class="col-sm-3"></div>
    </div>
`;

export default {
    name: "forgot",
    template: template,
    data() {
        return {
            email: ""
        }
    },
    methods: {
        submitForgot: function() {
            alert("Clicked login "+this.password+" - "+this.email)
        }
    }
}
