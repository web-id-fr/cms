Nova.booting((Vue, router) => {
    router.addRoutes([
        {
            name: 'menu-tool',
            path: '/menu-tool',
            component: require('./components/Tool'),
        },
    ])
});