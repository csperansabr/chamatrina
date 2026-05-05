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
| Formulários | PHPMailer via SMTP HostGator (contato.php migrado do Formspree) |
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
- Campos por evento: título, categoria, data/hora de início, data/hora de término (opcional), local, descrição, imagem de capa, vagas (opcional), status (ativo/inativo/encerrado)
- CRUD de cursos e atendimentos
- Gerenciamento de posts do blog
- Visualização e download das fichas de anamnese
- Favicon presente em todas as páginas do painel (`/img/ico/favicon.ico`)

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
| PHPMailer (SMTP) | Formulário de contato + notificações + e-mails de conclusão | Configurado em `includes/mailer.php` |
| WhatsApp Web API | Botão flutuante e links | +55 51 99256-3279 |
| Instagram | Links no rodapé e header | Conforme `config.php` |
| Loja externa | Link "Loja" no menu principal | irananatural.lojavirtualnuvem.com.br |

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

---

## 16. Funcionalidade — Atendimentos Online (Benzimento)

**Implementado em:** 2026-05-04

### 16.1 Visão geral

Sistema completo de solicitação de atendimento online com foco em Benzimento. Permite ao visitante preencher um formulário no site, que registra a solicitação no banco de dados, notifica a equipe por e-mail e disponibiliza controle administrativo com rastreamento de status, alerta de pendências, limpeza automática de dados e monitoramento de rotinas.

---

### 16.2 Estrutura técnica

#### Tabelas criadas

| Tabela | Descrição |
|---|---|
| `atendimentos` | Registros de solicitações de atendimento online |
| `atendimentos_log` | Log anônimo de atendimentos excluídos (LGPD) |
| `rotinas_execucao` | Histórico de execução das rotinas automáticas |

#### Campos — `atendimentos`

| Campo | Tipo | Descrição |
|---|---|---|
| `id` | INT PK | Identificador único |
| `nome` | VARCHAR(255) | Nome completo do solicitante |
| `email` | VARCHAR(255) | E-mail |
| `whatsapp` | VARCHAR(30) | WhatsApp |
| `data_nascimento` | DATE | Data de nascimento |
| `nome_mae` | VARCHAR(255) | Nome da mãe |
| `endereco` | TEXT | Endereço completo |
| `tipo_atendimento` | VARCHAR(100) | Tipo (ex.: Benzimento) |
| `intencao` | TEXT | Intenção / propósito |
| `status` | ENUM | `pendente` ou `concluido` |
| `data_solicitacao` | DATETIME | Timestamp automático |
| `data_conclusao` | DATETIME | Preenchido ao concluir |
| `msg_conclusao` | TEXT | Mensagem enviada ao solicitante no ato da conclusão |

#### Campos — `atendimentos_log` (sem dados pessoais)

| Campo | Tipo |
|---|---|
| `id` | INT PK |
| `tipo_atendimento` | VARCHAR(100) |
| `data_solicitacao` | DATETIME |
| `data_conclusao` | DATETIME |
| `data_exclusao` | DATETIME |

#### Campos — `rotinas_execucao`

| Campo | Tipo |
|---|---|
| `id` | INT PK |
| `nome_rotina` | VARCHAR(100) |
| `data_execucao` | DATETIME |
| `status` | ENUM (`sucesso`, `erro`) |
| `mensagem` | TEXT |

---

### 16.3 Fluxo do sistema

```
Visitante preenche benzimento.php
    → POST para benzimento-enviar.php
        → Valida dados (frontend + backend)
        → Salva em atendimentos (status = pendente)
        → Envia e-mail para contato@chamatrina.org.br
        → Redireciona para benzimento.php?status=ok

Admin acessa /admin/atendimentos.php
    → Visualiza lista de atendimentos (filtro por status/busca)
    → Coluna "Datas" exibe data de solicitação + data de conclusão (quando concluído)
    → Botão "Concluir" na listagem envia mensagem padrão e conclui diretamente
    → Acessa atendimento-ver.php?id=X para ver detalhes completos
    → Formulário de conclusão exibe textarea pré-preenchida com mensagem padrão (editável)
    → Clica "Enviar mensagem e concluir"
        → atendimento-concluir.php envia e-mail HTML com logo ao solicitante
        → Seta status=concluido + data_conclusao=NOW() + salva msg_conclusao

Rotina diária (alerta-pendentes)
    → Busca pendentes com data_solicitacao <= NOW() - 3 dias
    → Envia e-mail de alerta se encontrar
    → Registra em rotinas_execucao

Rotina diária (limpeza-dados)
    → Busca concluidos com data_conclusao <= NOW() - 90 dias
    → Grava log anônimo em atendimentos_log
    → Exclui registros originais (LGPD)
    → Registra em rotinas_execucao

Rotina diária (verificar-rotinas)
    → Checa se alerta-pendentes e limpeza-dados rodaram nas últimas 24h
    → Envia alerta se encontrar erro ou atraso
    → Registra em rotinas_execucao
```

---

### 16.4 Rotinas automáticas (cron jobs)

Configurar no **cPanel → Cron Jobs** do HostGator:

| Rotina | Arquivo | Horário sugerido | Comando |
|---|---|---|---|
| Alerta de pendentes | `cron/alerta-pendentes.php` | Diário às 08h | `0 8 * * * php /home/cleit467/public_html/cron/alerta-pendentes.php` |
| Limpeza de dados | `cron/limpeza-dados.php` | Diário às 03h | `0 3 * * * php /home/cleit467/public_html/cron/limpeza-dados.php` |
| Verificação de rotinas | `cron/verificar-rotinas.php` | Diário às 09h | `0 9 * * * php /home/cleit467/public_html/cron/verificar-rotinas.php` |
| Encerrar eventos | `cron/encerrar-eventos.php` | A cada hora | `0 * * * * php /home/cleit467/public_html/cron/encerrar-eventos.php` |

---

### 16.5 Regras de negócio

- Status possíveis: `pendente` → `concluido` (sem reversão)
- Alerta de pendência: atendimentos com `status = pendente` e `data_solicitacao <= NOW() - 3 dias`
- Retenção: atendimentos `concluido` com `data_conclusao <= NOW() - 90 dias` são excluídos automaticamente
- Antes da exclusão, um log anônimo (sem dados pessoais) é gravado em `atendimentos_log`
- Semáforo do dashboard: verde (tudo OK), amarelo (atraso ou nunca executada), vermelho (erro)
- Conclusão: ao concluir, o admin envia obrigatoriamente uma mensagem ao solicitante via e-mail HTML com logo da Fraternidade
- Mensagem padrão pré-preenchida no formulário de conclusão; pode ser editada antes do envio
- `msg_conclusao` é salva na tabela para rastreabilidade

---

### 16.6 URLs / Rotas criadas

| URL | Arquivo | Tipo | Descrição |
|---|---|---|---|
| `/benzimento.php` | `benzimento.php` | Público | Formulário de solicitação |
| `/benzimento-enviar.php` | `benzimento-enviar.php` | POST interno | Processador do formulário |
| `/politica-privacidade.php` | `politica-privacidade.php` | Público | Página de política de privacidade (LGPD) |
| `/admin/atendimentos.php` | `admin/atendimentos.php` | Admin | Listagem de atendimentos |
| `/admin/atendimento-ver.php` | `admin/atendimento-ver.php` | Admin | Detalhes de um atendimento |
| `/admin/atendimento-concluir.php` | `admin/atendimento-concluir.php` | Admin POST | Marcar como concluído |
| `/admin/rotinas.php` | `admin/rotinas.php` | Admin | Painel de monitoramento de rotinas |
| `/setup_atendimentos.php` | `setup_atendimentos.php` | Instalação | Criar tabelas — **excluir após executar** |

---

### 16.7 Observações importantes

- **Instalação:** Executar `setup_atendimentos.php` uma vez no servidor e deletar o arquivo em seguida
- **Migração de coluna:** `setup_atendimentos.php` também adiciona `msg_conclusao` via `ALTER TABLE` para instâncias existentes
- **Cron jobs:** Configurar os quatro jobs no cPanel. Sem eles, alertas e limpeza não ocorrem
- **E-mail de destino:** Notificações de nova solicitação vão para `contato@chamatrina.org.br`; e-mail de conclusão vai para o e-mail do solicitante
- **E-mail HTML:** O e-mail de conclusão é gerado em HTML com logo, gradiente roxo, saudação personalizada e assinatura. Inclui `AltBody` em texto puro como fallback
- **LGPD:** O campo `lgpd_aceite` é obrigatório no formulário e validado no backend
- **Semáforo:** Exibido no topo do dashboard admin; clicável para abrir `/admin/rotinas.php`
- **Dependência:** Requer as tabelas criadas via `setup_atendimentos.php` antes de qualquer uso

---

---

## 17. Funcionalidade — Data de Término de Eventos

**Implementado em:** 2026-05-04

### 17.1 Visão geral

Campo `data_evento_fim` adicionado à tabela `eventos` para registrar a data e hora de encerramento de cada evento. O campo é opcional — quando ausente, o sistema usa `data_evento` (início) como referência para encerramento automático.

### 17.2 Alterações no banco de dados

| Campo | Tipo | Descrição |
|---|---|---|
| `data_evento_fim` | DATETIME NULL | Data e hora de término do evento (opcional) |

**Migração:** `setup.php` atualizado com `ADD COLUMN data_evento_fim ... AFTER data_evento` via `ALTER TABLE` com try/catch (não falha se a coluna já existir).

### 17.3 Comportamento no painel admin

- **`admin/evento-form.php`:** Campo "Data e horário de término" ao lado do campo de início. Vagas movido para a linha seguinte.
- **`admin/eventos.php`:** Colunas "Início" e "Término" separadas na tabela de listagem. Exibe `—` quando sem data fim.
- **`admin/evento-salvar.php`:** Recebe `data_evento_fim` do POST; armazena `null` quando vazio.

### 17.4 Exibição pública (`eventos.php`)

Exibição inteligente conforme o cenário:

| Cenário | Exibição |
|---|---|
| Sem data fim | `15/06/2025 às 09:00h` |
| Mesmo dia | `15/06/2025 das 09:00h às 17:00h` |
| Dias diferentes | `15/06/2025 às 09:00h até 16/06/2025 às 12:00h` |

### 17.5 Encerramento automático (cron)

- **Arquivo:** `cron/encerrar-eventos.php`
- **Frequência:** A cada hora (`0 * * * *`)
- **Lógica:** Atualiza para `status = 'encerrado'` todos os eventos `ativo` onde:
  - `data_evento_fim IS NOT NULL AND data_evento_fim < NOW()`, **ou**
  - `data_evento_fim IS NULL AND data_evento < NOW()` (fallback)
- **Monitoramento:** Incluída no `cron/verificar-rotinas.php` e no semáforo do dashboard (`admin/index.php`)

### 17.6 Configuração do cron no cPanel

```
0 * * * *   php /home/cleit467/public_html/cron/encerrar-eventos.php
```

### 17.7 Observações

- A migração de banco (`setup.php`) deve ser executada no servidor para adicionar a coluna em instâncias existentes
- Eventos sem `data_evento_fim` continuam funcionando normalmente — o campo é totalmente opcional
- O encerramento automático substitui a necessidade de alterar manualmente o status no painel

---

*Documento gerado em 2026-04-29. Última atualização: 2026-05-04.*
