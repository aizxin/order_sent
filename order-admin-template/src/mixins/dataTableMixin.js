import { statusAction, deleteAction, getDataLists } from '@/api/common'

export default {
  data() {
    return {
      lists: [],
      total: 0,
      infoId: -1,
      size: 20,
      currentPage: 1,
      searchQuery: {}
    }
  },
  filters: {
    filterText(data, field, type = '') {
      const fields = field.split('.')
      if (fields.length > 1) {
        data = data[fields[0]] ? data[fields[0]] : {}
        field = fields[1]
      }

      if (type === 'sub') {
        return data[field] ? data[field].substring(data[field].length - 10) + '...' : ''
      } else {
        return data[field] ? data[field] : ''
      }
    }
  },
  watch: {
    currentPage(page) {
      this.handleMainLists()
    }
  },
  created() {
    this.handleMainLists()
  },
  methods: {
    async handleMainLists() {
      const { data } = await getDataLists(this.action, this.getSearchQuery())
      this.resultAssignment(data)
    },
    changePage(page) {
      this.currentPage = page
    },
    setSearchQuery(query) {
      this.searchQuery = query
    },
    searchLists(query) {
      this.setSearchQuery(query)
      this.handleMainLists()
    },
    getSearchQuery() {
      return Object.assign({}, { size: this.size, page: this.currentPage }, this.searchQuery)
    },
    handleEdit(type, id) {
      this.$emit('edit', type, id)
    },
    handleStatus(model, item) {
      statusAction(model, item).then(res => {
        this.showReturn(res)
      })
    },
    handleDelete(model, item) {
      deleteAction(model, item).then(res => {
        this.showReturn(res)
      })
    },
    resultAssignment({ rows, total }) {
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
    }
  }
}
