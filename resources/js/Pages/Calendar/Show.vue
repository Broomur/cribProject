<script setup>
import Layout from '@/Layouts/Layout.vue';
import NavLink from '@/Components/NavLink.vue';
import { Head} from '@inertiajs/inertia-vue3';
import { DateTime } from 'luxon';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

const props = defineProps([ 'event' ])

</script>

<template>
    <Head :title="event.name" />

    <Layout>
        <template #header>
            <FontAwesomeIcon icon="fa-solid fa-calendar-days" />&nbsp;
           {{ event[0]['name'] }}
           &nbsp;<FontAwesomeIcon icon="fa-solid fa-calendar-days" />
        </template>

        <div>
            <NavLink as="button" :href="route('calendar.index')" :active="route().current('calendar.index')">
                <FontAwesomeIcon icon="fa-solid fa-arrow-rotate-back" />&nbsp;Retour à l'agenda
            </NavLink>
        </div>
    
        <div class="colorDiv rounded shadow-lg">
            <article>
                <h3>{{ event[0]['name'] }}</h3>
                <small>Créé par : {{ event[0]['users']['name'] }}, le {{ DateTime.fromISO(event[0]['created_at']).setLocale('fr').toLocaleString(DateTime.DATE_SHORT) }}</small>
                <p class="mt-6">{{ event[0]['description'] }}</p>
                
            </article>
        </div>

    </Layout>
</template>
