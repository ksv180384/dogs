<script setup lang="ts">
import { ref, defineEmits, defineProps, watch } from 'vue';
import { Button } from '@/components/ui/button';

interface Props {
  imagePreview?: string;
  isLoad?: boolean;
}

const model = defineModel();
const props = defineProps<Props>();
const emits = defineEmits<{
  (e: 'onChangeImage', payload: { files: FileList | null; previewUrl?: string }): void;
}>();

const inputImg = ref<HTMLInputElement | null>(null);
const imagePreview = ref<string | undefined>(props.imagePreview);

// Обработчик изменения через input файла
const onChangeFileInput = (e: Event) => {
  const target = e.target as HTMLInputElement;
  const files = target.files;

  if (!files || files.length === 0) {
    removeImg();
    return;
  }

  processImageFile(files[0], files);

  model.value = files[0];
};

// Обработчик drag and drop
const addImage = (e: DragEvent) => {
  e.preventDefault();
  if (!inputImg.value) return;

  const files = e.dataTransfer?.files;
  if (!files || files.length === 0) return;

  processImageFile(files[0], files);

  model.value = files[0];
};

// Общая функция обработки изображения
const processImageFile = (file: File, files: FileList) => {
  // Проверяем, что это изображение
  if (!file.type.startsWith('image/')) {
    console.error('Файл не является изображением');
    return;
  }

  // Создаем DataTransfer для input
  const dataTransfer = new DataTransfer();
  dataTransfer.items.add(file);
  if (inputImg.value) {
    inputImg.value.files = dataTransfer.files;
  }

  // Создаем preview изображения
  const reader = new FileReader();
  reader.onload = (event) => {
    imagePreview.value = event.target?.result as string;
    emits('onChangeImage', {
      files: dataTransfer.files,
      previewUrl: imagePreview.value
    });
  };
  reader.readAsDataURL(file);
};

// Удаление изображения
const removeImg = () => {
  imagePreview.value = undefined;
  if (inputImg.value) {
    inputImg.value.value = '';
  }
  emits('onChangeImage', { files: null, previewUrl: undefined });
};

// Предотвращение стандартного поведения браузера
const preventDefault = (e: DragEvent) => {
  e.preventDefault();
};

// Следим за изменениями props.imagePreview
watch(() => props.imagePreview, (newVal) => {
  imagePreview.value = newVal;
});
</script>

<template>
  <div
    class="relative flex h-[260px] w-[200px] items-center justify-center border-2 border-dashed rounded-lg overflow-hidden hover:border-primary transition-colors"
    @drop="addImage"
    @dragover="preventDefault"
    @dragleave="preventDefault"
  >
    <template v-if="imagePreview">
      <div class="relative h-full w-full">
        <div class="absolute right-2 top-2 z-10">
          <Button
            variant="destructive"
            size="sm"
            type="button"
            @click="removeImg"
          >
            Удалить
          </Button>
        </div>
        <img
          :src="imagePreview"
          alt="Preview изображения"
          class="h-full w-full object-cover"
        />
      </div>
    </template>
    <template v-else>
      <div class="flex flex-col items-center gap-2 p-4 text-center">
        <i class="bi bi-cloud-arrow-up text-4xl text-muted-foreground"></i>
        <p class="text-sm text-muted-foreground">
          Перетащите изображение сюда или кликните для выбора
        </p>
      </div>
    </template>

    <input
      ref="inputImg"
      type="file"
      accept="image/*"
      class="absolute inset-0 h-full w-full cursor-pointer opacity-0"
      @change="onChangeFileInput"
    />

    <div
      v-if="isLoad"
      class="absolute inset-0 flex flex-col items-center justify-center bg-background/80 backdrop-blur-sm"
    >
      <div class="h-8 w-8 animate-spin rounded-full border-4 border-primary border-t-transparent"></div>
      <div class="mt-3 text-sm text-muted-foreground">
        Загрузка изображения...
      </div>
    </div>
  </div>
</template>
