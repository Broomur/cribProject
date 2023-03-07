<script setup>
import Layout from '@/Layouts/Layout.vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { Head } from '@inertiajs/inertia-vue3';
import { DateTime } from 'luxon';
import { computed, onMounted, onUnmounted, ref } from 'vue';
import ModalPopup from '@/Components/ModalPopup.vue';

function useBreakpoints() {
  let windowWidth = ref(window.innerWidth)

  const onWidthChange = () => windowWidth.value = window.innerWidth
  onMounted(() => window.addEventListener('resize', onWidthChange))
  onUnmounted(() => window.removeEventListener('resize', onWidthChange))

  const type = computed(() => {
    if (windowWidth.value < 640) return 'xs'
    if (windowWidth.value >= 640 && windowWidth.value < 1024) return 'md'
    if (windowWidth.value >= 1024) return 'lg'
    return null; // This is an unreachable line, simply to keep eslint happy.
  })

  const width = computed(() => windowWidth.value)

  return { width, type }
}

const { width, type } = useBreakpoints()


const props = defineProps([ 'calendar', 'album', 'menus', 'article', 'birthdays' ]);

</script>

<template>
    <Head title="Accueil" />

    <Layout>
        <template #header>
            <FontAwesomeIcon icon="fa-solid fa-star" />&nbsp;
            Les dernières actualités
            &nbsp;<FontAwesomeIcon icon="fa-solid fa-star" />
        </template>
        <main>
            <section class="dashboardSection">
                <div>
                    <article v-if="birthdays.length >= 1" class="dashboardArticle rounded shadow-lg">
                        <h2>
                            <FontAwesomeIcon icon="fa-solid fa-cake-candles" />&nbsp;
                            Les anniversaires du mois
                            &nbsp;<FontAwesomeIcon icon="fa-solid fa-cake-candles" />
                        </h2>
                        <ul>
                            <template v-for="birthday in birthdays">
                                <li>{{ birthday.firstname }} le {{ DateTime.fromISO(birthday.birthday).setLocale('fr').toFormat('d LLLL') }}</li>
                            </template>
                        </ul>
                    </article>
                </div>
                <div>
                    <article v-if="album" class="dashboardArticle rounded shadow-lg">  
                        <h2><FontAwesomeIcon icon="fa-solid fa-camera-retro" /><br />Dernier album ajouté</h2>
                        <a :href="route('albums.show', album)" :active="route().current('albums.show')">
                            <figure class="relativeFigure">
                                <img class="absoluteImg box-border h-48 p-2 border-2" :src="album.pictures[0]['path']" />
                                <figcaption class="absoluteFigcaption">{{ album.name }}</figcaption>
                            </figure>
                        </a>
                    </article>
                </div>
                <div>
                    <article v-if="calendar" class="dashboardArticle rounded shadow-lg">
                        <h2><FontAwesomeIcon icon="fa-solid fa-calendar-days" /><br />Prochain événement</h2>
                        <p>{{ calendar.name }}</p>
                        <p>le {{ DateTime.fromISO(calendar.date).setLocale('fr').toLocaleString(DateTime.DATE_FULL) }} à {{ DateTime.fromISO(calendar.time).setLocale('fr').toLocaleString(DateTime.TIME_SIMPLE) }}</p>
                        <p class="whitespace-normal">{{ calendar.description }}</p>
                    </article>
                </div>

                <div>
                    <article v-if="article" class="dashboardArticle rounded shadow-lg">
                        <h2><FontAwesomeIcon icon="fa-solid fa-newspaper" /><br />Dernier article publié</h2>
                        <p>{{ article.name }}</p>
                        <p class="whitespace-normal">{{ article.content }}</p>
                        <small>Rédigé par {{ article.users['name'] }} le {{ DateTime.fromISO(article.created_at).setLocale('fr').toLocaleString(DateTime.DATE_SHORT) }} | catégorie : {{ article.categories['name'] }}</small>
                    </article>
                </div>

                <div>
                    <article class="dashboardArticle rounded shadow-lg">
                        <h2><FontAwesomeIcon icon="fa-solid fa-carrot" /><br />Les menus de la semaine</h2>
                        <table v-if="type !== 'xs'"  class="dashboardTable table-auto border-spacing-4">
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

                        <ModalPopup v-else v-for="menu in menus">
                            <template #button>
                                {{ DateTime.fromFormat(menu.weekday, 'EEEE').setLocale('fr').toFormat('EEEE') }}
                            </template>
                            <template #content>
                                <table class="table-auto border-spacing-4">
                                    <thead>
                                        <tr>
                                            <th colspan="2">{{ DateTime.fromFormat(menu.weekday, 'EEEE').setLocale('fr').toFormat('EEEE') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>P'tits Mickeys</td>
                                            <td>{{ menu.PM_menu }}</td>
                                        </tr>
                                        <tr>
                                            <td>BB Mickeys</td>
                                            <td>{{ menu.BB_menu }}</td>
                                        </tr>
                                        <tr>
                                            <td>Goûter</td>
                                            <td>{{ menu.snack }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </template>
                        </ModalPopup>
                    </article>
                </div>
            </section>
        </main>
        
    </Layout>
</template>
