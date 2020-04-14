Nova.booting((Vue, router) => {
    router.addRoutes([
        {
            name: 'component-tool',
            path: '/component-tool',
            component: require('./components/Tool'),
        },
    ])
});
