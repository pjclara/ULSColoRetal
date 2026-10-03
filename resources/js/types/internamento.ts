import type {
    CasoSocialItem,
    ClavienDindoItem,
    DestinoItem,
    EstadoDaAltaItem,
    OrigemDoInternamentoItem,
    User,
    LocalizacaoItem,
    origensDaReferenciacaoItem
    
} from './type';

export type InternamentoOptions = {
    origensInternamento: OrigemDoInternamentoItem[];
    estadosAlta: EstadoDaAltaItem[];
    responsaveis: User[];
    clavienDindo: ClavienDindoItem[];
    destinos: DestinoItem[];
    casosSociais: CasoSocialItem[];
    localizacoes: LocalizacaoItem[];
    origensDaReferenciacao?: origensDaReferenciacaoItem[];
};

export type LookupOption = {
    id: number;
    nome: string;
};

/** Grau de Clavien-Dindo tipicamente associado, usado para sugerir a classificação. */
export type ResolucaoComplicacaoOption = LookupOption & {
    clavien_dindo_id?: number | null;
};

export type IntervencaoOption = LookupOption & {
    centro_de_referencia: boolean;
    cirurgia_de_ressecao: boolean;
};

export type BlocoOperatorioCRSOptions = {
    intencoes: LookupOption[];
    estomasDeProtecao: LookupOption[];
    locaisExtracaoPeca: LookupOption[];
    tiposDeDreno: LookupOption[];
    aderencias: LookupOption[];
    tiposDeResseccao: LookupOption[];
    neoplasiasResiduais: LookupOption[];
    perdasHematicas: LookupOption[];
};

export type IntervencaoDescricaoOptions = {
    anastemoseModos: LookupOption[];
    anastemoseVias: LookupOption[];
    anastemoseSentidos: LookupOption[];
    tiposDeReconstrucao: LookupOption[];
    localizacoesAnastemose: LookupOption[];
    confirmacoesAnastemose: LookupOption[];
    qualidadesPecaOperatoria: LookupOption[];
};

export type BlocoOperatorioOptions = {
    tiposDeCirurgia: LookupOption[];
    tiposDeAbordagem: LookupOption[];
    reIntervencoesNaoProgramadas: LookupOption[];
    intervencoes: IntervencaoOption[];
    crs: BlocoOperatorioCRSOptions;
    descricao: IntervencaoDescricaoOptions;
};