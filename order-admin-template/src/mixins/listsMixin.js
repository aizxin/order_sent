import { statusAction, deleteAction, getDataLists } from '@/api/common'

export default {
  data() {
    return {
      elementLoadingText: '正在加载...',
      lists: [],
      total: 0,
      infoId: -1,
      size: 20,
      currentPage: 1,
      loadPage: false,
      type: ''
    }
  },
  watch: {
    currentPage(page) {
      this.handleMainLists({ size: this.size, page: this.currentPage })
    },
    loadPage(val) {
      val && this.handleMainLists({ size: this.size, page: this.currentPage })
    }
  },
  created() {
    this.handleMainLists({ size: this.size, page: this.currentPage })
  },
  methods: {
    getDataLists,
    handleMainLists() {
      throw new Error('component must implement handleMainLists method')
    },
    changePage(page) {
      this.currentPage = page
    },
    handleStatus(model, item) {
      statusAction(model, item).then(res => {
        this.showReturn(res)
      })
    },
    handleDelete(model, item) {
      this.$confirm(
        '你确认要删除？',
        '删除',
        {
          confirmButtonText: '确认',
          cancelButtonText: '取消',
          type: 'warning'
        }
      ).then(() => {
        deleteAction(model, item).then(res => {
          this.showReturn(res)
        })
      })
    },
    resultAssignment(res) {
      if (!res) {
        return
      }
      const { rows, total } = res
      this.lists = rows
      this.total = total
    },
    showReturn(result) {
      let errorType = 'error'
      if (result.code === 200) {
        errorType = 'success'
        this.$emit('success')
        this.handleMainLists()
      }
      this.$message({
        message: result.message,
        type: errorType
      })
    },
    handleAction(id = 0, type = '') {
      this.loadPage = false
      this.type = type
      this.infoId = id
    }
  }
}
