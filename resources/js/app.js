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
    return {
        locales,
        open: false,
        responseText: 'Choose a country to translate the website.',
        storageKey: 'np_country_code_v2',
        selected: { code: 'EN', country: 'English', language: 'English', locale: 'en' },
        ready: false,

        init() {
            window.localStorage.removeItem('np_country_code');
            const savedCode = window.localStorage.getItem(this.storageKey);
            const saved = this.locales.find((locale) => locale.code === savedCode);

            if (saved) {
                this.selected = saved;
            }

            this.responseText = this.messageFor(this.selected);
            window.addEventListener('np-google-translate-ready', () => {
                this.ready = true;
                this.applyWebsiteTranslation(false);
            });

            if (window.npGoogleTranslateReady) {
                this.ready = true;
                this.applyWebsiteTranslation(false);
            }
        },

        choose(locale) {
            this.selected = locale;
            this.open = false;
            window.localStorage.setItem(this.storageKey, locale.code);
            this.responseText = this.messageFor(locale);
            this.applyWebsiteTranslation(true);
        },

        messageFor(locale) {
            if (! locale) {
                return 'Choose a country to translate the website.';
            }

            if (locale.locale === 'en') {
                return 'Website language set to English.';
            }

            return `Website will translate to ${locale.language}.`;
        },

        applyWebsiteTranslation(shouldReload = false) {
            const language = this.selected.locale || 'en';

            document.documentElement.lang = language;

            if (language === 'en') {
                this.clearTranslateCookie();
                if (shouldReload) {
                    window.location.reload();
                }

                return;
            } else {
                this.setTranslateCookie(language);
            }

            const combo = document.querySelector('.goog-te-combo');
            if (combo) {
                combo.value = language === 'en' ? '' : language;
                combo.dispatchEvent(new Event('change'));
                return;
            }

            if (shouldReload) {
                window.location.reload();
            }
        },

        setTranslateCookie(language) {
            if (window.npSetGoogleTranslate) {
                window.npSetGoogleTranslate(language);
                return;
            }

            document.cookie = `googtrans=/en/${language}; path=/; SameSite=Lax`;
        },

        clearTranslateCookie() {
            if (window.npClearGoogleTranslate) {
                window.npClearGoogleTranslate();
                return;
            }

            document.cookie = 'googtrans=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/';
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
