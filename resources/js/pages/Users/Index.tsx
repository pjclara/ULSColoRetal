import { AppPageHeader } from '@/components/app/app-page-header';
import { AppPagination } from '@/components/app/app-pagination';
import { AppTable, AppTableColumn } from '@/components/app/app-table';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/react';
import { useState } from 'react';
import CreateOrUpdateUserModal from './CreateOrUpdateUserModal';

type Props = {
    users: {
        data: {
            id: number;
            name: string;
            abreviatura?: string | null;
            email: string;
            numero_mecanografico?: string | null;
            categoria?: string | null;
            especialidade?: string | null;
            ativo: boolean;
        }[];
        links: any[];
        from?: number | null;
        to?: number | null;
        total?: number | null;
    };
};

type User = {
    id: number;
    name: string;
    abreviatura?: string | null;
    email: string;
    numero_mecanografico?: string | null;
    categoria?: string | null;
    especialidade?: string | null;
    ativo: boolean;
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Utilizadores',
        href: '/users',
    },
];

export default function UsersIndex({ users }: Props) {
    const [showModal, setShowModal] = useState(false);
    const [editing, setEditing] = useState<User | null>(null);
    const [deleting, setDeleting] = useState<User | null>(null);
    const [deletingLoading, setDeletingLoading] = useState(false);
    const [openModal, setOpenModal] = useState(false);

    const columns: AppTableColumn<User>[] = [
        {
            key: 'name',
            label: 'Nome',
        },
        {
            key: 'email',
            label: 'Email',
        },
        {
            key: 'ativo',
            label: 'Ativo',
        },
        {
            key: 'acoes',
            label: 'Ações',
            className: 'text-right',
            render: (user) => (
                <div className="flex justify-end gap-2">
                    <Button
                        size="sm"
                        onClick={() => {
                            setEditing(user);
                            setOpenModal(true);
                        }}
                    >
                        Editar
                    </Button>
                    <Button className="bg-green-500" size="sm" onClick={() => router.get(`/users/${user.id}`)}>
                        Ver
                    </Button>

                    <Button size="sm" variant="destructive" onClick={() => setDeleting(user)}>
                        Eliminar
                    </Button>
                </div>
            ),
        },
    ];
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Utilizadores" />
            <div className="p-6">
                <AppPageHeader
                    title="Utilizadores"
                    description="Gestão e consulta de utilizadores"
                    action={
                        <Button
                            onClick={() => {
                                setEditing(null);
                                setOpenModal(true);
                            }}
                        >
                            Novo utilizador
                        </Button>
                    }
                />

                <AppTable columns={columns} data={users.data} rowKey={(user) => user.id} />

                <AppPagination links={users.links} from={users.from ?? undefined} to={users.to ?? undefined} total={users.total ?? undefined} />
            </div>

            <CreateOrUpdateUserModal open={openModal} onClose={() => setOpenModal(false)} user={editing} />
        </AppLayout>
    );
}
