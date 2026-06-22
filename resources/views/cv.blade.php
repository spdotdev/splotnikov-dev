<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CV | Stanislav Plotnikov</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&display=swap"
        rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"
        crossorigin="anonymous"></script>
    <style>
        :root {
            --bg-primary: #ffffff;
            --bg-secondary: #f8fafc;
            --bg-card: #ffffff;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --accent: #0f766e;
            --accent-hover: #0d9488;
            --border: #e2e8f0;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        [data-theme="dark"] {
            --bg-primary: #0a0a0a;
            --bg-secondary: #1a1a1a;
            --bg-card: #1a1a1a;
            --text-primary: #f1f5f9;
            --text-secondary: #94a3b8;
            --accent: #2dd4bf;
            --accent-hover: #5eead4;
            --border: #2a2a2a;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DM Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--bg-primary);
            color: var(--text-primary);
            line-height: 1.6;
            transition: var(--transition);
        }

        /* Navigation - same as portfolio */
        nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: var(--bg-primary);
            border-bottom: 1px solid var(--border);
            z-index: 1000;
            transition: var(--transition);
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--accent);
            text-decoration: none;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            list-style: none;
            align-items: center;
        }

        .nav-links a {
            color: var(--text-secondary);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
        }

        .nav-links a:hover {
            color: var(--accent);
        }

        .nav-links a.nav-active {
            color: var(--accent);
        }

        .theme-toggle {
            background: var(--bg-secondary);
            border: 1px solid var(--border);
            padding: 0.5rem;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
        }

        .theme-toggle:hover {
            background: var(--border);
        }

        .theme-toggle svg {
            width: 20px;
            height: 20px;
            fill: var(--text-primary);
        }

        .sun-icon {
            display: none;
        }

        .moon-icon {
            display: block;
        }

        [data-theme="dark"] .sun-icon {
            display: block;
        }

        [data-theme="dark"] .moon-icon {
            display: none;
        }

        .mobile-menu {
            display: none;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0.5rem;
        }

        .mobile-menu svg {
            width: 24px;
            height: 24px;
            stroke: var(--text-primary);
        }

        /* CV page main */
        .cv-page {
            padding: 6rem 2rem 4rem;
            max-width: 900px;
            margin: 0 auto;
        }

        .cv-page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1rem;
            flex-wrap: wrap;
        }

        .cv-page-label {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--text-secondary);
        }

        .cv-actions {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .cv-actions-sep {
            color: var(--text-secondary);
            font-size: 0.875rem;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            transition: var(--transition);
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            font-family: inherit;
        }

        .btn-primary {
            background: var(--accent);
            color: white;
        }

        .btn-primary:hover {
            background: var(--accent-hover);
        }

        .btn-secondary {
            background: var(--bg-secondary);
            color: var(--text-primary);
            border: 1px solid var(--border);
        }

        .btn-secondary:hover {
            background: var(--border);
        }

        .btn-ghost {
            background: none;
            color: var(--text-secondary);
            padding: 0.35rem 0.5rem;
            font-size: 0.875rem;
        }

        .btn-ghost:hover {
            color: var(--accent);
        }

        /* CV content wrapper: document feel (accent edge, subtle shadow) */
        #cv-content {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-left: 3px solid var(--accent);
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
            padding: 2.5rem;
            line-height: 1.45;
            display: flex;
            gap: 2rem;
            align-items: flex-start;
        }

        .cv-main {
            flex: 1;
            min-width: 0;
        }

        .cv-right {
            flex-shrink: 0;
            width: 180px;
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        .cv-photo {
            flex-shrink: 0;
        }

        .cv-photo img {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            object-fit: cover;
            display: block;
            border: 3px solid var(--border);
        }

        .cv-photo .photo-placeholder {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            background: var(--bg-secondary);
            border: 3px dashed var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-secondary);
            font-size: 0.75rem;
            text-align: center;
            padding: 0.5rem;
        }

        .cv-sidebar {
            font-size: 0.85rem;
            color: var(--text-primary);
        }

        .cv-contact-block {
            margin-bottom: 1.25rem;
        }

        .cv-sidebar .cv-contact {
            color: var(--text-secondary);
            font-size: 0.85rem;
            margin: 0;
            line-height: 1.6;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .cv-sidebar .cv-contact .cv-contact-line,
        .cv-sidebar .cv-contact a {
            display: block;
        }

        .cv-sidebar .cv-contact a {
            color: var(--accent);
            text-decoration: none;
        }

        .cv-sidebar .cv-contact a:hover {
            text-decoration: underline;
        }

        .cv-links-block .cv-links {
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
        }

        .cv-links-block .cv-links a {
            font-size: 0.8rem;
            color: var(--accent);
            text-decoration: none;
        }

        .cv-links-block .cv-links a:hover {
            text-decoration: underline;
        }

        .cv-company-block .cv-company-details {
            margin: 0;
            font-size: 0.8rem;
            color: var(--text-primary);
            line-height: 1.5;
            display: flex;
            flex-direction: column;
            gap: 0.2rem;
        }

        .cv-company-block .cv-company-name {
            font-weight: 600;
        }

        .cv-company-block .cv-company-line {
            font-size: 0.75rem;
            color: var(--text-secondary);
        }

        .cv-company-block .cv-company-details a {
            color: var(--accent);
            text-decoration: none;
        }

        .cv-company-block .cv-company-details a:hover {
            text-decoration: underline;
        }

        .cv-focus {
            padding-top: 1rem;
            margin-top: 1rem;
            border-top: 1px solid var(--border);
        }

        .cv-focus-label {
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--text-secondary);
            margin: 0 0 0.5rem;
        }

        .cv-expertise-group {
            margin-bottom: 0.5rem;
        }

        .cv-expertise-group:last-child {
            margin-bottom: 0;
        }

        .cv-expertise-group-label {
            display: block;
            font-size: 0.6rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--text-secondary);
            margin-bottom: 0.25rem;
        }

        .cv-focus-pills {
            display: flex;
            flex-wrap: wrap;
            gap: 0.35rem;
        }

        .cv-pill {
            display: inline-block;
            font-size: 0.7rem;
            font-weight: 500;
            color: var(--text-primary);
            background: var(--bg-secondary);
            border: 1px solid var(--border);
            padding: 0.25rem 0.5rem;
            border-radius: 999px;
        }

        .cv-main h1 {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
            color: var(--text-primary);
        }

        .cv-main .cv-title {
            font-size: 1.1rem;
            color: var(--text-primary);
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .cv-main .cv-availability {
            font-size: 0.9rem;
            color: var(--text-secondary);
            margin-bottom: 0.5rem;
        }

        .cv-main .cv-contact {
            color: var(--text-secondary);
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .cv-main .cv-contact a {
            color: var(--accent);
            text-decoration: none;
        }

        .cv-main .cv-contact a:hover {
            text-decoration: underline;
        }

        .cv-main h2 {
            font-size: 1rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin: 1.25rem 0 0.6rem;
            color: var(--text-primary);
            border-bottom: 1px solid var(--border);
            padding-bottom: 0.25rem;
        }

        .cv-main h2:first-of-type {
            margin-top: 0;
        }

        .cv-main h2.cv-section-primary {
            font-size: 1.1rem;
            margin-top: 1.5rem;
        }

        .cv-main p {
            font-size: 0.9rem;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .cv-main ul {
            margin: 0.4rem 0 0.8rem 1.25rem;
            font-size: 0.9rem;
        }

        .cv-main li {
            margin-bottom: 0.25rem;
        }

        .cv-main .job-block {
            margin-bottom: 1rem;
        }

        .cv-main .job-title {
            font-weight: 600;
            font-size: 0.95rem;
        }

        .cv-main .job-title a {
            color: var(--accent);
            text-decoration: none;
        }

        .cv-main .job-title a:hover {
            text-decoration: underline;
        }

        .cv-main .job-meta {
            font-size: 0.85rem;
            color: var(--text-secondary);
            margin-bottom: 0.35rem;
        }

        .cv-main .project-block {
            margin-bottom: 0.9rem;
        }

        .cv-main .project-block h3 {
            font-size: 0.95rem;
            font-weight: 600;
            margin-bottom: 0.35rem;
        }

        .cv-main .project-block p {
            font-size: 0.85rem;
            margin-bottom: 0.2rem;
        }

        .cv-main .past-roles-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.45rem;
            margin-bottom: 0.8rem;
        }

        .cv-main .past-role-card {
            border: 1px solid var(--border);
            border-radius: 6px;
            padding: 0.5rem 0.65rem;
            background: var(--bg-secondary);
        }

        .cv-main .past-role-name {
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--text-primary);
            margin: 0 0 0.1rem;
        }

        .cv-main .past-role-sector {
            font-size: 0.7rem;
            color: var(--accent);
            font-weight: 500;
            margin: 0 0 0.2rem;
        }

        .cv-main .past-role-desc {
            font-size: 0.78rem;
            color: var(--text-secondary);
            margin: 0;
            line-height: 1.4;
        }

        .cv-main .education-line {
            font-size: 0.9rem;
            font-weight: 600;
        }

        .cv-main .education-line a {
            color: var(--accent);
            text-decoration: none;
        }

        /* PDF export: one A4 only, photo on right, minimal margins, identical to print */
        #cv-content.pdf-export {
            display: flex !important;
            flex-direction: row !important;
            width: 200mm;
            height: 277mm;
            min-height: 277mm;
            max-height: 277mm;
            padding: 6mm;
            gap: 6mm;
            background: #ffffff !important;
            border: none;
            border-radius: 0;
            box-shadow: none;
            overflow: hidden;
            box-sizing: border-box;
        }

        #cv-content.pdf-export .cv-main {
            order: 1;
            flex: 1;
            min-width: 0;
        }

        #cv-content.pdf-export .cv-right {
            order: 2;
            flex-shrink: 0;
        }

        /* Light theme + high-contrast text for PDF (no gray, no dark edges) */
        #cv-content.pdf-export-light {
            background: #ffffff !important;
            color: #1e293b !important;
        }

        #cv-content.pdf-export-light .job-title a,
        #cv-content.pdf-export-light .cv-contact a,
        #cv-content.pdf-export-light .cv-links a,
        #cv-content.pdf-export-light .cv-company-details a,
        #cv-content.pdf-export-light .education-line a {
            color: #1a56db !important;
        }

        #cv-content.pdf-export-light .cv-title {
            color: #1e293b !important;
        }

        #cv-content.pdf-export-light .cv-availability {
            color: #334155 !important;
        }

        #cv-content.pdf-export-light .cv-contact,
        #cv-content.pdf-export-light .job-meta,
        #cv-content.pdf-export-light .cv-sidebar {
            color: #334155 !important;
        }

        #cv-content.pdf-export-light h2 {
            border-color: #cbd5e1 !important;
            color: #1e293b !important;
        }

        #cv-content.pdf-export-light p,
        #cv-content.pdf-export-light li {
            color: #1e293b !important;
        }

        #cv-content.pdf-export-light .cv-focus-label,
        #cv-content.pdf-export-light .cv-expertise-group-label {
            color: #334155 !important;
        }

        #cv-content.pdf-export-light .cv-company-details,
        #cv-content.pdf-export-light .cv-company-line {
            color: #1e293b !important;
        }

        #cv-content.pdf-export-light .cv-pill {
            color: #1e293b !important;
            background: #f1f5f9 !important;
            border-color: #e2e8f0 !important;
        }

        #cv-content.pdf-export .cv-photo img,
        #cv-content.pdf-export .cv-photo .photo-placeholder {
            border-color: #cbd5e1;
        }

        #cv-content.pdf-export .cv-photo .photo-placeholder {
            background: #f1f5f9;
            color: #475569;
        }

        /* PDF: scaled to fill one A4 page */
        #cv-content.pdf-export .cv-main {
            font-size: 9pt;
            line-height: 1.30;
        }

        #cv-content.pdf-export .cv-main h1 {
            font-size: 14pt;
        }

        #cv-content.pdf-export .cv-main .cv-title {
            font-size: 10pt;
        }

        #cv-content.pdf-export .cv-main .cv-availability {
            font-size: 8.5pt;
        }

        #cv-content.pdf-export .cv-main h2 {
            font-size: 8.5pt;
            margin: 4pt 0 2pt;
            padding-bottom: 1pt;
        }

        #cv-content.pdf-export .cv-main h2.cv-section-primary {
            font-size: 9.5pt;
            margin-top: 5pt;
        }

        #cv-content.pdf-export .cv-main p,
        #cv-content.pdf-export .cv-main ul {
            font-size: 8.5pt;
        }

        #cv-content.pdf-export .cv-main ul {
            margin: 2pt 0 2pt 10pt;
        }

        #cv-content.pdf-export .cv-main li {
            margin-bottom: 1pt;
        }

        #cv-content.pdf-export .cv-main .job-block {
            margin-bottom: 4pt;
        }

        #cv-content.pdf-export .cv-main .job-meta {
            font-size: 8pt;
        }

        #cv-content.pdf-export .cv-main .project-block {
            margin-bottom: 3pt;
        }

        #cv-content.pdf-export .cv-main .project-block h3 {
            font-size: 8.5pt;
        }

        #cv-content.pdf-export .cv-main .project-block p {
            font-size: 8pt;
        }

        #cv-content.pdf-export .cv-main .past-roles-grid {
            grid-template-columns: 1fr 1fr 1fr;
            gap: 2.5pt;
            margin-bottom: 4pt;
        }

        #cv-content.pdf-export .cv-main .past-role-card {
            padding: 3pt 5pt;
        }

        #cv-content.pdf-export .cv-main .past-role-name {
            font-size: 7.5pt;
        }

        #cv-content.pdf-export .cv-main .past-role-sector {
            font-size: 6.5pt;
        }

        #cv-content.pdf-export .cv-main .past-role-desc {
            font-size: 7pt;
        }

        #cv-content.pdf-export .cv-right {
            width: 38mm;
            gap: 4pt;
        }

        #cv-content.pdf-export .cv-photo img,
        #cv-content.pdf-export .cv-photo .photo-placeholder {
            width: 32mm;
            height: 32mm;
        }

        #cv-content.pdf-export .cv-sidebar {
            font-size: 8pt;
        }

        #cv-content.pdf-export .cv-contact-block {
            margin-bottom: 4pt;
        }

        #cv-content.pdf-export .cv-sidebar .cv-contact {
            font-size: 7.5pt;
            gap: 3pt;
        }

        #cv-content.pdf-export .cv-links-block .cv-links {
            font-size: 7pt;
        }

        #cv-content.pdf-export .cv-company-block .cv-company-details {
            font-size: 7pt;
        }

        #cv-content.pdf-export .cv-company-block .cv-company-line {
            font-size: 6.5pt;
        }

        #cv-content.pdf-export .cv-focus {
            padding-top: 3pt;
            margin-top: 3pt;
        }

        #cv-content.pdf-export .cv-focus-label {
            font-size: 6.5pt;
        }

        #cv-content.pdf-export .cv-expertise-group {
            margin-bottom: 3pt;
        }

        #cv-content.pdf-export .cv-expertise-group-label {
            font-size: 5.5pt;
            margin-bottom: 1pt;
        }

        #cv-content.pdf-export .cv-focus-pills {
            gap: 2pt;
        }

        #cv-content.pdf-export .cv-pill {
            font-size: 6.5pt;
            padding: 1.5pt 4pt;
        }

        @media screen and (max-width: 768px) {
            .nav-links {
                display: none;
            }

            .nav-links.active {
                display: flex;
                flex-direction: column;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: var(--bg-primary);
                border-bottom: 1px solid var(--border);
                padding: 1rem 2rem;
                gap: 1rem;
            }

            .mobile-menu {
                display: block;
            }

            #cv-content {
                flex-direction: column;
                align-items: stretch;
            }

            .cv-right {
                order: -1;
                width: 100%;
                align-items: center;
                flex-direction: column;
            }

            .cv-sidebar {
                max-width: 100%;
            }
        }

        /* Print: one A4, same layout as PDF (photo stays right), high-contrast text */
        @media print {

            nav,
            .cv-actions,
            .theme-toggle,
            .mobile-menu,
            .no-print {
                display: none !important;
            }

            body {
                background: #fff;
                color: #1e293b;
                padding: 0;
            }

            .cv-page {
                padding: 0;
                max-width: 100%;
            }

            #cv-content {
                display: flex !important;
                flex-direction: row !important;
                border: none;
                border-radius: 0;
                padding: 6mm;
                background: #fff;
                box-shadow: none;
                min-height: 285mm;
                max-height: 285mm;
                gap: 6mm;
                align-items: flex-start;
                overflow: hidden;
                box-sizing: border-box;
            }

            #cv-content .cv-main {
                order: 1;
                flex: 1;
                min-width: 0;
            }

            #cv-content .cv-right {
                order: 2;
                flex-shrink: 0;
                width: 38mm;
                gap: 4pt;
            }

            /* Dark text everywhere for visibility (no gray) */
            #cv-content .cv-main,
            #cv-content .cv-sidebar {
                color: #1e293b;
            }

            #cv-content .cv-main .job-title a,
            #cv-content .cv-main .education-line a,
            #cv-content .cv-contact a,
            #cv-content .cv-links a,
            #cv-content .cv-company-details a {
                color: #1a56db;
            }

            #cv-content .cv-main .cv-title {
                color: #1e293b;
            }

            #cv-content .cv-contact,
            #cv-content .job-meta,
            #cv-content .cv-sidebar {
                color: #334155;
            }

            #cv-content h2 {
                color: #1e293b;
                border-color: #cbd5e1;
            }

            #cv-content p,
            #cv-content li {
                color: #1e293b;
            }

            #cv-content .cv-main {
                font-size: 9pt;
                line-height: 1.25;
            }

            #cv-content .cv-main h1 {
                font-size: 14pt;
            }

            #cv-content .cv-main .cv-title {
                font-size: 10pt;
            }

            #cv-content .cv-main .cv-availability {
                font-size: 8.5pt;
                color: #334155;
            }

            #cv-content .cv-main h2 {
                font-size: 8.5pt;
                margin: 3pt 0 2pt;
                padding-bottom: 1pt;
            }

            #cv-content .cv-main h2.cv-section-primary {
                font-size: 9.5pt;
                margin-top: 4pt;
            }

            #cv-content .cv-main p,
            #cv-content .cv-main ul {
                font-size: 8.5pt;
            }

            #cv-content .cv-main ul {
                margin: 2pt 0 2pt 10pt;
            }

            #cv-content .cv-main li {
                margin-bottom: 1pt;
            }

            #cv-content .cv-main .job-block {
                margin-bottom: 4pt;
            }

            #cv-content .cv-main .job-meta {
                font-size: 8pt;
            }

            #cv-content .cv-main .project-block {
                margin-bottom: 3pt;
            }

            #cv-content .cv-main .project-block h3 {
                font-size: 8.5pt;
            }

            #cv-content .cv-main .project-block p {
                font-size: 8pt;
            }

            #cv-content .cv-main .past-roles-grid {
                grid-template-columns: 1fr 1fr 1fr;
                gap: 2.5pt;
                margin-bottom: 4pt;
            }

            #cv-content .cv-main .past-role-card {
                padding: 3pt 5pt;
            }

            #cv-content .cv-main .past-role-name {
                font-size: 7.5pt;
            }

            #cv-content .cv-main .past-role-sector {
                font-size: 6.5pt;
            }

            #cv-content .cv-main .past-role-desc {
                font-size: 7pt;
            }

            #cv-content .cv-photo img,
            #cv-content .cv-photo .photo-placeholder {
                width: 32mm;
                height: 32mm;
                border-color: #cbd5e1;
            }

            #cv-content .cv-sidebar {
                font-size: 8pt;
            }

            #cv-content .cv-contact-block {
                margin-bottom: 4pt;
            }

            #cv-content .cv-sidebar .cv-contact {
                font-size: 7.5pt;
                gap: 3pt;
            }

            #cv-content .cv-links-block .cv-links {
                font-size: 7pt;
            }

            #cv-content .cv-company-block .cv-company-details {
                font-size: 7pt;
            }

            #cv-content .cv-company-block .cv-company-line {
                font-size: 6.5pt;
            }

            #cv-content .cv-focus {
                padding-top: 3pt;
                margin-top: 3pt;
            }

            #cv-content .cv-focus-label {
                font-size: 6.5pt;
            }

            #cv-content .cv-expertise-group {
                margin-bottom: 3pt;
            }

            #cv-content .cv-expertise-group-label {
                font-size: 5.5pt;
                margin-bottom: 1pt;
            }

            #cv-content .cv-focus-pills {
                gap: 2pt;
            }

            #cv-content .cv-pill {
                font-size: 6.5pt;
                padding: 1.5pt 4pt;
            }

            @page {
                size: A4;
                margin: 0;
            }

            html,
            body {
                width: 210mm;
                min-height: 297mm;
                height: 297mm;
                margin: 0;
                padding: 0;
                overflow: hidden;
            }

            #cv-content {
                overflow: hidden;
            }
        }
    </style>
</head>

<body>
    <nav>
        <div class="nav-container">
            <a href="index.html" class="logo">SP</a>
            <ul class="nav-links">
                <li><a href="index.html">Home</a></li>
                <li><a href="cv.html" class="nav-active">CV</a></li>
                <li><a href="index.html#manifesto">Manifesto</a></li>
                <li><a href="index.html#proof">Proof</a></li>
                <li><a href="index.html#toolkit">Toolkit</a></li>
                <li><a href="index.html#contact">Contact</a></li>
                <li>
                    <button class="theme-toggle no-print" aria-label="Toggle theme">
                        <svg class="sun-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="5"></circle>
                            <line x1="12" y1="1" x2="12" y2="3"></line>
                            <line x1="12" y1="21" x2="12" y2="23"></line>
                            <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
                            <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
                            <line x1="1" y1="12" x2="3" y2="12"></line>
                            <line x1="21" y1="12" x2="23" y2="12"></line>
                            <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
                            <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
                        </svg>
                        <svg class="moon-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                        </svg>
                    </button>
                </li>
            </ul>
            <button class="mobile-menu no-print" aria-label="Menu">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
            </button>
        </div>
    </nav>

    <main class="cv-page">
        <div class="cv-page-header no-print">
            <p class="cv-page-label">Resume</p>
            <div class="cv-actions">
                <button type="button" class="btn btn-ghost" id="btn-print" aria-label="Print CV">Print</button>
                <span class="cv-actions-sep">·</span>
                <button type="button" class="btn btn-ghost" id="btn-pdf" aria-label="Download PDF">Download PDF</button>
            </div>
        </div>

        <div id="cv-content">
            <div class="cv-main">
                <h1>Stanislav Plotnikov</h1>
                <p class="cv-title">Senior Developer / Legacy Modernization & Solution Architect</p>
                <p class="cv-availability">Available for freelance and contract roles.</p>

                <h2>Executive Summary</h2>
                <p>Pragmatic Solution Architect with 14 years of professional experience, focused on transforming
                    legacy technical debt into scalable, high-performance infrastructure. Specialist in incremental
                    modernization (Strangler Fig Pattern) and Modular Monolith design. I prioritize business continuity
                    and ROI over industry trends, ensuring zero-downtime migrations and long-term maintainability.</p>

                <h2>Core Competencies</h2>
                <ul>
                    <li><strong>Architecture:</strong> System Design, Modular Monoliths, Microservices Feasibility, API
                        Gateway Design.</li>
                    <li><strong>Engineering:</strong> PHP (Laravel, Symfony, Phalcon expert), Node.js, Python (AI/LLM
                        Integration).</li>
                    <li><strong>Cloud & DevOps:</strong> Docker, Ansible, CI/CD (GitHub Actions), Linux
                        Systems Hardening.</li>
                    <li><strong>Strategic AI:</strong> LLM Orchestration, Agentic Workflows, Automated System
                        Monitoring.</li>
                </ul>

                <h2 class="cv-section-primary">Professional Experience</h2>

                <div class="job-block">
                    <p class="job-title">Senior Developer / Startup Advisor</p>
                    <ul>
                        <li>Conducted in-depth marketing research for a startup entering the Brazilian specialty coffee
                            market in the Tilburg region of the Netherlands.</li>
                        <li>Delivered actionable market entry insights covering local consumer behavior, competitive
                            landscape, and positioning strategy to guide the client's go-to-market decisions.</li>
                    </ul>
                </div>

                <div class="job-block">
                    <p class="job-title">Senior Developer / Modernization Engineer | Hosintra B.V.</p>
                    <ul>
                        <li>Upgraded the core application framework from Phalcon 3.4 to Laravel 12, enabling access to a
                            modern ecosystem and long-term vendor support.</li>
                        <li>Migrated the runtime environment from PHP 7.4 to PHP 8.4, resolving all deprecations and
                            modernizing the codebase to leverage current language features.</li>
                    </ul>
                </div>

                <div class="job-block">
                    <p class="job-title">Senior Developer / Automation & AI Engineer | East-West Support</p>
                    <ul>
                        <li>Developed automated data processing pipelines in Python, increasing throughput and
                            automation speed by 40%.</li>
                        <li>Architected and built a scalable, robust Voice AI phone call automation platform for
                            customer support, reducing client support workforce requirements by 100%.</li>
                    </ul>
                </div>

                <div class="job-block">
                    <p class="job-title">Senior Developer / API Integration Engineer | Hosintra B.V.</p>
                    <ul>
                        <li>Engineered a custom middleware abstraction layer for complex vendor API integrations,
                            significantly reducing technical debt and eliminating client migration risk, saving the
                            product.</li>
                    </ul>
                </div>

                <h2 class="cv-section-primary">Employment History</h2>

                <div class="past-roles-grid">
                    <div class="past-role-card">
                        <p class="past-role-name">Getnoticed</p>
                        <p class="past-role-sector">Recruitment Marketing · PHP / Symfony</p>
                        <p class="past-role-desc">Developed employer branding and conversion-driven career websites for the labor market.</p>
                    </div>
                    <div class="past-role-card">
                        <p class="past-role-name">Bek</p>
                        <p class="past-role-sector">Omnichannel Communications · PHP / Laravel</p>
                        <p class="past-role-desc">Bridged physical print and digital marketing portals to help brands communicate consistently with customers.</p>
                    </div>
                    <div class="past-role-card">
                        <p class="past-role-name">New Brand Activators</p>
                        <p class="past-role-sector">Brand Activation · PHP / Laravel</p>
                        <p class="past-role-desc">Created physical and digital brand interaction platforms to drive customer loyalty and engagement.</p>
                    </div>
                    <div class="past-role-card">
                        <p class="past-role-name">Mijnwebwinkel</p>
                        <p class="past-role-sector">E-commerce / SaaS · PHP / Symfony</p>
                        <p class="past-role-desc">Built and maintained SaaS infrastructure enabling businesses to launch and manage online stores.</p>
                    </div>
                    <div class="past-role-card">
                        <p class="past-role-name">@Leisure Group</p>
                        <p class="past-role-sector">E-commerce / Travel · PHP / Symfony</p>
                        <p class="past-role-desc">Developed a digital travel platform processing bookings and payments for vacation rentals.</p>
                    </div>
                    <div class="past-role-card">
                        <p class="past-role-name">Van Os Beheer & Beleggingen BV</p>
                        <p class="past-role-sector">Finance / Telecom · PHP</p>
                        <p class="past-role-desc">Delivered digital marketing and telecom solutions within a financial holding group.</p>
                    </div>
                </div>

                <h2 class="cv-section-primary">Key Projects</h2>

                <div class="project-block">
                    <h3>Legacy Modernization Project (Phalcon to Laravel)</h3>
                    <p><strong>Challenge:</strong> Client was stuck on an unmaintainable legacy framework with high
                        vendor-lock-in risk.</p>
                    <p><strong>Solution:</strong> Built a bridge layer to allow parallel execution of both frameworks.
                        Migrated high-risk modules first while keeping the legacy system operational.</p>
                    <p><strong>Result:</strong> Reduced Total Cost of Ownership (TCO) and enabled the client to ship new
                        features 30% faster.</p>
                </div>

                <div class="project-block">
                    <h3>Automation & AI-Driven Workflow</h3>
                    <p><strong>Challenge:</strong> Manual data entry and filtering were creating bottlenecks in client
                        operations.</p>
                    <p><strong>Solution:</strong> Integrated LLM agents into existing workflows for intelligent
                        automation
                        of sales.</p>
                    <p><strong>Result:</strong> Automated 16h of manual labor per week.</p>
                </div>

                <h2>Education & Certifications</h2>
                <p class="education-line">MBO 4 IT Administration | <a href="https://radiuscollege.nl">Radius College
                        Breda</a></p>
            </div>
            <div class="cv-right">
                <div class="cv-photo">
                    <img src="{{ asset('vendor/splotnikov/profile.png') }}" data-profile-img alt="Stanislav Plotnikov"
                        onerror="this.style.display='none'; var p=this.nextElementSibling; if(p) p.style.display='flex';">
                    <div class="photo-placeholder" style="display:none">Add {{ asset('vendor/splotnikov/profile.png') }} for your photo
                    </div>
                </div>
                <div class="cv-sidebar">
                    <div class="cv-contact-block">
                        <p class="cv-contact">
                            <span class="cv-contact-line">Eindhoven, Netherlands</span>
                            <a href="tel:+31634500328">+31 (0) 6 345 00 328</a>
                            <a href="mailto:stplotnikov@gmail.com">stplotnikov@gmail.com</a>
                            <a href="https://splotnikov.dev">splotnikov.dev</a>
                        </p>
                    </div>
                    <div class="cv-focus cv-links-block">
                        <p class="cv-focus-label">Links</p>
                        <p class="cv-links">
                            <a href="https://linkedin.com/in/splotnikov">linkedin.com/in/splotnikov</a>
                            <a href="https://github.com/spdotdev">github.com/spdotdev</a>
                        </p>
                    </div>
                    <div class="cv-focus cv-company-block">
                        <p class="cv-focus-label">Business details</p>
                        <p class="cv-company-details">
                            <span class="cv-company-name">Scuttle Development</span>
                            <a href="tel:+31618233175" class="cv-company-line">+31 (0) 6 182 33 175</a>
                            <a href="mailto:info@scuttle.dev" class="cv-company-line">info@scuttle.dev</a>
                            <span class="cv-company-line">KVK: 96040947</span>
                            <span class="cv-company-line">VAT: NL005184584B85</span>
                        </p>
                    </div>
                    <div class="cv-focus">
                        <p class="cv-focus-label">Expertise</p>
                        <div class="cv-expertise-group">
                            <span class="cv-expertise-group-label">Backend</span>
                            <div class="cv-focus-pills">
                                <span class="cv-pill">PHP</span>
                                <span class="cv-pill">Laravel</span>
                                <span class="cv-pill">Symfony</span>
                                <span class="cv-pill">Python</span>
                            </div>
                        </div>
                        <div class="cv-expertise-group">
                            <span class="cv-expertise-group-label">Infra</span>
                            <div class="cv-focus-pills">
                                <span class="cv-pill">Docker</span>
                                <span class="cv-pill">Linux</span>
                                <span class="cv-pill">Ansible</span>
                                <span class="cv-pill">DevOps & CI/CD</span>
                            </div>
                        </div>
                        <div class="cv-expertise-group">
                            <span class="cv-expertise-group-label">AI</span>
                            <div class="cv-focus-pills">
                                <span class="cv-pill">Claude</span>
                                <span class="cv-pill">Cursor</span>
                                <span class="cv-pill">Perplexity</span>
                                <span class="cv-pill">Gemini</span>
                                <span class="cv-pill">OpenRouter</span>
                            </div>
                        </div>
                    </div>
                    <div class="cv-focus">
                        <p class="cv-focus-label">Languages</p>
                        <div class="cv-focus-pills">
                            <span class="cv-pill">English</span>
                            <span class="cv-pill">Dutch</span>
                            <span class="cv-pill">Russian</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Profile image: resolve path for both custom domain (/) and project path (/splotnikov/)
        (function () {
            var base = location.pathname.indexOf('/splotnikov') === 0 ? '/splotnikov' : '';
            document.querySelectorAll('img[data-profile-img]').forEach(function (img) { img.src = base + '/{{ asset('vendor/splotnikov/profile.png') }}'; });
        })();
        // Theme (same as portfolio)
        const themeToggle = document.querySelector('.theme-toggle');
        const html = document.documentElement;
        const savedTheme = localStorage.getItem('theme') || 'light';
        html.setAttribute('data-theme', savedTheme);
        if (themeToggle) {
            themeToggle.addEventListener('click', () => {
                const currentTheme = html.getAttribute('data-theme');
                const newTheme = currentTheme === 'light' ? 'dark' : 'light';
                html.setAttribute('data-theme', newTheme);
                localStorage.setItem('theme', newTheme);
            });
        }

        // Mobile menu
        const mobileMenu = document.querySelector('.mobile-menu');
        const navLinks = document.querySelector('.nav-links');
        if (mobileMenu && navLinks) {
            mobileMenu.addEventListener('click', () => navLinks.classList.toggle('active'));
        }

        // Print
        document.getElementById('btn-print').addEventListener('click', () => {
            const el = document.getElementById('cv-content');
            el.classList.add('pdf-export', 'pdf-export-light');
            setTimeout(() => {
                window.print();
                el.classList.remove('pdf-export', 'pdf-export-light');
            }, 100);
        });

        // PDF (ATS-optimized: one A4, always light theme to avoid dark edges)
        document.getElementById('btn-pdf').addEventListener('click', () => {
            const el = document.getElementById('cv-content');
            const btn = document.getElementById('btn-pdf');
            el.classList.add('pdf-export', 'pdf-export-light');

            requestAnimationFrame(() => {
                const opt = {
                    margin: 0,
                    filename: 'Stanislav_Plotnikov_CV_2026.pdf',
                    image: { type: 'jpeg', quality: 1 },
                    html2canvas: {
                        scale: 2,
                        useCORS: true,
                        letterRendering: true,
                        logging: false,
                        backgroundColor: '#ffffff'
                    },
                    jsPDF: {
                        unit: 'mm',
                        format: 'a4',
                        orientation: 'portrait',
                        hotfixes: ['px_scaling']
                    },
                    pagebreak: { mode: ['avoid-all', 'css', 'legacy'] }
                };
                btn.disabled = true;
                btn.textContent = 'Generating…';
                html2pdf().set(opt).from(el).save().then(() => {
                    el.classList.remove('pdf-export', 'pdf-export-light');
                    btn.disabled = false;
                    btn.textContent = 'Download PDF';
                }).catch(() => {
                    el.classList.remove('pdf-export', 'pdf-export-light');
                    btn.disabled = false;
                    btn.textContent = 'Download PDF';
                });
            });
        });
    </script>
</body>

</html>