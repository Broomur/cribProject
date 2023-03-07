<script setup>
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import Layout from '@/Layouts/Layout.vue';
import NavLink from '@/Components/NavLink.vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { Head, useForm } from '@inertiajs/inertia-vue3';
import { ref } from 'vue';

const props = defineProps(['team']);

const form = useForm({
    picture: null,
});

const edit = ref([]);
const data = ref([]);

props.team.data.forEach(element => edit.value.push(false));
props.team.data.forEach(element => data.value.push(null));

function submit(index, team) {
    form.picture = data._rawValue[index];
    form.post(route('team.update', {'team': team}));
    edit._rawValue[index] = false;
}
</script>
 
<template>
    <Head title="Trombinoscope" />

    <Layout>
        <template #header>
            <FontAwesomeIcon icon="fa-solid fa-user" />&nbsp;
            Trombinoscope
            &nbsp;<FontAwesomeIcon icon="fa-solid fa-user" />
        </template>

        <NavLink as="button" v-if="$page.props.auth.user_role[0]['id'] >= 9" :href="route('team.create')" class="hover:cursor-pointer">
            <FontAwesomeIcon icon="fa-solid fa-file-arrow-up" />&nbsp;Importer une photo
        </NavLink>

        <div v-if="team.data.length <= 0" class="colorDiv rounded shadow-lg">
            Le trombinoscope est vide...
        </div>

        <div v-else class="colorDiv rounded shadow-lg">
            <div class="flex md:flex-row sm:flex-col flex-wrap justify-center rounded">
                <div v-for="team, index in team.data">
                    <figure class="relativeFigure">
                        <img class="box-border h-auto mt-4 border-2 border[#e5e7e6]" :src="team.path" />
                        <figcaption class="absoluteFigcaption">
                            {{ team.name }}
                        </figcaption>
                    </figure>

                    <a v-if="$page.props.auth.user.id === team.user_id || $page.props.auth.user_role[0]['id'] >= 10" as="button" class="hover:cursor-pointer"  @click="edit[index] = true" title="Editer">
                        <FontAwesomeIcon icon="fa-solid fa-pen-to-square"/>
                    </a>
                    <div class="pageModalBG" v-if="edit[index] === true" @click="edit[index] = false"></div>

                    <div class="flex flex-col justify-around shadow-lg pageModalEdit" v-if="edit[index] === true">
                        <div class="mt-4 flex flex-row">
                            <InputLabel class="basis-1/4 mt-4" for="picture" value="Photo" />
                            <TextInput id="picture" type="file" accept="image/*" class="basis-3/4 block w-full" @input="data[index] = $event.target.files[0]" required />
                            <InputError class="mt-2" :message="form.errors.picture" />
                        </div>
                        <div class="flex flex-row justify-evenly">
                            <a as="button" class="grow hover:cursor-pointer border-2 border-solid border-[#212E53] rounded mx-2" @click="edit[index] = false">
                                <FontAwesomeIcon icon="fa-solid fa-x" />&nbsp;
                                Annuler
                            </a>
                            <a as="button" class="grow hover:cursor-pointer border-2 border-solid border-[#212E53] rounded mx-2" @click="submit(index, team)">
                                <FontAwesomeIcon icon="fa-solid fa-check" />&nbsp;
                                Valider
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        

        <div v-if="team.data.length > 10" class="paginate mt-6">
            <NavLink as="button" :disabled="!team.prev_page_url" :href="team.prev_page_url">Précédent</NavLink>
            <NavLink as="button" :disabled="!team.next_page_url" :href="team.next_page_url">Suivant</NavLink>
        </div>
    </Layout>
</template>
