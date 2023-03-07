<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { ref } from 'vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

const open = ref(false);
</script>

<template>
  <PrimaryButton class="open" @click="open = true">
      <slot name="button" />
  </PrimaryButton>

  <Teleport to="body">
    
    <div v-if="open === true">
      <div class="background" @click="open = false"></div>

      <div class="modal border-4 border-gray-300 p-4">
        <FontAwesomeIcon class="xMark hover:text-gray-600 active:text-red-600" icon="fa-solid fa-circle-xmark" @click="open = false" :active="{ }" />
        <slot name="content" class="content" />
      </div>
    </div>
  </Teleport>
</template>

<style scoped>


.modal {
  position: absolute;
  z-index: 999;
  position: fixed;
  width: 80vw;
  height: 50vh;
  top: 35vh;
  left: 10vw;
  padding: 2em;
  box-shadow: 0 0 20px #000;
  background-color: var(--grey);
  color: var(--deepBlue);
  text-align: justify;
  padding-top: 4rem;
}

.close, .open {
  width: 80%;
  height: 3.5rem;
  display: block;
  margin: 2rem auto;
  font-size: 1.2rem;
}

.background {
  display: block;
  position: fixed;
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
  background-color: rgba(0,0,0,.3);
  z-index: 998;
}
</style>
