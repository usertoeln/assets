<template>
    <div class="card">
        <div class="card-header">
            <h5>Venues</h5>
        </div>
        <div class="card-body">
            <div class="card-text">
                <b-form-group>
                    <b-form-checkbox-group
                            stacked
                            switches
                            :options="venues"
                            v-model="selected_venue"
                            value-field="venue_id"
                            text-field="vname">
                    </b-form-checkbox-group>
                </b-form-group>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import bus from '../../event_bus';

    export default {
        name: "venues",
        data() {
            return {
                venues_waiting: false,
                venues: [],
                selected_venue: null,
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
        },
        created: function () {
            this.venues_waiting = true;
            axios({
                url: '/api/get_venues',
                data: null,
                method: 'get'
            }).then(res => {
                // console.log(res.data);
                this.venues = res.data;
                this.makeToast(res.data.length + ' Venues load successfully', 'success');
                this.venues_waiting = false;
            }).catch(res => {
                console.log('get_users', res);
                this.makeToast('Venues loading failed', 'danger');
                this.venues_waiting = false;
            });
        },
        watch: {
            selected_venue: function (val) {
                this.$emit('on_select_venue', val);
                bus.$emit('on_change_venue', val);
            }
        },
    }
</script>

<style scoped>

</style>