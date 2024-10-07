<template>
	<div class="grid" style="height: 60px;margin:0">
		<div class="col-6">
			<div class="flex" @click="goBack">
				<img :src="'/public/'+ branch_logo" style="width: 40px;object-fit: contain;" />
				<span style="font-size: .7rem;">{{ branch_name }}</span>
			</div>
		</div>
		<div class="col-6 p-0" style="position: relative;">
			<div class="boxtriangle" style=""></div>
			<div class="topright" style="width: 100%;">
				<div align="center" style="width: 100%;">
					<div>ROOM</div>
					<div>{{ room }}</div>
				</div>
			</div>
		</div>
	</div>
</template>
<!-- Headorder -->
<script setup>
    import { useRouter } from 'vue-router'
    const router = useRouter()
	const room = localStorage.getItem('room')
	const branch = localStorage.getItem('branch')
    const branch_name = localStorage.getItem('branch_name')
    const branch_logo = localStorage.getItem('branch_logo')
    
    const goBack = ()=>{
        router.go(-1);
    }
</script>