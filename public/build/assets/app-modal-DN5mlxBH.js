import{r as l,j as e}from"./app-C3oe6NE7.js";import{B as w}from"./button-DZSTdWqi.js";import{X as h}from"./x-PTxNss0E.js";function k({open:t,title:o,description:s,children:m,footer:d,onClose:a,maxWidth:c="4xl",closeOnOverlayClick:u=!0,closeOnEscape:n=!0}){const i=l.useId(),x=l.useId();if(l.useEffect(()=>{if(!t||!n)return;const r=b=>{b.key==="Escape"&&a()};return document.addEventListener("keydown",r),()=>{document.removeEventListener("keydown",r)}},[t,n,a]),l.useEffect(()=>{if(!t)return;const r=document.body.style.overflow;return document.body.style.overflow="hidden",()=>{document.body.style.overflow=r}},[t]),!t)return null;const f={sm:"max-w-sm",md:"max-w-md",lg:"max-w-lg",xl:"max-w-xl","2xl":"max-w-2xl","3xl":"max-w-3xl","4xl":"max-w-4xl","5xl":"max-w-5xl","6xl":"max-w-6xl","7xl":"max-w-7xl",full:"max-w-[calc(100vw-2rem)]"};return e.jsx("div",{className:"fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 backdrop-blur-sm",role:"dialog","aria-modal":"true","aria-labelledby":i,"aria-describedby":s?x:void 0,onMouseDown:r=>{u&&r.target===r.currentTarget&&a()},children:e.jsxs("div",{className:`
                    flex
                    max-h-[calc(100vh-2rem)]
                    w-full
                    ${f[c]}
                    flex-col
                    overflow-hidden
                    rounded-2xl
                    border
                    border-neutral-200
                    bg-white
                    shadow-2xl
                    dark:border-neutral-800
                    dark:bg-neutral-900
                `,children:[e.jsxs("div",{className:"flex shrink-0 items-start justify-between gap-4 border-b border-neutral-200 px-6 py-5 dark:border-neutral-800",children:[e.jsxs("div",{className:"min-w-0",children:[e.jsx("h2",{id:i,className:"text-xl font-semibold tracking-tight",children:o}),s&&e.jsx("p",{id:x,className:"mt-1 text-sm text-neutral-500 dark:text-neutral-400",children:s})]}),e.jsx(w,{type:"button",variant:"ghost",size:"icon",onClick:a,"aria-label":"Fechar",className:"shrink-0",children:e.jsx(h,{className:"size-4"})})]}),e.jsx("div",{className:"min-h-0 flex-1 overflow-y-auto px-6 py-6",children:m}),d&&e.jsx("div",{className:"flex shrink-0 items-center justify-end gap-3 border-t border-neutral-200 bg-neutral-50 px-6 py-4 dark:border-neutral-800 dark:bg-neutral-950/50",children:d})]})})}export{k as A};
