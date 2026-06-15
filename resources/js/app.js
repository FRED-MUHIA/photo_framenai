import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

window.richTextEditor = function () {
    return {
        input: null,
        editor: null,

        init(input, editor) {
            this.input = input;
            this.editor = editor;

            if (this.input.value.trim().match(/<[a-z][\s\S]*>/i)) {
                this.editor.innerHTML = this.input.value;
            } else {
                this.editor.textContent = this.input.value;
            }

            this.sync();
            this.editor.closest('form')?.addEventListener('submit', () => this.sync());
        },

        format(command) {
            this.editor.focus();
            document.execCommand(command, false, null);
            this.sync();
        },

        block(tagName) {
            this.editor.focus();
            document.execCommand('formatBlock', false, tagName);
            this.sync();
        },

        link() {
            this.editor.focus();
            const url = window.prompt('Paste link URL');

            if (! url) {
                return;
            }

            document.execCommand('createLink', false, url);
            this.sync();
        },

        sync() {
            if (! this.input || ! this.editor) {
                return;
            }

            this.input.value = this.editor.innerHTML.trim();
        },
    };
};

window.eastAfricaLanguageSelector = function (locales = []) {
    const translations = {
        en: {
            all_products: 'All products',
            admin_panel: 'Admin Panel',
            country: 'Country',
            dashboard: 'Dashboard',
            language_response: 'Language set for your country.',
            login: 'Login',
            quick_quote: 'Quick Quote',
            search_placeholder: 'Search...',
            shopping_cart: 'Shopping Cart',
            what_we_print: 'What We Print',
        },
        sw: {
            all_products: 'Bidhaa zote',
            admin_panel: 'Paneli ya Admin',
            country: 'Nchi',
            dashboard: 'Dashibodi',
            language_response: 'Lugha imewekwa kwa nchi yako.',
            login: 'Ingia',
            quick_quote: 'Nukuu ya Haraka',
            search_placeholder: 'Tafuta...',
            shopping_cart: 'Kikapu',
            what_we_print: 'Tunachochapisha',
        },
        rw: {
            all_products: 'Ibicuruzwa byose',
            admin_panel: 'Ubuyobozi',
            country: 'Igihugu',
            dashboard: 'Ahabanza',
            language_response: 'Ururimi rwashyizweho ku gihugu cyawe.',
            login: 'Injira',
            quick_quote: 'Saba igiciro',
            search_placeholder: 'Shaka...',
            shopping_cart: 'Igare ryo kugura',
            what_we_print: 'Ibyo duca',
        },
        rn: {
            all_products: 'Ibicuruzwa vyose',
            admin_panel: 'Ubuyobozi',
            country: 'Igihugu',
            dashboard: 'Ikibaho',
            language_response: 'Ururimi rwashizwe ku gihugu cawe.',
            login: 'Injira',
            quick_quote: 'Saba igiciro',
            search_placeholder: 'Rondera...',
            shopping_cart: 'Igare ryo kugura',
            what_we_print: 'Ivyo ducapa',
        },
        so: {
            all_products: 'Dhammaan alaabta',
            admin_panel: 'Maamulka',
            country: 'Dal',
            dashboard: 'Dashboard',
            language_response: 'Luuqadda waxaa loo dejiyay dalkaaga.',
            login: 'Gal',
            quick_quote: 'Qiime degdeg ah',
            search_placeholder: 'Raadi...',
            shopping_cart: 'Gaariga iibsiga',
            what_we_print: 'Waxaan daabacno',
        },
        am: {
            all_products: 'ሁሉም ምርቶች',
            admin_panel: 'አስተዳዳሪ',
            country: 'ሀገር',
            dashboard: 'ዳሽቦርድ',
            language_response: 'ቋንቋው ለሀገርዎ ተዘጋጅቷል።',
            login: 'ግባ',
            quick_quote: 'ፈጣን ዋጋ',
            search_placeholder: 'ፈልግ...',
            shopping_cart: 'የግዢ ቅርጫት',
            what_we_print: 'የምናትመው',
        },
    };

    return {
        locales,
        open: false,
        responseText: '',
        selected: { code: 'EA', country: 'East Africa', language: 'English', locale: 'en' },

        init() {
            const savedCode = window.localStorage.getItem('np_country_code');
            const saved = this.locales.find((locale) => locale.code === savedCode);

            if (saved) {
                this.selected = saved;
            }

            this.applyTranslation();
        },

        choose(locale) {
            this.selected = locale;
            this.open = false;
            window.localStorage.setItem('np_country_code', locale.code);
            this.applyTranslation();
        },

        applyTranslation() {
            const copy = translations[this.selected.locale] || translations.en;
            const fallback = translations.en;

            document.documentElement.lang = this.selected.locale || 'en';

            document.querySelectorAll('[data-ea-i18n]').forEach((element) => {
                const key = element.dataset.eaI18n;
                element.textContent = copy[key] || fallback[key] || element.textContent;
            });

            document.querySelectorAll('[data-ea-placeholder]').forEach((element) => {
                const key = element.dataset.eaPlaceholder;
                element.setAttribute('placeholder', copy[key] || fallback[key] || element.getAttribute('placeholder'));
            });

            this.responseText = copy.language_response || fallback.language_response;
        },
    };
};

window.productSearchSuggestions = function (endpoint) {
    return {
        endpoint,
        query: '',
        suggestions: [],
        loading: false,
        open: false,
        abortController: null,

        init() {
            this.query = new URLSearchParams(window.location.search).get('q') || '';
            this.$watch('query', (value) => this.search(value));
        },

        get allResultsUrl() {
            return `/products?q=${encodeURIComponent(this.query.trim())}`;
        },

        show() {
            if (this.query.trim().length >= 2) {
                this.open = true;
                this.search(this.query);
            }
        },

        async search(value) {
            const term = value.trim();

            if (term.length < 2) {
                this.open = false;
                this.suggestions = [];
                this.loading = false;
                this.abortController?.abort();
                return;
            }

            this.abortController?.abort();
            this.abortController = new AbortController();
            this.loading = true;
            this.open = true;

            try {
                const response = await fetch(`${this.endpoint}?q=${encodeURIComponent(term)}`, {
                    headers: { Accept: 'application/json' },
                    signal: this.abortController.signal,
                });

                if (! response.ok) {
                    throw new Error('Product suggestions request failed.');
                }

                this.suggestions = await response.json();
            } catch (error) {
                if (error.name !== 'AbortError') {
                    this.suggestions = [];
                }
            } finally {
                this.loading = false;
            }
        },
    };
};

Alpine.start();
