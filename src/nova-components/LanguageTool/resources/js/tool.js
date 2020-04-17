Nova.booting((Vue, router) => {
    router.addRoutes([
        {
            name: 'language-tool',
            path: '/language-tool',
            component: require('./components/Tool'),
        },
    ])
});