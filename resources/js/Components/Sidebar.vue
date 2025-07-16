<script setup>
import { Link } from '@inertiajs/vue3';
import SidebarLink from '@/Components/SidebarLink.vue';

const routes = {
  dashboard: route('dashboard'),
};

</script>
<template>
  <aside
    class="fixed left-0 top-0 z-50 h-screen w-[60px] overflow-hidden bg-gray-800 shadow-lg hover:w-48 transition-all duration-200 group">
    <div class="flex justify-left py-4 ml-3">
      <Link :to="routes.dashboard">
      <img class="h-11 w-10" src="/images/logo-mini.png" alt="Logo">
      </Link>
    </div>

    <nav class="flex flex-col gap-2 px-1 mt-4">
      <SidebarLink srcImg="/images/home.svg" :link="routes.dashboard">
        Página Inicial
      </SidebarLink>
    </nav>
  </aside>
</template>
