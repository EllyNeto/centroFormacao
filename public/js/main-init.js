/* Main layout code-highlight initialization */
document.addEventListener('DOMContentLoaded', (event) => {
    if (typeof hljs !== 'undefined') {
        hljs.highlightAll();
        hljs.configure({ ignoreUnescapedHTML: true });

        document.querySelectorAll('pre code').forEach((el) => {
            hljs.highlightElement(el);
        });
    }
});
