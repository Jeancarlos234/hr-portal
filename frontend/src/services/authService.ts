    import api, { tokenStorage } from './api';
    import type {
    AuthResponse,
    LoginPayload,
    MeResponse,
    RegisterPayload,
    User,
    } from '../types/auth';

    export const authService = {
    /**
     * Login: POST /api/auth/login
     */
    async login(payload: LoginPayload): Promise<{ user: User; token: string }> {
        const { data } = await api.post<AuthResponse>('/auth/login', payload);
        tokenStorage.set(data.data.token);
        return data.data;
    },

    /**
     * Registro: POST /api/auth/register
     */
    async register(payload: RegisterPayload): Promise<{ user: User; token: string }> {
        const { data } = await api.post<AuthResponse>('/auth/register', payload);
        tokenStorage.set(data.data.token);
        return data.data;
    },

    /**
     * Logout: POST /api/auth/logout
     */
    async logout(): Promise<void> {
        try {
        await api.post('/auth/logout');
        } finally {
        tokenStorage.clear();
        }
    },

    /**
     * Usuario actual: GET /api/auth/me
     */
    async me(): Promise<User> {
        const { data } = await api.get<MeResponse>('/auth/me');
        return data.data;
    },
    };