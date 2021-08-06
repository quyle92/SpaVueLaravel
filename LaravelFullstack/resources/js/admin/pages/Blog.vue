<template>
	<div>
		<div class="content">
			<div class="container-fluid">
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
					<p class="_title0">blogs <Button  @click="addBlogModal=true"><Icon type="md-add" />Add blog</Button></p><!-- {{tags}}{{categories}} -->

					<div class="_overflow _table_div">
						<table class="_table">
								<!-- TABLE TITLE -->
							<tr>
								<th>ID</th>
								<th>title</th>
								<th>blog</th>
                <th>tags</th>
                <th>categories</th>
								<th>Action</th>
							</tr>
								<!-- TABLE TITLE -->


								<!-- ITEMS -->
							<tr v-for="(blog, index) in blogs" :key="index">
								<td>{{blog.id}}</td>
								<td>{{blog.title}}</td>
								<td>{{blog.post}}</td>
                <td><tag type="border" v-for="(tag, tagIndex) in blog.tags" :key="tagIndex">{{tag.tagName}}</tag></td>
                <td><tag type="border" v-for="(cat, catIndex) in blog.categories" :key="catIndex">{{cat.categoryName}}</tag></td>
								<td>
									 <Button type="info" size="small" @click="edit(blog.id, index)">Edits</Button><!--(3) -->
                   <Button type="error" size="small" @click="deleteModal(blog,index)">Del</Button>
								</td>
							</tr>
								<!-- ITEMS -->

						</table>
					</div>
				</div>

				<!-- blog adding modal -->
				<Modal
			        v-model="addBlogModal"
			        title="Add blog"
			        :mask-closable="false"
				>	
    				  <div class="space">
    			        <Input v-model="data.title" placeholder="title"></Input>
    			    </div>
    			    <div class="space">
    			        <Input v-model="data.post" placeholder="blog"></Input>
    			    </div>

              <Select style="width:200px" multiple v-model="data.category_id">
                <Option v-for="(cat, index) in categories" :key="index" :value="cat.id" >{{ cat.categoryName }}
                 </Option>
              </Select>

              <Select style="width:200px" multiple v-model="data.tag_id">{{tags}}
                <Option v-for="(tag, index) in tags" :key="index" :value="tag.id" >{{ tag.tagName }}
                 </Option>
              </Select>

			       	<div slot="footer">
  				    	<Button type="default" @click="addBlogModal=false">Close</Button>
  				    	<Button type="primary" @click="addBlog()" :disabled="isAdding" :loading="isLoading">{{isAdding ? 'Adding...' : 'Add blog'}}</Button>
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
import { bus } from '../../app'
export default {
  components: {
    'delete-modal': deleteModal,
  },
  props:{

  },
  data: function() {
    return {
     		data:{
          title: '',
          post: '',
          category_id:[],
          tag_id:[]
     		},
     		addBlogModal: false,
     		isAdding: false,
     		isLoading: false,
     		blogs: [],
        tags: [],
        categories:[]
 	  }
 	 
  },
  methods:{
  	async addBlog(){
  		this.isAdding = this.isLoading = true;


		let response = await this.callApi('post', 'app/blog/write', this.data);
  		if( response.status == 200)
  		{	
  			this.success('blog added ok!');
        if (response.data?.blog !== undefined){
  			   this.blogs.push(response.data.blog);
        }
  			
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

      return new Promise( e => {
        setTimeout( () => {
          this.isAdding = false;
          this.isLoading = false;
        },1000)
      });

  	},
    edit( blog_id, index )
    {
        this.$router.push({name: 'edit-blog', params: { blog_id: blog_id}});
        // bus.$emit('editBlog', this.blogs[index]);(4)
        this.$store.state.blogInstance.blog = this.blogs[index];
        this.$store.state.blogInstance.tags = this.tags;
        this.$store.state.blogInstance.categories = this.categories;
    },
  	deleteModal(blog,index){
  		let obj = {
        item: blog,
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
            let response = await this.callApi('post', 'app/blog/delete', deleteObj.deletedItem);

            if( response.status == 200)
            { 
                setTimeout( () => {
                  this.success('blog deleted ok!');
                  this.isAdding = ! this.isAdding;
                  this.isLoading = ! this.isLoading;
                  this.$store.commit('hideDelModal');
                
                  this.blogs.splice(deleteObj.deletedIndex, 1);
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
  	let response = await this.callApi('get', 'app/blog/get');
    if(response.status == 200)
    { 
      this.blogs = response.data;
    }
    else
    {
      console.log(response.data);
    }

    response = await this.callApi('get', 'app/category/get');

    if(response.status == 200)
    { 
      this.categories = response.data;
    }
    else
    {
      console.log(response.data);
    }

    response = await this.callApi('get', 'app/tags/get?foo=blog');

    if(response.status == 200)
    { 
      this.tags = response.data;
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
(1a): viết như thế này sẽ làm editData độc lập với blogs( immutable)
(1b): cũng sẽ làm cho  editData độc lập với blogs( immutable) nhưng cú pháp ES6 này ngắn gọn hơn
(2): phải gắn lại biến như vậy, ko thì sẽ ko lấy dc value của selectedblog khi ở trong vòng lặp forEach ở dưới. ref: https://stackoverflow.com/a/33041634/11297747
(3): vue-router params: https://stackoverflow.com/a/45298071/11297747
(4): ko dùng event bus dc vì  event is emitted on the bus before your EditBlog  is created/mounted. Bus chĩ dủng đc khi component 1 và component 2 đã đc mounted xong đồng thời.
Ref: https://stackoverflow.com/a/56806551/11297747
-->