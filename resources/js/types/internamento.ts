import type {
    CasoSocialItem,
    ClavienDindoItem,
    DestinoItem,
    EstadoDaAltaItem,
    OrigemDoInternamentoItem,
    User,
    LocalizacaoItem,
} from './type';

export type InternamentoOptions = {
    origensInternamento: OrigemDoInternamentoItem[];
    estadosAlta: EstadoDaAltaItem[];
    responsaveis: User[];
    clavienDindo: ClavienDindoItem[];
    destinos: DestinoItem[];
    casosSociais: CasoSocialItem[];
    localizacoes: LocalizacaoItem[];
};