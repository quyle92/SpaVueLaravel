<template>
	<div>
		<div class="content">
			<div class="container-fluid">
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
					<p class="_title0">Users <Button  @click="addUserModal=true"><Icon type="md-add" />Add admin</Button></p>

					<div class="_overflow _table_div">
						<table class="_table">
								<!-- TABLE TITLE -->
							<tr>
								<th>ID</th>
								<th>fullName</th>
								<th>Email</th>
								<th>User type</th>
								<th>Action</th>
							</tr>
								<!-- TABLE TITLE -->


								<!-- ITEMS -->
							<tr v-for="(user, index) in users" :key="index">
								<td>{{user.id}}</td>
								<td >{{user.fullName}}</td>
								<td >{{user.email}}</td>
								<td >{{user.role_id}}</td>
								<td>
									 <Button type="info" size="small" @click="showEditModal(user)">Edit</Button>
									 <Button type="error" size="small" @click="deleteModal(user,index)">Del</Button>
								</td>
							</tr>
								<!-- ITEMS -->

						</table>
					</div>
				</div>

				<!-- user adding modal -->
				<Modal
			        v-model="addUserModal"
			        title="Add admin"
			        :mask-closable="false"
				>	
				  <div class="space">
			        <Input v-model="data.fullName" placeholder="fullName"></Input>
			    </div>
			    <div class="space">
			        <Input v-model="data.email" placeholder="Email"></Input>
			    </div>
				  <div class="space">
			        <Input v-model="data.password" placeholder="Password"></Input>
			    </div>
			    <div class="space" >
			        <Select style="width:200px" v-model="data.role_id">
				       <Option v-for="(role, index) in roles" :key="index" :value="role.role_id">{{ role.role_name }}</Option>
				        <!-- <Option value="editor">editor</Option> -->				        
				    </Select>
			    </div>
			       

			       	<div slot="footer">
				    	<Button type="default" @click="addUserModal=false">Close</Button>
				    	<Button type="primary" @click="addUser()" :disabled="isAdding" :loading="isLoading">{{isAdding ? 'Adding...' : 'Add user'}}</Button>
			   		</div>
			    </Modal>

			    <!-- user editing modal -->
				<Modal
			        v-model="editUserModal"
			        title="Edit admin"
			        :mask-closable="false"
				>	
				<div class="space">
			        <Input v-model="selectedUser.fullName" placeholder="fullName"></Input>
			    </div>
			    <div class="space">
			        <Input v-model="selectedUser.email" placeholder="Email"></Input>
			    </div>
			    <div class="space">
			        <Select style="width:200px" v-model="selectedUser.role_id">
				        <Option v-for="(role, index) in roles" :key="index" :value="role.role_id">{{ role.role_name }}</Option>
				    </Select>
			    </div>
		       	<div slot="footer">
				    	<Button type="default" @click="editUserModal=false">Close</Button>
				    	<Button type="primary" @click="updateUser()" :disabled="isAdding" :loading="isLoading">{{isAdding ? 'Adding...' : 'Add user'}}</Button>
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
     			fullName: '',
     			email: '',
     			password:'',
     			role_id:''
     		},
     		addUserModal: false,
     		editUserModal: false,
     		selectedUser: '',
     		isAdding: false,
     		isLoading: false,
     		users: [],
     		editData:'',
        roles:[
          {
            role_id: 1,
            role_name: 'admin',
          },
          {
            role_id: 2,
            role_name: 'editor',
          },
          {
            role_id: 3,
            role_name: 'moderator',
          },
          {
            role_id: 4,
            role_name: 'user',
          },
        ]
 	  }
 	 
  },
  methods:{
  	async addUser(){
  		this.isAdding = this.isLoading = true;
  		let count = 0;
  		for(let x in this.data)
  		{	
  			if( this.data[x].trim() == '' ){
  				this.error(  x + ' is missing!')
  			}
  			count++;
  			if(count === Object.keys(this.data).length)
  				new Promise( e => {
  					setTimeout(() =>{this.isAdding = this.isLoading = false;}, 300)
  				})
  		}
		let response = await this.callApi('post', 'app/user/write', this.data)
  		if( response.status == 200)
  		{	
  			this.success('User added ok!');
  			this.users.push(response.data.user);
  			//empty form field after creating
  			Object.keys(this.data).forEach(key => this.data[key]='');
  			
  		} else
  		{	
  			if( response.status == 422  ){
  				for(let x in response.data.errors)
  					this.error( response.data.errors[x] );
  			}
  			else{
  				this.swr();
  			}
  			
  		}

  	},
  	showEditModal(user){
  		this.editUserModal = true;
  		// this.editData.userfullName = user.userfullName;
  		// this.editData.id = user.id;(1a)
  		this.selectedUser = {...user};//(1b)
  	},
  	async updateUser(){
  		this.isAdding = this.isLoading = true;
  		let count = 0;
  		for(let x in this.selectedUser)
  		{	
  			if( this.selectedUser[x] !==  null )
  			{
  				if( this.selectedUser[x].length < 1 ){
  					this.error(  x + ' is missing!')
	  			}
	  			
  			}
  			count++;
  			if(count === Object.keys(this.selectedUser).length){
  				new Promise( e => {
  					setTimeout(() =>{this.isAdding = this.isLoading = false;}, 300)
  				});
  			}
  		}

  		
  		let response = await this.callApi('post', 'app/user/update', this.selectedUser);
  		if( response.status == 200)
  		{	
  			this.success('user edited ok!');
  			this.isAdding = false;
  			this.isLoading = false;
  			this.editModal = false;
  			this.users.map( (user) => {
				if(user.id == this.selectedUser.id){
					let selectedUser = {...this.selectedUser};//(2)
					Object.keys(user).forEach(function(key) {
					     user[key] = selectedUser[key];
					})
				}
				return user;
  			});
  			//empty form field after edting
			Object.keys(this.data).forEach(key => this.data[key]='');
  		} else
  		{	
  			if( response.status == 422  ){
  				for(let x in response.data.errors)
  					this.error( response.data.errors[x] );
  			}
  			else{
  				this.swr();
  			}
  			
  		}

  	},
  	deleteModal(user,index){
  		let obj = {
        item: user,
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
            let response = await this.callApi('post', 'app/user/delete', deleteObj.deletedItem);

            if( response.status == 200)
            { 
                setTimeout( () => {
                  this.success('user deleted ok!');
                  this.isAdding = ! this.isAdding;
                  this.isLoading = ! this.isLoading;
                  this.$store.commit('hideDelModal');
                
                  this.users.splice(deleteObj.deletedIndex, 1);
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
  	let response = await this.callApi('get', 'app/user/get');

  	if(response.status == 200)
  	{	
  		this.users = response.data;
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
(1a): viết như thế này sẽ làm editData độc lập với users( immutable)
(1b): cũng sẽ làm cho  editData độc lập với users( immutable) nhưng cú pháp ES6 này ngắn gọn hơn
(2): phải gắn lại biến như vậy, ko thì sẽ ko lấy dc value của selectedUser khi ở trong vòng lặp forEach ở dưới. ref: https://stackoverflow.com/a/33041634/11297747
-->