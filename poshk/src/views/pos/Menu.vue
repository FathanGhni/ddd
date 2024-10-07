<template>
    <div style="padding-bottom:80px;">
        <div class="mt-2 mb-2" align="center" style="font-weight: bold;font-size: .9rem;">Room Service</div>
        <div class="produk">
            <div class="items" v-for="(itms, key) in listdata" :key="key">
                <div class="itm-box">
                    <div align="center">
                        <label style="font-size: .7rem;" class="">{{ itms.posmenu_name }}</label>
                        <img v-if="itms.posmenu_picture" :src="$host +'/uploads/images/menu_pos/'+itms.posmenu_picture" style="object-fit: contain;height: 80px;width: 100%;" />
                        <img v-else src="/public/noimage.jpg" style="object-fit: contain;height: 80px;width: 100%;" />
                        <div style="font-size: .75rem;" class="pb-2">
                            Rp. {{ formatRp(Math.round(itms.pricesell)) }}
                        </div>
                        <div class="flex align-items-center">
                            <div class="flex-1 pl-2 pr-2">
                                <div @click="reduceQty(itms)" icon="pi pi-minus-circle" style="background-color: #00bce7;color: #fff;border: 1px solid rgb(153 153 153);border-radius: 100%;width: 18px;height: 18px;display: flex;align-items: center;justify-content: center;" >
                                <!-- <div> -->
                                    <!-- <Button 
                                        @click="reduceQty(itms)"
                                        icon="pi pi-minus-circle"
                                        class="p-button-info"
                                        style="padding: 1px;"
                                        /> -->
                                        <i class="pi pi-minus" style="font-size: 10px;"></i>
                                </div>
                            </div>
                            <div>
                                <input class="form-control" v-model="itms.quantity"  readonly style="border: 1px solid rgb(153 153 153);padding: 2px;width: 100%;text-align: center;" />
                                <!-- <InputNumber
                                    :min="1"
                                    v-model="itms.quantity" 
                                    style="width: 100%;"
                                    /> -->
                            </div>
                            <div class="flex-1 pl-2 pr-2">
                                <div @click="increaseQty(itms)" style="background-color: #00bce7;color: #fff; border: 1px solid rgb(153 153 153);border-radius: 100%;width: 18px;height: 18px;display: flex;align-items: center;justify-content: center;" >
                                <!-- <div>
                                    <Button
                                        @click="increaseQty(itms)"
                                        icon="pi pi-plus-circle"
                                        class="p-button-info"
                                        style="padding: 1px;"
                                        /> -->
                                        <i class="pi pi-plus" style="font-size: 10px;"></i>
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
    <Toast position="bottom-right" group="br" />
	<Loadingform :isBlocked="isLoadingform" />
</template>
<script>
    import { useRouter } from 'vue-router'

    const router = useRouter()

    // const goToOrder = ()=>{
    //     router.push({name:'Orderpos'})
    // }

    export default {
        components: {

        },
        data() {
            return {
                isLoadingform: false,
                listdata: [],
                quantity: 0,
                listOrders: [],
            }
        },
        mounted() {
            // alert(localStorage.getItem('token'))
            this.getData();
        },
        methods: {
            increaseQty(item){
                item.quantity++;

                let data = {
                    menuid: item.posmenu_id,
                    quantity: item.quantity,
                };
                if(this.listOrders.filter((i)=>i.menuid == item.posmenu_id).length < 1){
                    this.listOrders.push(data);
                } else {
                    let i = this.listOrders.map((i)=>i.menuid).indexOf(item.posmenu_id);
                    this.listOrders[i].quantity+= 1;
                }
                console.log(this.listOrders)
            },
            reduceQty(item){
                item.quantity--
                if(item.quantity<=0) item.quantity=1;

                if(this.listOrders.filter((i)=>i.menuid == item.posmenu_id).length < 1){
                    this.listOrders.push(data);
                } else {
                    let i = this.listOrders.map((i)=>i.menuid).indexOf(item.posmenu_id);
                    this.listOrders[i].quantity-= 1;
                }
                console.log(this.listOrders)
            },
            addToCart() {
                this.$router.push({name:'Orderpos'});

            },
            goToOrder() {
                if(this.listOrders.length > 0) {
                    var posting={
                        outlet: 11,
                        foguest: null,
                        freeguest: 'amalia',
                        pax: 1,
                        foroom: 0,
                        listOrders: this.listOrders 
                    };
                    this.isLoadingform = true;
                    this.$http.post(this.$apiserver+'/pos/apps/trans/orderTemp', posting,{
                        // headers: {Authorization: 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIzIiwianRpIjoiYzgxOWRhOGJiOWY3NzFlMjk0MTZhNDZiM2VhMGUyYTA1YjA4NGVkMDhmN2JlZDc4M2U1ZTE0YWYwOGExYzY2ODkwNTM1Yjg1MzdjODRlNmMiLCJpYXQiOjE3MjU1MDM0ODUsIm5iZiI6MTcyNTUwMzQ4NSwiZXhwIjoxNzU3MDM5NDg0LCJzdWIiOiIzIiwic2NvcGVzIjpbXX0.jBO27hmhTQckBLftS-GZIb6_V2Cy1HVfHImbsIliClcejFNXvltfkAHQYVsVHrkY4qwh5sab0pGBNl8jJ2e988PE88JmfK31NOO2FZ0Bo6zZAmUjVKOD6QELX8m228IJKPe88t9iRgWoqtULDZkPnR9m3pU7A2Uii5e-Cxis3sQbh0GLOgdm2mdTDlzgYNN6zX6hVESmQ0MY7Q3KRE3knmOtgPgZ6uwpBtqCqo-GitKxkkkb73Md8cok1n3vzXXdN00N3LZ7csYqw9HKNLn8bqoBPAqWIqtQFhO1W8O46yp3I5ikGHnJYqGKSpLTQjv9ufIL0uynmMietPPVzRdoNYh4QvYyi0ySLyQS0sZZ4I_zHDxlXfQB5DLudq6ac2MA2lKAasjw40q3m4N2kS7PdDbry6Zo9a_HS09JT-5yr58cvC1T3ijaY0nAr_TPjJmhoGo7O_MJhFLnIwbZkCkaaf7JKbjLSrLSYQo_d1bvmNs9dEFYLmHuzD77N0b3sIeNQtJjTiz7EHG_siBZlaOPqhOMBRWRN-EMLx1pEWZUmknfShOdNxLK1BEa6OTQWe73I3DyzKiki43Hw_XR5D5tu2b6fX2yGzFpvrwLiUQfstgU1joRrlkrucc6IycN4U_IaJ_g-5DIug6dvcaqD5NWX-TK-f7P8o035vap_ADxzZI'}
                        // headers: {Authorization: 'Bearer '+localStorage.getItem('token')}
                    })
                    .then((response)=> {
                        if(response.data.status == true){
                            this.$toast.add({ severity: 'success', summary: 'Notification', detail: 'Your Order Successfull', group: 'br', life: 3000 });
                            setTimeout(()=> {
                                this.$router.push('/home');
                            }, 1000);
                            this.resetorder();
                        }
                        else{
                            if(response.data.message!==null){
                                this.$toast.add({ severity: 'warn', summary: 'Notification', detail: 'Your Order Failed', group: 'br', life: 3000 });
                                this.isLoadingform = false;
                            }
                        }
                        this.isLoadingform = false;
                    })
                    .catch((e)=> {
                        this.$toast.add({ severity: 'warn', summary: 'Notification', detail: 'Your Order Failed', group: 'br', life: 3000 });
                        this.isLoadingform = false;
                    });
                    // this.$toast.add({ severity: 'success', summary: 'Notification', detail: 'Your Order Successfull', group: 'br', life: 3000 });
                } else {
                    this.$toast.add({ severity: 'warn', summary: 'Notification', detail: 'Please select menu first', group: 'br', life: 3000 });

                }
                

            },
            getData() {
                // this.$http.post(this.$apiserver+'/poin-of-sales/apps/comp/data').then((response) => {
                //     console.log(response.data)
                // })
                
                var posting={
                    outlet: 11
                };
                this.isLoadingform = true;
                this.$http.post(this.$apiserver+'/pos/apps/comp/data', posting,{
                //    headers: {Authorization: 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIzIiwianRpIjoiYzgxOWRhOGJiOWY3NzFlMjk0MTZhNDZiM2VhMGUyYTA1YjA4NGVkMDhmN2JlZDc4M2U1ZTE0YWYwOGExYzY2ODkwNTM1Yjg1MzdjODRlNmMiLCJpYXQiOjE3MjU1MDM0ODUsIm5iZiI6MTcyNTUwMzQ4NSwiZXhwIjoxNzU3MDM5NDg0LCJzdWIiOiIzIiwic2NvcGVzIjpbXX0.jBO27hmhTQckBLftS-GZIb6_V2Cy1HVfHImbsIliClcejFNXvltfkAHQYVsVHrkY4qwh5sab0pGBNl8jJ2e988PE88JmfK31NOO2FZ0Bo6zZAmUjVKOD6QELX8m228IJKPe88t9iRgWoqtULDZkPnR9m3pU7A2Uii5e-Cxis3sQbh0GLOgdm2mdTDlzgYNN6zX6hVESmQ0MY7Q3KRE3knmOtgPgZ6uwpBtqCqo-GitKxkkkb73Md8cok1n3vzXXdN00N3LZ7csYqw9HKNLn8bqoBPAqWIqtQFhO1W8O46yp3I5ikGHnJYqGKSpLTQjv9ufIL0uynmMietPPVzRdoNYh4QvYyi0ySLyQS0sZZ4I_zHDxlXfQB5DLudq6ac2MA2lKAasjw40q3m4N2kS7PdDbry6Zo9a_HS09JT-5yr58cvC1T3ijaY0nAr_TPjJmhoGo7O_MJhFLnIwbZkCkaaf7JKbjLSrLSYQo_d1bvmNs9dEFYLmHuzD77N0b3sIeNQtJjTiz7EHG_siBZlaOPqhOMBRWRN-EMLx1pEWZUmknfShOdNxLK1BEa6OTQWe73I3DyzKiki43Hw_XR5D5tu2b6fX2yGzFpvrwLiUQfstgU1joRrlkrucc6IycN4U_IaJ_g-5DIug6dvcaqD5NWX-TK-f7P8o035vap_ADxzZI'}
                    // headers: {Authorization: 'Bearer '+localStorage.getItem('token')}
                })
                .then((response)=> {
                    if(response.data.status){
                        this.listdata = response.data.result.menus;
                        this.$toast.add({severity: 'warn', summary: 'Data not found', detail: '#No result data.', life: 5000});
                    }
                    else{
                        if(response.data.message!==null){
                           // this.alertDialog('Warning', response.data.message);
                        }
                    }
                    this.isLoadingform = false;
                

                })
                .catch((e)=> {
                   // this.alertDialog('Warning', 'You dont have permission as an cashier');
                    this.isLoadingform = false;
                });
            },
            resetorder() {
                this.listOrders = [];
                this.quantity = 0;

            },
            showBottomRight() {
                this.$toast.add({ severity: 'success', summary: 'Success Message', detail: 'Message Content', group: 'br', life: 3000 });
            },
            alertDialog(header, message, acceptLabel, rejectLabel='No', data=null, openReject=false){
                if(header==null){
                    header='Confirmation';
                }
                if(acceptLabel==null){
                    acceptLabel='Ok';
                }
                return new Promise((resolve, reject) => {
                    this.$confirm.require({
                        message: message,
                        header: header,
                        icon: 'pi pi-exclamation-triangle',
                        rejectClass:(openReject==false?'noRejectBtn':''),
                        acceptLabel:acceptLabel,
                        rejectLabel:rejectLabel,
                        accept: () => {
                            resolve(data);
                        },
                        reject: () => {
                            reject(data);
                        }
                    });
                });
            },
            formatRp(x) {
                if(x==null) return '';
                return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            }
        }
    }
</script>
<style>
.doubleLine{
	display: -webkit-box;
	-webkit-line-clamp: 2;
	-webkit-box-orient: vertical;
	overflow: hidden;
	text-overflow: ellipsis;
}

.singleLine{
	text-overflow: ellipsis; 
	overflow: hidden; 
	white-space: nowrap;
	display: -webkit-box;
	-webkit-box-orient: vertical;
}
</style>