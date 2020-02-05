<template>
    <div>
        <b-navbar toggleable="lg" type="dark" variant="info">
            <!--<b-navbar-brand href="http://assets.irsafam.local/home">NavBar</b-navbar-brand>-->
            <b-navbar-brand :to="{ name : 'dashboard' }">IRSAFAM Asset Management System</b-navbar-brand>

            <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

            <b-collapse id="nav-collapse" is-nav>
                <b-navbar-nav>
                    <b-nav-item-dropdown text="Data">
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

    export default {
        name: "main_navbar",
        data() {
            return {
                user: '',
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
        },
        created() {
            axios.get('/api/user')
                .then(response => {
                    this.user = response.data;
                    // console.log(response);
                }).catch(response => {
                this.user = null;
                console.log(response);
            });
        }
    }
</script>

<style scoped>

</style>