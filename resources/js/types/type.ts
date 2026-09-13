export type DiagnosticoItem = {
    id: number;
    nome: string;
};

export type IntervencaoDescricaoData = {
    id: number;
    anastemose_modo_id: number | null;
    anastemose_via_id: number | null;
    anastemose_sentido_id: number | null;
    tipo_de_reconstrucao_id: number | null;
    reforco_anastemose: string | null;
    localizacao_anastemose_id: number | null;
    confirmacao_anastemose_id: number | null;
    libertacao_angulo: string | null;
    numero_cargas: number | null;
    distancia_linha_pectinea_anastemose: number | null;
    distancia_linha_pectinea_tumor: number | null;
    qualidade_peca_operatoria_id: number | null;
    comentarios: string | null;
};

export type BlocoOperatorioIntervencaoItem = {
    pivot_id: number;
    intervencao_id: number;
    nome: string | null;
    centro_de_referencia: boolean;
    cirurgia_de_ressecao: boolean;
    descricao: IntervencaoDescricaoData | null;
};

export type BlocoOperatorioCRSData = {
    id: number;
    experiencia_cirurgiao_id: number | null;
    asa: number | null;
    mortalidade: number | null;
    score_fisiologico: number | null;
    score_gravidade_cirurgico: number | null;
    preparacao_intestinal_id: number | null;
    intencao_id: number | null;
    paleativa_causa: string | null;
    duracao: number | null;
    estoma_de_protecao_id: number | null;
    local_extracao_peca_id: number | null;
    tipo_de_dreno_id: number | null;
    resseccao_multi_orgao: string | null;
    resseccao_multi_orgao_quais: string | null;
    aderencia_id: number | null;
    tipo_de_resseccao_id: number | null;
    neoplasia_residual_id: number | null;
    perdas_hematica_id: number | null;
    transfusao_intra_operatoria: string | null;
    unidades_globulos: number | null;
    protector_de_parede: string | null;
    complicacoes: string | null;
    complicacoes_quais: string | null;
};

export type InternamentoItem = {
    id?: number;
    utente_id: number;
    nome?: string;
    nome_curto?: string;
    numero_processo?: number | null;
    cama: string | null;
    localizacao_id: number | null;
    localizacao?: string;
    origem_do_internamento_id: number | null;
    data_de_entrada: string;
    dias_desde_entrada?: number | null;
    dias_internamento?: number | null;
    data_de_alta?: string | null;
    data_de_saida?: string | null;
    estado_da_alta_id?: number | null;
    responsavel_id: number | null;
    responsavel?: string;
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
    complicacaos?: {
        id: number;
        nome: string;
        resolucao_ids?: (number | string)[];
        pivot?: { resolucao?: (number | string)[] | null };
    }[];
    complicacoes?: {
        id: string;
        resolucao_ids: string[];
    }[];
    bloco_operatorios?: {
        id: number;
        data_de_inicio: string | null;
        tipo_de_cirurgia: string | null;
        tipo_de_cirurgia_id: number | null;
        tipo_de_abordagem_id: number | null;
        re_intervencao_nao_programada_id: number | null;
        resseccao_de_orgao?: string | null;
        causa_de_conversao?: string | null;
        comentarios?: string | null;
        intervencoes: BlocoOperatorioIntervencaoItem[];
        bloco_operatorio_c_r_s: BlocoOperatorioCRSData | null;
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
    nome?: string;
    numero_processo?: number | null;
    data_de_lista: string;
    estado_lista_espera: string;
    cancelar_lista_espera: boolean;
    comentarios?: string | null;
    responsavel_id: number | null;
    responsavel?: User | null;
    diagnosticos?: DiagnosticoItem[] | null;
    diagnostico_ids?: string[];
    agendamentos?: AgendamentoItem[] | null;
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

export type ResolucaoComplicacaoItem = {
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
    destino?: DestinoItem;
    responsavel?: User;
}

export type origensDaReferenciacaoItem = {
    id: number;
    nome: string;
}
 

export type AgendamentoItem = {
    id?: number;
    lista_de_espera_id: number;
    start: string;
    end: string;
    responsavel_id: number | null;
    tipo_de_agendamento_id: number;
    local_de_agendamento_id: number;
    sala_de_agendamento_id: number;
    periodo_de_agendamento_id: number;
    estado_de_agendamento_id: number;
    comentarios?: string | null;
}