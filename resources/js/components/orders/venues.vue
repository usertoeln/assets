<template>
    <div class="card">
        <div class="card-header">
            <h5>Asset and Venue information</h5>
        </div>
        <div class="card-body">
            <div class="card-text">
                <div class="row justify-content-around">
                    <div class="col-12 col-md-4">
                        <input v-model="mojodi_filter.vname" type="text" placeholder="Venue" class="form-control ">
                    </div>
                    <div class="col-12 col-md-4">
                        <input v-model="mojodi_filter.asset_name" type="text" placeholder="asset" class="form-control">
                    </div>
                    <div class="col-12 col-md-4">
                        <input v-model="mojodi_filter.brand" type="text" placeholder="brand" class="form-control">
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12">
                        <b-table hover :items="filtered_items" class="bg-white rounded"
                                 sticky-header sticky-header="600px" bordered striped small
                                 :fields="mojodi_fields" foot-clone no-border-collapse
                                 head-variant="dark"
                                 @row-selected="onRowSelected"
                                 outlined
                                 :filter="filter"
                                 @filtered="onFiltered"
                                 :filterIncludedFields="filterOn"
                                 selectable select-mode="single" selected-variant="success"
                                 :busy="mojodi_table_waiting">
                            <!--******* Busy template ***************-->
                            <template v-slot:table-busy>
                                <div class="text-center text-danger my-2">
                                    <b-spinner class="align-middle"></b-spinner>
                                    <strong>Loading...</strong>
                                </div>
                            </template>
                        </b-table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import bus from '../../event_bus';

    export default {
        /************************/
        name: "venues",
        /************************/
        props: ['mojodi', 'mojodi_table_waiting'],
        /************************/
        data() {
            return {
                mojodi_fields: [
                    {key: 'vname', sortable: true, label: 'vname'},
                    {key: 'asset_name', sortable: true, label: 'asset_name'},
                    {key: 'brand', sortable: true, label: 'brand'},
                    {key: 'color', sortable: true, label: 'color'},
                    {key: 'asset_count', sortable: true, label: 'asset_count'},
                ],
                filter: null,
                filterOn: [],
                totalRows: 0,
                filtered_items: this.mojodi,
                mojodi_filter: {
                    asset_name: '',
                    brand: '',
                    vname: '',
                },
            }
        },
        /************************/
        methods: {
            makeToast(message = 'IFI message', variant = 'primary') {
                this.$bvToast.toast(message, {
                    title: 'IFI message status',
                    autoHideDelay: 5000,
                    variant: variant,
                    solid: true,
                })
            },
            onRowSelected(item) {
                bus.$emit('on_venue_select', item);
            },
            onFiltered(filteredItems) {
                this.totalRows = filteredItems.length;
                this.currentPage = 1
            },
            search() {
                this.filtered_items = this.mojodi;

                if (this.filter.asset_name) {
                    let re = new RegExp(this.filter.asset_name + ".*", 'gi');
                    this.filtered_items = this.filtered_items.filter(item => item.asset_name.match(re));
                }

                if (this.filter.brand) {
                    let re = new RegExp(this.filter.brand + ".*", 'gi');
                    this.filtered_items = this.filtered_items.filter(item => item.brand.match(re));
                }

                if (this.filter.vname) {
                    let re = new RegExp(this.filter.vname + ".*", 'gi');
                    this.filtered_items = this.filtered_items.filter(item => item.vname.match(re));
                }

            },
        },
        /************************/
        computed: {
            getTableHeader() {
                return 'Found ' + this.mojodi.length + ' record';
            },
        },
        /************************/
        mounted() {
            this.totalRows = this.mojodi.length;
            this.filtered_items = this.mojodi;
        },
        /************************/
        watch: {
            mojodi_filter: {
                deep: true,
                handler: function (val) {
                    this.filter = val;
                    this.search();
                }
            },
            mojodi: {
                deep: true,
                handler: function (val) {
                    this.filtered_items = val;
                    this.totalRows = val.length;
                    this.mojodi_filter = {
                        asset_name: '',
                        brand: '',
                        vname: '',
                    };
                }
            }
        },
        /************************/
        created: function () {
            this.filtered_items = this.mojodi;
            bus.$on('on_order_selected', val => {
                this.mojodi_filter.asset_name = (val.length) ? val[0].asset_name : '';
            });
        },
        /************************/
        /************************/
    }
</script>

<style scoped>

</style>