import { type DateValue } from '@internationalized/date';

export interface DogSelectList {
  id: number;
  name: string;
}

export interface DogListItem {
  id: number;
  name: string;
  birthdate: string | null;
  description: string;
  type: string;
  status: string;
  parent_id: number | null;
  image_link: string | null;
}

export type Dog = {
  id: number;
  name: string;
  birthdate: string | null;
  description: string;
  type: string;
  status: string;
  parent_id: number | null;
  image: File | null;
  images: string[] | null;
}

// export type DogFormData = {
//   name: string;
//   birthdate:  DateValue | null;
//   description: string;
//   type: string;
//   status: string;
//   parent_id: number | null;
//   image: File | null;
//   slider_images: string[] | null;
// }

export interface DeleteImageResponse {
  success: boolean;
  message?: string;
  deletedId?: number;
  // Другие возможные поля ответа
}

export interface DeleteImageParams {
  id: number;
  // Дополнительные параметры, если нужны
}

export interface DogImageItem {
  id: number;
  image_link: string;
}
