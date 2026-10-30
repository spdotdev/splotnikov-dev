<?php

namespace Spdotdev\SplotnikovDev;

/**
 * The toolkit: seven groups of tools and practices in daily use, the same groups as the CV's expertise.
 * The home page shows three of them, /toolkit shows all.
 *
 * @phpstan-type Tool array{name:string, note:string, nl:string}
 * @phpstan-type Group array{slug:string, label:string, title:string, tools:list<Tool>, nl:array{label:string, title:string}}
 */
final class Toolkit
{
    public const HOME = ['frameworks', 'ai', 'security'];

    /**
     * @return list<Group>
     */
    public static function all(): array
    {
        return [
            [
                'slug' => 'languages',
                'label' => 'Languages',
                'title' => 'PHP since 2008, the right language next to it',
                'nl' => ['label' => 'Talen', 'title' => 'PHP sinds 2008, de juiste taal ernaast'],
                'tools' => [
                    ['name' => 'PHP', 'note' => 'Since 2008, from 5.x to 8.4, in production every day', 'nl' => 'Sinds 2008, van 5.x tot 8.4, elke dag in productie'],
                    ['name' => 'Python', 'note' => 'Data pipelines, scrapers, AI tooling, the glue between systems', 'nl' => 'Datapipelines, scrapers, AI-tooling, de lijm tussen systemen'],
                    ['name' => 'JavaScript / TypeScript', 'note' => 'Front ends without a framework, Node.js services, browser automation', 'nl' => 'Front-ends zonder framework, Node.js-services, browserautomatisering'],
                    ['name' => 'Kotlin, Go, C / C++', 'note' => 'Android clients, small daemons, the low-level corners', 'nl' => 'Android-clients, kleine daemons, de low-level hoeken'],
                    ['name' => 'SQL, Bash', 'note' => 'Schema design and queries that explain themselves; scripts that run unattended', 'nl' => 'Schemaontwerp en queries die zichzelf uitleggen; scripts die onbeheerd draaien'],
                ],
            ],
            [
                'slug' => 'frameworks',
                'label' => 'Frameworks',
                'title' => 'Laravel first, the others when they are already there',
                'nl' => ['label' => 'Frameworks', 'title' => 'Laravel eerst, de andere als ze er al zijn'],
                'tools' => [
                    ['name' => 'Laravel, Filament', 'note' => 'Products, client platforms, admin panels; packages mounted in one host', 'nl' => 'Producten, klantplatforms, beheerpanelen; pakketten in één host'],
                    ['name' => 'Symfony, Phalcon', 'note' => 'The legacy side of migrations: kept running while the new code takes over', 'nl' => 'De legacy kant van migraties: blijft draaien terwijl de nieuwe code overneemt'],
                    ['name' => 'Node.js', 'note' => 'Event-driven services and relays', 'nl' => 'Event-driven services en relays'],
                    ['name' => 'Jetpack Compose', 'note' => 'Android apps for my own products', 'nl' => 'Android-apps voor mijn eigen producten'],
                ],
            ],
            [
                'slug' => 'automation',
                'label' => 'Automation',
                'title' => 'If it repeats, it runs on its own',
                'nl' => ['label' => 'Automatisering', 'title' => 'Als het zich herhaalt, draait het vanzelf'],
                'tools' => [
                    ['name' => 'Ansible', 'note' => 'A hardened server from one playbook; staging and production are the same machine', 'nl' => 'Een geharde server uit één playbook; staging en productie zijn dezelfde machine'],
                    ['name' => 'GitHub Actions (CI/CD)', 'note' => 'Tests, static analysis and security gates on every push; rollback image kept', 'nl' => 'Tests, statische analyse en securitygates bij elke push; rollback-image bewaard'],
                    ['name' => 'n8n, webhooks, queues', 'note' => 'Workflows between tools; retries, idempotency and a report when something fails', 'nl' => 'Workflows tussen tools; retries, idempotentie en een melding als iets faalt'],
                    ['name' => 'Docker Compose, cron, systemd', 'note' => 'Nightly jobs, backups, the services that must keep running', 'nl' => 'Nachtelijke taken, back-ups, de services die moeten blijven draaien'],
                    ['name' => 'Playwright, Claude in Chrome', 'note' => 'Browser automation for the chores that only have a web interface', 'nl' => 'Browserautomatisering voor de klusjes die alleen een webinterface hebben'],
                ],
            ],
            [
                'slug' => 'ai',
                'label' => 'AI',
                'title' => 'Agents with guardrails, not a chatbot bolted on',
                'nl' => ['label' => 'AI', 'title' => 'Agents met vangrails, geen chatbot erop geplakt'],
                'tools' => [
                    ['name' => 'Claude Code, skills, MCP', 'note' => 'The daily tool; skills as written procedures, MCP servers in front of APIs', 'nl' => 'Het dagelijkse gereedschap; skills als geschreven procedures, MCP-servers vóór API\'s'],
                    ['name' => 'LLM orchestration, agent loops', 'note' => 'Multi-step agents, councils of models, a human approval step', 'nl' => 'Meerstaps-agents, raden van modellen, een menselijke goedkeuringsstap'],
                    ['name' => 'RAG, embeddings, evaluation sets', 'note' => 'Local code search with LanceDB and Ollama; a test set before any prompt ships', 'nl' => 'Lokale codesearch met LanceDB en Ollama; een testset voordat een prompt live gaat'],
                    ['name' => 'Claude Opus and Sonnet 5.5, Haiku 5.5, Gemini, OpenRouter', 'note' => 'The right model per task, with a cost cap per day', 'nl' => 'Het juiste model per taak, met een kostenplafond per dag'],
                    ['name' => 'Voice: speech to text, TTS, phone agents', 'note' => 'A voice assistant on my own phone and server; a Voice AI support line for a client', 'nl' => 'Een spraakassistent op mijn eigen telefoon en server; een Voice AI-supportlijn voor een klant'],
                ],
            ],
            [
                'slug' => 'infra',
                'label' => 'Infrastructure',
                'title' => 'Servers that can be rebuilt from a file',
                'nl' => ['label' => 'Infrastructuur', 'title' => 'Servers die uit een bestand herbouwd kunnen worden'],
                'tools' => [
                    ['name' => 'Linux: Debian, Ubuntu, Arch', 'note' => 'Servers and the laptop; everything tuned by hand and written down', 'nl' => 'Servers en de laptop; alles met de hand afgesteld en opgeschreven'],
                    ['name' => 'Docker, DigitalOcean, Proxmox', 'note' => 'Small cloud boxes and a home lab', 'nl' => 'Kleine cloudservers en een thuislab'],
                    ['name' => 'Caddy, nginx', 'note' => 'TLS by default, one proxy in front of many sites', 'nl' => 'TLS standaard, één proxy voor veel sites'],
                    ['name' => 'MySQL, PostgreSQL, Redis', 'note' => 'Migrations without downtime, queues and caches', 'nl' => 'Migraties zonder downtime, queues en caches'],
                    ['name' => 'Disaster recovery', 'note' => 'Backups off-site, restores tested and timed, monitoring to one phone', 'nl' => 'Back-ups off-site, herstel getest en geklokt, monitoring naar één telefoon'],
                ],
            ],
            [
                'slug' => 'networking',
                'label' => 'Networking',
                'title' => 'Know the network before you trust it',
                'nl' => ['label' => 'Netwerken', 'title' => 'Ken het netwerk voordat je het vertrouwt'],
                'tools' => [
                    ['name' => 'Design and segmentation', 'note' => 'VLANs, a guest and an IoT segment, nothing talks to what it does not need', 'nl' => 'VLAN\'s, een gast- en een IoT-segment, niets praat met wat het niet nodig heeft'],
                    ['name' => 'Firewalls', 'note' => 'Default deny on every host, the few ports that must be open', 'nl' => 'Standaard dicht op elke host, de paar poorten die open moeten'],
                    ['name' => 'DNS and TLS', 'note' => 'Records, certificates, HSTS; the part people forget until it breaks', 'nl' => 'Records, certificaten, HSTS; het deel dat men vergeet tot het breekt'],
                    ['name' => 'VPN and monitoring', 'note' => 'Private access to private things; a weekly inventory of my own networks', 'nl' => 'Privétoegang tot privédingen; een wekelijkse inventaris van mijn eigen netwerken'],
                ],
            ],
            [
                'slug' => 'security',
                'label' => 'Security',
                'title' => 'Hardening, testing, evidence, since the BackTrack days',
                'nl' => ['label' => 'Beveiliging', 'title' => 'Hardening, testen, bewijs, sinds de BackTrack-tijd'],
                'tools' => [
                    ['name' => 'Server hardening', 'note' => 'SSH, firewall, fail2ban, unattended updates, secrets out of the code', 'nl' => 'SSH, firewall, fail2ban, automatische updates, secrets uit de code'],
                    ['name' => 'DevSecOps pipelines', 'note' => 'Dependency audit, secret scan and static analysis before anything merges', 'nl' => 'Dependency-audit, secret-scan en statische analyse voordat er iets samengaat'],
                    ['name' => 'Web application testing', 'note' => 'Authorization, sessions, injection, known-vulnerable dependencies; written authorization first', 'nl' => 'Autorisatie, sessies, injectie, afhankelijkheden met bekende lekken; eerst schriftelijke toestemming'],
                    ['name' => 'Backups and recovery', 'note' => 'A backup counts after it has been restored on a scratch box and timed', 'nl' => 'Een back-up telt pas nadat hij op een testmachine hersteld en geklokt is'],
                    ['name' => 'NIS2-aligned practices', 'note' => 'Gap analysis, remediation and evidence a customer or auditor can read', 'nl' => 'Gap-analyse, herstel en bewijs dat een klant of auditor kan lezen'],
                ],
            ],
        ];
    }

    /**
     * @return list<Group>
     */
    public static function home(): array
    {
        return array_values(array_filter(self::all(), fn (array $g): bool => in_array($g['slug'], self::HOME, true)));
    }
}
