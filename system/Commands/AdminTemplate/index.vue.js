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
        $('#{permalink}-table').dataTable( {
            serverSide: true,
            ajax: base_api + "/{permalink}/list",
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

        let d = this
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
