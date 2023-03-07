<script setup>
import { ref } from 'vue';
import Layout from '@/Layouts/Layout.vue';
import { Head, useForm } from '@inertiajs/inertia-vue3';
import { DateTime } from 'luxon';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import NavLink from '@/Components/NavLink.vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

const props = defineProps(['user', 'roles']);

const activeUser = useForm({
    role: 'parent',
});

const setRole = useForm({
    role: null,
});

const deleteUser = useForm({});

const openValidate = ref(false);
const openSetRole = ref(false);
const openDelete = ref(false);
</script>
 
<template>
    <Head :title="user.name" />

    <Layout>
        <template #header>
            <FontAwesomeIcon icon="fa-solid fa-gear" />&nbsp;Gestion du compte : {{ user.name }}&nbsp;<FontAwesomeIcon icon="fa-solid fa-gear" />
        </template>

        <NavLink :href="route('admin.index')">
            <FontAwesomeIcon icon="fa-solid fa-arrow-rotate-back" />&nbsp;Retour au tableau des utilisateurs
        </NavLink>

        <div class="colorDiv rounded shadow-lg">
            <div>
                <ul class="list-disc">
                    <li>Nom : {{ user.name }}</li>
                    <li>Inscrit le {{ DateTime.fromISO(user.created_at).setLocale('fr').toLocaleString(DateTime.DATE_FULL) }}</li>
                    <li>Role : {{ user.roles[0]['name'] }}</li>
                    <li>Email : <a class="link" href="mailto:user.email">{{ user.email }}</a></li>
                    <li v-if="user.children.length > 0">Enfant(s) :
                        <ul>
                            <li class="indent-8" v-for="child in user.children">{{ child.firstname }} {{ child.lastname }}, né·e le {{ DateTime.fromISO(child.birthday).setLocale('fr').toLocaleString(DateTime.DATE_FULL) }}</li>
                        </ul>
                    </li>
                </ul>
            </div>

            <div class="flex flex-col justify-between mt-8" v-if="openSetRole === false && openDelete === false && openValidate === false">
                
                <PrimaryButton class="grow" v-if="user.roles[0]['name'] === 'compte inactif' && user.type === 'parent'" @click="openValidate = true">Activer le compte</PrimaryButton>
                
                <PrimaryButton class="grow" v-if="openSetRole === false && user.roles[0]['name'] === 'compte inactif' && user.type === 'pro'" @click="openSetRole = true">Activer le compte</PrimaryButton>

                <PrimaryButton class="grow mt-6" v-if="openDelete === false && user.roles[0]['name'] !== 'admin'" @click="openDelete = true">Supprimer le compte</PrimaryButton>
                
            </div>

            <div class="mt-8" v-else>

                <div v-if="openValidate === true && user.roles[0]['name'] === 'compte inactif'">
                    <p>Avant de valider une inscription, assurez-vous que l'utilisateur est bien un·e usager·ère de la crèche.</p>
                    <div class="flex flex-row mt-4">
                        <PrimaryButton v-if="user.roles[0]['name'] === 'compte inactif' && user.type === 'parent'" @click="activeUser.put(route('admin.activate_parent', user))" class="basis-2/4 ml-2">
                            Activer le compte
                        </PrimaryButton>
                        <PrimaryButton @click="openValidate = false" class="basis-2/4 ml-2">
                            Annuler
                        </PrimaryButton>
                    </div>
                </div>

                <form v-if="openSetRole === true && user.roles[0]['name'] === 'compte inactif' && user.type === 'pro'" @submit.prevent="setRole.put(route('admin.activate_pro', user), { onSuccess: () => open = false })">
                    <p>Avant de valider une inscription, assurez-vous d'avoir vérifié que l'utilisateur est bien un·e employé·e de la crèche</p>
                    <template v-for="role in roles">
                        <input :id="role.id" type="radio" :value="role.name" v-model="setRole.role" />
                        <label class="ml-2 mr-28" :for="role.id">{{ role.name }}</label>
                    </template>
                    <div class="flex flex-row mt-4">
                        <PrimaryButton :disabled="setRole.processing" class="basis-2/4 ml-2">
                            Valider
                        </PrimaryButton>
                        <PrimaryButton @click="openSetRole = false" class="basis-2/4 ml-2">
                            Annuler
                        </PrimaryButton>
                    </div>
                </form>

                <form v-if="openDelete === true" @submit.prevent="deleteUser.put(route('admin.destroy', user), { onSuccess: () => open = false })">
                    <p class="text-red-600">Êtes-vous sûr de vouloir supprimer le compte {{ user.name }} ? Cette action est irréversible.</p>

                    <div class="flex flex-row mt-4">
                        <PrimaryButton :disabled="deleteUser.processing" class="basis-2/4 ml-2">
                            Supprimer ce compte
                        </PrimaryButton>
                        <PrimaryButton @click="openDelete = false" class="basis-2/4 ml-2">
                            Annuler
                        </PrimaryButton>
                    </div>

                </form>
            </div>
        </div>
    </Layout>
</template>