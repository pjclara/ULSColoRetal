import AppLogoIcon from '@/components/app-logo-icon';
import { type SharedData } from '@/types';
import { Head, Link, usePage } from '@inertiajs/react';
import { CalendarCheck2, ClipboardList, ShieldCheck, Users } from 'lucide-react';

const features = [
    {
        icon: Users,
        title: 'Gestão de utentes',
        description: 'Centraliza os processos clínicos e sociais de cada utente num único registo.',
    },
    {
        icon: ClipboardList,
        title: 'Casos sociais',
        description: 'Acompanha intervenções, descrições e o percurso de cada caso social.',
    },
    {
        icon: CalendarCheck2,
        title: 'Agenda de consultas',
        description: 'Organiza marcações e consultas num calendário integrado.',
    },
    {
        icon: ShieldCheck,
        title: 'Auditoria',
        description: 'Regista e consulta o histórico de alterações para garantir rastreabilidade.',
    },
];

export default function Welcome() {
    const { auth } = usePage<SharedData>().props;

    return (
        <>
            <Head title="Bem-vindo">
                <link rel="preconnect" href="https://fonts.bunny.net" />
                <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
            </Head>
            <div className="flex min-h-screen flex-col bg-background text-foreground">
                <header className="w-full border-b border-sidebar-border/70">
                    <div className="mx-auto flex w-full max-w-5xl items-center justify-between gap-4 px-6 py-4">
                        <div className="flex items-center gap-3">
                            <div className="flex size-10 shrink-0 items-center justify-center overflow-hidden rounded-md">
                                <AppLogoIcon className="size-10 object-contain" />
                            </div>
                            <div className="leading-tight">
                                <p className="text-sm font-semibold">UF Cirurgia Colorectal</p>
                                <p className="text-xs text-muted-foreground">Centro Hospitalar Leiria</p>
                            </div>
                        </div>
                       
                    </div>
                </header>

                <main className="flex flex-1 flex-col items-center justify-center px-6 py-16">
                    <div className="mx-auto flex w-full max-w-3xl flex-col items-center text-center">
                        <h1 className="text-3xl font-semibold tracking-tight sm:text-4xl">Plataforma de Gestão Clínico-Social</h1>
                        <p className="mt-4 max-w-2xl text-balance text-muted-foreground">
                            Uma aplicação dedicada à Unidade Funcional de Cirurgia Colorectal do Centro Hospitalar Leiria, para gerir utentes,
                            casos sociais, intervenções e consultas de forma centralizada e segura.
                        </p>
                        <div className="mt-8 flex items-center gap-3">
                            {auth.user ? (
                                <Link
                                    href={route('dashboard')}
                                    className="inline-block rounded-md bg-primary px-6 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90"
                                >
                                    Aceder ao painel
                                </Link>
                            ) : (
                                <Link
                                    href={route('login')}
                                    className="inline-block rounded-md bg-primary px-6 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90"
                                >
                                    Entrar na aplicação
                                </Link>
                            )}
                        </div>
                    </div>

                    <div className="mx-auto mt-16 grid w-full max-w-4xl grid-cols-1 gap-4 sm:grid-cols-2">
                        {features.map(({ icon: Icon, title, description }) => (
                            <div key={title} className="flex items-start gap-4 rounded-lg border border-sidebar-border/70 bg-card p-5 text-card-foreground">
                                <div className="flex size-10 shrink-0 items-center justify-center rounded-md bg-primary/10 text-primary">
                                    <Icon className="size-5" />
                                </div>
                                <div>
                                    <h2 className="font-medium">{title}</h2>
                                    <p className="mt-1 text-sm text-muted-foreground">{description}</p>
                                </div>
                            </div>
                        ))}
                    </div>
                </main>

                <footer className="border-t border-sidebar-border/70 py-6 text-center text-xs text-muted-foreground">
                    Unidade Funcional de Cirurgia Colorectal &middot; Centro Hospitalar Leiria
                </footer>
            </div>
        </>
    );
}
