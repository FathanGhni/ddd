<template>
    <Headorder/>
    <div class="pl-2 pr-2">
        <div align="center" class="mb-3">Guest</div>
        <div class="mt-2">
            
        </div>
    </div>
    <Footer />
</template>
<script setup>
    import { useRoute, useRouter} from 'vue-router'
    import { onMounted, inject, ref } from 'vue';
    import Headorder from '../components/Headorder.vue'
    import Footer from '../components/Footer.vue'

</script>