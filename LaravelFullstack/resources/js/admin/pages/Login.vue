<template>
	<div>
		<div class="container">
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20 col-md-4">
					<div class="space">
			        	<Input v-model='data.fullName' placeholder="fullName"></Input>
				  </div>
					<div class="space">
				        <Input v-model='data.password' placeholder="Password" type="text"></Input>
				  </div>
				  <div class="login_footer">
						<Button type="primary" @click="login">Login</Button>
				  </div>
				</div>
		</div>
	</div>
</template>

<script>
export default {
  props:{

  },
  data: function() {
    return {
 		data:{
 			fullName:'',
 			password: '',
 		},
 		isLogging: false
 	  }
  },
  methods:
  {
  	async login(){
  		if(this.data.fullName.length < 1 )  this.error('fullName is required!');
  		if(this.data.password.length < 1 )  this.error('password is required!');
  		let response = await this.callApi('post', 'loginProcess', this.data)
  		if( response.status == 200)
  		{	
  			this.success('Login ok!');
  			window.location.href = '/';
  		} else
  		{	
  			switch(response.status) {
          case 401:
            return this.error(response.data.msg);
            break;
          case 422:
            for( let x in response.data.errors )
              return this.error(response.data.errors[x][0]);
            break;
          default:
            this.swr();
        }
  		}
  	}
  }
}
</script>

<style scoped>
	._1adminOverveiw_table_recent{
		margin: 0 auto;
		margin-top: 220px;
	}
</style>
