<script setup>
import Layout from '@/Layouts/Layout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import NavLink from '@/Components/NavLink.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, useForm } from '@inertiajs/inertia-vue3';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

const form = useForm({
    album: null,
    pictures: null,
});

const submit = () => {
    form.post(route('albums.store'), {
        _method: 'put',
    })
};
</script>
 
<template>
    <Head title="Importer des photos" />

    <Layout>
        <template #header>
            <FontAwesomeIcon icon="fa-solid fa-camera-retro" />&nbsp;Importer des photos&nbsp;<FontAwesomeIcon icon="fa-solid fa-camera-retro" />
        </template>
        <NavLink as="button" :href="route('albums.index')" :active="route().current('albums.index')">
           <FontAwesomeIcon icon="fa-solid fa-arrow-rotate-back" />&nbsp;Retour aux albums
        </NavLink>


        <div class="colorDiv rounded shadow-lg">   
            <form @submit.prevent="submit">
                
                <div v-if="form.progress" >
                    <progress :value="form.progress.percentage" max="100">
                        {{ form.progress.percentage }}%
                    </progress>
                </div>
                <div v-else>
                    <div>
                        <div class="flex flex-row">
                            <TextInput id="uploadedPictures" type="file" accept="image/*" class="mt-1 basis-2/3 block w-full" @input="form.pictures = $event.target.files" multiple required />
                            <small class="basis-1/3 text-red-600 mt-2"><FontAwesomeIcon icon="fa-solid fa-triangle-exclamation" />&nbsp;Maximum 20 photos et 32Go à la fois.</small>
                        </div>
                        <InputError class="mt-2" :message="form.errors.pictures" />
                    </div>

                    <div class="mt-4 flex flex-row">
                        <InputLabel for="album" value="Nom de l'album" class="basis-1/5 mt-6" />
                        <TextInput id="album" type="text" class="mt-1 block w-full basis-4/5 form" v-model="form.album" required />
                        <InputError class="mt-2" :message="form.errors.album" />
                    </div>

                    
                    <div class="flex items-center justify-end mt-4">
                        <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            <FontAwesomeIcon icon="fa-solid fa-check" />&nbsp;Valider
                        </PrimaryButton>
                    </div>
                </div>
            </form>
        </div>          

    </Layout>
</template>
