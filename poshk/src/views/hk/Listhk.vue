<template>
	<div style="padding-bottom:80px;">
		<div class="mt-2 mb-2" align="center" style="font-weight: bold;font-size: .9rem;">Housekeeping Service</div>
		<div class="produk">
			<div class="items" v-for="itms in 20">
				<div class="itm-box">
					<div align="center">
						<span style="font-size: .7rem;">AAA</span>
						<img src="/public/noimage.jpg" style="object-fit: contain;height: 80px;width: 100%;" />
						<div class="flex align-items-center">
							<div class="flex-1 pl-2 pr-2">
								<div style="border: 1px solid #ccc;border-radius: 100%;width: 18px;height: 18px;display: flex;align-items: center;justify-content: center;">
									-
								</div>
							</div>
							<div>
								<input readonly style="border: 1px solid #ccc;padding: 2px;width: 100%;text-align: center;" />
							</div>
							<div class="flex-1 pl-2 pr-2">
								<div style="border: 1px solid #ccc;border-radius: 100%;width: 18px;height: 18px;display: flex;align-items: center;justify-content: center;">
									+
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div style="position: fixed;bottom: 60px;z-index: 2;width: 100%;" align="center">
		<Button @click="goToOrder" severity="danger" class="p-button-sm" label="Order" />
	</div>
</template>
<script setup>
    import { useRouter } from 'vue-router'

    const router = useRouter() 
    const goToOrder = ()=>{
        router.push({name:'Orderhousekeeping'})
    }
    const goBack = ()=>{
        router.go(-1);
    }
    const footerFeed = (id)=>{
    	console.log('footer click', id)
    }
</script>