<template>
    <div class="container-fluid my-3" style="">
        <div class="row">
            <div class="col-2">
                <venues @on_select_venue="get_venue_assets"/>
            </div>
            <div class="col-4">
                <venue_assets @on_asset_select="show_turn_over"
                              :venue_assets_table_waiting="venue_assets_table_waiting"
                              :venue_assets_data="venue_assets_data"/>
            </div>
            <div class="col-6">
                <asset_turn_over :selected_asset="selected_asset"/>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import venues from './venues'
    import venue_assets from './venue_assets'
    import asset_turn_over from './asset_turn_over';
    import bus from '../../event_bus';

    export default {
        name: "index",
        components: {venues, venue_assets, asset_turn_over},
        data() {
            return {
                venue_assets_data: [],
                selected_asset: {},
                venue_assets_table_waiting: false,
                // asset_turn_over_table_waiting: false,
            }
        },
        methods: {
            makeToast(message = 'IFI message', variant = 'primary') {
                this.$bvToast.toast(message, {
                    title: 'IFI message status',
                    autoHideDelay: 5000,
                    variant: variant,
                    solid: true,
                })
            },
            get_venue_assets(val) {
                this.venue_assets_table_waiting = true;
                axios({
                    url: '/api/ops/get_mojodi',
                    data: {venue_id: val},
                    method: 'post'
                }).then(res => {
                    // this.venue_assets_table_waiting = false;
                    this.venue_assets_table_waiting = false;
                    this.venue_assets_data = res.data;
                    this.makeToast(res.data.length + ' Warehouse inventory load successfully', 'success');
                }).catch(res => {
                    console.log('get_users', res);
                    this.makeToast('Warehouse inventory loading failed', 'danger');
                    this.venue_assets_table_waiting = false;
                });
            },
            show_turn_over(val) {
                this.selected_asset = val;
            },
        },
        created() {
            // bus.$on('on_change_venue', (data) => {
            // console.log(data);
            // });
        },
    }
</script>

<style scoped>

</style>