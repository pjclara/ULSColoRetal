import { NavFooter } from '@/components/nav-footer';
import { NavMain } from '@/components/nav-main';
import { NavUser } from '@/components/nav-user';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/react';
import { BarChart3, CalendarDays, Folder, LayoutGrid, UserCog, Users } from 'lucide-react';
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
        title: 'Auditoria',
        url: '/auditoria',
        icon: BarChart3,
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
];

const footerNavItems: NavItem[] = [];

export function AppSidebar() {
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
                <NavMain items={dashboardNavItems} title="Dasboard"/>
                <NavMain items={blocoNavItems} title="Bloco"/>
                <NavMain items={mainNavItems} title="Gestão"/>
                <NavMain items={administracaoNavItems} title="Administração" />
            </SidebarContent>

            <SidebarFooter>
                <NavFooter items={footerNavItems} className="mt-auto" />
                <NavUser />
            </SidebarFooter>
        </Sidebar>
    );
}
