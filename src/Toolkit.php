<?php

namespace Spdotdev\SplotnikovDev;

/**
 * The toolkit: six groups of tools and practices in daily use. The home page
 * shows the first three, /toolkit shows all six.
 *
 * @phpstan-type Tool array{name:string, note:string, nl:string}
 * @phpstan-type Group array{slug:string, label:string, title:string, tools:list<Tool>, nl:array{label:string, title:string}}
 */
final class Toolkit
{
    /**
     * @return list<Group>
     */
    public static function all(): array
    {
        return [
            [
                'slug' => 'backend',
                'label' => 'Backend engineering',
                'title' => 'PHP first, the right tool next to it',
                'nl' => ['label' => 'Backend engineering', 'title' => 'PHP eerst, het juiste gereedschap ernaast'],
                'tools' => [
                    ['name' => 'PHP: Laravel, Phalcon, Symfony', 'note' => 'Since 2008, from 5.x to 8.4, in production', 'nl' => 'Sinds 2008, van 5.x tot 8.4, in productie'],
                    ['name' => 'Python, Node.js, Go', 'note' => 'Data pipelines, AI tooling, event-driven services, small daemons', 'nl' => 'Datapipelines, AI-tooling, event-driven services, kleine daemons'],
                    ['name' => 'MySQL, PostgreSQL, Redis, SQLite', 'note' => 'Schema design, migrations without downtime, queues and caches', 'nl' => 'Schemaontwerp, migraties zonder downtime, queues en caches'],
                    ['name' => 'Kotlin, Jetpack Compose', 'note' => 'Android clients for my own products', 'nl' => 'Android-clients voor mijn eigen producten'],
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
                'slug' => 'security',
                'label' => 'Security',
                'title' => 'Network, web, hardening, since the BackTrack days',
                'nl' => ['label' => 'Beveiliging', 'title' => 'Netwerk, web, hardening, sinds de BackTrack-tijd'],
                'tools' => [
                    ['name' => 'Linux: BackTrack, Kali, BlackArch', 'note' => 'BackTrack on a live CD around 2008, Kali after it, BlackArch on my laptop today', 'nl' => 'BackTrack op een live-cd rond 2008, daarna Kali, vandaag BlackArch op mijn laptop'],
                    ['name' => 'Network security', 'note' => 'Inventory and scanning of my own networks, segmentation, firewalls, VPN', 'nl' => 'Inventarisatie en scans van mijn eigen netwerken, segmentatie, firewalls, VPN'],
                    ['name' => 'Web application testing', 'note' => 'Authorization, sessions, injection, dependencies with known vulnerabilities; written authorization first', 'nl' => 'Autorisatie, sessies, injectie, afhankelijkheden met bekende lekken; eerst schriftelijke toestemming'],
                    ['name' => 'Hardening', 'note' => 'SSH, firewall, fail2ban, unattended updates, secrets out of the code, backups that have been restored', 'nl' => 'SSH, firewall, fail2ban, automatische updates, secrets uit de code, back-ups die hersteld zijn'],
                ],
            ],
            [
                'slug' => 'infra',
                'label' => 'Architecture and infrastructure',
                'title' => 'Infrastructure that can be rebuilt from a file',
                'nl' => ['label' => 'Architectuur en infrastructuur', 'title' => 'Infrastructuur die uit een bestand herbouwd kan worden'],
                'tools' => [
                    ['name' => 'Docker, Ansible, Linux', 'note' => 'Debian, Ubuntu and Arch; infrastructure as code, one playbook for staging and production', 'nl' => 'Debian, Ubuntu en Arch; infrastructuur als code, één playbook voor staging en productie'],
                    ['name' => 'DigitalOcean, Proxmox, Caddy, nginx', 'note' => 'Small cloud boxes and a home lab, TLS by default', 'nl' => 'Kleine cloudservers en een thuislab, TLS standaard'],
                    ['name' => 'Modular monoliths, Strangler Fig', 'note' => 'Architecture reviews, scalability audits, migrations in daily use', 'nl' => 'Architectuurreviews, schaalbaarheidsaudits, migraties die in gebruik blijven'],
                    ['name' => 'Disaster recovery', 'note' => 'Backups off-site, restores tested and timed, monitoring to one phone', 'nl' => 'Back-ups off-site, herstel getest en geklokt, monitoring naar één telefoon'],
                ],
            ],
            [
                'slug' => 'devops',
                'label' => 'DevOps and testing',
                'title' => 'Pipelines that block red',
                'nl' => ['label' => 'DevOps en testen', 'title' => 'Pipelines die rood blokkeren'],
                'tools' => [
                    ['name' => 'GitHub Actions, CI/CD', 'note' => 'Tests, static analysis and security gates on every push; rollback image kept', 'nl' => 'Tests, statische analyse en securitygates bij elke push; rollback-image bewaard'],
                    ['name' => 'PHPUnit, Larastan, Pint', 'note' => 'The suite runs before merge; nothing merges red', 'nl' => 'De suite draait vóór de merge; niets gaat rood samen'],
                    ['name' => 'Playwright, browser testing', 'note' => 'Real pages, light and dark, phone and desktop, before anything deploys', 'nl' => 'Echte pagina\'s, licht en donker, telefoon en desktop, voordat er iets live gaat'],
                    ['name' => 'Git: rebase, small releases', 'note' => 'One intent per commit, a rollback for every release, tags for what runs in production', 'nl' => 'Eén intentie per commit, een rollback voor elke release, tags voor wat in productie draait'],
                ],
            ],
            [
                'slug' => 'automation',
                'label' => 'Automation',
                'title' => 'If it repeats, it runs on its own',
                'nl' => ['label' => 'Automatisering', 'title' => 'Als het zich herhaalt, draait het vanzelf'],
                'tools' => [
                    ['name' => 'Python, shell, cron, systemd', 'note' => 'Scrapers, pipelines, nightly jobs, the glue between systems', 'nl' => 'Scrapers, pipelines, nachtelijke taken, de lijm tussen systemen'],
                    ['name' => 'n8n, webhooks, queues', 'note' => 'Workflows between tools; retries, idempotency and a report when something fails', 'nl' => 'Workflows tussen tools; retries, idempotentie en een melding als iets faalt'],
                    ['name' => 'Agents in the loop', 'note' => 'An agent drafts, a person taps; evaluation set, cost cap, switch-off in the runbook', 'nl' => 'Een agent schrijft een concept, een mens tikt; evaluatieset, kostenplafond, uitknop in het runbook'],
                    ['name' => 'Browser automation', 'note' => 'Playwright and Claude in Chrome for the chores that only have a web interface', 'nl' => 'Playwright en Claude in Chrome voor de klusjes die alleen een webinterface hebben'],
                ],
            ],
        ];
    }

    /**
     * @return list<Group>
     */
    public static function home(): array
    {
        return array_slice(self::all(), 0, 3);
    }
}
