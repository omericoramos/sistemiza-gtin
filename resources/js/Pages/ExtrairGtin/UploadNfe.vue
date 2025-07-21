<script setup>
import { useForm, Head } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import ModalTaxExclusion from '@/Components/ModalTaxExclusion.vue'
import { reactive, ref } from 'vue'

const props = defineProps(['flash', 'fileName', 'errors'])

const form = useForm({
    file: null,
    remember: false,
})
const token = ref(null)
const modalCustom = ref(null)

const showBtnSuccess = ref(false)
const showBtnError = ref(false)

const handleModalFechar = () => {

    if (token.value) {
        window.location.href = route('extractGtin.download', token.value);
    }
}

const modalinfo = reactive({
    title: 'Preparando os arquivos',
    effectStatus: 'prepareData',
    showEffect: false
})

const submit = () => {

    modalinfo.title = 'Preparando os arquivos'

    openModal()

    if (!checkEmptyFields()) return

    const formData = new FormData()
    formData.append('file', form.file[0])

    const unzipFiles = async () => {
        const response = await axios.post(route('extractGtin.upload'), formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            }
        })
        return response.data
    }

    unzipFiles().then((response) => {

        response.statusCode == 200 ? processNfeGtinCode() : mountMessageError(response.message)

    }).catch((error) => {
        const { message } = JSON.parse(error.request.response)
        mountMessageError(message)
    })
}

const processNfeGtinCode = async () => {

    // Atualiza o modal para indicar que o arquivo está sendo gerado
    modalinfo.title = 'Gerando o Arquivo Excel com os dados das NFes'
    modalinfo.effectStatus = 'importFiles'

    const processGtinCode = async () => {

        // Faz a requisição para extrair o arquivo
        const response = await fetch(route('processNfeGtinCode.process'), {
            method: 'GET', // Ou POST, se necessário
        })

        const data = await response.json()
        return data
    }

    processGtinCode().then((response) => {

        response.statusCode == 200 ? success(response)
            : mountMessageError(response.message)

    }).catch((error) => {
        const message = JSON.parse(error.request.response)
        mountMessageError(message)
    })

}
const success = (response) => {

    form.file = null
    modalinfo.title = `<p class='text-emerald-700'>${response.message}</p>`
    modalinfo.showEffect = false
    showBtnSuccess.value = true
    token.value = response.token
}

const mountMessageError = (message) => {
    modalinfo.title = `<p class='text-rose-500'>Erro: ${message}</p>`
    modalinfo.showEffect = false
    showBtnError.value = true
}

const checkEmptyFields = () => {
    const condition = [
        { condition: !form.file, message: 'Selecione o arquivo Zip' }
    ]

    const error = condition.find(item => item.condition)

    if (error) {
        mountMessageError(error.message, true)
        return false
    }

    return true
}

const openModal = () => {
    showBtnSuccess.value = false
    showBtnError.value = false
    modalinfo.showEffect = true
    modalCustom.value?.show()
}

</script>
<template>

    <Head title="Extrair dados NFe para arquivo Excel" />
    <AuthenticatedLayout>
        <section class="py-4">
            <div class=" bg-white max-w-7xl mx-auto py-2 px-4 sm:px-6 lg:px-2">

                <div class="max-w-7xl mx-auto sm:px-2 lg:px-2 my-5">
                    <h2 class="text-xl font-semibold  text-center p-4 text-sky-700 leading-tight">Processar NFes
                        Exclusão Monofasica </h2>
                    <h2 class=" text-gray-500 text-lg font-medium text-center mb-5">
                        Os arquivos XML devem estar compactados no formato zip.
                    </h2>
                </div>

                <!-- formulario -->
                <div class="w-full max-w-xl mx-auto mt-6">
                    <form id="arquivoRubricaEsocial" @submit.prevent="submit"
                        class="bg-white drop-shadow-md rounded px-8  pb-8 my-8" enctype="multipart/form-data">
                        <meta>
                        <div class="my-3">
                            <label for="formFile" class="my-4 inline-block text-neutral-700">
                                {{ form.label }}</label>
                            <input
                                class="relative m-0 block w-full min-w-0 flex-auto rounded border border-solid
                                     border-neutral-300 bg-clip-padding py-[0.32rem] px-3 text-base font-normal transition duration-300 
                                     ease-in-out file:-mx-3 file:-my-[0.32rem]  file:border-0 file:border-solid file:border-inherit
                                    file:bg-neutral-100 dark:file:bg-sky-600 file:px-3 file:py-[0.32rem] file:text-neutral-700 
                                    file:cursor-pointer dark:file:text-neutral-100 file:transition file:duration-150 file:ease-in-out  
                                    file:[margin-inline-end:0.75rem] file:[border-inline-end-width:1px] hover:file:bg-sky-700 cursor-pointer"
                                type="file" id="file" multiple name="files" @input="form.file = $event.target.files"
                                ref="fileInput" />

                        </div>
                        <div class="flex items-center justify-between">
                            <button
                                class=" w-full bg-sky-600 hover:bg-sky-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                                type="submit" data-te-toggle="modal" data-te-target="#staticBackdrop"
                                data-te-ripple-init data-te-ripple-color="light">
                                processar NFes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
        <ModalTaxExclusion ref="modalCustom" :title="modalinfo.title" :showEffect="modalinfo.showEffect"
            :successVisible="showBtnSuccess" :errorVisible="showBtnError" :effectStatus="modalinfo.effectStatus"
            @closeModal="handleModalFechar" />

        <!-- Main modal -->
        <div id="static-modal" data-modal-backdrop="static" tabindex="-1" aria-hidden="true"
            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full ">
            <div class="relative p-4 w-full max-w-2xl max-h-full -translate-y-32">
                <!-- Modal content -->
                <div class="relative bg-white rounded-xl shadow-sm pt-8 pb-2">
                    <!-- Modal header -->
                    <div class="flex items-center justify-center p-4 md:p-5 rounded-t">
                        <div v-html="modalinfo.title"
                            class="text-xl text-center font-semibold leading-relaxed text-gray-600">

                        </div>
                    </div>
                    <!-- Modal body -->
                    <div class="relative flex-auto p-4">
                        <div v-if="modalinfo.showEffect" class="custom-loader mx-auto"></div>
                    </div>
                    <!-- Modal footer -->
                    <div
                        class="flex h-36 items-center justify-center  mx-auto p-4 md:p-5 rounded-b dark:border-gray-600">

                        <button data-modal-hide="static-modal" type="button" id="btnSuccess" class="hidden w-20 text-white bg-emerald-500 hover:bg-emerald-700 focus:ring-4 focus:outline-none 
                            focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-emerald-500
                             dark:hover:bg-emerald-700 dark:focus:ring-emerald-800">
                            OK
                        </button>

                        <button id="btnError" data-modal-hide="static-modal" type="button" class="hidden py-2.5 px-5 ms-3 text-sm font-medium text-white focus:outline-none bg-rose-500 
                            rounded-lg border border-gray-200 hover:bg-rose-600 hover:text-gray-200 focus:z-10 focus:ring-4 
                            focus:ring-gray-100">
                            Fechar
                        </button>

                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>