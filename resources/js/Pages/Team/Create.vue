<script setup>
import Layout from '@/Layouts/Layout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import NavLink from '@/Components/NavLink.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, useForm } from '@inertiajs/inertia-vue3';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

const props = defineProps([ 'users' ]);

const form = useForm({
    name: null,
    picture: null,
});

const submit = () => {
    form.post(route('team.store'))
}
</script>

<template>
    <Head title="Ajouter une photo" />

    <Layout>
        <template #header>
            <FontAwesomeIcon icon="fa-solid fa-user-plus" />&nbsp;
            Ajouter une photo au trombinoscope
            &nbsp;<FontAwesomeIcon icon="fa-solid fa-user-plus" />
        </template>
        

        <NavLink as="button" :href="route('team.index')" :active="route().current('team.index')">
            <FontAwesomeIcon icon="fa-solid fa-arrow-rotate-back" />&nbsp;
            Retour au trombinoscope
        </NavLink>

        <div class="colorDiv rounded shadow-lg">
            <form @submit.prevent="submit">
                <div class="mt-4 flex flex-row">
                    <TextInput id="picture" type="file" accept="image/*" class="basis-4/4 block w-full" @input="form.picture = $event.target.files[0]" required />
                    <InputError class="mt-2" :message="form.errors.picture" />
                </div>

                <div v-if="$page.props.auth.user_role[0]['id'] >= 10" class="mt-4 flex flex-row justify-start">
                    <InputLabel class="basis-1/6 mt-4 textLeft" for="users" value="Professionel·le" />
                    <select id="users" class="basis-3/6" v-model="form.name">
                        <option v-if="users.length <= 0" disabled></option>
                        <template v-for="user in users">
                            <option :value="user.name">{{ user.name }}</option>
                        </template>
                    </select> 
                </div>

                <div v-else hidden>
                    <input type="text" :value="$page.props.auth.user.name" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        <FontAwesomeIcon icon="fa-solid fa-check" />&nbsp;Valider
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </Layout>
</template>