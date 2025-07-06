import { post } from './query';
import { DeleteImageResponse, DeleteImageParams } from '@/types/dog';

const deleteImage = async (id: number): Promise<DeleteImageResponse> => {
  const response = await post<DeleteImageResponse>(
    route('admin.dog.delete-image', { id: id }),
    { id } as DeleteImageParams
  );
  return response.data;
};

export default {
  deleteImage,
};
