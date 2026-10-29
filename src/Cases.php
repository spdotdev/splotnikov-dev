<?php

namespace Spdotdev\SplotnikovDev;

/**
 * Two pieces of client work, anonymized, shown at the bottom of the projects page.
 *
 * @phpstan-type CaseItem array{label:string, title:string, mess:string, solution:string, impact:string, facts:list<array{string, string}>, tags:list<string>, nl:array{label:string, title:string, mess:string, solution:string, impact:string, facts:list<array{string, string}>}}
 */
final class Cases
{
    /**
     * @return list<CaseItem>
     */
    public static function all(): array
    {
        return [
            [
                'label' => 'Legacy modernization',
                'title' => 'Modernizing a monolith: Phalcon to Laravel',
                'mess' => 'A client was trapped in a legacy Phalcon system on an unsupported PHP, unable to scale or to find specialized talent.',
                'solution' => 'A custom PHP middleware layer that lets both frameworks run side by side, then an incremental migration (the Strangler Fig pattern), module by module.',
                'impact' => '60 percent lower latency, PHP 7.4 to 8.4 back in support, and the hotels that run on the platform kept working through the whole migration.',
                'facts' => [['60%', 'lower latency'], ['7.4 → 8.4', 'PHP, back in support'], ['in use', 'during the whole migration']],
                'tags' => ['PHP', 'Laravel', 'Phalcon', 'Strangler Fig'],
                'nl' => [
                    'label' => 'Modernisering van legacy',
                    'title' => 'Een monoliet moderniseren: Phalcon naar Laravel',
                    'mess' => 'Een klant zat vast in een legacy Phalcon-systeem op een PHP zonder support, kon niet schalen en vond geen specialisten.',
                    'solution' => 'Een eigen PHP-middlewarelaag waarmee beide frameworks naast elkaar draaien, daarna een stapsgewijze migratie (het Strangler Fig-patroon), module voor module.',
                    'impact' => '60 procent lagere latency, PHP 7.4 naar 8.4 weer in support, en de hotels op het platform bleven de hele migratie door werken.',
                    'facts' => [['60%', 'lagere latency'], ['7.4 → 8.4', 'PHP, weer in support'], ['in gebruik', 'tijdens de hele migratie']],
                ],
            ],
            [
                'label' => 'Automation with AI',
                'title' => 'AI agents in the sales stack',
                'mess' => 'Manual research and data entry were stalling a sales operation: hours a week of looking things up and typing them over.',
                'solution' => 'LLM agents integrated into the existing workflow: they research prospects, draft in the sender\'s words and queue for a human tap. Evaluation set, cost cap and a switch-off in the runbook.',
                'impact' => '16 hours a week of manual work reclaimed; nothing leaves the building without a person approving it.',
                'facts' => [['16 h', 'a week reclaimed'], ['human', 'approval before any send'], ['capped', 'model cost per day']],
                'tags' => ['LLM', 'AI agents', 'Python', 'Automation'],
                'nl' => [
                    'label' => 'Automatisering met AI',
                    'title' => 'AI-agents in de salesstack',
                    'mess' => 'Handmatig onderzoek en data-invoer remden een salesoperatie: uren per week opzoeken en overtypen.',
                    'solution' => 'LLM-agents in de bestaande workflow: ze onderzoeken prospects, schrijven concepten in de woorden van de afzender en zetten ze klaar voor één menselijke tik. Evaluatieset, kostenplafond en een uitknop in het runbook.',
                    'impact' => '16 uur handwerk per week terug; niets verlaat het pand zonder dat een mens het goedkeurt.',
                    'facts' => [['16 u', 'per week terug'], ['mens', 'keurt goed vóór elke verzending'], ['begrensd', 'modelkosten per dag']],
                ],
            ],
        ];
    }
}
