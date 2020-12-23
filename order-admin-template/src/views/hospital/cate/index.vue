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
    <el-table
      :data="lists"
      border
      stripe
      fit
      highlight-current-row
      size="mini"
      row-key="id"
      :tree-props="{children: 'children', hasChildren: 'hasChildren'}"
    >
      <el-table-column prop="index" type="index" label="ID" width="100" align="center" />
      <el-table-column prop="name" label="分类名称" align="center" />
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
    <component :is="'edit'" v-model="infoId" :items="lists" @success="success()" />
  </div>
</template>

<script>
import listsMixin from '@/mixins/listsMixin'
import Edit from './edit'

export default {
  name: 'Cate',
  components: {
    Edit
  },
  mixins: [listsMixin],
  methods: {
    async handleMainLists(query) {
      const lists = await this.getDataLists(this.$route.name)
      this.lists = lists.data
    },
    success() {
      this.loadPage = !this.type
    }
  }
}
</script>
