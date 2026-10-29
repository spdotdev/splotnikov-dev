<?php

namespace Spdotdev\SplotnikovDev;

/**
 * The CV content in English, and the Dutch map for the same keys the view uses.
 */
final class Cv
{
    /**
     * @return array{lead:string, title:string, availability:string, sections:array<string, array<string, mixed>>}
     */
    public static function all(): array
    {
        return [
            'lead' => 'Senior developer and solution architect, PHP since 2008. Turning legacy debt into software that scales and stays in support. Available for freelance and contract roles.',
            'title' => 'Senior Developer / Legacy Modernization and Solution Architect',
            'availability' => 'Available for freelance and contract roles · Eindhoven, the Netherlands',
            'sections' => [
                'sum' => ['title' => 'Executive summary', 'text' => 'Pragmatic solution architect, programming in PHP since 2008 and in networks and Linux since 2005, focused on transforming legacy technical debt into scalable, high-performance infrastructure. Specialist in incremental modernization (Strangler Fig pattern), modular monolith design and automation with AI agents. I prioritize business continuity and return on investment over industry trends, with migrations in daily use and long-term maintainability.'],
                'core' => ['title' => 'Core competencies', 'items' => [
                    ['Architecture', 'system design, modular monoliths, microservices feasibility, API gateway design.'],
                    ['Engineering', 'PHP (Laravel, Symfony, Phalcon expert), Node.js, Python (AI and LLM integration), Kotlin.'],
                    ['Cloud and DevOps', 'Docker, Ansible, CI/CD (GitHub Actions), Linux systems hardening.'],
                    ['AI and automation', 'LLM orchestration, agentic workflows with a human step, MCP servers, evaluation sets, n8n.'],
                    ['Security', 'network and web application testing on authorized scope, server hardening, since the BackTrack days.'],
                ]],
                'exp' => ['title' => 'Professional experience', 'jobs' => [
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
            'cv_lead' => 'Senior developer en solution architect, PHP sinds 2008. Van legacy schuld naar software die schaalt en in support blijft. Beschikbaar voor freelance- en contractwerk.',
            'cv_title' => 'Senior Developer / Modernisering van legacy en Solution Architect',
            'cv_avail' => 'Beschikbaar voor freelance- en contractwerk · Eindhoven, Nederland',
            'cv_s_sum' => 'Samenvatting',
            'cv_t_sum' => 'Pragmatische solution architect, sinds 2008 programmeur in PHP en sinds 2005 bezig met netwerken en Linux, gericht op het omzetten van technische schuld in legacy software naar schaalbare, snelle infrastructuur. Specialist in stapsgewijze modernisering (Strangler Fig-patroon), modulaire monolieten en automatisering met AI-agents. Ik zet bedrijfscontinuïteit en rendement boven trends, met migraties die in gebruik blijven en onderhoudbaarheid op lange termijn.',
            'cv_s_core' => 'Kerncompetenties',
            'cv_i_core_0' => '<b>Architectuur:</b> systeemontwerp, modulaire monolieten, haalbaarheid van microservices, API-gatewayontwerp.',
            'cv_i_core_1' => '<b>Engineering:</b> PHP (Laravel, Symfony, Phalcon-expert), Node.js, Python (AI- en LLM-integratie), Kotlin.',
            'cv_i_core_2' => '<b>Cloud en DevOps:</b> Docker, Ansible, CI/CD (GitHub Actions), hardening van Linux-systemen.',
            'cv_i_core_3' => '<b>AI en automatisering:</b> LLM-orkestratie, agent-workflows met een menselijke stap, MCP-servers, evaluatiesets, n8n.',
            'cv_i_core_4' => '<b>Beveiliging:</b> netwerk- en webapplicatietests binnen toegestane scope, serverhardening, sinds de BackTrack-tijd.',
            'cv_s_exp' => 'Werkervaring',
            'cv_j_exp_0' => 'Senior Developer / Startup-adviseur',
            'cv_jb_exp_0_0' => 'Diepgaand marktonderzoek uitgevoerd voor een startup die de Braziliaanse specialty-coffeemarkt in de regio Tilburg betreedt.',
            'cv_jb_exp_0_1' => 'Bruikbare inzichten geleverd over lokaal consumentengedrag, het concurrentieveld en positionering, als basis voor de go-to-market-keuzes van de klant.',
            'cv_j_exp_1' => 'Senior Developer / Moderniseringsengineer',
            'cv_jb_exp_1_0' => 'Het kernframework van de applicatie opgewaardeerd van Phalcon 3.4 naar Laravel 12, met toegang tot een modern ecosysteem en langdurige support.',
            'cv_jb_exp_1_1' => 'De runtime gemigreerd van PHP 7.4 naar PHP 8.4, elke deprecation opgelost en de codebase naar de huidige taalfeatures gebracht.',
            'cv_j_exp_2' => 'Senior Developer / Automatiserings- en AI-engineer',
            'cv_jb_exp_2_0' => 'Geautomatiseerde dataverwerkingspipelines in Python gebouwd, met 40 procent meer doorvoer en snelheid.',
            'cv_jb_exp_2_1' => 'Een schaalbaar Voice AI-platform voor telefonische klantenservice ontworpen en gebouwd, dat de personeelsbehoefte van de klant voor support verving.',
            'cv_j_exp_3' => 'Senior Developer / API-integratie-engineer',
            'cv_jb_exp_3_0' => 'Een eigen middleware-abstractielaag gebouwd voor complexe leveranciers-API\'s, met minder technische schuld, zonder migratierisico voor de klant, waarmee het product gered werd.',
            'cv_s_hist' => 'Loopbaan',
            'cv_rs_0' => 'Recruitmentmarketing · PHP / Symfony', 'cv_rd_0' => 'Employer branding en conversiegerichte carrièresites voor de arbeidsmarkt.',
            'cv_rs_1' => 'Omnichannel-communicatie · PHP / Laravel', 'cv_rd_1' => 'Fysiek drukwerk en digitale marketingportalen verbonden, zodat merken consistent met klanten communiceren.',
            'cv_rs_2' => 'Brand activation · PHP / Laravel', 'cv_rd_2' => 'Fysieke en digitale merkinteractieplatforms die klantloyaliteit en betrokkenheid vergroten.',
            'cv_rs_3' => 'E-commerce / SaaS · PHP / Symfony', 'cv_rd_3' => 'De SaaS-infrastructuur gebouwd en onderhouden waarmee bedrijven webwinkels starten en runnen.',
            'cv_rs_4' => 'E-commerce / reizen · PHP / Symfony', 'cv_rd_4' => 'Een digitaal reisplatform dat boekingen en betalingen voor vakantiehuizen verwerkt.',
            'cv_rs_5' => 'Financieel / telecom · PHP', 'cv_rd_5' => 'Digitale marketing- en telecomoplossingen binnen een financiële holding.',
            'cv_s_proj' => 'Belangrijkste projecten',
            'cv_j_proj_0' => 'Modernisering van legacy: Phalcon naar Laravel',
            'cv_jl_proj_0_0' => '<b>Uitdaging:</b> de klant zat vast op een onhoudbaar legacy framework met een groot risico op vendor lock-in.',
            'cv_jl_proj_0_1' => '<b>Oplossing:</b> een bruglaag die beide frameworks parallel laat draaien. Risicovolle modules eerst verhuisd terwijl het legacy systeem bleef werken.',
            'cv_jl_proj_0_2' => '<b>Resultaat:</b> lagere totale kosten, en de klant levert nieuwe functies 30 procent sneller op.',
            'cv_j_proj_1' => 'Automatisering en AI-gedreven workflow',
            'cv_jl_proj_1_0' => '<b>Uitdaging:</b> handmatige data-invoer en filtering zorgden voor knelpunten in de operatie van de klant.',
            'cv_jl_proj_1_1' => '<b>Oplossing:</b> LLM-agents geïntegreerd in de bestaande workflows voor slimme automatisering van sales.',
            'cv_jl_proj_1_2' => '<b>Resultaat:</b> 16 uur handwerk per week geautomatiseerd.',
            'cv_s_edu' => 'Opleiding',
            'cv_t_edu' => 'MBO 4 ICT-beheer, <a href="https://radiuscollege.nl" rel="noopener" target="_blank">Radius College Breda</a>.',
            'cv_b_contact' => 'Contact', 'cv_b_where' => 'Eindhoven, Nederland', 'cv_b_links' => 'Links', 'cv_b_exp' => 'Expertise', 'cv_b_infra' => 'Infrastructuur', 'cv_b_sec' => 'Beveiliging', 'cv_b_sec1' => 'Netwerk',
            'cv_b_lang' => 'Talen', 'cv_b_l1' => 'Engels', 'cv_b_l2' => 'Nederlands', 'cv_b_l3' => 'Russisch',
            'cv_cta_h' => 'Wil je dit op papier, of in je inbox?',
            'cv_cta_p' => 'Download hierboven de PDF, of schrijf me en ik stuur hem met een korte notitie over wat ik voor jouw systeem kan doen.',
            'cv_cta_b1' => 'Gebruik het contactformulier', 'cv_cta_b2' => 'Bekijk de projecten',
        ];

        return $nl;
    }
}
