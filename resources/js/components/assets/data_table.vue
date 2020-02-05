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
                    <b-button size="sm" class="btn btn-primary mr-2" style="width:70px"
                              @click="openModal($event.target,'u',row.item,row.index)">Edit
                    </b-button>
                    <b-button size="sm" @click="remove(row.item,row.index)" style="width:70px"
                              class="btn btn-danger mr-2">Remove
                    </b-button>

                </template>
                <!--******* Row detail ***************-->
                <template v-slot:row-details="row">
                    <div class="container">
                        <div class="row justify-content-center mb-3 ">
                            <div class="col-12 col-md-8 mt-3 text-center">
                                <button class="btn btn-danger w-25" @click="remove(row.item,row.index)">Remove</button>
                                <button class="btn btn-success w-25"
                                        @click="openModal($event.target,'u',row.item,row.index)">Edit
                                </button>
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
        <div class="col-12">
            <button class="btn btn-success my-5" @click="openModal($event.target,'i')">Add new asset</button>
        </div>
        <!--**************************-->
        <div>
            <b-modal :id="get_asset_modal_id(0)" :title="modal.header" hide-footer size="md">
                <form @submit.prevent="save_asset">
                    <!--***********  Date Filter *************-->
                    <!--******* asset_name *********-->
                    <div class="form-row">
                        <div class="form-group col-12">
                            <label for="asset_name">Asset name:</label>
                            <input type="text" required
                                   class="form-control"
                                   v-model="form.asset_name"
                                   id="asset_name">
                        </div>
                    </div>
                    <!--******* color *********-->
                    <div class="form-row">
                        <div class="form-group col-12">
                            <label for="color">Asset color:</label>
                            <input type="text"
                                   class="form-control"
                                   v-model="form.color"
                                   id="color">
                        </div>
                    </div>
                    <!--******* spec *********-->
                    <div class="form-row">
                        <div class="form-group col-12">
                            <label for="spec">Asset spec.:</label>
                            <input type="text"
                                   class="form-control"
                                   v-model="form.spec"
                                   id="spec">
                        </div>
                    </div>
                    <!--******* brand *********-->
                    <div class="form-row">
                        <div class="form-group col-12">
                            <label for="brand">Asset brand:</label>
                            <input type="text"
                                   class="form-control"
                                   v-model="form.brand"
                                   id="brand">
                        </div>
                    </div>
                    <!--***********  Buttons *************-->
                    <div class="row">
                        <div class="col-6">
                            <button class="btn btn-success d-flex ml-auto px-5">Save</button>
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
                    {key: 'id', sortable: true, label: 'ID'},
                    {key: 'asset_name', sortable: true, label: 'asset_name'},
                    {key: 'color', sortable: true, label: 'color'},
                    {key: 'spec', sortable: true, label: 'spec'},
                    {key: 'brand', sortable: true, label: 'brand'},
                    'Actions',
                ],
                modal: {
                    header: 'Asset',
                },
                selected_items: [],
                form: {
                    asset_name: '',
                    color: '',
                    spec: '',
                    brand: '',
                },
                save_type: '',
                selected: {item: null, index: null},
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
            // console.log(this.information);
        },
        /**********************************/
        methods: {
            /***********************/
            openModal(target, m_type, item = null, index = 0) {
                // m_type = 'i' || 'u';
                this.save_type = m_type;
                if (m_type === 'i') {
                    this.modal.header = 'Define a new asset';
                    this.form = {
                        asset_name: '',
                        color: '',
                        spec: '',
                        brand: '',
                    };
                    this.selected = {item: null, index: null};
                } else {
                    this.modal.header = 'Edit asset';
                    this.form = {
                        asset_name: item.asset_name,
                        color: item.color,
                        spec: item.spec,
                        brand: item.brand,
                        id: item.id,
                    };
                    this.selected = {item: item, index: index};
                }
                this.$root.$emit('bv::show::modal', 'asset_modal_id', target);
            },
            /***********************/
            onRowSelected(item) {
                this.selected_items = item;
                this.$emit('select_rows', {item_length: this.selected_items.length, items: this.selected_items});
            },
            /***********************/
            onFiltered(filteredItems) {
                // Trigger pagination to update the number of buttons/pages due to filtering
                this.totalRows = filteredItems.length;
                this.currentPage = 1
            },
            /***********************/
            get_asset_modal_id(id = 0) {
                if (id)
                    return 'asset_modal_id_' + id;
                else {
                    return 'asset_modal_id';
                }
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
            /*************************************/

            save_asset() {
                if (this.save_type === 'i') {
                    /****** insert mode *******/
                    axios({
                        url: '/api/asset/insert',
                        data: this.form,
                        method: 'post'
                    }).then(res => {
                        // console.log('/api/asset/insert', res);
                        this.information.push(res.data);
                        this.makeToast('Asset create successfully', 'success');
                    }).catch(error => {
                        this.makeToast('Asset create failed : ' + error.response.data, 'danger');
                    });
                } else {
                    /****** update mode *******/
                    axios({
                        url: '/api/asset/update',
                        data: this.form,
                        method: 'post'
                    }).then(res => {
                        // console.log(res);
                        if (res.data === 1) {
                            this.information[this.selected.index].asset_name = this.form.asset_name;
                            this.information[this.selected.index].color = this.form.color;
                            this.information[this.selected.index].spec = this.form.spec;
                            this.information[this.selected.index].brand = this.form.brand;
                            this.makeToast('Asset update successfully', 'success');
                        }
                        else
                            this.makeToast('Asset update failed', 'danger');
                    }).catch(error => {
                        console.log(error);
                        this.makeToast('Asset update failed : ' + error.response.data, 'danger');
                    });
                }
                this.$root.$emit('bv::hide::modal', 'asset_modal_id');
            },
            /***********************/
            remove(item, index) {
                axios({
                    url: '/api/asset/remove',
                    data: item,
                    method: 'post'
                }).then(res => {
                    // console.log('remove asset ', item, index);
                    if (res.data) {
                        this.information.splice(this.information.indexOf(item), 1);
                        this.makeToast('Asset deleted successfully', 'success');
                    }
                    else
                        this.makeToast('Asset delete failed', 'danger');
                }).catch(error => {
                    console.log(error);
                    this.makeToast('Asset delete failed : ' + error.response.data, 'danger');
                });
            },
            /***********************/
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