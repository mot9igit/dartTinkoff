dartTinkoff.panel.OrderPayments = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'shoplogistic-order-payments-panel';
    }
    Ext.applyIf(config, {
        layout: 'form',
        cls: 'main-wrapper',
        defaults: {msgTarget: 'under', border: false},
        anchor: '100% 100%',
        border: false,
        url: shopLogistic.config.connector_url,
        items: this.getFields(config),
        listeners: {
            afterrender: function() {
                this.getData(config)
            }
        },
        pageSize:10,
        paging: true,
        remoteSort: true,
        autoHeight: true
    });

    dartTinkoff.panel.OrderPayments.superclass.constructor.call(this, config);
};

Ext.extend(dartTinkoff.panel.OrderPayments, MODx.Panel, {

});
Ext.reg('darttinkoff-order-payments-panel', dartTinkoff.panel.OrderPayments);