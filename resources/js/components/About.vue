<template>
    <div class="container">
        <div class="row mt-3">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">About</h3>

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
                      <th>Tagline</th>
                      <th>Details</th>
                      <th>Modify</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="item in about.data" :key="item.id">
                      <td>{{item.tagline| truncate(30,'...')}}</td>
                      <td>{{item.details| truncate(30,'...')}}</td>
                        <td>                                   
                            <a href="#" @click="editModal(item)">
                                <i class="fa fa-edit blue"></i>
                            </a>
                            /
                            <a href="#" @click="deleteAbout(item.id)">
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
                    <h5 class="modal-title" v-show="!editmode">Create New About</h5>
                    <h5 class="modal-title" v-show="editmode">Edit About</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form @submit.prevent="editmode ? editAbout() : createAbout()">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Tagline</label>
                            <input v-model="form.tagline" type="text" name="tagline"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('tagline') }">
                                <div v-if="form.errors.has('tagline')" v-html="form.errors.get('tagline')" /> 
                        </div>  
                            <div class="form-group">
                            <label>Details</label>  
                                    <textarea v-model="form.details" id="details"
                                    name="details" class="form-control">
                                    </textarea>
                                    <div v-if="form.errors.has('details')" v-html="form.errors.get('details')" />
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
                tagline: '',
                details: '',
                image: ''
                }),
                about: {}
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
            createAbout(){
                this.$Progress.start();
                this.form.post('api/about').then(() => {
                    $('#addNew').modal('hide')
                    this.$Progress.finish();
                    toast.fire(
                        'Successful',
                        'About information has been added',
                        'success'
                      );                    
                    console.log('success')
                    Fire.$emit('Refresh')
                }).catch(() => {
                    console.log('error')
                })
            },
            editAbout(){
                this.$Progress.start();
                this.form.put('api/about/'+ this.form.id).then(() => {
                    $('#addNew').modal('hide')
                    this.$Progress.finish();
                    toast.fire(
                        'Successful',
                        'About information has been updated',
                        'success'
                      );                    
                    console.log('success')
                    Fire.$emit('Refresh')
                }).catch(() => {
                    console.log('error')
                })
            },
            deleteAbout(id){
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
                  this.form.delete('api/about/'+id).then(() => {
                  this.$Progress.finish();
                  toast.fire(
                    'Deleted!',
                    'About information has been deleted.',
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
        loadAbout(){
            axios.get('api/about').then(({data}) => {
                this.about = data
            })
        }
        },
        mounted() {
            this.loadAbout();
            Fire.$on('Refresh',()=>{
                this.loadAbout();
            });            
            console.log('Component mounted.')
        },
        filters: {
          truncate: function(text,length, suffix) {
            return text.substring(0,length) + suffix;
          }
        },
    }
</script>
