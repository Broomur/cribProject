<script setup>
import Layout from '@/Layouts/Layout.vue';
import NavLink from '@/Components/NavLink.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { Head, useForm } from '@inertiajs/inertia-vue3';
import { ref } from 'vue';

const props = defineProps(['albums']);

const form = useForm({
    album: null,
});

const data = ref([])
const openEdit = ref([]);
const openDelete = ref([]);

props.albums.data.forEach(element => data.value.push(element.album))
props.albums.data.forEach(element => openEdit.value.push(false));
props.albums.data.forEach(element => openDelete.value.push(false));


function editSubmit(index, album) {
    form.album = data._rawValue[index];
    form.post(route('albums.update', { 'album': album }));
    openEdit._rawValue[index] = false;
}
</script>
 
<template>
    <Head title="Albums" />

    <Layout>
        <template #header>
            <FontAwesomeIcon icon="fa-solid fa-camera-retro" />&nbsp;Albums&nbsp;<FontAwesomeIcon icon="fa-solid fa-camera-retro" />
        </template>

        <NavLink as="button" v-if="$page.props.auth.user_role[0]['id'] >= 8" :href="route('albums.create')" :active="route().current('albums.create')">
            <FontAwesomeIcon icon="fa-solid fa-file-arrow-up" />&nbsp;Importer des photos
        </NavLink>

        <div class="colorDiv rounded shadow-lg">
            <div v-if="albums.data.length <= 0">
                Aucun album n'a encore été créé.
            </div>

            <div v-else class="flex md:flex-row sm:flex-col flex-wrap justify-center">
                <div v-for="album, index in albums.data" class="flex-initial md:m-4 shrink-0">
                    <a :href="route('albums.show', {'album': album})" :active="route().current('albums.show')">
                        <figure class="relativeFigure">
                            <img class="box-border h-48 mt-4 border-2 border-[#e5e7e6]" :src="album.pictures[0]['path']" />
                            <figcaption class="absoluteFigcaption">
                                {{ album.name }}
                            </figcaption>
                        </figure>
                    </a>
                    <div v-if="$page.props.auth.user.id === album.user_id || $page.props.auth.user_role[0]['id'] >= 10" class="mt-2 flex flex-row justify-around">
                        <a as="button" @click="openEdit[index] = true" class="hover:cursor-pointer" title="Modifier">
                            <FontAwesomeIcon icon="fa-solid fa-pen-to-square" />
                        </a>

                        <a as="button" @click="openDelete[index] = true" class="active:text-red-600 hover:cursor-pointer" title="Supprimer">
                            <FontAwesomeIcon icon="fa-solid fa-trash" />
                        </a>
                    </div>

                    <div class="pageModalBG" v-if="openEdit[index]" @click="openEdit[index] = !openEdit[index]"></div>

                    <div class="flex flex-col justify-around shadow-lg pageModalEdit" v-if="openEdit[index]">                            
                        <div>
                            <InputLabel for="album" value="Nom de l'album" />
                            <TextInput id="album" v-model="data[index]" type="text" class="mt-1 block w-full form" />
                            <InputError class="mt-2" :message="form.errors[`albums.${index}.album`]" />
                        </div>

                        <div class="flex items-center justify-center mt-4">
                            <a class="basis-1/2 hover:cursor-pointer border-2 border-solid border-[#212E53] rounded mx-2" as="button" @click="openEdit[index] = !openEdit[index]">
                                <FontAwesomeIcon icon="fa-solid fa-x" />&nbsp;Annuler
                            </a>
                            <a class="basis-1/2 hover:cursor-pointer border-2 border-solid border-[#212E53] rounded mx-2" @click="editSubmit(index, album)">
                                <FontAwesomeIcon icon="fa-solid fa-check" />Valider
                            </a>
                        </div>
                    </div>

                    <div class="pageModalBG" v-if="openDelete[index]" @click="openDelete[index] = false"></div>
                    
                    <div class="flex flex-col justify-around shadow-lg pageModalDelete" v-if="openDelete[index]">
                        <p class="grow text-red-600 mb-4">Êtes-vous sûr·e de vouloir supprimer cet album ? Cette action est définitive.</p>
                        <div class="flex flex-row justify-center">
                            <a class="basis-1/2 hover:cursor-pointer border-2 border-solid border-[#212E53] rounded mx-2" as="button" @click="openDelete[index] = false">
                                <FontAwesomeIcon icon="fa-solid fa-x" />&nbsp;Annuler
                            </a>
                            <a class="basis-1/2 hover:cursor-pointer border-2 border-solid border-[#212E53] rounded mx-2" as="button" :href="route('albums.delete', {'album': album})">
                                <FontAwesomeIcon icon="fa-solid fa-check" />&nbsp;Valider
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="albums.data.length > 6" class="paginate mt-6">
            <NavLink as="button" :disabled="!albums.prev_page_url" :href="albums.prev_page_url">Précédent</NavLink>
            <NavLink as="button" :disabled="!albums.next_page_url" :href="albums.next_page_url">Suivant</NavLink>
        </div>
    </Layout>
</template>