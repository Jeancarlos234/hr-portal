    import axios, { AxiosError } from 'axios';
    import type { InternalAxiosRequestConfig } from 'axios';

    const API_URL = import.meta.env.VITE_API_URL || 'http://127.0.0.1:8000/api';
    const TOKEN_KEY = 'rrhh_token';

    // ==========================================
    // INSTANCIA DE AXIOS
    // ==========================================

    export const api = axios.create({
    baseURL: API_URL,
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    },
    timeout: 15000,
    });

    // ==========================================
    // HELPERS DE TOKEN
    // ==========================================

    export const tokenStorage = {
    get: (): string | null => localStorage.getItem(TOKEN_KEY),
    set: (token: string): void => localStorage.setItem(TOKEN_KEY, token),
    clear: (): void => localStorage.removeItem(TOKEN_KEY),
    };

    // ==========================================
    // INTERCEPTOR DE REQUEST — agrega el token
    // ==========================================

    api.interceptors.request.use(
    (config: InternalAxiosRequestConfig) => {
        const token = tokenStorage.get();
        if (token) {
        config.headers.Authorization = `Bearer ${token}`;
        }
        return config;
    },
    (error) => Promise.reject(error),
    );

    // ==========================================
    // INTERCEPTOR DE RESPONSE — maneja 401
    // ==========================================

    api.interceptors.response.use(
    (response) => response,
    (error: AxiosError) => {
        // Si el token expiró o es inválido, limpiamos y redirigimos
        if (error.response?.status === 401) {
        // No redirigir si ya estamos en /login
        const isAuthRoute =
            window.location.pathname === '/login' ||
            window.location.pathname === '/register';

        if (!isAuthRoute) {
            tokenStorage.clear();
            window.location.href = '/login';
        }
        }
        return Promise.reject(error);
    },
    );

    // ==========================================
    // HELPER PARA EXTRAER MENSAJES DE ERROR
    // ==========================================

    export interface ApiError {
    message: string;
    errors?: Record<string, string[]>;
    }

    export function parseApiError(error: unknown): ApiError {
    if (axios.isAxiosError(error)) {
        const data = error.response?.data as
        | { message?: string; errors?: Record<string, string[]> }
        | undefined;

        return {
        message: data?.message || error.message || 'Error de conexión',
        errors: data?.errors,
        };
    }
    return { message: 'Error inesperado' };
    }

    export default api;