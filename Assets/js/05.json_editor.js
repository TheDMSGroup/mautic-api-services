// Extend the bootstrap3 theme with some minor aesthetic customizations.
function levenshtein (a, b) {
    var tmp;
    if (a.length === 0) { return b.length; }
    if (b.length === 0) { return a.length; }
    if (a.length > b.length) {
        tmp = a;
        a = b;
        b = tmp;
    }

    var i, j, res, alen = a.length, blen = b.length, row = Array(alen);
    for (i = 0; i <= alen; i++) { row[i] = i; }

    for (i = 1; i <= blen; i++) {
        res = i;
        for (j = 1; j <= alen; j++) {
            tmp = row[j - 1];
            row[j - 1] = res;
            res = b[i - 1] === a[j - 1] ? tmp : Math.min(tmp + 1, Math.min(res + 1, row[j] + 1));
        }
    }
    return res;
}

JSONEditor.defaults.themes.custom = JSONEditor.defaults.themes.bootstrap3.extend({
    // Support bootstrap-slider.
    getRangeInput: function (min, max, step) {
        var el = this._super(min, max, step);
        el.className = el.className.replace('form-control', '');
        return el;
    },
    // Make the buttons smaller and more consistent.
    getButton: function (text, icon, title) {
        var el = this._super(text, icon, title);
        if (title.indexOf('Delete') !== -1) {
            el.className = el.className.replace('btn-default', 'btn-sm btn-xs btn-danger');
        }
        else if (title.indexOf('Add') !== -1) {
            el.className = el.className.replace('btn-default', 'btn-md btn-primary');
        }
        else {
            el.className = el.className.replace('btn-default', 'btn-sm btn-xs btn-secondary');
        }
        return el;
    },
    // Pull header nav to the right.
    getHeaderButtonHolder: function () {
        var el = this.getButtonHolder();
        el.className = 'btn-group btn-group-sm btn-right';
        return el;
    },
    // Pull "new item" buttons to the left.
    getButtonHolder: function () {
        var el = document.createElement('div');
        el.className = 'btn-group btn-left';
        return el;
    },
    // Make the h3 elements clickable.
    getHeader: function (text) {
        var el = document.createElement('h3');
        el.onclick = function (e) {
            e.preventDefault();
            e.stopPropagation();
            var $collapseButton = mQuery(this).find('> div.btn-group > button.json-editor-btn-collapse:first:visible');
            if ($collapseButton.length) {
                var el = $collapseButton[0];
                if (el) {
                    var event = new MouseEvent('click', {
                        'view': window,
                        'bubbles': false,
                        'cancelable': true
                    });
                    el.dispatchEvent(event);
                }
            }
        };
        // el.style.cursor = 'pointer';
        if (typeof text === 'string') {
            el.textContent = text;
        }
        else {
            el.appendChild(text);
        }
        return el;
    }
});
// Extend the fontawesome icon kit for minor changes.
JSONEditor.defaults.iconlibs.custom = JSONEditor.AbstractIconLib.extend({
    mapping: {
        collapse: 'caret-down',
        expand: 'caret-right',
        delete: 'times',
        edit: 'pencil',
        add: 'plus',
        cancel: 'ban',
        save: 'save',
        moveup: 'arrow-up',
        movedown: 'arrow-down'
    },
    icon_prefix: 'fa fa-'
});

// Override default settings.
JSONEditor.defaults.options.ajax = false;
JSONEditor.defaults.options.theme = 'custom';
JSONEditor.defaults.options.iconlib = 'custom';
JSONEditor.defaults.options.disable_edit_json = true;
JSONEditor.defaults.options.disable_properties = true;
JSONEditor.defaults.options.disable_array_delete_all_rows = true;
JSONEditor.defaults.options.disable_array_delete_last_row = true;
JSONEditor.defaults.options.remove_empty_properties = false;
JSONEditor.defaults.options.required_by_default = true;
JSONEditor.defaults.options.expand_height = true;
JSONEditor.defaults.options.keep_oneof_values = false;

// Exclusive field.
Mautic.apiService_json_editor = function () {
    var $featureSettings = mQuery('#integration_details_featureSettings_api_services_settings:first:not(.exclusive-checked)');
    if ($featureSettings.length) {
        var apiServiceJSONEditor;
        console.log('Get Schema');
        // Grab the JSON Schema to begin rendering the form with JSONEditor.
        mQuery.ajax({
            dataType: 'json',
            cache: true,
            url: mauticBasePath + '/' + mauticAssetPrefix + 'plugins/MauticApiServicesBundle/Assets/json/apiservice.json',
            success: function (data) {
                console.log(data);
                var schema = data;

                // Create our widget container for the JSON Editor.
                var $apiServiceJSONEditor = mQuery('<div>', {
                    class: 'apiservices_jsoneditor'
                }).insertBefore($featureSettings);

                // Instantiate the JSON Editor based on our schema.
                apiServiceJSONEditor = new JSONEditor($apiServiceJSONEditor[0], {
                    schema: schema,
                    disable_collapse: true
                });

                $featureSettings.change(function () {
                    // Load the initial value if applicable.
                    var raw = mQuery(this).val(),
                        obj;
                    if (raw.length) {
                        try {
                            obj = mQuery.parseJSON(raw);
                            if (typeof obj === 'object') {
                                apiServiceJSONEditor.setValue(obj);
                            }
                        }
                        catch (e) {
                            console.warn(e);
                        }
                    }
                }).trigger('change');

                // Persist the value to the JSON Editor.
                apiServiceJSONEditor.on('change', function () {
                    var obj = apiServiceJSONEditor.getValue();
                    if (typeof obj === 'object') {
                        var raw = JSON.stringify(obj, null, 2);
                        if (raw.length) {
                            // Set the textarea.
                            $featureSettings.val(raw);
                        }
                    }
                });

                $featureSettings.addClass('exclusive-checked');
                $apiServiceJSONEditor.show();
            }
        });

    }
};
