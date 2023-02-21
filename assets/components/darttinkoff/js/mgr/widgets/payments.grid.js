dartTinkoff.grid.Payments = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'darttinkoff-grid-payments';
    }
    Ext.applyIf(config, {
        url: dartTinkoff.config.connector_url,
        fields: this.getFields(config),
        columns: this.getColumns(config),
        tbar: this.getTopBar(config),
        sm: new Ext.grid.CheckboxSelectionModel(),
        baseParams: {
            action: 'mgr/payments/getlist',
            order_id: config.order_id
        },
        listeners: {
            rowDblClick: function (grid, rowIndex, e) {
                var row = grid.store.getAt(rowIndex);
                this.updatePayment(grid, e, row);
            }
        },
        viewConfig: {
            forceFit: true,
            enableRowBody: true,
            autoFill: true,
            showPreview: true,
            scrollOffset: 0,
            getRowClass: function (rec) {
                return !rec.data.active
                    ? 'darttinkoff-grid-row-disabled'
                    : '';
            }
        },
        paging: true,
        remoteSort: true,
        autoHeight: true,
    });
    dartTinkoff.grid.Payments.superclass.constructor.call(this, config);

    // Clear selection on grid refresh
    this.store.on('load', function () {
        if (this._getSelectedIds().length) {
            this.getSelectionModel().clearSelections();
        }
    }, this);
};
Ext.extend(dartTinkoff.grid.Payments, MODx.grid.Grid, {
    windows: {},

    getMenu: function (grid, rowIndex) {
        var ids = this._getSelectedIds();

        var row = grid.getStore().getAt(rowIndex);
        var menu = dartTinkoff.utils.getMenu(row.data['actions'], this, ids);

        this.addContextMenuItem(menu);
    },

    getTopBar: function () {
        return [{
            xtype: 'button'
            ,text: '<i class="icon icon-plus"></i> ' + _('darttinkoff_create')
            ,listeners: {
                click: {fn: this.createPayment, scope:this}
            }
        }];
    },

    createPayment: function ()  {
        console.log(this.config);
        MODx.msg.confirm({
            title: _('darttinkoff_create')
            ,text: _('darttinkoff_create_confirm')
            ,url: this.config.url
            ,params: {
                action: 'mgr/payments/create',
                order_id: this.config.order_id
            }
            ,listeners: {
                success: {
                    fn:function(r) {
                        this.refresh();
                        var title = 'Операция выполнена';
                        var msg = 'Платеж создан';
                        Ext.MessageBox.alert(title,msg);
                    }, scope:this}
            }
        });
    },

    updatePayment: function (btn, e, row)  {
        if (typeof(row) != 'undefined') {
            this.menu.record = row.data;
        }
        else if (!this.menu.record) {
            return false;
        }
        var id = this.menu.record.id;

        MODx.Ajax.request({
            url: this.config.url,
            params: {
                action: 'mgr/payments/get',
                id: id
            },
            listeners: {
                success: {
                    fn: function (r) {
                        var w = MODx.load({
                            xtype: 'darttinkoff-payment-window-update',
                            id: Ext.id(),
                            record: r,
                            listeners: {
                                success: {
                                    fn: function () {
                                        this.refresh();
                                    }, scope: this
                                }
                            }
                        });
                        w.reset();
                        w.setValues(r.object);
                        w.show(e.target);
                    }, scope: this
                }
            }
        });
    },
    removePayment: function () {
        var ids = this._getSelectedIds();
        if (!ids.length) {
            return false;
        }
        MODx.msg.confirm({
            title: ids.length > 1
                ? _('darttinkoff_payments_remove')
                : _('darttinkoff_payment_remove'),
            text: ids.length > 1
                ? _('darttinkoff_payments_remove_confirm')
                : _('darttinkoff_payment_remove_confirm'),
            url: this.config.url,
            params: {
                action: 'mgr/payments/remove',
                ids: Ext.util.JSON.encode(ids),
            },
            listeners: {
                success: {
                    fn: function () {
                        this.refresh();
                    }, scope: this
                }
            }
        });
        return true;
    },
    cancelPayment: function () {
        var ids = this._getSelectedIds();
        if (!ids.length) {
            return false;
        }
        MODx.msg.confirm({
            title: ids.length > 1
                ? _('darttinkoff_payments_cancel')
                : _('darttinkoff_payment_cancel'),
            text: ids.length > 1
                ? _('darttinkoff_payments_cancel_confirm')
                : _('darttinkoff_payment_cancel_confirm'),
            url: this.config.url,
            params: {
                action: 'mgr/payments/cancel',
                ids: Ext.util.JSON.encode(ids),
            },
            listeners: {
                success: {
                    fn: function () {
                        this.refresh();
                    }, scope: this
                }
            }
        });
        return true;
    },

    checkPayment: function () {
        var ids = this._getSelectedIds();
        if (!ids.length) {
            return false;
        }
        MODx.msg.confirm({
            title: ids.length > 1
                ? _('darttinkoff_payments_check')
                : _('darttinkoff_payment_check'),
            text: ids.length > 1
                ? _('darttinkoff_payments_check_confirm')
                : _('darttinkoff_payment_check_confirm'),
            url: this.config.url,
            params: {
                action: 'mgr/payments/check',
                ids: Ext.util.JSON.encode(ids),
            },
            listeners: {
                success: {
                    fn: function () {
                        this.refresh();
                    }, scope: this
                }
            }
        });
        return true;
    },

    getFields: function () {
        return ['id', 'payment_id', 'order_id', 'status', 'payment_url', 'amount', 'createdon', 'updatedon', 'description', 'actions'];
    },

    getColumns: function () {
        return [{
            header: _('darttinkoff_payment_id'),
            dataIndex: 'id',
            sortable: true,
            width: 70
        }, {
            header: _('darttinkoff_payment_payment_id'),
            dataIndex: 'payment_id',
            sortable: true,
            width: 200,
        }, {
            header: _('darttinkoff_payment_status'),
            dataIndex: 'status',
            sortable: true,
            width: 200,
        }, {
            header: _('darttinkoff_payment_payment_url'),
            dataIndex: 'payment_url',
            sortable: true,
            width: 200,
        }, {
            header: _('darttinkoff_payment_amount'),
            dataIndex: 'amount',
            sortable: true,
            width: 200,
        }, {
            header: _('darttinkoff_grid_actions'),
            dataIndex: 'actions',
            renderer: dartTinkoff.utils.renderActions,
            sortable: false,
            width: 100,
            id: 'actions'
        }];
    },

    onClick: function (e) {
        var elem = e.getTarget();
        if (elem.nodeName == 'BUTTON') {
            var row = this.getSelectionModel().getSelected();
            if (typeof(row) != 'undefined') {
                var action = elem.getAttribute('action');
                if (action == 'showMenu') {
                    var ri = this.getStore().find('id', row.id);
                    return this._showMenu(this, ri, e);
                }
                else if (typeof this[action] === 'function') {
                    this.menu.record = row.data;
                    return this[action](this, e);
                }
            }
        }
        return this.processEvent('click', e);
    },

    _getSelectedIds: function () {
        var ids = [];
        var selected = this.getSelectionModel().getSelections();

        for (var i in selected) {
            if (!selected.hasOwnProperty(i)) {
                continue;
            }
            ids.push(selected[i]['id']);
        }

        return ids;
    },
});
Ext.reg('darttinkoff-grid-payments', dartTinkoff.grid.Payments);
