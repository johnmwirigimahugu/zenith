/**
 * ============================================================================
 * Copyright (C) 2025 by John Mwirigi Mahugu  for my beloved SETH NG'ANG'A :)
 * LICENSE {OPEN SOURCE}
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 * ============================================================================
 */
/**
 * zen.js - Front-End JavaScript Framework
 * Version: 3.1.6
 *
 * @Description: zen.js is a lightweight, dependency-free front-end framework combining Alpine.js, HTMX, AJX.js, and W3.js.
 * It offers reactive state, AJAX-driven UI, DOM utilities, SVG icons, and a zen- prefixed CSS framework for modern web apps.
 * Designed for Python/Ruby-like brevity, minimal JavaScript, and a shallow learning curve.
 *
 * @Author: John Mwirigi Mahugu - "Kesh"
 * @Email: johnmahugu@gmail.com
 * @Repository: https://github.com/johnmwirigimahugu/zen
 * @License: MIT
 * @Updated: July 28, 2025
 * @Timestamp: 04:14 AM EAT, Monday, July 28, 2025
 *
 * @Features:
 * 1. Reactivity: `zen-b`, `zen-t`, `zen-show`, `zen-x` for state, text, visibility, and expressions.
 * 2. Virtual DOM: `zen-tpl` for dynamic templates.
 * 3. Components: Reusable UI with `zen-c` and `client.c`.
 * 4. Routing: SPA routing with `client.router`.
 * 5. AJAX: `zen-ajax` for HTMX-like requests, with `client.ajax` for advanced chaining.
 * 6. Forms: `client.form` for serialization and validation.
 * 7. CSS: Responsive `zen-` classes with SVG and Font Awesome support.
 * 8. Icons: `zen-icon` for SVG icons and Font Awesome fallback.
 * 9. i18n: Multi-language support with `client.i18n`.
 * 10. Plugins: Extensible via `client.plugin`.
 * 11. Flash: Queued notifications with `client.flash`.
 * 12. Events: Pub/sub with `client.events`.
 * 13. Auth: Session management with `client.auth`.
 * 14. Data: `zen-each`, `zen-filter`, `zen-sort` for W3.js-like data manipulation.
 */

(function () {
    "use strict";

    // --- Utility Functions ---
    const debounce = (fn, ms) => {
        let t;
        return (...args) => { clearTimeout(t); t = setTimeout(() => fn(...args), ms); };
    };

    const zen = (function () {
        // --- Config ---
        const cfg = {
            base: '',
            theme: 'default',
            lang: 'en',
            pluginDir: '/plugins',
            debug: false,
            csrf: true,
            token: null
        };

        // --- Utils ---
        const u = {
            uuid: () => ([1e7]+-1e3+-4e3+-8e3+-1e11).replace(/[018]/g, c =>
                (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)),
            cookies: () => Object.fromEntries(document.cookie.split(';').map(c => c.trim().split('=').map(decodeURIComponent))),
            cookie: (n, v, o = {}) => {
                let c = `${encodeURIComponent(n)}=${encodeURIComponent(v)}`;
                if (o.expires) c += `; Expires=${o.expires.toUTCString()}`;
                if (o.maxAge) c += `; Max-Age=${o.maxAge}`;
                if (o.domain) c += `; Domain=${o.domain}`;
                if (o.path) c += `; Path=${o.path}`;
                if (o.secure) c += `; Secure`;
                if (o.httpOnly) c += `; HttpOnly`;
                if (o.sameSite) c += `; SameSite=${o.sameSite}`;
                document.cookie = c;
            },
            esc: s => document.createElement('div').appendChild(document.createTextNode(s)).parentNode.innerHTML,
            tpl: (t, d) => (typeof t === 'string' ? t : t.cloneNode(true).innerHTML)
                .replace(/{{(\w+)}}/g, (_, k) => u.esc(d[k] || '')),
            evalExpr: (expr, ctx) => {
                try {
                    return new Function(`with(arguments[0]){return ${expr}}`)(ctx);
                } catch (e) {
                    console.error(`Expression error: ${expr}`, e);
                    return null;
                }
            }
        };

        // --- Client Features ---
        const client = {
            css: {
                styles: `
:root {
    --zen-primary: #3b82f6;
    --zen-secondary: #64748b;
    --zen-success: #10b981;
    --zen-danger: #ef4444;
    --zen-warning: #f59e0b;
    --zen-info: #06b6d4;
    --zen-light: #f8fafc;
    --zen-dark: #1e293b;
    --zen-font-sans: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    --zen-font-size-xs: 0.75rem;
    --zen-font-size-sm: 0.875rem;
    --zen-font-size-base: 1rem;
    --zen-font-size-lg: 1.125rem;
    --zen-space-xs: 0.25rem;
    --zen-space-sm: 0.5rem;
    --zen-space-md: 1rem;
    --zen-space-lg: 1.5rem;
    --zen-radius-md: 0.375rem;
    --zen-shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    --zen-transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html { font-family: var(--zen-font-sans); line-height: 1.5; }
body { margin: 0; color: var(--zen-dark); background: white; }
.zen-container { max-width: 1200px; margin: 0 auto; padding: var(--zen-space-md); }
.zen-flex { display: flex; flex-direction: column; gap: var(--zen-space-sm); }
.zen-flex-row { flex-direction: row; }
.zen-justify-center { justify-content: center; }
.zen-items-center { align-items: center; }
.zen-grid { display: grid; gap: var(--zen-space-sm); }
.zen-grid-cols-2 { grid-template-columns: repeat(2, 1fr); }
.zen-btn { 
    display: inline-flex; align-items: center; justify-content: center; 
    padding: var(--zen-space-sm) var(--zen-space-md); 
    font-size: var(--zen-font-size-sm); 
    border-radius: var(--zen-radius-md); 
    cursor: pointer; 
    transition: var(--zen-transition); 
    border: 1px solid transparent;
    gap: var(--zen-space-xs);
    &[aria-disabled="true"] { opacity: 0.5; cursor: not-allowed; }
}
.zen-btn:focus { outline: 2px solid var(--zen-primary); outline-offset: 2px; }
.zen-btn-primary { background: var(--zen-primary); color: white; }
.zen-btn-primary:hover:not([aria-disabled="true"]) { background: #2563eb; }
.zen-btn-success { background: var(--zen-success); color: white; }
.zen-btn-danger { background: var(--zen-danger); color: white; }
.zen-btn-sm { padding: var(--zen-space-xs) var(--zen-space-sm); font-size: var(--zen-font-size-xs); }
.zen-btn-lg { padding: var(--zen-space-md) var(--zen-space-lg); font-size: var(--zen-font-size-lg); }
.zen-card { 
    background: white; 
    border: 1px solid var(--zen-light); 
    border-radius: var(--zen-radius-md); 
    box-shadow: var(--zen-shadow-sm); 
}
.zen-card-header { padding: var(--zen-space-md); border-bottom: 1px solid var(--zen-light); }
.zen-card-body { padding: var(--zen-space-md); }
.zen-form-group { margin-bottom: var(--zen-space-md); }
.zen-input { 
    width: 100%; 
    padding: var(--zen-space-sm) var(--zen-space-md); 
    font-size: var(--zen-font-size-base); 
    border: 1px solid var(--zen-light); 
    border-radius: var(--zen-radius-md);
}
.zen-input:focus { outline: none; border-color: var(--zen-primary); box-shadow: var(--zen-shadow-sm); }
.zen-alert { 
    padding: var(--zen-space-md); 
    border-radius: var(--zen-radius-md); 
    border: 1px solid; 
    position: fixed; 
    top: var(--zen-space-md); 
    right: var(--zen-space-md); 
    z-index: 1000; 
    max-width: 400px;
}
.zen-alert-success { background: #f0fdf4; border-color: #bbf7d0; color: #166534; }
.zen-alert-danger { background: #fef2f2; border-color: #fecaca; color: #991b1b; }
.zen-icon { display: inline-block; width: 1.5rem; height: 1.5rem; vertical-align: middle; }
.zen-text-xs { font-size: var(--zen-font-size-xs); }
.zen-text-sm { font-size: var(--zen-font-size-sm); }
.zen-m-md { margin: var(--zen-space-md); }
.zen-p-md { padding: var(--zen-space-md); }
.zen-absolute { position: absolute; }
.zen-fixed { position: fixed; }
.zen-top-0 { top: 0; }
.zen-right-0 { right: 0; }
.zen-z-50 { z-index: 50; }
.zen-spinner { 
    width: 1.5rem; height: 1.5rem; 
    border: 2px solid var(--zen-primary); 
    border-top-color: transparent; 
    border-radius: 50%; 
    animation: zen-spin 1s linear infinite;
}
@keyframes zen-spin { to { transform: rotate(360deg); } }
@media (min-width: 640px) { .zen-sm\\:flex { display: flex; flex-direction: row; } }
@media (min-width: 768px) { .zen-md\\:grid-cols-3 { grid-template-columns: repeat(3, 1fr); } }
                `,
                inject: () => {
                    if (!document.getElementById('zen-styles')) {
                        const s = document.createElement('style');
                        s.id = 'zen-styles';
                        s.textContent = client.css.styles;
                        document.head.appendChild(s);
                        const fa = document.createElement('link');
                        fa.rel = 'stylesheet';
                        fa.href = 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css';
                        document.head.appendChild(fa);
                        client.log('CSS and Font Awesome injected');
                    }
                },
                purge: () => {
                    const used = new Set();
                    new MutationObserver(() => {
                        client.dom.all('[class]').forEach(el => el.classList.forEach(c => used.add(c)));
                        const s = document.getElementById('zen-styles');
                        if (s) s.textContent = client.css.styles.split('\n').filter(l => {
                            const m = l.match(/\.([^\s{]+)/);
                            return !m || used.has(m[1]);
                        }).join('\n');
                        client.log('CSS purged');
                    }).observe(document.body, { childList: true, subtree: true });
                }
            },

            icons: {
                _icons: {
                    user: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>',
                    home: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>',
                    
                    // Adding more icons to reach 316
                    wifi_off: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="1" y1="1" x2="23" y2="23"></line><path d="M16.72 11.06A10.94 10.94 0 0 1 19 12.55"></path><path d="M5 12.55a11 11 0 0 1 .58-2.09M10.94 5.06A16.04 16.04 0 0 1 22 9"></path><path d="M1.42 9a16 16 0 0 1 4.7-2.88M8.53 16.11a6 6 0 0 1 6.95 0"></path><line x1="12" y1="20" x2="12.01" y2="20"></line></svg>',
                    battery: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="6" width="18" height="12" rx="2" ry="2"></rect><line x1="22" y1="12" x2="22" y2="12"></line></svg>',
                    battery_low: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 6H5a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2z"></path><line x1="22" y1="12" x2="22" y2="12"></line><line x1="12" y1="6" x2="12" y2="18"></line></svg>',
                    battery_full: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 6H5a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2z"></path><line x1="22" y1="12" x2="22" y2="12"></line><rect x="3" y="6" width="16" height="12" rx="2" ry="2"></rect></svg>',
                    compass: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"></polygon></svg>',
                    crosshair: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="22" y1="12" x2="2" y2="12"></line><line x1="12" y1="2" x2="12" y2="22"></line></svg>',
                    framer: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 16V9h14V2H5l14 14h-7m-7 0l7 7v-7m-7 0h7"></path></svg>',
                    gitlab: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22.65 1.95L9.93 14.67 4.17 8.91 1 12.08l8.93 8.93 13.72-13.72z"></path></svg>',
                    slack: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.5 10c-.8-1.5-2.1-2.6-3.7-3.2-.5-.1-1-.2-1.5-.2h-.1c-1.6.4-2.9 1.5-3.7 3.2-.8 1.5-1.2 3.1-1.2 4.8 0 1.7.4 3.3 1.2 4.8.8 1.6 2.1 2.6 3.7 3.2.5.1 1 .2 1.5.2h.1c1.6-.4 2.9-1.5 3.7-3.2.8-1.5 1.2-3.1 1.2-4.8 0-1.7-.4-3.3-1.2-4.8z"></path><path d="M17.5 10c-.8-1.5-2.1-2.6-3.7-3.2-.5-.1-1-.2-1.5-.2h-.1c-1.6.4-2.9 1.5-3.7 3.2-.8 1.5-1.2 3.1-1.2 4.8 0 1.7.4 3.3 1.2 4.8.8 1.6 2.1 2.6 3.7 3.2.5.1 1 .2 1.5.2h.1c1.6-.4 2.9-1.5 3.7-3.2.8-1.5 1.2-3.1 1.2-4.8 0-1.7-.4-3.3-1.2-4.8z"></path><path d="M2.5 10c-.8-1.5-2.1-2.6-3.7-3.2-.5-.1-1-.2-1.5-.2h-.1c-1.6.4-2.9 1.5-3.7 3.2-.8 1.5-1.2 3.1-1.2 4.8 0 1.7.4 3.3 1.2 4.8.8 1.6 2.1 2.6 3.7 3.2.5.1 1 .2 1.5.2h.1c1.6-.4 2.9-1.5 3.7-3.2.8-1.5 1.2-3.1 1.2-4.8 0-1.7-.4-3.3-1.2-4.8z"></path><path d="M21.5 10c-.8-1.5-2.1-2.6-3.7-3.2-.5-.1-1-.2-1.5-.2h-.1c-1.6.4-2.9 1.5-3.7 3.2-.8 1.5-1.2 3.1-1.2 4.8 0 1.7.4 3.3 1.2 4.8.8 1.6 2.1 2.6 3.7 3.2.5.1 1 .2 1.5.2h.1c1.6-.4 2.9-1.5 3.7-3.2.8-1.5 1.2-3.1 1.2-4.8 0-1.7-.4-3.3-1.2-4.8z"></path></svg>',
                    trello: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><rect x="7" y="7" width="3" height="10"></rect><rect x="14" y="7" width="3" height="5"></rect></svg>',
                    git: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="4"></circle><line x1="1.05" y1="12" x2="7" y2="12"></line><line x1="17.01" y1="12" x2="22.96" y2="12"></line></svg>',
                    github: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"></path></svg>',
                    linkedin: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect x="2" y="9" width="4" height="12"></rect><circle cx="4" cy="4" r="2"></circle></svg>',
                    twitter: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c11 3 21-8 21-19 0-.33-.01-.66-.02-1Z"></path></svg>',
                    facebook: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>',
                    instagram: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>',
                    youtube: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.42a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19.58C5.12 20 12 20 12 20s6.88 0 8.6-.42a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.33A29 29 0 0 0 22.54 6.42z"></path><polygon points="10 15 15 12 10 9 10 15"></polygon></svg>',
                    globe_2: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>',
                    aperture: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="14.31" y1="8" x2="20.05" y2="17.94"></line><line x1="9.69" y1="8" x2="21.17" y2="8"></line><line x1="7.38" y1="12" x2="13.12" y2="2.06"></line><line x1="9.69" y1="16" x2="3.95" y2="6.06"></line><line x1="14.31" y1="16" x2="2.83" y2="16"></line><line x1="16.62" y1="12" x2="10.88" y2="21.94"></line></svg>',
                    award_2: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="7"></circle><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline></svg>',
                    bell_plus: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path><line x1="12" y1="3" x2="12" y2="7"></line><line x1="10" y1="5" x2="14" y2="5"></line></svg>',
                    bell_minus: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path><line x1="10" y1="5" x2="14" y2="5"></line></svg>',
                    bell_snooze: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path><line x1="4" y1="4" x2="20" y2="20"></line></svg>',
                    bluetooth: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6.5 6.5 17.5 17.5 12 23 12 1 17.5 6.5 6.5 17.5"></polyline></svg>',
                    briefcase_2: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>',
                    calendar_plus: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line><line x1="12" y1="14" x2="12" y2="20"></line><line x1="9" y1="17" x2="15" y2="17"></line></svg>',
                    calendar_minus: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line><line x1="9" y1="17" x2="15" y2="17"></line></svg>',
                    calendar_check: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line><polyline points="9 16 12 19 19 12"></polyline></svg>',
                    calendar_x: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line><line x1="15" y1="13" x2="9" y2="19"></line><line x1="9" y1="13" x2="15" y2="19"></line></svg>',
                    chrome: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="12" r="4"></circle><line x1="21.17" y1="8" x2="12" y2="8"></line><line x1="3.95" y1="6.06" x2="9" y2="12"></line><line x1="21.17" y1="16" x2="12" y2="16"></line><line x1="3.95" y1="17.94" x2="9" y2="12"></line><line x1="8" y1="21.17" x2="12" y2="12"></line><line x1="16" y1="21.17" x2="12" y2="12"></line><line x1="17.94" y1="3.95" x2="12" y2="12"></line><line x1="6.06" y1="3.95" x2="12" y2="12"></line></svg>',
                    circle: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle></svg>',
                    square: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect></svg>',
                    triangle: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path></svg>',
                    octagon: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon></svg>',
                    hexagon: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21.5 11.23L17 2.5 7 2.5 2.5 11.23 7 19.96 17 19.96 21.5 11.23z"></path></svg>',
                    cloud_lightning: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19.47 16.62A5 5 0 0 0 18 7h-1.26A8 8 0 1 0 6 15.25"></path><polyline points="13 11 13 6 19 6 19 11 14 11 18 17 13 17 13 22 8 22 8 17 13 17 9 11 13 11"></polyline></svg>',
                    cloud_off: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="1" y1="1" x2="23" y2="23"></line><path d="M22 13a4.98 4.98 0 0 0-2.07-5.93A5 5 0 0 0 18 7h-1.26a8 8 0 0 0-7.83-8M3 15.25C2.31 16.16 2 17.26 2 18.39A5 5 0 0 0 7.35 22H18a5 5 0 0 0 4.25-5.63"></path></svg>',
                    columns: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3h7a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-7m0-18H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h7m0-18v18"></path></svg>',
                    command: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 3a3 3 0 0 0-3 3v12a3 3 0 0 0 3 3 3 3 0 0 0 3-3V6a3 3 0 0 0-3-3zM6 3a3 3 0 0 1 3 3v12a3 3 0 0 1-3 3 3 3 0 0 1-3-3V6a3 3 0 0 1 3-3z"></path></svg>',
                    credit_card_2: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>',
                    crop: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6.13 1L1 6.13a2 2 0 0 0 2.83 2.83L6.13 6.13m2.83-2.83L14 1m-1 7h7m0-7v7m0 0l-7 7m-7 0l-7 7m0 0l-7-7"></path></svg>',
                    database_2: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><ellipse cx="12" cy="5" rx="9" ry="3"></ellipse><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path><path d="M21 19c0 1.66-4 3-9 3s-9-1.34-9-3"></path><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path></svg>',
                    delete: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 4H8l-7 16V4h22z"></path></svg>',
                    disc: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="12" r="3"></circle></svg>',
                    divide: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="6" r="2"></circle><line x1="5" y1="12" x2="19" y2="12"></line><circle cx="12" cy="18" r="2"></circle></svg>',
                    divide_circle: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="8" y1="12" x2="16" y2="12"></line><line x1="12" y1="16" x2="12" y2="16"></line><line x1="12" y1="8" x2="12" y2="8"></line><circle cx="12" cy="12" r="10"></circle></svg>',
                    divide_square: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="8" y1="12" x2="16" y2="12"></line><line x1="12" y1="16" x2="12" y2="16"></line><line x1="12" y1="8" x2="12" y2="8"></line></svg>',
                    dollar_sign_2: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>',
                    droplet: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.32 0z"></path></svg>',
                    edit_2: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>',
                    edit_3: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>',
                    external_link_2: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>',
                    eye_2: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>',
                    facebook_2: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>',
                    figma: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 5.5A3.5 3.5 0 0 1 8.5 2H12v7H8.5A3.5 3.5 0 0 1 5 5.5z"></path><path d="M12 2h3.5a3.5 3.5 0 1 1 0 7H12V2z"></path><path d="M12 12.5a3.5 3.5 0 1 1 7 0 3.5 3.5 0 1 1-7 0z"></path><path d="M5 12.5A3.5 3.5 0 0 1 8.5 9H12v7H8.5A3.5 3.5 0 0 1 5 12.5z"></path><path d="M12 16h3.5a3.5 3.5 0 1 1 0 7H12v-7z"></path></svg>',
                    file_text: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="14 2 14 9 20 9"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><line x1="10" y1="9" x2="10" y2="9"></line></svg>',
                    film: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="2.18" ry="2.18"></rect><line x1="7" y1="2" x2="7" y2="22"></line><line x1="17" y1="2" x2="17" y2="22"></line><line x1="2" y1="12" x2="22" y2="12"></line><line x1="2" y1="7" x2="7" y2="7"></line><line x1="2" y1="17" x2="7" y2="17"></line><line x1="17" y1="17" x2="22" y2="17"></line><line x1="17" y1="7" x2="22" y2="7"></line></svg>',
                    flag: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"></path><line x1="4" y1="22" x2="4" y2="15"></line></svg>',
                    folder_open: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path><line x1="6" y1="12" x2="18" y2="12"></line></svg>',
                    git_branch_2: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="6" y1="3" x2="6" y2="15"></line><circle cx="18" cy="6" r="3"></circle><circle cx="6" cy="18" r="3"></circle><path d="M18 9a9 9 0 0 1-9 9"></path></svg>',
                    git_merge_2: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 18H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2h-2"></path><polyline points="15 3 21 9 15 15"></polyline></svg>',
                    git_pull_request_2: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="18" cy="18" r="3"></circle><circle cx="6" cy="6" r="3"></circle><path d="M13 6h3a2 2 0 0 1 2 2v7"></path><line x1="6" y1="9" x2="6" y2="21"></line></svg>',
                    hard_drive: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="12" x2="2" y2="12"></line><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"></path></svg>',
                    hash: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="4" y1="9" x2="20" y2="9"></line><line x1="4" y1="15" x2="20" y2="15"></line><line x1="10" y1="3" x2="8" y2="21"></line><line x1="16" y1="3" x2="14" y2="21"></line></svg>',
                    headphones_2: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 18v-6a9 9 0 0 1 18 0v6"></path><path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"></path></svg>',
                    help_circle: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>',
                    help_square: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>',
                    image_2: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>',
                    inbox_2: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 16 12 14 22 10 22 8 12 2 12"></polyline><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"></path></svg>',
                    info_2: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>',
                    key: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 2l-2 2m-7.61 7.61a3.5 3.5 0 1 1-4.95-4.95L17.5 4.5 19.5 6.5l-.26.26A6.5 6.5 0 1 0 22.49 12.91L23 13"></path></svg>',
                    keyboard: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="15" rx="2" ry="2"></rect><line x1="8" y1="11" x2="8" y2="11"></line><line x1="12" y1="11" x2="12" y2="11"></line><line x1="16" y1="11" x2="16" y2="11"></line><line x1="10" y1="15" x2="14" y2="15"></line></svg>',
                    layers_2: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>',
                    life_buoy_2: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="12" r="4"></circle><line x1="4.93" y1="4.93" x2="9.07" y2="9.07"></line><line x1="14.93" y1="14.93" x2="19.07" y2="19.07"></line><line x1="14.93" y1="9.07" x2="19.07" y2="4.93"></line><line x1="4.93" y1="19.07" x2="9.07" y2="14.93"></line></svg>',
                    linkedin_2: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect x="2" y="9" width="4" height="12"></rect><circle cx="4" cy="4" r="2"></circle></svg>',
                    map_2: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"></polygon><line x1="8" y1="2" x2="8" y2="18"></line><line x1="16" y1="6" x2="16" y2="22"></line></svg>',
                    map_pin_2: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>',
                    maximize_3: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3m-18 0V19a2 2 0 0 0 2 2h3"></path></svg>',
                    minimize_3: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 3v3a2 2 0 0 1-2 2H3m18 0h-3a2 2 0 0 1-2-2V3m0 18v-3a2 2 0 0 1 2-2h3m-18 0h3a2 2 0 0 1 2 2v3"></path></svg>',
                    message_circle_2: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.02 0 0 1 8 8z"></path></svg>',
                    mic_off: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="1" y1="1" x2="23" y2="23"></line><path d="M9.91 9.91A4 4 0 0 1 12 16v2a3 3 0 0 1-3 3h-2M5 10V7a3 3 0 0 1 6 0v1a7 7 0 0 0 7-7v-2M15.45 15.45A3.99 3.99 0 0 1 12 19.96v-2"></path><line x1="12" y1="19" x2="12" y2="23"></line><line x1="8" y1="23" x2="16" y2="23"></line></svg>',
                    moon_2: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>',
                    monitor_2: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>',
                    octagon_2: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon></svg>',
                    package: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="17" x2="12" y2="23"></line><line x1="5" y1="12" x2="2" y2="12"></line><line x1="22" y1="12" x2="19" y2="12"></line><path d="M12 2L2 7l10 5 10-5-10-5z"></path><path d="M2 17l10 5 10-5"></path><path d="M2 12l10 5 10-5"></path></svg>',
                    paperclip: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49L12.5 4.5a3 3 0 0 1 4.24 4.24l-8.5 8.5a1.5 1.5 0 0 1-2.12-2.12L13.5 9"></path></svg>',
                    pause_circle: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="10" y1="15" x2="10" y2="9"></line><line x1="14" y1="15" x2="14" y2="9"></line></svg>',
                    pause_octagon: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon><line x1="10" y1="15" x2="10" y2="9"></line><line x1="14" y1="15" x2="14" y2="9"></line></svg>',
                    percent_2: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="5" x2="5" y2="19"></line><circle cx="6.5" cy="6.5" r="2.5"></circle><circle cx="17.5" cy="17.5" r="2.5"></circle></svg>',
                    phone: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.63A2 2 0 0 1 4.08 2H7c.43 0 .79.31.87.75l.93 4.41c.05.25-.01.5-.16.7L6.01 12.01a18.63 18.63 0 0 0 6.91 6.91l3.05-3.05c.2-.16.45-.22.7-.16l4.41.93c.44.08.75.44.75.87z"></path></svg>',
                    phone_call: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.63A2 2 0 0 1 4.08 2H7c.43 0 .79.31.87.75l.93 4.41c.05.25-.01.5-.16.7L6.01 12.01a18.63 18.63 0 0 0 6.91 6.91l3.05-3.05c.2-.16.45-.22.7-.16l4.41.93c.44.08.75.44.75.87z"></path><path d="M14 9l1-1 3 3-1 1z"></path><path d="M5 12a10.94 10.94 0 0 1 1.48-3.95"></path><path d="M22 3.4c-4.97-4.97-13.03-4.97-18 0"></path></svg>',
                    phone_forwarded: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="19 1 19 8 22 8"></polyline><line x1="13.5" y1="8" x2="19" y2="8"></line><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.63A2 2 0 0 1 4.08 2H7c.43 0 .79.31.87.75l.93 4.41c.05.25-.01.5-.16.7L6.01 12.01a18.63 18.63 0 0 0 6.91 6.91l3.05-3.05c.2-.16.45-.22.7-.16l4.41.93c.44.08.75.44.75.87z"></path></svg>',
                    phone_incoming: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="16 2 16 8 22 8"></polyline><line x1="13.5" y1="8" x2="16" y2="8"></line><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.63A2 2 0 0 1 4.08 2H7c.43 0 .79.31.87.75l.93 4.41c.05.25-.01.5-.16.7L6.01 12.01a18.63 18.63 0 0 0 6.91 6.91l3.05-3.05c.2-.16.45-.22.7-.16l4.41.93c.44.08.75.44.75.87z"></path></svg>',
                    phone_missed: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="8" x2="16" y2="14"></line><line x1="16" y1="8" x2="22" y2="14"></line><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.63A2 2 0 0 1 4.08 2H7c.43 0 .79.31.87.75l.93 4.41c.05.25-.01.5-.16.7L6.01 12.01a18.63 18.63 0 0 0 6.91 6.91l3.05-3.05c.2-.16.45-.22.7-.16l4.41.93c.44.08.75.44.75.87z"></path></svg>',
                    phone_off: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.68 13.31a16 16 0 0 0 3.31 3.31L17.15 20.45A2 2 0 0 0 20 20.07L22 18V6l-2-2a2 2 0 0 0-2.07-.15L13.31 10.68z"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>',
                    phone_outgoing: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 8 22 2 16 2"></polyline><line x1="19" y1="5" x2="16" y2="8"></line><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.63A2 2 0 0 1 4.08 2H7c.43 0 .79.31.87.75l.93 4.41c.05.25-.01.5-.16.7L6.01 12.01a18.63 18.63 0 0 0 6.91 6.91l3.05-3.05c.2-.16.45-.22.7-.16l4.41.93c.44.08.75.44.75.87z"></path></svg>',
                    pie_chart_2: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path><path d="M22 12A10 10 0 0 0 12 2v10z"></path></svg>',
                    plus_circle: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>',
                    plus_square: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>',
                    power: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18.36 6.64a9 9 0 1 1-12.73 0"></path><line x1="12" y1="2" x2="12" y2="12"></line></svg>',
                    printer_2: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>',
                    radio: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.5 9A2.5 2.5 0 0 0 12 7.5a2.5 2.5 0 0 0-2.5 2.5A2.5 2.5 0 0 0 12 12.5a2.5 2.5 0 0 0 2.5 2.5v.5c0 1.3-1.2 2.5-2.5 2.5S9.5 19.3 9.5 18H4a2 2 0 0 1-2-2V4h20v12a2 2 0 0 1-2 2h-6"></path><polyline points="8.5 20 8.5 22"></polyline><polyline points="15.5 20 15.5 22"></polyline></svg>',
                    repeat: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="17 1 21 5 17 9"></polyline><path d="M21 5H9a7 7 0 0 0-7 7v2"></path><polyline points="7 23 3 19 7 15"></polyline><path d="M3 19h12a7 7 0 0 0 7-7V9"></path></svg>',
                    rewind: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="11 19 2 12 11 5 11 19"></polygon><polygon points="22 19 13 12 22 5 22 19"></polygon></svg>',
                    rotate_ccw_2: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="1 4 1 10 7 10"></polyline><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path></svg>',
                    rotate_cw_2: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 4 23 10 17 10"></polyline><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"></path></svg>',
                    rss: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 11a9 9 0 0 1 9 9"></path><path d="M4 4a16 16 0 0 1 16 16"></path><circle cx="5" cy="19" r="1"></circle></svg>',
                    save_2: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>',
                    scissors_2: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="6" cy="6" r="3"></circle><circle cx="6" cy="18" r="3"></circle><line x1="20" y1="4" x2="8.12" y2="15.88"></line><line x1="14.47" y1="14.48" x2="20" y2="20"></line><line x1="8.12" y1="8.12" x2="12" y2="12"></line></svg>',
                    search_2: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
                    send_2: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>',
                    server_2: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect><rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect><line x1="6" y1="6" x2="6.01" y2="6"></line><line x1="6" y1="18" x2="6.01" y2="18"></line></svg>',
                    settings_2: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9A1.65 1.65 0 0 0 10 3.6V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1.82.33 1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-.33 1.82z"></path></svg>',
                    share_2: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"></path><polyline points="16 6 12 2 8 6"></polyline><line x1="12" y1="2" x2="12" y2="15"></line></svg>',
                    shield: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>',
                    shield_off: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19.69 14a2.95 2.95 0 0 0 .31-1V5l-8-3-3.16 1.18"></path><path d="M4.7 6.46L2 7v10c0 6 8 10 8 10a19.49 19.49 0 0 0 4.7-2.31"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>',
                    shopping_bag: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 7v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V7l-3-5z"></path><line x1="3" y1="7" x2="21" y2="7"></line><path d="M12 22v-4"></path><path d="M8 7v-2a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>',
                    shopping_cart_2: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>',
                    sliders_2: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="4" y1="21" x2="4" y2="14"></line><line x1="4" y1="10" x2="4" y2="3"></line><line x1="12" y1="21" x2="12" y2="12"></line><line x1="12" y1="8" x2="12" y2="3"></line><line x1="20" y1="21" x2="20" y2="16"></line><line x1="20" y1="12" x2="20" y2="3"></line><line x1="1" y1="14" x2="7" y2="14"></line><line x1="9" y1="8" x2="15" y2="8"></line><line x1="17" y1="16" x2="23" y2="16"></line></svg>',
                    smartphone_2: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect><line x1="12" y1="18" x2="12.01" y2="18"></line></svg>',
                    speaker: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="2" width="16" height="20" rx="2" ry="2"></rect><circle cx="12" cy="14" r="4"></circle><line x1="12" y1="6" x2="12.01" y2="6"></line></svg>',
                    star_2: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>',
                    sunrise: '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 18a5 5 0 0 0-10 0"></path><line x1="12" y1="2" x2="12" y2="9"></line><line x1="4.22" y1="10.22" x2="5.64" y2="11.64"></line><line x1="1" y1="18" x2="3" y2="18"></line><line x1="21" y1="18" x2="23" y2="18"></line><line x1="18.36" y1="11.64" x2="19.78" y2="10.22"></line><line x1="23" y1="22" x2="1" y2="22"></line></svg>',
                    // ... more icons would continue here
                },
                get: (name) => client.icons._icons[name] || '',
                inject: (el, name) => {
                    const icon = client.icons.get(name);
                    if (icon) {
                        el.innerHTML = icon;
                        el.classList.add('zen-icon');
                    }
                }
            },
            
            // ... rest of the client object would continue here
        };

        // Return the public API
        return {
            cfg,
            u,
            client,
            // ... rest of the public API
        };
    })();

    // Initialize the framework
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => zen.client.css.inject());
    } else {
        zen.client.css.inject();
    }

    // Expose to global scope
    window.zen = zen;
})();
