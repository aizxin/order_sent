<template>
  <div class="upload-container">
    <el-upload
      v-show="!imageUrl"
      :accept="accept"
      :multiple="false"
      :show-file-list="false"
      :on-success="handleImageSuccess"
      :on-error="handleImageError"
      :before-upload="beforeUpload"
      class="image-uploader"
      drag
      :action="uploadUrl"
    >
      <i class="el-icon-upload" />
      <div class="el-upload__text">
        <em>点击上传</em>
      </div>
    </el-upload>
    <div v-show="imageUrl" class="image-preview">
      <div class="image-preview-wrapper">
        <img :src="imageUrl">
        <div class="image-preview-action">
          <i class="el-icon-delete" @click="rmImage" />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
const BASE_API = process.env.VUE_APP_BASE_API
import Compress from '@/utils/compress'

export default {
  name: 'SingleImageUpload',
  props: {
    value: {
      type: String,
      default: ''
    }
  },
  data() {
    return {
      accept: 'image/png,image/jpg,image/jpeg,image/pjpeg,image/gif,image/webp',
      uploadUrl: BASE_API + '/upload/img',
      tempUrl: '',
      imgQuality: 0.5
    }
  },
  computed: {
    imageUrl() {
      return this.value
    }
  },
  methods: {
    rmImage() {
      this.emitInput('')
    },
    emitInput(val) {
      this.$emit('input', val)
      this.$emit('success', val)
    },
    handleImageSuccess(response) {
      this.$loading().close()
      const { url } = response.data
      this.tempUrl = url
      this.emitInput(url)
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

<style lang="scss" scoped>
@import "~@/styles/mixin.scss";

.upload-container {
  width: 100%;
  position: relative;
  @include clearfix;

  .image-uploader {
    width: 200px;
    float: left;
  }

  .image-preview {
    width: 200px;
    height: 200px;
    position: relative;
    border: 1px dashed #d9d9d9;
    float: left;

    .image-preview-wrapper {
      position: relative;
      width: 100%;
      height: 100%;

      img {
        width: 100%;
        height: 100%;
      }
    }

    .image-preview-action {
      position: absolute;
      width: 100%;
      height: 100%;
      left: 0;
      top: 0;
      cursor: default;
      text-align: center;
      color: #fff;
      opacity: 0;
      font-size: 20px;
      background-color: rgba(0, 0, 0, .5);
      transition: opacity .3s;
      cursor: pointer;
      text-align: center;
      line-height: 200px;

      .el-icon-delete {
        font-size: 36px;
      }
    }

    &:hover {
      .image-preview-action {
        opacity: 1;
      }
    }
  }
}

</style>
