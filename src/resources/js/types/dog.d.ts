import { type DateValue } from '@internationalized/date';

export interface DogSelectList {
  id: number;
  name: string;
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
}

export type DogFormData = {
  name: string;
  birthdate:  DateValue | null;
  description: string;
  type: string;
  status: string;
  parent_id: number | null;
  image: File | null;
}

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
