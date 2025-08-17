import { post } from './query';
import {
  DeleteImageResponse,
  DeleteImageParams,
  DeleteImageGalleryParams,
  DeleteImageGalleryResponse
} from '@/types/dog';

const deleteImage = async (id: number): Promise<DeleteImageResponse> => {
  const response = await post<DeleteImageResponse>(
    route('admin.dog.delete-image', { id: id }),
    { id } as DeleteImageParams
  );
  return response.data;
};

const deleteGalleryImage = async (id: number): Promise<DeleteImageGalleryResponse> => {
  const response = await post<DeleteImageGalleryResponse>(
    route('admin.dog.delete-gallery-image', { id: id }),
    { id } as DeleteImageGalleryParams
  );

  if ('message' in response && 'images' in response) {
    return response as DeleteImageGalleryResponse;
  }
  throw new Error('Invalid response structure');
};

export default {
  deleteImage,
  deleteGalleryImage
};
