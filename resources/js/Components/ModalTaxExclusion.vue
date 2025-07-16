<script setup>
import { onMounted, ref, watch, computed } from 'vue';
import IconCheckSuccess from "@/Components/Icon/IconCheckSuccess.vue";
import IconDangerError from "@/Components/Icon/IconDangerError.vue"
import { Modal } from 'flowbite';

const props = defineProps({
    id: { type: String, default: 'static-modal' },
    title: { type: String, default: 'Processando...' },
    showEffect: { type: Boolean, default: false },
    successVisible: { type: Boolean, default: false },
    errorVisible: { type: Boolean, default: false },
    effectStatus: { type: String, default: '' }
});

const emit = defineEmits(['closed'])

let modalInstance = null;

onMounted(() => {
    const modalEl = document.getElementById(props.id);

    if (modalEl) {
        modalInstance = new Modal(modalEl, { backdrop: 'static' });
    }
});

const show = () => {
    modalInstance?.show();
};

const hide = () => {
    modalInstance?.hide();
};

const colors = computed(() => {

    switch (props.effectStatus) {
        case 'prepareData':
            return { effectColor: '#96051E', bgColor: '#ff5775' };
        case 'inconsistentNcmCst':
            return { effectColor: '#1C4AB0', bgColor: '#0387f7' };
        case 'importRubric':
            return { effectColor: '#B37813', bgColor: '#ffc150' };
        case 'importFiles':
            return { effectColor: '#065f46', bgColor: '#34d399' };
        default:
            return { effectColor: '#96051E', bgColor: '#ff5775' };
    }

});

const loaderStyle = computed(() => {
    return {
        background: `linear-gradient(60deg, #ee303000, ${colors.value.effectColor}) left -150px top 0/100px 40px no-repeat ${colors.value.bgColor}`,
        animation: 'ct1 1.8s infinite linear'
    }
});

defineExpose({ show, hide });
</script>

<style scoped>
.custom-loader {
    width: 350px;
    height: 20px;
    background:
        linear-gradient(60deg, #ee303000, v-bind("colors.effectColor")) left -150px top 0/100px 40px no-repeat v-bind("colors.bgColor");
    animation: ct1 1.8s infinite linear;
}

@keyframes ct1 {
    100% {
        background-position: right -100px top 0
    }
}
</style>

<template>
    <div :id="id" data-modal-backdrop="static" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">

        <div class="relative p-4 w-full max-w-2xl max-h-full -translate-y-32">
            <div class="relative bg-white rounded-xl shadow-sm pt-8 pb-2">

                <div class="flex items-center justify-center p-4 md:p-5 rounded-t">
                    <div class="text-xl text-center font-semibold leading-relaxed text-gray-600" v-html="title"></div>
                </div>

                <div class="relative flex-auto p-4">
                    <IconCheckSuccess v-if="successVisible" />
                    <IconDangerError v-if="errorVisible" />
                    <div v-if="showEffect" class="custom-loader mx-auto"></div>
                </div>

                <div class="flex h-36 items-center justify-center mx-auto p-4 md:p-5 rounded-b">
                    <button v-if="successVisible" @click="hide(); $emit('closeModal')" type="button"
                        class="w-20 text-white bg-emerald-500 hover:bg-emerald-700 rounded-lg text-sm px-5 py-2.5">
                        OK
                    </button>
                    <button v-if="errorVisible" @click="hide()" type="button"
                        class="py-2.5 px-5 ms-3 text-sm font-medium text-white bg-rose-500 hover:bg-rose-600 rounded-lg">
                        Fechar
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>