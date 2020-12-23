<template>
  <div class="upload-container">
    <el-upload
      :action="uploadUrl"
      :show-file-list="true"
      :on-success="handleImageSuccess"
      :on-error="handleImageError"
      :on-remove="handleRemove"
      :before-upload="beforeUpload"
      class="image-uploader"
      :accept="accept"
      :file-list="imageLists"
      list-type="picture-card"
    >
      <i slot="default" class="el-icon-plus" style="color: #409EFF" />
    </el-upload>
  </div>
</template>

<script>
const BASE_API = process.env.VUE_APP_BASE_API
import Compress from '@/utils/compress'

export default {
  name: 'MultipleImageUpload',
  props: {
    value: {
      type: Array,
      default() {
        return []
      }
    }
  },
  data() {
    return {
      accept: 'image/png,image/jpg,image/jpeg,image/pjpeg,image/gif,image/webp',
      uploadUrl: BASE_API + '/upload/img',
      imgQuality: 0.5
    }
  },
  computed: {
    imageLists() {
      return this.value.map((item) => {
        return {
          name: item,
          url: item
        }
      })
    }
  },
  methods: {
    emitInput(val) {
      this.$emit('input', val)
    },
    handleRemove(file, fileList) {
      const tempFileList = fileList.map(item => {
        return item.url
      })
      this.emitInput(tempFileList)
    },
    handleImageSuccess(response, file) {
      this.$loading().close()
      const { url } = response.data
      this.value.push(url)
      this.emitInput(this.value)
    },
    handleImageError(response) {
      this.$loading().close()
      this.$message.error('上传失败,请重试')
    },
    beforeUpload(file) {
      const isLt5M = file.size / 1024 / 1024 < 5
      if (!isLt5M) {
        this.$message.error('上传图片大小不能超过 5M!')
        return false
      } else {
        this.$loading({
          lock: true,
          text: '上传中',
          spinner: 'el-icon-loading',
          background: 'rgba(0, 0, 0, 0.7)'
        })
        try {
          const img = new Compress()
          return img.photoCompress(file)
        } catch (e) {
          return file
        }
      }
    }
  }
}
</script>

<style lang='scss' scoped>
</style>
