<script setup>
import Layout from '@/Layouts/Layout.vue';
import NavLink from '@/Components/NavLink.vue';
import { Head } from '@inertiajs/inertia-vue3';
import { DateTime } from 'luxon';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

const props = defineProps([ 'article', 'categories' ])


</script>

<template>
    <Head :title="article[0]['name']" />

    <Layout>
        <template #header>
            <FontAwesomeIcon icon="fa-solid fa-newspaper" />&nbsp;{{ article[0]['name'] }}&nbsp;<FontAwesomeIcon icon="fa-solid fa-newspaper" />
        </template>

        
        <NavLink as="button" :href="route('articles.index')" :active="route().current('articles.index')">
            <FontAwesomeIcon icon="fa-solid fa-arrow-rotate-back" />&nbsp;Retour aux articles
        </NavLink>
     
        <div class="colorDiv rounded p-4 m-4">

            <article>
                <h3>{{ article[0]['name'] }}</h3>
                <small>Rédacteur.ice : {{ article[0]['users']['name'] }} | catégorie : {{ article[0]['categories']['name'] }} | rédigé le {{ DateTime.fromISO(article[0]['created_at']).setLocale('fr').toLocaleString(DateTime.DATE_SHORT) }}</small>
                <p class="text-justify mt-8">{{ article[0]['content'] }}</p>
            </article>
        </div>

    </Layout>
</template>
