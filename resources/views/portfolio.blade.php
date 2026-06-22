<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio | Stanislav Plotnikov</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&display=swap"
        rel="stylesheet">
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
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
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
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.5), 0 2px 4px -2px rgba(0, 0, 0, 0.5);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.5), 0 4px 6px -4px rgba(0, 0, 0, 0.5);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'DM Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--bg-primary);
            color: var(--text-primary);
            line-height: 1.6;
            transition: var(--transition);
        }

        /* Navigation */
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

        /* Hero Section */
        .hero {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 6rem 2rem 4rem;
            background: var(--bg-primary);
            overflow: hidden;
        }

        .hero-bg {
            position: absolute;
            inset: 0;
            z-index: 0;
            width: 100%;
            height: 100%;
        }

        .hero-terminals {
            position: absolute;
            inset: 0;
            z-index: 0;
            pointer-events: none;
            overflow: hidden;
        }

        .terminal-panel {
            position: absolute;
            border-radius: 10px;
            border: 1px solid var(--border);
            background: var(--bg-card);
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .terminal-panel:nth-child(1) {
            top: 10%;
            left: 5%;
            width: min(300px, 82vw);
            max-height: 200px;
        }

        .terminal-panel:nth-child(1) .terminal-body {
            height: 158px;
        }

        .terminal-panel:nth-child(1) .terminal-scroll-run {
            animation-duration: 52s;
        }

        .terminal-panel:nth-child(2) {
            top: 48%;
            right: 6%;
            transform: translateY(-50%);
            width: min(260px, 75vw);
            max-height: 260px;
        }

        .terminal-panel:nth-child(2) .terminal-body {
            height: 218px;
        }

        .terminal-panel:nth-child(2) .terminal-scroll-run {
            animation-duration: 38s;
        }

        .terminal-panel:nth-child(3) {
            bottom: 12%;
            left: 8%;
            width: min(340px, 88vw);
            max-height: 160px;
        }

        .terminal-panel:nth-child(3) .terminal-body {
            height: 118px;
        }

        .terminal-panel:nth-child(3) .terminal-scroll-run {
            animation-duration: 65s;
        }

        .terminal-panel:nth-child(4) {
            top: 14%;
            right: 4%;
            width: min(280px, 78vw);
            max-height: 220px;
        }

        .terminal-panel:nth-child(4) .terminal-body {
            height: 178px;
        }

        .terminal-panel:nth-child(4) .terminal-scroll-run {
            animation-duration: 44s;
        }

        .terminal-panel:nth-child(5) {
            bottom: 18%;
            right: 12%;
            width: min(270px, 76vw);
            max-height: 200px;
        }

        .terminal-panel:nth-child(5) .terminal-body {
            height: 158px;
        }

        .terminal-panel:nth-child(5) .terminal-scroll-run {
            animation-duration: 58s;
        }

        .terminal-title {
            padding: 6px 10px;
            font-size: 0.68rem;
            color: var(--text-secondary);
            background: var(--bg-secondary);
            border-bottom: 1px solid var(--border);
            font-family: ui-monospace, monospace;
        }

        .terminal-body {
            overflow: hidden;
            padding: 8px 10px;
        }

        .terminal-scroll {
            font-family: ui-monospace, "SF Mono", Menlo, monospace;
            font-size: 0.68rem;
            line-height: 1.5;
            color: var(--text-secondary);
        }

        .terminal-scroll span {
            color: var(--accent);
        }

        .terminal-scroll .cmd {
            color: var(--text-primary);
        }

        .terminal-scroll-run {
            animation: terminal-scroll 50s linear infinite;
        }

        @keyframes terminal-scroll {
            0% {
                transform: translateY(0);
            }

            100% {
                transform: translateY(-50%);
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .terminal-scroll-run {
                animation: none;
            }
        }

        @media (max-width: 900px) {

            .terminal-panel:nth-child(2),
            .terminal-panel:nth-child(3),
            .terminal-panel:nth-child(4),
            .terminal-panel:nth-child(5) {
                display: none;
            }
        }

        .hero-content {
            position: relative;
            z-index: 1;
            max-width: 800px;
            text-align: center;
        }

        .hero-content .hero-avatar-wrap {
            animation: hero-in 0.7s ease-out both;
        }

        .hero-content .hero-avatar-wrap+h1 {
            animation: hero-in 0.6s ease-out 0.1s both;
        }

        .hero-content .hero-avatar-wrap+h1+p {
            animation: hero-in 0.6s ease-out 0.2s both;
        }

        .hero-content blockquote {
            animation: hero-in 0.6s ease-out 0.3s both;
        }

        .hero-content .hero-buttons {
            animation: hero-in 0.6s ease-out 0.45s both;
        }

        @keyframes hero-in {
            from {
                opacity: 0;
                transform: translateY(24px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .hero-avatar-wrap {
            position: relative;
            width: 200px;
            height: 200px;
            margin: 0 auto 2rem;
        }

        .hero-avatar-wrap::before {
            content: '';
            position: absolute;
            inset: -12px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent), #0d9488, var(--accent));
            background-size: 200% 200%;
            animation: hero-glow 6s ease-in-out infinite;
            opacity: 0.35;
            z-index: 0;
        }

        .hero-avatar {
            position: relative;
            z-index: 1;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            overflow: hidden;
            border: 3px solid var(--border);
        }

        .hero-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        @keyframes hero-glow {

            0%,
            100% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }
        }

        .hero h1 {
            font-size: clamp(2.5rem, 5vw, 4rem);
            font-weight: 700;
            margin-bottom: 1rem;
            letter-spacing: -0.02em;
        }

        .hero h1 span {
            color: var(--accent);
        }

        .hero p {
            font-size: 1.25rem;
            color: var(--text-secondary);
            margin-bottom: 2rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .hero-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 0.875rem 2rem;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: var(--accent);
            color: white;
        }

        .btn-primary:hover {
            background: var(--accent-hover);
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn-secondary {
            background: var(--bg-secondary);
            color: var(--text-primary);
            border: 1px solid var(--border);
        }

        .btn-secondary:hover {
            background: var(--border);
            transform: translateY(-2px);
        }

        /* Section Styles – clear sections like reference */
        section {
            padding: 4rem 2rem 5rem;
        }

        .section-alt {
            background: var(--bg-secondary);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .section-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .section-header h2 {
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 700;
            margin-bottom: 0.5rem;
            letter-spacing: -0.02em;
        }

        .section-header p {
            color: var(--text-secondary);
            max-width: 600px;
            margin: 0 auto;
        }

        .section-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 0.5rem;
        }

        .hero blockquote {
            font-size: 1.25rem;
            color: var(--text-secondary);
            font-style: italic;
            margin: 0 auto 2rem;
            max-width: 600px;
            border-left: none;
        }

        .manifesto-content {
            max-width: 720px;
            margin: 0 auto;
        }

        .manifesto-content ul {
            list-style: none;
            margin-top: 1.5rem;
        }

        .manifesto-content li {
            color: var(--text-secondary);
            margin-bottom: 1rem;
            padding-left: 1.5rem;
            position: relative;
        }

        .manifesto-content li::before {
            content: "—";
            position: absolute;
            left: 0;
            color: var(--accent);
        }

        /* About Section */
        .about-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
        }

        .about-image {
            aspect-ratio: 1;
            background: linear-gradient(135deg, var(--accent), #8b5cf6);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .about-image svg {
            width: 60%;
            height: 60%;
            opacity: 0.3;
        }

        .about-content h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .about-content p {
            color: var(--text-secondary);
            margin-bottom: 1.5rem;
        }

        .about-stats {
            display: flex;
            gap: 2rem;
        }

        .stat {
            text-align: center;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--accent);
        }

        .stat-label {
            color: var(--text-secondary);
            font-size: 0.875rem;
        }

        /* Skills Section */
        .skills-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
        }

        .skill-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 2rem;
            transition: var(--transition);
        }

        .skill-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
            border-color: var(--accent);
        }

        .skill-icon {
            width: 48px;
            height: 48px;
            background: var(--bg-secondary);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }

        .skill-icon svg {
            width: 24px;
            height: 24px;
            stroke: var(--accent);
        }

        .skill-card h3 {
            margin-bottom: 0.5rem;
        }

        .skill-card p {
            color: var(--text-secondary);
            font-size: 0.95rem;
        }

        /* Projects Section */
        .projects-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
        }

        .project-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
            transition: var(--transition);
            padding: 0;
        }

        .project-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
            border-color: var(--accent);
        }

        .project-image {
            height: 200px;
            background: linear-gradient(135deg, var(--accent), #8b5cf6);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .project-image svg {
            width: 64px;
            height: 64px;
            opacity: 0.5;
        }

        .project-image--terminal {
            background: var(--bg-secondary);
            border-bottom: 1px solid var(--border);
            display: block;
            padding: 0;
            overflow: hidden;
        }

        .project-terminal-title {
            padding: 4px 8px;
            font-size: 0.65rem;
            color: var(--text-secondary);
            background: var(--bg-card);
            border-bottom: 1px solid var(--border);
            font-family: ui-monospace, monospace;
        }

        .project-terminal-body {
            height: 168px;
            overflow: hidden;
            padding: 6px 8px;
        }

        .project-terminal-scroll {
            font-family: ui-monospace, "SF Mono", Menlo, monospace;
            font-size: 0.65rem;
            line-height: 1.45;
            color: var(--text-secondary);
        }

        .project-terminal-scroll span {
            color: var(--accent);
        }

        .project-terminal-scroll .cmd {
            color: var(--text-primary);
        }

        .project-terminal-scroll-run {
            animation: project-terminal-scroll 28s linear infinite;
        }

        @keyframes project-terminal-scroll {
            0% {
                transform: translateY(0);
            }

            100% {
                transform: translateY(-50%);
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .project-terminal-scroll-run {
                animation: none;
            }
        }

        .project-image--ai-prompt {
            background: var(--bg-secondary);
            border-bottom: 1px solid var(--border);
            display: block;
            padding: 0;
            overflow: hidden;
        }

        .project-ai-title {
            padding: 4px 8px;
            font-size: 0.65rem;
            color: var(--text-secondary);
            background: rgba(15, 118, 110, 0.08);
            border-bottom: 1px solid rgba(15, 118, 110, 0.2);
            font-family: ui-monospace, monospace;
        }

        [data-theme="dark"] .project-ai-title {
            background: rgba(45, 212, 191, 0.06);
            border-bottom-color: rgba(45, 212, 191, 0.2);
        }

        .project-ai-body {
            height: 168px;
            overflow: hidden;
            padding: 8px 10px;
        }

        .project-ai-scroll {
            font-family: ui-sans-serif, system-ui, sans-serif;
            font-size: 0.7rem;
            line-height: 1.5;
            color: var(--text-primary);
        }

        .project-ai-scroll .prompt-line {
            margin-bottom: 6px;
            padding-left: 8px;
            border-left: 2px solid var(--accent);
            color: var(--text-secondary);
        }

        .project-ai-scroll .prompt-label {
            font-size: 0.6rem;
            color: var(--text-secondary);
            margin-bottom: 2px;
        }

        .project-ai-scroll-run {
            animation: project-ai-scroll 32s linear infinite;
        }

        @keyframes project-ai-scroll {
            0% {
                transform: translateY(0);
            }

            100% {
                transform: translateY(-50%);
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .project-ai-scroll-run {
                animation: none;
            }
        }

        .project-content {
            padding: 1.5rem;
        }

        .project-content h3 {
            margin-bottom: 0.5rem;
        }

        .project-content p {
            color: var(--text-secondary);
            margin-bottom: 1rem;
            font-size: 0.95rem;
        }

        .project-tags {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            margin-bottom: 1rem;
        }

        .tag {
            background: var(--bg-secondary);
            color: var(--text-secondary);
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .project-links {
            display: flex;
            gap: 1rem;
        }

        .project-links a {
            color: var(--accent);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .project-links a:hover {
            text-decoration: underline;
        }

        /* Contact Section */
        .contact-content {
            max-width: 600px;
            margin: 0 auto;
            text-align: center;
        }

        .contact-links {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .contact-link {
            width: 56px;
            height: 56px;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
        }

        .contact-link:hover {
            background: var(--accent);
            border-color: var(--accent);
        }

        .contact-link:hover svg {
            stroke: white;
        }

        .contact-link svg {
            width: 24px;
            height: 24px;
            stroke: var(--text-primary);
            transition: var(--transition);
        }

        .contact-email {
            margin-top: 2rem;
            font-size: 1.25rem;
        }

        .contact-email a {
            color: var(--accent);
            text-decoration: none;
        }

        .contact-email a:hover {
            text-decoration: underline;
        }

        /* Footer – multi-column like reference */
        footer {
            position: relative;
            border-top: 1px solid var(--border);
            padding: 3rem 2rem 2rem;
            background: var(--bg-primary);
        }

        .footer-top {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 2rem 3rem;
            flex-wrap: wrap;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 2rem 3rem;
            flex: 1 1 auto;
            min-width: 0;
        }

        .footer-col h4 {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--text-secondary);
            margin-bottom: 1rem;
        }

        .footer-col ul {
            list-style: none;
        }

        .footer-col li {
            margin-bottom: 0.5rem;
        }

        .footer-col a {
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.9rem;
        }

        .footer-col a:hover {
            color: var(--accent);
        }

        .footer-typing-wrap {
            flex-shrink: 0;
            min-height: 7.5rem;
        }

        .footer-typing {
            font-family: ui-monospace, "SF Mono", Menlo, monospace;
            font-size: 0.8rem;
            color: var(--text-secondary);
            width: 380px;
            min-width: 380px;
            letter-spacing: 0.02em;
            box-sizing: border-box;
        }

        .footer-typing-line {
            white-space: nowrap;
            overflow-x: auto;
            overflow-y: hidden;
            height: 1.5em;
            line-height: 1.5em;
        }

        .footer-typing-line::-webkit-scrollbar {
            height: 3px;
        }

        .footer-typing-line::-webkit-scrollbar-thumb {
            background: var(--border);
            border-radius: 2px;
        }

        .footer-typing-output {
            font-size: 0.7rem;
            color: var(--text-secondary);
            opacity: 0.9;
            margin-top: 0.25rem;
            padding: 0.25rem 0 0 0.25rem;
            line-height: 1.4;
            height: 5.6em;
            min-height: 5.6em;
            overflow-y: auto;
            overflow-x: hidden;
            white-space: pre-wrap;
            word-break: break-all;
            box-sizing: border-box;
        }

        .footer-typing-output::-webkit-scrollbar {
            width: 4px;
        }

        .footer-typing-output::-webkit-scrollbar-thumb {
            background: var(--border);
            border-radius: 2px;
        }

        .footer-typing .cursor {
            display: inline-block;
            width: 2px;
            min-width: 2px;
            height: 1.1em;
            background: var(--accent);
            margin-left: 1px;
            vertical-align: text-bottom;
            animation: footer-cursor-blink 0.9s step-end infinite;
        }

        @keyframes footer-cursor-blink {

            0%,
            49% {
                opacity: 1;
            }

            50%,
            100% {
                opacity: 0;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .footer-typing .cursor {
                animation: none;
            }
        }

        @media (max-width: 1023px) {
            .footer-typing-wrap {
                display: none;
            }
        }

        .footer-bottom {
            max-width: 1200px;
            margin: 2rem auto 0;
            padding-top: 2rem;
            border-top: 1px solid var(--border);
            text-align: center;
            color: var(--text-secondary);
            font-size: 0.8rem;
        }

        .footer-bottom p {
            margin: 0;
        }

        /* Mobile Styles */
        @media (max-width: 768px) {
            .nav-links {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: var(--bg-primary);
                border-bottom: 1px solid var(--border);
                flex-direction: column;
                padding: 1rem 2rem;
                gap: 1rem;
            }

            .nav-links.active {
                display: flex;
            }

            .mobile-menu {
                display: block;
            }

            .about-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .about-image {
                max-width: 300px;
                margin: 0 auto;
            }

            .about-stats {
                justify-content: center;
            }

            .projects-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav>
        <div class="nav-container">
            <a href="index.html" class="logo">SP</a>
            <ul class="nav-links">
                <li><a href="index.html" class="nav-link-home nav-active">Home</a></li>
                <li><a href="cv.html">CV</a></li>
                <li><a href="#manifesto">Manifesto</a></li>
                <li><a href="#proof">Proof</a></li>
                <li><a href="#toolkit">Toolkit</a></li>
                <li><a href="#contact">Contact</a></li>
                <li>
                    <button class="theme-toggle" aria-label="Toggle theme">
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
            <button class="mobile-menu" aria-label="Menu">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
            </button>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <canvas class="hero-bg" id="hero-bg" aria-hidden="true"></canvas>
        <div class="hero-terminals" aria-hidden="true">
            <div class="terminal-panel">
                <div class="terminal-title">zsh — 80×24</div>
                <div class="terminal-body">
                    <div class="terminal-scroll terminal-scroll-run">
                        <div class="terminal-block">
                            <div><span>$</span> npm run build</div>
                            <div>&gt; vite v5.0.0</div>
                            <div>building for production...</div>
                            <div class="cmd">✓ 124 modules transformed.</div>
                            <div><span>$</span> git status</div>
                            <div>On branch main</div>
                            <div class="cmd">nothing to commit, working tree clean</div>
                            <div><span>$</span> docker ps</div>
                            <div>CONTAINER ID IMAGE STATUS</div>
                            <div>a1b2c3d4e5f6 app:latest Up 2 hours</div>
                            <div><span>$</span> php artisan migrate</div>
                            <div class="cmd">Migration table created successfully.</div>
                            <div><span>$</span> composer install --no-dev</div>
                            <div>Installing dependencies from lock file</div>
                        </div>
                        <div class="terminal-block">
                            <div><span>$</span> npm run build</div>
                            <div>&gt; vite v5.0.0</div>
                            <div>building for production...</div>
                            <div class="cmd">✓ 124 modules transformed.</div>
                            <div><span>$</span> git status</div>
                            <div>On branch main</div>
                            <div class="cmd">nothing to commit, working tree clean</div>
                            <div><span>$</span> docker ps</div>
                            <div>CONTAINER ID IMAGE STATUS</div>
                            <div>a1b2c3d4e5f6 app:latest Up 2 hours</div>
                            <div><span>$</span> php artisan migrate</div>
                            <div class="cmd">Migration table created successfully.</div>
                            <div><span>$</span> composer install --no-dev</div>
                            <div>Installing dependencies from lock file</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="terminal-panel">
                <div class="terminal-title">config.ts</div>
                <div class="terminal-body">
                    <div class="terminal-scroll terminal-scroll-run">
                        <div class="terminal-block">
                            <div><span>const</span> config = {</div>
                            <div> env: <span>'production'</span>,</div>
                            <div> api: <span>'https://api.example.com'</span>,</div>
                            <div> timeout: 30000,</div>
                            <div>};</div>
                            <div><span>export</span> <span>default</span> config;</div>
                            <div></div>
                            <div><span>//</span> Strangler fig: legacy → new</div>
                            <div>router.use(<span>'/v1'</span>, legacyMiddleware);</div>
                            <div>router.use(<span>'/v2'</span>, newService);</div>
                            <div></div>
                            <div><span>interface</span> DeployResult {</div>
                            <div> success: boolean;</div>
                            <div> buildId: string;</div>
                            <div>}</div>
                        </div>
                        <div class="terminal-block">
                            <div><span>const</span> config = {</div>
                            <div> env: <span>'production'</span>,</div>
                            <div> api: <span>'https://api.example.com'</span>,</div>
                            <div> timeout: 30000,</div>
                            <div>};</div>
                            <div><span>export</span> <span>default</span> config;</div>
                            <div></div>
                            <div><span>//</span> Strangler fig: legacy → new</div>
                            <div>router.use(<span>'/v1'</span>, legacyMiddleware);</div>
                            <div>router.use(<span>'/v2'</span>, newService);</div>
                            <div></div>
                            <div><span>interface</span> DeployResult {</div>
                            <div> success: boolean;</div>
                            <div> buildId: string;</div>
                            <div>}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="terminal-panel">
                <div class="terminal-title">log — app</div>
                <div class="terminal-body">
                    <div class="terminal-scroll terminal-scroll-run">
                        <div class="terminal-block">
                            <div>[12:34:01] INFO Server listening on :3000</div>
                            <div>[12:34:02] INFO DB connected</div>
                            <div>[12:34:03] INFO Cache warm</div>
                            <div>[12:35:10] GET /api/health 200 2ms</div>
                            <div>[12:35:11] GET /api/config 200 1ms</div>
                            <div>[12:36:00] INFO Scheduled job completed</div>
                            <div>[12:37:22] POST /api/sync 201 45ms</div>
                            <div>[12:38:01] INFO Metrics exported</div>
                            <div>[12:40:00] INFO Health check passed</div>
                        </div>
                        <div class="terminal-block">
                            <div>[12:34:01] INFO Server listening on :3000</div>
                            <div>[12:34:02] INFO DB connected</div>
                            <div>[12:34:03] INFO Cache warm</div>
                            <div>[12:35:10] GET /api/health 200 2ms</div>
                            <div>[12:35:11] GET /api/config 200 1ms</div>
                            <div>[12:36:00] INFO Scheduled job completed</div>
                            <div>[12:37:22] POST /api/sync 201 45ms</div>
                            <div>[12:38:01] INFO Metrics exported</div>
                            <div>[12:40:00] INFO Health check passed</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="terminal-panel">
                <div class="terminal-title">nmap — 80×24</div>
                <div class="terminal-body">
                    <div class="terminal-scroll terminal-scroll-run">
                        <div class="terminal-block">
                            <div><span>$</span> nmap -sV -T4 192.168.1.0/24</div>
                            <div>Starting Nmap 7.94</div>
                            <div>Nmap scan report for 192.168.1.1</div>
                            <div>PORT STATE SERVICE VERSION</div>
                            <div>22/tcp open ssh OpenSSH 8.9</div>
                            <div>80/tcp open http nginx 1.24</div>
                            <div>443/tcp open ssl/http nginx 1.24</div>
                            <div>MAC Address: AA:BB:CC:DD:EE:01</div>
                            <div><span>$</span> nmap -p 22,80,443 10.0.0.1</div>
                            <div>PORT STATE SERVICE</div>
                            <div>22/tcp open ssh</div>
                            <div>80/tcp open http</div>
                            <div>443/tcp open https</div>
                            <div class="cmd">3 ports scanned, 3 open</div>
                        </div>
                        <div class="terminal-block">
                            <div><span>$</span> nmap -sV -T4 192.168.1.0/24</div>
                            <div>Starting Nmap 7.94</div>
                            <div>Nmap scan report for 192.168.1.1</div>
                            <div>PORT STATE SERVICE VERSION</div>
                            <div>22/tcp open ssh OpenSSH 8.9</div>
                            <div>80/tcp open http nginx 1.24</div>
                            <div>443/tcp open ssl/http nginx 1.24</div>
                            <div>MAC Address: AA:BB:CC:DD:EE:01</div>
                            <div><span>$</span> nmap -p 22,80,443 10.0.0.1</div>
                            <div>PORT STATE SERVICE</div>
                            <div>22/tcp open ssh</div>
                            <div>80/tcp open http</div>
                            <div>443/tcp open https</div>
                            <div class="cmd">3 ports scanned, 3 open</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="terminal-panel">
                <div class="terminal-title">aircrack-ng — 80×24</div>
                <div class="terminal-body">
                    <div class="terminal-scroll terminal-scroll-run">
                        <div class="terminal-block">
                            <div><span>$</span> airodump-ng wlan0</div>
                            <div>CH 6 ][ Elapsed: 0 s</div>
                            <div>BSSID PWR CH ESSID</div>
                            <div>AA:11:22:33:44:55 -42 6 lab-network</div>
                            <div>BB:11:22:33:44:66 -58 11 guest-wifi</div>
                            <div></div>
                            <div><span>$</span> aircrack-ng -w wordlist.cap</div>
                            <div>Opening wordlist.cap</div>
                            <div>Read 15234 packets.</div>
                            <div>1 handshake</div>
                            <div>Testing key (hex): 0123456789</div>
                            <div class="cmd">[ 0.00%] passphrase not in list</div>
                            <div>Current passphrase: test-lab-01</div>
                        </div>
                        <div class="terminal-block">
                            <div><span>$</span> airodump-ng wlan0</div>
                            <div>CH 6 ][ Elapsed: 0 s</div>
                            <div>BSSID PWR CH ESSID</div>
                            <div>AA:11:22:33:44:55 -42 6 lab-network</div>
                            <div>BB:11:22:33:44:66 -58 11 guest-wifi</div>
                            <div></div>
                            <div><span>$</span> aircrack-ng -w wordlist.cap</div>
                            <div>Opening wordlist.cap</div>
                            <div>Read 15234 packets.</div>
                            <div>1 handshake</div>
                            <div>Testing key (hex): 0123456789</div>
                            <div class="cmd">[ 0.00%] passphrase not in list</div>
                            <div>Current passphrase: test-lab-01</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="hero-content">
            <div class="hero-avatar-wrap">
                <div class="hero-avatar"><img src="{{ asset('vendor/splotnikov/profile.png') }}" data-profile-img alt="Stanislav Plotnikov"></div>
            </div>
            <h1><span>Stanislav Plotnikov</span></h1>
            <p>Legacy Modernization & Solution Architect</p>
            <blockquote>"I transform legacy technical debt into scalable, high-performance infrastructure by
                prioritizing ROI over industry trends."</blockquote>
            <div class="hero-buttons">
                <a href="cv.html" class="btn btn-primary">View / Download CV</a>
                <a href="https://www.linkedin.com/in/stanislavplotnikov/" class="btn btn-secondary" target="_blank"
                    rel="noopener noreferrer">Connect on LinkedIn</a>
            </div>
        </div>
    </section>

    <!-- 01 / The Manifesto -->
    <section id="manifesto" class="section-alt">
        <div class="container">
            <div class="section-header">
                <p class="section-label">01 / The Manifesto</p>
                <h2>Engineering for ROI, Not Trends</h2>
            </div>
            <div class="manifesto-content">
                <p style="color: var(--text-secondary); margin-bottom: 1rem;">The most expensive line of code is the one
                    that shouldn't have been written. I don't follow "Cargo Cult" architecture. While others
                    over-engineer with microservices they can't manage, I advocate for <strong>Modular
                        Monoliths</strong> and <strong>Pragmatic Scaling</strong>.</p>
                <ul>
                    <li><strong>Pragmatism Over Hype:</strong> I choose stacks based on talent pipelines and TCO, not
                        GitHub stars.</li>
                    <li><strong>The Strangler Fig Strategy:</strong> No "grand rewrites." I believe in the seamless,
                        incremental replacement of legacy debt while maintaining 100% business continuity.</li>
                    <li><strong>Systems Thinking:</strong> A ticket developer closes tasks; an architect anticipates the
                        24-month trade-off of every structural decision.</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- 02 / Selected Proof -->
    <section id="proof" class="section-alt">
        <div class="container">
            <div class="section-header">
                <p class="section-label">02 / Selected Proof</p>
                <h2>Case Studies</h2>
            </div>
            <div class="projects-grid">
                <div class="project-card">
                    <div class="project-image project-image--terminal">
                        <div class="project-terminal-title">php artisan — Laravel</div>
                        <div class="project-terminal-body">
                            <div class="project-terminal-scroll project-terminal-scroll-run">
                                <div class="terminal-block">
                                    <div><span>$</span> php artisan migrate</div>
                                    <div class="cmd">Migration table created successfully.</div>
                                    <div><span>$</span> php artisan make:model Order -m</div>
                                    <div class="cmd">Model created successfully.</div>
                                    <div><span>$</span> php artisan route:list</div>
                                    <div>GET|HEAD api/users ......... UserController@index</div>
                                    <div><span>$</span> php artisan config:clear</div>
                                    <div class="cmd">Configuration cache cleared.</div>
                                    <div><span>$</span> php artisan cache:clear</div>
                                    <div class="cmd">Application cache cleared.</div>
                                    <div><span>$</span> php artisan migrate:status</div>
                                    <div>Ran? Migration name</div>
                                    <div>Yes create_users_table</div>
                                    <div><span>$</span> php artisan db:seed</div>
                                    <div class="cmd">Database seeding completed.</div>
                                    <div><span>$</span> php artisan serve</div>
                                    <div>Server running on [http://127.0.0.1:8000]</div>
                                </div>
                                <div class="terminal-block">
                                    <div><span>$</span> php artisan migrate</div>
                                    <div class="cmd">Migration table created successfully.</div>
                                    <div><span>$</span> php artisan make:model Order -m</div>
                                    <div class="cmd">Model created successfully.</div>
                                    <div><span>$</span> php artisan route:list</div>
                                    <div>GET|HEAD api/users ......... UserController@index</div>
                                    <div><span>$</span> php artisan config:clear</div>
                                    <div class="cmd">Configuration cache cleared.</div>
                                    <div><span>$</span> php artisan cache:clear</div>
                                    <div class="cmd">Application cache cleared.</div>
                                    <div><span>$</span> php artisan migrate:status</div>
                                    <div>Ran? Migration name</div>
                                    <div>Yes create_users_table</div>
                                    <div><span>$</span> php artisan db:seed</div>
                                    <div class="cmd">Database seeding completed.</div>
                                    <div><span>$</span> php artisan serve</div>
                                    <div>Server running on [http://127.0.0.1:8000]</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="project-content">
                        <h3>Modernizing a Monolith: Phalcon to Laravel</h3>
                        <p><strong>The Mess:</strong> A client was trapped in a legacy Phalcon system, unable to scale
                            or find specialized talent.</p>
                        <p><strong>The Solution:</strong> Engineered a custom PHP middleware layer to bridge the two
                            frameworks. Implemented an incremental migration strategy (Strangler Fig Pattern).</p>
                        <p><strong>The Impact:</strong> Achieved a <strong>60% reduction in latency</strong> and reduced
                            unnecessary overheads for more streamlined processes with <strong>zero downtime</strong>.
                        </p>
                        <div class="project-tags">
                            <span class="tag">PHP</span>
                            <span class="tag">Laravel</span>
                            <span class="tag">Phalcon</span>
                            <span class="tag">Strangler Fig</span>
                        </div>
                    </div>
                </div>
                <div class="project-card">
                    <div class="project-image project-image--ai-prompt">
                        <div class="project-ai-title">Chat — structured output</div>
                        <div class="project-ai-body">
                            <div class="project-ai-scroll project-ai-scroll-run">
                                <div class="terminal-block">
                                    <div class="prompt-label">User</div>
                                    <div class="prompt-line">Convert the meeting notes into JSON: title, action_items[],
                                        owners.</div>
                                    <div class="prompt-label">User</div>
                                    <div class="prompt-line">You are a senior engineer. Refactor this for readability,
                                        then explain in 3 bullets.</div>
                                    <div class="prompt-label">User</div>
                                    <div class="prompt-line">Extract API endpoints and request/response shapes from this
                                        OpenAPI doc.</div>
                                    <div class="prompt-label">User</div>
                                    <div class="prompt-line">Rewrite for a non-technical stakeholder in 2 sentences.
                                    </div>
                                    <div class="prompt-label">User</div>
                                    <div class="prompt-line">Given this schema, generate a TypeScript interface and a
                                        Zod validator.</div>
                                    <div class="prompt-label">User</div>
                                    <div class="prompt-line">Chain of thought, then give the final recommendation only.
                                    </div>
                                </div>
                                <div class="terminal-block">
                                    <div class="prompt-label">User</div>
                                    <div class="prompt-line">Convert the meeting notes into JSON: title, action_items[],
                                        owners.</div>
                                    <div class="prompt-label">User</div>
                                    <div class="prompt-line">You are a senior engineer. Refactor this for readability,
                                        then explain in 3 bullets.</div>
                                    <div class="prompt-label">User</div>
                                    <div class="prompt-line">Extract API endpoints and request/response shapes from this
                                        OpenAPI doc.</div>
                                    <div class="prompt-label">User</div>
                                    <div class="prompt-line">Rewrite for a non-technical stakeholder in 2 sentences.
                                    </div>
                                    <div class="prompt-label">User</div>
                                    <div class="prompt-line">Given this schema, generate a TypeScript interface and a
                                        Zod validator.</div>
                                    <div class="prompt-label">User</div>
                                    <div class="prompt-line">Chain of thought, then give the final recommendation only.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="project-content">
                        <h3>AI-Driven Workflow Integration</h3>
                        <p><strong>The Mess:</strong> Manual data processing bottlenecks were stalling business
                            operations.</p>
                        <p><strong>The Solution:</strong> Integrating LLM agents into the sales stack replaced rigid,
                            manual processes with autonomous AI that researches prospects.</p>
                        <p><strong>The Impact:</strong> Reclaimed <strong>16 hours/week</strong> of manual labor through
                            automated agentic workflows.</p>
                        <div class="project-tags">
                            <span class="tag">LLM</span>
                            <span class="tag">AI Agents</span>
                            <span class="tag">Automation</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 03 / The Toolkit -->
    <section id="toolkit">
        <div class="container">
            <div class="section-header">
                <p class="section-label">03 / The Toolkit</p>
                <h2>Technologies & Practices</h2>
            </div>
            <div class="skills-grid">
                <div class="skill-card">
                    <div class="skill-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                            <path d="M12 2L2 7l10 5 10-5-10-5z"></path>
                            <path d="M2 17l10 5 10-5"></path>
                            <path d="M2 12l10 5 10-5"></path>
                        </svg>
                    </div>
                    <h3>Architecture & Orchestration</h3>
                    <p><strong>Core:</strong> Kubernetes, Docker, Ansible, Linux Systems Architecture (Debian/RHEL
                        hardening). <strong>Strategy:</strong> Infrastructure as Code (IaC), Scalability Audits,
                        Disaster Recovery.</p>
                </div>
                <div class="skill-card">
                    <div class="skill-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                            <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                            <line x1="8" y1="21" x2="16" y2="21"></line>
                            <line x1="12" y1="17" x2="12" y2="21"></line>
                        </svg>
                    </div>
                    <h3>Backend Engineering</h3>
                    <p><strong>Primary:</strong> PHP (Laravel/Phalcon expert), Node.js (Event-driven services).
                        <strong>Supporting:</strong> Python (Data Processing & AI), Redis, MySQL/PostgreSQL.
                    </p>
                </div>
                <div class="skill-card">
                    <div class="skill-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                            <polyline points="16 18 22 12 16 6"></polyline>
                            <polyline points="8 6 2 12 8 18"></polyline>
                        </svg>
                    </div>
                    <h3>DevOps & Automation</h3>
                    <p><strong>Pipelines:</strong> GitHub Actions, CI/CD Automation. <strong>AI Implementation:</strong>
                        LLM Orchestration, Prompt Engineering for System Automation.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 04 / Contact -->
    <section id="contact">
        <div class="container">
            <div class="section-header">
                <p class="section-label">04 / Contact</p>
                <h2>Ready to cut the noise?</h2>
                <p>I'm available for architectural consultations and high-impact modernization projects. If you want a
                    partner who takes ownership of your system's health rather than just "moving tickets," let's talk.
                </p>
            </div>
            <div class="contact-content">
                <div class="contact-links">
                    <a href="https://github.com/spdotdev" class="contact-link" aria-label="GitHub">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                            <path
                                d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22">
                            </path>
                        </svg>
                    </a>
                    <a href="https://www.linkedin.com/in/stanislavplotnikov/" class="contact-link"
                        aria-label="LinkedIn">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                            <path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z">
                            </path>
                            <rect x="2" y="9" width="4" height="12"></rect>
                            <circle cx="4" cy="4" r="2"></circle>
                        </svg>
                    </a>
                </div>
                <div class="contact-email">
                    <a href="mailto:stplotnikov@gmail.com">stplotnikov@gmail.com</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-top">
            <div class="footer-grid">
                <div class="footer-col">
                    <h4>Navigate</h4>
                    <ul>
                        <li><a href="index.html">Home</a></li>
                        <li><a href="index.html#manifesto">Manifesto</a></li>
                        <li><a href="index.html#proof">Proof</a></li>
                        <li><a href="index.html#toolkit">Toolkit</a></li>
                        <li><a href="index.html#contact">Contact</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Connect</h4>
                    <ul>
                        <li><a href="cv.html">CV / Resume</a></li>
                        <li><a href="https://linkedin.com/in/splotnikov" target="_blank"
                                rel="noopener noreferrer">LinkedIn</a></li>
                        <li><a href="https://github.com/spdotdev" target="_blank" rel="noopener noreferrer">GitHub</a>
                        </li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Contact</h4>
                    <ul>
                        <li><a href="mailto:stplotnikov@gmail.com">Email</a></li>
                        <li><a href="https://splotnikov.dev">splotnikov.dev</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-typing-wrap">
                <div class="footer-typing" id="footer-typing" aria-hidden="true"></div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2026 Stanislav Plotnikov. Built for performance.</p>
        </div>
    </footer>

    <script>
        // Profile image: resolve path for both custom domain (/) and project path (/splotnikov/)
        (function () {
            var base = location.pathname.indexOf('/splotnikov') === 0 ? '/splotnikov' : '';
            document.querySelectorAll('img[data-profile-img]').forEach(function (img) { img.src = base + '/{{ asset('vendor/splotnikov/profile.png') }}'; });
        })();
        // Theme Toggle
        const themeToggle = document.querySelector('.theme-toggle');
        const html = document.documentElement;

        // Check for saved theme preference or default to light
        const savedTheme = localStorage.getItem('theme') || 'light';
        html.setAttribute('data-theme', savedTheme);

        themeToggle.addEventListener('click', () => {
            const currentTheme = html.getAttribute('data-theme');
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
        });

        // Footer: typing animation (Linux commands + output scrolling up, cursor moves with text)
        (function () {
            const el = document.getElementById('footer-typing');
            if (!el) return;
            const commands = [
                { cmd: 'sudo apt update', output: ['Hit:1 http://archive.ubuntu.com focal InRelease', 'Reading package lists... Done', 'Building dependency tree... Done'] },
                { cmd: 'git pull origin main', output: ['From github.com:user/repo', 'Already up to date.'] },
                { cmd: 'docker ps -a', output: ['CONTAINER ID   IMAGE      STATUS', 'a1b2c3d4e5f6   app:latest   Up 2 hours'] },
                { cmd: 'php artisan migrate', output: ['Migration table created successfully.', 'Migrating: 2024_01_15_000000_create_users_table', 'Migrated:  2024_01_15_000000_create_users_table'] },
                { cmd: 'npm run build', output: ['> vite v5.0.0', 'building for production...', '✓ 124 modules transformed.'] },
                { cmd: 'ls -la', output: ['total 42', 'drwxr-xr-x  8 user www 4096 Jan 15 10:00 .', '-rw-r--r--  1 user www  120 config.php'] },
                { cmd: 'cd /var/www && pwd', output: ['/var/www'] },
                { cmd: 'systemctl status nginx', output: ['● nginx.service - A high performance web server', '   Active: active (running)'] },
                { cmd: 'tail -f /var/log/app.log', output: ['[INFO] Request GET /api/health 200', '[INFO] Cache hit for config'] },
                { cmd: 'composer install --no-dev', output: ['Installing dependencies from lock file', 'Nothing to install, update or remove'] },
            ];
            const lineDiv = document.createElement('div');
            lineDiv.className = 'footer-typing-line';
            const promptSpan = document.createElement('span');
            promptSpan.textContent = '$ ';
            promptSpan.style.color = 'var(--accent)';
            const textSpan = document.createElement('span');
            const cursorSpan = document.createElement('span');
            cursorSpan.className = 'cursor';
            cursorSpan.setAttribute('aria-hidden', 'true');
            const outputDiv = document.createElement('div');
            outputDiv.className = 'footer-typing-output';
            lineDiv.appendChild(promptSpan);
            lineDiv.appendChild(textSpan);
            lineDiv.appendChild(cursorSpan);
            el.appendChild(lineDiv);
            el.appendChild(outputDiv);
            let cmdIndex = 0;
            let charIndex = 0;
            let state = 'typing';
            let outputLineIndex = 0;
            let outputTimeout = 0;
            const typeInterval = 85;
            const outputLineDelay = 180;
            const pauseAfterOutput = 2200;
            const pauseBeforeNext = 500;
            function scrollOutputToBottom() {
                outputDiv.scrollTop = outputDiv.scrollHeight;
            }
            function showNextOutputLine() {
                const item = commands[cmdIndex];
                if (outputLineIndex < item.output.length) {
                    if (outputLineIndex > 0) outputDiv.textContent += '\n';
                    outputDiv.textContent += item.output[outputLineIndex];
                    outputLineIndex++;
                    scrollOutputToBottom();
                    outputTimeout = setTimeout(showNextOutputLine, outputLineDelay);
                } else {
                    outputTimeout = setTimeout(nextCommand, pauseAfterOutput);
                }
            }
            function nextCommand() {
                if (outputTimeout) clearTimeout(outputTimeout);
                outputTimeout = 0;
                charIndex = 0;
                outputLineIndex = 0;
                cmdIndex = (cmdIndex + 1) % commands.length;
                textSpan.textContent = '';
                outputDiv.textContent = '';
                outputDiv.scrollTop = 0;
                state = 'typing';
                setTimeout(tick, pauseBeforeNext);
            }
            function tick() {
                if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
                if (state === 'output') return;
                const item = commands[cmdIndex];
                const cmd = item.cmd;
                if (charIndex < cmd.length) {
                    textSpan.textContent += cmd[charIndex];
                    charIndex++;
                    setTimeout(tick, typeInterval);
                } else {
                    state = 'output';
                    outputLineIndex = 0;
                    showNextOutputLine();
                }
            }
            setTimeout(tick, 300);
        })();

        // Hero background: particles
        (function () {
            const canvas = document.getElementById('hero-bg');
            if (!canvas) return;
            const heroEl = document.querySelector('.hero');
            const ctx = canvas.getContext('2d');
            let width = 0, height = 0;
            let t = 0;
            let raf = 0;

            function getHeroColor(name) {
                return getComputedStyle(document.documentElement).getPropertyValue('--' + name).trim() || '#0f766e';
            }

            function parseAccent(accent) {
                if (accent.startsWith('#')) {
                    const hex = accent.slice(1);
                    return { r: parseInt(hex.slice(0, 2), 16), g: parseInt(hex.slice(2, 4), 16), b: parseInt(hex.slice(4, 6), 16) };
                }
                const m = accent.match(/rgba?\((\d+),\s*(\d+),\s*(\d+)/);
                return m ? { r: +m[1], g: +m[2], b: +m[3] } : { r: 15, g: 118, b: 110 };
            }

            function resize() {
                if (!heroEl) return;
                const rect = heroEl.getBoundingClientRect();
                const dpr = Math.min(window.devicePixelRatio || 1, 2);
                width = rect.width;
                height = rect.height;
                canvas.width = width * dpr;
                canvas.height = height * dpr;
                canvas.style.width = width + 'px';
                canvas.style.height = height + 'px';
                ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
            }

            let particles = [];
            function initParticles() {
                particles = [];
                const n = Math.min(80, Math.floor((width * height) / 4000));
                for (let i = 0; i < n; i++) {
                    particles.push({
                        x: Math.random() * width,
                        y: Math.random() * height,
                        vx: (Math.random() - 0.5) * 0.3,
                        vy: (Math.random() - 0.5) * 0.2,
                        r: 0.5 + Math.random() * 1.2,
                        phase: Math.random() * Math.PI * 2,
                    });
                }
            }

            function drawParticles(rgb) {
                if (particles.length === 0) initParticles();
                particles.forEach(function (p) {
                    p.x += p.vx;
                    p.y += p.vy;
                    if (p.x < 0 || p.x > width) p.vx *= -1;
                    if (p.y < 0 || p.y > height) p.vy *= -1;
                    const alpha = 0.15 + 0.15 * Math.sin(t * 2 + p.phase);
                    ctx.fillStyle = 'rgba(' + rgb.r + ',' + rgb.g + ',' + rgb.b + ',' + alpha + ')';
                    ctx.beginPath();
                    ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
                    ctx.fill();
                });
            }

            function drawLoop() {
                if (!width || !height) {
                    raf = requestAnimationFrame(drawLoop);
                    return;
                }
                const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
                const rgb = parseAccent(getHeroColor('accent'));
                ctx.clearRect(0, 0, width, height);
                if (!reducedMotion) drawParticles(rgb);
                t += 0.015;
                raf = requestAnimationFrame(drawLoop);
            }

            resize();
            window.addEventListener('resize', function () {
                resize();
                particles = [];
            });
            if (heroEl) {
                const heroObs = new IntersectionObserver(function (entries) {
                    if (entries[0].isIntersecting) {
                        t = 0;
                        raf = requestAnimationFrame(drawLoop);
                    } else {
                        cancelAnimationFrame(raf);
                    }
                }, { threshold: 0 });
                heroObs.observe(heroEl);
            }
            raf = requestAnimationFrame(drawLoop);
        })();

        // Mobile Menu Toggle
        const mobileMenu = document.querySelector('.mobile-menu');
        const navLinks = document.querySelector('.nav-links');

        mobileMenu.addEventListener('click', () => {
            navLinks.classList.toggle('active');
        });

        // Close mobile menu when clicking a link
        document.querySelectorAll('.nav-links a').forEach(link => {
            link.addEventListener('click', () => {
                navLinks.classList.remove('active');
            });
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Nav active state from scroll (only on index)
        const sectionIds = ['manifesto', 'proof', 'toolkit', 'contact'];
        const navHome = document.querySelector('.nav-link-home');
        const sectionLinks = sectionIds.map(id => document.querySelector(`.nav-links a[href="#${id}"]`)).filter(Boolean);

        function setActiveNav(activeLink) {
            document.querySelectorAll('.nav-links a.nav-active').forEach(a => a.classList.remove('nav-active'));
            if (activeLink) activeLink.classList.add('nav-active');
        }

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (!entry.isIntersecting) return;
                const id = entry.target.id;
                const link = document.querySelector(`.nav-links a[href="#${id}"]`);
                if (link) setActiveNav(link);
            });
        }, { rootMargin: '-20% 0px -60% 0px', threshold: 0 });

        sectionIds.forEach(id => {
            const el = document.getElementById(id);
            if (el) observer.observe(el);
        });

        // When top of page, Home is active
        const hero = document.querySelector('.hero');
        if (hero && navHome) {
            const heroObserver = new IntersectionObserver((entries) => {
                if (entries[0].isIntersecting) setActiveNav(navHome);
            }, { threshold: 0.5 });
            heroObserver.observe(hero);
        } else if (navHome) {
            navHome.classList.add('nav-active');
        }
    </script>
</body>

</html>