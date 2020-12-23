<template>
  <el-dialog v-el-drag-dialog :title="editTitle + '医院'" :visible.sync="dialogFormVisible">
    <el-form
      ref="editForm"
      :model="editData"
      label-position="left"
      label-width="70px"
      style="width: 80%; margin-left:50px;"
    >
      <el-form-item label="角色名称" prop="name">
        <el-input v-model="editData.name" placeholder="角色名称" />
      </el-form-item>

      <el-form-item label="角色key" prop="key">
        <el-input v-model="editData.key" placeholder="角色key" />
      </el-form-item>

      <el-form-item label="角色描述" prop="remark">
        <el-input v-model="editData.remark" placeholder="角色描述" />
      </el-form-item>

      <el-form-item label="状态" prop="status">
        <el-radio-group v-model="editData.status">
          <el-radio-button label="0">禁用</el-radio-button>
          <el-radio-button label="1">启用</el-radio-button>
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

export default {
  mixins: [editMixin],
  methods: {
    async handleDataInfo(params) {
      const { data } = await this.getSingleInfo(this.$route.name, params)
      this.editData = data
    }
  }
}
</script>

<style scoped>

</style>
