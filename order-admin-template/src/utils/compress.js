class Compress {
  photoCompress(file, cb) {
    const ready = new FileReader()
    const self = this
    return new Promise((resolve, reject) => {
      ready.readAsDataURL(file)
      ready.onload = function() {
        const path = this.result
        const img = new Image()
        img.src = path
        img.onload = function() {
          const that = this
          // 默认按比例压缩
          const w = that.width
          let h = that.height
          const scale = w / h
          h = (w / scale)
          // 默认图片质量为0.5
          const quality = 0.5
          // 生成canvas
          const canvas = document.createElement('canvas')
          const ctx = canvas.getContext('2d')
          // 创建属性节点
          const anw = document.createAttribute('width')
          anw.nodeValue = w
          const anh = document.createAttribute('height')
          anh.nodeValue = h
          canvas.setAttributeNode(anw)
          canvas.setAttributeNode(anh)
          ctx.drawImage(that, 0, 0, w, h)
          // quality值越小，所绘制出的图像越模糊
          const files = self.dataURLtoFile(canvas.toDataURL('image/jpeg', quality), file.name)
          resolve(files)
        }
        img.onerror = () => {
          console.error('图片错误')
        }
      }
    })
  }

  dataURLtoFile(dataurl, filename) {
    const arr = dataurl.split(',')
    const mime = arr[0].match(/:(.*?);/)[1]
    const bstr = atob(arr[1])
    let n = bstr.length
    const u8arr = new Uint8Array(n)
    while (n--) {
      u8arr[n] = bstr.charCodeAt(n)
    }
    return new File([u8arr], filename, { type: mime })
  }

  canvasDataURL(path, name) {
    const self = this
    const img = new Image()
    img.src = path
    img.onload = function() {
      const that = this
      // 默认按比例压缩
      const w = that.width
      let h = that.height
      const scale = w / h
      h = (w / scale)
      // 默认图片质量为0.5
      const quality = 0.5
      // 生成canvas
      const canvas = document.createElement('canvas')
      const ctx = canvas.getContext('2d')
      // 创建属性节点
      const anw = document.createAttribute('width')
      anw.nodeValue = w
      const anh = document.createAttribute('height')
      anh.nodeValue = h
      canvas.setAttributeNode(anw)
      canvas.setAttributeNode(anh)
      ctx.drawImage(that, 0, 0, w, h)
      // quality值越小，所绘制出的图像越模糊
      const base64 = self.dataURLtoFile(canvas.toDataURL('image/jpeg', quality), name)
      console.log(base64)
      self.newFile = base64
    }
  }

  convertBase64UrlToBlob(urlData) {
    const arr = urlData.split(',')
    const mime = arr[0].match(/:(.*?);/)[1]
    const bstr = atob(arr[1])
    let n = bstr.length
    const u8arr = new Uint8Array(n)
    while (n--) {
      u8arr[n] = bstr.charCodeAt(n)
    }
    return new Blob([u8arr], { type: mime })
  }
}

export default Compress
