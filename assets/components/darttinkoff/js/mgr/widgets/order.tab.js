Ext.ComponentMgr.onAvailable('minishop2-window-order-update', function () {
    this.fields.items.push({
        xtype: 'darttinkoff-grid-payments',
        title: _('darttinkoff_order_payments_tab'),
        order_id: this.record.id || 0,
    });
});