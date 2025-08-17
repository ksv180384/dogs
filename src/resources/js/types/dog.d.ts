import { type DateValue } from '@internationalized/date';

export interface DogSelectList {
  id: number;
  name: string;
}

export interface DogListItem {
  id: number;
  name: string;
  gender: string;
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
  gender: 'male' | 'female';
  birthdate: string | null;
  description: string;
  type: string;
  status: string;
  parent_id: number | null;
  image: File | null;
  images: ImageGalleryItem[] | null;
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
}

export interface DeleteImageParams {
  id: number;
}

export interface DogImageItem {
  id: number;
  image_link: string;
}

export interface ImageGalleryItem {
  id: number;
  image_link: string;
}

export interface DeleteImageGalleryResponse {
  message: string;
  images: ImageGalleryItem[];
}

export interface DeleteImageGalleryParams {
  id: number;
}
