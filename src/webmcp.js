(function () {
    'use strict';

    if (!document.modelContext || typeof document.modelContext.registerTool !== 'function') {
        return;
    }

    const data = window.fourElementsAgentData || {};
    const pages = Array.isArray(data.pages) ? data.pages : [];
    const readOnly = { readOnlyHint: true, untrustedContentHint: false };

    const register = function (tool) {
        Promise.resolve(document.modelContext.registerTool(tool)).catch(function (error) {
            if (window.console && console.debug) {
                console.debug('WebMCP tool registration skipped:', error);
            }
        });
    };

    register({
        name: 'get_site_information',
        title: 'Informacje o 4elements',
        description: 'Zwraca podstawowe informacje o serwisie 4elements i aktualnie otwartej stronie.',
        inputSchema: { type: 'object', properties: {}, additionalProperties: false },
        annotations: readOnly,
        execute: async function () {
            return {
                name: data.name || '4elements',
                description: data.description || '',
                homeUrl: data.homeUrl || window.location.origin + '/',
                currentPage: {
                    title: document.title,
                    url: data.currentUrl || window.location.href
                }
            };
        }
    });

    register({
        name: 'list_services_and_pages',
        title: 'Oferta i ważne strony',
        description: 'Zwraca listę głównych usług oraz ważnych stron 4elements wraz z ich adresami URL.',
        inputSchema: { type: 'object', properties: {}, additionalProperties: false },
        annotations: readOnly,
        execute: async function () {
            return pages.map(function (page) {
                return { name: page.name, url: page.url };
            });
        }
    });

    register({
        name: 'find_site_page',
        title: 'Znajdź stronę 4elements',
        description: 'Wyszukuje najbardziej pasujące strony w ofercie i informacjach serwisu 4elements. Nie otwiera strony automatycznie.',
        inputSchema: {
            type: 'object',
            properties: {
                query: {
                    type: 'string',
                    minLength: 2,
                    description: 'Temat, usługa lub informacja, której szuka użytkownik.'
                }
            },
            required: ['query'],
            additionalProperties: false
        },
        annotations: readOnly,
        execute: async function (input) {
            const query = String(input.query || '').toLocaleLowerCase('pl-PL').trim();
            const words = query.split(/\s+/).filter(Boolean);
            const matches = pages.map(function (page) {
                const haystack = (page.name + ' ' + (page.keywords || '')).toLocaleLowerCase('pl-PL');
                const score = words.reduce(function (total, word) {
                    return total + (haystack.indexOf(word) !== -1 ? 1 : 0);
                }, 0);
                return { name: page.name, url: page.url, score: score };
            }).filter(function (page) {
                return page.score > 0;
            }).sort(function (a, b) {
                return b.score - a.score;
            }).slice(0, 5);

            return matches.length ? matches : [{
                name: 'Wyniki wyszukiwania w serwisie',
                url: (data.homeUrl || '/') + '?s=' + encodeURIComponent(input.query)
            }];
        }
    });

    register({
        name: 'get_contact_details',
        title: 'Kontakt z 4elements',
        description: 'Zwraca publiczne dane kontaktowe 4elements oraz adres strony kontaktowej.',
        inputSchema: { type: 'object', properties: {}, additionalProperties: false },
        annotations: readOnly,
        execute: async function () {
            const contactPage = pages.find(function (page) { return page.name === 'Kontakt'; });
            return {
                email: data.contact && data.contact.email ? data.contact.email : 'kontakt@4elements.pl',
                phones: data.contact && data.contact.phones ? data.contact.phones : [],
                contactPageUrl: contactPage ? contactPage.url : (data.homeUrl || '/') + 'kontakt/'
            };
        }
    });
}());
