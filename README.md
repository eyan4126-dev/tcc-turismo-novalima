# ⛰️ iNovaTour https://turismonl.lts.app.br/guia

> **iNovaTour:** Sistema web de inteligência turística e consolidação de dados municipais para a gestão estratégica de Nova Lima.

## 📖 Sobre o Projeto
O **iNovaTour** é uma plataforma inteligente desenvolvida como Trabalho de Conclusão de Curso (TCC) no SENAI. O sistema foi projetado para transformar a gestão do turismo em Nova Lima, substituindo a intuição e processos manuais por um ecossistema robusto de **inteligência de dados em tempo real**.

A solução conecta diretamente três pilares cruciais:
1. **O Turista:** Que fornece dados valiosos de comportamento de forma fluida.
2. **O Lojista (Setor Privado):** Que ganha um painel tático com indicadores exclusivos e inteligência competitiva para o seu negócio.
3. **O Administrador (Poder Público):** Que centraliza a consolidação dos dados municipais para tomadas de decisão macroeconômicas e captação de recursos.

## ✨ Funcionalidades Atuais do Sistema

### 📱 1. Captura Fluida via QR Code (Módulo Turista)
* **Coleta Direta no Ponto de Consumo:** O visitante interage com QR Codes estratégicos disponibilizados nos estabelecimentos, eventos e pontos turísticos da cidade.
* **Pesquisa de Perfil MVP:** Coleta rápida e sem fricção de dados essenciais: cidade de origem, tempo de permanência (se dorme na cidade ou faz bate-volta), motivo da visita e etc.

### 📊 2. Inteligência Tática para o Comércio (Módulo Lojista)
* **KPIs Estratégicos Privados:** Visualização em tempo real do Ticket Médio Real, Nota de Satisfação Interna e NPS (Net Promoter Score) exclusivo do estabelecimento.
* **Gráficos de Perfil de Cliente:** Gráficos interativos (Cidades de Origem, Motivos de Visita) para o lojista entender exatamente quem consome no seu espaço.
* **Parcerias de Co-Marketing:** Gráfico detalhado sobre o local de hospedagem dos clientes, permitindo que lojistas e restaurantes firmem parcerias de indicação diretamente com hotéis e pousadas da região.
* **Lançamento Operacional Intuitivo por Setor:** Formulário dinâmico que adapta as perguntas à realidade prática do lojista (usando o campo `ENUM` do banco de dados), eliminando conceitos complexos e coletando metas realistas:
  * **Hospedagem:** Registra Hóspedes, Noites de Quartos Ocupados e Capacidade Operacional (Métrica de Taxa de Ocupação Hoteleira).
  * **Alimentação e Comércio:** Registra Clientes no Local, Vendas Realizadas e Meta de Clientes Estimada para o mês.
  * **Natural e Cultural:** Registra Visitantes, Check-ins/Ingressos e Meta de Público Esperado (Capacidade de Carga/Sustentabilidade).

### 🏛️ 3. Consolidação Estratégica Municipal (Módulo Admin)
* **Triangulação de Dados:** Cruzamento entre o fluxo real capturado nos QR Codes e o desempenho operacional/metas mensais enviados pelos lojistas.
* **Gráficos de Tendência e Eficiência:** Dashboards gerenciais para identificar a ociosidade, saturação de destinos e o *Índice de Atingimento de Metas Operacionais* do comércio local.
* **Fomento ao Turismo:** Geração de dados estruturados e consolidados fundamentais para auditorias e relatórios exigidos pelo **ICMS Turismo** e **Sismapa**, maximizando repasses estaduais.

## 🚀 Tecnologias e Arquitetura

**Back-end & Banco de Dados:**
* **PHP 8+** com o framework **CodeIgniter 4** (Padrão MVC, segurança avançada de rotas e CSRF).
* **MySQL:** Modelagem relacional otimizada com integridade referencial rigorosa (tabelas de usuários conectadas a estabelecimentos e fluxos mensais).

**Front-end & Visualização:**
* **Bootstrap 5:** Interface responsiva focada no conceito *Mobile-First* para os turistas e usabilidade limpa para os lojistas.
* **JavaScript Vanilla & Fetch API:** Requisições assíncronas para gráficos dinâmicos.
* **Chart.js / SVG Dinâmico:** Renderização de gráficos de inteligência competitiva e KPIs.

## 👥 Equipe Desenvolvedora
* **Yan** - Scrum Master & Desenvolvedor Back-end
* **Daniel** - Arquiteto de Dados & Desenvolvedor Back-end
* **Waron** - Designer de UX/UI & Desenvolvedor Front-end
* **João Pedro** - Product Owner & Desenvolvedor Front-end
