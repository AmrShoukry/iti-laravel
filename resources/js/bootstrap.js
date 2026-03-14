import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';

import PostDetailsModal from './components/PostDetailsModal.vue';
import axios from 'axios';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import '../css/app.css';

createApp({})
    .component('PostDetailsModal', PostDetailsModal)
    .mount('#appModal');


    createInertiaApp({
        resolve: (name) => {
            const pages = import.meta.glob('./Pages/**/*.vue', { eager: true });
            const page = pages[`./Pages/${name}.vue`];

            if (!page) {
                console.error('Inertia page not found:', name);
                throw new Error(`Page not found: ${name}`);
            }

            return page.default;
        },
        setup({ el, App, props, plugin }) {
            createApp({ render: () => h(App, props) })
                .use(plugin)
                .mount(el);
        },
})
