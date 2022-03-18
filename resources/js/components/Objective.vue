<template>
    <div class="container">
        <div class="row mt-3">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Objectives</h3>

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
                      <th>Title</th>
                      <th>Content</th>
                      <th>Modify</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="item in objectives.data" :key="item.id">
                      <td>{{item.title}}</td>
                      <td>{{item.body}}</td>
                        <td>                                   
                            <a href="#" @click="editModal(item)">
                                <i class="fa fa-edit blue"></i>
                            </a>
                            /
                            <a href="#" @click="deleteObjective(item.id)">
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
                    <h5 class="modal-title" v-show="!editmode">Create New Objective</h5>
                    <h5 class="modal-title" v-show="editmode">Edit Objective</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form @submit.prevent="editmode ? editObjective() : createObjective()">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Title</label>
                            <input v-model="form.title" type="text" name="title"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('title') }">
                                <div v-if="form.errors.has('title')" v-html="form.errors.get('title')" /> 
                        </div>  
                            <div class="form-group">
                            <label>Content</label>  
                                    <textarea v-model="form.body" id="body"
                                    name="body" class="form-control">
                                    </textarea>
                                    <div v-if="form.errors.has('content')" v-html="form.errors.get('content')" />
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
                title: '',
                body: ''
            }),
                objectives: {}
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
            createObjective(){
                this.$Progress.start();
                this.form.post('api/objective').then(() => {
                    $('#addNew').modal('hide')
                    this.$Progress.finish();
                    toast.fire(
                        'Successful',
                        'Objective has been added',
                        'success'
                      );                    
                    console.log('success')
                    Fire.$emit('Refresh')
                }).catch(() => {
                    console.log('error')
                })
            },
            editObjective(){
                this.$Progress.start();
                this.form.put('api/objective/'+ this.form.id).then(() => {
                    $('#addNew').modal('hide')
                    this.$Progress.finish();
                    toast.fire(
                        'Successful',
                        'Objective has been updated',
                        'success'
                      );                    
                    console.log('success')
                    Fire.$emit('Refresh')
                }).catch(() => {
                    console.log('error')
                })
            },
            deleteObjective(id){
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
                  this.form.delete('api/objective/'+id).then(() => {
                  this.$Progress.finish();
                  toast.fire(
                    'Deleted!',
                    'Objective has been deleted.',
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
        loadObjectives(){
            axios.get('api/objective').then(({data}) => {
                this.objectives = data
            })
        }
        },
        mounted() {
            this.loadObjectives();
            Fire.$on('Refresh',()=>{
                this.loadObjectives();
            });            
            console.log('Component mounted.')
        },
        // filters: {
        //   truncate: function(text,length, suffix) {
        //     return text.substring(0,length) + suffix;
        //   }
        // },        
    }
</script>
