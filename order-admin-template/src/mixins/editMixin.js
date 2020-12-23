import { getSingleInfo, operateData, getOtherData } from '@/api/common'

export default {
  props: {
    value: {
      required: true,
      type: [Number, String]
    }
  },
  data() {
    return {
      editTitle: 'add',
      dialogFormVisible: false,
      editData: {}
    }
  },
  watch: {
    value: {
      handler(newVal) {
        this.editData = {}
        if (newVal > 0) {
          this.editTitle = 'edit'
        }
        newVal !== -1
          ? (this.dialogFormVisible = true)
          : (this.dialogFormVisible = false)
        if (newVal !== 0 && newVal !== -1) {
          this.handleDataInfo(newVal)
        }
      },
      immediate: true
    },
    dialogFormVisible(newVal) {
      if (!newVal) {
        this.editTitle = 'add'
        this.$emit('input', -1)
        this.callbackHiddenDialog()
      }
    }
  },
  methods: {
    operateData,
    getSingleInfo,
    getOtherData,
    handleDataInfo(params) {
      throw new Error('component must implement handleDataInfo method')
    },
    handleDataOperate(model) {
      const type = this.value > 0 ? 'edit' : 'add'
      this.operateData(model, type, this.editData).then(res => {
        this.showReturn(res)
      })
    },
    showReturn(result) {
      let errorType = 'error'
      if (result.code === 200) {
        errorType = 'success'
        this.dialogFormVisible = false
        this.$emit('success')
      } else {
        this.errorCallBack()
      }
      this.$message({
        message: result.message,
        type: errorType
      })
    },
    callbackHiddenDialog() {
      this.langContent = {}
    },
    errorCallBack() {
    }
  }
}
