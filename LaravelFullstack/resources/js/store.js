import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export default  new Vuex.Store({
	state:{
		DeleteObj:{
			deleteConfirmationModal: false,
		    deletedItem:'',
		    deletedIndex:0,
		    isDeleted: false,
		},
		user:false,
		blogInstance:{
			blog:'',
			tags:'',
			categories:''
		}
	},
	getters:
	{
		getDeleteObj(state){
			return state.DeleteObj;
		},
		getSelectedTags(state)
		{
			let { blog } = state.blogInstance;
			let selectedTags = [];
			blog.tags.forEach(function(e){
				selectedTags.push(e.id);
			});
			return selectedTags;
		
		},
		getSelectedCats(state)
		{
			let { blog } = state.blogInstance;
			let selectedCats = [];
			blog.categories.forEach(function(e){
				selectedCats.push(e.id);
			});
			return selectedCats;
		
		}
	},
	mutations:
	{
		deleteModal(state, payload)
		{
			state.DeleteObj.deleteConfirmationModal = true;
			state.DeleteObj.deletedItem = payload.item;
			state.DeleteObj.deletedIndex = payload.index;
		},

		delItem(state){
			state.DeleteObj.isDeleted = true;

		},
		hideDelModal(state){
			state.DeleteObj.isDeleted = false;
			state.DeleteObj.deleteConfirmationModal = false;
		}
	},
	actions:
	{

	}
});