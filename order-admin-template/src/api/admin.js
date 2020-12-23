import { post, get } from '@/utils/request'

/**
 * 获取管理员列表
 * @param query
 * @returns {AxiosPromise}
 */
export async function adminLists(query) {
  return await get('admin/lists', query)
}

/**
 * 获取管理员信息
 * @param query
 * @returns {AxiosPromise}
 */
export async function adminInfo(query) {
  return await get('admin/info', query)
}

/**
 * 新增管理员
 * @param query
 * @returns {AxiosPromise}
 */
export function adminAdd(query) {
  return post('admin/add', query)
}

/**
 * 编辑管理员
 * @param query
 * @returns {AxiosPromise}
 */
export function adminEdit(query) {
  return post('admin/edit', query)
}

/**
 * 管理员状态操作
 * @param query
 * @returns {AxiosPromise}
 */
export function adminStatus(query) {
  return post('admin/status', query)
}

/**
 * 管理员删除
 * @param query
 * @returns {AxiosPromise}
 */
export function adminDelete(query) {
  return post('admin/delete', query)
}

/**
 * 管理员修改
 * @param query
 * @returns {AxiosPromise}
 */
export function adminChangePassword(query) {
  return post('admin/changePassword', query)
}
