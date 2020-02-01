<template>
    <div class="row mt-5">
        <div class="col-12 text-center">
            <h3 class="text-center bg-info p-3 mt-5 text-white">{{ getTableHeader }} </h3>
        </div>
        <div class="col-12">
            <b-table striped hover :items="information" class="mt-3 bg-white"
                     :fields="information_fields" responsive
                     head-variant="light"
                     @row-selected="onRowSelected"
                     outlined
                     stacked="md"
                     :current-page="currentPage"
                     :per-page="perPage"
                     :filter="filter"
                     @filtered="onFiltered"
                     :filterIncludedFields="filterOn"
                     selectable select-mode="range" selected-variant="success"
                     :busy="table_waiting">
                <!--******* Busy template ***************-->
                <template v-slot:table-busy>
                    <div class="text-center text-danger my-2">
                        <b-spinner class="align-middle"></b-spinner>
                        <strong>Loading...</strong>
                    </div>
                </template>
                <!--******* Row number ***************-->
                <template v-slot:cell(#)="data">
                    {{ data.index+1 }}
                </template>
                <!--******* Actions ***************-->
                <template v-slot:cell(Actions)="row">
                    <b-button size="sm" @click="row.toggleDetails" class="mr-2">Actions</b-button>
                </template>
                <!--******* Row detail ***************-->
                <template v-slot:row-details="row">
                    <div class="container">
                        <div class="row justify-content-center mb-3 ">
                            <div class="col-12 col-md-8 bg-light rounded">
                                <h3 class="text-center my-3 text-muted border-bottom pb-3">Detail information</h3>
                                <div class="row">
                                    <!--******************************-->
                                    <div class="col-6 text-right label_class">ID :</div>
                                    <div class="col-6 text-left value_class">{{ row.item.id }}</div>
                                    <!--******************************-->
                                    <div class="col-6 text-right label_class">Full name :</div>
                                    <div class="col-6 text-left value_class">{{ row.item.full_name }}</div>
                                    <!--******************************-->
                                    <div class="col-6 text-right label_class">Mobile :</div>
                                    <div class="col-6 text-left value_class">{{ row.item.mobile }}</div>
                                    <!--******************************-->
                                    <div class="col-6 text-right label_class">Email :</div>
                                    <div class="col-6 text-left value_class">{{ row.item.email }}</div>
                                    <!--******************************-->
                                    <div class="col-6 text-right label_class">Target Country :</div>
                                    <div class="col-6 text-left value_class">{{ row.item.country }}</div>
                                    <!--******************************-->
                                    <div class="col-6 text-right label_class">Owner ID :</div>
                                    <div class="col-6 text-left value_class">{{ row.item.owner_id }}</div>
                                    <!--******************************-->
                                    <div class="col-6 text-right label_class">Followup date :</div>
                                    <div class="col-6 text-left value_class">{{ row.item.date_followUp }}</div>
                                    <!--******************************-->
                                    <div class="col-6 text-right label_class">Created at :</div>
                                    <div class="col-6 text-left value_class">{{ row.item.created_at }}</div>
                                    <!--******************************-->
                                    <div class="col-6 text-right label_class">Updated at :</div>
                                    <div class="col-6 text-left value_class">{{ row.item.updated_at }}</div>
                                    <!--******************************-->
                                </div>
                            </div>
                            <div class="col-12 col-md-8 mt-3 text-center">
                                <button class="btn btn-danger w-25">Remove</button>
                                <button class="btn btn-success w-25">Set status</button>
                            </div>
                        </div>
                    </div>
                </template>
            </b-table>
            <!--**************************-->
            <b-col sm="12" class="my-5">
                <b-pagination
                        v-model="currentPage"
                        :total-rows="totalRows"
                        :per-page="perPage"
                        align="fill"
                        size="sm"
                        class="my-0"
                ></b-pagination>
            </b-col>
            <!--**************************-->
        </div>
    </div>
</template>

<script>
    export default {
        name: "data_table",
        /**********************************/
        data() {
            return {
                currentPage: 1,
                perPage: 20,
                totalRows: 1,
                filter: null,
                filterOn: [],
                information_fields: [
                    '#',
                    // {key: 'venue_id', sortable: true, label: 'ID'},
                    // {key: 'city_id', sortable: true, label: 'ID'},
                    {key: 'vname', sortable: true, label: 'Venue name'},
                    {key: 'city_name', sortable: true, label: 'Venue city'},
                    {key: 'status', sortable: true, label: 'Venue status'},
                    'Actions',
                ],
                selected_items: [],
            }
        },
        /**********************************/
        props: ['table_waiting', 'information', 'reset_selection'],
        /**********************************/
        computed: {
            getTableHeader() {
                return 'Found ' + this.information.length + ' record';
            },
        },
        /**********************************/
        mounted() {
            this.totalRows = this.information.length;
            console.log(this.information);
        },
        /**********************************/
        methods: {
            onRowSelected(item) {
                this.selected_items = item;
                this.$emit('select_rows', {item_length: this.selected_items.length, items: this.selected_items});
            },
            onFiltered(filteredItems) {
                // Trigger pagination to update the number of buttons/pages due to filtering
                this.totalRows = filteredItems.length;
                this.currentPage = 1
            },
        },
        /**********************************/
        watch: {
            information: function () {
                this.totalRows = this.information.length;
            },
            reset_selection: function () {
            },
        },
        /**********************************/
    }
</script>

<style scoped>
    .label_class {
        font-weight : bold;
        font-style  : italic;
        color       : #da5d5d;
    }

    .value_class {
        font-weight : bold;
    }
</style>