<template>
  <div>
  	  <ul>
	  	<li>
	  		<router-link
	  			:to="{
	  				name: 'home'
	  			}"
	  		>
	  			Home
	  		</router-link>
	  	</li>

	  	<template v-if=" ! authenticated">
	  	<li>
	  		<router-link
	  			:to="{
	  				name: 'signin'
	  			}"
	  		>
	  			Sign in
	  		</router-link>
	  	</li>
	  	
	  	</template>
	  	<li v-if="user.name">
	  		{{user.name ? user.name : ''}}
	  	</li>
	  	<template v-if=" authenticated">
	  	<li>
	  		<router-link
	  			:to="{
	  				name: 'dashboard'
	  			}"
	  		>
	  			Dashboard
	  		</router-link>
	  	</li>
	  	<li v-if="authenticated" @click="SignOut()">
	  		<a href="#">
	  			Sign out
	  		</a>
	  	</li>
	  </template>
	  </ul>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import { mapActions } from 'vuex'
export default {
  name: 'TheNavigation',
  computed: {
  	...mapGetters({
  		authenticated: 'auth/authenticated',
  		user: 'auth/user'
  	})
  },
  methods: {
  	...mapActions({
  		SignOutAction: 'auth/signOut',
  	}),
  	SignOut(){
  		return this.SignOutAction().then( (e) => { console.log('then-nav: ' + e);
  			this.$router.replace({
  				name: 'home'
  			})
  		});
  	}
  }
}
</script>
