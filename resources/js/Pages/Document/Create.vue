<script setup>
import Layout from '@/Layouts/Layout.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import NavLink from '@/Components/NavLink.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, useForm } from '@inertiajs/inertia-vue3';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

const form = useForm({
    document: null,
});

const submit = () => {
    form.post(route('documents.store'), {
        _method: 'put',
    });
};
</script>

<template>
    <Head title="Importer un document" />

    <Layout>
        <template #header>
            <FontAwesomeIcon icon="fa-solid fa-file-invoice" />&nbsp;
            Importer un document
            &nbsp;<FontAwesomeIcon icon="fa-solid fa-file-invoice" />
        </template>

        <div>
            <NavLink :href="route('documents.index')" :active="route().current('documents.index')">
                <FontAwesomeIcon icon="fa-solid fa-arrow-rotate-back" />&nbsp;Retour aux documents
            </NavLink>
        </div>
        <div class="colorDiv rounded shadow-lg">
            <form @submit.prevent="submit">
                <div v-if="form.progress">
                    <progress :value="form.progress.perfentage" max="100">
                        {{ form.progress.percentage }}%
                    </progress>
                </div>

                <div v-else>
                    <div class="mt-4 flex flex-row">
                        <TextInput id="uploadDoc" type="file" accept="pdf" @input="form.document = $event.target.files[0]" required />
                        <InputError class="mt-2" :message="form.errors.document" />
                    </div>
                    <div class="mt-4 flex items-center justify-end">
                        <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            <FontAwesomeIcon icon="fa-solid fa-check" />&nbsp;Valider
                        </PrimaryButton>
                    </div>
                </div>
            </form>
        </div>
    </Layout>
</template>