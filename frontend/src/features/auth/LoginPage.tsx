    import { useState } from 'react';
    import { useForm } from 'react-hook-form';
    import { zodResolver } from '@hookform/resolvers/zod';
    import { z } from 'zod';
    import { Link, useNavigate } from 'react-router-dom';
    import { AuthLayout } from '../../layouts/AuthLayout';
    import { useAuth } from '../../hooks/useAuth';
    import { parseApiError } from '../../services/api';
    import styles from '../../style/LoginPage.module.css';

    const loginSchema = z.object({
    email: z.string().min(1, 'El correo es obligatorio').email('Correo inválido'),
    password: z.string().min(6, 'Mínimo 6 caracteres'),
    });

    type LoginFormData = z.infer<typeof loginSchema>;

    export function LoginPage() {
    const navigate = useNavigate();
    const { login } = useAuth();
    const [serverError, setServerError] = useState<string | null>(null);

    const {
        register,
        handleSubmit,
        formState: { errors, isSubmitting },
    } = useForm<LoginFormData>({
        resolver: zodResolver(loginSchema),
        defaultValues: { email: '', password: '' },
    });

    const onSubmit = async (data: LoginFormData) => {
        setServerError(null);
        try {
        await login(data);
        navigate('/dashboard', { replace: true });
        } catch (err) {
        const { message } = parseApiError(err);
        setServerError(message);
        }
    };

    return (
        <AuthLayout title="Iniciar sesión" subtitle="Ingresá tus credenciales para continuar">
        <form onSubmit={handleSubmit(onSubmit)} className={styles.form} noValidate>
            {serverError && <div className={styles.errorAlert}>{serverError}</div>}

            <div className={styles.field}>
            <label htmlFor="email" className={styles.label}>Correo electrónico</label>
            <input
                id="email"
                type="email"
                autoComplete="email"
                className={styles.input}
                placeholder="tu@empresa.com"
                {...register('email')}
            />
            {errors.email && <span className={styles.fieldError}>{errors.email.message}</span>}
            </div>

            <div className={styles.field}>
            <label htmlFor="password" className={styles.label}>Contraseña</label>
            <input
                id="password"
                type="password"
                autoComplete="current-password"
                className={styles.input}
                placeholder="••••••••"
                {...register('password')}
            />
            {errors.password && <span className={styles.fieldError}>{errors.password.message}</span>}
            </div>

            <button type="submit" className={styles.submit} disabled={isSubmitting}>
            {isSubmitting ? 'Ingresando...' : 'Ingresar'}
            </button>

            <p className={styles.footer}>
            ¿No tenés cuenta? <Link to="/register" className={styles.link}>Registrate</Link>
            </p>
        </form>
        </AuthLayout>
    );
    }