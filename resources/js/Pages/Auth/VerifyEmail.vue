<script setup>
import { computed } from 'vue';
import Layout from '@/Layouts/Layout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';

const props = defineProps({
    status: String,
});

const form = useForm();

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(() => props.status === 'verification-link-sent');
</script>

<template>
    <Layout>
        <Head title="Vérification d'email" />

        <div class="mb-4 text-[1.4rem] text-[#212E53]">
            Merci pour votre inscription ! Avant de vous connecter, pouvez-vous confirmer votre adresse email en cliquant sur le lien que nous venons de vous envoyer ? Si vous n'avez reçu aucun mail, nous pouvons vous le renvoyer à nouveau.
        </div>

        <div class="mb-4 font-medium text-[1.4rem] text-green-600" v-if="verificationLinkSent" >
            Un nouveau lien de vérification vous a été envoyé à l'adresse email renseignée lors de votre inscription.
        </div>

        <form @submit.prevent="submit">
            <div class="mt-4 flex items-center justify-between">
                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Renvoyer le lien
                </PrimaryButton>

                <Link :href="route('logout')" method="post" as="button" class="underline text-[1.4rem] text-[#212E53] hover:text-gray-900">Déconnexion</Link>
            </div>
        </form>
    </Layout>
</template>
