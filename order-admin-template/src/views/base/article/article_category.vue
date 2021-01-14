<template>
  <div class="app-container calendar-list-container">
    <div class="filter-container ">
      <el-button
        v-waves
        class="filter-item"
        type="primary"
        size="mini"
        icon="el-icon-plus"
        @click="handleEdit(0)"
      >新增
      </el-button>
    </div>
    <el-table
      :data="lists"
      row-key="id"
      border
      stripe
      fit
      highlight-current-row
      size="mini"
      :tree-props="{children: 'children', hasChildren: 'hasChildren'}"
    >>
      <el-table-column prop="id" label="ID" width="90" align="center" />
      <el-table-column prop="pid" label="父级ID" width="60" align="center" />
      <el-table-column prop="name" label="分类名称" align="center" />
      <el-table-column prop="name" label="分类图标" align="center" width="80">
        <template slot-scope="scope">
          <img :src="scope.row.img" width="60" height="60">
        </template>
      </el-table-column>
      <el-table-column prop="status_text" label="状态" align="center" />
      <el-table-column prop="sort" label="排序" align="center" />
      <el-table-column label="操作" align="center" width="300">
        <template slot-scope="scope">
          <el-button v-waves size="mini" type="primary" @click="handleEdit(scope.row.id)">编辑</el-button>
          <status-button :status="scope.row.status" @click="handleStatus('article_category',scope.row)" />
          <el-button v-waves size="mini" type="danger" @click="handleDelete('article_category', scope.row.id)">删除
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
  components: {},
  mixins: [listsMixin],
  data() {
    return {}
  },
  methods: {
    async handleMainLists() {
    },
    handleEdit(id) {
      this.infoId = id
    },
    success() {
      this.handleMainLists()
    }
  }
}
</script>

<style scoped>

</style>
