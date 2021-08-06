<template>
	<div>
		<div class="content">
			<div class="container-fluid">
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
					<p class="_title0">Tags <Button  @click="addModal=true"><Icon type="md-add" />Add tag</Button></p>

					<div class="_overflow _table_div">
						<table class="_table">
								<!-- TABLE TITLE -->
							<tr>
								<th>ID</th>
								<th>Tag</th>
								<th>Created at</th>
								<th>Action</th>
							</tr>
								<!-- TABLE TITLE -->


								<!-- ITEMS -->
							<tr v-for="(tag, index) in tags" :key="index">
								<td>{{tag.id}}</td>
								<td class="_table_name">{{tag.tagName}}</td>
								<td>{{tag.created_at}}</td>
								<td>
									 <Button type="info" size="small" @click="showEditModal(tag)">Edit</Button>
									 <Button type="error" size="small" @click="deleteModal(tag,index)">Del</Button>
								</td>
							</tr>
								<!-- ITEMS -->

						</table>
					</div>
				</div>
        <Page :total="total" :page-size='pageSize' :current="currentPage" @on-change="changePaginatedPage"  />
				<!-- tag adding modal -->
				<Modal
			        v-model="addModal"
			        title="Add tag"
			        :mask-closable="false"
				>
			        <Input v-model="data.tagName" placeholder="Enter something...">{{data.tagName}}</Input>

			       	<div slot="footer">
				    	<Button type="default" @click="addModal=false">Close</Button>
				    	<Button type="primary" @click="addTag()" :disabled="isAdding" :loading="isLoading">{{isAdding ? 'Adding...' : 'Add tag'}}</Button>
			   		</div>
			  </Modal>

			    <!-- tag editing modal -->
				<Modal
			        v-model="editModal"
			        title="Edit tag"
			        :mask-closable="false"
				>
			        <Input v-model="editData.tagName" placeholder="Enter something...">{{editData.tagName}}</Input>

			       	<div slot="footer">
				    	<Button type="default" @click="editModal=false">Close</Button>
				    	<Button type="primary" @click="editTag()" :disabled="isAdding" :loading="isLoading">{{isAdding ? 'Editing...' : 'Edit tag'}}</Button>
			   		</div>
			  </Modal>

			    <!-- deleting modal -->			
        <delete-modal v-bind:isAdding='isAdding' :isLoading='isLoading' @modity-button-state="modifyButtonState"></delete-modal>
			    
			</div>
		</div>
	</div>
</template>

<script>
import { mapGetters } from 'vuex'
import deleteModal from '../components/deleteModal'
export default {
  components: {
    'delete-modal': deleteModal,
  },
  props:{

  },
  data: function() {
    return {
 		data:{
 			tagName: '',
 		},
 		addModal: false,
 		editModal: false,
 		isAdding: false,
 		isLoading: false,
 		tags: [],
 		editData:'',
    total: 0,
    pageSize: 2,
    currentPage: 1, 
 	  }
 	 
  },
  methods:{
  	async addTag(){
  		if(this.data.tagName.trim() == '') return this.error('Tag name is required!');
  		this.isAdding = true;
  		this.isLoading = true;
  		let response = await this.callApi('post', 'app/tags/write', this.data, {
                  validateStatus: function (status) {console.log(status);
                    return status < 500; // Resolve only if the status code is less than 500
                  }
               });
  		if( response.status == 200)
  		{	
  			this.success('Tag added ok!');
  			
  			this.data.tagName = ''
  			this.tags.push(response.data.tag);
  		} else
  		{	
        if( response.status !== 500 ){
           this.error(response.data.errors);
        }
        else{
          this.swr();
        }
  			
  		}

      return new Promise( e => {
        setTimeout( () => {
          this.isAdding = false;
          this.isLoading = false;
        },1000)
      });

  	},
  	showEditModal(tag){
  		this.editModal = true;
  		// this.editData.tagName = tag.tagName;
  		// this.editData.id = tag.id;(1a)
  		this.editData = {...tag};//(1b)
  	},
  	async editTag(tagName){
  		if(this.editData.tagName.trim() == '') return this.error('Tag name is required!');
  		this.isAdding = true;
  		this.isLoading = true;
  		let response = await this.callApi('post', 'app/tags/update', this.editData)
  		if( response.status == 200)
  		{	
  			this.success('Tag edited ok!');
  			this.isAdding = false;
  			this.isLoading = false;
  			this.editModal = false;
  			this.tags.map( (tag) => {
  				if(tag.id == this.editData.id){
  					tag.tagName = this.editData.tagName;
  				}
  				return tag;
  			});
  			this.editData.tagName = ''
  			this.editData.id = ''
  		} else
  		{	
  			if( response.status !== 500 ){
  				 this.error(response.data.errors);
  			}
  			else{
  				this.swr();
  			}
  			
  		}
      
      return new Promise( e => {
        setTimeout( () => {
          this.isAdding = false;
          this.isLoading = false;
        },1000)
      });

  	},
  	deleteModal(tag,index){
  		let obj = {
        item: tag,
        index: index
      }
      this.$store.commit('deleteModal', obj);
  	},
    modifyButtonState(){
      this.isAdding = ! this.isAdding;
      this.isLoading = ! this.isLoading;
      this.$store.commit('hideDelModal');
    },
    async changePaginatedPage( page){
        let config = {
          params: {
            pageSize: 3
          },
        }
        let response = await this.callApi('get', 'app/tags/get?page=' + page);

        if(response.status == 200)
        { 
          this.tags = response.data.data;
          this.currentPage = response.data.current_page;
          this.pageSize = response.data.per_page;
          this.total = response.data.total;
        }
        else
        {
          console.log(response.data);
        }
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
            let params = {
              item_id: deleteObj.deletedItem.id,
              current_page: this.currentPage
            }
            let response = await this.callApi('post', 'app/tags/delete', params);

            if( response.status == 200)
            { 
                setTimeout( () => {
                  this.success('Tag deleted ok!');
                  this.isAdding = ! this.isAdding;
                  this.isLoading = ! this.isLoading;
                  this.$store.commit('hideDelModal');
                
                  this.tags.splice(deleteObj.deletedIndex, 1);

                  //render pagination
                  this.tags = response.data.data;
                  this.currentPage = response.data.current_page;
                  this.pageSize = response.data.per_page;
                  this.total = response.data.total;
                  
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
    let config = {
      params: {
        pageSize: 3
      },
    }
  	let response = await this.callApi('get', 'app/tags/get',config);

  	if(response.status == 200)
  	{	
  		this.tags = response.data.data;
      this.currentPage = response.data.current_page;
      this.pageSize = response.data.per_page;
      this.total = response.data.total;
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
-->