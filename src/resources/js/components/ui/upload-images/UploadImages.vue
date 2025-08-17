<script setup lang="ts">
import { ref, watch } from 'vue';

import ImageItem from '@/components/ui/upload-images/ImageItem.vue';

const model = defineModel<FileList | null>();
// const emits = defineEmits<{
//   (e: 'change', payload: string[]): void;  // Отправляем массив строк (base64)
//   (e: 'delete'): void;
// }>();

const inputImg = ref<HTMLInputElement | null>(null);
const images = ref<string[]>([]);

// Обработчик изменения через input файла
const onChangeFileInput = (e: Event) => {
  const target = e.target as HTMLInputElement;
  const newFiles = target.files;

  if (!newFiles || newFiles.length === 0) {
    return;
  }

  // Создаем массив из существующих файлов (если они есть)
  const existingFiles = model.value ? Array.from(model.value) : [];

  // Добавляем новые файлы
  const allFiles = [...existingFiles, ...Array.from(newFiles)];

  // Создаем новый FileList (через DataTransfer)
  const dataTransfer = new DataTransfer();
  allFiles.forEach(file => dataTransfer.items.add(file));

  // Обновляем model.value
  model.value = dataTransfer.files;

  // Очищаем input, чтобы можно было выбирать те же файлы снова
  target.value = '';
};

// Обработчик drag and drop
const addImage = (e: DragEvent) => {
  e.preventDefault();
  if (!inputImg.value) return;

  const files = e.dataTransfer?.files;

  if (!files || files.length === 0) return;

  model.value = files;
};

// Общая функция обработки изображения
const processImageFile = (files: FileList) => {

  images.value = [];
  for (const file of files){
    // Проверяем, что это изображение
    if (!file.type.startsWith('image/')) {
      console.error('Файл не является изображением');
      continue;
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
      if (event.target?.result) {
        images.value.push(event.target.result as string); // Без optional chaining!
      }
    };
    reader.readAsDataURL(file);
  }
};

// Удаление изображения
const removeImg = (index: number) => {
  if (images.value && index >= 0 && index < images.value.length) {
    images.value = images.value.filter((_, i) => i !== index);

    if (model.value instanceof FileList) {
      // Создаем новый FileList без удаленного элемента
      const dataTransfer = new DataTransfer();
      Array.from(model.value)
        .filter((_, i) => i !== index)
        .forEach(file => dataTransfer.items.add(file));
      model.value = dataTransfer.files;
    } else if (Array.isArray(model.value)) {
      model.value = model.value.filter((_, i) => i !== index);
    }
  }
};

// Предотвращение стандартного поведения браузера
const preventDefault = (e: DragEvent) => {
  e.preventDefault();
};

// Следим за изменениями props.imagePreview
watch(
  () => model.value,
  (newVal) => {
    if(newVal){
      processImageFile(newVal);
    }
},
  { deep: true });
</script>

<template>
  <div class="flex flex-col gap-2">
    <div
      class="relative flex h-[60px] w-full items-center justify-center border-2 border-dashed rounded-lg overflow-hidden hover:border-primary transition-colors"
      @drop="addImage"
      @dragover="preventDefault"
      @dragleave="preventDefault"
    >
      <div class="flex flex-col items-center gap-2 p-4 text-center">
        <i class="bi bi-cloud-arrow-up text-4xl text-muted-foreground"></i>
        <p class="text-sm text-muted-foreground">
          Перетащите изображение сюда или кликните для выбора
        </p>
      </div>

      <input
        ref="inputImg"
        multiple
        type="file"
        accept="image/*"
        class="absolute inset-0 h-full w-full cursor-pointer opacity-0"
        @change="onChangeFileInput"
      />

    </div>

    <div class="flex flex-wrap w-full gap-1 overflow-hidden">
      <template v-for="(image, index) in images">
        <ImageItem
          :image="image"
          :index="index"
          @remove="removeImg"
        />
      </template>
    </div>
  </div>
</template>
