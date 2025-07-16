<template>
    <div class="fixed top-20 right-28 z-50">
        <Transition name="float-left" appear class="w-[50rem]">
            <div v-if="show"
                class="bg-green-50 border border-1 border-green-400 text-green-700 pr-20 pl-4 py-4 mb-4 text-base rounded-lg shadow-md w-full max-w-lg"
                role="alert">
                <span class="block sm:inline text-left">{{ message }}</span>
            </div>
        </Transition>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const props = defineProps({
    message: String,
})

const show = ref(true)

onMounted(() => {
    setTimeout(() => {
        show.value = false
    }, 4000)
})
</script>


<style scoped>
.float-left-enter-active,
.float-left-leave-active {
    transition: all 0.4s ease-in-out;
    position: relative;
}

.float-left-enter-from {
    opacity: 0;
    transform: translateX(40px);
}

.float-left-enter-to {
    opacity: 1;
    transform: translateX(0);
}

.float-left-leave-from {
    opacity: 1;
    transform: translateX(0);
}

.float-left-leave-to {
    opacity: 0;
    transform: translateX(-40px);
}
</style>
  
