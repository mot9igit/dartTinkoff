dartTinkoff.page.Home = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        components: [{
            xtype: 'darttinkoff-panel-home',
            renderTo: 'darttinkoff-panel-home-div'
        }]
    });
    dartTinkoff.page.Home.superclass.constructor.call(this, config);
};
Ext.extend(dartTinkoff.page.Home, MODx.Component);
Ext.reg('darttinkoff-page-home', dartTinkoff.page.Home);