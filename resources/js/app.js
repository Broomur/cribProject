import './bootstrap';
import '../css/app.css';
import '../css/style.css';

import { library } from '@fortawesome/fontawesome-svg-core';
import { faUser, faUserSecret, faUserPlus, faUserMinus, faUsersRays, faBriefcase, faCarrot, faCalendarDays, faCameraRetro, faNewspaper, faBowlFood, faAddressBook, faMap, faFileInvoice, faArrowRotateBack, faRightFromBracket, faGear, faStar, faPhone, faLocationDot, faAt, faCircleXmark, faFileImport, faFileExport, faBook, faFileArrowUp, faCheck, faX, faPenToSquare, faPenNib, faKitchenSet, faCalendarPlus, faTrash, faHouse, faHandsHoldingChild, faShapes, faCakeCandles, faKey, faTriangleExclamation } from '@fortawesome/free-solid-svg-icons';
library.add(faUser, faUserSecret, faUserPlus, faUserMinus, faUsersRays, faBriefcase, faCarrot, faCalendarDays, faCameraRetro, faNewspaper, faBowlFood, faAddressBook, faMap, faFileInvoice, faArrowRotateBack, faRightFromBracket, faGear, faBook, faStar, faPhone, faLocationDot, faAt, faCircleXmark, faFileImport, faFileExport, faFileArrowUp, faCheck, faX, faPenToSquare, faPenNib, faKitchenSet, faCalendarPlus, faTrash, faHouse, faHandsHoldingChild, faShapes, faCakeCandles, faKey, faTriangleExclamation);

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, app, props, plugin }) {
        return createApp({ render: () => h(app, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .mount(el);
    },
});

InertiaProgress.init({ color: '#18534F' });
