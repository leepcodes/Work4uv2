<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <nav class="w-full bg-white px-10 py-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <img src="/images/work4ututor.png" alt="Work4U Tutor" class="h-8 object-contain">
        </div>
        <Link
            href="/logout"
            method="post"
            as="button"
            class="text-black text-sm hover:text-gray-800 transition-colors font-bold tracking-widest"
        >
            LOGOUT
        </Link>

        
    </nav>
</template>