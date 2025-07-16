<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import CardSection from '@/Components/CardSection.vue';
import SectionGrid from '@/Components/SectionGrid.vue';
import { Head } from '@inertiajs/vue3';

const routes = {
    extractGtin: route('extractGtin.index'),
};

</script>

<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>

        <SectionGrid extraClass="bg-white">

            <section class="py-4 my-8 rounded-md">

                <div class="max-w-7xl sm:px-2 lg:px-2">
                    <h2 class="font-thin font-roboto pb-4 ml-4 text-base text-sky-700 leading-tight">Fiscal</h2>
                </div>

                <div class="bg-white grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 px-6 py-8">
                    <CardSection srcImg="/images/excel.svg" :link="routes.extractGtin">
                        Extrair código GTIN
                    </CardSection>
                </div>
            </section>
        </SectionGrid>
    </AuthenticatedLayout>
</template>
