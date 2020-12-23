<template>
  <div class="upload-container">
    <el-upload
      v-show="!voiceUrl"
      :accept="accept"
      :on-success="handleVoiceSuccess"
      :on-error="handleVoiceError"
      :before-upload="beforeUpload"
      :action="uploadUrl"
    >
      <el-button size="small" type="primary" class="upload-button">点击上传</el-button>
    </el-upload>
    <div v-show="voiceUrl" class="voice-preview">
      <audio :src="voiceUrl" controls="controls">
        您的浏览器不支持预览该音频。
      </audio>
      <i class="el-icon-delete" @click="deleteVoice" />
    </div>
  </div>
</template>

<script>
const BASE_API = process.env.VUE_APP_BASE_API

export default {
  name: 'VoiceUpload',
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
      accept: 'voice/mp3,voice/wave,voice/midi,voice/avi,voice/rmvb,voice/mov,voice/mp4',
      uploadUrl: BASE_API + 'upload/file'
    }
  },
  computed: {
    voiceUrl() {
      return this.value
    }
  },
  watch: {
    value: {
      handler(val) {
        console.log(val, 'vvvv')
      },
      deep: true,
      immediate: true
    }
  },
  methods: {
    deleteVoice() {
      this.emitInput('')
    },
    emitInput(val) {
      this.$emit('input', val)
    },
    handleVoiceSuccess(response) {
      this.$loading().close()
      const { url } = response.data
      this.emitInput(url)
    },
    handleVoiceError(response) {
      this.$loading().close()
      this.$message.error('上传失败,请重试')
    },
    beforeUpload(file) {
      const isLt30M = file.size / 1024 / 1024 < 30
      if (!isLt30M) {
        this.$message.error('上传音频大小不能超过 30M!')
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

<style lang="scss" scoped>
.voice-preview {
  display: flex;
  align-items: center;

  .el-icon-delete {
    font-size: 25px;
    color: #F56C6C;
    margin-left: 20px;
  }
}

</style>
