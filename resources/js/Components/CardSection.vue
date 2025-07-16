<script setup>
import { Link } from '@inertiajs/vue3';
const props = defineProps({
    srcImg: { type: String, required: true },
    link: { type: String, required: true }
});

</script>

<template>
    <Link :href="link"
        class="text-slate-800 bg-white rounded-lg px-4 py-6 flex flex-col items-center justify-center text-center hover:shadow-lg hover:scale-105 transition-transform duration-200">
    <div class="mt-2 leading-none flex justify-center items-center">
        <img :src="srcImg" class="w-12 mb-3" alt="Icon" />
    </div>
    <p class="text-center text-xs text-slate-800 hover:text-slate-900 font-medium tracking-widest">
        <slot />
    </p>
    </Link>
</template>