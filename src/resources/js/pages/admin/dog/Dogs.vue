<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';

import { DogListItem } from "@/types/dog";

const { dogs } = defineProps<{
  dogs?: DogListItem[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Собаки',
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
    <div class="flex h-full flex-row flex-wrap justify-around gap-4 p-4">
      <template v-for="dog in dogs" :key="dog.id">
        <Link :href="route('admin.dog.edit', { id: dog.id })">
          <Card class="w-[380px] min-h-[320px] gap-2 py-2">
            <CardHeader class="py-2">
              <CardTitle class="flex flex-row justify-between">
                <div>{{ dog.name }}</div>
                <div>{{ dog.gender }}</div>
              </CardTitle>
            </CardHeader>
            <CardContent class="px-0 flex-1">
              <img v-if="dog.image_link" :src="dog.image_link" :alt="dog.name"/>
            </CardContent>
            <CardFooter class="flex flex-row">
              <div class="flex-1 text-xl">
                {{ dog.type }}
              </div>
              <div>
                Д/р: {{ dog.birthdate }}
              </div>
            </CardFooter>
          </Card>
        </Link>
      </template>
    </div>
  </AppLayout>
</template>
