<template>
  <div id="app">
  	<TheNavigation/>
    <router-view/>
  </div>
</template>

<script type="text/javascript">
import TheNavigation from '@/components/TheNavigation'

export default{
	components: {
		TheNavigation
	}
}
</script>


