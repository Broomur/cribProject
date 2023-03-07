<script setup>
import Layout from '@/Layouts/Layout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/inertia-vue3';

defineProps({
    status: String,
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <Layout>
        <Head title="Mot de passe oublié" />

        <div class="mb-4 text-[1.4rem] text-[#212E53]">
            Vous avez oublié votre mot de passe ? Renseignez l'adresse email liée à votre compte, nous vous enverrons un mail contenant un lien vous permettant de définir un nouveau mot de passe.
        </div>

        <div v-if="status" class="mb-4 font-medium text-[1.4rem] text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="Email" />
                <TextInput id="email" type="email" class="mt-1 block w-full form" v-model="form.email" required autofocus autocomplete="username" />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Confirmer
                </PrimaryButton>
            </div>
        </form>
    </Layout>
</template>
