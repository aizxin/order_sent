<template>
  <div class="app-container calendar-list-container">
    <div class="filter-container">
      <el-input v-model="searchOpt.word" placeholder="用户名" clearable class="filter-item form-search-input" />
      <search-select v-model="searchOpt.article_type" :list="articleTypes" placeholder="文章类型" class="filter-item form-search-input" />
      <el-button class="filter-item search" icon="el-icon-search">
        搜索
      </el-button>
      <el-button class="filter-item" icon="el-icon-refresh">
        重置
      </el-button>
      <el-button class="filter-item" type="primary" icon="el-icon-plus">
        新增
      </el-button>
    </div>
    <el-table :data="lists" border stripe fit highlight-current-row size="mini">
      <el-table-column prop="id" label="ID" width="50" align="center" />
      <el-table-column prop="name" label="标题" align="center" min-width="160" />
      <el-table-column label="封面图" width="80">
        <template slot-scope="scope">
          <img :src="scope.row.img" alt="" width="60" height="60">
        </template>
      </el-table-column>
      <el-table-column prop="article_category_name" label="所属分类" align="center" />
      <el-table-column prop="article_type_text" label="文章类型" align="center" />
      <el-table-column prop="click_num" label="点击量" align="center" />
      <el-table-column prop="comment_num" label="评论量" align="center" />
      <el-table-column prop="favorite" label="收藏量" align="center" />
      <el-table-column prop="status_text" label="状态" align="center" />
      <el-table-column prop="sort" label="排序" align="center" />
      <el-table-column label="操作" align="center" width="300">
        <template slot-scope="scope">
          <el-button v-waves size="mini" type="primary" @click="handleEdit(scope.row.id)">编辑</el-button>
          <status-button :status="scope.row.status" @click="handleStatus('article',scope.row)" />
          <el-button v-waves size="mini" type="danger" @click="handleDelete('article', scope.row.id)">删除</el-button>
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
// import CommonCategory from './common-category'
import SearchSelect from '@/views/common-component/search-select-id'

export default {
  name: 'Index',
  components: {
    SearchSelect
  },
  mixins: [listsMixin],
  data() {
    return {
      searchOpt: {},
      articleTypes: [
        { id: 'normal', name: '常规' },
        { id: 'video', name: '视频' },
        { id: 'voice', name: '音频' },
        { id: 'picture', name: '图集' }
      ]
    }
  },
  methods: {
    async handleMainLists(query) {
      const lists = await this.getDataLists('article', Object.assign({}, query, {
        size: this.size,
        page: this.currentPage
      }))
      this.resultAssignment(lists.data)
    },
    handleEdit(id) {
      this.infoId = id
    },
    success() {
      this.handleMainLists({ size: this.size, page: this.currentPage })
    },
    /*
    * 文章分类选择
    * */
    getArticleCate(val) {
      this.searchOpt.article_category_id = val
    },
    /*
    * 根据条件查询
    */
    getListByOpt() {
      this.handleMainLists({ size: this.size, page: this.currentPage })
    },
    /*
    * 重置
    * */
    getListByNoOpt() {
      this.searchOpt = {}
      this.getListByOpt()
    }
  }
}
</script>

<style scoped>

</style>
