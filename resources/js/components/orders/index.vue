<template>
    <div class="container-fluid mt-3">
        <div class="row">
            <div class="col-12 col-md-4">
                <venues :mojodi="mojodi" :mojodi_table_waiting="mojodi_table_waiting"/>
            </div>
            <div class="col-12 col-md-8 mt-3 mt-md-0">
                <orders/>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import venues from './venues';
    import orders from './orders';

    export default {
        name: "index",
        /***********************************/
        components: {venues, orders},
        /***********************************/
        data() {
            return {
                mojodi: [],
                mojodi_table_waiting: false,
            }
        },
        /***********************************/
        methods: {
            makeToast(message = 'IFI message', variant = 'primary') {
                this.$bvToast.toast(message, {
                    title: 'IFI message status',
                    autoHideDelay: 5000,
                    variant: variant,
                    solid: true,
                })
            },
            get_mojodi() {
                this.mojodi_table_waiting = true;
                axios({
                    url: '/api/ops/get_mojodi',
                    data: {venue_id: 'all'},
                    method: 'post'
                }).then(res => {
                    this.mojodi_table_waiting = false;
                    this.mojodi = res.data;
                    this.makeToast(res.data.length + ' Warehouse inventory load successfully', 'success');
                }).catch(res => {
                    console.log(res);
                    this.makeToast('Warehouse inventory loading failed', 'danger');
                    this.mojodi_table_waiting = false;
                    this.mojodi = [];
                });
            },
        },
        /***********************************/
        created() {
            this.get_mojodi();
        },
        /***********************************/
        /***********************************/
        /***********************************/
    }
</script>

<style scoped>

</style>