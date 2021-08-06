<template>
	<div>
		<div class="content">
			<div class="container-fluid">
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
					<p class="_title0">Role management 
            <Select style="width:200px" v-model="data.role_id"  >
               <Option v-for="(role, index) in roles" :key="index" :value="role.role_id" >{{ role.role_name }}</Option>
                <!-- <Option value="editor">editor</Option> -->               
            </Select>
          </p>
					<div class="_overflow _table_div">
						<table class="_table">
								<!-- TABLE TITLE -->
							<tr>
								<th>Resource</th>
								<th>Read</th>
								<th>Write</th>
								<th>Update</th>
								<th>Del</th>
							</tr>
								<!-- TABLE TITLE -->
          

								<!-- ITEMS -->
							<tr v-for="(rs, index) in resources" :key="index">
								<td>{{rs.resourceName}}</td>
								<td ><Checkbox v-model='rs.read'></Checkbox></td>
								<td ><Checkbox v-model='rs.write'></Checkbox></td>
								<td ><Checkbox v-model='rs.update'></Checkbox></td>
                <td ><Checkbox v-model='rs.delete'></Checkbox></td>
							</tr>
								<!-- ITEMS -->

						</table>
					</div>
				</div>
        <Button type="primary" >Assign</Button>
				
			    
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
     			role_id:'',
     		},
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
        ],
        resources:[
          {resourceName: 'home', read: false, write: false, update: false, delete: false, path: '/'},
          {resourceName: 'tags', read: false, write: false, update: false, delete: false, path: 'tags'},
          {resourceName: 'blog', read: false, write: false, update: false, delete: false, path: 'blog'},
          {resourceName: 'category', read: false, write: false, update: false, delete: false, path: 'category'},
          {resourceName: 'admin-users', read: false, write: false, update: false, delete: false, path: 'admin-users'},
          {resourceName: 'role', read: false, write: false, update: false, delete: false, path: 'role'},
          {resourceName: 'asign-role', read: false, write: false, update: false, delete: false, path: 'asign-role'},
        ],
        resourcesAssigned:'',
 	  }
 	 
  },
  methods:{
  	

  },
  watch: {
      data: {
        deep: true,
        async handler (obj) {
            // giữ lại resources cũ
            let resources = [...this.resources];
            resources.map( (rs) => {
              for(let x in rs){
                if( rs[x] === true ){
                  rs[x] = false;
                }
              } 
              return rs;
            });

            //lọc permission/resources theo role
            this.resourcesAssigned.filter( (rs) => {
                if( obj.role_id === rs.id && rs.permission !== null ){
                  return this.resources = JSON.parse( rs.permission );
                }
                else if( obj.role_id === rs.id && rs.permission === null ){
                  return this.resources = resources;
                }
                     
            })

            //for(let)
     
        }
        
      },
      resources: {
          deep:true,
          async handler(obj) {console.log(obj);
            let permission = JSON.stringify(this.resources);
            let role_id = this.data.role_id;
            if( permission.length < 1 ) return this.error('permission is missing');
            if( role_id.length < 1 ) return this.error('role_id is missing');
            let data = {
              permission: permission,
              role_id: role_id
            }
            let response = await this.callApi('post', 'app/role/assignRole', data);
            // console.log(response.data.role.permission);
            if( response.status == 200)
            { 
              this.success('That\'s ok!');
              this.resourcesAssigned.forEach( (item, index, arr) => {
                  if( item.id === this.data.role_id )
                  {
                    return arr[index].permission = response.data.role.permission;
                  }
              });

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
          }
      }
  },
  async created(){
  	let response = await this.callApi('get', 'app/role/getRoleAssigned');

  	if(response.status == 200)
  	{	
  		this.resourcesAssigned = response.data;
      
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