"use strict";

class RconPlugin {
    init (store) {
        this.store = store;
        this.isExecuting = false;
        this.input = document.getElementById('cmd-value');
        this.btn = document.getElementById('run-command');
        this.cont = document.getElementById('response');

        this.input.addEventListener('keydown', e => {
            if (e.code === 'Enter')
            {
                this.store.put(this.input.value);
                return this.send();
            }

            if (e.code === 'ArrowUp')
            {
                return this.readAndSetHistory();
            }

            // if (e.code === 'ArrowDown') -- For the future!™
            // {
            //     return this.readAndSetHistory(true);
            // }
        });

        this.btn.addEventListener('click', () => {
            this.store.put(this.input.value);
            this.send();
        });
    }

    send () {
        if (this.isExecuting)
            return;

        this.setExecuting();
        this.toggleState();

        const serverId = document.getElementById('server-id').value;
        axios.post(RconPluginEndpoint + serverId, {
            'cmd': this.input.value
        }).then(r => this.process(r))
            .catch(r => this.fail(r))
            .finally(() => {
               this.toggleState();
               this.setDone();
               this.input.value = '';
               this.cont.scrollTop = this.cont.scrollHeight;
               this.input.focus();
            });
    }

    process (response) {
        this.cont.innerHTML += `<span class='text-success'>→ <b>${this.input.value}</b></span> ↴<br/>`;
        this.cont.innerHTML += `${response.data}<br/>`;
    }

    fail(err) {
        this.cont.innerHTML += `<span class='text-warning'>→ <b>${this.input.value}</b></span> ↴<br/>`;
        this.cont.innerHTML += `${err.response.data.error}<br/>`;
    }

    /**
     * Toggle disabled for input and button.
     */
    toggleState() {
        this.btn.toggleAttribute('disabled');
        this.input.toggleAttribute('disabled');
    }

    setExecuting () {
        this.isExecuting = true;
    }

    setDone () {
        this.isExecuting = false;
    }

    readAndSetHistory(reverse) {
        // if (typeof reverse === 'undefined')
        //     reverse = false;
        this.input.value = this.store.get();
    }
}

// Snippet from: https://stackoverflow.com/users/674374/mithun-satheesh
const store = {
    keyCount: 0,
    commandCount: 0,
    prevCommand: [],


    put : function(val) {
        this.commandCount++;
        this.keyCount = this.commandCount;
        this.prevCommand.push(val);
    },

    get : function() {
        this.keyCount--;
        if(typeof this.prevCommand[this.keyCount] !== "undefined") {
            return this.prevCommand[this.keyCount];
        }
        return '';
    }
}

window.addEventListener('load', () => {
    new RconPlugin().init(store);
});

// https://developer.mozilla.org/en-US/docs/web/api/element/toggleattribute
if (!Element.prototype.toggleAttribute) {
    Element.prototype.toggleAttribute = function(name, force) {
        if(force !== void 0) force = !!force

        if (this.hasAttribute(name)) {
            if (force) return true;

            this.removeAttribute(name);
            return false;
        }
        if (force === false) return false;

        this.setAttribute(name, "");
        return true;
    };
}
