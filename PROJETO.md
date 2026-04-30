# Documento de Premissas — Site ChamaTrina
**Fraternidade Essência da Chama Trina**
**Domínio:** chamatrina.org.br | **Hospedagem:** HostGator (Plano Start)
**Data de criação:** 2026-04-29

---

## 1. Identidade do Projeto

| Campo | Valor |
|---|---|
| Nome oficial | Fraternidade Essência da Chama Trina |
| Domínio | chamatrina.org.br |
| Localização | Canoas - RS |
| Hosting | HostGator (Plano Start — upgrade disponível se necessário) |
| E-mail de contato | csperansa@gmail.com |
| WhatsApp | +55 51 99256-3279 |

**Missão do site:** Apresentar a Fraternidade Essência da Chama Trina como uma fraternidade movida pela Umbanda, divulgando seus trabalhos, atendimentos, vivências, cursos e práticas integradas com as Medicinas da Floresta (Ayahuasca, Rapé, Tabaco e Sananga).

**Impressão desejada ao primeiro acesso:** O visitante deve compreender imediatamente que se trata de uma Fraternidade espiritual, séria e acolhedora, movida pela Umbanda.

---

## 2. Público-Alvo

O site deve atender três perfis simultaneamente:

1. **Praticantes de Umbanda** — pessoas que já conhecem a tradição e buscam uma casa espiritual
2. **Curiosos e iniciantes** — pessoas sem contato prévio com Umbanda ou espiritualidade afro-brasileira
3. **Interessados nas Medicinas da Floresta** — pessoas que buscam cerimônias com Ayahuasca, Rapé, Tabaco ou Sananga

A linguagem do site deve ser acolhedora, respeitosa e acessível para os três grupos.

---

## 3. Relação entre Umbanda e Medicinas da Floresta

As práticas com Medicinas da Floresta (Ayahuasca, Rapé, Tabaco, Sananga) são **integradas ou complementares** à Umbanda dentro da Fraternidade — não são práticas separadas. O site deve comunicar essa integração de forma clara e natural.

---

## 4. Stack Tecnológica

| Componente | Tecnologia |
|---|---|
| Linguagem backend | PHP 7+ (migrar para PHP 8 quando possível) |
| Banco de dados | MySQL (disponível no HostGator) |
| Frontend | HTML5, CSS3, JavaScript vanilla |
| Formulários | Formspree (atual) → migrar para sistema próprio com MySQL |
| Analytics | Google Analytics 4 (G-VDS7NJM3E4) |
| Deploy | Servidor Apache no HostGator |

**Princípio:** Sem frameworks externos desnecessários. Tudo em PHP puro + MySQL para manter simplicidade e compatibilidade com o plano de hospedagem atual.

---

## 5. Identidade Visual

### Paleta de Cores (baseada no logo)
O logo é uma roda cromática completa com espiral central e brilho branco luminoso. As cores primárias a extrair para o site:

| Papel | Cor | Hex sugerido |
|---|---|---|
| Fundo principal | Violeta profundo | `#1a0533` |
| Fundo secundário | Índigo escuro | `#0d0f2b` |
| Destaque primário | Dourado/âmbar | `#f5a623` |
| Destaque secundário | Magenta | `#c0186c` |
| Acento terciário | Verde esmeralda | `#1db86a` |
| Texto principal | Branco suave | `#f0f0f0` |
| Texto secundário | Lilás claro | `#c8b8e8` |
| Botões CTA | Dourado com hover âmbar | `#f5a623` → `#e08c0a` |

### Diretrizes Visuais
- **Estilo:** Clean, místico, vibrante — mais colorido que o tema atual (navy escuro)
- **Gradientes:** Suaves, usando a paleta do logo (violeta → índigo → magenta)
- **Tipografia:** Fonte serifada elegante para títulos, sans-serif para corpo
- **Elementos místicos:** Sutis — evitar exageros. Espaçamento generoso, respiração visual
- **Logo:** Sempre presente no header com destaque. Arquivo: `img/logo.png`
- **Responsividade:** Mobile-first, funcionando em todas as resoluções

---

## 6. Estrutura de Páginas

### 6.1 Páginas existentes (manter e melhorar)

| Página | Arquivo | Status | Ação |
|---|---|---|---|
| Home | `index.php` | Funcional | Redesign visual |
| Sobre | `sobre.php` | Funcional | Revisar textos + redesign |
| Vivências | `vivencias.php` | Funcional | Unificar com sistema de eventos |
| Galeria | `galeria.php` | Funcional | Manter + melhorar |
| Contato | `contato.php` | Funcional | Manter |
| Evento Sagrado Masculino | `evento-sagrado-masculino.php` | Funcional | Migrar para sistema dinâmico |

### 6.2 Páginas a completar

| Página | Arquivo | Prioridade | Descrição |
|---|---|---|---|
| Atendimentos | `atendimentos.php` | Alta | Consultas espirituais, limpezas, passes — presencial/online/agendamento |
| Cursos e Workshops | `cursos.php` | Alta | Listagem dinâmica: Curso de Ervas, Workshop de Banhos, Defumações, Cachimbo |
| Eventos (unificado) | `eventos.php` | Alta | Única página de eventos com categorias dinâmicas |
| Blog | `blog/index.php` | Média | Sistema próprio com painel admin + MySQL |
| Anamnese | `anamnese.php` | Alta | Formulário de inscrição para Medicinas da Floresta |

### 6.3 Páginas a remover ou redirecionar
- `evento-sagrado-feminino.php` → migrar para sistema dinâmico de eventos
- `evento-cerimonias-mistas.php` → migrar para sistema dinâmico de eventos

---

## 7. Sistema de Eventos

### Funcionalidades
- Página única `/eventos.php` com categorias dinâmicas
- Exibição automática de eventos futuros ordenados por data
- Eventos expirados arquivados automaticamente (não aparecem no público)
- Filtro por categoria

### Categorias de Eventos
- Cerimônias com Medicinas da Floresta
- Trabalhos de Umbanda (Sagrado Masculino, Sagrado Feminino, Misto)
- Cursos e Workshops
- Atendimentos em Grupo
- Outros

### Painel de Administração (`/admin/`)
- Login protegido por senha (admin único, acesso total)
- CRUD de eventos: criar, editar, excluir, arquivar
- Campos por evento: título, categoria, data/hora, local, descrição, imagem de capa, vagas (opcional), status (ativo/inativo)
- CRUD de cursos e atendimentos
- Gerenciamento de posts do blog
- Visualização e download das fichas de anamnese

---

## 8. Formulário de Anamnese

### Contexto
Obrigatório para participação em cerimônias com Medicinas da Floresta. O participante cria uma conta, preenche a ficha e pode editá-la em acessos futuros.

### Sistema de Acesso do Participante
- Cadastro com: e-mail + senha OU CPF + senha
- Login para editar ficha já preenchida
- Apenas maiores de 18 anos

### Estrutura do Formulário

**Ficha Cadastral**
- Nome completo (obrigatório)
- Data de nascimento (obrigatório)
- CPF (obrigatório — usado para login)
- RG e órgão expedidor
- E-mail (obrigatório — usado para login)
- WhatsApp (obrigatório)
- Instagram / Facebook (opcional)
- Escolaridade
- Profissão
- Sexo
- Endereço: Rua, nº, Bairro, Cidade, Estado
- Contato de familiar para emergência (nome + telefone)

**Seção 1 — Vida Familiar**
- Estado civil ou de convivência
- Tem filhos? Quantos?
- Mora com quem?

**Seção 2 — Vida Profissional**
- Atividade profissional
- Você gosta do que faz?
- Você se sente estável no seu trabalho?
- Outras atividades

**Seção 3 — Saúde Física**
- Já teve alguma doença grave? Qual? Quando?
- Já fez alguma cirurgia? Qual? Quando?
- Tem atualmente algum problema de saúde? (cérebro, fígado, coração, pulmão, pressão, etc.)
- Problemas cardíacos? (sim/não)
- Diabetes? (sim/não)
- Úlceras? (sim/não)
- Está grávida? De quantos meses?
- Pressão arterial: ( ) Baixa ( ) Normal ( ) Alta
- Data do último eletrocardiograma
- Está fazendo algum tratamento? Qual?
- Faz uso de algum medicamento? Qual? Qual a dose? Para que é indicado?
- Você bebe? Com que frequência?
- Consome ou já consumiu algum tipo de droga? Qual? Com que frequência?
- Seu uso de bebida/droga trouxe prejuízos à sua vida? Quais?
- Já sentiu dificuldade em controlar esse uso?

**Seção 4 — Estado Emocional Atual**
Checkboxes (marque todos que se aplicam):
- ( ) Depressivo(a)
- ( ) Ansioso(a)
- ( ) Calmo(a)
- ( ) Preocupado(a)
- ( ) Angustiado(a)
- ( ) Desmotivado(a)
- ( ) Irritado(a)
- ( ) Alegre
- ( ) Com falta de concentração
- ( ) Com insônia
- ( ) Irrequieto(a)
- ( ) Normal

**Seção 5 — Saúde Mental / Histórico Psiquiátrico**
- Você ou alguém da família possui/possuiu distúrbios psicológicos? Qual? Quem? Nível atual (0–10)?
- Já foi internado em instituição psiquiátrica? Onde? Por quê?
- Histórico familiar: ( ) Problemas cardíacos ( ) Esquizofrenia ( ) Alcoolismo — Grau de parentesco?
- Já teve algum surto psicótico? Como foi?
- Já teve experiência de ver ou ouvir coisas que outros não podiam? Vê vultos?
- Já teve sensação de morte, projeções, desdobramentos, regressões?
- Já se sentiu perseguido ou ameaçado?
- Já teve dificuldade de ordenar os pensamentos por horas/dias?
- Já viveu situação de pensamentos muito acelerados?

**Seção 6 — Problemas no Ambiente Doméstico**
Checkboxes:
- ( ) Alcoolismo ( ) Consumo de Drogas ( ) Doenças ( ) Brigas Constantes
- ( ) Instabilidade Econômica ( ) Problemas Legais ( ) Problemas Psicológicos ( ) Outros

**Seção 7 — Reatividade**
- De 0 a 10, quanto você se considera reativo (pavio curto)?
- Já brigou fisicamente com alguém? Quantas vezes? Por quê?

**Seção 8 — Espiritualidade**
- Pratica alguma religião atualmente? Qual?
- O que busca em sua prática religiosa?
- Pratica algum tipo de meditação ou prática espiritual? Qual?
- Tem algum grau de mediunidade? É possível descrever?
- Já teve alguma experiência espiritual marcante? Como foi?
- Acredita que o desenvolvimento espiritual pode te ajudar? Em que?
- Já tomou Ayahuasca ou outras Medicinas da Floresta?
- Se sim: em que ocasião (ritual xamânico, com amigos, etc.)? Como foi?
- Como soube da Fraternidade Essência da Chama Trina?
- O que está buscando neste ritual?
  - ( ) Religião ( ) Autoconhecimento ( ) Espiritualidade ( ) Curiosidade ( ) Outros

**Seção 9 — Observações Gerais**
Campo de texto livre

**Seção 10 — Termo de Responsabilidade e Uso de Imagem**
Checkbox de aceite (obrigatório). Texto adaptado para a Fraternidade Essência da Chama Trina, incluindo:
- Declaração de livre vontade e maioridade
- Ciência da natureza dos trabalhos e preparação exigida
- Proibição de substâncias proscritas, álcool, armas
- Obrigação de permanecer até o encerramento do ritual
- Proibição de fotografar/filmar o ritual
- Autorização de uso de imagem para fins de divulgação
- Declaração de veracidade das informações

---

## 9. Sistema de Blog

### Estrutura
- URL: `/blog/` ou `/blog/index.php`
- Visual 100% integrado ao site (mesma identidade visual)
- Sistema próprio em PHP + MySQL (sem WordPress)

### Funcionalidades Públicas
- Listagem de posts com imagem de capa, título, resumo e data
- Página individual de post com texto completo
- Categorias (ex.: Umbanda, Medicinas da Floresta, Ensinamentos, Eventos)
- Compartilhamento social (WhatsApp, Instagram)

### Painel de Administração (integrado ao `/admin/`)
- Criar, editar, excluir posts
- Upload de imagem de capa
- Editor de texto simples (sem formatação complexa)
- Publicar / salvar como rascunho
- Definir categoria e data de publicação

---

## 10. Conteúdo das Páginas (textos base para revisão)

### 10.1 Atendimentos
**Tipos oferecidos:**
- Consultas espirituais individuais
- Limpezas espirituais
- Passes
- Atendimentos mediúnicos

**Modalidades:** Presencial e online, por agendamento via WhatsApp ou formulário no site.

*Texto base para a página:*
> Na Fraternidade Essência da Chama Trina, os atendimentos espirituais são realizados com amor, respeito e comprometimento. Seja você alguém em busca de orientação, equilíbrio ou simplesmente acolhimento, nossos médiuns e guias espirituais estão disponíveis para te receber. Os atendimentos são realizados de forma presencial em Canoas/RS ou de forma online, sempre mediante agendamento prévio.

### 10.2 Cursos e Workshops
**Ofertas atuais:**
- Curso de Ervas
- Workshop de Banhos
- Workshop de Defumações
- Workshop de Cachimbo

*Texto base:*
> O conhecimento é uma das maiores formas de cura. Na Fraternidade Essência da Chama Trina, oferecemos cursos e workshops que unem sabedoria ancestral, prática e espiritualidade. Cada encontro é uma oportunidade de se aprofundar nos ensinamentos da Umbanda e das tradições sagradas da floresta.

---

## 11. Equipe

| Membro | Informações |
|---|---|
| Lari | Conforme perfil atual no `sobre.php` |
| Zeli | Conforme perfil atual no `sobre.php` |
| Cleiton | Conforme perfil atual no `sobre.php` |

Informações atualizadas e aprovadas. Fotos em: `img/equipe/`.

---

## 12. Integrações Existentes

| Serviço | Uso | Chave/ID |
|---|---|---|
| Google Analytics | Rastreamento de visitas | G-VDS7NJM3E4 |
| Formspree | Formulário de contato | xbdpjlbp |
| WhatsApp Web API | Botão flutuante e links | +55 51 99256-3279 |
| Instagram | Links no rodapé e header | Conforme `config.php` |

---

## 13. Problemas Conhecidos a Corrigir

1. **Erros PHP:** Variáveis `$title`, `$description`, `$url` não definidas antes de incluir `head.php` em algumas páginas
2. **Blog link:** Menu aponta para `/blog/` que não existe ainda
3. **Sitemap:** Desatualizado — falta páginas de eventos e cursos
4. **Validação do formulário de contato:** Apenas HTML5 `required`, sem validação server-side
5. **Páginas placeholder:** Sagrado Feminino e Cerimônias Mistas sem conteúdo real

---

## 14. Roadmap de Desenvolvimento

### Fase 1 — Completar o que existe (Alta Prioridade)
- [ ] Corrigir erros PHP em todas as páginas
- [ ] Criar conteúdo real para `atendimentos.php`
- [ ] Criar conteúdo real para `cursos.php`
- [ ] Atualizar `sitemap.xml`

### Fase 2 — Sistema de Eventos (Alta Prioridade)
- [ ] Criar banco de dados MySQL (tabelas: eventos, categorias)
- [ ] Criar página única `/eventos.php` com categorias dinâmicas
- [ ] Criar painel admin básico `/admin/` com login
- [ ] Migrar eventos existentes para o novo sistema
- [ ] Remover páginas de evento estáticas antigas

### Fase 3 — Formulário de Anamnese (Alta Prioridade)
- [ ] Criar tabelas MySQL: participantes, anamneses
- [ ] Criar sistema de cadastro/login para participantes
- [ ] Criar formulário de anamnese multi-seção
- [ ] Integrar anamnese com inscrição em eventos
- [ ] Criar visualização das fichas no painel admin

### Fase 4 — Blog (Média Prioridade)
- [ ] Criar tabelas MySQL: posts, categorias_blog
- [ ] Criar páginas públicas do blog (`/blog/`)
- [ ] Integrar gerenciamento de posts ao painel admin

### Fase 5 — Redesign Visual (Paralelo às outras fases)
- [ ] Definir paleta de cores final baseada no logo
- [ ] Criar novo `style.css` com tema vibrante/místico
- [ ] Aplicar redesign em todas as páginas
- [ ] Testar responsividade em mobile e desktop

---

## 15. Regras e Diretrizes de Desenvolvimento

- Todo código em PHP puro + MySQL (sem frameworks)
- HTML5 semântico, CSS3 com variáveis CSS para a paleta de cores
- JavaScript vanilla — sem jQuery ou bibliotecas externas
- Responsivo: mobile-first, testar em telas de 320px a 1920px
- Sem comentários desnecessários no código
- Senhas armazenadas com `password_hash()` (bcrypt)
- Dados do formulário de anamnese: confidenciais, acesso restrito ao admin
- Imagens otimizadas para web antes do upload
- Compatível com PHP 7.4+ (manter compatibilidade até migrar para PHP 8)

---

*Documento gerado em 2026-04-29. Revisar e atualizar conforme o projeto evolui.*
