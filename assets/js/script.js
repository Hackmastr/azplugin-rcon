class RconPlugin {
    init () {
        this.isExecuting = false;
        this.input = $('#cmd-value');
        this.btn = $('#run-command');
        this.cont = $('#response');

        this.input.on('keypress', (e) => {
           if (e.which === 13)
               this.send();
        });

        this.btn.on('click', () => {
            this.send();
        });
    }

    send () {
        if (this.isExecuting)
            return;

        this.setExecuting();
        this.toggleState(false);

        const serverId = $('#server-id').val();
        axios.post(RconPluginEndpoint + serverId, {
            'cmd': this.input.val()
        }).then(r => this.process(r))
            .catch(r => this.fail(r))
            .finally(() => {
               this.toggleState(false);
               this.setDone();
               this.input.val('');
               this.cont.scrollTop(this.cont[0].clientHeight);
            });
    }

    process (response) {
        this.cont.append(`<span class='text-success'>→ <b>${this.input.val()}</b></span> ↴<br/>`);
        this.cont.append(`${response.data}<br/>`);
    }

    fail(err) {
        this.cont.append(`<span class='text-warning'>→ <b>${this.input.val()}</b></span> ↴<br/>`);
        this.cont.append(`${err.response.data.error}<br/>`);
    }

    /**
     * Toggle disabled for input and button.
     * @param {boolean} disabled
     */
    toggleState(disabled) {
        if (typeof disabled === 'undefined')
            disabled = false;

        this.btn.attr('disabled', disabled);
        this.input.attr('disabled', disabled);
    }

    setExecuting () {
        this.isExecuting = true;
    }

    setDone () {
        this.isExecuting = false;
    }
}


window.addEventListener('load', () => {
    new RconPlugin().init();
});
