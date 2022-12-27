'use strict';

/* helper functions */
function qsa(selector, parent = document) { return [...parent.querySelectorAll(selector)]; }
function qs(selector, parent = document) { return parent.querySelector(selector); }
function _set(name, value) { localStorage.setItem(name, JSON.stringify(value)); }
function _get(name) { return JSON.parse(localStorage.getItem(name)); }
/* ---------------- */

AOS.init({
    once: true,
});

window.addEventListener('DOMContentLoaded', function() {
    initPristineValidation();
    lucide.createIcons();
    qs('.js-sidenav-toggle').onclick = function() {
        document.body.classList.toggle('sidenav-visible');
    }
});

function initPristineValidation() {
    for (let item of qsa('.js-validate:not(.js-validate-inited)')) {
        let pristine = new Pristine(item);
        (p => {
            item.addEventListener('submit', function (e) {
                e.preventDefault();
                if (p.validate()) {
                    this.submit();
                }
            });
        })(pristine);
        item.classList.add('js-validate-inited');
    }
}