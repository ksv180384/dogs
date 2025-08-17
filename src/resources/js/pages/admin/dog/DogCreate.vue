<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

import { type BreadcrumbItem } from '@/types';
import { type InertiaForm } from '@inertiajs/vue3';
import { DogSelectList, DogFormData } from '@/types/dog';

import AppLayout from '@/layouts/AppLayout.vue';
import DogForm from '@/components/forms/DogForm.vue';

const { dogs, types, genders, statuses } = defineProps<{
  dogs?: DogSelectList[];
  types?: Record<string, string>;
  genders?: Record<string, string>;
  statuses?: Record<string, string>;
}>();

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Собаки',
    href: '/admin/dogs',
  },
  {
    title: 'Добавить собаку',
    href: '/admin/dogs/create',
  },
];

const submit = (form: InertiaForm<DogFormData>) => {
  form.post(route('admin.dog.store'), {
    onFinish: () => {

    },
  });
};
</script>

<template>
  <Head title="Добавить собаку" />

  <AppLayout :breadcrumbs="breadcrumbs">

    <div class="flex w-full h-full flex-row flex-wrap gap-4 p-4">
      <DogForm
        :dogs="dogs"
        :genders="genders"
        :types="types"
        :statuses="statuses"
        @submit="submit"
      />
    </div>
  </AppLayout>
</template>

<style scoped>

</style>
