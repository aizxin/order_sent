<template>
  <div class="app-container calendar-list-container">
    <div class="filter-container">
      <el-button
        v-waves
        class="filter-item"
        style="margin-left: 10px;"
        type="primary"
        size="mini"
        icon="el-icon-plus"
        @click="handleAction(0)"
      >新增
      </el-button>
    </div>
    <el-table :data="lists" border stripe fit highlight-current-row size="mini">
      <el-table-column prop="id" label="ID" width="50" align="center" />
      <el-table-column prop="name" label="角色名" align="center" />
      <el-table-column prop="scope" label="状态" align="center">
        <template slot-scope="scope">
          <el-tag v-waves type="primary" size="mini">{{ scope.row.status_text }}</el-tag>
        </template>
      </el-table-column>
      <el-table-column label="操作" align="center" width="300">
        <template slot-scope="scope">
          <el-button v-waves size="mini" type="primary" @click="handleAction(scope.row.id)">编辑</el-button>
          <el-button
            v-waves
            size="mini"
            type="primary"
            @click="handleAction(scope.row.id,'rule')"
          >授权
          </el-button>
          <el-button
            v-waves
            size="mini"
            @click="handleStatus($route.name,scope.row)"
          >{{ scope.row.status ? '禁用':'启用' }}
          </el-button>
          <el-button
            v-waves
            size="mini"
            type="danger"
            @click="handleDelete($route.name, scope.row.id)"
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
    <component :is="'edit'" v-model="infoId" @success="success()" />
  </div>
</template>

<script>
import listsMixin from '@/mixins/listsMixin'
import Edit from './edit'

export default {
  name: 'Role',
  components: {
    Edit
  },
  mixins: [listsMixin],
  methods: {
    async handleMainLists(query) {
      const lists = await this.getDataLists(this.$route.name, Object.assign({}, query, {
        size: this.size,
        page: this.currentPage
      }))
      this.resultAssignment(lists.data)
    },
    success() {
      this.loadPage = !this.type
    }
  }
}
</script>
