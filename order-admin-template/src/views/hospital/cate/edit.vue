<template>
  <el-dialog v-el-drag-dialog :title="editTitle + '分类'" :visible.sync="dialogFormVisible">
    <el-form
      ref="editForm"
      :model="editData"
      label-position="left"
      label-width="70px"
      style="width: 80%; margin-left:50px;"
    >
      <el-form-item label="顶级分类" prop="pid">
        <el-cascader
          v-model="editData.pid"
          :props="{ checkStrictly: true,value:'id',label:'name' }"
          :options="items"
          clearable
          @change="handleChange"
        />
      </el-form-item>
      <el-form-item label="分类名称" prop="name">
        <el-input v-model="editData.name" placeholder="接口名称" />
      </el-form-item>
      <el-form-item label="状态" prop="status">
        <el-radio-group v-model="editData.status">
          <el-radio-button :label="0">禁用</el-radio-button>
          <el-radio-button :label="1">启用</el-radio-button>
        </el-radio-group>

        <el-input v-model="editData.id" type="hidden" />
      </el-form-item>
    </el-form>

    <div slot="footer" class="dialog-footer">
      <el-button @click="dialogFormVisible = false">取消</el-button>
      <el-button type="primary" @click="handleDataOperate($route.name)">确认</el-button>
    </div>
  </el-dialog>
</template>

<script>
import editMixin from '@/mixins/editMixin'
import { searchToTree, treeToList } from '@/utils/tree-data'

export default {
  mixins: [editMixin],
  props: {
    items: {
      required: true,
      type: [Array]
    }
  },
  methods: {
    async handleDataInfo(params) {
      const { data } = await this.getSingleInfo(this.$route.name, params)
      this.editData = data
      if (data.pid !== 0) {
        this.editData.pid = searchToTree(treeToList(this.items), data.pid)
      }
    },
    handleChange(item) {
      if (!item) return
      this.editData.pid = item[item.length - 1]
    }
  }
}
</script>

<style scoped>

</style>
