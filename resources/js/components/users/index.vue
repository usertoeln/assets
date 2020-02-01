<template>
    <div class="container d-print-none">
        <search_component @on_filter="set_filter">
        </search_component>
        <data_table :information="filtered_items" :table_waiting="table_waiting"
                    @select_rows="control_change" :reset_selection="reset_selection">
        </data_table>
    </div>
</template>

<script>
    import axios from 'axios';
    import search_component from './search_component';
    import data_table from './data_table'

    export default {
        name: "index",
        /************************/
        data() {
            return {
                filter: [],
                filtered_items: [],
                table_waiting: false,
                information: [],
                can_change: false,
                change_items: [],
                reset_selection: false,
            }
        },
        /************************/
        props: [],
        /************************/
        components: {search_component, data_table},
        /************************/
        computed: {},
        /************************/
        methods: {
            /*****************/
            search() {
                this.filtered_items = this.information;


                if (this.filter.mobile) {
                    let re = new RegExp(this.filter.mobile + ".*", 'gi');
                    this.filtered_items = this.filtered_items.filter(item => item.mobile.match(re));
                }

                if (this.filter.country) {
                    let re = new RegExp(this.filter.country + ".*", 'gi');
                    this.filtered_items = this.filtered_items.filter(item => item.country.match(re));
                }


                if (this.filter.from_date)  /*2020-03-01   test_date=2020-02-02 12:00:00 */
                    this.filtered_items = this.filtered_items.filter(item => item.created_at >= this.filter.from_date + ' ' + '00:00:00');

                if (this.filter.to_date)  /*2020-03-01 test_date=2020-02-02 12:00:00 */
                    this.filtered_items = this.filtered_items.filter(item => item.created_at <= this.filter.to_date + ' ' + '24:00:00');

                console.log(this.filtered_items, this.filter);
                // this.update_table_caption();
            },
            /************************/
            makeToast(message = 'IFI message', variant = 'primary') {
                this.$bvToast.toast(message, {
                    title: 'IFI message status',
                    autoHideDelay: 5000,
                    variant: variant,
                    solid: true,
                })
            },
            /*****************/
            control_change(val) {
                this.change_items = val;
                this.can_change = val.item_length;
            },
            /*****************/
            set_filter(val) {
                this.filter = val;
                this.search();
            }
            /*****************/
        },
        /************************/
        created: function () {
            this.table_waiting=true;
            axios({
                url: '/api/get_users',
                data: null,
                method: 'get'
            }).then(res => {
                console.log('get_users' , res);
                this.information = res.data;
                this.filtered_items = res.data;
                this.makeToast(res.data.length + ' Data load successfully', 'success');
                this.table_waiting=false;
            }).catch(res => {
                console.log('get_users' , res);
                this.makeToast('Data loading failed', 'danger');
                this.table_waiting=false;
            });
        },
        /************************/
    }
</script>

<style scoped>

</style>