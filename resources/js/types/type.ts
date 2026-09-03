export type InternamentoItem = {

    id?: number;
    utente_id: number;
    cama: string | null;
    localizacao_id: number | null;
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
    id: number | null;
    nome: string;
    data_nascimento: string | null;
    sexo_id: number | null;
    numero_utente?: number | null;
    numero_processo?: number | null;
    concelho_id?: number | null;
    centro_de_referencia?: CentroDeReferenciaItem | null;
    lista_de_esperas?: ListaDeEsperaItem[] | null;
    responsavel?: User | null;
}

export type ListaDeEsperaItem = {
    id?: number;
    utente_id: number;
    data_de_lista: string;
    estado_lista_espera: string;
    cancelar_lista_espera: boolean;
    comentarios?: string | null;
    responsavel_id: number | null;
    responsavel?: User | null;
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
export type SexoItem = {
    id: number;
    nome: string;
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

export type LocalizacaoItem = {
    id: number;
    nome: string;
}

export type CentroDeReferenciaItem = {
    id?: number;
    utente_id: number;
    data_de_diagnostico: string;
    data_de_referenciacao: string;
    origem_id: number;
    data_de_entrada: string;
    data_de_saida: string;
    destino_id: number;
    responsavel_id: number;
    comentarios: string;
    origem?: OrigemDoInternamentoItem;
}

export type origensDaReferenciacaoItem = {
    id: number;
    nome: string;
}
 