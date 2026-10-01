function parseMarkdown(text) {
    return text
        .replace(/\*(.*?)\*/g, '<strong>$1</strong>')
        .replace(/_(.*?)_/g, '<em>$1</em>')
        .replace(/~(.*?)~/g, '<del>$1</del>')
        .replace(/`(.*?)`/g, '<code>$1</code>');
}

(function ($) {
    $.fn.initiateMarkdown = function (options) {
        const allTools = {
            bold: { label: '<i class="fa fa-bold"></i>', wrap: '*' },
            italic: { label: '<i class="fa fa-italic"></i>', wrap: '_' },
            mono: { label: '<i class="fa fa-code"></i>', wrap: '`' },
            strike: { label: '<i class="fa fa-strikethrough"></i>', wrap: '~' },
        };

        const settings = $.extend({
            previewOn: '.markdownPreview',
            position: 'top',
            tools: ['bold', 'italic', 'mono', 'strike'],
        }, options);
        

        const markdownButtons = settings.tools
            .filter(tool => allTools[tool])
            .map(tool => allTools[tool]);

        function insertMarkdown(textarea, wrap) {
            const start = textarea.selectionStart;
            const end = textarea.selectionEnd;
            const value = textarea.value;

            const newValue = value.substring(0, start) + wrap + value.substring(start, end) + wrap + value.substring(end);
            textarea.value = newValue;
            textarea.dispatchEvent(new Event('input'));
        }

        return this.each(function () {
            const $textarea = $(this);
            const $formGroup = $textarea.closest('.form-group');
            const $label = $formGroup.find('label').first();
            const $preview = $(settings.previewOn);

            const $btnContainer = $('<div class="mb-2 markdown-btn-group"></div>');

            markdownButtons.forEach(btn => {
                const $btn = $(`<button type="button" class="markdown-btn">${btn.label}</button>`);
                $btn.on('click', () => insertMarkdown($textarea[0], btn.wrap));
                $btnContainer.append($btn);
            });

            if (settings.position === 'bottom') {
                $btnContainer.insertAfter($textarea);
            } else {
                $btnContainer.insertAfter($label);
            }

            function updatePreview() {
                const raw = $textarea.val();
                $preview.html(parseMarkdown(raw));
            }

            $textarea.on('input paste change', updatePreview);
            updatePreview();
        });
    };
})(jQuery);
