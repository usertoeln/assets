<template>
    <div class="card">
        <div class="card-header">
            <h5>Orders information</h5>
        </div>
        <div class="card-body">
            <div class="card-text">
                <!--********** filter section *********-->
                <div class="row justify-content-around">
                    <div class="col-12 col-md-3">
                        <input v-model="order_filter.vname" type="text" placeholder="Venue" class="form-control ">
                    </div>
                    <div class="col-12 col-md-3">
                        <input v-model="order_filter.asset_name" type="text" placeholder="Asset"
                               class="form-control">
                    </div>
                    <div class="col-12 col-md-3">
                        <input v-model="order_filter.order_id" type="text" placeholder="Order Id" class="form-control">
                    </div>
                    <div class="col-12 col-md-3">
                        <input v-model="order_filter.status" type="text" placeholder="Order Status"
                               class="form-control">
                    </div>
                </div>
                <!--********** Header *********-->
                <div class="row d-none">
                    <div class="col-12">
                        <p v-html="get_order_header"></p>
                    </div>
                </div>
                <!--********** table section *********-->
                <div class="row mt-2">
                    <div class="col-12">
                        <b-table v-if="data_exists" hover :items="order_filtered_items" class="bg-white"
                                 sticky-header sticky-header="600px" bordered striped small
                                 :fields="orders_fields"
                                 head-variant="dark"
                                 @row-selected="onRowSelected"
                                 outlined
                                 :filter="filter"
                                 @filtered="onFiltered"
                                 :filterIncludedFields="filterOn"
                                 selectable select-mode="single" selected-variant="success"
                                 :busy="orders_table_waiting">
                            <!--******* parent_order ***************-->
                            <template v-slot:cell(parent_order)="data">
                                {{ data.item.parent_order ? 'P('+data.item.parent_order+')' : null }}
                                {{ data.item.has_child ? 'C('+data.item.has_child+')' : null}}
                            </template>
                            <!--******* Row number ***************-->
                            <template v-slot:cell(#)="data">
                                {{ data.index+1 }}
                            </template>
                            <!--******* Busy template ***************-->
                            <template v-slot:table-busy>
                                <div class="text-center text-danger my-2">
                                    <b-spinner class="align-middle"></b-spinner>
                                    <strong>Loading...</strong>
                                </div>
                            </template>
                            <!--******* Actions ***************-->
                            <template v-slot:cell(Actions)="row">
                                <b-button size="sm" @click="row.toggleDetails" class="mr-2">Actions</b-button>
                            </template>
                            <!--******* Row detail ***************-->
                            <template v-slot:row-details="row">
                                <!--****************************-->
                                <div class="container">
                                    <div class="row">
                                        <div class="col-12 col-md-10 ">
                                            <div class="row">
                                                <div class="col-12" v-if="row.item.parent.length>0">
                                                    <div class="row">
                                                        <div class="col-12 col-md-6"
                                                             v-for="(item,index) in row.item.parent">
                                                            <div class="card mt-3">
                                                                <div class="card-header">
                                                                    <h5>Base of {{ item.vname }} Order</h5>
                                                                </div>
                                                                <div class="card-body">
                                                                    <div class="card-text">
                                                                        <ul>
                                                                            <li>
                                                                                <span>Order ID:</span>
                                                                                <span>{{ item.order_id }}</span>
                                                                            </li>
                                                                            <li>
                                                                                <span>Order detail ID:</span>
                                                                                <span>{{ item.order_detail_id }}</span>
                                                                            </li>
                                                                            <li>
                                                                                <span>Asset count:</span>
                                                                                <span>{{ item.asset_count }}</span>
                                                                            </li>
                                                                            <li>
                                                                                <span>Serial number:</span>
                                                                                <span>{{ item.serial_no }}</span>
                                                                            </li>
                                                                            <li>
                                                                                <span>User name:</span>
                                                                                <span>{{ item.user_name }}</span>
                                                                            </li>
                                                                            <li>
                                                                                <span>Venue name:</span>
                                                                                <span>{{ item.vname }}</span>
                                                                            </li>
                                                                            <li>
                                                                                <span>Asset name:</span>
                                                                                <span>{{ item.asset_name }}</span>
                                                                            </li>
                                                                            <li>
                                                                                <span>Created at:</span>
                                                                                <span>{{ item.order_detail_created_at }}</span>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12" v-if="row.item.children.length>0">
                                                    <div class="row">
                                                        <div class="col-12 col-md-6"
                                                             v-for="(item,index) in row.item.children">
                                                            <div class="card mt-3">
                                                                <div class="card-header">
                                                                    <h5>
                                                                        {{ 'Destination no.' + (index+1) + '-' +
                                                                        item.vname }}
                                                                    </h5>
                                                                </div>
                                                                <div class="card-body">
                                                                    <div class="card-text">
                                                                        <ul>
                                                                            <li>
                                                                                <span>Order ID:</span>
                                                                                <span>{{ item.order_id }}</span>
                                                                            </li>
                                                                            <li>
                                                                                <span>Order Detail ID:</span>
                                                                                <span>{{ item.order_detail_id }}</span>
                                                                            </li>
                                                                            <li>
                                                                                <span>Asset Count:</span>
                                                                                <span>{{ item.asset_count }}</span>
                                                                            </li>
                                                                            <li>
                                                                                <span>Serial Number:</span>
                                                                                <span>{{ item.serial_no }}</span>
                                                                            </li>
                                                                            <li>
                                                                                <span>User Name:</span>
                                                                                <span>{{ item.user_name }}</span>
                                                                            </li>
                                                                            <li>
                                                                                <span>Venue Name:</span>
                                                                                <span>{{ item.vname }}</span>
                                                                            </li>
                                                                            <li>
                                                                                <span>Asset Name:</span>
                                                                                <span>{{ item.asset_name }}</span>
                                                                            </li>
                                                                            <li>
                                                                                <span>Received date:</span>
                                                                                <span>{{ item.received }}</span>
                                                                            </li>
                                                                            <li>
                                                                                <span>Created At.:</span>
                                                                                <span>{{ item.order_detail_created_at }}</span>
                                                                            </li>
                                                                        </ul>
                                                                        <button class="btn btn-success ml-auto d-flex"
                                                                                v-if="item.status===4 && !item.received"
                                                                                @click="order_received(item.order_detail_id)">
                                                                            Received
                                                                        </button>
                                                                        <div v-else-if="!item.received"
                                                                             class="text-danger text-center font-weight-bold">
                                                                            Pending....
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--****** buttons ***********-->
                                        <div class="col-12 col-md-2">
                                            <div class="text-right">
                                                <div class="col-12">
                                                    <b-button size="sm" @click="get_duty(row.item,row.index)"
                                                              style="width:140px"
                                                              :class="[get_buttons_state('duty',row.item) , 'btn','btn-success','mt-3']">
                                                        New duty
                                                    </b-button>
                                                </div>

                                                <div class="col-12">
                                                    <b-button size="sm" @click="packaging(row.item,row.index)"
                                                              style="width:140px"
                                                              :class="[get_buttons_state('packaging',row.item),'btn' ,'btn-warning','mt-3']">
                                                        Packaging
                                                    </b-button>
                                                </div>

                                                <div class="col-12">
                                                    <b-button size="sm" @click="sent(row.item,row.index)"
                                                              style="width:140px"
                                                              :class="[get_buttons_state('sent',row.item),'btn', 'btn-info','mt-3']">
                                                        Sent items
                                                    </b-button>
                                                </div>

                                                <div class="col-12">
                                                    <b-button size="sm" @click="receive_order(row.item,row.index)"
                                                              style="width:140px"
                                                              :class="[get_buttons_state('received',row.item),'btn', 'btn-success','mt-3']">
                                                        Asset received
                                                    </b-button>
                                                </div>

                                                <div class="col-12">
                                                    <b-button size="sm" @click="reject_order(row.item,row.index)"
                                                              style="width:140px"
                                                              :class="[get_buttons_state('reject',row.item),'btn', 'btn-danger','mt-3']">
                                                        Reject
                                                    </b-button>
                                                </div>

                                                <div class="col-12">
                                                    <b-button size="sm"
                                                              :class="[get_buttons_state('edit',row.item),'btn' ,'btn-primary' ,'mt-3']"
                                                              style="width:140px"
                                                              @click="openModal($event.target,'u',row.item,row.index)">
                                                        Edit order
                                                    </b-button>
                                                </div>

                                                <div class="col-12">
                                                    <b-button size="sm" @click="delete_order(row.item,row.index)"
                                                              style="width:140px"
                                                              :class="[get_buttons_state('delete',row.item),'btn' ,'btn-primary' ,'mt-3']">
                                                        Delete order
                                                    </b-button>
                                                </div>

                                                <div class="col-12">
                                                    <b-button size="sm" @click="remove(row.item,row.index)"
                                                              style="width:140px"
                                                              :class="[get_buttons_state('remove',row.item),'my-3', 'btn' ,'btn-danger']">
                                                        Remove order
                                                    </b-button>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </b-table>
                    </div>
                </div>
                <!--********  new request button *********-->
                <div class="row">
                    <div class="col-12 text-center">
                        <button :class="['btn', 'btn-success']"
                                style="width:140px"
                                @click="openModal($event.target,'i')">Add new order
                        </button>
                        <button :class="['btn', 'btn-warning']"
                                style="width:140px"
                                @click="reload_order">Data reload
                        </button>

                    </div>
                </div>
            </div>
        </div>
        <!--**************************-->
        <div>
            <b-modal id="order_modal" :title="order_modal.header" hide-footer size="md">
                <form @submit.prevent="save_order">
                    <!--******* venue_id *********-->
                    <div :class="['d-none']">
                        <b-form-group label="Venues:">
                            <b-form-select
                                    v-model="order_form.venue_id"
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
                                    v-model="order_form.asset_id" required
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
                            <input type="number" required min="1" max="1000"
                                   class="form-control"
                                   v-model="order_form.asset_count"
                                   id="turn_over_asset_count">
                        </div>
                    </div>
                    <!--******* serial_no *********-->
                    <div class="form-row d-none">
                        <div class="form-group col-12">
                            <label for="turn_over_serial_no">Serial number:</label>
                            <input type="text"
                                   class="form-control"
                                   v-model="order_form.serial_no"
                                   id="turn_over_serial_no">
                        </div>
                    </div>
                    <!--******* descp *********-->
                    <div class="form-row d-none">
                        <div class="form-group col-12">
                            <label for="turn_over_descp">Description :</label>
                            <input type="text"
                                   class="form-control"
                                   v-model="order_form.descp"
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
            <b-modal id="duty_modal" title="Duty form" hide-footer size="md">
                <div :class="[form_errors.length ? null : 'd-none','alert','alert-danger']">
                    <p>You have an error : </p>
                    <ul>
                        <li v-for="item in form_errors">{{ item }}</li>
                    </ul>
                </div>
                <form @submit.prevent="save_duty">
                    <!--******* asset_name *********-->
                    <div class="form-row">
                        <div class="form-group col-12">
                            <label for="duty_form_asset_name">Selected asset:</label>
                            <input type="text"
                                   class="form-control" disabled readonly
                                   v-model="duty_form.asset_name"
                                   id="duty_form_asset_name">
                        </div>
                    </div>
                    <!--******* asset_count *********-->
                    <div class="form-row">
                        <div class="form-group col-12">
                            <label for="duty_form_asset_count">Asset count:</label>
                            <input type="number" min="1" :max="get_max_asset_venue"
                                   class="form-control" required
                                   v-model="duty_form.asset_count"
                                   id="duty_form_asset_count">
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
        /**********************/
        name: "orders",
        /**********************/
        data() {
            return {
                assets: [],
                data_exists: true,
                asset: {},
                user_scopes: [],
                orders: [],
                table_waiting: false,
                orders_fields: [
                    '#',
                    {key: 'order_id', sortable: true, label: 'ID'},
                    {key: 'order_detail_created_at', sortable: true, label: 'Order Date'},
                    {key: 'vname', sortable: true, label: 'Venue'},
                    {key: 'asset_name', sortable: true, label: 'Asset'},
                    {key: 'asset_count', sortable: true, label: 'Count'},
                    {key: 'status', sortable: true, label: 'Status'},
                    {key: 'parent_order', sortable: true, label: 'Audit'},
                    {key: 'rec_type', sortable: true, label: 'Type'},
                    'Actions',
                ],
                form_errors: [],
                filter: null,
                filterOn: [],
                totalRows: 0,
                order_filtered_items: this.orders,
                order_filter: {
                    vname: '',
                    asset_name: '',
                    order_id: '',
                    status: '',
                },
                save_type: '',
                selected: {item: null, index: null},
                selected_venue: [],
                order_form: {
                    order_id: '',
                    venue_id: '',
                    parent_order: '',
                    order_detail_id: '',
                    asset_id: '',
                    asset_count: '',
                    serial_no: '',
                    status: '',
                    rec_type: '',
                    descp: '',
                    owner_id: '',
                },
                duty_form: {},
                selected_items: [],
                order_modal: {
                    header: 'Orders',
                },
                orders_table_waiting: false,
                venues: [],
            }
        },
        /************************/
        props: ['selected_asset'],
        /************************/
        computed: {
            get_order_header() {
                return this.orders.length ? this.orders.length + ' Record found' : 'Nothing found';
            },
            get_max_asset_venue() {
                let venue_count = (this.selected_venue.length) ? this.selected_venue[0].asset_count : 0;
                let request_count = (this.selected_items.length) ? this.selected_items[0].asset_count : 0;
                return (venue_count > request_count) ? request_count : venue_count;
            },
        },
        /**********************/
        created: function () {

            bus.$on('on_venue_select', val => {
                this.selected_venue = val;
            });
            this.get_user_access();
            this.get_assets();
            this.get_full_order();
        },
        /************************/
        watch: {
            selected_items: {
                deep: true,
                handler: function (val) {
                }
            },
            order_filter: {
                deep: true,
                handler: function (val) {
                    this.filter = val;
                    this.search();
                }
            },
        },
        /************************/
        methods: {
            /***********************/
            get_user_access() {
                axios.get('/api/get_user_access')
                    .then(response => {
                        this.user_scopes = response.data.order_access;
                    }).catch(response => {
                    console.log(response);
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
                    // this.makeToast(res.data.length + ' Asset load successfully', 'success');
                }).catch(res => {
                    console.log('get_assets', res);
                    this.makeToast('Asset load failed', 'danger');
                });
            },
            /***********************/
            get_full_order() {
                this.orders_table_waiting = true;
                axios({
                    url: '/api/get_full_order',
                    data: {show_init: false},
                    method: 'post'
                }).then(res => {
                    // console.log('get_data', res.data);
                    this.orders = res.data;
                    this.order_filtered_items = res.data;
                    // this.makeToast(res.data.length + ' orders load successfully', 'success');
                    this.orders_table_waiting = false;
                }).catch(error => {
                    this.orders = [];
                    console.log('get_data', error);
                    this.orders_table_waiting = false;
                    this.makeToast('orders records loading failed', 'danger');
                });
            },
            /***********************/
            onRowSelected(item) {
                this.selected_items = (item.length) ? item : {};
                bus.$emit('on_order_selected', this.selected_items);
            },
            /***********************/
            onFiltered(filteredItems) {
                this.totalRows = filteredItems.length;
                this.currentPage = 1
            },
            /***********************/
            search() {
                this.order_filtered_items = this.orders;

                if (this.order_filter.vname) {
                    let re = new RegExp(this.order_filter.vname + ".*", 'gi');
                    this.order_filtered_items = this.order_filtered_items.filter(item => item.vname.match(re));
                }

                if (this.order_filter.asset_name) {
                    let re = new RegExp(this.order_filter.asset_name + ".*", 'gi');
                    this.order_filtered_items = this.order_filtered_items.filter(item => item.asset_name.match(re));
                }

                if (this.order_filter.order_id) {
                    this.order_filtered_items = this.order_filtered_items.filter(item => {
                        // console.log(item.order_id, this.order_filter.order_id);
                        return (item.order_id == this.order_filter.order_id || item.parent_order == this.order_filter.order_id);
                    });
                }

                if (this.order_filter.status) {
                    let re = new RegExp(this.order_filter.status + ".*", 'gi');
                    this.order_filtered_items = this.order_filtered_items.filter(item => {
                        // console.log(item.status, this.order_filter.status);
                        return item.status.match(re);
                    });
                }
            },
            /***********************/
            openModal(target, m_type, item = null, index = 0) {
                // m_type = 'i' || 'u';
                this.save_type = m_type;
                if (m_type === 'i') {
                    this.order_modal.header = 'Define new request';
                    this.order_form = {
                        order_id: 0,
                        venue_id: 0,
                        parent_order: '',
                        order_detail_id: 0,
                        asset_id: '',
                        asset_count: '',
                        serial_no: '',
                        status: 1,
                        rec_type: 'debit',
                        descp: '',
                        owner_id: '',
                    };
                    this.selected = {item: null, index: null};
                } else {
                    this.order_modal.header = 'Edit order';
                    this.order_form = {
                        order_id: item.order_id,
                        venue_id: item.venue_id,
                        parent_order: item.parent_order,
                        order_detail_id: item.order_detail_id,
                        asset_id: item.asset_id,
                        asset_count: item.asset_count,
                        serial_no: item.serial_no,
                        status: item.status,
                        rec_type: item.rec_type,
                        descp: item.descp,
                        owner_id: item.owner_id,
                    };
                    this.selected = {item: item, index: index};
                }
                this.$root.$emit('bv::show::modal', 'order_modal', target);
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
                        this.orders.splice(this.orders.indexOf(item), 1);
                        this.makeToast('Order Delete successfully', 'success');
                        this.get_full_order();
                    }
                    else
                        this.makeToast('Order Delete failed', 'danger');
                }).catch(error => {
                    console.log(error);
                    this.makeToast('Order delete failed : ' + error.response.data, 'danger');
                });
            },
            /***********************/
            delete_order(item, index) {
                axios({
                    url: '/api/order_detail/delete',
                    data: {order_id: item.order_id},
                    method: 'post'
                }).then(res => {
                    console.log('delete_order ', item, index);
                    if (res.data) {
                        this.orders.splice(this.orders.indexOf(item), 1);
                        this.makeToast('Order Delete successfully', 'success');
                        this.get_full_order();
                    }
                    else
                        this.makeToast('Order Delete failed', 'danger');
                }).catch(error => {
                    console.log(error);
                    this.makeToast('Order delete failed : ' + error.response.data, 'danger');
                });
            },
            /***********************/
            get_asset_modal_id(id = 0) {
                return (id) ? 'turn_over_modal_id_' + id : 'turn_over_modal_id_';
            },
            /***********************/
            get_buttons_state(btn, item) {
                let btn_class = null;
                let rec_type_state = false;
                let status_state = false;
                let status = item.status;
                let rec_type = item.rec_type;
                let has_access = this.user_scopes.find(scope => {
                    return btn === scope
                });
                if (!has_access)
                    return 'd-none';
                switch (btn) {
                    case 'delete':
                        rec_type_state = true;
                        status_state = true;
                        break;
                    case 'remove':
                        rec_type_state = (rec_type === 'debit' || rec_type === 'credit');
                        status_state = (status === 'request' && !item.has_parent_order);
                        status_state &= !item.parent.length;
                        status_state &= !item.children.length;
                        break;
                    case 'edit':
                        rec_type_state = (rec_type === 'debit' || rec_type === 'credit');
                        status_state = (status === 'request' && !item.has_parent_order);
                        status_state &= !item.parent.length;
                        status_state &= !item.children.length;
                        break;
                    case 'reject':
                        rec_type_state = (rec_type === 'debit');
                        status_state = (status === 'request' && !item.has_parent_order);
                        status_state &= !item.parent.length;
                        status_state &= !item.children.length;
                        break;
                    case 'received':
                        rec_type_state = (rec_type === 'credit');
                        status_state = (status === 'sent') && false;
                        break;
                    case 'sent':
                        rec_type_state = (rec_type === 'credit');
                        status_state = (status === 'packaging');
                        break;
                    case 'packaging':
                        rec_type_state = (rec_type === 'credit');
                        status_state = (status === 'duty');
                        break
                    case 'duty':
                        let has_venue = this.selected_venue.length;
                        rec_type_state = (rec_type === 'debit');
                        status_state = (status === 'request') && has_venue && !item.has_parent_order;
                        status_state &= this.selected_items.length;
                        break;
                }
                btn_class = (rec_type_state && status_state) ? null : 'd-none';
                return btn_class;
            },
            /***********************/
            save_order() {
                if (this.save_type === 'i') {
                    /****** insert mode *******/
                    axios({
                        url: '/api/order_detail/insert',
                        data: this.order_form,
                        method: 'post'
                    }).then(res => {
                        // console.log('save_order', res);
                        this.orders.push(res.data[0]);
                        this.makeToast('order create successfully', 'success');
                    }).catch(error => {
                        console.log(error);
                        this.makeToast('order create failed : ' + error.response.data, 'danger');
                    });
                } else {
                    axios({
                        url: '/api/order_detail/update',
                        data: this.order_form,
                        method: 'post'
                    }).then(res => {
                        console.log('order_detail', res);
                        if (res.data) {
                            this.orders[this.selected.index].asset_count = this.order_form.asset_count;
                            // this.orders[this.selected.index].serial_no = this.form.serial_no;
                            // this.orders[this.selected.index].descp = this.form.descp;
                            this.makeToast('order update successfully', 'success');
                        }
                        else
                            this.makeToast('order update failed', 'danger');
                    }).catch(error => {
                        console.log(error);
                        this.makeToast('order update failed : ' + error.response.data, 'danger');
                    });
                }
                this.$root.$emit('bv::hide::modal', 'order_modal');
            },
            /***********************/
            sent(item, index) {
                axios({
                    url: '/api/order_detail/sent_order',
                    data: {
                        order_detail_id: item.order_detail_id,
                        order_id: item.order_id,
                    },
                    method: 'post'
                }).then(res => {
                    if (res.data)
                        this.orders[this.orders.indexOf(item)].status = res.data.status;
                    else
                        this.makeToast('Reject order failed', 'danger');
                }).catch(error => {
                    console.log(error);
                    this.makeToast('Reject order failed : ' + error.response.data, 'danger');
                });
            },
            /***********************/
            packaging(item, index) {
                axios({
                    url: '/api/order_detail/packaging_order',
                    data: {
                        order_detail_id: item.order_detail_id,
                        order_id: item.order_id,
                    },
                    method: 'post'
                }).then(res => {
                    if (res.data)
                        this.orders[this.orders.indexOf(item)].status = res.data.status;
                    else
                        this.makeToast('Reject order failed', 'danger');
                }).catch(error => {
                    console.log(error);
                    this.makeToast('Reject order failed : ' + error.response.data, 'danger');
                });
            },
            /***********************/
            reject_order(item, index) {
                axios({
                    url: '/api/order_detail/reject_order',
                    data: {
                        order_detail_id: item.order_detail_id,
                        order_id: item.order_id,
                    },
                    method: 'post'
                }).then(res => {
                    if (res.data)
                        this.orders[this.orders.indexOf(item)].status = res.data.status;
                    else
                        this.makeToast('Reject order failed', 'danger');
                }).catch(error => {
                    console.log(error);
                    this.makeToast('Reject order failed : ' + error.response.data, 'danger');
                });
            },
            /***********************/
            receive_order(item, index) {
                axios({
                    url: '/api/order_detail/receive_order',
                    data: {
                        order_detail_id: item.order_detail_id,
                        order_id: item.order_id,
                    },
                    method: 'post'
                }).then(res => {
                    if (res.data)
                        this.orders.splice(index, 1, res.data);
                    else
                        this.makeToast('Receive order failed', 'danger');
                }).catch(error => {
                    console.log(error);
                    this.makeToast('Receive order failed ', 'danger');
                });
            },
            /***********************/
            save_duty() {
                axios({
                    url: '/api/order_detail/save_duty',
                    data: this.duty_form,
                    method: 'post'
                }).then(res => {
                    this.form_errors = [];
                    console.log(res);
                    if (res.data) {
                        this.form_errors = [];
                        this.orders.push(res.data[0]);
                        this.$root.$emit('bv::hide::modal', 'duty_modal');
                    }
                    else {
                        this.makeToast('Duty create failed.', 'danger');
                        this.form_errors = ['Save error contact system administrator'];
                    }
                }).catch(error => {
                    this.form_errors = error.response.data;
                    this.makeToast('Error in connection', 'danger');
                });
            },
            /***********************/
            get_duty(item, index) {
                this.form_errors = [];
                this.duty_form = {
                    order_id: item.order_id,
                    venue_id_source: this.selected_venue[0].venue_id,
                    venue_id_target: item.venue_id,
                    asset_id: this.selected_venue[0].asset_id,
                    asset_count: 0,
                    asset_name: this.selected_venue[0].asset_name,
                };
                console.log('get_duty', item, index, this.selected_venue, this.duty_form);
                this.$root.$emit('bv::show::modal', 'duty_modal');
            },
            /***********************/
            reload_order() {
                this.get_full_order();
                bus.$emit('on_order_change', null);
            },
            /***********************/
            order_received(item) {
                this.orders_table_waiting = true;
                axios({
                    url: '/api/order_detail/receive_order',
                    data: {order_detail_id: item},
                    method: 'post'
                }).then(res => {
                    if (res.data)
                        this.orders[this.orders.indexOf(item)].status = res.data.status;
                    else
                        this.makeToast('Receiving order failed,' + res.data, 'danger');
                    this.orders_table_waiting = false;
                }).catch(error => {
                    console.log('order_received', error);
                    this.orders_table_waiting = false;
                    this.makeToast('Receiving order update connection failed,' + error.response.data, 'danger');
                });
            }
            /***********************/
        },
    }
</script>

<style scoped>

</style>