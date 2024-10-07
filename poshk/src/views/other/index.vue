<template>
    <div class="page-content">
    	<Headorder />
		<RouterView  />
		<Footer v-bind:page="3" :feedback="(id)=> feedbacks(id)" />
	</div>
</template>
<script setup>
    import { useRoute, useRouter, RouterView } from 'vue-router'
    import Headorder from '@/components/Headorder.vue'
    import Footer from '@/components/Footer.vue'
    const router = useRouter();

    const feedbacks = (id)=>{
        console.log('geed', id);
        if(id==2){
            router.push({name:'Otherindex'})
        }
        else if(id==3){
            router.push({name:'History', query:{page:3}});
        }
        else if(id==4){
            router.push({name:'Guest', query:{page:3}});
        }
    }
</script>