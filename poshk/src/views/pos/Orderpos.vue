<template>
	<div class="pl-2 pr-2">
		<div align="center" class="mb-3">Room Service History</div>

		<div class="mb-2" v-for="tm in 15">
			<div class="flex" style="background: #aab7ce6b;border-radius: 5px;overflow: hidden;"> 
				<img src="/public/noimage.jpg" style="object-fit: contain;width: 83px;" />
				<div class="flex-1 p-2" style="font-size: .8rem;">
					<div class="flex align-items-center">
						<div class="flex-1">
							<div>Nama Barang</div>
							<div>Rp. 2.000</div>
						</div>
						<i class="pi pi-trash"></i>
					</div>
					<div class="flex align-items-center pt-2">
						<div class="flex align-items-center flex-1">
							<div style="border: 1px solid #ccc;border-radius: 10px;padding:3px 5px;" class="flex align-items-center justify-content-center">
								-
							</div>
							<div style="width: 50px;" class="flex align-items-center justify-content-center">1</div>
							<div style="border: 1px solid #ccc;border-radius: 10px;padding:3px 5px;" class="flex align-items-center justify-content-center">
								+
							</div>
						</div>
						<div>
							<i class="pi pi-comment"></i>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div style="min-height: 135px;"></div>
	</div>
	<div style="position: fixed;bottom: 56px;z-index: 2;width: 100%;background: #fff;" class="p-2" >
		<div style="font-size: 1.2rem;">Total : 2.000.000</div>
		<div class="flex align-items-center">
			<div class="flex-1 flex align-items-center" style="font-size: .7rem;">
				<InputSwitch v-model="checked" />
				<div class="pl-2">Charges to Room</div>
			</div>
			<Button @click="goToOrder" severity="danger" class="p-button-sm" label="Confirm" />
		</div>
	</div>
</template>

<script setup>
    import { useRoute, useRouter} from 'vue-router'
	import { onMounted, inject, ref } from 'vue';
    import InputSwitch from 'primevue/inputswitch';

    const checked = ref(true)
</script>