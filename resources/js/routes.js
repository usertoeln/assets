import groups from './components/groups/index'
import users from './components/users/index'
import dashboard from './components/dashboard/index'
import main_content from './components/panel/main_content'
import venues from './components/venues/index'
import assets from './components/assets/index'
import cities from './components/cities/index'
import warehouse_inventory from './components/warehouse_inventory/index'
import orders from './components/orders/index'


const routes = [
    {
        path: '/spa', component: main_content, children: [
            {path: 'dashboard', name: 'dashboard', component: dashboard},
            {path: 'groups', name: 'groups', component: groups},
            {path: 'users', name: 'users', component: users},
            {path: 'venues', name: 'venues', component: venues},
            {path: 'assets', name: 'assets', component: assets},
            {path: 'cities', name: 'cities', component: cities},
            {path: 'warehouse_inventory', name: 'warehouse_inventory', component: warehouse_inventory},
            {path: 'orders', name: 'orders', component: orders},
        ]
    },
];

export default routes;