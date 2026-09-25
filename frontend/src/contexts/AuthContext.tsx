    import {
    createContext,
    useCallback,
    useEffect,
    useMemo,
    useState,
    type ReactNode,
    } from 'react';
    import { authService } from '../services/authService';
    import { tokenStorage } from '../services/api';
    import type {
    AuthContextValue,
    LoginPayload,
    RegisterPayload,
    User,
    } from '../types/auth';

    export const AuthContext = createContext<AuthContextValue | null>(null);

    interface AuthProviderProps {
    children: ReactNode;
    }

    export function AuthProvider({ children }: AuthProviderProps) {
    const [user, setUser] = useState<User | null>(null);
    const [token, setToken] = useState<string | null>(tokenStorage.get());
    const [isLoading, setIsLoading] = useState<boolean>(true);

    // ==========================================
    // EFECTO: validar sesión al iniciar
    // ==========================================
    useEffect(() => {
        const init = async () => {
        const storedToken = tokenStorage.get();

        if (!storedToken) {
            setIsLoading(false);
            return;
        }

        try {
            const currentUser = await authService.me();
            setUser(currentUser);
            setToken(storedToken);
        } catch {
            tokenStorage.clear();
            setUser(null);
            setToken(null);
        } finally {
            setIsLoading(false);
        }
        };

        void init();
    }, []);

    // ==========================================
    // LOGIN
    // ==========================================
    const login = useCallback(async (payload: LoginPayload) => {
        const { user: loggedUser, token: newToken } = await authService.login(payload);
        setUser(loggedUser);
        setToken(newToken);
    }, []);

    // ==========================================
    // REGISTER
    // ==========================================
    const register = useCallback(async (payload: RegisterPayload) => {
        const { user: newUser, token: newToken } = await authService.register(payload);
        setUser(newUser);
        setToken(newToken);
    }, []);

    // ==========================================
    // LOGOUT
    // ==========================================
    const logout = useCallback(async () => {
        try {
        await authService.logout();
        } finally {
        setUser(null);
        setToken(null);
        }
    }, []);

    // ==========================================
    // HELPERS
    // ==========================================
    const hasRole = useCallback(
        (role: string): boolean => user?.roles.some((r) => r.slug === role) ?? false,
        [user],
    );

    const hasPermission = useCallback(
        (permission: string): boolean => user?.permissions.includes(permission) ?? false,
        [user],
    );

    // ==========================================
    // VALOR DEL CONTEXTO
    // ==========================================
    const value = useMemo<AuthContextValue>(
        () => ({
        user,
        token,
        isAuthenticated: !!user && !!token,
        isLoading,
        login,
        register,
        logout,
        hasRole,
        hasPermission,
        }),
        [user, token, isLoading, login, register, logout, hasRole, hasPermission],
    );

    return <AuthContext.Provider value={value}>{children}</AuthContext.Provider>;
    }