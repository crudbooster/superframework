const template = `
    <div class='card'>
        <div class='card-header'>{module} list &nbsp; <button type="button" class="btn btn-sm btn-primary" @click="$router.push('/{permalink}/add')">Add New Data</button></div>
        <div class='card-body table-responsive'>                      
           <table id="{permalink}-table" class="table table-striped table-bordered">
                <thead>
                    <tr>{thead_columns}<th>Action</th></tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
`;

export default {
    name: "{permalink}",
    template: template,
    beforeCreate() {
        this.$parent.guard()
    },
    mounted() {
        let d = this

        d.$parent.showLoading()

        $('#{permalink}-table').DataTable( {
            serverSide: true,
            ajax: {
                url: base_api + "/{permalink}/list",
                error: function(jqXHR, exception) {
                    d.$parent.alertWarning(exception)
                },
                data: function(param) {
                    // Additional DataTable Params
                },
                initComplete: function(settings,json) {
                    d.$parent.hideLoading()
                },
                statusCode: {
                    403: function() {
                        d.$parent.forbiddenAlert()
                        location.href = backend_path + "/logout"
                    }
                }
            },
            order: [[ 0, "desc" ]],
            columns: {json_columns},
            columnDefs: [{
                targets: {action_target_idx},
                render: function(data, type, full, meta){
                    if(type === 'display'){
                        data = '<div align="right">' +
                            '<a class="btn btn-sm btn-success datatable-btn-edit" title="Edit data" data-id="'+full.{primary_key}+'" href="javascript:;">Edit</a> ' +
                            '<a class="btn btn-sm btn-danger datatable-btn-delete" title="Delete data" data-id="'+full.{primary_key}+'" href="javascript:;">Delete</a> ' +
                            '</div>';
                    }

                    return data;
                }
            }]
        });

        let router = this.$router
        $(document).on('click','.datatable-btn-edit', function() {
            let id = $(this).data('id')
            router.push('/{permalink}/edit/'+id)
        })

        $(document).on('click','.datatable-btn-delete',function() {
            let id = $(this).data('id')
            d.delete(id)
        })
    },
    data() {
        return {

        }
    },
    methods: {
        delete: function(id) {
            this.$parent.confirm("Are you sure want to delete?", () => {
                axios.post(base_api + "/{permalink}/delete",form_data({id:id}))
                    .then(resp=>{
                        this.$parent.alertSuccess(resp.data.message)
                        this.$router.push('/{permalink}')
                        $('#{permalink}-table').DataTable().ajax.reload();
                    })
                    .catch(err=>{
                        this.$parent.alertWarning(err.response.data.message)
                    })
            })
        }
    }
}
