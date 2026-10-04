import { NavFooter } from '@/components/nav-footer';
import { NavMain } from '@/components/nav-main';
import { NavUser } from '@/components/nav-user';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem, type SharedData } from '@/types';
import { Link, usePage } from '@inertiajs/react';
import { CalendarDays, Folder, LayoutGrid, LineChart, ListChecks, ShieldCheck, UserCog, Users } from 'lucide-react';
import AppLogo from './app-logo';



const dashboardNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        url: '/dashboard',
        icon: LayoutGrid,
    },
];

// bloco

const blocoNavItems: NavItem[] = [
    {
        title: 'Lista de espera',
        url: '/lista-de-esperas',
        icon: Folder,
    },
    {
        title: 'Calendário',
        url: '/agendamentos/calendario',
        icon: CalendarDays,
    },
];



const mainNavItems: NavItem[] = [
    {
        title: 'Internamentos',
        url: '/internamentos',
        icon: Folder,
    },
    {
        title: 'Estatísticas',
        url: '/estatisticas',
        icon: LineChart,
    },
];

const administracaoNavItems: NavItem[] = [
    {
        title: 'Utilizadores',
        url: '/users',
        icon: Users,
    },
    {
        title: 'Roles & Permissões',
        url: '/access-control',
        icon: UserCog,
    },
    {
        title: 'Resoluções de Complicação',
        url: '/resolucoes-complicacao',
        icon: ListChecks,
    },
];

// só visível ao superAdmin
const superAdminNavItems: NavItem[] = [
    {
        title: 'Todos os internamentos',
        url: '/admin/internamentos',
        icon: ShieldCheck,
    },
];

const footerNavItems: NavItem[] = [];

export function AppSidebar() {
    const { auth } = usePage<SharedData>().props;

    return (
        <Sidebar collapsible="icon" variant="inset">
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton size="lg" asChild>
                            <Link href="/dashboard" prefetch>
                                <AppLogo />
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>

            <SidebarContent>
                <NavMain items={dashboardNavItems} title="Dashboard"/>
                <NavMain items={blocoNavItems} title="Bloco"/>
                <NavMain items={mainNavItems} title="Gestão"/>
                <NavMain items={administracaoNavItems} title="Administração" />
                {auth.isSuperAdmin && <NavMain items={superAdminNavItems} title="Super Admin" />}
            </SidebarContent>

            <SidebarFooter>
                <NavFooter items={footerNavItems} className="mt-auto" />
                <NavUser />
            </SidebarFooter>
        </Sidebar>
    );
}
