/**
 * 多层 json 数据嵌套还原成一层
 * @param json
 * @param children
 * @returns {*|T[]|string}
 */
export function treeToList(json, children = 'children') {
  var newJson = json.concat([])
  var len = newJson.length
  // console.log('newJson', newJson)
  for (var i = 0; i < len; i++) {
    var item = newJson[i]
    if (item[children] && item[children].length !== 0) {
      var child = item[children]
      for (var j = 0; j < child.length; j++) {
        if (item.parentNode) {
          child[j].parentNode = item.parentNode.concat([item.id])
        } else {
          child[j].parentNode = [item.id]
        }
        newJson[len + j] = child[j]
      }
      len = newJson.length
    }
  }
  return newJson
}

/**
 * 根据子级ID  获取所有父级 ID
 * @param json
 * @param id
 * @returns {Array}
 */
export function searchToTree(json, id) {
  var newJson = json.concat([])
  var len = newJson.length
  var parentNode = []
  for (var s = 0; s < len; s++) {
    if (newJson[s].id === id) {
      if (newJson[s].parentNode == null || newJson[s].parentNode.length === 0) {
        parentNode = [newJson[s].id]
      } else {
        parentNode = newJson[s].parentNode.concat([id])
      }
    } else {
      continue
    }
  }
  return parentNode
}

/**
 * 数组去重
 * @param arr
 * @returns {Array}
 */
export function uniqueToList(arr) {
  return arr.filter(function(item, index, arr) {
    return arr.indexOf(item, 0) === index
  })
}

export function listToTree(oldArr) {
  oldArr.forEach(element => {
    const parentId = element.pid
    if (parentId !== 0) {
      oldArr.forEach(ele => {
        if (ele.id === parentId) {
          if (!ele.children) {
            ele.children = []
          }
          ele.children.push(element)
        }
      })
    }
  })
  oldArr = oldArr.filter(ele => ele.pid === 0)
  return oldArr
}
