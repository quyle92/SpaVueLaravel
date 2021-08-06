<template>
	<div>
		<div class="content">
			<div class="container-fluid">
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
					<p class="_title0">Role <Button  @click="addModal=true"><Icon type="md-add" />Add role</Button></p>

					<div class="_overflow _table_div">
						<table class="_table">
								<!-- TABLE TITLE -->
							<tr>
								<th>ID</th>
								<th>Role</th>
								<th>Created at</th>
								<th>Action</th>
							</tr>
								<!-- TABLE TITLE -->


								<!-- ITEMS -->
							<tr v-for="(role, index) in roles" :key="index">
								<td>{{role.id}}</td>
								<td class="_table_name">{{role.roleName}}</td>
								<td>{{role.created_at}}</td>
								<td>
									 <Button type="info" size="small" @click="showEditModal(role)">Edit</Button>
									 <Button type="error" size="small" @click="deleteModal(role,index)">Del</Button>
								</td>
							</tr>
								<!-- ITEMS -->

						</table>
					</div>
				</div>

				<!-- role adding modal -->
				<Modal
			        v-model="addModal"
			        title="Add role"
			        :mask-closable="false"
				>
			        <Input v-model="data.roleName" placeholder="Enter something...">{{data.roleName}}</Input>

			       	<div slot="footer">
				    	<Button type="default" @click="addModal=false">Close</Button>
				    	<Button type="primary" @click="add()" :disabled="isAdding" :loading="isLoading">{{isAdding ? 'Adding...' : 'Add role'}}</Button>
			   		</div>
			    </Modal>

			    <!-- role editing modal -->
				<Modal
			        v-model="editModal"
			        title="Edit role"
			        :mask-closable="false"
				>
			        <Input v-model="selectedRole.roleName" placeholder="Enter something...">{{selectedRole.roleName}}</Input>

			       	<div slot="footer">
				    	<Button type="default" @click="editModal=false">Close</Button>
				    	<Button type="primary" @click="update()" :disabled="isAdding" :loading="isLoading">{{isAdding ? 'Editing...' : 'Edit role'}}</Button>
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
 			roleName: '',
 			// permission: ''
 		},
 		addModal: false,
 		editModal: false,
 		isAdding: false,
 		isLoading: false,
 		roles: [],
 		selectedRole:'',


 	  }
 	 
  },
  methods:{
  	async add(){
  		if(this.data.roleName.trim() == '') return this.error('Role name is required!');
  		this.isAdding = true;
  		this.isLoading = true;

  		let response = await this.callApi('post', 'app/role/write', this.data)
  		if( response.status == 200)
  		{	
  			this.success('Role added ok!');
  			this.data.roleName = ''
  			this.roles.push(response.data.role);
  		} else
  		{	
  			if( response.status == 422 && response.data.errors.roleName[0].length > 0 ){
  				this.error(response.data.errors.roleName[0]);
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
  	showEditModal(role){
  		this.editModal = true;
  		// this.selectedRole.roleName = role.roleName;
  		// this.selectedRole.id = role.id;(1a)
  		this.selectedRole = {...role};//(1b)
  	},
  	async update(roleName){
  		if(this.selectedRole.roleName.trim() == '') return this.error('Role name is required!');
  		this.isAdding = true;
  		this.isLoading = true;
  		let response = await this.callApi('post', 'app/role/update', this.selectedRole)
  		if( response.status == 200)
  		{	
  			this.success('Role edited ok!');
  			this.isAdding = false;
  			this.isLoading = false;
  			this.editModal = false;
  			this.roles.map( (role) => {
  				if(role.id == this.selectedRole.id){
  					role.roleName = this.selectedRole.roleName;
  				}
  				return role;
  			});
  			this.selectedRole.roleName = ''
  			this.selectedRole.id = ''
  		} else
  		{	
  			if( response.status == 422 && response.data.errors.roleName[0].length > 0 ){
  				this.error(response.data.errors.roleName[0]).then( () => {
  					this.isAdding = false;
  					this.isLoading = false;
  				});
  			}
  			else{
  				this.swr();
  				this.isAdding = false;
  				this.isLoading = false;
  			}
  			
  		}

  	},
  	deleteModal(role,index){
  		let obj = {
        item: role,
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
            let response = await this.callApi('post', 'app/role/delete', deleteObj.deletedItem);

            if( response.status == 200)
            { 
                setTimeout( () => {
                  this.success('Role deleted ok!');
                  this.isAdding = ! this.isAdding;
                  this.isLoading = ! this.isLoading;
                  this.$store.commit('hideDelModal');
                
                  this.role.splice(deleteObj.deletedIndex, 1);
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
  	let response = await this.callApi('get', 'app/role/get');

  	if(response.status == 200)
  	{	
  		this.roles = response.data;
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
(1a): viết như thế này sẽ làm selectedRole độc lập với role( immutable)
(1b): cũng sẽ làm cho  selectedRole độc lập với role( immutable) nhưng cú pháp ES6 này ngắn gọn hơn
-->