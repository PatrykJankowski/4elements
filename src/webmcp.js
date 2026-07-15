(function () {
    'use strict';

    const modelContext = navigator.modelContext || document.modelContext;

    if (!modelContext || typeof modelContext.registerTool !== 'function') {
        return;
    }

    const data = window.fourElementsAgentData || {};
    const pages = Array.isArray(data.pages) ? data.pages : [];
    const faq = Array.isArray(data.faq) ? data.faq : [];
    const answerCapsules = Array.isArray(data.answerCapsules) ? data.answerCapsules : [];
    const readOnly = { readOnlyHint: true, untrustedContentHint: false };

    const registeredTools = [];
    const register = function (tool) {
        registeredTools.push(tool.name);
        try {
            Promise.resolve(modelContext.registerTool(tool)).catch(function (error) {
                if (window.console && console.debug) {
                    console.debug('WebMCP tool registration skipped:', error);
                }
            });
        } catch (error) {
            if (window.console && console.debug) {
                console.debug('WebMCP tool registration skipped:', error);
            }
        }
    };

    if (typeof modelContext.provideContext === 'function') {
        try {
            Promise.resolve(modelContext.provideContext({
                name: data.name || '4elements',
                description: data.description || '',
                url: data.currentUrl || window.location.href
            })).catch(function () {});
        } catch (error) {}
    }

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

    register({
        name: 'get_swimming_faq',
        title: 'FAQ nauki pływania',
        description: 'Zwraca publiczne odpowiedzi 4elements o nauce pływania, zapisach, przygotowaniu, pływalniach i płatnościach.',
        inputSchema: {
            type: 'object',
            properties: {
                query: {
                    type: 'string',
                    description: 'Opcjonalne pytanie lub temat, np. zapisy, czepek, odwołanie albo pływalnia.'
                }
            },
            additionalProperties: false
        },
        annotations: readOnly,
        execute: async function (input) {
            const items = answerCapsules.concat(faq);
            const query = String(input && input.query ? input.query : '').toLocaleLowerCase('pl-PL').trim();
            const words = query.split(/\s+/).filter(function (word) { return word.length > 1; });
            let results = items;

            if (words.length) {
                results = items.map(function (item) {
                    const haystack = (item.question + ' ' + item.answer).toLocaleLowerCase('pl-PL');
                    const score = words.reduce(function (total, word) {
                        return total + (haystack.indexOf(word) !== -1 ? 1 : 0);
                    }, 0);
                    return { question: item.question, answer: item.answer, score: score };
                }).filter(function (item) {
                    return item.score > 0;
                }).sort(function (a, b) {
                    return b.score - a.score;
                }).slice(0, 5);
            }

            return results.map(function (item) {
                return {
                    question: item.question,
                    answer: item.answer,
                    sourceUrl: data.faqUrl || (data.homeUrl || '/') + 'faq/'
                };
            });
        }
    });

    /*
     * Contact Form 7 may replace parts of a form while initializing or after
     * validation. Reapplying declarative attributes keeps WebMCP connected to
     * the existing CF7 validation and submission flow.
     */
    const annotateForm = function (form, name, description) {
        if (!form || form.hasAttribute('toolname')) {
            return;
        }

        form.setAttribute('toolname', name);
        form.setAttribute('tooldescription', description);

        Array.prototype.forEach.call(form.elements || [], function (control) {
            if (!control.name || control.hasAttribute('toolparamdescription')) {
                return;
            }

            const labels = Array.prototype.slice.call(form.querySelectorAll('label'));
            const label = control.id ? labels.find(function (candidate) {
                return candidate.htmlFor === control.id;
            }) : null;
            const labelText = label ? label.textContent.trim() : '';
            control.setAttribute(
                'toolparamdescription',
                labelText || control.getAttribute('placeholder') || ('Pole formularza: ' + control.name)
            );
        });
    };

    const annotateInteractiveForms = function () {
        if (data.isContactPage) {
            const contactForm = document.querySelector('.wpcf7 form');
            annotateForm(
                contactForm,
                'prepare_contact_message',
                'Wypełnia formularz kontaktowy 4elements. Użytkownik musi sprawdzić wiadomość i samodzielnie zatwierdzić wysłanie.'
            );
        }
    };

    annotateInteractiveForms();

    if (data.isContactPage && document.body) {
        const formObserver = new MutationObserver(annotateInteractiveForms);
        formObserver.observe(document.body, { childList: true, subtree: true });
    }

    window.addEventListener('pagehide', function () {
        if (typeof modelContext.unregisterTool !== 'function') {
            return;
        }

        registeredTools.forEach(function (name) {
            try {
                Promise.resolve(modelContext.unregisterTool(name)).catch(function () {});
            } catch (error) {}
        });
    }, { once: true });
}());
