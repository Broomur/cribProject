<script setup>
defineProps({
    type: {
        type: String,
        default: 'submit',
    },
});
</script>

<template>
    <button :type="type" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150">
        <slot />
    </button>
</template>

<style scoped>
    button {
        font-size: 1rem;
        background-color: var(--deepBlue);
        display: inline-block;
        transition: all .2s ease-in;
    }

    button:hover {
        background-color: transparent;
        color: var(--deepBlue);
        border: solid 1px var(--deepBlue);
    }
</style>