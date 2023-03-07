<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/inertia-vue3';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { computed, onMounted, onUnmounted, ref } from 'vue';
import Cookie from '@/Components/Cookie.vue';

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

const showingNavigationDropdown = ref(false);
const contact = ref(false);
const legalNotice = ref(false);
</script>

<template>
    <Cookie />
    <div>
        <div class="authBar">
            <nav class="authNav border-b border-gray-100">
                <!-- Primary Navigation Menu -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <Link :href="route('home')">
                                <ApplicationLogo class="block h-9 w-auto" />
                                </Link>
                            </div>

                            <!-- Navigation Links -->
                            <div v-if="$page.props.auth.user" class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                <NavLink v-if="$page.props.auth.user_role[0]['id'] >= 3" :href="route('dashboard')"
                                    :active="route().current('dashboard')">
                                    Accueil
                                </NavLink>
                                <NavLink v-if="$page.props.auth.user_role[0]['id'] >= 3" :href="route('albums.index')"
                                    :active="route().current('albums.index')">
                                    Albums
                                </NavLink>
                                <NavLink v-if="$page.props.auth.user_role[0]['id'] >= 3" :href="route('team.index')"
                                    :active="route().current('team.index')">
                                    L'équipe
                                </NavLink>
                                <NavLink v-if="$page.props.auth.user_role[0]['id'] >= 3" :href="route('calendar.index')"
                                    :active="route().current('calendar.index')">
                                    Agenda
                                </NavLink>
                                <NavLink
                                    v-if="$page.props.auth.user_role[0]['id'] === 8 || $page.props.auth.user_role[0]['id'] === 10 || $page.props.auth.user_role[0]['id'] === 11 || $page.props.auth.user_role[0]['id'] === 12"
                                    :href="route('menus.index')" :active="route().current('menus.index')">
                                    Menus
                                </NavLink>
                                <NavLink v-if="$page.props.auth.user_role[0]['id'] >= 3" :href="route('articles.index')"
                                    :active="route().current('articles.index')">
                                    Articles
                                </NavLink>
                                <NavLink v-if="($page.props.auth.user_role[0]['id'] >= 3)"
                                    :href="route('documents.index')" :active="route().current('documents.index')">
                                    Documents
                                </NavLink>
                                <NavLink v-if="$page.props.auth.user_role[0]['id'] >= 10" :href="route('admin.index')"
                                    :active="route().current('admin.index')">
                                    Administration
                                </NavLink>
                            </div>
                            <div v-else class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                <NavLink :href="route('login')" :active="route().current('login')">
                                    Connexion
                                </NavLink>
                                <NavLink :href="route('register')" :active="route().current('register')">
                                    Inscription
                                </NavLink>
                            </div>
                        </div>

                        <div v-if="$page.props.auth.user" class="hidden sm:flex sm:items-center sm:ml-6">
                            <!-- Settings Dropdown -->
                            <div class="ml-3 relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button type="button"
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-black bg-light-blue hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                                <span v-if="width >= 780">{{ $page.props.auth.user.name }}</span>

                                                <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content class="bg-light-blue">
                                        <DropdownLink :href="route('account_options', $page.props.auth.user)"
                                            method="get" as="button">
                                            <FontAwesomeIcon icon="fa-solid fa-gear" />&nbsp;Paramètres du compte
                                        </DropdownLink>
                                        <DropdownLink :href="route('logout')" method="post" as="button">
                                            <FontAwesomeIcon icon="fa-solid fa-right-from-bracket" />&nbsp;Déconnexion
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-mr-2 flex items-center sm:hidden">
                            <button @click="showingNavigationDropdown = ! showingNavigationDropdown"
                                class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path
                                        :class="{'hidden': showingNavigationDropdown, 'inline-flex': ! showingNavigationDropdown }"
                                        stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16" />
                                    <path
                                        :class="{'hidden': ! showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }"
                                        stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div :class="{'block': showingNavigationDropdown, 'hidden': ! showingNavigationDropdown}"
                    class="sm:hidden">
                    <div v-if="$page.props.auth.user" class="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                            Accueil
                        </ResponsiveNavLink>
                        <ResponsiveNavLink v-if="$page.props.auth.user_role[0]['id'] >= 3" :href="route('albums.index')"
                            :active="route().current('albums.index')">
                            Albums
                        </ResponsiveNavLink>
                        <ResponsiveNavLink v-if="$page.props.auth.user_role[0]['id'] >= 3" :href="route('team.index')"
                            :active="route().current('team.index')">
                            L'équipe
                        </ResponsiveNavLink>
                        <ResponsiveNavLink v-if="$page.props.auth.user_role[0]['id'] >= 3"
                            :href="route('calendar.index')" :active="route().current('calendar.index')">
                            Agenda
                        </ResponsiveNavLink>
                        <ResponsiveNavLink
                            v-if="$page.props.auth.user_role[0]['id'] === 8 || $page.props.auth.user_role[0]['id'] === 10 || $page.props.auth.user_role[0]['id'] === 11 || $page.props.auth.user_role[0]['id'] === 12"
                            :href="route('menus.index')" :active="route().current('menus.index')">
                            Menus
                        </ResponsiveNavLink>
                        <ResponsiveNavLink v-if="$page.props.auth.user_role[0]['id'] >= 3"
                            :href="route('articles.index')" :active="route().current('articles.index')">
                            Articles
                        </ResponsiveNavLink>
                        <ResponsiveNavLink v-if="$page.props.auth.user_role[0]['id'] >= 3"
                            :href="route('documents.index')" :active="route().current('documents.index')">
                            Documents
                        </ResponsiveNavLink>
                        <ResponsiveNavLink v-if="$page.props.auth.user_role[0]['id'] >= 10" :href="route('admin.index')"
                            :active="route().current('admin.index')">
                            Administration
                        </ResponsiveNavLink>
                    </div>

                    <div v-else>
                        <ResponsiveNavLink :href="route('login')" :active="route().current('login')">
                            Connexion
                        </ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('register')" :active="route().current('register')">
                            Inscription
                        </ResponsiveNavLink>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div v-if="$page.props.auth.user" class="pt-4 pb-1 border-t border-gray-200">
                        <div class="px-4">
                            <div class="font-medium text-base text-gray-800">{{ $page.props.auth.user.name }}</div>
                            <div class="font-medium text-sm text-gray-500">{{ $page.props.auth.user.email }}</div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('account_options', $page.props.auth.user)" method="get"
                                as="button">
                                Paramètres du compte
                            </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('logout')" method="post" as="button">
                                Déconnexion
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header class="authHeader" v-if="$slots.header">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <h1 class="header">
                        <slot name="header" />
                    </h1>
                </div>
            </header>

            <!-- Page Content -->
            <main class="authBackground">

                <div v-if="$page.props.flash.error" class="text-red-600 text-center mt-8">
                    {{ $page.props.flash.error }}
                </div>

                <div v-if="$page.props.flash.message" class="text-green-600 text-center mt-8">
                    {{ $page.props.flash.message }}
                </div>

                <div class="py-12">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class="sm:rounded-lg">
                            <div class="overflow-hidden p-6 container mx-auto">
                                <slot />

                            </div>
                        </div>
                    </div>
                </div>

                <div class="pageModalBG" v-if="contact === true || legalNotice === true"
                    @click="contact = false ; legalNotice = false"></div>

                <div class="shadow-lg pageModalText" v-if="contact">

                    <a as="button" class="xMark hover:cursor-pointer" @click="contact = false">
                        <FontAwesomeIcon icon="fa-solid fa-circle-xmark"
                            class="active:text-red-600 hover:text-gray-600" />
                    </a>
                    <div>
                        <h2 class="sectionTitle">Contactez-nous</h2>
                        <p>
                        <ul>
                            <li>
                                <FontAwesomeIcon icon="fa-solid fa-phone" />&nbsp;:&nbsp;<a
                                    href="tel:+33479798542">04.79.79.85.42</a>
                            </li>
                            <li>
                                <FontAwesomeIcon icon="fa-solid fa-location-dot" />&nbsp;:&nbsp;6 place Pierre Jomain,
                                73230 Barby
                            </li>
                            <li>
                                <FontAwesomeIcon icon="fa-solid fa-at" />&nbsp;Direction : <a
                                    href="mailto:crechemickeys@gmail.com">crechemickeys@gmail.com</a>
                            </li>
                            <li>
                                <FontAwesomeIcon icon="fa-solid fa-at" />&nbsp;Association : <a
                                    href="mailto:secretariatmickeys@gmail.com">secretariatmickeys@gmail.com</a>
                            </li>

                        </ul>
                        </p>
                    </div>
                </div>

                <div class="shadow-lg pageModalText" v-if="legalNotice">

                    <a as="button" class="xMark hover:cursor-pointer" @click="legalNotice = false">
                        <FontAwesomeIcon icon="fa-solid fa-circle-xmark"
                            class="active:text-red-600 hover:text-gray-600" />
                    </a>
                    <div>
                        <h2 class="sectionTitle">Mentions légales</h2>
                        <p class="textLeft">Conformément aux dispositions de la loi n° 2004-575 du 21 juin 2004 pour la
                            confiance en l’économie numérique, il est précisé aux utilisateurs du site crechebarby.fr
                            l’identité des différents intervenants dans le cadre de sa réalisation et de son suivi.</p>

                        <h3 class="mt-4 sectionTitle">Propriétaire</h3>
                        <p class="textLeft">Le site internet crechebarby.fr est la propriété de l'association Les Petits
                            Mickeys. L’ensemble des éléments édités sur ce site, incluant notamment les textes,
                            photographies… sont rédigées sous la
                            responsabilité exclusive du propriétaire du site seul responsable des contenus. Ces derniers
                            sont protégés par les dispositions du code de la Propriété Intellectuelle. En conséquence,
                            aucun élément composant ce site ne peut être copié, reproduit, modifié, réédité, chargé,
                            dénaturé, transmis ou distribué de quelque manière que ce soit, de façon partielle ou
                            intégrale, sans l’autorisation écrite et préalable de la présidente de l'association. Seule
                            la copie à usage privé est autorisée pour votre usage personnel, privé et non-commercial,
                            sur votre ordinateur.</p>

                        <h3 class="mt-4 sectionTitle">Hébergeur</h3>
                        <ul class="textLeft">
                            <li>Ce site est hébergé par FirstHeberg</li>
                            <li>Techcrea Solutions SARL</li>
                            <li>70 rue Jean Jaures - 59770 Marly - France</li>
                        </ul>

                        <h3 class="mt-4 sectionTitle">Webmaster</h3>
                        <ul class="textLeft">
                            <li>Simon Chauvel</li>
                            <li>491 rue du Chemin Neuf</li>
                            <li>73230 Barby</li>
                            <li>
                                <FontAwesomeIcon icon="fa-solid fa-at" />&nbsp;
                                <a href="mailto:simonchauvel@gmail.com">
                                    simonchauvel@gmail.com
                                </a>
                            </li>
                            <li>
                                <FontAwesomeIcon icon="fa-solid fa-phone" />&nbsp;
                                <a href="tel:+33614566067">
                                    06.14.56.60.67
                                </a>
                            </li>
                        </ul>
                    </div>

                </div>
            </main>



            <!-- Page Footer -->
            <footer>
                <div class="authFooter">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="footerContent">
                            <section class="footerSection">
                                <a as="button" class="hover:cursor-pointer" @click="contact = true">
                                    <FontAwesomeIcon icon="fa-solid fa-address-book" />&nbsp;Contactez-nous
                                </a>
                            </section>
                            <section class="footerSection">
                                <a as="button" class="hover:cursor-pointer" @click="legalNotice = true">
                                    <FontAwesomeIcon icon="fa-solid fa-book" />&nbsp;Mentions légales
                                </a>
                            </section>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
</template>
