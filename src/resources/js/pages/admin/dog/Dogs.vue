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
  <Head title="Dashboard" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <template #right>
      <Link :href="route('admin.dog.create')">
        <Button variant="link">Добавить</Button>
      </Link>
    </template>
    <div class="flex h-full flex-row flex-wrap gap-4 p-4">
      <card class="w-[400px] h-[500px]">
        <card-header>
          <card-title>test</card-title>
        </card-header>
        <card-content>asdas</card-content>
        <card-footer>footer</card-footer>
      </card>
    </div>
  </AppLayout>
</template>
