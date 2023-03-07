<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/inertia-vue3';
import Layout from '@/Layouts/Layout.vue';
import NavLink from '@/Components/NavLink.vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

const props = defineProps(['documents'])

const openDelete = ref([]);

props.documents.data.forEach(element => openDelete.value.push(false));
</script>

<template>
    <Head title="Documents" />

    <Layout>
        <template #header>
            <FontAwesomeIcon icon="fa-solid fa-file-invoice" />&nbsp;
            Documents
            &nbsp;<FontAwesomeIcon icon="fa-solid fa-file-invoice" />
        </template>

            <div>
                <NavLink v-if="$page.props.auth.user_role[0]['id'] >= 10" as="button" :href="route('documents.create')">
                    <FontAwesomeIcon icon="fa-solid fa-file-import"></FontAwesomeIcon>&nbsp;Importer un document
                </NavLink>
            </div>
            
        <div class="colorDiv rounded shadow-lg">
            <div v-if="documents.data.length <= 0">
                <p>Aucun document n'est en ligne.</p>
            </div>

            <div v-else class="flex md:flex-row flex-col flex-wrap justify-around">
                <article class="bg-[#e5e7e6] md:w-5/12 p-8 border-box rounded shadow-lg text-center m-4" v-for="document, index in documents.data">
                    <p>{{ document.name }}</p>
                    <div class="mt-2 flex flex-row justify-around">
                        <a as="button" :href="document.path" download title="Télécharger">
                            <FontAwesomeIcon icon="fa-solid fa-file-export" />
                        </a>
                        <a v-if="$page.props.auth.user_role[0]['id'] >= 10" as="button" @click="openDelete[index] = true" class="active:text-red-600 hover:cursor-pointer" title="Supprimer">
                            <FontAwesomeIcon icon="fa-solid fa-trash" />
                        </a>
                    </div>

                    <div class="pageModalBG" v-if="openDelete[index] === true" @click="openDelete[index] = false"></div>

                    <div class="flex flex-col justify-around shadow-lg pageModalDelete" ref="openDelete" v-if="openDelete[index] === true">
                        <p class="grow text-red-600 mb-4">Êtes-vous sûr·e de vouloir supprimer ce document ? Cette action est définitive.</p>
                        <div class="flex flex-row justify-center">
                            <a class="basis-1/2 hover:cursor-pointer border-2 border-solid border-[#212E53] rounded mx-2" as="button" @click="openDelete[index] = false">
                                <FontAwesomeIcon icon="fa-solid fa-x" />&nbsp;Annuler
                            </a>
                            <a class="basis-1/2 hover:cursor-pointer border-2 border-solid border-[#212E53] rounded mx-2" as="button" :href="route('documents.delete', {'document': document})">
                                <FontAwesomeIcon icon="fa-solid fa-check" />&nbsp;Valider
                            </a>
                        </div>
                    </div>
                </article>
            </div>
        </div>

        <div v-if="documents.data.length >= 6" class="paginate mt-6">
            <NavLink as="button" :disabled="!documents.prev_page_url" :href="documents.prev_page_url">Précédent</NavLink>
            <NavLink as="button" :disabled="!documents.next_page_url" :href="documents.next_page_url">Suivant</NavLink>
        </div>
    </Layout>
</template>