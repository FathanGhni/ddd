<template>
    <div class="bodylogin">
        <div class="head">
            <img src="/public/bgweb.png" style="z-index: 1;" />
        </div>
        <div class="content">
            <div class="mainc">
                <img src="/public/chs.png" class="logo" />
                <div style="margin-top: 40px;display: flex;flex-direction: column;justify-content: center;align-items: center;">
                    <span style="color: #204282;font-size: .82rem;">Input your Room Number</span>
                    <div class="inputlogin">
                        <img src="/public/icnlogin.png"  style="" />
                        <input v-model="roomno" v-on:keyup.enter="signin" placeholder="Room Number" />
                    </div>
                    <Button label="Sign In ->" @click="signin" severity="danger" class="p-button-sm" style="margin-top: 10px;" />
                    <div style="margin-top: 50px;color: #204282;font-size: .82rem;">
                        The booking information will be provided<br/>
                        by our reception staff when your check<br/>
                        in. if you forgot or haven't received it.<br/>
                        you can contact the reception staff for<br/>
                        details.
                    </div>
                </div>
            </div>
            <div class="footer" style="bottom:-10px;">
                <img src="/public/bgleft.png" style="object-fit: contain;width: 70%;" />
            </div>
        </div>
    </div>
    <Toast position="bottom-right" group="br" />
	<Loadingform :isBlocked="isLoadingform" />
</template>
<script>
	import { useRoute, useRouter } from 'vue-router'
    import { apiCall } from '@/lib/api.ts'
    import { onMounted } from 'vue'
	const router = useRouter()
	const route = useRoute()
    // onMounted(()=>{
    //     console.log('onMounted')
    //     apiCall('config', {app:route.query.role});
    // })
	// const gotoHome = ()=>{
	// 	router.push({name:'Home'})
	// }
    export default {
        components: {

        },
        data() {
            return {
                isLoadingform: false,
                roomno: null,
                branch: null
            }
        },
        mounted() {
            // apiCall('config', {app:route.query.role});
            if(this.$route.query.branch) {
                this.branch = this.$route.query.branch;
            }
        },
        methods: {
            signin() {
                if(!this.roomno){
                    this.$toast.add({ severity: 'warn', summary: 'Notification', detail: 'Room Number is Empty', group: 'br', life: 3000 });
					return;
				}
				var url = ''
				var postobj = null
					url = this.$apiserver+'/auth/apps/signin';
					postobj = {
						roomno: this.roomno,
                        branch: this.branch,
						remember_me: false
					}

					this.isLoadingform = true
					this.$http.post(url, postobj)
					.then((response)=> {
						if(response.data.result.user.id){
							if(response.data){
								if(response.data.result.user.id){
									localStorage.setItem('room', response.data.result.user.room);
									localStorage.setItem('branch', response.data.result.user.branch);
									localStorage.setItem('branch_logo', response.data.result.user.branch_logo);
									localStorage.setItem('branch_name', response.data.result.user.branch_name);
									localStorage.setItem('branch_address', response.data.result.user.branch_address);
                                    this.$router.push({name:'Home'});

									this.$store.commit('setAuthUser', response.data.result.user);
									this.$store.commit('setState', {param: 'defaultPass', value: Boolean(response.data?.result?.default_pass)});
									
									return;
								}
							}
						}else{
                            this.$toast.add({ severity: 'warn', summary: 'Notification', detail: 'You have to Reserve First', group: 'br', life: 3000 });
						}
						this.isLoadingform = false;
					})
					.catch((e)=> {
						console.log('error', e)
						if(e.response){
							if(e.response.data){
								if(e.response.data.message){
									this.txtLabel='error'
									this.txtMsg=e.response.data.message
								}
							}
						}
						this.isLoadingform = false;
					})
            }
        }
    }
</script>