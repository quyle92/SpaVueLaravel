export default {
	data(){
		return {

		}
	},
	methods: {
		async callApi(method, url, data)
		{	
			try{
				return await axios({
				  method: method,
				  url: url,
				  data: data
				});
			}
			catch (e){
				return e.response;
			}
		},
		info (desc, title = 'Hey') {
	        this.$Notice.info({
	        	top: 80,
	            title: title,
	            desc: desc
	        });
	    },
	    success (desc, title = 'Great!') {
	        this.$Notice.success({
	        	top: 80,
	            title: title,
	            desc: desc
	        });
	    },
	    warning (desc, title = 'Oops!') {
	        this.$Notice.warning({
	        	top: 80,
	            title: title,
	            desc: desc
	        });
	    },
	    error (desc, title = 'Hey') {
	        this.$Notice.error({
	        	top: 80,
	            title: title,
	            desc: desc,
	        });
	        return new Promise( (resolve) => {
	        	setTimeout(() => {
				    resolve();
				 }, 2000);
	        });
	    },
	    swr (desc = 'Sth went wrong!Try again', title = 'Hey') {
	        this.$Notice.error({
	        	top: 80,
	            title: title,
	            desc: desc,
	        });
	        return new Promise( (resolve) => {
	        	setTimeout(() => {
				    resolve();
				 }, 300);
	        });
	    },

	}
}
