<script setup>
import Layout from '@/Layouts/Layout.vue';
import NavLink from '@/Components/NavLink.vue';
import { Head } from '@inertiajs/inertia-vue3';
import { DateTime } from 'luxon';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

const props = defineProps([ 'menus' ])

</script>

<template>
    <Head title="Menu" />

    <Layout>
        <template #header>
            <FontAwesomeIcon icon="fa-solid fa-carrot" />&nbsp;
            Menus
            &nbsp;<FontAwesomeIcon icon="fa-solid fa-carrot" />
        </template>

        <NavLink as="button" v-if="$page.props.auth.user_role[0]['id'] === 8 || $page.props.auth.user_role[0]['id'] >= 10" :href="route('menus.create')" :active="route().current('menus.create')">
            <FontAwesomeIcon icon="fa-solid fa-pen-to-square" />&nbsp;Editer les menus
        </NavLink>
        <div class="colorDiv rounded shadow-lg">
            <table class="table-auto border-spacing-4">
                <thead>
                    <tr>
                        <th></th>
                        <th>P'tits Mickeys</th>
                        <th>BB Mickeys</th>
                        <th>Goûter</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="menu in menus">
                            <td>{{ DateTime.fromFormat(menu.weekday, 'EEEE').setLocale('fr').toFormat('EEEE') }}</td>
                            <td>{{ menu.PM_menu }}</td>
                            <td>{{ menu.BB_menu }}</td>
                            <td>{{ menu.snack }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

    </Layout>
</template>
