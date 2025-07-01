<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select';

import { type DogSelectList, DogFormData } from '@/types/dog';

import InputError from '@/components/InputError.vue';
import UploadImage from '@/components/UploadImage.vue';
import Editor from '@/components/editor/Editor.vue';

const { dogs, types, statuses, errors } = defineProps<{
  dogs?: DogSelectList[];
  types?: Record<string, string>;
  statuses?: Record<string, string>;
}>();
const emits = defineEmits(['submit']);

const form = useForm<DogFormData>({
  name: '',
  description: '',
  type: '',
  status: '',
  parent_id: null,
  image: null,
});

const submit = () => {
  emits('submit', form);
}
</script>

<template>
  <form @submit.prevent="submit" class="flex w-full flex-col gap-6">
    <div class="flex flex-col lg:flex-row w-full gap-6">
      <div class="flex flex-1 flex-col gap-4">
        <div class="flex flex-col gap-2">
          <Label for="type">Тип</Label>
          <Select v-model="form.type" for="type">
            <SelectTrigger class="w-full">
              <SelectValue placeholder="Тип" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem
                v-for="(typeName, typeKey) in types"
                :value="typeKey"
                :key="typeName"
              >
                {{ typeName }}
              </SelectItem>
            </SelectContent>
          </Select>
          <InputError :message="form.errors.type" />
        </div>

        <div v-if="form.type === 'puppy'" class="flex flex-col gap-2">
          <Label for="type">Производители</Label>
          <Select v-model="form.parent_id" for="parent">
            <SelectTrigger class="w-full">
              <SelectValue placeholder="Производители" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem
                v-for="dog in dogs"
                :value="dog.id"
                :key="dog.id"
              >
                {{ dog.name }}
              </SelectItem>
            </SelectContent>
          </Select>
          <InputError :message="form.errors.parent_id" />
        </div>

        <div class="flex flex-col gap-2">
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

        <div class="flex flex-col gap-2">
          <Label for="status">Статус</Label>
          <Select v-model="form.status" id="status">
            <SelectTrigger class="w-full">
              <SelectValue placeholder="Статус" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem
                v-for="(statusName, statusKey) in statuses"
                :value="statusKey"
                :key="statusKey"
              >
                {{ statusName }}
              </SelectItem>
            </SelectContent>
          </Select>
          <InputError :message="form.errors.status" />
        </div>
      </div>

      <div class="flex flex-1">
        <UploadImage v-model="form.image"/>
      </div>
    </div>
    <div>
      <Editor v-model="form.description"/>
    </div>
    <div>
      <Button type="submit" class="mt-2 w-full" tabindex="5" :disabled="form.processing">
        <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
        Добавить
      </Button>
    </div>
  </form>
</template>

<style scoped>

</style>
