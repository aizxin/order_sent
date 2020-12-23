import { listToTree } from '@/utils/tree-data'
import { getOtherData } from '@/api/common'
import { constantRoutes } from '@/router'
import Layout from '@/layout'

export function _import(file) {
  if (process.env.NODE_ENV === 'development') {
    return require('@/views/' + file + '.vue').default
  } else {
    return () => import('@/views/' + file + '.vue')
  }
}

export function filterAsyncRoutes(accessedRouters) {
  accessedRouters.map(function(item) {
    item.component = Layout
    item.children.map(function(child) {
      child.component = _import(child.component)
    })
  })
  accessedRouters.push({ path: '*', redirect: '/404', hidden: true })
  return accessedRouters
}

const state = {
  routes: [],
  addRoutes: []
}

const mutations = {
  SET_ROUTES: (state, routes) => {
    state.addRoutes = routes
    state.routes = constantRoutes.concat(routes)
  }
}

const actions = {
  generateRoutes({ commit }) {
    return new Promise((resolve, reject) => {
      getOtherData('auth', 'menu').then(response => {
        const { data } = response
        if (!data) {
          reject('Verification failed, please Login again.')
        }
        const routers = filterAsyncRoutes(listToTree(data))
        commit('SET_ROUTES', routers)
        resolve(routers)
      }).catch(error => {
        reject(error)
      })
    })
  }
}

export default {
  namespaced: true,
  state,
  mutations,
  actions
}
