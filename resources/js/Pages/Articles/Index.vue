<script setup>
import Layout from '@/Layouts/Layout.vue';
import NavLink from '@/Components/NavLink.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import TextArea from '@/Components/TextArea.vue';
import InputError from '@/Components/InputError.vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { Head, useForm } from '@inertiajs/inertia-vue3';
import { DateTime } from 'luxon';
import { ref } from 'vue';

const props = defineProps([ 'articles', 'categoriesNurse', 'categoriesBoss', 'categories' ])

const form = useForm({
    name: null,
    content: null,
    category: null,
});

const data = ref([]);
const openEdit = ref([]);
const openDelete = ref([]);

props.articles.data.forEach(element => data.value.push([element.name, element.content, element.categories.name]))
props.articles.data.forEach(element => openEdit.value.push(false));
props.articles.data.forEach(element => openDelete.value.push(false));


function editSubmit(index, article) {
    form.name = data._rawValue[index][0];
    form.content = data._rawValue[index][1];
    form.category = data._rawValue[index][2];
    form.put(route('articles.update', { 'article': article }));
    openEdit._rawValue[index] = false;
}
</script>

<template>
    <Head title="Articles" />

    <Layout>
        <template #header>
            <FontAwesomeIcon icon="fa-solid fa-newspaper" />&nbsp;Articles&nbsp;<FontAwesomeIcon icon="fa-solid fa-newspaper" />
        </template>

        
        <NavLink as="button" v-if="$page.props.auth.user_role[0]['id'] >= '4'" :href="route('articles.create')" :active="route().current('articles.create')">
            <FontAwesomeIcon icon="fa-solid fa-pen-nib" />&nbsp;Rédiger un article
        </NavLink>
        <div class="colorDiv shadow-lg">
            <div v-if="articles.data.length <= 0">
                <p>Aucun article n'a encore été publié.</p>
            </div>

            <div v-else class="flex md:flex-row flex-col flex-wrap justify-around">
                <div v-for="article, index in articles.data" class="bg-[#e5e7e6] md:w-5/12 p-8 border-box rounded shadow-lg text-center m-4">
                    <a :href="route('articles.show', {'article': article})" :active="route().current('articles.show')">

                        <h3>{{ article.name }}</h3>
                        <small>Rédacteur.ice : {{ article.users['name'] }} | rédigé le {{ DateTime.fromISO(article.created_at).setLocale('fr').toLocaleString(DateTime.DATE_SHORT) }}</small>
                    </a>

                    <div v-if="$page.props.auth.user.id === article.users.id || $page.props.auth.user_role[0]['id'] >= 10" class="mt-2 flex flex-row justify-around">
                        <a as="button" @click="openEdit[index] = true" class="hover:cursor-pointer" title="Editer">
                            <FontAwesomeIcon icon="fa-solid fa-pen-to-square" />
                        </a>

                        <a as="button" @click="openDelete[index] = true" class="active:text-red-600 hover:cursor-pointer" title="Supprimer">
                            <FontAwesomeIcon icon="fa-solid fa-trash" />
                        </a>
                    </div>

                    <div class="pageModalBG" v-if="openEdit[index]" @click="openEdit[index] = false"></div>

                    <div class="flex flex-col justify-around shadow-lg pageModalEdit" v-if="openEdit[index]">                            
                            <div>
                                <InputLabel for="name" value="Titre de l'article" />
                                <TextInput id="name" v-model="data[index][0]" type="text" class="mt-1 block w-full form" />
                                <InputError class="mt-2" :message="form.errors[`articles.${index}.name`]" />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="content" value="Corps de l'article" />
                                <TextArea id="content" class="mt-1 block w-full form" v-model="data[index][1]" />
                                <InputError class="mt-2" :message="form.errors[`article.${index}.content`]" />
                            </div>

                            <div class="mt-4 flex flex-row justify-start" v-if="$page.props.auth.user_role[0]['id'] === 10">
                                <InputLabel class="basis-1/6 mt-4 textLeft" for="categories" value="Catégorie" />
                                <select class="basis-1/6" id="categories" v-model="data[index][2]">
                                    <option disabled value="">Choisissez une catégorie</option>
                                    <template v-for="category in categoriesNurse">
                                        <option :value="category.name">{{ category.name }}</option>
                                    </template>
                                </select> 
                            </div>

                            <div class="mt-4 flex flex-row justify-start" v-if="$page.props.auth.user_role[0]['id'] === 11">
                                <InputLabel class="basis-1/6 mt-4 textLeft" for="categories" value="Catégorie" />
                                <select class="basis-1/6" id="categories" v-model="data[index][2]">
                                    <option disabled value="">Choisissez une catégorie</option>
                                    <template v-for="category in categoriesBoss">
                                        <option :value="category.name">{{ category.name }}</option>
                                    </template>
                                </select> 
                            </div>

                            <div class="mt-4 flex flex-row justify-start" v-if="$page.props.auth.user_role[0]['id'] === 12">
                                <InputLabel class="basis-1/6 mt-4 textLeft" for="categories" value="Catégorie" />
                                <select class="basis-1/6" id="categories" v-model="data[index][2]">
                                    <template v-for="category in categories">
                                        <option :value="category.name">{{ category.name }}</option>
                                    </template>
                                </select> 
                            </div>

                            <div class="flex items-center justify-center mt-4">
                                <a class="basis-1/2 hover:cursor-pointer border-2 border-solid border-[#212E53] rounded mx-2" as="button" @click="openEdit[index] = false">
                                    <FontAwesomeIcon icon="fa-solid fa-x" />&nbsp;Annuler
                                </a>
                                <a class="basis-1/2 hover:cursor-pointer border-2 border-solid border-[#212E53] rounded mx-2" @click="editSubmit(index, article)">
                                    <FontAwesomeIcon icon="fa-solid fa-check" />Valider
                                </a>
                            </div>
                    </div>

                    <div class="pageModalBG" v-if="openDelete[index]" @click="openDelete[index] = false"></div>
                    
                    <div class="flex flex-col justify-around shadow-lg pageModalDelete" v-if="openDelete[index]">
                        <p class="grow text-red-600 mb-4">Êtes-vous sûr·e de vouloir supprimer cet article ? Cette action est définitive.</p>
                        <div class="flex flex-row justify-center">
                            <a class="basis-1/2 hover:cursor-pointer border-2 border-solid border-[#212E53] rounded mx-2" as="button" @click="openDelete[index] = false">
                                <FontAwesomeIcon icon="fa-solid fa-x" />&nbsp;Annuler
                            </a>
                            <a class="basis-1/2 hover:cursor-pointer border-2 border-solid border-[#212E53] rounded mx-2" as="button" :href="route('articles.delete', {'article': article})">
                                <FontAwesomeIcon icon="fa-solid fa-check" />&nbsp;Valider
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="articles.data.length > 6" class="paginate mt-6">
            <NavLink :disabled="!articles.prev_page_url" :href="articles.prev_page_url">Précédent</NavLink>
            <NavLink :disabled="!articles.next_page_url" :href="articles.next_page_url">Suivant</NavLink>
        </div>
    </Layout>
</template>
