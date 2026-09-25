    import { useContext } from 'react';
    import { AuthContext } from '../contexts/AuthContext';
    import type { AuthContextValue } from '../types/auth';

    export function useAuth(): AuthContextValue {
    const ctx = useContext(AuthContext);

    if (!ctx) {
        throw new Error('useAuth debe usarse dentro de <AuthProvider>');
    }

    return ctx;
    }