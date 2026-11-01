# Estimates against real time

Every time estimate given to Stas for work on this site, with the real wall-clock time from his go to deployed and checked live (plan, review and fixes included). Same table as sd-plan keeps in its docs.

| Slice | Estimate | Go | Done and live | Real time |
|---|---|---|---|---|
| UI proposals 1 to 3: top menu with equal gaps and Tools in the Dutch menu (both sites), whoami as a list on the phone, readable terminal output on the phone (v0.3.14, scuttle-dev v0.7.7) | 40 + 30 + 15 = 85 min | 2026-10-30 06:00 | 2026-10-30 06:12 | 12 min |
| UI proposal 4: portrait next to the name on the desktop, terminal in the first screen (v0.3.15) | 20 min | 2026-10-30 06:24 | 2026-10-30 06:32 | 8 min |
| UI proposal 5: the projects as compact rows on the phone (v0.3.16) | 30 min | 2026-10-30 06:35 | 2026-10-30 06:44 | 9 min |
| UI proposal 6: CV on the phone, contact first and the other side blocks fold (v0.3.17) | 30 min | 2026-10-30 06:49 | 2026-10-30 07:04 | 15 min |
| Take Manifesto out of the top menu (v0.3.35), plus the calendar month fix found on the way | 15 min | 2026-10-31 13:09 | 2026-10-31 13:25 | 16 min |
| Mail and phone links only on the contact page; home, footer and privacy point at the form, the CV keeps the addresses as text (v0.3.36, re-tagged v0.3.37) | 40 min, both sites together | 2026-10-31 15:49 | 2026-10-31 16:19 | 30 min, shared with scuttle-dev and the four booking picks; the last 7 min were a re-tag after a parallel push rebased the tagged commit off main and a Pint style fix |
| Round 9, autonomous agent run (same request as scuttle-dev): Dutch topics and errors on the contact form, shorter lead, Send in the first phone screen, noopener, snippets, contact button on the projects page, 12 new tests (v0.3.38) | none given, started on his message | 2026-10-31 21:58 | 2026-10-31 22:28 | 30 min: agent 20 min (Sonnet, medium effort), tag, deploy and smoke check 10 min |
| Round 10, autonomous agent run (same standing order): no long dashes plus a guard test, 44 px buttons on phones, errors in both languages, post title cut at the colon, CV project count derived, posts end with a contact button, throttle test (v0.3.39) | none given | 2026-10-31 23:53 | 2026-11-01 00:11 | 18 min: agent 10 min (Sonnet, medium effort), tag, deploy and live check 8 min |
| Round 11, autonomous agent run (same standing order): contact line on toolkit and blog, readable without JavaScript, photo modal focus, print sheets, outbound link check in the weekly crawl, Atom feed, deprecations fail the suite, Larastan 6 (v0.3.40) | none given | 2026-11-01 00:23 | 2026-11-01 00:44 | 21 min: agent 12 min (Sonnet, medium effort), tag, deploy and smoke check 9 min |
| Round 12, autonomous agent run (same standing order): contact form never follows the referer, session fallback and CMS fallback when the database is down, cache headers, Dutch 404, post body links, package files, Dutch consistency test, Lighthouse 98 to 100 on the dump (v0.3.41) | none given | 2026-11-01 01:05 | 2026-11-01 01:30 | 25 min: agent 19 min (Sonnet, medium effort), queued behind the scuttle.dev deploy, deploy and smoke check 6 min |
