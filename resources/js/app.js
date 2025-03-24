import './bootstrap';
import { createApp } from 'vue';
import { createRouter, createWebHistory } from 'vue-router';


import App from './App.vue';
import Index from './components/Index.vue';
import View from './components/View.vue';

// Define your routes
const routes = [
    {
        name: 'Index',
        path: '/',
        component: Index
    },
    {
        name: 'View',
        path: '/view/:number',
        component: View
    }
];

// Create the router instance
const router = createRouter({
    history: createWebHistory(),
    routes,
});

// Create and mount the root instance
createApp(App)
    .use(router)
    .mount('#app');
