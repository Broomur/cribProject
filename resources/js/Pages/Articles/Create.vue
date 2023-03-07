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

const props = defineProps([ 'categoriesNurse', 'categoriesBoss', 'categories' ])

const form = useForm({
    name: '',
    content: '',
    category: null,
});

const submit = () => {
    form.post(route('articles.store'), {
        onFinish: () => {
            form.reset();
        }
    })
};
</script>
 
<template>
    <Head title="Rédiger un article" />

    <Layout>
        <template #header>
            <FontAwesomeIcon icon="fa-solid fa-newspaper" />&nbsp;Rédiger un article&nbsp;<FontAwesomeIcon icon="fa-solid fa-newspaper" />
        </template>

        <div>
            <NavLink as="button" :href="route('articles.index')" :active="route().current('articles.index')">
                <FontAwesomeIcon icon="fa-solid fa-arrow-rotate-back" />&nbsp;Retour aux articles
            </NavLink>
        </div>
        <div class="colorDiv shadow-lg">
            <form @submit.prevent="submit">
                <div>
                    <InputLabel for="name" value="Titre de l'article" />
                    <TextInput id="name" v-model="form.name" type="text" class="mt-1 block w-full form" required />
                    <InputError class="mt-2" :message="form.errors.name" />
                </div>
                
                <div class="mt-4">
                    <InputLabel for="content" value="Corps de l'article" />
                    <TextArea id="content" class="mt-1 block w-full form" v-model="form.content" required />
                    <InputError class="mt-2" :message="form.errors.content" />
                </div>

                <div class="mt-4 flex flex-row justify-start" v-if="$page.props.auth.user_role[0]['id'] === 10">
                    <InputLabel class="basis-1/6 mt-4 textLeft" for="categories" value="Catégorie" />
                    <select id="categories" class="basis-1/6" v-model="form.category">
                        <template v-for="category in categoriesNurse">
                            <option :value="category.name">{{ category.name }}</option>
                        </template>
                    </select> 
                </div>

                <div class="mt-4 flex flex-row justify-start" v-if="$page.props.auth.user_role[0]['id'] === 11">
                    <InputLabel class="basis-1/6 mt-4 textLeft" for="categories" value="Catégorie" />
                    <select id="categories" class="basis-1/6" v-model="form.category">
                        <template v-for="category in categoriesBoss">
                            <option :value="category.name">{{ category.name }}</option>
                        </template>
                    </select> 
                </div>

                <div class="mt-4 flex flex-row justify-start" v-if="$page.props.auth.user_role[0]['id'] === 12">
                    <InputLabel class="basis-1/6 mt-4 textLeft" for="categories" value="Catégorie" />
                    <select id="categories" class="basis-1/6" v-model="form.category">
                        <template v-for="category in categories">
                            <option :value="category.name">{{ category.name }}</option>
                        </template>
                    </select> 
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
