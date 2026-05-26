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
        path: "/contract/:code",
        name: 'contract-simple',
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
        path: "/contract/draft/:code/:token",
        name: 'investor-draft-contract',
        component: () => import('../Pages/Draft.vue')
    },
    {
        path: "/public/contract/draft/:code/:token",
        name: 'investor-public-draft-contract',
        component: () => import('../Pages/Draft.vue')
    },
    {
        path: "/draft/:code",
        name: 'draft-code',
        component: () => import('../Pages/Draft.vue')
    },
  {
    path: "/pdf",
    name: 'pdf',
    component: () => import('../Pages/AhmedPrintPDF.vue')
  },
]

export default routes;
