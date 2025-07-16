<script setup>
    const props = defineProps({
        srcImg: { type: String, required: true },
        link: { type: String, default: '#' }
    });
</script>

<template>
    <a :href="link" class="group flex items-center pr-5 pl-2 py-3 text-white hover:bg-gray-700 transition">
        <img :src="srcImg" alt="Icon" class="w-5 h-5 mr-2" />
        <span class="hidden group-hover:inline text-sm font-medium whitespace-nowrap">
            <slot />
        </span>
    </a>
</template>