<template>
    <div class="card">
        <div class="card-header">
            <h5>Orders information</h5>
        </div>
        <div class="card-body">
            <div class="card-text">
                <!--******** table Header*********-->
                <p v-html="get_order_header"></p>
                <!--******** table *********-->
                <b-table v-if="data_exists" hover :items="orders" class="bg-white col-12"
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
                                <div class="col-12 col-md-10 bg-light p-3 rounded">
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
                                <!--****** buttons ***********-->
                                <div class="col-10 col-md-2">
                                    <div class="text-right">
                                        <div class="col-12">
                                            <b-button size="sm" @click="remove(row.item,row.index)"
                                                      style="width:140px"
                                                      :class="[get_buttons_state('duty',row.item) , 'btn','btn-success','mt-3']">
                                                New duty
                                            </b-button>
                                        </div>

                                        <div class="col-12">
                                            <b-button size="sm" @click="remove(row.item,row.index)"
                                                      style="width:140px"
                                                      :class="[get_buttons_state('pack',row.item),'btn' ,'btn-warning','mt-3']">
                                                Packaging
                                            </b-button>
                                        </div>

                                        <div class="col-12">
                                            <b-button size="sm" @click="remove(row.item,row.index)" style="width:140px"
                                                      :class="[get_buttons_state('sent',row.item),'btn', 'btn-info','mt-3']">
                                                Sent items
                                            </b-button>
                                        </div>

                                        <div class="col-12">
                                            <b-button size="sm" @click="remove(row.item,row.index)" style="width:140px"
                                                      :class="[get_buttons_state('received',row.item),'btn', 'btn-success','mt-3']">
                                                Asset received
                                            </b-button>
                                        </div>

                                        <div class="col-12">
                                            <b-button size="sm" @click="remove(row.item,row.index)" style="width:140px"
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
                                            <b-button size="sm" @click="remove(row.item,row.index)" style="width:140px"
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
                <!--********  new request button *********-->
                <div class="col-12 text-center">
                    <button :class="['btn', 'btn-success']"
                            style="width:140px"
                            @click="openModal($event.target,'i')">Add new order
                    </button>
                </div>
            </div>
        </div>
        <!--**************************-->
        <div>
            <b-modal id="order_modal" :title="order_modal.header" hide-footer size="md">
                <form @submit.prevent="save_order">
                    <!--******* venue_id *********-->
                    <div class="form-row d-none">
                        <b-form-select
                                v-model="order_form.venue_id"
                                :options="venues"
                                class="mb-3"
                                value-field="id"
                                text-field="vname"
                        ></b-form-select>
                    </div>
                    <!--******* asset_id *********-->
                    <div class="form-row">
                        <div class="form-group col-12">
                            <label for="asset_id">Asset:</label>
                            <input type="text"
                                   class="form-control"
                                   v-model="order_form.asset_id"
                                   id="asset_id">
                        </div>
                    </div>
                    <!--******* asset_count *********-->
                    <div class="form-row">
                        <div class="form-group col-12">
                            <label for="turn_over_asset_count">Asset count:</label>
                            <input type="number"
                                   class="form-control"
                                   v-model="order_form.asset_count"
                                   id="turn_over_asset_count">
                        </div>
                    </div>
                    <!--******* descp *********-->
                    <div class="form-row">
                        <div class="form-group col-12">
                            <label for="turn_over_descp">Description :</label>
                            <input type="text"
                                   class="form-control"
                                   v-model="order_form.descp"
                                   id="turn_over_descp">
                        </div>
                    </div>
                    <!--******  Buttons *******-->
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
    export default {
        /**********************/
        name: "orders",
        /**********************/
        data() {
            return {
                assets: [],
                data_exists: true,
                asset: {},
                user_scopes: ['remove', 'edit', 'reject', 'received', 'sent', 'pack', 'duty'],
                orders: [],
                table_waiting: false,
                orders_fields: [
                    '#',
                    {key: 'order_detail_id', sortable: true, label: 'ID'},
                    {key: 'order_detail_created_at', sortable: true, label: 'Order Date'},
                    {key: 'vname', sortable: true, label: 'Venue'},
                    {key: 'asset_name', sortable: true, label: 'Asset'},
                    {key: 'asset_count', sortable: true, label: 'Count'},
                    {key: 'status', sortable: true, label: 'Status'},
                    {key: 'rec_type', sortable: true, label: 'Type'},
                    'Actions',
                ],
                filter: null,
                filterOn: [],
                totalRows: 0,
                filtered_items: {},
                save_type: '',
                selected: {item: null, index: null},
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
                let header = null;
                return 'You have xxx record';
            },
        },
        /**********************/
        created: function () {
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
        },
        /************************/
        methods: {
            /***********************/
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
            get_full_order() {
                this.orders_table_waiting = true;
                axios({
                    url: '/api/get_full_order',
                    data: null,
                    method: 'post'
                }).then(res => {
                    console.log('get_data', res.data);
                    this.orders = res.data;
                    this.makeToast(res.data.length + ' orders load successfully', 'success');
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
                    console.log('openModal', this.selected_items);
                    this.form = {
                        order_id: this.selected_items[0].order_id,
                        venue_id: this.selected_items[0].venue_id,
                        order_detail_id: this.selected_items[0].order_detail_id,
                        asset_id: this.selected_items[0].asset_id,
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
                    console.log('remove asset ', item, index);
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
                    case 'remove':
                        rec_type_state = (rec_type === 'debit' || rec_type === 'credit')
                        status_state = (status === 'request')
                        break;
                    case 'edit':
                        rec_type_state = (rec_type === 'debit' || rec_type === 'credit');
                        status_state = (status !== 'reject' && status !== 'received');
                        break;
                    case 'reject':
                        rec_type_state = (rec_type === 'debit');
                        status_state = (status === 'request');
                        break;
                    case 'received':
                        rec_type_state = (rec_type === 'debit');
                        status_state = (status === 'request')
                        break;
                    case 'sent':
                        rec_type_state = (rec_type === 'credit')
                        status_state = (status === 'packaging')
                        break;
                    case 'pack':
                        rec_type_state = (rec_type === 'credit')
                        status_state = (status === 'duty')
                        break
                    case 'duty':
                        rec_type_state = (rec_type === 'debit')
                        status_state = (status === 'request')
                        break;
                }
                btn_class = (rec_type_state && status_state) ? null : 'd-none';
                return btn_class;
            },
            /***********************/
        },
    }
</script>

<style scoped>

</style>