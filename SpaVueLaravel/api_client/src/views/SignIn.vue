<template>
  	<form @submit.prevent="submit">
  		{{form}}
  		<div>
  			<label for="email"> Email </label>
  			<input type="text" name="email" id="email" v-model="form.email">
  		</div>

  		<div>
  			<label for="password"> Password </label>
  			<input type="password" name="email" id="password" v-model="form.password">
  		</div>

  		<div>
  			<button type="submit" class="btn btn-default">Sign in</button>
  		</div>
  	</form>
</template>

<script>
import { mapActions } from 'vuex'
export default {
  name: 'signin',
  components: {

  },
  data(){
  	return {
  		form:{
  			email: '',
  			password: ''
  		}
  	}
  },
  methods:{
    ...mapActions({
      signIn: 'auth/signIn'
    }),
  	 submit() {
  		this.signIn(this.form).then( () => {
        this.$router.replace({
          name: 'dashboard'
        });
      }).catch( e => {
          console.log(e);
        });
  	}
  }
}
</script>
