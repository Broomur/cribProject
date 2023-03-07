<script setup>
import 'vue3-carousel/dist/carousel.css'
import { Carousel, Slide, Pagination, Navigation } from 'vue3-carousel'
import { Head } from '@inertiajs/inertia-vue3';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { computed, onMounted, onUnmounted, ref } from 'vue';
import LayoutVue from '@/Layouts/Layout.vue';
import ModalPopup from '@/Components/ModalPopup.vue';

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


const props = defineProps(['intro', 'welcome', 'educative', 'jobOffers', 'introAlbum', 'welcomeDoc', 'educativeDoc'])
</script>

<template>
    <Head title=" " />

    <LayoutVue>
        <template #header>
            <FontAwesomeIcon icon="fa-solid fa-house" />&nbsp;
            Bienvenue sur le site de la crèche de Barby
            &nbsp;<FontAwesomeIcon icon="fa-solid fa-house" />
        </template>

        <div v-if="intro.content.length >= 1">
            <div class="mb-8 rounded colorDiv shadow-lg text-center" v-if="type !== 'xs'">
                <h2 class="sectionTitle">
                    <FontAwesomeIcon icon="fa-solid fa-users-rays" /><br />
                    Présentation de la crèche
                </h2>
                <p>{{ intro.content }}</p>
                <template v-if="introAlbum.pictures[0]">
                    <Carousel :wrap-around="true">
                        <Slide v-for="picture in introAlbum.pictures" :key="picture.id">
                            <div class="carousel__item">
                                <img :src="picture.path">
                            </div>
                        </Slide>
                        <template #addons>
                            <Navigation />
                            <Pagination />
                        </template>
                    </Carousel>
                </template>
            </div>

            <div v-else class="mb-4 rouded colorDiv">
                <ModalPopup>
                    <template #button>
                        Présentation de la crèche
                    </template>
                    <template #content>
                        <h2 class="sectionTitle">
                            <FontAwesomeIcon icon="fa-solid fa-users-rays" /><br />Présentation de la crèche
                        </h2>
                        <p class="scroll text-center" >
                            {{ intro.content }}
                            <template v-if="introAlbum.pictures[0]">
                                <Carousel :wrap-around="true">
                                    <Slide v-for="picture in introAlbum.pictures" :key="picture.id">
                                        <div class="carousel__item">
                                            <img :src="picture.path">
                                        </div>
                                    </Slide>
                                    <template #addons>
                                        <Navigation />
                                        <Pagination />
                                    </template>
                                </Carousel>
                            </template>
                        </p>
                    </template>
                </ModalPopup>
            </div>
        </div>

        <div v-if="welcome.content.length >= 1">
            <div class="mb-8 rounded colorDiv shadow-lg text-center" v-if="type !== 'xs'">
                <h2 class="sectionTitle">
                    <FontAwesomeIcon icon="fa-solid fa-hands-holding-child" /><br />Projet d'accueil
                </h2>
                <p>{{ welcome.content }}</p>
                <a as="button" v-if="welcomeDoc" :href="welcomeDoc.path" download>
                    <FontAwesomeIcon icon="fa-solid fa-file-export" />&nbsp;Télécharger le projet d'accueil
                </a>
            </div>

            <div v-else class="mb-8 rounded colorDiv shadow-lg">
                <ModalPopup>
                    <template #button>
                        Projet d'accueil
                    </template>
                    <template #content>
                        <h2 class="sectionTitle">
                            <FontAwesomeIcon icon="fa-solid fa-hands-holding-child" /><br />Projet d'accueil
                        </h2>
                        <p class="scroll text-center">
                            {{ welcome.content }}
                            <a class="block" as="button" v-if="welcomeDoc" :href="welcomeDoc.path" download title="Télécharger le projet d'accueil">
                                <FontAwesomeIcon icon="fa-solid fa-file-export" />
                            </a>
                        </p>
                    </template>
                </ModalPopup>
            </div>
        </div>

        <div v-if="educative.content.length >= 1">
            <div class="mb-8 rounded colorDiv shadow-lg text-center" v-if="type !== 'xs'">
                <h2 class="sectionTitle">
                    <FontAwesomeIcon icon="fa-solid fa-shapes" /><br />Projet éducatif
                </h2>
                <p>{{ educative.content }}</p>
                <a as="button" v-if="educativeDoc" :href="educativeDoc.path" download>
                    <FontAwesomeIcon icon="fa-solid fa-file-export" />&nbsp;Télécharger le projet éducatif
                </a>
            </div>

            <div v-else class="mb-8 rounded colorDiv shadow-lg">
                <ModalPopup>
                    <template #button>
                        Projet éducatif
                    </template>
                    <template #content>
                        <h2 class="sectionTitle">
                            <FontAwesomeIcon icon="fa-solid fa-shapes" /><br />Projet éducatif
                        </h2>
                        <p class="scroll text-center">{{ educative.content }}</p>
                        <a class="block" as="button" v-if="educativeDoc" :href="educativeDoc.path" download title="Télécharger le projet éducatif">
                            <FontAwesomeIcon icon="fa-solid fa-file-export" />
                        </a>
                    </template>
                </ModalPopup>
            </div>
        </div>

        <div v-if="jobOffers.length >= 1">
            <div class="mb-8 rounded colorDiv shadow-lg" v-if="type !== 'xs'">
                <h2 class="sectionTitle">
                    <FontAwesomeIcon icon="fa-solid fa-briefcase" /><br />Offres d'emploi
                </h2>
                <article v-for="jobOffer in jobOffers" v-if="jobOffers.length > 0">
                    <p>{{ jobOffer.name }}</p>
                    <p>{{ jobOffer.content }}</p>
                </article>
                <p v-else>Il n'y a actuellement pas de recrutement en cours.</p>
            </div>

            <div v-else class="mb-8 rounded colorDiv shadow-lg">
                <ModalPopup v-if="jobOffers.length > 0">
                    <template #button>
                        Offres d'emploi
                    </template>
                    <template #content>
                        <h2 class="sectionTitle">
                            <FontAwesomeIcon icon="fa-solid fa-briefcase" /><br />Offres d'emploi
                        </h2>
                        <article v-for="jobOffer in jobOffers">
                            <p>{{ jobOffer.name }}</p>
                            <p>{{ jobOffer.content }}</p>
                        </article>                       
                    </template>
                </ModalPopup>
                <p v-else>Il n'y a actuellement pas de recrutement en cours.</p>
            </div>
        </div>
    </LayoutVue>

</template>
