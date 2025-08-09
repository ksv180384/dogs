<script setup lang="ts">

import { Button } from '@/components/ui/button';
import { DogImageItem } from '@/types/dog';

const { image } = defineProps<{
  image: DogImageItem;
}>();

const emits = defineEmits<{
  (e: 'remove', payload: number): void
}>()

const removeImg = () => {
  emits('remove', image.id);
}
</script>

<template>
  <div class="overflow-hidden w-40 rounded-sm relative group">
    <Button
      variant="destructive"
      size="sm"
      type="button"
      class="opacity-0 group-hover:opacity-100 transition-opacity absolute top-2 right-2 z-10"
      @click="removeImg()"
    >
      Удалить
    </Button>
    <img :src="image.image_link" :alt="`Dog image ${image.id}`" class="" />
  </div>
</template>

<style scoped>

</style>
