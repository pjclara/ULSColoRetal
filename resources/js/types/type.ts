export type InternamentoItem = {
    id?: number;
    utente_id: number;
    cama: string | null;
    origem_do_internamento_id: number | null;
    data_de_entrada: string;
    data_de_alta?: string | null;
    data_de_saida?: string | null;
    estado_da_alta_id?: number | null;
    responsavel_id: number | null;
    motivo_internamento: string;
    clavien_dindo_id?: number | null;
    destino_id?: number | null;
    caso_social_id?: number | null;
    bloquear_tabela: boolean;
    comentarios?: string | null;
    diagnosticos?: {
        id: number;
        nome: string;
    }[];
}

export  type UtenteItem = {
    id: number;
    nome: string;
    data_nascimento: string;
    sexo_id: string;
    numero_utente?: number | null;
    numero_processo?: number | null;
    concelho_id?: number | null;
}

export type OrigemDoInternamentoItem = {
    id: number;
    nome: string;
}

export type EstadoDaAltaItem = {
    id: number;
    nome: string;
}

export type User = {
    id: number;
    name: string;
    email: string;
    password: string;
    username: string;
    abrev: string;
    ativo: boolean;
    equipa: string;
    sexo: string;
}

export type ClavienDindoItem = {
    id: number;
    nome: string;
}

export type DestinoItem = {
    id: number;
    nome: string;
}

export type CasoSocialItem = {
    id: number;
    nome: string;
}


export type Pagination<T> = {
    data: T[];
    links: {
        url: string | null;
        label: string;
        active: boolean;    
    }[];
    from: number | null;
    to: number | null;
    total: number | null;
}

export type Option = {
    value: string | number;
    label: string;
}