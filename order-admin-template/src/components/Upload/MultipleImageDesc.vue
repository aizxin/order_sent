<template>
  <div class="mutiple-image-upload-container">
    <el-upload
      :accept="accept"
      :on-success="handleImageSuccess"
      :on-error="handleImageError"
      :on-remove="deleteImage"
      :before-upload="beforeUpload"
      list-type="picture"
      :action="uploadUrl"
    >
      <el-button size="small" type="primary" class="upload-button">点击上传</el-button>
    </el-upload>
    <div v-for="(item, index) in imgsList" :key="index" class="img-and-desc">
      <div>
        <img :src="item.url" alt="">
        <div class="img-preview-action" @click="deleteImage(index)">
          <i class="el-icon-delete" />
        </div>
      </div>
      <div>
        <span class="form-desc">图片描述(必填哦~)：</span>
        <el-input
          v-model="item.desc"
          type="textarea"
          :rows="3"
          placeholder="请输入内容"
        />
      </div>
    </div>
  </div>
</template>

<script>
const BASE_API = process.env.VUE_APP_BASE_API
import Compress from '../../utils/compress'

export default {
  name: 'SingleImageUpload',
  props: {
    value: {
      type: [String, Array],
      default() {
        return []
      }
    }
  },
  data() {
    return {
      accept: 'image/png,image/jpg,image/jpeg,image/pjpeg,image/gif,image/webp',
      uploadUrl: BASE_API + 'upload/img',
      imgQuality: 0.5,
      emitImgsList: []
    }
  },
  computed: {
    imgsList() {
      return this.value
    }
  },
  watch: {
    emitImgsList: {
      handler(val) {
        this.$emit('input', val)
      },
      deep: true
    }
  },
  methods: {
    /*
    * 删除图片
    * */
    deleteImage(index) {
      this.value.splice(index, 1)
      this.emitImgsList = this.value
    },
    handleImageSuccess(response) {
      this.$loading().close()
      const { url } = response.data
      this.value.push({ url: url })
      this.emitImgsList = this.value
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

<style lang="scss">
@import "~@/styles/mixin.scss";

.mutiple-image-upload-container {
  .el-upload-list__item {
    display: none;
  }

  .img-and-desc {
    display: flex;
    align-items: center;
    margin-top: 8px;

    div {
      &:first-child {
        width: 100px;
        min-width: 100px;
        height: 100px;
        margin-right: 15px;
        position: relative;

        img {
          width: 100%;
          height: 100%;
        }
      }

      &:last-child {
        width: 100%;
      }
    }

    .img-preview-action {
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
      line-height: 100px;

      .el-icon-delete {
        font-size: 26px;
      }

      &:hover {
        opacity: 1;
      }
    }
  }
}

</style>
