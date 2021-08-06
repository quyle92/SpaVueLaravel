<template>
  <div>
    <div class="content">
      <div class="container-fluid">
        <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
        <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
          <p class="_title0">Category <Button  @click="addModal=true"><Icon type="md-add" />Add category</Button></p>

          <div class="_overflow _table_div">
            <table class="_table">
                <!-- TABLE TITLE -->
              <tr>
                <th>ID</th>
                <th>Icon Image</th>
                <th>Category name</th>
                <th>Slug name</th>
                <th>Created at</th>
                <th>Action</th>
              </tr>
                <!-- TABLE TITLE -->


                <!-- ITEMS -->
              <tr v-for="(category, index) in categories" :key="index">
                <td>{{category.id}}</td>
                <td><img v-bind:src="/uploads/ + category.iconImage" width=50 height=50></td>
                <td >{{category.categoryName}}</td>
                <td >{{category.categorySlug}}</td>
                <td>{{category.created_at}}</td>
                <td>
                   <Button type="info" size="small" @click="showEditModal(category)">Edit</Button>
                   <Button type="error" size="small" @click="deleteModal(category,index)">Del</Button>
                </td>
              </tr>
                <!-- ITEMS -->

            </table>
          </div>
        </div>

        <!-- category adding modal -->
        <Modal
              v-model="addModal"
              title="Add category"
              :mask-closable="false"
        >
            <Upload
                multiple
                type="drag"
                :headers="{'x-csrf-token': token, 'X-Requested-With': 'XMLHttpRequest'}"
                :before-upload="handleBeforeUpload"
                :on-format-error="handleFormatError"
                :on-exceeded-size="handleMaxSize"
                action="/app/addCat">
                <div style="padding: 20px 0">
                    <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                    <p>Click or drag files here to upload</p>
                </div>
            </Upload>
              <div v-if="data.iconImage !== null">Upload file: {{ data.iconImage.name }} 
              </div>
              <Input v-model="data.categoryName" placeholder="Enter something...">{{data.categoryName}}</Input>

              <div slot="footer">
              <Button type="default" @click="addModal=false">Close</Button>
              <Button type="primary" @click="addCat()" :disabled="isAdding" :loading="isLoading">{{isAdding ? 'Adding...' : 'Add category'}}</Button>
            </div>
        </Modal>

          <!-- category editing modal -->
        <Modal
              v-model="editModal"
              title="Edit category"
              :mask-closable="false"
        >
            <Upload
                multiple
                type="drag"
                :headers="{'x-csrf-token': token, 'X-Requested-With': 'XMLHttpRequest'}"
                :before-upload="handleBeforeUpload_Edit"
                :on-format-error="handleFormatError"
                :on-exceeded-size="handleMaxSize"
                action="/app/addCat">
                <div style="padding: 20px 0">
                    <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                    <p>Click or drag files here to upload</p>
                </div>
            </Upload><!--(2)-->
              <div v-if="selectedCat.iconImage !== null">Upload file: {{  selectedCat.iconImage.name ? selectedCat.iconImage.name : selectedCat.iconImage  }} 
              </div>
              <Input v-model="selectedCat.categoryName" placeholder="Enter something...">{{selectedCat.categoryName}}</Input>

              <div slot="footer">
              <Button type="default" @click="editModal=false">Close</Button>
              <Button type="primary" @click="editCat()" :disabled="isAdding" :loading="isLoading">{{isAdding ? 'Adding...' : 'Add category'}}</Button>
            </div>
        </Modal>


          <!-- deleting modal -->     
        <delete-modal v-bind:isAdding='isAdding' :isLoading='isLoading' @modity-button-state="modifyButtonState"></delete-modal>
         
      </div>
    </div>
  </div>
</template>

<script>
import deleteModal from '../components/deleteModal'
import { mapState } from 'vuex'
import { mapGetters } from 'vuex'
export default {
  components: {
    'delete-modal': deleteModal,
  },
  data: function() {
    return {
    data:{
      iconImage: '',
      categoryName: '',
    },
    categories:[],
    selectedCat:{
      iconImage: '',
      categoryName: '',
    },
    addModal: false,
    editModal: false,
    isAdding: false,
    isLoading: false,
    editData:'',
    token:'',
    config: {
        headers: {
        'Content-Type': 'multipart/form-data'
        }
      }

    }
   
  },
  methods:{
    handleBeforeUpload (file) {
        console.log(file);
        this.data.iconImage = file;
        return false;
    },
    handleBeforeUpload_Edit (file) {
        console.log(file);
        this.selectedCat.iconImage = file;
        return false;
    },
    handleFormatError (file) {
        this.$Notice.warning({
            title: 'The file format is incorrect',
            desc: 'File format of ' + file.name + ' is incorrect, please select jpg or png.'
        });
    },
    handleMaxSize (file) {
        this.$Notice.warning({
            title: 'Exceeding file size limit',
            desc: 'File  ' + file.name + ' is too large, no more than 2M.'
        });
    },
    async addCat(){
      if(this.data.categoryName.trim() == '') return this.error('Category name is required!');
      this.isAdding = true;
      this.isLoading = true;
      let formData = new FormData();
       formData.append('iconImage', this.data.iconImage);
       formData.append('categoryName', this.data.categoryName);
      let response = await this.callApi('post', 'app/category/write', formData, this.config);
      if( response.status == 200)
      { 
        this.success('Category added ok!');
        this.isAdding = false;
        this.isLoading = false;
        this.addModal = false;
        this.data.iconImage = '';
        this.data.categoryName = '';
        this.categories.push(response.data.cat);
      } else
      { 
        if( response.status == 422 && response.data.errors.iconImage[0].length > 0 ){
          this.error(response.data.errors.iconImage[0]);
          this.isAdding = false;
          this.isLoading = false;
        }
        else{
          this.swr();
          this.isAdding = false;
          this.isLoading = false;
        }
        
      }

    },
    showEditModal(category){
      this.editModal = true;
      // this.editData.tagName = data.tagName;
      // this.editData.id = data.id;(1a)
      this.selectedCat = {...category};//(1b)
      console.log(this.selectedCat);
    },
    async editCat(){
      if(this.selectedCat.categoryName.trim() == '') return this.error('Category name is required!');
      this.isAdding = true;
      this.isLoading = true;
      let formData = new FormData();
       formData.append('iconImage', this.selectedCat.iconImage);
       formData.append('categoryName', this.selectedCat.categoryName);
       formData.append('id', this.selectedCat.id);
      let response = await this.callApi('post', 'app/category/update', formData)
      if( response.status == 200)
      {
        this.success('Category edited ok!');
        this.isAdding = false;
        this.isLoading = false;
        this.editModal = false;
        this.categories.map( (category) => {
          if(category.id == this.selectedCat.id){
            category.iconImage = this.selectedCat.iconImage;
            category.categoryName = this.selectedCat.categoryName;
          }
          return category;
        });
      } else
      { 
        if( response.status == 422 && response.data.errors.categoryName[0].length > 0 ){

          this.error(response.data.errors.categoryName[0]).then( () => {
            this.isAdding = false;
            this.isLoading = false;
          });
        }
        else{
          this.swr().then( () => {
            this.isAdding = false;
            this.isLoading = false;
          });;
        }
        
      }

    },
    deleteModal(category,index){
      let obj = {
        item: category,
        index: index
      }
      this.$store.commit('deleteModal', obj);
    },
    modifyButtonState(){
      this.isAdding = ! this.isAdding;
      this.isLoading = ! this.isLoading;
      this.$store.commit('hideDelModal');
    }
  },
  computed:{
    ...mapGetters([
      'getDeleteObj'
      ])
  },
  watch: {
      getDeleteObj: {
        deep: true,
        async handler (deleteObj) {
          if(deleteObj.isDeleted){
            let response = await this.callApi('post', 'app/category/delete', deleteObj.deletedItem);

            if( response.status == 200)
            { 
                setTimeout( () => {
                  this.success('Category deleted ok!');
                  this.modifyButtonState();
                
                  this.categories.splice(deleteObj.deletedIndex, 1);
                }, 1500);
            } else
            { 
                this.isAdding = ! this.isAdding;
                this.isLoading = ! this.isLoading;
                this.swr();
            }
            
          }
        }
        
      }
  },
  async created(){
    this.token = window.Laravel.csrfToken
    let response = await this.callApi('get', 'app/category/get');

    if(response.status == 200)
    { 
      this.categories = response.data;
    }
    else
    {
      console.log(response.data);
    }
  }
}
</script>

<style scoped>
  
</style>
<!-- 
Note:
(1a): viết như thế này sẽ làm editData độc lập với tags( immutable)
(1b): cũng sẽ làm cho  editData độc lập với tags( immutable) nhưng cú pháp ES6 này ngắn gọn hơn
(2): you have to include x-csrf-token and 'X-Requested-With': 'XMLHttpRequest'  header b/c 
a) this auto post submit on img upload so for security, any post request must be included csrf-token.<style scoped>
b) 'X-Requested-With': 'XMLHttpRequest' is to make sure that the the response is returned the json data
</style>
-->