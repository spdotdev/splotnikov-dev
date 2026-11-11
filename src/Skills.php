<?php

namespace Spdotdev\SplotnikovDev;

/**
 * The skills we built, with what each does and how often it has been used on the author's machine.
 * Counts come from the local usage record (skillUsage in ~/.claude.json, 2026-11-11). Zero means built and not yet
 * used on a real job: the page says so instead of claiming more. Only one repository is public; the others are
 * private, so the page names them without a link. Update the counts when the record changes.
 */
final class Skills
{
    /** @return list<array{name: string, what: string, uses: int, public_url: ?string}> */
    public static function all(): array
    {
        return [
            ['name' => 'Ralph loops', 'what' => 'Runs an autonomous coding loop until a task is done, with a router that picks an in-session loop or a fresh-context loop for a larger build.', 'uses' => 68, 'public_url' => null],
            ['name' => 'Focus', 'what' => 'Narrows a large codebase to the exact spot to work on, by feature or by folder, and saves a focus brief as the next task.', 'uses' => 30, 'public_url' => null],
            ['name' => 'Architect and orchestrate', 'what' => 'Turns a task into a constrained coding prompt, either with a human in the loop or as an autonomous loop with clean pivots.', 'uses' => 36, 'public_url' => null],
            ['name' => 'Debrief', 'what' => 'Summarises a working session: what was done, grouped by task, with a status for each and the time it took.', 'uses' => 8, 'public_url' => null],
            ['name' => 'Tick off', 'what' => 'Closes out the open items of a session: fixes the critical ones now and files the edge cases as GitHub issues.', 'uses' => 3, 'public_url' => null],
            ['name' => 'PDF form fill', 'what' => 'Fills a PDF form. It checks first whether the PDF has real fillable fields, and fills every field with a value or "not applicable".', 'uses' => 3, 'public_url' => null],
            ['name' => 'Hour estimates', 'what' => 'Estimates billable hours for client work, calibrated on logged hours, with a buffer and a confidence per line.', 'uses' => 1, 'public_url' => null],
            ['name' => 'Field manual', 'what' => 'Writes a visually designed field manual for a codebase, as a page and as Markdown.', 'uses' => 1, 'public_url' => 'https://github.com/spdotdev/skill-manual'],
            ['name' => 'Advice pack', 'what' => 'Brainstorms a client question, then produces an advice pack: a Markdown note, a page, a PDF and an estimate.', 'uses' => 0, 'public_url' => null],
            ['name' => 'Repository standard', 'what' => 'The standard for the repository around an AI skill, with a tool that sizes, scaffolds and validates it.', 'uses' => 0, 'public_url' => null],
            ['name' => 'Infographic', 'what' => 'Builds a category-grouped icon infographic as a page, with optional animated icons and a video export.', 'uses' => 0, 'public_url' => null],
            ['name' => 'Model advisor', 'what' => 'Chooses the model and effort level for a new agent run, using a small, cheap model.', 'uses' => 0, 'public_url' => null],
        ];
    }
}
