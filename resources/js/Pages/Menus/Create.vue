<script setup>
import { computed, onMounted, onUnmounted, ref } from 'vue'; 
import Layout from '@/Layouts/Layout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue'
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue'; 
import NavLink from '@/Components/NavLink.vue';
import { Head, useForm } from '@inertiajs/inertia-vue3';
import { DateTime } from 'luxon';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

function useBreakpoints() {
  let windowWidth = ref(window.innerWidth)

  const onWidthChange = () => windowWidth.value = window.innerWidth
  onMounted(() => window.addEventListener('resize', onWidthChange))
  onUnmounted(() => window.removeEventListener('resize', onWidthChange))

  const type = computed(() => {
    if (windowWidth.value < 640) return 'xs'
    if (windowWidth.value >= 640 && windowWidth.value < 1024) return 'md'
    if (windowWidth.value >= 1024) return 'lg'
    return null; // This is an unreachable line, simply to keep eslint happy.
  })

  const width = computed(() => windowWidth.value)

  return { width, type }
}

const { width, type } = useBreakpoints()

const props = defineProps([ 'menus' ])

const form = useForm({
    menus: [
        {
            PM_menu: props.menus[0]['PM_menu'], 
            BB_menu: props.menus[0]['BB_menu'], 
            snack: props.menus[0]['snack'], 
        },
        {
            PM_menu: props.menus[1]['PM_menu'],
            BB_menu: props.menus[1]['BB_menu'],
            snack: props.menus[1]['snack'],
        },
        {
            PM_menu: props.menus[2]['PM_menu'],
            BB_menu: props.menus[2]['BB_menu'],
            snack: props.menus[2]['snack'],
        },
        {
            PM_menu: props.menus[3]['PM_menu'],
            BB_menu: props.menus[3]['BB_menu'],
            snack: props.menus[3]['snack'],
        },
        {
            PM_menu: props.menus[4]['PM_menu'],
            BB_menu: props.menus[4]['BB_menu'],
            snack: props.menus[4]['snack'],
        },
    ]
});

</script>

<template>
    <Head title="Editer le menu" />

    <Layout>
        <template #header>
            <FontAwesomeIcon icon="fa-solid fa-carrot" />&nbsp;
            Edition des menus
            &nbsp;<FontAwesomeIcon icon="fa-solid fa-carrot" />
        </template>

        
        <NavLink as="button" :href="route('menus.index')" :active="route().current('menus.index')">
            <FontAwesomeIcon icon="fa-solid fa-arrow-rotate-back" />&nbsp;Retour à la liste des menus
        </NavLink>
     
        <div class="colorDiv rounded shadow-lg">
            <form @submit.prevent="form.post(route('menus.store'))">
                    <table class="table-auto border-spacing-4">
                        <thead>
                            <tr>
                                <th></th>
                                <th>P'tits Mickeys</th>
                                <th>BB Mickeys</th>
                                <th>Goûter</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="menu, index in menus">
                                    <td v-if="type !== 'xs'">
                                        {{ DateTime.fromFormat(menu.weekday, 'EEEE').setLocale('fr').toFormat('EEEE') }}
                                    </td>
                                    <td v-else>
                                        {{ DateTime.fromFormat(menu.weekday, 'EEEE').setLocale('fr').toFormat('EEE') }}
                                    </td>
                                    <td>
                                        <TextInput type="text" maxlength="110" class="my-2 block w-full" v-model="form.menus[index].PM_menu"/>
                                        <InputError class="mt-2 form" :message="form.errors[`menus.${index}.PM_menu`]" />
                                    </td>
                                    <td>
                                        <TextInput type="text" maxlength="110" class="my-2 block w-full" v-model="form.menus[index].BB_menu" />
                                        <InputError class="mt-2 form" :message="form.errors[`menus.${index}.BB_menu`]" />
                                    </td>
                                    <td>
                                        <TextInput type="text" maxlength="110" class="my-2 block w-full" v-model="form.menus[index].snack" />
                                        <InputError class="mt-2 form" :message="form.errors[`menus.${index}.snack`]" />
                                    </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="flex items-center justify-end my-2">
                    <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        <FontAwesomeIcon icon="fa-solid fa-check" />&nbsp;Valider
                    </PrimaryButton>
                </div>
            </form>
        </div>

    </Layout>
</template>
