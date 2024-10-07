<template>
    <div class="page-content">
    	<Headorder />
		<RouterView  />
		<Footer v-bind:page="2" :feedback="(id)=> feedbacks(id)" />
	</div>
</template>
<script setup>
    import { useRouter, RouterView } from 'vue-router'
    import Headorder from '@/components/Headorder.vue'
    import Footer from '@/components/Footer.vue'
    const router = useRouter();

    const feedbacks = (id)=>{
        console.log('geed', id);
        if(id==2){
            router.push({name:'Housekeeping'})
        }
        else if(id==3){
            router.push({name:'History', query:{page:2}});
        }
        else if(id==4){
            router.push({name:'Guest', query:{page:2}});
        }
    }
</script>