<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import {Card, CardContent, CardFooter, CardHeader, CardTitle} from "@/components/ui/card";

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Dogs',
    href: '/dogs',
  },
];
</script>

<template>
  <Head title="Добавить собаку" />

  <AppLayout :breadcrumbs="breadcrumbs">

    <div class="flex h-full flex-row flex-wrap gap-4 p-4">
      Добавить собаку
    </div>
  </AppLayout>
</template>
