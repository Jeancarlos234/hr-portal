    import type { ReactNode } from 'react';
    import styles from '../style/AuthContext.module.css';

    interface AuthLayoutProps {
    title: string;
    subtitle?: string;
    children: ReactNode;
    }

    export function AuthLayout({ title, subtitle, children }: AuthLayoutProps) {
    return (
        <div className={styles.container}>
        <div className={styles.card}>
            <div className={styles.header}>
            <h1 className={styles.title}>{title}</h1>
            {subtitle && <p className={styles.subtitle}>{subtitle}</p>}
            </div>
            <div className={styles.body}>{children}</div>
        </div>
        <p className={styles.footer}>RRHH Sistema · v1.0</p>
        </div>
    );
    }