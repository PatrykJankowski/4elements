(function () {
    'use strict';

    var form = document.querySelector('.faq-page__search');
    var input = document.getElementById('faq-site-search');
    var status = document.getElementById('faq-search-status');
    var items = Array.prototype.slice.call(document.querySelectorAll('[data-faq-item]'));

    if (!form || !input || !status || !items.length) {
        return;
    }

    var normalize = function (value) {
        return String(value || '')
            .toLocaleLowerCase('pl-PL')
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .replace(/ł/g, 'l')
            .trim();
    };

    var relatedWords = {
        cena: ['platnosc', 'oplacic', 'wplata'],
        koszt: ['platnosc', 'oplacic', 'wplata'],
        platnosc: ['oplacic', 'wplata'],
        odwolanie: ['odwolac', 'odrobic'],
        nieobecnosc: ['odwolac', 'odrobic'],
        basen: ['plywalnia', 'lokalizacja'],
        adres: ['plywalnia', 'lokalizacja'],
        zapis: ['zapisy', 'formularz', 'rejestracja'],
        dziecko: ['dzieci', 'wiek', 'grupa'],
        rodzic: ['dzieckiem', 'szatni'],
        sprzet: ['zabrac', 'czepek', 'recznik']
    };

    var matches = function (text, terms) {
        return terms.every(function (term) {
            if (text.indexOf(term) !== -1) {
                return true;
            }
            return (relatedWords[term] || []).some(function (related) {
                return text.indexOf(related) !== -1;
            });
        });
    };

    var filter = function () {
        var query = normalize(input.value);
        var terms = query.split(/\s+/).filter(Boolean);
        var visible = 0;

        items.forEach(function (item) {
            var show = !terms.length || matches(normalize(item.getAttribute('data-faq-search')), terms);
            item.hidden = !show;
            if (show) {
                visible += 1;
            }
        });

        if (!terms.length) {
            status.textContent = '';
        } else if (visible === 1) {
            status.textContent = 'Znaleziono 1 odpowiedź.';
        } else if (visible > 1 && visible < 5) {
            status.textContent = 'Znaleziono ' + visible + ' odpowiedzi.';
        } else if (visible >= 5) {
            status.textContent = 'Znaleziono ' + visible + ' odpowiedzi.';
        } else {
            status.textContent = 'Nie znaleziono odpowiedzi. Spróbuj krótszej frazy lub napisz do nas.';
        }
    };

    input.addEventListener('input', filter);
    form.addEventListener('submit', function (event) {
        event.preventDefault();
        filter();
        input.focus();
    });
}());
