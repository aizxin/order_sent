import store from '@/store'

export default {
  inserted(el, binding, vnode) {
    const { value } = binding
    const permissionRulesActions = store.getters.ruleAction
    if (value) {
      const hasPermission = permissionRulesActions.includes(value)

      if (!hasPermission) {
        el.parentNode && el.parentNode.removeChild(el)
      }
    } else {
      throw new Error(`need roles! Like v-permission="'add_admin'"`)
    }
  }
}
