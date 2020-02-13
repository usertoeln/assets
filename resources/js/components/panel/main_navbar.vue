<template>
    <div>
        <b-navbar toggleable="lg" type="dark" variant="info">
            <!--<b-navbar-brand href="http://assets.irsafam.local/home">NavBar</b-navbar-brand>-->
            <b-navbar-brand :to="{ name : 'dashboard' }">IRSAFAM Asset Management System</b-navbar-brand>

            <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

            <b-collapse id="nav-collapse" is-nav>
                <b-navbar-nav>
                    <b-nav-item-dropdown text="Data" v-if="user_access.view_menu_data">
                        <b-dropdown-item :to="{ name : 'users' }">Users</b-dropdown-item>
                        <b-dropdown-item :to="{ name : 'groups' }">Groups</b-dropdown-item>
                        <b-dropdown-item :to="{ name : 'assets' }">Assets</b-dropdown-item>
                        <b-dropdown-item :to="{ name : 'venues' }">Venues</b-dropdown-item>
                        <b-dropdown-item :to="{ name : 'cities' }">Cities</b-dropdown-item>
                    </b-nav-item-dropdown>
                </b-navbar-nav>

                <b-navbar-nav>
                    <b-nav-item-dropdown text="Operations">
                        <b-dropdown-item :to="{ name : 'warehouse_inventory' }">Warehouse Inventory</b-dropdown-item>
                        <b-dropdown-item :to="{ name : 'orders' }">Order Management</b-dropdown-item>
                    </b-nav-item-dropdown>
                </b-navbar-nav>

                <!-- Right aligned nav items -->
                <b-navbar-nav class="ml-auto">
                    <b-nav-item-dropdown right>
                        <!-- Using 'button-content' slot -->
                        <template v-slot:button-content>
                            <em>{{ user.name }}</em>
                        </template>
                        <b-dropdown-item href="#">Profile</b-dropdown-item>
                        <b-dropdown-item @click="logout">Sign Out</b-dropdown-item>
                    </b-nav-item-dropdown>
                </b-navbar-nav>
            </b-collapse>
        </b-navbar>
    </div>
</template>

<script>
    import axios from 'axios';
    import bus from '../../event_bus';

    export default {
        name: "main_navbar",
        data() {
            return {
                user: '',
                user_access: {
                    view_menu_order_management: false,
                    view_menu_warehouse_inventory: false,
                    view_menu_operations: false,
                    view_menu_data: false,
                    order_access: [],
                },
            }
        },
        methods: {
            logout: () => {
                axios.post('/logout')
                    .then(response => {
                        window.location.href = response;
                    }).catch(response => {
                    console.log(response);
                });
            },
            get_user_access() {
                axios.get('/api/get_user_access')
                    .then(response => {
                        this.user_access = response.data;
                        bus.$emit('get_user_access', response.data);
                        // console.log('main_navbar get_user_access bus.emit', response.data);
                    }).catch(response => {
                    console.log(response);
                });
            },
        },
        mounted() {
        },
        created() {
            axios.get('/api/user')
                .then(response => {
                    this.user = response.data;
                    this.get_user_access();
                }).catch(response => {
                this.user = null;
                console.log(response);
            });
        }
    }
</script>

<style scoped>

</style>