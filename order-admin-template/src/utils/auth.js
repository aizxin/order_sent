import Cookies from 'js-cookie'

const TokenKey = 'vue_admin_template_token'

export function getToken() {
  return Cookies.get(TokenKey)
}

export function setToken(token) {
  return Cookies.set(TokenKey, token)
}

export function removeToken() {
  return Cookies.remove(TokenKey)
}

export function filterDynamicRoutes(menuList = [], dynamicRoutes = []) {
  menuList.forEach(route => {
    const tmp = { ...route }
    console.log(tmp)
  })
  return dynamicRoutes
}
