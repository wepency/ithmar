const routes = [
    {
        path: "/public/investor/:id/contract/:code",
        name: 'public-investor-contract',
        component: () => import('../Pages/Contract.vue')
    },
    {
        path: "/investor/:id/contract/:code",
        name: 'investor-contract',
        component: () => import('../Pages/Contract.vue')
    },
    {
        path: "/public/contract/:code/:token",
        name: 'public-token-contract',
        component: () => import('../Pages/Contract.vue')
    },
    {
        path: "/contract/:code/:token",
        name: 'contract',
        component: () => import('../Pages/Contract.vue')
    },
    {
        path: "/dashboard/contract/show/:code",
        name: 'code-contract',
        component: () => import('../Pages/Contract.vue')
    },
    {
        path: "/public/dashboard/contract/show/:code",
        name: 'fatoorah-contract',
        component: () => import('../Pages/Contract.vue')
    },
    {
        path: "/myfatoorah/success",
        name: 'public-fatoorah-contract',
        component: () => import('../Pages/Contract.vue')
    },
  {
    path: "/pdf",
    name: 'pdf',
    component: () => import('../Pages/AhmedPrintPDF.vue')
  },
]

export default routes;
