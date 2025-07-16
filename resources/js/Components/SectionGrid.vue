<script setup>
const props = defineProps({
    extraClass: { type: String, default: '' }
});
</script>

<template>
    <section class="pb-8 max-w-7xl mx-auto sm:px-2 lg:px-2 mt-10  shadow-lg gap-8 rounded-xl" :class="extraClass">
        <slot />
    </section>
</template>