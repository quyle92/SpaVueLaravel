import axios from 'axios'

export default {
  namespaced: true,
  state: {
  	token: null,
  	user: null
  },
  getters:{
  	authenticated(state) {
  		return ( state.token && state.user ) ? true : false;//(1)
  	},
  	user(state) {
  		return ( state.token && state.user ) ? state.user : '';
  	}
  },
  mutations: {
  	SET_TOKEN (state, token) {
  		state.token = token
  	},
  	SET_USER (state, user) {
  		state.user = user
  	},

  },
  actions: {
  	async signIn ( {dispatch}, credentials )
  	{
  		let  response = await axios.post('auth/signin', credentials);
  		return dispatch('attempt', response.data.token);
  	},
  	async attempt ( {commit, state}, token )
  	{

      if( token ) {
        commit('SET_TOKEN', token);
      }
      if(!state.token) return;

  		try{
  			let response = await axios.get('auth/me');
  			commit('SET_USER', response.data)
  		}
  		catch(e){
        // catch xảy ra khi token not specified/expired or fake
  			commit('SET_TOKEN', null)
  			commit('SET_USER', null)
  		}
  	},
    signOut( {commit} )
    {
        return axios.get('auth/signout').then( (e)=>{console.log(e.data);
          commit('SET_TOKEN', null)
          commit('SET_USER', null)
        }).catch( (e) => {
          console.log('Lỗi: ' + e);
        });
   
    }
  }
}

/*
Notes
 */
//(1) phải set cả  state.token && state.user vì biết đâu state.token kia là hàng giả thì sao. Nếu hàng giả thì state.user sẽ null vì api call to server ko trả về kqua.