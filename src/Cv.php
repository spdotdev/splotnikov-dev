<?php

namespace Spdotdev\SplotnikovDev;

/**
 * The CV content in English, and the Dutch map for the same keys the view uses.
 */
final class Cv
{
    /**
     * @return array{lead:string, title:string, availability:string, expertise:array<string, array{0:string, 1:list<string>}>, sections:array<string, array<string, mixed>>}
     */
    public static function all(): array
    {
        return [
            'lead' => 'Senior developer and solution architect, PHP since 2008. Turning legacy debt into software that scales and stays in support. Available for freelance and contract roles.',
            'title' => 'Senior Developer / Legacy Modernization and Solution Architect',
            'availability' => 'Available for freelance and contract roles · Eindhoven, the Netherlands',
            'expertise' => [
                'languages' => ['Languages', ['PHP', 'Python', 'JavaScript / TypeScript', 'Kotlin', 'Go', 'C / C++', 'SQL', 'Bash']],
                'frameworks' => ['Frameworks', ['Laravel', 'Symfony', 'Phalcon', 'Filament', 'Node.js', 'Jetpack Compose']],
                'automation' => ['Automation', ['Ansible', 'GitHub Actions (CI/CD)', 'Docker Compose', 'n8n', 'cron / systemd']],
                'ai' => ['AI', ['Claude Code', 'MCP', 'Agents', 'RAG', 'LLM orchestration', 'Evaluation sets']],
                'infra' => ['Infrastructure', ['Linux (Debian, Ubuntu, Arch)', 'Docker', 'DigitalOcean', 'Proxmox', 'Caddy / nginx', 'MySQL / PostgreSQL / Redis']],
                'networking' => ['Networking', ['Network design and segmentation (VLAN)', 'Firewalls', 'DNS and TLS', 'VPN', 'Monitoring']],
                'security' => ['Security', ['Server hardening', 'DevSecOps pipelines', 'Web application testing', 'Authorization and sessions', 'Backups and recovery', 'NIS2-aligned practices']],
            ],
            'sections' => [
                'sum' => ['title' => 'Executive summary', 'text' => 'Pragmatic solution architect, programming in PHP since 2008 and in networks and Linux since 2005, focused on transforming legacy technical debt into scalable, high-performance infrastructure. Specialist in incremental modernization (Strangler Fig pattern), modular monolith design and automation with AI agents. I prioritize business continuity and return on investment over industry trends, with migrations in daily use and long-term maintainability.'],
                'core' => ['title' => 'Core competencies', 'items' => [
                    ['Architecture', 'system design, modular monoliths, microservices feasibility, API gateway design, migrations in daily use (Strangler Fig).'],
                    ['Languages and frameworks', 'PHP since 2008 (Laravel, Symfony, Phalcon, Filament), Python, JavaScript and TypeScript, Kotlin, Go, C and C++, SQL.'],
                    ['Automation and DevOps', 'Ansible, Docker, CI/CD with GitHub Actions, n8n, scheduled jobs; every release small, tested and reversible.'],
                    ['AI', 'LLM orchestration, agentic workflows with a human step, MCP servers, retrieval (RAG), evaluation sets and cost caps.'],
                    ['Infrastructure and networking', 'Linux servers and home lab (Debian, Ubuntu, Arch, Proxmox), DigitalOcean, Caddy and nginx, MySQL, PostgreSQL, Redis; network segmentation, firewalls, DNS and TLS.'],
                    ['Security', 'server hardening, DevSecOps pipelines, web application testing on authorized scope, authorization and session design, backups that have been restored, NIS2-aligned practices.'],
                ]],
                'exp' => ['title' => 'Professional experience', 'jobs' => [
                    ['role' => 'Solution Architect / Full-stack Developer', 'company' => 'Scuttle Development, for a rental start-up', 'bullets' => ['Designed and built a market-validation platform for a new rental service in the Netherlands: sign-ups and click tracking per city, four page variants to compare, an admin with live statistics and exports, all in Laravel 13 and Filament 5.', 'Replaced third-party analytics with an own consent-gated session recorder and heat maps, so the client owns the data and visitors are tracked only after a yes.', 'Delivered it as a repeatable product: Docker with automatic TLS, deploys from GitHub Actions on a tag, Ansible hardening with nightly backups, 121 PHP tests and 242 browser tests across four screen sizes, documentation per service.']],
                    ['role' => 'Senior Developer / Startup Advisor', 'company' => '', 'bullets' => ['Conducted in-depth market research for a startup entering the Brazilian specialty coffee market in the Tilburg region of the Netherlands.', 'Delivered actionable market-entry insights on local consumer behavior, the competitive landscape and positioning, to guide the client\'s go-to-market decisions.']],
                    ['role' => 'Senior Developer / Modernization Engineer', 'company' => 'Hosintra B.V.', 'bullets' => ['Upgraded the core application framework from Phalcon 3.4 to Laravel 12, opening a modern ecosystem and long-term vendor support.', 'Migrated the runtime from PHP 7.4 to PHP 8.4, resolving every deprecation and modernizing the codebase to current language features.']],
                    ['role' => 'Senior Developer / Automation and AI Engineer', 'company' => 'East-West Support', 'bullets' => ['Developed automated data processing pipelines in Python, increasing throughput and automation speed by 40 percent.', 'Architected and built a scalable Voice AI phone call automation platform for customer support, replacing the client\'s support workforce requirement.']],
                    ['role' => 'Senior Developer / API Integration Engineer', 'company' => 'Hosintra B.V.', 'bullets' => ['Engineered a custom middleware abstraction layer for complex vendor API integrations, cutting technical debt, removing the client migration risk and saving the product.']],
                ]],
                'hist' => ['title' => 'Employment history', 'roles' => [
                    ['Getnoticed', 'Recruitment marketing · PHP / Symfony', 'Employer branding and conversion-driven career websites for the labor market.'],
                    ['Bek', 'Omnichannel communications · PHP / Laravel', 'Bridged physical print and digital marketing portals so brands communicate consistently with customers.'],
                    ['New Brand Activators', 'Brand activation · PHP / Laravel', 'Physical and digital brand interaction platforms that drive customer loyalty and engagement.'],
                    ['Mijnwebwinkel', 'E-commerce / SaaS · PHP / Symfony', 'Built and maintained the SaaS infrastructure that lets businesses launch and run online stores.'],
                    ['@Leisure Group', 'E-commerce / travel · PHP / Symfony', 'A digital travel platform processing bookings and payments for vacation rentals.'],
                    ['Van Os Beheer & Beleggingen BV', 'Finance / telecom · PHP', 'Digital marketing and telecom solutions within a financial holding group.'],
                ]],
                'proj' => ['title' => 'Key projects', 'jobs' => [
                    ['role' => 'Market validation platform for a rental service', 'company' => '', 'lines' => [['Challenge', 'an entrepreneur wanted to know whether there is real demand for a new rental service before investing in building it, with nothing in place: no site, no infrastructure, no measurement, and privacy rules to respect from day one.'], ['Solution', 'a landing platform that captures sign-ups per city, compares four page designs against each other, records sessions and heat maps only after consent, and shows everything in an admin with exports; shipped on a hardened server with automatic TLS, tagged deploys and nightly backups, at a fixed price.'], ['Result', 'a live, measurable test of demand per city and design, data the client owns and can show an investor, and a foundation the real service can be built on without starting over.']]],
                    ['role' => 'Legacy modernization: Phalcon to Laravel', 'company' => '', 'lines' => [['Challenge', 'the client was stuck on an unmaintainable legacy framework with a high vendor lock-in risk.'], ['Solution', 'a bridge layer that runs both frameworks in parallel. High-risk modules moved first while the legacy system stayed operational.'], ['Result', 'lower total cost of ownership, and the client ships new features 30 percent faster.']]],
                    ['role' => 'Automation and AI-driven workflow', 'company' => '', 'lines' => [['Challenge', 'manual data entry and filtering created bottlenecks in the client\'s operations.'], ['Solution', 'LLM agents integrated into the existing workflows for intelligent automation of sales.'], ['Result', '16 hours of manual work a week automated.']]],
                ]],
            ],
        ];
    }

    /**
     * The Dutch strings, keyed like the data-i attributes in cv.blade.php.
     *
     * @return array<string, string>
     */
    public static function nl(): array
    {
        $nl = [
            'cv_lbl' => 'Curriculum vitae', 'cv_pdf' => 'Download PDF', 'cv_print' => 'Printen',
            'cv_lead' => 'Senior developer en solution architect, PHP sinds 2008. Van legacy-schuld naar software die schaalt en in support blijft. Beschikbaar voor freelance- en contractwerk.',
            'cv_title' => 'Senior Developer / Modernisering van legacy en Solution Architect',
            'cv_avail' => 'Beschikbaar voor freelance- en contractwerk · Eindhoven, Nederland',
            'cv_g_l' => 'In het kort',
            'cv_g1t' => 'Werk dat ik doe', 'cv_g1d' => 'Modernisering van legacy, automatisering met AI-agents, serverhardening. Eerst PHP en Laravel, daarnaast Python en Linux.',
            'cv_g2t' => 'Vorm', 'cv_g2d' => 'Freelance en detachering, via mijn bedrijf Scuttle Development. EUR 90 per uur excl. btw, opgeschreven op <a href="https://scuttle.dev/nl/pricing">scuttle.dev/nl/pricing</a>.',
            'cv_g3t' => 'Waar', 'cv_g3d' => 'Eindhoven. Op locatie in Brabant, op afstand voor de rest van Nederland en Europa.',
            'cv_g4t' => 'Talen', 'cv_g4d' => 'Nederlands, Engels, Russisch. Documenten schrijf ik in het Nederlands of Engels.',
            'cv_g5t' => 'Kijk voordat je belt', 'cv_g5d' => 'Zestien openbare repositories op <a href="https://github.com/spdotdev" rel="noopener" target="_blank">GitHub</a>, de <a href="/projects">projectenpagina</a> en de <a href="/blog">blog</a>.',
            'cv_s_sum' => 'Samenvatting',
            'cv_t_sum' => 'Pragmatische solution architect, sinds 2008 programmeur in PHP en sinds 2005 bezig met netwerken en Linux, gericht op het omzetten van technische schuld in legacy-software naar schaalbare, snelle infrastructuur. Specialist in stapsgewijze modernisering (Strangler Fig-patroon), modulaire monolieten en automatisering met AI-agents. Ik zet bedrijfscontinuïteit en rendement boven trends, met migraties die in gebruik blijven en onderhoudbaarheid op lange termijn.',
            'cv_s_core' => 'Kerncompetenties',
            'cv_i_core_0' => '<b>Architectuur:</b> systeemontwerp, modulaire monolieten, haalbaarheid van microservices, API-gatewayontwerp, migraties die in gebruik blijven (Strangler Fig).',
            'cv_i_core_1' => '<b>Talen en frameworks:</b> PHP sinds 2008 (Laravel, Symfony, Phalcon, Filament), Python, JavaScript en TypeScript, Kotlin, Go, C en C++, SQL.',
            'cv_i_core_2' => '<b>Automatisering en DevOps:</b> Ansible, Docker, CI/CD met GitHub Actions, n8n, geplande taken; elke release klein, getest en terug te draaien.',
            'cv_i_core_3' => '<b>AI:</b> LLM-orkestratie, agent-workflows met een menselijke stap, MCP-servers, retrieval (RAG), evaluatiesets en kostenplafonds.',
            'cv_i_core_4' => '<b>Infrastructuur en netwerken:</b> Linux-servers en thuislab (Debian, Ubuntu, Arch, Proxmox), DigitalOcean, Caddy en nginx, MySQL, PostgreSQL, Redis; netwerksegmentatie, firewalls, DNS en TLS.',
            'cv_i_core_5' => '<b>Beveiliging:</b> serverhardening, DevSecOps-pipelines, webapplicatietests binnen toegestane scope, autorisatie- en sessieontwerp, back-ups die hersteld zijn, werkwijze in lijn met NIS2.',
            'cv_s_exp' => 'Werkervaring',
            'cv_j_exp_0' => 'Solution Architect / Full-stack Developer',
            'cv_jb_exp_0_0' => 'Een marktvalidatieplatform ontworpen en gebouwd voor een nieuwe verhuurdienst in Nederland: aanmeldingen en kliks per stad, vier paginavarianten om te vergelijken, een beheeromgeving met live statistieken en exports, alles in Laravel 13 en Filament 5.',
            'cv_jb_exp_0_1' => 'Analytics van derden vervangen door een eigen sessierecorder met heatmaps achter toestemming, zodat de klant de data bezit en bezoekers pas na een ja gevolgd worden.',
            'cv_jb_exp_0_2' => 'Opgeleverd als herhaalbaar product: Docker met automatische TLS, deploys vanuit GitHub Actions op een tag, Ansible-hardening met nachtelijke back-ups, 121 PHP-tests en 242 browsertests op vier schermformaten, documentatie per dienst.',
            'cv_j_exp_1' => 'Senior Developer / Startup-adviseur',
            'cv_jb_exp_1_0' => 'Diepgaand marktonderzoek uitgevoerd voor een startup die de Braziliaanse specialty-coffeemarkt in de regio Tilburg betreedt.',
            'cv_jb_exp_1_1' => 'Bruikbare inzichten geleverd over lokaal consumentengedrag, het concurrentieveld en positionering, als basis voor de go-to-market-keuzes van de klant.',
            'cv_j_exp_2' => 'Senior Developer / Moderniseringsengineer',
            'cv_jb_exp_2_0' => 'Het kernframework van de applicatie opgewaardeerd van Phalcon 3.4 naar Laravel 12, met toegang tot een modern ecosysteem en langdurige support.',
            'cv_jb_exp_2_1' => 'De runtime gemigreerd van PHP 7.4 naar PHP 8.4, elke deprecation opgelost en de codebase naar de huidige taalfeatures gebracht.',
            'cv_j_exp_3' => 'Senior Developer / Automatiserings- en AI-engineer',
            'cv_jb_exp_3_0' => 'Geautomatiseerde dataverwerkingspipelines in Python gebouwd, met 40 procent meer doorvoer en snelheid.',
            'cv_jb_exp_3_1' => 'Een schaalbaar Voice AI-platform voor telefonische klantenservice ontworpen en gebouwd, dat de personeelsbehoefte van de klant voor support verving.',
            'cv_j_exp_4' => 'Senior Developer / API-integratie-engineer',
            'cv_jb_exp_4_0' => 'Een eigen middleware-abstractielaag gebouwd voor complexe leveranciers-API\'s, met minder technische schuld, zonder migratierisico voor de klant, waarmee het product gered werd.',
            'cv_s_hist' => 'Loopbaan',
            'cv_rs_0' => 'Recruitmentmarketing · PHP / Symfony', 'cv_rd_0' => 'Employer branding en conversiegerichte carrièresites voor de arbeidsmarkt.',
            'cv_rs_1' => 'Omnichannel-communicatie · PHP / Laravel', 'cv_rd_1' => 'Fysiek drukwerk en digitale marketingportalen verbonden, zodat merken consistent met klanten communiceren.',
            'cv_rs_2' => 'Brand activation · PHP / Laravel', 'cv_rd_2' => 'Fysieke en digitale merkinteractieplatforms die klantloyaliteit en betrokkenheid vergroten.',
            'cv_rs_3' => 'E-commerce / SaaS · PHP / Symfony', 'cv_rd_3' => 'De SaaS-infrastructuur gebouwd en onderhouden waarmee bedrijven webwinkels starten en runnen.',
            'cv_rs_4' => 'E-commerce / reizen · PHP / Symfony', 'cv_rd_4' => 'Een digitaal reisplatform dat boekingen en betalingen voor vakantiehuizen verwerkt.',
            'cv_rs_5' => 'Financieel / telecom · PHP', 'cv_rd_5' => 'Digitale marketing- en telecomoplossingen binnen een financiële holding.',
            'cv_s_proj' => 'Belangrijkste projecten',
            'cv_j_proj_0' => 'Marktvalidatieplatform voor een verhuurdienst',
            'cv_jl_proj_0_0' => '<b>Uitdaging:</b> een ondernemer wilde weten of er echte vraag is naar een nieuwe verhuurdienst voordat hij in de bouw investeert, met niets voorhanden: geen site, geen infrastructuur, geen meting, en privacyregels die vanaf dag één gelden.',
            'cv_jl_proj_0_1' => '<b>Oplossing:</b> een landingsplatform dat aanmeldingen per stad vastlegt, vier paginaontwerpen tegen elkaar afzet, sessies en heatmaps pas na toestemming opneemt en alles toont in een beheeromgeving met exports; opgeleverd op een geharde server met automatische TLS, getagde deploys en nachtelijke back-ups, voor een vaste prijs.',
            'cv_jl_proj_0_2' => '<b>Resultaat:</b> een live, meetbare test van de vraag per stad en ontwerp, data die de klant bezit en aan een investeerder kan laten zien, en een basis waarop de echte dienst gebouwd kan worden zonder opnieuw te beginnen.',
            'cv_j_proj_1' => 'Modernisering van legacy: Phalcon naar Laravel',
            'cv_jl_proj_1_0' => '<b>Uitdaging:</b> de klant zat vast op een onhoudbaar legacy framework met een groot risico op vendor lock-in.',
            'cv_jl_proj_1_1' => '<b>Oplossing:</b> een bruglaag die beide frameworks parallel laat draaien. Risicovolle modules eerst verhuisd terwijl het legacy systeem bleef werken.',
            'cv_jl_proj_1_2' => '<b>Resultaat:</b> lagere totale kosten, en de klant levert nieuwe functies 30 procent sneller op.',
            'cv_j_proj_2' => 'Automatisering en AI-gedreven workflow',
            'cv_jl_proj_2_0' => '<b>Uitdaging:</b> handmatige data-invoer en filtering zorgden voor knelpunten in de operatie van de klant.',
            'cv_jl_proj_2_1' => '<b>Oplossing:</b> LLM-agents geïntegreerd in de bestaande workflows voor slimme automatisering van sales.',
            'cv_jl_proj_2_2' => '<b>Resultaat:</b> 16 uur handwerk per week geautomatiseerd.',
            'cv_s_edu' => 'Opleiding',
            'cv_t_edu' => 'MBO 4 ICT-beheer, <a href="https://www.linkedin.com/company/radius-college/" rel="noopener" target="_blank">Radius College Breda</a>.',
            'cv_b_biz' => 'Bedrijfsgegevens', 'cv_biz_on' => 'Zakelijk', 'cv_biz_off' => 'Persoonlijk',
            'cv_b_contact' => 'Contact', 'cv_b_where' => 'Eindhoven, Nederland', 'cv_b_links' => 'Links', 'cv_b_exp' => 'Expertise',
            'cv_e_languages' => 'Talen', 'cv_e_frameworks' => 'Frameworks', 'cv_e_automation' => 'Automatisering', 'cv_e_ai' => 'AI', 'cv_e_infra' => 'Infrastructuur', 'cv_e_networking' => 'Netwerken', 'cv_e_security' => 'Beveiliging',
            'cv_b_lang' => 'Gesproken talen', 'cv_b_l1' => 'Engels', 'cv_b_l2' => 'Nederlands', 'cv_b_l3' => 'Russisch',
            'cv_cta_h' => 'Wil je dit op papier, of in je inbox?',
            'cv_cta_p' => 'Download hierboven de PDF, of schrijf me en ik stuur hem met een korte notitie over wat ik voor jouw systeem kan doen.',
            'cv_cta_b1' => 'Gebruik het contactformulier', 'cv_cta_b2' => 'Bekijk de projecten',
        ];

        return $nl;
    }
}
