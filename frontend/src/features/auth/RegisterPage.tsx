    import { useState } from 'react';
    import { useForm } from 'react-hook-form';
    import { zodResolver } from '@hookform/resolvers/zod';
    import { z } from 'zod';
    import { Link, useNavigate } from 'react-router-dom';
    import { AuthLayout } from '../../layouts/AuthLayout';
    import { useAuth } from '../../hooks/useAuth';
    import { parseApiError } from '../../services/api';
    import styles from '../../style/RegisterPage.module.css';

    const registerSchema = z
    .object({
        name: z.string().min(2, 'Mínimo 2 caracteres'),
        email: z.string().min(1, 'El correo es obligatorio').email('Correo inválido'),
        password: z.string().min(8, 'Mínimo 8 caracteres'),
        password_confirmation: z.string(),
        phone: z.string().optional(),
    })
    .refine((d) => d.password === d.password_confirmation, {
        message: 'Las contraseñas no coinciden',
        path: ['password_confirmation'],
    });

    type RegisterFormData = z.infer<typeof registerSchema>;

    export function RegisterPage() {
    const navigate = useNavigate();
    const { register: signUp } = useAuth();
    const [serverError, setServerError] = useState<string | null>(null);

    const {
        register,
        handleSubmit,
        formState: { errors, isSubmitting },
    } = useForm<RegisterFormData>({
        resolver: zodResolver(registerSchema),
        defaultValues: {
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        phone: '',
        },
    });

    const onSubmit = async (data: RegisterFormData) => {
        setServerError(null);
        try {
        await signUp(data);
        navigate('/dashboard', { replace: true });
        } catch (err) {
        const { message } = parseApiError(err);
        setServerError(message);
        }
    };

    return (
        <AuthLayout title="Crear cuenta" subtitle="Registrate para comenzar a usar el sistema">
        <form onSubmit={handleSubmit(onSubmit)} className={styles.form} noValidate>
            {serverError && <div className={styles.errorAlert}>{serverError}</div>}

            <div className={styles.field}>
            <label className={styles.label}>Nombre completo</label>
            <input className={styles.input} {...register('name')} placeholder="Juan Pérez" />
            {errors.name && <span className={styles.fieldError}>{errors.name.message}</span>}
            </div>

            <div className={styles.field}>
            <label className={styles.label}>Correo electrónico</label>
            <input type="email" className={styles.input} {...register('email')} placeholder="tu@empresa.com" />
            {errors.email && <span className={styles.fieldError}>{errors.email.message}</span>}
            </div>

            <div className={styles.field}>
            <label className={styles.label}>Teléfono (opcional)</label>
            <input className={styles.input} {...register('phone')} placeholder="+593 99 999 9999" />
            </div>

            <div className={styles.field}>
            <label className={styles.label}>Contraseña</label>
            <input type="password" className={styles.input} {...register('password')} placeholder="Mínimo 8 caracteres" />
            {errors.password && <span className={styles.fieldError}>{errors.password.message}</span>}
            </div>

            <div className={styles.field}>
            <label className={styles.label}>Confirmar contraseña</label>
            <input type="password" className={styles.input} {...register('password_confirmation')} placeholder="Repetí la contraseña" />
            {errors.password_confirmation && (
                <span className={styles.fieldError}>{errors.password_confirmation.message}</span>
            )}
            </div>

            <button type="submit" className={styles.submit} disabled={isSubmitting}>
            {isSubmitting ? 'Creando cuenta...' : 'Crear cuenta'}
            </button>

            <p className={styles.footer}>
            ¿Ya tenés cuenta? <Link to="/login" className={styles.link}>Iniciá sesión</Link>
            </p>
        </form>
        </AuthLayout>
    );
    }