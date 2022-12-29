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
    initRepeatables();
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

function initRepeatables() {
    for (let item of qsa('.js-repeatable:not(.js-repeatable-inited)')) {
        let template_html = qs('script[type="text/template"]', item).innerHTML;
        let add_button = qs('.js-repeatable-add', item);
        add_button.addEventListener('click', function() {
            console.log(item)
            item.insertAdjacentHTML('beforeend', template_html);
            initPristineValidation();
            initRepeatables();
            lucide.createIcons();
        });
        item.classList.add('js-repeatable-inited');
    }
    for (let del_item of qsa('.js-repeatable .js-repeatable-remove:not(.js-repeatable-inited)')) {
        del_item.addEventListener('click', function() {
            let parent = this.closest('.js-repeatable');
            let this_row = this.closest('.js-repeatable-row');
            if (parent && this_row) {
                parent.removeChild(this_row);
            }
        });
        del_item.classList.add('js-repeatable-inited');
    }
}