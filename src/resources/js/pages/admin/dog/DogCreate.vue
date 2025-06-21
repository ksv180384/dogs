<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

import { type BreadcrumbItem } from '@/types';

import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
  Select,
  SelectContent,
  SelectGroup,
  SelectItem,
  SelectLabel,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select'
import AppLayout from '@/layouts/AppLayout.vue';
import InputError from '@/components/InputError.vue';
import UploadImage from "@/components/UploadImage.vue";

const { types, statuses } = defineProps<{
  types?: object;
  statuses?: object;
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

const form = useForm({
  name: '',
  type: '',
  status: '',
  image: '',
});

const submit = () => {
  form.post(route('admin.dog.store'), {
    onFinish: () => form.reset('password', 'password_confirmation'),
  });
};
</script>

<template>
  <Head title="Добавить собаку" />

  <AppLayout :breadcrumbs="breadcrumbs">

    <div class="flex h-full flex-row flex-wrap gap-4 p-4">
      <form @submit.prevent="submit" class="flex flex-col gap-6">
        <div class="grid gap-6">
          <div class="grid gap-2">
            <Label for="name">Имя</Label>
            <Input
              v-model="form.name"
              id="name"
              type="text"
              required
              autofocus
              :tabindex="1"
              autocomplete="name"
              placeholder="Имя"
            />
            <InputError :message="form.errors.name" />
          </div>

          <div class="grid gap-2">
            <Label for="type">Тип</Label>
            <Select for="type">
              <SelectTrigger>
                <SelectValue placeholder="Тип" />
              </SelectTrigger>
              <SelectContent>
                <SelectItem
                  v-for="(typeName, typeKey) in types"
                  :value="typeKey"
                >
                  {{ typeName }}
                </SelectItem>
              </SelectContent>
            </Select>
          </div>

          <div class="grid gap-2">
            <Label for="status">Статус</Label>
            <Select id="status">
              <SelectTrigger>
                <SelectValue placeholder="Статус" />
              </SelectTrigger>
              <SelectContent>
                <SelectItem
                  v-for="(statusName, statusKey) in statuses"
                  :value="statusKey"
                >
                  {{ statusName }}
                </SelectItem>
              </SelectContent>
            </Select>
          </div>

          <div>
            <UploadImage v-model="form.image"/>
          </div>

<!--          <div class="grid gap-2">-->
<!--            <Label for="email">Email address</Label>-->
<!--            <Input id="email" type="email" required :tabindex="2" autocomplete="email" v-model="form.email" placeholder="email@example.com" />-->
<!--            <InputError :message="form.errors.email" />-->
<!--          </div>-->

<!--          <div class="grid gap-2">-->
<!--            <Label for="password">Password</Label>-->
<!--            <Input-->
<!--              id="password"-->
<!--              type="password"-->
<!--              required-->
<!--              :tabindex="3"-->
<!--              autocomplete="new-password"-->
<!--              v-model="form.password"-->
<!--              placeholder="Password"-->
<!--            />-->
<!--            <InputError :message="form.errors.password" />-->
<!--          </div>-->

<!--          <div class="grid gap-2">-->
<!--            <Label for="password_confirmation">Confirm password</Label>-->
<!--            <Input-->
<!--              id="password_confirmation"-->
<!--              type="password"-->
<!--              required-->
<!--              :tabindex="4"-->
<!--              autocomplete="new-password"-->
<!--              v-model="form.password_confirmation"-->
<!--              placeholder="Confirm password"-->
<!--            />-->
<!--            <InputError :message="form.errors.password_confirmation" />-->
<!--          </div>-->

          <Button type="submit" class="mt-2 w-full" tabindex="5" :disabled="form.processing">
            <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
            Добавить
          </Button>
        </div>
      </form>
    </div>
  </AppLayout>
</template>
