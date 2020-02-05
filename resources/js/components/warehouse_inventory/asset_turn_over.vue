<template>
    <div class="card">
        <div class="card-header">
            <h5>Inventory turnover of asset</h5>
        </div>
        <div class="card-body">
            <div class="card-text">
                <p v-html="get_inventory_turnover_asset_header"></p>
                <!--******** table *********-->
                <b-table v-if="data_exists" hover :items="turn_overs" class="bg-white col-12"
                         sticky-header sticky-header="600px" bordered
                         :fields="turn_overs_fields"
                         head-variant="light"
                         @row-selected="onRowSelected"
                         outlined
                         :filter="filter"
                         @filtered="onFiltered"
                         :filterIncludedFields="filterOn"
                         selectable select-mode="single" selected-variant="success"
                         :busy="asset_turn_over_table_waiting">
                    <!--******* Busy template ***************-->
                    <template v-slot:table-busy>
                        <div class="text-center text-danger my-2">
                            <b-spinner class="align-middle"></b-spinner>
                            <strong>Loading...</strong>
                        </div>
                    </template>
                    <!--******* Actions ***************-->
                    <template v-slot:cell(Actions)="row">
                        <b-button size="sm" class="btn btn-primary mr-2" style="width:70px"
                                  @click="openModal($event.target,'u',row.item,row.index)">Edit
                        </b-button>
                        <b-button size="sm" @click="remove(row.item,row.index)" style="width:70px"
                                  class="btn btn-danger mr-2">Remove
                        </b-button>

                    </template>
                </b-table>
                <!--*****************-->
                <div class="col-12 text-center">
                    <button :class="[selected_items.length ? null : null,'btn', 'btn-success', 'w-25']"
                            @click="openModal($event.target,'i')">Add new
                    </button>
                </div>
                <!--*****************-->
            </div>
        </div>
        <!--**************************-->
        <div>
            <b-modal :id="get_asset_modal_id(0)" :title="modal.header" hide-footer size="md">
                <form @submit.prevent="save_turn_over">
                    <!--******* venue_id *********-->
                    <div :class="[save_type==='i' ? null : 'd-none']">
                        <b-form-group label="Venues:">
                            <b-form-select
                                    v-model="form.venue_id"
                                    :options="venues"
                                    value-field="venue_id"
                                    text-field="vname"
                            ></b-form-select>
                        </b-form-group>
                    </div>
                    <!--******* asset_name *********-->
                    <div :class="[save_type==='i' ? null : 'd-none']">
                        <b-form-group label="Asset:">
                            <b-form-select
                                    v-model="form.asset_id"
                                    :options="assets"
                                    value-field="id"
                                    text-field="asset_name"
                            ></b-form-select>
                        </b-form-group>
                    </div>
                    <!--******* asset_count *********-->
                    <div class="form-row">
                        <div class="form-group col-12">
                            <label for="turn_over_asset_count">Asset count:</label>
                            <input type="number"
                                   class="form-control"
                                   v-model="form.asset_count"
                                   id="turn_over_asset_count">
                        </div>
                    </div>
                    <!--******* serial_no *********-->
                    <div class="form-row">
                        <div class="form-group col-12">
                            <label for="turn_over_serial_no">Serial number:</label>
                            <input type="text"
                                   class="form-control"
                                   v-model="form.serial_no"
                                   id="turn_over_serial_no">
                        </div>
                    </div>
                    <!--******* descp *********-->
                    <div class="form-row">
                        <div class="form-group col-12">
                            <label for="turn_over_descp">Description :</label>
                            <input type="text"
                                   class="form-control"
                                   v-model="form.descp"
                                   id="turn_over_descp">
                        </div>
                    </div>
                    <!--***********  Buttons *************-->
                    <div class="row">
                        <div class="col-12 text-right">
                            <button class="btn btn-success w-25">Save</button>
                        </div>
                    </div>
                </form>
            </b-modal>
        </div>
        <!--**************************-->
    </div>
</template>

<script>
    import axios from 'axios';
    import bus from '../../event_bus';

    export default {
        /************************/
        name: "asset_turn_over",
        /************************/
        data() {
            return {
                assets: [],
                data_exists: false,
                asset: {},
                turn_overs: [],
                table_waiting: false,
                turn_overs_fields: [
                    {key: 'order_detail_created_at', sortable: true, label: 'Date'},
                    {key: 'serial_no', sortable: true, label: 'Serial'},
                    {key: 'rec_type', sortable: true, label: 'Type'},
                    {key: 'descp', sortable: true, label: 'Descp.'},
                    {key: 'asset_count', sortable: true, label: 'Count'},
                    'Actions',
                ],
                filter: null,
                filterOn: [],
                totalRows: 0,
                filtered_items: {},
                save_type: '',
                selected: {item: null, index: null},
                form: {
                    order_id: '',
                    venue_id: '',
                    order_detail_id: '',
                    asset_id: '',
                    asset_count: '',
                    serial_no: '',
                    descp: '',
                    status: 6,
                    rec_type: 'init',
                },
                selected_items: [],
                modal: {
                    header: 'Asset',
                },
                asset_turn_over_table_waiting: true,
                selected_venues: [],
                venues: [],
            }
        },
        /************************/
        props: ['selected_asset'],
        /************************/
        created: function () {
            this.get_assets();
            this.get_venues();
            bus.$on('on_change_venue', (data) => {
                this.selected_venues = data;
            });
        },
        /************************/
        watch: {
            selected_asset: {
                deep: true,
                handler: function (val) {
                }
            },
        },
        /************************/
        computed: {
            get_inventory_turnover_asset_header() {
                let header = null;
                if (this.selected_asset.length) {
                    this.asset = this.selected_asset[0];
                    header = '<p>The turnover of <span class="text-danger font-weight-bold">' + this.asset.asset_count + '</span> count of Asset <span class="text-danger font-weight-bold">' + this.asset.asset_name + '</span> Brand <span class="text-danger font-weight-bold">' + this.asset.brand + '</span> color <span class="text-danger font-weight-bold">' + this.asset.color + '</span> in <span class="text-danger font-weight-bold">' + this.asset.vname + '</span> Venue : </p>';
                    this.get_data();
                    this.data_exists = true;
                } else {
                    header = '<p>to see Inventory turnover of asset Select asset from <span class="text-danger font-weight-bold">Warehouse inventory</span> first</p>';
                    this.asset = {};
                    this.data_exists = false;
                }
                return header;
            },
        },
        /************************/
        methods: {
            /***********************/
            get_venues() {
                axios({
                    url: '/api/get_venues',
                    data: null,
                    method: 'get'
                }).then(res => {
                    this.venues = res.data;
                    // console.log(res.data);
                    this.makeToast(res.data.length + ' Venues load successfully', 'success');
                }).catch(res => {
                    console.log('get_users', res);
                    this.makeToast('Venues loading failed', 'danger');
                });
            },
            /***********************/
            get_assets() {
                axios({
                    url: '/api/get_assets',
                    data: null,
                    method: 'get'
                }).then(res => {
                    $.each(res.data, (index, item) => {
                        this.assets.push({
                            id: item.id,
                            asset_name: item.asset_name + ' , ' + item.brand + ' , ' + item.color
                        })
                    });
                    this.makeToast(res.data.length + ' Asset load successfully', 'success');
                }).catch(res => {
                    console.log('get_assets', res);
                    this.makeToast('Asset load failed', 'danger');
                });
            },
            /***********************/
            get_data() {
                this.asset_turn_over_table_waiting = true;
                axios({
                    url: '/api/ops/get_asset_turn_over',
                    data: {venue_id: this.asset.venue_id, asset_id: this.asset.asset_id},
                    method: 'post'
                }).then(res => {
                    // console.log('get_data', res.data);
                    this.turn_overs = res.data;
                    this.makeToast(res.data.length + ' turnover records load successfully', 'success');
                    this.asset_turn_over_table_waiting = false;
                }).catch(error => {
                    this.turn_overs = [];
                    console.log('get_data', error);
                    this.asset_turn_over_table_waiting = false;
                    this.makeToast('turnover records loading failed', 'danger');
                });
            },
            /***********************/
            onRowSelected(item) {
                this.selected_items = (item.length) ? item : {};
            },
            /***********************/
            onFiltered(filteredItems) {
            },
            /***********************/
            search() {
            },
            /***********************/
            openModal(target, m_type, item = null, index = 0) {
                // m_type = 'i' || 'u';
                this.save_type = m_type;
                if (m_type === 'i') {
                    this.modal.header = 'Define new ';
                    // console.log('openModal', this.selected_items);
                    this.form = {
                        order_id: this.selected_items.length ? this.selected_items[0].order_id : 0,
                        venue_id: this.selected_items.length ? this.selected_items[0].venue_id : 0,
                        order_detail_id: this.selected_items.length ? this.selected_items[0].order_detail_id : 0,
                        asset_id: this.selected_items.length ? this.selected_items[0].asset_id : 0,
                        asset_count: null,
                        serial_no: null,
                        descp: null,
                        status: 6,
                        rec_type: 'init',
                    };
                    this.selected = {item: null, index: null};
                } else {
                    this.modal.header = 'Edit warehouse inventory';
                    this.form = {
                        order_id: item.order_id,
                        venue_id: item.venue_id,
                        order_detail_id: item.order_detail_id,
                        asset_id: item.asset_id,
                        asset_count: item.asset_count,
                        serial_no: item.serial_no,
                        descp: item.descp,
                        status: 6,
                        rec_type: 'init',
                    };
                    this.selected = {item: item, index: index};
                }
                this.$root.$emit('bv::show::modal', 'turn_over_modal_id', target);
            },
            /***********************/
            makeToast(message = 'IFI message', variant = 'primary') {
                this.$bvToast.toast(message, {
                    title: 'IFI message status',
                    autoHideDelay: 5000,
                    variant: variant,
                    solid: true,
                })
            },
            /***********************/
            remove(item, index) {
                axios({
                    url: '/api/order_detail/remove',
                    data: {order_detail_id: item.order_detail_id},
                    method: 'post'
                }).then(res => {
                    // console.log('remove asset ', item, index);
                    if (res.data) {
                        this.turn_overs.splice(this.turn_overs.indexOf(item), 1);
                        this.makeToast('Turn over Delete successfully', 'success');
                    }
                    else
                        this.makeToast('Turn over Delete failed', 'danger');
                }).catch(error => {
                    console.log(error);
                    this.makeToast('Asset delete failed : ' + error.response.data, 'danger');
                });
            },
            /***********************/
            get_asset_modal_id(id = 0) {
                if (id)
                    return 'turn_over_modal_id_' + id;
                else {
                    return 'turn_over_modal_id';
                }
            },
            /***********************/
            save_turn_over() {
                if (this.save_type === 'i') {
                    /****** insert mode *******/
                    axios({
                        url: '/api/order_detail/insert',
                        data: this.form,
                        method: 'post'
                    }).then(res => {
                        console.log('/api/order_detail/insert', res);
                        this.turn_overs.push(res.data);
                        this.makeToast('Turn over create successfully', 'success');
                    }).catch(error => {
                        console.log(error);
                        this.makeToast('Turn over create failed : ' + error.response.data, 'danger');
                    });
                } else {
                    axios({
                        url: '/api/order_detail/update',
                        data: this.form,
                        method: 'post'
                    }).then(res => {
                        console.log('order_detail', res);
                        if (res.data) {
                            this.turn_overs[this.selected.index].asset_count = this.form.asset_count;
                            this.turn_overs[this.selected.index].serial_no = this.form.serial_no;
                            this.turn_overs[this.selected.index].descp = this.form.descp;
                            this.makeToast('Asset update successfully', 'success');
                        }
                        else
                            this.makeToast('Asset update failed', 'danger');
                    }).catch(error => {
                        console.log(error);
                        this.makeToast('order_detail update failed : ' + error.response.data, 'danger');
                    });
                }
                this.$root.$emit('bv::hide::modal', 'turn_over_modal_id');
            },
            /***********************/
        },
        /************************/
    }
</script>

<style scoped>

</style>