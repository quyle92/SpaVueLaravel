<template>
	<div>
		<div class="content">
			<div class="container-fluid">
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
          <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20 col-md-4">
          <div class="space">
               ID: <Input v-model="blog.id" >{{blog.id}}</Input>
          </div>
          <div class="space">
              Title: <Input  type="text" v-model="blog.title">{{blog.title}}</Input>
          </div>
          <div class="space">
              Blog: <Input  type="text" v-model="blog.post">{{blog.post}}</Input>
          </div>
          <div class="space">
            <Select style="width:200px" multiple v-model="selectedTags">
               <Option v-for="(tag, index) in tags" :key="index" :value="tag.id" >{{ tag.tagName }}
               </Option>
            </Select>
          </div>
          <div class="space">
            <Select style="width:200px" multiple v-model="selectedCats">
               <Option v-for="(cat, index) in categories" :key="index" :value="cat.id" >{{ cat.categoryName }}
               </Option>
            </Select>
          </div>
          <div class="login_footer">
            <Button type="primary" >Login</Button>
          </div>
        </div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  components: {
   
  },
  data: function() {
    return {
     		blog: {},
        tags: [],
        selectedTags:[],
        selectedCats:[],
        categories:[]
 	  }
 	},
  computed:{
     ...mapGetters([
        'getSelectedTags','getSelectedCats'
      ])
  },
  created(){
    this.blog = this.$store.state.blogInstance.blog;
    this.tags = this.$store.state.blogInstance.tags;
    this.categories = this.$store.state.blogInstance.categories;
    this.selectedTags = this.getSelectedTags;
    this.selectedCats = this.getSelectedCats;

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
-->