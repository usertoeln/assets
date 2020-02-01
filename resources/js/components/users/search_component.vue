<template>
    <div class="row mt-5">
        <div class="col-12 mt-3 text-center text-info">
            <h1>User's Form Management</h1>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-header text-black bg-warning">
                    <h2>Search Box</h2>
                </div>
                <!--*******************-->
                <form class="mb-0" @submit.prevent="getData">
                    <div class="card-body">
                        <div class="card-text">
                            <div class="customSearchSection p-3">
                                <div class="row">
                                    <!--******* from_date *********-->
                                    <div class="form-group col-12 col-md-4">
                                        <label for="test_date_wri">From date:</label>
                                        <input type="date"
                                               class="form-control"
                                               v-model="data_form.from_date"
                                               id="from_date">
                                    </div>
                                    <!--******* to_date *********-->
                                    <div class="form-group col-12 col-md-4">
                                        <label for="to_date">To date:</label>
                                        <input type="date"
                                               class="form-control"
                                               v-model="data_form.to_date"
                                               id="to_date">
                                    </div>
                                    <!--******* mobile *********-->
                                    <div class="col-12 col-md-4">
                                        <b-form-group label="Mobile:">
                                            <b-form-input v-model="data_form.mobile"
                                                          label="Mobile:">
                                            </b-form-input>
                                        </b-form-group>
                                    </div>
                                    <!--******* country *********-->
                                    <div class="col-12 col-md-4">
                                        <b-form-group label="Country:">
                                            <b-form-input v-model="data_form.country"
                                                          label="Country:">
                                            </b-form-input>
                                        </b-form-group>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-center p-3 bg-warning">
                        <button type="submit" class="btn btn-success px-5">
                            Search
                        </button>
                    </div>
                </form>
                <!--*******************-->
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "search_component",
        /************************/
        props: ['table_waiting'],
        /************************/
        data() {
            return {
                information: [],
                data_form: {
                    from_date: '',
                    to_date: '',
                    mobile: null,
                    country: null,
                },
            }
        },
        /************************/
        methods: {
            /************************/
            makeToast(message = 'IFI message', variant = 'primary') {
                this.$bvToast.toast(message, {
                    title: 'IFI message status',
                    autoHideDelay: 5000,
                    variant: variant,
                    solid: true,
                })
            },
            /************************/
            getData() {
            },
            /************************/
        },
        /************************/
        watch: {
            data_form: {
                deep: true,
                handler: function (val) {
                    this.$emit('on_filter', val)
                }
            },
        },
        /************************/
        created: function () {

        }
    }
</script>

<style scoped>

</style>