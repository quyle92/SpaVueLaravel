<template>
	<div>
		 <Modal v-model="getDeleteObj.deleteConfirmationModal" width="360">
            <p slot="header" style="color:#f60;text-align:center">
                <Icon type="ios-information-circle"></Icon>
                <span>Delete confirmation</span>
            </p>
            <div style="text-align:center">
                <p>Will you delete it?</p>
            </div>
            <div slot="footer">
                <Button type="error" size="large" long :disabled="isAdding" :loading="isLoading" 
                 @click="delItem()">{{isAdding ? 'Deleting...' : 'Delete'}}</Button>
            </div>
        </Modal>
	</div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  props:['isAdding', 'isLoading'],
  data: function() {
    return {
 		 
 	  }
  },
  computed:{
     ...mapGetters([
        'getDeleteObj',
      ])
  },
  methods:{
    async delItem()
    {  
      this.$emit('modity-button-state');
      this.$store.commit('delItem');
      
      
    }
  }
}
</script>

<style scoped>

</style>
