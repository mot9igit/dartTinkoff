var dartTinkoff = function (config) {
    config = config || {};
    dartTinkoff.superclass.constructor.call(this, config);
};
Ext.extend(dartTinkoff, Ext.Component, {
    page: {}, window: {}, grid: {}, tree: {}, panel: {}, combo: {}, config: {}, view: {}, utils: {}
});
Ext.reg('darttinkoff', dartTinkoff);

dartTinkoff = new dartTinkoff();