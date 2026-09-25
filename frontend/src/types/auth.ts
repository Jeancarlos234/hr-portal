    // ==========================================
    // TIPOS DE AUTENTICACIÓN
    // ==========================================

    export interface Company {
    id: number;
    name: string;
    }

    export interface Role {
    id: number;
    name: string;
    slug: string;
    }

    export interface User {
    id: number;
    name: string;
    email: string;
    phone: string | null;
    avatar: string | null;
    status: 'active' | 'inactive';
    created_at: string;
    company: Company | null;
    roles: Role[];
    permissions: string[];
    }

    // ==========================================
    // PAYLOADS
    // ==========================================

    export interface LoginPayload {
    email: string;
    password: string;
    }

    export interface RegisterPayload {
    name: string;
    email: string;
    password: string;
    password_confirmation: string;
    phone?: string;
    company_id?: number;
    }

    // ==========================================
    // RESPUESTAS DE API
    // ==========================================

    export interface AuthResponse {
    success: boolean;
    message: string;
    data: {
        user: User;
        token: string;
    };
    }

    export interface MeResponse {
    success: boolean;
    message: string;
    data: User;
    }

    // ==========================================
    // CONTEXTO
    // ==========================================

    export interface AuthContextValue {
    user: User | null;
    token: string | null;
    isAuthenticated: boolean;
    isLoading: boolean;
    login: (payload: LoginPayload) => Promise<void>;
    register: (payload: RegisterPayload) => Promise<void>;
    logout: () => Promise<void>;
    hasRole: (role: string) => boolean;
    hasPermission: (permission: string) => boolean;
    }