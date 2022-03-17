<template>
    <div class="container">
        <div class="row mt-3">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Team</h3>

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
                      <th>Position</th>
                      <th>Modify</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="item in team.data" :key="item.id">
                      <td>{{item.first_name}} {{item.last_name}}</td>
                      <td>{{item.position}}</td>
                        <td>                                   
                            <a href="#" @click="editModal(item)">
                                <i class="fa fa-edit blue"></i>
                            </a>
                            /
                            <a href="#" @click="deleteTeam(item.id)">
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
                    <h5 class="modal-title" v-show="!editmode">Create New Team Member</h5>
                    <h5 class="modal-title" v-show="editmode">Edit Team Member</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form @submit.prevent="editmode ? editTeam() : createTeam()">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>First Name</label>
                            <input v-model="form.first_name" type="text" name="first_name"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('first_name') }">
                                <div v-if="form.errors.has('first_name')" v-html="form.errors.get('first_name')" /> 
                        </div>  
                        <div class="form-group">
                            <label>Last Name</label>
                            <input v-model="form.last_name" type="text" name="last_name"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('last_name') }">
                                <div v-if="form.errors.has('last_name')" v-html="form.errors.get('last_name')" /> 
                        </div> 
                        <div class="form-group">
                        <label>Position</label>  
                                <select v-model="form.position" id="position"
                                name="position" class="form-control">
                                <option value="">Select Position</option>
                                <option value="chairman">Chairman</option>
                                <option value="sec/gen">Secretary General</option>
                                <option value="treasurer">Treasurer</option>
                                </select>
                                <div v-if="form.errors.has('position')" v-html="form.errors.get('position')" />
                        </div>
                        <div class="form-group">
                            <label for="image">Feature Image</label>
                            <div class="col-sm-12">
                            <input type="file" @change="uploadImage" name="image" class="form-input">
                            </div>  
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
                first_name: '',
                last_name: '',
                position: ''
                }),
                team: {}
            }
        },
        methods: {
                        uploadImage(element){
            // console.log('Uploading')
            let file = element.target.files[0];
            let reader = new FileReader();
            console.log(file)
            if(file['size'] < 711177){
            reader.onloadend = (file) => {
              // console.log('RESULT',reader.result)
            this.form.image = reader.result
            }
            reader.readAsDataURL(file);    
            }else{
            Swal.fire({
              icon: 'error',
              title: 'Large file...',
              text: 'Please upload a smaller file'
            })  
            }  
            }, 
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
            createTeam(){
                this.$Progress.start();
                this.form.post('api/team').then(() => {
                    $('#addNew').modal('hide')
                    this.$Progress.finish();
                    toast.fire(
                        'Successful',
                        'Team member has been created',
                        'success'
                      );                    
                    console.log('success')
                    Fire.$emit('Refresh')
                }).catch(() => {
                    console.log('error')
                })
            },
            editTeam(){
                this.$Progress.start();
                this.form.put('api/team/'+ this.form.id).then(() => {
                    $('#addNew').modal('hide')
                    this.$Progress.finish();
                    toast.fire(
                        'Successful',
                        'Team member has been updated',
                        'success'
                      );                    
                    console.log('success')
                    Fire.$emit('Refresh')
                }).catch(() => {
                    console.log('error')
                })
            },
            deleteTeam(id){
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
                  this.form.delete('api/team/'+id).then(() => {
                  this.$Progress.finish();
                  toast.fire(
                    'Deleted!',
                    'Team member has been deleted.',
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
        loadTeam(){
            axios.get('api/team').then(({data}) => {
                this.team = data
            })
        }
        },
        mounted() {
            this.loadTeam();
            Fire.$on('Refresh',()=>{
                this.loadTeam();
            });            
            console.log('Component mounted.')
        }
    }
</script>
