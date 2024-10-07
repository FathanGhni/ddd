<template>
	<Teleport to="body">
		<div v-if="isBlocked" class="poploading" style="" :style="'z-index:'+zIndex">
			<div style="text-align:center;position:relative;display: flex;justify-content: center;align-items: center;height: 100%;flex-direction: column;">
				<div style="text-align:center;position:relative;display: flex;justify-content: center;align-items: center;flex-direction: column;">
					<ProgressSpinner :strokeWidth="4" />
					<div class="titleloading">LOADING</div>
				</div>
				<Button v-if="abort" label="Abort Proccess" @click="btnAbort" class="p-button-outlined p-button-danger p-mt-2" />
			</div>
		</div>
	</Teleport>
</template>
<style lang="css">
	.poploading{
		position: fixed;
		height: 100%;
		top: 0;
		left: 0;
		width: 100%;
		background-color: #48484833;
		color: #000;
		padding-left: 10%;
	}
	.titleloading{
		position:absolute;
		font-size: 1.4rem;
		font-weight: bold;
		color: #fff;
		text-shadow: 2px 2px #594C4C;
	}
	
</style>
<script>
import Button from 'primevue/button';
import ProgressSpinner from 'primevue/progressspinner';
export default {
	name: 'Loadingform',
	components:{
		ProgressSpinner,
		Button,
	},
	props:{
		isBlocked:{
			type:Boolean,
			default:false
		},
		abort:{
			type:Boolean,
			default:false
		},
		zIndex:{
			type:Number,
			default:90000000
		},
	},
	data(){
		return {
		}
	},
	created(){

	},
	methods:{
		btnAbort(){
			console.log('click abort')
			this.$emit('abort',true);
		},
	},
	mounted(){
	},
	watch:{

	}
}
</script>