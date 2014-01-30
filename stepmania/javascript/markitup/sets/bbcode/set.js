var mySettings;

(function () {
    mySettings = {
        nameSpace: 'bbcode',
        previewParserPath: '', // path to your BBCode parser
        onTab: {
            keepDefault: false,
            openWith: '  '
        },
        markupSet: [{
            name: 'Bold',
            className: 'bold',
            key: 'B',
            openWith: '[b]',
            closeWith: '[/b]'
        }, {
            name: 'Italic',
            className: 'italic',
            key: 'I',
            openWith: '[i]',
            closeWith: '[/i]'
        }, {
            name: 'Underline',
            className: 'underline',
            key: 'U',
            openWith: '[u]',
            closeWith: '[/u]'
        }, {
            name: 'Strikethrough',
            className: 'stroke',
            openWith: '[s]',
            closeWith: '[/s]'
        }, {
            separator: '---------------'
        }, {
            name: 'Size',
            className: 'size',
            dropMenu: [{
                name: 'Small',
                openWith: '[small]',
                closeWith: '[/small]'
            }, {
                name: 'Large',
                openWith: '[large]',
                closeWith: '[/large]'
            }]
        }, {
            name: 'Color',
            className: 'color'
        }, {
            name: 'Rainbow',
            className: 'rainbow',
            openWith: '[rainbow]',
            closeWith: '[/rainbow]'
        }, {
            separator: '---------------'
        }, {
            name: 'Left',
            className: 'left-align',
            replaceWith: function (h) {
                return h.selection.replace(/\[[//]?(center|right)\]/g, "");
            }
        }, {
            name: 'Center',
            className: 'center-align',
            openWith: '[center]',
            closeWith: '[/center]'
        }, {
            name: 'Right',
            className: 'right-align',
            openWith: '[right]',
            closeWith: '[/right]'
        }, {
            separator: '---------------'
        }, {
            name: 'Picture',
            className: 'picture',
            key: 'P',
            beforeInsert: promptForParams('img', 'Picture link:', 'Title:', false)
        }, {
            name: 'Youtube',
            className: 'youtube',
            beforeInsert: function (h) {
                var replace = window.prompt('Youtube link:', h.selection);
                if (replace === null && replace === '') {
                    h.replaceWith = null;
                } else {
                    h.replaceWith = replace;
                }
            },
            openWith: '[youtube]',
            closeWith: '[/youtube]',
            placeHolder: 'Youtube link here...'
        }, {
            name: 'Link',
            className: 'link',
            key: 'L',
            beforeInsert: promptForParams('url', 'Link:', 'Text:', true)
        }, {
            separator: '---------------'
        }, {
            name: 'Quote',
            className: 'quotes',
            beforeInsert: optionalParam('quote', 'Author:')
        }, {
            name: 'Code',
            className: 'code',
            openWith: '[code]',
            closeWith: '[/code]'
        }, {
            name: 'Abbreviation',
            className: 'abbreviation',
            openWith: '[abbr=[![Description:]!]]',
            closeWith: '[/abbr]',
            placeHolder: 'Abbreviation here...'
        }, {
            separator: '---------------'
        }, {
            name: 'Clean BBCode from selection',
            className: 'clean',
            replaceWith: function (h) {
                return h.selection.replace(/\[(.*?)\]/g, "");
            }
        }


            /*
             * TODO list, reply selected, stepsblock, alignment css
             *
             */
            /*{name:'Bulleted list', openWith:'[list]\n', closeWith:'\n[/list]'},
             {name:'Numeric list', openWith:'[list=[![Starting number]!]]\n', closeWith:'\n[/list]'},
             {name:'List item', openWith:'[*] '},
             {separator:'---------------' },*/
            //{name:'Preview', className:"preview", call:'preview' }
        ]
    };

    /**
     * It there are two parameters for a tag, and It's not clear where
     * the selected text should go. It prompts for both.
     * It assumes that the second parameter is optional.
     *
     * @param {string} tagName The name of the tag.
     * @param {string} firstParamText The text on the prompt for the first parameter.
     * @param {string} secondParamText The text on the prompt for the second parameter.
     * @param {bool} firstParamAsOption Indicates whether the first parameter should move
     * to the option part of [name=option]param[/name] if the second is also present.
     * @return {function} A function that can be used in beforeInsert.
     */
    function promptForParams(tagName, firstParamText, secondParamText, firstParamAsOption) {
        return function (h) {
            var firstParamValue = window.prompt(firstParamText, h.selection),
                secondParamValue = window.prompt(secondParamText, h.selection);

            if (secondParamValue === null || secondParamValue === '') {
                h.openWith = '[' + tagName + ']';
                h.replaceWith = firstParamValue;
            } else if (firstParamAsOption) {
                h.openWith = '[' + tagName + '=' + firstParamValue + ']';
                h.replaceWith = secondParamValue;
            } else {
                h.openWith = '[' + tagName + '=' + secondParamValue + ']';
                h.replaceWith = firstParamValue;
            }
            h.closeWith = h.closeWith || '[/' + tagName + ']';
        };
    }
    function optionalParam(tagName, paramText) {
        return function (h) {
            var paramValue = window.prompt(paramText, '');
            if (paramValue === null && paramValue === '') {
                h.openWith = '[' + tagName + ']';
            } else {
                h.openWith = '[' + tagName + '=' + paramValue + ']';
            }
            h.closeWith = h.closeWith || '[/' + tagName + ']';
        };
    }


})();