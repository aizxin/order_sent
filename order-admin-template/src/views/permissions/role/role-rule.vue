<template>
  <el-dialog v-el-drag-dialog :title="info.name+' 授权'" :visible.sync="dialogFormVisible" class="role-rule">
    <el-tree
      ref="tree"
      :data="rules"
      :default-checked-keys="info.rules"
      show-checkbox
      default-expand-all
      node-key="id"
      highlight-current
      :props="{children: 'children',label: 'label'}"
      @check="handleCheck"
    />
    <div slot="footer" class="dialog-footer">
      <el-button @click="dialogFormVisible = false">取消</el-button>
      <el-button type="primary" @click="handleSubmit">确认</el-button>
    </div>
  </el-dialog>
</template>

<script>
import editMixin from '@/mixins/editMixin'

export default {
  name: 'RoleRule',
  mixins: [editMixin],
  data() {
    return {
      info: {},
      rules: []
    }
  },
  methods: {
    async handleDataInfo(params) {
      const { data } = await this.getSingleInfo(this.$route.name, params)
      this.info = data
      const rule = await this.getOtherData('rule', 'lists')
      this.rules = rule.data
    },
    async handleSubmit() {
      if (this.info.tree === undefined) {
        this.$message({
          message: '请选择节点,再提交',
          type: 'error'
        })
        return
      }
      const res = await this.operateData(this.$route.name, 'rule', this.info)
      this.showReturn(res)
    },
    async handleCheck(node, tree) {
      this.info.tree = tree
    }
  }
}
</script>

<style lang="scss" scoped>
.role-rule {
  top: 0px;
}
</style>
