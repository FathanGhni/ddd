<template>
	<div class="footer-bar" style="">
			<div @click="btnClick(1)">
				<i class="pi pi-home"></i>
			</div>
			<div @click="btnClick(2)">
				<i class="pi pi-search"></i>
			</div>
			<div @click="btnClick(3)">
				<i class="pi pi-list"></i>
			</div>
			<div @click="btnClick(4)">
				<i class="pi pi-user"></i>
			</div>
		</div>
</template>
<script setup>
    import { useRoute, useRouter } from 'vue-router'
    import { onMounted } from 'vue'
    const router = useRouter()
    const route = useRoute()
	const props = defineProps(['feedback', 'page'])
	onMounted(()=> {
		console.log('props', props.page)
	})
	const btnClick = (id)=>{
		if(id==1){
			router.replace({name:'Home'})
			return
		}
		var currentUrl = window.location.pathname;
		var page = parseInt(route.query?.page);
			console.log('btnClick', currentUrl);
		if(page){
			var nameOrder = null;
			if(page==1){
				nameOrder = 'Posmenu';
			}
			else if(page==2){
				nameOrder = 'Housekeeping';
			}
			else if(page==3){
				nameOrder = 'Otherindex';
			}
			if(id==2){
				router.push({name:nameOrder})
			}
			if(id==3){
				router.push({name:'History', query:{page:page}})
			}
			if(id==4){
				router.push({name:'Guest', query:{page:page}})
			}
		}

		/*
		if(id==2){
			if(props.page){
				if(parseInt(props.page)==1){
					router.replace({name:'Posmenu'})
				}
				else if(parseInt(props.page)==2){
					router.replace({name:'Housekeeping'})
				}
				else if(parseInt(props.page)==3){
					router.replace({name:'Otherindex'})
				}
			}
			return
		}
		if(id==3){
			router.replace({path:'/history'})
			return
		}
		if(id==4){
			router.replace({name:'Guest'})
			return
		}*/
		if (typeof props.feedback === 'function') {
			props.feedback(id);
		}
	}
</script>