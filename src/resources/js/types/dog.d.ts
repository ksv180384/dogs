
export interface DogSelectList {
  id: number;
  name: string;
}

export type DogFormData = {
  name: string;
  description: string;
  type: string;
  status: string;
  parent_id: number | null;
  image: File | null;
}
