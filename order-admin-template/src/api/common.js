import { post, get } from '@/utils/request'

export async function statusAction(model, item) {
  const action = item.status ? 'disabled' : 'enabled'
  const data = {
    id: item.id,
    action: action
  }
  return await post(model + '/status', data)
}

export async function deleteAction(model, id) {
  return await post(model + '/delete', { id: id })
}

export async function getSingleInfo(model, id) {
  return await get(model + '/info', { id: id })
}

export async function getDataLists(model, query) {
  return await get(model + '/lists', query)
}

export async function operateData(model, type, data) {
  return await post(model + '/' + type, data)
}

export async function getOtherData(model, type = 'label') {
  return await get(model + '/' + type)
}
