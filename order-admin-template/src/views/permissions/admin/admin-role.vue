<template>
  <el-dialog v-el-drag-dialog :title="info.name+'('+info.username+') 授权'" :visible.sync="dialogFormVisible">
    <el-checkbox-group v-model="adminRoles">
      <el-checkbox v-for="item of roles" :key="item.id" :label="'role:'+item.id" :value="item.id">
        {{ item.name }}
      </el-checkbox>
    </el-checkbox-group>
    <div slot="footer" class="dialog-footer">
      <el-button @click="dialogFormVisible = false">取消</el-button>
      <el-button type="primary" @click="handleSubmit">确认</el-button>
    </div>
  </el-dialog>
</template>

<script>
import editMixin from '@/mixins/editMixin'

export default {
  name: 'AdminRole',
  mixins: [editMixin],
  data() {
    return {
      info: {},
      roles: [],
      adminRoles: []
    }
  },
  methods: {
    async handleDataInfo(params) {
      const { data } = await this.getSingleInfo(this.$route.name, params)
      this.info = data
      this.adminRoles = data.role
      const role = await this.getOtherData('role', 'lists')
      this.roles = role.data
    },
    async handleSubmit() {
      const result = await this.operateData('admin', 'role', { id: this.info.id, role: this.adminRoles })
      result.code === 200 ? this.dialogFormVisible = false : ''
      this.$message({
        message: result.message,
        type: result.code === 200 ? 'success' : 'error'
      })
    }
  }
}
</script>

<style scoped>

</style>
