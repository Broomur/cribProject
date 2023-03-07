<script setup>
import Layout from '@/Layouts/Layout.vue';
import NavLink from '@/Components/NavLink.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import TextArea from '@/Components/TextArea.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useForm, Head } from '@inertiajs/inertia-vue3';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { ref } from 'vue';

const props = defineProps(['users', 'roles', 'texts', 'introAlbum', 'welcomeDoc', 'educativeDoc']);

const textForm = useForm({
    content: null,
});

const data = ref([]);
const openEdit = ref([]);

props.texts.forEach(element => data.value.push([element.content]))
props.texts.forEach(element => openEdit.value.push(false));

const textSubmit = (index, text) => {
    textForm.content = data._rawValue[index][0];
    textForm.post(route('admin.update', {'hometext': text}));
    openEdit._rawValue[index] = false;
};

const albumForm = useForm({
    album: 'public-presentation',
    pictures: null,
});

const albumSubmit = () => {
    albumForm.post(route('albums.store'), {
        _method: 'put',
    })
}

const docForm = useForm({
    document: null,
})

let docName = null;
const docSubmit = () => {
    const renamedFile = new File([docForm.document], docName);
    docForm.document = renamedFile;
    docForm.post(route('documents.store'), {
        _method: 'put',
    });
}
</script>
 
<template>
    <Head title="Administration" />

    <Layout>
        <template #header>
            <FontAwesomeIcon icon="fa-solid fa-gear" />&nbsp;Gestion des utilisateurs&nbsp;<FontAwesomeIcon icon="fa-solid fa-gear" />
        </template>
        
        <div class="colorDiv rounded shadow-lg mb-8">
            <h2 class="text-center sectionTitle">Éditer les contenus de la page d'accueil</h2>

            <div class="flex flex-col flex-wrap justify-around mb-4">
                <div class="flex flex-row justify-between" v-for="text, index in texts">
                    <h3 v-if="index === 0" class="inline">Présentation de la crèche</h3>
                    <h3 v-if="index === 1" class="inline">Projet d'accueil</h3>
                    <h3 v-if="index === 2" class="inline">Projet éducatif</h3>
                    &nbsp;<a as="button" @click="openEdit[index] = true" class="hover:cursor-pointer" title="Éditer">
                        <FontAwesomeIcon icon="fa-solid fa-pen-to-square" />
                    </a>

                    <div class="pageModalBG" v-if="openEdit[index]" @click="openEdit[index] = false"></div>

                    <div class="flex flex-col justify-around shadow-lg pageModalEdit" v-if="openEdit[index]">  
                        <div>
                            <FontAwesomeIcon class="xMark hover:text-gray-600 active:text-red-600" icon="fa-solid fa-circle-xmark" @click="openEdit[index] = false" />
                            <InputLabel v-if="index === 0" for="content" value="Éditer le texte de présentation de la crèche" />
                            <InputLabel v-if="index === 1" for="content" value="Éditer le projet d'accueil" />
                            <InputLabel v-if="index === 2" for="content" value="Éditer le projet éducatif" />
                            <TextArea id="content" class="mt-1 block w-full form" v-model="data[index][0]" />
                            <InputError class="mt-2" :message="textForm.errors[`text.${index}.content`]" />

                            <div class="flex items-center justify-end mt-4">
                                <PrimaryButton class="ml-4" :class="{ 'opacity-25': textForm.processing }" @click="textSubmit(index, text)">
                                    <FontAwesomeIcon icon="fa-solid fa-check" />&nbsp;Valider
                                </PrimaryButton>
                            </div>
                        </div>

                        <div v-if="index === 0" class="mt-8">
                            <form @submit.prevent="albumSubmit">
                                <div v-if="albumForm.progress" >
                                    <progress :value="albumForm.progress.percentage" max="100">
                                        {{ albumForm.progress.percentage }}%
                                    </progress>
                                </div>

                                <div v-else>
                                    <div>
                                        <InputLabel for="uploadedPictures" value="Ajouter des photos à la présentation de la crèche" />
                                        <div class="flex flex-row mt-4">
                                            <TextInput id="uploadedPictures" type="file" accept="image/*" class="mt-1 basis-2/3 block w-full" @input="albumForm.pictures = $event.target.files" multiple required />
                                            <small class="basis-1/3 text-red-600 mt-2"><FontAwesomeIcon icon="fa-solid fa-triangle-exclamation" />&nbsp;Maximum 20 photos et 32Go à la fois.</small>
                                        </div>
                                        <InputError class="mt-2" :message="albumForm.errors.pictures" />
                                    </div>
                                    
                                    <div class="flex items-center justify-end mt-4">
                                        <PrimaryButton class="ml-4" :class="{ 'opacity-25': albumForm.processing }" :disabled="albumForm.processing">
                                            <FontAwesomeIcon icon="fa-solid fa-check" />&nbsp;Valider
                                        </PrimaryButton>
                                    </div>
                                </div>
                            </form>
                            
                            <div v-if="introAlbum[0]['pictures'].length >= 1" class="flex justify-center mt-4">
                                <a :href="route('albums.show', {'album': introAlbum})">
                                    <figure class="relativeFigure">
                                        <img class="box-border h-48 mt-4 border-2 border-[#e5e7e6]" :src="introAlbum[0]['pictures'][0]['path']" />
                                        <figcaption class="absoluteFigcaption">
                                            Voir les photos
                                        </figcaption>
                                    </figure>
                                </a>
                            </div>
                        </div>

                        <div v-if="index !== 0" class="mt-8">
                            <form @submit.prevent="docSubmit">
                                <div v-if="docForm.progress">
                                    <progress :value="docForm.progress.percentage" max="100">
                                        {{ docForm.progress.percentage }}%
                                    </progress>
                                </div>

                                <div v-else>
                                    <InputLabel for="uploadDoc" value="Joindre un document" />
                                    <div class="mt-4 flex flex-row">
                                        <TextInput id="uploadDoc" type="file" accept="pdf" @input="docForm.document = $event.target.files[0]" required />
                                        <InputError class="mt-2" :message="docForm.errors.document" />
                                    </div>
                                    <div class="mt-4 flex items-center justify-end">
                                        
                                        <PrimaryButton v-if="index === 1" class="ml-4" :class="{ 'opacity-25': docForm.processing }" :disabled="docForm.processing" @click="docName = 'public-welcome'">
                                            <FontAwesomeIcon icon="fa-solid fa-check" />&nbsp;Valider
                                        </PrimaryButton>

                                        
                                        <PrimaryButton v-if="index === 2" class="ml-4" :class="{ 'opacity-25': docForm.processing }" :disabled="docForm.processing" @click="docName = 'public-educative'">
                                            <FontAwesomeIcon icon="fa-solid fa-check" />&nbsp;Valider
                                        </PrimaryButton>
                                    </div>
                                </div>
                            </form>

                            <div class="flex flex-row justify-around mt-8" v-if="welcomeDoc">
                                <p>Gestion du document</p>
                                <PrimaryButton v-if="index === 1" as="button" :href="welcomeDoc.path" download>
                                    <FontAwesomeIcon icon="fa-solid fa-file-export" />&nbsp;Télécharger
                                </PrimaryButton>
                                <PrimaryButton  v-if="index === 1" as="button" :href="route('documents.delete', {'document': welcomeDoc})">
                                        <FontAwesomeIcon icon="fa-solid fa-trash" />&nbsp;Supprimer
                                </PrimaryButton>
                            </div>

                            <div class="flex flex-row justify-around" v-if="educativeDoc">
                                <PrimaryButton v-if="index === 2" as="button" :href="educativeDoc.path" download>
                                    <FontAwesomeIcon icon="fa-solid fa-file-export" />&nbsp;Télécharger
                                </PrimaryButton>
                                <PrimaryButton  v-if="index === 2" as="button" :href="route('documents.delete', {'document': educativeDoc})">
                                        <FontAwesomeIcon icon="fa-solid fa-trash" />&nbsp;Supprimer
                                </PrimaryButton>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="colorDiv rounded shadow-lg">
            <table class="adminTable">
                <thead>
                    <tr>
                        <th colspan="3">Utilisateurs</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="user in users.data">
                        <td>
                            {{ user.name }}
                        </td>

                        <td v-for="role in user.roles">
                            {{ role.name }}
                        </td>

                        <td>
                            <NavLink class="link" as="button" :href="route('admin.account', {'user': user})">
                                Gestion du compte
                            </NavLink>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div v-if="users.data.length >= 20" class="paginate mt-6">
                <NavLink as="button" :disabled="!users.prev_page_url" :href="users.prev_page_url">Précédent</NavLink>
                <NavLink as="button" :disabled="!users.next_page_url" :href="users.next_page_url">Suivant</NavLink>
            </div>
        </div>

        
    </Layout>
</template>
