<?php

namespace Spdotdev\SplotnikovDev;

/**
 * Open source projects from github.com/spdotdev, the personal side. Client work
 * never appears here; the two anonymized cases live in Cases.
 *
 * @phpstan-type Project array{slug:string, name:string, kind:string, short:string, stack:list<string>, url:string, nl:array{kind:string, short:string}}
 */
final class Projects
{
    /**
     * @return array<string, Project>
     */
    public static function all(): array
    {
        return [
            'whatsapp-mcp' => [
                'slug' => 'whatsapp-mcp',
                'name' => 'WhatsApp MCP',
                'kind' => 'Fork, in daily use · Go, Python',
                'short' => 'An MCP server that lets an assistant read and send WhatsApp through a linked device. I run it on my own server for reminders, archives and a voice relay; the fork adds mentions, revoke, media and responder toggles.',
                'stack' => ['Go', 'Python', 'MCP', 'SQLite'],
                'url' => 'https://github.com/spdotdev/whatsapp-mcp',
                'nl' => ['kind' => 'Fork, dagelijks in gebruik · Go, Python', 'short' => 'Een MCP-server waarmee een assistent WhatsApp leest en verstuurt via een gekoppeld apparaat. Ik draai hem op mijn eigen server voor herinneringen, archieven en een spraakrelay; de fork voegt mentions, intrekken, media en responder-schakelaars toe.'],
            ],
            'skill-manual' => [
                'slug' => 'skill-manual',
                'name' => 'Manual',
                'kind' => 'Claude Code skill · open source',
                'short' => 'One command that turns any codebase into a designed field manual: architecture, how it runs, how to change it, as an artifact page, Markdown and an optional standalone HTML or PDF. Built because every handover needs one.',
                'stack' => ['Claude Code', 'Skills', 'HTML'],
                'url' => 'https://github.com/spdotdev/skill-manual',
                'nl' => ['kind' => 'Claude Code-skill · open source', 'short' => 'Eén commando dat elke codebase omzet in een vormgegeven handboek: architectuur, hoe het draait, hoe je het aanpast, als artifact-pagina, Markdown en een losse HTML of PDF. Gebouwd omdat elke overdracht er een nodig heeft.'],
            ],
            'claude-telegram-relay' => [
                'slug' => 'claude-telegram-relay',
                'name' => 'Claude Telegram Relay',
                'kind' => 'Reference pattern · TypeScript',
                'short' => 'The smallest honest way to run Claude Code as an always-on Telegram bot: one daemon, one chat, the full tool set, with the setup for Linux, macOS and Windows. The pattern behind my own manager agent.',
                'stack' => ['TypeScript', 'Claude Code', 'Telegram'],
                'url' => 'https://github.com/spdotdev/claude-telegram-relay',
                'nl' => ['kind' => 'Referentiepatroon · TypeScript', 'short' => 'De kleinste eerlijke manier om Claude Code als altijd-aan Telegram-bot te draaien: één daemon, één chat, de volledige toolset, met de installatie voor Linux, macOS en Windows. Het patroon achter mijn eigen manager-agent.'],
            ],
            'inventory' => [
                'slug' => 'inventory',
                'name' => 'Inventory',
                'kind' => 'Own product · Laravel, Android',
                'short' => 'A private inventory for several households: a Laravel backend with an API and an MCP server, and a Kotlin Android client. Scan, count, share a list, ask the assistant where the spare keys are.',
                'stack' => ['Laravel', 'Kotlin', 'MCP', 'Filament'],
                'url' => 'https://github.com/spdotdev/inventory-laravel',
                'nl' => ['kind' => 'Eigen product · Laravel, Android', 'short' => 'Een privé voorraadbeheer voor meerdere huishoudens: een Laravel-backend met API en MCP-server, en een Android-client in Kotlin. Scan, tel, deel een lijst, vraag de assistent waar de reservesleutels liggen.'],
            ],
            'whatsapp-analyser' => [
                'slug' => 'whatsapp-analyser',
                'name' => 'WhatsApp Analyser',
                'kind' => 'Tool · Python',
                'short' => 'Reads a WhatsApp export and answers the questions you actually have: who talks when, how fast people reply, what a group is really about. Pandas, no cloud, your data stays on your disk.',
                'stack' => ['Python', 'Pandas'],
                'url' => 'https://github.com/spdotdev/WhatsAppAnalyser',
                'nl' => ['kind' => 'Tool · Python', 'short' => 'Leest een WhatsApp-export en beantwoordt de vragen die je echt hebt: wie praat wanneer, hoe snel mensen antwoorden, waar een groep eigenlijk over gaat. Pandas, geen cloud, je data blijft op je eigen schijf.'],
            ],
            'bitburner-scripts' => [
                'slug' => 'bitburner-scripts',
                'name' => 'Bitburner scripts',
                'kind' => 'Game automation · JavaScript',
                'short' => 'A game about hacking, turned into an engineering project: scripts synced into Bitburner over its remote file API, a scheduler that spreads work over every server it owns, and a bridge so the editor on my laptop is the editor in the game.',
                'stack' => ['JavaScript', 'Node.js'],
                'url' => 'https://github.com/spdotdev/bitburner-scripts',
                'nl' => ['kind' => 'Game-automatisering · JavaScript', 'short' => 'Een spel over hacken, omgebouwd tot engineeringproject: scripts die via de remote file API het spel in gaan, een planner die werk verdeelt over elke server die hij bezit, en een brug zodat de editor op mijn laptop de editor in het spel is.'],
            ],
            'prompt' => [
                'slug' => 'prompt',
                'name' => 'Prompt',
                'kind' => 'Front-end mockup · HTML, JavaScript',
                'short' => 'A static mockup of an AI chat interface: sidebar, thread, syntax-highlighted code blocks, no model behind it. The starting point I use when a client wants to see what an assistant on their data would look like before anything is built.',
                'stack' => ['HTML', 'CSS', 'JavaScript'],
                'url' => 'https://github.com/spdotdev/prompt',
                'nl' => ['kind' => 'Front-end mockup · HTML, JavaScript', 'short' => 'Een statische mockup van een AI-chatinterface: zijbalk, gesprek, codeblokken met syntax-kleuring, geen model erachter. Het startpunt dat ik gebruik als een klant wil zien hoe een assistent op zijn data eruitziet voordat er iets gebouwd wordt.'],
            ],
            'splotnikov-dev' => [
                'slug' => 'splotnikov-dev',
                'name' => 'splotnikov.dev',
                'kind' => 'This site · Laravel package',
                'short' => 'This website as a Laravel library: one shell, one stylesheet, one script, a terminal panel with typed scenes, a printable CV, a contact form, tests for every page, deployed from main through a host app on one small server.',
                'stack' => ['Laravel', 'Blade', 'PHPUnit', 'GitHub Actions'],
                'url' => 'https://github.com/spdotdev/splotnikov-dev',
                'nl' => ['kind' => 'Deze site · Laravel-pakket', 'short' => 'Deze website als Laravel-bibliotheek: één shell, één stylesheet, één script, een terminalpaneel met getypte scènes, een printbaar CV, een contactformulier, tests voor elke pagina, uitgerold vanaf main via een hostapp op één kleine server.'],
            ],
        ];
    }
}
