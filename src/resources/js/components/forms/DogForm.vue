<script setup lang="ts">
import { ref, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';
import {
  DateValue,
  parseDate,
  DateFormatter,
  getLocalTimeZone,
} from '@internationalized/date';
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
import { Calendar } from '@/components/ui/calendar';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import { CalendarIcon } from 'lucide-vue-next';
const df = new DateFormatter('ru-RU', {
  dateStyle: 'long',
});
import api from '@/services/api';

import { type DogSelectList, DogFormData, Dog } from '@/types/dog';

import InputError from '@/components/InputError.vue';
import UploadImage from '@/components/UploadImage.vue';
import Editor from '@/components/editor/Editor.vue';

const { dog, dogs, types, statuses } = defineProps<{
  dog?: Dog | null;
  dogs?: DogSelectList[];
  types?: Record<string, string>;
  statuses?: Record<string, string>;
}>();
const emits = defineEmits(['submit']);

const isLoadingImageAction = ref(false);
const imageLink = ref(dog?.image || '');

const form = useForm<DogFormData>({
  name: dog?.name || '',
  birthdate: dog?.birthdate ? parseDate(dog.birthdate as string) as DateValue : null,
  description: dog?.description || '',
  type: dog?.type || '',
  status: dog?.status || '',
  parent_id: dog?.parent_id || null,
  image: null,
});

const submit = () => {
  // Преобразуем дату в формат Y-m-d
  const formattedData = {
    ...form.data(),
    birthdate: form.birthdate ? form.birthdate.toString() : null,
  };
  form.transform((data) => formattedData);
  emits('submit', form);
}

const deleteImage = async () => {
  if(!dog?.id){
    return;
  }
  isLoadingImageAction.value = true;
  try {
    await api.dog.deleteImage(dog.id);
    imageLink.value = '';
  } catch (e) {
    console.error(e);
  } finally {
    isLoadingImageAction.value = false;
  }
}

watch(
  () => dog?.image,
  (newVal) => {
   imageLink.value = newVal || '';
  }
);
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
          <Label for="type">Дата рождения</Label>
          <Popover>
            <PopoverTrigger as-child>
              <Button
                variant="outline"
              >
                <CalendarIcon class="mr-2 h-4 w-4" />
                {{ form.birthdate ? df.format(form.birthdate.toDate(getLocalTimeZone())) : "Дата рождения" }}
              </Button>
            </PopoverTrigger>
            <PopoverContent class="w-auto p-0">
              <Calendar v-model="form.birthdate" initial-focus />
            </PopoverContent>
          </Popover>
          <InputError :message="form.errors.birthdate" />
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
        <UploadImage
          v-model="form.image"
          :image-preview="imageLink"
          @delete="deleteImage"
        />
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
