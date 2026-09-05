import { ImgHTMLAttributes } from 'react';

/** Logótipo da Unidade Funcional de Cirurgia Colorectal (Centro Hospitalar Leiria), a entidade responsável pela app. */
export default function AppLogoIcon(props: ImgHTMLAttributes<HTMLImageElement>) {
    return <img src="/img/logotipo.png" alt="Unidade Funcional de Cirurgia Colorectal" {...props} />;
}
