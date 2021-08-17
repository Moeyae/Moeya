(function (tinymce) {
    tinymce.PluginManager.add( 'zaxu_highlight_tinymce_button', function (editor, url) {
        // generate language values
        var languageValues = [
            {text: 'plaintext', value: 'paintext'},
            {text: 'Apache', value: 'apache'},
            {text: 'Bash', value: 'bash'},
            {text: 'C#', value: 'cs'},
            {text: 'C++', value: 'cpp'},
            {text: 'CSS', value: 'css'},
            {text: 'CoffeeScript', value: 'coffeescript'},
            {text: 'Diff', value: 'diff'},
            {text: 'Go', value: 'go'},
            {text: 'Diff', value: 'diff'},
            {text: 'HTML, XML', value: 'xml'},
            {text: 'HTTP', value: 'http'},
            {text: 'JSON', value: 'json'},
            {text: 'Java', value: 'java'},
            {text: 'JavaScript', value: 'javascript'},
            {text: 'Kotlin', value: 'kotlin'},
            {text: 'Less', value: 'less'},
            {text: 'Lua', value: 'lua'},
            {text: 'Makefile', value: 'makefile'},
            {text: 'Markdown', value: 'markdown'},
            {text: 'Nginx', value: 'nginx'},
            {text: 'Objective-C', value: 'objectivec'},
            {text: 'PHP', value: 'php'},
            {text: 'Perl', value: 'perl'},
            {text: 'Properties', value: 'properties'},
            {text: 'Python', value: 'python'},
            {text: 'Ruby', value: 'ruby'},
            {text: 'Rust', value: 'rust'},
            {text: 'SCSS', value: 'scss'},
            {text: 'SQL', value: 'sql'},
            {text: 'Shell Session', value: 'shell'},
            {text: 'Swift', value: 'swift'},
            {text: 'TOML, INI', value: 'ini'},
            {text: 'TypeScript', value: 'typescript'},
            {text: 'YAML', value: 'yaml'},
        ];

        editor.on( 'init', function () {
        } );

        // Add Code Insert Button to toolbar
        editor.addButton('zaxu_highlight_tinymce_button', {
            title : themeSettings.highlight_title,
            icon: 'wp_code',
            onclick: function() {
                editor.windowManager.open({
                    title : themeSettings.highlight_title,
                    minWidth : 700,
                    body : [
                        {
                            type : 'listbox',
                            name : 'lang',
                            label : themeSettings.highlight_language,
                            values : languageValues
                        },
                        {
                            type : 'textbox',
                            name : 'code',
                            label : themeSettings.highlight_code,
                            multiline : true,
                            minHeight : 200
                        }
                    ],
                    onsubmit : function(e){
                        var code = e.data.code.replace(/\r\n/gmi, '\n'),
                            tag = 'code';
                        code =  tinymce.html.Entities.encodeAllRaw(code);
                        var sp = (e.data.addspaces ? '&nbsp;' : '');
                        editor.insertContent(sp + '<pre class="zaxu-highlightjs"><code class="' + e.data.lang + '">' + code + '</code></pre>' + sp + '<p></p>');
                    }
                });
            }
        });
    } );
})( window.tinymce );