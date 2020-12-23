<template>
  <div class="upload-container">
    <el-upload
      v-if="!videoSrc"
      class="video-upload"
      :action="uploadUrl"
      :accept="accept"
      :on-error="handleVideoError"
      :on-success="handleVideoSuccess"
      :before-upload="beforeUploadVideo"
    >
      <el-button size="small" type="primary" class="upload-button">点击上传</el-button>
    </el-upload>
    <div v-if="videoSrc" class="image-preview">
      <div class="image-preview-wrapper">
        <video
          :src="videoSrc"
          class="video-player"
          controls="controls"
        >
          您的浏览器不支持视频播放
        </video>
        <div class="image-preview-action">
          <i class="el-icon-delete" @click="emitInput('')" />
        </div>
      </div>
    </div>
    <p class="form-desc">
      <span>最多可以上传1个视频，建议大小50M，推荐格式mp4</span>
    </p>
  </div>
</template>

<script>
const BASE_API = process.env.VUE_APP_BASE_API
export default {
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
      accept: 'video/mp4, video/ogg, video/flv, video/avi, video/wmv, video/rmvb, video/mov',
      uploadUrl: BASE_API + 'upload/file'
    }
  },
  computed: {
    videoSrc() {
      return this.value
    }
  },
  methods: {
    // 上传前
    beforeUploadVideo(file) {
      var fileSize = file.size / 1024 / 1024 < 500
      if (!fileSize) {
        this.$message.error('上传视频大小不能超过 500M!')
        return false
      } else {
        this.$loading({
          lock: true,
          text: '上传中',
          spinner: 'el-icon-loading',
          background: 'rgba(0, 0, 0, 0.7)'
        })
      }
    },
    // 上传成功
    handleVideoSuccess(response, file) {
      this.$loading().close()
      const { url } = response.data
      this.emitInput(url)
    },
    handleVideoError(response) {
      this.$loading().close()
      this.$message.error('上传失败,请重试')
    },
    emitInput(val) {
      this.$emit('input', val)
    }
  }
}
</script>

<style lang="scss" scoped>
@import "~@/styles/mixin.scss";

.image-preview {
  width: 300px;
  height: 225px;
  position: relative;
  border: 1px dashed #d9d9d9;

  .video-player {
    width: 100%;
    height: 100%;
  }

  .image-preview-wrapper {
    position: relative;
    width: 100%;
    height: 100%;
  }

  .image-preview-action {
    position: absolute;
    width: 100%;
    height: 20%;
    left: 0;
    top: 0;
    cursor: default;
    color: #fff;
    opacity: 0;
    font-size: 20px;
    background-color: rgba(0, 0, 0, .5);
    transition: opacity .3s;
    cursor: pointer;
    text-align: center;
    line-height: 200px;

    .el-icon-delete {
      position: absolute;
      top: 10px;
      right: 20px;
      font-size: 26px;
    }
  }

  &:hover {
    .image-preview-action {
      opacity: 1;
    }
  }
}

</style>
