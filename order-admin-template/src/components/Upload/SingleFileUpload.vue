<template>
  <div class="upload-container">
    <el-upload
      :action="uploadUrl"
      :on-success="handleImageSuccess"
      :on-error="handleImageError"
      :before-upload="beforeUpload"
      class="image-uploader"
    >
      <el-button size="small" type="primary">点击上传</el-button>
    </el-upload>
  </div>
</template>

<script>
const BASE_API = process.env.VUE_APP_BASE_API

export default {
  name: 'SingleFileUpload',
  props: {
    value: {
      type: String,
      default() {
        return ''
      }
    }
  },
  data() {
    return {
      uploadUrl: BASE_API + 'upload/file'
    }
  },
  methods: {
    emitInput(val) {
      this.$emit('input', val)
    },
    handleImageSuccess(response, file) {
      this.$loading().close()
      const { url } = response.data
      this.emitInput(url)
    },
    handleImageError(response) {
      this.$loading().close()
      this.$message.error('上传失败,请重试')
    },
    beforeUpload(file) {
      const isLt5M = file.size / 1024 / 1024 < 500
      if (!isLt5M) {
        this.$message.error('上传文件大小不能超过 500M!')
        return false
      } else {
        this.$loading({
          lock: true,
          text: '上传中',
          spinner: 'el-icon-loading',
          background: 'rgba(0, 0, 0, 0.7)'
        })
      }
    }
  }
}
</script>

<style lang='scss' scoped>
</style>
