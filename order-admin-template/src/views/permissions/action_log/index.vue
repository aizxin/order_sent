<template>
  <div class="app-container calendar-list-container">
    <el-table :data="lists" border stripe fit highlight-current-row size="mini">
      <el-table-column prop="id" label="ID" width="50" align="center" />
      <el-table-column prop="action_name" label="操作" align="center" />
      <el-table-column prop="username" label="操作人" align="center" />
      <el-table-column prop="create_ip" label="操作ip" align="center" />
      <el-table-column prop="url" label="链接" align="center" />
      <el-table-column prop="create_time" label="操作时间" align="center" />
      <el-table-column prop="scope" label="操作位置" align="center">
        <template slot-scope="scope">
          <el-tag v-waves type="primary" size="mini">{{ scope.row.type_text }}</el-tag>
        </template>
      </el-table-column>
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
  name: 'ActionLog',
  mixins: [listsMixin],
  data() {
    return {
      action: 'action_log'
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
