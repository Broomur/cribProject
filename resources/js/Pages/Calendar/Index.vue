<script setup>
import Layout from '@/Layouts/Layout.vue';
import NavLink from '@/Components/NavLink.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import TextArea from '@/Components/TextArea.vue';
import InputError from '@/Components/InputError.vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { Head, useForm } from '@inertiajs/inertia-vue3';
import { DateTime } from 'luxon';
import { ref } from 'vue';

const props = defineProps([ 'calendar' ])

const form = useForm({
    name: null,
    date: null,
    time: null,
    description: null,
});

const data = ref([]);
const openEdit = ref([]);
const openDelete = ref([]);

props.calendar.data.forEach(element => data.value.push([element.name, element.date, DateTime.fromISO(element.time).setLocale('fr').toLocaleString(DateTime.TIME_24_SIMPLE), element.description]));
props.calendar.data.forEach(element => openEdit.value.push(false));
props.calendar.data.forEach(element => openDelete.value.push(false));

function editSubmit(index, event) {
    form.name = data._rawValue[index][0];
    form.date = data._rawValue[index][1];
    form.time = data._rawValue[index][2];
    form.description = data._rawValue[index][3];
    form.put(route('calendar.update', event));
    openEdit._rawValue[index] = false;
}

</script>

<template>
    <Head title="Agenda" />

    <Layout>
        <template #header>
            <FontAwesomeIcon icon="fa-solid fa-calendar-days" />&nbsp;
            Agenda
            &nbsp;<FontAwesomeIcon icon="fa-solid fa-calendar-days" />
        </template>

        <NavLink as="button" v-if="$page.props.auth.user_role[0]['name'] === 'commission animation' || $page.props.auth.user_role[0]['id'] > 9" :href="route('calendar.create')" :active="route().current('calendar.create')">
            <FontAwesomeIcon icon="fa-solid fa-calendar-plus" />&nbsp;Créer un événement
        </NavLink>
        <div class="colorDiv rounded shadow-lg">
            <div v-if="calendar.data.length <= 0">
                Aucun événement n'est encore prévu.
            </div>

            <div v-else class="flex md:flex-row flex-col justify-center">
                
                <article v-for="event, index in calendar.data" class="md:basis-1/2 bg-[#e5e7e6] p-4 border-box rounded shadow-lg text-center mx-4 mt-4">
                    <a :href="route('calendar.show', {'calendar': event})">
                        <h3>{{ event.name }}</h3>
                        <p>Le {{ DateTime.fromISO(event.date).setLocale('fr').toLocaleString(DateTime.DATE_FULL) }} à {{ DateTime.fromISO(event.time).setLocale('fr').toLocaleString(DateTime.TIME_24_SIMPLE) }}</p>
                        <p><em>Created by {{ event.users['name'] }}</em></p>
                    </a>


                    <div v-if="$page.props.auth.user.id === event.users.id || $page.props.auth.user_role[0]['id'] >= 10" class="mt-2 flex flex-row justify-around">
                        <a as="button" @click="openEdit[index] = true" class="hover:cursor-pointer" title="Editer">
                            <FontAwesomeIcon icon="fa-solid fa-pen-to-square" />
                        </a>

                        <a as="button" @click="openDelete[index] = true" class="active:text-red-600 hover:cursor-pointer" title="Supprimer">
                            <FontAwesomeIcon icon="fa-solid fa-trash" />
                        </a>
                    </div>

                    <div class="pageModalBG" v-if="openEdit[index]" @click="openEdit[index] = false"></div>

                    <div class="flex flex-col justify-around shadow-lg pageModalEdit" ref="openDelete" v-if="openEdit[index]">                            
                        <div>
                            <InputLabel for="name" value="Nom de l'événement" />
                            <TextInput id="name" v-model="data[index][0]" type="text" class="mt-1 block w-full form" required />
                            <InputError class="mt-2" :message="form.errors[`calendar.${index}.name`]" />
                        </div>

                        <div class="mt-4 flex">
                            <div class="flex-auto">
                                <InputLabel for="date" value="Date" />
                                <TextInput id="date" class="mt-1 block w-full form" type="date" v-model="data[index][1]" required />
                                <InputError class="mt-2" :message="form.errors[`calendar.${index}.date`]" />
                            </div>

                            <div class="flex-auto">
                                <InputLabel for="time" value="Heure" />
                                <TextInput id="time" class="mt-1 block w-full form" type="time" v-model="data[index][2]" required />
                                <InputError class="mt-2" :message="form.errors[`calendar.${index}.time`]" />
                            </div>

                        </div>

                        <div class="mt-4">
                            <InputLabel for="description" value="Description" />
                            <TextArea id="description" class="mt-1 block w-full form" v-model="data[index][3]" required />
                            <InputError class="mt-2" :message="form.errors[`calendar.${index}.description`]" />
                        </div>

                        <div class="flex items-center justify-center mt-4">
                            <a class="basis-1/2 hover:cursor-pointer border-2 border-solid border-[#212E53] rounded mx-2" as="button" @click="openEdit[index] = false">
                                <FontAwesomeIcon icon="fa-solid fa-x" />&nbsp;Annuler
                            </a>
                            <a class="basis-1/2 hover:cursor-pointer border-2 border-solid border-[#212E53] rounded mx-2" @click="editSubmit(index, event)">
                                <FontAwesomeIcon icon="fa-solid fa-check" />Valider
                            </a>
                        </div>
                    </div>

                    <div class="pageModalBG" v-if="openDelete[index]" @click="openDelete[index] = false"></div>
                        
                    <div class="flex flex-col justify-around shadow-lg pageModalDelete" ref="openDelete" v-if="openDelete[index]">
                        <p class="grow text-red-600 mb-4">Êtes-vous sûr·e de vouloir supprimer cet événement ? Cette action est définitive.</p>
                        <div class="flex flex-row justify-center">
                            <a class="basis-1/2 hover:cursor-pointer border-2 border-solid border-[#212E53] rounded mx-2" as="button" @click="openDelete[index] = false">
                                <FontAwesomeIcon icon="fa-solid fa-x" />&nbsp;Annuler
                            </a>
                            <a class="basis-1/2 hover:cursor-pointer border-2 border-solid border-[#212E53] rounded mx-2" as="button" :href="route('calendar.delete', {'calendar': event})">
                                <FontAwesomeIcon icon="fa-solid fa-check" />&nbsp;Valider
                            </a>
                        </div>
                    </div>
                </article>
            </div>
        </div>

        <div v-if="calendar.data.length > 6" class="paginate mt-6">
            <NavLink as="button" :disabled="!calendar.prev_page_url" :href="calendar.prev_page_url">Précédent</NavLink>
            <NavLink as="button" :disabled="!calendar.next_page_url" :href="calendar.next_page_url">Suivant</NavLink>
        </div>
    </Layout>
</template>
