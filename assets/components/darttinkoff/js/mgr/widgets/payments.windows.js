dartTinkoff.window.UpdatePayment = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'darttinkoff-payment-window-update';
    }
    Ext.applyIf(config, {
        title: _('darttinkoff_payment_update'),
        width: 550,
        autoHeight: true,
        url: dartTinkoff.config.connector_url,
        action: 'mgr/payments/update',
        fields: this.getFields(config),
        keys: [{
            key: Ext.EventObject.ENTER, shift: true, fn: function () {
                this.submit()
            }, scope: this
        }]
    });
    dartTinkoff.window.UpdatePayment.superclass.constructor.call(this, config);
};
Ext.extend(dartTinkoff.window.UpdatePayment, MODx.Window, {

    getFields: function (config) {
        return [{
            xtype: 'hidden',
            name: 'id',
            id: config.id + '-id',
        }, {
            xtype: 'statictextfield',
            fieldLabel: _('darttinkoff_payment_payment_id'),
            name: 'payment_id',
            id: config.id + '-payment_id',
            anchor: '99%',
            readOnly: true,
            allowBlank: false,
        }, {
            xtype: 'statictextfield',
            fieldLabel: _('darttinkoff_payment_status'),
            name: 'status',
            id: config.id + '-status',
            anchor: '99%',
            readOnly: true,
            allowBlank: false,
        }, {
            xtype: 'statictextfield',
            fieldLabel: _('darttinkoff_payment_payment_url'),
            name: 'payment_url',
            id: config.id + '-payment_url',
            anchor: '99%',
            readOnly: true,
            allowBlank: false,
        }, {
            xtype: 'statictextfield',
            fieldLabel: _('darttinkoff_payment_amount'),
            name: 'amount',
            id: config.id + '-amount',
            anchor: '99%',
            readOnly: true,
            allowBlank: false,
        }, {
            xtype: 'statictextfield',
            fieldLabel: _('darttinkoff_payment_card_id'),
            name: 'card_id',
            id: config.id + '-card_id',
            anchor: '99%',
            readOnly: true,
            allowBlank: false,
        }, {
            xtype: 'statictextfield',
            fieldLabel: _('darttinkoff_payment_pan'),
            name: 'pan',
            id: config.id + '-pan',
            anchor: '99%',
            readOnly: true,
            allowBlank: false,
        }, {
            xtype: 'statictextfield',
            fieldLabel: _('darttinkoff_payment_exp_date'),
            name: 'exp_date',
            id: config.id + '-exp_date',
            anchor: '99%',
            readOnly: true,
            allowBlank: false,
        }, {
            xtype: 'textarea',
            fieldLabel: _('darttinkoff_payment_description'),
            name: 'description',
            id: config.id + '-description',
            anchor: '99%',
            height: 150,
        }];
    },

    loadDropZones: function () {
    }

});
Ext.reg('darttinkoff-payment-window-update', dartTinkoff.window.UpdatePayment);