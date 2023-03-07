<script setup>
import Layout from '@/Layouts/Layout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import TextArea from '@/Components/TextArea.vue';
import InputError from '@/Components/InputError.vue';
import NavLink from '@/Components/NavLink.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, useForm } from '@inertiajs/inertia-vue3';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

const form = useForm({
    name: null,
    date: null,
    time: null,
    description: null,
})

const submit = () => {
    form.post(route('calendar.store'), {
        onFinish: () => {
            form.reset();
        }
    })
};
</script>

<template>
    <Head title="Créer un événement" />

    <Layout>
        <template #header>
            <FontAwesomeIcon icon="fa-solid fa-calendar-days" />&nbsp;
            Créer un événement
            &nbsp;<FontAwesomeIcon icon="fa-solid fa-calendar-days" />
        </template>

        <NavLink as="button" :href="route('calendar.index')" :active="route().current('calendar.index')">
            <FontAwesomeIcon icon="fa-solid fa-arrow-rotate-back" />&nbsp;Retour à l'agenda
        </NavLink>

        <div class="colorDiv rounded shadow-lg">
            <form @submit.prevent="submit">
                <div>
                    <InputLabel for="name" value="Nom de l'événement" />
                    <TextInput id="name" v-model="form.name" type="text" class="mt-1 block w-full form" required />
                    <InputError class="mt-2" :message="form.errors.name" />
                </div>

                <div class="mt-4 flex">
                    <div class="flex-auto">
                        <InputLabel for="date" value="Date" />
                        <TextInput id="date" v-model="form.date" type="date" class="mt-1 block w-full form" required />
                        <InputError class="mt-2" :message="form.errors.date" />
                    </div>
                    
                    <div class="flex-auto">
                        <InputLabel for="time" value="Heure" />
                        <TextInput id="time" v-model="form.time" type="time"    class="mt-1 block w-full form" required />
                        <InputError class="mt-2" :message="form.errors.time" />
                    </div>
                </div>

                <div class="mt-4">
                    <InputLabel for="description" value="Description" />
                    <TextArea id="description" v-model="form.description" class="mt-1 block w-full form" required rows="10" />
                    <InputError class="mt-2" :message="form.errors.description" />
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
