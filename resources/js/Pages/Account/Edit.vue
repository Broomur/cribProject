<script setup>
import Layout from '@/Layouts/Layout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import { Head, useForm } from '@inertiajs/inertia-vue3';
import { ref } from 'vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

const props = defineProps(['user']);

const userForm = useForm({
    firstname: props.user.name.split(' ')[0],
    lastname: props.user.name.split(' ')[1],
    email: props.user.email,
});

const passwordForm = useForm({
    old_passwd: null,
    new_passwd: null,
    verify_passwd: null,
});

const deleteForm = useForm({
    id: props.user.id,
})

const userEdit = ref(false);

const passwdEdit = ref(false);

const deleteUser = ref(false);

function editSubmit(user) {
    userForm.put(route('user.update', user));
    userEdit.value = !userEdit.value;
}

function editPasswd(user) {
    passwordForm.put(route('user.passwd', user))
    passwdEdit.value = false;
    userEdit.value = !userEdit.value;
}

function destroy(user) {
    deleteForm.put(route('user.destroy', user));
}
</script>
 
<template>
    <Head :title="user.name" />

    <Layout>
        <template #header>
           <FontAwesomeIcon icon="fa-solid fa-user" />&nbsp;Paramètres du compte : {{ user['name'] }}&nbsp;<FontAwesomeIcon icon="fa-solid fa-user"/>
        </template>

        <div class="colorDiv rounded shadow-lg">
            <div>
                <h2 class="h2">Informations du compte</h2>
                <div class="flex flex-row justify-between">
                    <ul>
                        <li>{{ user.name }}</li>
                        <li>{{ user.email }}</li>
                    </ul>
                    <a as="button" @click="userEdit = !userEdit" title="Modifier les informations du compte" class="hover:cursor-pointer text-blue-600 hover:text-indigo-600 underline underline-offset-2">
                        <FontAwesomeIcon icon="fa-solid fa-pen-to-square" />
                    </a>
                </div>
            </div>
        </div>

        <div class="pageModalBG" v-if="userEdit" @click="userEdit = !userEdit; passwdEdit = false"></div>
        
        <div class="flex flex-col justify-around shadow-lg pageModalEdit" v-if="userEdit">
            <div>
                <InputLabel for="firstname" value="Prénom" />
                <TextInput id="firstname" v-model="userForm.firstname" type="text" class="mt-1 block w-full form" required />
                <InputError class="mt-2" :message="userForm.errors.firstname" />
            </div>
            <div>
                <InputLabel for="lastname" value="Nom" />
                <TextInput id="lastname" v-model="userForm.lastname" type="text" class="mt-1 block w-full form" required />
                <InputError class="mt-2" :message="userForm.errors.lastname" />
            </div>
            <div>
                <InputLabel for="email" value="Email" />
                <TextInput id="email" v-model="userForm.email" type="email" class="mt-1 block w-full form" required />
                <InputError class="mt-2" :message="userForm.errors.email" />
            </div>

            

            <div v-if="!passwdEdit && !deleteUser" class="flex flex-row justify-evenly">
                <a as="button" @click="passwdEdit = !passwdEdit" class="md:mr-64 hover:cursor-pointer text-blue-600 hover:text-indigo-600 underline underline-offset-2" title="Modifier le mot de passe">
                    <FontAwesomeIcon icon="fa-solid fa-key" />
                </a>
                <a as="button" v-if="$page.props.auth.user_role[0]['name'] !== 'admin'" @click="deleteUser = !deleteUser" class="md:ml-64 hover:cursor-pointer text-red-600 hover:text-red-600 underline underline-offset-2" title="Supprimer le compte">
                    <FontAwesomeIcon icon="fa-solid fa-user-minus" />
                </a>
            </div>

            <div v-else class="flex sm:flex-col md:flex-row justify-around">
                <div>
                    <InputLabel for="old_passwd" value="Mot de passe actuel" />
                    <TextInput id="old_passwd" v-model="passwordForm.old_passwd" type="password" class="mt-1 block w-full form" required />
                    <InputError class="mt-2" :message="passwordForm.errors.old_passwd" />
                </div>

                <div>
                    <InputLabel for="new_passwd" value="Nouveau mot de passe" />
                    <TextInput id="new_passwd" v-model="passwordForm.new_passwd" type="password" class="mt-1 block w-full form" required />
                    <InputError class="mt-2" :message="passwordForm.errors.new_passwd" />
                </div>

                <div>
                    <InputLabel for="passwd_control" value="Confirmation" />
                    <TextInput id="passwd_control" v-model="passwordForm.verify_passwd" type="password" class="mt-1 block w-full form" required />
                    <InputError class="mt-2" :message="passwordForm.errors.verify_passwd" />
                </div>
            </div>

            <div v-if="deleteUser" class="flex flex-col">
                <p class="text-red-600 textCenter">Voulez-vous vraiment supprimer votre compte ? Cette action est irréversible.</p>
                <div class="flex flex-row">
                    <a as="button" @click="deleteUser = false" class="mr-64 hover:cursor-pointer text-blue-600 hover:text-indigo-600 underline underline-offset-2">
                        <FontAwesomeIcon icon="fa-solid fa-x" />&nbsp;Annuler
                    </a>
                    <a as="button" @click="destroy(user)" class="ml-64 hover:cursor-pointer text-blue-600 hover:text-red-600 underline underline-offset-2">
                        <FontAwesomeIcon icon="fa-solid fa-check" />&nbsp;Valider
                    </a>
                </div>
            </div>

            <div v-if="!passwdEdit" class="flex items-center justify-center mt-4">
                <a class="textCenter basis-1/2 hover:cursor-pointer border-2 border-solid border-[#212E53] rounded mx-2" as="button" @click="userEdit = !userEdit; passwdEdit = false">
                    <FontAwesomeIcon icon="fa-solid fa-x" />&nbsp;Annuler
                </a>
                <a class="textCenter basis-1/2 hover:cursor-pointer border-2 border-solid border-[#212E53] rounded mx-2" @click="editSubmit(user)">
                    <FontAwesomeIcon icon="fa-solid fa-check" />&nbsp;Valider
                </a>
            </div>

            <div v-else class="flex items-center justify-center mt-4">
                <a class="textCenter basis-1/2 hover:cursor-pointer border-2 border-solid border-[#212E53] rounded mx-2" as="button"
                @click="passwdEdit = false">
                    <FontAwesomeIcon icon="fa-solid fa-x" />&nbsp;Annuler
                </a>
                <a class="textCenter basis-1/2 hover:cursor-pointer border-2 border-solid border-[#212E53] rounded mx-2" @click="editPasswd(user)">
                    <FontAwesomeIcon icon="fa-solid fa-check" />&nbsp;Valider
                </a>
            </div>
        </div>
    </Layout>
</template>