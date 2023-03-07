<script setup>
import Layout from '@/Layouts/Layout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import { ref } from 'vue';

const pro = ref(false);

const form = useForm({
    firstname: '',
    lastname: '',
    email: '',
    password: '',
    password_confirmation: '',
    rgpd: false,
    children: [{firstname: '', lastname: '', birthday: '',}],
    type: pro,
});

form.defaults({
    children: [{firstname: '', lastname: '', birthday: ''}]
})

function addChild(child) {
    form.children.push({firstname: '', lastname: '', birthday: ''});
};

function removeChild(index) {
    form.children.splice(index, 1);
};
</script>

<template>
    <Layout>
        <Head title="Inscription" />

        <fieldset class="border-2 border-[#212E53] rounded p-4">
            <legend>Vous êtes un·e :</legend>
            <div v-if="pro === false">
                <ul>
                    <p>
                        <input id="form" type="radio" checked />
                        <label class="ml-2 mr-8" for="form">Parent</label>
                    </p>
                    <p>
                        <input id="proForm" type="radio" @click="pro = true" />
                        <label class="ml-2 mr-8" for="proForm">Professionnel·le</label>
                    </p>
                </ul>
            </div>

            <div v-else>
                <ul>
                    <p>
                        <input id="form" type="radio" @click="pro = false" />
                        <label class="ml-2 mr-8" for="form">Parent</label>
                    </p>
                    <p>
                        <input id="proForm" type="radio" checked/>
                        <label class="ml-2 mr-8" for="proForm">Professionnel·le</label>
                    </p>
                </ul>
            </div>
        </fieldset>
        <div>
            <form @submit.prevent="form.post(route('register'), { onFinish: () => form.reset('password', 'password_confirmation') })">
                <fieldset class="border-2 border-[#212E53] rounded p-4">
                    <legend>Vos informations personnelles :</legend>
                    <div>
                        <InputLabel for="firstname" value="Prénom" />
                        <TextInput id="firstname" type="text" class="mt-1 block w-full form" v-model="form.firstname" required autofocus autocomplete="firstname" />
                        <InputError class="mt-2" :message="form.errors.firstname" />
                    </div>

                    <div class="mt-4">
                        <InputLabel for="lastname" value="Nom" />
                        <TextInput id="lastname" type="text" class="mt-1 block w-full form" v-model="form.lastname" required autocomplete="lastname" />
                        <InputError class="mt-2" :message="form.errors.lastname" />
                    </div>

                    <div class="mt-4">
                        <InputLabel for="email" value="Email" />
                        <TextInput id="email" type="email" class="mt-1 block w-full form" v-model="form.email" required autocomplete="username" />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <div class="mt-4">
                        <InputLabel for="password" value="Mot de passe" />
                        <TextInput id="password" type="password" class="mt-1 block w-full form" v-model="form.password" required autocomplete="new-password" />
                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>

                    <div class="mt-4">
                        <InputLabel for="password_confirmation" value="Confirmez votre mot de passe" />
                        <TextInput id="password_confirmation" type="password" class="mt-1 block w-full form" v-model="form.password_confirmation" required autocomplete="new-password" />
                        <InputError class="mt-2" :message="form.errors.password_confirmation" />
                    </div>
                </fieldset>

                <div v-if="pro === false" v-for="child, index in form.children">
                    <fieldset class="border-2 border-[#212E53] rounded p-4">
                        <legend>Enfant n°{{index+1}}</legend>
                        <div class="mt-4">
                            <InputLabel for="child_name" value="Prénom de l'enfant" />
                            <TextInput id="child_name" type="text" class="mt-1 block w-full form" v-model="child.firstname" />
                            <InputError class="mt-2" :message="form.errors.children" />
                        </div>

                        <div class="mt-4">
                            <InputLabel for="child_name" value="Nom de l'enfant" />
                            <TextInput id="child_name" type="text" class="mt-1 block w-full form" v-model="child.lastname" />
                            <InputError class="mt-2" :message="form.errors.children" />
                        </div>

                        <div class="mt-4">
                            <InputLabel for="child_birthday" value="Anniversaire de l'enfant" />
                            <TextInput id="child_birthday" type="date" class="mt-1 block w-full form" v-model="child.birthday" />
                            <InputError class="mt-2" :message="form.errors.children" />
                        </div>

                        <div class="mt-4">
                            <PrimaryButton v-if="form.children.length > 1" class="m-2" @click="removeChild(index)">Retirer l'enfant</PrimaryButton> 
                            <PrimaryButton class="m-2" @click="addChild(child)">Ajouter un enfant</PrimaryButton>
                        </div>
                    </fieldset>
                </div>

                <div>
                    <fieldset class="border-2 border-[#212E53] rounded p-4">
                        <legend>Politique de confidentialité</legend>
                        <div class="border-2 border-[#212E53] bg-white p-2 m-2 h-80 overflow-y-scroll">
                            <p>Conformément au Règlement Général sur la Protection des Données, nous vous informons que les informations recueillies par ce site sont celles renseignées lors de votre inscription : votre nom et prénom, votre adresse email, les noms, prénoms et date de naissance de vos enfants inscrits à la crèche (uniquement si vous êtes un parent).</p>
                            <br />
                            <p>Lorsque vous vous inscrivez sur notre site web, les informations que vous renseignez sont stockées dans notre base de données et ne seront utilisées que pour les raisons spécifiques mentionnées ci-dessous.</p>
                            <br />
                            <p>Nous recueillons ces informations uniquement afin d'assurer le bon fonctionnement de notre site web. Elles sont stockées dans notre base de données, hébergée par FirstHeberg.com, qui fournit également l'hébergement de notre site web. En aucun cas ces informations ne seront partagées avec ou vendues a un autre service.</p>
                            <br />
                            <p>Vous pouvez à tout moment modifier les informations suivantes : nom et prénom, adresse email.
                            Vous pouvez modifier les informations concernant vos enfants en contactant l'administrateur du site.
                            Vous pouvez décider à n'importe quel moment de supprimer toutes vos données personnelles depuis votre page de gestion de compte.</p>
                        </div>
                        <TextInput id="rgpd" type="checkbox" class="mt-1 border-black w-6 h-6 inline-block mr-2 form" v-model="form.rgpd" value="true" />
                        <InputLabel for="rgpd" class="inline" value="En cochant cette case, je reconnais avoir pris connaissance et être d'accord avec la politique de confidentialité du présent site." />
                        <InputError class="mt-2" :message="form.errors.rgpd" />
                    </fieldset>
                </div>

                <div class="flex items-center justify-end mt-4">
                    <Link :href="route('login')" class="underline text-gray-600 hover:text-gray-900">
                        Déjà inscrit ?
                    </Link>

                    <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        S'inscrire
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </Layout>
</template>
