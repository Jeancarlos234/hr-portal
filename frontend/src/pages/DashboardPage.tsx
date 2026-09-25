    import { useAuth } from '../hooks/useAuth';

    export function DashboardPage() {
    const { user, logout } = useAuth();

    const handleLogout = async () => {
        await logout();
        window.location.href = '/login';
    };

    return (
        <div style={{ padding: '2rem', fontFamily: 'sans-serif' }}>
        <h1>Dashboard</h1>
        <p>¡Bienvenido, <strong>{user?.name}</strong>!</p>

        <h2>Tu información:</h2>
        <ul>
            <li><strong>Email:</strong> {user?.email}</li>
            <li><strong>Empresa:</strong> {user?.company?.name ?? '(sin empresa)'}</li>
            <li><strong>Roles:</strong> {user?.roles.map((r) => r.name).join(', ')}</li>
            <li><strong>Permisos:</strong> {user?.permissions.length} asignados</li>
        </ul>

        <h2>Tus permisos:</h2>
        <ul>
            {user?.permissions.slice(0, 10).map((p) => <li key={p}>{p}</li>)}
        </ul>

        <button
            onClick={handleLogout}
            style={{
            marginTop: '2rem',
            padding: '0.75rem 1.5rem',
            background: '#e53e3e',
            color: 'white',
            border: 'none',
            borderRadius: '8px',
            cursor: 'pointer',
            fontSize: '1rem',
            }}
        >
            Cerrar sesión
        </button>
        </div>
    );
    }