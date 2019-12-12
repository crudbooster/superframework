const template = `
    <div class='card'>
        <div class='card-header'><a href="javascript:;" @click="$router.push('/{permalink}')" title="Back To List" style="font-size: 20px">&laquo;</a> &nbsp; {{ form_title }}</div>
        <form id="{permalink}-form" method="post">
        <div class='card-body'>
           {form_group}          
           
           <p></p>
           <div class="row">
            <div class="col-sm-6">
                <button type="button" @click="$router.push('/{permalink}')" class="btn btn-outline-success">&laquo; Back To List</button>
                <button type="button" @click="submitForm" class="btn btn-success">Save {module}</button>
            </div>
            <div class="col-sm-6">
                <div align="right">                
                                
                </div>         
            </div>            
           </div>
           
        </div>
        </form>
    </div>
`;

export default {
    name: "{permalink}_form",
    template: template,
    beforeCreate() {
        this.$parent.guard()
    },
    mounted() {
        this.{primary_key} = this.$route.params.id
        if( this.{primary_key} ) {
            this.setFormTitle("Edit {module}")
            this.loadForm(this.{primary_key})
        } else {
            this.setFormTitle("Add {module}")
            this.clearForm()
            this.{primary_key} = ""
        }
        $('#{permalink}-form').validate()
        $('#{permalink}-form input[type=text]:first-child').focus()
    },
    data() {
        return {
            form_title: "",
            {data_return}
        }
    },
    methods: {
        setFormTitle: function(title) {
            this.form_title = title
        },
        clearForm: function() {
            {clear_form}
        },
        loadForm: function({primary_key}) {
            var data = new FormData();
            data.append("{primary_key}", {primary_key});
            axios.post(this.$parent.base_api + "/{permalink}/read", data)
                .then(resp=>{
                    {data_load_form}
                })
                .catch(err=>{
                    this.$parent.alertWarning(err.response.data.message)
                })
        },
        submitForm: function() {
            if( $('#{permalink}-form').valid() ) {
                this.$parent.is_loading = true
                var data = form_data({
                {data_submit_form}
                });
                if(!this.{primary_key}) {
                    axios.post(this.$parent.base_api + "/{permalink}/create", data)
                        .then(resp=>{
                            this.$parent.alertSuccess(resp.data.message)
                            this.clearForm()
                            this.$router.push("/{permalink}")
                        })
                        .catch(err=>{
                            this.$parent.alertWarning(err.response.data.message)
                        })
                        .finally(()=>{
                            this.$parent.is_loading = false
                        })
                } else {
                    axios.post(this.$parent.base_api + "/{permalink}/update", data)
                        .then(resp=>{
                            this.$parent.alertSuccess(resp.data.message)
                            this.$router.push("/{permalink}")
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
}
