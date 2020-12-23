import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

/* Layout */
import Layout from '@/layout'

/**
 * Note: sub-menu only appear when route children.length >= 1
 * Detail see: https://panjiachen.github.io/vue-element-admin-site/guide/essentials/router-and-nav.html
 *
 * hidden: true                   if set true, item will not show in the sidebar(default is false)
 * alwaysShow: true               if set true, will always show the root menu
 *                                if not set alwaysShow, when item has more than one children route,
 *                                it will becomes nested mode, otherwise not show the root menu
 * redirect: noRedirect           if set noRedirect will no redirect in the breadcrumb
 * name:'router-name'             the name is used by <keep-alive> (must set!!!)
 * meta : {
    roles: ['admin','editor']    control the page roles (you can set multiple roles)
    title: 'title'               the name show in sidebar and breadcrumb (recommend set)
    icon: 'svg-name'             the icon show in the sidebar
    breadcrumb: false            if set false, the item will hidden in breadcrumb(default is true)
    activeMenu: '/example/list'  if set path, the sidebar will highlight the path you set
  }
 */

/**
 * constantRoutes
 * a base page that does not have permission requirements
 * all roles can be accessed
 */
export const constantRoutes = [
  {
    path: '/login',
    component: () => import('@/views/login/index'),
    hidden: true
  },
  {
    path: '/404',
    component: () => import('@/views/404'),
    hidden: true
  },
  {
    path: '/error',
    component: Layout,
    redirect: 'noRedirect',
    name: 'ErrorPages',
    hidden: true,
    meta: {
      title: '页面错误',
      icon: '404'
    },
    children: [
      {
        path: '401',
        component: () => import('@/views/error-page/401'),
        name: 'Page401',
        meta: { title: '401', noCache: true }
      },
      {
        path: '404',
        component: () => import('@/views/error-page/404'),
        name: 'Page404',
        meta: { title: '404', noCache: true }
      }
    ]
  }
  // {
  //   path: '/hospital',
  //   component: () => import('@/layout'),
  //   // name: 'hospital',
  //   meta: { title: '医院管理', icon: 'example' },
  //   children: [
  //     {
  //       path: 'cate1',
  //       name: 'cate1',
  //       component: () => import('@/views/hospital/cate/index'),
  //       meta: { title: '医院分类', icon: 'table' }
  //     }
  //     // {
  //     //   path: 'cate2',
  //     //   name: 'cate2',
  //     //   component: () => import('@/views/hospital/cate/index'),
  //     //   meta: { title: '医院分类', icon: 'table' }
  //     // }
  //   ]
  // }
  // {
  //   path: '/',
  //   component: () => import('@/layout'),
  //   redirect: '/dashboard',
  //   children: [{
  //     path: 'dashboard',
  //     name: 'Dashboard',
  //     component: () => import('@/views/dashboard/index'),
  //     meta: { title: '首页', icon: 'dashboard' }
  //   }]
  // },
  // {
  //   path: '/basic',
  //   component: () => import('@/layout'),
  //   // name: 'basic',
  //   meta: { title: '基础管理', icon: 'example' },
  //   children: [
  //     {
  //       path: 'admin',
  //       name: 'Admin',
  //       component: () => import('@/views/base/admin/index'),
  //       meta: { title: '管理员', icon: 'table' }
  //     },
  //     {
  //       path: 'role',
  //       name: 'Role',
  //       component: () => import('@/views/base/role/index'),
  //       meta: { title: '角色', icon: 'eye-open' }
  //     },
  //     {
  //       path: 'rule',
  //       name: 'Rule',
  //       component: () => import('@/views/base/rule/index'),
  //       meta: { title: '权限', icon: 'tree' }
  //     },
  //     {
  //       path: 'action',
  //       name: 'ActionLog',
  //       component: () => import('@/views/base/action_log/index'),
  //       meta: { title: '行为', icon: 'nested' }
  //     },
  //     {
  //       path: 'log',
  //       name: 'AdminLog',
  //       component: () => import('@/views/base/admin_log/index'),
  //       meta: { title: '日志', icon: 'form' }
  //     }
  //   ]
  // }
]

const originalPush = Router.prototype.push
Router.prototype.push = function push(location) {
  return originalPush.call(this, location).catch(err => err)
}

const createRouter = () => new Router({
  // mode: 'history', // require service support
  scrollBehavior: () => ({ y: 0 }),
  routes: constantRoutes
})

const router = createRouter()

// Detail see: https://github.com/vuejs/vue-router/issues/1234#issuecomment-357941465
export function resetRouter() {
  const newRouter = createRouter()
  router.matcher = newRouter.matcher // reset router
}

export default router
