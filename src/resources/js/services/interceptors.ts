import type { AxiosInstance, InternalAxiosRequestConfig, AxiosResponse, AxiosError } from 'axios';

interface ApiError {
  message?: string;
  errors?: Record<string, string[]>;
  status?: number;
}

interface ApiResponse<T = any> {
  data: T;
  status: number;
  message?: string;
}

const handleUnauthorizedError = (): void => {
  // Обработка неавторизованного доступа
  // Например, перенаправление на страницу входа
  // window.location.href = '/login';
  showErrorNotification('Ошибка авторизации', 'Требуется авторизация');
};

const handleValidationError = (errors: Record<string, string[]>): void => {
  // Обработка ошибок валидации
  const errorMessages = Object.values(errors).flat().join('\n');
  showErrorNotification('Ошибка валидации', errorMessages);

  // Можно также сохранить ошибки для отображения в формах
  // store.commit('setValidationErrors', errors);
};

const showErrorNotification = (title: string, error: string): void => {
  // Здесь можно интегрировать с вашей системой уведомлений
  console.error(`${title}: ${error}`);
  // Например: useToast().show({ type: 'error', title, message });
};

export const setupInterceptors = (api: AxiosInstance): void => {
  // Request interceptor
  api.interceptors.request.use(
    (config: InternalAxiosRequestConfig) => {

      return config;
    },
    (error: AxiosError) => {
      return Promise.reject(error);
    }
  );

  // Response interceptor
  api.interceptors.response.use(
    (response: AxiosResponse<ApiResponse>) => {
      // Обрабатываем успешные ответы
      return response.data.data ?? response.data;
    },
    async (error: AxiosError<ApiError>) => {
      handleApiError(error);
      return Promise.reject(error);
    }
  );
};

const handleApiError = (error: AxiosError<ApiError>): void => {
  if (!error.response) {
    if (error.request) {
      // Запрос был сделан, но ответ не получен
      showErrorNotification('Ошибка сети', 'Не удалось получить ответ от сервера');
    } else {
      // Ошибка при настройке запроса
      showErrorNotification('Ошибка', error.message);
    }
    return;
  }

  const { status, data } = error.response;

  switch (status) {
    case 401:
      handleUnauthorizedError();
      break;

    case 403:
      showErrorNotification('Ошибка доступа', 'У вас нет прав для выполнения этого действия');
      break;

    case 422:
      handleValidationError(data.errors || {});
      break;

    case 500:
      showErrorNotification('Серверная ошибка', data.message || 'Произошла ошибка на сервере');
      break;

    default:
      showErrorNotification('Ошибка', data.message || 'Произошла неизвестная ошибка');
  }
};
