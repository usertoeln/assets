<template>
    <div class="card">
        <div class="card-header">
            <h5>Warehouse inventory</h5>
        </div>
        <div class="card-body">
            <div class="card-text">
                <div class="row justify-content-around">
                    <input v-model="mojodi_filter.asset_name"
                           type="text" placeholder="asset" class="form-control col-5">
                    <input v-model="mojodi_filter.brand"
                           type="text" placeholder="brand" class="form-control col-5">
                </div>
                <div class="row mt-2">
                    <b-table hover :items="filtered_items" class="bg-white col-12"
                             sticky-header sticky-header="600px" bordered
                             :fields="venue_assets_data_fields"
                             head-variant="light"
                             @row-selected="onRowSelected"
                             outlined
                             :filter="filter"
                             @filtered="onFiltered"
                             :filterIncludedFields="filterOn"
                             selectable select-mode="single" selected-variant="success"
                             :busy="venue_assets_table_waiting">
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
</template>

<script>
    export default {
        /************************/
        name: "venue_assets",
        /************************/
        props: ['venue_assets_data', 'venue_assets_table_waiting'],
        /************************/
        data() {
            return {
                venue_assets_data_fields: [
                    {key: 'vname', sortable: true, label: 'vname'},
                    {key: 'asset_name', sortable: true, label: 'asset_name'},
                    {key: 'brand', sortable: true, label: 'brand'},
                    {key: 'color', sortable: true, label: 'color'},
                    {key: 'asset_count', sortable: true, label: 'asset_count'},
                ],
                filter: null,
                filterOn: [],
                totalRows: 0,
                filtered_items: this.venue_assets_data,
                mojodi_filter: {
                    asset_name: '',
                    brand: '',
                },
            }
        },
        /************************/
        methods: {
            onRowSelected(item) {
                this.$emit('on_asset_select', item);
            },
            onFiltered(filteredItems) {
                // Trigger pagination to update the number of buttons/pages due to filtering
                this.totalRows = filteredItems.length;
                this.currentPage = 1
            },
            search() {
                this.filtered_items = this.venue_assets_data;

                if (this.filter.asset_name) {
                    let re = new RegExp(this.filter.asset_name + ".*", 'gi');
                    this.filtered_items = this.filtered_items.filter(item => item.asset_name.match(re));
                }

                if (this.filter.brand) {
                    let re = new RegExp(this.filter.brand + ".*", 'gi');
                    this.filtered_items = this.filtered_items.filter(item => item.brand.match(re));
                }
            },
        },
        /************************/
        computed: {
            getTableHeader() {
                return 'Found ' + this.information.length + ' record';
            },
        },
        /************************/
        mounted() {
            this.totalRows = this.venue_assets_data.length;
            this.filtered_items = this.venue_assets_data;
            // console.log(this.filtered_items);
        },
        /************************/
        watch: {
            mojodi_filter: {
                deep: true,
                handler: function (val) {
                    this.filter = val;
                    this.search();
                    // console.log(val);
                }
            },
            venue_assets_data: {
                deep: true,
                handler: function (val) {
                    this.filtered_items = val;
                    this.totalRows = val.length;
                    this.mojodi_filter = {
                        asset_name: '',
                        brand: '',
                    };
                }
            }
        },
        /************************/
        created: function () {
            this.filtered_items = this.venue_assets_data;
        },
        /************************/

    }
</script>

<style scoped>

</style>