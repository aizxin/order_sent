<template>
  <div class="app-container calendar-list-container">
    <el-table :data="lists" border stripe fit highlight-current-row size="mini">
      <el-table-column prop="id" label="ID" width="50" align="center" />
      <el-table-column prop="admin_id" label="用户ID" align="center" />
      <el-table-column prop="name" label="真实姓名" align="center" />
      <el-table-column prop="login_ip" label="登录ip" align="center" />
      <el-table-column prop="login_time" label="登录时间" align="center" />
      <el-table-column label="操作" align="center" width="100">
        <template slot-scope="scope">
          <el-button
            v-waves
            size="mini"
            type="danger"
            @click="handleDelete(action, scope.row.id)"
          >删除
          </el-button>
        </template>
      </el-table-column>
    </el-table>
    <div class="pagination-container">
      <el-pagination
        background
        layout="prev, pager, next"
        :page-size.sync="size"
        :total="total"
        @current-change="changePage"
      />
    </div>
  </div>
</template>

<script>
import listsMixin from '@/mixins/listsMixin'

export default {
  name: 'AdminLog',
  mixins: [listsMixin],
  data() {
    return {
      action: 'admin_log'
    }
  },
  methods: {
    async handleMainLists(query) {
      const lists = await this.getDataLists(this.$route.name, Object.assign({}, query, {
        size: this.size,
        page: this.currentPage
      }))
      this.resultAssignment(lists.data)
    }
  }
}
</script>
