<template>
    <div class="page-content">
        <img src="/public/bgweb2.png" style="object-fit: revert-layer;width: 60%;height: 80px;position: absolute;top: 0;left: 0;z-index: 0;">
        <div class="grid" style="position: relative;z-index: 2;padding-top: 10px;">
            <div class="col-6" style="font-size: 1.6rem;color: #fff;">
                <span class="pl-3" style="cursor:pointer;" @click="btnLogout">
                    <-
                </span>
            </div>
            <div class="col-6" align="center">
                <img :src="'/public/'+branch_logo" style="width: 50px;object-fit: contain;" />
                <div style="font-size: .7rem;font-weight: bold;">{{ branch_name }}</div>
            </div>
        </div>
        <div class="mt-2" style="display: flex;justify-content: center;align-items: center;flex-direction: column;height:80vh;">
            <div>
                <Button @click="goToMenu()" label="Room Service" style="width: 200px;" />
            </div>
            <div class="mt-3 mb-3">
                <Button @click="goToHk()" label="House Keeping" style="width: 200px;" />
            </div>
            <div>
                <Button @click="goToOther()" label="Other Service" style="width: 200px;" />
            </div>
        </div>
        <img src="/public/bgleft2.png" style="object-fit: contain;position: absolute;bottom: -30px;left: -30px;z-index: 0;width: 30%;">
    </div>
</template>
<script setup>
    import { useRoute, useRouter } from 'vue-router'
    import { onMounted } from 'vue'
    const router = useRouter()
    const route = useRoute()
    const branch = localStorage.getItem('branch')
    const branch_name = localStorage.getItem('branch_name')
    const branch_logo = localStorage.getItem('branch_logo')

    onMounted(()=>{
    })
    const goToHk = ()=>{
        router.push({name:'Housekeeping'})
    }
    const goToMenu = ()=>{
        router.push({name:'Posmenu'})
    }
    const goToOther = ()=>{
        router.push({name:'Otherindex'})
    }
    const btnLogout = ()=>{
        router.replace({name:'Login'})
    }
    const goBack = ()=>{
        router.go(-1);
    }
</script>