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

Alpine.start();
