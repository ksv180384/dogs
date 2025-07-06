import axios, {
  AxiosInstance,
  AxiosRequestConfig,
  AxiosResponse,
  AxiosError,
  Method,
  InternalAxiosRequestConfig
} from 'axios';
import { setupInterceptors } from './interceptors';

interface ApiResponse<T = any> {
  data: T;
  status: number;
  statusText: string;
  headers: any;
  config: InternalAxiosRequestConfig;
  request?: any;
}

interface ApiError<T = any> extends AxiosError<T> {
  // Можно расширить стандартную ошибку Axios
}

// Создаем базовый экземпляр Axios
const query: AxiosInstance = axios.create({
  baseURL: import.meta.env.VITE_API_BASE_URL || '/',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
  withCredentials: true,
});

// Устанавливаем интерцепторы
setupInterceptors(query);

// Основные HTTP методы с типизацией
export const get = <T = any, R = ApiResponse<T>>(
  path: string,
  config?: AxiosRequestConfig
): Promise<R> => {
  return query.get<T, R>(path, config);
};

export const post = <T = any, R = ApiResponse<T>, D = any>(
  path: string,
  data?: D,
  config?: AxiosRequestConfig<D>
): Promise<R> => {
  return query.post<T, R, D>(path, data, config);
};

export const put = <T = any, R = ApiResponse<T>, D = any>(
  path: string,
  data?: D,
  config?: AxiosRequestConfig<D>
): Promise<R> => {
  return query.put<T, R, D>(path, data, config);
};

export const del = <T = any, R = ApiResponse<T>, D = any>(
  path: string,
  data?: D,
  config?: AxiosRequestConfig<D>
): Promise<R> => {
  return query.delete<T, R, D>(path, {
    data,
    ...config
  });
};

// Дополнительные методы по необходимости
export const patch = <T = any, R = ApiResponse<T>, D = any>(
  path: string,
  data?: D,
  config?: AxiosRequestConfig<D>
): Promise<R> => {
  return query.patch<T, R, D>(path, data, config);
};

// Универсальный метод для любых HTTP запросов
export const request = <T = any, R = ApiResponse<T>, D = any>(
  config: AxiosRequestConfig<D>
): Promise<R> => {
  return query.request<T, R, D>(config);
};

export default {
  get,
  post,
  put,
  delete: del,
  patch,
  request
};
