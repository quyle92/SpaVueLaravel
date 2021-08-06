<template>
	<div >
		<div v-if="$store.state.user">
      <!--========== ADMIN SIDE MENU one ========-->
      <div class="_1side_menu" >
        <div class="_1side_menu_logo">
          <h3 style="text-align:center;">Logo Image</h3>
          <!--<img src="/img/logo.jpg" style="width: 108px;margin-left: 68px;"/>-->
        </div>

        <!--~~~~~~~~ MENU CONTENT ~~~~~~~~-->
        <div class="_1side_menu_content">
          <div class="_1side_menu_img_name">
            <!-- <img class="_1side_menu_img" src="/pic.png" alt="" title=""> -->
            <p class="_1side_menu_name">Admin</p>
          </div>

          <!--~~~ MENU LIST ~~~~~~-->
          <div class="_1side_menu_list">
            <ul class="_1side_menu_list_ul">
              <li v-for="route,index in allowedRoutes" :key="index">
                <router-link  :to="route.path"><Icon type="ios-speedometer" /> {{route.name}} </router-link>
              </li>
              <li><a href="/logout"><Icon type="ios-speedometer" /> Logout</a></li>
            </ul>
          </div>
        </div>
      </div>
      <!--========== ADMIN SIDE MENU ========-->

      <!--========= HEADER ==========-->
      <div class="header">
        <div class="_2menu _box_shadow">
          <div class="_2menu_logo">
            <ul class="open_button">
              <li>
                <Icon type="ios-list" />
              </li>
              <!--<li><Icon type="ios-albums" /></li>-->
            </ul>
          </div>
        </div>
      </div>
      <!--========= HEADER ==========-->
    </div>
		<router-view></router-view>
	</div>
</template>

<script>
export default {
  props:['user','permission'],
  data: function() {
    return {
 		 routes:[]
 	  }
  },
  created(){
      if( this.user !== false )
      { 
        this.$store.state.user = this.user;
        // console.log(this.permission);
        this.permission.forEach( (e) => {
          let {resourceName, read, path } = e;
          let obj = {
            name: resourceName,
            allowed:read,
            path: path
          }
          this.routes.push(obj);
        });
      }
  },
  computed:{
      allowedRoutes: function () {
        return this.routes.filter(route => route.allowed === true)
      }
  }
}
</script>

<style scoped>

</style>
