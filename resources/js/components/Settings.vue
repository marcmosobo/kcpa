<template>
    <div class="container">
        <div class="row mt-3">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Amount Declarations</h3>

                <div class="card-tools" align="right">
                            <button type="button" class="btn btn-sm btn-primary" @click="newModal()">
                                <i class="fa fa-plus-square"></i>
                                Add New
                            </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                  <thead>
                    <tr>
                      <th>Name</th>
                      <th>Amount</th>
                      <th>Period</th>
                      <th>Modify</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="item in amountsettings.data" :key="item.id">
                      <td>{{item.name}}</td>
                      <td>{{item.amount}}</td>
                      <td>{{item.period }}</td>
                                <td>                                   
                                    <a href="#" @click="editModal(item)">
                                        <i class="fa fa-edit blue"></i>
                                    </a>
                                    /
                                    <a href="#" @click="deleteAmountSetting(item.id)">
                                        <i class="fa fa-trash red"></i>
                                    </a>
                                </td> 
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNew" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" v-show="!editmode">Create New Setting</h5>
                    <h5 class="modal-title" v-show="editmode">Edit Setting</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form @submit.prevent="editmode ? editAmountSetting() : createAmountSetting()">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Name</label>
                            <input v-model="form.name" type="text" name="name"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                                <div v-if="form.errors.has('name')" v-html="form.errors.get('name')" /> 
                        </div>  
                        <div class="form-group">
                            <label>Amount</label>
                            <input v-model="form.amount" type="text" name="amount"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('amount') }">
                                <div v-if="form.errors.has('amount')" v-html="form.errors.get('amount')" /> 
                        </div>
                        <div class="form-group">
                        <label>Period</label>  
                                <select v-model="form.period" id="period"
                                name="period" class="form-control">
                                <option value="">Select Period</option>
                                <option value="yearly">Yearly</option>
                                <option value="monthly">Monthly</option>
                                <option value="weekly">Weekly</option>
                                <option value="daily">Daily</option>
                                </select>
                                <div v-if="form.errors.has('period')" v-html="form.errors.get('period')" />
                        </div>                                                                                                                                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button v-show="editmode" type="submit" class="btn btn-success">Update</button>
                        <button v-show="!editmode" type="submit" class="btn btn-primary">Create</button>
                    </div>
                </form>
                </div>
            </div>
        </div>         
    </div>
</template>

<script>
    import axios from 'axios';
    export default {
        data () {
            return {
                editmode: false,
                form: new Form({
                id: '',    
                name: '',
                amount: '',
                period: ''
                }),
                amountsettings: {}
            }
        },
        methods: {
            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#addNew').modal('show');                

            },
            editModal(item){
                this.editmode = true;
                this.form.reset();
                $('#addNew').modal('show');
                this.form.fill(item)
            },
            createAmountSetting(){
                this.$Progress.start();
                this.form.post('api/amountsetting').then(() => {
                    $('#addNew').modal('hide')
                    this.$Progress.finish();
                    toast.fire(
                        'Successful',
                        'Amount declaration has been created',
                        'success'
                      );                    
                    console.log('success')
                    Fire.$emit('Refresh')
                }).catch(() => {
                    console.log('error')
                })
            },
            editAmountSetting(){
                this.$Progress.start();
                this.form.put('api/amountsetting/'+ this.form.id).then(() => {
                    $('#addNew').modal('hide')
                    this.$Progress.finish();
                    toast.fire(
                        'Successful',
                        'Amount declaration has been updated',
                        'success'
                      );                    
                    console.log('success')
                    Fire.$emit('Refresh')
                }).catch(() => {
                    console.log('error')
                })
            },
            deleteAmountSetting(id){
                this.$Progress.start();
                Swal.fire({
                  title: 'Are you sure?',
                  text: "You won't be able to revert this!",
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                  if (result.isConfirmed) { 
                  //send request to the server
                  this.form.delete('api/amountsetting/'+id).then(() => {
                  this.$Progress.finish();
                  toast.fire(
                    'Deleted!',
                    'Amount declaration has been deleted.',
                    'success'
                  )
                  Fire.$emit('Refresh');
                  }).catch(() => {
                  this.$Progress.fail();  
                    Swal.fire(
                    'Failed!',
                    'There was something wrong.',
                    'warning'
                  )
                  }); 
                  }else if(result.isDenied) {
                    console.log('cancelled')
                  }
                                   
                }) 
        },
        loadAmountSettings(){
            axios.get('api/amountsetting').then(({data}) => {
                this.amountsettings = data
            })
        }
        },
        mounted() {
            this.loadAmountSettings();
            Fire.$on('Refresh',()=>{
                this.loadAmountSettings();
            });            
            console.log('Component mounted.')
        }
    }
</script>
