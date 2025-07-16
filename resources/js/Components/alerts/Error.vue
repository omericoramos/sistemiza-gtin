<script setup>
import { ref, onMounted } from 'vue'

const props = defineProps({
    message: String,
    duration: { type: Number, default: 4000 },
})

const show = ref(true)

onMounted(() => {
    setTimeout(() => {
        show.value = false
    }, props.duration)
})
</script>

<template>
    <div class="fixed top-6 right-6 z-50">
        <Transition name="float-left" appear class="w-[50rem]">
            <div v-if=" show"
            class="bg-red-50 border border-1 border-red-400 text-red-700 pr-20 pl-4 py-4 mb-4 text-base rounded-lg shadow-md w-full max-w-lg"
            role="alert">
            <span class="block sm:inline">{{ message }}</span>
    </div>
    </Transition>
    </div>
</template>

<style scoped>
.float-left-enter-active,
.float-left-leave-active {
    transition: all 0.5s ease;
}

.float-left-enter-from {
    opacity: 0;
    transform: translateX(100%);
}

.float-left-leave-to {
    opacity: 0;
    transform: translateX(-100%);
}
</style>
