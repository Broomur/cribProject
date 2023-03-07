<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { ref } from 'vue';

const status = ref(localStorage.getItem('BarbyCribCookies'));
const show = ref(true);


function accept() {
    localStorage.setItem('BarbyCribCookies', 'accept');
}

</script>

<template>
    <transition name="fade">
        <aside class="cookie-comply" v-if="show && !status">
            <div class="cookie-comply__text">
                <header class="cookie-comply__header"><h3 class="cookie-comply__header-title">Politique des cookies</h3></header>
                <p class="cookie-comply__header-description">Nous utilisons des cookies afin permettre le bon fonctionnement de ce site et de vous offrir une expérience de navigation optimale. Ils disparaîtront dès la fermeture de votre navigateur internet et ne s'imisceront pas dans votre vie privé.<br />Nous vous souhaitons une bonne visite ;)</p>
            </div>
            <div class="cookie-comply__actions">
                <PrimaryButton class="cookie-comply__button" @click="accept(); show = !show">
                    Je comprends
                </PrimaryButton>
            </div>
        </aside>
    </transition>
</template>

<style scoped>
* {
    z-index: 999;
}
</style>