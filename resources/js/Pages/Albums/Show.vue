<script setup>
import Layout from '@/Layouts/Layout.vue';
import NavLink from '@/Components/NavLink.vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { Head } from '@inertiajs/inertia-vue3';
import { ref } from 'vue';

const props = defineProps(['album']);

const openDelete = ref([]);

props.album.data[0]['pictures'].forEach(element => openDelete.value.push(false));
</script>
 
<template>
    <Head :title="album.data[0]['name']" />

    <Layout>
        <template v-if="album.data[0]['name'] !== 'public-presentation'" #header>
            <FontAwesomeIcon icon="fa-solid fa-camera-retro" />&nbsp;Album {{ album.data[0]['name'] }}&nbsp;<FontAwesomeIcon icon="fa-solid fa-camera-retro" />
        </template>
        <template v-else #header>
            <FontAwesomeIcon icon="fa-solid fa-camera-retro" />&nbsp;Présentation de la crèche&nbsp;<FontAwesomeIcon icon="fa-solid fa-camera-retro" />
        </template>
        
        <NavLink v-if="album.data[0]['name'] !== 'public-presentation'"  as="button" :href="route('albums.index')" :active="route().current('albums.index')">
            <FontAwesomeIcon icon="fa-solid fa-arrow-rotate-back" />&nbsp;Retourner aux albums
        </NavLink>
        <NavLink v-else as="button" :href="route('admin.index')" :active="route().current('admin.index')">
            <FontAwesomeIcon icon="fa-solid fa-arrow-rotate-back" />&nbsp;Retourner à l'administration
        </NavLink>

        <div class="colorDiv rounded shadow-lg">
            <div v-for="picture, index in album.data[0]['pictures']" class="mt-4">
                <figure class="relativeFigure">
                    <img :src="picture['path']" />

                    <div class="flex flex-col justify-around shadow-lg pageModalDelete" v-if="openDelete[index]">
                        <p v-if="album.data[0]['pictures'].length >= 2" class="grow text-red-600 mb-4">Êtes-vous sûr·e de vouloir supprimer cette photo ? Cette action est définitive.</p>
                        <p v-else class="grow text-red-600 mb-4">Êtes-vous sûr·e de vouloir supprimer cette photo ? Cette action est définitive. Cela supprimera également l'album {{ album.data[0]['name'] }}</p>
                        <div class="flex flex-row justify-center">
                            <a class="basis-1/2 hover:cursor-pointer border-2 border-solid border-[#212E53] rounded mx-2" as="button" @click="openDelete[index] = !openDelete[index]">
                                <FontAwesomeIcon icon="fa-solid fa-x" />&nbsp;Annuler
                            </a>
                            <a v-if="album.data[0]['pictures'].length >= 2" class="basis-1/2 hover:cursor-pointer border-2 border-solid border-[#212E53] rounded mx-2" as="button" :href="route('albums.picture.delete', {'album': album.data[0], 'picture': picture})">
                                <FontAwesomeIcon icon="fa-solid fa-check" />&nbsp;Valider
                            </a>
                            <a v-else class="basis-1/2 hover:cursor-pointer border-2 border-solid border-[#212E53] rounded mx-2" as="button" :href="route('albums.delete', {'album': album.data[0]})">
                                <FontAwesomeIcon icon="fa-solid fa-check" />&nbsp;Valider
                            </a>
                        </div>
                    </div>
                </figure>

                <div v-if="$page.props.auth.user.id === picture.user_id || $page.props.auth.user_id >= 10" class="mt-2 flex flex-row justify-end">
                    <a title="Supprimer" as="button" @click="openDelete[index] = !openDelete[index]" class="active:text-red-600 hover:cursor-pointer">
                        <FontAwesomeIcon icon="fa-solid fa-trash" />
                    </a>
                </div>

                <div class="pageModalBG" v-if="openDelete[index]" @click="openDelete[index] = !openDelete[index]"></div>
            </div>
            
        </div>

        <div v-if="album.data.length > 10" class="paginate mt-6">
            <NavLink as="button" :disabled="!album.prev_page_url" :href="album.prev_page_url">Précédent</NavLink>
            <NavLink as="button" :disabled="!album.next_page_url" :href="album.next_page_url">Suivant</NavLink>
        </div>
    </Layout>
</template>
